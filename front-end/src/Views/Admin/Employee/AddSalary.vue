<template>
  <div class="container mt-5">

    <!-- Titre de la page -->
    <h2 class="text-center mb-4 text-dark">
      <i class="bi bi-wallet me-2"></i>
      Ajouter un salaire
    </h2>

    <!-- Message de succès -->
    <div v-if="successMessage" class="alert alert-success text-center">
      {{ successMessage }}
    </div>

    <!-- Message d'erreur -->
    <div v-if="error" class="alert alert-danger text-center">
      {{ error }}
    </div>

    <!-- Formulaire d'ajout de salaire -->
    <form @submit.prevent="submitSalary">

      <!-- Montant net -->
      <div class="mb-3">
        <label for="netAmount" class="form-label">Montant net (DH) :</label>
        <input
          type="number"
          v-model="salary.net_amount"
          id="netAmount"
          class="form-control"
          required
          min="0"
          step="0.01"
        />
      </div>

      <!-- Montant brut supplémentaire -->
      <div class="mb-3">
        <label for="grossAmount" class="form-label">Montant brut supplémentaire (DH) :</label>
        <input
          type="number"
          v-model="salary.gross_amount"
          id="grossAmount"
          class="form-control"
          min="0"
          step="0.01"
        />
      </div>

      <!-- Date du salaire -->
      <div class="mb-3">
        <label for="salaryDate" class="form-label">Date :</label>
        <input
          type="date"
          v-model="salary.salary_date"
          id="salaryDate"
          class="form-control"
          required
        />
      </div>

      <!-- Sélection de la banque -->
      <div class="mb-3">
        <label for="bank_id" class="form-label">Banque :</label>
        <select v-model="salary.bank_id" class="form-select" id="bank_id" required>
          <option value="" disabled>Choisissez une banque</option>
          <option v-for="bank in banks" :key="bank.id" :value="bank.id">
            {{ bank.bank_name }}
          </option>
        </select>
      </div>

      <!-- Bouton Ajouter -->
      <div class="d-flex justify-content-center mt-3">
        <button type="submit" class="btn btn-success px-4" style="width: 100%;">Ajouter</button>
      </div>

    </form>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';   
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

export default {
  name: 'AddSalary',
  setup() {
    const route = useRoute();
    const router = useRouter();

    const banks = ref([]);
    const employeeId = route.params.id;
    const experienceId = route.params.experienceId;

    const salary = ref({
      net_amount: '',
      gross_amount: '',
      salary_date: '',
      bank_id: '',
    });

    const successMessage = ref('');
    const error = ref(null);

    // Récupérer la liste des banques
    const getBank = async () => {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/showBank/${employeeId}`);
        banks.value = response.data;       
      } catch (err) {
        console.error('Erreur lors du chargement des banques :', err);
      }
    };

    // Soumettre le salaire
    const submitSalary = async () => {
      error.value = null;
      successMessage.value = null;

      try {
        const response = await axios.post(
          `http://127.0.0.1:8000/api/experience/${experienceId}/salary`,
          {
            net_amount: salary.value.net_amount,
            gross_amount: salary.value.gross_amount || 0,
            salary_date: salary.value.salary_date,
            bank_id: salary.value.bank_id,
            user_id: employeeId,
          },
          {
            headers: {
              'Content-Type': 'application/json',
            },
          }
        );

        successMessage.value = response.data.message || "Le salaire ajoutée avec succès !";
        error.value = null;

        setTimeout(() => {
          router.push(`/employee/${employeeId}/nav/experience`);
        }, 2000);
      } catch (err) {
        console.error('Erreur lors de la requête :', err);
        error.value = "Erreur lors de l'ajout du salaire. Veuillez vérifier les données saisies ou réessayer plus tard.";
      }
    };

    // Charger les banques au montage du composant
    onMounted(() => {
      getBank();
    });

    return {
      salary,
      successMessage,
      error,
      submitSalary,
      banks,
    };
  },
};
</script>

<style scoped>
.container {
  max-width: 600px;
  margin: auto;
}
</style>
