<template>
  <div class="main-container">
     <div v-if="loading">Chargement...</div>
     <div v-else-if="error">Erreur de chargement des données.</div>
     <div v-else>
        <div class="product-list">
           <div v-for="product in products" :key="product.id" class="product-item">
              <a :href="`/lesProduits/voirleproduit/${product.id}`">
                 <div class="product-info">
                   
                    <img :src="'/' +product.image" :alt="product.nomProduit">
                    <span class="product-promo" v-if="product.nomCategoriePromo" :class="getPromoClass(product.nomCategoriePromo)">
                    {{ product.nomCategoriePromo }}
                    </span>
                    <span class="product-nom">{{ product.nomProduit }}</span>
                    
                    
                 </div>
              </a>
           </div>
        </div>
     </div>
  </div>
</template>
<script>
  import { ref, onMounted } from 'vue';
  
  export default {
    setup() {
        const products = ref([]);
      const recherche = ref(marecherche);
      const loading = ref(false);
      const error = ref(null);
  
      
  
      const fetchProducts = async () => {
    try {
      loading.value = true;
      const payload = JSON.stringify({ motcle: recherche.value });
      const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: payload
      };
      const response = await fetch('/api/mobile/GetListeProduitRecherche', requestOptions);
      if (!response.ok) throw new Error('Erreur de chargement des produits');
      products.value = await response.json();
    } catch (e) {
      error.value = e.message;
    } finally {
      loading.value = false;
    }
  };
  
  
  const getPromoClass = (nomCategoriePromo) => {
        switch (nomCategoriePromo) {
          case 'Promo': return 'promo-rouge';
          case 'Flash': return 'promo-vert';
          
          default: return 'promo-jaune';
        }
      };
  
      onMounted(() => {
        fetchProducts();
      });
  
      return { recherche, products, loading, error , getPromoClass };
    }
  };
</script>
<style scoped>
  .main-container {
  flex: 1;
  padding-top: 200px;
  padding-bottom: 10px;
  padding-right: 30px;
  padding-left: 30px;
  }
  .product-list {
  display: grid;
  grid-template-columns: repeat(4, 1fr); /* Trois colonnes de taille égale */
  grid-gap: 20px; /* Espace entre les colonnes et les lignes */
  margin-top: 30px;
  margin-right: 10px;
  }
  .product-item {
  background-color: #fff;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
  }
  .product-item a {
  text-decoration: none;
  color: inherit;
  display: block;
  position: relative;
  }
  .product-item img {
  width: 100%;
  height: auto;
  }
  .product-info {
  display: flex;
  flex-direction: column;
  height: 100%; /* Assurez-vous que le conteneur occupe toute la hauteur de la cellule de la grille */
  }
  .product-nom {
  color: white;
  font-size: 4vh;
  font-weight: bold;
  text-align: center;
  background-color: rgba(0, 0, 0, 1);  padding: 5px;
  
  }
  .product-price {
  color: white;
  font-size: 4vh; /* Taille de la police en fonction de la hauteur de la fenêtre */
  font-weight: bold; /* Rend le texte en gras */
  text-align: center; /* Centre le texte horizontalement */
  background-color: rgb(47, 103, 61);
  padding: 5px; /* Ajout de padding pour un meilleur rendu */
  }
  .product-item .product-info::after {
  content: ""; /* Nécessaire pour générer l'élément */
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Couleur noire avec 50% d'opacité */
  opacity: 0; /* Commencez avec une opacité de 0 */
  transition: opacity 0.3s; /* Transition douce pour l'opacité */
  }
  .product-item:hover .product-info::after {
  opacity: 1; /* Opacité totale au survol */
  }
  .lesProduitsContainer {
  display: flex;
  background-color: #000000;
  }
  .sticky-container {
  position: sticky;
  top: 0;
  padding-left: 20px;
  padding-right: 20px;
  width: 200px;
  background-color: #000000;
  margin-top: 8vh;
  }
  .product-promo {
  font-size: 4vh; /* Taille de la police */
  font-weight: bold; /* Rend le texte en gras */
  color: #fff; /* Couleur du texte */
  background-color: rgb(255, 255, 255);
  margin-right: 20%;
  margin-left: 5%;
  margin-bottom: 5px;
  padding: 5px;
  font-size: 3vh;
  border-radius: 10px;
  /* Autres styles selon votre design */
  }
  .promo-rouge {
  font-size: 4vh; /* Taille de la police */
  font-weight: bold; /* Rend le texte en gras */
  color: #fff; /* Couleur du texte */
  background-color: rgb(198, 24, 24);
  margin-right: 60%;
  text-align: center;
  margin-left: 5%;
  margin-bottom: 5px;
  padding: 5px;
  font-size: 3vh;
  border-radius: 10px;
  /* Autres styles selon votre design */
  }
  .promo-vert {
  font-size: 4vh; /* Taille de la police */
  font-weight: bold; /* Rend le texte en gras */
  color: #fff; /* Couleur du texte */
  background-color: rgb(17, 97, 34);
  text-align: center;
  margin-right: 70%;
  margin-left: 5%;
  margin-bottom: 5px;
  padding: 5px;
  font-size: 3vh;
  border-radius: 10px;
  }
  .promo-jaune {
  font-size: 4vh; /* Taille de la police */
  font-weight: bold; /* Rend le texte en gras */
  color: #fff; /* Couleur du texte */
  background-color: rgb(238, 234, 32);
  text-align: center;
  margin-right: 70%;
  margin-left: 5%;
  margin-bottom: 5px;
  padding: 5px;
  font-size: 3vh;
  border-radius: 10px;
  }
  .promo-default {
  color: white;
  }
</style>