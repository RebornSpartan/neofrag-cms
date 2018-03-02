<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Panels;

use NF\NeoFrag\Libraries\Panel;

class Pagination extends Panel
{
	public function __toString()
	{
		if ($pagination = $this->output->module()->pagination->get_pagination())
		{
			return $pagination.'<div class="clearfix"></div>';
		}

		return '';
	}
}
