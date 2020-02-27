<?php
/**
*
* @package phpBB Extension - DrDeath F1WebTip
* @copyright (c) 2020 Dr.Death - www.lpi-clan.de
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace drdeath\f1webtip\migrations\v11x;

class release_1_1_3 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['drdeath_f1webtip_version']) && version_compare($this->config['drdeath_f1webtip_version'], '1.1.3', '>=');
	}

	static public function depends_on()
	{
		return array('\drdeath\f1webtip\migrations\v10x\release_1_0_0');
	}

	public function update_data()
	{
		return array(
			// Set the current version
			array('config.update', array('drdeath_f1webtip_version', '1.1.3')),
		);
	}
}
