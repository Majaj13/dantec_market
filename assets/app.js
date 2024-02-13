import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss'
import './styles/test.scss'

import { createApp } from 'vue';
import CategoriesComposants from './composants/categoriescomposants.vue';
import FicheProduitComposants from './composants/ficheproduit.vue';
import PanierComposants from './composants/panier.vue';
import RecherchesComposants from './composants/recherches.vue';
import ProfilComposants from './composants/profil.vue';
import Actualite from './composants/actualite.vue';
import LesActualites from './composants/LesActualites.vue';
const appCategoriesComposants = createApp(CategoriesComposants);
const appFicheProduitComposants = createApp(FicheProduitComposants);
const appPanierComposants = createApp(PanierComposants);
const appRecherchesComposants = createApp(RecherchesComposants);
const appProfilComposants = createApp(ProfilComposants);
const appActualite = createApp(Actualite);
const appLesActualites = createApp(LesActualites);
appCategoriesComposants.mount('#app');
appFicheProduitComposants.mount('#app2');
appPanierComposants.mount('#app3');
appRecherchesComposants.mount('#app4');
appProfilComposants.mount('#app5');
appActualite.mount('#app6');
appLesActualites.mount('#app7');

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉')
