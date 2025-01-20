<template>
  <div>
    <!-- Section d'information utilisateur (commentée pour l'instant) -->
    <!-- <h1 v-if="user">Welcome, {{ user.first_name }}</h1>
    <p v-if="user">Email: {{ user.email }}</p> -->
    <!-- <p v-else>Loading user data...</p> -->

    <div class="container">
      <button type="button" class="btn btn-primary-lg"></button>
      <div class="row">
        <div class="col-12 col-md-4">
          <div class="card shadow" style="min-height: 120px; border: none; border-radius: 15px; background-color: #fff;">
            <div class="card-body text-black">
              <div class="d-flex align-items-center mb-3">
                <div style="width: 30px; height: 30px; background-color: gray; display: flex; justify-content: center; align-items: center; border-radius: 8px; margin-right: 8px;">
                  <i class="bi bi-people" style="font-size: 1rem;"></i>
                </div>
                <p class="card-title mb-0" style="font-weight: bold">{{ activeEmployeeCount }}</p>
              </div>
              <p class="card-text">Les employés actifs dans l'entreprise.</p>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-4">
          <div class="card shadow" style="min-height: 120px; border: none; border-radius: 15px; background-color: #fff;">
            <div class="card-body text-black">
              <div class="d-flex align-items-center mb-3">
                <div style="width: 30px; height: 30px; background-color: gray; display: flex; justify-content: center; align-items: center; border-radius: 8px; margin-right: 8px;">
                  <i class="bi bi-person-check" style="font-size: 1rem;"></i>
                </div>
                <p class="card-title mb-0" style="font-weight: bold">{{ countEmployeesOnHoliday }}</p>
              </div>
              <p class="card-text">Les employés sont actuellement en congé.</p>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-4">
          <div class="card shadow" style="min-height: 120px; border: none; border-radius: 15px; background-color: #fff;">
            <div class="card-body text-black">
              <div class="d-flex align-items-center mb-3">
                <div style="width: 30px; height: 30px; background-color: gray; display: flex; justify-content: center; align-items: center; border-radius: 8px; margin-right: 8px;">
                  <i class="bi bi-person" style="font-size: 1rem;"></i>
                </div>
                <p class="card-title mb-0" style="font-weight: bold">{{ countStagiaires }}</p>
              </div>
              <p class="card-text">Les stagiaires en cours de formation.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <h2 class="text-center mb-4">Les employés en congé</h2>

      <!-- Table des employés en congé -->
      <table class="table table-bordered table-striped text-center align-middle">
        <thead>
          <tr>
            <th scope="col">Nom</th>
            <th scope="col">Prenom</th>
            <th scope="col">Email</th>
          
          </tr>
        </thead>
        <tbody>
          <tr v-if="isLoading">
            <td colspan="6" class="text-center">Chargement des données...</td>
          </tr>
          <tr v-if="employeesOnHoliday.length === 0 && !isLoading">
            <td colspan="6" class="text-center">Aucune personne en congé</td>
          </tr>
          <tr v-for="employee in employeesOnHoliday" :key="employee.id">
            <td>{{ employee.first_name }}</td>
            <td>{{ employee.last_name }}</td>
            <td>{{ employee.email }}</td>
           
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const isLoading = ref(true);  
const employeesOnHoliday = ref([]);
const activeEmployeeCount = ref(0);
const countEmployeesOnHoliday = ref(0);
const countStagiaires = ref(0);

const fetchEmployeesOnHoliday = async () => {
  try {
    console.log('Fetching employees on holiday...');
    const response = await axios.get('http://127.0.0.1:8000/api/getEmployeesOnHoliday');
    console.log('Employees on holiday:', response.data);
    employeesOnHoliday.value = response.data;
  } catch (error) {
    console.error('Error fetching employees on holiday:', error);
  } finally {
    isLoading.value = false;
  }
};



const fetchActiveEmployeeCount = async () => {
  try {
    console.log('Fetching active employee count...');
    const response = await axios.get('http://127.0.0.1:8000/api/getEmployeeActif');
    console.log('Active employees count:', response.data);
    if (response.data && response.data.data) {
      activeEmployeeCount.value = response.data.data;
    }
  } catch (error) {
    console.error('Error fetching active employees count:', error);
  }
};

const fetchcountEmployeesOnHoliday = async () => {
  try {
    console.log('Fetching count of employees on holiday...');
    const response = await axios.get('http://127.0.0.1:8000/api/countEmployeesOnHoliday');
    console.log('Employees on holiday count:', response.data);
    if (response.data && response.data.data) {
      countEmployeesOnHoliday.value = response.data.data;
    }
  } catch (error) {
    console.error('Error fetching count of employees on holiday:', error);
  }
};

const fetchCountStagiaire = async () => {
  try {
    console.log('Fetching count of stagiaires...');
    const response = await axios.get('http://127.0.0.1:8000/api/countStagiaires');
    console.log('Stagiaires count:', response.data);
    if (response.data && response.data.data) {
      countStagiaires.value = response.data.data;
    }
  } catch (error) {
    console.error('Error fetching stagiaires count:', error);
  }
};

onMounted(() => {
  console.log('Component mounted');
  fetchEmployeesOnHoliday();
  fetchActiveEmployeeCount();
  fetchcountEmployeesOnHoliday();
  fetchCountStagiaire();
});
</script>

<style scoped>
.col-12 {
  margin-bottom: 15px;
}
.row {
  margin-top: 80px;
  margin-bottom: 60px;
}
</style>
