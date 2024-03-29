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
	'ACP_F1_DRIVERS_ADD_DRIVER'						=> 'Neuer Fahrer',
	'ACP_F1_DRIVERS_ADD'							=> 'Eintragen',
	'ACP_F1_DRIVERS_DELETE_DRIVER'					=> 'Löschen',
	'ACP_F1_DRIVERS_DISABLED'						=> 'Auswählbar',
	'ACP_F1_DRIVERS_DRIVER_DELETE_CONFIRM'			=> '<br>Sind Sie sicher, dass Sie den Fahrer %s löschen möchten?',
	'ACP_F1_DRIVERS_DRIVER_DELETED'					=> 'Der Fahrer %s wurde erfolgreich gelöscht',
	'ACP_F1_DRIVERS_DRIVER_UPDATED'					=> 'Fahrer Datenbank erfolgreich aktualisiert',
	'ACP_F1_DRIVERS_DRIVERIMAGE'					=> 'Portrait',
	'ACP_F1_DRIVERS_DRIVERNAME'						=> 'Fahrername',
	'ACP_F1_DRIVERS_DRIVERPOINTS'					=> 'WM Punkte',
	'ACP_F1_DRIVERS_DRIVERTEAM'						=> 'Team',
	'ACP_F1_DRIVERS_EDIT_DRIVER'					=> 'Bearbeiten',
	'ACP_F1_DRIVERS_ERROR_DRIVERNAME'				=> 'Bitte geben Sie einen Fahrernamen an',
	'ACP_F1_DRIVERS_ERROR_IMAGE'					=> 'Bitte geben Sie ein Fahrerbild an',
	'ACP_F1_DRIVERS_EXPLAIN'						=> 'Hier können Sie neue F1 Fahrer erstellen oder vorhandene bearbeiten',
	'ACP_F1_DRIVERS_NOT_ADDED'						=> 'Der Fahrer %s wurde nicht hinzugefügt, da er keinem Team zugeordnet werden konnte.<br>Bitte erstellen Sie erst ein Team um anschliessend einen Fahrer zuzuweisen.',
	'ACP_F1_DRIVERS_PENALTY'						=> 'Strafpunkte',
	'ACP_F1_DRIVERS_TITEL_ADD_DRIVER_EXPLAIN'		=> 'Hier können Sie einen neuen F1 Fahrer erstellen',
	'ACP_F1_DRIVERS_TITEL_ADD_DRIVER'				=> 'F1 Fahrer eintragen',
	'ACP_F1_DRIVERS_TITEL_EDIT_DRIVER_EXPLAIN'		=> 'Hier können Sie einen F1 Fahrer bearbeiten',
	'ACP_F1_DRIVERS_TITEL_EDIT_DRIVER'				=> 'F1 Fahrer bearbeiten',
	'ACP_F1_DRIVERS'								=> 'F1 Fahrer',
	'ACP_F1_MANAGEMENT'								=> 'F1 WebTipp',
	'ACP_F1_RACES_ADD_RACE'							=> 'Neues Rennen',
	'ACP_F1_RACES_ADD'								=> 'Eintragen',
	'ACP_F1_RACES_DELETE_RACE'						=> 'Löschen',
	'ACP_F1_RACES_EDIT_RACE'						=> 'Bearbeiten',
	'ACP_F1_RACES_ERROR_DATE_YEAR'					=> 'Das Datum liegt außerhalb des erlaubten Bereichs. Bitte überprüfe Sie Ihre Eingabe',
	'ACP_F1_RACES_ERROR_RACENAME'					=> 'Bitte geben Sie einen Austragungsort an',
	'ACP_F1_RACES_EXPLAIN'							=> 'Hier können Sie neue F1 Rennen erstellen oder vorhandene bearbeiten',
	'ACP_F1_RACES_RACE_DELETE_CONFIRM'				=> '<br>Sind Sie sicher, dass Sie das Rennen %s löschen möchtest?',
	'ACP_F1_RACES_RACE_DELETED'						=> 'Das Rennen wurde erfolgreich gelöscht',
	'ACP_F1_RACES_RACE_UPDATED'						=> 'Renn Datenbank erfolgreich aktualisiert',
	'ACP_F1_RACES_RACEDEAD'							=> 'Deadline',
	'ACP_F1_RACES_RACEDEBUT'						=> 'Streckendebüt',
	'ACP_F1_RACES_RACEDISTANCE'						=> 'Renndistanz',
	'ACP_F1_RACES_RACEIMAGE'						=> 'Strecken Logo',
	'ACP_F1_RACES_RACELAPS'							=> 'Anzahl der Runden',
	'ACP_F1_RACES_RACELENGTH'						=> 'Streckenlänge',
	'ACP_F1_RACES_RACENAME'							=> 'Austragungsort',
	'ACP_F1_RACES_RACETIME'							=> 'Rennbeginn',
	'ACP_F1_RACES_TITEL_ADD_RACE_EXPLAIN'			=> 'Hier können Sie ein neues F1 Rennen erstellen',
	'ACP_F1_RACES_TITEL_ADD_RACE'					=> 'F1 Rennen eintragen',
	'ACP_F1_RACES_TITEL_EDIT_RACE_EXPLAIN'			=> 'Hier können Sie ein F1 Rennen bearbeiten',
	'ACP_F1_RACES_TITEL_EDIT_RACE'					=> 'F1 Rennen bearbeiten',
	'ACP_F1_RACES'									=> 'F1 Rennen',
	'ACP_F1_SETTING_GUEST_VIEWING_EXPLAIN'			=> 'Nur möglich wenn Zugriff auf eine <strong>F1 WebTipp Gruppe</strong> deaktiviert ist.',
	'ACP_F1_SETTING_GUEST_VIEWING'					=> 'F1 WebTipp sichtbar für Gäste',
	'ACP_F1_SETTINGS_ACCESS_GROUP_EXPLAIN'			=> 'Hier können Sie den Zugriff auf den F1 WebTipp auf eine bestimmte Gruppe beschränken',
	'ACP_F1_SETTINGS_ACCESS_GROUP'					=> 'F1 WebTipp Gruppe',
	'ACP_F1_SETTINGS_CAR_IMG_HEIGHT_EXPLAIN'		=> 'Hier können Sie die <strong>Höhe in Px</strong> angeben,<br>in der das Autobild dargestellt werden soll',
	'ACP_F1_SETTINGS_CAR_IMG_HEIGHT'				=> 'Autobild Höhe',
	'ACP_F1_SETTINGS_CAR_IMG_WIDTH_EXPLAIN'			=> 'Hier können Sie die <strong>Breite in Px</strong> angeben,<br>in der das Autobild dargestellt werden soll',
	'ACP_F1_SETTINGS_CAR_IMG_WIDTH'					=> 'Autobild Breite',
	'ACP_F1_SETTINGS_CONFIG'						=> 'F1 Konfiguration',
	'ACP_F1_SETTINGS_DEACTIVATED'					=> '*** deaktiviert ***',
	'ACP_F1_SETTINGS_DRIVER_IMG_HEIGHT_EXPLAIN'		=> 'Hier können Sie die <strong>Höhe in Px</strong> angeben,<br>in der das Fahrerbild dargestellt werden soll',
	'ACP_F1_SETTINGS_DRIVER_IMG_HEIGHT'				=> 'Fahrerbild Höhe',
	'ACP_F1_SETTINGS_DRIVER_IMG_WIDTH_EXPLAIN'		=> 'Hier können Sie die <strong>Breite in Px</strong> angeben,<br>in der das Fahrerbild dargestellt werden soll',
	'ACP_F1_SETTINGS_DRIVER_IMG_WIDTH'				=> 'Fahrerbild Breite',
	'ACP_F1_SETTINGS_EXPLAIN'						=> 'Hier können Sie die F1 WebTipp Konfiguration bearbeiten',
	'ACP_F1_SETTINGS_FORUM_EXPLAIN'					=> 'Hier kann das Diskussionsforum zum F1 WebTipp eingetragen werden',
	'ACP_F1_SETTINGS_FORUM'							=> 'F1 Forum',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_HEIGHT_EXPLAIN'	=> 'Hier können Sie die <strong>Höhe in Px</strong> angeben,<br>in der der Banner dargestellt werden soll',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_HEIGHT'			=> 'Banner Höhe',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_WIDTH_EXPLAIN'	=> 'Hier können Sie die <strong>Breite in Px</strong> angeben,<br>in der der Banner dargestellt werden soll',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_WIDTH'			=> 'Banner Breite',
	'ACP_F1_SETTINGS_HEADBANNER1_IMG_EXPLAIN'		=> 'Banner für die F1 WebTipp Übersichtsseite<br><code>ext/drdeath/f1webtip/images/banners/</code>',
	'ACP_F1_SETTINGS_HEADBANNER1_IMG'				=> 'Banner F1 Webtipp',
	'ACP_F1_SETTINGS_HEADBANNER2_IMG_EXPLAIN'		=> 'Banner für die F1 WebTipp Regelnseite<br><code>ext/drdeath/f1webtip/images/banners/</code>',
	'ACP_F1_SETTINGS_HEADBANNER2_IMG'				=> 'Banner F1 Regeln',
	'ACP_F1_SETTINGS_HEADBANNER3_IMG_EXPLAIN'		=> 'Banner für die F1 WebTipp Statistikseite<br><code>ext/drdeath/f1webtip/images/banners/</code>',
	'ACP_F1_SETTINGS_HEADBANNER3_IMG'				=> 'Banner F1 Statistik',
	'ACP_F1_SETTINGS_MODERATOR_EXPLAIN'				=> 'Wählen Sie jemanden mit Moderatoren- oder Administratorenrechten aus',
	'ACP_F1_SETTINGS_MODERATOR'						=> 'F1 WebTipp Moderator',
	'ACP_F1_SETTINGS_NO_CAR_IMG_EXPLAIN'			=> 'Hier können Sie das Bild angeben, welches angezeigt wird,<br>wenn kein Autobild vorhanden ist<br><code>ext/drdeath/f1webtip/images/cars/</code>',
	'ACP_F1_SETTINGS_NO_CAR_IMG'					=> 'Standard Autobild',
	'ACP_F1_SETTINGS_NO_DRIVER_IMG_EXPLAIN'			=> 'Hier können Sie das Bild angeben, welches angezeigt wird,<br>wenn kein Fahrerbild vorhanden ist<br><code>ext/drdeath/f1webtip/images/drivers/</code>',
	'ACP_F1_SETTINGS_NO_DRIVER_IMG'					=> 'Standard Fahrerbild',
	'ACP_F1_SETTINGS_NO_RACE_IMG_EXPLAIN'			=> 'Hier können Sie das Bild angeben, welches angezeigt wird,<br>wenn kein Rennstreckenbild vorhanden ist<br><code>ext/drdeath/f1webtip/images/races/</code>',
	'ACP_F1_SETTINGS_NO_RACE_IMG'					=> 'Standard Rennstrecke',
	'ACP_F1_SETTINGS_NO_TEAM_IMG_EXPLAIN'			=> 'Hier können Sie das Bild angeben, welches angezeigt wird,<br>wenn kein Teamlogo vorhanden ist<br><code>ext/drdeath/f1webtip/images/teams/</code>',
	'ACP_F1_SETTINGS_NO_TEAM_IMG'					=> 'Standard Teamlogo',
	'ACP_F1_SETTINGS_OFFSET_EXPLAIN'				=> 'Hier können Sie die Deadline für die Tippabgabe bestimmten.<br>(Zeit in Sekunden bis zum Rennbeginn)',
	'ACP_F1_SETTINGS_OFFSET'						=> 'Deadline Offset',
	'ACP_F1_SETTINGS_PICS'							=> 'Bilder',
	'ACP_F1_SETTINGS_POINTS_FASTEST_EXPLAIN'		=> 'Punkte für die schnellste Runde',
	'ACP_F1_SETTINGS_POINTS_FASTEST'				=> 'Schnellste Runde',
	'ACP_F1_SETTINGS_POINTS_MENTIONED_EXPLAIN'		=> 'Punkte für das Erwähnen eines Fahrers',
	'ACP_F1_SETTINGS_POINTS_MENTIONED'				=> 'Erwähnt',
	'ACP_F1_SETTINGS_POINTS_PLACED_EXPLAIN'			=> 'Punkte für das richtige Platzieren eines Fahrers',
	'ACP_F1_SETTINGS_POINTS_PLACED'					=> 'Platziert',
	'ACP_F1_SETTINGS_POINTS_TIRED_EXPLAIN'			=> 'Punkte für die richtige Anzahl Ausfälle',
	'ACP_F1_SETTINGS_POINTS_TIRED'					=> 'Ausfälle',
	'ACP_F1_SETTINGS_POINTS'						=> 'Punktevergabe',
	'ACP_F1_SETTINGS_RACE_IMG_HEIGHT_EXPLAIN'		=> 'Hier können Sie die <strong>Höhe in Px</strong> angeben,<br>in der die Rennstrecke dargestellt werden soll',
	'ACP_F1_SETTINGS_RACE_IMG_HEIGHT'				=> 'Rennstrecke Höhe',
	'ACP_F1_SETTINGS_RACE_IMG_WIDTH_EXPLAIN'		=> 'Hier können Sie die <strong>Breite in Px</strong> angeben,<br>in der die Rennstrecke dargestellt werden soll',
	'ACP_F1_SETTINGS_RACE_IMG_WIDTH'				=> 'Rennstrecke Breite',
	'ACP_F1_SETTINGS_RACEOFFSET_EXPLAIN'			=> 'Hier wird festgelegt wann das "Aktuelle Rennen" wechselt.<br>(Zeit in Sekunden nach Rennbeginn)',
	'ACP_F1_SETTINGS_RACEOFFSET'					=> 'Aktuelles Rennen',
	'ACP_F1_SETTINGS_REMINDER_ENABLED_EXPLAIN'		=> 'Hier können Sie angeben, ob 2 bis 3 Tage vor dem Rennbeginn eine F1 Erinnerungsmail versendet werden soll.<br><strong>Hinweis: </strong>Kann nur aktiviert werden, wenn der F1 WebTipp auf eine bestimmte <strong>F1 WebTipp Gruppe</strong> beschränkt worden ist.',
	'ACP_F1_SETTINGS_REMINDER_ENABLED'				=> 'Cronjob für F1 Erinnerungsmail aktivieren',
	'ACP_F1_SETTINGS_SAFETY_CAR_EXPLAIN'			=> 'Punkte für die richtige Anzahl von Safety Car Einsätzen',
	'ACP_F1_SETTINGS_SAFETY_CAR'					=> 'Safety Car',
	'ACP_F1_SETTINGS_SEASON_RESET_EXPLAIN'			=> '<strong>Achtung:</strong> Wenn Sie auf den Button klicken, wird die F1 Saison unwiderruflich zurückgesetzt!<br><br>Nach dem Reset müssen noch die Renntermine der neuen F1 Saison angepasst werden. Der <a href="http://www.lpi-clan.de">Support</a> dieser Extension bietet hierfür SQL-Updates an.',
	'ACP_F1_SETTINGS_SEASON_RESET'					=> 'F1 Saison zurücksetzen',
	'ACP_F1_SETTINGS_SEASON_RESETTED'				=> 'F1 Saison zurückgesetzt. Renntermine aktualisieren!',
	'ACP_F1_SETTINGS_SHOW_AVATAR_EXPLAIN'			=> 'Hier können Sie festlegen, ob der Avatar des Benutzers in der Spieler Statistik angezeigt werden soll',
	'ACP_F1_SETTINGS_SHOW_AVATAR'					=> 'Avatar anzeigen',
	'ACP_F1_SETTINGS_SHOW_COUNTDOWN_EXPLAIN'		=> 'Hier können Sie festlegen, ob der Countdown im F1 WebTipp gezeigt werden soll.',
	'ACP_F1_SETTINGS_SHOW_COUNTDOWN'				=> 'Countdown anzeigen',
	'ACP_F1_SETTINGS_SHOW_GFX_EXPLAIN'				=> 'Sollen die Grafiken der Fahrer und Teams angezeigt werden?',
	'ACP_F1_SETTINGS_SHOW_GFX'						=> 'Fahrer- und Team-Grafiken anzeigen',
	'ACP_F1_SETTINGS_SHOW_GFXR_EXPLAIN'				=> 'Sollen die Grafiken der Rennstrecken angezeigt werden?',
	'ACP_F1_SETTINGS_SHOW_GFXR'						=> 'Rennstrecken-Grafiken anzeigen',
	'ACP_F1_SETTINGS_SHOW_HEADBANNER_EXPLAIN'		=> 'Hier können Sie festlegen, ob der F1 Banner im Header gezeigt werden soll.',
	'ACP_F1_SETTINGS_SHOW_HEADBANNER'				=> 'F1 Banner anzeigen',
	'ACP_F1_SETTINGS_SHOW_PROFILE_EXPLAIN'			=> 'Sollen die Tipp Ergebnisse in den Userprofilen angezeigt werden?',
	'ACP_F1_SETTINGS_SHOW_PROFILE'					=> 'Anzeige im Profil',
	'ACP_F1_SETTINGS_SHOW_VIEWTOPIC_EXPLAIN'		=> 'Sollen die Tipp Ergebnisse in den Beiträgen angezeigt werden?',
	'ACP_F1_SETTINGS_SHOW_VIEWTOPIC'				=> 'Anzeige in Beiträgen',
	'ACP_F1_SETTINGS_TEAM_IMG_HEIGHT_EXPLAIN'		=> 'Hier können Sie die <strong>Höhe in Px</strong> angeben,<br>in der das Teamlogo dargestellt werden soll',
	'ACP_F1_SETTINGS_TEAM_IMG_HEIGHT'				=> 'Teamlogo Höhe',
	'ACP_F1_SETTINGS_TEAM_IMG_WIDTH_EXPLAIN'		=> 'Hier können Sie die <strong>Breite in Px</strong> angeben,<br>in der das Teamlogo dargestellt werden soll',
	'ACP_F1_SETTINGS_TEAM_IMG_WIDTH'				=> 'Teamlogo Breite',
	'ACP_F1_SETTINGS_UPDATED'						=> 'F1 Einstellungen erfolgreich aktualisiert',
	'ACP_F1_SETTINGS'								=> 'F1 Einstellungen',
	'ACP_F1_TEAMS_ADD_TEAM'							=> 'Neues Team',
	'ACP_F1_TEAMS_ADD'								=> 'Eintragen',
	'ACP_F1_TEAMS_ADDTEAM_TEAMCAR'					=> 'Team Auto',
	'ACP_F1_TEAMS_ADDTEAM_TEAMIMAGE'				=> 'Team Logo',
	'ACP_F1_TEAMS_ADDTEAM_TEAMNAME'					=> 'Teamname',
	'ACP_F1_TEAMS_ADDTEAM_TITLE_EXPLAIN'			=> 'Hier können Sie ein neues F1 Team erstellen',
	'ACP_F1_TEAMS_ADDTEAM_TITLE'					=> 'F1 Team eintragen',
	'ACP_F1_TEAMS_DELETE_TEAM'						=> 'Löschen',
	'ACP_F1_TEAMS_DRIVERPOINTS'						=> 'WM Punkte',
	'ACP_F1_TEAMS_DRIVERTEAM'						=> 'Team',
	'ACP_F1_TEAMS_EDIT_TEAM'						=> 'Bearbeiten',
	'ACP_F1_TEAMS_EDITTEAM_TITLE_EXPLAIN'			=> 'Hier können Sie ein F1 Team bearbeiten',
	'ACP_F1_TEAMS_EDITTEAM_TITLE'					=> 'F1 Team bearbeiten',
	'ACP_F1_TEAMS_ERROR_TEAMNAME'					=> 'Bitte geben Sie einen Teamnamen an',
	'ACP_F1_TEAMS_EXPLAIN'							=> 'Hier können Sie neue F1 Teams erstellen oder vorhandene bearbeiten',
	'ACP_F1_TEAMS_PENALTY'							=> 'Strafe',
	'ACP_F1_TEAMS_TEAM_DELETE_CONFIRM'				=> '<br>Sind Sie sicher, dass Sie das Team %s löschen möchten?',
	'ACP_F1_TEAMS_TEAM_DELETED'						=> 'Das Team %s wurde erfolgreich gelöscht',
	'ACP_F1_TEAMS_TEAM_NOT_DELETED'					=> 'Das Team %s wurde nicht gelöscht, da es noch Fahrer beinhaltet. Bitte löschen Sie erst die Fahrer aus dem Team.',
	'ACP_F1_TEAMS_TEAM_UPDATED'						=> 'Team Datenbank erfolgreich aktualisiert',
	'ACP_F1_TEAMS'									=> 'F1 Teams',
	'ACP_F1WEBTIP_SETTING_SAVED'					=> 'Formel 1 Konfiguration erfolgreich gespeichert!',

]);
