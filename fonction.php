<?php 
include("inc/dbconnecte.php");
?>
<!DOCTYPE html>
<html>
<?php include("inc/head.php");
include("inc/function.php"); ?>
<body>
<!-- header navigation -->
	<?php include("inc/navbar.php"); ?>	
	<?php 
	session_start();
	if ($_SESSION['type']=='SCR') {
		echo "<li class='nav-item'><a class='nav-link' href='agents.php'><i class='fa fa-users'></i> Agents</a></li>
			  <li class='nav-item'><a class='nav-link' href='absence.php'><i class='fa fa-calendar-check-o'></i> Absence</a></li>
			  <li class='nav-item active'><a class='nav-link' href='fonction.php'><i class='fa fa-tasks'></i> Fonction</a></li>
			  <li class='nav-item'><a class='nav-link' href='settings.php'><i class='fa fa-cog'></i> Parametre</a></li>";
	}elseif ($_SESSION['type']=='ADM') {
		?>
		<li class="nav-item"><a class="nav-link" href="agents.php"><i class="fa fa-users"></i> Agents</a></li>
		<li class="nav-item"><a class="nav-link" href="Banque.php"><i class="fa fa-university"></i> Banques</a></li>
		<li class="nav-item active"><a class="nav-link" href="fonction.php"><i class="fa fa-tasks"></i> Fonctions</a></li>
		<li class="nav-item"><a class="nav-link" href="absence.php"><i class="fa fa-calendar-check-o"></i> Absences</a></li>
		<li class="nav-item"><a class="nav-link" href="paie.php"><i class="fa fa-money"></i> Paie</a></li>
		<li class="nav-item"><a class="nav-link" href="settings.php"><i class="fa fa-cog"></i> Parametres</a></li>
		<?php
	}

	?>
		
	<?php include("inc/endnavbar.php"); ?>	
<?php 
if ($_SESSION['type']=='GDP') {
	?>
		<div class="container">
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<br><div class="card">
						<center><br><h3>vous n'avez pas l'autorisation d'accedez a cette page</h3></center>
					</div>
				</div>
			</div>
		</div>
	<?php
	exit;
}
?>
<div class="container-fluid">
	<?php 
		if ($_GET['op']=='fonction') {
			$classfn='btn-success';
		}else{
			$classfn='btn-outline-success';
		}
		if ($_GET['op']=='departement') {
			$classdpt='btn-success';
		}else{
			$classdpt='btn-outline-success';
		}
		if ($_GET['op']=='direction') {
			$classdrc='btn-success';
		}else{
			$classdrc='btn-outline-success';
		}
	?>
	<div class="row" style="background-color: #868e96;">
		<div class="col-md-4">
		<a class="btn <?php echo($classfn) ?>" href="fonction.php?op=fonction" style="width: 100%;height: 100%; color: white;">fonction</a>
		</div>
		<div class="col-md-4">
			<a class="btn <?php echo($classdpt) ?>" href="fonction.php?op=departement" style="width: 100%;height: 100%; color: white;">departement</a>
		</div>
		<div class="col-md-4">
			<a class="btn <?php echo($classdrc) ?>" href="fonction.php?op=direction" style="width: 100%;height: 100%; color: white;">Direction</a>
		</div>
	</div>
			<hr>
		<?php 
		if (isset($_GET['op'])) {
				if (!empty($_GET['op'])) {
					?>
						<div class="row">
							<?php
//**************************************************************
//**************************************************************
			//START page fonction 
								if ($_GET['op']=='fonction') {
									$reqbanque=$c->prepare("SELECT * FROM FONCTION");
									$reqbanque->EXECUTE();
									?>
										<!-- Side navigation -->
	<div class="col-md-3 sidemenu">
		<center><br>
			<a class="btn btn-outline-info pull-left col-md-5" href="fonction.php?op=fonction&operation=add"><i class="fa fa-plus"></i> Ajouter</a>
			<a class="btn btn-outline-danger pull-right col-md-5" href="fonction.php?op=fonction&operation=del"><i class="fa fa-trash"></i> Supprimer</a><br><br>
			<form method="post"><br>
				<div class="input-group col-md-10">
			        <input class="form-control" type="text" name="srch" placeholder="Recherche...">
			        <button class="btn btn-info" type="submit" name="search"><span><i class="fa fa-search"></i></span></button>
			    </div>
			</form>
			<?php 
				if (isset($_POST['search'])) {
					if (!empty($_POST['srch'])) {
					$reqbanque=$c->prepare("SELECT * FROM FONCTION WHERE UPPER(NOM_FONCTION)LIKE UPPER(?) OR ID LIKE ?");
					$reqbanque->EXECUTE(array($_POST['srch'],$_POST['srch']));
					}
				}
			?>
		</center><br>
		<ul>
			<?php 
				while ($row=$reqbanque->fetch()) {
					echo "<li>
							<div>
								<a href='fonction.php?op=fonction&mat=".$row['ID']."'>
									<i class='fa fa-tasks'></i>
									<span>
										".$row['NOM_FONCTION']."
									</span>
								</a>
							</div>
						</li>";
				}
			?>
		</ul>
	</div>
	<!-- END Side navigation -->

	<!-- Modal add banque -->
		
	<!-- END Modal add banque-->
 
</div>

<!-- ************ row jdid *************** -->
<div class="row justify-content-end">
	<!-- ************ page des detail *************** -->
	<div class="col-md-9 fpage">
    	<?php 
    	if (isset($_GET['operation'])AND $_GET['op']=="fonction") {
    		if ($_GET['operation']=="del" AND $_GET['op']=="fonction"){
    			?>
    				<br><a class="btn btn-outline-info" href="fonction.php?op=fonction"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour</a>
    				<div><br>
    					<form action="" method="post">
				            <div class="table-responsive">
				              <table class="table">
				                <tr>
				                  <th>Id</th>
				                  <th>Nom</th>
				                </tr>
				                  <?php
				                  $reqbanquemodal = $c->prepare("SELECT * FROM FONCTION");
				                  $reqbanquemodal->EXECUTE();
				                    while ($row=$reqbanquemodal->fetch()) {
				                      echo "
				                      <tr>
				                        <td>".$row['ID']."</td>
				                        <td><label class='form-check-label'><input type='checkbox' value='".$row['ID']."' name='delete[]' class='form-check-input'>".$row['NOM_FONCTION']."</label></td>
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
								      $reqdel=$c->prepare("DELETE FROM FONCTION WHERE ID=?");
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
    		} elseif ($_GET['operation']=="add" AND $_GET['op']=="fonction"){
    			?>
					<br><a class="btn btn-outline-info" href="fonction.php?op=fonction"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour</a>
					<div class="col-md-5"><br>
						<div class="card">
							<div class="card-header">
								<h5>Ajouter une Fonction</h5>
							</div>
							<div class="card-body">
								<form method="post">
									<div class="form-group">
										<label for="id">Id de la Fonction</label>
										<input class="form-control" type="text" name="id" id="id">
									</div>
									<div class="form-group">
										<label for="name">Nom de la Fonction</label>
										<input class="form-control" type="text" name="name" id="name">
									</div>
									<button class="btn btn-primary pull-right" type="submit" name="add">Ajouter</button>
									<?php 
										if (isset($_POST['add'])) {
											// settef les variable
											$id = $_POST['id'];
											$name = $_POST['name'];
											if (!empty($id)||!empty($name)) {
												$reqadd=$c->prepare("INSERT INTO FONCTION VALUES (?,?)");
												$reqadd->EXECUTE(array($id,$name));
												echo "<meta http-equiv='refresh' content='2'>";
												echo "
													<div class='alert alert-success alert-dimissable'>
											            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
											            <strong>Succes :</strong>l'ajoute a ete effectuer. Patienter Svp...
											        </div>
												";
											}
										}
									?>
								</form>
							</div>
						</div>
					</div>
    			<?php
    		}
    	}
    		elseif (isset($_GET['mat'])) {
    			if (!empty($_GET['mat'])) {
    				$reqinfob=$c->prepare("SELECT * FROM FONCTION WHERE ID=?");
    				$reqinfob->EXECUTE(array($_GET['mat']));
    				$infob=$reqinfob->fetch();
    				$reqinfoba=$c->prepare("SELECT * FROM APPRENTI WHERE COD_F=? AND (DATE_FINC >= SYSDATE)");
    				$reqnbr=$c->prepare("SELECT count(*) as NBR FROM APPRENTI WHERE COD_F=? AND (DATE_FINC >= SYSDATE)");
    				$reqinfoba->EXECUTE(array($_GET['mat']));
    				$reqnbr->EXECUTE(array($_GET['mat']));
    				$nbr=$reqnbr->fetch();
    				?>
    					<br><a class="btn btn-outline-info" href="fonction.php?op=fonction"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour</a>
    				<br>
    					<div class="jumbotron">
						  <h3>La Fonction: <?php echo $infob['NOM_FONCTION']; ?></h3> 
						  <h5>Il y'a <?php echo $nbr['NBR']; ?> Agent sous cette Fonction.</h5> 
						</div>
						<hr>
						<div class="table-responsive">
					      <table class="table table-hover">
					        <tr style="background: #e9ecef;">
					          <th>Matricule</th>
					          <th>Nom</th>
					          <th>Prenom</th>
					          <th>Date de naissance</th>
					          
					        </tr>
					        <?php 
					        	while ($agrow=$reqinfoba->fetch()) {
					        		echo "
					        				<tr>
					        					<td>".$agrow['MATRICULE']."</td>
					        					<td>".$agrow['NOM']."</td>
					        					<td>".$agrow['PRENOM']."</td>
					        					<td>".$agrow['DATE_N']."</td>
					        					
					        				</tr>
					        			";
					        	}
					        ?>
					      </table>
					    </div>
    				<?php
    			}
    		}else{
    			?>
    				<div>
    					<?php 
    								$reqbnc=$c->prepare("SELECT * FROM FONCTION");
    								$reqbnc->EXECUTE();
    								$reqnbrag=$c->prepare("SELECT COUNT(*) AS NBRAG FROM APPRENTI WHERE (DATE_FINC >= SYSDATE)");
    								$reqnbrag->EXECUTE();
    								$rnbr=$reqnbrag->fetch();
    								$namebanque=array();
    								$agbanque=array();
    								$i=0;
    								while ($row=$reqbnc->fetch()) {
    									$ag=$c->prepare("SELECT COUNT(*) AS NBR FROM APPRENTI WHERE COD_F=? AND (DATE_FINC >= SYSDATE)");
    									$ag->EXECUTE(array($row['ID']));
    									$j=$ag->fetch();
    									//$pag=$j['NBR']*100/$rnbr['NBRAG']; 
    									$namebanque[$i]=$row['NOM_FONCTION'];
    									$agbanque[$i]=$j['NBR'];
    									$i++;
    								}
    							?>
    					<div class="card col-md-9" style="float: none;">
    						<div class="card-header">Statistique des Fonctions</div>
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
							        labels: [<?php for($i=0;$i < count($agbanque);$i++){echo "'".$namebanque[$i]."',";} ?>],
							        datasets: [{
							            label: 'Statistique des Fonctions',
							            data: [<?php for($i=0;$i < count($agbanque);$i++){echo $agbanque[$i].",";} ?>],
							            backgroundColor: [
							                <?php for($i=0;$i < count($agbanque);$i++){echo "'#868e96;',";} ?>
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
									<?php
								}
//**************************************************************
//**************************************************************
			//End page fonction START page departement
								elseif ($_GET['op']=='departement') {
									$reqbanque=$c->prepare("SELECT * FROM DEPARTEMENT");
									$reqbanque->EXECUTE();
									?>
										<!-- Side navigation -->
	<div class="col-md-3 sidemenu">
		<center><br>
			<a class="btn btn-outline-info pull-left col-md-5" href="fonction.php?op=departement&operation=add"><i class="fa fa-plus"></i> Ajouter</a>
			<a class="btn btn-outline-danger pull-right col-md-5" href="fonction.php?op=departement&operation=del"><i class="fa fa-trash"></i> Supprimer</a><br><br>
			<form method="post"><br>
				<div class="input-group col-md-10">
			        <input class="form-control" type="text" name="srch" placeholder="Recherche...">
			        <button class="btn btn-info" type="submit" name="search"><span><i class="fa fa-search"></i></span></button>
			    </div>
			</form>
			<?php 
				if (isset($_POST['search'])) {
					if (!empty($_POST['srch'])) {
					$reqbanque=$c->prepare("SELECT * FROM DEPARTEMENT WHERE UPPER(NOM_DPT)LIKE UPPER(?) OR ID_DPT LIKE ?");
					$reqbanque->EXECUTE(array($_POST['srch'],$_POST['srch']));
					}
				}
			?>
		</center><br>
		<ul>
			<?php 
				while ($row=$reqbanque->fetch()) {
					echo "<li>
							<div>
								<a href='fonction.php?op=departement&mat=".$row['ID_DPT']."'>
									<i class='fa fa-tasks'></i>
									<span>
										".$row['NOM_DPT']."
									</span>
								</a>
							</div>
						</li>";
				}
			?>
		</ul>
	</div>
	<!-- END Side navigation -->

	<!-- Modal add banque -->
		
	<!-- END Modal add banque-->
 
</div>

<!-- ************ row jdid *************** -->
<div class="row justify-content-end">
	<!-- ************ page des detail *************** -->
	<div class="col-md-9 fpage">
    	<?php 
    	if (isset($_GET['operation'])AND $_GET['op']=="departement") {
    		if ($_GET['operation']=="del" AND $_GET['op']=="departement"){
    			?>
    				<br><a class="btn btn-outline-info" href="fonction.php?op=departement"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour </a>
    				<div><br>
    					<form action="" method="post">
				            <div class="table-responsive">
				              <table class="table">
				                <tr>
				                  <th>Id</th>
				                  <th>Nom</th>
				                </tr>
				                  <?php
				                  $reqbanquemodal = $c->prepare("SELECT * FROM DEPARTEMENT");
				                  $reqbanquemodal->EXECUTE();
				                    while ($row=$reqbanquemodal->fetch()) {
				                      echo "
				                      <tr>
				                        <td>".$row['ID_DPT']."</td>
				                        <td><label class='form-check-label'><input type='checkbox' value='".$row['ID_DPT']."' name='delete[]' class='form-check-input'>".$row['NOM_DPT']."</label></td>
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
								      $reqdel=$c->prepare("DELETE FROM DEPARTEMENT WHERE ID_DPT=?");
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
    		} elseif ($_GET['operation']=="add" AND $_GET['op']=="departement"){
    			?>
					<br><a class="btn btn-outline-info" href="fonction.php?op=departement"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour</a>
					<div class="col-md-5"><br>
						<div class="card">
							<div class="card-header">
								<h5>Ajouter une Departement</h5>
							</div>
							<div class="card-body">
								<form method="post">
									<div class="form-group">
										<label for="id">Id de la Departement</label>
										<input class="form-control" type="text" name="id" id="id">
									</div>
									<div class="form-group">
										<label for="name">Nom de la Departement</label>
										<input class="form-control" type="text" name="name" id="name">
									</div>
									<div class="form-group">
										<label for="drc">Sous la Direction</label>
										<select class="form-control" name="sldir">
											<?php 
											$direc=$c->prepare("SELECT * FROM DIRECTION");
											$direc->EXECUTE();
											while ($rowdir=$direc->fetch()) {
												?>
												<option value="<?php echo($rowdir['ID_DRC']) ?>"><?php echo $rowdir['NOM_DRC']; ?></option>
												<?php
											}
											?>
										</select>
									</div>
									<button class="btn btn-primary pull-right" type="submit" name="add">Ajouter</button>
									<?php 
										if (isset($_POST['add'])) {
											// settef les variable
											$id = $_POST['id'];
											$name = $_POST['name'];
											$drc = $_POST['sldir'];
											if (!empty($id) AND !empty($name) AND !empty($drc)) {
												$reqadd=$c->prepare("INSERT INTO DEPARTEMENT VALUES (?,?,?)");
												$reqadd->EXECUTE(array($id,$name,$drc));
												echo "<meta http-equiv='refresh' content='2'>";
												echo "
													<div class='alert alert-success alert-dimissable'>
											            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
											            <strong>Succes :</strong>l'ajoute a ete effectuer. Patienter Svp...
											        </div>
												";
											}
										}
									?>
								</form>
							</div>
						</div>
					</div>
    			<?php
    		}
    	}
    		elseif (isset($_GET['mat'])) {
    			if (!empty($_GET['mat'])) {
    				$reqinfob=$c->prepare("SELECT * FROM DEPARTEMENT WHERE ID_DPT=?");
    				$reqinfob->EXECUTE(array($_GET['mat']));
    				$infob=$reqinfob->fetch();
    				$reqinfoba=$c->prepare("SELECT * FROM APPRENTI WHERE ID_DPT=? AND (DATE_FINC >= SYSDATE)");
    				$reqnbr=$c->prepare("SELECT count(*) as NBR FROM APPRENTI WHERE ID_DPT=? AND (DATE_FINC >= SYSDATE)");
    				$reqinfoba->EXECUTE(array($_GET['mat']));
    				$reqnbr->EXECUTE(array($_GET['mat']));
    				$nbr=$reqnbr->fetch();
    				?>
    					<br><a class="btn btn-outline-info" href="fonction.php?op=departement"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour</a>
    				<br>
    					<div class="jumbotron">
						  <h3>Departement: <?php echo $infob['NOM_DPT']; ?></h3> 
						  <h5>Il y'a <?php echo $nbr['NBR']; ?> Agents dans cette departement.</h5> 
						</div>
						<hr>
						<div class="table-responsive">
					      <table class="table table-hover">
					        <tr style="background: #e9ecef;">
					          <th>Matricule</th>
					          <th>Nom</th>
					          <th>Prenom</th>
					          <th>Date de naissance</th>
					          
					        </tr>
					        <?php 
					        	while ($agrow=$reqinfoba->fetch()) {
					        		echo "
					        				<tr>
					        					<td>".$agrow['MATRICULE']."</td>
					        					<td>".$agrow['NOM']."</td>
					        					<td>".$agrow['PRENOM']."</td>
					        					<td>".$agrow['DATE_N']."</td>
					        					
					        				</tr>
					        			";
					        	}
					        ?>
					      </table>
					    </div>
    				<?php
    			}
    		}else{
    			?>
    				<div>
    					<?php 
    								$reqbnc=$c->prepare("SELECT * FROM DEPARTEMENT");
    								$reqbnc->EXECUTE();
    								$reqnbrag=$c->prepare("SELECT COUNT(*) AS NBRAG FROM APPRENTI WHERE (DATE_FINC >= SYSDATE)");
    								$reqnbrag->EXECUTE();
    								$rnbr=$reqnbrag->fetch();
    								$namebanque=array();
    								$agbanque=array();
    								$i=0;
    								while ($row=$reqbnc->fetch()) {
    									$ag=$c->prepare("SELECT COUNT(*) AS NBR FROM APPRENTI WHERE ID_DPT=? AND (DATE_FINC >= SYSDATE)");
    									$ag->EXECUTE(array($row['ID_DPT']));
    									$j=$ag->fetch();
    									//$pag=$j['NBR']*100/$rnbr['NBRAG']; 
    									$namebanque[$i]=$row['NOM_DPT'];
    									$agbanque[$i]=$j['NBR'];
    									$i++;
    								}
    							?>
    					<div class="card col-md-9" style="float: none;">
    						<div class="card-header">Statistique des departements</div>
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
							        labels: [<?php for($i=0;$i < count($agbanque);$i++){echo "'".$namebanque[$i]."',";} ?>],
							        datasets: [{
							            label: 'Statistique des Fonctions',
							            data: [<?php for($i=0;$i < count($agbanque);$i++){echo $agbanque[$i].",";} ?>],
							            backgroundColor: [
							                <?php for($i=0;$i < count($agbanque);$i++){echo "'".rand_color()."',";} ?>
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
									<?php
								}
//**************************************************************
//**************************************************************
			//End page departement START page direction
								elseif ($_GET['op']=='direction') {
									$reqbanque=$c->prepare("SELECT * FROM DIRECTION");
									$reqbanque->EXECUTE();
									?>
										<!-- Side navigation -->
	<div class="col-md-3 sidemenu">
		<center><br>
			<a class="btn btn-outline-info pull-left col-md-5" href="fonction.php?op=direction&operation=add"><i class="fa fa-plus"></i> Ajouter</a>
			<a class="btn btn-outline-danger pull-right col-md-5" href="fonction.php?op=direction&operation=del"><i class="fa fa-trash"></i> Supprimer</a><br><br>
			<form method="post"><br>
				<div class="input-group col-md-10">
			        <input class="form-control" type="text" name="srch" placeholder="Recherche...">
			        <button class="btn btn-info" type="submit" name="search"><span><i class="fa fa-search"></i></span></button>
			    </div>
			</form>
			<?php 
				if (isset($_POST['search'])) {
					if (!empty($_POST['srch'])) {
					$reqbanque=$c->prepare("SELECT * FROM DIRECTION WHERE UPPER(NOM_DRC)LIKE UPPER(?) OR ID_DRC LIKE ?");
					$reqbanque->EXECUTE(array($_POST['srch'],$_POST['srch']));
					}
				}
			?>
		</center><br>
		<ul>
			<?php 
				while ($row=$reqbanque->fetch()) {
					echo "<li>
							<div>
								<a href='fonction.php?op=direction&mat=".$row['ID_DRC']."'>
									<i class='fa fa-tasks'></i>
									<span>
										".$row['NOM_DRC']."
									</span>
								</a>
							</div>
						</li>";
				}
			?>
		</ul>
	</div>
	<!-- END Side navigation -->

	<!-- Modal add banque -->
		
	<!-- END Modal add banque-->
 
</div>

<!-- ************ row jdid *************** -->
<div class="row justify-content-end">
	<!-- ************ page des detail *************** -->
	<div class="col-md-9 fpage">
    	<?php 
    	if (isset($_GET['operation'])AND $_GET['op']=="direction") {
    		if ($_GET['operation']=="del" AND $_GET['op']=="direction"){
    			?>
    				<br><a class="btn btn-outline-info" href="fonction.php?op=direction"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour</a>
    				<div><br>
    					<form action="" method="post">
				            <div class="table-responsive">
				              <table class="table">
				                <tr>
				                  <th>Id</th>
				                  <th>Nom</th>
				                </tr>
				                  <?php
				                  $reqbanquemodal = $c->prepare("SELECT * FROM DIRECTION");
				                  $reqbanquemodal->EXECUTE();
				                    while ($row=$reqbanquemodal->fetch()) {
				                      echo "
				                      <tr>
				                        <td>".$row['ID_DRC']."</td>
				                        <td><label class='form-check-label'><input type='checkbox' value='".$row['ID_DRC']."' name='delete[]' class='form-check-input'>".$row['NOM_DRC']."</label></td>
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
								      $reqdel=$c->prepare("DELETE FROM DIRECTION WHERE ID_DRC=?");
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
    		} elseif ($_GET['operation']=="add" AND $_GET['op']=="direction"){
    			?>
					<br><a class="btn btn-outline-info" href="fonction.php?op=direction"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour</a>
					<div class="col-md-5"><br>
						<div class="card">
							<div class="card-header">
								<h5>Ajouter une direction</h5>
							</div>
							<div class="card-body">
								<form method="post">
									<div class="form-group">
										<label for="id">Id de la direction</label>
										<input class="form-control" type="text" name="id" id="id">
									</div>
									<div class="form-group">
										<label for="name">Nom de la direction</label>
										<input class="form-control" type="text" name="name" id="name">
									</div>
									<button class="btn btn-primary pull-right" type="submit" name="add">Ajouter</button>
									<?php 
										if (isset($_POST['add'])) {
											// settef les variable
											$id = $_POST['id'];
											$name = $_POST['name'];
											if (!empty($id)||!empty($name)) {
												$reqadd=$c->prepare("INSERT INTO DIRECTION VALUES (?,?)");
												$reqadd->EXECUTE(array($id,$name));
												echo "<meta http-equiv='refresh' content='2'>";
												echo "
													<div class='alert alert-success alert-dimissable'>
											            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
											            <strong>Succes :</strong>l'ajoute a ete effectuer. Patienter Svp...
											        </div>
												";
											}
										}
									?>
								</form>
							</div>
						</div>
					</div>
    			<?php
    		}
    	}
    		elseif (isset($_GET['mat'])) {
    			if (!empty($_GET['mat'])) {
    				$reqinfob=$c->prepare("SELECT * FROM DIRECTION WHERE ID_DRC=?");
    				$reqinfob->EXECUTE(array($_GET['mat']));
    				$infob=$reqinfob->fetch();
    				$reqinfoba=$c->prepare("SELECT * FROM DEPARTEMENT WHERE ID_DRC=?");
    				$reqnbr=$c->prepare("SELECT count(*) as NBR FROM DEPARTEMENT WHERE ID_DRC=?");
    				$reqinfoba->EXECUTE(array($_GET['mat']));
    				$reqnbr->EXECUTE(array($_GET['mat']));
    				$nbr=$reqnbr->fetch();
    				?>
    					<br><a class="btn btn-outline-info" href="fonction.php?op=direction"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour</a>
    				<br>
    					<div class="jumbotron">
						  <h3>La Direction: <?php echo $infob['NOM_DRC']; ?></h3> 
						  <h5>Il y'a <?php echo $nbr['NBR']; ?> departement sous cette Direction.</h5> 
						</div>
						<hr>
						<div class="table-responsive">
					      <table class="table table-hover">
					        <tr style="background: #e9ecef;">
					          <th>Matricule</th>
					          <th>Nom</th>
					          
					        </tr>
					        <?php 
					        	while ($agrow=$reqinfoba->fetch()) {
					        		echo "
					        				<tr>
					        					<td>".$agrow['ID_DPT']."</td>
					        					<td>".$agrow['NOM_DPT']."</td>
					        					
					        				</tr>
					        			";
					        	}
					        ?>
					      </table>
					    </div>
    				<?php
    			}
    		}else{
    			?>
    				<div>
    					<?php 
    								$reqbnc=$c->prepare("SELECT * FROM DIRECTION");
    								$reqbnc->EXECUTE();
    								$reqnbrag=$c->prepare("SELECT COUNT(*) AS NBRAG FROM DEPARTEMENT");
    								$reqnbrag->EXECUTE();
    								$rnbr=$reqnbrag->fetch();
    								$namebanque=array();
    								$agbanque=array();
    								$i=0;
    								while ($row=$reqbnc->fetch()) {
    									$ag=$c->prepare("SELECT COUNT(*) AS NBR FROM DEPARTEMENT WHERE ID_DRC=?");
    									$ag->EXECUTE(array($row['ID_DRC']));
    									$j=$ag->fetch();
    									//$pag=$j['NBR']*100/$rnbr['NBRAG']; 
    									$namebanque[$i]=$row['NOM_DPT'];
    									$agbanque[$i]=$j['NBR'];
    									$i++;
    								}
    							?>
    					<div class="card col-md-9" style="float: none;">
    						<div class="card-header">Statistique des Direction</div>
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
							        labels: [<?php for($i=0;$i < count($agbanque);$i++){echo "'".$namebanque[$i]."',";} ?>],
							        datasets: [{
							            label: 'Statistique des Fonctions',
							            data: [<?php for($i=0;$i < count($agbanque);$i++){echo $agbanque[$i].",";} ?>],
							            backgroundColor: [
							                <?php for($i=0;$i < count($agbanque);$i++){echo "'".rand_color()."',";} ?>
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
									<?php
								}
//**************************************************************
//**************************************************************
			//End page direction 
							?>
						</div>
					<?php
				}else{
					//empty get chart
				}
			}
		?>
</div>
</body>
</html>