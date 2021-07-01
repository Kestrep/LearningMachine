/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './app.scss';

import handleBurger from './js/burger';
import debugButtons from './js/debug';
import handleModal from './js/modal';
import formEvents from './js/formEvents';
import { $, ifExist } from './js/utilities';

handleBurger()

window.addEventListener('load', function() {
    // Component
    debugButtons()
    handleModal()
    formEvents()
})

// start the Stimulus application
// import './bootstrap';