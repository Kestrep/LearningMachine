export default function grabCard() {

    // Configuration
    const THRESHOLD = 100
    const currentCard = document.querySelector('.learning-card ')

    // Mouse's variables
    let initial_X
    let initial_Y
    let difference_X
    let difference_Y

    // State of the play
    let isClicking = false
    let answerShown = false
    let EXCLUDE = false // TODO : remove - when the changeCard function is ready !
    let backDisplay = false
    
    // As mouseDown, the initial position is set
    currentCard.addEventListener('mousedown', e => {
        initial_X = e.clientX
        initial_Y = e.clientY
        isClicking = true
    })

    window.addEventListener('mouseup', e => {
    
        isClicking = false
        if(!EXCLUDE) {
            currentCard.querySelector('.cards-ctr').style.transform = `translateX(0) rotateZ(0deg)`
        }
    })

    // If front display, only the reverse action is display
    window.addEventListener('mousemove', e => {

        // Register the mouvement of the mouse
        if (isClicking) {
            difference_X = initial_X - e.clientX
            difference_Y = initial_Y - e.clientY
        } else {
            return
        }

        // If front display, only the reverse action is display when threshold is cross
        currentCard.querySelector('.cards-flip').style.transform = `rotateX(${difference_Y / THRESHOLD * 90 }deg)`
        if(difference_Y > THRESHOLD || difference_Y < - THRESHOLD) {
            if(!backDisplay) {
                currentCard.querySelector('.cards-flip').style.transform = `rotateX(180deg)`
            } else {
                currentCard.querySelector('.cards-flip').style.transform = `rotateX(0deg)`
            }
            answerShown = true
            backDisplay = !backDisplay
            isClicking = false

            return
        }

        // If answerShown 
        if(answerShown) {
            currentCard.querySelector('.cards-ctr').style.transform = `translateX(${ - difference_X * 2}px) rotateZ(${ - difference_X / THRESHOLD * 45}deg)`

            if(difference_X  > THRESHOLD || difference_X  < -THRESHOLD/*  || difference_Y  > THRESHOLD || difference_Y */) {

                // Exclude the card of the screen
                currentCard.querySelector('.cards-ctr').style.transform = `translateX(${ - difference_X / THRESHOLD * 150}vw) rotateZ(${ - difference_X / THRESHOLD * 45}deg)`

                // reinit
                EXCLUDE = true
                isClicking = false
            }
        }

    })
    
    // TOFIX: remove
    /**
     * Special command to reset the card position -- DEV only
     */
    
    document.querySelector('#reset').addEventListener('click', e => {
        currentCard.style.transform = `translateX(0) rotateZ(0deg)`
    })
}
