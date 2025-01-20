<template>
    <div class="wrapper">
      <div class="sidebar">
        <h2> Admin</h2>
        <hr class="divider"> 
        <ul>
          <li><router-link to="/"><i class="bi bi-house-door"></i> Dashboard</router-link></li>
          <li><router-link to="/listEmployee"><i class="bi bi-person-lines-fill"></i> Empolyée</router-link></li>
       </ul>   
       <div class="logout">
        <hr class="divider"> 
        <ul>
          <li>
            <a href="#" @click.prevent="logout">
              <i class="bi bi-box-arrow-right"></i> Déconnexion</a>
          </li>
          <li v-if="errorMessage">{{ errorMessage }}</li>
        </ul>
       </div>
     </div>     
    </div>
  </template>
  <script>
  import axios from "axios";
  
  export default {
    name: "SideBar",
  
    methods: {
      async logout() {
        try {
          const token = localStorage.getItem("user-token");
  
          if (!token) {
            console.log("Vous n'êtes pas connecté.");
            return;
          }
  
           await axios.post(
            "http://127.0.0.1:8000/api/logout",
            {},
            {
              headers: {
                Authorization: `Bearer ${token}`, 
              },
            }
          );
  
          localStorage.removeItem("user-token");
          localStorage.removeItem("user");
  
          this.$router.push({ name: "Login" });
        } catch (error) {
          console.error("Erreur lors de la déconnexion :", error);
        }
      },
    },
  };
  
</script>
 
<style>
*{
    
    box-sizing: none;
    list-style: none;
    font-style: oblique 40deg;
    font-family:Serif;

}
.wrapper{
    display: flex;
    position: relative;
}
.wrapper .sidebar{
    position:fixed;
    width: 240px;
    height: 100%;
    background:rgb(0, 5, 48);
    padding: 30px 0 ;
}
.wrapper .sidebar h2{
    color: #fff;
    text-transform: uppercase;
    text-align: center;
    font-family: 'Arial', sans-serif;  
    font-style: oblique;

}
.wrapper .sidebar ul li{
    padding: 15px;
    border-bottom: 1px rgba(0,0,0,05);
    border-top: 1px solid rgba(25,225,225,0,05);
    border-top-left-radius: 15px;
    border-bottom-right-radius:15px;
  }

.wrapper .sidebar ul li a{
    color: #fff;
    text-decoration: none;
    display: block;
}
.wrapper .sidebar ul li a i{
  margin-right: 5px; 
  font-size: 22px;
  }

  .wrapper .sidebar ul li:hover{
    background:rgb(16, 23, 83);
  } 
  .wrapper .sidebar ul li:hover a{
    color: #fff;
  }
.wrapper .sidebar   .logout {
  bottom: 0px;
  position:fixed;
  border-bottom: 1px rgba(0,0,0,05);
  border-top: 1px solid rgba(25,225,225,0,05);
   width: 240px;

 }

 .divider{
  background-color: white;
  height: 2px; 

 }

</style>
  