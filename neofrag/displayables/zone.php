<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Displayables;

use NF\NeoFrag\Displayable;

class Zone extends Displayable
{
	public function __invoke($disposition_id, $output, $page, $zone_id)
	{
		if ($live_editor = $this->output->live_editor())
		{
			if ($live_editor & \NF\NeoFrag\Core\Output::ZONES)
			{
				$output = '	<div class="pull-right">
								'.($page == '*' ? '<button type="button" class="btn btn-link live-editor-fork" data-enabled="0">'.icon('fa-toggle-off').' '.$this->lang('Disposition commune').'</button>' : '<button type="button" class="btn btn-link live-editor-fork" data-enabled="1">'.icon('fa-toggle-on').' '.$this->lang('Disposition spécifique à la page').'</button>').'
							</div>
							<h3>'.(!empty($this->output->theme()->info()->zones[$zone_id]) ? $this->output->theme()->info()->zones[$zone_id] : $this->lang('Zone #%d', $zone_id)).' <div class="btn-group"><button type="button" class="btn btn-xs btn-success live-editor-add-row" data-toggle="tooltip" data-container="body" title="'.$this->lang('Nouveau Row').'">'.icon('fa-plus').'</button></div></h3>'.
							$output;
			}

			$output = '<div'.($live_editor & \NF\NeoFrag\Core\Output::ZONES ? ' class="live-editor-zone"' : '').' data-disposition-id="'.$disposition_id.'">'.$output.'</div>';
		}

		return $output;
	}
}
