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
import MoinsVendusComponent from './composants/moinsvendus.vue';
import NouveauteComponent from './composants/nouveaute.vue';
import TendanceComponent from './composants/tendance.vue';
import FlashComponent from './composants/flash.vue';
import ActualiteComponent from './composants/actualite.vue';
import MessageComponent from './composants/message.vue';
import CatalogueComponent from './composants/catalogue.vue';



const appCategoriesComposants = createApp(CategoriesComposants);
const appFicheProduitComposants = createApp(FicheProduitComposants);
const appPanierComposants = createApp(PanierComposants);
const appRecherchesComposants = createApp(RecherchesComposants);
const appProfilComposants = createApp(ProfilComposants);
const appPartenairesComposants = createApp(PartenairesComposants);
const appTodayComponentComposants = createApp(TodayComponent);
const appMoinsVendusComponentComposants = createApp(MoinsVendusComponent);
const appNouveauteComponentComposants = createApp(NouveauteComponent);
const appTendanceComponentComposants = createApp(TendanceComponent);
const appFlashComponentComposants = createApp(FlashComponent);
const appActualiteComponentComposants = createApp(ActualiteComponent);
const appMessageComponentComposants = createApp(MessageComponent);
const appCatalogueComponentComposants = createApp(CatalogueComponent);


appCategoriesComposants.mount('#app');
appFicheProduitComposants.mount('#app2');
appPanierComposants.mount('#app3');
appRecherchesComposants.mount('#app4');
appProfilComposants.mount('#app5');
appPartenairesComposants.mount('#app6');
appTodayComponentComposants.mount('#app7');
appMoinsVendusComponentComposants.mount('#app8');
appNouveauteComponentComposants.mount('#app9');
appFlashComponentComposants.mount('#app10');
appTendanceComponentComposants.mount('#app11');
appActualiteComponentComposants.mount('#app12');
appMessageComponentComposants.mount('#app13');
appCatalogueComponentComposants.mount('#app14');




console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉')