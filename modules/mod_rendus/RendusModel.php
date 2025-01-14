<?php

class RendusModel extends Connexion
{

    // GET

    function getRendusByPersonne($idPersonne)
    {
        $req = "SELECT Rendu.nom AS Rendu_nom, SAE.nomSae AS SAE_nom, Rendu.dateLimite, SAE.idSAE
                FROM Personne
                INNER JOIN EtudiantGroupe ON EtudiantGroupe.idEtudiant = Personne.idPersonne
                INNER JOIN RenduGroupe ON RenduGroupe.idGroupe = EtudiantGroupe.idGroupe
                INNER JOIN Rendu ON Rendu.idRendu = RenduGroupe.idRendu
                INNER JOIN SAE ON SAE.idSAE = Rendu.idSAE
                WHERE Personne.idPersonne = :idPersonne";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idPersonne", $idPersonne, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }
}
