<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Fields;

class Null_
{
<<<<<<< HEAD
=======
	public function init($field)
	{
		$field->default(NULL);
	}

>>>>>>> upstream/dev
	public function raw($value)
	{
		if (!empty($value))
		{
			return $value;
		}
	}
}
