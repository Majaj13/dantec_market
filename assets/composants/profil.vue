<template>
    <div class="capsules">
       <div class="capsule" @click="loadContentData('compte')">Compte</div>
       <div class="capsule" @click="loadContentData('commandes')">Commandes</div>
       <div class="capsule" @click="loadContentData('liste')">liste</div>
       <div class="capsule" @click="loadContentData('fidelite')">Fidélité</div>
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
          </tr>
        </thead>
        <tbody>
          <tr v-for="detail in commande.details" :key="detail.id">
            <td>{{ detail.nomProduit }} </td>
            <td class="align-right">{{ detail.prixretenu.toFixed(2) }} €</td>
            <td>{{ detail.quantite }}</td>
            <td class="align-right">{{ detail.total.toFixed(2)  }} €</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

          </div>
       </div>
       <div v-if="activeContent === 'liste'">
          <!-- Affichez les données du service client -->
          <ul>
             <li v-for="(item, index) in listeData" :key="index">{{ item }}</li>
          </ul>
       </div>
       <div v-if="activeContent === 'fidelite'">
          <!-- Affichez les données du service client -->
          <ul>
             <li v-for="(item, index) in fideliteData" :key="index">{{ item }}</li>
          </ul>
       </div>
    </div>
 </template>
  

<script>
import { ref, onMounted } from 'vue';

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
    const commandesData = ref([]);
    const listeData = ref([]);
    const fideliteData = ref([]);
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
    // Fonction pour charger les données associées à chaque capsule
    const loadContentData = async (contentType) => {
      // Ici, vous pouvez ajouter votre logique pour charger les données spécifiques à chaque type de contenu
      // Par exemple, charger les commandes de l'utilisateur si contentType est 'commandes'
      switch (contentType) {
        case 'compte':
          // Simule le chargement des données du compte
         
          break;
        case 'commandes':
          // Simule le chargement des données de commandes
          break;
        case 'liste':
          // Simule le chargement des données de service client
          listeData.value = ['Question 1', 'Réponse 1', 'Question 2', 'Réponse 2'];
          break;
          case 'fidelite':
          // Simule le chargement des données de service client
          fideliteData.value = ['Question 1', 'Réponse 1', 'Question 2', 'Réponse 2'];
          break;
      }
      // Définit le contenu actif pour afficher le composant approprié
      activeContent.value = contentType;
    };

    onMounted(() => {
  chargerLesCommandes();
  chargerDonneesCompte();
});

    // Expose les variables et fonctions au template
    return {modifierUtilisateur,commandesData, voirDetails, activeContent, compteData, commandesData, listeData, fideliteData, loadContentData };
  }
};


</script>
<style>
.capsules {
  display: flex;
  justify-content: space-around;
  padding: 10px;
  margin-top: 200px; /* Positionne l'ensemble à 100px du haut */
}

.capsule {
  background-color: white;
  color: black;
  padding: 10px; /* Ajustez le padding si nécessaire */
  border-radius: 25px;
  border: 1px solid black;
  font-family: Arial, sans-serif;
  width: 150px; /* Largeur fixe */
  height: 50px; /* Hauteur fixe */
  display: flex;
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

</style>