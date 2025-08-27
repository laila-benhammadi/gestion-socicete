<template>
  <div class="container mt-4">

    <!-- Titre -->
    <h2 class="text-center mb-4 text-dark">
      <i class="bi bi-cash-stack me-2"></i> Salaires
    </h2>

    <!-- Messages de succès et d'erreur -->
    <div v-if="messageSuccess" class="alert alert-success text-center">{{ messageSuccess }}</div>
    <div v-if="errorAdd" class="alert alert-danger text-center">{{ errorAdd }}</div>
    <div v-if="error" class="alert alert-danger text-center">{{ error }}</div>

    <!-- Loading -->
    <div v-if="loading" class="loading-container">
      <div class="spinner"></div>
    </div>

    <!-- Aucune donnée -->
    <div v-if="salaries.length === 0 && !loading" class="text-center text-muted mt-4">
      <p>Il n'y a pas de salaires pour cet employé.</p>
    </div>

    <!-- Liste des salaires -->
    <div v-else>
      <table class="table table-bordered table-striped text-center align-middle">
        <thead>
          <tr>
            <th>Montant net</th>
            <th>Montant brut</th>
            <th>Banque</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="salary in salaries" :key="salary.id">
            <td>{{ salary.net_amount }} DH</td>
            <td>{{ salary.gross_amount }} DH</td>
            <td>{{ salary.bank.bank_name }}</td>
            <td>{{ salary.salary_date }}</td>
            <td>
              <button
                type="button"
                class="btn btn-dark"
                data-bs-toggle="modal"
                data-bs-target="#modalUpdateSalary"
                @click="openUpdateModal(salary)"
              >
                <i class="bi bi-pencil-square"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Modifier un salaire -->
    <div class="modal fade" id="modalUpdateSalary" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <h5 class="modal-title">Modifier le salaire</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <!-- Modal Body -->
          <div class="modal-body">
            <label for="net_amount" class="form-label">Montant net :</label>
            <input
              type="text"
              v-model="selectedSalary.net_amount"
              id="net_amount"
              class="form-control"
              required
            />

            <label for="gross_amount" class="form-label mt-2">Montant brut :</label>
            <input
              type="text"
              v-model="selectedSalary.gross_amount"
              id="gross_amount"
              class="form-control"
              required
            />

            <label for="salary_date" class="form-label mt-2">Date :</label>
            <input
              type="date"
              v-model="selectedSalary.salary_date"
              id="salary_date"
              class="form-control"
              required
            />

            <label for="bank_id" class="form-label mt-2">Banque :</label>
            <select v-model="selectedSalary.bank_id" class="form-select" id="bank_id" required>
              <option value="" disabled selected>Choisir une Banque</option>
              <option v-for="bank in banks" :key="bank.id" :value="bank.id">
                {{ bank.bank_name }}
              </option>
            </select>
          </div>

          <!-- Modal Footer -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-success"
              data-bs-dismiss="modal"
              @click="updateSalary"
            >
              Modifier
            </button>
          </div>

        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

export default {
  name: 'SalaryList',
  setup() {
    const salaries = ref([]);
    const banks = ref([]);
    const error = ref(null);
    const errorAdd = ref(null);
    const messageSuccess = ref(null);
    const loading = ref(false);

    const route = useRoute();
    const employeeId = route.params.id;

    const selectedSalary = ref({
      id: null,
      net_amount: '',
      gross_amount: '',
      bank_name: '',
      salary_date: '',
      bank_id: '',
    });

    // Récupérer les salaires
    const fetchSalaries = async () => {
      if (!employeeId) {
        error.value = 'Identifiant employé introuvable.';
        return;
      }
      loading.value = true;
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/getSalaries/${employeeId}`);
        salaries.value = response.data.salaries;
      } catch {
        error.value = 'Erreur lors du chargement des salaires.';
      } finally {
        loading.value = false;
      }
    };

    // Ouvrir modal pour modifier
    const openUpdateModal = (salary) => {
      selectedSalary.value = { ...salary };
    };

    // Mettre à jour un salaire
    const updateSalary = async () => {
      try {
        const response = await axios.put(
          `http://127.0.0.1:8000/api/updateSalary/${selectedSalary.value.id}`,
          selectedSalary.value
        );

        if (response.data && response.data.salary) {
          fetchSalaries();
          messageSuccess.value = 'Salaire mis à jour avec succès';
          errorAdd.value = null;
          setTimeout(() => (messageSuccess.value = null), 3000);
        }
      } catch {
        error.value = 'Erreur lors de la mise à jour du salaire.';
      }
    };

    // Récupérer les banques
    const getBank = async () => {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/showBank/${employeeId}`);
        banks.value = response.data;
      } catch (err) {
        console.error('Erreur lors de la récupération des banques:', err);
      }
    };

    onMounted(() => {
      fetchSalaries();
      getBank();
    });

    return {
      salaries,
      banks,
      selectedSalary,
      messageSuccess,
      error,
      errorAdd,
      loading,
      openUpdateModal,
      updateSalary,
    };
  },
};
</script>

<style scoped>
.table {
  margin-top: 20px;
}
</style>
