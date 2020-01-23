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

$lang = array_merge($lang, array(
	'F1WEBTIP_PAGE'					=> 'F1 WebTipp',

	'FORMEL_TITLE'					=> 'F1 WebTipp',
	'FORMEL_CURRENT_RACE'			=> 'Aktuelles Rennen',
	'FORMEL_CURRENT_QUALI'			=> 'Qualifikation',
	'FORMEL_CURRENT_RESULT'			=> 'Ergebnis',
	'FORMEL_NO_QUALI'				=> 'Keine Qualifikation verfügbar',
	'FORMEL_NO_RESULTS'				=> 'Kein Ergebnis verfügbar',
	'FORMEL_RACENAME'				=> 'Rennstrecke',
	'FORMEL_RACELENGTH'				=> 'Streckenlänge',
	'FORMEL_RACEDISTANCE'			=> 'Renndistanz',
	'FORMEL_RACELAPS'				=> 'Anzahl Runden',
	'FORMEL_RACEDEBUT'				=> 'Streckendebüt',
	'FORMEL_RACETIME'				=> 'Rennstart',
	'FORMEL_RACEDEAD'				=> 'Deadline',
	'FORMEL_NEXT_RACE'				=> 'Nächstes',
	'FORMEL_PREV_RACE'				=> 'Vorheriges',
	'FORMEL_PLACE'					=> 'Platz',
	'FORMEL_EDIT'					=> 'Bearbeiten',
	'FORMEL_RULES'					=> 'Regeln',
	'FORMEL_FORUM'					=> 'Diskussionsforum',
	'FORMEL_STATISTICS'				=> 'Statistik',
	'FORMEL_CALL_MOD'				=> 'Moderator benachrichtigen',
	'FORMEL_POLE'					=> 'Poleposition',
	'FORMEL_RACE_WINNER'			=> 'Sieger',
	'FORMEL_DELETE'					=> 'Löschen',
	'FORMEL_PACE'					=> 'Schnellste Runde',
	'FORMEL_TIRED'					=> 'Anzahl Ausfälle',
	'FORMEL_SAFETYCAR'				=> 'Safety Car Einsätze',
	'FORMEL_NO_TIPP'				=> 'Es wurde kein Tipp gefunden',
	'FORMEL_YOUR_TIPP'				=> 'Dein Tipp',
	'FORMEL_YOUR_POINTS'			=> 'Deine Punkte',
	'FORMEL_GAME_OVER'				=> 'Die Frist ist abgelaufen. Du kannst für dieses Rennen keinen Tipp mehr abgeben.',
	'FORMEL_ADD_TIPP'				=> 'Tipp absenden',
	'FORMEL_DEL_TIPP'				=> 'Tipp löschen',
	'FORMEL_EDIT_TIPP'				=> 'Tipp bearbeiten',
	'FORMEL_TIPP_DELETED'			=> 'Der Tipp wurde erfolgreich gelöscht<br /><br />Klicke %shier%s, um zum F1 WebTipp zurückzukehren<br /><br />Klicke %shier%s, um zum Forum zurückzukehren',
	'FORMEL_DUBLICATE_VALUES'		=> '<span style="color:red; font-weight:bold; font-size: 1.5em">Der Tipp wurde nicht angenommen: Ein Fahrer wurde mehrfach platziert</span><br /><br />Klicke %shier%s, um zu dem F1 Tipp zurückzukehren<br /><br />Klicke %shier%s, um zum Forum zurückzukehren',
	'FORMEL_ACCEPTED_TIPP'			=> 'Der Tipp wurde erfolgreich eingetragen<br /><br />Klicke %shier%s, um für weitere Rennen zu tippen<br /><br />Klicke %shier%s, um zum Forum zurückzukehren',
	'FORMEL_RESULTS_TITLE'			=> 'F1 WebTipp Moderation',
	'FORMEL_RESULTS_TITLE_EXP'		=> 'Hier kannst Du die Ergebnisse für die einzelnen Rennen eintragen oder bearbeiten',
	'FORMEL_MOD_BUTTON_TEXT'		=> 'Moderation',
	'FORMEL_RESULTS_DELETED'		=> 'Die Rennergebnisse wurden gelöscht<br /><br />Klicke %shier%s, um zur F1 WebTipp Moderation zurückzukehren<br /><br />Klicke %shier%s, um zum F1 WebTipp zurückzukehren',
	'FORMEL_RESULTS_ERROR'			=> 'Es gab einen Fehler. Bitte versuche es erneut<br /><br />Klicke %shier%s, um zur F1 WebTipp Moderation zurückzukehren<br /><br />Klicke %shier%s, um zum F1 WebTipp zurückzukehren',
	'FORMEL_RESULTS_DOUBLE'			=> 'Es wurde ein Fahrer mehrfach eingetragen. Bitte versuche es erneut<br /><br />Klicke %shier%s, um zur F1 WebTipp Moderation zurückzukehren<br /><br />Klicke %shier%s, um zum F1 WebTipp zurückzukehren',
	'FORMEL_RESULTS_ACCEPTED'		=> 'Die Ergebnisse wurden eingetragen<br /><br />Klicke %shier%s, um zur F1 WebTipp Moderation zurückzukehren<br /><br />Klicke %shier%s, um zum F1 WebTipp zurückzukehren',
	'FORMEL_RESULTS_ADD'			=> 'Eintragen',
	'FORMEL_RESULTS_QUALI_TITLE'	=> 'Qualifikation eintragen',
	'FORMEL_RESULTS_RESULT_TITLE'	=> 'Rennergebnis eintragen',
	'FORMEL_TOP_POINTS'				=> 'Punkte',
	'FORMEL_TOP_NAME'				=> 'Top Spieler',
	'FORMEL_TOP_DRIVER'				=> 'Top Fahrer',
	'FORMEL_TOP_TEAMS'				=> 'Top Teams',
	'FORMEL_NO_TIPPS'				=> 'Noch keine Tipps vorhanden',
	'FORMEL_TIPPS_MADE'				=> 'Abgegebene Tipps: ',
	'FORMEL_BACK_TO_TIPP'			=> 'Zurück zum Tipp',
	'FORMEL_USER_STATS'				=> 'Spieler',
	'FORMEL_DRIVER_STATS'			=> 'Fahrer',
	'FORMEL_TEAM_STATS'				=> 'Teams',
	'FORMEL_TOP_MORE'				=> 'Zeige alle',
	'FORMEL_STATS_TITLE'			=> 'Formel 1 Statistik',
	'FORMEL_POINTS_WON'				=> 'Erzielte Punkte',
	'FORMEL_ALL_POINTS'				=> 'Gesamt Punkte',
	'FORMEL_RULES_TITLE'			=> 'Spielregeln',
	'FORMEL_RULES_GENERAL'			=> 'Allgemeines',
	'FORMEL_PROFILE_WEBTIPP'		=> 'Formel 1 Punkte',
	'FORMEL_PROFILE_RANK'			=> '%s. Platz',
	'FORMEL_PROFILE_NORANK'			=> 'Keine Platzierung',
	'FORMEL_PROFILE_TIPSS'			=> '%s von %s Rennen getippt',
	'FORMEL_RULES_GENERAL_EXP'		=> 'Der F1 WebTipp ist ein Saison begleitendes Tippspiel. Hier kannst Du den anderen Community Mitgliedern zeigen, wer wirklich Ahnung von der Formel 1 hat!<br /><br />Du kannst zu jedem Formel 1 Rennen einen Tipp abgeben und Punkte sammeln. Solltest Du einmal längere Zeit nicht anwesend sein, kannst Du auch jetzt schon Deinen Tipp für die kommenden Rennen abgeben. Diese kannst Du auch jederzeit wieder ändern. Die aktuellen Punktestände von Dir und den anderen Tippern kannst Du auf der Statistikseite einsehen. Du kannst Dir auch die Tipps der anderen User ansehen, indem Du auf der WebTipp Übersichtsseite bei den abgegebenen Tipps auf einen Usernamen klickst. ( Tipps werden allerdings erst nach Erreichen der Deadline angezeigt )',
	'FORMEL_RULES_SCORE'			=> 'Punktevergabe',
	'FORMEL_RULES_SCORE_EXP'		=> 'Getippt wird auf die ersten 10 Plätze, die schnellste Runde, die Anzahl der Ausfälle, sowie die Anzahl der Safety Car Einsätze.',
	'FORMEL_RULES_MENTIONED'		=> 'Hast Du in Deinem Tipp einen Fahrer erwähnt, der als einer der ersten 10 Fahrer ins Ziel kam, erhältst Du dafür <strong>%s</strong>.',
	'FORMEL_RULES_PLACED'			=> 'Wenn Du diesen Fahrer auch noch auf den richtigen Platz gesetzt hast, erhältst Du dafür noch einmal <strong>%s</strong>.',
	'FORMEL_RULES_FASTEST'			=> 'Hast Du den schnellsten Fahrer richtig genannt, bekommst Du dafür <strong>%s</strong>.',
	'FORMEL_RULES_TIRED'			=> 'Für die richtige Anzahl der Ausfälle bekommst Du <strong>%s</strong>.',
	'FORMEL_RULES_SAFETYCAR'		=> 'Für die richtige Anzahl der Safety Car Einsätze bekommst Du <strong>%s</strong>.',
	'FORMEL_RULES_TOTAL'			=> 'Insgesamt kannst Du also mit jedem Tipp (rein theoretisch) <strong>%s</strong> erspielen.',
	'FORMEL_RULES_POINTS'			=> array(
		1	=> 'Punkt',
		2	=> 'Punkte',
	),
	'FORMEL_DEFINE'					=> 'Nicht gesetzt',
	'FORMEL_ACCESS_DENIED'			=> 'Der Zugriff auf den F1 WebTipp ist nur einer bestimmten Benutzergruppe gestattet.<br /><br />Klick %shier%s, um einen Aufnahmeantrag zu stellen<br />Klick %shier%s, um zum Index zurückzukehren',
	'FORMEL_MOD_ACCESS_DENIED'		=> 'Der Zugriff auf die F1 WebTipp Moderation ist nur Moderatoren oder Administratoren gestattet.<br /><br />Klick %shier%s um zum Formel 1 WebTipp zurückzukehren.<br />Klick %shier%s, um zum Index zurückzukehren',
	'FORMEL_ERROR_MODE' 			=> 'Fehler ! Mode unbekannt !<br /><br />Klick %shier%s um zum F1 WebTipp zurückzukehren.<br />Klick %shier%s, um zum Index zurückzukehren',
	'FORMEL_CLOSE_WINDOW'			=> 'Fenster schliessen',
	'FORMEL_HIDDEN'					=> 'Verdeckt bis Deadline',
	'FORMEL_COUNTDOWN_DEADLINE'		=> 'Countdown bis Deadline',
	'FORMEL_DEADLINE_REACHED'		=> 'Countdown beendet',

	'FORMEL_GUESTS_PLACE_NO_TIP'	=> '<strong>Gäste können keinen Tipp abgeben.</strong><br /><br />Um Tipps abgeben zu können, musst Du registriert und angemeldet sein.<br />',
	'FORMEL_RACE_ABORD'				=> 'Rennabruch (halbe Punktzahl!)',
	'FORMEL_RACE_DOUBLE'			=> 'Rennen mit doppelter Punktzahl',

	'VIEWING_F1WEBTIPP'				=> 'Betrachtet den F1 WebTipp',

	'FORMEL_MAIL_ADMIN'				=> 'F1 WebTipp - Versendete Erinnerungs-Mails für Rennen in %1$s',
	'FORMEL_MAIL_ADMIN_MESSAGE'		=> 'Mail wurde an folgende User gesendet: %1$s',
	'FORMEL_LOG'					=> 'F1 WebTipp Erinnerungs-Mail gesendet an: %1$s',
	'FORMEL_LOG_ERROR'				=> '<strong>F1 WebTipp - Erinnerungsmail an %1$s nicht erfolgreich.</strong>',

	'LOG_FORMEL_TIP_GIVEN'			=> 'F1 WebTipp für Rennen %s abgegeben',
	'LOG_FORMEL_TIP_EDITED'			=> 'F1 WebTipp für Rennen %s bearbeitet',
	'LOG_FORMEL_TIP_NOT_VALID'		=> 'F1 WebTipp für Rennen %s ungültig. Tipp wurde abgewiesen',
	'LOG_FORMEL_TIP_DELETED'		=> 'F1 WebTipp für Rennen %s gelöscht',
	'LOG_FORMEL_QUALI_DELETED'		=> 'F1 WebTipp Qualifying Ergebnis für Rennen %s gelöscht',
	'LOG_FORMEL_QUALI_ADDED'		=> 'F1 WebTipp Qualifying Ergebnis für Rennen %s eingetragen',
	'LOG_FORMEL_QUALI_NOT_VALID'	=> 'F1 WebTipp Qualifying Ergebnis für Rennen %s ungültig. Eintragung wurde abgewiesen',
	'LOG_FORMEL_RESULT_DELETED'		=> 'F1 WebTipp Rennergebnis für Rennen %s gelöscht',
	'LOG_FORMEL_RESULT_ADDED'		=> 'F1 WebTipp Rennergebnis für Rennen %s eingetragen',
	'LOG_FORMEL_RESULT_NOT_VALID'	=> 'F1 WebTipp Rennergebnis für Rennen %s ungültig. Eintragung wurde abgewiesen',
	'LOG_FORMEL_SAISON_RESET'		=> 'F1 WebTipp Saison zurückgesetzt.',
	'LOG_FORMEL_SETTINGS'			=> 'F1 WebTipp Einstellungen aktualisiert',
	'LOG_FORMEL_RACE_ADDED'			=> 'F1 WebTipp Rennen %s hinzugefügt',
	'LOG_FORMEL_RACE_EDITED'		=> 'F1 WebTipp Rennen %s bearbeitet',
	'LOG_FORMEL_RACE_DELETED'		=> 'F1 WebTipp Rennen %s gelöscht',
	'LOG_FORMEL_TEAM_ADDED'			=> 'F1 WebTipp Team %s hinzugefügt',
	'LOG_FORMEL_TEAM_EDITED'		=> 'F1 WebTipp Team %s bearbeitet',
	'LOG_FORMEL_TEAM_DELETED'		=> 'F1 WebTipp Team %s gelöscht',
	'LOG_FORMEL_DRIVER_ADDED'		=> 'F1 WebTipp Fahrer %s hinzugefügt',
	'LOG_FORMEL_DRIVER_EDITED'		=> 'F1 WebTipp Fahrer %s bearbeitet',
	'LOG_FORMEL_DRIVER_DELETED'		=> 'F1 WebTipp Fahrer %s gelöscht',
	'LOG_FORMEL_CRON'				=> 'F1 WebTipp Cronjob wurde ausgeführt.',

	'ACP_FORMEL_SETTINGS'			=> 'F1 Einstellungen',
	'ACP_FORMEL_DRIVERS'			=> 'F1 Fahrer',
	'ACP_FORMEL_TEAMS'				=> 'F1 Teams',
	'ACP_FORMEL_RACES'				=> 'F1 Rennen',

	'ACP_F1WEBTIP_TITLE'			=> 'F1 WebTipp Module',
	'ACP_F1WEBTIP_SETTING_SAVED'	=> 'Formel 1 Konfiguration erfolgreich gespeichert!',

	'ACP_F1_MANAGEMENT'								=> 'F1 WebTipp',
	'ACP_F1_SETTINGS'								=> 'F1 Einstellungen',
	'ACP_F1_SETTINGS_EXPLAIN'						=> 'Hier kannst Du die F1 WebTipp Konfiguration bearbeiten',
	'ACP_F1_SETTINGS_CONFIG'						=> 'F1 Konfiguration',
	'ACP_F1_SETTINGS_MODERATOR'						=> 'F1 WebTipp Moderator',
	'ACP_F1_SETTINGS_MODERATOR_EXPLAIN'				=> 'Dies muss ein Mitglied einer Moderatorengruppe sein',
	'ACP_F1_SETTINGS_DEACTIVATED'					=> '*** de-aktiviert ***',
	'ACP_F1_SETTINGS_UPDATED'						=> 'F1 Einstellungen erfolgreich aktualisiert',
	'ACP_F1_SETTINGS_ACCESS_GROUP'					=> 'F1 WebTipp Gruppe',
	'ACP_F1_SETTINGS_ACCESS_GROUP_EXPLAIN'			=> 'Hier kannst Du den Zugriff auf den F1 WebTipp auf eine bestimmte Gruppe beschränken',
	'ACP_F1_SETTINGS_OFFSET'						=> 'Deadline Offset',
	'ACP_F1_SETTINGS_OFFSET_EXPLAIN'				=> 'Hier kannst Du die Deadline für die Tippabgabe bestimmten.<br />(Zeit in Sekunden bis zum Rennbeginn)',
	'ACP_F1_SETTINGS_RACEOFFSET'					=> 'Aktuelles Rennen',
	'ACP_F1_SETTINGS_RACEOFFSET_EXPLAIN'			=> 'Hier wird festgelegt wann das "Aktuelle Rennen" wechselt.<br />(Zeit in Sekunden nach Rennbeginn)',
	'ACP_F1_SETTINGS_FORUM'							=> 'F1 Forum',
	'ACP_F1_SETTINGS_FORUM_EXPLAIN'					=> 'Hier kann das Diskussionsforum zum F1 WebTipp eingetragen werden',
	'ACP_F1_SETTINGS_SHOW_PROFILE'					=> 'Anzeige im Profil',
	'ACP_F1_SETTINGS_SHOW_PROFILE_EXPLAIN'			=> 'Sollen die Tipp Ergebnisse in den Userprofilen angezeigt werden?',
	'ACP_F1_SETTINGS_SHOW_VIEWTOPIC'				=> 'Anzeige in Beiträgen',
	'ACP_F1_SETTINGS_SHOW_VIEWTOPIC_EXPLAIN'		=> 'Sollen die Tipp Ergebnisse in den Beiträgen angezeigt werden?',
	'ACP_F1_SETTINGS_SHOW_COUNTDOWN'				=> 'Countdown anzeigen',
	'ACP_F1_SETTINGS_SHOW_COUNTDOWN_EXPLAIN'		=> 'Hier kannst Du festlegen, ob der Countdown im F1 WebTipp gezeigt werden soll.',
	'ACP_F1_SETTINGS_POINTS'						=> 'Punktevergabe',
	'ACP_F1_SETTINGS_POINTS_MENTIONED'				=> 'Erwähnt',
	'ACP_F1_SETTINGS_POINTS_MENTIONED_EXPLAIN'		=> 'Punkte für das Erwähnen eines Fahrers',
	'ACP_F1_SETTINGS_POINTS_PLACED'					=> 'Platziert',
	'ACP_F1_SETTINGS_POINTS_PLACED_EXPLAIN'			=> 'Punkte für das richtige Platzieren eines Fahrers',
	'ACP_F1_SETTINGS_POINTS_FASTEST'				=> 'Schnellste Runde',
	'ACP_F1_SETTINGS_POINTS_FASTEST_EXPLAIN'		=> 'Punkte für die schnellste Runde',
	'ACP_F1_SETTINGS_POINTS_TIRED'					=> 'Ausfälle',
	'ACP_F1_SETTINGS_POINTS_TIRED_EXPLAIN'			=> 'Punkte für die richtige Anzahl Ausfälle',
	'ACP_F1_SETTINGS_SAFETY_CAR'					=> 'Safety Car',
	'ACP_F1_SETTINGS_SAFETY_CAR_EXPLAIN'			=> 'Punkte für die richtige Anzahl von Safety Car Einsätzen',
	'ACP_F1_SETTINGS_PICS'							=> 'Bilder',
	'ACP_F1_SETTINGS_SHOW_HEADBANNER'				=> 'F1 Banner anzeigen',
	'ACP_F1_SETTINGS_SHOW_HEADBANNER_EXPLAIN'		=> 'Hier kannst Du festlegen, ob der F1 Banner im Header gezeigt werden soll.',
	'ACP_F1_SETTINGS_SHOW_AVATAR'					=> 'Avatar anzeigen',
	'ACP_F1_SETTINGS_SHOW_AVATAR_EXPLAIN'			=> 'Hier kannst Du festlegen, ob der Avatar des Benutzers in der Spieler Statistik angezeigt werden soll',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_HEIGHT'			=> 'Banner Höhe',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_HEIGHT_EXPLAIN'	=> 'Hier kannst Du die <strong>Höhe in Px</strong> angeben,<br />in der der Banner dargestellt werden soll',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_WIDTH'			=> 'Banner Breite',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_WIDTH_EXPLAIN'	=> 'Hier kannst Du die <strong>Breite in Px</strong> angeben,<br />in der der Banner dargestellt werden soll',
	'ACP_F1_SETTINGS_HEADBANNER1_IMG'				=> 'Banner F1 Webtipp',
	'ACP_F1_SETTINGS_HEADBANNER1_IMG_EXPLAIN'		=> 'Banner für die F1 WebTipp Übersichtsseite',
	'ACP_F1_SETTINGS_HEADBANNER2_IMG'				=> 'Banner F1 Regeln',
	'ACP_F1_SETTINGS_HEADBANNER2_IMG_EXPLAIN'		=> 'Banner für die F1 WebTipp Regelnseite',
	'ACP_F1_SETTINGS_HEADBANNER3_IMG'				=> 'Banner F1 Statistik',
	'ACP_F1_SETTINGS_HEADBANNER3_IMG_EXPLAIN'		=> 'Banner für die F1 WebTipp Statistikseite',
	'ACP_F1_SETTINGS_SHOW_GFXR'						=> 'Rennstrecken-Grafiken anzeigen',
	'ACP_F1_SETTINGS_SHOW_GFXR_EXPLAIN'				=> 'Sollen die Grafiken der Rennstrecken angezeigt werden?',
	'ACP_F1_SETTINGS_NO_RACE_IMG'					=> 'Standart Rennstrecke',
	'ACP_F1_SETTINGS_NO_RACE_IMG_EXPLAIN'			=> 'Hier kannst Du das Bild angeben, welches angezeigt wird,<br />wenn kein Rennstreckenbild vorhanden ist',
	'ACP_F1_SETTINGS_RACE_IMG_HEIGHT'				=> 'Rennstrecke Höhe',
	'ACP_F1_SETTINGS_RACE_IMG_HEIGHT_EXPLAIN'		=> 'Hier kannst Du die <strong>Höhe in Px</strong> angeben,<br />in der die Rennstrecke dargestellt werden soll',
	'ACP_F1_SETTINGS_RACE_IMG_WIDTH'				=> 'Rennstrecke Breite',
	'ACP_F1_SETTINGS_RACE_IMG_WIDTH_EXPLAIN'		=> 'Hier kannst Du die <strong>Breite in Px</strong> angeben,<br />in der die Rennstrecke dargestellt werden soll',
	'ACP_F1_SETTINGS_SHOW_GFX'						=> 'Fahrer- und Team-Grafiken anzeigen',
	'ACP_F1_SETTINGS_SHOW_GFX_EXPLAIN'				=> 'Sollen die Grafiken der Fahrer und Teams angezeigt werden?',
	'ACP_F1_SETTINGS_NO_CAR_IMG'					=> 'Standart Autobild',
	'ACP_F1_SETTINGS_NO_CAR_IMG_EXPLAIN'			=> 'Hier kannst Du das Bild angeben, welches angezeigt wird,<br />wenn kein Autobild vorhanden ist',
	'ACP_F1_SETTINGS_CAR_IMG_HEIGHT'				=> 'Autobild Höhe',
	'ACP_F1_SETTINGS_CAR_IMG_HEIGHT_EXPLAIN'		=> 'Hier kannst Du die <strong>Höhe in Px</strong> angeben,<br />in der das Autobild dargestellt werden soll',
	'ACP_F1_SETTINGS_CAR_IMG_WIDTH'					=> 'Autobild Breite',
	'ACP_F1_SETTINGS_CAR_IMG_WIDTH_EXPLAIN'			=> 'Hier kannst Du die <strong>Breite in Px</strong> angeben,<br />in der das Autobild dargestellt werden soll',
	'ACP_F1_SETTINGS_NO_DRIVER_IMG'					=> 'Standart Fahrerbild',
	'ACP_F1_SETTINGS_NO_DRIVER_IMG_EXPLAIN'			=> 'Hier kannst Du das Bild angeben, welches angezeigt wird,<br />wenn kein Fahrerbild vorhanden ist',
	'ACP_F1_SETTINGS_DRIVER_IMG_HEIGHT'				=> 'Fahrerbild Höhe',
	'ACP_F1_SETTINGS_DRIVER_IMG_HEIGHT_EXPLAIN'		=> 'Hier kannst Du die <strong>Höhe in Px</strong> angeben,<br />in der das Fahrerbild dargestellt werden soll',
	'ACP_F1_SETTINGS_DRIVER_IMG_WIDTH'				=> 'Fahrerbild Breite',
	'ACP_F1_SETTINGS_DRIVER_IMG_WIDTH_EXPLAIN'		=> 'Hier kannst Du die <strong>Breite in Px</strong> angeben,<br />in der das Fahrerbild dargestellt werden soll',
	'ACP_F1_SETTINGS_NO_TEAM_IMG'					=> 'Standart Teamlogo',
	'ACP_F1_SETTINGS_NO_TEAM_IMG_EXPLAIN'			=> 'Hier kannst Du das Bild angeben, welches angezeigt wird,<br />wenn kein Teamlogo vorhanden ist',
	'ACP_F1_SETTINGS_TEAM_IMG_HEIGHT'				=> 'Teamlogo Höhe',
	'ACP_F1_SETTINGS_TEAM_IMG_HEIGHT_EXPLAIN'		=> 'Hier kannst Du die <strong>Höhe in Px</strong> angeben,<br />in der das Teamlogo dargestellt werden soll',
	'ACP_F1_SETTINGS_TEAM_IMG_WIDTH'				=> 'Teamlogo Breite',
	'ACP_F1_SETTINGS_TEAM_IMG_WIDTH_EXPLAIN'		=> 'Hier kannst Du die <strong>Breite in Px</strong> angeben,<br />in der das Teamlogo dargestellt werden soll',
	'ACP_F1_SETTINGS_SEASON_RESET'					=> 'F1 Saison zurücksetzen',
	'ACP_F1_SETTINGS_SEASON_RESET_EXPLAIN'			=> '<strong>Achtung:</strong> Wenn Du auf den Button klickst, wird die F1 Saison unwiderruflich zurückgesetzt!<br /><br />Nach dem Reset müssen noch die Renntermine der neuen F1 Saison angepasst werden. Der <a href="http://www.lpi-clan.de">Support</a> dieses Mods bietet hierfür SQL-Updates an.',
	'ACP_F1_SETTINGS_SEASON_RESETTED'				=> 'F1 Saison zurückgesetzt. Renntermine aktualisieren!',
	'ACP_F1_SETTING_GUEST_VIEWING'					=> 'F1 WebTipp sichtbar für Gäste',
	'ACP_F1_SETTING_GUEST_VIEWING_EXPLAIN'			=> 'Nur möglich wenn Zugriff auf eine <strong>F1 WebTipp Gruppe</strong> deaktiviert ist.',
	'ACP_F1_SETTINGS_REMINDER_ENABLED'				=> 'Cronjob für F1 Erinnerungsmail aktivieren',
	'ACP_F1_SETTINGS_REMINDER_ENABLED_EXPLAIN'		=> 'Hier kannst Du angeben, ob 2 bis 3 Tage vor dem Rennbeginn eine F1 Erinnerungsmail versendet werden soll.<br /><strong>Hinweis: </strong>Kann nur aktiviert werden, wenn der F1 WebTipp auf eine bestimmte <strong>F1 WebTipp Gruppe</strong> beschränkt worden ist.',

	'ACP_F1_DRIVERS'								=> 'F1 Fahrer',
	'ACP_F1_DRIVERS_EXPLAIN'						=> 'Hier kannst Du neue F1 Fahrer erstellen oder vorhandene bearbeiten',
	'ACP_F1_DRIVERS_ADD'							=> 'Eintragen',
	'ACP_F1_DRIVERS_ADD_DRIVER'						=> 'Neuer Fahrer',
	'ACP_F1_DRIVERS_TITEL_ADD_DRIVER'				=> 'F1 Fahrer eintragen',
	'ACP_F1_DRIVERS_TITEL_ADD_DRIVER_EXPLAIN'		=> 'Hier kannst Du einen neuen F1 Fahrer erstellen',
	'ACP_F1_DRIVERS_DRIVERNAME'						=> 'Fahrername',
	'ACP_F1_DRIVERS_DRIVERIMAGE'					=> 'Portrait',
	'ACP_F1_DRIVERS_DRIVERTEAM'						=> 'Team',
	'ACP_F1_DRIVERS_DRIVERPOINTS'					=> 'WM Punkte',
	'ACP_F1_DRIVERS_EDIT_DRIVER'					=> 'Bearbeiten',
	'ACP_F1_DRIVERS_TITEL_EDIT_DRIVER'				=> 'F1 Fahrer bearbeiten',
	'ACP_F1_DRIVERS_TITEL_EDIT_DRIVER_EXPLAIN'		=> 'Hier kannst Du einen F1 Fahrer bearbeiten',
	'ACP_F1_DRIVERS_DELETE_DRIVER'					=> 'Löschen',
	'ACP_F1_DRIVERS_DRIVER_DELETED'					=> 'Der Fahrer wurde erfolgreich gelöscht',
	'ACP_F1_DRIVERS_DRIVER_DELETE_CONFIRM'			=> '<br/>Bist Du dir sicher, dass Du den Fahrer %s löschen möchtest?',
	'ACP_F1_DRIVERS_DRIVER_UPDATED'					=> 'Fahrer Datenbank erfolgreich aktualisiert',
	'ACP_F1_DRIVERS_ERROR_IMAGE'					=> 'Bitte gib ein Fahrerbild an',
	'ACP_F1_DRIVERS_ERROR_DRIVERNAME'				=> 'Bitte gib einen Fahrernamen an',
	'ACP_F1_DRIVERS_PENALTY'						=> 'Strafpunkte',
	'ACP_F1_DRIVERS_DISABLED'						=> 'Auswählbar',

	'ACP_F1_TEAMS'									=> 'F1 Teams',
	'ACP_F1_TEAMS_EXPLAIN'							=> 'Hier kannst Du neue F1 Teams erstellen oder vorhandene bearbeiten',
	'ACP_F1_TEAMS_ADD_TEAM'							=> 'Neues Team',
	'ACP_F1_TEAMS_ADDTEAM_TITLE'					=> 'F1 Team eintragen',
	'ACP_F1_TEAMS_ADDTEAM_TITLE_EXPLAIN'			=> 'Hier kannst Du ein neues F1 Team erstellen',
	'ACP_F1_TEAMS_ADDTEAM_TEAMNAME'					=> 'Teamname',
	'ACP_F1_TEAMS_ADDTEAM_TEAMIMAGE'				=> 'Team Logo',
	'ACP_F1_TEAMS_ADDTEAM_TEAMCAR'					=> 'Team Auto',
	'ACP_F1_TEAMS_ADD'								=> 'Eintragen',
	'ACP_F1_TEAMS_EDITTEAM_TITLE'					=> 'F1 Team bearbeiten',
	'ACP_F1_TEAMS_EDITTEAM_TITLE_EXPLAIN'			=> 'Hier kannst Du ein F1 Team bearbeiten',
	'ACP_F1_TEAMS_DRIVERTEAM'						=> 'Team',
	'ACP_F1_TEAMS_DRIVERPOINTS'						=> 'WM Punkte',
	'ACP_F1_TEAMS_EDIT_TEAM'						=> 'Bearbeiten',
	'ACP_F1_TEAMS_DELETE_TEAM'						=> 'Löschen',
	'ACP_F1_TEAMS_TEAM_UPDATED'						=> 'Team Datenbank erfolgreich aktualisiert',
	'ACP_F1_TEAMS_TEAM_DELETE_CONFIRM'				=> '<br/>Bist Du dir sicher, dass Du das Team %s löschen möchtest?',
	'ACP_F1_TEAMS_TEAM_DELETED'						=> 'Das Team wurde erfolgreich gelöscht',
	'ACP_F1_TEAMS_ERROR_TEAMNAME'					=> 'Bitte gib einen Teamnamen an',
	'ACP_F1_TEAMS_PENALTY'							=> 'Strafe',

	'ACP_F1_RACES'									=> 'F1 Rennen',
	'ACP_F1_RACES_EXPLAIN'							=> 'Hier kannst Du neue F1 Rennen erstellen oder vorhandene bearbeiten',
	'ACP_F1_RACES_ADD_RACE'							=> 'Neues Rennen',
	'ACP_F1_RACES_TITEL_ADD_RACE'					=> 'F1 Rennen eintragen',
	'ACP_F1_RACES_TITEL_ADD_RACE_EXPLAIN'			=> 'Hier kannst Du ein neues F1 Rennen erstellen',
	'ACP_F1_RACES_RACENAME'							=> 'Austragungsort',
	'ACP_F1_RACES_RACEIMAGE'						=> 'Strecken Logo',
	'ACP_F1_RACES_RACELENGTH'						=> 'Streckenlänge',
	'ACP_F1_RACES_RACEDISTANCE'						=> 'Renndistanz',
	'ACP_F1_RACES_RACELAPS'							=> 'Anzahl der Runden',
	'ACP_F1_RACES_RACEDEBUT'						=> 'Streckendebüt',
	'ACP_F1_RACES_RACETIME'							=> 'Rennbeginn',
	'ACP_F1_RACES_RACEDEAD'							=> 'Deadline',
	'ACP_F1_RACES_EDIT_RACE'						=> 'Bearbeiten',
	'ACP_F1_RACES_TITEL_EDIT_RACE'					=> 'F1 Rennen bearbeiten',
	'ACP_F1_RACES_TITEL_EDIT_RACE_EXPLAIN'			=> 'Hier kannst Du ein F1 Rennen bearbeiten',
	'ACP_F1_RACES_DELETE_RACE'						=> 'Löschen',
	'ACP_F1_RACES_ADD'								=> 'Eintragen',
	'ACP_F1_RACES_RACE_UPDATED'						=> 'Renn Datenbank erfolgreich aktualisiert',
	'ACP_F1_RACES_RACE_DELETE_CONFIRM'				=> '<br/>Bist Du dir sicher, dass Du das Rennen %s löschen möchtest?',
	'ACP_F1_RACES_RACE_DELETED'						=> 'Das Rennen wurde erfolgreich gelöscht',
	'ACP_F1_RACES_ERROR_RACENAME'					=> 'Bitte gib alle benötigten Felder an',

	'FORMEL_DONATE'									=> 'Spende für die Formel 1 Erweiterung: Diese Erweiterung ist, wie alle meine Erweiterungen, völlig kostenlos. Wenn Du davon profitierst hast, kannst Du auch eine Spende über den PayPal-Spendenbutton (Donate) tätigen. Ich würde es begrüßen. Ich verspreche, dass es weder Spam noch Anfragen für weitere Spenden geben wird, obwohl sie immer willkommen wären.',
));
