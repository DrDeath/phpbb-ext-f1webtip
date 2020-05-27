<?php
/**
 *
 * Formula 1 WebTip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2014, Dr.Death, http://www.lpi-clan.de
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace drdeath\f1webtip\acp;

/**
 * @ignore
 */
use \DateTime;

class main_module
{
	var $u_action;

	/*
	* Reads all files in the given directory and scans for images
	*
	* Parameter: directory to scan
	* Returns an array including all found images
	*/
	public function load_files($dir)
	{
		$result = [];
		$image_ary = preg_grep('~\.(jpeg|jpg|gif|png)$~', scandir($dir));

		// recreate array, shoud start with 0 ;-)
		foreach ($image_ary as $value)
		{
			$result[] = $value;
		}

		return $result;
	}

	/*
	* Creates dropdown boxes for image selection
	*
	* Parameters: directory to scan, default selection, selection name, optional: first option value
	* Returns a string as dropdown box with all found images in given directory
	*/
	public function create_dropdown($dir, $select_default, $name, $default = false)
	{
		$images = $this->load_files($dir);
		$image_entries = '';

		foreach ($images as $image)
		{
			$selected = ($image == $select_default) ? 'selected' : '';
			$image_entries .= '<option value="' . $image . '" ' . $selected . '>' . $image . '</option>';
		}

						$image_combo	 = '<select name="' . $name . '">';
		($default) ?	$image_combo	.= '<option value="" ' . $selected . '>' . $default . '</option>' : '';
						$image_combo	.= $image_entries;
						$image_combo	.= '</select>';

		return $image_combo;
	}

	function main($id, $mode)
	{
		global $db, $config, $user, $template, $request, $auth;
		global $phpbb_container, $phpbb_extension_manager, $phpbb_log, $phpbb_root_path;

		$language = $phpbb_container->get('language');

		$ext_path = $phpbb_extension_manager->get_extension_path('drdeath/f1webtip', true);

		// Load extension language file
		$language->add_lang('acp_common', 'drdeath/f1webtip');

		// short names for database tables
		$table_races 	= $phpbb_container->getParameter('tables.f1webtip.races');
		$table_teams	= $phpbb_container->getParameter('tables.f1webtip.teams');
		$table_drivers 	= $phpbb_container->getParameter('tables.f1webtip.drivers');
		$table_wm 		= $phpbb_container->getParameter('tables.f1webtip.wm');
		$table_tips 	= $phpbb_container->getParameter('tables.f1webtip.tips');

		// short names for image directories
		$dir_banners	= $phpbb_root_path . 'ext/drdeath/f1webtip/images/banners';
		$dir_races		= $phpbb_root_path . 'ext/drdeath/f1webtip/images/races';
		$dir_cars		= $phpbb_root_path . 'ext/drdeath/f1webtip/images/cars';
		$dir_drivers	= $phpbb_root_path . 'ext/drdeath/f1webtip/images/drivers';
		$dir_teams		= $phpbb_root_path . 'ext/drdeath/f1webtip/images/teams';

		$this->tpl_name = 'f1webtip_body';

		add_form_key('drdeath/f1webtip');

		// What are we working on?
		switch ($mode)
		{
			###########################
			###      SETTINGS      ####
			###########################
			case 'settings':

				$this->page_title = $language->lang('ACP_F1_SETTINGS');

				//
				// Reset F1 Season
				//

				if ($request->is_set_post('reset_all'))
				{
					// Have we confirmed with yes ?
					if (confirm_box(true))
					{
						// remove all user tips
						$sql = 'DELETE FROM ' . $table_tips;
						$result = $db->sql_query($sql);

						// remove all wm points
						$sql = 'DELETE FROM ' . $table_wm;
						$result = $db->sql_query($sql);

						// remove all race and qualifying results
						$sql_ary = [
							'race_result'		=> 0,
							'race_quali'		=> 0,
						];

						$sql = 'UPDATE ' . $table_races . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) ;
						$db->sql_query($sql);

						//remove penalty from drivers
						$sql_ary = [
							'driver_penalty'	=> 0,
						];

						$sql = 'UPDATE ' . $table_drivers . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) ;
						$db->sql_query($sql);

						//remove penalty from teams
						$sql_ary = [
							'team_penalty'	=> 0,
						];

						$sql = 'UPDATE ' . $table_teams . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) ;
						$db->sql_query($sql);

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_SAISON_RESET');

						$error = $language->lang('ACP_F1_SETTINGS_SEASON_RESETTED');
						trigger_error($error . adm_back_link($this->u_action));
					}
					// Create a confirmbox with yes and no.
					else
					{
						confirm_box(false, $language->lang('ACP_F1_SETTINGS_SEASON_RESET_EXPLAIN'), build_hidden_fields([
							'reset_all'				=> true,
							])
						, 'confirm_body.html');
					}
				}

				//
				// Submit button pushed
				//

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('drdeath/f1webtip'))
					{
						trigger_error('FORM_INVALID');
					}

					$config->set('drdeath_f1webtip_headbanner1_img',	$request->variable('headbanner1_img',			$config['drdeath_f1webtip_headbanner1_img']));
					$config->set('drdeath_f1webtip_headbanner2_img',	$request->variable('headbanner2_img',			$config['drdeath_f1webtip_headbanner2_img']));
					$config->set('drdeath_f1webtip_headbanner3_img',	$request->variable('headbanner3_img',			$config['drdeath_f1webtip_headbanner3_img']));
					$config->set('drdeath_f1webtip_no_car_img',			$request->variable('no_car_img',				$config['drdeath_f1webtip_no_car_img']));
					$config->set('drdeath_f1webtip_no_driver_img',		$request->variable('no_driver_img',				$config['drdeath_f1webtip_no_driver_img']));
					$config->set('drdeath_f1webtip_no_race_img',		$request->variable('no_race_img',				$config['drdeath_f1webtip_no_race_img']));
					$config->set('drdeath_f1webtip_no_team_img',		$request->variable('no_team_img',				$config['drdeath_f1webtip_no_team_img']));

					$config->set('drdeath_f1webtip_car_img_height',		(int) $request->variable('car_img_height',		$config['drdeath_f1webtip_car_img_height']));
					$config->set('drdeath_f1webtip_car_img_width',		(int) $request->variable('car_img_width',		$config['drdeath_f1webtip_car_img_width']));
					$config->set('drdeath_f1webtip_driver_img_height',	(int) $request->variable('driver_img_height',	$config['drdeath_f1webtip_driver_img_height']));
					$config->set('drdeath_f1webtip_driver_img_width',	(int) $request->variable('driver_img_width',	$config['drdeath_f1webtip_driver_img_width']));
					$config->set('drdeath_f1webtip_head_height',		(int) $request->variable('head_height',			$config['drdeath_f1webtip_head_height']));
					$config->set('drdeath_f1webtip_head_width',			(int) $request->variable('head_width',			$config['drdeath_f1webtip_head_width']));
					$config->set('drdeath_f1webtip_race_img_height',	(int) $request->variable('race_img_height',		$config['drdeath_f1webtip_race_img_height']));
					$config->set('drdeath_f1webtip_race_img_width',		(int) $request->variable('race_img_width',		$config['drdeath_f1webtip_race_img_width']));
					$config->set('drdeath_f1webtip_team_img_height',	(int) $request->variable('team_img_height',		$config['drdeath_f1webtip_team_img_height']));
					$config->set('drdeath_f1webtip_team_img_width',		(int) $request->variable('team_img_width',		$config['drdeath_f1webtip_team_img_width']));

					$config->set('drdeath_f1webtip_deadline_offset',	(int) $request->variable('deadline_offset',		'0'));
					$config->set('drdeath_f1webtip_event_change',		(int) $request->variable('event_change',		'0'));
					$config->set('drdeath_f1webtip_forum_id',			(int) $request->variable('forum_id',			'0'));
					$config->set('drdeath_f1webtip_mod_id',				(int) $request->variable('mod_id',				'0'));
					$config->set('drdeath_f1webtip_points_fastest',		(int) $request->variable('points_fastest',		'0'));
					$config->set('drdeath_f1webtip_points_mentioned',	(int) $request->variable('points_mentioned',	'0'));
					$config->set('drdeath_f1webtip_points_placed',		(int) $request->variable('points_placed',		'0'));
					$config->set('drdeath_f1webtip_points_safety_car',	(int) $request->variable('points_safety_car',	'0'));
					$config->set('drdeath_f1webtip_points_tired',		(int) $request->variable('points_tired',		'0'));
					$config->set('drdeath_f1webtip_restrict_to',		(int) $request->variable('restrict_to',			'0'));
					$config->set('drdeath_f1webtip_show_avatar',		(int) $request->variable('show_avatar',			'0'));
					$config->set('drdeath_f1webtip_show_countdown',		(int) $request->variable('show_countdown',		'0'));
					$config->set('drdeath_f1webtip_show_gfx',			(int) $request->variable('show_gfx',			'0'));
					$config->set('drdeath_f1webtip_show_gfxr',			(int) $request->variable('show_gfxr',			'0'));
					$config->set('drdeath_f1webtip_show_headbanner',	(int) $request->variable('show_headbanner',		'0'));
					$config->set('drdeath_f1webtip_show_in_profile',	(int) $request->variable('show_in_profile',		'0'));
					$config->set('drdeath_f1webtip_show_in_viewtopic',	(int) $request->variable('show_in_viewtopic',	'0'));

					// Guest viewing can only be activated, if the F1 WebTip is not restricted to a specific group (restrict_to == 0)
					// Cron reminder can only be activated, if the F1 WebTip is     restricted to a specific group (restrict_to <> 0)
					if ($request->variable('restrict_to', 	'0') == 0)
					{
						$config->set('drdeath_f1webtip_guest_viewing',		(int) $request->variable('guest_viewing',		'0'));
						$config->set('drdeath_f1webtip_reminder_enabled', 	'0');
					}
					else
					{
						$config->set('drdeath_f1webtip_guest_viewing', 		'0');
						$config->set('drdeath_f1webtip_reminder_enabled',	(int) $request->variable('reminder_enabled',	'0'));
					}

					$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_SETTINGS');

					$error = $language->lang('ACP_F1WEBTIP_SETTING_SAVED');
					trigger_error($error . adm_back_link($this->u_action));
				}

				//
				// Generate a moderator list for the F1 WebTip
				//

				//Get all possible moderators and administrators at once
				$mod_ary			= $auth->acl_get_list(false, 'm_', false);
				$mod_ary			= (!empty($mod_ary[0]['m_'])) ? $mod_ary[0]['m_'] : [];
				$admin_ary			= $auth->acl_get_list(false, 'a_', false);
				$admin_ary			= (!empty($admin_ary[0]['a_'])) ? $admin_ary[0]['a_'] : [];

				$admin_mod_array 	= array_unique(array_merge($admin_ary, $mod_ary));

				$sql = 'SELECT user_id, username
						FROM ' . USERS_TABLE . '
						WHERE ' . $db->sql_in_set('user_id', $admin_mod_array);

				$result = $db->sql_query($sql);

				// Fill combobox
				while ($row = $db->sql_fetchrow($result))
				{
					$preselected = ($row['user_id'] == $config['drdeath_f1webtip_mod_id']) ? 'selected' : '';

					$template->assign_block_vars('moderators', [
						'PRESELECTED'	=> $preselected,
						'USER_ID'		=> $row['user_id'],
						'USERNAME'		=> $row['username'],
						]
					);
				}

				//
				// Get all group data
				// Don't select the default phpBB3 groups
				// If choosen "deactivated" - all "registered user" have access.
				//

				$sql = 'SELECT *
					FROM ' . GROUPS_TABLE . '
					WHERE group_type <> ' . GROUP_SPECIAL ;
				$result = $db->sql_query($sql);

				$preselected = ($config['drdeath_f1webtip_restrict_to'] == 0) ? 'selected' : '';

				$template->assign_block_vars('accessgroups', [
					'PRESELECTED'	=> $preselected,
					'GROUP_ID'		=> 0,
					'GROUPNAME'		=> $language->lang('ACP_F1_SETTINGS_DEACTIVATED'),
					]
				);

				while ($row = $db->sql_fetchrow($result))
				{
					$preselected = ($row['group_id'] == $config['drdeath_f1webtip_restrict_to']) ? 'selected' : '';

					$template->assign_block_vars('accessgroups', [
						'PRESELECTED'	=> $preselected,
						'GROUP_ID'		=> $row['group_id'],
						'GROUPNAME'		=> $row['group_name'],
						]
					);
				}

				//
				// Get all forum data - Don't select categories or links
				//

				$sql = 'SELECT forum_id, forum_name, forum_type
					FROM ' . FORUMS_TABLE . '
					WHERE forum_type <> ' . FORUM_CAT . '
						AND forum_type <> ' . FORUM_LINK . '
					ORDER BY forum_name ASC';
				$result = $db->sql_query($sql);

				$preselected = ($config['drdeath_f1webtip_forum_id'] == 0) ? 'selected' : '';

				$template->assign_block_vars('forums', [
					'PRESELECTED'	=> $preselected,
					'FORUM_ID'		=> 0,
					'FORUMNAME'		=> $language->lang('ACP_F1_SETTINGS_DEACTIVATED'),
					]
				);

				while ($row = $db->sql_fetchrow($result))
				{
					$preselected = ($row['forum_id'] == $config['drdeath_f1webtip_forum_id']) ? 'selected' : '';

					$template->assign_block_vars('forums', [
						'PRESELECTED'	=> $preselected,
						'FORUM_ID'		=> $row['forum_id'],
						'FORUMNAME'		=> $row['forum_name'],
						]
					);
				}

				$db->sql_freeresult($result);

				//
				// Generate image select dropdown boxes
				//

				$image_headbanner1_combo	= $this->create_dropdown($dir_banners,	$config['drdeath_f1webtip_headbanner1_img'],	'headbanner1_img');
				$image_headbanner2_combo	= $this->create_dropdown($dir_banners,	$config['drdeath_f1webtip_headbanner2_img'],	'headbanner2_img');
				$image_headbanner3_combo	= $this->create_dropdown($dir_banners,	$config['drdeath_f1webtip_headbanner3_img'],	'headbanner3_img');
				$image_no_race_img_combo	= $this->create_dropdown($dir_races,	$config['drdeath_f1webtip_no_race_img'],		'no_race_img');
				$image_no_car_img_combo		= $this->create_dropdown($dir_cars,		$config['drdeath_f1webtip_no_car_img'],			'no_car_img');
				$image_no_driver_img_combo	= $this->create_dropdown($dir_drivers,	$config['drdeath_f1webtip_no_driver_img'],		'no_driver_img');
				$image_no_team_img_combo	= $this->create_dropdown($dir_teams,	$config['drdeath_f1webtip_no_team_img'],		'no_team_img');

				if ($config['drdeath_f1webtip_show_headbanner'])
				{
					$template->assign_block_vars('headbanner_on', []);
				}

				if ($config['drdeath_f1webtip_show_gfxr'])
				{
					$template->assign_block_vars('gfxr_on', []);
				}

				if ($config['drdeath_f1webtip_show_gfx'])
				{
					$template->assign_block_vars('gfx_on', []);
				}

				$template->assign_vars([
					'CAR_IMG_HEIGHT'					=> $config['drdeath_f1webtip_car_img_height'],
					'CAR_IMG_WIDTH'						=> $config['drdeath_f1webtip_car_img_width'],
					'DRIVER_IMG_HEIGHT'					=> $config['drdeath_f1webtip_driver_img_height'],
					'DRIVER_IMG_WIDTH'					=> $config['drdeath_f1webtip_driver_img_width'],
					'GUEST_VIEWING'						=> $config['drdeath_f1webtip_guest_viewing'],
					'HEADBANNER_IMG_HEIGHT'				=> $config['drdeath_f1webtip_head_height'],
					'HEADBANNER_IMG_WIDTH'				=> $config['drdeath_f1webtip_head_width'],
					'HEADBANNER1_IMG'					=> $image_headbanner1_combo,
					'HEADBANNER2_IMG'					=> $image_headbanner2_combo,
					'HEADBANNER3_IMG'					=> $image_headbanner3_combo,
					'NO_CAR_IMG'						=> $image_no_car_img_combo,
					'NO_DRIVER_IMG'						=> $image_no_driver_img_combo,
					'NO_RACE_IMG'						=> $image_no_race_img_combo,
					'NO_TEAM_IMG'						=> $image_no_team_img_combo,
					'POINTS_FASTEST'					=> $config['drdeath_f1webtip_points_fastest'],
					'POINTS_MENTIONED'					=> $config['drdeath_f1webtip_points_mentioned'],
					'POINTS_PLACED'						=> $config['drdeath_f1webtip_points_placed'],
					'POINTS_SAFETY_CAR'					=> $config['drdeath_f1webtip_points_safety_car'],
					'POINTS_TIRED'						=> $config['drdeath_f1webtip_points_tired'],
					'RACE_IMG_HEIGHT'					=> $config['drdeath_f1webtip_race_img_height'],
					'RACE_IMG_WIDTH'					=> $config['drdeath_f1webtip_race_img_width'],
					'REMINDER_ENABLED'					=> $config['drdeath_f1webtip_reminder_enabled'],
					'S_SETTING'							=> true,
					'SETTING_OFFSET'					=> $config['drdeath_f1webtip_deadline_offset'],
					'SETTING_RACEOFFSET'				=> $config['drdeath_f1webtip_event_change'],
					'SHOW_AVATAR'						=> $config['drdeath_f1webtip_show_avatar'],
					'SHOW_COUNTDOWN'					=> $config['drdeath_f1webtip_show_countdown'],
					'SHOW_GFX'							=> $config['drdeath_f1webtip_show_gfx'],
					'SHOW_GFXR'							=> $config['drdeath_f1webtip_show_gfxr'],
					'SHOW_HEADBANNER'					=> $config['drdeath_f1webtip_show_headbanner'],
					'SHOW_PROFILE'						=> $config['drdeath_f1webtip_show_in_profile'],
					'SHOW_VIEWTOPIC'					=> $config['drdeath_f1webtip_show_in_viewtopic'],
					'TEAM_IMG_HEIGHT'					=> $config['drdeath_f1webtip_team_img_height'],
					'TEAM_IMG_WIDTH'					=> $config['drdeath_f1webtip_team_img_width'],
					'U_ACTION'							=> $this->u_action,
				]);

			break;

			##########################
			###      DRIVERS      ####
			##########################
			case 'drivers':

				$this->page_title = $language->lang('ACP_F1_DRIVERS');

				// Check buttons
				$button_adddriver 	= $request->is_set_post('adddriver');
				$button_add 		= $request->is_set_post('add');
				$button_del			= $request->is_set_post('del');
				$button_edit 		= $request->is_set_post('edit');

				// Check data
				$driverimg			= $request->variable('driverimg'		,	''	);
				$drivername			= $request->variable('drivername'		,	''	,	true	);
				$driverteam			= $request->variable('driverteam'		,	0	);
				$driver_id			= $request->variable('driver_id'		,	0	);
				$driver_penalty		= $request->variable('driver_penalty'	,	0.0	);
				$driver_disabled	= $request->variable('driver_disabled'	,	0	);

				//
				// Delete a driver
				//

				if ($button_del && $driver_id <> 0)
				{
					// Have we confirmed with yes ?
					if (confirm_box(true))
					{
						// Delete the driver
						$sql = 'DELETE FROM ' . $table_drivers . '
								WHERE driver_id = ' . (int) $driver_id;
						$db->sql_query($sql);

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_DRIVER_DELETED', false, [$drivername . ' (ID ' . $driver_id . ')']);

						$error = $language->lang('ACP_F1_DRIVERS_DRIVER_DELETED', $drivername);
						trigger_error($error . adm_back_link($this->u_action));
					}
					// Create a confirmbox with yes and no.
					else
					{
						confirm_box(false, $language->lang('ACP_F1_DRIVERS_DRIVER_DELETE_CONFIRM', $drivername), build_hidden_fields([
							'del'				=> true,
							'driver_id'			=> $driver_id,
							'drivername'		=> $drivername,
							])
						, 'confirm_body.html');
					}
				}

				//
				// Add a new driver
				//

				// add or update the driver
				if ($button_add && $drivername <> '')
				{
					// Is it salty ?
					if (!check_form_key('drdeath/f1webtip'))
					{
						trigger_error('FORM_INVALID');
					}

					if ($driver_id == 0)
					{
						// Add the driver
						$sql_ary = [
							'driver_name'		=> $drivername,
							'driver_img'		=> $driverimg,
							'driver_team'		=> $driverteam,
							'driver_penalty'	=> $driver_penalty,
							'driver_disabled'	=> $driver_disabled,
						];

						$db->sql_query('INSERT INTO ' . $table_drivers . ' ' . $db->sql_build_array('INSERT', $sql_ary));

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_DRIVER_ADDED', false, [$drivername]);
					}
					else
					{
						// Edit the driver
						$sql_ary = [
							'driver_name'		=> $drivername,
							'driver_img'		=> ($config['drdeath_f1webtip_show_gfx'] == 1) ? $driverimg : '',
							'driver_team'		=> $driverteam,
							'driver_penalty'	=> $driver_penalty,
							'driver_disabled'	=> $driver_disabled,
						];

						$sql = 'UPDATE ' . $table_drivers . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) . "
							WHERE driver_id = $driver_id";
						$db->sql_query($sql);

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_DRIVER_EDITED', false, [$drivername . ' (ID ' . $driver_id . ')']);
					}

					$error = $language->lang('ACP_F1_DRIVERS_DRIVER_UPDATED');
					trigger_error($error . adm_back_link($this->u_action));
				}

				//
				// Load, add or update driver
				//

				if ($button_adddriver || ($button_edit && $driver_id <> 0) || ($button_add && $drivername == ''))
				{
					$preselected_id = '';

					// Create error message if drivername is empty
					if ($button_add && $drivername == '')
					{
						$error	 = $language->lang('ACP_F1_DRIVERS_ERROR_DRIVERNAME');
						trigger_error($error . adm_back_link($this->u_action), E_USER_WARNING);
					}

					// Init some vars
					$title_exp 	= $language->lang('ACP_F1_DRIVERS_TITEL_ADD_DRIVER_EXPLAIN');
					$title 		= $language->lang('ACP_F1_DRIVERS_TITEL_ADD_DRIVER');

					// Load initial values
					if ($button_edit)
					{
						// overwrites the "add driver" title and sets the "edit driver" title
						$title_exp 	= $language->lang('ACP_F1_DRIVERS_TITEL_EDIT_DRIVER_EXPLAIN'); // overwrites the "add driver" title and sets the "edit driver" title
						$title 		= $language->lang('ACP_F1_DRIVERS_TITEL_EDIT_DRIVER');

						// Get drivers data
						$sql = 'SELECT *
							FROM ' . $table_drivers . '
							WHERE driver_id = ' . (int) $driver_id . '
								ORDER BY driver_name';
						$result = $db->sql_query($sql);

						$row = $db->sql_fetchrow($result);

						$driver_disabled 	= $row['driver_disabled'];
						$driver_penalty 	= $row['driver_penalty'];
						$driverimg 			= $row['driver_img'];
						$drivername 		= $row['driver_name'];
						$preselected_id 	= $row['driver_team'];

						$db->sql_freeresult($result);
					}

					// Get all teams data
					$sql = 'SELECT *
						FROM ' . $table_teams . '
						ORDER BY team_name';
					$result = $db->sql_query($sql);

					// Fill combobox
					while ($row = $db->sql_fetchrow($result))
					{
						$preselected = ($row['team_id'] == $preselected_id) ? 'selected' : '';

						$template->assign_block_vars('teamrows', [
							'PRESELECTED'	=> $preselected,
							'TEAM_ID'		=> $row['team_id'],
							'TEAMNAME'		=> $row['team_name'],
							]
						);
					}

					$db->sql_freeresult($result);

					// Generate imagebox for driver
					$image_driver_combo	= $this->create_dropdown($dir_drivers, $driverimg, 'driverimg', $language->lang('ACP_F1_SETTINGS_NO_DRIVER_IMG'));

					// Generate page
					if ($config['drdeath_f1webtip_show_gfx'] == 1)
					{
						$template->assign_block_vars('gfx_on', []);
					}

					$template->assign_vars([
						'DRIVER_ID'					=> $driver_id,
						'L_ACP_F1_DRIVERS_EXPLAIN'	=> $title_exp,
						'L_ACP_F1_DRIVERS'			=> $title,
						'PREDEFINED_IMG'			=> $image_driver_combo,
						'PREDEFINED_NAME'			=> $drivername,
						'PREDEFINED_PENALTY'		=> $driver_penalty,
						'S_ADDDRIVERS'				=> true,
						'S_DRIVER_DISABLED'			=> ($driver_disabled == true) ? "checked=\"checked\"" : "",
						'S_DRIVER_ENABLED'			=> ($driver_disabled == false) ? "checked=\"checked\"" : "",
						'U_ACTION'					=> $this->u_action,
						]
					);
				}
				else
				{
					//
					// Load the driver overview page
					//

					// Get all teams data
					$sql = 'SELECT *
						FROM ' . $table_teams . '
						ORDER BY team_name';
					$result = $db->sql_query($sql);

					$teams = [];

					while ($row = $db->sql_fetchrow($result))
					{
						$teams[$row['team_id']]		= $row['team_name'];
					}

					$db->sql_freeresult($result);

					// Get all drivers data
					$sql = 'SELECT *
						FROM ' . $table_drivers . '
						ORDER BY driver_name';
					$result = $db->sql_query($sql);

					while ($row = $db->sql_fetchrow($result))
					{
						$current_user_id	= $row['driver_id'];
						$driverimg			= ($row['driver_img'] == '') ? '<img src="' . $ext_path . 'images/drivers/' . $config['drdeath_f1webtip_no_driver_img'] . '" width="' . $config['drdeath_f1webtip_driver_img_width'] . '" height="' . $config['drdeath_f1webtip_driver_img_height'] . '" alt="">' : '<img src="' . $ext_path . 'images/drivers/' . $row['driver_img'] . '" width="' . $config['drdeath_f1webtip_driver_img_width'] . '" height="' . $config['drdeath_f1webtip_driver_img_height'] . '" alt="">';
						$driver_penalty 	= $row['driver_penalty'];
						$driver_disabled 	= $row['driver_disabled'];

						$pointssql = 'SELECT SUM(wm_points) AS total_points
							FROM ' . $table_wm . '
							WHERE wm_driver = ' . (int) $current_user_id;
						$user_points = $db->sql_query($pointssql);

						$driver_points = $db->sql_fetchrow($user_points);
						$points = ($driver_points['total_points'] <> '') ? $driver_points['total_points'] - $driver_penalty : 0 - $driver_penalty;

						$db->sql_freeresult($user_points);

						$template->assign_block_vars(($config['drdeath_f1webtip_show_gfx'] == 1) ? 'driverrows_gfx' : 'driverrows', [
							'DRIVER_DISABLED'	=> ($driver_disabled == true) ? $language->lang('NO') : $language->lang('YES'),
							'DRIVER_PENALTY'	=> $driver_penalty,
							'DRIVERID'			=> $row['driver_id'],
							'DRIVERIMG'			=> $driverimg,
							'DRIVERNAME'		=> $row['driver_name'],
							'DRIVERPOINTS'		=> $points,
							'DRIVERTEAM'		=> (isset($teams[$row['driver_team']])) ? $teams[$row['driver_team']] : '',
							]
						);

					}

					$db->sql_freeresult($result);

					$colspan = 7;

					if ($config['drdeath_f1webtip_show_gfx'] == 1)
					{
						$colspan = 8;
						$template->assign_block_vars('gfx_on', []);
					}

					// Generate page
					$template->assign_vars([
						'COLSPAN'			=> $colspan,
						'DRIVER_ID'			=> $driver_id,
						'S_DRIVERS'			=> true,
						'U_ACTION'			=> $this->u_action,
						]
					);
				}

			break;

			##########################
			###       TEAMS       ####
			##########################
			case 'teams':

				$this->page_title = $language->lang('ACP_F1_TEAMS');

				// Check buttons & data
				$button_addteam 	= $request->is_set_post('addteam');
				$button_add 		= $request->is_set_post('add');
				$button_del 		= $request->is_set_post('del');
				$button_edit 		= $request->is_set_post('edit');

				$teamimg 			= $request->variable('teamimg'		,	''	,	true	);
				$teamcar 			= $request->variable('teamcar'		,	''	,	true	);
				$teamname 			= $request->variable('teamname'		,	''	,	true	);
				$team_id 			= $request->variable('team_id'		,	0	);
				$team_penalty		= $request->variable('team_penalty'	,	0.0	);

				//
				// Delete a team
				//

				if ($button_del && $team_id <> 0)
				{
					// Have we confirmed with yes ?
					if (confirm_box(true))
					{
						// prevent teams from being deleted when drivers are still assigned.
						$sql = 'SELECT *
							FROM ' . $table_drivers . '
							WHERE driver_team = ' . (int) $team_id ;
						$result = $db->sql_query($sql);

						if ($db->sql_fetchrow($result))
						{
							// Team contains one or more driver - don't delete the team
							$db->sql_freeresult($result);

							$error = $language->lang('ACP_F1_TEAMS_TEAM_NOT_DELETED', $teamname);
							trigger_error($error . adm_back_link($this->u_action), E_USER_WARNING);
						}
						else
						{
							$db->sql_freeresult($result);

							// Delete the team
							$sql = 'DELETE FROM ' . $table_teams . '
									WHERE team_id = ' . (int) $team_id;
							$db->sql_query($sql);

							$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_TEAM_DELETED', false, [$teamname . ' (ID ' . $team_id . ')']);

							$error = $language->lang('ACP_F1_TEAMS_TEAM_DELETED', $teamname);
							trigger_error($error . adm_back_link($this->u_action));
						}
					}
					// Create a confirmbox with yes and no.
					else
					{
						confirm_box(false, $language->lang('ACP_F1_TEAMS_TEAM_DELETE_CONFIRM', $teamname), build_hidden_fields([
							'del'				=> true,
							'team_id'			=> $team_id,
							'teamname'			=> $teamname,
							])
						, 'confirm_body.html');
					}
				}

				//
				// Add a new team
				//

				if ($button_add && $teamname <> '')
				{
					// Is it salty ?
					if (!check_form_key('drdeath/f1webtip'))
					{
						trigger_error('FORM_INVALID');
					}

					if ($team_id == 0)
					{
						// Add the team
						$sql_ary = [
							'team_name'		=> $teamname,
							'team_img'		=> $teamimg,
							'team_car'		=> $teamcar,
							'team_penalty'	=> $team_penalty,
						];

						$db->sql_query('INSERT INTO ' . $table_teams . ' ' . $db->sql_build_array('INSERT', $sql_ary));

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_TEAM_ADDED', false, [$teamname]);
					}
					else
					{
						// Edit the team
						$sql_ary = [
							'team_name'		=> $teamname,
							'team_img'		=> ($config['drdeath_f1webtip_show_gfx'] == 1) ? $teamimg : '',
							'team_car'		=> ($config['drdeath_f1webtip_show_gfx'] == 1) ? $teamcar : '',
							'team_penalty'	=> $team_penalty,
						];

						$sql = 'UPDATE ' . $table_teams . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE team_id = ' . (int) $team_id;

						$db->sql_query($sql);

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_TEAM_EDITED', false, [$teamname . ' (ID ' . $team_id . ')']);
					}

					$error = $language->lang('ACP_F1_TEAMS_TEAM_UPDATED');
					trigger_error($error . adm_back_link($this->u_action));
				}

				//
				// Load, add or update team
				//

				if ($button_addteam || ($button_edit && $team_id <> 0) || ($button_add && $teamname == ''))
				{
					if ($button_add && $teamname == '')
					{
						// Create error message if teamname is empty
						$error  = $language->lang('ACP_F1_TEAMS_ERROR_TEAMNAME');
						trigger_error($error . adm_back_link($this->u_action), E_USER_WARNING);
					}

					// Init some vars
					$title_exp = $language->lang('ACP_F1_TEAMS_ADDTEAM_TITLE_EXPLAIN');
					$title = $language->lang('ACP_F1_TEAMS_ADDTEAM_TITLE');

					// Load values
					if ($button_edit)
					{
						$title_exp = $language->lang('ACP_F1_TEAMS_EDITTEAM_TITLE_EXPLAIN');
						$title = $language->lang('ACP_F1_TEAMS_EDITTEAM_TITLE');

						$sql = 'SELECT *
							FROM ' . $table_teams . '
							WHERE team_id = ' . (int) $team_id . '
							ORDER BY team_name';
						$result = $db->sql_query($sql);

						$row = $db->sql_fetchrow($result);

						$team_penalty 	= $row['team_penalty'];
						$teamcar		= $row['team_car'];
						$teamimg		= $row['team_img'];
						$teamname		= $row['team_name'];

						$db->sql_freeresult($result);
					}

					// Generate imagebox for team
					$image_team_combo		= $this->create_dropdown($dir_teams, $teamimg, 'teamimg', $language->lang('ACP_F1_SETTINGS_NO_TEAM_IMG'));
					$image_teamcar_combo	= $this->create_dropdown($dir_cars, $teamcar, 'teamcar', $language->lang('ACP_F1_SETTINGS_NO_CAR_IMG'));

					// Generate page
					if ($config['drdeath_f1webtip_show_gfx'] == 1)
					{
						$template->assign_block_vars('gfx_on', []);
					}

					$template->assign_vars([
						'L_ACP_F1_TEAMS_EXPLAIN'	=> $title_exp,
						'L_ACP_F1_TEAMS'			=> $title,
						'PREDEFINED_CAR'			=> $image_teamcar_combo,
						'PREDEFINED_ID'				=> $team_id,
						'PREDEFINED_IMG'			=> $image_team_combo,
						'PREDEFINED_NAME'			=> $teamname,
						'PREDEFINED_PENALTY'		=> $team_penalty,
						'S_ADDTEAM'					=> true,
						]
					);
				}
				else
				{
					// Load the team overview page
					// Fetch all teams
					$sql = 'SELECT *
						FROM ' . $table_teams . '
						ORDER BY team_name';
					$result = $db->sql_query($sql);

					while ($row = $db->sql_fetchrow($result))
					{
						$current_team	= $row['team_id'];
						$team_car		= ($row['team_car'] == '') ? '<img src="' . $ext_path . 'images/cars/'  . $config['drdeath_f1webtip_no_car_img']  . '" width="' . $config['drdeath_f1webtip_car_img_width']  . '" height="' . $config['drdeath_f1webtip_car_img_height']  . '" alt="">' : '<img src="' . $ext_path . 'images/cars/'  . $row['team_car'] . '" width="' . $config['drdeath_f1webtip_car_img_width']  . '" height="' . $config['drdeath_f1webtip_car_img_height']  . '" alt="">';
						$team_img		= ($row['team_img'] == '') ? '<img src="' . $ext_path . 'images/teams/' . $config['drdeath_f1webtip_no_team_img'] . '" width="' . $config['drdeath_f1webtip_team_img_width'] . '" height="' . $config['drdeath_f1webtip_team_img_height'] . '" alt="">' : '<img src="' . $ext_path . 'images/teams/' . $row['team_img'] . '" width="' . $config['drdeath_f1webtip_team_img_width'] . '" height="' . $config['drdeath_f1webtip_team_img_height'] . '" alt="">';
						$team_penalty 	= $row['team_penalty'];

						$pointssql		= '	SELECT SUM(wm_points) AS total_points
											FROM ' . $table_wm . '
											WHERE wm_team = ' . (int) $current_team;
						$team_points = $db->sql_query($pointssql);

						$current_points = $db->sql_fetchrow($team_points);

						$points = ($current_points['total_points'] <> '') ? $current_points['total_points'] - $team_penalty: 0 - $team_penalty;

						$db->sql_freeresult($team_points);

						$template->assign_block_vars(($config['drdeath_f1webtip_show_gfx'] == 1) ? 'teamrows_gfx' : 'teamrows', [
							'POINTS'		=> $points,
							'TEAM_PENALTY'	=> $team_penalty,
							'TEAMCAR'		=> $team_car,
							'TEAMID'		=> $row['team_id'],
							'TEAMIMG'		=> $team_img,
							'TEAMNAME'		=> $row['team_name'],
							]
						);
					}

					$db->sql_freeresult($result);

					// Generate page
					$colspan = 5;

					if ($config['drdeath_f1webtip_show_gfx'] == 1)
					{
						$colspan = 7;
						$template->assign_block_vars('gfx_on', []);
					}

					$template->assign_vars([
						'COLSPAN'		=> $colspan,
						'S_TEAMS'		=> true,
						'U_ACTION'		=> $this->u_action,
						]
					);
				}

			break;

			##########################
			###       RACES       ####
			##########################
			case 'races':

				$this->page_title = $language->lang('ACP_F1_RACES');

				// Check buttons & data
				$button_add 	= $request->is_set_post('add');
				$button_addrace = $request->is_set_post('addrace');
				$button_del 	= $request->is_set_post('del');
				$button_edit 	= $request->is_set_post('edit');

				$b_day 			= $request->variable('c_day'			,	$user->format_date(time(),"j")	);
				$b_month 		= $request->variable('c_month'			,	$user->format_date(time(),"n")	);
				$b_year 		= $request->variable('c_year'			,	$user->format_date(time(),"Y")	);
				$b_hour 		= $request->variable('c_hour'			,	$user->format_date(time(),"G")	);
				$b_minute 		= $request->variable('c_minute'			,	0	);
				$b_second 		= $request->variable('c_second'			,	0	);

				$race_id 		= $request->variable('race_id'			,	0	);
				$racedebut 		= $request->variable('racedebut'		,	0	,	true	);
				$racedistance 	= $request->variable('racedistance'		,	''	,	true	);
				$raceimg 		= $request->variable('raceimg'			,	''	,	true	);
				$racelaps 		= $request->variable('racelaps'			,	0	,	true	);
				$racelength 	= $request->variable('racelength'		,	''	,	true	);
				$racename 		= $request->variable('racename'			,	''	,	true	);

				//
				// Delete a race
				//

				if ($button_del && $race_id <> 0)
				{
					// Have we confirmed with yes ?
					if (confirm_box(true))
					{
						// Delete the race
						$sql = 'DELETE FROM ' . $table_races . '
								WHERE race_id = ' . (int) $race_id;
						$db->sql_query($sql);

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_RACE_DELETED', false, [$racename . ' (ID ' . $race_id . ')']);

						$error = $language->lang('ACP_F1_RACES_RACE_DELETED');
						trigger_error($error . adm_back_link($this->u_action));
						}
					// Create a confirmbox with yes and no.
					else
					{
						confirm_box(false, $language->lang('ACP_F1_RACES_RACE_DELETE_CONFIRM', $racename), build_hidden_fields([
							'del'				=> true,
							'race_id'			=> $race_id,
							'racename'			=> $racename,
							])
						, 'confirm_body.html');
					}
				}

				//
				// Add a new race
				//

				// Check if a race location is given
				if ($button_add && $racename == '')
				{
					// Create error message if racename is empty
					$error  = $language->lang('ACP_F1_RACES_ERROR_RACENAME');
					trigger_error($error . adm_back_link($this->u_action), E_USER_WARNING);
				}

				// add or update the race
				if ($button_add && $racename <> '')
				{
					// Is it salty ?
					if (!check_form_key('drdeath/f1webtip'))
					{
						trigger_error('FORM_INVALID');
					}

					// prevent error for timestamp out of range
					if ($b_year < 1970 || $b_year > 2105)
					{
						// Create error message if year is out of timestamp range
						$error  = $language->lang('ACP_F1_RACES_ERROR_DATE_YEAR');
						trigger_error($error . adm_back_link($this->u_action), E_USER_WARNING);
					}

					$gmt_time	= gmmktime($b_hour, $b_minute, $b_second, $b_month, $b_day, $b_year);
					$offset		= $user->timezone->getOffset(new DateTime($config['board_timezone']));

					$racetime 	= $gmt_time - $offset ;

					if ( $race_id == 0 )
					{
						// Add the race
						$sql_ary = [
							'race_name'		=> $racename,
							'race_img'		=> $raceimg,
							'race_quali'	=> 0,
							'race_result'	=> 0,
							'race_time'		=> $racetime,
							'race_length'	=> $racelength,
							'race_laps'		=> $racelaps,
							'race_distance'	=> $racedistance,
							'race_debut'	=> $racedebut,
							'race_mail'		=> 0,
						];

						$db->sql_query('INSERT INTO ' . $table_races . ' ' . $db->sql_build_array('INSERT', $sql_ary));

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_RACE_ADDED', false, [$racename]);
					}
					else
					{
						// Edit the race
						$sql_ary = [
							'race_name'		=> $racename,
							'race_img'		=> ($config['drdeath_f1webtip_show_gfxr'] == 1) ? $raceimg : '',
							'race_time'		=> $racetime,
							'race_length'	=> $racelength,
							'race_laps'		=> $racelaps,
							'race_distance'	=> $racedistance,
							'race_debut'	=> $racedebut,
						];

						$sql = 'UPDATE ' . $table_races . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE race_id = ' . (int) $race_id;
						$db->sql_query($sql);

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_RACE_EDITED', false, [$racename . ' (ID ' . $race_id . ')']);
					}

					$error = $language->lang('ACP_F1_RACES_RACE_UPDATED');
					trigger_error($error . adm_back_link($this->u_action));
				}

				//
				// Load add oder edit race
				//

				if ($button_addrace || ($button_edit && $race_id <> 0) || ($button_add && $racename == ''))
				{
					$title_exp 	= $language->lang('ACP_F1_RACES_TITEL_ADD_RACE_EXPLAIN');
					$title 		= $language->lang('ACP_F1_RACES_TITEL_ADD_RACE');

					// Load values
					if ($button_edit)
					{
						$title_exp 		= $language->lang('ACP_F1_RACES_TITEL_EDIT_RACE_EXPLAIN');
						$title 			= $language->lang('ACP_F1_RACES_TITEL_EDIT_RACE');

						$sql 			= '	SELECT *
											FROM ' . $table_races . '
											WHERE race_id = ' . (int) $race_id;
						$result = $db->sql_query($sql);

						$row 			= $db->sql_fetchrow($result);

						$racedebut 		= $row['race_debut'];
						$racedistance 	= $row['race_distance'];
						$raceimg 		= $row['race_img'];
						$racelaps 		= $row['race_laps'];
						$racelength 	= $row['race_length'];
						$racename 		= $row['race_name'];
						$racetime 		= $row['race_time'];

						$b_day 		= $user->format_date($racetime, "j");
						$b_month 	= $user->format_date($racetime, "n");
						$b_year 	= $user->format_date($racetime, "Y");
						$b_hour 	= $user->format_date($racetime, "G");
						$b_minute 	= $user->format_date($racetime, "i");
						$b_second 	= $user->format_date($racetime, "s");

						$db->sql_freeresult($result);
					}

					// Day month year hour minute second comboboxes
					$c_day = '<select name="c_day" size="1" class="gensmall">';

					for ($i = 1; $i < 32; ++$i)
					{
						$c_day .= '<option value="' . $i . '">&nbsp;' . $i . '&nbsp;</option>';
					}

					$c_day .= '</select>';
					$c_month = '<select name="c_month" size="1" class="gensmall">
								<option value="1">&nbsp;' .  $user->lang['datetime']['January'] . '&nbsp;</option>
								<option value="2">&nbsp;' .  $user->lang['datetime']['February'] . '&nbsp;</option>
								<option value="3">&nbsp;' .  $user->lang['datetime']['March'] . '&nbsp;</option>
								<option value="4">&nbsp;' .  $user->lang['datetime']['April'] . '&nbsp;</option>
								<option value="5">&nbsp;' .  $user->lang['datetime']['May'] . '&nbsp;</option>
								<option value="6">&nbsp;' .  $user->lang['datetime']['June'] . '&nbsp;</option>
								<option value="7">&nbsp;' .  $user->lang['datetime']['July'] . '&nbsp;</option>
								<option value="8">&nbsp;' .  $user->lang['datetime']['August'] . '&nbsp;</option>
								<option value="9">&nbsp;' .  $user->lang['datetime']['September'] . '&nbsp;</option>
								<option value="10">&nbsp;' . $user->lang['datetime']['October'] . '&nbsp;</option>
								<option value="11">&nbsp;' . $user->lang['datetime']['November'] . '&nbsp;</option>
								<option value="12">&nbsp;' . $user->lang['datetime']['December'] . '&nbsp;</option>
								</select>';
					$c_hour = '<select name="c_hour" size="1" class="gensmall">';

					for ($i = 0; $i < 24; ++$i)
					{
						$c_hour .= '<option value="' . $i . '">&nbsp;' . $i . '&nbsp;</option>';
					}

					$c_hour .= '</select>';

					$c_minute = '<select name="c_minute" size="1" class="gensmall">';
					$c_second = '<select name="c_second" size="1" class="gensmall">';

					for ($i = 0; $i < 60; ++$i)
					{
						$j = ($i < 10) ? '0' : '';
						$c_minute .= '<option value="' . $j . $i . '">&nbsp;' . $j . $i . '&nbsp;</option>';
						$c_second .= '<option value="' . $j . $i . '">&nbsp;' . $j . $i . '&nbsp;</option>';
					}

					$c_minute .= '</select>';
					$c_second .= '</select>';

					$c_day 		= str_replace("value=\"" . $b_day		. "\">", "value=\"" . $b_day	. "\" SELECTED>" , $c_day);
					$c_month 	= str_replace("value=\"" . $b_month		. "\">", "value=\"" . $b_month	. "\" SELECTED>" , $c_month);
					$c_hour 	= str_replace("value=\"" . $b_hour		. "\">", "value=\"" . $b_hour	. "\" SELECTED>" , $c_hour);
					$c_minute 	= str_replace("value=\"" . $b_minute	. "\">", "value=\"" . $b_minute	. "\" SELECTED>" , $c_minute);
					$c_second 	= str_replace("value=\"" . $b_second	. "\">", "value=\"" . $b_second	. "\" SELECTED>" , $c_second);

					$c_year 	= '<input type="text" class="post" name="c_year" size="4" maxlength="4" value="' . $b_year . '" />';

					$racetime_combos = $c_day . '&nbsp;.&nbsp;' . $c_month . '&nbsp;.&nbsp;' . $c_year . '<br/><br/>&nbsp;' . $c_hour . '&nbsp;:&nbsp;' . $c_minute . '&nbsp;:&nbsp;' . $c_second;

					// Generate imagebox for race
					$image_race_combo	= $this->create_dropdown($dir_races, $raceimg, 'raceimg', $language->lang('ACP_F1_SETTINGS_NO_RACE_IMG'));

					// Generate page
					if ($config['drdeath_f1webtip_show_gfxr'] == 1)
					{
						$template->assign_block_vars('gfxr_on', []);
					}

					$template->assign_vars([
						'L_ACP_F1_RACES_EXPLAIN'=> $title_exp,
						'L_ACP_F1_RACES' 		=> $title,
						'PREDEFINED_DEBUT' 		=> $racedebut,
						'PREDEFINED_DISTANCE' 	=> $racedistance,
						'PREDEFINED_ID' 		=> $race_id,
						'PREDEFINED_IMG' 		=> $image_race_combo,
						'PREDEFINED_LAPS' 		=> $racelaps,
						'PREDEFINED_LENGTH' 	=> $racelength,
						'PREDEFINED_NAME' 		=> $racename,
						'RACETIME_COMBOS' 		=> $racetime_combos,
						'S_ADD_RACES'			=> true,
						'U_ACTION'				=> $this->u_action,
						]
					);
				}
				else
				{
					//
					// Load the race page
					//

					// Get all race data
					$sql = 'SELECT *
							FROM ' . $table_races . '
								ORDER BY race_time ASC';
					$result = $db->sql_query($sql);

					while ($row = $db->sql_fetchrow($result))
					{
						$race_img = ($row['race_img'] == '') ? '<img src="' . $ext_path . 'images/races/' . $config['drdeath_f1webtip_no_race_img'] . '" width="94" height="54" alt="">' : '<img src="' . $ext_path . 'images/races/' . $row['race_img'] . '" width="94" height="54" alt="">';

						$template->assign_block_vars(($config['drdeath_f1webtip_show_gfxr'] == 1) ? 'racerows_gfxr' : 'racerows', [
							'RACEDEAD' 	=> $user->format_date($row['race_time'] - $config['drdeath_f1webtip_deadline_offset'], false, true),
							'RACEID' 	=> $row['race_id'],
							'RACEIMG' 	=> $race_img,
							'RACENAME' 	=> $row['race_name'],
							'RACETIME' 	=> $user->format_date($row['race_time'], false, true),
							]
						);
					}

					$db->sql_freeresult($result);

					// Generate page
					$colspan = 5;

					if ($config['drdeath_f1webtip_show_gfxr'] == 1)
					{
						$colspan = 6;
						$template->assign_block_vars('gfxr_on', []);
					}

					$template->assign_vars([
						'COLSPAN' 		=> $colspan,
						'S_RACES'		=> true,
						'U_ACTION'		=> $this->u_action,
						]
					);
				}

			break;
		}
	}
}
