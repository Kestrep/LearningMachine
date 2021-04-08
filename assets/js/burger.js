function handleBurger() {
    const burgers = document.querySelectorAll('.burger')
    burgers.forEach(burger => {
        burger.addEventListener('click', () => {
            burger.classList.toggle('active')
            // Dire au body de ne plus scroller
            document.querySelector('body').classList.toggle('overflow-hidden')

            // If there is a burger, take the menu and put it in the main section
            let menu = document.getElementById(burger.dataset.target)
            menu.classList.toggle('active')
        })
    })
}

export default handleBurger
