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

// Common
$lang = array_merge($lang, [
	'F1WEBTIP_PAGE'					=> 'Formel 1',
	'FORMEL_ACCEPTED_TIPP'			=> 'Der Tipp wurde erfolgreich eingetragen<br><br>Klicke %shier%s, um für weitere Rennen zu tippen<br><br>Klicke %shier%s, um zum Forum zurückzukehren',
	'FORMEL_ACCESS_DENIED'			=> 'Der Zugriff auf den Formel 1 WebTipp ist nur einer bestimmten Benutzergruppe gestattet.<br><br>Klick %shier%s, um einen Aufnahmeantrag zu stellen<br>Klick %shier%s, um zum Index zurückzukehren',
	'FORMEL_ADD_TIPP'				=> 'Tipp absenden',
	'FORMEL_ALL_POINTS'				=> 'Gesamt Punkte',
	'FORMEL_BACK_TO_TIPP'			=> 'Zurück zum Tipp',
	'FORMEL_CALL_MOD'				=> 'Moderator benachrichtigen',
	'FORMEL_CLOSE_WINDOW'			=> 'Fenster schliessen',
	'FORMEL_COUNTDOWN_DEADLINE'		=> 'Countdown bis Deadline',
	'FORMEL_CURRENT_QUALI'			=> 'Qualifikation',
	'FORMEL_CURRENT_RACE'			=> 'Aktuelles Rennen',
	'FORMEL_CURRENT_RESULT'			=> 'Ergebnis',
	'FORMEL_DEADLINE_REACHED'		=> 'Countdown beendet',
	'FORMEL_DEFINE'					=> 'Nicht gesetzt',
	'FORMEL_DEL_TIPP'				=> 'Tipp löschen',
	'FORMEL_DELETE'					=> 'Löschen',
	'FORMEL_DISTANCE_UNIT'			=> 'km',
	'FORMEL_DONATE'					=> 'Spende für die Formel 1 Erweiterung: Diese Erweiterung ist, wie alle meine Erweiterungen, völlig kostenlos. Wenn Du davon profitierst hast, kannst Du auch eine Spende über den PayPal-Spendenbutton (Donate) tätigen. Ich würde es begrüßen. Ich verspreche, dass es weder Spam noch Anfragen für weitere Spenden geben wird, obwohl sie immer willkommen wären.',
	'FORMEL_DRIVER_STATS'			=> 'Fahrer',
	'FORMEL_DUBLICATE_VALUES'		=> '<span style="color:red; font-weight:bold; font-size: 1.5em">Der Tipp wurde nicht angenommen: Ein Fahrer wurde mehrfach platziert</span><br><br>Klicke %shier%s, um zu dem Formel 1 Tipp zurückzukehren<br><br>Klicke %shier%s, um zum Forum zurückzukehren',
	'FORMEL_EDIT_TIPP'				=> 'Tipp bearbeiten',
	'FORMEL_EDIT'					=> 'Bearbeiten',
	'FORMEL_ERROR_MODE' 			=> 'Fehler ! Mode unbekannt !<br><br>Klick %shier%s um zum Formel 1 WebTipp zurückzukehren.<br>Klick %shier%s, um zum Index zurückzukehren',
	'FORMEL_FORUM'					=> 'Forum',
	'FORMEL_GAME_OVER'				=> 'Die Frist ist abgelaufen. Du kannst für dieses Rennen keinen Tipp mehr abgeben.',
	'FORMEL_GUESTS_PLACE_NO_TIP'	=> '<strong>Gäste können keinen Tipp abgeben.</strong><br><br>Um Tipps abgeben zu können, musst Du registriert und angemeldet sein.<br>',
	'FORMEL_HIDDEN'					=> 'Verdeckt bis Deadline',
	'FORMEL_LOG_ERROR'				=> '<strong>Formel 1 WebTipp - Erinnerungsmail an %1$s nicht erfolgreich.</strong>',
	'FORMEL_LOG'					=> 'Formel 1 WebTipp Erinnerungs-Mail gesendet an: %1$s',
	'FORMEL_MAIL_ADMIN_MESSAGE'		=> 'Mail wurde an folgende User gesendet: %1$s',
	'FORMEL_MAIL_ADMIN'				=> 'Formel 1 WebTipp - Versendete Erinnerungs-Mails für Rennen in %1$s',
	'FORMEL_MOD_ACCESS_DENIED'		=> 'Der Zugriff auf die Formel 1 WebTipp Moderation ist nur Moderatoren oder Administratoren gestattet.<br><br>Klick %shier%s um zum Formel 1 WebTipp zurückzukehren.<br>Klick %shier%s, um zum Index zurückzukehren',
	'FORMEL_MOD_BUTTON_TEXT'		=> 'Moderation',
	'FORMEL_NEXT_RACE'				=> 'Nächstes',
	'FORMEL_NO_QUALI'				=> 'Keine Qualifikation verfügbar',
	'FORMEL_NO_RESULTS'				=> 'Kein Ergebnis verfügbar',
	'FORMEL_NO_TIPP'				=> 'Es wurde kein Tipp gefunden',
	'FORMEL_NO_TIPPS'				=> 'Noch keine Tipps vorhanden',
	'FORMEL_PACE'					=> 'Schnellste Runde',
	'FORMEL_PLACE'					=> 'Platz',
	'FORMEL_POINTS_WON'				=> 'Erzielte Punkte',
	'FORMEL_POLE'					=> 'Poleposition',
	'FORMEL_PREV_RACE'				=> 'Vorheriges',
	'FORMEL_PROFILE_NORANK'			=> 'Keine Platzierung',
	'FORMEL_PROFILE_RANK'			=> '%s. Platz',
	'FORMEL_PROFILE_TIPSS'			=> '%s von bisher %s Rennen getippt',
	'FORMEL_PROFILE_WEBTIPP'		=> 'Formel 1 Punkte',
	'FORMEL_RACE_ABORD'				=> 'Rennabruch (halbe Punktzahl!)',
	'FORMEL_RACE_DOUBLE'			=> 'Rennen mit doppelter Punktzahl',
	'FORMEL_RACE_WINNER'			=> 'Sieger',
	'FORMEL_RACEDEAD'				=> 'Deadline',
	'FORMEL_RACEDEBUT'				=> 'Streckendebüt',
	'FORMEL_RACEDISTANCE'			=> 'Renndistanz',
	'FORMEL_RACELAPS'				=> 'Anzahl Runden',
	'FORMEL_RACELENGTH'				=> 'Streckenlänge',
	'FORMEL_RACENAME'				=> 'Rennstrecke',
	'FORMEL_RACETIME'				=> 'Rennstart',
	'FORMEL_RESULTS_ACCEPTED'		=> 'Die Ergebnisse wurden eingetragen<br><br>Klicke %shier%s, um zur Formel 1 WebTipp Moderation zurückzukehren<br><br>Klicke %shier%s, um zum Formel 1 WebTipp zurückzukehren',
	'FORMEL_RESULTS_ADD'			=> 'Eintragen',
	'FORMEL_RESULTS_DELETED'		=> 'Die Rennergebnisse wurden gelöscht<br><br>Klicke %shier%s, um zur Formel 1 WebTipp Moderation zurückzukehren<br><br>Klicke %shier%s, um zum Formel 1 WebTipp zurückzukehren',
	'FORMEL_RESULTS_DOUBLE'			=> 'Es wurde ein Fahrer mehrfach eingetragen. Bitte versuche es erneut<br><br>Klicke %shier%s, um zur Formel 1 WebTipp Moderation zurückzukehren<br><br>Klicke %shier%s, um zum Formel 1 WebTipp zurückzukehren',
	'FORMEL_RESULTS_ERROR'			=> 'Es gab einen Fehler. Bitte versuche es erneut<br><br>Klicke %shier%s, um zur Formel 1 WebTipp Moderation zurückzukehren<br><br>Klicke %shier%s, um zum Formel 1 WebTipp zurückzukehren',
	'FORMEL_RESULTS_QUALI_TITLE'	=> 'Qualifikation eintragen',
	'FORMEL_RESULTS_RESULT_TITLE'	=> 'Rennergebnis eintragen',
	'FORMEL_RESULTS_TITLE_EXP'		=> 'Hier kannst Du die Ergebnisse für die einzelnen Rennen eintragen oder bearbeiten',
	'FORMEL_RESULTS_TITLE'			=> 'Formel 1 WebTipp Moderation',
	'FORMEL_RULES_FASTEST'			=> 'Hast Du den schnellsten Fahrer richtig genannt, bekommst Du dafür <strong>%s</strong>.',
	'FORMEL_RULES_GENERAL_EXP'		=> 'Der Formel 1 WebTipp ist ein Saison begleitendes Tippspiel. Hier kannst Du den anderen Community Mitgliedern zeigen, wer wirklich Ahnung von der Formel 1 hat!<br><br>Du kannst zu jedem Formel 1 Rennen einen Tipp abgeben und Punkte sammeln. Solltest Du einmal längere Zeit nicht anwesend sein, kannst Du auch jetzt schon Deinen Tipp für die kommenden Rennen abgeben. Diese kannst Du auch jederzeit wieder ändern. Die aktuellen Punktestände von Dir und den anderen Tippern kannst Du auf der Statistikseite einsehen. Du kannst Dir auch die Tipps der anderen User ansehen, indem Du auf der WebTipp Übersichtsseite bei den abgegebenen Tipps auf einen Usernamen klickst. ( Tipps werden allerdings erst nach Erreichen der Deadline angezeigt )',
	'FORMEL_RULES_GENERAL'			=> 'Allgemeines',
	'FORMEL_RULES_MENTIONED'		=> 'Hast Du in Deinem Tipp einen Fahrer erwähnt, der als einer der ersten 10 Fahrer ins Ziel kam, erhältst Du dafür <strong>%s</strong>.',
	'FORMEL_RULES_PLACED'			=> 'Wenn Du diesen Fahrer auch noch auf den richtigen Platz gesetzt hast, erhältst Du dafür noch einmal <strong>%s</strong>.',
	'FORMEL_RULES_SAFETYCAR'		=> 'Für die richtige Anzahl der Safety Car Einsätze bekommst Du <strong>%s</strong>.',
	'FORMEL_RULES_SCORE_EXP'		=> 'Getippt wird auf die ersten 10 Plätze, die schnellste Runde, die Anzahl der Ausfälle, sowie die Anzahl der Safety Car Einsätze.',
	'FORMEL_RULES_SCORE'			=> 'Punktevergabe',
	'FORMEL_RULES_TIRED'			=> 'Für die richtige Anzahl der Ausfälle bekommst Du <strong>%s</strong>.',
	'FORMEL_RULES_TITLE'			=> 'Spielregeln',
	'FORMEL_RULES_TOTAL'			=> 'Insgesamt kannst Du also mit jedem Tipp (rein theoretisch) <strong>%s</strong> erspielen.',
	'FORMEL_RULES'					=> 'Regeln',
	'FORMEL_SAFETYCAR'				=> 'Safety Car Einsätze',
	'FORMEL_STATISTICS'				=> 'Statistik',
	'FORMEL_STATS_DRIVERIMAGE'		=> 'Portrait',
	'FORMEL_STATS_DRIVERNAME'		=> 'Fahrername',
	'FORMEL_STATS_TEAMCAR'			=> 'Team Auto',
	'FORMEL_STATS_TEAMIMAGE'		=> 'Team Logo',
	'FORMEL_STATS_TEAMNAME'			=> 'Teamname',
	'FORMEL_STATS_TITLE'			=> 'Formel 1 Statistik',
	'FORMEL_TEAM_STATS'				=> 'Teams',
	'FORMEL_TIPP_DELETED'			=> 'Der Tipp wurde erfolgreich gelöscht<br><br>Klicke %shier%s, um zum Formel 1 WebTipp zurückzukehren<br><br>Klicke %shier%s, um zum Forum zurückzukehren',
	'FORMEL_TIPPS_MADE'				=> 'Abgegebene Tipps',
	'FORMEL_TIRED'					=> 'Anzahl Ausfälle',
	'FORMEL_TITLE'					=> 'Formel 1 WebTipp',
	'FORMEL_TOP_DRIVER'				=> 'Top Fahrer',
	'FORMEL_TOP_MORE'				=> 'Zeige alle',
	'FORMEL_TOP_NAME'				=> 'Top Spieler',
	'FORMEL_TOP_POINTS'				=> 'Punkte',
	'FORMEL_TOP_TEAMS'				=> 'Top Teams',
	'FORMEL_USER_STATS'				=> 'Spieler',
	'FORMEL_YOUR_POINTS'			=> 'Deine Punkte',
	'FORMEL_YOUR_TIPP'				=> 'Dein Tipp',
	'VIEWING_F1WEBTIPP'				=> 'Betrachtet den Formel 1 WebTipp',

	'FORMEL_RULES_POINTS'			=> [
		1	=> 'Punkt',
		2	=> 'Punkte',
	],

]);
