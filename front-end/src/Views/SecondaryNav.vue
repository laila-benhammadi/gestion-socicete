<template>
    <nav class="secondary-nav">
      <ul>
        <li><router-link to="/ajouter-salaire">Ajouter Salaire</router-link></li>
        <li><router-link to="/ajouter-poste">Ajouter Poste</router-link></li>
        <li><router-link ">Ajouter Expérience</router-link></li>
      </ul>
    </nav>
  </template>
  
  <script>
  export default {
    name: "SecondaryNav",
  };
  </script>
  
  <style scoped>
  .secondary-nav {
    background-color: #ffffff;
    padding: 15px;
    border-bottom: 1px solid #ddd;
  }
  .secondary-nav ul {
    list-style: none;
    display: flex;
    gap: 15px;
    padding: 0;
    margin: 0;
  }
  .secondary-nav li {
    font-size: 14px;
  }
  .secondary-nav a {
    text-decoration: none;
    color: #007bff;
  }
  .secondary-nav a:hover {
    text-decoration: underline;
  }
  </style>
  