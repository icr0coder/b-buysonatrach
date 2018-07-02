<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	    <link rel="stylesheet" href="css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/style.css">
	    <link rel="stylesheet" href="css/font-awesome.min.css">
	    <script src="js/jquery.min.js"></script>
	    <script src="js/popper.min.js"></script>
	    <script src="js/bootstrap.min.js"></script>
	    <title>SONATRACH - LOGIN</title>
</head>
<?php
    include_once("inc/dbconnecte.php");
    SESSION_START();
    if (!empty($_SESSION['username'])) {
       if ($_SESSION['type']=='ADM') {
          header("LOCATION:agents.php");
          }elseif ($_SESSION['type']=='SCR') {
            header("LOCATION:agents.php");
          }else{
          header("LOCATION:banque.php");  
          }
    }
    if (isset($_POST['cnx'])){
      $usernameUT = $_POST['username'];
      $passUT = sha1($_POST['password']);
      $type = $_POST['type'];
      if (!empty($usernameUT) AND !empty($passUT)) {
        $querylogin = $c->prepare("SELECT * FROM TUSER WHERE USERNAME=? AND PASSWORD=? AND TYPE=?");
        $querylogin->EXECUTE(array($usernameUT,$passUT,$type));
        $row=$querylogin->fetch();
        if ($row==true) {
          $_SESSION['username']=$row['USERNAME'];
          $_SESSION['type']=$row['TYPE'];
          if ($date_config=='auto') {
            date_default_timezone_set('UTC');
            $_SESSION['date'] = date("d/m/Y");
            $reqdate=$c->prepare("UPDATE TB_VARS SET DATE_INI=?");
            $reqdate->EXECUTE(array($_SESSION['date']));
          }else{
            $reqdate=$c->prepare("SELECT DATE_INI FROM TB_VARS");
            $reqdate->EXECUTE();
            $date=$reqdate->fetch();
            $_SESSION['date'] = $date['DATE_INI'];
          }
         if ($_SESSION['type']=='ADM') {
          header("LOCATION:agents.php");
          }elseif ($_SESSION['type']=='SCR') {
            header("LOCATION:agents.php");
          }else{
          header("LOCATION:banque.php");  
          }
        } else {
          $erreurlogin="Pseudo ou mot de passe inconnu.";
        }
      }else {
        $erreurlogin="Veuillez remplir tous les champs s'il vous plait !";
      }
    };
    ?>
<body style="background: #868e96;">
<!-- header navigation -->
	<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
		<div class="container-fluid">
			<div class="col-md-3">
				<a class="navbar-brand" href="">
				<img class="bg-warning logo" src="img/logo.png"> SONATRACH
			</a>
			</div>
		</div>
	</nav>
<!-- end header navigation -->
<div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			
		</div>
		<div class="col-md-4"><br>
			<div class="card">
				<div class="card-header bg-dark" style="color: white;">Connexion</div>
				<div class="card-body">
					<form method="post">
						<?php
                if (isset($erreurlogin)) {
                  echo "<div class='alert alert-warning alert-dimissable'>
                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Erreur :</strong>".$erreurlogin."
                  </div>";
                }
                ?>
                <hr>
                <div class="form-groupe">
                  <label for="username">Pseudo : </label>
                  <div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span><input class="form-control" id="username" type="text" name="username" placeholder="Pseudo..."></div>
                </div>
                <div class="form-groupe">
                  <label for="password">mot de passe : </label>
                  <div class="input-group"><span class="input-group-addon"><i class="fa fa-lock"></i></span><input class="form-control" id="password" type="password" name="password" placeholder="Mot de passe..."></div>
                </div>
                <hr>
                <div class="form-groupe">
                	<label for="type">Type : </label>
                	<select class="custom-select" name="type" id="type">
	                	<option value="ADM">Administrateur</option>
	                	<option value="SCR">Secrétaire</option>
	                	<option value="GDP">Gestionnaire de paiement</option>
	                </select>
                </div>
                <hr>
                <button class="btn btn-outline-dark pull-right" type="submit" name="cnx" style="width:100px;"><i class="fa fa-power-off"></i></button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			
		</div>
	</div>
</div>

</body>
</html>