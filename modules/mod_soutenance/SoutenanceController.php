<?php

require_once 'modules/mod_soutenance/SoutenanceView.php';
require_once 'modules/mod_soutenance/SoutenanceModel.php';

class SoutenanceController
{

    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new SoutenanceView();
        $this->model = new SoutenanceModel();
    }

    public function exec()
    {
        switch ($_GET['action']) {
            case "home":
                $this->initSoutenance();
                break;
            case "homeMaj":
                $this->validerModifHomePage();
                break;
            case "AjouterUneNote":
                $this->initAjouterUneNote();
                break;
            case "evaluer":
                $this->initEvaluerUneEval();
                break;
            case "maj":
                $this->initMettreAJourLesNotes();
                break;
            default :
                $this->initSoutenance();
                break;
        }
    }

    private function initSoutenance(){
        if ($_SESSION["estProfUtilisateur"] == 1) { //Est un prof
            $soutenances = $this->model->getSoutenanceProfByPersonne($_SESSION['idUtilisateur']);
            $notes = $this->model->getNotesdesSoutenanceProfByPersonne($_SESSION['idUtilisateur']);
        }else{
            $soutenances = null;
            $notes = null;
        }
        $this->view->initSoutenancePage($soutenances,$notes);
    }
    private function initAjouterUneNote(){
        if ($_SESSION["estProfUtilisateur"] == 1) { //Est un prof
            $idSoutenance = $_POST['idSoutenance'];
            $this->model->creerNotePourUneSoutenance($idSoutenance);
            header('Location: index.php?module=soutenance&action=home');
        }else{
            $this->initSoutenance();
        }
    }

    private function initEvaluerUneEval() {
        if ($_SESSION["estProfUtilisateur"] == 1) { // Est un prof
            $soutenances = $this->model->getSoutenanceProfByPersonne($_SESSION['idUtilisateur']);
            $notes = $this->model->getNotesdesSoutenanceProfByPersonne($_SESSION['idUtilisateur']);
            $flag = 0;
            $infoTitre = [];
            $idSAE = null;
            // Vérification si l'évaluation existe
            foreach ($notes as $note) {
                if ($_GET['eval'] == $note['idEval']) {
                    $infoTitre['SAE_nom'] = $note['SAE_nom'];
                    $infoTitre['Soutenance_nom'] = $note['Soutenance_nom'];
                    $infoTitre['Eval_nom'] = $note['Eval_nom'];
                    $infoTitre['idEval'] = $note['idEval'];
                    $idSAE = $note['idSAE']; // Récupération de l'idSAE
                    $flag = 1;
                    break;
                }
            }
    
            if ($flag === 0 || $idSAE === null) {
                $this->initSoutenance();
                return;
            }
    
            // Récupération des données nécessaires
            $notesDesElvesParGroupe = $this->model->getNotesParGroupeDuneEval($_GET['eval']);
            $tousLesElevesParGroupe = $this->model->getElevesParGroupe($idSAE);
            $tousLesElevesSansGroupe = $this->model->getElevesSansGroupe($idSAE);
    
            // Regroupement des étudiants par groupe
            $tousLesGroupes = [];
            foreach ($tousLesElevesParGroupe as $eleve) {
                $idGroupe = $eleve['idGroupe'] ?? 'Sans groupe';
                if (!isset($tousLesGroupes[$idGroupe])) {
                    $tousLesGroupes[$idGroupe] = [
                        'nom' => $eleve['Groupe_nom'] ?? 'Sans groupe',
                        'etudiants' => []
                    ];
                }
                $tousLesGroupes[$idGroupe]['etudiants'][] = $eleve;
            }
    
            // Appel à la vue
            $this->view->initEvaluerPage(
                $soutenances,
                $notes,
                $infoTitre,
                $notesDesElvesParGroupe,
                $tousLesGroupes,
                $tousLesElevesSansGroupe
            );
        } else { // Est un étudiant
            $this->initSoutenance();
        }
    }

    private function initMettreAJourLesNotes() {
        if ($_SESSION["estProfUtilisateur"] == 1) { // Est un professeur
            $notes = [];
            foreach ($_POST as $key => $value) {
                // Vérifie si la clé commence par 'note_idEleve_'
                if (strpos($key, 'note_idEleve_') === 0) {
                    // Extrait l'ID de l'élève à partir de la clé (la partie numérique après 'note_idEleve_')
                    $idEleve = substr($key, strlen('note_idEleve_'));
    
                    // Récupère la note associée à cet élève
                    $note = isset($_POST['note_idEleve_'.$idEleve]) ? $_POST['note_idEleve_'.$idEleve] : '';
    
                    // Ajoute les données dans le tableau $notes
                    $notes[] = [
                        'idEleve' => $idEleve,
                        'idEval' => $_POST['idEval'],
                        'note' => $note
                    ];
                }
            }
    
            $this->model->MettreAJourLesNotes($notes);
            header('Location: index.php?module=soutenance&action=home');
        } else { // Est un étudiant
            $this->initSoutenance();
        }
    }
    

    private function validerModifHomePage(){
        if ($_SESSION["estProfUtilisateur"] == 1) { // Est un professeur
            $idEval = $_POST['idEval'];
            $noteNom = $_POST['noteNom'];
            $coef = $_POST['coef'];
            $this->model->MettreAJourInfoUneEval($idEval, $noteNom, $coef);
            header('Location: index.php?module=soutenance&action=home');

            
        }else { // Est un étudiant
            $this->initSoutenance();
        }
    }
    
}
