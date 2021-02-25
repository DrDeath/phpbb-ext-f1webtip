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
    'F1WEBTIP_PAGE'					          => 'Formula 1',
    'FORMEL_ACCEPTED_TIPP'			     => 'You tip was accepted<br><br>Click %shere%s to place more Formula 1 tips<br><br>Click %shere%s to go back to forum',
    'FORMEL_ACCESS_DENIED'			     => 'Access denied. You have to be a certain group member to join this tip.<br><br>Click %shere%s to ask for membership<br>Click %shere%s to go back to forum',
    'FORMEL_ADD_TIPP'				         => 'Send tip',
    'FORMEL_ALL_POINTS'				       => 'Total points',
    'FORMEL_BACK_TO_TIPP'			      => 'Back to tip',
    'FORMEL_CALL_MOD'				         => 'Contact moderator',
    'FORMEL_CLOSE_WINDOW'			      => 'Close window',
    'FORMEL_COUNTDOWN_DEADLINE'		 => 'Countdown till deadline',
    'FORMEL_CURRENT_QUALI'			     => 'Qualification',
    'FORMEL_CURRENT_RACE'			      => 'Current race',
    'FORMEL_CURRENT_RESULT'			    => 'Result',
    'FORMEL_DEADLINE_REACHED'		   => 'Deadline reached',
    'FORMEL_DEFINE'					          => 'Not placed',
    'FORMEL_DEL_TIPP'				         => 'Delete tip',
    'FORMEL_DELETE'					          => 'Delete',
    'FORMEL_DONATE'					          => 'Donate to my extensions: This extension, as with all of my extensions, is totally free of charge. If you have benefited from using it then please consider making a donation by clicking the PayPal donation button above - I would appreciate it. I promise that there will be no spam nor requests for further donations, although they would always be welcome.',
    'FORMEL_DRIVER_STATS'			      => 'Driver',
    'FORMEL_DUBLICATE_VALUES'		   => 'Error while sending your tip: You placed a driver more than once<br><br>Click %shere%s to go back to the Formula 1 WbTip overview<br><br>Click %shere%s to go back to forum',
    'FORMEL_EDIT_TIPP'				        => 'Edit tip',
    'FORMEL_EDIT'					            => 'Edit',
    'FORMEL_ERROR_MODE' 			       => 'Error ! Unknown Mode !<br><br>Click %shere%s to go back to Formula 1 Webtip.<br>Click %shere%s to go back to forum',
    'FORMEL_FORUM'					           => 'Forum',
    'FORMEL_GAME_OVER'				        => 'Time is over. No changes possible anymore.',
    'FORMEL_GUESTS_PLACE_NO_TIP'	 => '<strong>Guests cannot place a tip.</strong><br><br>In order to place a tip you have to be registered and logged in.<br>',
    'FORMEL_HIDDEN'					          => 'Hidden till deadline',
    'FORMEL_LOG_ERROR'				        => '<strong>Formula 1 WebTip - Reminder mail to %1$s was not successful.</strong>',
    'FORMEL_LOG'					             => 'Formula 1 WebTip - Reminder mail sent to: %1$s',
    'FORMEL_MAIL_ADMIN_MESSAGE'		 => 'Mail was sent to following users: %1$s',
    'FORMEL_MAIL_ADMIN'				       => 'Formula 1 WebTip - Sent reminder mails for race in %1$s',
    'FORMEL_MOD_ACCESS_DENIED'		  => 'Access denied. You have to be a moderator or administrator to access the moderation panel.<br><br>Click %shere%s to go back to Formula 1 Webtip.<br>Click %shere%s to go back to forum',
    'FORMEL_MOD_BUTTON_TEXT'		    => 'Moderation',
    'FORMEL_NEXT_RACE'				        => 'Next',
    'FORMEL_NO_QUALI'				         => 'No qualification found',
    'FORMEL_NO_RESULTS'				       => 'No result found',
    'FORMEL_NO_TIPP'				          => 'No tip found',
    'FORMEL_NO_TIPPS'				         => 'No tips made',
    'FORMEL_PACE'					            => 'Fastest lap',
    'FORMEL_PLACE'					           => 'Place',
    'FORMEL_POINTS_WON'				       => 'Points',
    'FORMEL_POLE'					            => 'Poleposition',
    'FORMEL_PREV_RACE'				        => 'Previous',
    'FORMEL_PROFILE_NORANK'			    => 'No ranking',
    'FORMEL_PROFILE_RANK'			      => '%s. Place',
    'FORMEL_PROFILE_TIPSS'			     => '%s of %s races tiped',
    'FORMEL_PROFILE_WEBTIPP'		    => 'Formula 1 points',
    'FORMEL_RACE_ABORD'				       => 'Race aborted (half points!)',
    'FORMEL_RACE_DOUBLE'			       => 'Race with double points',
    'FORMEL_RACE_WINNER'			       => 'Winner',
    'FORMEL_RACEDEAD'				         => 'Deadline',
    'FORMEL_RACEDEBUT'				        => 'First race',
    'FORMEL_RACEDISTANCE'			      => 'Race length',
    'FORMEL_RACELAPS'				         => 'Laps',
    'FORMEL_RACELENGTH'				       => 'Lap length',
    'FORMEL_RACENAME'				         => 'Location',
    'FORMEL_RACETIME'				         => 'Race begins',
    'FORMEL_RESULTS_ACCEPTED'		   => 'Results saved<br><br>Click %shere%s to go back to Formula 1 WebTip moderation<br><br>Click %shere%s to go back to Formula 1 WebTip',
    'FORMEL_RESULTS_ADD'			       => 'Add',
    'FORMEL_RESULTS_DELETED'		    => 'Results deleted<br><br>Click %shere%s to go back to Formula 1 WebTip moderation<br><br>Click %shere%s to go back to Formula 1 WebTip',
    'FORMEL_RESULTS_DOUBLE'			    => 'You placed a driver more than once. Please try again<br><br>Click %shere%s to go back to Formula 1 WebTip moderation<br><br>Click %shere%s to go back to Formula 1 WebTip',
    'FORMEL_RESULTS_ERROR'			     => 'Error while saving. Please try again<br><br>Click %shere%s to go back to Formula 1 WebTip moderation<br><br>Click %shere%s to go back to Formula 1 WebTip',
    'FORMEL_RESULTS_QUALI_TITLE'	 => 'Add qualification',
    'FORMEL_RESULTS_RESULT_TITLE'	=> 'Edit race results',
    'FORMEL_RESULTS_TITLE_EXP'		  => 'Here you can add or edit every events results',
    'FORMEL_RESULTS_TITLE'			     => 'Formula 1 WebTip moderation',
    'FORMEL_RULES_FASTEST'			     => 'If you got the fastest driver, you can get <strong>%s</strong>.',
    'FORMEL_RULES_GENERAL_EXP'		  => 'Here you can show the other community members who really has a clue of the formula 1.<br><br>For every race you can place a tip and collect points. If you are away for a long time, you can now enter your tips for as many races as you want and change it whenever you want. To see the current ranking just visit the statistics page. If you want to know what the other tipers tiped, just click on their usernames on the overview page ( Tips are only shown if the deadline was reached )',
    'FORMEL_RULES_GENERAL'			     => 'General',
    'FORMEL_RULES_MENTIONED'		    => 'For mention a Top 10 driver you can get <strong>%s</strong>.',
    'FORMEL_RULES_PLACED'			      => 'For placing the exact drivers result you can get <strong>%s</strong>.',
    'FORMEL_RULES_SAFETYCAR'		    => 'For the right count of safety car deployments you can get <strong>%s</strong>.',
    'FORMEL_RULES_SCORE_EXP'		    => 'You can place your tip for the first 10 drivers, such as the fastest lap, the count of tired drivers and the count of safety car deployments.',
    'FORMEL_RULES_SCORE'			       => 'Points',
    'FORMEL_RULES_TIRED'			       => 'For the right tired count you can get <strong>%s</strong>.',
    'FORMEL_RULES_TITLE'			       => 'Rules',
    'FORMEL_RULES_TOTAL'			       => 'In total you can get <strong>%s</strong>.',
    'FORMEL_RULES'					           => 'Rules',
    'FORMEL_SAFETYCAR'				        => 'Safety Cars',
    'FORMEL_STATISTICS'				       => 'Statistics',
    'FORMEL_STATS_DRIVERIMAGE'		  => 'Driver Image',
    'FORMEL_STATS_DRIVERNAME'		   => 'Driver Name',
    'FORMEL_STATS_TEAMCAR'			     => 'Team Car',
    'FORMEL_STATS_TEAMIMAGE'		    => 'Team Logo',
    'FORMEL_STATS_TEAMNAME'			    => 'Team Name',
    'FORMEL_STATS_TITLE'			       => 'Formula 1 statistics',
    'FORMEL_TEAM_STATS'				       => 'Teams',
    'FORMEL_TIPP_DELETED'			      => 'Tip was removed<br><br>Click %shere%s to go back to the Formula 1 WebTip overview<br><br>Click %shere%s to go to forum',
    'FORMEL_TIPPS_MADE'				       => 'Placed tips',
    'FORMEL_TIRED'					           => 'Tired count',
    'FORMEL_TITLE'					           => 'Formula 1 WebTip',
    'FORMEL_TOP_DRIVER'				       => 'Top drivers',
    'FORMEL_TOP_MORE'				         => 'Show all',
    'FORMEL_TOP_NAME'				         => 'Top players',
    'FORMEL_TOP_POINTS'				       => 'Points',
    'FORMEL_TOP_TEAMS'				        => 'Top teams',
    'FORMEL_USER_STATS'				       => 'User',
    'FORMEL_YOUR_POINTS'			       => 'Your points',
    'FORMEL_YOUR_TIPP'				        => 'Your tip',
    'VIEWING_F1WEBTIPP'				       => 'Viewing Formula 1 WebTip',

    'FORMEL_RULES_POINTS'			=> [
        1	=> 'Point',
        2	=> 'Points',
    ],

]);
