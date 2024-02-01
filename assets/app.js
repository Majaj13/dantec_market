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
const appCategoriesComposants = createApp(CategoriesComposants);
const appFicheProduitComposants = createApp(FicheProduitComposants);
appCategoriesComposants.mount('#app');
appFicheProduitComposants.mount('#app2');

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉')
