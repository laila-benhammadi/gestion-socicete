<template>
  <div class="container mt-5 shadow-sm">
    <div class="mb-6 mt-6 p-3 secondary-nav"></div>
    <h2 class="mb-4 text-center">Formulaire d'inscription</h2>
    <div v-if="errorMessage" class="alert alert-danger" role="alert">
    {{ errorMessage }}
   </div>


    <form @submit.prevent="handleAdd">
      <div class="row">
        <div class="col-md-4">
          <div class="mb-3">
            <label for="last_name" class="form-label">Nom:</label>
            <input type="text" id="last_name" class="form-control" v-model="last_name" required />
          </div>
          <div class="mb-3">
            <label for="personal_email" class="form-label">Email personnel:</label>
            <input type="email" id="personal_email" class="form-control" v-model="personal_email" required />
          </div>
          <div class="mb-3">
            <label for="date_of_birth" class="form-label">Date de naissance:</label>
            <input type="date" id="date_of_birth" class="form-control" v-model="date_of_birth" required />
          </div>
          <div class="mb-3">
            <label for="family_situation" class="form-label">Situation familiale:</label>
            <select id="family_situation" class="form-select" v-model="family_situation" required>
              <option value="Célibataire">Célibataire</option>
              <option value="Marié(e)">Marié(e)</option>
              <option value="Divorcé(e)">Divorcé(e)</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Adresse:</label>
            <input type="text" id="address" class="form-control" v-model="address" required />
          </div>
          <div class="mb-3">
            <label for="nationality" class="form-label">Nationalité:</label>
            <input type="text" id="nationality" class="form-control" v-model="nationality" required />
          </div>
          <div class="mb-3">
            <label for="cin" class="form-label">Carte d'Identité Nationale (CIN):</label>
            <input type="text" id="cin" class="form-control" v-model="cin" required />
          </div>
          <div class="mb-3">
            <label for="cv_file" class="form-label">Fichier CV (PDF):</label>
            <input type="file" class="form-control" id="cv_file" @change="handleFileChange($event, 'cvFile')" />
          </div>
           
        </div>

        <div class="col-md-4">
          <div class="mb-3">
            <label for="first_name" class="form-label">Prénom:</label>
            <input type="text" id="first_name" class="form-control" v-model="first_name" required />
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email professionnelle:</label>
            <input type="email" id="email" class="form-control" v-model="email" required />
          </div>
          <div class="mb-3">
            <label for="gender" class="form-label">Genre:</label>
            <select id="gender" class="form-select" v-model="gender" required>
              <option value="male">Homme</option>
              <option value="female">Femme</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="phone_number" class="form-label">Numéro de téléphone:</label>
            <input type="text" id="phone_number" class="form-control" v-model="phone_number" required />
          </div>
          <div class="mb-3">
            <label for="city" class="form-label">Ville:</label>
            <input type="text" id="city" class="form-control" v-model="city" required />
          </div>
          <div class="mb-3">
            <label for="contry" class="form-label">Pays:</label>
            <input type="text" id="contry" class="form-control" v-model="contry" required />
          </div>
          <div class="mb-3">
            <label for="hiring_date" class="form-label">Date d'embauche:</label>
            <input type="date" id="hiring_date" class="form-control" v-model="hiring_date" required />
          </div>
           
          <div class="mb-3">
            <label for="cin_file" class="form-label">Fichier CIN (PDF):</label>
            <input type="file" class="form-control" id="cin_file" @change="handleFileChange($event, 'cinFile')" />
          </div>
         
         
        </div>

        <div class="col-md-4">         
          
          <div class="mb-3">
            <label for="post" class="form-label">Poste:</label>
            <select id="post" class="form-select" v-model="job_title" required>
              <option value="" disabled selected>Choisir un Poste</option>
              <option value="Développeur Front-End">Développeur Front-End</option>
              <option value="Développeur Back-End">Développeur Back-End</option>
              <option value="Développeur Full-Stack">Développeur Full-Stack</option>
              <option value="Développeur Mobile (iOS/Android)">Développeur Mobile (iOS/Android)</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="contract_type" class="form-label">Type de contrat:</label>
            <select id="contract_type" class="form-select" v-model="type_contract" required>
              <option value="" disabled selected>Choisir un Type de contrat</option>
              <option value="CDD">CDD</option>
              <option value="CDI">CDI</option>
              <option value="Stage">Stage</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="start_date" class="form-label">Date de début:</label>
            <input type="date" id="start_date" class="form-control" v-model="start_date" required />
          </div>
          <div class="mb-3">
            <label for="end_date" class="form-label">Date de fin:</label>
            <input type="date" id="end_date" class="form-control" v-model="end_date" />
          </div>
         

          <div class="border rounded shadow-sm mb-3 p-3 bg-light">
            <div class="image-preview-container text-center" :class="{'image-selected': imageUrl}">
              <div v-if="imageUrl">
                <img :src="imageUrl" alt="Image Preview" class="img-thumbnail img-fluid">
              </div>
              <div v-else class="empty-image-placeholder">
                <span>Pas d'image sélectionnée</span>
              </div>
            </div>
            
            <div class="form-group">
              <label for="picture">Photo</label>
              <input type="file" ref="pictureInput" @change="handleFileChange($event, 'pictureFile')" class="form-control" />
            </div>
          </div>
          <div class="mb-3">
            <label for="document_file" class="form-label">Autre document (PDF):</label>
            <input type="file" class="form-control" id="document_file" @change="handleFileChange($event, 'documentFile')" />
          </div>

          
        </div>
      </div>

      <div class="d-flex flex-column gap-2 w-100 mt-4">
        <button type="submit" class="btn btn-dark">Ajouter Employé</button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from "axios";
import { ref } from "vue";

export default {
  name: "AddEmployee",
  setup() {
    const first_name = ref("");
    const last_name = ref("");
    const personal_email = ref("");
    const date_of_birth = ref("");
    const city = ref("");
    const nationality = ref("");
    const gender = ref("");
    const cin = ref("");
    const family_situation = ref("");
    const hiring_date = ref("");
    const address = ref("");
    const contry = ref("");
    const email = ref("");
    const phone_number = ref("");
    const password = ref("");
    const pictureInput = ref(null);
    const pictureFile = ref(null);
    const cinFile = ref(null);
    const cvFile = ref(null);
    const documentFile = ref(null);
    const imageUrl = ref(null);
    const type_contract = ref("");
    const job_title = ref("");
    const start_date = ref("");
    const end_date = ref("");
    const employeeId = ref(null);
    const successMessage = ref("");
    const errorMessage = ref("");



    const handleFileChange = (event, fileRef) => {
      const file = event.target.files[0];
      if (file) {
        if (fileRef === "cinFile") cinFile.value = file;
        if (fileRef === "cvFile") cvFile.value = file;
        if (fileRef === "documentFile") documentFile.value = file;
        if (fileRef === "pictureFile") {
          pictureFile.value = file;
          imageUrl.value = URL.createObjectURL(file);
        }
      }
    };

    const handleAdd = async () => {
      successMessage.value = "";
      errorMessage.value = "";

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
        if (response.data.error) {
        errorMessage.value = `Erreur: ${response.data.error}`;
        }else {
          employeeId.value = response.data.user.id; // Get the employee ID

          successMessage.value = "Employé ajouté avec succès.";

          const experienceData = {
            job_title: job_title.value,
            type_contract: type_contract.value,
            start_date: start_date.value,
            end_date: end_date.value
          };

          await axios.post(`http://127.0.0.1:8000/api/addExperience/${employeeId.value}`, experienceData);

    // Rediriger avec un message de succès dans l'URL
        setTimeout(() => {
          this.$router.push({ path: '/listEmployee', query: { success: "Employé ajouté avec succès." } });
        }, 
        2000);
        }
      } catch (error) {
        console.error("Erreur API:", error);
        errorMessage.value = "Une erreur est survenue.";
      }
    };

    

    return {
      first_name,
      last_name,
      personal_email,
      date_of_birth,
      city,
      nationality,
      gender,
      cin,
      family_situation,
      hiring_date,
      address,
      contry,
      email,
      phone_number,
      password,
      pictureInput,
      pictureFile,
      imageUrl,
      type_contract,
      job_title,
      start_date,
      end_date,
      employeeId,
      successMessage,
      errorMessage,
      handleFileChange,
      handleAdd,
    };
  },
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
