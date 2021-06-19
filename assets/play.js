/**
 * 
 */

let deck = [];
let currentPlayingCardObject;

const playgroundHtmlElement = document.querySelector('.playground-ctr');



function setCurrentPlayingCardObject(cardObject) {
    currentPlayingCardObject = cardObject;
    currentPlayingCardObject.frontPlayed = false
    if (currentPlayingCardObject.playStep === undefined) {
        currentPlayingCardObject.playStep = 1
    }

    playCycle(currentPlayingCardObject)
}

/**
 * Avec le bouton debug, va chercher un lot de carte et en rempli le deck
 */
function getCardsWithAjaxDebug() {
    // Si bouton de debug, on fait une requête AJAX avec le bouton debug
    if (document.querySelector('.request-fulldeck-button') != null) {
        document.querySelector('.request-fulldeck-button').addEventListener("click", () => {
            fetch('http://127.0.0.1:8000/card/ajax').then(response => response.json()).then(data => {
                deck = data;
                setCurrentPlayingCardObject(deck[0])
            })
        })
    }

    if (document.querySelector('.request-update-button') != null) {
        document.querySelectorAll('.request-update-button').forEach(element => {
            element.addEventListener("click", e => {
                let cardID = currentPlayingCardObject ? currentPlayingCardObject.id : null
                console.log(cardID)
                let data = {
                    "id": cardID,
                    "stage": element.dataset.stage
                }
                fetch('http://127.0.0.1:8000/card/ajax/update', {
                    method: 'POST',
                    body: JSON.stringify(data)
                }).then(result => result.json()).then(response => console.log(response))
            })
        })
    }
}

/**
 * 
 */
function addFrontCardsEvents(element) {
    element.addEventListener("click", e => {
        element.style.transform = 'translateX(1000px)'
        currentPlayingCardObject.frontPlayed = true
    })
}

/**
 * 
 */
function addBackCardsEvents(element) {
    element.addEventListener("click", e => {
        if (currentPlayingCardObject.frontPlayed) {
            element.style.transform = 'translateX(1000px)'
            updateCard(currentPlayingCardObject)
        } else {
            console.log("Le front n'a pas été joué")
        }
    })
}

/**
 * 
 */
function updateCard(currentPlayingCardObject) {
    if (currentPlayingCardObject.playStep === 1) {
        currentPlayingCardObject.playStep = 2
        replaceCard(3)
    } else if (currentPlayingCardObject.playStep === 2) {
        currentPlayingCardObject.playStep = 3
        replaceCard(7)
    } else if (currentPlayingCardObject.playStep === 3) {
        currentPlayingCardObject.playStep = 4
            // Update la carte présente

        // récupère une nouvelle carte et l'insère dans le deck

    }

    console.log('La carte est passé au stage ' + currentPlayingCardObject.playStep)
    setCurrentPlayingCardObject(deck[0])
}

/**
 * Replace une carte à l'index donné dans la variable deck
 */
function replaceCard(index) {
    console.log(deck)
    const currentCard = deck.shift();
    let newArray = [];
    for (let i = 0; i < deck.length; i++) {
        const element = deck[i];
        if (i === index) { newArray.push(currentCard) }
        newArray.push(element);
        if ((index >= deck.length && i === deck.length - 1)) { newArray.push(currentCard) }
    }
    deck = newArray
}

/**
 * 
 */
function playCycle(currentPlayingCardObject) {

    // Préparer le node playground avec les information de la carte en cours
    let currentHtmlCard = playgroundHtmlElement.cloneNode(true);

    // Remplir les champs correspondant
    currentHtmlCard.querySelector('.card-side.front .main-ctnt').textContent = currentPlayingCardObject.frontMainContent
    currentHtmlCard.querySelector('.card-side.back .main-ctnt').textContent = currentPlayingCardObject.backMainContent

    // Ajoute les eventListeners
    addFrontCardsEvents(currentHtmlCard.querySelector('.card-side.front'))
    addBackCardsEvents(currentHtmlCard.querySelector('.card-side.back'))

    document.querySelector('section.main').textContent = ''
    document.querySelector('section.main').appendChild(currentHtmlCard)
}

getCardsWithAjaxDebug()