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
	'ACP_F1_DRIVERS_ADD_DRIVER'						=> 'Add driver',
	'ACP_F1_DRIVERS_ADD'							=> 'Send',
	'ACP_F1_DRIVERS_DELETE_DRIVER'					=> 'Delete',
	'ACP_F1_DRIVERS_DISABLED'						=> 'Selectable',
	'ACP_F1_DRIVERS_DRIVER_DELETE_CONFIRM'			=> '<br>Are you sure you want to delete the driver %s ?',
	'ACP_F1_DRIVERS_DRIVER_DELETED'					=> 'The driver %s was removed',
	'ACP_F1_DRIVERS_DRIVER_UPDATED'					=> 'Driver data updated',
	'ACP_F1_DRIVERS_DRIVERIMAGE'					=> 'Driver Image',
	'ACP_F1_DRIVERS_DRIVERNAME'						=> 'Driver Name',
	'ACP_F1_DRIVERS_DRIVERPOINTS'					=> 'Driver Points',
	'ACP_F1_DRIVERS_DRIVERTEAM'						=> 'Driver Team',
	'ACP_F1_DRIVERS_EDIT_DRIVER'					=> 'Edit',
	'ACP_F1_DRIVERS_ERROR_DRIVERNAME'				=> 'Please give a drivername',
	'ACP_F1_DRIVERS_ERROR_IMAGE'					=> 'Please give a driver pic',
	'ACP_F1_DRIVERS_EXPLAIN'						=> 'Here you can add or edit the drivers',
	'ACP_F1_DRIVERS_NOT_ADDED'						=> 'The driver %s was not added because he could not be assigned to a team.<br>Please create a team first to assign a driver afterwards.',
	'ACP_F1_DRIVERS_PENALTY'						=> 'Penalty',
	'ACP_F1_DRIVERS_TITEL_ADD_DRIVER_EXPLAIN'		=> 'Here you can add a new driver',
	'ACP_F1_DRIVERS_TITEL_ADD_DRIVER'				=> 'Add F1 driver',
	'ACP_F1_DRIVERS_TITEL_EDIT_DRIVER_EXPLAIN'		=> 'Here you can edit a F1 driver',
	'ACP_F1_DRIVERS_TITEL_EDIT_DRIVER'				=> 'Edit F1 driver',
	'ACP_F1_DRIVERS'								=> 'F1 Drivers',
	'ACP_F1_MANAGEMENT'								=> 'F1 WebTip',
	'ACP_F1_RACES_ADD_RACE'							=> 'New Race',
	'ACP_F1_RACES_ADD'								=> 'Send',
	'ACP_F1_RACES_DELETE_RACE'						=> 'Delete',
	'ACP_F1_RACES_EDIT_RACE'						=> 'Edit',
	'ACP_F1_RACES_ERROR_DATE_YEAR'					=> 'The date is out of range. Please check your date entry',
	'ACP_F1_RACES_ERROR_RACENAME'					=> 'Please insert a Race Location',
	'ACP_F1_RACES_EXPLAIN'							=> 'Here you can add or edit Races',
	'ACP_F1_RACES_RACE_DELETE_CONFIRM'				=> '<br>Are you sure you want to delete the race %s?',
	'ACP_F1_RACES_RACE_DELETED'						=> 'Race deleted',
	'ACP_F1_RACES_RACE_UPDATED'						=> 'Race data saved',
	'ACP_F1_RACES_RACEDEAD'							=> 'Deadline',
	'ACP_F1_RACES_RACEDEBUT'						=> 'Debut',
	'ACP_F1_RACES_RACEDISTANCE'						=> 'Distance',
	'ACP_F1_RACES_RACEIMAGE'						=> 'Logo',
	'ACP_F1_RACES_RACELAPS'							=> 'Laps',
	'ACP_F1_RACES_RACELENGTH'						=> 'Lap Length',
	'ACP_F1_RACES_RACENAME'							=> 'Location',
	'ACP_F1_RACES_RACETIME'							=> 'Race start',
	'ACP_F1_RACES_TITEL_ADD_RACE_EXPLAIN'			=> 'Here you can add a new Race',
	'ACP_F1_RACES_TITEL_ADD_RACE'					=> 'Add F1 Race',
	'ACP_F1_RACES_TITEL_EDIT_RACE_EXPLAIN'			=> 'Here you can edit a F1 Race',
	'ACP_F1_RACES_TITEL_EDIT_RACE'					=> 'Edit F1 Race',
	'ACP_F1_RACES'									=> 'F1 Races',
	'ACP_F1_SETTING_GUEST_VIEWING_EXPLAIN'			=> 'Only possible if permission for a <strong>F1 WebTip Group</strong> is <strong>de-activated</strong>.',
	'ACP_F1_SETTING_GUEST_VIEWING'					=> 'F1 WebTip visible for guests',
	'ACP_F1_SETTINGS_ACCESS_GROUP_EXPLAIN'			=> 'Here you can give permissions to a group for the F1 WebTip',
	'ACP_F1_SETTINGS_ACCESS_GROUP'					=> 'F1 WebTip Group',
	'ACP_F1_SETTINGS_CAR_IMG_HEIGHT_EXPLAIN'		=> 'Here you can define the <strong>height in px</strong> for the car image',
	'ACP_F1_SETTINGS_CAR_IMG_HEIGHT'				=> 'Car image height',
	'ACP_F1_SETTINGS_CAR_IMG_WIDTH_EXPLAIN'			=> 'Here you can define the <strong>width in px</strong> for the car image',
	'ACP_F1_SETTINGS_CAR_IMG_WIDTH'					=> 'Car image width',
	'ACP_F1_SETTINGS_CONFIG'						=> 'F1 Configuration',
	'ACP_F1_SETTINGS_DEACTIVATED'					=> '*** deactivated ***',
	'ACP_F1_SETTINGS_DRIVER_IMG_HEIGHT_EXPLAIN'		=> 'Here you can define the <strong>height in px</strong> for the driver image',
	'ACP_F1_SETTINGS_DRIVER_IMG_HEIGHT'				=> 'Driver image height',
	'ACP_F1_SETTINGS_DRIVER_IMG_WIDTH_EXPLAIN'		=> 'Here you can define the <strong>width in px</strong> for the driver image',
	'ACP_F1_SETTINGS_DRIVER_IMG_WIDTH'				=> 'Driver image width',
	'ACP_F1_SETTINGS_EXPLAIN'						=> 'Here you can edit your F1 WebTips settings',
	'ACP_F1_SETTINGS_FORUM_EXPLAIN'					=> 'Set the forum where you discuss the F1 WebTip',
	'ACP_F1_SETTINGS_FORUM'							=> 'F1 Forum',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_HEIGHT_EXPLAIN'	=> 'Here you can define the <strong>height in px</strong> for the banner',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_HEIGHT'			=> 'Banner hight',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_WIDTH_EXPLAIN'	=> 'Here you can define the <strong>width in px</strong> for the banner',
	'ACP_F1_SETTINGS_HEADBANNER_IMG_WIDTH'			=> 'Banner width',
	'ACP_F1_SETTINGS_HEADBANNER1_IMG_EXPLAIN'		=> 'Banner for the F1 WebTip overview page<br><code>ext/drdeath/f1webtip/images/banners/</code>',
	'ACP_F1_SETTINGS_HEADBANNER1_IMG'				=> 'Banner F1 Webtip',
	'ACP_F1_SETTINGS_HEADBANNER2_IMG_EXPLAIN'		=> 'Banner for the F1 WebTip rules page<br><code>ext/drdeath/f1webtip/images/banners/</code>',
	'ACP_F1_SETTINGS_HEADBANNER2_IMG'				=> 'Banner F1 rules',
	'ACP_F1_SETTINGS_HEADBANNER3_IMG_EXPLAIN'		=> 'Banner for the F1 WebTip statistics page<br><code>ext/drdeath/f1webtip/images/banners/</code>',
	'ACP_F1_SETTINGS_HEADBANNER3_IMG'				=> 'Banner F1 statistics',
	'ACP_F1_SETTINGS_MODERATOR_EXPLAIN'				=> 'Select someone with moderator or administrator permissions',
	'ACP_F1_SETTINGS_MODERATOR'						=> 'WebTip moderator',
	'ACP_F1_SETTINGS_NO_CAR_IMG_EXPLAIN'			=> 'Here you can define the standard image, for an empty car image entry<br><code>ext/drdeath/f1webtip/images/cars/</code>',
	'ACP_F1_SETTINGS_NO_CAR_IMG'					=> 'Standard car image',
	'ACP_F1_SETTINGS_NO_DRIVER_IMG_EXPLAIN'			=> 'Here you can define the standard image, for an empty driver image entry<br><code>ext/drdeath/f1webtip/images/drivers/</code>',
	'ACP_F1_SETTINGS_NO_DRIVER_IMG'					=> 'Standard driver image',
	'ACP_F1_SETTINGS_NO_RACE_IMG_EXPLAIN'			=> 'Here you can define the standard image, for an empty race image entry<br><code>ext/drdeath/f1webtip/images/races/</code>',
	'ACP_F1_SETTINGS_NO_RACE_IMG'					=> 'Standard race image',
	'ACP_F1_SETTINGS_NO_TEAM_IMG_EXPLAIN'			=> 'Here you can define the standard image, for an empty team image entry<br><code>ext/drdeath/f1webtip/images/teams/</code>',
	'ACP_F1_SETTINGS_NO_TEAM_IMG'					=> 'Standard team image',
	'ACP_F1_SETTINGS_OFFSET_EXPLAIN'				=> 'Here you can set the Deadline. (Time in Seconds before the Race Start)',
	'ACP_F1_SETTINGS_OFFSET'						=> 'Deadline Offset',
	'ACP_F1_SETTINGS_PICS'							=> 'Pics',
	'ACP_F1_SETTINGS_POINTS_FASTEST_EXPLAIN'		=> 'Points for the fastest lap',
	'ACP_F1_SETTINGS_POINTS_FASTEST'				=> 'Fastest',
	'ACP_F1_SETTINGS_POINTS_MENTIONED_EXPLAIN'		=> 'Points for mention a driver in the Top 10',
	'ACP_F1_SETTINGS_POINTS_MENTIONED'				=> 'Mentioned',
	'ACP_F1_SETTINGS_POINTS_PLACED_EXPLAIN'			=> 'Points for the drivers correct place',
	'ACP_F1_SETTINGS_POINTS_PLACED'					=> 'Placed',
	'ACP_F1_SETTINGS_POINTS_TIRED_EXPLAIN'			=> 'Points for the correct count of tired cars',
	'ACP_F1_SETTINGS_POINTS_TIRED'					=> 'Tired',
	'ACP_F1_SETTINGS_POINTS'						=> 'Points',
	'ACP_F1_SETTINGS_RACE_IMG_HEIGHT_EXPLAIN'		=> 'Here you can define the <strong>height in px</strong> for the race image',
	'ACP_F1_SETTINGS_RACE_IMG_HEIGHT'				=> 'Race image height',
	'ACP_F1_SETTINGS_RACE_IMG_WIDTH_EXPLAIN'		=> 'Here you can define the <strong>width in px</strong> for the race image',
	'ACP_F1_SETTINGS_RACE_IMG_WIDTH'				=> 'Race image width',
	'ACP_F1_SETTINGS_RACEOFFSET_EXPLAIN'			=> 'Here you can set when the "actual race" changed. (Time in Seconds from Race Start)',
	'ACP_F1_SETTINGS_RACEOFFSET'					=> 'Race delay',
	'ACP_F1_SETTINGS_REMINDER_ENABLED_EXPLAIN'		=> 'Here you can specify whether an email reminder to be sent 2-3 days prior to the start of the race.<br><strong>Hint: </strong>Can only be activated when the F1 WebTip was limited to a particular group.',
	'ACP_F1_SETTINGS_REMINDER_ENABLED'				=> 'Activate Cronjob for reminder mails',
	'ACP_F1_SETTINGS_SAFETY_CAR_EXPLAIN'			=> 'Points for the correct count of safety car deployment',
	'ACP_F1_SETTINGS_SAFETY_CAR'					=> 'Safety Car',
	'ACP_F1_SETTINGS_SEASON_RESET_EXPLAIN'			=> '<strong>Attention:</strong> If you click the button, all F1 season data will be lost!<br><br>After resetting the F1 season, you have to update all race start times.',
	'ACP_F1_SETTINGS_SEASON_RESET'					=> 'Reset F1 season',
	'ACP_F1_SETTINGS_SEASON_RESETTED'				=> 'F1 Season resettet. Update race start times!',
	'ACP_F1_SETTINGS_SHOW_AVATAR_EXPLAIN'			=> 'Here you can define whether to show the avatar on users´s statistics or not',
	'ACP_F1_SETTINGS_SHOW_AVATAR'					=> 'Show avatar',
	'ACP_F1_SETTINGS_SHOW_COUNTDOWN_EXPLAIN'		=> 'Do you want to display countdown till deadline in F1 WebTip?',
	'ACP_F1_SETTINGS_SHOW_COUNTDOWN'				=> 'Display Countdown',
	'ACP_F1_SETTINGS_SHOW_GFX_EXPLAIN'				=> 'Do you want to display driver, team and car images?',
	'ACP_F1_SETTINGS_SHOW_GFX'						=> 'Show extended images',
	'ACP_F1_SETTINGS_SHOW_GFXR_EXPLAIN'				=> 'Do you want to display the race images?',
	'ACP_F1_SETTINGS_SHOW_GFXR'						=> 'Show race images',
	'ACP_F1_SETTINGS_SHOW_HEADBANNER_EXPLAIN'		=> 'Here you can define whether to show the F1 headbanners or not',
	'ACP_F1_SETTINGS_SHOW_HEADBANNER'				=> 'Show F1 banner',
	'ACP_F1_SETTINGS_SHOW_PROFILE_EXPLAIN'			=> 'Do you want to display information in users profile?',
	'ACP_F1_SETTINGS_SHOW_PROFILE'					=> 'Display in profile',
	'ACP_F1_SETTINGS_SHOW_VIEWTOPIC_EXPLAIN'		=> 'Do you want to display information in users prostings?',
	'ACP_F1_SETTINGS_SHOW_VIEWTOPIC'				=> 'Display in postings',
	'ACP_F1_SETTINGS_TEAM_IMG_HEIGHT_EXPLAIN'		=> 'Here you can define the <strong>height in px</strong> for the team image',
	'ACP_F1_SETTINGS_TEAM_IMG_HEIGHT'				=> 'Team image height',
	'ACP_F1_SETTINGS_TEAM_IMG_WIDTH_EXPLAIN'		=> 'Here you can define the <strong>width in px</strong> for the team image',
	'ACP_F1_SETTINGS_TEAM_IMG_WIDTH'				=> 'Team image width',
	'ACP_F1_SETTINGS_UPDATED'						=> 'F1 Settings succesfully updated',
	'ACP_F1_SETTINGS'								=> 'F1 Settings',
	'ACP_F1_TEAMS_ADD_TEAM'							=> 'New Team',
	'ACP_F1_TEAMS_ADD'								=> 'Send',
	'ACP_F1_TEAMS_ADDTEAM_TEAMCAR'					=> 'Team Car',
	'ACP_F1_TEAMS_ADDTEAM_TEAMIMAGE'				=> 'Team Logo',
	'ACP_F1_TEAMS_ADDTEAM_TEAMNAME'					=> 'Team Name',
	'ACP_F1_TEAMS_ADDTEAM_TITLE_EXPLAIN'			=> 'Here you can add a new team',
	'ACP_F1_TEAMS_ADDTEAM_TITLE'					=> 'Add F1 Team',
	'ACP_F1_TEAMS_DELETE_TEAM'						=> 'Delete',
	'ACP_F1_TEAMS_DRIVERPOINTS'						=> 'WM Points',
	'ACP_F1_TEAMS_DRIVERTEAM'						=> 'Team',
	'ACP_F1_TEAMS_EDIT_TEAM'						=> 'Edit',
	'ACP_F1_TEAMS_EDITTEAM_TITLE_EXPLAIN'			=> 'Here you can edit a F1 Team',
	'ACP_F1_TEAMS_EDITTEAM_TITLE'					=> 'Edit F1 Team',
	'ACP_F1_TEAMS_ERROR_TEAMNAME'					=> 'Please give a Teamname',
	'ACP_F1_TEAMS_EXPLAIN'							=> 'Here you can add or edit the teams',
	'ACP_F1_TEAMS_PENALTY'							=> 'Penalty',
	'ACP_F1_TEAMS_TEAM_DELETE_CONFIRM'				=> '<br>Are you sure you want to delete the team %s ?',
	'ACP_F1_TEAMS_TEAM_DELETED'						=> 'Team %s deleted',
	'ACP_F1_TEAMS_TEAM_NOT_DELETED'					=> 'Team %s was not deleted, because it still contains drivers. Please delete the drivers from the team first.',
	'ACP_F1_TEAMS_TEAM_UPDATED'						=> 'Team data saved',
	'ACP_F1_TEAMS'									=> 'F1 Teams',
	'ACP_F1WEBTIP_SETTING_SAVED'					=> 'Settings have been saved successfully!',

]);
