<?php
$config = "config.txt";
if (!file_exists($config)) {
	$config = fopen("config.txt", "w");
	file_put_contents($config, "user:/U".PHP_EOL."pass:/PS".PHP_EOL."base:/BD".PHP_EOL."date:/DT");
}
$configstr = file_get_contents($config);
$utilisateur = substr($configstr, strpos($configstr, "user:")+5, strpos($configstr, "/U")-strpos($configstr, "user:")-5);
$motdepasse = substr($configstr, strpos($configstr, "pass:")+5, strpos($configstr, "/PS")-strpos($configstr, "pass:")-5);
$lien_base = substr($configstr, strpos($configstr, "base:")+5, strpos($configstr, "/BD")-strpos($configstr, "base:")-5);
$date_config = substr($configstr, strpos($configstr, "date:")+5, strpos($configstr, "/DT")-strpos($configstr, "date:")-5);
$version = substr($configstr, strpos($configstr, "VERSION:")+8, strpos($configstr, ";")-strpos($configstr, "VERSION:")-8);
if (empty($utilisateur) or empty($motdepasse) or empty($lien_base) or empty($date_config)) {
	file_put_contents($config, "user:/U".PHP_EOL."pass:/PS".PHP_EOL."base:/BD".PHP_EOL."date:/DT");
}
try
{
	// connexion à la base Oracle et création de l'objet
	$c = new PDO("oci:dbname=".$lien_base, $utilisateur,$motdepasse);
}
catch (PDOException $erreur)
{
	//echo $erreur->getMessage();
	if (isset($_POST['majdbinfo'])) {
	  $dbname = $_POST['dbname'];
	  $dbuser = $_POST['dbuser'];
	  $dbpass = $_POST['dbmdp'];
	  if (!empty($dbname) AND !empty($dbuser) AND !empty($dbpass)) {
	    $configstr = str_replace($utilisateur, $dbuser."/U", $configstr);
	    $configstr = str_replace($motdepasse, $dbpass."/PS", $configstr);
	    $configstr = str_replace($lien_base, $dbname."/BD", $configstr);
	    file_put_contents($config, $configstr);
	    $success = "Mise a jour effectuer avec succes.";
	  }
	  else {
	    $erreurdbconfig = "veuillez remplir les champs S.V.P !";
	  }
	}

	if (isset($erreur)) {
	  echo "<div class='modal fad modal-warning' role='dialog' data-toggle='modal' id='firstrun'>
						<div class='modal-dialog'>
							<div class='modal-content card-danger'>
								<div class='modal-header bg-warning'>Erreur base de données</div>
								<div class='modal-body'>".$erreur->getMessage()."<br>Essai de configurer la base de données<br><br><br>
	                <br>
	                <form class='form-horizontal' method='post' name='dbinfo'>";
	  if (isset($erreurdbconfig)) {
	    echo "<div class='alert alert-warning alert-dimissable'>
	    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	      <strong>Erreur :</strong>".$erreurdbconfig."
	    </div>";
	  }
	  if (isset($success)) {
	    echo "<div class='alert alert-success alert-dimissable'>
	    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
	      <strong>Succes :</strong>Mise a jour effectuer.
	    </div>
	    <meta http-equiv='refresh' content='1'>";
	  }
	                  echo "<div class='form-groupe'>
	                    <label class='control-label' for='dbname'>Nom de la base de données: </label>
	                    <input class='form-control' type='text' name='dbname' id='dbname' value=''>
	                  </div>
	                  <div class='form-groupe'>
	                    <label class='control-label' for='user'>Nom de l'utilisateur: </label>
	                    <input class='form-control' type='text' name='dbuser' id='user' value=''>
	                  </div>
	                  <div class='form-groupe'>
	                    <label class='control-label' for='mdp'>Mot de passe: </label>
	                    <input class='form-control' type='password' name='dbmdp' id='mdp' data-toggle='password' value=''>
	                  </div>
	                  <br><button class='btn btn-outline-success pull-right' type='submit' name='majdbinfo'>Mise a jour</button>
	                </form>
								</div>
								<div class='modal-footer'></div>
							</div>
						</div>
					</div>
					<script>
						$('#firstrun').modal('show')
					</script>";
	};
}
?>
