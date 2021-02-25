<?php
/**
 * Formula 1 WebTip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2014, Dr.Death, http://www.lpi-clan.de
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace drdeath\f1webtip;

class ext extends \phpbb\extension\base
{
    /**
     * Check the phpBB version to determine if this extension can be enabled.
     * F1WebTipp drops support for phpBB 3.1.x
     * Supports only phpBB version >= 3.2.8 and < 3.4.
     *
     * @return bool
     */
    public function is_enableable()
    {
        $config = $this->container->get('config');

        return phpbb_version_compare($config['version'], '3.2.8', '>=') && phpbb_version_compare($config['version'], '3.4', '<');
    }
}
