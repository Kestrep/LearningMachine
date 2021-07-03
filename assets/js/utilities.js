/**
 * Retourne le résultat de la sélection sour forme de tableau s'il y a plusieurs éléments, sous forme d'élément HTML s'il n'y a qu'un élément
 * @param {*} selector 
 * @param {*} selector 
 */
const $ = (selector, container = null, log = false) => {

    if (log) console.log(container)

    if (container === null) container = document
    const nodeList = container.querySelectorAll(selector);

    if (log) console.log('Utilities : ' + nodeList.length + ' éléments sélectionnés.')

    if (nodeList.length === 1) {
        return nodeList[0]
    }

    return nodeList;
}

/**
 * Si l'élément que je lui donne existe, alors elle exécute le callback avec l'élément comme paramètre
 * @param {String || HTMLElement} element 
 * @param {Function} callback 
 * @returns 
 */
const ifExist = (element, callback) => {

    if (typeof element === 'string') element = $(element)
    if (element == null) return

    callback(element)
}

const textToHTML = string => {

    let temp = document.createElement('div')
    temp.innerHTML = string

    return temp.firstChild
}

export { $, ifExist, textToHTML }