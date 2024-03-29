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

// ACP Common
$lang = array_merge($lang, [
	'ACP_F1_DRIVERS_ADD_DRIVER'						=> 'coureur toevoegen',
	'ACP_F1_DRIVERS_ADD'							=> 'Verstuur',
	'ACP_F1_DRIVERS_DELETE_DRIVER'					=> 'Verwijder',
	'ACP_F1_DRIVERS_DISABLED'						=> 'Selecteer baar',
	'ACP_F1_DRIVERS_DRIVER_DELETE_CONFIRM'			=> '<br>Ben je zeker om deze coureur te verwijderen?',
	'ACP_F1_DRIVERS_DRIVER_DELETED'					=> 'De geselecteerde coureur is verwijderd',
	'ACP_F1_DRIVERS_DRIVER_UPDATED'					=> 'data van de coureur is bijgewerkt',
	'ACP_F1_DRIVERS_DRIVERIMAGE'					=> 'Coureur afbeelding',
	'ACP_F1_DRIVERS_DRIVERNAME'						=> 'naam coureur',
	'ACP_F1_DRIVERS_DRIVERPOINTS'					=> 'Coureurs punten',
	'ACP_F1_DRIVERS_DRIVERTEAM'						=> 'Coureur team',
	'ACP_F1_DRIVERS_EDIT_DRIVER'					=> 'Aanpassen',
	'ACP_F1_DRIVERS_ERROR_DRIVERNAME'				=> 'Plaats a.u.b. een naam van een coureur',
	'ACP_F1_DRIVERS_ERROR_IMAGE'					=> 'Plaats a.u.b. een afbeelding van een coureur',
	'ACP_F1_DRIVERS_EXPLAIN'						=> 'Hier kan je soureurs toevoegen of aanpassen',
	'ACP_F1_DRIVERS_NOT_ADDED'						=> 'De coureur %s was niet toegevoegd omdat hij niet aan een team kon worden toegewezen.<br>Maak eerst een team aan en wijs dan een coureur toe.',
	'ACP_F1_DRIVERS_PENALTY'						=> 'Penalty',
	'ACP_F1_DRIVERS_TITEL_ADD_DRIVER_EXPLAIN'		=> 'Hier kan je een nieuwe coureur toevoegen',
	'ACP_F1_DRIVERS_TITEL_ADD_DRIVER'				=> 'toevoegen F1 coureur',
	'ACP_F1_DRIVERS_TITEL_EDIT_DRIVER_EXPLAIN'		=> 'Hier kun je aanpassingen van een coureur doen',
	'ACP_F1_DRIVERS_TITEL_EDIT_DRIVER'				=> 'Aanpassen F1 coureur',
	'ACP_F1_DRIVERS'								=> 'F1 Coureurs',
	'ACP_F1_MANAGEMENT'								=> 'F1 Voorspellingen',
	'ACP_F1_RACES_ADD_RACE'							=> 'Nieuw Race',
	'ACP_F1_RACES_ADD'								=> 'Verstuur',
	'ACP_F1_RACES_DELETE_RACE'						=> 'Verwijder',
	'ACP_F1_RACES_EDIT_RACE'						=> 'Bewerk',
	'ACP_F1_RACES_ERROR_DATE_YEAR'					=> 'De datum ligt buiten het toegestane bereik. Controleer uw invoer',
	'ACP_F1_RACES_ERROR_RACENAME'					=> 'Plaats a.u.b. een locatie voor de race',
	'ACP_F1_RACES_EXPLAIN'							=> 'Hier kan je de races toevoegen of aanpassen',
	'ACP_F1_RACES_RACE_DELETE_CONFIRM'				=> '<br>Ben je zeker om deze race te verwijderen??',
	'ACP_F1_RACES_RACE_DELETED'						=> 'Race verwijderd',
	'ACP_F1_RACES_RACE_UPDATED'						=> 'Race data is opgeslagen',
	'ACP_F1_RACES_RACEDEAD'							=> 'Deadline',
	'ACP_F1_RACES_RACEDEBUT'						=> 'Debuut',
	'ACP_F1_RACES_RACEDISTANCE'						=> 'Afstand',
	'ACP_F1_RACES_RACEIMAGE'						=> 'Logo',
	'ACP_F1_RACES_RACELAPS'							=> 'ronden',
	'ACP_F1_RACES_RACELENGTH'						=> 'Lengte van de ronde',
	'ACP_F1_RACES_RACENAME'							=> 'Locatie',
	'ACP_F1_RACES_RACETIME'							=> 'Race start',
	'ACP_F1_RACES_TITEL_ADD_RACE_EXPLAIN'			=> 'Hier kun je een nieuwe race toevoegen',
	'ACP_F1_RACES_TITEL_ADD_RACE'					=> 'Toevoegen F1 Race',
	'ACP_F1_RACES_TITEL_EDIT_RACE_EXPLAIN'			=> 'Hier kan je een F1 Race bewerken',
	'ACP_F1_RACES_TITEL_EDIT_RACE'					=> 'Bewerk F1 Race',
	'ACP_F1_RACES'									=> 'F1 Races',
	'ACP_F1_SETTING_GUEST_VIEWING_EXPLAIN'			=> '',
	'ACP_F1_SETTING_GUEST_VIEWING'					=> 'F1 Voorspellingen zichtbaar voor gasten',
	'ACP_F1_SETTINGS_ACCESS_GROUP_EXPLAIN'			=> 'Hier kan je permissies instellen aan een groep voor de F1 Voorspellingen',
	'ACP_F1_SETTINGS_ACCESS_GROUP'					=> 'F1 Voorspellingen Groep',
	'ACP_F1_SETTINGS_CAR_IMG_HEIGHT_EXPLAIN'		=> 'Hier kan je de <strong>hoogte in px</strong> van de auto afbeelding ingeven',
	'ACP_F1_SETTINGS_CAR_IMG_HEIGHT'				=> 'Hoogte afbeelding auto',
	'ACP_F1_SETTINGS_CAR_IMG_WIDTH_EXPLAIN'			=> 'Hier kan je de <strong>breedte in px</strong> van de auto afbeelding ingeven',
	'ACP_F1_SETTINGS_CAR_IMG_WIDTH'					=> 'Breedte afbeelding auto',
	'ACP_F1_SETTINGS_CONFIG'						=> 'F1 Configuratie',
	'ACP_F1_SETTINGS_DEACTIVATED'					=> '*** gedeactiveerd ***',
	'ACP_F1_SETTINGS_DRIVER_IMG_HEIGHT_EXPLAIN'		=> 'Hier kan je de <strong>hoogte in px</strong> van de coureur afbeelding ingeven',
	'ACP_F1_SETTINGS_DRIVER_IMG_HEIGHT'				=> 'Hoogte afbeelding coureur',
	'ACP_F1_SETTINGS_DRIVER_IMG_WIDTH_EXPLAIN'		=> 'Hier kan je de <strong>breedte in px</strong> van de coureur afbeelding ingeven',
	'ACP_F1_SETTINGS_DRIVER_IMG_WIDTH'				=> 'Breedte afbeelding coureur',
	'ACP_F1_SETTINGS_EXPLAIN'						=> 'Hier kan je jouw instellingen aanpassen van de F1 voorspellingen',
	'ACP_F1_SETTINGS_FORUM_EXPLAIN'					=> 'Stel hier het forum in waar je kunt discussieren over de F1 Voorspellingen',
	'ACP_F1_SETTINGS_FORUM'							=> 'F1 Forum',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_HEIGHT_EXPLAIN'	=> 'Hier kan je de <strong>hoogte in px</strong> van de banner ingeven',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_HEIGHT'			=> 'Banner hoogte',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_WIDTH_EXPLAIN'	=> 'Hier kan je de <strong>breedte in px</strong> an de banner ingeven',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_WIDTH'			=> 'Banner breedte',
	'ACP_F1_SETTINGS_HEADBANNER1_IMG_EXPLAIN'		=> 'Banner voor de pagina overzicht van de F1 Voorspellingen<br><code>ext/drdeath/f1webtip/images/banners/</code>',
	'ACP_F1_SETTINGS_HEADBANNER1_IMG'				=> 'Banner F1 Voorspellingen',
	'ACP_F1_SETTINGS_HEADBANNER2_IMG_EXPLAIN'		=> 'Banner voor de pagina regels van de F1 Voorspellingen<br><code>ext/drdeath/f1webtip/images/banners/</code>',
	'ACP_F1_SETTINGS_HEADBANNER2_IMG'				=> 'Banner F1 regels',
	'ACP_F1_SETTINGS_HEADBANNER3_IMG_EXPLAIN'		=> 'Banner voor de pagina statistieken van de F1 Voorspellingen<br><code>ext/drdeath/f1webtip/images/banners/</code>',
	'ACP_F1_SETTINGS_HEADBANNER3_IMG'				=> 'Banner F1 statistieken',
	'ACP_F1_SETTINGS_MODERATOR_EXPLAIN'				=> 'Selecteer iemand met moderator- of administratorrechten',
	'ACP_F1_SETTINGS_MODERATOR'						=> 'Voorspellingen moderator',
	'ACP_F1_SETTINGS_NO_CAR_IMG_EXPLAIN'			=> 'Hier kan je de standaard afbeelding ingeven indien je geen geschikte auto afbeelding hebt<br><code>ext/drdeath/f1webtip/images/cars/</code>',
	'ACP_F1_SETTINGS_NO_CAR_IMG'					=> 'Standaard auto afbeelding',
	'ACP_F1_SETTINGS_NO_DRIVER_IMG_EXPLAIN'			=> 'Hier kan je de standaard afbeelding indien je geen geschikte coureur afbeelding hebt<br><code>ext/drdeath/f1webtip/images/drivers/</code>',
	'ACP_F1_SETTINGS_NO_DRIVER_IMG'					=> 'Standaard coureur afbeelding',
	'ACP_F1_SETTINGS_NO_RACE_IMG_EXPLAIN'			=> 'Hier kan je de standaard afbeelding ingeven<br><code>ext/drdeath/f1webtip/images/races/</code>',
	'ACP_F1_SETTINGS_NO_RACE_IMG'					=> 'Standaard race afbeelding',
	'ACP_F1_SETTINGS_NO_TEAM_IMG_EXPLAIN'			=> 'Hier kan je de standaard afbeelding indien je geen geschikte team afbeelding hebt<br><code>ext/drdeath/f1webtip/images/teams/</code>',
	'ACP_F1_SETTINGS_NO_TEAM_IMG'					=> 'Standaard team afbeelding',
	'ACP_F1_SETTINGS_OFFSET_EXPLAIN'				=> 'Hier kan je de deadline instellen. (Tijd in seconden voor aanvang van de race)',
	'ACP_F1_SETTINGS_OFFSET'						=> 'Deadline aanpassen',
	'ACP_F1_SETTINGS_PICS'							=> 'Afbeeldingen',
	'ACP_F1_SETTINGS_POINTS_FASTEST_EXPLAIN'		=> 'Punten voor de snelst gereden ronde',
	'ACP_F1_SETTINGS_POINTS_FASTEST'				=> 'Snelste',
	'ACP_F1_SETTINGS_POINTS_MENTIONED_EXPLAIN'		=> 'Punten van een coureur ongeacht plaats in de Top 10',
	'ACP_F1_SETTINGS_POINTS_MENTIONED'				=> 'Willekeurige plaats van een coureur',
	'ACP_F1_SETTINGS_POINTS_PLACED_EXPLAIN'			=> 'Punten voor de coureur op de juiste plaats in de Top 10',
	'ACP_F1_SETTINGS_POINTS_PLACED'					=> 'Juiste plaats van een coureur',
	'ACP_F1_SETTINGS_POINTS_TIRED_EXPLAIN'			=> 'Punten voor het juiste aantal banden wissels',
	'ACP_F1_SETTINGS_POINTS_TIRED'					=> 'Banden',
	'ACP_F1_SETTINGS_POINTS'						=> 'Punten',
	'ACP_F1_SETTINGS_RACE_IMG_HEIGHT_EXPLAIN'		=> 'Hier kan je de <strong>hoogte in px</strong> van de race afbeelding ingeven',
	'ACP_F1_SETTINGS_RACE_IMG_HEIGHT'				=> 'Race afbeelding hoogte',
	'ACP_F1_SETTINGS_RACE_IMG_WIDTH_EXPLAIN'		=> 'Hier kan je de  <strong>breedte in px</strong> van de race afbeelding ingeven',
	'ACP_F1_SETTINGS_RACE_IMG_WIDTH'				=> 'Race afbeelding breedte',
	'ACP_F1_SETTINGS_RACEOFFSET_EXPLAIN'			=> 'Hier kan je de instellingen aanpassen wanneer de "actuele race" is veranderd. (Tijd in seconden voor aanvang van de race)',
	'ACP_F1_SETTINGS_RACEOFFSET'					=> 'Race vertraagd',
	'ACP_F1_SETTINGS_REMINDER_ENABLED_EXPLAIN'		=> 'Hier kan je aangeven om een herinnering mail te versturen 2-3 dagen voor de start van de race.<br><strong>Hint: </strong>Kan alleen worden geactiveerd wanneer de F1 Voorspellingen is beperkt tot een bepaalde groep.',
	'ACP_F1_SETTINGS_REMINDER_ENABLED'				=> 'Activeer taak voor het versturen van een herinnering mail',
	'ACP_F1_SETTINGS_SAFETY_CAR_EXPLAIN'			=> 'Punten voor het juiste aantal keren van het inbrengen van de Saftey Car',
	'ACP_F1_SETTINGS_SAFETY_CAR'					=> 'Safety Car',
	'ACP_F1_SETTINGS_SEASON_RESET_EXPLAIN'			=> '<strong>Attentie:</strong> Als je op deze button klikt, zal alle F1 data worden gewist!<br><br>Na het resetten van het F1 seizoen, moet je alle start tijden weer ingeven/aanpassen.',
	'ACP_F1_SETTINGS_SEASON_RESET'					=> 'Reset F1 seizoen',
	'ACP_F1_SETTINGS_SEASON_RESETTED'				=> 'F1 seizoen is gereset. Pas alle start tijden aan!',
	'ACP_F1_SETTINGS_SHOW_AVATAR_EXPLAIN'			=> 'Hier kan je ingeven of je de gebruikers avatar wilt tonen in de gebruikers statistieken/of niet',
	'ACP_F1_SETTINGS_SHOW_AVATAR'					=> 'Toon avatar',
	'ACP_F1_SETTINGS_SHOW_COUNTDOWN_EXPLAIN'		=> 'Wil je de informatie tonen van het aftellen tot de deadline in F1 Voorspellingen?',
	'ACP_F1_SETTINGS_SHOW_COUNTDOWN'				=> 'Toon aftellen',
	'ACP_F1_SETTINGS_SHOW_GFX_EXPLAIN'				=> 'Wil je de afbeeldingen tonen van coureur, team en auto?',
	'ACP_F1_SETTINGS_SHOW_GFX'						=> 'Toon uitgebreide afbeeldingen',
	'ACP_F1_SETTINGS_SHOW_GFXR_EXPLAIN'				=> 'Wil je de race afbeeldingen tonen?',
	'ACP_F1_SETTINGS_SHOW_GFXR'						=> 'Toon race afbeeldingen',
	'ACP_F1_SETTINGS_SHOW_HEADBANNER_EXPLAIN'		=> 'Hier kan je ingeven welke hoofdbanners je wilt tonen/of niet',
	'ACP_F1_SETTINGS_SHOW_HEADBANNER'				=> 'Toon F1 banner',
	'ACP_F1_SETTINGS_SHOW_PROFILE_EXPLAIN'			=> 'Wil je de informatie tonen in het gebruikers profiel?',
	'ACP_F1_SETTINGS_SHOW_PROFILE'					=> 'Toon in profiel',
	'ACP_F1_SETTINGS_SHOW_VIEWTOPIC_EXPLAIN'		=> 'Wil je de informatie tonen in de post van de gebruikers profiel?',
	'ACP_F1_SETTINGS_SHOW_VIEWTOPIC'				=> 'Toon in post',
	'ACP_F1_SETTINGS_TEAM_IMG_HEIGHT_EXPLAIN'		=> 'Hier kan je de <strong>hoogte in px</strong> van de team afbeelding ingeven',
	'ACP_F1_SETTINGS_TEAM_IMG_HEIGHT'				=> 'Hoogte afbeelding team',
	'ACP_F1_SETTINGS_TEAM_IMG_WIDTH_EXPLAIN'		=> 'Hier kan je de <strong>breedte in px</strong> van de team afbeelding ingeven',
	'ACP_F1_SETTINGS_TEAM_IMG_WIDTH'				=> 'Breedte afbeelding team',
	'ACP_F1_SETTINGS_UPDATED'						=> 'F1 instellingen zijn succesvol bijgewerkt',
	'ACP_F1_SETTINGS'								=> 'F1 Instellingen',
	'ACP_F1_TEAMS_ADD_TEAM'							=> 'Nieuw Team',
	'ACP_F1_TEAMS_ADD'								=> 'Verstuur',
	'ACP_F1_TEAMS_ADDTEAM_TEAMCAR'					=> 'Team auto',
	'ACP_F1_TEAMS_ADDTEAM_TEAMIMAGE'				=> 'Team Logo',
	'ACP_F1_TEAMS_ADDTEAM_TEAMNAME'					=> 'Team naam',
	'ACP_F1_TEAMS_ADDTEAM_TITLE_EXPLAIN'			=> 'Hier kun je een nieuw team toevoegen',
	'ACP_F1_TEAMS_ADDTEAM_TITLE'					=> 'Toevoegen F1 Team',
	'ACP_F1_TEAMS_DELETE_TEAM'						=> 'Verwijder',
	'ACP_F1_TEAMS_DRIVERPOINTS'						=> 'WK Punten',
	'ACP_F1_TEAMS_DRIVERTEAM'						=> 'Team',
	'ACP_F1_TEAMS_EDIT_TEAM'						=> 'Bewerk',
	'ACP_F1_TEAMS_EDITTEAM_TITLE_EXPLAIN'			=> 'Hier kan je een F1 Team bewerken',
	'ACP_F1_TEAMS_EDITTEAM_TITLE'					=> 'Bewerk F1 Team',
	'ACP_F1_TEAMS_ERROR_TEAMNAME'					=> 'Plaats a.u.b. een naam van een team',
	'ACP_F1_TEAMS_EXPLAIN'							=> 'Hier kan je de teams toevoegen of aanpassen',
	'ACP_F1_TEAMS_PENALTY'							=> 'Penalty',
	'ACP_F1_TEAMS_TEAM_DELETE_CONFIRM'				=> '<br>Ben je zeker om dit team te verwijderen?',
	'ACP_F1_TEAMS_TEAM_DELETED'						=> 'Team %s verwijderd',
	'ACP_F1_TEAMS_TEAM_NOT_DELETED'					=> 'Team %s werd niet verwijderd, omdat er nog steeds coureurs in zitten. Verwijder eerst de coureurs uit het team.',
	'ACP_F1_TEAMS_TEAM_UPDATED'						=> 'Team data is opgeslagen',
	'ACP_F1_TEAMS'									=> 'F1 Teams',
	'ACP_F1WEBTIP_SETTING_SAVED'					=> 'Instellingen zijn succesvol opgeslagen!',

]);
