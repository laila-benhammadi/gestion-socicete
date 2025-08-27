<template>
  <div class="container mt-4 mb-4">

    <!-- Navbar / Titre -->
    <nav class="navbar navbar-expand-lg navbar-light w-100 border-bottom" style="margin:0; border-bottom: 1px solid black;">
      <div class="flex items-center justify-between w-full">
        <div class="flex items-center space-x-4" style="margin-left: 15px;">
          <div class="w-[60px] h-[60px] icon-gradient bg-blue-600 rounded-xl flex items-center justify-center">
            <i class="bi bi-people text-white text-xl"></i>
          </div>
          <div style="margin-left: 10px;">
            <h1 class="text-2xl font-bold text-gray-800">Liste des employés</h1>
          </div>
        </div>
      </div>
    </nav>

    <!-- Bouton Ajouter un employé -->
    <RouterLink
      to="/addEmployee"
      class="btn btn-success mt-4 text-white text-decoration-none"
    >
      <i class="bi bi-plus-circle"></i>
      Ajouter un employé
    </RouterLink>

    <!-- Loader -->
    <div v-if="loading" class="loading-container">
      <div class="spinner"></div>
    </div>

    <!-- Message d'erreur -->
    <div v-if="error" class="alert alert-danger mt-3" role="alert">
      {{ error }}
    </div>

    <!-- Tableau des employés -->
    <div v-if="!loading && employees.length" class="table-responsive">
      <vue-good-table
        class="my-table mt-3"
        :columns="columns"
        :rows="employees"
        :search-options="{ enabled: true, placeholder: 'Rechercher...' }"
        :pagination-options="{ enabled: true, perPage: 10, perPageDropdown: false }"
      >
        <template #table-row="props">
          <span v-if="props.column.field === 'details'">
            <RouterLink :to="`/employee/${props.row.id}/nav`">
              <i class="bi bi-person-vcard icon-info"></i>
            </RouterLink>
          </span>
          <span v-else>
            {{ props.formattedRow[props.column.field] }}
          </span>
        </template>
      </vue-good-table>
    </div>

    <!-- Aucune donnée -->
    <div v-if="!loading && !employees.length" class="empty-state">
      <p class="text-center">Aucun employé trouvé.</p>
    </div>

  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { VueGoodTable } from 'vue-good-table-next';
import 'vue-good-table-next/dist/vue-good-table-next.css';

export default {
  name: 'ListEmployee',
  components: { VueGoodTable },

  setup() {
    const employees = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const columns = [
      { label: 'Nom', field: 'fullName', sortable: true },
      { label: 'Email', field: 'email', sortable: true },
      { label: 'CIN', field: 'cin', sortable: true },
      { label: "Date d'embauche", field: 'formattedHiringDate', sortable: true },
      { label: 'Détails', field: 'details', sortable: false },
    ];

    const formatDate = (dateString) => {
      if (!dateString) return '';
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(dateString).toLocaleDateString('fr-FR', options);
    };

    const fetchEmployees = async () => {
      loading.value = true;
      error.value = null;
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/admin/getEmployee');
        employees.value = response.data.map(emp => ({
          ...emp,
          fullName: `${emp.first_name} ${emp.last_name}`,
          formattedHiringDate: formatDate(emp.hiring_date),
          details: '',
        }));
      } catch (err) {
        error.value = 'Erreur lors du chargement des employés.';
        console.error(err);
      } finally {
        loading.value = false;
      }
    };

    onMounted(fetchEmployees);

    return { employees, loading, error, columns };
  },
};
</script>

<style scoped>
.loading-container {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
}

.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  border-left-color: #000;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.icon-info {
  font-size: 30px;
  color: #198754;
  cursor: pointer;
  margin-left: 30px;
}

.icon-info:hover {
  color: rgb(28, 185, 0);
}

::v-deep .vgt-table tbody tr td {
  color: black !important;
}

::v-deep .my-table .vgt-global-search {
  margin-bottom: 20px !important;
}

::v-deep .my-table .vgt-global-search input {
  border-radius: 8px;
  padding: 8px 12px;
  border: 1px solid #000;
}

::v-deep(.my-table .vgt-table-wrapper) {
  border: 2px solid #444 !important;
  border-radius: 12px;
  overflow: hidden;
}

::v-deep(.my-table .vgt-table) {
  border: none !important;
}

.icon-gradient {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.rounded-xl {
  border-radius: .75rem;
}

.my-table {
  text-transform: uppercase;
}
</style>
