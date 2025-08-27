<template>
  <div class="container mt-5">

    <!-- Titre de la page -->
    <h2 class="mb-4 text-center flex items-center justify-center gap-2 text-black text-2xl font-bold">
      <i class="bi bi-pencil-square text-black-600"></i>
      Modifier le congé
    </h2>

    <!-- Formulaire de modification -->
    <form @submit.prevent="updateHoliday">
      <div class="row">

        <!-- Colonne gauche -->
        <div class="col-md-6">
          <div class="mb-3">
            <label for="type_vacancy" class="form-label">Type de congé :</label>
            <select id="type_vacancy" class="form-select" v-model="type_vacancy" required>
              <option value="" disabled>Choisir un type de congé</option>
              <option value="Congé annuel">Congé annuel</option>
              <option value="Congé maladie">Congé maladie</option>
              <option value="Autre">Autre</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="number_days" class="form-label">Nombre de jours :</label>
            <input
              type="number"
              id="number_days"
              class="form-control"
              v-model="number_days"
              required
              min="1"
              step="1"
            />
          </div>

          <div class="mb-3">
            <label for="certificate" class="form-label">Certificat (facultatif) :</label>
            <input
              type="file"
              id="certificate"
              class="form-control"
              @change="handleFileChange"
            />
          </div>
        </div>

        <!-- Colonne droite -->
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
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description :</label>
            <textarea
              id="description"
              class="form-control"
              v-model="description"
              rows="4"
            ></textarea>
          </div>
        </div>

      </div>

      <!-- Bouton Mettre à jour -->
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-success px-4">Mettre à jour</button>
      </div>
    </form>

    <!-- Messages d'erreur et de succès -->
    <div v-if="errorUpdate" class="alert alert-danger mt-3">{{ errorUpdate }}</div>
    <div v-if="messageSuccess" class="alert alert-success mt-3">{{ messageSuccess }}</div>

  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

export default {
  name: 'UpdateHoliday',
  setup() {
    const type_vacancy = ref('');
    const number_days = ref('');
    const description = ref('');
    const start_date = ref('');
    const end_date = ref('');
    const certificate = ref(null);

    const errorUpdate = ref(null);
    const messageSuccess = ref(null);

    const route = useRoute();
    const router = useRouter();
    const holidayId = route.params.holidayId;

    // Gestion du fichier
    const handleFileChange = (event) => {
      certificate.value = event.target.files[0];
    };

    // Récupération des données du congé
    const getHoliday = async () => {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/getHoliday/${holidayId}`);
        const holiday = response.data.data || response.data;

        type_vacancy.value = holiday.type_vacancy || '';
        start_date.value = holiday.start_date || '';
        end_date.value = holiday.end_date || '';
        description.value = holiday.description || '';
        number_days.value = holiday.number_days || '';
      } catch {
        errorUpdate.value = 'Erreur lors du chargement des données du congé.';
      }
    };

    // Mise à jour du congé
    const updateHoliday = async () => {
      // Validation des champs
      if (!type_vacancy.value || !start_date.value) {
        errorUpdate.value = 'Les champs Type de congé et Date de début sont obligatoires.';
        return;
      }

      if (end_date.value && new Date(end_date.value) < new Date(start_date.value)) {
        errorUpdate.value = 'La date de fin ne peut pas être antérieure à la date de début.';
        return;
      }

      if (number_days.value <= 0) {
        errorUpdate.value = 'Le nombre de jours doit être un nombre positif.';
        return;
      }

      const formData = new FormData();
      formData.append('type_vacancy', type_vacancy.value);
      formData.append('description', description.value);
      formData.append('number_days', number_days.value);
      formData.append('start_date', start_date.value);
      if (end_date.value) formData.append('end_date', end_date.value);
      if (certificate.value) formData.append('certificate', certificate.value);

      try {
        await axios.post(`http://127.0.0.1:8000/api/updateHoliday/${holidayId}`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' },
        });

        messageSuccess.value = 'Congé mis à jour avec succès.';
        errorUpdate.value = null;

        const employeeId = route.params.id;
        router.push(`/employee/${employeeId}/nav/holiday`);

        setTimeout(() => (messageSuccess.value = null), 3000);
      } catch (error) {
        errorUpdate.value = error.response?.data?.message || 'Une erreur est survenue lors de la mise à jour.';
      }
    };

    onMounted(() => {
      getHoliday();
    });

    return {
      type_vacancy,
      number_days,
      description,
      start_date,
      end_date,
      certificate,
      errorUpdate,
      messageSuccess,
      updateHoliday,
      handleFileChange,
    };
  },
};
</script>

<style scoped>
/* Ajouter des styles personnalisés si nécessaire */
</style>
