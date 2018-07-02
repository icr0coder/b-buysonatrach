<?php 
include("inc/function.php");
include("inc/dbconnecte.php");
$reqagent=$c->prepare("SELECT * FROM APPRENTI WHERE ACTIF='O'AND (DATE_FINC >= SYSDATE)");
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
		echo "<li class='nav-item'><a class='nav-link' href='agents.php'><i class='fa fa-users'></i> Agents</a></li>
			  <li class='nav-item active'><a class='nav-link' href='absence.php'><i class='fa fa-calendar-check-o'></i> Absence</a></li>
			  <li class='nav-item'><a class='nav-link' href='fonction.php'><i class='fa fa-tasks'></i> Fonction</a></li>
			  <li class='nav-item'><a class='nav-link' href='settings.php'><i class='fa fa-cog'></i> Parametre</a></li>";
	}elseif ($_SESSION['type']=='ADM') {
		?>
		<li class="nav-item"><a class="nav-link" href="agents.php"><i class="fa fa-users"></i> Agents</a></li>
		<li class="nav-item"><a class="nav-link" href="Banque.php"><i class="fa fa-university"></i> Banques</a></li>
		<li class="nav-item"><a class="nav-link" href="fonction.php"><i class="fa fa-tasks"></i> Fonctions</a></li>
		<li class="nav-item active"><a class="nav-link" href="absence.php"><i class="fa fa-calendar-check-o"></i> Absences</a></li>
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

<!-- end header navigation -->
<div class="container-fluid">
	<div class="row">
		<div class="col-md-4"><br>
			<h5>
				<?php 
				    date_default_timezone_set('UTC');
            		echo $_SESSION['date'];
				?>
			</h5>
		</div>
		<div class="col-md-4">
			<form method="post"><br>
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
			<form method="POST"><br>
				<?php 
					if (isset($_POST['filtre'])) {
						$reqagent=$c->prepare("SELECT * FROM APPRENTI WHERE ID_DPT IN (SELECT ID_DPT FROM DEPARTEMENT WHERE ID_DRC=?)");
						$reqagent->EXECUTE(array($_POST['dpt']));
					}
				?>
					<!--<div class="form-group">-->
							
							<div id="drdiv">
								<input type="hidden" name="dptcode" id="dptcode">
							<select class="form-control" name="dr" id="dr" onchange="dptfunc(this)">
							<option > Direction... </option>
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
						<!--</div>-->
						<div id="maskeddiv">
						<?php 
						$nbrdiv=0;
								$reqdrc=$c->prepare("SELECT * FROM DIRECTION");
								$reqdrc->EXECUTE();
								while ($rowdrc=$reqdrc->fetch()) {
								$nbrdiv=$nbrdiv+1;	
							?>
							
								<div style="display: none;" id="<?php echo($rowdrc['ID_DRC']) ?>">
									<select class="form-control" name="dpt" id="dpt" onchange="selection(this)">
										<option value="0" onclick="clkfun()">retoure...</option>
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
									drdiv=document.getElementById("drdiv")
									drdiv.style.display="none";
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

							function clkfun(){
								if (dptcode==0) {
									divsh=document.getElementById("drdiv");
									divsh.style.display="block";
									op=document.getElementById("dpt");
									op.style.display="none";
								}
							}
						</script>
					<button class="btn btn-info" type="submit" name="filtre">Filtrer</button>
			</form>
		</div>
	</div>
<!-- ************ row jdid *************** -->
<div class="row">
	<!-- ************ page des detail *************** -->
	<div class="col-md-12">
		<br><div class="table-responsive">
			<table class="table table-hover table-bordered table-responsive" id="tbpoint" style="border-color: #000000;">
				<tr style="background: #BDBDBD;">
					<th>Agents \ Jours</th>
					<?php for ($i=1; $i < date("t")+1; $i++) { 
						?>
							<th class="rotate"><div><span><?php echo $i; ?></span></div></th>
						<?php
					} ?>
					<th>Totale</th>
				</tr>
				<?php 
					while ($row=$reqagent->fetch()) {
						$reqabt=$c->prepare("SELECT COUNT(*) AS CNT FROM ABSENTER WHERE (MATRICULE=?) AND (CODE_AB<>'PR') AND (to_char(DATE_AB, 'MM-YYYY')=?)");
						$reqabt->EXECUTE(array($row['MATRICULE'],date("m")."-".date("Y")));
						$totale=$reqabt->fetch();
						?>
							<tr onclick="rowindex(this)">
								<td class="text-center" style="height: 70px;background: #BDBDBD;"><?php echo $row['NOM'].", ".$row['PRENOM']; ?></td>
								<?php 
								for ($i=1; $i < date("t")+1; $i++) { 
									$m=date("m");
									$y=date("y");
									$day= date("l",mktime(0,0,0,$m,$i,$y));
									if ($day=="Friday" OR $day=="Saturday") {
										?>
										<td style="background: #BDBDBD;"></td>	
										<?php
									}else{
										$reqabaf=$c->prepare("SELECT * FROM ABSENTER WHERE MATRICULE=? AND DATE_AB=?");
										$reqabaf->EXECUTE(array($row['MATRICULE'],$i."/".$m."/".$y));
										$rowab=$reqabaf->fetch(); 
											//var_dump($rowab['CODE_AB']);
											if ($rowab==true AND $rowab['CODE_AB']!=='PR') {
									?>
											<td class="jrpointage bg-danger" onclick="tdnbr(this)" id="tdjr">
												<input type="hidden" name="matricule" value="<?php echo($row['MATRICULE']); ?>" id="mattd">
												<input type="hidden" name="matricule" value="<?php echo($i); ?>" id="jr">
												<input type="hidden" name="updatept" value="1" id="updatept">
											</td>
									<?php			
											}elseif ($rowab==true AND $rowab['CODE_AB']=='PR') {
												?>
													<td class="jrpointage bg-success" onclick="tdnbr(this)" id="tdjr">
														<input type="hidden" name="matricule" value="<?php echo($row['MATRICULE']); ?>" id="mattd">
														<input type="hidden" name="matricule" value="<?php echo($i); ?>" id="jr">
														<input type="hidden" name="updatept" value="1" id="updatept">
													</td>
												<?php	
											}else{											
									?>
										<td class="jrpointage" onclick="tdnbr(this)" id="tdjr">
											<input type="hidden" name="matricule" value="<?php echo($row['MATRICULE']); ?>" id="mattd">
											<input type="hidden" name="matricule" value="<?php echo($i); ?>" id="jr">
											<input type="hidden" name="updatept" value="0" id="updatept">
										</td>
									<?php
									}
									
								}
							} ?>
								<td class="text-center"><?php echo $totale['CNT']; ?></td>
							</tr>
						<?php
					}
				?>	
			</table>
			<?php 
				if (isset($_POST['appliq'])) {
					$daate=$_POST['jrtd']."/".date("m")."/".date("Y");
					$matr=$_POST['matricule'];
					if ($_POST['selectab']=="PR") {
						$cdab="PR";
					}else{
						$cdab=$_POST['codab'];
					}
					if ($_POST['updatemd']==1) {
						$reqab=$c->prepare("UPDATE ABSENTER SET CODE_AB=? WHERE MATRICULE=? AND DATE_AB=?");
						$reqab->EXECUTE(array($cdab,$matr,$daate));
					}else{
						$reqab=$c->prepare("INSERT INTO ABSENTER VALUES (?,?,?)");
						$reqab->EXECUTE(array($matr,$cdab,$daate));
					}
					
					echo "<meta http-equiv='refresh' content='0'>";
				}
			?>
<div class="modal col-md-4" id="pointage" role="document">
	<form method="POST">
	<div class="card">
		<div class="card-header" id="card-header"></div>
		<input type="hidden" name="matricule" id="matricule"/>
		<input type="hidden" name="jrtd" id="jrtd"/>
		<input type="hidden" name="updatemd" id="updatemd">
		<div class="card-body">
			<select class="form-control" onchange="selchange(this)" id="selectab" name="selectab">
				<option value="PR">Présent</option>
				<option value="ab">Ajouter absence</option>
			</select>
			<div id="abs" style="display: none;">
				<br>
					<div class="input-group">
						<label>Type d'absence :</label>
						<select class="form-control" name="codab">
							<?php 
								$reqmotif = $c->prepare("SELECT * FROM ABSENCE WHERE CODE_AB <> 'PR' " );
								$reqmotif->EXECUTE();
								while ($row=$reqmotif->fetch()) {
									?>
										<option value="<?php echo($row['CODE_AB']) ?>"><?php echo $row['JUSTIFICATION']; ?></option>
									<?php
								}
							?>
						</select>
					</div>
			</div>
		</div>
		<div class="card-footer">
			<button class="btn btn-outline-info pull-right" name="appliq">Appliquer</button>
			<button class="btn btn-outline-danger" data-dismiss="modal">close</button>
		</div>
	</div>
	</form>
</div>
		</div>
	</div>
</div>
<!-- ************ Fin row *************** -->

</div>
			<script>
	
		function tdnbr(td){
			tdjr = td.cellIndex;			
		}

		function rowindex(x) {
			var inex = document.getElementById("tbpoint");
			val = inex.rows[x.rowIndex].cells[1].getElementsByTagName("input")[0].value;
			up = inex.rows[x.rowIndex].cells[tdjr].getElementsByTagName("input")[2].value;
			$('#pointage').modal('show');
		}

		$( "#pointage" ).on('shown.bs.modal', function(){
    		document.getElementById("matricule").value = val;
    		document.getElementById("jrtd").value = tdjr;
    		document.getElementById("updatemd").value = up;
    		document.getElementById("card-header").innerHTML = "Matricule d'agent: "+val+" le jour: "+tdjr;
		});

		function selchange(x){
			var select = document.getElementById("abs");
		    if (x.value === "ab") {
		        select.style.display = "block";
		    } else {
		        select.style.display = "none";
		    }
		}
</script>
</body>
</html>