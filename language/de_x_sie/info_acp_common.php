<?php
/**
 *
 * Formula 1 WebTip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2014, Dr.Death, http://www.lpi-clan.de
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
 * DO NOT CHANGE
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
	'ACP_F1WEBTIP_TITLE'			=> 'F1 WebTipp Module',
	'ACP_FORMEL_DRIVERS'			=> 'F1 Fahrer',
	'ACP_FORMEL_RACES'				=> 'F1 Rennen',
	'ACP_FORMEL_SETTINGS'			=> 'F1 Einstellungen',
	'ACP_FORMEL_TEAMS'				=> 'F1 Teams',
	'LOG_FORMEL_CRON'				=> 'Formel 1 Cronjob wurde ausgeführt.',
	'LOG_FORMEL_DRIVER_ADDED'		=> 'Formel 1 Fahrer %s hinzugefügt',
	'LOG_FORMEL_DRIVER_DELETED'		=> 'Formel 1 Fahrer %s gelöscht',
	'LOG_FORMEL_DRIVER_EDITED'		=> 'Formel 1 Fahrer %s bearbeitet',
	'LOG_FORMEL_QUALI_ADDED'		=> 'Formel 1 Qualifying Ergebnis für Rennen %s eingetragen',
	'LOG_FORMEL_QUALI_DELETED'		=> 'Formel 1 Qualifying Ergebnis für Rennen %s gelöscht',
	'LOG_FORMEL_QUALI_NOT_VALID'	=> 'Formel 1 Qualifying Ergebnis für Rennen %s ungültig. Eintragung wurde abgewiesen',
	'LOG_FORMEL_RACE_ADDED'			=> 'Formel 1 Rennen %s hinzugefügt',
	'LOG_FORMEL_RACE_DELETED'		=> 'Formel 1 Rennen %s gelöscht',
	'LOG_FORMEL_RACE_EDITED'		=> 'Formel 1 Rennen %s bearbeitet',
	'LOG_FORMEL_RESULT_ADDED'		=> 'Formel 1 Rennergebnis für Rennen %s eingetragen',
	'LOG_FORMEL_RESULT_DELETED'		=> 'Formel 1 Rennergebnis für Rennen %s gelöscht',
	'LOG_FORMEL_RESULT_NOT_VALID'	=> 'Formel 1 Rennergebnis für Rennen %s ungültig. Eintragung wurde abgewiesen',
	'LOG_FORMEL_SAISON_RESET'		=> 'Formel 1 Saison zurückgesetzt.',
	'LOG_FORMEL_SETTINGS'			=> 'Formel 1 Einstellungen aktualisiert',
	'LOG_FORMEL_TEAM_ADDED'			=> 'Formel 1 Team %s hinzugefügt',
	'LOG_FORMEL_TEAM_DELETED'		=> 'Formel 1 Team %s gelöscht',
	'LOG_FORMEL_TEAM_EDITED'		=> 'Formel 1 Team %s bearbeitet',
	'LOG_FORMEL_TIP_DELETED'		=> 'Formel 1 WebTipp für Rennen %s gelöscht',
	'LOG_FORMEL_TIP_EDITED'			=> 'Formel 1 WebTipp für Rennen %s bearbeitet',
	'LOG_FORMEL_TIP_GIVEN'			=> 'Formel 1 WebTipp für Rennen %s abgegeben',
	'LOG_FORMEL_TIP_NOT_VALID'		=> 'Formel 1 WebTipp für Rennen %s ungültig. Tipp wurde abgewiesen',

]);
