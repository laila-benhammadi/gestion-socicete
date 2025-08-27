<template>
  <div class="container mt-5">

    <!-- Titre de la page -->
    <h2 class="mb-4 text-center text-dark">
      <i class="bi bi-calendar-plus"></i> Ajouter un congé
    </h2>

    <!-- Formulaire d'ajout de congé -->
    <form @submit.prevent="addHoliday">
      <div class="row">

        <!-- Colonne gauche -->
        <div class="col-md-6">
          <div class="mb-3">
            <label for="type_vacancy" class="form-label">Type de congé :</label>
            <select id="type_vacancy" class="form-select" v-model="type_vacancy" required>
              <option value="" disabled selected>Choisir un type de congé</option>
              <option value="Congé annuel">Congé annuel</option>
              <option value="Congé maladie">Congé maladie</option>
              <option value="Autre">Autre</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="number_days" class="form-label">Nombre du jour :</label>
            <input type="number" id="number_days" class="form-control" v-model="number_days" required />
          </div>

          <div class="mb-3">
            <label for="certificate" class="form-label">Certificat (facultatif) :</label>
            <input type="file" id="certificate" class="form-control" @change="handleFileChange" />
          </div>
        </div>

        <!-- Colonne droite -->
        <div class="col-md-6">
          <div class="mb-3">
            <label for="start_date" class="form-label">Date de début :</label>
            <input type="date" id="start_date" class="form-control" v-model="start_date" required />
          </div>

          <div class="mb-3">
            <label for="end_date" class="form-label">Date de fin :</label>
            <input type="date" id="end_date" class="form-control" v-model="end_date" />
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description :</label>
            <textarea id="description" class="form-control" v-model="description" required></textarea>
          </div>
        </div>

      </div>

      <!-- Bouton Ajouter -->
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-success px-4">Ajouter</button>
      </div>
    </form>

    <!-- Message d'erreur -->
    <div v-if="errorAdd" class="alert alert-danger mt-3">{{ errorAdd }}</div>

  </div>
</template>

<script>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

export default {
  name: 'AddHoliday',
  setup() {
    const type_vacancy = ref("");
    const start_date = ref("");
    const end_date = ref("");
    const description = ref("");
    const number_days = ref("");
    const certificate = ref(null);
    const errorAdd = ref(null);
    const messageSuccess = ref(null);

    const route = useRoute();
    const router = useRouter();

    const handleFileChange = (event) => {
      certificate.value = event.target.files[0];
    };

    const addHoliday = async () => {
      const experienceId = route.params.id;

      // Validation des champs obligatoires
      if (!type_vacancy.value || !start_date.value || !description.value || !number_days.value) {
        errorAdd.value = 'Veuillez remplir tous les champs.';
        return;
      }

      const formData = new FormData();
      formData.append('experience_id', experienceId);
      formData.append('type_vacancy', type_vacancy.value);
      formData.append('description', description.value);
      formData.append('number_days', number_days.value);
      formData.append('start_date', start_date.value);

      if (end_date.value) formData.append('end_date', end_date.value);
      if (certificate.value) formData.append('certificate', certificate.value);

      try {
        const response = await axios.post(
          `http://127.0.0.1:8000/api/addHoliday/${experienceId}`,
          formData,
          { headers: { 'Content-Type': 'multipart/form-data' } }
        );

        messageSuccess.value = 'Congé ajouté avec succès.';
        router.push({
          path: `/employee/${experienceId}/nav/holiday`,
          query: { success: 'Expérience ajoutée avec succès' },
        });
      } catch (err) {
        console.error("Erreur lors de l'ajout de l'expérience:", err);
        errorAdd.value = "Erreur lors de l'ajout de l'expérience. Veuillez réessayer.";
      }
    };

    return {
      type_vacancy,
      start_date,
      end_date,
      description,
      number_days,
      certificate,
      errorAdd,
      messageSuccess,
      addHoliday,
      handleFileChange,
    };
  },
};
</script>

<style scoped>
/* Styles personnalisés si nécessaire */
</style>
