<?php
switch ($action) {
    case 'accueil':
    {
        $message="C'est la page d'accueil";
        include("views/v_accueil.php");
        break;
    }
    case 'lister': {
        $les_membres=$pdo->getLesMembres();
        require 'views/v_listemembres.php';
        break;
    }
    case 'saisir': {
    
        require 'views/v_saisiemembre.php';
        break;
    }
    case 'ctrlsaisir':{
       // On traite le formulaire
       if(!empty($_REQUEST)){
        //POST n'est pas vide
        if(isset($_REQUEST['nom'], $_REQUEST['prenom'])
            && !empty($_REQUEST['nom']) && !empty ($_REQUEST['prenom']))
            {
                //le formulaire est complet
                //On recupère les données en les protégeant 
                $nom = strip_tags($_POST['nom']);
                $prenom = strip_tags($_POST['prenom']);
            } 

        $_nom =$_REQUEST['nom'];
        $_prenom =$_REQUEST['prenom'];
        $rep=$pdo->setLesMembres($_nom,$_prenom);
        if ($rep!= true)
        {$message=$rep;}
        else{
            $message="$nom et $prenom ont ete ajoute ";
        }

        include("views/v_accueil.php");
        break;
        }
    }  

    case 'supprimer':{
            // Pour la liste déroulante 
            $lesMembres = $pdo->getInfoMembres();
            require 'views\v_deletemembre.php';
            break;
    }

    case 'controledelete':{
                if (isset($_POST['id']))
                {
                //le formulaire est complet
                //On recupère les données en les protégeant 
                $id = strip_tags($_POST['id']);
                }
            @$_id = $_POST['id'];
            $rep=$pdo->deleteMembre($_id);
            if ($rep!= true)
        {$message=$rep;}
        else{
            $message="Membre supprimé ";
        }

        require("views/v_accueil.php");
        break;
        }

            
    
}
