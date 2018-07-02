<?php 
include("inc/function.php");
include("inc/dbconnecte.php");
$reqagent=$c->prepare("SELECT * FROM APPRENTI WHERE (ACTIF='O') AND (DATE_FINC >= SYSDATE)");
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
	if ($_SESSION['type']=='GDP') {
		echo "<li class='nav-item'><a class='nav-link' href='agents.php'><i class='fa fa-users'></i> Agents</a></li>
			  <li class='nav-item'><a class='nav-link' href='absence.php'><i class='fa fa-calendar-check-o'></i> Absence</a></li>
			  <li class='nav-item'><a class='nav-link' href='fonction.php'><i class='fa fa-tasks'></i> Fonction</a></li>
			  <li class='nav-item active'><a class='nav-link' href='paie.php'><i class='fa fa-money'></i> Paie</a></li>
			  <li class='nav-item'><a class='nav-link' href='settings.php'><i class='fa fa-cog'></i> Parametre</a></li>";
	}elseif ($_SESSION['type']=='ADM') {
		?>
		<li class="nav-item"><a class="nav-link" href="agents.php"><i class="fa fa-users"></i> Agents</a></li>
		<li class="nav-item"><a class="nav-link" href="Banque.php"><i class="fa fa-university"></i> Banques</a></li>
		<li class="nav-item"><a class="nav-link" href="fonction.php"><i class="fa fa-tasks"></i> Fonction</a></li>
		<li class="nav-item"><a class="nav-link" href="absence.php"><i class="fa fa-calendar-check-o"></i> Absences</a></li>
		<li class="nav-item active"><a class="nav-link" href="paie.php"><i class="fa fa-money"></i> Paie</a></li>
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
						<center><br><h3>vous n'avez pas l'autorisation d'accedez a cette page</h3></center>
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
	<div class="col-md-8"><br>
		
		<a class="btn btn-outline-info" href="journale.php" target="_blank"><i class="fa fa-clipboard" aria-hidden="true"></i> Journale de paie</a>
	</div>
	<div class="col-md-4"><br>
		<form method="POST">
			<?php 
			date_default_timezone_set('UTC');
				if (isset($_POST['calcpaie'])) {
					while ($row=$reqagent->fetch()) {
    					$reqsem=$c->prepare("SELECT DATE_INI-DATE_REC FROM TB_VARS,APPRENTI WHERE MATRICULE=?");
	                	$reqsem->EXECUTE(array($row['MATRICULE']));
	                	$rowsem=$reqsem->fetch();
	                	$sem= (int)($rowsem['DATE_INI-DATE_REC']/180);
	                	$reqabt=$c->prepare("SELECT COUNT(*) AS CNT FROM ABSENTER WHERE (MATRICULE=?) AND CODE_AB<>'PR'");
						$reqabt->EXECUTE(array($row['MATRICULE']));
						$totale=$reqabt->fetch();
						calcul($sem,$row['MATRICULE'],$c);
						//condition de modification de paie
						$reqver=$c->prepare("SELECT * FROM TB_PAIE WHERE (ID_PAIE=?) AND (CLOTURE=0)");
						$reqver->EXECUTE(array($row['MATRICULE'].date("m").date("Y")));
						$rowconf=$reqver->fetch();
						if ($rowconf==true) {
							$reqstorepaie1=$c->prepare("UPDATE PAIE SET GAINBASE=?,GAINRETENUE=?,GAINNET=? WHERE ID_PAIE=?");
							$reqstorepaie1->EXECUTE(array($gainsbaser.",00 DA",$retenue.",00 DA",$gainsbaser-$retenue.",00 DA",$row['MATRICULE'].date("m").date("Y")));	
						}else{

						//sauvegarder la paie
						$reqstorepaie=$c->prepare("INSERT INTO TB_PAIE VALUES(?,?,?)");
						$reqstorepaie->EXECUTE(array($row['MATRICULE'].date("m").date("Y"),date("m").date("Y"),0));
						$reqstorepaie1=$c->prepare("INSERT INTO PAIE VALUES(?,?,?,?,?)");
						$reqstorepaie1->EXECUTE(array($row['MATRICULE'],$row['MATRICULE'].date("m").date("Y"),$gainsbaser.",00 DA",$retenue.",00 DA",$gainsbaser-$retenue.",00 DA"));
						}			
					}
				echo "<meta http-equiv='refresh' content='0'>";	
				}
				if (isset($_POST['cloture'])) {
					
				}
			?>
			<button class="btn btn-outline-info" type="submit" name="calcpaie"><i class="fa fa-save" aria-hidden="true"></i> Calculer la paie</button>
			<button class="btn btn-danger" type="submit" name="cloture"><i class="fa fa-save" aria-hidden="true"></i> Cloturer la paie</button>
		</form>
	</div>
</div>
<hr>
<div class="row">
		<div class="col-md-4">
			<h5>
				<?php 
				    date_default_timezone_set('UTC');
            		echo $_SESSION['date'];
				?>
			</h5>
		</div>
		<div class="col-md-4">
			<form method="post">
				<div class="input-group col-md-10">
			        <input class="form-control" type="text" name="srch" placeholder="Recherche...">
			        <button class="btn btn-outline-info" type="submit" name="search"><span><i class="fa fa-search"></i></span></button>
			    </div>
			</form>
			<?php 
				if (isset($_POST['search'])) {
					if (!empty($_POST['srch'])) {
					$reqagent=$c->prepare("SELECT * FROM APPRENTI WHERE (ACTIF='O') AND (UPPER(NOM)LIKE UPPER(?) OR UPPER(PRENOM)LIKE UPPER(?) OR UPPER(NOM||' '||PRENOM) LIKE UPPER(?) OR UPPER(PRENOM||' '||NOM) LIKE UPPER(?) OR UPPER(MATRICULE) LIKE UPPER(?) OR UPPER(N_SS) LIKE UPPER(?))");
					$reqagent->EXECUTE(array($_POST['srch'],$_POST['srch'],$_POST['srch'],$_POST['srch'],$_POST['srch'],$_POST['srch']));
					}
				}
			?>
		</div>
		<div class="col-md-4">
			<form method="POST">
				<?php 
					if (isset($_POST['filtre'])) {
						$reqagent=$c->prepare("SELECT * FROM APPRENTI WHERE ID_DPT IN (SELECT ID_DPT FROM DEPARTEMENT WHERE ID_DRC=?)");
						$reqagent->EXECUTE(array($_POST['dpt']));
					}
				?>
				<div class="input-group">
					<select class="form-control" name="dpt">
						<option value=""><h5>Service... </h5></option>
						<?php 
						$reqservice=$c->prepare("SELECT * FROM DIRECTION");
						$reqservice->EXECUTE();
						while ($rowservice=$reqservice->fetch()) {
							?>
								<option value="<?php echo($rowservice['ID_DRC']) ?>"><?php echo $rowservice['NOM_DRC']; ?></option>
							<?php
						}
						?>
					</select>
					<button class="btn btn-info" type="submit" name="filtre">Filtrer</button>
				</div>
			</form>
		</div>
	</div>
<!-- ************ row jdid *************** -->
<div class="row">
	<!-- ************ page des detail *************** -->
	<div class="col-md-12">
    	<div class="table-responsive"><br>
    		<table class="table table-hover">
    			<tr>
    				<td>Nom, Prénom</td>
    				<td>Semestre</td>
    				<td>Gain Base</td>
    				<td>Absence</td>
    				<td>Retenue</td>
    				<td>Gain Net</td>
    				<td>Imprimer</td>
    			</tr>
    			<?php 
    			while ($row=$reqagent->fetch()) {
    				$reqsem=$c->prepare("SELECT * FROM TB_PAIE INNER JOIN PAIE ON TB_PAIE.ID_PAIE=PAIE.ID_PAIE WHERE (PAIE.MATRICULE=?) AND (DATE_PAIE=?)");
	                $reqsem->EXECUTE(array($row['MATRICULE'],date("m").date("Y")));
	                $paie=$reqsem->fetch();
	                $reqabt=$c->prepare("SELECT COUNT(*) AS CNT FROM ABSENTER WHERE (MATRICULE=?) AND (CODE_AB<>'PR') AND (to_char(DATE_AB, 'MM-YYYY')=?)");
					$reqabt->EXECUTE(array($row['MATRICULE'],date("m")."-".date("Y")));
					$totale=$reqabt->fetch();

					$reqsem=$c->prepare("SELECT DATE_INI-DATE_REC FROM TB_VARS,APPRENTI WHERE MATRICULE=?");
	                	$reqsem->EXECUTE(array($row['MATRICULE']));
	                	$rowsem=$reqsem->fetch();
	                	$sem= (int)($rowsem['DATE_INI-DATE_REC']/180);
    				?>
    				<tr>
    					<td><?php echo $row['NOM'].", ".$row['PRENOM']; ?></td>
    					<td><?php echo $sem; ?></td>
    					<td><?php echo $paie['GAINBASE']; ?></td>
    					<td><?php echo $totale['CNT']; ?></td>
    					<td><?php echo $paie['GAINRETENUE']; ?></td>
    					<td><?php echo $paie['GAINNET']; ?></td>
    					<td><a class="btn btn-outline-success" href="print.php?Matricule=<?php echo($row['MATRICULE']) ?>" target="_blank">Imprimer</a></td>
    				</tr>
    				<?php
    			}
    			?>
    		</table>
    	</div>
    </div>
    <!-- ************ Fin page detail *************** -->
</div>
<!-- ************ Fin row *************** -->

</div>

</body>
</html>