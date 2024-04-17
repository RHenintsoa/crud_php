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

/// Fonction pour afficher la liste des actions possibles
function showHelp() {
    echo "Actions disponibles :\n";
    echo "- LIST : Afficher la liste des utilisateurs\n";
    echo "- ADD : Ajouter un nouvel utilisateur\n";
    echo "- SEARCH : Effectuer une recherche \n";
    echo "- DELETE : Sipprimer un utulisateur";
    echo "- Si vous souhaitez modifier, veuillez taper la commande LIST puis MODIFY \n";
    echo "- exit : Quitter\n";
}
/// Fonction réutilisable pour affichage des informations d'un utilisateur
function affichage($user, $display_Eol=false){
    echo "Nom : ".$user->name.PHP_EOL;
    echo "Prénom : ".$user->firstname.PHP_EOL;
    echo "Profession : ".$user->profession.PHP_EOL;
    if($display_Eol){
        echo PHP_EOL;
    }
}
///Fonction réutilisable pour ajouter des informations
function dataUser(){
    $name = readline('NAME : ');
    $firstname = readline('FIRSTNAME : ');
    $profession = readline('PROFESSION : ');
    $birthdate = readline('DATE OF BIRTH (YYYY-MM-DD) : ');
    if (strtotime($birthdate) === false) {
        echo "Date de naissance invalide. Veuillez entrer une date au format YYYY-MM-DD.";
        return;
    }
    $formatted_birthdate = date('Y-m-d', strtotime($birthdate));
   $newUser =[
        'name'=> $name,
        'firstname' => $firstname,
        'profession' =>$profession,
        'birthdate' =>$birthdate
    ];
    return $newUser;
}

// Fonction pour ajouter un nouvel utilisateur
function addUser() {
    echo "ajout d'un nouvel membre en cours...\n";
    $newUser = dataUser();
    if ($newUser === null) {
        echo "Arrêter l'ajout si les données de l'utilisateur sont invalides".PHP_EOL;
        return;
    }
    $GLOBALS['lists'][] = $newUser;
    $newList = json_encode($GLOBALS['lists'],JSON_PRETTY_PRINT);
    file_put_contents('list.json',$newList);
    echo "Utilisateur ajouté avec succès".PHP_EOL;
}
// Fonction pour afficher la liste des utilisateurs
function listUsers() {
    foreach ($GLOBALS['lists'] as $list){ /* Récupérer les listes dans le json file pour pouvoir les afficher*/
        affichage($list,true);
    }
    echo "Souhaiteriez-vous modifier une ligne, si OUI veuillez taper la commande MODIFY, sinon taper juste NON".PHP_EOL;
    $modify = readline ('');
    if($modify == 'MODIFY'){
        modifyUser();
    }else {
        return;
    }
}
function modifyUser(){
    $nameToModify = readline('Veuillez entrer le nom correspondant à la ligne que vous sohaitez modifier : ');   
    $foundToModify = false;
    foreach ($GLOBALS['lists'] as $list){
        if ($nameToModify !="" && $nameToModify===$list->name) {
        $foundToModify = true;
        affichage($list);
        echo'Veuillez entrer les nouvelles informations'.PHP_EOL;    
        $updateData =[
            'name'=> readline('Nom : '),
            'firstname' =>readline('Prénom : '),
            'profession' =>readline('Profession : ')
            ];   
        $list->name = $updateData['name'];
        $list->firstname = $updateData['firstname'];
        $list->profession = $updateData['profession'];
        $updateData = json_encode($GLOBALS['lists'],JSON_PRETTY_PRINT);
        file_put_contents('list.json',$updateData);
        echo "Modification utilisateur ajouté avec succès".PHP_EOL;
        return;
        // var_dump($updateData);
        }
    }
    if($foundToModify){
        echo $nameToModify . " n'existe pas" . PHP_EOL;
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
            affichage($result);
        }
    } else {
        echo "Le nom " . $keyword . " que vous cherchez n'existe pas" . PHP_EOL;
    }
}

// Fonction delete
function deleteUser(){
    $nameToDelete = readline('veuillez entrer le nom que vous souhaiteriez supprimer de la liste : ');
    foreach ($GLOBALS['lists'] as $key=>$list){
        if ($nameToDelete!="" && $nameToDelete===$list->name) {
            affichage($list, true);
            
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
