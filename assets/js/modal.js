const openModal = (e) => {
    console.log('opening the Modal')
    e.preventDefault()
    e.stopImmediatePropagation()

    // Ajoute la classe modal-active au body pour prévenir la fermeture
    document.querySelector('body').classList.add('modal-active')

    // Créer la structure de base de la modal
    const modal = document.createElement('div')
    modal.classList.add('modal', 'd-cc')

    const modalContainer = document.createElement('div')
    modalContainer.classList.add('modal-ctr')

    modal.appendChild(modalContainer)


    // Ajoute la modal au <main>
    document.querySelector('main').appendChild(modal)

    return modal;
}

/**
 * @return {HTMLElement}
 */
const fetchThecontent = url => {

    /**
     * Deux types de form en modal :
     *  Modification de la carte
     *  Ajout d'une catégorie
     * Modification d'une catégorie
     */
    fetch(url, {
        method: 'POST',
        // body: JSON.stringify(data),
        // headers: { 'X-Requested-With': 'XMLHttpRequest' },
    }).then(result => result.json()).then(response => console.log(response))

    return document.createElement('div')
}

/**
 * @param {HTMLElement} modal
 */
const fillModal = (modal, modalContent) => {
    console.log('fill the modal', modalContent)
}

/**
 * Listen to close the modal if the user click outside the modal
 */
const listenForCloseModal = (modal) => {
    modal.addEventListener('click', (e) => {

        if (!modal.querySelector('.modal-ctr').contains(e.target)) closeModal(modal)
    })
}

const closeModal = (modal) => {
    console.log('Closing the modal', modal)
    document.querySelector('body').classList.remove('modal-active')
    modal.remove()
}


export default function handleModal() {
    document.querySelectorAll('.modal-trigger').forEach(trigger => {
        trigger.addEventListener('click', e => {
            const modal = openModal(e)

            const modalContent = document.querySelector(`#${trigger.dataset.target}`) ? document.querySelector(`#${trigger.dataset.target}`) : fetchThecontent(trigger.href)

            fillModal(modal, modalContent)
            listenForCloseModal(modal)
        })
    })
}