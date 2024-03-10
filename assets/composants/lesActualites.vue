<template>
    <div>
        <div class="bienvenue-actu">
            <h1>Bienvenue sur l’actualité de Dantec Market</h1>
            <p>Ici nous traiteront de l’actualité sur le magasin et sur tout ce qui l’entoure...</p>
        </div>
        <div v-if="loading">Chargement...</div>
        <div v-else-if="error">{{ error }}</div>
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
                    <div class="image-container">
                        <img :src="actus[0].image" alt="Image">
                    </div>
                </div>
            </div>

            <div class="mini-actu-container">
                <p>Découvrez nos récentes actualités:</p>
                <div v-for="(actu, index) in actus.slice(1, 4)" :key="actu.id" class="card-actu">
                    <img :src="actu.lesimages[0].url" alt="Image de l'actualité">
                    <div class="contenu">
                        <h1>{{ actu.titre }}</h1>
                        <p>{{ actu.texte }}</p>
                    </div>
                    <a :href="`/actualite/voiractu/${actu.id}`">Lire plus</a>
                </div>
                <button class="boutonActu">Plus d'actualités</button>
            </div>
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

        onMounted(() => {
            fetchActus();
        });

        return { actus, loading, error,};
    }
};
</script>


<style scoped>
    /* Page actu */

.bienvenue-actu {
    width: 100%;
    height: 75%;
    background-image: url('/public/images/dantecMarket2.1.jpg');
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
    align-items: center;
    justify-content: space-between;
    width: 100%;
    height: 75vh;
    box-sizing: border-box;
    margin-bottom: 1vh;
    background-color: #E3E3E3; /* Couleur de fond des conteneurs */
    margin-top: 20vh; /* Ajout de la marge en haut (33% de la hauteur de l'écran) */
}

.text-container {
    width: 50%; /* Réserve 50% de l'espace pour le texte */
    padding: 0 20px; /* Espacement pour le texte */
}

.image-container {
    width: 45%; /* Réserve 45% de l'espace pour l'image */
}

.inner-container img {
    width: 100%;
    height: 100%;
    background-repeat: no-repeat;
    background-size: cover;
}

.inner-container h1,
.inner-container p {
    font-family: 'Inter Black';
}

.inner-container h1 {
    font-size: 7.5vh;
    font-weight: 600;
    color: #FAFAFA;
    text-shadow: 0px 0.4vh 0.4vh rgba(0, 0, 0, 0.25);
    margin-top: 0; /* Supprimer la marge par défaut */
}

.inner-container p {
    font-size: 4vh;
    font-weight: 600;
    color: #000000;
    margin: 8vh 0; /* Espacement avec le bouton */
}

.inner-container button {
    width: 40vh;
    height: 8vh;
    background: #DFBA61;
    border-radius: 5vh;
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
    justify-content: center;
    align-items: center;
    flex-wrap: wrap; /* Permet aux cartes d'avis de passer à la ligne si l'espace est insuffisant */
    margin-top: 2vh; /* Ajoutez une marge en haut pour l'espace entre les cartes d'avis et les autres conteneurs */
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