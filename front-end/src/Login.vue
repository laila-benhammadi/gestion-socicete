<template>
  <div class="container">
    <div class="row justify-content-center g-0"
         style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%;">
      
      <div class="col-12 col-md-8">
        <div class="row g-0">

          <!-- Formulaire Login -->
          <div class="col-12 col-md-6 p-4 bg-light shadow-lg rounded-start">
            <h2 class="text-center mt-4">Login</h2>
            
            <!-- Icone utilisateur -->
            <div style="width:100px; height:100px;  
                        background: linear-gradient(135deg, rgb(70, 80, 150), rgb(16, 23, 83));
                        border-radius:50%; display:flex; align-items:center; justify-content:center;
                        margin: 0 auto;">
              <i class="fas fa-user" style="font-size:28px; color:white;"></i>
            </div>

            <!-- Formulaire -->
            <form @submit.prevent="login">

              <!-- Email -->
              <div class="d-flex flex-column align-items-center mt-4 mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                  type="email"
                  class="input-responsive"
                  id="email"
                  placeholder="Enter your email"
                  v-model="email"
                  required
                />
              </div>

              <!-- Password -->
              <div class="d-flex flex-column align-items-center mt-4">
                <label for="password" class="form-label">Password</label>
                <input
                  type="password"
                  class="input-responsive"
                  id="password"
                  placeholder="Enter your password"
                  v-model="password"
                  required
                />
              </div>

              <!-- Bouton Login -->
              <div class="d-flex justify-content-center mt-5 mb-4">
                <button type="submit" class="btn btn-custom custom-size">Sign In</button>
              </div>

            </form>

            <!-- Message d'erreur -->
            <div v-if="errorMessage" class="alert alert-danger mt-4" role="alert">
              <p style="color: red;">{{ errorMessage }}</p>
            </div>
          </div>

          <!-- Section droite / message de bienvenue -->
          <div class="col-droite col-12 col-md-6 text-light p-4 shadow-sm rounded-end d-flex flex-column justify-content-center align-items-center">
            <h3 class="text-center">Welcome to our platform</h3>
            <p>Please sign in to continue.</p>
          </div>

        </div>
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AdminLogin',
  data() {
    return {
      email: '',
      password: '',
      errorMessage: '',
    };
  },
  methods: {
    async login() {
      this.errorMessage = '';

      try {
        const response = await axios.post('http://127.0.0.1:8000/api/admin/login', {
          email: this.email,
          password: this.password,
        });

        // Vérification du token et redirection
        if (response.status === 200 && response.data.token) {
          const { user, token } = response.data;
          localStorage.setItem('user-token', token);
          localStorage.setItem('user', JSON.stringify(user));
          this.$router.push({ name: 'Dashboard' });
        }
      } catch (error) {
        console.log("Erreur de connexion :", error.response || error);
        if (error.response && error.response.status === 401) {
          this.errorMessage = "L'email ou le mot de passe est incorrect.";
        }
      }
    },
  },
};
</script>

<style scoped>
/* Focus input */
.form-control:focus {
  box-shadow: none;
  border-color: #ced4da;
}

/* Section droite */
.col-droite {
  background: rgb(0, 5, 48);
}

/* Bouton personnalisé */
.btn-custom {
  background-color: rgb(0, 5, 48);
  color: #fff;
  border: none;
}
.btn-custom:hover {
  background-color: rgb(16, 23, 83);
}

/* Input responsive */
.input-responsive {
  width: 320px;
  height: 40px;
  margin: auto;
  display: block;
  padding: 6px 12px;
  font-size: 16px;
  border: 2px solid rgb(0, 5, 48);
  border-radius: 5px;
  background-color: #fff;
  box-sizing: border-box;
}

/* Label aligné */
.form-label {
  margin-right: 250px;
}

/* Taille bouton / input */
.custom-size,
input {
  width: 320px;
  height: 40px;
  margin: auto;
  display: block;
}
</style>
