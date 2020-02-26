<?php
/**
*
* @package phpBB Extension - DrDeath F1WebTip
* @copyright (c) 2020 Dr.Death - www.lpi-clan.de
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
	'FORMEL_YOUR_TIPP'				=> 'Ihr Tipp',
	'FORMEL_YOUR_POINTS'			=> 'Ihre Punkte',
	'FORMEL_GAME_OVER'				=> 'Die Frist ist abgelaufen. Sie können für dieses Rennen keinen Tipp mehr abgeben.',
	'FORMEL_ADD_TIPP'				=> 'Tipp absenden',
	'FORMEL_DEL_TIPP'				=> 'Tipp löschen',
	'FORMEL_EDIT_TIPP'				=> 'Tipp bearbeiten',
	'FORMEL_TIPP_DELETED'			=> 'Der Tipp wurde erfolgreich gelöscht<br><br>Klicken Sie %shier%s, um zum F1 WebTipp zurückzukehren<br><br>Klicken Sie %shier%s, um zum Forum zurückzukehren',
	'FORMEL_DUBLICATE_VALUES'		=> '<span style="color:red; font-weight:bold; font-size: 1.5em">Der Tipp wurde nicht angenommen: Ein Fahrer wurde mehrfach platziert</span><br><br>Klicken Sie %shier%s, um zu dem F1 Tipp zurückzukehren<br><br>Klicken Sie %shier%s, um zum Forum zurückzukehren',
	'FORMEL_ACCEPTED_TIPP'			=> 'Der Tipp wurde erfolgreich eingetragen<br><br>Klicken Sie %shier%s, um für weitere Rennen zu tippen<br><br>Klicken Sie %shier%s, um zum Forum zurückzukehren',
	'FORMEL_RESULTS_TITLE'			=> 'F1 WebTipp Moderation',
	'FORMEL_RESULTS_TITLE_EXP'		=> 'Hier können Sie die Ergebnisse für die einzelnen Rennen eintragen oder bearbeiten',
	'FORMEL_MOD_BUTTON_TEXT'		=> 'Moderation',
	'FORMEL_RESULTS_DELETED'		=> 'Die Rennergebnisse wurden gelöscht<br><br>Klicken Sie %shier%s, um zur F1 WebTipp Moderation zurückzukehren<br><br>Klicken Sie %shier%s, um zum F1 WebTipp zurückzukehren',
	'FORMEL_RESULTS_ERROR'			=> 'Es gab einen Fehler. Bitte versuchen Sie es erneut<br><br>Klicken Sie %shier%s, um zur F1 WebTipp Moderation zurückzukehren<br><br>Klicken Sie %shier%s, um zum F1 WebTipp zurückzukehren',
	'FORMEL_RESULTS_DOUBLE'			=> 'Es wurde ein Fahrer mehrfach eingetragen. Bitte versuchen Sie es erneut<br><br>Klicken Sie %shier%s, um zur F1 WebTipp Moderation zurückzukehren<br><br>Klicken Sie %shier%s, um zum F1 WebTipp zurückzukehren',
	'FORMEL_RESULTS_ACCEPTED'		=> 'Die Ergebnisse wurden eingetragen<br><br>Klicken Sie %shier%s, um zur F1 WebTipp Moderation zurückzukehren<br><br>Klicken Sie %shier%s, um zum F1 WebTipp zurückzukehren',
	'FORMEL_RESULTS_ADD'			=> 'Eintragen',
	'FORMEL_RESULTS_QUALI_TITLE'	=> 'Qualifikation eintragen',
	'FORMEL_RESULTS_RESULT_TITLE'	=> 'Rennergebnis eintragen',
	'FORMEL_TOP_POINTS'				=> 'Punkte',
	'FORMEL_TOP_NAME'				=> 'Top Spieler',
	'FORMEL_TOP_DRIVER'				=> 'Top Fahrer',
	'FORMEL_TOP_TEAMS'				=> 'Top Teams',
	'FORMEL_NO_TIPPS'				=> 'Noch keine Tipps vorhanden',
	'FORMEL_TIPPS_MADE'				=> 'Abgegebene Tipps',
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
	'FORMEL_RULES_GENERAL_EXP'		=> 'Der F1 WebTipp ist ein Saison begleitendes Tippspiel. Hier können Sie den anderen Community Mitgliedern zeigen, wer wirklich Ahnung von der Formel 1 hat!<br><br>Sie können zu jedem Formel 1 Rennen einen Tipp abgeben und Punkte sammeln. Sollten Sie einmal längere Zeit nicht anwesend sein, können Sie auch jetzt schon Ihren Tipp für die kommenden Rennen abgeben. Diese können Sie auch jederzeit wieder ändern. Die aktuellen Punktestände von Ihnen und den anderen Tippern können Sie auf der Statistikseite einsehen. Sie können sich auch die Tipps der anderen User ansehen, indem Sie auf der WebTipp Übersichtsseite bei den abgegebenen Tipps auf einen Usernamen klicken. ( Tipps werden allerdings erst nach Erreichen der Deadline angezeigt )',
	'FORMEL_RULES_SCORE'			=> 'Punktevergabe',
	'FORMEL_RULES_SCORE_EXP'		=> 'Getippt wird auf die ersten 10 Plätze, die schnellste Runde, die Anzahl der Ausfälle, sowie die Anzahl der Safety Car Einsätze.',
	'FORMEL_RULES_MENTIONED'		=> 'Haben Sie in Ihrem Tipp einen Fahrer erwähnt, der als einer der ersten 10 Fahrer ins Ziel kam, erhalten Sie dafür <strong>%s</strong>.',
	'FORMEL_RULES_PLACED'			=> 'Wenn Sie diesen Fahrer auch noch auf den richtigen Platz gesetzt haben, erhalten Sie dafür noch einmal <strong>%s</strong>.',
	'FORMEL_RULES_FASTEST'			=> 'Haben Sie den schnellsten Fahrer richtig genannt, bekommen Sie dafür <strong>%s</strong>.',
	'FORMEL_RULES_TIRED'			=> 'Für die richtige Anzahl der Ausfälle bekommen Sie <strong>%s</strong>.',
	'FORMEL_RULES_SAFETYCAR'		=> 'Für die richtige Anzahl der Safety Car Einsätze bekommen Sie <strong>%s</strong>.',
	'FORMEL_RULES_TOTAL'			=> 'Insgesamt können Sie also mit jedem Tipp (rein theoretisch) <strong>%s</strong> erspielen.',
	'FORMEL_RULES_POINTS'			=> array(
		1	=> 'Punkt',
		2	=> 'Punkte',
	),
	'FORMEL_DEFINE'					=> 'Nicht gesetzt',
	'FORMEL_ACCESS_DENIED'			=> 'Der Zugriff auf den F1 WebTipp ist nur einer bestimmten Benutzergruppe gestattet.<br><br>Klicken Sie %shier%s, um einen Aufnahmeantrag zu stellen<br>Klicken Sie %shier%s, um zum Index zurückzukehren',
	'FORMEL_MOD_ACCESS_DENIED'		=> 'Der Zugriff auf die F1 WebTipp Moderation ist nur Moderatoren oder Administratoren gestattet.<br><br>Klicken Sie %shier%s um zum Formel 1 WebTipp zurückzukehren.<br>Klicken Sie %shier%s, um zum Index zurückzukehren',
	'FORMEL_ERROR_MODE' 			=> 'Fehler ! Mode unbekannt !<br><br>Klicken Sie %shier%s um zum F1 WebTipp zurückzukehren.<br>Klicken Sie %shier%s, um zum Index zurückzukehren',
	'FORMEL_CLOSE_WINDOW'			=> 'Fenster schliessen',
	'FORMEL_HIDDEN'					=> 'Verdeckt bis Deadline',
	'FORMEL_COUNTDOWN_DEADLINE'		=> 'Countdown bis Deadline',
	'FORMEL_DEADLINE_REACHED'		=> 'Countdown beendet',

	'FORMEL_GUESTS_PLACE_NO_TIP'	=> '<strong>Gäste können keinen Tipp abgeben.</strong><br><br>Um Tipps abgeben zu können, müssen Sie registriert und angemeldet sein.<br>',
	'FORMEL_RACE_ABORD'				=> 'Rennabruch (halbe Punktzahl!)',
	'FORMEL_RACE_DOUBLE'			=> 'Rennen mit doppelter Punktzahl',

	'VIEWING_F1WEBTIPP'				=> 'Betrachtet den F1 WebTipp',

	'FORMEL_MAIL_ADMIN'				=> 'F1 WebTipp - Versendete Erinnerungs-Mails für Rennen in %1$s',
	'FORMEL_MAIL_ADMIN_MESSAGE'		=> 'Mail wurde an folgende User gesendet: %1$s',
	'FORMEL_LOG'					=> 'F1 WebTipp Erinnerungs-Mail gesendet an: %1$s',
	'FORMEL_LOG_ERROR'				=> '<strong>F1 WebTipp - Erinnerungsmail an %1$s nicht erfolgreich.</strong>',

	'FORMEL_STATS_TEAMNAME'			=> 'Teamname',
	'FORMEL_STATS_TEAMIMAGE'		=> 'Team Logo',
	'FORMEL_STATS_TEAMCAR'			=> 'Team Auto',
	'FORMEL_STATS_DRIVERNAME'		=> 'Fahrername',
	'FORMEL_STATS_DRIVERIMAGE'		=> 'Portrait',

	'FORMEL_DONATE'					=> 'Spenden Sie für die Formel 1 Erweiterung: Diese Erweiterung ist, wie alle meine Erweiterungen, völlig kostenlos. Wenn Sie davon profitiert haben, könnten Sie auch eine Spende über den PayPal-Spendenbutton (Donate) tätigen. Ich würde es begrüßen. Ich verspreche, dass es weder Spam noch Anfragen für weitere Spenden geben wird, obwohl sie immer willkommen wären.',

));
