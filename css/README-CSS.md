# Tache : CSS


## Tries des classes CSS :
il faut verifier que toutes les classes css utilisé
dans une page soit bien presentes dans le nouveaux css *(surtout le css de **affichierpost.css**, j'ai eu 2-3 problemes avec son fichier php...)*

## Organisation CSS :

il faut que les classe css presents dans chaque fichiers, soit rangé par ordre d'apparition dans le fichier php, pour une meilleur rapidité pour modifier, ajouter et supprimer des propriété ou classes.

## Les details ... :
- Suprimmer les propriétés ou classes inutile / inutilisé
- Pour chaque classe, renommer la classe avec au debut, le nom de la page ou son abbréviation :
  par exemple : si la classe "post-content" est utilisé dans la page **afficherpost.php**, renommer la classe comme -> "AffPost-post-content" par exemple.
  cela permet d'éviter les conflit de classe entre les differente page ou peut se retrouver cette classe *(classe **post-content** presente dans **afficherpost.php** et **profilViewer.php**)*
- Regrouper les propriété *(si possible)* :
  exemple :
```
.elementA {
 margin: 0; 
 padding: 0; 
 color: black;
 }

.elementB {
margin : 0;
padding 0;
background-color : #000;
}
```

devient :

```
.elementA, .elementB {
    margin: 0; 
    padding: 0;
}

.elementA {
    color: black;
}

.elementB {
    background-color : #000;
}
```
**ATTENTION** : en mettant les propriété commune **AVANT** les propriétés propres a chaque classes *(pour une éventuel modification propre d'une classe)*

