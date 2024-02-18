<template>
    <div class="titre-container"><h1>De quoi voulez vous parler ?</h1></div>
    <div class="message-container">
      <div class="message-left">
        <p>Saisissez votre message :</p>
      </div>
      <div class="message-right">
        <textarea v-model="message" placeholder="Votre message ici" rows="10"></textarea>
        <button :disabled="isSubmitted" @click="submitMessage">{{ buttonText }}</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const message = ref("");
  const isSubmitted = ref(false);
  const buttonText = ref("Soumettre");
  
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
      } else {
        alert("Échec de l'envoi");
      }
    } catch (error) {
      console.error('Erreur lors de l’envoi du message:', error);
      alert("Erreur lors de l'envoi");
    }
  };
  </script>
  
  <style scoped>
  .titre-container{
    margin-top: 20vh;
    color: white;
    margin-left: 10%;
  }
  .message-container {
    display: flex;
    border: 1px solid white;
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
  </style>
  