<template>
    <div class="product-container" v-if="product">
      <div class="product-image">
        <!-- Affichage de la première image du produit, si disponible -->
        <img :src="selectedImage" />
        <div class="carousel-container">
         
          <Swiper :slides-per-view="2" :space-between="20">
  <SwiperSlide v-for="(image, index) in product.lesImages" :key="index">
    <img :src="'/' + image.url" @click="selectImage(image.url)" />
  </SwiperSlide>
</Swiper>
            
            </div>
      </div>
     
    
      <div class="product-details">
         <h1>{{ product.nomProduit }}</h1>
         <p v-if="promo.prixpromo">
  Prix Promo: {{ promo.prixpromo }}€
  <a href="/chemin-vers-le-panier" class="icon-panier">
    <i class="fa fa-shopping-cart"></i> <!-- Icône de panier FontAwesome -->
  </a>
</p>
<p v-else>
  Prix: {{ product.prix }}€
  <a href="/chemin-vers-le-panier" class="icon-panier">
    <i class="fa fa-shopping-cart"></i> <!-- Icône de panier FontAwesome -->
  </a>
</p>

        <p :class="quantiteClass">{{ product.quantiteDisponible }} article(s) en stock</p>
        <div class="promo-container" v-if="product">
  <!-- ... autres éléments du produit ... -->

  <div v-if="promo.nomCategoriePromo" :class="['promo-capsule', {'flash': promo.nomCategoriePromo === 'Flash', 'promo': promo.nomCategoriePromo === 'Promo'}]">
    {{ promo.nomCategoriePromo }}
  </div>

  <!-- Afficher le temps restant en dehors de la capsule de promotion -->
  <div v-if="promo.fin" class="temps-restant">
  Il vous reste : {{ calculerTempsRestant(promo.fin) }}
  </div>
</div>
        <p>Description :</p>
        <p>{{ descriptionTronquee }}</p>
      </div>
</div> 

<div class="avis-container">
      <p>Nos élèves sont ceux qui en parlent le mieux:</p>
      <div v-for="comment in product.lesCommentaires" :key="comment.id" class="card-avis">
                <div class="etoiles" :data-rating="comment.note">
                    <p v-for="i in 5" :key="i" :id="i" :class="{ 'active': i <= comment.note }">★</p>
                </div>
                <div class="titre">{{ comment.commentaire }}</div>
                <div class="date">{{ formatDate(comment.dateCommentaire) }}</div>
      </div>       
      </div>  

  </template>

  <script>
  import { ref, onMounted } from 'vue';
  import { Swiper, SwiperSlide } from 'swiper/vue';
  import 'swiper/css';

  export default {
    
    components: {
      Swiper,
      SwiperSlide,
    },
    setup() {
      const product = ref(produitData);
      const promo = ref(null); // Pour stocker les données de promotion
      const loading = ref(false);
      const error = ref(null);
      const selectedImage = ref('');

      const selectImage = (url) => {
      selectedImage.value = '/' + url;
    };

    const formatDate = (date) => {
      const d = new Date(date);
      return d.toISOString().split('T')[0]; // Formate la date en 'YYYY-MM-DD'
    };

    const onSwiper = (swiper) => {
        console.log(swiper);
      };
      const onSlideChange = () => {
        console.log('slide change');
      };

      const isPromoActive = async() => {
      const maintenant = new Date();
      const finPromo = promo.value ? new Date(promo.value.fin) : null;
      return promo.value && finPromo >= maintenant;
    };
      
      const fetchProductDetails = async () => {
        try {
          loading.value = true;
          
          const payload = JSON.stringify({ id: product.value });
          const requestOptions = {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: payload
          };
          const response = await fetch('/api/mobile/getProduit', requestOptions);
          if (!response.ok) throw new Error('Erreur de chargement du produit');
          product.value = await response.json();
          if (product.value && product.value.lesImages.length > 0) {
          selectedImage.value = '/' + product.value.lesImages[0].url;
        }
        } catch (e) {
          error.value = e.message;
        } finally {
          loading.value = false;
        }
      };
      const fetchPromoDetails = async () => {
      try {
        const payload = JSON.stringify({ id: product.value });
        const response = await fetch('/api/mobile/GetPromo', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: payload
        });

        if (response.ok) {
          const promoData = await response.json();
          if (promoData && promoData.length > 0) {
            promo.value = promoData[0];
          }
        }
      } catch (e) {
        console.error("Erreur lors de la récupération des détails de la promotion", e);
      }
    };
    const calculerTempsRestant = (dateFin) => {
      const maintenant = new Date();
      const fin = new Date(dateFin);
      const tempsRestant = fin - maintenant;

      // Convertissez `tempsRestant` en une structure lisible (jours, heures, minutes, secondes)
      let secondes = Math.floor(tempsRestant / 1000);
      let minutes = Math.floor(secondes / 60);
      let heures = Math.floor(minutes / 60);
      let jours = Math.floor(heures / 24);

      heures = heures % 24;
      minutes = minutes % 60;
      secondes = secondes % 60;

      return `${jours} jours, ${heures} heures, ${minutes} minutes, ${secondes} secondes`;
    };
  
      onMounted(() => {
        fetchProductDetails();
        fetchPromoDetails();
        
      });
  
      return { product,formatDate , promo ,loading, error,calculerTempsRestant,isPromoActive ,selectImage, selectedImage, onSwiper, onSlideChange,};
    },
    computed: {
    quantiteClass() {
      if (this.product.quantiteDisponible > 10) {
        return 'quantite-elevee'; // Classe pour quantité > 10
      } else if (this.product.quantiteDisponible >= 5 && this.product.quantiteDisponible <= 10) {
        return 'quantite-moyenne'; // Classe pour quantité entre 5 et 10
      } else {
        return 'quantite-faible'; // Classe pour quantité < 5
      }
    },
    descriptionTronquee() {
      if (this.product.description && this.product.description.length > 600) {
        return this.product.description.substring(0, 600) + '...';
      }
      return this.product.description;
    }
  
  }
      
  };
  </script>
<style>

.product-container {
  display: flex;
  background-color: black;
  color: white;
  padding-top: 140px;
  padding-bottom: 50px;
}
.promo-container {
  display: flex;
  background-color: black;
  color: white;
  padding-bottom: 20px;
  
}

.product-image {
  width: 40%; /* Modifiez la largeur à 40% pour l'image */
  padding-left: 50px;
  padding-right: 50px;
  overflow: hidden; /* Empêche le débordement du contenu */
}

.product-image img {
  max-width: 100%;  /* Assure que la largeur de l'image ne dépasse pas celle du conteneur */
  height: auto;     /* Maintient l'aspect ratio de l'image */
}
.product-details {
  width: 60%; /* Largeur totale de la section des détails du produit */
  
}

.product-details * {
  text-align: justify; /* Justifie le texte des paragraphes */
  max-width: 80%; /* Limite la largeur maximale du contenu textuel à 60% de la section */

}

.carousel-container {
  margin-right: 20px; /* Ajoute une marge droite de 50px */
    margin-top: 20px; /* Espacement de 20px entre l'image et le carrousel */

}

.swiper img{
  cursor: pointer;

}

.product-details h1 {
  color: rgba(195, 195, 125, 0.5); 
  margin-bottom: 10px; /* Ajoutez ou ajustez la marge en bas du titre si nécessaire */
  border-bottom: 1px solid green; /* Crée un filet de 1px de couleur verte */
  padding-bottom: 10px; /* Ajoutez un peu d'espace entre le filet et le texte qui suit */
}

.product-details p:nth-of-type(1) { /* Cible spécifiquement le paragraphe du prix */
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: white; /* Définit la couleur du texte en blanc */
  font-size: 30px; /* Définit la taille de la police à 4% de la hauteur de la fenêtre de visualisation */
  font-weight: bold; /* Rend le texte en gras */
}
.product-details p:nth-of-type(3) { /* Cible spécifiquement le paragraphe du prix */
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: white; /* Définit la couleur du texte en blanc */
  font-size: 30px; /* Définit la taille de la police à 4% de la hauteur de la fenêtre de visualisation */
  font-weight: bold; /* Rend le texte en gras */
}
.quantite-elevee {
  color: green; /* Vert pour une quantité supérieure à 10 */
  font-size: 24px; /* Définit la taille de la police à 4% de la hauteur de la fenêtre de visualisation */
  font-weight: bold; /* Rend le texte en gras */

}

.quantite-moyenne {
  color: yellow; /* Jaune pour une quantité entre 5 et 10 */
  font-size: 24px; /* Définit la taille de la police à 4% de la hauteur de la fenêtre de visualisation */
  font-weight: bold; /* Rend le texte en gras */

}

.quantite-faible {
  font-size: 24px; /* Définit la taille de la police à 4% de la hauteur de la fenêtre de visualisation */
  font-weight: bold; /* Rend le texte en gras */

  color: red; /* Rouge pour une quantité inférieure à 5 */
}
.icon-panier {
  color: rgb(255, 255, 255); /* Rouge pour une quantité inférieure à 5 */

}

.promo-capsule {
  padding: 5px;
  border-radius: 10px;
  color: white;
  margin-right: 15px; /* Ajoute une marge en bas de la capsule */

}
.temps-restant {
  margin-top: 6px; /* Ajoute une marge au-dessus du texte du temps restant */
  /* Autres styles si nécessaire */
}

.flash {
  background-color: rgb(17, 128, 63);
}

.promo {
  background-color: rgb(209, 26, 26);
}

.titre-container {
    display: flex;
    justify-content: left;
    align-items: left;
    flex-wrap: wrap; /* Permet aux cartes d'avis de passer à la ligne si l'espace est insuffisant */
    margin-top: 2vh; /* Ajoutez une marge en haut pour l'espace entre les cartes d'avis et les autres conteneurs */
margin-left: 5vh;
}

.avis-container {
  margin-left: 0px;
  background-color: #000;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap; /* Permet aux cartes d'avis de passer à la ligne si l'espace est insuffisant */
    margin-top: 2vh; /* Ajoutez une marge en haut pour l'espace entre les cartes d'avis et les autres conteneurs */

}

.avis-container p{
    height: 20%;
    color: #ffffff;
    font-size: 6.5vh;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin-top: 2vh;
    margin-bottom: 5vh;
    margin-right: 30vh;
}

.titre-container p{
    height: 20%;
    color: #ffffff;
    font-size: 6.5vh;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin-top: 2vh;
    margin-bottom: 5vh;
    margin-right: 30vh;
}

.card-avis {
    width: 25%;
    height: 50vh;
    flex-shrink: 0;
    background: #E3E3E3;
    margin-right: 5vh; /* Ajustez la marge selon vos besoins pour l'espace entre les cartes d'avis */
    margin-bottom: 1vh; /* Ajoutez une marge en bas pour l'espace entre les lignes */
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Ajuste l'espace entre les éléments à l'intérieur de la carte */
    box-shadow: v 0.4vh 0.4vh rgba(0, 0, 0, 0.25);
}

.titre,
.avis,
.date,
.etoiles {
    width: 100%;
    font-family: Inter;
}

.titre {
    height: 15%;
    font-size: 3.6vh;
    font-weight: 700;
    color: #000000;
    text-align: center;
}

.avis {
    height: 60%;
    font-size: 3.6vh;
    font-weight: 500;
    color: #000;
    text-align: center;
}

.date {
    height: 5%;
    font-size: 2vh;
    font-weight: 500;
    color: #000;
    text-align: right;
}

.etoiles {
    height: 20%;
    display: flex;
    position: relative;
}

.etoiles[data-rating="5"] p[id="1"],
.etoiles[data-rating="5"] p[id="2"],
.etoiles[data-rating="5"] p[id="3"],
.etoiles[data-rating="5"] p[id="4"],
.etoiles[data-rating="5"] p[id="5"] {
    color: #DFBA61;
}

.etoiles[data-rating="4"] p[id="1"],
.etoiles[data-rating="4"] p[id="2"],
.etoiles[data-rating="4"] p[id="3"],
.etoiles[data-rating="4"] p[id="4"] {
    color: #DFBA61;
}

.etoiles[data-rating="3"] p[id="1"],
.etoiles[data-rating="3"] p[id="2"],
.etoiles[data-rating="3"] p[id="3"] {
    color: #DFBA61;
}

.etoiles[data-rating="2"] p[id="1"],
.etoiles[data-rating="2"] p[id="2"] {
    color: #DFBA61;
}

.etoiles[data-rating="1"] p[id="1"] {
    color: #DFBA61;
}

.etoiles p {
    color: #D9D9D9;
    -webkit-text-stroke: 0.2vh #2D2D2D;
    filter: drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.25));
    text-decoration: none;
    font-size: 5vh;
    margin: 0 0.2vh;
}

.etoiles p:not(:first-child) {
    margin-left: 0.5vh; /* Ajustement de la marge à gauche pour les étoiles suivantes */
}

.boutonAvis {
    margin: 6vh 0vh;
    align-items: center;
}

.boutonAvis button{
    width: 64.6vh;
    height: 15.2vh;
    flex-shrink: 0;
    background: rgba(223, 186, 97, 0.80);
    border-radius: 5vh;
    text-decoration: none;
    color: #2D2D2D;
    text-align: center;
    font-family: Inter;
    font-size: 5.5vh;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}


</style>

  