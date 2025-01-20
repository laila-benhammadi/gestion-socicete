<template>
    <div>
      <h3>Salaires pour l'expérience</h3>
      <div v-if="loading">Chargement des salaires...</div>
      <div v-else>
        <table v-if="salaries.length">
          <thead>
            <tr>
              <th>Date</th>
              <th>Salaire brut</th>
              <th>Salaire net</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="salary in salaries" :key="salary.id">
              <td>{{ salary.date }}</td>
              <td>{{ salary.gross_amount }}</td>
              <td>{{ salary.net_amount }}</td>
            </tr>
          </tbody>
        </table>
        <div v-else>
          <p>Aucun salaire trouvé pour cette expérience.</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRoute } from 'vue-router';
  
  export default {
    name: 'ExperienceSalaryList',
    setup() {
      const salaries = ref([]);
      const loading = ref(false);
      const route = useRoute();
  
      const fetchSalaries = async () => {
        loading.value = true;
        const experienceId = route.params.id;
        try {
          const response = await axios.get(`http://127.0.0.1:8000/api/experience/${experienceId}/salaries`);
          salaries.value = response.data.salaries;
        } catch (error) {
          console.error('Erreur lors du chargement des salaires :', error);
        } finally {
          loading.value = false;
        }
      };
  
      onMounted(() => {
        fetchSalaries();
      });
  
      return { salaries, loading };
    },
  };
  </script>
  
  <style scoped>
  table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
  }
  table, th, td {
    border: 1px solid #ddd;
  }
  th, td {
    padding: 8px;
    text-align: left;
  }
  th {
    background-color: #f2f2f2;
  }
  </style>
  