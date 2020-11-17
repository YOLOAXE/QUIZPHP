<section class="page-section" id="Connection">
	<div class="container">
		<h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Connexion</h2>
		<div class="divider-custom">
			<div class="divider-custom-line"></div>
			<div class="divider-custom-icon">
				<i class="fas fa-star"></i>
			</div>
			<div class="divider-custom-line"></div>
		</div>
		<div class="row">
			<div class="col-lg-8 mx-auto">
				<?php
					echo form_open('TeacherManager/teacherConnection', 'novalidate="novalidate"');
				?>
					<div class="control-group">
						<div class="form-group floating-label-form-group controls mb-0 pb-2">
							<label>Prenom</label>
							<?php
								$attributs = array('class' => 'form-control',
												   'id' => 'prenom',
								 				   'name' => 'prenom',
												   'placeholder' => 'Prenom',
											       'required' => 'required', 
								                   'value' => set_value('prenom'));
			 					echo form_input($attributs);
			 					echo form_error('prenom','<small id="passwordHelp" class="text-danger">','</small>');
							?>
							<p class="help-block text-danger"></p>
						</div>
					</div>
					<div class="control-group">
						<div class="form-group floating-label-form-group controls mb-0 pb-2">
							<label>Nom</label>
							<?php
								$attributs = array('class' => 'form-control',
												   'id' => 'nom',
								 				   'name' => 'nom',
												   'placeholder' => 'Nom',
											       'required' => 'required', 
								                   'value' => set_value('nom'));
			 					echo form_input($attributs);
			 					echo form_error('nom','<small id="passwordHelp" class="text-danger">','</small>');
							?>
							<p class="help-block text-danger"></p>
						</div>
					</div>
					<div class="control-group">
						<div class="form-group floating-label-form-group controls mb-0 pb-2">
							<label>Mot de passe</label>
							<?php
								$attributs = array('class' => 'form-control',
												   'id' => 'pass',
												   'name' => 'password',
												   'placeholder' => 'Mot de passe',
												   'required' => 'required');
								echo form_password($attributs);
								echo form_error('password','<small id="passwordHelp" class="text-danger">','</small>');
							?>
							<p class="help-block text-danger"></p>
						</div>
					</div>
					<br />
					<div class="form-group">
						<?php
							echo form_submit('envoyer', 'Connexion', 'class="btn btn-primary btn-xl"');
						?>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>
