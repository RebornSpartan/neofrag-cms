<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_statistics_m_statistics extends Model
{
	public function get_statistics($filters = NULL)
	{
		$statistics = [];
		$colors     = ['#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9', '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'];

		$i = 0;

		foreach ($this->addons->get_modules() as $module)
		{
			if ($controller = $module->controller('statistics'))
			{
				foreach ($controller->statistics() as $name => $statistic)
				{
					if ($filters === NULL || in_array($module->name.'-'.$name, $filters))
					{
						$statistics[$module->name.'-'.$name] = array_merge($statistic, [
							'title' => $module->lang($statistic['title'], NULL),
							'color' => $colors[$i % 10]
						]);
					}
					
					$i++;
				}
			}
		}

		return $statistics;
	}
}
