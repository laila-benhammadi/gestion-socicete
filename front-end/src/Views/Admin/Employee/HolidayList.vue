<template>
    <div class="container mt-5">

      <h2 class="text-center mb-4">Liste des congé</h2>

      <div v-if="messageSuccess" class="alert alert-success text-center">
        {{ messageSuccess }}
      </div>
  
      <div v-if="loading" style="font-size: 20px; color: #007bff; text-align: center; font-weight: bold;">
        Chargement...
      </div>
   
      <div v-if="error" class="alert alert-danger text-center">
        {{ error }}
      </div>

      <div class="row">
      <div class="col-12 col-md-4">
      <div class="card shadow" style="min-height: 120px; border: none; border-radius: 15px; background-color: #fff;">
        <div class="card-body text-black">

          <div class="d-flex align-items-center mb-3">
            <div style="width: 30px; height: 30px; background-color: gray; display: flex; justify-content: center; align-items: center; border-radius: 8px; margin-right: 8px;">
              <i class="bi bi-people" style="font-size: 1rem;"></i>
            </div>
             <p class="card-title mb-0" style="font-weight: bold">{{  calculateHolidayDaysOnYear.total_holiday_days }}</p>
          </div>
          <p class="card-text"> Nombre total de congé.</p>
        </div>
      </div>
    </div>
      <div class="col-12 col-md-4  ">
        <div class="card shadow" style="min-height: 120px; border: none; border-radius: 15px; background-color: #fff;">
          <div class="card-body text-black">
            <div class="d-flex align-items-center mb-3">
              <div style="width: 30px; height: 30px; background-color: gray; display: flex; justify-content: center; align-items: center; border-radius: 8px; margin-right: 8px;">
                <i  class="bi bi-person-check" style="font-size: 1rem;"></i>
                      </div>
                      <p class="card-title mb-0" style="font-weight: bold">{{ calculateTotalHolidayDays.total_days }} </p>

            </div>
            <p class="card-text">  Jours de congé utilisés.</p>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4 ">
        <div class="card shadow" style="min-height: 120px; border: none; border-radius: 15px; background-color: #fff;">
          <div class="card-body text-black">
            <div class="d-flex align-items-center mb-3">
              <div style="width: 30px; height: 30px; background-color: gray; display: flex; justify-content: center; align-items: center; border-radius: 8px; margin-right: 8px;">
                <i class="bi bi-person" style="font-size: 1rem;"></i>

              </div>
              <p class="card-title mb-0" style="font-weight: bold"> {{ calculateRemainingHolidayDays.remaining_days }} </p>
            </div>
            <p class="card-text">  Jours restants de congé.</p>
          </div>
        </div>
      </div>

    </div>
  </div>

  
      <div v-if="!loading && holidays.length" class="mt-4">
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-dark text-white">
            <RouterLink :to="`/employee/${experienceId}/nav/addHoliday`"  class="text-white text-decoration-none">
              <i class="bi bi-plus-circle"></i>
              Ajouter autre
            </RouterLink>
          </button>
        </div>
        <!-- <div class="mt-4">
  <div class="card shadow-sm p-3">
    <h5 class="text-center mb-0">Total des jours de congé: {{ totalVacationDays }}</h5>
  </div>
</div> -->


  
        <div class=" shadow-sm p-4 mt-4">
          <div class="table-responsive">
              <table class="table table-bordered table-striped text-center align-middle">

              <thead >
                <tr>
                  <th >Type de congé</th>
                  <th > Desription </th>
                  <th  >Nombre du  Jours</th>
                  <th >Statut</th>
                  <th  >Date Debut</th>
                  <th  >Date Fin</th>
                  <th  >Certificat  </th>
                  <th >Modifier</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="holiday in holidays" :key="holiday.id">
                  <td>{{ holiday.type_vacancy }}</td> 
                  <td>{{ holiday.description }}</td> 
                  <td>{{ holiday.number_days }}</td> 
                  <td>
                     <span  class="badge text-bg-dark px-3 py-2" style="font-size: 12px; border-radius: 0.5rem;" > {{ holiday.status}} </span>
                  </td>
                  <td>{{ holiday.start_date }}</td> 
                  <td>{{ holiday.end_date || 'Employe(é) e vacance.' }}</td>

                  <td>
                  <a
                    v-if="holiday.certificate"
                    :href="`http://127.0.0.1:8000/storage/${holiday.certificate}`"
                    target="_blank"
                    class="btn btn-outline-dark btn-sm">
                    Voir le certificat
                  </a>
                  <span v-else class="text-muted fst-italic">Pas de certificat</span>
                  </td>
                  <td>
                      <RouterLink :to="`/employee/${experienceId}/nav/updateHoliday/${holiday.id}`" >
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
  
      <div v-if="!loading && !error && !holidays.length" class="text-center text-muted mt-4">
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-dark">
            <RouterLink :to="`/employee/${experienceId}/nav/addHoliday`"  class="text-white text-decoration-none">
              <i class="bi bi-plus-circle"></i>
              Ajouter congé
            </RouterLink>
          </button>
        </div>
        <p>Aucune congé trouvée.</p>
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
    const totalVacationDays = ref(0); 
    const loading = ref(false);
    const error = ref(null);
    const route = useRoute();
    const router = useRouter();
    const calculateHolidayDaysOnYear = ref(0);
    const calculateTotalHolidayDays = ref(0);
    const calculateRemainingHolidayDays = ref(0);  
   
    const messageSuccess = ref(route.query.success || null);

    const experienceId = ref(route.params.id);


    const getcalculateHolidayDaysOnYear = async () => {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/calculateHolidayDays/${experienceId.value}`);
    calculateHolidayDaysOnYear.value = response.data;
    console.log(response.data);
    
  } catch (err) {
    error.value = err.response?.data?.message || 'Une erreur s\'est produite lors de la récupération des données.';
    console.error(err);
  }
};

const getcalculateTotalHolidayDays = async () => {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/calculateTotalHolidayDays/${experienceId.value}`);
    calculateTotalHolidayDays.value = response.data;
    console.log(response.data);
    
  } catch (err) {
    error.value = err.response?.data?.message || 'Une erreur s\'est produite lors de la récupération des données.';
    console.error(err);
  }
};


const getcalculateRemainingHolidayDays = async () => {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/calculateRemainingHolidayDays/${experienceId.value}`);
    calculateRemainingHolidayDays.value = response.data;
    console.log(response.data);
    
  } catch (err) {
    error.value = err.response?.data?.message || 'Une erreur s\'est produite lors de la récupération des données.';
    console.error(err);
  }
};

 

    const fetchHolidays = async () => {
      loading.value = true;
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/showHoliday/${experienceId.value}`);
        holidays.value = response.data.holidays.sort((a, b) => b.id - a.id);

        // totalVacationDays.value = response.data.total_vacation_days;

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

    return { holidays, loading, error, experienceId, messageSuccess, totalVacationDays,calculateHolidayDaysOnYear, calculateTotalHolidayDays,calculateRemainingHolidayDays};
  }
};
</script>

  
<style scoped>

</style>
