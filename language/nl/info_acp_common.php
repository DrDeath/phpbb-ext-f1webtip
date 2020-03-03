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
	'ACP_F1WEBTIP_TITLE'			=> 'F1 Voorspellingen Module',
	'ACP_FORMEL_DRIVERS'			=> 'F1 Coureurs',
	'ACP_FORMEL_RACES'				=> 'F1 Races',
	'ACP_FORMEL_SETTINGS'			=> 'F1 Instellingen',
	'ACP_FORMEL_TEAMS'				=> 'F1 Teams',
	'LOG_FORMEL_CRON'				=> 'Formule 1 taak wordt niet uitgevoerd.',
	'LOG_FORMEL_DRIVER_ADDED'		=> 'Formule 1 driver %s toegevoegd.',
	'LOG_FORMEL_DRIVER_DELETED'		=> 'Formule 1 driver %s verwijderd.',
	'LOG_FORMEL_DRIVER_EDITED'		=> 'Formule 1 driver %s aangepast.',
	'LOG_FORMEL_QUALI_ADDED'		=> 'Formule 1 voor het resultaat van de qualificatie voor de race %s zijn aangepast.',
	'LOG_FORMEL_QUALI_DELETED'		=> 'Formule 1 voor het resultaat van de qualificatie voor de race %s zijn verwijderd.',
	'LOG_FORMEL_QUALI_NOT_VALID'	=> 'Formule 1 voor het resultaat van de qualificatie voor de race %s zijn niet geldig. Verzoek afgewezen.',
	'LOG_FORMEL_RACE_ADDED'			=> 'Formule 1 race %s toegevoegd.',
	'LOG_FORMEL_RACE_DELETED'		=> 'Formule 1 race %s verwijderd',
	'LOG_FORMEL_RACE_EDITED'		=> 'Formule 1 race %s aangepast.',
	'LOG_FORMEL_RESULT_ADDED'		=> 'Formule 1 voor het resultaat van de race %s zijn aangepast.',
	'LOG_FORMEL_RESULT_DELETED'		=> 'Formule 1 voor het resultaat van de race %s zijn verwijderd.',
	'LOG_FORMEL_RESULT_NOT_VALID'	=> 'Formule 1 voor het resultaat van de race %s zijn niet geldig. Verzoek afgewezen.',
	'LOG_FORMEL_SAISON_RESET'		=> 'Formule 1 voor het seizoen zijn ge-reset.',
	'LOG_FORMEL_SETTINGS'			=> 'Formule 1 instellingen zijn bijgewerkt.',
	'LOG_FORMEL_TEAM_ADDED'			=> 'Formule 1 team %s toegevoegd.',
	'LOG_FORMEL_TEAM_DELETED'		=> 'Formule 1 team %s verwijderd.',
	'LOG_FORMEL_TEAM_EDITED'		=> 'Formule 1 team %s aangepast.',
	'LOG_FORMEL_TIP_DELETED'		=> 'Formule 1 Voorspellingen voor de  race %s zijn verwijderd.',
	'LOG_FORMEL_TIP_EDITED'			=> 'Formule 1 Voorspellingen voor de race %s zijn aangepast.',
	'LOG_FORMEL_TIP_GIVEN'			=> 'Formule 1 Voorspellingen voor de race %s zijn toegevoegd.',
	'LOG_FORMEL_TIP_NOT_VALID'		=> 'Formule 1 Voorspellingen voor de race %s zijn niet geldig. Voorspellingen zijn afgekeurd.',

));
