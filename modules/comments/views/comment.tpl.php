<div class="media<?php if ($comment->parent()) echo ' comments-child' ?>">
	<?php echo $comment->user->avatar() ?>
	<div class="media-body">
		<?php
			$actions = [];

			if ($this->user() && !$comment->parent())
			{
<<<<<<< HEAD
				$actions[] = '<a class="btn btn-link" href="#" data-comment-id="'.$comment->id.'">'.icon('fa-mail-reply').' '.$this->lang('Répondre').'</a>';
=======
				$actions[] = '<li><a class="btn btn-link btn-sm" href="#" data-comment-id="'.$comment->id.'">'.icon('fa-mail-reply').' '.$this->lang('Répondre').'</a></li>';
>>>>>>> upstream/dev
			}

			if ($this->user->admin || ($this->user() && $this->user->id == $comment->user))
			{
<<<<<<< HEAD
				$actions[] = $this->button_delete('ajax/comments/delete/'.$comment->id)->compact();
=======
				$actions[] = '<li>'.$this->button_delete('ajax/comments/delete/'.$comment->id)->compact().'</li>';
>>>>>>> upstream/dev
			}

			if ($actions)
			{
<<<<<<< HEAD
				echo '<div class="action">'.implode($actions).'</div>';
=======
				echo '<ul class="list-right">'.implode($actions).'</ul>';
>>>>>>> upstream/dev
			}
		?>
		<h6>
			<?php echo $comment->user() ? $comment->user->link() : $this->lang('Visiteur') ?>
			<small><?php echo icon('fa-clock-o').' '.$comment->date ?></small>
		</h6>
		<?php echo $comment->content ? strtolink(nl2br($comment->content), TRUE) : '<i>'.$this->lang('Message supprimé').'</i>' ?>
	</div>
</div>
