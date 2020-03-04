<?php
/**
*
* @package phpBB Extension - DrDeath F1WebTip
* @copyright (c) 2014 Dr.Death - www.lpi-clan.de
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace drdeath\f1webtip\acp;

class main_module
{
	var $u_action;

	/*
	* Loads all files of ext/drdeath/f1webtip/images
	* Returns an array with all found files
	*/
	public function load_files($dir)
	{
		$result = array();
		$image_ary = preg_grep('~\.(jpeg|jpg|gif|png)$~', scandir($dir));

		// recreate array, shoud start with 0 ;-)
		foreach ($image_ary as $key => $value)
		{
					$result[] = $value;
		}

		return $result;
	}


	function main($id, $mode)
	{
		global $db, $config, $user, $template, $request, $auth;
		global $phpbb_container, $phpbb_extension_manager, $phpbb_log, $phpbb_root_path;

		$language = $phpbb_container->get('language');

		$ext_path = $phpbb_extension_manager->get_extension_path('drdeath/f1webtip', true);

		// Load extension language file
		$language->add_lang('acp_common', 'drdeath/f1webtip');

		$table_races 	= $phpbb_container->getParameter('tables.f1webtip.races');
		$table_teams	= $phpbb_container->getParameter('tables.f1webtip.teams');
		$table_drivers 	= $phpbb_container->getParameter('tables.f1webtip.drivers');
		$table_wm 		= $phpbb_container->getParameter('tables.f1webtip.wm');
		$table_tips 	= $phpbb_container->getParameter('tables.f1webtip.tips');

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
						$sql = 'TRUNCATE TABLE ' . $table_tips;
						$result = $db->sql_query($sql);

						$sql = 'TRUNCATE TABLE ' . $table_wm;
						$result = $db->sql_query($sql);

						$sql_ary = array(
							'race_result'		=> 0,
							'race_quali'		=> 0,
						);

						$sql = 'UPDATE ' . $table_races . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) ;
						$db->sql_query($sql);

						//remove penalty from drivers
						$sql_ary = array(
							'driver_penalty'	=> 0,
						);

						$sql = 'UPDATE ' . $table_drivers . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) ;
						$db->sql_query($sql);

						//remove penalty from teams
						$sql_ary = array(
							'team_penalty'	=> 0,
						);

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
						confirm_box(false, $language->lang('ACP_F1_SETTINGS_SEASON_RESET_EXPLAIN'), build_hidden_fields(array(
							'reset_all'				=> true,
							))
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

					$config->set('drdeath_f1webtip_mod_id', 			$request->variable('mod_id', 			'0'));

					// WebTip visible for guests can only be activated, if the F1 WebTip is not restricted to a specific group
					if ($request->variable('restrict_to', 	'0') == 0)
					{
						$config->set('drdeath_f1webtip_guest_viewing', $request->variable('guest_viewing', 	'0'));
					}
					else
					{
						$config->set('drdeath_f1webtip_guest_viewing', '0');
					}

					$config->set('drdeath_f1webtip_restrict_to', 		$request->variable('restrict_to', 		'0'));
					$config->set('drdeath_f1webtip_deadline_offset', 	$request->variable('deadline_offset', 	'0'));
					$config->set('drdeath_f1webtip_event_change', 		$request->variable('event_change', 		'0'));
					$config->set('drdeath_f1webtip_forum_id', 			$request->variable('forum_id', 			'0'));
					$config->set('drdeath_f1webtip_show_in_profile', 	$request->variable('show_in_profile', 	'0'));
					$config->set('drdeath_f1webtip_show_in_viewtopic', 	$request->variable('show_in_viewtopic', '0'));
					$config->set('drdeath_f1webtip_show_countdown', 	$request->variable('show_countdown', 	'0'));

					// Cron Reminder can only be activated, if the F1 WebTip is restricted to a specific group
					if ($request->variable('restrict_to', 	'0') == 0)
					{
						$config->set('drdeath_f1webtip_reminder_enabled', '0');
					}
					else
					{
						$config->set('drdeath_f1webtip_reminder_enabled', $request->variable('reminder_enabled', '0'));
					}

					$config->set('drdeath_f1webtip_points_mentioned', 	$request->variable('points_mentioned', 	'0'));
					$config->set('drdeath_f1webtip_points_placed', 		$request->variable('points_placed', 	'0'));
					$config->set('drdeath_f1webtip_points_fastest', 	$request->variable('points_fastest', 	'0'));
					$config->set('drdeath_f1webtip_points_tired', 		$request->variable('points_tired', 		'0'));
					$config->set('drdeath_f1webtip_points_safety_car', 	$request->variable('points_safety_car', '0'));

					$config->set('drdeath_f1webtip_show_avatar', 		$request->variable('show_avatar', 		'0'));

					$config->set('drdeath_f1webtip_show_headbanner', 	$request->variable('show_headbanner', 	'0'));
					$config->set('drdeath_f1webtip_head_height', 		$request->variable('head_height', 		$config['drdeath_f1webtip_head_height']));
					$config->set('drdeath_f1webtip_head_width', 		$request->variable('head_width', 		$config['drdeath_f1webtip_head_width']));
					$config->set('drdeath_f1webtip_headbanner1_img', 	$request->variable('headbanner1_img', 	$config['drdeath_f1webtip_headbanner1_img']));
					$config->set('drdeath_f1webtip_headbanner2_img', 	$request->variable('headbanner2_img', 	$config['drdeath_f1webtip_headbanner2_img']));
					$config->set('drdeath_f1webtip_headbanner3_img', 	$request->variable('headbanner3_img', 	$config['drdeath_f1webtip_headbanner3_img']));

					$config->set('drdeath_f1webtip_show_gfxr', 			$request->variable('show_gfxr', 		'0'));
					$config->set('drdeath_f1webtip_no_race_img', 		$request->variable('no_race_img', 		$config['drdeath_f1webtip_no_race_img']));
					$config->set('drdeath_f1webtip_race_img_height', 	$request->variable('race_img_height', 	$config['drdeath_f1webtip_race_img_height']));
					$config->set('drdeath_f1webtip_race_img_width', 	$request->variable('race_img_width', 	$config['drdeath_f1webtip_race_img_width']));
					$config->set('drdeath_f1webtip_show_gfx', 			$request->variable('show_gfx', 			'0'));
					$config->set('drdeath_f1webtip_no_car_img', 		$request->variable('no_car_img', 		$config['drdeath_f1webtip_no_car_img']));
					$config->set('drdeath_f1webtip_car_img_height', 	$request->variable('car_img_height', 	$config['drdeath_f1webtip_car_img_height']));
					$config->set('drdeath_f1webtip_car_img_width', 		$request->variable('car_img_width', 	$config['drdeath_f1webtip_car_img_width']));
					$config->set('drdeath_f1webtip_no_driver_img', 		$request->variable('no_driver_img', 	$config['drdeath_f1webtip_no_driver_img']));
					$config->set('drdeath_f1webtip_driver_img_height', 	$request->variable('driver_img_height',	$config['drdeath_f1webtip_driver_img_height']));
					$config->set('drdeath_f1webtip_driver_img_width', 	$request->variable('driver_img_width', 	$config['drdeath_f1webtip_driver_img_width']));
					$config->set('drdeath_f1webtip_no_team_img', 		$request->variable('no_team_img', 		$config['drdeath_f1webtip_no_team_img']));
					$config->set('drdeath_f1webtip_team_img_height', 	$request->variable('team_img_height', 	$config['drdeath_f1webtip_team_img_height']));
					$config->set('drdeath_f1webtip_team_img_width', 	$request->variable('team_img_width', 	$config['drdeath_f1webtip_team_img_width']));

					$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_SETTINGS');

					$error = $language->lang('ACP_F1WEBTIP_SETTING_SAVED');
					trigger_error($error . adm_back_link($this->u_action));
				}

				//
				// Generate a moderator list for the F1 WebTip
				//

				$combo_mod_entries = '';
				$mod_ary = $admin_ary = array();

				//Get all possible moderators and administrators at once
				$mod_ary			= $auth->acl_get_list(false, 'm_', false);
				$mod_ary			= (array($mod_ary[0]['m_']) == true) ? $mod_ary[0]['m_'] : array();
				$admin_ary			= $auth->acl_get_list(false, 'a_', false);
				$admin_ary			= (array($admin_ary[0]['a_']) == true) ? $admin_ary[0]['a_'] : array();

				$admin_mod_array 	= array_unique(array_merge($admin_ary, $mod_ary));

				$sql = 'SELECT user_id, username
						FROM ' . USERS_TABLE . '
						WHERE ' . $db->sql_in_set('user_id', $admin_mod_array);

				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$selected = ($row['user_id'] == $config['drdeath_f1webtip_mod_id']) ? 'selected' : '';
					$combo_mod_entries .= '<option value="' . $row['user_id'] . '" ' . $selected . '>' . $row['username'] . '</option>';
				}

				// Generate possible moderator combobox
				$mods_combo		 = '<select name="mod_id">';
				$mods_combo		.= $combo_mod_entries;
				$mods_combo		.= '</select>';

				//
				// Get all group data
				// Don't select the default phpBB3 groups
				// If choosen "deactivated" - all "registered user" have access.
				//

				$combo_groups_entries = '';

				$sql = 'SELECT *
					FROM ' . GROUPS_TABLE . '
					WHERE group_type <> ' . GROUP_SPECIAL ;
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$selected = ($row['group_id'] == $config['drdeath_f1webtip_restrict_to']) ? 'selected' : '';
					$combo_groups_entries .= '<option value="' . $row['group_id'] . '" ' . $selected . '>' . $row['group_name'] . '</option>';
				}

				$db->sql_freeresult($result);

				// Generate groups combobox
				$selected = ($config['drdeath_f1webtip_restrict_to'] == 0) ? 'selected' : '';
				$group_combo	 = '<select name="restrict_to">';
				$group_combo	.= '<option value="0" ' . $selected . '>' . $language->lang('ACP_F1_SETTINGS_DEACTIVATED') . '</option>';
				$group_combo	.= $combo_groups_entries;
				$group_combo	.= '</select>';

				//
				// Get all forum data - Don't select categories or links
				//

				$combo_forums_entries = '';

				$sql = 'SELECT forum_id, forum_name, forum_type
					FROM ' . FORUMS_TABLE . '
					WHERE forum_type <> ' . FORUM_CAT . '
						AND forum_type <> ' . FORUM_LINK . '
					ORDER BY forum_name ASC';
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$selected = ($row['forum_id'] == $config['drdeath_f1webtip_forum_id'] ) ? 'selected' : '';
					$combo_forums_entries .= '<option value="' . $row['forum_id'] . '" ' . $selected . '>' . $row['forum_name'] . '</option>';
				}
				$db->sql_freeresult($result);

				// Generate forums combobox
				$selected		 = ($config['drdeath_f1webtip_forum_id'] == 0) ? 'selected' : '';
				$forums_combo	 = '<select name="forum_id">';
				$forums_combo	.= '<option value="0" ' . $selected . '>' . $language->lang('ACP_F1_SETTINGS_DEACTIVATED') . '</option>';
				$forums_combo	.= $combo_forums_entries;
				$forums_combo	.= '</select>';

				//
				// Generate imageboxes
				//

				$image_dir = $phpbb_root_path . 'ext/drdeath/f1webtip/images';
				$images = $this->load_files($image_dir);
				$image_headbanner1_entries = $image_headbanner2_entries = $image_headbanner3_entries ='';
				$image_no_race_img_entries = $image_no_car_img_entries = $image_no_driver_img_entries = $image_no_team_img_entries = '';

				// Generate imagebox for headerbanner1 index
				foreach ($images as $image)
				{
					$selected = ($image == $config['drdeath_f1webtip_headbanner1_img']) ? 'selected' : '';
					$image_headbanner1_entries .= '<option value="' . $image . '" ' . $selected . '>' . $image . '</option>';
				}

				$image_headbanner1_combo	 = '<select name="headbanner1_img">';
				$image_headbanner1_combo	.= $image_headbanner1_entries;
				$image_headbanner1_combo	.= '</select>';

				// Generate imagebox for headerbanner2 rules
				foreach ($images as $image)
				{
					$selected = ($image == $config['drdeath_f1webtip_headbanner2_img']) ? 'selected' : '';
					$image_headbanner2_entries .= '<option value="' . $image . '" ' . $selected . '>' . $image . '</option>';
				}

				$image_headbanner2_combo	 = '<select name="headbanner2_img">';
				$image_headbanner2_combo	.= $image_headbanner2_entries;
				$image_headbanner2_combo	.= '</select>';

				// Generate imagebox for headerbanner3 stats
				foreach ($images as $image)
				{
					$selected = ($image == $config['drdeath_f1webtip_headbanner3_img']) ? 'selected' : '';
					$image_headbanner3_entries .= '<option value="' . $image . '" ' . $selected . '>' . $image . '</option>';
				}

				$image_headbanner3_combo	 = '<select name="headbanner3_img">';
				$image_headbanner3_combo	.= $image_headbanner3_entries;
				$image_headbanner3_combo	.= '</select>';

				// Generate imagebox for no_race_img
				foreach ($images as $image)
				{
					$selected = ($image == $config['drdeath_f1webtip_no_race_img']) ? 'selected' : '';
					$image_no_race_img_entries .= '<option value="' . $image . '" ' . $selected . '>' . $image . '</option>';
				}

				$image_no_race_img_combo	 = '<select name="no_race_img">';
				$image_no_race_img_combo	.= $image_no_race_img_entries;
				$image_no_race_img_combo	.= '</select>';

				// Generate imagebox for no_car_img
				foreach ($images as $image)
				{
					$selected = ($image == $config['drdeath_f1webtip_no_car_img']) ? 'selected' : '';
					$image_no_car_img_entries .= '<option value="' . $image . '" ' . $selected . '>' . $image . '</option>';
				}

				$image_no_car_img_combo	 = '<select name="no_car_img">';
				$image_no_car_img_combo	.= $image_no_car_img_entries;
				$image_no_car_img_combo	.= '</select>';

				// Generate imagebox for no_driver_img
				foreach ($images as $image)
				{
					$selected = ($image == $config['drdeath_f1webtip_no_driver_img']) ? 'selected' : '';
					$image_no_driver_img_entries .= '<option value="' . $image . '" ' . $selected . '>' . $image . '</option>';
				}

				$image_no_driver_img_combo	 = '<select name="no_driver_img">';
				$image_no_driver_img_combo	.= $image_no_driver_img_entries;
				$image_no_driver_img_combo	.= '</select>';

				// Generate imagebox for no_team_img
				foreach ($images as $image)
				{
					$selected = ($image == $config['drdeath_f1webtip_no_team_img']) ? 'selected' : '';
					$image_no_team_img_entries .= '<option value="' . $image . '" ' . $selected . '>' . $image . '</option>';
				}

				$image_no_team_img_combo	 = '<select name="no_team_img">';
				$image_no_team_img_combo	.= $image_no_team_img_entries;
				$image_no_team_img_combo	.= '</select>';

				if ($config['drdeath_f1webtip_show_headbanner'])
				{
					$template->assign_block_vars('headbanner_on', array());
				}

				if ($config['drdeath_f1webtip_show_gfxr'])
				{
					$template->assign_block_vars('gfxr_on', array());
				}

				if ($config['drdeath_f1webtip_show_gfx'])
				{
					$template->assign_block_vars('gfx_on', array());
				}

				$template->assign_vars(array(
					'S_SETTING'							=> true,
					'U_ACTION'							=> $this->u_action,
					'D_MODERATOR'						=> $mods_combo,
					'D_ACCESS_GROUP'					=> $group_combo,
					'D_FORUM'							=> $forums_combo,
					'GUEST_VIEWING'						=> $config['drdeath_f1webtip_guest_viewing'],
					'SETTING_OFFSET'					=> $config['drdeath_f1webtip_deadline_offset'],
					'SETTING_RACEOFFSET'				=> $config['drdeath_f1webtip_event_change'],
					'SHOW_PROFILE'						=> $config['drdeath_f1webtip_show_in_profile'],
					'SHOW_VIEWTOPIC'					=> $config['drdeath_f1webtip_show_in_viewtopic'],
					'SHOW_COUNTDOWN'					=> $config['drdeath_f1webtip_show_countdown'],
					'REMINDER_ENABLED'					=> $config['drdeath_f1webtip_reminder_enabled'],

					'POINTS_MENTIONED'					=> $config['drdeath_f1webtip_points_mentioned'],
					'POINTS_PLACED'						=> $config['drdeath_f1webtip_points_placed'],
					'POINTS_FASTEST'					=> $config['drdeath_f1webtip_points_fastest'],
					'POINTS_TIRED'						=> $config['drdeath_f1webtip_points_tired'],
					'POINTS_SAFETY_CAR'					=> $config['drdeath_f1webtip_points_safety_car'],

					'SHOW_HEADBANNER'					=> $config['drdeath_f1webtip_show_headbanner'],
					'HEADBANNER_IMG_HEIGHT'				=> $config['drdeath_f1webtip_head_height'],
					'HEADBANNER_IMG_WIDTH'				=> $config['drdeath_f1webtip_head_width'],

					'HEADBANNER1_IMG'					=> $image_headbanner1_combo,
					'HEADBANNER2_IMG'					=> $image_headbanner2_combo,
					'HEADBANNER3_IMG'					=> $image_headbanner3_combo,

					'SHOW_GFXR'							=> $config['drdeath_f1webtip_show_gfxr'],
					'NO_RACE_IMG'						=> $image_no_race_img_combo,
					'RACE_IMG_HEIGHT'					=> $config['drdeath_f1webtip_race_img_height'],
					'RACE_IMG_WIDTH'					=> $config['drdeath_f1webtip_race_img_width'],

					'SHOW_GFX'							=> $config['drdeath_f1webtip_show_gfx'],
					'NO_CAR_IMG'						=> $image_no_car_img_combo,
					'CAR_IMG_HEIGHT'					=> $config['drdeath_f1webtip_car_img_height'],
					'CAR_IMG_WIDTH'						=> $config['drdeath_f1webtip_car_img_width'],
					'NO_DRIVER_IMG'						=> $image_no_driver_img_combo,
					'DRIVER_IMG_HEIGHT'					=> $config['drdeath_f1webtip_driver_img_height'],
					'DRIVER_IMG_WIDTH'					=> $config['drdeath_f1webtip_driver_img_width'],
					'NO_TEAM_IMG'						=> $image_no_team_img_combo,
					'TEAM_IMG_HEIGHT'					=> $config['drdeath_f1webtip_team_img_height'],
					'TEAM_IMG_WIDTH'					=> $config['drdeath_f1webtip_team_img_width'],

					'SHOW_AVATAR'						=> $config['drdeath_f1webtip_show_avatar'],
				));

			break;

			##########################
			###      DRIVERS      ####
			##########################
			case 'drivers':

				$this->page_title = $language->lang('ACP_F1_DRIVERS');

				$reset_all = (isset($_POST['reset_all'])) ? true : false;

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
						$sql = 'DELETE FROM ' . $table_drivers . '
								WHERE driver_id = ' . (int) $driver_id;
						$db->sql_query($sql);

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_DRIVER_DELETED', false, array($drivername . ' (ID ' . $driver_id . ')' ));

						$error = sprintf($language->lang('ACP_F1_DRIVERS_DRIVER_DELETED'), $drivername);
						trigger_error($error . adm_back_link($this->u_action));
					}
					// Create a confirmbox with yes and no.
					else
					{
						confirm_box(false, sprintf($language->lang('ACP_F1_DRIVERS_DRIVER_DELETE_CONFIRM'), $drivername), build_hidden_fields(array(
							'del'				=> true,
							'driver_id'			=> $driver_id,
							'drivername'		=> $drivername,
							))
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
						$sql_ary = array(
							'driver_name'		=> $drivername,
							'driver_img'		=> $driverimg,
							'driver_team'		=> $driverteam,
							'driver_penalty'	=> $driver_penalty,
							'driver_disabled'	=> $driver_disabled,
						);

						$db->sql_query('INSERT INTO ' . $table_drivers . ' ' . $db->sql_build_array('INSERT', $sql_ary));

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_DRIVER_ADDED', false, array($drivername));
					}
					else
					{
						$sql_ary = array(
							'driver_name'		=> $drivername,
							'driver_img'		=> ($config['drdeath_f1webtip_show_gfx'] == 1) ? $driverimg : '',
							'driver_team'		=> $driverteam,
							'driver_penalty'	=> $driver_penalty,
							'driver_disabled'	=> $driver_disabled,
						);

						$sql = 'UPDATE ' . $table_drivers . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) . "
							WHERE driver_id = $driver_id";
						$db->sql_query($sql);

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_DRIVER_EDITED', false, array($drivername . ' (ID ' . $driver_id . ')' ));
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

					// Create error messages
					if ($button_add && $drivername == '')
					{
						$error	 = $language->lang('ACP_F1_DRIVERS_ERROR_DRIVERNAME');
						trigger_error($error . adm_back_link($this->u_action), E_USER_WARNING);
					}

					// Init some vars
					$title_exp 	= $language->lang('ACP_F1_DRIVERS_TITEL_ADD_DRIVER_EXPLAIN');
					$title 		= $language->lang('ACP_F1_DRIVERS_TITEL_ADD_DRIVER');

					// Load initial values
					if ($button_edit || ($button_add && $drivername == ''))
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

						if ($button_edit)
						{
							$drivername 	= $row['driver_name'];
						}

						$driverimg 			= $row['driver_img'];
						$preselected_id 	= $row['driver_team'];
						$driver_penalty 	= $row['driver_penalty'];
						$driver_disabled 	= $row['driver_disabled'];

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

						$template->assign_block_vars('teamrows', array(
							'TEAMNAME'		=> $row['team_name'],
							'TEAM_ID'		=> $row['team_id'],
							'PRESELECTED'	=> $preselected,
							)
						);
					}

					$db->sql_freeresult($result);

					//
					// Generate imageboxes
					//

					$image_dir = $phpbb_root_path . 'ext/drdeath/f1webtip/images';
					$images = $this->load_files($image_dir);
					$driverimg_entries = '';

					// Generate imagebox for driver
					foreach ($images as $image)
					{
						$selected = ($image == $driverimg) ? 'selected' : '';
						$driverimg_entries .= '<option value="' . $image . '" ' . $selected . '>' . $image . '</option>';
					}

					$image_driver_combo	 = '<select name="driverimg">';
					$image_driver_combo	.= '<option value="" ' . $selected . '>' . $language->lang('ACP_F1_SETTINGS_NO_DRIVER_IMG') . '</option>';
					$image_driver_combo	.= $driverimg_entries;
					$image_driver_combo	.= '</select>';

					// Generate page
					if ($config['drdeath_f1webtip_show_gfx'] == 1)
					{
						$template->assign_block_vars('gfx_on', array());
					}

					$template->assign_vars(array(
						'U_ACTION'					=> $this->u_action,
						'S_ADDDRIVERS'				=> true,
						'PREDEFINED_NAME'			=> $drivername,
						'PREDEFINED_IMG'			=> $image_driver_combo,
						'PREDEFINED_PENALTY'		=> $driver_penalty,
						'S_DRIVER_DISABLED'			=> ($driver_disabled == true) ? "checked=\"checked\"" : "",
						'S_DRIVER_ENABLED'			=> ($driver_disabled == false) ? "checked=\"checked\"" : "",
						'DRIVER_ID'					=> $driver_id,
						'L_ACP_F1_DRIVERS_EXPLAIN'	=> $title_exp,
						'L_ACP_F1_DRIVERS'			=> $title,
						)
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

					while ($row = $db->sql_fetchrow($result))
					{
						$teams[$row['team_id']]		= $row['team_name'];
						$teamlogos[$row['team_id']]	= $row['team_img'];
					}

					$db->sql_freeresult($result);

					// Get all drivers data
					$sql = 'SELECT *
						FROM ' . $table_drivers . '
						ORDER BY driver_name';
					$result = $db->sql_query($sql);

					while ($row = $db->sql_fetchrow($result))
					{
						$driverimg			= $row['driver_img'];
						$current_user_id	= $row['driver_id'];
						$driverimg			= ($driverimg == '') ? '<img src="' . $ext_path . 'images/' . $config['drdeath_f1webtip_no_driver_img'] . '" width="' . $config['drdeath_f1webtip_driver_img_width'] . '" height="' . $config['drdeath_f1webtip_driver_img_height'] . '" alt="">' : '<img src="' . $ext_path . 'images/' . $driverimg . '" width="' . $config['drdeath_f1webtip_driver_img_width'] . '" height="' . $config['drdeath_f1webtip_driver_img_height'] . '" alt="">';
						$driver_penalty 	= $row['driver_penalty'];
						$driver_disabled 	= $row['driver_disabled'];

						$pointssql = 'SELECT SUM(wm_points) AS total_points
							FROM ' . $table_wm . '
							WHERE wm_driver = ' . (int) $current_user_id;
						$user_points = $db->sql_query($pointssql);

						$driver_points = $db->sql_fetchrow($user_points);
						$points = ($driver_points['total_points'] <> '') ? $driver_points['total_points'] - $driver_penalty : 0 - $driver_penalty;

						$db->sql_freeresult($user_points);

						$template->assign_block_vars(($config['drdeath_f1webtip_show_gfx'] == 1) ? 'driverrows_gfx' : 'driverrows', array(
							'DRIVERNAME'		=> $row['driver_name'],
							'DRIVERID'			=> $row['driver_id'],
							'DRIVERIMG'			=> $driverimg,
							'DRIVERTEAM'		=> (isset($teams[$row['driver_team']])) ? $teams[$row['driver_team']] : '',
							'DRIVERPOINTS'		=> $points,
							'DRIVER_PENALTY'	=> $driver_penalty,
							'DRIVER_DISABLED'	=> ($driver_disabled == true) ? $language->lang('NO') : $language->lang('YES'),
							)
						);

					}

					$db->sql_freeresult($result);

					$colspan = 7;

					if ($config['drdeath_f1webtip_show_gfx'] == 1)
					{
						$colspan = 8;
						$template->assign_block_vars('gfx_on', array());
					}

					// Generate page
					$template->assign_vars(array(
						'U_ACTION'			=> $this->u_action,
						'S_DRIVERS'			=> true,
						'COLSPAN'			=> $colspan,
						'DRIVER_ID'			=> $driver_id,
						)
					);
				}

			break;

			##########################
			###       TEAMS       ####
			##########################
			case 'teams':

				$this->page_title = $language->lang('ACP_F1_TEAMS');

				$lang = 'ACP_F1_TEAMS';

				// Check buttons & data
				$button_addteam 	= $request->is_set_post('addteam');
				$button_add 		= $request->is_set_post('add');
				$button_del 		= $request->is_set_post('del');
				$button_edit 		= $request->is_set_post('edit');

				$teamimg 			= $request->variable('teamimg'		,	''	,	true	);
				$teamcar 			= $request->variable('teamcar'		,	''	,	true	);
				$teamname 			= $request->variable('teamname'	,	''	,	true	);
				$team_id 			= $request->variable('team_id'		,	0	);
				$team_penalty		= $request->variable('team_penalty',	0.0	);

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
							$db->sql_freeresult($result);

							$error = sprintf($language->lang('ACP_F1_TEAMS_TEAM_NOT_DELETED'), $teamname);
							trigger_error($error . adm_back_link($this->u_action), E_USER_WARNING);
						}
						else
						{
							$db->sql_freeresult($result);

							$sql = 'DELETE FROM ' . $table_teams . '
									WHERE team_id = ' . (int) $team_id;
							$db->sql_query($sql);

							$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_TEAM_DELETED', false, array($teamname . ' (ID ' . $team_id . ')' ));

							$error = sprintf($language->lang('ACP_F1_TEAMS_TEAM_DELETED'), $teamname);
							trigger_error($error . adm_back_link($this->u_action));
						}
					}
					// Create a confirmbox with yes and no.
					else
					{
						confirm_box(false, sprintf($language->lang('ACP_F1_TEAMS_TEAM_DELETE_CONFIRM'), $teamname), build_hidden_fields(array(
							'del'				=> true,
							'team_id'			=> $team_id,
							'teamname'			=> $teamname,
							))
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
						$sql_ary = array(
							'team_name'		=> $teamname,
							'team_img'		=> $teamimg,
							'team_car'		=> $teamcar,
							'team_penalty'	=> $team_penalty,
						);

						$db->sql_query('INSERT INTO ' . $table_teams . ' ' . $db->sql_build_array('INSERT', $sql_ary));

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_TEAM_ADDED', false, array($teamname));
					}
					else
					{
						$sql_ary = array(
							'team_name'		=> $teamname,
							'team_img'		=> ($config['drdeath_f1webtip_show_gfx'] == 1) ? $teamimg : '',
							'team_car'		=> ($config['drdeath_f1webtip_show_gfx'] == 1) ? $teamcar : '',
							'team_penalty'	=> $team_penalty,
						);

						$sql = 'UPDATE ' . $table_teams . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE team_id = ' . (int) $team_id;

						$db->sql_query($sql);

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_TEAM_EDITED', false, array($teamname . ' (ID ' . $team_id . ')' ));
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

						$teamname		= $row['team_name'];
						$teamimg		= $row['team_img'];
						$teamcar		= $row['team_car'];
						$team_penalty 	= $row['team_penalty'];

						$db->sql_freeresult($result);
					}

					//
					// Generate imageboxes
					//

					$image_dir = $phpbb_root_path . 'ext/drdeath/f1webtip/images';
					$images = $this->load_files($image_dir);
					$teamimg_entries = $teamcarimg_entries = '';

					// Generate imagebox for team
					foreach ($images as $image)
					{
						$selected = ($image == $teamimg) ? 'selected' : '';
						$teamimg_entries .= '<option value="' . $image . '" ' . $selected . '>' . $image . '</option>';
					}

					$image_team_combo	 = '<select name="teamimg">';
					$image_team_combo	.= '<option value="" ' . $selected . '>' . $language->lang('ACP_F1_SETTINGS_NO_TEAM_IMG') . '</option>';
					$image_team_combo	.= $teamimg_entries;
					$image_team_combo	.= '</select>';

					// Generate imagebox for team car
					foreach ($images as $image)
					{
						$selected = ($image == $teamcar) ? 'selected' : '';
						$teamcarimg_entries .= '<option value="' . $image . '" ' . $selected . '>' . $image . '</option>';
					}

					$image_teamcar_combo	 = '<select name="teamcar">';
					$image_teamcar_combo	.= '<option value="" ' . $selected . '>' . $language->lang('ACP_F1_SETTINGS_NO_CAR_IMG') . '</option>';
					$image_teamcar_combo	.= $teamcarimg_entries;
					$image_teamcar_combo	.= '</select>';

					// Generate page
					if ($config['drdeath_f1webtip_show_gfx'] == 1)
					{
						$template->assign_block_vars('gfx_on', array());
					}

					$template->assign_vars(array(
						'S_ADDTEAM'					=> true,
						'PREDEFINED_NAME'			=> $teamname,
						'PREDEFINED_IMG'			=> $image_team_combo,
						'PREDEFINED_CAR'			=> $image_teamcar_combo,
						'PREDEFINED_ID'				=> $team_id,
						'PREDEFINED_PENALTY'		=> $team_penalty,
						'L_ACP_F1_TEAMS_EXPLAIN'	=> $title_exp,
						'L_ACP_F1_TEAMS'			=> $title,
						)
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
						$team_img		= $row['team_img'];
						$team_car		= $row['team_car'];
						$current_team	= $row['team_id'];
						$team_img		= ($team_img == '') ? '<img src="' . $ext_path . 'images/' . $config['drdeath_f1webtip_no_team_img'] . '" width="' . $config['drdeath_f1webtip_team_img_width'] . '" height="' . $config['drdeath_f1webtip_team_img_height'] . '" alt="">' : '<img src="' . $ext_path . 'images/' . $team_img . '" width="' . $config['drdeath_f1webtip_team_img_width'] . '" height="' . $config['drdeath_f1webtip_team_img_height'] . '" alt="">';
						$team_car		= ($team_car == '') ? '<img src="' . $ext_path . 'images/' . $config['drdeath_f1webtip_no_car_img'] . '" width="' . $config['drdeath_f1webtip_car_img_width'] . '" height="' . $config['drdeath_f1webtip_car_img_height'] . '" alt="">' : '<img src="' . $ext_path . 'images/' . $team_car . '" width="' . $config['drdeath_f1webtip_car_img_width'] . '" height="' . $config['drdeath_f1webtip_car_img_height'] . '" alt="">';
						$team_penalty 	= $row['team_penalty'];

						$pointssql		= '	SELECT SUM(wm_points) AS total_points
											FROM ' . $table_wm . '
											WHERE wm_team = ' . (int) $current_team;
						$team_points = $db->sql_query($pointssql);

						$current_points = $db->sql_fetchrow($team_points);

						$points = ($current_points['total_points'] <> '') ? $current_points['total_points'] - $team_penalty: 0 - $team_penalty;

						$db->sql_freeresult($team_points);

						$template->assign_block_vars(($config['drdeath_f1webtip_show_gfx'] == 1) ? 'teamrows_gfx' : 'teamrows', array(
							'TEAMNAME'		=> $row['team_name'],
							'TEAMID'		=> $row['team_id'],
							'POINTS'		=> $points,
							'TEAMIMG'		=> $team_img,
							'TEAMCAR'		=> $team_car,
							'TEAM_PENALTY'	=> $team_penalty,
							)
						);
					}

					$db->sql_freeresult($result);

					// Generate page
					$colspan = 5;

					if ($config['drdeath_f1webtip_show_gfx'] == 1)
					{
						$colspan = 7;
						$template->assign_block_vars('gfx_on', array());
					}

					$template->assign_vars(array(
						'U_ACTION'		=> $this->u_action,
						'S_TEAMS'		=> true,
						'COLSPAN'		=> $colspan,
						)
					);
				}

			break;

			##########################
			###       RACES       ####
			##########################
			case 'races':

				$this->page_title = $language->lang('ACP_F1_RACES');

				// Check buttons & data
				$button_addrace = $request->is_set_post('addrace');
				$button_add 	= $request->is_set_post('add');
				$button_del 	= $request->is_set_post('del');
				$button_edit 	= $request->is_set_post('edit');

				$b_day 			= $request->variable('c_day'			,	$user->format_date(time(),"d")	);
				$b_month 		= $request->variable('c_month'			,	$user->format_date(time(),"n")	);
				$b_year 		= $request->variable('c_year'			,	$user->format_date(time(),"Y")	);
				$b_hour 		= $request->variable('c_hour'			,	$user->format_date(time(),"G")	);
				$b_minute 		= $request->variable('c_minute'			,	0	);
				$b_second 		= $request->variable('c_second'			,	0	);

				$raceimg 		= $request->variable('raceimg'			,	''	,	true	);
				$racename 		= $request->variable('racename'			,	''	,	true	);
				$racelength 	= $request->variable('racelength'		,	''	,	true	);
				$racedistance 	= $request->variable('racedistance'		,	''	,	true	);
				$racelaps 		= $request->variable('racelaps'			,	0	,	true	);
				$racedebut 		= $request->variable('racedebut'		,	0	,	true	);

				$race_id 		= $request->variable('race_id'			,	0	);

				//
				// Delete a race
				//

				if ($button_del && $race_id <> 0)
				{
					// Have we confirmed with yes ?
					if (confirm_box(true))
					{
						$sql = 'DELETE FROM ' . $phpbb_container->getParameter('tables.f1webtip.races') . '
								WHERE race_id = ' . (int) $race_id;
						$db->sql_query($sql);

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_RACE_DELETED', false, array($racename . ' (ID ' . $race_id . ')' ));

						$error = $language->lang('ACP_F1_RACES_RACE_DELETED');
						trigger_error($error . adm_back_link($this->u_action));
						}
					// Create a confirmbox with yes and no.
					else
					{
						confirm_box(false, sprintf($language->lang('ACP_F1_RACES_RACE_DELETE_CONFIRM'), $racename), build_hidden_fields(array(
							'del'				=> true,
							'race_id'			=> $race_id,
							'racename'			=> $racename,
							))
						, 'confirm_body.html');
					}
				}

				//
				// Add a new race
				//

				// Check if a race location is given
				if ($button_add && $racename == '')
				{
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

					// is_dst is deprecated since php 5.1 and was removed in php 7.0
					$racetime = mktime($b_hour, $b_minute, $b_second, $b_month, $b_day, $b_year);

					if ( $race_id == 0 )
					{
						$sql_ary = array(
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
						);

						$db->sql_query('INSERT INTO ' . $phpbb_container->getParameter('tables.f1webtip.races') . ' ' . $db->sql_build_array('INSERT', $sql_ary));

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_RACE_ADDED', false, array($racename));
					}
					else
					{
						$sql_ary = array(
							'race_name'		=> $racename,
							'race_img'		=> ($config['drdeath_f1webtip_show_gfxr'] == 1) ? $raceimg : '',
							'race_time'		=> $racetime,
							'race_length'	=> $racelength,
							'race_laps'		=> $racelaps,
							'race_distance'	=> $racedistance,
							'race_debut'	=> $racedebut,
						);

						$sql = 'UPDATE ' . $table_races . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE race_id = ' . (int) $race_id;
						$db->sql_query($sql);

						$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_FORMEL_RACE_EDITED', false, array($racename . ' (ID ' . $race_id . ')' ));
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

						$racename 		= $row['race_name'];
						$raceimg 		= $row['race_img'];
						$racelength 	= $row['race_length'];
						$racelaps 		= $row['race_laps'];
						$racedistance 	= $row['race_distance'];
						$racedebut 		= $row['race_debut'];
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
								<option value="1">&nbsp;' . $user->lang['datetime']['January'] . '&nbsp;</option>
								<option value="2">&nbsp;' . $user->lang['datetime']['February'] . '&nbsp;</option>
								<option value="3">&nbsp;' . $user->lang['datetime']['March'] . '&nbsp;</option>
								<option value="4">&nbsp;' . $user->lang['datetime']['April'] . '&nbsp;</option>
								<option value="5">&nbsp;' . $user->lang['datetime']['May'] . '&nbsp;</option>
								<option value="6">&nbsp;' . $user->lang['datetime']['June'] . '&nbsp;</option>
								<option value="7">&nbsp;' . $user->lang['datetime']['July'] . '&nbsp;</option>
								<option value="8">&nbsp;' . $user->lang['datetime']['August'] . '&nbsp;</option>
								<option value="9">&nbsp;' . $user->lang['datetime']['September'] . '&nbsp;</option>
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
						$c_minute .= '<option value="' . $i . '">&nbsp;' . $j . $i . '&nbsp;</option>';
						$c_second .= '<option value="' . $i . '">&nbsp;' . $j . $i .'&nbsp;</option>';
					}

					$c_minute .= '</select>';
					$c_second .= '</select>';

					$c_day 		= str_replace("value=\"" . $b_day . "\">", "value=\"" . $b_day . "\" SELECTED>" ,$c_day);
					$c_month 	= str_replace("value=\"" . $b_month . "\">", "value=\"" . $b_month . "\" SELECTED>" ,$c_month);
					$c_year 	= '<input type="text" class="post" name="c_year" size="4" maxlength="4" value="' . $b_year . '" />';
					$c_hour 	= str_replace("value=\"" . $b_hour . "\">", "value=\"" . $b_hour . "\" SELECTED>" ,$c_hour);
					$c_minute 	= str_replace("value=\"" . $b_minute . "\">", "value=\"" . $b_minute . "\" SELECTED>" ,$c_minute);
					$c_second 	= str_replace("value=\"" . $b_second . "\">", "value=\"" . $b_second . "\" SELECTED>" ,$c_second);

					$racetime_combos = $c_day . '&nbsp;.&nbsp;' . $c_month . '&nbsp;.&nbsp;' . $c_year . '<br/><br/>&nbsp;' . $c_hour . '&nbsp;:&nbsp;' . $c_minute . '&nbsp;:&nbsp;' . $c_second;

					//
					// Generate imageboxes
					//

					$image_dir = $phpbb_root_path . 'ext/drdeath/f1webtip/images';
					$images = $this->load_files($image_dir);
					$raceimg_entries = '';

					// Generate imagebox for race
					foreach ($images as $image)
					{
						$selected = ($image == $raceimg) ? 'selected' : '';
						$raceimg_entries .= '<option value="' . $image . '" ' . $selected . '>' . $image . '</option>';
					}

					$image_race_combo	 = '<select name="raceimg">';
					$image_race_combo	.= '<option value="" ' . $selected . '>' . $language->lang('ACP_F1_SETTINGS_NO_RACE_IMG') . '</option>';
					$image_race_combo	.= $raceimg_entries;
					$image_race_combo	.= '</select>';


					// Generate page
					if ($config['drdeath_f1webtip_show_gfxr'] == 1)
					{
						$template->assign_block_vars('gfxr_on', array());
					}

					$template->assign_vars(array(
						'S_ADD_RACES'			=> true,
						'U_ACTION'				=> $this->u_action,
						'PREDEFINED_NAME' 		=> $racename,
						'PREDEFINED_IMG' 		=> $image_race_combo,
						'PREDEFINED_LENGTH' 	=> $racelength,
						'PREDEFINED_LAPS' 		=> $racelaps,
						'PREDEFINED_DISTANCE' 	=> $racedistance,
						'PREDEFINED_DEBUT' 		=> $racedebut,
						'PREDEFINED_ID' 		=> $race_id,
						'L_ACP_F1_RACES_EXPLAIN'=> $title_exp,
						'L_ACP_F1_RACES' 		=> $title,
						'RACETIME_COMBOS' 		=> $racetime_combos,
						)
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
						$race_img = $row['race_img'];
						$race_img = ($race_img == '') ? '<img src="' . $ext_path . 'images/' . $config['drdeath_f1webtip_no_race_img'] . '" width="94" height="54" alt="">' : '<img src="' . $ext_path . 'images/' . $race_img . '" width="94" height="54" alt="">';

						$template->assign_block_vars(($config['drdeath_f1webtip_show_gfxr'] == 1) ? 'racerows_gfxr' : 'racerows', array(
							'RACEIMG' 	=> $race_img,
							'RACENAME' 	=> $row['race_name'],
							'RACEID' 	=> $row['race_id'],
							'RACETIME' 	=> $user->format_date($row['race_time'], false, true),
							'RACEDEAD' 	=> $user->format_date($row['race_time'] - $config['drdeath_f1webtip_deadline_offset'], false, true)
						));
					}

					$db->sql_freeresult($result);

					// Generate page
					$colspan = 5;

					if ($config['drdeath_f1webtip_show_gfxr'] == 1)
					{
						$colspan = 6;
						$template->assign_block_vars('gfxr_on', array());
					}

					$template->assign_vars(array(
						'S_RACES'		=> true,
						'U_ACTION'		=> $this->u_action,
						'COLSPAN' 		=> $colspan,
						)
					);
				}

			break;
		}
	}
}
