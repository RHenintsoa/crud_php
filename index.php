<?php
// récupération des données json
$lists= json_decode(file_get_contents('list.json'));


// Boucle infinie pour attendre les commandes de l'utilisateur
while (true) {
    // Demander à l'utilisateur d'entrer une commande
    $command = readline("Entrez une commande : ");

    // Vérifier la commande entrée par l'utilisateur et exécuter l'action correspondante
    switch ($command) {
        case 'help':
            showHelp();
            break;
        case 'LIST':
            listUsers();
            break;
        case 'ADD':
            addUser();
            break;
        case 'SEARCH':
            search();
            break;
        // case 'MODIFY':
        //     modifyUser();
        //     break;
        case 'DELETE':
            deleteUser();
            break;    
        case 'exit':
            echo "A la prochaine fois !\n";
            exit; // Quitter l'application
        default:
            echo "Commande invalide. Utilisez 'help' pour afficher la liste des actions disponibles.\n";
            break;
    }
}

// Fonction pour afficher la liste des actions possibles
function showHelp() {
    echo "Actions disponibles :\n";
    echo "- LIST : Afficher la liste des utilisateurs\n";
    echo "- ADD : Ajouter un nouvel utilisateur\n";
    echo "- SEARCH : Effectuer une recherche \n";
    echo "- DELETE : Sipprimer un utulisateur";
    echo "- Si vous souhaitez modifier, veuillez taper la commande LIST puis MODIFY \n";
    echo "- exit : Quitter\n";
}


// Fonction pour ajouter un nouvel utilisateur
function addUser() {
    echo "ajout d'un nouvel membre en cours...\n";
    $newUser =[
            'name'=> readline('NAME : '),
            'firstname' =>readline('FIRSTNAME : '),
            'profession' =>readline('PROFESSION : ')
    ];
    $GLOBALS['lists'][] = $newUser;
    $newList = json_encode($GLOBALS['lists'],JSON_PRETTY_PRINT);
    file_put_contents('list.json',$newList);
    echo "Utilisateur ajouté avec succès".PHP_EOL;
}
// Fonction pour afficher la liste des utilisateurs
function listUsers() {
    foreach ($GLOBALS['lists'] as $list){ /* Récupérer les listes dans le json file pour pouvoir les afficher*/
        echo "Nom : ".$list->name.PHP_EOL;
        echo "Prénom : ".$list->firstname.PHP_EOL;
        echo "Profession : ".$list->profession.PHP_EOL;
        echo PHP_EOL; 
    }
    echo "Si vous souhaitez modifier une ligne, veuillez taper la commande MODIFY".PHP_EOL;
    $modify = readline ('');
    if($modify == 'MODIFY'){
        modifyUser();
    }else {
        return;
    }
}
function modifyUser(){
    $nameToModify = readline('Veuillez entrer le nom correspondant à la ligne que vous sohaitez modifier : ');
    var_dump($nameToModify);
    return;
    foreach ($GLOBALS['lists'] as $list){
        if ($nameToModify !="" && $nameToModify==$list->name) {
        echo "Nom : ".$list->name.PHP_EOL;
        echo "Prénom : ".$list->firstname.PHP_EOL;
        echo "Profession : ".$list->profession.PHP_EOL;
        echo PHP_EOL;
        $updateData =[
            'name'=> readline('Nom : '),
            'firstname' =>readline('Prénom : '),
            'profession' =>readline('Profession : ')
            ];   
        $list->name = $updateData['name'];
        $list->firstname = $updateData['firstname'];
        $list->profession = $updateData['profession'];
        // $updateData = json_encode($GLOBALS['lists'],JSON_PRETTY_PRINT);
        // file_put_contents('list.json',$updateData);
        // echo "Modification utilisateur ajouté avec succès".PHP_EOL;
        // return;
        var_dump($updateData);
    } 
    else{
        echo $nameToModify." n'existe pas".PHP_EOL;
        return;
    }
}
}
// Fonction recherche
function search() {
    $keyword = readline("Entrez le nom de la personne : ");/* mot clé à rechercher*/
    $results = []; /*tableau pour stocker les résultats mais on réinitialise en vide*/
    foreach ($GLOBALS['lists'] as $list) {
        if ($keyword !== "" && $keyword === $list->name) {
            // Ajouter le résultat trouvé au tableau des résultats
            $results[] = $list;
        }
    }
    if (count($results) > 0) {
        // Afficher tous les résultats trouvés
        foreach ($results as $result) {
            echo "Nom : " . $result->name . PHP_EOL;
            echo "Prénom : " . $result->firstname . PHP_EOL;
            echo "Profession : " . $result->profession . PHP_EOL;
            echo PHP_EOL;
        }
    } else {
        echo "Le nom " . $keyword . " que vous cherchez n'existe pas" . PHP_EOL;
    }
}

// Fonction delete
function deleteUser(){
    $nameToDelete = readline('veuillez entrer le nom que vous souhaiteriez supprimer de la liste : ');
    foreach ($GLOBALS['lists'] as $key=>$list){
        if ($nameToDelete!="" && $nameToDelete==$list->name) {
            echo "Nom : ".$list->name.PHP_EOL;
            echo "Prénom : ".$list->firstname.PHP_EOL;
            echo "Profession : ".$list->profession.PHP_EOL;
            echo "Vous voulez supprimer définitivement ?";
            $reponse = readline('');
            if ($reponse == 'OUI'){
                array_splice($GLOBALS['lists'],$key,1);
                file_put_contents('list.json', json_encode($GLOBALS['lists'], JSON_PRETTY_PRINT));
                echo "suppression avec succès".PHP_EOL;
            }else {
                echo "suppression annulé".PHP_EOL;
                return;
            }
        } 
    }
}
?>
