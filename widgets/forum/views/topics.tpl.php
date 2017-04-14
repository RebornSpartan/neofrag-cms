<?php foreach ($topics as $topic): ?>
<div class="media">
	<div class="media-left">
		<?php echo NeoFrag()->model2('user', $topic['user_id'])->avatar() ?>
	</div>
	<div class="media-body">
		<p class="media-heading"><a href="<?php echo url('forum/topic/'.$topic['topic_id'].'/'.url_title($topic['title'])) ?>"><?php echo $topic['title'] ?></a></p>
		<?php echo icon('fa-clock-o').' '.time_span($topic['date']).' '.icon('fa-comments-o').' '.$topic['count_messages'] ?></div>
</div>
<?php endforeach ?>
