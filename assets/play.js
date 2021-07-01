import { $, textToHTML } from './js/utilities';
/**
 * Les cycles de vie d'un carte :
 * 
 *  Elle est en attente dans le deck
 * 
 *  Elle est mise sur le devant de la scène
 *      initialize
 * 
 *  Elle est jouée
 *      Configurée dans les eventsListener
 * 
 *  Elle est remise dans le deck et les valeurs sont réinitialisées, ou envoyée au back
 *      updateCard
 * 
 * 
 * Playsteps of cards :
 *  1 = jamais jouée
 *  2 = jouée une fois avec succès
 *  3 = jouée deux fois avec succès
 *  4 = en attente de suppression (suite à l'update dans la base de donnée)
 */

let deck = [];
let current;
const playground = $('.playground-ctr');


/**
 * Configure le deck, configure les cartes
 */
async function updatePlayground() {
    console.log('updatePlayground')
        // Avoir toujours 20 cartes à jouer (donc dont le playstep est strictement inférieur à 4) dans le deck
    let realDeckCount = 0;
    const DECK_SIZE = 20;
    deck.forEach(card => {
        if (card.playstep < 4) realDeckcount += 1;
    })

    if (realDeckCount <= DECK_SIZE) {
        await fetch(playground.dataset.bank + '/' + (DECK_SIZE - realDeckCount)).then(res => res.json()).then(res => {
            res.map(card => {

                // Transforme le render text HTML
                card.html = textToHTML(card.html)

                // Ajoute les events listener
                addFrontEvents($('.card-side.front', card.html))
                addBackEvents($('.card-side.back', card.html))
            })

            deck = deck.concat(res)
        })

    }

    console.log(deck)
        // Initie la première carte
        // ! Ca va poser problème !
    $('section.main').textContent = ''
    $('section.main').appendChild(deck[0].html)
        // if (current == null) initialize(card)
}

function addFrontEvents() {
    console.log('Front Event')
}

function addBackEvents() {
    console.log('Back  Event')
}


updatePlayground()