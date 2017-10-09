<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Settings\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Ajax extends Controller_Module
{
	public function humans()
	{
		return $this->config->nf_humans_txt;
	}

	public function robots()
	{
		return $this->config->nf_robots_txt;
	}

	public function debugbar()
	{
		if ($tab = post('tab'))
		{
			$this->session->set('debugbar', 'tab', $tab);
		}
		else if ($tab === '')
		{
			$this->session->destroy('debugbar', 'tab');
		}
		else if ($height = (int)post('height'))
		{
			$this->session->set('debugbar', 'height', $height);
		}
	}
}
