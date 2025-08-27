<template>
  <div class="layout">
    <SideBar />
    <div class="main-content">
      <div class="content">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script>
import SideBar from '../Components/SideBar.vue';

export default {
  name: 'Layouts',
  components: {
    SideBar,
  },
};
</script>

<style scoped>
.layout {
  display: flex;
}

/* Zone principale */
.main-content {
  flex: 1;
}

/* Contenu décalé uniquement sur desktop */
.content {
  padding: 20px;
  margin-left: 200px; /* Espace pour la sidebar */
}

/* Responsive : plein écran sur mobile */
@media (max-width: 768px) {
  .content {
    margin-left: 0;
  }
}
</style>
