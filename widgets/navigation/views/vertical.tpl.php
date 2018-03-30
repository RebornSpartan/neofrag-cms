<ul class="nav flex-column">
<?php
	$actives = [];

	foreach ($links as list(,,, $url))
	{
		if (is_array($url))
		{
			foreach ($url as list(,,, $url))
			{
				if (strpos($this->url->request, ltrim(str_replace($this->url(), '', $this->url($url)), '/')) === 0)
				{
					$actives[] = $url;
				}
			}
		}
	}

	usort($actives, function($a, $b){
		return strlen($a) < strlen($b);
	});

	foreach ($links as list($title, $icon, $access, $url))
	{
		if (is_array($url))
		{
			$active = FALSE;

			$submenu = function($url) use ($actives, &$active){
				$result = '';

				foreach ($url as list($title, $icon, $access, $url))
				{
					if ($access)
					{
						$class = '';

						if ($actives && $actives[0] == $url)
						{
							$active = TRUE;
							$class  = ' class="active"';
						}

						$result .= '<li'.$class.'><a href="'.url($url).'">'.icon($icon).$this->lang($title).'</a></li>';
					}
				}

				return $result;
			};

			if ($submenu = $submenu($url))
			{
				echo '	<li'.($active ? ' class="active"' : '').'>
							<a data-toggle="collapse" href="#">'.icon($icon).' <span class="hidden-xs">'.$this->lang($title).'</span><span class="fa arrow"></span></a>
							<ul class="nav flex-column">'.$submenu.'</ul>
						</li>';
			}
		}
		else if ($access)
		{
			echo '<li'.($this->url->request == $url ? ' class="active"' : '').'><a href="'.url($url).'">'.icon($icon).'<span class="nav-label">'.$this->lang($title).'</span></a></li>';
		}
	}
?>
</ul>
