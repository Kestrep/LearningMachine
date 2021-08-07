import { $ } from '../utilities'


export const initializeShowcards = card => {
    card.addEventListener('click', e => {
        if ($('.body', card).contains(e.target)) {

            if (card.classList.contains('folded')) {
                // Déplie la card
                card.classList.remove('folded')
                $('.foldable', card).forEach(element => {
                    element.style.maxHeight = element.scrollHeight + 'px'
                });

            } else {
                // Replie la card
                card.classList.add('folded')
                $('.foldable', card).forEach(element => {
                    element.style.maxHeight = '0px'
                });
            }
        }
    })
}