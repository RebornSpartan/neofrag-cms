<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Navigation\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($settings = [])
	{
		return $this->js('navigation')
					->panel()
					->body($this->view('horizontal', $settings), FALSE);
	}

	public function vertical($settings = [])
	{
		return $this->js('navigation')
					->view('vertical', $settings);
	}
}
