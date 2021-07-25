# Documentation du site

## Convention pour Symfony

Dans Symfony : 
    - entity : camelCase
    - variables ==> camelCase
    - class (objets) ==> PascalCase
    - varaible twig dans template : camelCase (ad.author.fullName)

## Convention pour js
    
- Dans JS :
  - variables let --> camelCase
  - functions --> camelCase
  - const objects --> PascalCase

## Convention pour html/css

Pour les classes html/css :
    la-classe-en-question

?    Block_
?    _element_
?    _modifier
?    -subword-
?    -action-
?    Object-
?    -modifier

## Architecture de la page html

Header
    _site-menu_
Main
    section.top
        _page-menu_
    section.body
        _interactive space_
    section.bottom
        _don't know yet_
Footer
Aside

## Séparer le template des composants / objets

- Card :
  - Utilisé lors du jeu
    Flashcard fait office de container
        nav.card-menu
        .side-front
        .side-back
        .note

  - Utilisé dans l'index
    ShowCard fait office de container - pour l'instant
        .body
            .content-front
            .separator
            .content-back
        nav

c