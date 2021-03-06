<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Navigation;

use NF\NeoFrag\Addons\Widget;

class Navigation extends Widget
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Navigation'),
			'description' => '',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
<<<<<<< HEAD
			'license'     => 'LGPLv3 <https://neofr.ag/license>'
=======
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.1.7'
			],
			'types'       => [
				'index'      => 'Horizontal',
				'vertical'   => 'Vertical'
			]
>>>>>>> upstream/dev
		];
	}
}
