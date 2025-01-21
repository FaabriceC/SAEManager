
<?php

class DashboardModel extends Connexion{
    public function getRenduNonRenduPersonne($idPersonne)
    {

        $req = "SELECT distinct(Rendu.idRendu) as idRendu, Rendu.nom, Rendu.dateLimite, Rendu.idSAE, Rendu.idEvaluation, SAE.nomSae
        FROM Rendu
        INNER JOIN SAE ON Rendu.idSAE = SAE.idSAE
        INNER JOIN Groupe ON SAE.idSAE = Groupe.idSAE
        WHERE Groupe.idGroupe in (SELECT EtudiantGroupe.idGroupe
                                  FROM EtudiantGroupe
                                  WHERE EtudiantGroupe.idEtudiant = :idPersonne)
        AND Groupe.idgroupe NOT IN (SELECT idGroupe
                                      FROM RenduGroupe
                                      WHERE RenduGroupe.idRendu = idRendu)
        ORDER BY Rendu.dateLimite ASC
         ";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(':idPersonne', $idPersonne);


        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function getEvaluationSoutenanceEvaluateur($idEvaluateur){
        $req = "SELECT Soutenance.idSAE, Soutenance.titre, Soutenance.idEvaluation, SAE.nomSae, Soutenance.date
        FROM SAE
        INNER JOIN Soutenance ON Soutenance.idSAE = SAE.idSAE 
        INNER JOIN Evaluation ON Evaluation.idEval=Soutenance.idEvaluation
        WHERE IntervenantEvaluateur = :idEvaluateur
        OR Soutenance.idSAE IN (SELECT SAE.idSAE
                            FROM SAE
                            WHERE SAE.idResponsable = :idEvaluateur)
        OR Soutenance.idSAE IN (SELECT ResponsablesSAE.idSAE
                            FROM ResponsablesSAE
                            WHERE ResponsablesSAE.idResp = :idEvaluateur)
         ";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(':idEvaluateur', $idEvaluateur);


        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function getEvaluationRenduEvaluateur($idEvaluateur){
        $req = "SELECT Rendu.idSAE, Rendu.nom, Rendu.idEvaluation, SAE.nomSae, Rendu.dateLimite
        FROM SAE
        INNER JOIN Rendu ON SAE.idSAE = Rendu.idSAE
        INNER JOIN Evaluation ON Evaluation.idEval=Rendu.idEvaluation
        WHERE IntervenantEvaluateur = :idEvaluateur
        OR Rendu.idSAE IN (SELECT SAE.idSAE
                            FROM SAE
                            WHERE SAE.idResponsable = :idEvaluateur)
        OR Rendu.idSAE IN (SELECT ResponsablesSAE.idSAE
                            FROM ResponsablesSAE
                            WHERE ResponsablesSAE.idResp = :idEvaluateur)
         ";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(':idEvaluateur', $idEvaluateur);


        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function getNotification($idPersonne){
        $req = "SELECT *
        FROM Notifications
        WHERE idPersonne = :idPersonne
        ORDER BY date DESC;
         ";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(':idPersonne', $idPersonne);

        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function getSoutenanceNonPasserPersonne($idUtilisateur)
    {
        $dateTime = date('Y-m-d H:i:s');

        $req = "SELECT distinct(Soutenance.idSoutenance), Soutenance.titre, Soutenance.idSAE, PassageSoutenance.date, SAE.nomSae
        FROM Soutenance
        INNER JOIN PassageSoutenance ON PassageSoutenance.idSoutenance = Soutenance.idSoutenance
        INNER JOIN Groupe ON PassageSoutenance.idGroupe = Groupe.idgroupe
        INNER JOIN SAE ON Groupe.idSAE = SAE.idSAE
        WHERE Groupe.idGroupe in (SELECT EtudiantGroupe.idGroupe
                                  FROM EtudiantGroupe
                                  WHERE EtudiantGroupe.idEtudiant = :idPersonne)
        AND Groupe.idgroupe IN (SELECT idGroupe
                                      FROM PassageSoutenance
                                      WHERE PassageSoutenance.idSoutenance = Soutenance.idSoutenance)
        AND PassageSoutenance.date > :dateTime";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(':idPersonne', $idUtilisateur);
        $pdo_req->bindValue(':dateTime', $dateTime);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function getPersonne($idUtilisateur)
    {
        $req = "SELECT *
        FROM Personne
        WHERE idPersonne = :idPersonne";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(':idPersonne', $idUtilisateur);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function suprimmernotif($idNotification)
    {
        $req = "DELETE FROM Notifications WHERE idNotification = :idNotification";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(':idNotification', $idNotification);
        $pdo_req->execute();
    }

    public function getEvaluationSoutenanceJury($idUtilisateur)
    {
        $req = "SELECT Soutenance.idSAE, Soutenance.titre, Soutenance.idSoutenance, Soutenance.idEvaluation, SAE.nomSae, Soutenance.date
        FROM SAE
        INNER JOIN Soutenance ON Soutenance.idSAE = SAE.idSAE 
        INNER JOIN JurySoutenance ON JurySoutenance.idSoutenance = Soutenance.idSoutenance
        WHERE idPersonne = :idPersonne
         ";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(':idPersonne', $idUtilisateur);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function getPassageSoutenanceJury($idUtilisateur)
    {
        $req = "SELECT SAE.idSAE, Soutenance.titre, PassageSoutenance.date, SAE.nomSae, Groupe.nom
        FROM SAE
        INNER JOIN Soutenance ON Soutenance.idSAE = SAE.idSAE
        INNER JOIN JurySoutenance ON JurySoutenance.idSoutenance = Soutenance.idSoutenance
        INNER JOIN PassageSoutenance ON PassageSoutenance.idSoutenance = Soutenance.idSoutenance
        INNER JOIN Groupe ON Groupe.idgroupe = PassageSoutenance.idGroupe
        WHERE JurySoutenance.idPersonne = :idPersonne
        ORDER BY PassageSoutenance.date ASC
         ";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(':idPersonne', $idUtilisateur);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }
}
