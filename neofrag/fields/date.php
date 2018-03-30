<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Fields;

<<<<<<< HEAD
class Date extends Field_DateTime
=======
class Date extends DateTime
>>>>>>> upstream/dev
{
	public function raw($value)
	{
		return substr($value->sql(), 0, 10);
	}
}
