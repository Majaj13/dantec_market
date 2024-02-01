<template>
  <div class="lesProduitsContainer">
    <div class="sticky-container">
      <div class="notre-offre-title">Notre offre</div>
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
                <img :src="product.image" :alt="product.nomProduit">
                <div class="overlay">
                  <span class="product-nom">{{ product.nomProduit }}</span>
                  <span class="product-description"><p>{{ descriptionTronquee }}</p></span>
                  <div class="bottom-section">
                    <span class="product-price">{{ product.nomCategoriePromo ? product.prixpromo : product.prix }} €</span>
                    <div class="button-container">
                      <a href="/chemin-vers-le-panier" class="icon-panier">
                        <i class="fa fa-shopping-cart"></i>
                      </a>
                    </div>
                  </div>
                </div>
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
    padding-top: 10vh;
    padding-bottom: 1vh;
    padding-right: 3vh;
    padding-left: 3vh;
  }
.product-list {
  display: grid;
  grid-template-columns: repeat(3, 1fr); /* Trois colonnes de taille égale */
  grid-gap: 8vh; /* Espace entre les colonnes et les lignes */
  margin-top: 3vh;
  margin-right: 1vh;
}

.product-item {
  position: relative;
  background-color: #fff;
  border-radius: 5vh;
  overflow: hidden;
}

.product-item a {
  text-decoration: none;
  color: inherit;
  display: block;
  position: relative;
}

.product-item img {
  width: 100%;  /* Fait en sorte que l'image occupe 100% de la largeur du conteneur parent */
  height: 50vh;  /* Hauteur initiale à 0 (sera ajustée avec la propriété de ratio) */
  padding-bottom: 0%;  /* Définit le ratio d'aspect à 1:1 pour rendre l'image carrée */
  object-fit: cover;  /* Redimensionne l'image pour couvrir l'intégralité du conteneur sans déformation */
}

.product-info {
  position: relative;
  display: flex;
  flex-direction: column;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(180deg, rgba(243, 229, 171, 0.42) 0.11%, rgba(223, 186, 97, 0.75) 73.46%);
  opacity: 0;
  border-bottom-left-radius: 1vh;
  border-bottom-right-radius: 1vh;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
  padding: 1vh;
  box-sizing: border-box;
  transition: opacity 0.3s;
  z-index: 1;
}

.product-item:hover .overlay {
  opacity: 1;
}

.product-nom {
  color: #2D2D2D;
  text-align: center;
  font-family: Inter;
  font-size: 6.25vh;
  font-style: normal;
  font-weight: 800;
  line-height: normal;
  opacity: 0;
  transition: opacity 0.3s;
  z-index: 2;
}

.product-description {
  color: #2D2D2D;
  text-align: center;
  font-family: Inter;
  font-size: 4vh;
  font-style: normal;
  font-weight: 600;
  line-height: normal;
  opacity: 0;
  transition: opacity 0.3s;
  z-index: 2;
}

.bottom-section {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
}

.product-price {
  color: #2D2D2D;
  font-family: Inter;
  font-size: 7vh;
  font-style: normal;
  font-weight: 900;
  line-height: normal;
  opacity: 0;
  transition: opacity 0.3s;
  z-index: 2;
  margin-right: 10vh;
}

.button-container {
  margin-left: auto;
  margin-right: 1vh;
}

.add-to-cart-button {
  color: #2d2d2d;
  opacity: 0;
  transition: opacity 0.3s;
  z-index: 2;
}

.add-to-cart-button img {
  margin-right: 0.5vh;
  height: 10vh;
  border: none; /* Ajout de cette ligne pour supprimer la bordure */
  background-color: transparent;
}

.product-item .product-info::after {
  content: "";
  position: absolute;
  bottom: 0;  /* Changement de 'top' à 'bottom' */
  left: 0;
  width: 100%;
  height: 0;  /* Modification de la hauteur à 0 pour commencer */
  background: linear-gradient(180deg, rgba(243, 229, 171, 0.42) 0.11%, rgba(223, 186, 97, 0.75) 73.46%);
  opacity: 0;
  border-bottom-left-radius: 1vh;  /* Reproduire le rayon du coin inférieur gauche du .product-item */
  border-bottom-right-radius: 1vh;  /* Reproduire le rayon du coin inférieur droit du .product-item */
  transition: opacity 0.3s, height 0.3s;  /* Ajout de la transition pour la hauteur */
}

.product-item:hover .product-info::after {
  height: 100%;  /* Ajuster la hauteur à 100% au survol */
  opacity: 1;
}

.product-item:hover .product-nom,
.product-item:hover .product-price,
.product-item:hover .product-description,
.product-item:hover .add-to-cart-button {
  opacity: 1;
}

.lesProduitsContainer {
    display: flex;
    background-color: #000000;
  }

  .sticky-container {
    position: sticky;
    top: 0;
    padding-left: 2vh;
    padding-right: 2vh;
    width: 20vh;
    height: auto;
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
    margin-bottom: 1vh;
    border-bottom: 0.2vh solid #DFBA61; /* Ajoute un séparateur en bas de chaque élément de catégorie */
    overflow: hidden; /* Ajoutez overflow: hidden pour masquer les sous-catégories par défaut */
  }

  .category-title {
    font-size: 2.4vh;
    font-weight: bold;
    margin-bottom: 1vh;
  }

  .sub-categories {
    display: none; /* Cache les sous-catégories par défaut */
    list-style: none; /* Optionnel: supprime les puces */
    padding-left: 2vh; /* Optionnel: espace pour les sous-catégories */
    max-height: 0; /* Ajoutez max-height: 0 pour cacher les sous-catégories par défaut */
    overflow: hidden; /* Ajoutez overflow: hidden pour masquer le contenu qui dépasse la hauteur maximale */
    transition: max-height 0.3s ease-in-out;
}
.sub-category-item a {
    text-decoration: none; /* Supprime le soulignement du lien */
    color: inherit; /* Le lien prend la couleur de texte de son élément parent */
    cursor: pointer; /* Change le curseur pour indiquer un lien cliquable */
}

.sub-category-item a:hover {
    text-decoration: underline 0.2vh solid #DFBA61; /* Optionnel: ajoute un soulignement au survol pour une meilleure indication de lien cliquable */
}


.category-item:hover .sub-categories {
    display: block; /* Affiche les sous-catégories au survol */
    max-height: 100vh; /* Définissez la hauteur maximale pour révéler les sous-catégories avec l'animation */
}

.notre-offre-title {
  font-size: 2.4vh; /* Taille de la police */
  font-weight: bold; /* Rend le texte en gras */
  color: #fff; /* Couleur du texte */
  padding-bottom: 1vh; /* Espacement en dessous du titre */
  text-align: left; /* Centrer le texte */
  margin-bottom: 2vh; /* Marge en dessous du titre */
}
.product-promo {
  font-size: 4vh; /* Taille de la police */
  font-weight: bold; /* Rend le texte en gras */
  color: #fff; /* Couleur du texte */
  background-color: rgb(255, 255, 255);
  margin-right: 20%;
  margin-left: 5%;
  margin-bottom: 0.5vh;
  padding: 0.5vh;
  font-size: 3vh;
  border-radius: 1vh;
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
  margin-bottom: 0.5vh;
  padding: 0.5vh;
  font-size: 3vh;
  border-radius: 1vh;
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
  margin-bottom: 0.5vh;
  padding: 0.5vh;
  font-size: 3vh;
  border-radius: 1vh;
}
.promo-jaune {
  font-size: 4vh; /* Taille de la police */
  font-weight: bold; /* Rend le texte en gras */
  color: #fff; /* Couleur du texte */
  background-color: rgb(238, 234, 32);
  text-align: center;
  margin-right: 70%;
  margin-left: 5%;
  margin-bottom: 0.5vh;
  padding: 0.5vh;
  font-size: 3vh;
  border-radius: 1vh;
}
.promo-default {
  color: white;
}

.icon-panier {
  color: rgb(255, 255, 255); /* Rouge pour une quantitÃ© infÃ©rieure Ã  5 */
  font-size: 6.6vh;

}

</style>
