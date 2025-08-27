<template>
  <div class="container mt-4">

    <!-- Titre -->
    <h2 class="text-center mb-4 text-dark">
      <i class="bi bi-bank me-2"></i> Banques
    </h2>

    <!-- Messages de succès et d'erreur -->
    <div v-if="messageSuccess" class="alert alert-success text-center">{{ messageSuccess }}</div>
    <div v-if="errorAdd" class="alert alert-danger text-center">{{ errorAdd }}</div>

    <!-- Loading et erreurs -->
    <div v-if="loading" class="loading-container">
      <div class="spinner"></div>
    </div>
    <div v-else-if="error" class="alert alert-danger text-center">{{ error }}</div>

    <!-- Liste des banques -->
    <div v-if="!loading && banks.length" class="mt-4">

      <!-- Bouton Ajouter -->
      <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAddBank">
          <i class="bi bi-plus-circle"></i> Ajouter autre
        </button>
      </div>

      <!-- Tableau des banques -->
      <div class="p-4">
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-center align-middle mt-4">
            <thead>
              <tr>
                <th scope="col">Nom de la banque</th>
                <th scope="col">RIB</th>
                <th scope="col">Modifier</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="bank in banks" :key="bank.id">
                <td>{{ bank.bank_name }}</td>
                <td>{{ bank.rib }}</td>
                <td>
                  <button
                    type="button"
                    class="btn btn-dark"
                    data-bs-toggle="modal"
                    data-bs-target="#modalUpdateBank"
                    @click="openUpdatebank(bank)"
                  >
                    <i class="bi bi-pencil-square"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Message si aucune banque -->
    <div v-if="!loading && !error && !banks.length" class="text-center text-muted mt-4">
      <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalAddBank">
          <i class="bi bi-plus-circle"></i> Ajouter
        </button>
      </div>
      <p>Aucune banque trouvée.</p>
    </div>

    <!-- Modal Ajouter une banque -->
    <div class="modal fade" id="modalAddBank" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-black">Ajouter une banque</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <label for="bank_name" class="form-label">Nom de la banque :</label>
            <input type="text" v-model="bank_name" id="bank_name" class="form-control" required />

            <label for="rib" class="form-label">RIB :</label>
            <input type="text" v-model="rib" id="rib" class="form-control" required />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="addBank">
              Ajouter
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Modifier une banque -->
    <div class="modal fade" id="modalUpdateBank" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Modifier la banque</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <label for="update_bank_name" class="form-label">Nom de la banque :</label>
            <input
              type="text"
              v-model="selectedBank.bank_name"
              id="update_bank_name"
              class="form-control"
              required
            />

            <label for="update_rib" class="form-label">RIB :</label>
            <input
              type="text"
              v-model="selectedBank.rib"
              id="update_rib"
              class="form-control"
              required
            />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="updateBank">
              Modifier
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

export default {
  name: 'ListBank',
  setup() {
    const banks = ref([]);
    const loading = ref(false);
    const error = ref(null);
    const errorAdd = ref(null);
    const messageSuccess = ref(null);

    const bank_name = ref('');
    const rib = ref('');
    const selectedBank = ref({ id: null, bank_name: '', rib: '' });

    const route = useRoute();
    const employeeId = route.params.id;

    // Récupérer la liste des banques
    const fetchBanksEmployee = async () => {
      loading.value = true;
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/showBank/${employeeId}`);
        banks.value = response.data.sort((a, b) => b.id - a.id);
      } catch {
        error.value = 'Erreur lors du chargement des banques.';
      } finally {
        loading.value = false;
      }
    };

    // Ajouter une banque
    const addBank = async () => {
      if (!bank_name.value || !rib.value) {
        errorAdd.value = 'Veuillez remplir tous les champs.';
        return;
      }
      const newBank = { user_id: employeeId, bank_name: bank_name.value, rib: rib.value };
      try {
        const response = await axios.post(`http://127.0.0.1:8000/api/addBank/${employeeId}`, newBank);
        banks.value.unshift(response.data.bank);
        messageSuccess.value = 'Banque ajoutée avec succès';
        bank_name.value = '';
        rib.value = '';
        errorAdd.value = null;

        setTimeout(() => (messageSuccess.value = null), 3000);
      } catch {
        errorAdd.value = 'Erreur lors de l\'ajout de la banque.';
      }
    };

    // Préparer la banque à modifier
    const openUpdatebank = (bank) => {
      console.log('Selected Bank:', bank);
      selectedBank.value = { ...bank };
    };

    // Modifier la banque
    const updateBank = async () => {
      if (!selectedBank.value || !selectedBank.value.id) {
        error.value = 'Aucune banque sélectionnée pour la mise à jour.';
        return;
      }
      try {
        const response = await axios.put(
          `http://127.0.0.1:8000/api/updateBank/${selectedBank.value.id}`,
          selectedBank.value
        );
        const index = banks.value.findIndex((bank) => bank.id === selectedBank.value.id);
        if (index !== -1) banks.value[index] = response.data.bank;

        messageSuccess.value = 'Banque mise à jour avec succès';
        errorAdd.value = null;
        setTimeout(() => (messageSuccess.value = null), 3000);

        fetchBanksEmployee();
      } catch {
        error.value = 'Erreur lors de la mise à jour de la banque.';
      }
    };

    onMounted(fetchBanksEmployee);

    return {
      banks,
      loading,
      error,
      errorAdd,
      messageSuccess,
      bank_name,
      rib,
      selectedBank,
      addBank,
      updateBank,
      openUpdatebank,
    };
  },
};
</script>
