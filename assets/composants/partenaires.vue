<template>
    <div class="bande-image">
     <img src="/images/fondprofilvue.png" alt="Image descriptive">
  </div>
  <div class="partenaires-container">
    <h1 style="color: white; margin-top: 50px;">Nos partenaires</h1>
    <div class="partenaires-grid">
      <div
        v-for="partenaire in partenaires"
        :key="partenaire.id"
        class="partenaire-card"
        @click="navigateTo(partenaire.url)"
      >
        <img :src="partenaire.logo" :alt="partenaire.nom" class="logo"/>
        <div class="overlay">
          <span>{{ partenaire.nom }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const partenaires = ref([]);

const fetchPartenaires = async (apiUrl) => {
try {
  const response = await fetch(apiUrl);
  if (!response.ok) {
    throw new Error('Réponse du réseau non ok');
  }
  const data = await response.json();
  partenaires.value = data;
} catch (error) {
  console.error("Erreur lors de la récupération des partenaires:", error);
}
};

onMounted(() => {
fetchPartenaires('/api/mobile/getPartenaires');
});

const navigateTo = (url) => {
window.location.href = url;
};

setInterval(() => {
  const allCards = document.querySelectorAll('.partenaire-card');
  if (allCards.length) {
    const randomCard = allCards[Math.floor(Math.random() * allCards.length)];
    randomCard.classList.add('flash');
    setTimeout(() => randomCard.classList.remove('flash'), 1000); // Augmente à 1000ms pour plus de visibilité
  }
}, 2000);
</script>
  
<style lang="scss" scoped>
.bande-image {
  height: 15vw; /* Hauteur fixe pour la bande */
  width: 100vw; /* Largeur basée sur la largeur de la fenêtre */
  overflow: hidden; /* Cache tout contenu qui dépasse de la bande */
  }
.partenaires-container {
  text-align: center;
  width: 80%;
  margin: auto;
  margin-top: 10vh;
}

.partenaires-grid {
  
  display: flex;
  flex-wrap: wrap;
  margin: 2rem; /* ajustez selon vos besoins pour gérer l'espacement entre les cartes */
}

.partenaire-card {
  
  position: relative;
  cursor: pointer;
  flex: 1 0 18%; /* Cela permet à chaque carte de prendre environ 20% de la largeur moins les marges */
  margin: 0.0rem; /* ajustez selon vos besoins pour l'espacement */
}

.partenaire-card img {
  width: 100%;
  height: auto;
  
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(255, 165, 0, 0.5); /* Léger voile orangé */
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.5s;
}

.partenaire-card:hover .overlay {
  opacity: 1;
}

.flash {
  animation: flashAnimation 1s forwards; 
}

@keyframes flashAnimation {
  from { background-color: transparent; }
  to { background-color: rgba(255, 255, 255, 0.5); }
}
</style>

  