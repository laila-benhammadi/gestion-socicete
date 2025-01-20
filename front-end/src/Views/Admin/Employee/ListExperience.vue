<template>
  <div class="container mt-5">

    <h2 class="text-center mb-4">Liste des expériences</h2>

     <div v-if="successMessage" class="alert alert-success text-center">
      {{ successMessage }}
    </div>

     <div v-else-if="loading" style="font-size: 20px; color: #007bff; text-align: center; font-weight: bold;">
      Chargement...
    </div>

     <div v-else-if="error" class="alert alert-danger text-center">
      {{ error }}
    </div>

     <div v-if="!loading && experiences.length" class="mt-4">
      <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-dark text-white">
          <RouterLink :to="`/employee/${employeeId}/nav/addExperience`" class="text-white text-decoration-none">
            <i class="bi bi-plus-circle"></i>
              Ajouter autre
          </RouterLink>
        </button>
      </div>

     <div class=" p-4 mt-4">
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-center align-middle">
            <thead>
              <tr>
                <th scope="col">Poste</th>
                <th scope="col">Contrat</th>
                <th scope="col">Date Debut</th>
                <th scope="col">Date Fin</th>
                <th scope="col"> Salaire</th>
                <th scope="col">Modifier </th>
                <th scope="col">Congé </th>
              </tr>
            </thead>
            <tbody>
  <tr v-for="experience in experiences" :key="experience.id">
    <td>{{ experience.job_title }}</td>
    <td>
      <span class="badge text-bg-dark px-3 py-2" style="font-size: 12px; border-radius: 0.5rem;">
        {{ experience.type_contract }}
      </span>
    </td>
    <td>{{ experience.start_date }}</td>
    <td>{{ experience.end_date || 'Employé(é) est en travail.' }}</td>

     <td>
      <template v-if="!experience.end_date">
        <RouterLink :to="`/employee/${employeeId}/experience/${experience.id}/addSalary`">
          <button type="button" class="btn btn-dark">
            <i class="bi bi-wallet"></i>
          </button>
        </RouterLink>
      </template>
      <template v-else>
        <span class="text-muted">L'employée ne reste pas dans la société.</span>
      </template>
    </td>

     <td>
      <router-link :to="`/employee/${employeeId}/nav/editExperience/${experience.id}`">
        <button type="button" class="btn btn-dark">
          <i class="bi bi-pencil-square"></i>
        </button>
      </router-link>
    </td>

    <!-- Holiday button -->
    <td>
      <RouterLink :to="`/employee/${experience.id}/nav/holiday`"> 
      <button type="button" class="btn btn-dark">
        <i class="bi bi-calendar2-day"></i>
      </button>
    </RouterLink>
    </td>
  </tr>
</tbody>


          </table>
        </div>
      </div>
    </div>

     <div v-if="!loading && !error && !experiences.length" class="text-center text-muted mt-4">
      <div class="d-flex justify-content-end">
          <RouterLink :to="`/employee/${employeeId}/nav/addExperience`">
            <button type="button" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i>
            Ajouter  
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
