<template>
  <div class="container mt-4">

    <!-- Titre de la page -->
    <h2 class="text-center mb-4 text-dark">
      <i class="fas fa-briefcase me-2"></i>
      Liste des expériences
    </h2>

    <!-- Message de succès -->
    <div v-if="successMessage" class="alert alert-success text-center">
      {{ successMessage }}
    </div>

    <!-- Spinner de chargement -->
    <div v-if="loading" class="loading-container">
      <div class="spinner"></div>
    </div>

    <!-- Message d'erreur -->
    <div v-else-if="error" class="alert alert-danger text-center">
      {{ error }}
    </div>

    <!-- Tableau des expériences -->
    <div v-if="!loading && experiences.length" class="mt-4">
      
      <!-- Bouton Ajouter -->
      <div class="d-flex justify-content-end">
        <button
          style="background-color: #198754; color: white; border: none; padding: 0.375rem 0.75rem; border-radius: 0.25rem; cursor: pointer;"
        >
          <RouterLink
            :to="`/employee/${employeeId}/nav/addExperience`"
            class="text-white text-decoration-none"
          >
            <i class="bi bi-plus-circle"></i>
            Ajouter une expérience
          </RouterLink>
        </button>
      </div>

      <!-- Tableau -->
      <div class="p-4 mt-4">
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-center align-middle">
            <thead>
              <tr>
                <th scope="col">Poste</th>
                <th scope="col">Type de contrat</th>
                <th scope="col">Date de début</th>
                <th scope="col">Date de fin</th>
                <th scope="col">Salaire</th>
                <th scope="col">Modifier</th>
                <th scope="col">Congé</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="experience in experiences" :key="experience.id">

                <!-- Poste -->
                <td>{{ experience.job_title }}</td>

                <!-- Type de contrat avec badge -->
                <td>
                  <span
                    class="badge px-3 py-2"
                    :class="{
                      'bg-warning text-dark': experience.type_contract.toLowerCase() === 'stage',
                      'bg-info text-dark': experience.type_contract.toLowerCase() === 'cdd',
                      'bg-success bg-opacity-25 text-success': experience.type_contract.toLowerCase() === 'cdi',
                      'bg-light text-secondary': !['stage','cdd','cdi'].includes(experience.type_contract.toLowerCase())
                    }"
                    style="font-size: 12px; border-radius: 0.5rem; opacity: 0.8;"
                  >
                    {{ experience.type_contract }}
                  </span>
                </td>

                <!-- Dates -->
                <td>{{ experience.start_date }}</td>
                <td>
                  <template v-if="experience.end_date">
                    {{ experience.end_date }}
                  </template>
                  <template v-else>
                    <span class="text-muted">Employé(e) actuellement en poste</span>
                  </template>
                </td>

                <!-- Salaire -->
                <td>
                  <template v-if="experience.type_contract === 'Stage'">
                    <span class="text-muted">Stage — pas de salaire à ajouter</span>
                  </template>
                  <template v-else>
                    <template v-if="!experience.end_date">
                      <RouterLink :to="`/employee/${employeeId}/experience/${experience.id}/addSalary`">
                        <button type="button" class="btn btn-dark" title="Ajouter un salaire">
                          <i class="bi bi-wallet"></i>
                        </button>
                      </RouterLink>
                    </template>
                    <template v-else>
                      <span class="text-muted">L'employé(e) n'est plus dans la société.</span>
                    </template>
                  </template>
                </td>

                <!-- Modifier -->
                <td>
                  <RouterLink
                    :to="`/employee/${employeeId}/nav/editExperience/${experience.id}`"
                    title="Modifier l'expérience"
                  >
                    <button type="button" class="btn btn-dark">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                  </RouterLink>
                </td>

                <!-- Congé -->
                <td>
                  <template v-if="experience.type_contract === 'Stage'">
                    <span class="text-muted">Stage - pas de vacance à ajouter</span>
                  </template>
                  <template v-else>
                    <RouterLink :to="`/employee/${experience.id}/nav/holiday`" title="Gérer les congés">
                      <button type="button" class="btn btn-dark">
                        <i class="bi bi-calendar2-day"></i>
                      </button>
                    </RouterLink>
                  </template>
                </td>

              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Message si aucune expérience -->
    <div v-if="!loading && !error && !experiences.length" class="text-center text-muted mt-4">
      <div class="d-flex justify-content-end">
        <RouterLink :to="`/employee/${employeeId}/nav/addExperience`">
          <button type="button" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i>
            Ajouter une expérience
          </button>
        </RouterLink>
      </div>
      <p>Aucune expérience trouvée.</p>
    </div>

  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

export default {
  name: 'ListExperience',

  setup() {
    const experiences = ref([]);
    const loading = ref(false);
    const error = ref(null);
    const successMessage = ref(null);
    const route = useRoute();
    const router = useRouter();
    const employeeId = ref(route.params.id);

    const fetchExperiences = async () => {
      loading.value = true;
      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/showExperience/${employeeId.value}`
        );
        experiences.value = response.data.sort((a, b) => b.id - a.id);
      } catch (err) {
        error.value = err.response?.data?.message || err.message || "Une erreur s'est produite";
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      if (route.query.success) {
        successMessage.value = route.query.success;
        setTimeout(() => {
          successMessage.value = null;
          const { success, ...queryWithoutSuccess } = route.query;
          router.replace({ query: queryWithoutSuccess });
        }, 3000);
      }
      fetchExperiences();
    });

    return { experiences, loading, error, successMessage, employeeId };
  },
};
</script>

<style>
.loading-container {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
}

.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  border-left-color: #000; /* Black spinner */
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>
