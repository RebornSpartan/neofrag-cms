<div class="media popover-user">
	<div class="media-left">
		<?php echo $user->avatar() ?>
	</div>
	<div class="media-body">
		<h4 class="media-heading"><?php echo $user->profile()->first_name.' '.$user->profile()->last_name ?> <b><?php echo $user->profile()->username ?></b></h4>
		<?php //echo $user->groups() ?>
	</div>
</div>
