<template>
  <div class="bande-image">
     <img src="/images/fondprofilvue.png" alt="Image descriptive">
  </div>

  <div class="container">
    <div v-if="loading" class="loading">Chargement...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else>
      <div v-if="actu">
        <h1>{{ actu.titre }}</h1>
        <div class="content">
          <p>{{ truncatedText }}</p>
          <img ref="actuImage" :src="actu.images[0].url" alt="Image">
        </div>
        <div class="remaining-text" v-if="remainingText">
          <p>{{ remainingText }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="mini-actu-container">
      <p>Découvrez nos récentes actualités:</p>
      <div v-for="(actua, index) in actus.slice(1, 4)" :key="actua.id" class="card-actu">
        <img :src="actua.lesimages[0].url" alt="Image de l'actualité">
        <div class="contenu">
          <h1>{{ actua.titre }}</h1>
          <p>{{ actua.texte }}</p>
        </div>
        <a>Lire plus</a>
      </div>
      <button class="boutonActu">Plus d'actualités</button>
    </div>
</template>
  
<script>
import { ref, onMounted, computed } from 'vue';

export default {
  setup() {
    const actu = ref(null);
    const actus = ref([]);
    const loading = ref(false);
    const error = ref(null);
    const actuImage = ref(null); // Référence pour l'image

    const fetchActu = async (id) => {
      try {
        loading.value = true;
        const response = await fetch(`/api/mobile/actualites/${id}`);
        if (!response.ok) throw new Error('Erreur de chargement des données.');
        actu.value = await response.json();
      } catch (e) {
        error.value = e.message;
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      const id = window.location.pathname.split('/').pop();
      fetchActu(id);
      fetchActus();
    });

    const fetchActus = async () => {
      try {
        loading.value = true;
        const response = await fetch('/api/mobile/getLesActualites');
        if (!response.ok) throw new Error('Erreur de chargement des données.');
        actus.value = await response.json();
      } catch (e) {
        error.value = e.message;
      } finally {
        loading.value = false;
      }
    };

    const estimateLinesPerPixel = 0.1; // Estimation du nombre de lignes par pixel
    const maxLines = 20; // Nombre maximum de lignes que vous voulez afficher

    const truncatedText = computed(() => {
      if (actu.value && actuImage.value) {
        const maxTextLines = Math.min(
          Math.floor(actuImage.value.height * estimateLinesPerPixel),
          maxLines
        );
        const textLines = actu.value.texte.split('\n'); // Sépare le texte par lignes
        return textLines.slice(0, maxTextLines).join('\n'); // Sélectionne les premières lignes
      }
      return '';
    });

    const remainingText = computed(() => {
      if (actu.value && actuImage.value) {
        const maxTextLines = Math.min(
          Math.floor(actuImage.value.height * estimateLinesPerPixel),
          maxLines
        );
        const textLines = actu.value.texte.split('\n');
        if (textLines.length > maxTextLines) {
          return textLines.slice(maxTextLines).join('\n'); // Sélectionne les lignes restantes
        }
      }
      return '';
    });

    return { actu, loading, error, truncatedText, remainingText, actus, actuImage };
  }
};
</script>
  
<style scoped lang="scss">

.container {
  max-width: 80%;
  margin: 0 auto;
  padding: 2vh;
  h1 {
  font-size: 9.6vh;
  font-weight: bold;
  margin-top: 40vh;
}
}

.content {
  display: flex;
  align-items: flex-start;
  margin-bottom: auto;
}

.content p {
  font-size: 3.5vh;
  flex: 1;
}

.content img {
  width: 85vh;
  height: auto;
  margin-left: 2vh;
}

.remaining-text {
  font-size: 3.5vh;
  margin-top: 2vh;
}



.mini-actu-container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap; /* Permet aux cartes d'avis de passer à la ligne si l'espace est insuffisant */
    margin-top: 60vh; /* Ajoutez une marge en haut pour l'espace entre les cartes d'avis et les autres conteneurs */
}

.mini-actu-container p{
    height: 20%;
    color: #E3E3E3;
    font-size: 7.5vh;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin-top: 2vh;
    margin-bottom: 5vh;
    margin-right: 30vh;
}

.card-actu {
    width: 30%;
    height: 65vh;
    flex-shrink: 0;
    background: #E3E3E3;
    margin-right: 5vh; /* Ajustez la marge selon vos besoins pour l'espace entre les cartes d'avis */
    margin-bottom: 1vh; /* Ajoutez une marge en bas pour l'espace entre les lignes */
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Ajuste l'espace entre les éléments à l'intérieur de la carte */
    box-shadow: 0.4vh 0.4vh rgba(0, 0, 0, 0.25);
}


.boutonActu {
    width: 64.6vh;
    height: 12.2vh;
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
    align-items: center;
    justify-content: center;
    display: flex;
    margin: 5vh 0vh;
    cursor:pointer;
}

.card-actu img {
    width: 100%;
    height: 30vh;
    object-fit: cover; /* Ajuste l'image pour couvrir tout l'espace disponible sans déformation */
}

.card-actu a {
    width: 28.5vh;
    height: 6.8vh;
    flex-shrink: 0;
    background: #DFBA61;
    border-radius: 5vh;
    border: 2px solid #000; /* Ajout de la bordure noire */
    text-decoration: none;
    color: #2D2D2D;
    text-align: center;
    font-family: Inter;
    font-size: 3.5vh;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    align-items: center;
    justify-content: center;
    display: flex;
    margin: auto;
    margin-bottom: 1.5vh;
    cursor:pointer;
}

.contenu {
    height: auto;
    overflow: hidden;
    padding: 1.5vh;
}

.contenu h1 {
    color: #FAFAFA;
    font-size: 7vh;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    word-wrap: break-word;
    -webkit-text-stroke: 1px #000; /* Contour d'une épaisseur de 1 pixel avec une couleur noire */
    text-align: center;
}

.contenu p {
    width: 100%;
    height: 50%;
    color: #000000;
    font-size: 2.5vh;
    font-style: normal;
    font-weight: 650;
    line-height: 1.5;
    word-wrap: break-word;
    overflow: hidden; /* Empêche le texte de déborder */
    text-overflow: ellipsis; /* Affiche des points de suspension (...) lorsque le texte déborde */
    display: -webkit-box; /* Utilisé pour contrôler le nombre de lignes */
    -webkit-line-clamp: 3; /* Limite le contenu à 3 lignes */
    -webkit-box-orient: vertical; /* Indique que le contenu est empilé verticalement */
}

</style>