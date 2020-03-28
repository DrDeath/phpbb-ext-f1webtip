<?php
/**
 *
 * Formula 1 WebTip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2014, Dr.Death, http://www.lpi-clan.de
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace drdeath\f1webtip\migrations\v11x;

class release_1_1_5 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['drdeath_f1webtip_version']) && version_compare($this->config['drdeath_f1webtip_version'], '1.1.5', '>=');
	}

	static public function depends_on()
	{
		return ['\drdeath\f1webtip\migrations\v10x\release_1_0_0'];
	}

	public function update_data()
	{
		return array(
			// Set the current version
			['config.update', ['drdeath_f1webtip_version', '1.1.5']],
		);
	}
}
