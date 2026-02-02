
## 1. Filtres export Excel


Ajouter au Back Office Admin, un menu "Export Excel", qui permet d'exporter les "ventes" (UserOwnGames).


On doit pouvoir filtrer par :
- période (début et fin), avec :
  - fin est saisie, mais pas le début, on prend tout ce qui est avant la date de fin 
  - début est saisie, mais pas la fin, on prend tout ce qui est après la date de début
- éditeur de jeux
- par jeu

Les filtres sont **cumulatifs**, ce n'est pas des "OR" !

Ajouter la dépendance Excel :

```bash
composer require phpoffice/phpspreadsheet
```
