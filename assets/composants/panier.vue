<template>
  <div class="container">
    <div v-if="messageDeSucces" class="message-de-succes">
      {{ messageDeSucces }}
    </div>
    <div v-else>
   
    <div class="panier" v-if="commandesNonValidees && commandesNonValidees.length > 0">
      <div class="header">
      <h1>
        Votre panier
      </h1>
    </div>
      <div class="panier-header">
        <div>
          Article(s)
        </div>
        <div>
          Prix
        </div>
        <div>
          Quantité
        </div>
        <div>
          Total
        </div>
      </div>
      <div class="panier-item" v-for="(commande, index) in commandesNonValidees"
      :key="index">
        <div>
          {{ commande.nomProduit }}
        </div>
        <div class="align-right">
          {{ commande.prixretenu.toFixed(2) }} €
        </div>
        <div class="quantite-buttons">
          <button @click="decrementerQuantite(commande)">
            -
          </button>
          <div class="align-right">
            {{ commande.quantite }}
          </div>
          <button @click="incrementerQuantite(commande)">
            +
          </button>
        </div>
        <div class="align-right">
          {{ commande.total.toFixed(2) }} €
        </div>
      </div>
      <div class="panier-total">
        <div>
          Total de votre panier
        </div>
        <div>
          {{ totalGeneral.toFixed(2) }} €
        </div>
      </div>
      <div v-if="creneauxDisponibles  && creneauxDisponibles .length > 0">
        <h2 class="titre-horaires">
          Horaires d'ouverture de cette semaine
        </h2>
        <div class="tableau">
          <table>
            <thead>
              <tr class="tableau-item">
                <th>
                  Date
                </th>
                <th>
                  Heure de début
                </th>
                <th>
                  Heure de fin
                </th>
                <th>
                  Sélectionner
                </th>
              </tr>
            </thead>
            <tbody>
              <tr class="tableau-item" v-for="(creneau, index) in creneauxDisponibles "
              :key="index">
                <td>
                  {{ formaterDate(creneau.jour) }}
                </td>
                <td class="align-right">
                  {{ formaterHeure(creneau.heureDebut) }}
                </td>
                <td class="align-right">
                  {{ formaterHeure(creneau.heureFin) }}
                </td>
                <td>
                  <input type="radio" name="creneauSelectionne" v-model="creneauSelectionne"
                  :value="creneau">
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="footer">
        <button class="button-left" @click="continuerAchats()">
          Continuer mes achats
        </button>
        <div class="spacer">
        </div>
        <button class="button-right" v-on:click="reserverCreneau">
          Réserver ma commande
        </button>
      </div>
    </div>
    <div v-else>
      <p class="message-de-succes">
        Votre panier est vide.
      </p>
    </div>
  </div>
</div>
</template>

<script>
import { ref, onMounted, watch } from 'vue';

export default {
  setup() {
    const messageDeSucces = ref(''); // Ajout de cette ligne dans setup()

      const creneauSelectionne = ref(null);
      const creneauxDisponibles = ref([]);
      const commandesNonValidees = ref([]);
      const totalGeneral = ref(0);
      const isLoading = ref(false);
      const error = ref(null);
// //
      const chargerCreneaux = async () => {
      isLoading.value = true;
  try {
    const response = await fetch('/api/mobile/semaine-courante');
if (!response.ok) throw new Error('Échec du chargement des créneaux');
const creneaux = await response.json();
creneauxDisponibles.value = creneaux; // Assignation aux créneaux disponibles

  } catch (error) {
      console.error(error.message);
  } finally {
      isLoading.value = false;
  }
};
/////
const formaterDate = (date) => {
      const nouvelleDate = new Date(date);
      return nouvelleDate.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
      });
    };
    const formaterHeure = (date) => {
      const heure = new Date(date);
      return heure.toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
      });
    };
////
const reserverCreneau = async () => {
  if (!creneauSelectionne.value) {
      alert("Veuillez sélectionner un créneau.");
      return;
  }
  const commandeId = commandesNonValidees.value[0].id; // Remplacez '.id' par la propriété correcte si elle est nommée différemment

  try {
    const response = await fetch('/api/mobile/reserver', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        jour: creneauSelectionne.value.jour,
        heureDebut: creneauSelectionne.value.heureDebut,
        commandeId:commandeId
    }),
});
      if (!response.ok) throw new Error('Échec de la réservation');
      // Gérer le succès de la réservation ici
      messageDeSucces.value = "Réservation réussie ! Votre commande a été enregistrée.";
  } catch (error) {
      console.error(error.message);
      // Gérer l'échec de la réservation ici
      alert("Échec de la réservation.");
  }
};


//
const incrementerQuantite = async (commande) => {
  commande.quantite += 1;
  await envoyerQuantiteAPI(commande.nomProduit, commande.quantite);
  
};

const decrementerQuantite = async (commande) => {
  if (commande.quantite > 1) { // Empêcher la quantité d'aller en dessous de 1
      commande.quantite -= 1;
      await envoyerQuantiteAPI(commande.nomProduit, commande.quantite);
      
  }
};

const envoyerQuantiteAPI = async (nomProduit, nouvelleQuantite) => {
  try {
      const response = await fetch('/api/mobile/MajProduitCommande', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ nomProduit: nomProduit, quantite: nouvelleQuantite }),
      });

      if (response.ok) await chargerCommandesNonValidees();


      if (!response.ok) throw new Error('Problème lors de la mise à jour de la quantité');
      // Optionnel : Mettre à jour l'état local si nécessaire ou gérer la réponse de l'API
  } catch (error) {
      console.error(error.message);
      // Gérer l'erreur, par exemple afficher un message à l'utilisateur
  }
};

//

      const chargerCommandesNonValidees = async () => {
          isLoading.value = true;
          try {
              // Remplacer '/api/path' par l'URL réelle de votre API
              const response = await fetch('/api/mobile/commandenonvalidee', {
                  method: 'POST', // ou 'GET', selon l'API
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({ userId: 1 }),
              });

              if (!response.ok) throw new Error('Échec du chargement des commandes');
              const data = await response.json();
              commandesNonValidees.value = data;
          } catch (e) {
              error.value = e.message;
          } finally {
              isLoading.value = false;
          }
      };

      // Observer les changements sur commandesNonValidees et mettre à jour totalGeneral
      watch(commandesNonValidees, (nouvellesCommandes) => {
          totalGeneral.value = nouvellesCommandes.reduce((acc, commande) => acc + commande.total, 0);
      });

      onMounted(() => {
    chargerCommandesNonValidees(); // Ceci est déjà là pour charger les commandes
    chargerCreneaux(); // Ajoutez cette ligne pour charger les créneaux
});


const continuerAchats = () => {
      window.location.href = '/lesProduits';
    };


      const validerCommande = () => {
          // Logique pour valider la commande
      };

      return {messageDeSucces ,continuerAchats,formaterHeure,formaterDate, creneauSelectionne,reserverCreneau,creneauxDisponibles, incrementerQuantite,decrementerQuantite,commandesNonValidees, continuerAchats, validerCommande, totalGeneral, isLoading, error };
  }
};

</script>

<style scoped>


.container {
  width: 60%; /* Assure que le conteneur prend toute la largeur */
  min-height: 120vh; /* Assure que le conteneur prend au moins toute la hauteur de la fenêtre */
  font-family: Arial, sans-serif;
  display: flex;
  flex-direction: column;
  background: #000000; /* Couleur de fond */
  
}

.message-de-succes {

  color: green;
  font-weight: bold;
  padding: 10px;
  margin-top: 50px;
  border-radius: 5px;
  background-color: #ebf9eb;
}
.header {

width: 100%;
background-color: black; /* Fond noir */
padding: 10px;
text-align: left; /* Alignement à gauche */
margin-bottom: 30px; /* Espace de 30px sous l'en-tête */
}

.header h1 {
color: white; /* Texte en blanc */
font-weight: bold; /* Texte en gras */
margin: 0; /* Retire les marges par défaut du h1 pour un contrôle précis */
padding-right: 10px;
}

.panier, .tableau {
  width: 100%; /* Assure que le panier et le tableau prennent toute la largeur du conteneur */
  display: flex;
  flex-direction: column;
  border: 1px solid #ffffff; /* Bordure noire de 1px */
  border-radius: 10px; /* Rayon de bordure pour les coins arrondis */
  padding: 30px; /* Espace intérieur autour des éléments */
  
  margin: auto; /* Centrer la carte horizontalement */
  box-sizing: border-box; /* Inclut la bordure et le padding dans la largeur totale */
  
}

.titre-horaires {
  margin-top: 20px;
  margin-bottom: 30px;
  color: white; /* Définit la couleur du texte en blanc */
  text-align: left; /* Alignement du texte à gauche */
  /* Ajoutez ici d'autres styles si nécessaire (taille de police, marge, etc.) */
}
.panier-header, .panier-total {
  margin-top: 10px;
  margin-bottom: 10px;
  padding-left: 10px;
padding-right: 10px;
  background-color: #f2f2f2;


  display: flex;
  justify-content: space-between;
  font-weight: bold;
}
.panier-item {
  display: flex;
  align-items: right; /* Centre verticalement */
  justify-content: space-between; /* Espacement horizontal */
  /* autres styles */
}
.panier-item, .panier-livraison {
  padding-left: 10px;
padding-right: 10px;
  border-bottom: 1px solid #ddd;
 
 
  display: flex;
  justify-content: space-between;
}
.panier-total {
  padding-left: 10px;
padding-right: 10px;
  font-weight: bold;
}
.footer {
  width: 100%;
display: flex;
justify-content: space-between; /* Alignement des boutons aux deux extrémités */
padding: 0; /* Assurez-vous qu'il n'y a pas de padding interne */
margin-top: 20px; /* Espace au-dessus des boutons */
}

.button-left {
padding-left: 5px;
padding-right: 5px;
background: rgba(223, 186, 97, 0.95);
  border-radius: 5vh;
  text-decoration: none;
  color: #2D2D2D;
  text-align: center;
  font-family: Inter;
  font-size: 4vh;
  font-style: normal;
  font-weight: 700;
  line-height: normal;
cursor: pointer;
align-self: flex-start; /* Alignement du bouton gauche avec le bord supérieur */
}

.button-right {
padding-left: 5px;
padding-right: 5px;
background: rgba(223, 186, 97, 0.95);
  border-radius: 5vh;
  text-decoration: none;
  color: #2D2D2D;
  text-align: center;
  font-family: Inter;
  font-size: 4vh;
  font-style: normal;
  font-weight: 700;
  line-height: normal;
align-self: flex-end; /* Alignement du bouton droit avec le bord inférieur */
}
.align-right {
  text-align: right;
}
.panier-item:nth-child(odd) {
  
  color: white;
}

.panier-item:nth-child(even) {
  color: rgb(219, 221, 213);
}

.quantite-buttons button {
display: flex;
  justify-content: right;
  align-items: center;
  width: 20px; /* Largeur du bouton */
  height: 20px; /* Hauteur du bouton */
  margin: 0 5px;
  background: rgba(223, 186, 97, 0.95); /* Utilise la même couleur que les autres boutons */
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0; /* Ajustement du padding pour les petits boutons */
  font-size: 10px; /* Ajustez si nécessaire pour le signe plus et moins */
}

.quantite-buttons {
  display: flex;
  align-items: right;
  justify-content: right;
}

.quantite-buttons div {
  min-width: 20px; /* Assurez-vous que l'espace pour la quantité est suffisant */
  text-align: center;
  display: flex;
  justify-content: right;
  align-items: right;
}

.tableau {
  width: 100%;
  margin-top: 10px;
  margin-bottom: 10px;
  background-color: #000000;
  display: flex;
  justify-content: space-between;
  font-weight: bold;
}
.tableau thead {
  background-color: white; /* Applique un fond blanc à l'en-tête */
}
.tableau td, .tableau th {
  padding-left: 30px; /* Augmente l'espace à gauche de la cellule */
  padding-right: 30px; /* Augmente l'espace à droite de la cellule */
}
.tableau tbody td {
  color: white; /* Définit la couleur du texte des cellules à blanc */
  text-align: right;
}

.tableau table {
  border-collapse: separate; /* Nécessaire pour utiliser border-spacing */
  border-spacing: 1px 0; /* Ajoute 20px d'espace horizontal entre les cellules sans affecter l'espacement vertical */
}



.align-right {
  text-align: right;
}


@media (max-width: 768px) {
  .container, .footer {
      flex-direction: column;
      align-items: center;
  }
  .panier-header, .panier-item, .panier-total, .tableau-item {
  display: flex;
  justify-content: space-between;
 
}

  .quantite-buttons {
      justify-content: center;
  }
  .button-left, .button-right {
      width: 80%; /* Boutons plus larges pour faciliter les clics */
      margin-bottom: 10px;
  }
}

/* Adaptations pour téléphones mobiles */
@media (max-width: 480px) {
  .header, .panier, .footer {
      width: 95%; /* Utilise plus d'espace de l'écran */
  }
  .header h1 {
      font-size: 5vw; /* Taille de texte basée sur la largeur de l'écran */
  }
  .panier-item, .panier-header, .panier-total, .button-left, .button-right {
      flex-direction: column;
      text-align: center;
  }
  .panier-item div, .panier-header div, .panier-total div {
      margin-bottom: 5px; /* Espacement pour la lisibilité */
  }
  .quantite-buttons button {
      margin: 0 10px; /* Espace supplémentaire autour des boutons */
  }
  .quantite-buttons {
      justify-content: space-around;
  }
}

</style>