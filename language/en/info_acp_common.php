<?php
/**
*
* @package phpBB Extension - DrDeath F1WebTip
* @copyright (c) 2014 Dr.Death - www.lpi-clan.de
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//
$lang = array_merge($lang, [
	'ACP_F1WEBTIP_TITLE'			=> 'F1 WebTip Module',
	'ACP_FORMEL_DRIVERS'			=> 'F1 Drivers',
	'ACP_FORMEL_RACES'				=> 'F1 Races',
	'ACP_FORMEL_SETTINGS'			=> 'F1 Settings',
	'ACP_FORMEL_TEAMS'				=> 'F1 Teams',
	'LOG_FORMEL_CRON'				=> 'Formula 1 Cronjob was executed.',
	'LOG_FORMEL_DRIVER_ADDED'		=> 'Formula 1 driver %s added.',
	'LOG_FORMEL_DRIVER_DELETED'		=> 'Formula 1 driver %s deleted.',
	'LOG_FORMEL_DRIVER_EDITED'		=> 'Formula 1 driver %s edited.',
	'LOG_FORMEL_QUALI_ADDED'		=> 'Formula 1 qualifying result for race %s added.',
	'LOG_FORMEL_QUALI_DELETED'		=> 'Formula 1 qualifying result for race %s deleted.',
	'LOG_FORMEL_QUALI_NOT_VALID'	=> 'Formula 1 qualifying result for race %s not valid. Entry rejected.',
	'LOG_FORMEL_RACE_ADDED'			=> 'Formula 1 race %s added.',
	'LOG_FORMEL_RACE_DELETED'		=> 'Formula 1 race %s deleted',
	'LOG_FORMEL_RACE_EDITED'		=> 'Formula 1 race %s edited.',
	'LOG_FORMEL_RESULT_ADDED'		=> 'Formula 1 race result for race %s added.',
	'LOG_FORMEL_RESULT_DELETED'		=> 'Formula 1 race result for race %s deleted.',
	'LOG_FORMEL_RESULT_NOT_VALID'	=> 'Formula 1 race result for race %s not valid. Entry rejected.',
	'LOG_FORMEL_SAISON_RESET'		=> 'Formula 1 saison reseted.',
	'LOG_FORMEL_SETTINGS'			=> 'Formula 1 settings updated.',
	'LOG_FORMEL_TEAM_ADDED'			=> 'Formula 1 team %s added.',
	'LOG_FORMEL_TEAM_DELETED'		=> 'Formula 1 team %s deleted.',
	'LOG_FORMEL_TEAM_EDITED'		=> 'Formula 1 team %s edited.',
	'LOG_FORMEL_TIP_DELETED'		=> 'Formula 1 Webtip for race %s deleted.',
	'LOG_FORMEL_TIP_EDITED'			=> 'Formula 1 Webtip for race %s edited.',
	'LOG_FORMEL_TIP_GIVEN'			=> 'Formula 1 Webtip for race %s added.',
	'LOG_FORMEL_TIP_NOT_VALID'		=> 'Formula 1 Webtip for race %s not valid. Tip rejected.',

]);
