<<<<<<< HEAD
<img class="img-responsive" src="<?php echo NeoFrag()->model2('file', $file_id)->path() ?>" alt="" />
=======
<img class="img-fluid" src="<?php echo NeoFrag()->model2('file', $file_id)->path() ?>" alt="" />
>>>>>>> upstream/dev
<?php if (!empty($vignettes)): ?>
<div class="panel-body">
	<div class="row">
		<?php foreach ($vignettes as $vignette): ?>
		<div class="col-md-2">
			<a class="thumbnail<?php echo ($image_id == $vignette['image_id']) ? ' active' : ''  ?>" href="<?php echo url('gallery/image/'.$vignette['image_id'].'/'.url_title($vignette['title'])) ?>">
<<<<<<< HEAD
				<img class="img-responsive" src="<?php echo NeoFrag()->model2('file', $vignette['thumbnail_file_id'])->path() ?>" alt="" />
=======
				<img class="img-fluid" src="<?php echo NeoFrag()->model2('file', $vignette['thumbnail_file_id'])->path() ?>" alt="" />
>>>>>>> upstream/dev
			</a>
		</div>
		<?php endforeach ?>
	</div>
</div>
<?php endif ?>
