<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include 'Install.php';
$Install = new Install();

$fileyml = '..'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'parameters.yml';
$filesql = 'c0symfony.sql';

$test= $Install->selectDB();

if($test===true) {
	if(file_exists($filesql) && is_writable($filesql) && file_exists($fileyml) && is_writable($fileyml)) {
		$test2= $Install->installer($fileyml,$filesql);
		$msg= ($test2===true) ? 'Le site à été crée avec succès' : 'Une erreur est survenue lors de la création du site';
		$msg2= ($test2===true) ? '<p class="alert alert-success">Paramètrez dès maitenant votre site depuis l\'<a href="account_index.html">espace administateur</a>.</p>' : '<p class="alert alert-danger">Erreur lors de l\'application de la base de données. Veuillez réessayer en revenant <a href="installation.php">au formulaire</a>.</p>';
	}	
} else {
	$msg= 'Une erreur est survenue lors de la création du site';
	$msg2= '<p class="alert alert-danger">La base de données existe dèja. Veuillez réessayer en revenant <a href="installation.php">au formulaire</a>.</p>';
}

?>
<!DOCTYPE html>
<html lang="fr">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	
		<title>Installation - Confirmation</title>
	
		<!-- Bootstrap Core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
	
		<!-- Custom CSS -->
		<link href="css/slick.css" rel="stylesheet">
		<link href='css/fontface.css' rel='stylesheet'>
		<link href="css/custom-front.css" rel="stylesheet">
	
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	
	<body style="background:#333">
		<div class="container">
			<!-- Header -->
			<header style="margin: 20px 0px;">
				<div class="row">
					<!-- Logo -->
					<div class="col-lg-12">
						<img src="images/install_logo.png" alt="ODYSSEUS INSTALLATION" />
					</div>
			</header>
			<!-- /.Header -->
		
			<!-- Page Content -->
			<div style="background-color: #eee; padding: 15px">
				<div class="row">
					<!-- Commande -->
					<div class="col-lg-12">
						<div class="col-md-12 text-center">
							<h1 class="text-center" style="margin:10px 0px 15px"><?php echo $msg; ?></h1>
							<h4 class="text-center"><?php echo $msg2; ?></h4>
						</div>
					</div>
				</div>
			</div>
			<!-- /.page content -->

			<!-- Copyright -->
			<p style="margin:10px 0px; color:#eee; text-align:center; font-size:11px">© Odysseus 2014/2015 - Tout droits réservés</p>

		</div>
		<!-- /.container -->

		<!-- jQuery & Bootstrap Core JavaScript -->
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/slick.min.js"></script>
		<script src="js/custom.js"></script>
	</body>

</html>
