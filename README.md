#Cuistot

Une application php qui permet de générer une *recette* de cuisine, ici d'un *brownie*, à partir du listing de ses ingrédients aux actions pour sa préparation ! Cette application n'as pas de véritable but fonctionnelle, et est plus une histoire de fun et de défi "conceptuel" ;-\)

Un certain nombre de contraintes ont été rencontrées, et elles ont été relevées en principe, mais pas forcément dans un schéma englobant toutes les recettes de cuisine du monde. Pour ce faire, celà induirait un travail de factorisation a un niveau probablement supérieur, entrainant une logique bien plus conséquente ! \(On fait ici juste un brownie, n'oublions pas, qui reste une recette très "basique" !\). A néanmoins dut être géré :

* Gestion du changement des états des ingrédients concomitant à un type d'action précis.
* Gestion des accords des mots typique à la langue française, et la construction syntaxique des phrases d'actions.

####Les tableaux de valeurs générique :

* `$etalons` paramétre : chaîne de caractère qui détermine la quantification pour tous les ingrédients. La caractère spéciale spéciale "auto" détermine que l'élement étalon est l'élément en lui-même \(exemple, pour l'oeuf\).
* `$actions` : paramétre : un tableau (ici associatif) comprenant lui même deux chaînes de caractères qui déterminent respectivement le verbe qualifiant l'action, dans un but d'affichage et de lecture humaine, puis le type d'interaction sur le plat, les interactions pouvant entrainer des conséquences sur les noms des éléments stockés dans la table ingrédients (exemple : les oeufs vont se transformer en pate si ils subissent l'action melange). Mettre une chaîne de caractére vide si il n'y a pas de type d'interaction.
* `$ou` paramétre : chaîne de caractère, détermine le lieu des actions \(four, plaques chauffantes, saladiers, etc.\). Mot clé "aucun", en index\(0\), pour ne pas préciser de lieu d'action lors de l'appel de la fonction dédiée "actions".
* `$finalitees` : paramétre : chaîne de caractère. Se greffe a la fin de chaque instructions pour déterminer jusqu'à quel objectifs mener une action d'interaction sur les ingrédients. Là aussi, le mot clé "aucune" \(index\(0\)\) induit l'absence de finalité pour la phrase finale générée par la fonction "actions".
* `$ingredients` : Paramétre : un tableau composé de 4 chaîne de caractére et d'un tableau. Détermine le nom de l'ingrédient, son genre \("m" ou "f"\), pluriel \("s" ou "p"\), un adjectif le qualifiant si il subit l'action "cuisson" et un tableau contenant les infos nécessaires si il subit l'action "melange". Les données de ce tableau là écrasent en faît les infos vue précédemment si il subit une action. Actuellement, il n'y a qu'un type d'action susceptible de changer les données initiales, qui est "melange". Lorsque les colonnes "cuisson" ou "melange" contiennent la suite de caractères "rien", celà revient à préciser qu'il n'y a pas d'interactions entre cet ingrédient et les dites actions.

####Les fonctions php qui permettent d'imbriquer les actions :

* `ingredient` : function qui renvoi une chaine de caractéres lisible, qui caractérise chaque ingrédient ; Prend en paramétre 3 chaînes de caractères, son nom, sa quantité et son métre étalon.
* `liste_ingredients` : boucle sur la liste des fonctions passées en paramètres et renvois la chaine à afficher pour l'utilisateur.
* `actions` : LA fonction de ce script, qui gére les interactions entre les différents éléments du tableau, et génére une phrase intelligible destinée à l'affichage. Prend respectivement en paramétres deux tableaux \(ingrédients et actions\), et deux chaînes de caractères \(lieu de l'action et finalités de celle-ci\).
* `faire_la_recette` : boucle sur la liste des actions.

####Tableaux spécifiques au brownie :
* `$liste_ingredients_brownie` : Tableau qui liste les fonction ingrédients autant de fois qu'il le faut pour faire un brownie !
* `$actions_ingredients_brownie` : Tableau qui liste les actions et paramètres spécifiques pour faire ce même brownie !

####Exécution du code :

	echo liste_ingredients($liste_ingredients_brownie);
	echo "======================================<br />";
	echo faire_la_recette($actions_ingredients_brownie);
	
####Créateur

  * Simon Ertel ([korvus](https://github.com/korvus08))