<?php 


require '/vendor/autoload.php';
date_default_timezone_set("UTC");
use Spipu\Html2Pdf\Html2Pdf;

	    	    SESSION_START();
			    if (empty($_SESSION['username'])) {
			      header("LOCATION:index.php");
			    }

ob_start();
?>
<style type="text/css">
.thmat{
	border-collapse: collapse;
	font-size: 16px;
}
	.thmat th{
		border: 1px solid black;
		width: 150px;
		text-align: center;
		padding: 5px;
		color: white;
		background-color: #595959;
	}
	.thmat td{
		border: 1px solid black;
		width: 200.5px;
		text-align: center;	
		padding-bottom: 2px;
		padding-top: 2px;
	}
.thmat2{
	height: 40%;
	line-height: 10mm; 
	font-size: 16px;
	border-bottom: 1px;
}
	.thmat2 th{
		border: 1px solid black;
		text-align: center;
		padding: 5px;
		color: white;
		background-color: #595959;
	}
	.thmat2 td{
		border: 1px solid black;
		text-align: center;	
		padding-bottom: none;
		padding-top: 2px;
	}
.tdstyle{
	height: 50mm;
}
.tdstyle td{
	border-top:none;
	border-bottom: none; 
	border-left:1px solid black; 
	border-right:1px solid black;
}
</style>
<?php 
include("inc/dbconnecte.php");
	$req=$c->prepare("SELECT * FROM APPRENTI WHERE ACTIF='O' AND MATRICULE=?");
	$req->EXECUTE(array($_GET['Matricule']));
	$info=$req->fetch();
	$reqfonction=$c->prepare("SELECT * FROM FONCTION WHERE ID=?");
	$reqfonction->EXECUTE(array($info['COD_F']));
	$fon=$reqfonction->fetch();
	$reqsem=$c->prepare("SELECT * FROM TB_PAIE INNER JOIN PAIE ON TB_PAIE.ID_PAIE=PAIE.ID_PAIE WHERE (PAIE.MATRICULE=?) AND (DATE_PAIE=?)");
	                $reqsem->EXECUTE(array($_GET['Matricule'],date("m").date("Y")));
	                $paie=$reqsem->fetch();
	                $reqabt=$c->prepare("SELECT COUNT(*) AS CNT FROM ABSENTER WHERE (MATRICULE=?) AND (CODE_AB<>'PR') AND (to_char(DATE_AB, 'MM-YYYY')=?)");
					$reqabt->EXECUTE(array($_GET['Matricule'],date("m")."-".date("Y")));
					$totale=$reqabt->fetch();

					$reqsem=$c->prepare("SELECT DATE_INI-DATE_REC FROM TB_VARS,APPRENTI WHERE MATRICULE=?");
	                	$reqsem->EXECUTE(array($_GET['Matricule']));
	                	$rowsem=$reqsem->fetch();
	                	$sem= (int)($rowsem['DATE_INI-DATE_REC']/180);
?>
		<page backtop='30mm' backbottom='20mm' backleft='8mm' backright='8mm'>
			<page_header>
				<table style="width: 100%;">
					<tr>
						<td style="width: 10%;"><img src="img/logo.png" style="width: 100%;height: 15%;"></td>
						<td style="width: 25%;"></td>
						<td style="width: 65%;"><h1 style="font-size: 40px;">SONATRACH</h1></td>
					</tr>
				</table>
			</page_header>
			<table style="width: 100%;">
				<tr>
					<td style="width: 60%;"></td>
					<td style="width: 25%;"><h3><?php echo 'Mois : '.date('M'); ?></h3></td>
					<td><h3><?php echo 'Annee : '.date('Y'); ?></h3></td>
				</tr>
			</table>
			<h1 style="text-align: center;font-size: 30px">BULLETIN DE PAIE</h1>
			<table class="thmat">
				<tr>
					<th>Matricule</th>
					<th>Nom, Prenom</th>
					<th>Date d'entrer</th>
					<th>Date de naissance</th>
					<th>Fonction</th>	
				</tr>
				<tr>
					<td><?php echo $info['MATRICULE']; ?></td>
					<td><?php echo $info['NOM'].', '.$info['PRENOM']; ?></td>
					<td><?php echo $info['DATE_REC']; ?></td>
					<td><?php echo $info['DATE_N']; ?></td>
					<td><?php echo $fon['NOM_FONCTION']; ?></td>	
				</tr>
			</table>
			<table class="thmat2" cellspacing="0">
				<tr>
					<th colspan="2">Rubriaue</th>
					<th rowspan="2">Jours</th>
					<th rowspan="2" style="width: 13%;">Taux</th>
					<th colspan="2">Gain</th>
					<th colspan="2">Retenue</th>
				</tr>
				<tr>
					<th style="width: 5%;">Code</th>
					<th style="width: 20%;">Designation</th>
					<th style="width: 12%;">Imposable</th>
					<th style="width: 15%;">Non Imposable</th>
					<th style="width: 15%;">Imposable</th>
					<th style="width: 16%;">Non Imposable</th>
				</tr>
					<?php 
						$absreq=$c->prepare("SELECT * FROM ABSENTER WHERE (MATRICULE=?) AND CODE_AB<>'PR' AND (to_char(DATE_AB, 'MM-YYYY')=?)");
						$absreq->EXECUTE(array($_GET['Matricule'],date("m")."-".date("Y")));
						$tdtt=0;$tdtt2=1;
						$ar[0]='ok';
							while ($rowabs=$absreq->fetch()) {
							$varabs=$c->prepare("SELECT * FROM ABSENCE WHERE CODE_AB=?");
							$varabs->EXECUTE(array($rowabs['CODE_AB']));
							$vaerbbs=$varabs->fetch();
							$varabs1=$c->prepare("SELECT COUNT(*) AS CNTT FROM ABSENTER WHERE (MATRICULE=?) AND (CODE_AB=?)");
							$varabs1->EXECUTE(array($_GET['Matricule'],$rowabs['CODE_AB']));
							$vaerbbs1=$varabs1->fetch();
							if (!in_array($vaerbbs['CODE_AB'], $ar)) {
								$tdtt++;
							?>
						<tr class="tdstyle" style="outline: thin solid;">	
							<td style="height: 5%;"><?php echo $vaerbbs['CODE_AB'].'<br>'; ?></td>
							<td style="height: 5%;"><?php echo $vaerbbs['JUSTIFICATION'].'<br>'; ?></td>
							<td style="height: 5%;"><?php echo $vaerbbs1['CNTT']; ?></td>
							<td style="height: 5%;"><?php echo $paie['GAINBASE']/30*$vaerbbs1['CNTT'].',00 DA'; ?></td>
							<?php 
							$ar[$tdtt2]=$vaerbbs['CODE_AB'];
							if ($tstvar!=='ok') {
								?>
								<td><?php echo $paie['GAINNET']; ?></td>
								<?php
								$tstvar='ok';
							}else{
								?>
									<td style="height: 5%"></td>
								<?php
							}
							?>
							<td style="height: 5%"></td>
							<td style="height: 5%"></td>
							<?php 
							if ($tstvar2!=='ok') {
								?>
								<td><?php echo $paie['GAINRETENUE']; ?></td>
								<?php
								$tstvar2='ok';
							}else{
								?>
									<td style="height: 5%"></td>
								<?php
							}
							?>
						</tr>
							<?php 
							}
								$tdtt2++;
							} 
							$pct=100-($tdtt*10);
							?>
							<tr>
								<td style="height: <?php echo $pct; ?>%;border-top:none;"></td>
								<td style="height: <?php echo $pct; ?>%;border-top:none;"></td>
								<td style="height: <?php echo $pct; ?>%;border-top:none;"></td>
								<td style="height: <?php echo $pct; ?>%;border-top:none;"></td>
								<td style="height: <?php echo $pct; ?>%;border-top:none;"></td>
								<td style="height: <?php echo $pct; ?>%;border-top:none;"></td>
								<td style="height: <?php echo $pct; ?>%;border-top:none;"></td>
								<td style="height: <?php echo $pct; ?>%;border-top:none;"></td>
							</tr>
						<tr style="position: fixed;">
							<th colspan="2"></th>
							<th>Total</th>
							<th>Total</th>
							<th>Net</th>
							<th></th>
							<th></th>
							<th>Retenue</th>
						</tr>
						<tr>
							<td colspan="2"></td>
							<td><?php echo $totale['CNT']; ?></td>
							<td><?php echo $paie['GAINRETENUE']; ?></td>
							<td><?php echo $paie['GAINNET']; ?></td>
							<td></td>
							<td></td>
							<td><?php echo $paie['GAINRETENUE']; ?></td>
						</tr>
			</table>
			<page_footer>

			</page_footer>
		</page>
<?php
$content=ob_get_clean();
$html2pdf = new Html2Pdf('L','A4','fr');
$html2pdf->writeHTML($content);
$html2pdf->output($_GET['Matricule'].'.pdf');

?>