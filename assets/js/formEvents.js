import { $, textToHTML } from './utilities'
import { displayModal } from './modal'

/**
 * Actualise l'évènement lié à l'update de la sous-catégorie lors d'une modification d'une catégorie
 */
function addUpdateEvent(form) {
    // TODO : supprimer
    const categorySelect = $('#card_category', form)
    const subcategorySelect = $('#card_subcategory', form)

    // Vérifie qu'on est dans le formulaire
    if (categorySelect == null || subcategorySelect == null) return


    categorySelect.addEventListener('change', e => {
        // Vérifie qu'on est sur une catégorie déjà existante
        if (e.target.value === "Ajouter une catégorie" || e.target.value === "Ajouter une sous-catégorie") return

        const subcatUrl = $('#card_category', form).dataset.getsubcategoriesurl

        // Va chercher les subcategories correspondantes
        fetch(subcatUrl, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                "Content-type": "application/json"
            },
            body: JSON.stringify({
                id: e.target.value
            })
        }).then(result => result.json()).then(objects => {
            console.log(objects)
            subcategorySelect.innerHTML = ''

            // Ajoute les options au select
            objects.forEach(obj => {
                const optionElement = document.createElement('option')
                optionElement.value = obj.id
                optionElement.innerText = obj.name
                subcategorySelect.append(optionElement)
            })
            addNewTaxonomyOption(subcategorySelect)
        })
    })
}


/**
 * Ajoute une l'option 'Ajouter une option "Nouvelle catégorie" et lier la modale de création de taxonomie au champ de formulaire'
 * @param {HTMLElement} taxonomyField Le select auquel ajouter l'option 'Créer nouvelle catégorie ou Sous-catégorie 
 */
function addNewTaxonomyOption(taxonomyField) {

    let newOption = ''
    if (taxonomyField.id === 'card_subcategory') {
        newOption = textToHTML('<option class="form-button">Ajouter une sous-catégorie</option>')
    } else if (taxonomyField.id === 'card_category') {
        newOption = textToHTML('<option class="form-button">Ajouter une catégorie</option>')
    }

    // TODO : changer le newurl - ne veut rien dire
    newOption.addEventListener('click', async() => {
        // Trouver l'ID de la catégorie en cours // TODO : Si nouvelle subcategory, alors on prérempli la catégorie
        let categoryId = taxonomyField.closest('form').querySelector('#card_category').value

        // Url du formulaire pour la catégorie  ou la sous catégorie
        let url = taxonomyField.dataset.newurl

        // If the taxonomyField is sub_category, get the category value and add it to the url
        if (taxonomyField.id === 'card_subcategory') {
            url = url + '/' + categoryId
            console.log(url)
        }

        // Go fetch the form
        let form = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(async(res) => {
                let result = await res.text()
                result = textToHTML(result)
                return result
            })
            // Display the form in the modal
        displayModal(form, () => {
            // Refresh
            location.reload();

        })
    })

    // Ajoute l'élément au options
    taxonomyField.appendChild(newOption)

    // l'option doit avoir un data-target
}



/**
 * 
 * @param {HTMLElement} form Formulaire sur lequel ajouter les évènements de formulaire 
 */
export default function addFormEvents(form) {
    addUpdateEvent(form)
    form.querySelectorAll('.js-taxonomy-field').forEach(taxonomyField => {
        addNewTaxonomyOption(taxonomyField)
    })
}