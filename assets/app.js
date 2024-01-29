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

const appCategoriesComposants = createApp(CategoriesComposants);
appCategoriesComposants.mount('#app');

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉')
