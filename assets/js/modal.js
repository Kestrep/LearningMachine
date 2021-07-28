import { $, displayFlash, textToHTML } from './utilities'

const openModal = () => {
    console.log('opening the Modal')


    // Ajoute la classe modal-active au body pour prévenir la fermeture
    document.querySelector('body').classList.add('modal-active')

    // Créer la structure de base de la modal
    const modal = textToHTML('<div class="modal f-cc"><div class="modal-ctr"></div></div>')

    // Ajoute la modal au <main>
    document.querySelector('main').appendChild(modal)

    return modal;
}

const addClosingEvents = (modal, closeCallback = null) => {
    modal.addEventListener('click', (e) => {

        // Gestion des submissions
        if (modal.querySelector('.submit-button')) {
            if (modal.querySelector('.submit-button').contains(e.target)) {
                e.preventDefault();
                let form = modal.querySelector('form')
                let url = modal.querySelector('.submit-button').dataset.url
                console.log(url)
                let formData = new FormData(form);

                fetch(url, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                }).then(
                    closeModal(modal)
                )
            }
        }
        // Fermeture si click en dehors de la modal ou sur l'élément .close-modal
        if (!modal.querySelector('.modal-ctr').contains(e.target) || modal.querySelector('.close-modal').contains(e.target)) closeModal(modal)
    })
}

const closeModal = (modal) => {
    console.log('Closing the modal', modal)
    document.querySelector('body').classList.remove('modal-active')
    modal.remove()
}

/**
 * 
 * @param {HTMLElement} content The content to display inside the modal
 */
export const displayModal = (content) => {
    console.log(content)
    const modal = openModal()
    console.log(modal.querySelector('.modal-ctr'))
    modal.querySelector('.modal-ctr').appendChild(content)

    addClosingEvents(modal)
}

/**
 * 
 * @param {HTMLElement} trigger L'élément qui va déclencher la modal avec un click 
 * @param {requestCallback} content Le contenu qui va s'afficher dans la modal
 * @param {Callback} closeCallback Bouton de fermeture ?
 */
export const initializeModal = async(trigger, content, closeCallback = null) => {
    console.error('DEPRECATED - a function use the deprecated initializeModal')

    // display the content if Promise
    if (typeof content === 'function') {
        let result = await content()
            // console.log(result)
    }

    trigger.addEventListener('click', e => {
        e.preventDefault()
        e.stopImmediatePropagation()

        // Créer et remplir la modale
        const modal = openModal(e)
        modal.querySelector('.modal-ctr').appendChild(content)

        // const modalContent = document.querySelector(`#${trigger.dataset.target}`) ? document.querySelector(`#${trigger.dataset.target}`) : fetchThecontent(trigger.href)
        addClosingEvents(modal, closeCallback)
    })
}