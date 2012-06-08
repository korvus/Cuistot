<?php

header("Content-Type: text/html;charset=utf-8");

/* Utile pour quantifier les ingrédients*/
$etalons = array(
    "gr",
    "sachet du commerce",
    "auto",//Mot clé
    "Cuillére à soupes",
    "Pincée"
);

$actions = array(
    "cuisson" => array("verbe"=>"cuire","interagit"=>"cuisson"),
    "melange" => array("verbe"=>"melanger","interagit"=>"melange"),
    "casse" => array("verbe"=>"casser","interagit"=>"melange"),
	"verse" => array("verbe"=>"verser","interagit"=>""),
    "ajout" => array("verbe"=>"ajouter","interagit"=>"melange"),
    "battre" => array("verbe"=>"battre","interagit"=>"melange")
);

$ou = array(
    0 => "aucun",//Mot clé
    1 => " dans le four",
    2 => " dans un saladier",
    3 => " dans une poele",
	4 => " dans un moule",
	5 => " au four"
);

$finalitees = array(
    0 => "aucune",//Mot clé
    1 => "fondu",
    2 => "mélangé",
	3 => "bien mélangé",
	4 => "craquelé légérement"
);

$ingredients = array(
    0 => array(
        "name" => "chocolat noir",
        "genre" => "m",
        "pluriel" => "s",
		"cuisson" => "fondu",
		"melange" => "rien"//rien = mot clé
    ),
    1 => array(
        "name" => "sucre en poudre",
        "genre" => "m",
        "pluriel" => "s",
		"cuisson" => "caramélisé",
		"melange" => "rien"
    ),
    2 => array(
        "name" => "beurre",
        "genre" => "m",
        "pluriel" => "s",
		"cuisson" => "fondu",
		"melange" => "rien"
    ),
    3 => array(
        "name" => "sucre vanillé",
        "genre" => "m",
        "pluriel" => "s",
		"cuisson" => "caramélisé",
		"melange" => "rien"
    ),
    4 => array(
        "name" => "oeufs",
        "genre" => "m",
        "pluriel" => "p",
		"cuisson" => "rien",//rien = mot clé
		"melange" => array("mot"=>"pate","genre"=>"f","pluriel"=>"s")
    ),
    5 => array(
        "name" => "noix de pécan",
        "genre" => "f",
        "pluriel" => "p",
		"cuisson" => "grillées",
		"melange" => "rien"
    ),
    6 => array(
        "name" => "amandes effilées",
        "genre" => "f",
        "pluriel" => "p",
		"cuisson" => "grillées",
		"melange" => "rien"
    ),
    7 => array(
        "name" => "sel",
        "genre" => "m",
        "pluriel" => "s",
		"cuisson" => "rien",
		"melange" => "rien"
    ),
    8 => array(
        "name" => "farine",
        "genre" => "f",
        "pluriel" => "s",
		"melange" => "rien"
    )
);

/* Actions */
function ingredient($nom,$etalon,$quantitee){/* étalonnage de chaque ingrédient */
    $retour = "";
    if($etalon == "auto"){
        $etalon = $nom;
        $nom = "";
    }else{
        $nom = "de ".$nom;
    }
    $retour .= "$quantitee $etalon $nom";   
    return $retour;
}

/* Listage des ingrédients */
function liste_ingredients($ingredients){
    $retour = "";
    foreach ($ingredients as &$ingredient){
        $retour .= $ingredient."<br />\n";
    }
    return $retour;
}

/* Boucle sur l'ensemble de chaque actions s'enchainant successivement */
function faire_la_recette($une_action){
    $retour = "";
	
    foreach ($une_action as &$action){
        $retour .= $action."<br />\n";
    }
    return $retour;
}

function actions($listeIngredient,$actions,$ou,$finalitees){
    $retour = "";
    $nbIngredients = sizeof($listeIngredient);
    $nbActions = sizeof($actions);
    $b = $bb = 0;
	$cuisson = 0;
	$melange = 0;
	$transformation = "rien";
	
    //Liste les actions
    foreach ($actions as &$action){
        $bb++;
        $ponctuation = ", ";
		$actioner = $action["verbe"];
		if($action["interagit"] == "cuisson"){
			$cuisson++;
		}
		if($action["interagit"] == "melange"){
			$melange++;
		}
        if($nbActions-1 == $bb){
            $ponctuation = " puis ";
        }
        if($nbActions == $bb){
            $ponctuation = "";
        }
        $retour .= "$actioner$ponctuation";
    }
	
    //Liste les ingrédients ciblés par l'action
    foreach ($listeIngredient as &$ingredient){
        $b++;
        $nom = $ingredient["name"];
        $article = "le";
        $ponctuation = ", ";

        if($ingredient["pluriel"]=="p"){
            $article = "les";
        }else{
            if($ingredient["genre"]=="m"){
                $article = "le";
            }else{
                $article = "la";
            }
        }
        if($nbIngredients-1 == $b){
            $ponctuation = " et ";
        }
        if($nbIngredients == $b){
            $ponctuation = "";
        }

        $retour .= " $article $nom$ponctuation";

		//Si il y a eu la cuisson d'un des éléments, celui ci est transformé dans le tableau principal, avec adjonction de l'adjectif lui correspondant.
		$match = 0;
		if($cuisson>0){
			global $ingredients;
			foreach ($ingredients as &$ingredient_dans_tableau){
				if($ingredient["name"] == $ingredient_dans_tableau["name"]){
					if($ingredient["cuisson"] != "rien"){
						$ingredients[$match]["name"] = $ingredient["name"]." ".$ingredient["cuisson"];
					}
					break;
				}
				$match++;
			}
		}
		if($melange > 0){
			if($ingredient["melange"] != "rien"){
				$transformation = $ingredient["melange"];
			}
		}
    }

	//Tous les éléments en contact avec un élément répondant au paramétre "melange" sont affectés
	$boucleMelange = 0;
	if($melange > 0){
		foreach ($listeIngredient as &$ingredient){
			global $ingredients;
			foreach ($ingredients as &$ingredient_dans_tableau){
				if($ingredient["name"]==$ingredient_dans_tableau["name"] && $transformation!= "rien"){
					$ingredients[$boucleMelange]["name"] = $transformation["mot"];
					$ingredients[$boucleMelange]["genre"] = $transformation["genre"];
					$ingredients[$boucleMelange]["pluriel"] = $transformation["pluriel"];
					break;
				}
				$boucleMelange++;
			}
		}
	}
	
	/* Localisation de l'action */
	if($ou =="aucun"){$ou = "";}
	$retour .= $ou;
	
    if($finalitees != "aucune"){
        $retour .= " jusqu'à ce que ce soit $finalitees.";
    }else{
        $retour .= ".";
    }
    
	return $retour;

}

$liste_ingredients_brownie = array(
    ingredient($ingredients[0]["name"],$etalons[0],200),
    ingredient($ingredients[8]["name"],$etalons[0],50),
    ingredient($ingredients[1]["name"],$etalons[0],130),
    ingredient($ingredients[2]["name"],$etalons[0],150),
    ingredient($ingredients[3]["name"],$etalons[1],1),
    ingredient($ingredients[4]["name"],$etalons[2],3),
    ingredient($ingredients[5]["name"],$etalons[0],50),
    ingredient($ingredients[6]["name"],$etalons[3],2),
    ingredient($ingredients[7]["name"],$etalons[4],1) 
);

$actions_ingredients_brownie = array(
    0 => actions(
        array($ingredients[0],$ingredients[2]),
        array($actions["cuisson"]),
        $ou[0],
        $finalitees[1]
    ),
    1 => actions(
        array($ingredients[4]),
        array($actions["casse"],$actions["battre"]),
        $ou[2],
        $finalitees[0]
    ),
    2 => actions(
        array($ingredients[1],$ingredients[3],$ingredients[7],$ingredients[8]),
        array($actions["ajout"]),
        $ou[0],
        $finalitees[2]
    ),
    3 => actions(
        array($ingredients[0], $ingredients[4]),
        array($actions["melange"]),
        $ou[2],
        $finalitees[0]
    ),
    4 => actions(
        array($ingredients[8], $ingredients[5], $ingredients[6] ),
        array($actions["ajout"]),
        $ou[0],
        $finalitees[0]
    ),
    5 => actions(
        array(),
        array($actions["melange"]),
        $ou[0],
        $finalitees[0]
    ),
    6 => actions(
        array($ingredients[4]),
        array($actions["verse"]),
        $ou[4],
        $finalitees[0]
    ),
    7 => actions(
        array($ingredients[4]),
        array($actions["cuisson"]),
        $ou[5],
        $finalitees[4]
    )
);

echo liste_ingredients($liste_ingredients_brownie);
echo "======================================<br />";
echo faire_la_recette($actions_ingredients_brownie);



/*tableau ingredients pattern = nom, quantité, poid;

150 g de beurre
1 sachet de sucre vanillé
50 g de farine
3 oeufs
50 g de noix de pécan
2 cuill. à soupe d'amandes effilées
1 pincée de sel*/
