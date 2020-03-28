<?php
/**
 *
 * Formula 1 WebTip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2014, Dr.Death, http://www.lpi-clan.de
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace drdeath\f1webtip\acp;

class main_info
{
	function module()
	{
		return array(
			'filename'	=> '\drdeath\f1webtip\acp\main_module',
			'title'		=> 'ACP_F1_MANAGEMENT',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'settings'		=> array('title' => 'ACP_FORMEL_SETTINGS',	'auth' => 'ext_drdeath/f1webtip && acl_a_formel_settings',	'cat' => array('ACP_F1WEBTIP_TITLE')),
				'drivers'		=> array('title' => 'ACP_FORMEL_DRIVERS',	'auth' => 'ext_drdeath/f1webtip && acl_a_formel_drivers',	'cat' => array('ACP_F1WEBTIP_TITLE')),
				'teams'			=> array('title' => 'ACP_FORMEL_TEAMS',		'auth' => 'ext_drdeath/f1webtip && acl_a_formel_teams',		'cat' => array('ACP_F1WEBTIP_TITLE')),
				'races'			=> array('title' => 'ACP_FORMEL_RACES',		'auth' => 'ext_drdeath/f1webtip && acl_a_formel_races',		'cat' => array('ACP_F1WEBTIP_TITLE')),
			),
		);
	}
}
