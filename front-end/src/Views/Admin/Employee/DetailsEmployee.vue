<template>
  <div class="container py-5">

    <!-- Header Profil -->
    <div
      class="profile-header p-4 rounded shadow-sm mb-4 d-flex align-items-center flex-column flex-md-row"
    >
      <div class="profile-avatar d-flex align-items-center justify-content-center me-3 mb-3 mb-md-0">
        <i class="fas fa-user text-white fs-2"></i>
      </div>
      <div class="text-center text-md-start">
        <h1 class="mb-1">
          {{ (employee.first_name + ' ' + employee.last_name).toUpperCase() }}
        </h1>
        <p class="mb-0 opacity-75">Profil Employé</p>
      </div>
    </div>

    <!-- Spinner de chargement -->
    <div v-if="loading" class="text-center my-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Chargement...</span>
      </div>
    </div>

    <div v-else>

      <!-- Message d'erreur -->
      <div v-if="error" class="alert alert-danger">
        Erreur lors du chargement des informations : {{ error }}
      </div>

      <!-- Contenu principal -->
      <div v-else class="row g-4 align-items-stretch">

        <!-- Informations personnelles -->
        <div class="col-12 col-lg-4 d-flex">
          <div class="info-card p-4 rounded shadow-sm bg-white w-100 h-100">
            <div class="mb-3 border-bottom pb-2 d-flex align-items-center">
              <i class="fas fa-user-circle icon-header icon-blue me-2"></i>
              <h2 class="h5 m-0">Informations Personnelles</h2>
            </div>

            <div class="info-item d-flex align-items-center mb-3">
              <i class="fas fa-envelope info-icon me-3 text-secondary"></i>
              <div>
                <p class="mb-1 small text-secondary">Email Personnel</p>
                <p class="mb-0">{{ employee.personal_email || 'Non renseigné' }}</p>
              </div>
            </div>

            <div class="info-item d-flex align-items-center mb-3">
              <i class="fas fa-phone info-icon me-3 text-secondary"></i>
              <div>
                <p class="mb-1 small text-secondary">Téléphone</p>
                <p class="mb-0">{{ employee.phone_number || 'Non renseigné' }}</p>
              </div>
            </div>

            <div class="info-item d-flex align-items-center mb-3">
              <i class="fas fa-birthday-cake info-icon me-3 text-secondary"></i>
              <div>
                <p class="mb-1 small text-secondary">Date de naissance</p>
                <p class="mb-0">{{ employee.date_of_birth || 'Non renseignée' }}</p>
              </div>
            </div>

            <div class="info-item d-flex align-items-center mb-3">
              <i class="fas fa-venus-mars info-icon me-3 text-secondary"></i>
              <div>
                <p class="mb-1 small text-secondary">Genre</p>
                <p class="mb-0">{{ employee.gender || 'Non renseigné' }}</p>
              </div>
            </div>

            <div class="info-item d-flex align-items-center mb-3">
              <i class="fas fa-home info-icon me-3 text-secondary"></i>
              <div>
                <p class="mb-1 small text-secondary">Situation familiale</p>
                <p class="mb-0">{{ employee.family_situation || 'Non renseignée' }}</p>
              </div>
            </div>

            <div class="info-item d-flex align-items-center">
              <i class="fas fa-flag info-icon me-3 text-secondary"></i>
              <div>
                <p class="mb-1 small text-secondary">Nationalité</p>
                <p class="mb-0">{{ employee.nationality || 'Non renseignée' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Informations professionnelles -->
        <div class="col-12 col-lg-4 d-flex">
          <div class="info-card p-4 rounded shadow-sm bg-white w-100 h-100">
            <div class="mb-3 border-bottom pb-2 d-flex align-items-center">
              <i class="fas fa-briefcase icon-header icon-green me-2"></i>
              <h2 class="h5 m-0">Informations Professionnelles</h2>
            </div>

            <div class="info-item d-flex align-items-center mb-3">
              <i class="fas fa-at info-icon me-3 text-secondary"></i>
              <div>
                <p class="mb-1 small text-secondary">Email Professionnel</p>
                <p class="mb-0">{{ employee.email || 'Non renseigné' }}</p>
              </div>
            </div>

            <div class="info-item d-flex align-items-center mb-3">
              <i class="fas fa-id-card info-icon me-3 text-secondary"></i>
              <div>
                <p class="mb-1 small text-secondary">CIN</p>
                <p class="mb-0">{{ employee.cin || 'Non renseigné' }}</p>
              </div>
            </div>

            <div class="info-item d-flex align-items-center mb-3">
              <i class="fas fa-calendar-alt info-icon me-3 text-secondary"></i>
              <div>
                <p class="mb-1 small text-secondary">Date d'embauche</p>
                <p class="mb-0">{{ employee.hiring_date || 'Non renseignée' }}</p>
              </div>
            </div>

            <div class="info-item d-flex align-items-center mb-3">
              <i class="fas fa-map-marker-alt info-icon me-3 text-secondary"></i>
              <div>
                <p class="mb-1 small text-secondary">Adresse</p>
                <p class="mb-0">{{ employee.address || 'Non renseignée' }}</p>
              </div>
            </div>

            <div class="info-item d-flex align-items-center">
              <i class="fas fa-city info-icon me-3 text-secondary"></i>
              <div>
                <p class="mb-1 small text-secondary">Ville</p>
                <p class="mb-0">{{ employee.city || 'Non renseignée' }}</p>
              </div>
            </div>

            <div class="info-item d-flex align-items-center">
              <i class="fas fa-flag-usa info-icon me-3 text-secondary"></i>
              <div>
                <p class="mb-1 small text-secondary">Pays</p>
                <p class="mb-0">{{ employee.contry || 'Non renseigné' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Photo et documents -->
        <div class="col-12 col-lg-4 d-flex">
          <div class="info-card p-4 rounded shadow-sm bg-white w-100 h-100">

            <div class="mb-3 border-bottom pb-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-camera icon-header icon-purple me-2"></i>
              <h2 class="h5 m-0">Photo & Documents</h2>
            </div>

            <div class="photo-placeholder mb-4">
              <img
                v-if="employee.work_files && employee.work_files.picture"
                :src="`http://127.0.0.1:8000/storage/${employee.work_files.picture}`"
                alt="Photo de profil"
                class="img-fluid rounded"
                style="max-height: 300px; width: auto; margin: 0 auto;"
              />
              <div
                v-else
                class="text-muted fs-4 d-flex flex-column align-items-center justify-content-center"
                style="height: 300px;"
              >
                <i class="fas fa-user-circle"></i>
                <small>Aucune image</small>
              </div>
            </div>

            <div class="d-grid gap-3">
              <a
                v-if="employee.work_files && employee.work_files.cin_file"
                :href="`http://127.0.0.1:8000/storage/${employee.work_files.cin_file}`"
                target="_blank"
                class="btn btn-primary d-flex align-items-center justify-content-center"
                download
              >
                <i class="fas fa-download me-2"></i> Télécharger la CIN
              </a>

              <a
                v-if="employee.work_files && employee.work_files.cv_file"
                :href="`http://127.0.0.1:8000/storage/${employee.work_files.cv_file}`"
                target="_blank"
                class="btn btn-success d-flex align-items-center justify-content-center"
                download
              >
                <i class="fas fa-download me-2"></i> Télécharger le CV
              </a>

              <a
                v-if="employee.work_files && employee.work_files.document_file"
                :href="`http://127.0.0.1:8000/storage/${employee.work_files.document_file}`"
                target="_blank"
                class="btn btn-warning d-flex align-items-center justify-content-center"
                download
              >
                <i class="fas fa-download me-2"></i> Télécharger le document
              </a>
            </div>

          </div>
        </div>

      </div>
    </div>

    <!-- Router view pour sous-pages éventuelles -->
    <router-view />
    
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
        const response = await axios.get(
          `http://127.0.0.1:8000/api/getEmployeeDetails/${employeeId}`
        );
        employee.value = response.data.employee;
        error.value = null;
      } catch (err) {
        error.value =
          err.response?.data?.message || err.message || 'Erreur inconnue';
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => fetchEmployeeDetails());

    return { employee, loading, error };
  },
};
</script>

<style scoped>
.profile-header {
  background: linear-gradient(135deg, #020924 0%, #323c6b 100%);
  color: white;
}

.profile-avatar {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, #3b82f6, #8b5cf6);
  border-radius: 50%;
  font-size: 2rem;
  color: white;
}

.info-card {
  transition: box-shadow 0.3s ease, transform 0.3s ease;
}

.info-card:hover {
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  transform: translateY(-5px);
}

.icon-header {
  font-size: 1.5rem;
}

.icon-blue {
  color: #3b82f6;
}

.icon-green {
  color: #10b981;
}

.icon-purple {
  color: #8b5cf6;
}

.photo-placeholder {
  background-color: #f3f4f6;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 300px;
  color: #9ca3af;
  font-size: 4rem;
}

.info-icon {
  width: 20px;
}
</style>
