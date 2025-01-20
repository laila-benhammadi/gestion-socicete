<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: 'App',
};
</script>

<style scoped>
#app {
  height: 100vh;
  margin: 0;
  font-family: 'Arial', sans-serif; /* Utilisation d'une police différente */
  font-style: oblique;
}
</style>
