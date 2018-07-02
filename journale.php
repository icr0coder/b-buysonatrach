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
.thmat2{
	height: 40%;
	line-height: 10mm; 
	font-size: 16px;
	border-bottom: 1px;
}
	.thmat2 th{
		height: 20px;
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
.tdstyle td{
	border-top:none;
	border-bottom: none; 
	border-left:1px solid black; 
	border-right:1px solid black;
}
</style>
<?php 
include("inc/dbconnecte.php");
	$req=$c->prepare("SELECT * FROM APPRENTI WHERE ACTIF='O' AND (DATE_FINC >= SYSDATE)");
	$req->EXECUTE();
$ttbase=0;
$ttretenue=0;
$ttnet=0;
	$reqsem=$c->prepare("SELECT * FROM TB_PAIE INNER JOIN PAIE ON TB_PAIE.ID_PAIE=PAIE.ID_PAIE WHERE (TB_PAIE.DATE_PAIE=?)");
	$reqsem->EXECUTE(array(date("m").date("Y")));
	while ($paie=$reqsem->fetch()) {
		$ttbase=$ttbase+substr($paie['GAINBASE'], 0,strlen($paie['GAINBASE'])-6);
		$ttretenue=$ttretenue+substr($paie['GAINRETENUE'], 0,strlen($paie['GAINRETENUE'])-6);
		$ttnet=$ttnet+substr($paie['GAINNET'], 0,strlen($paie['GAINNET'])-6);
	}
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
			<h1 style="text-align: center;font-size: 30px">JOURNAL DE PAIE</h1>
			<table class="thmat2" cellspacing="">
				<tr>
					<th>Matricule</th>
					<th>Nom, Prenom</th>
					<th>Date de recrutement</th>
					<th>Date fin de contrat</th>
					<th>Salaire de base</th>
					<th>Retenue</th>
					<th>Net a payer</th>	
				</tr>
				<?php 
				$tdtt=0;
				while ($info=$req->fetch()) {
					$reqsem=$c->prepare("SELECT * FROM TB_PAIE INNER JOIN PAIE ON TB_PAIE.ID_PAIE=PAIE.ID_PAIE WHERE (PAIE.MATRICULE=?) AND (DATE_PAIE=?)");
					$reqsem->EXECUTE(array($info['MATRICULE'],date("m").date("Y")));
					$paie=$reqsem->fetch();
					?>
				<tr class="tdstyle">
					<td><?php echo $info['MATRICULE']; ?></td>
					<td style="width: 25%;"><?php echo $info['NOM'].', '.$info['PRENOM']; ?></td>
					<td><?php echo $info['DATE_REC']; ?></td>
					<td><?php echo $info['DATE_FINC']; ?></td>
					<td><?php echo $paie['GAINBASE']; ?></td>
					<td style="width: 12%;"><?php echo $paie['GAINRETENUE']; ?></td>
					<td style="width: 12%;"><?php echo $paie['GAINNET']; ?></td>	
				</tr>
					<?php
				$tdtt=$tdtt+1;
				}
				$pct=250-($tdtt*5);
				?>
				<tr>
					<td style="height: <?php echo $pct; ?>;border-top:none;"></td>
					<td style="height: <?php echo $pct; ?>;border-top:none;"></td>
					<td style="height: <?php echo $pct; ?>;border-top:none;"></td>
					<td style="height: <?php echo $pct; ?>;border-top:none;"></td>
					<td style="height: <?php echo $pct; ?>;border-top:none;"></td>
					<td style="height: <?php echo $pct; ?>;border-top:none;"></td>
					<td style="height: <?php echo $pct; ?>;border-top:none;"></td>
				</tr>
				<tr>
					<th colspan="4">TOTAUX</th>
					<th><?php echo $ttbase.',00 DA'; ?></th>
					<th><?php echo $ttretenue.',00 DA'; ?></th>
					<th><?php echo $ttnet.',00 DA'; ?></th>
				</tr>
			</table>
			
			<page_footer>

			</page_footer>
		</page>
<?php
$content=ob_get_clean();
$html2pdf = new Html2Pdf('L','A4','fr');
$html2pdf->writeHTML($content);
$html2pdf->output("journal_de_paie.pdf");

?>