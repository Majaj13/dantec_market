<template>
    <div class="bande-image">
     <img src="/images/fondprofilvue.png" alt="Image descriptive">
  </div>
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
        <div class="titre-favori-container">
           <h1>{{ product.nomProduit }}</h1>
           <span class="coeur" v-if="messageStatus">{{ messageStatus }}</span>
           <span v-if="utilisateurAuthentifie">
           <i class="fa fa-heart" :class="{ 'coeur-rouge': estDansLesFavoris, 'coeur-blanc': !estDansLesFavoris }" @click="ajouterAuxFavoris(product.id)"></i>
          </span>
          </div>
        <p v-if="promo.prixpromo">
           Prix Promo: {{ promo.prixpromo }}€
           <span v-if="utilisateurAuthentifie">
              <a href="#" class="icon-panier" @click="ajouterAuPanier(product.id)">
                 <i class="fa fa-shopping-cart"></i> <!-- Icône de panier FontAwesome -->
              </a>
           </span>
           <span v-if="messagePanier">{{ messagePanier }}</span> <!-- Message à côté du panier -->
        </p>
        <p v-else>
           Prix: {{ product.prix }}€
           <span v-if="utilisateurAuthentifie">
              <a  href="#" class="icon-panier" @click="ajouterAuPanier(product.id)">
                 <i class="fa fa-shopping-cart"></i> <!-- Icône de panier FontAwesome -->
              </a>
           </span>
           <span v-if="messagePanier">{{ messagePanier }}</span> <!-- Message à côté du panier -->
        </p>
        <p :class="quantiteClass">{{ product.quantiteDisponible }} article(s) en stock</p>
        <div class="promo-container" v-if="product">
           <!-- ... autres éléments du produit ... -->
           <div v-if="promo.nomCategoriePromo" :class="['promo-capsule', {'nouveaute': promo.nomCategoriePromo === 'Nouveaute','flash': promo.nomCategoriePromo === 'Flash', 'promo': promo.nomCategoriePromo === 'Promo'}]">
              {{ promo.nomCategoriePromo }}
           </div>
           <!-- Afficher le temps restant en dehors de la capsule de promotion -->
           <div v-if="promo.fin" class="temps-restant">
              Il vous reste : {{ calculerTempsRestant(promo.fin) }}
           </div>
        </div>
        <div class="rating"> Avis : 
           <span v-for="star in 5" :key="star" class="star"> 
           <i class="fa fa-star" :class="{'is-active': star <= product.etoiles}"></i>
           </span>sur {{ product.nbvotes }} avis
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
  import { ref,watch, onMounted } from 'vue';
  import { Swiper, SwiperSlide } from 'swiper/vue';
  import 'swiper/css';
  
  export default {
    
    components: {
      Swiper,
      SwiperSlide,
    },
    setup() {
      const messageStatus = ref('');
  
      const product = ref(produitData);
      const promo = ref(null); // Pour stocker les données de promotion
      const loading = ref(false);
      const error = ref(null);
      const selectedImage = ref('');
      const messagePanier = ref('');
      const utilisateurAuthentifie = ref(false);
      const estDansLesFavoris = ref(false); // Pour stocker l'état de favori
      
  
      //gestion du coeur
      const verifierSiFavori = async (produitId) => {
    try {
        const reponse = await fetch('/api/mobile/versverifierfavoris', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: produitId }),
        });
        if (reponse.ok) {
            const data = await reponse.json();
            estDansLesFavoris.value = data.estFavori;
        } else {
            console.error('Erreur lors de la vérification des favoris');
            estDansLesFavoris.value = false;
        }
    } catch (erreur) {
        console.error('Erreur lors de la communication avec l\'API', erreur);
        estDansLesFavoris.value = false;
    }
  };
  
  //gestion du favoris
  const ajouterAuxFavoris = async (produitId) => {
      try {
        const response = await fetch('/api/mobile/Ajoutfavori', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({id: produitId }),
        });
  
        if (response.ok) {
          // Mise à jour de l'état en cas de succès
          messageStatus.value = 'Produit ajouté aux favoris avec succès.';
        } else {
          // Mise à jour de l'état en cas d'échec
          messageStatus.value = 'Erreur lors de l\'ajout du produit aux favoris.';
        }
      } catch (erreur) {
        console.error('Erreur lors de l’ajout aux favoris', erreur);
        messageStatus.value = 'Erreur de communication avec l\'API.';
      }
    };
  //fin gestion favoris
  const verifierAuthentification = async () => {
      try {
        const reponse = await fetch('/api/mobile/user');
        if (reponse.status === 200) {
          utilisateurAuthentifie.value = true;
        } else {
          utilisateurAuthentifie.value = false;
        }
      } catch (erreur) {
        console.error('Erreur lors de la vérification de l’authentification', erreur);
      }
    };
  
  //Gestion du panier
  const ajouterAuPanier = async (produitId) => {
  try {
    const prixRetenu = promo.value.prixpromo>0  ? promo.value.prixpromo : product.value.prix;
  
    const reponse = await fetch('/api/mobile/AjoutProduitCommande', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ produitId: produitId, quantite: 1, prix: prixRetenu }),
    });
  
    if (reponse.ok) {
      messagePanier.value = 'Produit ajouté';
    } else {
      messagePanier.value = 'Produit non ajouté';
    }
  } catch (error) {
    console.error('Erreur:', error);
    messagePanier.value = 'Erreur lors de l’ajout du produit';
  }
  };
  
    // fin de gestion du panier
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
  
    watch(() => product.value, (nouveauProduit) => {
      if (nouveauProduit && nouveauProduit.id) {
        verifierSiFavori(nouveauProduit.id);
        if (nouveauProduit.lesImages.length > 0) {
          selectedImage.value = '/' + nouveauProduit.lesImages[0].url;
        }
      }
    }, { immediate: true });
  
      onMounted(() => {
        fetchProductDetails();
        fetchPromoDetails();
        verifierAuthentification();
        verifierSiFavori(product.value.id);
        
      });
  
      return {estDansLesFavoris, ajouterAuxFavoris, messageStatus ,product,formatDate,ajouterAuPanier,utilisateurAuthentifie, messagePanier  , promo ,loading, error,calculerTempsRestant,isPromoActive ,selectImage, selectedImage, onSwiper, onSlideChange,};
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
<style lang="scss" scoped>
.bande-image {
  height: 15vw; /* Hauteur fixe pour la bande */
  width: 100vw; /* Largeur basée sur la largeur de la fenêtre */
  overflow: hidden; /* Cache tout contenu qui dépasse de la bande */
  }
  .bande-image img {
  width: 100%; /* Assure que l'image s'étend sur toute la largeur */
  height: 100%; /* Assure que la hauteur de l'image remplit la bande */
  object-fit: cover; /* Assure que l'image couvre la zone sans être déformée */
  }
  .rating .fa-star {
  font-size: 1em; /* Définit la taille des étoiles à 10px */
  }
  .rating span.star {
  margin-right: 3px; /* Réduit l'espace à droite de chaque étoile */
  }
  .star i {
  color: gray; /* Couleur par défaut des étoiles */
  }
  .star i.is-active {
  color: gold; /* Couleur des étoiles sélectionnées */
  }
  .star.disabled {
  pointer-events: none; /* Désactive les événements de souris */
  opacity: 0.5; /* Rend l'étoile semi-transparente */
  }
  .coeur{
  margin-left: auto;
  }
  .carousel-container{
  margin-top: 10px;
  }
  .product-container {
  display: flex;
   background-color: #2D2D2D;
  color: white;
  padding-top: 5vh;
  padding-bottom: 50px;
  }
  .promo-container {
  display: flex;
  background-color: #2D2D2D;
  color: white;
  padding-bottom: 10px;
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
  .swiper-slide img {
  display: block;
  width: 60%;
  -webkit-box-reflect: below 1px linear-gradient(transparent, transparent , #0002 , #0004);
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
  .nouveaute{
  background-color: rgb(38, 20, 168);
  }
  .titre-favori-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%; /* Assure que le conteneur occupe toute la largeur */
  }
  .fa-heart {
  margin-left: auto;
  cursor: pointer; /* Ajoute une indication visuelle que l'icône est cliquable */
  }
  .avis-container {
  margin-left: 0px;
  background-color: #2D2D2D;
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
  font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
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
  font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  font-size: 5.5vh;
  font-style: normal;
  font-weight: 700;
  line-height: normal;
  }
  .coeur-rouge {
  color: red;
  }
  .coeur-blanc {
  color: white;
  }
</style>