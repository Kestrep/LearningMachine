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
    let result = document.createElement('div')
    fetch(url, {
        method: 'POST',
        // body: JSON.stringify(data),
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
    }).then(response => {
        return response.text()
    }).then(html => {
        let parser = new DOMParser();
        let doc = parser.parseFromString(html, 'text/html')
        let form = doc.querySelector('form')

        result.appendChild(form)
    })

    return result
}

/**
 * @param {HTMLElement} modal
 */
const fillModal = (modal, modalContent) => {
    modal.querySelector('.modal-ctr').appendChild(modalContent)
    console.log('fill the modal', modalContent)
}

/**
 * Listen to close the modal if the user click outside the modal
 */
const listenForCloseModal = (modal) => {
    modal.addEventListener('click', (e) => {

        if (modal.querySelector('.submit-button').contains(e.target)) {
            e.preventDefault();
            console.log('Hey !!!')
            let form = modal.querySelector('form')
            let url = modal.querySelector('.submit-button').dataset.url
            console.log(url)
            let formData = new FormData(form);

            fetch(url, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            }).then(closeModal(modal))

        }
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
 * @param {*} modalTrigger 
 * @param {*} modalContent = null
 */
export const addModalEvents = (modalTrigger, modalContent = null) => {
    modalTrigger.addEventListener('click', e => {
        console.log(e)
        const modal = openModal(e)

        if (modalContent && typeof modalContent === 'function') {
            modalContent().then(res => {
                console.log('resultat du callback', res.querySelector('form'))
                fillModal(modal, res.querySelector('form'))
            })
        } else if (!modalContent && document.querySelector(`#${modalTrigger.dataset.target}`)) {
            modalContent = document.querySelector(`#${modalTrigger.dataset.target}`)
            fillModal(modal, modalContent)
        } else if (!modalContent && modalTrigger.href) {
            modalContent = fetchThecontent(modalTrigger.href)
            fillModal(modal, modalContent)
        }
        // const modalContent = document.querySelector(`#${trigger.dataset.target}`) ? document.querySelector(`#${trigger.dataset.target}`) : fetchThecontent(trigger.href)
        listenForCloseModal(modal)
    })
}


export default function handleModal(element = null, target = null) {
    if (!element) { element = document }
    element.querySelectorAll('.modal-trigger').forEach(trigger => {
        addModalEvents(trigger)
    })
}

/**
 * A chaque modal trigger
 * J'ajoute un écouteur d'évènement
 * Je lui donne des options
 * Ce peut être le contenu
 * Ce peut être le type de contenu
 * 
 */