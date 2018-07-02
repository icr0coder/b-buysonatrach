<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	    <link rel="stylesheet" href="css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/style.css">
	    <link rel="stylesheet" href="css/font-awesome.min.css">
	    <script src="js/jquery.min.js"></script>
	    <script src="js/popper.min.js"></script>
	    <script src="js/bootstrap.min.js"></script>
	    <script src="js/chart.min.js"></script>
	    <title>Gestion de la paie</title>
	    <?php 
	    	    SESSION_START();
			    if (empty($_SESSION['username'])) {
			      header("LOCATION:index.php");
			    }
	    ?>
</head>