<template>
    <div class="bande-image">
     <img src="/images/fondprofilvue.png" alt="Image descriptive">
  </div>
    <div class="titre-container"><h1>De quoi voulez vous parler ?</h1></div>
    <div class="message-container">
      <div class="message-left">
       
      </div>
      <div class="message-right">
        <div v-if="showMessageConfirmation" class="message-confirmation">
  Votre message a été envoyé avec succès.
</div>
        <textarea v-model="message" placeholder="Votre message ici" rows="10"></textarea>
        <button :disabled="isSubmitted" @click="submitMessage">{{ buttonText }}</button>
      </div>
    </div>
    <div class="historique-container"><h1>Historique de nos échanges</h1></div>
  <div class="histo-messages-container">
    <div v-for="message in messages" :key="message.id" class="message">
      <div>Date: {{ message.dateMessage }}</div>
      <div>Message: {{ message.message }}</div>
      <div>Réponse: {{ message.reponse }}</div>
      <div>État: {{ message.etat }}</div>
    </div>
  </div>
  </template>
  
  <script setup>
  import { ref,onMounted  } from 'vue';
  
  const messages = ref([]);
  const message = ref("");
  const isSubmitted = ref(false);
  const buttonText = ref("Soumettre");
  const showMessageConfirmation = ref(false); // Pour la confirmation visuelle
  
  const submitMessage = async () => {
    try {
      const response = await fetch('/api/mobile/creermessage', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ message: message.value }),
      });
  
      if (response.ok) {
        buttonText.value = "Envoi réussi";
        isSubmitted.value = true;
        showMessageConfirmation.value = true; // Affiche la confirmation
        message.value = ""; // Réinitialise le champ après l'envoi
      } else {
        alert("Échec de l'envoi"); // Envisagez une meilleure gestion des erreurs ici
      }
    } catch (error) {
      console.error('Erreur lors de l’envoi du message:', error);
      alert("Erreur lors de l'envoi"); // Envisagez une meilleure gestion des erreurs ici
    }
  };

  const fetchMessages = async () => {
  try {
    const response = await fetch('/api/mobile/touslesmessages');
    if (response.ok) {
      const data = await response.json();
      messages.value = data; // Assurez-vous que l'API renvoie le format attendu
    } else {
      console.error('Erreur lors de la récupération des messages');
    }
  } catch (error) {
    console.error('Erreur lors de la connexion à l’API:', error);
  }
};

onMounted(fetchMessages);
  </script>
  
  
  <style scoped>

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
  .titre-container{
    margin-top: 5vh;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    color: white;
    margin-left: 10%;
  }
  .historique-container {
  color: white;
  font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  margin-left: 10%;
  margin-top: 3vh;
}

.message-container {
    display: flex;
    border: 1px solid white;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    color: white;
    width: 80%;
    margin: auto;
    margin-top: 3vh;
  }

    .histo-messages-container {
  display: flex;
  flex-direction: column; /* Organise les enfants verticalement */
  border: 1px solid white;
  font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  color: white;
  width: 80%;
  margin: auto;
  margin-top: 3vh;
}
  
  .message-left {
    background-image: url('/public/images/actualites1.jpg'); /* Remplacez par le chemin de votre image */
    background-size: cover; /* Assure que l'image couvre entièrement la zone sans perdre ses proportions */
  background-position: center; /* Centre l'image dans la zone */
  width: 40%; /* Définit la largeur de la partie gauche à 40% */
  
  }
  .message-right {
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  
    padding: 20px;
  display: flex;
  flex-direction: column;
  width: 60%; /* Ajusté pour occuper le reste, soit 60% */
  }
  textarea {
     
    color: white;
    background-color: black;
    margin-bottom: 10px;
  width: 100%; /* Assurez-vous que la textarea utilise tout l'espace disponible */
  }
  button {
    background-color: gold;
    border-radius: 5px;
  border: none;
  padding: 10px;
  cursor: pointer;
  }
  button:disabled {
    cursor: not-allowed;
  }
  .message-confirmation {
  color: green; /* Choisissez une couleur visible et accessible */
  margin-top: 20px;
}
.message {
  border: 1px solid white;
  margin: 10px;
  padding: 10px;
}

  </style>
  