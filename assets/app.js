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
import addFormEvents from './js/formEvents';
import { $, ifExist, displayFlash, textToHTML, displayAlert } from './js/utilities';
import updatePlayground from './play.js'

handleBurger()

window.addEventListener('load', function() {
    // Component
    debugButtons()
        // Initialisation des modals lors du chargement

    document.querySelectorAll('.modal-trigger').forEach(trigger => {
        console.error(['Pas de content défini !!', trigger]) // initializeModal(trigger, content)
    })

    if ($('form')) addFormEvents($('form'))

    if ($('.flashcards-ctr')) {
        updatePlayground()
    }
    if ($('.flash-ctr') && $('.flash-ctr').innerHTML !== '') {
        console.error('There is a flash in the page')
    }

    // Display alert if alert in the DOM
    displayAlert();
})

// start the Stimulus application
// import './bootstrap';

if ($('.flash-button')) {
    console.log($('.flash-button'))
    $('.flash-button').addEventListener('click', () => {
        displayFlash('Il est important de debugger', 'Coucou', 'orange', 'feather')
    })
}