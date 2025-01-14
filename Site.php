<?php
require_once 'modules/mod_connexion/ConnexionModule.php';


class Site
{

	private $moduleName;
	private $module;

	public function __construct()
	{
		$this->moduleName = isset($_GET['module']) ? $_GET['module'] : "home";
		
		if(!isset($_SESSION['loginUtilisateur'])){
			$this->moduleName = "connexion";
		}else if(isset($_SESSION['loginUtilisateur']) && $this->moduleName =="connexion"){
			$this->moduleName = "home";
		}

		$infoConnexion = isset($_GET['infoConnexion']) ? $_GET['infoConnexion'] : null;

		if ($infoConnexion) {
			// Si une action liée à la connexion est demandée
			$moduleConnexion = new ConnexionModule();
			$moduleConnexion->exec();

			// Optionnel : Afficher le contenu généré
			//echo $moduleConnexion->getAffichage();
		} else {
			switch ($this->moduleName) {
				case "connexion":
					if(!isset($_SESSION['loginUtilisateur'])){
						require_once 'modules/mod_connexion/ConnexionModule.php';
					}
					break;
				case "home":
					if(isset($_SESSION['loginUtilisateur'])){
						require_once 'modules/mod_home/HomeModule.php';
					}
					break;
				case "sae":
					if(isset($_SESSION['loginUtilisateur'])){
						require_once 'modules/mod_sae/SaeModule.php';
					}
					break;
				case "rendus":
					if(isset($_SESSION['loginUtilisateur'])){
						require_once 'modules/mod_rendus/RendusModule.php';
					}
					break;
				case "creerSae":
					if(isset($_SESSION['loginUtilisateur'])){
						require_once 'modules/mod_creerSae/CreerSaeModule.php';
					}
					break;

				default:
					if(isset($_SESSION['loginUtilisateur'])){
						die("Module inexistant");
					}
					break;
					
			}
		}
	}

	public function execModule(){
		if(!isset($_SESSION['loginUtilisateur'])){
			$this->moduleName = "connexion";
		}else if(isset($_SESSION['loginUtilisateur']) && $this->moduleName =="connexion"){
			$this->moduleName = "home";
		}

		$moduleClass = $this->moduleName . "Module";
		$this->module = new $moduleClass();
		$this->module->exec();

	}

	public function getModule()
	{
		return $this->module;
	}
}
