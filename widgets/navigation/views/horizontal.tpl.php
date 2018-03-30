<div class="list-group">
<?php foreach ($links as $link): ?>
<<<<<<< HEAD
	<a class="list-group-item<?php if (strpos($this->url->request, $link['url'] ?: 'index') === 0) echo ' active' ?>" href="<?php echo (!preg_match('#^(https?:)?//#i', $link['url']) ? url() : '').$link['url'] ?>" target="<?php echo !empty($link['target']) ? $link['target'] : '_parent' ?>"><?php echo $link['title'] ?></a>
=======
	<a class="list-group-item<?php if (strpos($this->url->request, $link['url'] ?: 'index') === 0) echo ' active' ?>" href="<?php echo url($link['url']) ?>" target="<?php echo !empty($link['target']) ? $link['target'] : '_parent' ?>"><?php echo $link['title'] ?></a>
>>>>>>> upstream/dev
<?php endforeach ?>
</div>
