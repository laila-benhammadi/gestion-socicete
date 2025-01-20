<template>
  <div class="container mt-5">
    <h2 class="text-center mb-4">Liste des Salaires</h2>
    <div v-if="messageSuccess" class="alert alert-success text-center">
      {{ messageSuccess }}
    </div>

       <div v-if="salaries.length === 0" class="text-center text-muted mt-4">
        <p>Il n'y a pas de salaires pour cet employé.</p>
        
      <!--    <button type="button" class="btn btn-dark">
          <RouterLink :to="`/employee/${employeeId}/nav/addSalary`">
            <i class="bi bi-patch-plus"> </i>
            Ajouter autre
          </RouterLink>
        </button>-->
      </div>

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
    </div>

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
        <!-- Net Amount Input -->
        <label for="net_amount" class="form-label">Montant net :</label>
        <input
          type="text"
          v-model="selectedSalary.net_amount"
          id="net_amount"
          class="form-control"
          required
        />

        <!-- Gross Amount Input -->
        <label for="gross_amount" class="form-label mt-2">Montant brut :</label>
        <input
          type="text"
          v-model="selectedSalary.gross_amount"
          id="gross_amount"
          class="form-control"
          required
        />

        <!-- Salary Date Input -->
        <label for="salary_date" class="form-label mt-2">Date :</label>
        <input
          type="date"
          v-model="selectedSalary.salary_date"
          id="salary_date"
          class="form-control"
          required
        />

        <!-- Bank Selection -->
        <label for="bank_id" class="form-label">Banque :</label>
        <select v-model="selectedSalary.bank_id" class="form-select" id="bank_id" required>
          <option value="" disabled selected>Choisir une Banque</option>
          <option v-for="bank in banks" :key="bank.id" :value="bank.id">
            {{bank.bank_name}}
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
    const route = useRoute();
    const employeeId = route.params.employeeId;
    const messageSuccess = ref(null);  // Ajout de la variable messageSuccess
    const errorAdd = ref(null); // Ajout de la variable errorAdd



    const selectedSalary = ref({
      id: null,
      net_amount: '',
      gross_amount: '',
      bank_name: '',
      salary_date: '',
      bank_id: '',

    });

    const fetchSalaries = async () => {
      if (!employeeId) {
        error.value = 'Identifiant employé introuvable.';
        return;
      }

      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/getSalaries/${employeeId}`
        );
        salaries.value = response.data.salaries;
      } catch (err) {
        error.value = 'Erreur lors du chargement des salaires.';
      }
    };

    const openUpdateModal = (salary) => {
      selectedSalary.value = { ...salary };
    };

    const updateSalary = async () => {
      try {
        const response = await axios.put(
          `http://127.0.0.1:8000/api/updateSalary/${selectedSalary.value.id}`,
          selectedSalary.value
        );

         // Vérifier si la réponse contient les informations attendues
         if (response.data && response.data.salary) {
      // Recharger les salaires mis à jour depuis l'API
        fetchSalaries();  // Appel à la fonction pour recharger les données des salaires

      // Afficher un message de succès
 

          // Afficher un message de succès
          messageSuccess.value = 'Salaire mis à jour avec succès';
          errorAdd.value = null;

          // Masquer le message après 3 secondes
          setTimeout(() => (messageSuccess.value = null), 3000);
        }
      } catch (err) {
        error.value = 'Erreur lors de la mise à jour du salaire.';
      }
    };
            


    const getBank = async () => {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/showBank/${employeeId}`);
    console.log('Response ', response.data);     
    banks.value = response.data;       
  } catch (error) {
    console.error('Error fetching bank details:', error);
  }
};




    onMounted(() => {
      fetchSalaries();
      getBank();
    });

    return {
      salaries,
      error,
      messageSuccess,

      selectedSalary,
      openUpdateModal,
      updateSalary,
      banks
    };
  },
};
</script>

<style scoped>
.table {
  margin-top: 20px;
}
</style>
