function handleCard() {
    let currentCard = document.querySelector('.moving-card')

    let initialX, initialY, relativeX, relativeY
    let initiate = false

    const littleThreshold = 50
    const middleThreshold = 200

    currentCard.addEventListener('mousedown', e => {
        initialX = e.clientX
        initialY = e.clientY
        initiate = true

        console.log('Hi')
    });

    currentCard.addEventListener('mousemove', e => {
        relativeX = e.clientX-initialX
        relativeY = e.clientY-initialY

        if(initiate && Math.abs(relativeX) < littleThreshold) {
            currentCard.style.transform = `translate(${relativeX}px, ${relativeY}px)`
        } else if (initiate && Math.abs(relativeX) > littleThreshold && Math.abs(relativeX) < middleThreshold) {
            currentCard.style.transform = `translate(${(relativeX * 2) - (relativeX / Math.abs(relativeX)) * littleThreshold }px, ${relativeY}px)`
        }
        
    })

    document.addEventListener('mouseup', () => {
        initiate = false

        // Restore if threshold passed
        if(Math.abs(relativeX) < littleThreshold) {
            currentCard.style.transform = `translate(${0}px, ${0}px)`
        } else {
            currentCard.style.transform = `translate(${(relativeX / Math.abs(relativeX) * 100)}vw, ${0}px)`
        }
    })


    // DEBUG
    document.querySelector('.restore-button').addEventListener('click', e => {
        currentCard.style.transform = `translate(${0}px, ${0}px)`
    })

}

handleCard()
