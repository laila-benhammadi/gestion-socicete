<template>
  <div id="app">
    <div class="container mt-4 shadow-sm">
      <h2 class="mb-5 text-center text-dark">
        <i class="bi bi-person-plus"></i>
        Formulaire d'inscription
      </h2>

      <!-- Messages -->
      <div v-if="successMessage" class="alert alert-success" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ successMessage }}
      </div>
      <div v-if="errorMessage" class="alert alert-danger" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ errorMessage }}
      </div>

      <!-- Formulaire -->
      <form @submit.prevent="handleAdd">
        <div class="row">

          <!-- Left Column -->
          <div class="col-md-4">
            <div class="mb-3">
              <label for="last_name" class="form-label"><i class="fas fa-user me-1"></i> Nom:</label>
              <input type="text" id="last_name" class="form-control" v-model="last_name" required />
            </div>

            <div class="mb-3">
              <label for="personal_email" class="form-label"><i class="fas fa-envelope me-1"></i> Email personnel:</label>
              <div class="input-group mb-3">
                <input type="text" id="email" class="form-control" placeholder="email" v-model="per_email" required />
                <span class="input-group-text">@gmail.com</span>
              </div>
            </div>

            <div class="mb-3">
              <label for="date_of_birth" class="form-label"><i class="fas fa-calendar me-1"></i> Date de naissance:</label>
              <input type="date" id="date_of_birth" class="form-control" v-model="date_of_birth" required />
            </div>

            <div class="mb-3">
              <label for="family_situation" class="form-label"><i class="fas fa-heart me-1"></i> Situation familiale:</label>
              <select id="family_situation" class="form-select" v-model="family_situation" required>
                <option value="Célibataire">Célibataire</option>
                <option value="Marié(e)">Marié(e)</option>
                <option value="Divorcé(e)">Divorcé(e)</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="address" class="form-label"><i class="fas fa-home me-1"></i> Adresse:</label>
              <input type="text" id="address" class="form-control" v-model="address" required />
            </div>

            <div class="mb-3">
              <label for="nationality" class="form-label"><i class="fas fa-flag me-1"></i> Nationalité:</label>
              <input type="text" id="nationality" class="form-control" v-model="nationality" required />
            </div>

            <div class="mb-3">
              <label for="cin" class="form-label"><i class="fas fa-id-card me-1"></i> CIN:</label>
              <input type="text" id="cin" class="form-control" v-model="cin" required />
            </div>

            <div class="mb-3">
              <label for="cv_file" class="form-label"><i class="fas fa-file-pdf me-1"></i> CV (PDF):</label>
              <input type="file" class="form-control" id="cv_file" @change="handleFileChange($event, 'cvFile')" accept=".pdf" />
            </div>
          </div>

          <!-- Middle Column -->
          <div class="col-md-4">
            <div class="mb-3">
              <label for="first_name" class="form-label"><i class="fas fa-user me-1"></i> Prénom:</label>
              <input type="text" id="first_name" class="form-control" v-model="first_name" required />
            </div>

            <div class="mb-3">
              <label for="email" class="form-label"><i class="fas fa-briefcase me-1"></i> Email professionnel:</label>
              <div class="input-group mb-3">
                <input type="text" id="email" class="form-control" placeholder="email" v-model="emailP" required />
                <span class="input-group-text">@societe.com</span>
              </div>
            </div>

            <div class="mb-3">
              <label for="gender" class="form-label"><i class="fas fa-venus-mars me-1"></i> Genre:</label>
              <select id="gender" class="form-select" v-model="gender" required>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label"><i class="fas fa-phone me-1"></i> Téléphone:</label>
              <div class="input-group mb-3">
                <span class="input-group-text">+212</span>
                <input type="tel" id="phone" class="form-control" placeholder="612345678" v-model="phoneN" required />
              </div>
            </div>

            <div class="mb-3">
              <label for="city" class="form-label"><i class="fas fa-city me-1"></i> Ville:</label>
              <input type="text" id="city" class="form-control" v-model="city" required />
            </div>

            <div class="mb-3">
              <label for="contry" class="form-label"><i class="fas fa-globe me-1"></i> Pays:</label>
              <input type="text" id="contry" class="form-control" v-model="contry" required />
            </div>

            <div class="mb-3">
              <label for="hiring_date" class="form-label"><i class="fas fa-calendar-check me-1"></i> Date d'embauche:</label>
              <input type="date" id="hiring_date" class="form-control" v-model="hiring_date" required />
            </div>

            <div class="mb-3">
              <label for="cin_file" class="form-label"><i class="fas fa-file-pdf me-1"></i> CIN (PDF/IMAGE):</label>
              <input type="file" class="form-control" id="cin_file" @change="handleFileChange($event, 'cinFile')" accept=".pdf,image/*" />
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-md-4">
            <div class="mb-3">
              <label for="post" class="form-label"><i class="fas fa-briefcase me-1"></i> Poste:</label>
              <select id="post" class="form-select" v-model="job_title" required>
                <option value="" disabled selected>Choisir un Poste</option>
                <option value="Développeur Front-End">Développeur Front-End</option>
                <option value="Développeur Back-End">Développeur Back-End</option>
                <option value="Développeur Full-Stack">Développeur Full-Stack</option>
                <option value="Développeur Mobile (iOS/Android)">Développeur Mobile (iOS/Android)</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="contract_type" class="form-label"><i class="fas fa-file-contract me-1"></i> Type de contrat:</label>
              <select id="contract_type" class="form-select" v-model="type_contract" required>
                <option value="" disabled selected>Choisir un Type de contrat</option>
                <option value="CDD">CDD</option>
                <option value="CDI">CDI</option>
                <option value="Stage">Stage</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="start_date" class="form-label"><i class="fas fa-play-circle me-1"></i> Date de début:</label>
              <input type="date" id="start_date" class="form-control" v-model="start_date" required />
            </div>

            <div class="mb-3">
              <label for="end_date" class="form-label"><i class="fas fa-stop-circle me-1"></i> Date de fin:</label>
              <input type="date" id="end_date" class="form-control" v-model="end_date" />
            </div>

            <!-- Upload Photo -->
            <div class="border rounded shadow-sm mb-3 p-3 bg-light">
              <div class="image-preview-container text-center" :class="{'image-selected': imageUrl}">
                <div v-if="imageUrl">
                  <img :src="imageUrl" alt="Image Preview" class="img-thumbnail img-fluid" />
                </div>
                <div v-else class="empty-image-placeholder">
                  <i class="fas fa-camera fa-2x mb-2 text-muted"></i><br />
                  <span>Pas d'image sélectionnée</span>
                </div>
              </div>

              <div class="form-group mt-3">
                <label for="picture"><i class="fas fa-image me-1"></i> Photo</label>
                <input type="file" ref="pictureInput" @change="handleFileChange($event, 'pictureFile')" class="form-control" accept="image/*" />
              </div>
            </div>

            <div class="mb-3">
              <label for="document_file" class="form-label"><i class="fas fa-file-pdf me-1"></i> Autre document (PDF):</label>
              <input type="file" class="form-control" id="document_file" @change="handleFileChange($event, 'documentFile')" accept=".pdf" />
            </div>
          </div>

        </div>

        <!-- Submit Button -->
        <div class="d-flex flex-column gap-2 w-100 mt-4">
          <button type="submit" class="btn btn-success">
            <i class="fas fa-user-plus me-2"></i> Ajouter Employé
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { ref, computed } from "vue";
import { useRouter } from "vue-router";

export default {
  name: "AddEmployee",
  setup() {
    const router = useRouter();

    /* =====================
       Champs de formulaire
    ===================== */
    const first_name = ref("");
    const last_name = ref("");
    const per_email = ref("");
    const personal_email = computed(() => `${per_email.value}@gmail.com`);
    const date_of_birth = ref("");
    const city = ref("");
    const nationality = ref("");
    const gender = ref("");
    const cin = ref("");
    const family_situation = ref("");
    const hiring_date = ref("");
    const address = ref("");
    const contry = ref("");
    const emailP = ref("");    
    const email = computed(() => `${emailP.value}@societe.com`);
    const phoneN = ref("");
    const phone_number = computed(() => `+212${phoneN.value}`);
    const password = ref("");

    /* =====================
       Fichiers uploadés
    ===================== */
    const pictureFile = ref(null);
    const cinFile = ref(null);
    const cvFile = ref(null);
    const documentFile = ref(null);
    const imageUrl = ref(null);

    /* =====================
       Contrat / poste
    ===================== */
    const type_contract = ref("");
    const job_title = ref("");
    const start_date = ref("");
    const end_date = ref("");

    /* =====================
       État du composant
    ===================== */
    const employeeId = ref(null);
    const successMessage = ref("");
    const errorMessage = ref("");

    /* =====================
       Fonctions utilitaires
    ===================== */
    const scrollToTop = () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    };

    const handleFileChange = (event, fileRef) => {
      const file = event.target.files[0];
      if (!file) return;

      switch (fileRef) {
        case "cinFile": cinFile.value = file; break;
        case "cvFile": cvFile.value = file; break;
        case "documentFile": documentFile.value = file; break;
        case "pictureFile": pictureFile.value = file; imageUrl.value = URL.createObjectURL(file); break;
      }
    };

    /* =====================
       Soumission du formulaire
    ===================== */
    const handleAdd = async () => {
      successMessage.value = "";
      errorMessage.value = "";
      scrollToTop();

      const formData = new FormData();
      formData.append("first_name", first_name.value);
      formData.append("last_name", last_name.value);
      formData.append("personal_email", personal_email.value);
      formData.append("date_of_birth", date_of_birth.value);
      formData.append("city", city.value);
      formData.append("nationality", nationality.value);
      formData.append("gender", gender.value);
      formData.append("cin", cin.value);
      formData.append("family_situation", family_situation.value);
      formData.append("hiring_date", hiring_date.value);
      formData.append("address", address.value);
      formData.append("contry", contry.value);
      formData.append("email", email.value);
      formData.append("phone_number", phone_number.value);
      formData.append("password", password.value);
      if (pictureFile.value) formData.append("picture", pictureFile.value);
      if (cinFile.value) formData.append("cin_file", cinFile.value);
      if (cvFile.value) formData.append("cv_file", cvFile.value);
      if (documentFile.value) formData.append("document_file", documentFile.value);
      formData.append("job_title", job_title.value);
      formData.append("type_contract", type_contract.value);
      formData.append("start_date", start_date.value);
      formData.append("end_date", end_date.value);

      try {
        const response = await axios.post("http://127.0.0.1:8000/api/addEmployee", formData);
        if (response.data.error) { errorMessage.value = `Erreur : ${response.data.error}`; return; }
        employeeId.value = response.data.user.id;
        successMessage.value = "Employé ajouté avec succès.";

        await axios.post(`http://127.0.0.1:8000/api/addExperience/${employeeId.value}`, {
          job_title: job_title.value,
          type_contract: type_contract.value,
          start_date: start_date.value,
          end_date: end_date.value,
        });

        setTimeout(() => {
          router.push({ path: "/listEmployee", query: { success: "Employé ajouté avec succès." } });
        }, 2000);

      } catch (error) {
        if (error.response?.data?.errors) {
          errorMessage.value = Object.values(error.response.data.errors).flat().join(" ");
        } else if (error.response?.data?.error) {
          errorMessage.value = error.response.data.error;
        } else {
          errorMessage.value = "Une erreur est survenue. Veuillez réessayer.";
        }
        scrollToTop();
      }
    };

    return {
      first_name, last_name, per_email, personal_email,
      date_of_birth, city, nationality, gender, cin,
      family_situation, hiring_date, address, contry,
      emailP, email, phoneN, phone_number, password,
      pictureFile, cinFile, cvFile, documentFile, imageUrl,
      type_contract, job_title, start_date, end_date,
      employeeId, successMessage, errorMessage,
      scrollToTop, handleFileChange, handleAdd
    };
  }
};
</script>

<style>
.container {
  padding-bottom: 20px;
}

.secondary-nav {
  margin-bottom: 10px;
}

.image-preview-container {
  text-align: center;
}

.empty-image-placeholder {
  font-size: 14px;
  color: #888;
}
</style>
