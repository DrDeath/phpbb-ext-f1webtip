<?php

/**
*
* @package phpBB Extension - DrDeath F1WebTip
* @copyright (c) 2014 Dr.Death - www.lpi-clan.de
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace drdeath\f1webtip;

class ext extends \phpbb\extension\base
{
	/**
	 * Check the phpBB version to determine if this extension can be enabled.
	 * F1WebTipp drops support for phpBB 3.1.x
	 * Supports only phpBB version >= 3.2.8 and < 3.4
	 * @return boolean
	 */
	public function is_enableable()
	{
		$config = $this->container->get('config');
		return phpbb_version_compare($config['version'], '3.2.8', '>=') && phpbb_version_compare($config['version'], '3.4', '<');
	}
}
