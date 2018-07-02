<?php 
include("inc/function.php");
include("inc/dbconnecte.php");
$reqagent=$c->prepare("SELECT * FROM APPRENTI");
$reqagent->EXECUTE();
?>
<!DOCTYPE html>
<html>
<?php include("inc/head.php"); ?>
<body>
<!-- header navigation -->
	<?php include("inc/navbar.php"); ?>	
	<?php 
	session_start();
	if ($_SESSION['type']=='SCR') {
		echo "<li class='nav-item active'><a class='nav-link' href='agents.php'><i class='fa fa-users'></i> Agents</a></li>
			  <li class='nav-item'><a class='nav-link' href='absence.php'><i class='fa fa-calendar-check-o'></i> Absence</a></li>
			  <li class='nav-item'><a class='nav-link' href='fonction.php'><i class='fa fa-tasks'></i> Fonction</a></li>
			  <li class='nav-item'><a class='nav-link' href='settings.php'><i class='fa fa-cog'></i> Parametre</a></li>";	
	}elseif ($_SESSION['type']=='ADM') {
		?>
		<li class="nav-item active"><a class="nav-link" href="agents.php"><i class="fa fa-users"></i> Agents</a></li>
		<li class="nav-item"><a class="nav-link" href="Banque.php"><i class="fa fa-university"></i> Banques</a></li>
		<li class="nav-item"><a class="nav-link" href="fonction.php"><i class="fa fa-tasks"></i> Fonctions</a></li>
		<li class="nav-item"><a class="nav-link" href="absence.php"><i class="fa fa-calendar-check-o"></i> Absences</a></li>
		<li class="nav-item"><a class="nav-link" href="paie.php"><i class="fa fa-money"></i> Paie</a></li>
		<li class="nav-item"><a class="nav-link" href="settings.php"><i class="fa fa-cog"></i> Parametres</a></li>
		<?php
	}else{
		?>
		<li class="nav-item active"><a class="nav-link" href="agents.php"><i class="fa fa-users"></i> Agents</a></li>
		<li class="nav-item"><a class="nav-link" href="Banque.php"><i class="fa fa-university"></i> Banques</a></li>
		<li class="nav-item"><a class="nav-link" href="paie.php"><i class="fa fa-money"></i> Paie</a></li>
		<li class="nav-item"><a class="nav-link" href="settings.php"><i class="fa fa-cog"></i> Parametres</a></li>
		<?php
	}

	?>
		
	<?php include("inc/endnavbar.php"); ?>	

<!-- end header navigation -->
<div class="container-fluid">
	<div class="row">

	<!-- Side navigation -->
	<div class="col-md-3 sidemenu">
		<center><br>
			<?php 
				if ($_SESSION['type']!=='GDP') {
					# code...
				
			 ?>
			<a class="btn btn-outline-info pull-left col-md-5" href="agents.php?operation=add"><i class="fa fa-plus"></i> Ajouter</a>
			<a class="btn btn-outline-danger pull-right col-md-5" href="agents.php?operation=del"><i class="fa fa-trash"></i> Supprimer</a><br><br>
			<?php 
		}
			 ?>
			<form method="post"><br>
				<div class="input-group col-md-10">
			        <input class="form-control" type="text" name="srch" placeholder="Recherche...">
			        <button class="btn btn-outline-info" type="submit" name="search"><span><i class="fa fa-search"></i></span></button>
			    </div>
			</form>
			<?php 
				if (isset($_POST['search'])) {
					if (!empty($_POST['srch'])) {
					$reqagent=$c->prepare("SELECT * FROM APPRENTI WHERE UPPER(NOM)LIKE UPPER(?) OR UPPER(PRENOM)LIKE UPPER(?) OR UPPER(NOM||' '||PRENOM) LIKE UPPER(?) OR UPPER(PRENOM||' '||NOM) LIKE UPPER(?) OR UPPER(MATRICULE) LIKE UPPER(?) OR UPPER(N_SS) LIKE UPPER(?)");
					$reqagent->EXECUTE(array($_POST['srch'],$_POST['srch'],$_POST['srch'],$_POST['srch'],$_POST['srch'],$_POST['srch']));
					}
				}
			?>
		</center><br>
		<ul>
			<?php 
				while ($row=$reqagent->fetch()) {
					$img=getimg($row['MATRICULE']);
					echo "<li>
							<div>
								<a href='agents.php?id=".$row['MATRICULE']."'>
									<img class='rounded-circle' style='width:40px;height:40px;' src='".$img."'>
									<span>
										".$row['NOM']." ".$row['PRENOM']."
									</span>
								</a>
							</div>
						</li>";
				}
			?>
		</ul>
	</div>
	<!-- END Side navigation -->

	<!-- Modal add agents -->

	<!-- END Modal add agents-->
</div>

<!-- ************ row jdid *************** -->
<div class="row justify-content-end">
	<!-- ************ page des detail *************** -->
	<div class="col-md-9 fpage">
    	<?php 
    	if (isset($_GET['operation'])) {
    		if ($_GET['operation']== "add" AND $_SESSION['type']!=='GDP') {
    			?>
    		<a class="btn btn-outline-info" href="agents.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour</a>
    		<form method="post" enctype="multipart/form-data">
    			<?php 
					if (isset($_POST['add'])) {
						// settef les variable
						if ($_POST['hdcaisse']=="C") {
							$nss="01";
							$codb="01";
							$tp="C";
						}else{
							$nss=$_POST['nss'];
							$codb=$_POST['codb'];
							$tp="B";
						}
						date_default_timezone_set("UTC");
						$mat = $_POST['mat'];
						$extension_upload = strtolower(substr(strrchr($_FILES['img']['name'],'.'),1));
						$target_file="img/user/{$mat}.{$extension_upload}";
						$name = $_POST['name'];
						$pname = $_POST['pname'];
						$daten=$_POST['daten'];
						$dater=$_POST['dater'];
						$datef=$_POST['datef'];
						$gs=$_POST['gs'];
						$codef=$_POST['codef'];
						$dptcode=$_POST['dptcode'];
						if (!empty($mat) AND !empty($name) AND !empty($pname) AND !empty($daten) AND !empty($dater) AND !empty($datef) AND !empty($nss) AND !empty($gs) AND !empty($tp) AND !empty($codb) AND !empty($codef) AND !empty($dptcode)) {
						$daten=date_create($daten);
						$daten=date_format($daten,"d/m/Y");
						$dater=date_create($dater);
						$dater=date_format($dater,"d/m/Y");
						$datef=date_create($datef);
						$datef=date_format($datef,"d/m/Y");							
							$reqadd=$c->prepare("INSERT INTO APPRENTI VALUES (?,?,?,?,?,?,?,?,?,?,'O',?,?)");
							$reqadd->EXECUTE(array($mat,$name,$pname,$daten,$dater,$datef,$nss,$gs,$tp,$codb,$codef,$dptcode));
							if (!empty($_FILES["img"]["tmp_name"])) {
							$check = getimagesize($_FILES["img"]["tmp_name"]);
							}
						    if($check !== false) {
						        move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
						    }
							echo "<meta http-equiv='refresh' content='2'>";
							echo "
								<div class='alert alert-success alert-dimissable'>
						            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						            <strong>Succes :</strong>L'ajoute a ete effectuer. Patienter Svp...
						        </div>
							";
						}else{
							echo "
								<div class='alert alert-danger alert-dimissable'>
						            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						            <strong>Succes :</strong>Remplissez les champs vides...
						        </div>
							";
						}
					}
				?>
				<div class="pull-right col-md-4">
    				<input class="btn btn-default btn-file" type="file" name="img" id="img" accept="image/jpg">
    			</div>
    			<div class="col-md-3">
    				<h4 style="color: #2E9AFE;">Identification</h4><hr>
    				<div class="form-group">
						<label for="mat">Matricule</label>
						<input class="form-control" type="text" name="mat" id="mat">
					</div>
    			</div>
       			<hr>
    			<div class="row">
    				<div class="col-md-5">
	    				<h4 style="color: #2E9AFE;">Informations personnels</h4><hr>
	    				<div class="form-group">
							<label for="name">Nom</label>
							<input class="form-control" type="text" name="name" id="name">
						</div>
						<div class="form-group">
							<label for="pname">Prenom</label>
							<input class="form-control" type="text" name="pname" id="pname">
						</div>
						<div class="form-group">
							<label for="daten">Date de naissance</label>
							<input class="form-control" type="date" name="daten" id="daten" placeholder="jj/mm/yyyy">
						</div>
						<div class="form-group">
							<label for="gs">Groupe Sanguin</label>
							<select class="form-control" name="gs" id="gs">
								<option value="O+">O+</option>
								<option value="O-">O-</option>
								<option value="A+">A+</option>
								<option value="A-">A-</option>
								<option value="B+">B+</option>
								<option value="B-">B-</option>
								<option value="AB-">AB-</option>
								<option value="AB+">AB+</option>
							</select>
						</div>
	    			</div>
	    			<div class="col-md-5">
	    				<h4 style="color: #2E9AFE;">Details dans l'entreprise</h4><hr>
	    				<div class="form-group">
							<label for="dater">Date de recrutement</label>
							<input class="form-control" type="date" name="dater" id="dater" placeholder="jj/mm/yyyy">
						</div>
						<div class="form-group">
							<label for="datef">Date fin de contrat</label>
							<input class="form-control" type="date" name="datef" id="datef" placeholder="jj/mm/yyyy">
						</div>
						<div class="form-group">
							<label for="dr">Direction</label>
							<input type="hidden" name="dptcode" id="dptcode">
							<select class="form-control" name="dr" id="dr" onchange="dptfunc(this)">
							<option > ... </option>
							<?php 
								$reqdrc=$c->prepare("SELECT * FROM DIRECTION");
								$reqdrc->EXECUTE();
								while ($rowdrc=$reqdrc->fetch()) {
									?>
										<option value="<?php echo($rowdrc['ID_DRC']) ?>"><?php echo $rowdrc['NOM_DRC']; ?></option>
									<?php
								}
							?>							
							</select>
						</div>
						<div id="maskeddiv">
						<?php 
						$nbrdiv=0;
								$reqdrc=$c->prepare("SELECT * FROM DIRECTION");
								$reqdrc->EXECUTE();
								while ($rowdrc=$reqdrc->fetch()) {
								$nbrdiv=$nbrdiv+1;	
							?>
							
								<div class="form-group" style="display: none;" id="<?php echo($rowdrc['ID_DRC']) ?>">
									<label for="dpt">Departement</label>
									<select class="form-control" name="dpt" id="dpt" onchange="selection(this)">
									<?php 
										$reqdpt=$c->prepare("SELECT * FROM DEPARTEMENT WHERE ID_DRC=?");
										$reqdpt->EXECUTE(array($rowdrc['ID_DRC']));
										while ($rowdpt=$reqdpt->fetch()) {
											?>
											<option value="<?php echo($rowdpt['ID_DPT']) ?>"><?php echo $rowdpt['NOM_DPT']; ?></option>
											<?php
										}
									?>							
									</select>
								</div>
							<?php
						}
						?>
						</div>
						<script type="text/javascript">
							function dptfunc(div){
								for (var i = 0; i < <?php echo $nbrdiv;?>; i++) {
									drc= div.value;
									divsh=document.getElementById(drc);
									ddd=document.getElementById("maskeddiv");
									dd=ddd.getElementsByTagName("div")[i];
									if (dd.getAttribute("id") == drc) {
										dd.style.display="block";
										dptcode=dd.getElementsByTagName("select")[0].value;
										document.getElementById("dptcode").value=dptcode;
									}else{
										dd.style.display="none";
									}
									
								}
							}

							function selection(op){
								dptcode= op.value;
								document.getElementById("dptcode").value=dptcode;
							}
						</script>
						<div class="form-group">
							<label for="codef">Fonction</label>
							
							<select class="form-control" name="codef" id="codef">
								<?php 
									$reqbanq=$c->prepare("SELECT * FROM FONCTION");
									$reqbanq->EXECUTE();
									while ($r=$reqbanq->fetch()) {
										echo "<option value='".$r['ID']."'>".$r['NOM_FONCTION']."</option>";
									}
								?>
							</select>
						</div>
	    			</div>
	    			<div class="col-md-2"><button class="btn btn-primary pull-right" type="submit" name="add">Ajouter</button></div>
    			</div>
    			<div class="row">
    				<div class="col-md-5">
    					<h4 style="color: #2E9AFE;">Informations sur le mode de paiement</h4><hr>
						<div class="form-group">
							<label for="tp">Type Paie</label>
							<input type="hidden" name="hdcaisse" id="hdcaisse" value="C">
							<select onchange="tppaie(this)" class="form-control">
								<option value="c">Caisse</option>
								<option value="b">Banque</option>
							</select>
						</div>

						<script type="text/javascript">
							function tppaie(paie){
								if (paie.value=="b") {
									document.getElementById("banquetp").style.display="block";
									document.getElementById("hdcaisse").value="B";
								}else{
									document.getElementById("banquetp").style.display="none";
									document.getElementById("hdcaisse").value="C";
								}
							}
						</script>
    				</div>
    				<div class="col-md-5">
    					<br><br><br><br><div id="banquetp" style="display: none;">
							<div class="form-group">
							<label for="codb">Banque</label>
							
							<select class="form-control" name="codb" id="codb">
								<?php 
									$reqbanq=$c->prepare("SELECT * FROM BANQUE");
									$reqbanq->EXECUTE();
									while ($r=$reqbanq->fetch()) {
										echo "<option value='".$r['ID']."'>".$r['NOM_BANQUE']."</option>";
									}
								?>
							</select>
						</div>
    					<div class="form-group">
							<label for="nss">Numero de compte banquaire</label>
							<input class="form-control" type="text" name="nss" id="nss">
						</div>
						</div>
    				</div>
    			</div>
			</form>		
    			<?php
    		} elseif ($_GET['operation']== "del" AND $_SESSION['type']!=='GDP') {
    			?>
    				<br><a class="btn btn-outline-info" href="agents.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour</a>
    				<div><br>
				    	<form action="" method="post">
				    		<?php 
								//***************** supprimer un agent **************
								if (isset($_POST['confirmsupp'])) {
								  if (!empty($_POST['delete'])) {
								    foreach($_POST['delete'] as $delid) {
								    $reqdelapp1=$c->prepare("DELETE FROM ABSENTER WHERE MATRICULE=?");
								      $reqdelapp1->EXECUTE(array($delid));
								      $reqdelapp2=$c->prepare("DELETE FROM PAIE WHERE MATRICULE=?");
								      $reqdelapp2->EXECUTE(array($delid));
								      $reqdelapp=$c->prepare("DELETE FROM APPRENTI WHERE MATRICULE=?");
								      $reqdelapp->EXECUTE(array($delid));
								      if (getimg($delid)!=="img/user/user.png"){
								      	unlink(getimg($delid));
								      }
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
				            <div class="table-responsive">
				              <table class="table">
				                <tr>
				                  <th>Matricule</th>
				                  <th>Nom et prénom</th>
				                </tr>
				                  <?php
				                  $reqapp = $c->prepare("SELECT * FROM APPRENTI");
				                  $reqapp->EXECUTE();
				                    while ($row=$reqapp->fetch()) {
				                      echo "
				                      <tr>
				                        <td>".$row['MATRICULE']."</td>
				                        <td><label class='form-check-label'><input type='checkbox' value='".$row['MATRICULE']."' name='delete[]' class='form-check-input'>".$row['NOM'].", ".$row['PRENOM']."</label></td>
				                      </tr>";
				                    }
				                  ?>
				              </table>
				            </div>
				            <button class="btn btn-primary pull-right" type="submit" name="confirmsupp">Confirmer</button>
				        </form>
    				</div>
    			<?php
    		}
    	}
    		elseif (isset($_GET['id'])) {
    			if (!empty($_GET['id'])) {
    				$reqinfo=$c->prepare("SELECT * FROM APPRENTI WHERE MATRICULE=?");
    				$reqinfo->EXECUTE(array($_GET['id']));
    				$info=$reqinfo->fetch();
    				  $reqsem=$c->prepare("SELECT DATE_INI-DATE_REC FROM TB_VARS,APPRENTI WHERE MATRICULE=?");
	                  $reqsem->EXECUTE(array($info['MATRICULE']));
	                  $rowsem=$reqsem->fetch();
	                  $sem= (int)($rowsem['DATE_INI-DATE_REC']/180);
	                  $psem=$sem*100/6;
	                  switch ($sem) {
	                  	case 2:
	                  		calcul(2,$_GET['id'],$c);
	                  		break;
	                  	case 3:
                          calcul(3,$_GET['id'],$c);
                          break;
                        case 4:
                          calcul(4,$_GET['id'],$c);
                          break;
                        case 5:
                          calcul(5,$_GET['id'],$c);
                          break;
                        case 6:
                          calcul(6,$_GET['id'],$c);
                          break;
	                  	
	                  	default:
	                  		$paie=00;
	                  		$gainsbaser=00;
	                  		break;
	                  }
    				?>
    					<br><a class="btn btn-outline-info pull-left" href="agents.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour</a>
    					<div class="jumbotron">
    						<div class="pull-right">
    							<div class="pull-right">
						  		<?php if ($info['ACTIF']=='O') {
						  			?>
						  				<h5>Statut: <span class="rounded-circle" style="width: 10px;height: 10px;background-color:green;display: inline-block;"></span> Actif</h5> 
						  			<?php
						  		}else{
						  			?>
						  				<h5>Statut: <span class="rounded-circle" style="width: 10px;height: 10px;background-color:red;display: inline-block;"></span> Non Actif</h5>
						  			<?php
						  		} ?>
						  	</div>
    							<?php
    							$img=getimg($info['MATRICULE']);
    								echo"<img class='img-thumbnail' src='".$img."' style='width: 150px;height: 150px'>";
    							?>
						  	</div>
						  	<h3><?php echo $info['NOM'].", ".$info['PRENOM']; ?></h3> 
						  	<h5>Née le <?php echo $info['DATE_N']; ?>.</h5>
						</div>
						<hr>
						<ul class="nav nav-tabs">
						  <li class="nav-item">
						    <a class="bg-light nav-link active" data-toggle="tab" href="#profil">Profil</a>
						  </li>
						  <li class="nav-item">
						  	<?php if ($_SESSION['type']!=='GDP'){ ?>
						    <a class="bg-light nav-link" data-toggle="tab" href="#modif">Modification</a>
						  <?php } ?>
						  </li>
						</ul>

						
						<div class="tab-content">
						  <div class="tab-pane active container" id="profil">
						  	<div>
						  		<div class="pull-right"><label><strong>Matricule: </strong></label><span> <?php echo $info['MATRICULE'];?></span>
						  		</div>
						  		<?php 
						  			//modification affichage direction dep agetn req
						  		$reqdir=$c->prepare("SELECT * FROM DIRECTION INNER JOIN DEPARTEMENT ON DIRECTION.ID_DRC=DEPARTEMENT.ID_DRC WHERE ID_DPT=?");
						  		$reqdir->EXECUTE(array($info['ID_DPT']));
						  		$infodir=$reqdir->fetch();
						  		$reqfon=$c->prepare("SELECT * FROM FONCTION WHERE ID=?");
						  		$reqfon->EXECUTE(array($info['COD_F']));
						  		$infofon=$reqfon->fetch();
						  		?>
						  		<h5>Informations personnels</h5><hr>
						  		<?php 
						  		if ($info['N_SS']!=='01') {
						  			?>
						  				<label><strong>Numero de Compte banquaire: </strong></label><span> <?php echo $info['N_SS'];?></span><br>
						  			<?php
						  		}
						  		?>
						  		<label><strong>Nom: </strong></label><span> <?php echo $info['NOM'];?></span><br>
						  		<label><strong>Prénom: </strong></label><span> <?php echo $info['PRENOM'];?></span><br>
						  		<label><strong>Date de naissance: </strong></label><span> <?php echo $info['DATE_N'];?></span><br>
						  		<label><strong>Groupe sanguin: </strong></label><span> <?php echo $info['GROUPE_S'];?></span><br>
						  		<label><strong>Direction: </strong></label><span> <?php echo $infodir['NOM_DRC'];?></span><br>
						  		<label><strong>Département: </strong></label><span> <?php echo $infodir['NOM_DPT'];?></span><br>
						  		<label><strong>Fonction: </strong></label><span> <?php echo $infofon['NOM_FONCTION'];?></span><br>
						  	</div>
						  	
						  </div>
						  <div class="tab-pane container" id="modif">
						  	<div class="pull-right">
						  		<div class="form-group">
						  			<form method="post">
						  				<?php 
						  				if (isset($_POST['stactiv'])) {
						  					$reades=$c->prepare("UPDATE APPRENTI SET ACTIF='O' WHERE MATRICULE=?");
						  					$reades->EXECUTE(array($_GET['id']));
						  					echo "<meta http-equiv='refresh' content='0'>";
						  				}
						  				if (isset($_POST['stdesactiv'])) {
						  					$reades=$c->prepare("UPDATE APPRENTI SET ACTIF='N' WHERE MATRICULE=?");
						  					$reades->EXECUTE(array($_GET['id']));
						  					echo "<meta http-equiv='refresh' content='0'>";
						  				}
						  				?>
						  				<label>Modifier Statut :</label>
						  				<?php 
						  				if ($info['ACTIF']!=='O') {
						  					?>
						  					<button class="btn btn-success" name="stactiv">Activer</button>
						  					<?php
						  				}else{
						  					?>
						  					<button class="btn btn-danger" name="stdesactiv">Desactiver</button>
						  					<?php
						  				}
						  				?>
						  			</form>
						  		</div>
						  	</div>
						  	<div class="col-md-6">
						  		<!-- edition des info *********************************************** -->
						  		<form method="post">
						  			<h4 style="color: #2E9AFE;">Modification nom,prenom</h4><hr>
						  			<?php 
						  			if (isset($_POST['submod'])) {
						  				if (!empty($_POST['namemod'])) {
						  					$reqname=$c->prepare("UPDATE APPRENTI SET NOM=? WHERE MATRICULE=?");
						  					$reqname->EXECUTE(array($_POST['namemod'],$_GET['id']));
						  					echo "<meta http-equiv='refresh' content='0'>";
						  				}
						  				if (!empty($_POST['pnamemod'])) {
						  					$reqprename=$c->prepare("UPDATE APPRENTI SET PRENOM=? WHERE MATRICULE=?");
						  					$reqprename->EXECUTE(array($_POST['pnamemod'],$_GET['id']));
						  					echo "<meta http-equiv='refresh' content='0'>";
						  				}
						  			}
						  			?>
						  			<div class="form-group">
						  				<label>Nom</label>
						  				<input class="form-control" type="text" name="namemod">
						  			</div>
						  			<div class="form-group">
						  				<label>Prenom</label>
						  				<input class="form-control" type="text" name="pnamemod">
						  			</div>
						  			<button class="btn btn-success" type="submit" name="submod">Modifier</button>
						  		</form>
						  	</div>
						  	<?php 
						  		if ($img=="img/user/user.png") {
						  			$btndel="<button class='btn btn-outline-danger disabled'>Supp.</button>";
						  		}else{
						  			$btndel="<button class='btn btn-outline-danger' type='submit' name='delete'>Supp.</button>";
						  		}
						  	?>
						  	<br>
						  	<div class="col-md-6">
						  		<h4 style="color: #2E9AFE;">Modification Photo</h4><hr>
					  			<form method="post" enctype="multipart/form-data">
					  				<?php 
					  					if (isset($_POST['delete'])) {
					  						unlink($img);
					  						echo "<meta http-equiv='refresh' content='0'>";
					  					}
					  					if (isset($_POST['editimg'])) {
					  						$mat=$info['MATRICULE'];
					  						$extension_upload = strtolower(substr(strrchr($_FILES['img']['name'],'.'),1));
											$target_file="img/user/{$mat}.{$extension_upload}";
											$check = getimagesize($_FILES["img"]["tmp_name"]);
										    if($check !== false) {
										    	if ($img !== "img/user/user.png") {
										    		unlink($img);
										    		move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
										    	}else{
										    		move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
										    	}
										    }
					  						echo "<meta http-equiv='refresh' content='0'>";
					  					}
					  				?>
				  					<div class="form-group">
				  						<label>Modification du photo:</label>
				  						<?php echo $btndel; ?>
				  					</div>
				  					<div class="form-group">
				  						<label><input class="" type="file" name="img"></label>
				  						<button class="btn btn-outline-info" type="submit" name="editimg">valider</button>
				  					</div>
					  			</form>
						  	</div>
						  </div>
						</div>
    				<?php
    			}
    		}else{
    			?>
    				<div>
    					<!-- Statistique des agents ************************ -->
    					<?php
    					$s1=0;$s2=0;$s3=0;$s4=0;$s5=0;$s6=0;
    					$reqagent=$c->prepare("SELECT COUNT(*) AS NBRAG FROM APPRENTI");
    					$reqagent->EXECUTE();
    					$nbragent=$reqagent->fetch();
    					$reqagent1=$c->prepare("SELECT * FROM APPRENTI");
    					$reqagent1->EXECUTE();
    					while ($rowagent=$reqagent1->fetch()) {
    					 $reqsem=$c->prepare("SELECT DATE_INI-DATE_REC FROM TB_VARS,APPRENTI WHERE MATRICULE=?");
		                  $reqsem->EXECUTE(array($rowagent['MATRICULE']));
		                  $rowsem=$reqsem->fetch();
		                  $sem= (int)($rowsem['DATE_INI-DATE_REC']/180);
		                  switch ($sem) {
		                  	case 1:
		                  		$s1=$s1+1;
		                  		break;
		                  	case 2:
		                  		$s2=$s2+1;
		                  		break;
		                  	case 3:
		                  		$s3=$s3+1;
		                  		break;
		                  	case 4:
		                  		$s4=$s4+1;
		                  		break;
		                  	case 5:
		                  		$s5=$s5+1;
		                  		break;
		                  	case 6:
		                  		$s6=$s6+1;
		                  		break;
		                  	
		                  	default:
		                  		# code...
		                  		break;
		                  }
    					 }

    					?>
    					<div class="card col-md-9" style="float: none;">
    						<div class="card-header">Statistique des agents</div>
    						<div class="card-body">
    							<canvas id="myChart"></canvas>
    						</div>
    						<br>
    					</div>

<script>
var ctx = document.getElementById("myChart");
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Semestre 1', "Semestre 2", "Semestre 3", "Semestre 4", "Semestre 5", "Semestre 6"],
        datasets: [{
            label: '',
            data: [<?php echo $s1.",".$s2.",".$s3.",".$s4.",".$s5.",".$s6; ?>],
            backgroundColor: [
                <?php for($i=0;$i<7;$i++){echo "'".rand_color()."',";} ?>
            ],
            borderWidth: 0
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
    				</div>
    			<?php
    		}
    	?>
    </div>
    <!-- ************ Fin page detail *************** -->
</div>
<!-- ************ Fin row *************** -->

</div>

</body>
</html>