<?php

require_once 'GenericView.php';

class HomeView extends GenericView
{
    public function __construct()
    {
        parent::__construct();
    }

    function initHomePage()
    {

        if ($_SESSION["estProfUtilisateur"] == 1) { //Est un prof
            echo <<<HTML
            <div class="container mt-5">
                <h1 class="fw-bold">ACCUEIL</h1>
                <div class="card shadow bg-white rounded w-100 h-75">
                    <div class="d-flex w-100 h-75 justify-content-center m-auto">
                        {$this->cardSAE()}
                        {$this->cardRendus()}
                        {$this->cardCreerSAE()}
                    </div>
                </div>
            </div>
        HTML;
        } else { //Est un élève
            echo <<<HTML
            <div class="container mt-5">
                <h1 class="fw-bold">ACCUEIL</h1>
                <div class="card shadow bg-white rounded w-100 h-75">
                    <div class="d-flex w-100 h-75 justify-content-center m-auto">
                        {$this->cardSAE()}
                        {$this->cardRendus()}
                    </div>
                    
                </div>

            </div>

        HTML;
        }
    }

    function cardSAE()
    {

        return <<<HTML
        <div class="card shadow px-3 mx-5  rounded w-25 h-75 card-sae-content">
            <a href="index.php?module=sae&action=home" class="text-decoration-none text-reset">
                <div class="card card-sae shadow w-100 h-100"></div>
                <div class="m-4">
                    <h4>SAÉs</h4>
                    <p>Retrouvez ici la liste de toutes les SAÉs auquel vous avez été associé.</p>
                </div>
            </a>
        </div>
        HTML;
    }

    function cardRendus()
    {
        return <<<HTML
        <div class="card shadow px-3 mx-5 rounded-bottom w-25 h-75 card-rendus-content">
            <a href="index.php?module=rendus&action=home" class="text-decoration-none text-reset">
                <div class="card card-rendus shadow w-100 h-100"></div>
                <div class="m-4">
                    <h4>Rendus</h4>
                    <p>Retrouvez ici la liste de tous les rendus (ceux à rendre et ceux déjà rendus)</p>
                </div>
            </a>
        </div>
        HTML;
    }
    function cardCreerSAE()
    {
        return <<<HTML
        <div class="card shadow px-3 mx-5 rounded-bottom w-25 h-75 card-rendus-content">
            <a href="index.php?module=creerSae&action=home" class="text-decoration-none text-reset">
                <div style="background: #B2B2FD;" class="card card-rendus shadow w-100 h-100"></div>
                <div class="m-4">
                    <h4>Créer une SAÉ</h4>
                    <p>Créer un nouveau sujet de SAE.</p>
                </div>
            </a>
        </div>
        HTML;
    }
}
