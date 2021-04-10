function handleCard() {
    let currentCard = document.querySelector('.moving-card')
    currentCard.style.transformOrigin = '50%, 50%'
    currentCard.style.transition = '3ms ease-in'

    let initialX = 0
    let initialY = 0
    let relativeX = 0
    let relativeY = 0
    let translateX = 0
    let translateY = 0
    let rotatePercent = 0
    let initiate = false

    const littleThreshold = 50
    const middleThreshold = 100
    const bigThreshold = 200

    currentCard.addEventListener('mousedown', e => {
        initialX = e.clientX
        initialY = e.clientY
        initiate = true

        console.log('Hi')
    });

    currentCard.addEventListener('mousemove', e => {
        relativeX = e.clientX-initialX
        relativeY = e.clientY-initialY

        if(Math.abs(relativeX) < littleThreshold) {
            translateX = relativeX
            translateY = relativeY
            rotatePercent = 0
        } else if (Math.abs(relativeX) > littleThreshold && Math.abs(relativeX) < bigThreshold) {
            direction = (relativeX / Math.abs(relativeX))
            translateX = (relativeX * 2) - direction * littleThreshold
            translateY = relativeY
            rotatePercent = direction * (Math.abs(relativeX) - littleThreshold) / littleThreshold

            /**
             * Quand abs(relativeX) = littleThreshold, rotate = 0
             * Quand abs(relativeX) = middleThreshold, rotate = 1
             * 
             * 
             * 
             */
            console.log(rotatePercent)
        }

        if(initiate) currentCard.style.transform = `translate(${translateX}px, ${translateY}px) rotateZ(${rotatePercent * 45}deg)`
        
    })

    document.addEventListener('mouseup', () => {
        initiate = false

        // Restore if threshold passed
        if(Math.abs(relativeX) < middleThreshold) {
            currentCard.style.transform = `translate(${0}px, ${0}px)`
        } else {
            currentCard.style.transform = `translate(${(relativeX / Math.abs(relativeX) * 100)}vw, ${0}px) rotateZ(0)`
        }
    })


    // DEBUG
    document.querySelector('.restore-button').addEventListener('click', e => {
        currentCard.style.transform = `translate(${0}px, ${0}px)`
    })

}

handleCard()
