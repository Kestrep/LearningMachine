import { addModalEvents } from './modal'

/**
 * 
 * @returns 
 */
function updateSubCategoryInput() {
    // TODO : supprimer
    const partialUrl = 'http://127.0.0.1:8000/card/new/ajax/getSubCategory/' // Ajouter l'ID
    const categoryInput = document.querySelector('#card_category')
    const subCategoryInput = document.querySelector('#card_subCategory')

    // Vérifie qu'on est dans le formulaire
    if (categoryInput == null || subCategoryInput == null) return

    categoryInput.addEventListener('change', e => {

        // Vérifie qu'on est sur une catégorie
        if (e.target.value === "Ajouter une catégorie") return

        // Va chercher les subcategories correspondantes
        fetch(partialUrl + e.target.value).then(result => result.json()).then(objects => {
            console.log(objects)
            subCategoryInput.innerHTML = ''

            // Ajoute les options au select
            objects.forEach(obj => {
                const optionElement = document.createElement('option')
                optionElement.value = obj.id
                optionElement.innerText = obj.name
                subCategoryInput.append(optionElement)
            })

            // Ajoute l'option 'Add new catégorie'
            addOption(subCategoryInput)
        })
    })
}


/**
 * Ajoute une l'option 'Ajouter une catégorie au select'
 * @param {*} element 
 */
function addOption(taxonomyField) {

    let formOption = document.createElement('option')

    formOption.textContent = "Ajouter une catégorie"
    formOption.classList.add('form-button', 'modal-trigger')
        // définit l'url
    formOption.attributes.href = taxonomyField.dataset.target
        // Trouver l'ID de la catégorie en cours
    let categoryId = taxonomyField.closest('form').querySelector('#card_category').value
    if (taxonomyField.id === 'card_subCategory') {
        formOption.attributes.href += '/' + categoryId
    }

    console.log(formOption.attributes.href)
        // Trouver l'url pour le formulaire

    addModalEvents(formOption, async() => {
            let res = await fetch(formOption.attributes.href)
                .then(res => { return res.text() })
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html')
                    return doc
                })
            return res
        })
        // Ajoute l'élément au options
    taxonomyField.appendChild(formOption)
        // l'option doit avoir un data-target

}

export default function formEvents() {

    updateSubCategoryInput()
    document.querySelectorAll('.option-open-form').forEach(taxonomyField => {
        addOption(taxonomyField)
    })
}