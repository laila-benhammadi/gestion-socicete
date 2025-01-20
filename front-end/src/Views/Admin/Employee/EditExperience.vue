<template>
  <div class="container mt-5">
    <h2 class="mb-4 text-center">Formulaire de modification d'expérience</h2>

    <form @submit.prevent="updateExperience">
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="job_title" class="form-label">Poste :</label>
            <select id="job_title" class="form-select" v-model="job_title">
              <option value="" disabled>Choisir un Poste</option>
              <option value="Développeur Front-End">Développeur Front-End</option>
              <option value="Développeur Back-End">Développeur Back-End</option>
              <option value="Développeur Full-Stack">Développeur Full-Stack</option>
              <option value="Développeur Mobile (iOS/Android)">Développeur Mobile (iOS/Android)</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="type_contract" class="form-label">Type de contrat:</label>
            <select id="type_contract" class="form-select" v-model="type_contract">
              <option value="" disabled>Choisir un Type de contrat</option>
              <option value="CDD">CDD</option>
              <option value="CDI">CDI</option>
              <option value="Stage">Stage</option>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label for="start_date" class="form-label">Date de début:</label>
            <input type="date" id="start_date" class="form-control" v-model="start_date" />
          </div>

          <div class="mb-3">
            <label for="end_date" class="form-label">Date de fin:</label>
            <input type="date" id="end_date" class="form-control" v-model="end_date" />
          </div>
        </div>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-success px-4">Mettre à jour</button>
      </div>
    </form>

    <div v-if="errorEdit" class="alert alert-danger mt-3">{{ errorEdit }}</div>
    <div v-if="messageSuccess" class="alert alert-success mt-3">{{ messageSuccess }}</div>
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
    const employeeId = route.params.employeeId; 

    const getExperience = async () => {
      if (experienceId) {
        loading.value = true;
        try {
          const response = await axios.get(`http://127.0.0.1:8000/api/getExperience/${experienceId}`);
          const experience = response.data;
          job_title.value = experience.job_title;
          type_contract.value = experience.type_contract.toUpperCase(); // Conversion en majuscules
          start_date.value = experience.start_date;
          end_date.value = experience.end_date;
        } catch (err) {
          errorEdit.value = `Erreur: ${err.message}`;
        } finally {
          loading.value = false;
        }
      }
    };

    const updateExperience = async () => {
      const data = {
        user_id: employeeId,
        type_contract: type_contract.value,
        job_title: job_title.value,
        start_date: start_date.value,
        end_date: end_date.value,
      };

      try {
        const response = await axios.put(`http://127.0.0.1:8000/api/updateExperience/${experienceId}`, data);
        messageSuccess.value = 'L\'expérience a été mise à jour.';
        router.push(`/employee/${employeeId}/nav/experience`);
      } catch (err) {
        errorEdit.value = `Erreur: ${err.message}`;
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
