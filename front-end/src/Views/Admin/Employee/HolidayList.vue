<template>
  <div class="container mt-4">

    <!-- Titre de la page -->
    <h2 class="text-center mb-4 text-dark">
      <i class="bi bi-calendar-check me-2"></i>
      Congés
    </h2>

    <!-- Messages de succès et d'erreur -->
    <div v-if="messageSuccess" class="alert alert-success text-center">
      {{ messageSuccess }}
    </div>
    <div v-if="loading" class="loading-container">
      <div class="spinner"></div>
    </div>
    <div v-else-if="error" class="alert alert-danger text-center">
      {{ error }}
    </div>

    <!-- Statistiques de congés -->
    <div class="row">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Total congés -->
        <div class="shadow-lg rounded-xl bg-white min-h-[120px] p-4 flex items-center gap-4">
          <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
            <i class="bi bi-calendar text-blue-600 text-xl"></i>
          </div>
          <div class="flex flex-col">
            <p class="text-3xl font-bold mb-0">{{ calculateHolidayDaysOnYear.total_holiday_days }}</p>
            <p class="text-gray-600 text-sm -mt-1">Nombre total de congé</p>
          </div>
        </div>

        <!-- Jours utilisés -->
        <div class="shadow-lg rounded-xl bg-white min-h-[120px] p-4 flex items-center gap-4">
          <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
            <i class="bi bi-calendar-check text-green-600 text-xl"></i>
          </div>
          <div class="flex flex-col">
            <p class="text-3xl font-bold mb-0">{{ calculateTotalHolidayDays.total_days }}</p>
            <p class="text-gray-600 text-sm -mt-1">Jours de congé utilisés</p>
          </div>
        </div>

        <!-- Jours restants -->
        <div class="shadow-lg rounded-xl bg-white min-h-[120px] p-4 flex items-center gap-4">
          <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
            <i class="bi bi-calendar3-week text-yellow-600 text-xl"></i>
          </div>
          <div class="flex flex-col">
            <p class="text-3xl font-bold mb-0">{{ calculateRemainingHolidayDays.remaining_days }}</p>
            <p class="text-gray-600 text-sm -mt-1">Jours restants de congé</p>
          </div>
        </div>

      </div>
    </div>

    <!-- Bouton Ajouter congé -->
    <div v-if="!loading && holidays.length" class="mt-4">
      <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-success text-white">
          <RouterLink :to="`/employee/${experienceId}/nav/addHoliday`" class="text-white text-decoration-none">
            <i class="bi bi-plus-circle"></i>
            Ajouter autre
          </RouterLink>
        </button>
      </div>

      <!-- Tableau des congés -->
      <div class="shadow-sm p-4 mt-4">
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-center align-middle">
            <thead>
              <tr>
                <th>Type de congé</th>
                <th>Description</th>
                <th>Nombre du Jours</th>
                <th>Statut</th>
                <th>Date Debut</th>
                <th>Date Fin</th>
                <th>Certificat</th>
                <th>Modifier</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="holiday in holidays" :key="holiday.id">
                <td>{{ holiday.type_vacancy }}</td>
                <td>{{ holiday.description }}</td>
                <td>{{ holiday.number_days }}</td>
                <td>
                  <span
                    :class="{
                      'bg-green-100 text-green-800': holiday.status === 'À venir',
                      'bg-yellow-100 text-yellow-800': holiday.status === 'En cours',
                      'bg-red-100 text-red-800': holiday.status === 'Terminé'
                    }"
                    class="inline-block px-3 py-1 text-sm rounded-full"
                  >
                    {{ holiday.status }}
                  </span>
                </td>
                <td>{{ holiday.start_date }}</td>
                <td>{{ holiday.end_date || 'Employé(e) en vacance' }}</td>
                <td>
                  <a
                    v-if="holiday.certificate"
                    :href="`http://127.0.0.1:8000/storage/${holiday.certificate}`"
                    target="_blank"
                    class="btn btn-outline-dark btn-sm"
                  >
                    Voir le certificat
                  </a>
                  <span v-else class="text-muted fst-italic">Pas de certificat</span>
                </td>
                <td>
                  <RouterLink :to="`/employee/${experienceId}/nav/updateHoliday/${holiday.id}`">
                    <button type="button" class="btn btn-dark">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                  </RouterLink>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Aucune congé trouvée -->
    <div v-if="!loading && !error && !holidays.length" class="text-center text-muted mt-4">
      <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-success">
          <RouterLink :to="`/employee/${experienceId}/nav/addHoliday`" class="text-white text-decoration-none">
            <i class="bi bi-plus-circle"></i>
            Ajouter congé
          </RouterLink>
        </button>
      </div>
      <p>Aucune congé trouvée.</p>
    </div>

  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';

export default {
  name: 'ListHoliday',
  setup() {
    const holidays = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const route = useRoute();
    const router = useRouter();
    const experienceId = ref(route.params.id);

    const messageSuccess = ref(route.query.success || null);

    const calculateHolidayDaysOnYear = ref(0);
    const calculateTotalHolidayDays = ref(0);
    const calculateRemainingHolidayDays = ref(0);

    // Récupérer les jours de congé totaux
    const getcalculateHolidayDaysOnYear = async () => {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/calculateHolidayDays/${experienceId.value}`);
        calculateHolidayDaysOnYear.value = response.data;
      } catch (err) {
        error.value = err.response?.data?.message || 'Erreur lors de la récupération des données.';
      }
    };

    const getcalculateTotalHolidayDays = async () => {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/calculateTotalHolidayDays/${experienceId.value}`);
        calculateTotalHolidayDays.value = response.data;
      } catch (err) {
        error.value = err.response?.data?.message || 'Erreur lors de la récupération des données.';
      }
    };

    const getcalculateRemainingHolidayDays = async () => {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/calculateRemainingHolidayDays/${experienceId.value}`);
        calculateRemainingHolidayDays.value = response.data;
      } catch (err) {
        error.value = err.response?.data?.message || 'Erreur lors de la récupération des données.';
      }
    };

    // Récupérer la liste des congés
    const fetchHolidays = async () => {
      loading.value = true;
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/showHoliday/${experienceId.value}`);
        holidays.value = response.data.holidays.sort((a, b) => b.id - a.id);
      } catch (err) {
        error.value = err.message;
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      if (route.query.success) {
        messageSuccess.value = route.query.success;
        setTimeout(() => {
          messageSuccess.value = null;
          const { success, ...queryWithoutSuccess } = route.query;
          router.replace({ query: queryWithoutSuccess });
        }, 3000);
      }

      fetchHolidays();
      getcalculateHolidayDaysOnYear();
      getcalculateTotalHolidayDays();
      getcalculateRemainingHolidayDays();
    });

    return {
      holidays,
      loading,
      error,
      experienceId,
      messageSuccess,
      calculateHolidayDaysOnYear,
      calculateTotalHolidayDays,
      calculateRemainingHolidayDays
    };
  }
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
</style>
