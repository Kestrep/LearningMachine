export default function ajaxRequests() {
    if (document.querySelector('.request-button') == null) return false
    document.querySelector('.request-button').addEventListener("click", () => {
        const url = 'http://127.0.0.1:8000/card/ajax'
        fetch(url).then(response => response.json()).then(data => {
            // Initiation de la première card
            let playGroundObject = document.querySelector('.playground-ctr')
            playGroundObject.querySelector('.card-side.front .main-ctnt').textContent = data[0].frontMainContent
            playGroundObject.querySelector('.card-side.back .main-ctnt').textContent = data[0].backMainContent

            console.log(data)
        })
    })

    // document.querySelector('.request-post-button').addEventListener("click", () => {
    //     console.log(post)
    // })
}