<template>
  <div class="wrapper">
    <!-- Bouton toggle pour mobile -->
    <button class="menu-toggle" @click="isOpen = !isOpen">
      <i class="bi bi-list"></i>
    </button>

    <div class="sidebar" :class="{ open: isOpen }">
      <!-- Bloc utilisateur -->
      <div class="d-flex align-items-center position-relative p-2">
        <!-- Avatar avec icône -->
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
             style="width:50px; height:50px; font-size:24px; position: relative;">
          <i class="bi bi-person-fill"></i>
          <!-- Cercle de statut -->
          <span class="position-absolute" 
                style="bottom:0; right:0; width:12px; height:12px; background-color:green; border-radius:50%; border:2px solid white;">
          </span>
        </div>

        <!-- Infos utilisateur -->
        <div class="user-info">
          <h5 class="mb-0 fs-6 text-white">Admin Principal</h5>
          <small class="text-muted d-block">ADMINISTRATEUR</small>
          <div class="text-success small">En ligne</div>
        </div>
      </div>

      <hr class="divider">

      <!-- Menu principal -->
      <ul>
        <li>
          <router-link to="/" class="small">
            <i class="bi bi-house-door"></i> Tableau de bord
          </router-link>
        </li>
        <li>
          <router-link to="/listEmployee" class="small">
            <i class="bi bi-person-lines-fill"></i> Employée
          </router-link>
        </li>
      </ul>

      <!-- Bloc déconnexion -->
      <div class="logout">
        <hr class="divider">
        <ul>
          <li>
            <a href="#" @click.prevent="logout">
              <i class="bi bi-box-arrow-right"></i> Déconnexion
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "SideBar",
  data() {
    return {
      isOpen: false
    };
  },
  methods: {
    async logout() {
      try {
        const token = localStorage.getItem("user-token");
        await axios.post(
          "http://127.0.0.1:8000/api/logout",
          {},
          {
            headers: { Authorization: `Bearer ${token}` }
          }
        );
        localStorage.removeItem("user-token");
        localStorage.removeItem("user");
        this.$router.push({ name: "Login" });
      } catch (error) {
        console.error("Erreur lors de la déconnexion :", error);
      }
    }
  }
};
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  list-style: none;
  font-family: 'Arial', sans-serif;
}

.wrapper {
  display: flex;
  position: relative;
}

/* Sidebar */
.sidebar {
  position: fixed;
  width: 200px;
  left: 0;
  top: 0;
  height: 100vh;
  background: rgb(0, 5, 48);
  padding: 30px 0;
  transition: transform 0.3s ease;
  z-index: 1050;
  overflow-y: auto;
}

.sidebar h2 {
  color: #fff;
  text-transform: uppercase;
  text-align: center;
  margin-bottom: 20px;
}

.sidebar ul li {
  padding: 15px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar ul li a {
  color: #fff;
  text-decoration: none;
  display: block;
}

.sidebar ul li a i {
  margin-right: 10px;
  font-size: 18px;
}

.sidebar ul li:hover {
  background: rgb(16, 23, 83);
}

/* Logout */
.logout {
  position: absolute;
  bottom: 0;
  width: 100%;
  padding: 10px 0;
  background: rgb(0, 5, 48);
}

.divider {
  background-color: white;
  height: 1px;
  margin: 10px 0;
}

/* Bouton toggle visible sur mobile */
.menu-toggle {
  display: none;
  position: fixed;
  top: 15px;
  left: 15px;
  background: rgb(0, 5, 48);
  color: white;
  border: none;
  padding: 8px 12px;
  font-size: 20px;
  z-index: 1001;
}

/* Responsive */
@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
  }
  .sidebar.open {
    transform: translateX(0);
  }
  .menu-toggle {
    display: block;
  }
}

.sidebar.open {
  transform: translateX(0);
}

/* Content wrapper */
.content-wrapper {
  transition: margin-left 0.3s ease;
  margin-left: 0;
}

.content-wrapper.sidebar-open {
  margin-left: 250px;
}
</style>
