<template>
    <div class="container mt-5">
      <div class=" p-4" v-if="employee && !loading">
        <div class="row">
    

           <div class="col-md-4 mb-4">
             <ul class="list-unstyled mb-3">      
              <li><strong style="color: rgb(16, 23, 83); text-transform: uppercase;">{{ (employee.first_name + ' ' + employee.last_name).toUpperCase() }}</strong></li>
              <li><strong style="color: rgb(16, 23, 83); text-transform: uppercase;">Email personnel :</strong> {{ employee.personal_email }} </li>
              <li><strong style="color: rgb(16, 23, 83); text-transform: uppercase;">Numéro téléphone :</strong> {{ employee.phone_number }}</li>
              <li><strong style="color: rgb(16, 23, 83); text-transform: uppercase;">Ne(é) à :</strong> {{ employee.date_of_birth }}</li>
              <li><strong style="color: rgb(16, 23, 83); text-transform: uppercase;">Genre:</strong> {{ employee.gender }}</li>
              <li><strong style="color: rgb(16, 23, 83); text-transform: uppercase;">Situation familiale:</strong> {{ employee.family_situation || 'Non renseignée' }}</li>
              <li><strong style="color: rgb(16, 23, 83); text-transform: uppercase;">Nationalité:</strong> {{ employee.nationality }}</li>
            </ul>
          </div>
  
           <div class="col-md-4 mb-4">
            <h5 class="text-info"></h5>
            <ul class="list-unstyled">
                <li><strong style="color: rgb(16, 23, 83); text-transform: uppercase;">Email profisionnelle  :</strong> {{ employee.email || 'Non renseigné' }}</li>
                <li><strong style="color: rgb(16, 23, 83); text-transform: uppercase;">Carte d'Identité Nationale (CIN)  :</strong> {{ employee.cin || 'Non renseigné' }}</li>
                <li><strong style="color: rgb(16, 23, 83); text-transform: uppercase;">hiring_date :</strong> {{ employee.hiring_date || 'Non renseigné' }}</li>
                <li><strong style="color: rgb(16, 23, 83); text-transform: uppercase;">Adresse :</strong> {{ employee.address  || 'Non renseigné'}}</li>
                <li><strong style="color: rgb(16, 23, 83); text-transform: uppercase;">Ville :</strong> {{ employee.city || 'Non renseigné' }}</li>
                <li><strong style="color: rgb(16, 23, 83); text-transform: uppercase;">  Pays :</strong> {{ employee.contry || 'Non renseigné' }}</li>
           
             
            </ul>
          </div>
          <div class="col-md-4 mb-4 text-center">
  <div class="card shadow-sm" style="width: 90%; max-width: 300px; margin: 0 auto;">
     <div style="height: 70%; background-color: #f8f9fa;">
       <img
       v-if="employee.work_files && employee.work_files.picture"
        :src="`http://127.0.0.1:8000/storage/${employee.work_files.picture}`"
        alt="Photo de l'employé"
        class="img-fluid"
        style="width: 100%; height: 100%; object-fit: cover;">
       <div
        v-else
        class="d-flex justify-content-center align-items-center text-muted"
        style="height: 100%; width: 100%; font-size: 16px; color: #6c757d;">
        Aucune image
      </div>
    </div>
  </div> 
</div>
                   <a v-if="employee.work_files"
                    :href="`http://127.0.0.1:8000/storage/${employee.work_files.cin_file}`"
                    target="_blank"
                    class="btn btn-outline-dark btn-lg mb-3">
                  Voir cin
                     </a>
                     
                     <a v-if="employee.work_files"
                    :href="`http://127.0.0.1:8000/storage/${employee.work_files.cv_file}`"
                    target="_blank"
                    class="btn btn-outline-dark btn-lg mb-3">
                  Voir cv
                     </a>
                     
                     <a v-if="employee.work_files"
                    :href="`http://127.0.0.1:8000/storage/${employee.work_files.document_file}`"
                    target="_blank"
                    class="btn btn-outline-dark btn-lg mb-3">
                  Voir document
                     </a>

        </div>
       
       
      </div>
    </div>
  </template>
<script>

import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

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
  font-size: 14px;
}
.secondary-nav a {
  text-decoration: none;
  color: #007bff;
}
.secondary-nav a:hover {
  text-decoration: underline;
}
</style>
