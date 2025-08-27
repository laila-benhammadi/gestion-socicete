<template>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light w-100 border-bottom" style="margin:0; border-bottom: 1px solid black;">
    <div class="flex items-center justify-between w-full">
      <div class="flex items-center space-x-4" style="margin-left: 15px;">
        <div class="w-[60px] h-[60px] icon-gradient rounded-xl flex items-center justify-center">
          <i class="fas fa-building text-white text-xl"></i>
        </div>
        <div style="margin-left: 10px;">
          <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
          <p class="text-gray-600 text-sm">Gestion des employés</p>
        </div>
      </div>
    </div>
  </nav>

  <!-- Cards Section -->
  <div class="container">
    <div class="row">

      <!-- Actifs -->
      <div class="col-12 col-md-4">
        <div class="shadow-lg rounded-xl bg-white min-h-[120px] p-2">
          <div class="card-body text-black pl-3" style="margin-left: 15px;">
            <div class="d-flex align-items-center">
              <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-green-600 text-xl"></i>
              </div>
              <div class="flex flex-col ml-2" style="margin-left: 10px;">
                <p class="text-3xl font-bold mb-0 leading-none mt-3">{{ activeEmployeeCount }}</p>
                <p class="text-sm font-semibold text-green-600 -mt-1">Actifs</p>
              </div>
            </div>
            <p class="text-gray-600 text-sm -mt-2">Employés actifs</p>
          </div>
        </div>
      </div>

      <!-- En congé -->
      <div class="col-12 col-md-4">
        <div class="shadow-lg rounded-xl bg-white min-h-[120px] p-2">
          <div class="card-body text-black pl-3" style="margin-left: 15px;">
            <div class="d-flex align-items-center">
              <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                <i class="bi bi-person-check text-yellow-600 text-xl"></i>
              </div>
              <div class="flex flex-col ml-2" style="margin-left: 10px;">
                <p class="text-3xl font-bold mb-0 leading-none mt-3">{{ countEmployeesOnHoliday }}</p>
                <p class="text-sm font-semibold text-yellow-600 -mt-1">En congé</p>
              </div>
            </div>
            <p class="text-gray-600 text-sm -mt-2">Employés en congé</p>
          </div>
        </div>
      </div>

      <!-- Stagiaires -->
      <div class="col-12 col-md-4">
        <div class="shadow-lg rounded-xl bg-white min-h-[120px] p-2">
          <div class="card-body text-black pl-3" style="margin-left: 15px;">
            <div class="d-flex align-items-center">
              <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
              </div>
              <div class="flex flex-col ml-2" style="margin-left: 10px;">
                <p class="text-3xl font-bold mb-0 leading-none mt-3">{{ countStagiaires }}</p>
                <p class="text-sm font-semibold text-blue-600 -mt-1">En formation</p>
              </div>
            </div>
            <p class="text-gray-600 text-sm -mt-2">Stagiaires actifs</p>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Employees on Holiday Table -->
  <div class="glass-card rounded-2xl shadow-lg p-6 fade-in mt-8">

    <!-- Table Header -->
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center" style="margin-left: 15px;margin-top: 5px;">
          <i class="fas fa-umbrella-beach text-orange-600"></i>
        </div>
        <h2 class="text-xs text-black mt-2" style="margin-left: 5px;">Employés en congé</h2>
      </div>
      <div class="text-sm text-gray-500" style="margin-right: 15px;">
        Mis à jour {{ new Date().toLocaleString('fr-FR') }}
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="flex items-center justify-center py-12">
      <div class="loading-spinner"></div>
      <span class="ml-3 text-gray-600">Chargement des données...</span>
    </div>

    <!-- Empty State -->
    <div v-else-if="employeesOnHoliday.length === 0" class="text-center py-12 mb-6">
      <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="bi bi-calendar-x text-gray-400 text-3xl"></i>
      </div>
      <h3 class="text-black mt-2" style="margin-left: 5px;">Aucun employé en congé</h3>
      <p class="text-gray-500">Tous les employés sont actuellement au travail.</p>
    </div>

    <!-- Table -->
    <div v-else class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-gray-200">
            <th class="text-left py-3 px-4 font-semibold text-gray-700">
              <div class="flex items-center gap-2">
                <i class="fas fa-user text-gray-400"></i>
                <span>Nom</span>
              </div>
            </th>
            <th class="text-left py-3 px-4 font-semibold text-gray-700">
              <div class="flex items-center gap-2">
                <i class="fas fa-id-card text-gray-400"></i>
                <span>Prénom</span>
              </div>
            </th>
            <th class="text-left py-3 px-4 font-semibold text-gray-700">
              <div class="flex items-center gap-2">
                <i class="fas fa-envelope text-gray-400"></i>
                <span>Email</span>
              </div>
            </th>
            <th class="text-left py-3 px-4 font-semibold text-gray-700">
              <div class="flex items-center gap-2">
                <i class="fas fa-info-circle text-gray-400"></i>
                <span>Statut</span>
              </div>
            </th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="employee in paginatedEmployees" :key="employee.id" class="table-row border-b border-gray-100">
            <td class="py-4 px-4">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center" style="margin-right: 10px;">
                  <span class="text-white font-semibold text-sm">{{ employee.first_name.charAt(0) }}{{ employee.last_name.charAt(0) }}</span>
                </div>
                <span class="font-medium text-gray-800">{{ employee.last_name }}</span>
              </div>
            </td>
            <td class="py-4 px-4 text-gray-700">{{ employee.first_name }}</td>
            <td class="py-4 px-4">
              <a :href="'mailto:' + employee.email" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                {{ employee.email }}
              </a>
            </td>
            <td class="py-4 px-4">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                <i class="fas fa-calendar-check mr-1"></i>
                En congé
              </span>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination Controls -->
      <div class="flex items-center justify-between mt-4" style="margin-bottom: 10px;">
        <div class="flex items-center gap-2" style="margin-left: 15px;">
          <span>Rows per page:</span>
          <select v-model.number="rowsPerPage" class="border rounded px-2 py-1">
            <option v-for="n in rowsPerPageOptions" :key="n" :value="n">{{ n }}</option>
          </select>
        </div>
        <div class="flex items-center gap-2" style="margin-right: 15px;">
          <span>{{ (currentPage-1)*rowsPerPage + 1 }} - {{ Math.min(currentPage*rowsPerPage, employeesOnHoliday.length) }} of {{ employeesOnHoliday.length }}</span>
          <button @click="currentPage = Math.max(currentPage - 1, 1)" 
                  :disabled="currentPage === 1" 
                  class="px-2 py-1 border rounded disabled:opacity-50">
            Previous
          </button>
          <button @click="currentPage = Math.min(currentPage + 1, totalPages)" 
                  :disabled="currentPage === totalPages" 
                  class="px-2 py-1 border rounded disabled:opacity-50">
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

/* --------------------- Reactive States --------------------- */
const isLoading = ref(true);  
const employeesOnHoliday = ref([]);
const activeEmployeeCount = ref(0);
const countEmployeesOnHoliday = ref(0);
const countStagiaires = ref(0);

/* --------------------- Pagination --------------------- */
const currentPage = ref(1);
const rowsPerPage = ref(10);
const rowsPerPageOptions = [5, 10, 25, 50];
const totalPages = computed(() => Math.ceil(employeesOnHoliday.value.length / rowsPerPage.value));
const paginatedEmployees = computed(() => {
  const start = (currentPage.value - 1) * rowsPerPage.value;
  const end = start + rowsPerPage.value;
  return employeesOnHoliday.value.slice(start, end);
});

/* --------------------- Fetch Functions --------------------- */
const fetchEmployeesOnHoliday = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/getEmployeesOnHoliday');
    employeesOnHoliday.value = response.data;
  } catch (error) {
    console.error(error);
  } finally {
    isLoading.value = false;
  }
};

const fetchActiveEmployeeCount = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/getEmployeeActif');
    activeEmployeeCount.value = response.data?.data ?? 0;
  } catch (error) {
    console.error(error);
  }
};

const fetchCountEmployeesOnHoliday = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/countEmployeesOnHoliday');
    countEmployeesOnHoliday.value = response.data?.data ?? 0;
  } catch (error) {
    console.error(error);
  }
};

const fetchCountStagiaire = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/countStagiaires');
    countStagiaires.value = response.data?.data ?? 0;
  } catch (error) {
    console.error(error);
  }
};

/* --------------------- Lifecycle Hook --------------------- */
onMounted(() => {
  fetchEmployeesOnHoliday();
  fetchActiveEmployeeCount();
  fetchCountEmployeesOnHoliday();
  fetchCountStagiaire();
});
</script>

<style scoped>
.row {
  margin-top: 40px;
  margin-bottom: 60px;
}
.icon-gradient {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.rounded-xl {
  border-radius: .75rem;
}
</style>
