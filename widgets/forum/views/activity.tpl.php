<p><?php echo $this->lang('there_are', $count = ($users = count($data['users'])) + $data['visitors'], $count); ?></p>
<?php echo implode(', ', array_map(function($a){ return $this->user->link($a['user_id'], $a['username']); }, $data['users'])).' '.($users ? $this->lang('and') : '').' '.$this->lang('guests', $data['visitors'], $data['visitors']); ?>
