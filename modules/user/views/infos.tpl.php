<<<<<<< HEAD
<ul class="list-inline no-margin">
	<li class="col-md-3">
		<b><?php echo $this->lang('Inscrit depuis le') ?></b><br />
		<?php echo time_span($registration_date) ?>
	</li>
	<li class="col-md-3">
		<b><?php echo $this->lang('Dernière activité') ?></b><br />
		<?php echo time_span($last_activity_date) ?>
	</li>
	<li class="col-md-6">
		<b><?php echo $this->lang('Groupes') ?></b><br />
		<?php echo $groups ?>
	</li>
</ul>
=======
<div class="row">
	<div class="col-4">
		<b><?php echo $this->lang('Inscrit depuis le') ?></b><br />
		<?php echo $user->registration_date ?>
	</div>
	<div class="col-4">
		<b><?php echo $this->lang('Dernière activité') ?></b><br />
		<?php echo $user->last_activity_date ?>
	</div>
	<div class="col-4">
		<b><?php echo $this->lang('Groupes') ?></b><br />
		<?php echo $user->groups() ?>
	</div>
</div>
>>>>>>> upstream/dev
