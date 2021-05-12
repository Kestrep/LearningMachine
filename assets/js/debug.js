/**
 * Debug Button for the creation page, where you can select the number of words and copy it into clipboard
 */
export default function debugButtons() {
    const loremCopyButton = document.querySelector('.debug .loremCopy-Button')
    const loremCopyWordCount = document.querySelector(`#${loremCopyButton.dataset.count}`);

    let wordsCount = 5;
    loremCopyWordCount.addEventListener('keyup', e => {
        wordsCount = loremCopyWordCount.value;
    });

    loremCopyButton.addEventListener('click', e => {

        let countableLorem = [];
        for (let i = 0; i < wordsCount; i++) {
            countableLorem.push('lorem')
        }

        console.log()

        navigator.clipboard.writeText(`${countableLorem.join(' ')}`).then(function() {
            /* clipboard successfully set */
        }, function() {
            /* clipboard write failed */
        });
    })
}