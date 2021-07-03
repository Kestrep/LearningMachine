// import handleCard from './js/handleCard__delete';
import { $, textToHTML } from './js/utilities';
/** Informations générales
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
let tempRes = [{ "id": 641, "frontMainContent": "Aperiam illo dolor odio.", "frontSubcontent": "Sit inventore adipisci rerum iure distinctio et.", "backMainContent": "Libero expedita blanditiis voluptates at.", "stage": 4, "html": "<div class=\"playground-ctr stacked-cards\" data-bank=\"\/card\/get\">\n    <nav class=\"card-menu active d-ee\">\n        <div class=\"stage-icons d-ce\">\n            <i class=\"icon-star-fill\"><\/i>\n            <i class=\"icon-star-fill\"><\/i>\n            <i class=\"icon-star-empty\"><\/i>\n        <\/div>\n        <div class=\"edit-icons d-cc modal-trigger\" data-target=\"test-modal\"><i class=\"icon-closed-eye\"><\/i><\/div>\n        <a href=\"\/card\/641\/edit\" class=\"edit-icons d-cc modal-trigger\"><i class=\"icon-feather\"><\/i><\/a>\n    <\/nav>\n\n    <div class=\"card-side front moving-card\">\n        <div class=\"content d-cc\">\n            <div class=\"main-ctnt\">Aperiam illo dolor odio.<\/div>\n            <div class=\"sub-ctnt\">Sit inventore adipisci rerum iure distinctio et.<\/div>\n        <\/div>\n    <\/div>\n    <div class=\"card-side back\">\n        <div class=\"content d-cc\">\n            <div class=\"main-ctnt\">Libero expedita blanditiis voluptates at.<\/div>\n            <div class=\"sub-ctnt\">Deleniti aperiam sint nobis in ipsam numquam commodi facere.<\/div>\n        <\/div>\n    <\/div>\n\n    <div class=\"note-ctr\">\n        Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque non quasi unde veniam, dolores facere voluptatem error quis numquam sequi necessitatibus vitae eaque cumque maiores?\n    <\/div>\n<\/div>\n" }, { "id": 637, "frontMainContent": "Numquam ut illum voluptatem.", "frontSubcontent": "Laborum a dignissimos error repellendus.", "backMainContent": "Dolorem a porro ipsa.", "stage": 7, "html": "<div class=\"playground-ctr stacked-cards\" data-bank=\"\/card\/get\">\n    <nav class=\"card-menu active d-ee\">\n        <div class=\"stage-icons d-ce\">\n            <i class=\"icon-star-fill\"><\/i>\n            <i class=\"icon-star-fill\"><\/i>\n            <i class=\"icon-star-empty\"><\/i>\n        <\/div>\n        <div class=\"edit-icons d-cc modal-trigger\" data-target=\"test-modal\"><i class=\"icon-closed-eye\"><\/i><\/div>\n        <a href=\"\/card\/637\/edit\" class=\"edit-icons d-cc modal-trigger\"><i class=\"icon-feather\"><\/i><\/a>\n    <\/nav>\n\n    <div class=\"card-side front moving-card\">\n        <div class=\"content d-cc\">\n            <div class=\"main-ctnt\">Numquam ut illum voluptatem.<\/div>\n            <div class=\"sub-ctnt\">Laborum a dignissimos error repellendus.<\/div>\n        <\/div>\n    <\/div>\n    <div class=\"card-side back\">\n        <div class=\"content d-cc\">\n            <div class=\"main-ctnt\">Dolorem a porro ipsa.<\/div>\n            <div class=\"sub-ctnt\">Eaque explicabo sit harum perferendis veniam labore dignissimos.<\/div>\n        <\/div>\n    <\/div>\n\n    <div class=\"note-ctr\">\n        Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque non quasi unde veniam, dolores facere voluptatem error quis numquam sequi necessitatibus vitae eaque cumque maiores?\n    <\/div>\n<\/div>\n" }, { "id": 635, "frontMainContent": "Dolores quisquam.", "frontSubcontent": "Suscipit quo accusantium itaque et aperiam expedita.", "backMainContent": "Ipsum quaerat sequi.", "stage": 1, "html": "<div class=\"playground-ctr stacked-cards\" data-bank=\"\/card\/get\">\n    <nav class=\"card-menu active d-ee\">\n        <div class=\"stage-icons d-ce\">\n            <i class=\"icon-star-fill\"><\/i>\n            <i class=\"icon-star-fill\"><\/i>\n            <i class=\"icon-star-empty\"><\/i>\n        <\/div>\n        <div class=\"edit-icons d-cc modal-trigger\" data-target=\"test-modal\"><i class=\"icon-closed-eye\"><\/i><\/div>\n        <a href=\"\/card\/635\/edit\" class=\"edit-icons d-cc modal-trigger\"><i class=\"icon-feather\"><\/i><\/a>\n    <\/nav>\n\n    <div class=\"card-side front moving-card\">\n        <div class=\"content d-cc\">\n            <div class=\"main-ctnt\">Dolores quisquam.<\/div>\n            <div class=\"sub-ctnt\">Suscipit quo accusantium itaque et aperiam expedita.<\/div>\n        <\/div>\n    <\/div>\n    <div class=\"card-side back\">\n        <div class=\"content d-cc\">\n            <div class=\"main-ctnt\">Ipsum quaerat sequi.<\/div>\n            <div class=\"sub-ctnt\">Sint est aut ipsa nihil dolor consequuntur officiis.<\/div>\n        <\/div>\n    <\/div>\n\n    <div class=\"note-ctr\">\n        Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque non quasi unde veniam, dolores facere voluptatem error quis numquam sequi necessitatibus vitae eaque cumque maiores?\n    <\/div>\n<\/div>\n" }]
const playground = $('.playground-ctr');


/**
 * Configure le deck, configure les cartes
 */
export default async function updatePlayground() {
    console.log('updatePlayground')

    // Avoir toujours 20 cartes à jouer (donc dont le playstep est strictement inférieur à 4) dans le deck
    let realDeckCount = 0;
    const DECK_SIZE = 20;
    deck.forEach(card => {
        if (card.playstep < 4) realDeckCount += 1;
    })

    /* if (realDeckCount <= DECK_SIZE) {
        await fetch(playground.dataset.bank + '/' + (DECK_SIZE - realDeckCount)).then(res => res.json()).then(res => {
            res.map(card => {

                // Transforme le render text HTML
                card.html = textToHTML(card.html)

                // Ajoute les events listener
                addFrontEvents($('.card-side.front', card.html))
                    // addBackEvents($('.card-side.back', card.html))
            })

            deck = deck.concat(res)
        })
    } */

    if (deck < 2) {
        tempRes.map(card => {

            card.html = textToHTML(card.html)
            card.frontPlayed = false
            card.playstep = 1

            // Ajoute les events listener
            addFrontEvents($('.card-side.front', card.html), card)
            addBackEvents($('.card-side.back', card.html), card)
        })

        deck = deck.concat(tempRes)

        console.log("Téléchargement terminé. Let's go avec les " + deck.length + " cartes !")

    }
    // Initie la première carte du deck
    // ! Ca va poser problème !
    $('.playground-limits').textContent = ''
    $('.playground-limits').appendChild(deck[0].html)
}


const handleCard = async(card, order = 'fail') => {

    // Gérer les évènements quand le front a été joué
    if (card.frontPlayed === false) {

        card.frontPlayed = true

        // On enregistre le succès
        if (order === 'fail') {
            card.frontSuccess = false
        } else if (order === 'success') {
            card.frontSuccess = true
        }

        // Gérer les évènements quand le front et le back ont été joués
    } else if (card.frontPlayed === true) {
        if (order === 'fail') {
            // On remet dans le deck 3 cartes plus tard
        } else if (order === 'success') {

            // Ca dépend du playstep
            if (card.playstep === 1) { // Carte jamais jouée

                // On envoie un peu plus loin (+3)
            } else if (card.playstep === 2) { // Carte jouée une fois avec succès

                // On envoie un peu plus loin (+7)

            } else if (card.playstep === 3) { // Carte jouée deux fois avec succès

                // On update son stage dans la bdd

                // On supprime la carte du deck

            }

        } else {
            console.error('Fail or Success required')
        }

        updatePlayground()
            // on relance le jeu
    }
}

// ######   #######   ###################################################################################################################
// ##       ##   ###  ###################################################################################################################
// ####     #######   ################################################################################################################### 
// ##       ##  ##    ###################################################################################################################  
// ##       ##   ###  ###################################################################################################################   


function addFrontEvents(element, card) {

    let iX, iY; // Initial X and Y
    let rX, rY; // Relative X to initials
    let traveledX, traveledY; // Chemin parcouru entre -1 et 1
    const Threshold = 100; // Seuil en pixels
    const opacityThreshold = 0.5; // Seuil en pourcentage
    let initiate = false

    // mouse down ==> récupère des coordonnées
    element.addEventListener('mousedown', e => {

        if (card.frontPlayed === true) return
            // Initialize X and Y
        iX = e.clientX;
        iY = e.clientY;

        // Initiate the mousemove
        initiate = true

    })

    // mouse move ==> fait bouger la carte
    element.addEventListener('mousemove', e => {

        if (!initiate || card.frontPlayed === true) return
        rX = e.clientX - iX;
        rY = e.clientY - iY;

        traveledX = rX / Threshold

        element.style.transform = `translate(${traveledX * 50}%, ${rY}px) rotateZ(${traveledX * 45}deg)`

        if (Math.abs(traveledX) > opacityThreshold) {
            // Calcul de l'opacité
            let opacityPourcent = (Math.abs(traveledX) - opacityThreshold) / (1 - opacityThreshold)

            element.style.opacity = 1 - opacityPourcent
        }

        if (traveledX > 1) {
            element.style.transform = `translate(200%)`
            handleCard(card, 'success')
            initiate = false
        } else if (traveledX < -1) {
            element.style.transform = `translate(-200%)`
            handleCard(card, 'fail')
            initiate = false
        }

    })



    element.addEventListener('mouseleave', e => {
        if (card.frontPlayed === true) return
        console.log('mouseleave of front')
        initiate = false
        element.style.transform = `translate(${0 * 50}%, ${0}px) rotateZ(${0 * 45}deg)`
        element.style.opacity = 1
    })

    element.addEventListener('mouseup', e => {
        if (card.frontPlayed === true) return
        initiate = false

        traveledX = 0;
        element.style.transform = `translate(${0 * 50}%, ${0}px) rotateZ(${0 * 45}deg)`
        element.style.opacity = 1

    })
}

// #######      ##      #######  ##    ##                #############################################################################################
// ##    #     ####     ##       ##  ##                  #############################################################################################
// #######    ##  ##    ##       #######                 #############################################################################################
// ##    #   ########   ##       ##   ##                 #############################################################################################
// #######  ##      ##  #######  ##    ##                #############################################################################################



function addBackEvents(element, card) {

    let iX, iY; // Initial X and Y
    let rX, rY; // Relative X to initials
    let traveledX, traveledY; // Chemin parcouru entre -1 et 1
    const Threshold = 100; // Seuil en pixels
    const opacityThreshold = 0.5; // Seuil en pourcentage
    let initiate = false

    // mouse down ==> récupère des coordonnées
    element.addEventListener('mousedown', e => {

        if (card.frontPlayed === false || card.frontPlayed === null) return

        // Initialize X and Y
        iX = e.clientX;
        iY = e.clientY;

        // Initiate the mousemove
        initiate = true

    })

    // mouse move ==> fait bouger la carte
    element.addEventListener('mousemove', e => {

        if (!initiate || card.frontPlayed === false || card.frontPlayed === null) return
        rX = e.clientX - iX;
        rY = e.clientY - iY;

        traveledX = rX / Threshold

        element.style.transform = `translate(${traveledX * 50}%)`

        if (Math.abs(traveledX) > opacityThreshold) {
            // Calcul de l'opacité
            let opacityPourcent = (Math.abs(traveledX) - opacityThreshold) / (1 - opacityThreshold)

            element.style.opacity = 1 - opacityPourcent
        }

        if (traveledX > 1) {
            handleCard(card, 'success')
            initiate = false
            element.style.transform = `translate(200%)`
        } else if (traveledX < -1) {
            handleCard(card, 'fail')
            initiate = false
            element.style.transform = `translate(-200%)`
        }

    })



    element.addEventListener('mouseleave', e => {
        if (card.frontPlayed === false || card.frontPlayed === null) return
        console.log('mouseleave of back')
        initiate = false
        element.style.transform = `translate(0%)`
        element.style.opacity = 1
    })

    element.addEventListener('mouseup', e => {
        if (card.frontPlayed === false || card.frontPlayed === null) return
        initiate = false
        element.style.transform = `translate(0%)`
        element.style.opacity = 1
    })

    element.addEventListener('dblclick', e => {
        if (card.frontPlayed === false || card.frontPlayed === null) return
        console.log('Double click')
    })
}