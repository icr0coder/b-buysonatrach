<?php 
include("inc/dbconnecte.php");
$reqdate=$c->prepare("SELECT DATE_INI FROM TB_VARS");
$reqdate->EXECUTE();
$date=$reqdate->fetch();
?>
<!DOCTYPE html>
<html>
<?php include("inc/head.php"); ?>
<body>
<!-- header navigation -->
	<?php include("inc/navbar.php"); ?>	
	<?php 
	session_start();
	if ($_SESSION['type']=='GDP') {
		echo "<li class='nav-item'><a class='nav-link' href='agents.php'><i class='fa fa-users'></i> Agents</a></li>
			  <li class='nav-item'><a class='nav-link' href='Banque.php'><i class='fa fa-university'></i> Banque</a></li>
			  <li class='nav-item'><a class='nav-link' href='paie.php'><i class='fa fa-money'></i> Paie</a></li>
			  <li class='nav-item active'><a class='nav-link' href='settings.php'><i class='fa fa-cog'></i> Parametre</a></li>";	
	}elseif ($_SESSION['type']=='ADM') {
		?>
		<li class="nav-item"><a class="nav-link" href="agents.php"><i class="fa fa-users"></i> Agents</a></li>
		<li class="nav-item"><a class="nav-link" href="Banque.php"><i class="fa fa-university"></i> Banques</a></li>
		<li class="nav-item"><a class="nav-link" href="fonction.php"><i class="fa fa-tasks"></i> Fonctions</a></li>
		<li class="nav-item"><a class="nav-link" href="absence.php"><i class="fa fa-calendar-check-o"></i> Absences</a></li>
		<li class='nav-item'><a class='nav-link' href='paie.php'><i class='fa fa-money'></i> Paie</a></li>
		<li class="nav-item active"><a class="nav-link" href="settings.php"><i class="fa fa-cog"></i> Parametres</a></li>
		<?php
	}else{
		?>
		<li class="nav-item"><a class="nav-link" href="agents.php"><i class="fa fa-users"></i> Agents</a></li>
		<li class="nav-item"><a class="nav-link" href="fonction.php"><i class="fa fa-tasks"></i> Fonctions</a></li>
		<li class="nav-item"><a class="nav-link" href="absence.php"><i class="fa fa-calendar-check-o"></i> Absences</a></li>
		<li class="nav-item active"><a class="nav-link" href="settings.php"><i class="fa fa-cog"></i> Parametres</a></li>
	<?php
	}

	?>
		
	<?php include("inc/endnavbar.php"); ?>	

<!-- end header navigation -->
<body>
<div class="container-fluid">

	<div class="row">
			<!-- Side navigation -->
	<div class="col-md-3 sidemenu">
		<center><br>
			<ul>
				<?php if ($_SESSION['type']=='ADM') { ?>
				<li><a href="settings.php?option=user">Paramétre des utilisateurs</a></li>
				<li><a href="settings.php?option=var">Paramétre des variables</a></li>
				<li><a href="settings.php?option=date">Paramétre de la date</a></li>
				<li><a href="settings.php?option=update">Mise a jour</a></li>
				<?php }elseif ($_SESSION['type']=='GDP') { ?>
				<li><a href="settings.php?option=var">Paramétre des variables</a></li>
				<?php }else{ ?>
				<li><a href="settings.php?option=date">Paramétre de la date</a></li>
				<?php } ?>
			</ul>
		</center>
	</div>
	<!-- END Side navigation -->
	</div>
	<div class="row justify-content-end">
		<div class="col-md-9">
			<div class="row">
									<?php 
			if (isset($_GET['option'])) {
				if ($_GET['option']=='var') {
					?>
		<div class="col-md-4">
			<div class="card">
				<div class="card-header"><h5>Modification des taux de semestres</h5></div>
				<div class="card-body">
					<form action="" method="post">
                      <?php
                      $reqinfo=$c->prepare("SELECT * FROM TB_VARS");
                      $reqinfo->EXECUTE();
                      $row=$reqinfo->fetch();
                      if (isset($_POST['majtaux'])) {
                    for ($i=2; $i < 7; $i++) {
                      $ts="s".$i;
                      if (!empty($_POST[$ts])) {
                          $strreq="UPDATE TB_VARS SET T_S".$i." = '".$_POST[$ts]."'";
                          $reqts= $c->prepare($strreq);
                          $reqts->EXECUTE();
                          $successts="ok";
                          echo "<meta http-equiv='refresh' content='0'>";
                      }
                    }
                  }
                      if (isset($erreurts)) {
                        echo "<div class='alert alert-warning alert-dimissable'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                          <strong>Erreur :</strong>".$erreurts."
                        </div>";
                      }
                      if (isset($successts)) {
                        echo "<div class='alert alert-success alert-dimissable'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                          <strong>Succes :</strong>Mise a jour effectuer.
                        </div>";
                      }
                      ?>
                      <div class="form-group">
                        <label for="s1">Taux du 1er Semestre:</label>
                        <input class="form-control" type="text" placeholder="0%">
                      </div>
                      <div class="form-group">
                        <label for="s2">Taux du 2é Semestre:</label>
                        <input class="form-control" type="text" name="s2" id="s2" placeholder=<?php echo $row['T_S2']."%"; ?>>
                      </div>
                      <div class="form-group">
                        <label for="s3">Taux du 3é Semestre:</label>
                        <input class="form-control" type="text" name="s3" id="s3" placeholder=<?php echo $row['T_S3']."%"; ?>>
                      </div>
                      <div class="form-group">
                        <label for="s4">Taux du 4é Semestre:</label>
                        <input class="form-control" type="text" name="s4" id="s4" placeholder=<?php echo $row['T_S4']."%"; ?>>
                      </div>
                      <div class="form-group">
                        <label for="s5">Taux du 5é Semestre:</label>
                        <input class="form-control" type="text" name="s5" id="s5" placeholder=<?php echo $row['T_S5']."%"; ?>>
                      </div>
                      <div class="form-group">
                        <label for="s6">Taux du 6é Semestre:</label>
                        <input class="form-control" type="text" name="s6" id="s6" placeholder=<?php echo $row['T_S6']."%"; ?>>
                      </div>
                      <button class="btn btn-outline-info pull-right" type="submit" name="majtaux">Mise a jour</button>
                    </form>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<?php
			if (isset($_POST['majsmg'])) {
                    if (!empty($_POST['smg'])) {
                        $strreq="UPDATE TB_VARS SET SMG = '".$_POST['smg']."'";
                        $reqsmg= $c->prepare($strreq);
                        $reqsmg->EXECUTE();
                        $successsmg="ok";
                    }else {
                      $erreursmg="S'il vous plait remplicez tout les champs.";
                    }
                  }
                ?>
                <div class="card">
                  <div class="card-header">
                    <h5>SMG</h5>
                  </div>
                  <div class="card-body">
                    <form action="" method="post">
                      <?php
                      if (isset($erreursmg)) {
                        echo "<div class='alert alert-warning alert-dimissable'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                          <strong>Erreur :</strong>".$erreursmg."
                        </div>";
                      }
                      if (isset($successsmg)) {
                        echo "<div class='alert alert-success alert-dimissable'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                          <strong>Succes :</strong>Mise a jour effectuer.
                        </div>";
                      }
                      ?>
                      <div class="form-group">
                        <label for="smg">Valeur de SMG</label>
                        <input class="form-control" type="text" name="smg" id="smg" placeholder=<?php echo $row['SMG']."DA"; ?>>
                      </div>
                      <button class="btn btn-outline-info pull-right" type="submit" name="majsmg">Mise a jour</button>
                    </form>
                  </div>
                </div>
		</div>
					<?php
				}elseif ($_GET['option']=='user' AND $_SESSION['type']=='ADM') {
					?>
						<div class="col-md-5">
							<div class="card">
								<div class="card-header">
									<h5>Ajouter un utilisateur</h5>
								</div>
								<div class="card-body">
									<form method="post">
										<?php
										if (isset($_POST['cnx'])) {
											$user=$_POST['username'];
											$password=sha1($_POST['password']);
											$type=$_POST['type'];
											if (!empty($user) AND !empty($password) AND !empty($type)) {
												$adduser=$c->prepare("SELECT * FROM TUSER WHERE USERNAME=? AND TYPE=?");
												$adduser->EXECUTE(array($user,$type));
												$row=$adduser->fetch();
												if ($row==true) {
													$erreurlogin="utilisateur dejé exicte.";
												}else{
												$adduser=$c->prepare("INSERT INTO TUSER(USERNAME,PASSWORD,TYPE) VALUES(?,?,?)");
												$adduser->EXECUTE(array($user,$password,$type));
												echo "<div class='alert alert-success alert-dimissable'>
						                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						                    <strong>Success :</strong>utilisateur Ajouter.
						                  </div>";}
											}
										}
						                if (isset($erreurlogin)) {
						                  echo "<div class='alert alert-warning alert-dimissable'>
						                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						                    <strong>Erreur :</strong>".$erreurlogin."
						                  </div>";
						                }
						                ?>
						                <hr>
						                <div class="form-groupe">
						                  <label for="username">Pseudo: </label>
						                  <div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span><input class="form-control" id="username" type="text" name="username" placeholder="votre pseudo..."></div>
						                </div>
						                <div class="form-groupe">
						                  <label for="password">mot de pass: </label>
						                  <div class="input-group"><span class="input-group-addon"><i class="fa fa-lock"></i></span><input class="form-control" id="password" type="text" name="password" placeholder="votre mot de pass..."></div>
						                </div>
						                <hr>
						                <div class="form-groupe">
						                	<label for="type">Type de session : </label>
						                	<select class="custom-select" name="type" id="type">
							                	<option value="ADM">Administrateur</option>
							                	<option value="SCR">Secrétaire</option>
							                	<option value="GDP">Gestionnaire de paie</option>
							                </select>
						                </div>
						                <hr>
						                <button class="btn btn-outline-info pull-right" type="submit" name="cnx"><i class="fa fa-cycle"></i> Ajouter</button>
									</form>
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<h5>Supprimer un Utilisateur :</h5>
							<form action="" method="post">
				            <div class="table-responsive">
				              <table class="table">
				                <tr>
				                  <th>Type</th>
				                  <th>Utilisateur</th>
				                </tr>
				                  <?php
				                  $reqbanquemodal = $c->prepare("SELECT * FROM TUSER");
				                  $reqbanquemodal->EXECUTE();
				                    while ($row=$reqbanquemodal->fetch()) {
				                      echo "
				                      <tr>
				                        <td>".$row['TYPE']."</td>
				                        <td><label class='form-check-label'><input type='checkbox' value='".$row['ID']."' name='delete[]' class='form-check-input'>".$row['USERNAME']."</label></td>
				                      </tr>";
				                    }
				                  ?>
				              </table>
				            </div>
				            <button class="btn btn-primary pull-right" type="submit" name="confirmsupp">Confirmer</button>
							<?php 
								//***************** supprimer une banque **************
								if (isset($_POST['confirmsupp'])) {
								  if (!empty($_POST['delete'])) {
								    foreach($_POST['delete'] as $delid) {
								      $reqdel=$c->prepare("DELETE FROM TUSER WHERE ID=?");
								      $reqdel->EXECUTE(array($delid));
								    }
								      echo "<meta http-equiv='refresh' content='2'>";
								      echo "
											<div class='alert alert-success alert-dimissable'>
									            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
									            <strong>Succes :</strong>la suppression a ete effectuer. Patienter Svp...
									        </div>
										";
								  }
								}
							?>
				          </form>
						</div>
					<?php
				}elseif($_GET['option']=='date'){
					?>
						<div class="col-md-5">
						<div class="card">
							<div class="card-header">Modification de la date</div>
							<div class="card-body">
								<form method="post">
									<?php 
										if (isset($_POST['subdate'])) {
											if (!empty($_POST['date'])) {
												$reqini=$c->prepare("UPDATE TB_VARS SET DATE_INI = ?");
												$reqini->EXECUTE(array($_POST['date']));
												echo "<meta http-equiv='refresh' content='0'>";
											}
										}
									?>
									<input class="form-control" type="text" name="date" placeholder="jj/mm/aaaa"><br>
									<button class="btn btn-outline-info" type="submit" name="subdate">Modifier</button>
									<button class="btn btn-outline-secondary" data-dismiss="modal">Annuler</button>
								</form>
							</div>
						</div>
						</div>
						<div class="col-md-5">
						<form method="post">
							<?php 
								if (isset($_POST['valdate'])) {
								  $configstr = str_replace($date_config, $_POST['optionsRadios'], $configstr);
					              file_put_contents($config, $configstr);
					              echo "<meta http-equiv='refresh' content='0'>";
					              ?>
					              	<script type="text/javascript">
					              		alert("les mise a jour seron effectuer l'orsque le prochaine login");
					              	</script>
					              <?php
								}
							?>
						<fieldset class="form-group">
						    <legend>modification de la date :</legend>
						    <div class="form-check">
						      <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="auto" <?php if($date_config=='auto'){echo "checked";} ?>>
						        Auto (Date du Systéme)
						      </label>
						    </div>
						    <div class="form-check">
						    <label class="form-check-label">
						        <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="manuel"  <?php if($date_config=='manuel'){echo "checked";} ?>>
						        Manuel
						      </label>
						    </div>
						  </fieldset>
						  <button class="btn btn-outline-info" type="submit" name="valdate">Valider</button>
						</form>
						</div>
					<?php
				}elseif ($_GET['option']=='update') {
					?>
						<div class="col-md-12">
							<label class="pull-right"><strong>La version d'application: </strong><?php echo $version; ?></label>

						</div>
					<?php
				}
			}
		?>
			</div>
		</div>
	</div>
</div>
</body>
</html>