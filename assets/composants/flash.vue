<template>
    <div>
      <p>Le temps est compté...</p>
      <p>
        <a :href="`/lesProduits/voirleproduit/${product.id}`">
        <img :src="product.image" :alt="product.nomProduit">
      </a>
    </p>
      <p> {{ product.nomProduit }}</p>
    </div>
  </template>
  <script>
  import { ref, onMounted } from 'vue';
  
  export default {
    setup() {
      
      const product = ref({});
  
  
      const fetchProductFlash = async () => {
        try {
          const response = await fetch('/api/mobile/produitType', {
            method: 'POST', // ou 'GET' si l'API attend une requête GET
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ promo: 'Flash' }), 
          });
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          const data = await response.json();
          product.value = data;
        } catch (error) {
          console.error('There was a problem with the fetch operation:', error);
        }
      };
  
      onMounted(() => {
        fetchProductFlash();
      });
  
      return {  product };
    },
  };
  </script>
<style>

</style>