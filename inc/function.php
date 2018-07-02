<?php
function calcul($s,$mat,$c){
	global $gainsbaser,$retenue,$paie;
	$strreq="SELECT SMG,T_S".$s." FROM TB_VARS";
	$gainsbase=$c->prepare("SELECT SMG,T_S".$s." FROM TB_VARS");
    $gainsbase->EXECUTE();
    $gainsbaser=$gainsbase->fetch();
    $gainsbaser=$gainsbaser['SMG']*$gainsbaser['T_S'.$s]/100;
    $retenue=$c->prepare("SELECT COUNT(*) AS CNT FROM ABSENTER WHERE (MATRICULE=?) AND CODE_AB<>'PR' AND (to_char(DATE_AB, 'MM-YYYY')=?)");
    date_default_timezone_set("UTC");
    $retenue->EXECUTE(array($mat,date("m")."-".date("Y")));
    $retenuer=$retenue->fetch();
    $retenue=$gainsbaser/30*$retenuer['CNT'];
    $paie=$gainsbaser-$retenue;
}
function getimg($info){
    $imgext=0; 
    if (file_exists("img/user/".$info.".jpg")) {
        $imgext=1;
    }if (file_exists("img/user/".$info.".jpeg")) {
        $imgext=2;
    }if (file_exists("img/user/".$info.".png")) {
        $imgext=3;
    }
    switch ($imgext) {
        case 1:
            $img="img/user/".$info.".jpg";
            break;
        case 2:
            $img="img/user/".$info.".jpeg";
            break;
        case 3:
            $img="img/user/".$info.".png";
            break;
        
        default:
            $img="img/user/user.png";
            break;
    }
    return $img;
}
function rand_color() {
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}