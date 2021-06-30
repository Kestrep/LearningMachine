/**
 * Debug Button for the creation page, where you can select the number of words and copy it into clipboard
 */
export default function debugButtons() {




    //     // ? Comment faire un truc plus propre ?
    //     if (document.querySelector('.debug .loremCopy-Button') == null) return false
    //     const loremCopyButton = document.querySelector('.debug .loremCopy-Button')
    //     const loremCopyWordCount = document.querySelector(`#${loremCopyButton.dataset.count}`);
    // 
    //     let wordsCount = 5;
    //     loremCopyWordCount.addEventListener('keyup', e => {
    //         wordsCount = loremCopyWordCount.value;
    //     });
    // 
    //     loremCopyButton.addEventListener('click', e => {
    // 
    //         let countableLorem = [];
    //         for (let i = 0; i < wordsCount; i++) {
    //             countableLorem.push('lorem')
    //         }
    // 
    //         console.log()
    // 
    //         navigator.clipboard.writeText(`${countableLorem.join(' ')}`).then(function() {
    //             /* clipboard successfully set */
    //         }, function() {
    //             /* clipboard write failed */
    //         });
    //     })

    // const url = 'http://127.0.0.1:8000/card/new'
    const form = document.querySelector('form-d')
    if (!form) return

    form.addEventListener('submit', e => {
        e.preventDefault()

        const formData = new FormData(form)
            // for (let el of formData.entries()) {
            //     console.log(el)
            // }
            // return false
        fetch(url, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        }).then(result => console.log(result))
    })
}