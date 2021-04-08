function handleBurger() {
    const burgers = document.querySelectorAll('.burger')
    burgers.forEach(burger => {
        burger.addEventListener('click', () => {
            burger.classList.toggle('active')
            document.getElementById(burger.dataset.target).classList.toggle('menu-active')
            document.querySelector('body').classList.toggle('overflow-hidden')
        })
    })
}

export default handleBurger
