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
import handleModal, { initializeModal } from './js/modal';
import formEvents from './js/formEvents';
import { $, ifExist, displayFlash, textToHTML } from './js/utilities';
import updatePlayground from './play.js'

handleBurger()

window.addEventListener('load', function() {
    // Component
    // debugButtons()
    // Initialisation des modals lors du chargement


    document.querySelectorAll('.modal-trigger').forEach(trigger => {
        console.error(['Pas de content défini !!', trigger]) // initializeModal(trigger, content)
    })


    formEvents()
    updatePlayground()
    if ($('.flash-ctr') && $('.flash-ctr').innerHTML !== '') {
        console.error('There is a flash in the page')
    }
})

// start the Stimulus application
// import './bootstrap';

$('.flash-button').addEventListener('click', () => {
    displayFlash('Il est important de debugger', 'Coucou', 'orange', 'feather')
})