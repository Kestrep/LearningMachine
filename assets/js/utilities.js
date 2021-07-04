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

/**
 * Convertit une string en element HTML
 * @param {String} string 
 * @returns {HTMLElement}
 */
const textToHTML = string => {

    let temp = document.createElement('div')
    temp.innerHTML = string

    return temp.firstChild
}

/**
 * 
 * @param {String} message 
 * @param {String} exclamation 
 * @param {String} color 
 * @param {String} icon
 */
const displayFlash = (message, exclamation = null, color = 'green', icon = 'thumbs-up') => {

    if (message.length < 7 && exclamation === null) {
        exclamation = message
        message = null
    } else if (exclamation === null) {
        exclamation = 'Bravo !'
    }

    $('.flash-ctr').innerHTML = `<a class="btn-${color} flash">${exclamation} <i class="icon-${icon} inht"></i></a>`
    $('.info-scroll').innerHTML = message

    setTimeout(() => {
        $('.flash-ctr').classList.add('display-flash')
        setTimeout(() => {
            $('.flash-ctr').classList.remove('display-flash')
        }, 5000)
    }, 100)
}

export { $, ifExist, textToHTML, displayFlash }