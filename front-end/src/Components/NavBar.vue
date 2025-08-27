<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid d-flex">
      <!-- Champ de recherche -->
      <div>
        <input type="text" class="form-control me-2" placeholder="Entrez quelque chose" aria-label="Recherche"
          style="width: 400px; border-radius: 10px; text-decoration: none;">
      </div>

      <!-- Espace vide pour pousser les éléments à droite -->
      <div class="ms-auto d-flex align-items-center">
        <!-- Bouton de notifications -->
        <button class="btn btn-light me-3">
          <i class="bi bi-bell"></i> <!-- Icône de cloche -->
        </button>
        <!-- Ajoute ceci avant </div> dans ms-auto -->
        <button class="btn btn-light me-3" @click="toggleDarkMode">
          <i class="bi" :class="isDark ? 'bi-sun' : 'bi-moon'"></i>
        </button>

        <!-- Icône de profil -->
        <div class="navbar-profile">
          <img src="" alt="Profile" class="profile-img" style="width: 40px; height: 40px; border-radius: 50%;">
        </div>
      </div>
    </div>
  </nav>
</template>
<script setup>
import { ref, onMounted } from 'vue'

const isDark = ref(false)

const toggleDarkMode = () => {
  isDark.value = !isDark.value
  document.body.classList.toggle('dark-mode', isDark.value)
  localStorage.setItem('darkMode', isDark.value)
}

onMounted(() => {
  const saved = localStorage.getItem('darkMode') === 'true'
  isDark.value = saved
  if (saved) document.body.classList.add('dark-mode')
})
</script>


<style>
  nav {
    background-color: #fff;
  }
  .navbar {
    padding: 9px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
    margin-left: 200px;
    text-align: left;
    font-family: 'Arial', sans-serif;
    font-size: 12px;
  }
  .header h1 {
    font-family: courier;
    font-style: oblique;
    color: rgb(0, 0, 0);  
  }
  .navbar-profile {
    cursor: pointer;
  }
  .bi-bell {
    font-size: 20px;
    color: #2c3e50;
  }





  
</style>