</ul>
			</div>
			<div class="justify-content-end col-md-4">
				<ul class="navbar-nav pull-right">
					<li class="nav-item deconect"><a class="nav-link btn-outline-danger" href="logout.php"><i class="fa fa-power-off"></i> Deconnexion</a></li>
					<li class="nav-item col-md-2">
						<?php 
							if ($date_config=='auto') {
								?>
								<label class="pull-left" style="color: white;"><?php echo $_SESSION['date']; ?></label>
								<?php
							}else{
								?>
								<button class="btn btn-primary" data-toggle="modal" data-target="#modini"><?php echo $_SESSION['date']; ?></button>
								<?php
							}
						?>
					</li>
				</ul>
			</div>
		</div>
	</nav>
<div class="modal" id="modini">
	<div class="card">
		<div class="card-header">Modifier la date d'aujourdhui </div>
		<div class="card-body">
			<form method="post">
				<?php 
					if (isset($_POST['subdate'])) {
						if (!empty($_POST['date'])) {
							$reqini=$c->prepare("UPDATE TB_VARS SET DATE_INI = ?");
							$reqini->EXECUTE(array($_POST['date']));
							$reqinidt=$c->prepare("SELECT DATE_INI FROM TB_VARS");
							$reqinidt->EXECUTE();
							$row=$reqinidt->fetch();
							$_SESSION['date']=$row['DATE_INI'];
							echo "<meta http-equiv='refresh' content='0'>";
						}
					}
				?>
				<input class="form-control" type="text" name="date" placeholder="jj/mm/aaaa"><br>
				<button class="btn btn-outline-info" type="submit" name="subdate">Modifier</button>
				<button class="btn btn-outline-secondary" data-dismiss="modal">Annuler</button>
			</form>
		</div>
	</div>
</div>