<?php foreach ($news as $news): ?>
<div class="media">
	<div class="media-left">
		<?php echo NeoFrag()->model2('user', $news['user_id'])->avatar() ?>
	</div>
	<div class="media-body">
		<h4 class="media-heading"><a href="<?php echo url('news/'.$news['news_id'].'/'.url_title($news['title'])) ?>"><?php echo $news['title'] ?></a></h4>
		<?php echo icon('fa-clock-o').' '.time_span($news['date']) ?>
		<?php if (($comments = $this->module('comments')) && $comments->is_enabled()): ?>
			<li><?php echo $comments->link('news', $news['news_id'], 'news/'.$news['news_id'].'/'.url_title($title)) ?></li>
		<?php endif ?>
	</div>
</div>
<?php endforeach ?>
