<?php 
	if (SESSION_START()) {
    session_destroy();
}
header("LOCATION:index.php");
?>