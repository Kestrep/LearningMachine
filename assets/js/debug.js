import { $ } from './utilities'

/**
 * Debug Button for the creation page, where you can select the number of words and copy it into clipboard
 */
export default function debugButtons() {

    // PONS BUTTON
    $('.pons-button').addEventListener('click', e => {
        const json = 'https://jsonplaceholder.typicode.com/todos/1'
        const secret = 'bbc4f2e30869932dae59107ffd186c23eb684fd043690d8b77cb213be118e6b9'
        const domain = 'https://api.pons.com/v1/dictionary?'
        const lang = 'de'
        const word = 'vor'

        const param = `q=${word}&l=dees"`

        fetch(domain + param, {
            headers: {
                "X-Secret": secret
            }
        }).then(res => console.log(res))
    })

    // LEIPZIG BUTTON
    // Voir documentation http://api.corpora.uni-leipzig.de/ws/swagger-ui.html
    $('.leipzig-button').addEventListener('click', async e => {

        const proxy = 'https://cors-anywhere.herokuapp.com/'
        const sentence = "http://api.corpora.uni-leipzig.de/ws/sentences/deu_news_2012_1M/sentences/erinnern"
        const sentenceTagged = "http://api.corpora.uni-leipzig.de/ws/sentences/deu_news_2012_1M/sentencestagged/erinnern"

        let defResult = await fetch(proxy + sentence).then(res => res.json()).then(res => {
            // Unlock proxy here : https://cors-anywhere.herokuapp.com/corsdemo
            console.log(res.sentences)
            let arr = res.sentences
            let result = arr.map(single => {
                return single.sentence
            })

            return result
        })

        let temp_sentence = defResult[0]
        let temp_result = temp_sentence.match(/\b(\w+)\b/g)

        temp_result.forEach(word => {
            fetch(proxy + `http://api.corpora.uni-leipzig.de/ws/words/deu_news_2012_1M/word/${word}`).then(res => res.json()).then(res => {
                console.log(res)
            })
        })
    })

}