export default function ajaxRequests() {
    if (document.querySelector('.request-button') == null) return false
    document.querySelector('.request-button').addEventListener("click", () => {
        const url = 'http://127.0.0.1:8000/card/ajax'
        fetch(url).then(response => response.json()).then(data => console.log(data))
    })
}