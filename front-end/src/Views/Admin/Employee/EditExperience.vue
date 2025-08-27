<template>
  <div class="container mt-4">

    <!-- Titre de la page -->
    <h2 class="mb-4 text-center text-dark">
      <i class="bi bi-pencil-square"></i>
      Modifier d'expérience
    </h2>

    <!-- Messages d'erreur et de succès -->
    <div v-if="errorEdit" class="alert alert-danger mt-3" role="alert">
      {{ errorEdit }}
    </div>
    <div v-if="messageSuccess" class="alert alert-success mt-3" role="alert">
      {{ messageSuccess }}
    </div>

    <!-- Formulaire de modification -->
    <form @submit.prevent="updateExperience">

      <div class="row">

        <!-- Partie gauche : Poste et type de contrat -->
        <div class="col-md-6">

          <!-- Poste -->
          <div class="mb-3">
            <label for="job_title" class="form-label">Poste :</label>
            <select id="job_title" class="form-select" v-model="job_title" required>
              <option value="" disabled>Choisissez un poste</option>
              <option value="Développeur Front-End">Développeur Front-End</option>
              <option value="Développeur Back-End">Développeur Back-End</option>
              <option value="Développeur Full-Stack">Développeur Full-Stack</option>
              <option value="Développeur Mobile (iOS/Android)">Développeur Mobile (iOS/Android)</option>
            </select>
          </div>

          <!-- Type de contrat -->
          <div class="mb-3">
            <label for="type_contract" class="form-label">Type de contrat :</label>
            <select id="type_contract" class="form-select" v-model="type_contract" required>
              <option value="" disabled>Choisissez un type de contrat</option>
              <option value="CDD">CDD</option>
              <option value="CDI">CDI</option>
              <option value="Stage">Stage</option>
            </select>
          </div>

        </div>

        <!-- Partie droite : Dates -->
        <div class="col-md-6">

          <!-- Date de début -->
          <div class="mb-3">
            <label for="start_date" class="form-label">Date de début :</label>
            <input type="date" id="start_date" class="form-control" v-model="start_date" required />
          </div>

          <!-- Date de fin -->
          <div class="mb-3">
            <label for="end_date" class="form-label">Date de fin :</label>
            <input type="date" id="end_date" class="form-control" v-model="end_date" />
            <small class="text-muted">Laisser vide si l'expérience est toujours en cours</small>
          </div>

        </div>
      </div>

      <!-- Bouton de soumission -->
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-success px-4">Modifer</button>
      </div>

    </form>

  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

export default {
  name: 'EditExperience',
  setup() {
    const job_title = ref('');
    const type_contract = ref('');
    const start_date = ref('');
    const end_date = ref('');
    const errorEdit = ref(null);
    const messageSuccess = ref(null);
    const loading = ref(false);

    const route = useRoute();
    const router = useRouter();
    const experienceId = route.params.experienceId;
    const employeeId = route.params.id;

    // Récupérer l'expérience à modifier
    const getExperience = async () => {
      if (experienceId) {
        loading.value = true;
        try {
          const response = await axios.get(`http://127.0.0.1:8000/api/getExperience/${experienceId}`);
          const experience = response.data;
          job_title.value = experience.job_title;
          type_contract.value = experience.type_contract; // garder la casse comme dans la base
          start_date.value = experience.start_date;
          end_date.value = experience.end_date;
        } catch (err) {
          errorEdit.value = `Erreur lors du chargement : ${err.response?.data?.message || err.message}`;
        } finally {
          loading.value = false;
        }
      }
    };

    // Mettre à jour l'expérience
    const updateExperience = async () => {
      errorEdit.value = null;
      messageSuccess.value = null;

      if (!job_title.value || !type_contract.value || !start_date.value) {
        errorEdit.value = "Veuillez remplir tous les champs obligatoires.";
        return;
      }

      const data = {
        user_id: employeeId,
        job_title: job_title.value,
        type_contract: type_contract.value,
        start_date: start_date.value,
        end_date: end_date.value || null,
      };

      try {
        await axios.put(`http://127.0.0.1:8000/api/updateExperience/${experienceId}`, data);
        messageSuccess.value = "L'expérience mise à jour avec succès !";

        setTimeout(() => {
          router.push(`/employee/${employeeId}/nav/experience`);
        }, 1500);
      } catch (err) {
        errorEdit.value = `Erreur lors de la mise à jour : ${err.response?.data?.message || err.message}`;
      }
    };

    onMounted(() => {
      getExperience();
    });

    return {
      job_title,
      type_contract,
      start_date,
      end_date,
      errorEdit,
      messageSuccess,
      updateExperience,
    };
  },
};
</script>
