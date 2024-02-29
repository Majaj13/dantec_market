<template>
    <div class="lejour">
      <p>Le produit du {{ weekday }}</p>
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
      const weekday = ref('');
      const product = ref({});
  
      const setWeekday = () => {
        const days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        const today = new Date();
        weekday.value = days[today.getDay()];
      };
  
      const fetchProductOfTheDay = async () => {
        try {
          const response = await fetch('/api/mobile/produitdujour', {
            method: 'POST', // ou 'GET' si l'API attend une requête GET
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ jour: weekday.value }),
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
        setWeekday();
        fetchProductOfTheDay();
      });
  
      return { weekday, product };
    },
  };
  </script>
  <style>
  .lejour {
    color: silver;

}
</style>
  