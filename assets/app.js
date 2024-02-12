import './bootstrap.js';
import Swiper from 'swiper';
import 'swiper/swiper-bundle.css';
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
import PartenairesComposants from './composants/partenaires.vue';
import TodayComponent from './composants/TodayComponent.vue';


const appCategoriesComposants = createApp(CategoriesComposants);
const appFicheProduitComposants = createApp(FicheProduitComposants);
const appPanierComposants = createApp(PanierComposants);
const appRecherchesComposants = createApp(RecherchesComposants);
const appProfilComposants = createApp(ProfilComposants);
const appPartenairesComposants = createApp(PartenairesComposants);
const appTodayComponentComposants = createApp(TodayComponent);

appCategoriesComposants.mount('#app');
appFicheProduitComposants.mount('#app2');
appPanierComposants.mount('#app3');
appRecherchesComposants.mount('#app4');
appProfilComposants.mount('#app5');
appPartenairesComposants.mount('#app6');
appTodayComponentComposants.mount('#app7');



console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉')