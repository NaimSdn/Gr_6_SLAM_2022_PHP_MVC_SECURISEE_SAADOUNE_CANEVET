<?php
//acces au Modele parent pour l heritage
namespace App\Models;
use CodeIgniter\Model;



//=========================================================================================
//définition d'une classe Modele (meme nom que votre fichier Modele.php) 
//héritée de Modele et permettant d'utiliser les raccoucis et fonctions de CodeIgniter
//  Attention vos Fichiers et Classes Controleur et Modele doit commencer par une Majuscule 
//  et suivre par des minuscules
//=========================================================================================
class Modele extends Model {

//==========================
// Code du modele
//==========================


//Permet de faire la connexion au site grace à la BDD
public function Connexion($login, $mdp) {
	$db = db_connect();	
	$sql = 'SELECT id, login, mdp, nom , prenom FROM visiteur where login = ? AND mdp= ?';
	$resultat = $db->query($sql, [$login, $mdp]);
	$resultat = $resultat->getResult();
	return $resultat;
}

//Permet de vérifier si l'utilisateur est bien connecter
public function Verification($id, $mois){
	$db = db_connect();
	$sql = 'SELECT COUNT(*) AS nb FROM fichefrais where idVisiteur = ? AND mois = ?';
	$resultat = $db->query($sql, [$id, $mois]);
	$resultat = $resultat->getResult();
	return $resultat;
}

//Permet de créer une fiche de frais à un utilisateur unique
public function CreerFicheFrais($visiteur, $mois){
	$db = db_connect();

	print_r(date("Y-m-d"));

	$sql = 'INSERT INTO fichefrais VALUES (?, ?, 0, 0, ?, "CR")';
	$resultat = $db->query($sql, [$visiteur, $mois, date("Y-m-d")]);

	$firstSQL = 'INSERT INTO ligneFraisForfait VALUES (?,?,"ETP",0)';
	$secondSQL = 'INSERT INTO ligneFraisForfait VALUES (?,?,"KM",0)';
	$thirdSQL = 'INSERT INTO ligneFraisForfait VALUES (?,?,"NUI",0)';
	$fourthSQL = 'INSERT INTO ligneFraisForfait VALUES (?,?,"REP",0)';

	$resultat = $db->query($firstSQL, [$visiteur, $mois]);
	$resultat = $db->query($secondSQL, [$visiteur, $mois]);
	$resultat = $db->query($thirdSQL, [$visiteur, $mois]);
	$resultat = $db->query($fourthSQL, [$visiteur, $mois]);

	$resultat = $resultat->getResult();
	return $resultat;
}

//Permet d'envoyer une fiche de frais
public function envoieFicheFrais($ETP, $KM, $NUI, $REP,$idVisiteur,$mois){

	$db = db_connect();

	$firstSQL = 'UPDATE ligneFraisForfait SET quantite = ? WHERE idFraisForfait = "ETP" AND idVisiteur = ? AND mois = ?';
	$secondSQL = 'UPDATE ligneFraisForfait SET quantite = ? WHERE idFraisForfait = "KM"AND idVisiteur = ? AND mois = ?';
	$thirdSQL = 'UPDATE ligneFraisForfait SET quantite = ? WHERE idFraisForfait = "NUI"AND idVisiteur = ? AND mois = ?';
	$fourthSQL = 'UPDATE ligneFraisForfait SET quantite = ? WHERE idFraisForfait = "REP"AND idVisiteur = ? AND mois = ?';

	$resultat = $db->query($firstSQL, [$ETP,$idVisiteur,$mois]);
	$resultat = $db->query($secondSQL, [$KM,$idVisiteur,$mois]);
	$resultat = $db->query($thirdSQL, [$NUI,$idVisiteur,$mois]);
	$resultat = $db->query($fourthSQL, [$REP,$idVisiteur,$mois]);

	$resultat = $resultat->getResult();
	return $resultat;

}


//Permet d'envoyer une fiche hors forfait
public function envoieHF($idVisiteur, $mois, $nom, $date, $montant){
	$db = db_connect();

	$firstSQL = 'INSERT INTO lignefraishorsforfait (idVisiteur,mois,libelle,date,montant) VALUES (?,?,?,?,?)';

	$resultat = $db->query($firstSQL, [$idVisiteur, $mois, $nom, $date, $montant]);
	$resultat = $resultat->getResult();
	return $resultat;
}

public function afficherFicheFrais($mois){
	$db = db_connect();

	$result   = $db->query('SELECT idVisiteur, idFraisForfait, quantite FROM lignefraisforfait where mois = ?', [$mois]);

	$results = $result->getResult();
	return $results;

}

public function afficherFicheHorsFrais($mois){
	$db = db_connect();

	$result   = $db->query('SELECT idVisiteur, libelle, date, montant FROM lignefraishorsforfait where mois = ?', [$mois]);

	$results = $result->getResult();
	return $results;
}







//==========================
// Fin Code du modele
//===========================


//fin de la classe
}


?>