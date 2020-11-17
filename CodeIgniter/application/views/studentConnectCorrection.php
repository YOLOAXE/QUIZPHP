<section class="page-section" id="SCConnection">
	<div class="container">
		<h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Quizz Correction</h2>
		<div class="divider-custom">
			<div class="divider-custom-line"></div>
			<div class="divider-custom-icon">
				<i class="fas fa-star"></i>
			</div>
			<div class="divider-custom-line"></div>
		</div>
		<div class="row">
			<div class="col-lg-8 mx-auto">
			<?php echo form_open('StudentManager/studentConnectionCorrectionPage') ?>
				<div class="control-group">
                    <div class="form-group floating-label-form-group controls mb-0 pb-2">
                        <label>Clé</label>
                        <?php
                            $attributs = array('class' => 'form-control',
                                                'id' => 'key',
                                                'name' => 'key',
                                                'placeholder' => 'Clé',
                                                'required' => 'required');
                            echo form_password($attributs);
                            echo form_error('key','<small id="passwordHelp" class="text-danger">','</small>');
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
