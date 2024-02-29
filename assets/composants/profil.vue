<template>
  <div class="bande-image">
     <img src="/images/fondprofilvue.png" alt="Image descriptive">
  </div>
  <div class="capsules">
     <div class="capsule" @click="loadContentData('compte')">
      <img src="/images/profil.png"  alt="Icone Compte" class="image-circulaire rotation-base rotation-moderate">

      Compte</div>
     <div class="capsule" @click="loadContentData('commandes')">
      <img src="/images/commande.png" alt="Icone Compte" class="image-circulaire rotation-base rotation-moderate">
Commandes</div>
     <div class="capsule" @click="loadContentData('liste')">
      <img src="/images/favoris.png" alt="Icone Compte" class="image-circulaire rotation-base rotation-moderate">
liste</div>
     <div class="capsule" @click="loadContentData('fidelite')">
      <img src="/images/fidelite.png" alt="Icone Compte" class="image-circulaire rotation-base rotation-moderate">
Fidélité</div>
     <!-- Ajoutez d'autres capsules si nécessaire -->
  </div>
  <div class="ligne-blanche"></div>
  <div class="content">
     <!-- Affichez les détails du compte -->
     <div v-if="activeContent === 'compte'" class="carte-identite">
        <!-- Image de profil -->
        <div class="profil-container">
           <img :src="'/' + compteData.photoUrl" alt="Photo de profil" class="photo-profil">
        </div>
        <!-- Informations utilisateur -->
        <div class="info-utilisateur">
           <form @submit.prevent="modifierUtilisateur">
              <p><strong>Nom :</strong> <input v-model="compteData.nom" placeholder="Nom"></p>
              <p><strong>Prénom :</strong> <input v-model="compteData.prenom" placeholder="Prénom"></p>
              <p><strong>Email :</strong> <input v-model="compteData.email" type="email" placeholder="Email"></p>
              <p><strong>Téléphone :</strong> <input v-model="compteData.telephone" placeholder="Téléphone"></p>
              <p><strong>Classe :</strong> <input v-model="compteData.classe" placeholder="Classe"></p>
              <button type="submit">Modifier</button>
           </form>
        </div>
     </div>
     <div v-if="activeContent === 'commandes'">
        <div>
           <div class="table-container">
              <div v-for="(commande, index) in commandesData" :key="index" class="commande-group">
                 <!-- Informations de la commande -->
                 <div class="commande-info">
                    <span>Commande n°{{ commande.id }}</span>
                    <span>{{ commande.dateCommande }}</span>
                    <span class="montant-total">{{ commande.montantTotal }} €</span>
                    <button class="details-button" @click="voirDetails(commande, index)">Voir Détails</button>
                 </div>
                 <!-- Tableau de détails de la commande -->
                 <div v-if="commande.showDetails" class="details-container">
                    <table>
                       <thead>
                          <tr>
                             <th>Article</th>
                             <th>Prix</th>
                             <th>Quantité</th>
                             <th>Total</th>
                             <th>Evaluation</th>
                          </tr>
                       </thead>
                       <tbody>
                          <tr v-for="detail in commande.details" :key="detail.id">
                             <td>{{ detail.nomProduit }} </td>
                             <td class="align-right">{{ detail.prixretenu.toFixed(2) }} €</td>
                             <td>{{ detail.quantite }}</td>
                             <td class="align-right">{{ detail.total.toFixed(2)  }} €</td>
                             <td>
  <div class="detail-actions">
    <div class="rating">
      <span v-for="star in 5" :key="star" class="star" @click="!detail.noteDonnee && setRating(star, commande.id, detail.nomProduit, detail.pid)">
        <i class="fa fa-star" :class="{'is-active': star <= detail.etoiles, 'disabled': detail.noteDonnee}"></i>
      </span>
    </div>
    <div class="commenter">
      <i class="fa fa-comment" @click="ouvrirModaleCommentaire(detail.pid)">Commenter</i>
    </div>
  </div>

                                <div v-if="afficherModale" class="modale">
    <div class="modale-content">
      <span class="modale-close" @click="fermerModale">&times;</span>
      <h2>Ajouter un commentaire</h2>
      <textarea v-model="commentaire" class="textarea-commentaire" placeholder="Votre commentaire..."></textarea>
      <div class="footer-modale">
        <button @click="envoyerCommentaire" class="envoyer-btn">Envoyer</button>
      </div>
    </div>
  </div>
                             </td>
                          </tr>
                       </tbody>
                    </table>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <div v-if="activeContent === 'liste'">
        <div>
           <div class="table-container">
              <div v-for="(liste, index) in listeData" :key="index" class="commande-group">
                 <!-- Informations de la commande -->
                 <div class="liste-info">
                    <span><img :src="'/' +  liste.imageUrl " class="photo-liste" /></span>
                    <span>{{ liste.nomProduit }}</span>
                    <span class="montant-total">{{ liste.prix }} €</span>
                    <button class="details-button" @click="enleverFavoris(liste, index)">Supprimer</button>
                    <button class="details-button" @click="voirProduit (liste, index)">Voir Produit</button>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <div v-if="activeContent === 'fidelite'">
        <div class="points-fidelite">Vous avez {{ compteData.fidelite }} points</div>
        <div class="carte-fidelite">
           <div v-for="n in nombreImages" :key="n" class="case-fidelite">
              <img src="/images/p1.jpg" alt="Point de fidélité">
           </div>
        </div>
     </div>
  </div>
</template>
<script>
  import { ref, onMounted,computed } from 'vue';
  
  export default {
    setup() {
        
      // État pour gérer le contenu actif
      const activeContent = ref('');
      // Simule les données pour chaque section. Dans une application réelle, ces données pourraient être chargées depuis une API
      const compteData = ref({
    nom: '',
    prenom: '',
    email: '',
    telephone: '',
    classe: '',
    photoUrl: ''
  });
  const afficherModale = ref(false);
    const commentaire = ref('');
    const produitActuelId = ref(null);

      const commandesData = ref([]);
      const listeData = ref([]);
      const fideliteData = ref([]);
      const nombreImages = computed(() => Math.floor(compteData.value.fidelite / 10));
  
      // commentaire
      const ouvrirModaleCommentaire = (id) => {
      produitActuelId.value = id;
      afficherModale.value = true;
    };

    const fermerModale = () => {
      afficherModale.value = false;
      commentaire.value = '';
    };

    const envoyerCommentaire = async () => {
      if (!commentaire.value.trim()) {
        alert("Veuillez entrer un commentaire.");
        return;
      }
      try {
        const reponse = await fetch('/api/mobile/ajoutcommentaire', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            idProduit: produitActuelId.value,
            commentaire: commentaire.value,
          }),
        });

        if (!reponse.ok) throw new Error('Erreur lors de l’envoi du commentaire');

        alert('Commentaire envoyé avec succès');
        fermerModale();
      } catch (error) {
        console.error('Erreur lors de l’envoi du commentaire:', error);
      }
    };
      // rating
      const setRating = async (rating, commandeId, detailId,produiId) => {
        const commandeIndex = commandesData.value.findIndex(c => c.id === commandeId);
  if (commandeIndex === -1) return;

  const detailIndex = commandesData.value[commandeIndex].details.findIndex(d => d.nomProduit === detailId);
  if (detailIndex === -1) return;

  if (detailIndex.noteDonnee == true) return;

  commandesData.value[commandeIndex].details[detailIndex].etoiles = rating;
  commandesData.value[commandeIndex].details[detailIndex].noteDonnee = true;

  commandesData.value = [...commandesData.value];

      // Mettez ici l'appel à l'API pour envoyer la note
      try {
        const response = await fetch('/api/mobile/GetLesetEtoiles', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            idCommande: commandeId,
            idProduit: produiId, // Utilisez l'ID du détail du produit
            nombreEtoiles: rating,
          }),
        });
  
        if (!response.ok) {
          throw new Error('Erreur lors de l’envoi de la note');
        }
  
        // Traitez ici la réponse de l'API
        console.log('Note envoyée avec succès pour le produit ID:', detailId);
      } catch (error) {
        console.error('Erreur lors de l’envoi de la note pour le produit ID:', detailId, error);
      }

  };
  
      // modifier le user
      const modifierUtilisateur = async () => {
    try {
      const response = await fetch('/api/mobile/modifieruser', {
        method: 'POST', // ou 'PUT' selon votre API
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(compteData.value),
      });
      if (!response.ok) {
        throw new Error('Erreur lors de la modification des données utilisateur');
      }
      // Réponse de succès
      alert('Compte modifié avec succès!');
      // Mettre à jour l'interface utilisateur ou rediriger l'utilisateur si nécessaire
    } catch (error) {
      console.error('Erreur lors de la modification de l’utilisateur:', error);
      alert('Erreur lors de la modification du compte');
    }
  };
  
      // charger le compte
      const chargerDonneesCompte = async () => {
    try {
      const response = await fetch('/api/mobile/compteuser');
      if (!response.ok) {
        throw new Error("Erreur lors du chargement des données du compte");
      }
      const data = await response.json();
      compteData.value = data;
    } catch (error) {
      console.error("Erreur lors du chargement des données du compte:", error);
    }
  };
      // Fonction pour charger les commandes depuis l'API avec fetch
      const chargerLesCommandes = async () => {
        try {
          const response = await fetch('/api/mobile/GetLesCommandes');
          if (!response.ok) {
            throw new Error("Erreur lors du chargement des commandes");
          }
          const data = await response.json();
          commandesData.value = data;
        } catch (error) {
          console.error("Erreur lors du chargement des commandes:", error);
        }
      };
      const voirDetails = (commande, index) => {
    // Vérifier si les détails ont déjà été chargés
    if (!commandesData.value[index].details) {
      fetch('/api/mobile/detailcommande', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ Id: commande.id }),
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Erreur lors du chargement des détails de la commande');
        }
        return response.json();
      })
      .then(details => {
        // Succès : Ajouter les détails à l'objet de commande et afficher les détails
        commandesData.value[index].details = details;
        commandesData.value[index].showDetails = true;
      })
      .catch(error => {
        console.error('Erreur lors du chargement des détails:', error);
        // Gérer l'erreur...
      });
    } else {
      // Toggle l'affichage des détails si déjà chargés
      commandesData.value[index].showDetails = !commandesData.value[index].showDetails;
    }
  };
  
  const chargerLaListeDesFavoris = async () => {
    try {
      const reponse = await fetch('/api/mobile/getListe');
      if (!reponse.ok) {
        throw new Error('Erreur lors du chargement de la liste des favoris');
      }
      const data = await reponse.json();
      listeData.value = data;
    } catch (error) {
      console.error("Erreur lors du chargement de la liste des favoris:", error);
      // Gérer l'erreur appropriée ici, par exemple, en affichant un message à l'utilisateur
    }
  };
  
  
  // Fonction pour enlever un produit des favoris
  const enleverFavoris = async (liste, index) => {
    try {
      // Préparer le corps de la requête. Assurez-vous que l'ID est correctement extrait de l'objet 'liste'.
      const body = JSON.stringify({ id: liste.id });
  
      const response = await fetch('/api/mobile/supprimerfavoris', {
        method: 'POST', // ou 'DELETE', selon ce que votre API attend
        headers: {
          'Content-Type': 'application/json',
        },
        body: body,
      });
  
      if (!response.ok) {
        // Gérer les réponses d'erreur de l'API
        throw new Error('Erreur lors de la suppression du favori');
      }
  
      // Traiter la réponse
      const result = await response.json(); // ou une autre action selon la réponse de votre API
  
      // Optionnel : Mettre à jour l'état pour refléter la suppression sans recharger la page
      listeData.value.splice(index, 1); // Supprime l'élément de l'array 'listeData'
  
      alert('Favori supprimé avec succès');
    } catch (error) {
      console.error('Erreur lors de la suppression du favori:', error);
      alert('Erreur lors de la suppression du favori');
    }
  };
  
  
  // Fonction pour voir un produit
  const voirProduit = (liste) => {
    window.location.href = `/lesProduits/voirleproduit/${liste.id}`;
  };
      // Fonction pour charger les données associées à chaque capsule
      const loadContentData = async (contentType) => {
        // Ici, vous pouvez ajouter votre logique pour charger les données spécifiques à chaque type de contenu
        // Par exemple, charger les commandes de l'utilisateur si contentType est 'commandes'
        switch (contentType) {
          case 'compte':
            break;
          case 'commandes':
            break;
          case 'liste':
            break;
            case 'fidelite':
            break;
        }
        // Définit le contenu actif pour afficher le composant approprié
        activeContent.value = contentType;
      };
  
      onMounted(() => {
    chargerLesCommandes();
    chargerDonneesCompte();
    chargerLaListeDesFavoris();
  });
  
      // Expose les variables et fonctions au template
      return { afficherModale, commentaire, ouvrirModaleCommentaire, fermerModale, envoyerCommentaire, setRating,nombreImages,enleverFavoris,voirProduit,modifierUtilisateur,commandesData, voirDetails, activeContent, compteData, commandesData, listeData, fideliteData, loadContentData };
    }
  };
  
  
</script>
<style scoped>

.modale {
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.4);
}

.modale-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.modale-close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.modale-close:hover,
.modale-close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
.textarea-commentaire {
  width: calc(100% - 30px); /* Prend toute la largeur avec un padding de 15px de chaque côté */
  padding: 15px;
  margin: 15px 0; /* Ajoute un peu d'espace au-dessus et en dessous */
  box-sizing: border-box; /* Inclut le padding et la bordure dans la largeur et hauteur totales */
}
.footer-modale {
  text-align: right; /* Alignement du bouton à droite */
}

.commenter {
  cursor: pointer;
  display: flex;
  justify-content: flex-end; /* Aligner le texte de commenter à droite */
}

.detail-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.rating {
  flex: 1; /* Assure que le rating prend de l'espace et pousse le commenter vers la droite */
}
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
.capsules {
  display: flex;
  justify-content: space-around;
  padding: 10px;
  margin-top: 30vh; /* Positionne l'ensemble à 100px du haut */

  }
  .capsule {
  background-color: white;
  color: black;
  padding: 10px; /* Ajustez le padding si nécessaire */
  border-radius: 25px;
  border: 1px solid black;
  font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  width: 150px; /* Largeur fixe */
  height: 50px; /* Hauteur fixe */
  display: flex;
  position: relative;
  justify-content: center;
  align-items: center;
  text-align: center;
  cursor: pointer;
  }
  /* Optionnel: Ajoute un peu d'espace entre les capsules si vous le souhaitez */
  .capsule:not(:last-child) {
  margin-right: 10px;
  }

 
  .ligne-blanche {
  height: 1px; /* Définit la hauteur de la ligne à 1px */
  background-color: white; /* Couleur de la ligne */
  margin-top: 20px; /* Espace de 20px entre les capsules et la ligne */
  width: 80%; /* Définit la largeur de la ligne à 80% */
  margin-left: auto; /* Centrage horizontal : marge gauche automatique */
  margin-right: auto; /* Centrage horizontal : marge droite automatique */
  }
  /*tableau des commandes*/
  .content{
  margin-top: 20px;
  margin-bottom: 20px;;
  }
  .table-container {
  width: 60%;
  margin: 0 auto; /* Centrer le tableau dans le conteneur */
  }
  .table-row {
  display: flex;
  justify-content: space-between;
  border-bottom: 1px solid white; /* Filet blanc entre chaque ligne */
  padding: 10px 0;
  }
  .table-cell {
  flex: 1;
  }
  .montant-total {
  text-align: right;
  }
  .voir-details {
  text-align: right;
  }
  .details-container {
  margin-top: 10px;
  background-color: #ffffff; /* Fond légèrement différent pour distinguer les détails */
  }
  .details-container table {
  width: 100%;
  border-collapse: collapse;
  }
  .details-container th, .details-container td {
  border: 1px solid #ddd;
  padding: 8px;
  }
  .details-container th {
  background-color: #e2e2e2;
  }
  .commande-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 20px; /* Espace entre chaque groupe de commande */
  }
  .commande-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  color: white;
  background-color: #000000; /* Légère couleur de fond */
  border-bottom: 1px solid #ddd; /* Séparateur visuel */
  }
  .liste-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  color: white;
  background-color: #000000; /* Légère couleur de fond */
  border-bottom: 1px solid #ddd; /* Séparateur visuel */
  }
  .commande-info span, .commande-info .montant-total {
  flex: 1;
  }
  .details-button {
  margin-left: 10px;
  padding: 5px 10px;
  background-color: transparent;
  color: white;
  border: 2px solid #ffffff; /* Définit une bordure solide blanche de 2px */
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease, color 0.3s ease; /* Animation optionnelle */
  }
  .details-button:hover {
  background-color: #ffffff;
  color: black;
  }
  .details-container {
  margin-top: 10px; /* Espace entre les infos de commande et les détails */
  }
  .details-container table {
  width: 100%;
  border-collapse: collapse;
  }
  .details-container th {
  background-color: #eee;
  }
  .align-right {
  text-align: right;
  }
  /*Fin de tableau des commandes*/
  /* gestion du compte*/
  .carte-identite {
  width: 40%;
  margin: 0 auto; /* Centrer le tableau dans le conteneur */
  display: flex;
  align-items: center; /* Centre verticalement */
  padding: 20px;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 15px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  .profil-container, .info-utilisateur {
  margin: 10px;
  }
  .photo-liste {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  object-fit: cover;
  }
  .photo-profil {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  }
  .info-utilisateur {
  display: flex;
  flex-direction: column;
  justify-content: center;
  margin-left: 20px; /* Ajustez selon vos besoins pour l'espacement */
  }
  .info-utilisateur p {
  margin: 4px 0; /* Espacement entre les champs */
  }
  .info-utilisateur input {
  border: none; /* Supprime la bordure */
  outline: none; /* Supprime l'outline au focus */
  background-color: transparent; /* Fond transparent */
  margin-left: 10px; /* Espacement entre le label et le champ */
  }
  .info-utilisateur input::placeholder {
  color: #666; /* Couleur du texte du placeholder pour améliorer la visibilité */
  }
  /* Style pour le bouton Modifier */
  .info-utilisateur button {
  background-color: #206714; /* Couleur de fond */
  color: white; /* Couleur du texte */
  border: none; /* Supprime la bordure */
  padding: 10px 15px; /* Padding intérieur */
  margin-top: 10px; /* Espacement au-dessus du bouton */
  cursor: pointer; /* Change le curseur en main */
  border-radius: 5px; /* Bords arrondis */
  }
  .info-utilisateur button:hover {
  background-color: #39cd4d; /* Couleur de fond au survol */
  }
  .carte-fidelite {
  display: flex;
  flex-wrap: wrap;
  max-width: 500px; /* Ajustez selon vos besoins */
  gap: 10px; /* Espace entre les cases */
  border: 1px solid #ffffff; /* Bordure noire de 1px */
  border-radius: 10px; /* Rayon de bordure pour les coins arrondis */
  padding: 10px; /* Espace intérieur autour des éléments */
  margin: auto; /* Centrer la carte horizontalement */
  box-sizing: border-box; /* Inclut la bordure et le padding dans la largeur totale */
  }
  .case-fidelite {
  flex-basis: calc(20% - 10px); /* Ajustez pour 5 images par ligne avec un peu d'espace */
  text-align: center;
  }
  .case-fidelite img {
  width: 100%; /* Ajustez selon la taille de vos images */
  height: auto;
  border-radius: 10px;
  }
  .points-fidelite {
  text-align: center; /* Centre le texte des points de fidélité */
  margin-top: 10px; /* Espace au-dessus du texte des points de fidélité */
  }
  /* Ajoutez cette classe pour centrer verticalement la carte de fidélité */
  .conteneur-fidelite {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh; /* Hauteur totale de la vue pour centrer verticalement */
  }
  .points-fidelite {
  color: white; /* Texte en blanc */
  font-weight: bold; /* Texte en gras */
  font-size: 3vh; /* Taille de la police basée sur la hauteur de la vue */
  text-align: center; /* Centre le texte */
  font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  margin-top: 10px; /* Espace au-dessus du texte des points de fidélité, ajustez selon besoin */
  }

  /* Style pour les images circulaires */
.image-circulaire {
  border-radius: 50%;
  width: 150px; /* Ajustez selon la taille souhaitée de l'image */
  height: 150px;
  object-fit: cover;
  position: absolute;
  top: -170px; /* Ajustez cette valeur si nécessaire pour positionner au-dessus des capsules */
  left: 50%;
  transform: translateX(-50%);
}

/* Base de l'animation de rotation */
.rotation-base {
  animation: rotationInfinie linear infinite;
}

@keyframes rotationInfinie {
  from {
    transform: translateX(-50%) rotate(0deg);
  }
  to {
    transform: translateX(-50%) rotate(360deg);
  }
}

/* Classes pour différentes vitesses d'animation */
.rotation-lente {
  animation-duration: 30s; /* Rotation plus lente */
}

.rotation-moderate {
  animation-duration: 20s; /* Vitesse moyenne */
}

.rotation-rapide {
  animation-duration: 10s; /* Rotation rapide */
}

</style>

