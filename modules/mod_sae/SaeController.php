<?php

require_once 'modules/mod_sae/SaeView.php';
require_once 'modules/mod_sae/SaeModel.php';

class SaeController
{

    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new SaeView();
        $this->model = new SaeModel();
    }

    public function exec()
    {
        switch ($_GET['action']) {
            case "home":
                $this->initSae();
                break;
            case "details":
                $this->initDetails();
                break;
            case "groupe":
                $this->initGroup();
                break;
            case "note":
                $this->initNote();
                break;
            case "uploadFichier":
                $this->uploadFichier();
                break;
        }
    }

    private function initSae()
    {

        $saes = $this->model->getSaesByPersonneId(1);
        $this->view->initSaePage($saes);
    }

    private function initDetails()
    {
        $mySAE = $this->model->getSaesByPersonneId(1);
        $acces = false;
        foreach ($mySAE as $sae) {
            if ($sae['idSAE'] == $_GET['id']) {
                $acces = true;
            }
        }

        if ($acces) {
            $saes = $this->model->getSaeById($_GET['id']);
            $ressource = $this->model->getRessourceBySAE($_GET['id']);
            $rendus = $this->model->getRenduBySae($_GET['id']);
            $soutenance = $this->model->getSoutenanceBySae($_GET['id']);

            $this->view->initSaeDetails($saes, $ressource, $rendus, $soutenance);
        } else {
            header('Location: index.php');
        }
    }

    private function initGroup()
    {
        $sae = $this->model->getSaeById($_GET['id']);
        $groupeID = $this->model->getMyGroupId($_GET['id']);
        $groupe = $this->model->getMyGroup($_GET['id'], $groupeID);
        $responsable = $this->model->getSaeResponsable($_GET['id']);

        $this->view->initGroupPage($sae, $groupe, $responsable);
    }

    private function initNote()
    {

        $groupeID = $this->model->getMyGroupId($_GET['id']);

        $notes = $this->model->getNote($_GET['id'], $groupeID);
        $sae = $this->model->getSaeById($_GET['id']);
        $noteSoutenance = $this->model->getNoteSoutenance($_GET['id'], $groupeID);

        $this->view->initNotePage($notes, $sae, $noteSoutenance);
    }

    private function uploadFichier() {
        $fileName = isset($_POST['fileName']) ? $_POST["fileName"] : (isset($_FILES['fileInput']['name']) ? basename($_FILES['fileInput']['name']) : null);
    
        if (!isset($_FILES['fileInput'])) {
            echo "Erreur lors du téléchargement du fichier.";
            exit;
        }

        $this->model->uploadFichier($fileName, $_FILES['fileInput']['tmp_name'], $_POST['colorChoice'], $_GET["id"]);
    }
}
