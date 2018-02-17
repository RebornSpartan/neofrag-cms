<div class="row justify-content-md-center authenticators">
	<?php foreach ($authenticators as $authenticator): ?>
	<div class="col col-sm-6 col-md-4 col-lg-3">
		<img class="img-fluid" src="<?php echo image('authenticators/'.$authenticator->info()->name.'.png') ?>" alt="">
		<a class="btn btn-primary" href="<?php echo url('user/auth/'.url_title($authenticator->info()->name)) ?>"><?php echo $authenticator->info()->title ?></a>
	</div>
	<?php endforeach ?>
</div>
