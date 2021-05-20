mot de passe : pass

# Right Now


- [ ] Ajouter le système d'autocomplétion de la catégorie / Sous-catégorie
  - [ ] Complétion automatique avec la dernière sous-catégorie utilisée



- [ ] Modification d'une card
  - [ ] Ajouter un champs 'dernière modification le...
  - [ ] Pouvoir faire changer de catégorie
    - [ ] En requête, donc un minimum d'information pour pouvoir faire l'effet
    - [ ] vérifier que les anciennes catégories ont déjà été supprimées

- [ ] Travailler sur les entités
  - [ ] Ajouter le User à la carte
- Ajouter le User à la carte ?

## Fonctionnement du javascript lorsqu'on jour
*On repère les cards avec leur id*

- Quand on arrive sur la page play
- Il y a une requête ajax qui va récupérer une vingtaine de cartes
- On a une variable qui stocke l'ensemble des cartes téléchargées **cardsArray**
- On initie
  - On récupère le contenu html du playground dans une variable
  - On update l'ensemble des données avec la première carte
  - On joue
  - Quand on termine, on exécute une fonction de fin de jeu
    - Avec l'update AJAX (pour augmenter le niveau ou autre)
      - Si carte apprise, alors on supprime l'objet du **cardsArray**
    - Pour replacer l'objet courant dans la liste des objets un peu plus loin


!! Il me faut un point d'appui où le jeu s'arrête !! -- Ca peut se faire via un addEventListener => Quand on bouge la carte par exemple


# Backlog



## To develop

### A réfléchir :
- Quand une carte est crée, comment faire pour pouvoir la créer sans y associer une sous-catégorie ?

### Fonctionnalités

- [ ] La partie card est réservée aux utilisateurs inscrits
- [ ] Si on arrive sur la partie card de manière anonyme, on est renvoyé vers un utilisateur spécial
  - [ ] Trouver une charte graphique montrant qu'on est bien un utilisateur anonyme
  - [ ] Faire des renvois réguliers vers l'inscription


### Design

*Quand une écriture ouun bouton est en orange, il faut pouvoir clicker dessus*

- [ ] Quand on passe sur les icones, le curseur devient une main
- [ ] Voir pourquoi il y a un overflow-x
- [ ] Faire un width-max sur les cards
- [ ] Refaire la page d'inscription // de Login
- [ ] Page d'accueil


### Divers non-urgent

- [ ] Changer toutes les requêtes dans vers les repository par une requête findBy
  - [ ] Comprendre comment fonctionne la fonction findBy
