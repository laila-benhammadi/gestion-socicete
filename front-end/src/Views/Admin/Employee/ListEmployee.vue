<template>
    <div class="container mt-5 mb-4">
      <h2 class="text-center mb-4">Liste des employée</h2>
  
      <button class="btn btn-dark mt-4">
  <RouterLink :to="`/addEmployee`" class="text-white text-decoration-none">
    Ajouter Employee
  </RouterLink>
</button>
      <div v-if="$route.query.success" class="alert alert-success" role="alert">
        {{ $route.query.success }}
      </div>

      <div v-if="loading" style="font-size: 20px; color: #007bff; text-align: center; font-weight: bold;">
        Chargement...
      </div>
  
      <table class="table table-bordered table-striped text-center align-middle mt-4" v-if="!loading && employees.length">
 
        <thead >
          <tr>
          <th scope="col">Nom </th>
          <th scope="col">Email</th>
          <th scope="col">CIN</th>
          <th scope="col">Date de Début</th>
          <th scope="col">Infos</th>
        </tr>
        </thead>
        <tbody>
          <tr v-for="employee in employees" :key="employee.id">
            <td>{{ employee.first_name }} {{ employee.last_name }}</td>
            <td>{{ employee.email }}</td>
            <td>{{ employee.cin }}</td>
            <td>{{ employee.hiring_date }}</td>
            <td>
              <RouterLink :to="`/employee/${employee.id}/nav`">
                <i class="bi bi-person-vcard" style="font-size: 25px; color: black;"></i>
              </RouterLink>
             
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from 'vue';
  import axios from 'axios'; 
  import { useRouter } from 'vue-router'; 
  export default {
    name: 'ListEmployee',
    setup() {
      const employees = ref([]);
      const loading = ref(false);
      const error = ref(null);
  
      const fetchEmployees = async () => {
        loading.value = true;
        try {
          const response = await axios.get('http://127.0.0.1:8000/api/admin/getEmployee');
          employees.value = response.data;
        } catch (err) {
          error.value = err.message;
        } finally {
          loading.value = false;
        }
      };
  
      
  
      onMounted(() => {
        fetchEmployees();
      });
  
      return { employees, loading, error };
    }
  };
  </script>
  