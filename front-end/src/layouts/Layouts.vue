<template>
  <div class="layout">
    <SideBar />
    <div class="main-content">
      <NavBar />
      <div class="content">
        <router-view></router-view>  <!-- Affiche le contenu de la page -->
      </div>
    </div>
  </div>
</template>

<script>
import NavBar from '../Components/NavBar.vue';
import SideBar from '../Components/SideBar.vue';

export default {
  name: 'Layouts',
  components: {
    NavBar,
    SideBar,
  },
};
</script>

<style scoped>

.content {
  margin-left: 240px;
}
</style>
