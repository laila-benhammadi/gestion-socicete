<template>
  <div class="container mt-5">
    <div class="card shadow-sm p-4" v-if="employee && !loading">
      <div class="row">
        <nav class="secondary-nav">
          <ul>
            <li v-if="employee.id"><RouterLink :to="`/employee/${employee.id}/nav/`">Infos</RouterLink> </li>
            <li v-if="employee.id"><RouterLink :to="`/employee/${employee.id}/nav/experience`">Expériences</RouterLink> </li>
            <li v-if="employee.id"><RouterLink :to="`/employee/${employee.id}/nav/bank`">Banque</RouterLink> </li>
            <!-- <li v-if="employee.id"><RouterLink :to="`/employee/${employee.id}/nav/holiday`">Congé</RouterLink> </li> -->
            <li  v-if="employee.id"><RouterLink :to="`/employee/${employee.id}/salaries`">
             Les salaires
          </RouterLink>
           </li>   
          </ul>
        </nav>
        <router-view />
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { RouterLink, useRoute } from 'vue-router';

export default {
  name: 'EmployeeDetails',
  setup() {
    const employee = ref({}); 
    const loading = ref(false);
    const error = ref(null);
    const route = useRoute();  

    const fetchEmployeeDetails = async () => {
      loading.value = true;
      const employeeId = route.params.id;  
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/getEmployeeDetails/${employeeId}`);
        employee.value = response.data.employee; 
      } catch (err) {
        error.value = err.message;
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      fetchEmployeeDetails();
    });

    return { employee, loading, error };
  }
};
</script>

<style scoped>
.secondary-nav {
  background-color: #ffffff;
  padding: 15px;
  border-bottom: 1px solid #ddd;
}
.secondary-nav ul {
  list-style: none;
  display: flex;
  gap: 15px;
  padding: 0;
  margin: 0;
}
.secondary-nav li {
  font-size: 17px;
  flex: 1;  
  text-align: center; 
  position: relative;  }
.secondary-nav a {
  text-decoration: none;
  color: #0a2542;
}
.secondary-nav a:hover {
  text-decoration: underline;
}
</style>
