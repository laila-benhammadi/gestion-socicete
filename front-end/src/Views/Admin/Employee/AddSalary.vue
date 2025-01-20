<template>
    <div class="container mt-5">
      <h2 class="text-center mb-4">Ajouter un Salaire</h2>
  
       <div v-if="successMessage" class="alert alert-success text-center">
        {{ successMessage }}
      </div>
  
       <div v-if="error" class="alert alert-danger text-center">
        {{ error }}
      </div>
  
       <form @submit.prevent="submitSalary">
        <div class="mb-3">
          <label for="netAmount" class="form-label">Montant Net (DH) :</label>
          <input
            type="number"
            v-model="salary.net_amount"
            id="netAmount"
            class="form-control"
            required
          />
        </div>
  
        <div class="mb-3">
          <label for="grossAmount" class="form-label">Montant Supplémentaire Brut (DH) :</label>
          <input
            type="number"
            v-model="salary.gross_amount"
            id="grossAmount"
            class="form-control"
          />
        </div>
  
        <div class="mb-3">
          <label for="salaryDate" class="form-label">Date :</label>
          <input
            type="date"
            v-model="salary.salary_date"
            id="salaryDate"
            class="form-control"
            required
          />
        </div>
        <div class="mb-3">
        <label for="bank_id" class="form-label">Banque:</label>
        <select v-model="salary.bank_id" class="form-select" id="bank_id" required>
         <option value="" disabled selected>Choisir une Banque</option>
        <option v-for="bank in banks" :key="bank.id" :value="bank.id">
           {{bank.bank_name}}
         </option>
        </select>



      </div>
  
        <button type="submit" class="btn btn-success">Ajouter</button>
       
      </form>
    </div>
  </template>
  
  <script>
import { ref, onMounted } from 'vue';   
import { useRoute, useRouter } from 'vue-router';
  import axios from 'axios';
  
  export default {
    name: 'AddSalary',
    setup() {
      const route = useRoute();
      const router = useRouter();
      const banks = ref ([]);
      const employeeId = route.params.employeeId;
      const experienceId = route.params.experienceId; 
   
      const salary = ref({
        net_amount: '',
        gross_amount: '',
        salary_date: '',
        bank_id: '',
      });
  
      const successMessage = ref('');
      const error = ref(null);
  
      const getBank = async () => {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/showBank/${employeeId}`);
    console.log('Response ', response.data);     
    banks.value = response.data;       
  } catch (error) {
    console.error('Error fetching bank details:', error);
  }
};



       


      const submitSalary = async () => {
        try {
          const response = await axios.post(
            `http://127.0.0.1:8000/api/experience/${experienceId}/salary`,
            {
              net_amount: salary.value.net_amount,
              gross_amount: salary.value.gross_amount || 0,
              salary_date: salary.value.salary_date,
              bank_id: salary.value.bank_id,
              user_id: employeeId,
            },
            {
              headers: {
                'Content-Type': 'application/json',
              },
            }
          );
  
          successMessage.value = response.data.message;
          error.value = null;
  
          setTimeout(() => {
            router.push(`/employee/${employeeId}/nav/experience`);
          }, 2000);
        } catch (err) {
          console.error('Erreur lors de la requête :', err);
          error.value = 'Erreur lors de l\'ajout du salaire. Veuillez vérifier les données saisies ou réessayer plus tard.';
        }
      };


      onMounted(() => {
      getBank(employeeId);
    });
  
      return {
        salary,
        successMessage,
        error,
        submitSalary,
        employeeId,
        getBank,
        banks,
        
      };
    },
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 600px;
    margin: auto;
  }
  </style>
  