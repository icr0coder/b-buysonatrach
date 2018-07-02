<?php 
include("inc/dbconnecte.php");
$reqbanque=$c->prepare("SELECT * FROM BANQUE");
$reqbanque->EXECUTE();
$reqdate=$c->prepare("SELECT DATE_INI FROM TB_VARS");
$reqdate->EXECUTE();
$date=$reqdate->fetch();
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
	if ($_SESSION['type']=='GDP') {
		echo "<li class='nav-item'><a class='nav-link' href='agents.php'><i class='fa fa-users'></i> Agents</a></li>
		<li class='nav-item active'><a class='nav-link' href='Banque.php'><i class='fa fa-university'></i> Banque</a></li>
		<li class='nav-item'><a class='nav-link' href='paie.php'><i class='fa fa-money'></i> Paie</a></li>
			  <li class='nav-item'><a class='nav-link' href='settings.php'><i class='fa fa-cog'></i> Parametre</a></li>";	
	}elseif ($_SESSION['type']=='ADM') {
		?>
		<li class="nav-item"><a class="nav-link" href="agents.php"><i class="fa fa-users"></i> Agents</a></li>
		<li class="nav-item active"><a class="nav-link" href="Banque.php"><i class="fa fa-university"></i> Banques</a></li>
		<li class="nav-item"><a class="nav-link" href="fonction.php"><i class="fa fa-tasks"></i> Fonctions</a></li>
		<li class="nav-item"><a class="nav-link" href="absence.php"><i class="fa fa-calendar-check-o"></i> Absences</a></li>
		<li class="nav-item"><a class="nav-link" href="paie.php"><i class="fa fa-money"></i> Paie</a></li>
		<li class="nav-item"><a class="nav-link" href="settings.php"><i class="fa fa-cog"></i> Parametres</a></li>
		<?php
	}

	?>
		
	<?php include("inc/endnavbar.php"); ?>	
<?php 
if ($_SESSION['type']=='SCR') {
	?>
		<div class="container">
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<br><div class="card">
						<center><br><h3>vous n'avez pas l'autorisation d'acceder a cette page</h3></center>
					</div>
				</div>
			</div>
		</div>
	<?php
	exit;
}
?>
<!-- end header navigation -->

<div class="container-fluid">
	<div class="row">

	<!-- Side navigation -->
	<div class="col-md-3 sidemenu">
		<center><br>
			<a class="btn btn-outline-info pull-left col-md-5" href="banque.php?operation=add"><i class="fa fa-plus"></i> Ajouter</a>
			<a class="btn btn-outline-danger pull-right col-md-5" href="banque.php?operation=del"><i class="fa fa-trash"></i> Supprimer</a><br><br>
			<form method="post"><br>
				<div class="input-group col-md-10">
			        <input class="form-control" type="text" name="srch" placeholder="Recherche...">
			        <button class="btn btn-info" type="submit" name="search"><span><i class="fa fa-search"></i></span></button>
			    </div>
			</form>
			<?php 
				if (isset($_POST['search'])) {
					if (!empty($_POST['srch'])) {
					$reqbanque=$c->prepare("SELECT * FROM BANQUE WHERE UPPER(NOM_BANQUE)LIKE UPPER(?)");
					$reqbanque->EXECUTE(array($_POST['srch']));
					}
				}
			?>
		</center><br>
		<ul>
			<?php 
				while ($row=$reqbanque->fetch()) {
					echo "<li>
							<div>
								<a href='Banque.php?mat=".$row['ID']."'>
									<i class='fa fa-university'></i>
									<span>
										".$row['NOM_BANQUE']."
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
    	if (isset($_GET['operation'])) {
    		if ($_GET['operation']=="del") {
    			?>
    				<br><a class="btn btn-outline-info" href="banque.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour</a>
    				<div><br>
    					<form action="" method="post">
				            <div class="table-responsive">
				              <table class="table">
				                <tr>
				                  <th>Id</th>
				                  <th>Nom</th>
				                </tr>
				                  <?php
				                  $reqbanquemodal = $c->prepare("SELECT * FROM banque");
				                  $reqbanquemodal->EXECUTE();
				                    while ($row=$reqbanquemodal->fetch()) {
				                      echo "
				                      <tr>
				                        <td>".$row['ID']."</td>
				                        <td><label class='form-check-label'><input type='checkbox' value='".$row['ID']."' name='delete[]' class='form-check-input'>".$row['NOM_BANQUE']."</label></td>
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
								      $reqdel=$c->prepare("DELETE FROM BANQUE WHERE ID=?");
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
    		} elseif ($_GET['operation']=="add") {
    			?>
					<br><a class="btn btn-outline-info" href="banque.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour</a>
					<div class="col-md-5"><br>
						<div class="card">
							<div class="card-header">
								<h5>Ajouter une banque</h5>
							</div>
							<div class="card-body">
								<form method="post">
									<div class="form-group">
										<label for="id">Id de la banque</label>
										<input class="form-control" type="text" name="id" id="id">
									</div>
									<div class="form-group">
										<label for="name">Nom de la banque</label>
										<input class="form-control" type="text" name="name" id="name">
									</div>
									<button class="btn btn-primary pull-right" type="submit" name="add">Ajouter</button>
									<?php 
										if (isset($_POST['add'])) {
											// settef les variable
											$id = $_POST['id'];
											$name = $_POST['name'];
											if (!empty($id) AND !empty($name)) {
												$reqadd=$c->prepare("INSERT INTO BANQUE VALUES (?,?)");
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
    				$reqinfob=$c->prepare("SELECT * FROM BANQUE WHERE ID=?");
    				$reqinfob->EXECUTE(array($_GET['mat']));
    				$infob=$reqinfob->fetch();
    				$reqinfoba=$c->prepare("SELECT * FROM APPRENTI WHERE COD_BNC=?");
    				$reqnbr=$c->prepare("SELECT count(*) as NBR FROM APPRENTI WHERE COD_BNC=?");
    				$reqinfoba->EXECUTE(array($_GET['mat']));
    				$reqnbr->EXECUTE(array($_GET['mat']));
    				$nbr=$reqnbr->fetch();
    				?>
    					<br><a class="btn btn-outline-info" href="banque.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> retour</a>
    				<br>
    					<div class="jumbotron">
						  <h3><?php echo $infob['NOM_BANQUE']; ?></h3> 
						  <h5>Le nombre d'agents qui adhérent cette banque est : <?php echo $nbr['NBR']; ?> .</h5> 
						</div>
						<hr>
						<div class="table-responsive">
					      <table class="table table-hover">
					        <tr style="background: #e9ecef;">
					          <th>Matricule</th>
					          <th>Nom</th>
					          <th>Prenom</th>
					          <th>Date de naissance</th>
					          <th>N°compte</th>
					        </tr>
					        <?php 
					        	while ($agrow=$reqinfoba->fetch()) {
					        		echo "
					        				<tr>
					        					<td>".$agrow['MATRICULE']."</td>
					        					<td>".$agrow['NOM']."</td>
					        					<td>".$agrow['PRENOM']."</td>
					        					<td>".$agrow['DATE_N']."</td>
					        					<td>num compt banquaire</td>
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
    								$reqbnc=$c->prepare("SELECT * FROM BANQUE");
    								$reqbnc->EXECUTE();
    								$reqnbrag=$c->prepare("SELECT COUNT(*) AS NBRAG FROM APPRENTI");
    								$reqnbrag->EXECUTE();
    								$rnbr=$reqnbrag->fetch();
    								$namebanque=array();
    								$agbanque=array();
    								$i=0;
    								while ($row=$reqbnc->fetch()) {
    									$ag=$c->prepare("SELECT COUNT(*) AS NBR FROM APPRENTI WHERE COD_BNC=?");
    									$ag->EXECUTE(array($row['ID']));
    									$j=$ag->fetch();
    									//$pag=$j['NBR']*100/$rnbr['NBRAG']; 
    									$namebanque[$i]=$row['NOM_BANQUE'];
    									$agbanque[$i]=$j['NBR'];
    									$i++;
    								}
    							?>
    						<div class="card col-md-9" style="float: none;">
	    						<div class="card-header">Statistique des banques</div>
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
							            label: 'Statistique des banques',
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
<!-- ************ Fin row *************** -->

</div>

</body>
</html>