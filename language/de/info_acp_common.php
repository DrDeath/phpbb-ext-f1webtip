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
	$lang = array();
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
$lang = array_merge($lang, array(
	'ACP_F1WEBTIP_TITLE'			=> 'F1 WebTipp Module',
	'ACP_FORMEL_DRIVERS'			=> 'F1 Fahrer',
	'ACP_FORMEL_RACES'				=> 'F1 Rennen',
	'ACP_FORMEL_SETTINGS'			=> 'F1 Einstellungen',
	'ACP_FORMEL_TEAMS'				=> 'F1 Teams',
	'LOG_FORMEL_CRON'				=> 'F1 WebTipp Cronjob wurde ausgeführt.',
	'LOG_FORMEL_DRIVER_ADDED'		=> 'F1 WebTipp Fahrer %s hinzugefügt',
	'LOG_FORMEL_DRIVER_DELETED'		=> 'F1 WebTipp Fahrer %s gelöscht',
	'LOG_FORMEL_DRIVER_EDITED'		=> 'F1 WebTipp Fahrer %s bearbeitet',
	'LOG_FORMEL_QUALI_ADDED'		=> 'F1 WebTipp Qualifying Ergebnis für Rennen %s eingetragen',
	'LOG_FORMEL_QUALI_DELETED'		=> 'F1 WebTipp Qualifying Ergebnis für Rennen %s gelöscht',
	'LOG_FORMEL_QUALI_NOT_VALID'	=> 'F1 WebTipp Qualifying Ergebnis für Rennen %s ungültig. Eintragung wurde abgewiesen',
	'LOG_FORMEL_RACE_ADDED'			=> 'F1 WebTipp Rennen %s hinzugefügt',
	'LOG_FORMEL_RACE_DELETED'		=> 'F1 WebTipp Rennen %s gelöscht',
	'LOG_FORMEL_RACE_EDITED'		=> 'F1 WebTipp Rennen %s bearbeitet',
	'LOG_FORMEL_RESULT_ADDED'		=> 'F1 WebTipp Rennergebnis für Rennen %s eingetragen',
	'LOG_FORMEL_RESULT_DELETED'		=> 'F1 WebTipp Rennergebnis für Rennen %s gelöscht',
	'LOG_FORMEL_RESULT_NOT_VALID'	=> 'F1 WebTipp Rennergebnis für Rennen %s ungültig. Eintragung wurde abgewiesen',
	'LOG_FORMEL_SAISON_RESET'		=> 'F1 WebTipp Saison zurückgesetzt.',
	'LOG_FORMEL_SETTINGS'			=> 'F1 WebTipp Einstellungen aktualisiert',
	'LOG_FORMEL_TEAM_ADDED'			=> 'F1 WebTipp Team %s hinzugefügt',
	'LOG_FORMEL_TEAM_DELETED'		=> 'F1 WebTipp Team %s gelöscht',
	'LOG_FORMEL_TEAM_EDITED'		=> 'F1 WebTipp Team %s bearbeitet',
	'LOG_FORMEL_TIP_DELETED'		=> 'F1 WebTipp für Rennen %s gelöscht',
	'LOG_FORMEL_TIP_EDITED'			=> 'F1 WebTipp für Rennen %s bearbeitet',
	'LOG_FORMEL_TIP_GIVEN'			=> 'F1 WebTipp für Rennen %s abgegeben',
	'LOG_FORMEL_TIP_NOT_VALID'		=> 'F1 WebTipp für Rennen %s ungültig. Tipp wurde abgewiesen',

));
