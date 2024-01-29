<template>
  <div class="lesProduitsContainer">
    <div class="sticky-container">
      <div class="notre-offre-title">Notre offre</div> <!-- Ajout du titre ici -->
      <ul class="categories">
        <li class="category-item" v-for="parentCategory in parentCategories" :key="parentCategory.id">
          {{ parentCategory.nom }}
          <ul class="sub-categories">
            <li class="sub-category-item" v-for="category in parentCategory.lescategories" :key="category.id">
              <a href="#" class="category-link" @click="loadProductsByCategory(category)">
                {{ category.nom }}
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="main-container">
    <div v-if="loading">Chargement...</div>
      <div v-else-if="error">Erreur de chargement des données.</div>
        <div v-else>
          <div class="product-list">
            <div v-for="product in products" :key="product.id" class="product-item">
  <a :href="`/lesProduits/voirleproduit/${product.id}`">
    <div class="product-info">
      <span class="product-nom">{{ product.nomProduit }}</span>
      <img :src="product.image" :alt="product.nomProduit">

      <span class="product-promo" v-if="product.nomCategoriePromo" :class="getPromoClass(product.nomCategoriePromo)">
        {{ product.nomCategoriePromo }}
      </span>
      <span v-else class="product-promo"> Prix</span>
      
      <span class="product-price">
        {{ product.nomCategoriePromo ? product.prixpromo : product.prix }} €
      </span>
    </div>
  </a>
</div>

      </div>
    </div>
  </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';

export default {
  setup() {
    const parentCategories = ref([]);
    const products = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const fetchCategories = async () => {
      try {
        const response = await fetch('/api/mobile/categories');
        if (!response.ok) throw new Error('Erreur de chargement des catégories');
        parentCategories.value = await response.json();
      } catch (e) {
        error.value = e.message;
      }
    };

    const fetchProducts = async () => {
  try {
    loading.value = true;
    const payload = JSON.stringify({ categoryID: 47 });
    const requestOptions = {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: payload
    };
    const response = await fetch('/api/mobile/GetListeProduitParCategorie', requestOptions);
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



const loadProductsByCategory = async (category) => {
  try {
    loading.value = true;
    const payload = JSON.stringify({ categoryID: category.id });
    const requestOptions = {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: payload
    };
    const response = await fetch('/api/mobile/GetListeProduitParCategorie', requestOptions);
    if (!response.ok) throw new Error('Erreur de chargement des produits');
    products.value = await response.json();
  } catch (e) {
    error.value = e.message;
  } finally {
    loading.value = false;
  }
};


    onMounted(() => {
      fetchCategories();
      fetchProducts();
    });

    return { parentCategories, products, loading, error, loadProductsByCategory , getPromoClass };
  }
};
</script>


<style scoped>

.main-container {
    flex: 1;
    padding-top: 100px;
    padding-bottom: 10px;
    padding-right: 30px;
    padding-left: 30px;
  }
.product-list {
  display: grid;
  grid-template-columns: repeat(3, 1fr); /* Trois colonnes de taille égale */
  grid-gap: 80px; /* Espace entre les colonnes et les lignes */
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
  background-color: rgba(195, 195, 125, 0.5);  padding: 5px;
  border-top-left-radius: 10px;  /* Rayon pour le coin supérieur gauche */
  border-top-right-radius: 10px;
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

  .categories {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .categories, .sub-categories {
    font-size: 2.2vh; /* Définit la taille du texte à 19% de la hauteur de la fenêtre de visualisation */
    font-weight: bold; /* Rend le texte en gras */
    list-style: none; /* Supprime les puces */
    cursor: pointer; /* Change le curseur pour indiquer la possibilité de cliquer */
    color: white; /* Définit la couleur du texte en blanc */
}

  .category-item {
    margin-bottom: 10px;
    border-bottom: 2px solid green; /* Ajoute un séparateur en bas de chaque élément de catégorie */

    
  }

  .category-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
  }

  .sub-categories {
    display: none; /* Cache les sous-catégories par défaut */
    list-style: none; /* Optionnel: supprime les puces */
    padding-left: 20px; /* Optionnel: espace pour les sous-catégories */
}
.sub-category-item a {
    text-decoration: none; /* Supprime le soulignement du lien */
    color: inherit; /* Le lien prend la couleur de texte de son élément parent */
    cursor: pointer; /* Change le curseur pour indiquer un lien cliquable */
}

.sub-category-item a:hover {
    text-decoration: underline; /* Optionnel: ajoute un soulignement au survol pour une meilleure indication de lien cliquable */
}


.category-item:hover .sub-categories {
    display: block; /* Affiche les sous-catégories au survol */
}

.notre-offre-title {
  font-size: 24px; /* Taille de la police */
  font-weight: bold; /* Rend le texte en gras */
  color: #fff; /* Couleur du texte */
  padding-bottom: 10px; /* Espacement en dessous du titre */
  text-align: left; /* Centrer le texte */
  margin-bottom: 20px; /* Marge en dessous du titre */
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
