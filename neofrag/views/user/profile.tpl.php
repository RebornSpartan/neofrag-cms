<<<<<<< HEAD
<div class="media user-profile">
	<?php echo $user->avatar() ?>
	<div class="media-body">
		<h6 class="mt-0"><?php echo $user->link() ?></h6>
	</div>
=======
<div class="user-profile">
	<?php echo $user->avatar() ?>
	<h2><?php echo $user->username ?></h2>
	<?php echo $this->array
					->append_if($quote = $user->profile()->quote, '<i class="text-muted">'.$quote.'</i>')
					->append($user->profile()->first_name.' '.$user->profile()->last_name)
					->append($this	->array
									->append_if($sex = $user->profile()->sex, icon('fa-'.($sex == 'female' ? 'venus' : 'mars').' '.$sex))
									->append_if($user->profile()->date_of_birth, function($date_of_birth){
										return $this->lang('%d an|%d ans', $age = $date_of_birth->interval('today')->y, $age);
									})
									->__toString()
					)
					->append_if($user->profile()->location, function($location){
						return $this->label($location, 'fa-map-marker');
					})
					->filter()
					->each(function($a){
						return '<h3>'.$a.'</h3>';
					}) ?>
	<?php $socials = $this	->array([
								['website',   'fa-globe',     ''],
								['linkedin',  'fa-linkedin',  'https://www.linkedin.com/in/'],
								['github',    'fa-github',    'https://github.com/'],
								['instagram', 'fa-instagram', 'https://www.instagram.com/'],
								['twitch',    'fa-twitch',    'https://www.twitch.tv/']
							])
							->filter(function($a) use ($user){
								return $user->profile()->{$a[0]};
							})
							->each(function($a) use ($user){
								return '<a href="'.$a[2].$user->profile()->{$a[0]}.'" class="btn '.$a[0].'">'.icon($a[1]).'</a>';
							});
	?>
	<?php if (!$socials->empty()): ?><div class="socials"><?php echo $socials ?></div><?php endif ?>
	<?php if ($this->user() && $this->user != $user) echo $this->button()->title('Contacter')->icon('fa-envelope-o')->color('dark btn-block')->url('user/messages/compose/'.$user->url()) ?>
>>>>>>> upstream/dev
</div>
