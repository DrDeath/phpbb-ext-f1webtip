<?php
/**
 * Formula 1 WebTip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2014, Dr.Death, http://www.lpi-clan.de
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

/**
 * DO NOT CHANGE.
 */
if (!defined('IN_PHPBB')) {
    exit;
}

if (empty($lang) || !is_array($lang)) {
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
    'F1WEBTIP_PAGE'					          => 'Formule 1',
    'FORMEL_ACCEPTED_TIPP'			     => 'You tip was accepted<br><br>Klik %shier%s om meer voorspellingen te maken<br><br>Klik %shier%s om terug te gaan naar het forum',
    'FORMEL_ACCESS_DENIED'			     => 'Toegang niet toegestaan. Je moet een geregistreerde gebruiker zijn om voorspellingen  te kunnen maken.<br><br>Klik %shier%s om je te registreren<br>Klik %shier%s om terug te gaan naar het forum',
    'FORMEL_ADD_TIPP'				         => 'Verstuur voorspelling',
    'FORMEL_ALL_POINTS'				       => 'Totaal punten',
    'FORMEL_BACK_TO_TIPP'			      => 'Terug naar voorspellingen',
    'FORMEL_CALL_MOD'				         => 'vraag naar de moderator',
    'FORMEL_CLOSE_WINDOW'			      => 'Sluit window',
    'FORMEL_COUNTDOWN_DEADLINE'		 => 'Aftellen tot de deadline',
    'FORMEL_CURRENT_QUALI'			     => 'Qualificatie',
    'FORMEL_CURRENT_RACE'			      => 'Race',
    'FORMEL_CURRENT_RESULT'			    => 'Resultaat',
    'FORMEL_DEADLINE_REACHED'		   => 'Deadline is bereikt',
    'FORMEL_DEFINE'					          => 'Niet geplaatst',
    'FORMEL_DEL_TIPP'				         => 'Verwijder voorspelling',
    'FORMEL_DELETE'					          => 'Delete',
    'FORMEL_DONATE'					          => 'Donatie voor de Formule 1-extensie: deze extensie is, net als al mijn extensies, volledig gratis. Als u hiervan hebt geprofiteerd, kunt u ook een donatie doen met de PayPal-donatieknop. Ik zou het waarderen. Ik beloof dat er geen spam of verzoeken om verdere donaties zullen zijn, hoewel ze altijd welkom zouden zijn.',
    'FORMEL_DRIVER_STATS'			      => 'Coureur',
    'FORMEL_DUBLICATE_VALUES'		   => 'Fout bij het versturen van jou voorspelling:Error while sending your tip: Je hebt een coureur meerdere keren voorspeld<br><br>Klik %shier%s om terug te gaan naar het overzicht van Formule 1 Voorspellingen<br><br>Klik %shier%s om terug te gaan naar het forum',
    'FORMEL_EDIT_TIPP'				        => 'Aanpassen voorspelling',
    'FORMEL_EDIT'					            => 'Aanpassen',
    'FORMEL_ERROR_MODE' 			       => 'Fout ! Onbekende modus !<br><br>Klik %shier%s om terug te gaan naar Formule 1 Voorspellingen.<br>Klik %shier%s om terug te gaan naar het forum',
    'FORMEL_FORUM'					           => 'Forum',
    'FORMEL_GAME_OVER'				        => 'Tijd om te voorspellen is voorbij. Je kan geen voorspellingen meer doen.',
    'FORMEL_GUESTS_PLACE_NO_TIP'	 => '<strong>Gasten kunnen geen voorspellingen maken.</strong><br><br>Om een voorspelling te maken moet je registreren of inloggen.<br>',
    'FORMEL_HIDDEN'					          => 'Sluiten tot deadline',
    'FORMEL_LOG_ERROR'				        => '<strong>Formule 1 Voorspellingen - herinnering e-mail naar %1$s is niet verstuurd.</strong>',
    'FORMEL_LOG'					             => 'Formule 1 Voorspellingen - herinnering e-mail verstuurd naar: %1$s',
    'FORMEL_MAIL_ADMIN_MESSAGE'		 => 'E-mail is verstuurd naar de volgende gebruikers: %1$s',
    'FORMEL_MAIL_ADMIN'				       => 'Formule 1 Voorspellingen - Stuur een herinnering e-mail voor de race %1$s dagen voor aanvang',
    'FORMEL_MOD_ACCESS_DENIED'		  => 'Toegang niet toegestaan. Je moet een moderator of administrator zijn om het moderatie panel te kunnen bedienen.<br><br>Klik %shier%s om terug te gaan naar Formule 1 Voorspellingen.<br>Klik %shier%s om terug te gaan naar het forum',
    'FORMEL_MOD_BUTTON_TEXT'		    => 'Moderatie',
    'FORMEL_NEXT_RACE'				        => 'Volgende',
    'FORMEL_NO_QUALI'				         => 'Geen resultaten gevonden',
    'FORMEL_NO_RESULTS'				       => 'Geen resultaten gevonden',
    'FORMEL_NO_TIPP'				          => 'Geen voorspellingen gevonden',
    'FORMEL_NO_TIPPS'				         => 'Geen voorspelling gemaakt',
    'FORMEL_PACE'					            => 'Snelst gereden ronde',
    'FORMEL_PLACE'					           => 'Plaats',
    'FORMEL_POINTS_WON'				       => 'Punten',
    'FORMEL_POLE'					            => 'Pole position',
    'FORMEL_PREV_RACE'				        => 'Vorige',
    'FORMEL_PROFILE_NORANK'			    => 'Geen plaats',
    'FORMEL_PROFILE_RANK'			      => '%s. Plaats',
    'FORMEL_PROFILE_TIPSS'			     => '%s van de %s races zijn voorspeld',
    'FORMEL_PROFILE_WEBTIPP'		    => 'Formule 1 punten',
    'FORMEL_RACE_ABORD'				       => 'Race afgebroken (halve punten!)',
    'FORMEL_RACE_DOUBLE'			       => 'Race met dubbele punten',
    'FORMEL_RACE_WINNER'			       => 'Winnaar',
    'FORMEL_RACEDEAD'				         => 'Deadline',
    'FORMEL_RACEDEBUT'				        => 'Eerste verreden race op dit circuit',
    'FORMEL_RACEDISTANCE'			      => 'Lengte van de totale race',
    'FORMEL_RACELAPS'				         => 'Aantal te rijden rondes',
    'FORMEL_RACELENGTH'				       => 'Lengte van de ronde',
    'FORMEL_RACENAME'				         => 'Locatie',
    'FORMEL_RACETIME'				         => 'Race begint om',
    'FORMEL_RESULTS_ACCEPTED'		   => 'Resultaten zijn opgeslagen<br><br>Klik %shier%s om terug te gaan naar Formule 1 Voorspellingen moderatie<br><br>Klik %shier%s om terug te gaan naar Formule 1 voorspellingen',
    'FORMEL_RESULTS_ADD'			       => 'Toevoegen',
    'FORMEL_RESULTS_DELETED'		    => 'Resultaten zijn verwijderd<br><br>Klik %shier%s om terug te gaan naar Formule 1 Voorspellingen moderatie<br><br>Klik %shier%s om terug te gaan naar Formule 1 voorspellingen',
    'FORMEL_RESULTS_DOUBLE'			    => 'Je hebt een coureur meerdere malen geplaatst. Probeer het nog een keer<br><br>Klik %shier%s om terug te gaan naar Formule 1 Voorspellingen moderatie<br><br>Klik %shier%s om terug te gaan naar Formule 1 voorspellingen',
    'FORMEL_RESULTS_ERROR'			     => 'Fout bij het opslaan. Probeer het nog een keer<br><br>Klik %shier%s om terug te gaan naar Formule 1 Voorspellingen moderatie<br><br>Klik %shier%s om terug te gaan naar Formule 1 voorspellingen',
    'FORMEL_RESULTS_QUALI_TITLE'	 => 'Toevoegen qualificatie',
    'FORMEL_RESULTS_RESULT_TITLE'	=> 'Aanpassen race resultaten',
    'FORMEL_RESULTS_TITLE_EXP'		  => 'Hier kun je toevoegen of veranderen de resultaten van elk willekeurige race',
    'FORMEL_RESULTS_TITLE'			     => 'Formule 1 Voorspellingen moderatie',
    'FORMEL_RULES_FASTEST'			     => 'Als je de juiste coureur met de snelste ronde voorspeld, dan krijg je <strong>%s</strong>.',
    'FORMEL_RULES_GENERAL_EXP'		  => 'Voor iedere race kan je een voorspelling doen en punten verzamelen. Als je voor een langere tijd weg bent, Je kan races van tevoren voorspellen en aanpassen wanneer je dit wilt. Je kunt de huidige stand bekijken op de statistieken pagina. Als je wilt weten wat de andere gebruikers hebben voorspeld, klik dan op hun naam op de overzichts pagina.(Voorspellingen kunnen alleen bekeken worden als de deadline is bereikt)<br>',
    'FORMEL_RULES_GENERAL'			     => 'Algemeen',
    'FORMEL_RULES_MENTIONED'		    => 'Voor voorspelling van een courreur (ongeacht plaats) in de top 10, krij je <strong>%s</strong>.',
    'FORMEL_RULES_PLACED'			      => 'Voor voorspelling van een courreur (op de juiste plaats) in de top 10, krij je <strong>%s</strong>.',
    'FORMEL_RULES_SAFETYCAR'		    => 'Voor voorspelling van het aantal Saftey Cars, krij je <strong>%s</strong>.',
    'FORMEL_RULES_SCORE_EXP'		    => 'Je kan de voorspellingen maken voor de eerste tien coureurs, de snelste ronden, het totaal aantal banden wissels en het aantal saftey cars.',
    'FORMEL_RULES_SCORE'			       => 'Punten',
    'FORMEL_RULES_TIRED'			       => 'Voor voorspelling van het aantal banden wissels, krij je <strong>%s</strong>.',
    'FORMEL_RULES_TITLE'			       => 'Regels',
    'FORMEL_RULES_TOTAL'			       => 'In totaal kan je <strong>%s</strong> behalen.',
    'FORMEL_RULES'					           => 'Regels',
    'FORMEL_SAFETYCAR'				        => 'Safety Cars',
    'FORMEL_STATISTICS'				       => 'Statistieken',
    'FORMEL_STATS_DRIVERIMAGE'		  => 'Coureur afbeelding',
    'FORMEL_STATS_DRIVERNAME'		   => 'Naam coureur',
    'FORMEL_STATS_TEAMCAR'			     => 'Team Auto',
    'FORMEL_STATS_TEAMIMAGE'		    => 'Team Logo',
    'FORMEL_STATS_TEAMNAME'			    => 'Team naam',
    'FORMEL_STATS_TITLE'			       => 'Formule 1 statistieken',
    'FORMEL_TEAM_STATS'				       => 'Teams',
    'FORMEL_TIPP_DELETED'			      => 'Voorspelling is verwijderd<br><br>Klik %shier%s om terug te gaan naar het overzicht van Formule 1 Voorspellingen<br><br>Klik %shier%s om terug te gaan naar het forum',
    'FORMEL_TIPPS_MADE'				       => 'voorspelling geplaatst',
    'FORMEL_TIRED'					           => 'Tired count',
    'FORMEL_TITLE'					           => 'Formule 1 Voorspellingen',
    'FORMEL_TOP_DRIVER'				       => 'Top coureurs',
    'FORMEL_TOP_MORE'				         => 'Toon alle',
    'FORMEL_TOP_NAME'				         => 'Top spelers',
    'FORMEL_TOP_POINTS'				       => 'Punten',
    'FORMEL_TOP_TEAMS'				        => 'Top teams',
    'FORMEL_USER_STATS'				       => 'Gebruiker',
    'FORMEL_YOUR_POINTS'			       => 'Jouw punten',
    'FORMEL_YOUR_TIPP'				        => 'Jou voorspelling',
    'VIEWING_F1WEBTIPP'				       => 'Bekijk Formule 1 Voorspellingen',

    'FORMEL_RULES_POINTS'			=> [
        1	=> 'Punt',
        2	=> 'Punten',
    ],

]);
