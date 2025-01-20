
<template>
    
    <div class="container " >
      <div class="row  justify-content-center g-0 " style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%;">
    
    <div class="col-12 col-md-8 ">
      <div class="row g-0 ">


        <div class="col-12 col-md-6 p-4 bg-light shadow-lg rounded-start ">
          <h2 class="text-center mt-4">Login</h2>
          <p class="text-center">Please enter your login details:</p>
          
          <form @submit.prevent="login">
            <div class="d-flex flex-column align-items-center mt-5">
                    <div class="mb-3">
                       <label for="email" class="form-label">Email </label>
                        <input
                            type="email"
                            class="input-responsive"
                            id="email"
                            placeholder="Enter your password"
                            v-model="email"
                            required
                        />
                    </div>
                </div>
                <div class="d-flex flex-column align-items-center mt-5">
                    <div class="mb-3">
                      <div></div>
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
                </div>

                <div class="d-flex justify-content-center mt-4 mb-4">
                    <button type="submit" class="btn btn-custom custom-size">Sign In</button>
                </div>
          </form>
           <div v-if="errorMessage" class="alert alert-danger mt-4" role="alert">
            <p v-if="errorMessage" style="color: red;">{{ errorMessage }}</p>
          </div>

        </div>


        <div class="col-droite col-12 col-md-6  text-light p-4 shadow-sm rounded-end d-flex flex-column justify-content-center align-items-center">
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
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
      errorMessage: '', 
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('http://127.0.0.1:8000/api/admin/login', {
          email: this.email,
          password: this.password,
        });

        const { user, token } = response.data; 
        console.log("User received from API:", user);
        console.log("Token received from API:", token);

        console.log('Email:', this.email);
        console.log('Password:', this.password);

        console.log("User received from API:", user);
        console.log("Token received from API:", token);
        localStorage.setItem('user-token', token);
        localStorage.setItem('user', JSON.stringify(user));

        this.$router.push({ name: 'Dashboard' }); 




      } catch (error) {
        if (error.response && error.response.data.message) {
          this.errorMessage = error.response.data.message;
        } else {
          this.errorMessage = 'The credentials provided are incorrect.';
        }
      }
    },
  },
};
</script>


<style scoped>


.form-control:focus {
  box-shadow: none;
  border-color: #ced4da;  
 }

.col-droite{
  background: rgb(0, 5, 48)
}

.custom-size {
  width: 320px; 
  height: 40px;  
  margin: auto; 
  display: block;
}
.btn-custom {
background-color: rgb(0, 5, 48);
color: #fff;
border: none;
}
.btn-custom:hover {
background-color: rgb(16, 23, 83);
 }
 .input-responsive {
    width: 100%;   
    max-width: 500px;   
    padding: 6px 30PX;  
    font-size: 16px;   
    border: 2px solid rgb(0, 5, 48);  
    border-radius: 5px;   
    background-color: #fff;
    height: 40px;  

    
}


    </style>
