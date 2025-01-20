<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid d-flex">
      <div>
        <h3>Welcome!</h3>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'NavBar'
}
</script>

<style>
  nav {
    background-color: #fff;
  }
  .navbar {
    padding: 9px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
    margin-left: 240px;
    text-align: left;
    font-family: 'Arial', sans-serif;
    font-size: 12px;
  }
  .header h1 {
    font-family: courier;
    font-style: oblique;
    color: rgb(0, 0, 0);  
  }
</style>
