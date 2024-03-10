<template>
    <div>
      <p>Il s'arrache...</p>
      <p><img :src="product.image" :alt="product.nomProduit"></p>
      <p> {{ product.nomProduit }}</p>
    </div>
  </template>
  <script>
  import { ref, onMounted } from 'vue';
  
  export default {
    setup() {
      
      const product = ref({});
  
  
      const fetchProductTendance = async () => {
        try {
          const response = await fetch('/api/mobile/produitType', {
            method: 'POST', // ou 'GET' si l'API attend une requête GET
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ promo: 'Tendance' }), 
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
        fetchProductTendance();
      });
  
      return {  product };
    },
  };
  </script>
<style>

</style>