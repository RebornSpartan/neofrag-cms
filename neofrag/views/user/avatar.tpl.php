<?php
	$avatar = $user->profile()->avatar ? $user->profile()->avatar->path() : image($user->profile()->sex == 'female' ? 'default_avatar_female.jpg' : 'default_avatar_male.jpg');
?>
<div class="avatar">
<?php if ($user->id): ?>
	<a href="<?php echo url('user/'.$user->id.'/'.url_title($user->username)) ?>">
<<<<<<< HEAD
		<img class="img-responsive" src="<?php echo $avatar ?>" alt="" />
	</a>
<?php else: ?>
	<img class="img-responsive" src="<?php echo $avatar ?>" alt="" />
=======
		<img class="img-fluid" src="<?php echo $avatar ?>" alt="" />
	</a>
<?php else: ?>
	<img class="img-fluid" src="<?php echo $avatar ?>" alt="" />
>>>>>>> upstream/dev
<?php endif ?>
</div>
