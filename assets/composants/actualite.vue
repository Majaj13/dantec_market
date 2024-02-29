<template>
    <div>
       <div class="bienvenue-actu">
          <h1>Quoi de neuf chez Dantec Market</h1>
          <p>Ici nous traiterons de l’actualité du magasin et de ce qui l’entoure...</p>
       </div>
       <div v-if="loading">Chargement...</div>
       <div v-else-if="error">Erreur de chargement des données.</div>
       <div v-else>
          <div v-if="actus.length > 0" class="container-actu">
            <div class="inner-container">
   <div class="text-container">
      <h1>{{ actus[0].titre }}</h1>
      <p>{{ actus[0].texte }}</p>
      <form :action="`/actualite/voiractu/${actus[0].id}`">
         <button type="submit">Lire plus</button>
      </form>
   </div>
   <div class="divider"></div> <!-- Barre verticale -->
  
 
   <div class="image-container">
      
      <img v-for="image in actus[0].lesimages" :key="image.id" :src="'/' + image.url" alt="Image de l'actualité">
   </div>
</div>
          </div>
          <div> <hr class="separateur" /> <!-- Trait horizontal --></div>
  <div> <p>Découvrez nos récentes actualités:</p></div>
          <div class="mini-actu-container">
 
            <div v-for="actu in (showAll ? actus : actus.slice(1, 5))" :key="actu.id" class="card-actu">
      
      <img :src="'/' + actu.lesimages[0].url" alt="Image de l'actualité" class="image-actu">
      <div class="miniactutitre">{{ actu.titre }}</div>
     
       <div class="contenu">
         <p>{{ actu.texte }}</p>
      </div>
      <hr class="separateur" /> <!-- Trait horizontal -->

   </div>
  
</div>
<div><button class="boutonActu" @click="toggleShowAll">Plus d'actualités</button></div>
       </div>
    </div>
 </template>
 <script>
    import { ref, onMounted } from 'vue';
    
    export default {
      setup() {
        const actus = ref([]);
        const loading = ref(false);
        const error = ref(null);
        const showAll = ref(false); // Variable pour contrôler l'affichage

    
        const fetchActus = async () => {
      try {
        const response = await fetch('/api/mobile/getLesActualites');
        if (!response.ok) throw new Error('Erreur de chargement des catégories');
        actus.value = await response.json();
      } catch (e) {
        error.value = e.message;
      }
    };

    const toggleShowAll = () => {
      showAll.value = !showAll.value; // Bascule l'état pour afficher toutes les actualités
    };
    
        onMounted(() => {
            fetchActus();
        });
    
        return { actus, loading, error, showAll, toggleShowAll};
      }
    };
 </script>
 <style scoped>
    /* Page actu */
    .bienvenue-actu {
    width: 100%;
    height: 75%;
    background-image: url('/public/images/dantecMarketactu.jpg');
    background-repeat: no-repeat;
    background-size: cover;
    }
    .bienvenue-actu h1,
    .bienvenue-actu p {
    position: relative;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 112.6vh;
    height: 28.4vh;
    flex-shrink: 0;
    color: #FAFAFA;
    text-align: center;
    font-family: 'Inter Black';
    text-shadow: 0px 0.4vh 0.4vh rgba(0, 0, 0, 0.25);
    justify-content: center;
    align-items: center;
    }
    .bienvenue-actu h1 {
    font-size: 9.6vh;
    font-weight: 600;
    }
    .bienvenue-actu p {
    font-size: 5vh;
    font-weight: 600;
    }
    .container-actu {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    }
    .inner-container {
      display: flex;
   
   justify-content: space-between;
   width: 100%;
   height: 75vh;
   background-color: #2D2D2D; /* Fond noir */
   padding: 10px; /* Padding général */
    }
    .text-container {
   background-color:(158, 152, 152)#b3b3b3;
   width: 45%; /* Ajustement pour le padding */
   padding-top : 20px; /* Espace entre le texte et la barre verticale */
   color: white; /* Pour une meilleure lisibilité sur fond noir */
   text-align: justify; /* Justification du texte */
}
.divider {
   width: 1px;
   background-color: white; /* Barre verticale blanche */
   height: 100%;
}
.image-container {
   width: 45%; 
   height: 100%;
   padding: 20px; /* Espace entre la barre verticale et l'image */
}

.image-container img {
   width: 100%; /* Assure que l'image prend toute la largeur du conteneur */
   height: 100%; /* Ajuste la hauteur automatiquement pour maintenir le ratio */
   object-fit: cover; /* Couvre l'espace disponible sans déformation, peut être changé en 'contain' pour s'assurer que toute l'image est visible */
   border: none; /* Optionnel: enlève toute bordure autour de l'image */
   padding: 0; /* Enlève le padding autour de l'image, si nécessaire */
}


    .inner-container h1 {
    font-size: 5.5vh;
    font-weight: 400;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    color: #FAFAFA;
    text-shadow: 0px 0.4vh 0.4vh rgba(0, 0, 0, 0.25);
    margin-top: 0; /* Supprimer la marge par défaut */
    }
    .inner-container p {
      font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    font-size: 3vh;
    color: 
    #ffffff;
    margin: 8vh 0; /* Espacement avec le bouton */
    }
    .inner-container button {
    width: 40vh;
    height: 8vh;
    background: #DFBA61;
    border-radius: 2vh;
    text-decoration: none;
    color: #2D2D2D;
    text-align: center;
    font-family: Inter;
    font-size: 3.8vh;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin: auto;
    display: inherit;
    cursor:pointer;
    }
    .mini-actu-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between; /* Assure une répartition égale de l'espace autour des éléments */
}
.mini-actu-container button {
    width: 40vh;
    height: 8vh;
    background: #DFBA61;
    border-radius: 2vh;
    text-decoration: none;
    color: #2D2D2D;
    text-align: center;
    font-family: Inter;
    font-size: 3.8vh;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin: auto;
    display: inherit;
    cursor:pointer;
    }
.miniactutitre{
   padding-top: 5vh;
   padding-left: 10vh;
   padding-right: 10vh;
   font-size: 5.5vh;
    font-weight: 400;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    color: #FAFAFA;
    text-shadow: 0px 0.4vh 0.4vh rgba(0, 0, 0, 0.25);
    margin-top: 0; /* Supprimer la marge par défaut */
}
    .separateur {
   width: 80%; /* 80% de la largeur du conteneur */
   border-top: 1px solid white; /* Un trait horizontal blanc */
   margin: 20px auto; /* Centré avec une marge au-dessus et en dessous */
}

.mini-actu-container p {
   text-align: center; /* Centre le texte du paragraphe */
   color: white; /* Assurez-vous que la couleur du texte est blanche pour une meilleure visibilité */
}

.card-actu {
 
   display: flex;
  flex-direction: column;
  justify-content: space-between; /* Ajoute de l'espace entre le contenu et le bouton, poussant le bouton vers le bas */
  flex-basis: 45%;
  margin: 10px;
  max-width: 45%;
  height: 100%; /* Assurez-vous que cela fonctionne pour votre mise en page, vous devrez peut-être ajuster cette valeur */
  margin-bottom: 0vh;
}

.image-actu {
   width: 80%; /* 80% de la largeur du conteneur de la carte */
   margin: 10px auto; /* Centré avec une marge au-dessus et en dessous */
   display: block; /* Assure que l'image est traitée comme un bloc pour le centrage */
}

.contenu p {
  height: 30vh; /* Définit la hauteur du conteneur à 20% de la hauteur de la fenêtre */
  overflow: auto; /* Ajoute une barre de défilement si le contenu dépasse la hauteur du conteneur */
  text-align: justify;
  padding: 10vh 10vh;
}


.boutonActu {
   display: block; /* Assure que le bouton est un bloc pour le centrage */
   width: auto; /* Permet au bouton de s'adapter au texte */
   margin: 20px auto; /* Centré avec une marge au-dessus et en dessous */
}
 </style>