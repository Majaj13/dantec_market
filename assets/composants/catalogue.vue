<template>
       <div class="bande-image">
     <img src="/images/Catalogue.jpg" alt="Image descriptive">
  </div>
</template>
<script></script>
<style>
.bande-image {
  height: 50vw; /* Hauteur fixe pour la bande */
  width: 100vw; /* Largeur basée sur la largeur de la fenêtre */
  overflow: hidden; /* Cache tout contenu qui dépasse de la bande */
  }
  .bande-image img {
  width: 100%; /* Assure que l'image s'étend sur toute la largeur */
  height: 100%; /* Assure que la hauteur de l'image remplit la bande */
  object-fit: cover; /* Assure que l'image couvre la zone sans être déformée */
  }
</style>