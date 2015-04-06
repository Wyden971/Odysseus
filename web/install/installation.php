<!DOCTYPE html>
<html lang="fr">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	
		<title>Installation</title>
	
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
						<div class="row form-group">
							<div class="col-xs-12">
								<ul class="nav nav-pills nav-justified thumbnail setup-panel">
									<li class="active"><a href="#step-1">
										<h4 class="list-group-item-heading">ÉTAPE 1</h4>
										<p class="list-group-item-text">Informations générales</p>
									</a></li>
									<li class="disabled"><a href="#step-2">
										<h4 class="list-group-item-heading">ÉTAPE 2</h4>
										<p class="list-group-item-text">Base de données</p>
									</a></li>
								</ul>
							</div>
						</div>
						<form class="form-horizontal" method="POST" action="installation_confirm.php" enctype="multipart/form-data">
							<!-- ETAPE 1 -->
							<div class="row setup-content" id="step-1">
								<div class="col-lg-12">
										<div class="titleHeader clearfix">
											<h3>Informations générales</h3>
										</div>
										<div class="form-group">
											<label for="admuser" class="col-sm-3 control-label">Nom d'utilisateur</label>
											<div class="col-sm-9">
												<input type="text" class="form-control" id="admuser" name="admuser" placeholder="Entrez le nom d'utilisateur">
											</div>
										</div>
										<div class="form-group">
											<label for="admmail" class="col-sm-3 control-label">E-mail *</label>
											<div class="col-sm-9">
												<input type="email" class="form-control" id="admmail" name="admmail" placeholder="Entrez l'email de l'administateur" required>
											</div>
										</div>
										<div class="form-group">
											<label for="admpass" class="col-sm-3 control-label">Mot de passe *</label>
											<div class="col-sm-9">
												<p style="margin: 7px 0px 5px">admin <span style="color:#666; font-style:italic">(Vous pourrez modifier ce mot de passe depuis l'adminstration)</span></p>
											</div>
										</div>
										<div class="form-group">
											<label for="theme" class="col-sm-3 control-label">Couleur du thème *</label>
											<div class="col-sm-9">
												<select class="form-control" id="theme" name="theme" required>
														<option value="Vert" selected>Vert (Par défaut)</option>
														<option>Rouge</option>
														<option>Bleu</option>
														<option>Violet</option>
														<option>Or</option>
												</select>
											</div>
										</div>
										<button type="button" id="activate-step-2" class="btn btn-greend btn-long pull-right">Passer à l'étape suivante</button>
								</div>
							</div>
							<!-- ETAPE 2 -->
							<div class="row setup-content" id="step-2">
								<div class="col-lg-12">
										<div class="titleHeader clearfix">
											<h3>Informations de la base de données</h3>
										</div>
										<div class="form-group">
											<label for="host" class="col-sm-3 control-label">Hôte *</label>
											<div class="col-sm-9">
												<input type="text" class="form-control" id="host" name="host" placeholder="Entrez l'adresse hôte de la BDD">
											</div>
										</div>
										<div class="form-group">
											<label for="user" class="col-sm-3 control-label">Utilisateur *</label>
											<div class="col-sm-9">
												<input type="text" class="form-control" id="user" name="user" placeholder="Entrez l'utilisateur de la BDD" required>
											</div>
										</div>
										<div class="form-group">
											<label for="pass" class="col-sm-3 control-label">Mot de passe *</label>
											<div class="col-sm-9">
												<input type="password" class="form-control" id="pass" name="pass" placeholder="Entrez le mot de passe" required>
											</div>
										</div>
										<div class="form-group">
											<label for="dbname" class="col-sm-3 control-label">Base de donnée à utiliser *</label>
											<div class="col-sm-9">
												<input type="text" class="form-control" id="dbname" name="dbname" placeholder="Entrez la base de données à utiliser" required>
											</div>
										</div>
										<button type="submit" id="activate-step-3" class="btn btn-greend btn-long pull-right">Créer le site web</button>
								</div>
							</div>
						</form>
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
