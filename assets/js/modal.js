function listenModal() {
    const currentModal = document.querySelector('.modal.active');
    currentModal.addEventListener('click', e => {

        e.stopImmediatePropagation()
        

        if(currentModal === null) {
            console.log('Error')
            return
        }
        const noteBox = currentModal.querySelector('.ctr')

        const top = noteBox.offsetTop
        const right = noteBox.offsetLeft + noteBox.offsetWidth
        const bottom = noteBox.offsetTop + noteBox.offsetHeight
        const left = noteBox.offsetLeft

        if(
            e.clientX > bottom
            || e.clientX < top
            || e.clientY > right
            || e.clientY < left
            || e.target.classList.contains('cancel-button')
        ) {
            currentModal.classList.remove('active')
            document.querySelector('body').classList.remove('modal-active')
        }

    })
}

export default function handleModal() {
    document.querySelectorAll('.modal-button').forEach(button => {
        button.addEventListener('click', e=> {

            console.log('click')
            e.stopImmediatePropagation()
            document.querySelector(`#${e.target.dataset.target}`).classList.add('active')
            document.querySelector('body').classList.add('modal-active')

            listenModal()
        })
    })
}