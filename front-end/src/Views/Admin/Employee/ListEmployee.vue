<template>
  <div class="container mt-4 mb-4">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light w-100 border-bottom" style="margin:0; border-bottom: 1px solid black;">
      <div class="flex items-center space-x-4 ml-4">
        <div class="w-15 h-15 icon-gradient rounded-xl flex items-center justify-center">
          <i class="bi bi-people text-white text-xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800" style="margin-left: 5px;">Liste des employés</h1>
      </div>
    </nav>

    <!-- Bouton Ajouter -->
    <RouterLink
      to="/addEmployee"
      class="btn btn-success mt-4 text-white flex items-center gap-2"
    >
      <i class="bi bi-plus-circle"></i> Ajouter un employé
    </RouterLink>

    <!-- Loader -->
    <div v-if="loading" class="flex justify-center mt-4">
      <div class="spinner"></div>
    </div>

    <!-- Erreur -->
    <div v-if="error" class="alert alert-danger mt-3">{{ error }}</div>

    <!-- Recherche et tableau -->
    <div v-if="!loading && employees.length" class="mt-4 overflow-x-auto bg-white rounded shadow p-4">

      <!-- Recherche -->
      <input 
        v-model="search" 
        type="text" 
        placeholder="Rechercher..." 
        class="border rounded px-3 py-2 mb-4 w-full"
      />

      <!-- Tableau -->
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th @click="sortBy('last_name')" class="cursor-pointer px-4 py-2 text-left font-semibold text-gray-700">
              <div class="flex items-center gap-2">
                <i class="fas fa-user text-gray-400"></i> Nom
                <span v-if="sortKey === 'last_name'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
              </div>
            </th>
            <th @click="sortBy('first_name')" class="cursor-pointer px-4 py-2 text-left font-semibold text-gray-700">
              <div class="flex items-center gap-2">
                <i class="fas fa-id-card text-gray-400"></i> Prénom
                <span v-if="sortKey === 'first_name'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
              </div>
            </th>
            <th @click="sortBy('email')" class="cursor-pointer px-4 py-2 text-left font-semibold text-gray-700">
              <div class="flex items-center gap-2">
                <i class="fas fa-envelope text-gray-400"></i> Email
                <span v-if="sortKey === 'email'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
              </div>
            </th>
            <th @click="sortBy('hiring_date')" class="cursor-pointer px-4 py-2 text-left font-semibold text-gray-700">
              <div class="flex items-center gap-2">
                <i class="fas fa-calendar-alt text-gray-400"></i> Date d’embauche
                <span v-if="sortKey === 'hiring_date'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
              </div>
            </th>
            <th class="px-4 py-2 text-left font-semibold text-gray-700">
              <div class="flex items-center gap-2">
                <i class="fas fa-clipboard-list text-gray-400"></i> Détails
              </div>
            </th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
          <tr v-for="employee in paginatedEmployees" :key="employee.id">
            <td class="px-4 py-2">{{ employee.last_name }}</td>
            <td class="px-4 py-2">{{ employee.first_name }}</td>
            <td class="px-4 py-2">
              <a :href="'mailto:' + employee.email" class="text-blue-600 hover:text-blue-800">
                {{ employee.email }}
              </a>
            </td>
            <td class="px-4 py-2">{{ formatDate(employee.hiring_date) }}</td>
            <td class="px-4 py-2 text-center">
              <RouterLink :to="`/employee/${employee.id}/nav`">📝</RouterLink>
            </td>
          </tr>
          <tr v-if="filteredEmployees.length === 0">
            <td colspan="5" class="text-center py-4 text-gray-500">Aucun employé trouvé.</td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="flex justify-between items-center mt-4">
        <div>
          <select v-model.number="perPage" class="border rounded px-2 py-1">
            <option v-for="n in [5,10,20,50]" :key="n" :value="n">{{ n }}</option>
          </select>
        </div>
        <div class="space-x-2">
          {{ startIndex + 1 }} - {{ endIndex }} sur {{ filteredEmployees.length }}
          <button class="px-3 py-1 border rounded disabled:opacity-50" :disabled="currentPage === 1" @click="currentPage--">Précédent</button>
          <button class="px-3 py-1 border rounded disabled:opacity-50" :disabled="currentPage === totalPages" @click="currentPage++">Suivant</button>
        </div>
      </div>
    </div>

    <!-- Aucun employé -->
    <div v-if="!loading && !employees.length" class="text-center mt-4 text-gray-500">
      Aucun employé trouvé.
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

/* --------------------- Reactive States --------------------- */
const employees = ref([])
const loading = ref(false)
const error = ref(null)
const search = ref('')
const perPage = ref(10)
const currentPage = ref(1)
const sortKey = ref('')
const sortOrder = ref('asc')

/* --------------------- Fetch API --------------------- */
const fetchEmployees = async () => {
  loading.value = true
  error.value = null
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/admin/getEmployee')
    employees.value = response.data
  } catch (err) {
    error.value = 'Erreur lors du chargement des employés.'
  } finally {
    loading.value = false
  }
}

onMounted(fetchEmployees)

/* --------------------- Computed: Filtrage --------------------- */
const filteredEmployees = computed(() => {
  return employees.value.filter(emp => {
    const fullName = `${emp.first_name} ${emp.last_name}`.toLowerCase()
    return fullName.includes(search.value.toLowerCase()) ||
           (emp.email && emp.email.toLowerCase().includes(search.value.toLowerCase()))
  })
})

/* --------------------- Tri --------------------- */
const sortBy = (key) => {
  if (sortKey.value === key) sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
  else { sortKey.value = key; sortOrder.value = 'asc' }
}

const sortedEmployees = computed(() => {
  let data = [...filteredEmployees.value]
  if (!sortKey.value) return data
  return data.sort((a,b) => {
    let valA = a[sortKey.value] || ''
    let valB = b[sortKey.value] || ''
    if (sortKey.value === 'hiring_date') { valA = new Date(valA); valB = new Date(valB) }
    if (valA < valB) return sortOrder.value === 'asc' ? -1 : 1
    if (valA > valB) return sortOrder.value === 'asc' ? 1 : -1
    return 0
  })
})

/* --------------------- Pagination --------------------- */
const totalPages = computed(() => Math.ceil(sortedEmployees.value.length / perPage.value))
const startIndex = computed(() => (currentPage.value - 1) * perPage.value)
const endIndex = computed(() => Math.min(startIndex.value + perPage.value, sortedEmployees.value.length))
const paginatedEmployees = computed(() => sortedEmployees.value.slice(startIndex.value, endIndex.value))

/* --------------------- Format Date --------------------- */
const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('fr-FR')
}
</script>

<style scoped>
.spinner {
  border: 4px solid rgba(0,0,0,0.1);
  border-left-color: #000;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

.icon-gradient { background: linear-gradient(135deg,#667eea,#764ba2); }
.rounded-xl { border-radius: .75rem; }
</style>
