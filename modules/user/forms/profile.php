<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$this	->rule($this->form_text('first_name')
					->title('Prénom')
		)
		->rule($this->form_text('last_name')
					->title('Nom')
		)
		->rule($this->form_file('avatar')
					->title('Avatar')
		)
		->rule($this->form_date('date_of_birth')
					->title('Date de naissance')
		)
		->rule($this->form_radio('sex')
					->title('Sexe')
					->data([
						'female' => 'Femme',
						'male'   => 'Homme'
					])
		)
		->rule($this->form_text('location')
					->title('Localisation')
		)
		->rule($this->form_url('website')
					->title('Site web')
		)
		->rule($this->form_text('quote')
					->title('Citation')
		)
		->rule($this->form_bbcode('signature')
					->title('Signature')
		);
