<template>
  <div class="container mt-5">

    <!-- Titre de la page -->
    <h2 class="text-center mb-4 text-dark">
      <i class="bi bi-briefcase"></i>
      Ajouter expérience
    </h2>

    <!-- Message de succès -->
    <div v-if="messageSuccess" class="alert alert-success mt-3">
      {{ messageSuccess }}
    </div>

    <!-- Message d'erreur -->
    <div v-if="errorAdd" class="alert alert-danger mt-3">
      {{ errorAdd }}
    </div>

    <!-- Formulaire d'ajout -->
    <form @submit.prevent="addExperience">
      <div class="row">

        <!-- Colonne gauche : poste et type de contrat -->
        <div class="col-md-6">
          <div class="mb-3">
            <label for="post" class="form-label">Poste :</label>
            <select id="post" class="form-select" v-model="job_title" required>
              <option value="" disabled>Choisissez un poste</option>
              <option value="Développeur Front-End">Développeur Front-End</option>
              <option value="Développeur Back-End">Développeur Back-End</option>
              <option value="Développeur Full-Stack">Développeur Full-Stack</option>
              <option value="Développeur Mobile (iOS/Android)">Développeur Mobile (iOS/Android)</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="contract_type" class="form-label">Type de contrat :</label>
            <select id="contract_type" class="form-select" v-model="type_contract" required>
              <option value="" disabled>Choisissez un type de contrat</option>
              <option value="CDD">CDD</option>
              <option value="CDI">CDI</option>
              <option value="Stage">Stage</option>
            </select>
          </div>
        </div>

        <!-- Colonne droite : dates de début et fin -->
        <div class="col-md-6">
          <div class="mb-3">
            <label for="start_date" class="form-label">Date de début :</label>
            <input
              type="date"
              id="start_date"
              class="form-control"
              v-model="start_date"
              required
            />
          </div>

          <div class="mb-3">
            <label for="end_date" class="form-label">Date de fin :</label>
            <input
              type="date"
              id="end_date"
              class="form-control"
              v-model="end_date"
            />
            <small class="text-muted">
              Laissez vide si l'expérience est toujours en cours
            </small>
          </div>
        </div>

      </div>

      <!-- Bouton Ajouter -->
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-success px-4">Ajouter</button>
      </div>
    </form>

  </div>
</template>

<script>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

export default {
  name: 'AddExperience',
  setup() {
    const type_contract = ref('');
    const job_title = ref('');
    const start_date = ref('');
    const end_date = ref('');
    const employeeId = ref(null);
    const errorAdd = ref(null);
    const messageSuccess = ref(null);
    const route = useRoute();
    const router = useRouter();

    const addExperience = async () => {
      employeeId.value = route.params.id;
      errorAdd.value = null;
      messageSuccess.value = null;

      if (!type_contract.value || !job_title.value || !start_date.value) {
        errorAdd.value = 'Veuillez remplir tous les champs obligatoires.';
        return;
      }

      const newExperience = {
        user_id: employeeId.value,
        type_contract: type_contract.value,
        job_title: job_title.value,
        start_date: start_date.value,
        end_date: end_date.value || null,
      };

      try {
        await axios.post(
          `http://127.0.0.1:8000/api/addExperience/${employeeId.value}`,
          newExperience
        );

        messageSuccess.value = "L'expérience ajoutée avec succès !";
        setTimeout(() => {
          router.push({
            path: `/employee/${employeeId.value}/nav/experience`,
          });
        }, 1500);
      } catch (err) {
        console.error("Erreur lors de l'ajout de l'expérience :", err);
        errorAdd.value = "Erreur lors de l'ajout de l'expérience. Veuillez réessayer.";
      }
    };

    return {
      type_contract,
      job_title,
      start_date,
      end_date,
      errorAdd,
      messageSuccess,
      addExperience,
    };
  },
};
</script>

<style scoped>
/* Styles personnalisés (à ajouter si nécessaire) */
</style>
