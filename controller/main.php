<?php
/**
*
* @package phpBB Extension - DrDeath F1WebTip
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace drdeath\f1webtip\controller;

class main
{
	/* @var \phpbb\config\config */
	protected $config;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/**
	* Constructor
	*
	* @param \phpbb\config\config		$config
	* @param \phpbb\controller\helper	$helper
	* @param \phpbb\template\template	$template
	* @param \phpbb\user				$user
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
	}

	/**
	* get_formel_userdata
	*
	* Get username, user_colour from a user_id
	* Returns user_id, username, user_colour if user_id was found.
	*/
	protected function get_formel_userdata($user_id)
	{
		global $db;

		$sql = 'SELECT user_id, username, user_colour, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . (int) $user_id . '
				AND user_id <> ' . ANONYMOUS;
		$result = $db->sql_query($sql);

		return ($row = $db->sql_fetchrow($result)) ? $row : false;
	}


	/**
	* get_formel_races
	*
	* Get all formel races data
	* Returns all races in $races
	*/
	protected function get_formel_races()
	{
		global $db, $phpbb_container;

		$table_races	= $phpbb_container->getParameter('tables.f1webtip.races');
		$races 			= array();

		$sql = 'SELECT *
			FROM ' . $table_races . '
			ORDER BY race_time ASC';
		$result = $db->sql_query($sql);

		$races = $db->sql_fetchrowset($result);

		$db->sql_freeresult($result);

		return $races;
	}


	/**
	* get_formel_teams
	*
	* Get all formel teams data
	* Returns all teams in array $teams
	*/
	protected function get_formel_teams()
	{
		global $db, $phpbb_container;

		$table_teams	= $phpbb_container->getParameter('tables.f1webtip.teams');
		$teams 			= array();

		$sql = 'SELECT *
			FROM ' . $table_teams;
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$teams[$row['team_id']] = $row;
		}

		$db->sql_freeresult($result);

		return $teams;
	}

	/**
	* get_formel_drivers
	*
	* Get all formel drivers data
	* Returns all driver with assigned driver, car and team images in array $drivers
	*/
	protected function get_formel_drivers()
	{
		global $db;
		global $phpbb_container, $phpbb_extension_manager, $phpbb_path_helper;
		global $config, $phpEx, $phpbb_root_path;

		// Define the ext path. We will use it later for assigning the correct path to our local immages
		$ext_path = $phpbb_path_helper->update_web_root_path($phpbb_extension_manager->get_extension_path('drdeath/f1webtip', true));

		$teams 			= $this->get_formel_teams();

		$table_drivers	= $phpbb_container->getParameter('tables.f1webtip.drivers');
		$drivers 		= array();

		$sql = 'SELECT *
			FROM ' . $table_drivers . '
			ORDER BY driver_id ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			if ($row['driver_team'] <> 0)
			{
				$drivercar = ($teams[$row['driver_team']]['team_car'] <> '') ? '<img src="' . $ext_path . 'images/' . $teams[$row['driver_team']]['team_car'] . '" width="' . $config['drdeath_f1webtip_car_img_width'] . '" height="' . $config['drdeath_f1webtip_car_img_height'] . '" alt="" />' : '<img src="' . $ext_path . 'images/' . $config['drdeath_f1webtip_no_car_img'] . '" width="' . $config['drdeath_f1webtip_car_img_width'] . '" height="' . $config['drdeath_f1webtip_car_img_height'] . '" alt="" />';
			}
			else
			{
				$drivercar = '<img src="' . $ext_path . 'images/' . $config['drdeath_f1webtip_no_car_img'] . '" width="' . $config['drdeath_f1webtip_car_img_width'] . '" height="' . $config['drdeath_f1webtip_car_img_height'] . '" alt="" />';
			}

			$row['driver_img'] 			= ($row['driver_img'] == '') ? '<img src="' . $ext_path . 'images/' . $config['drdeath_f1webtip_no_driver_img'] . '" width="' . $config['drdeath_f1webtip_driver_img_width'] . '" height="' . $config['drdeath_f1webtip_driver_img_height'] . '" alt="" />' : '<img src="' . $ext_path . 'images/' . $row['driver_img'] . '" width="' . $config['drdeath_f1webtip_driver_img_width'] . '" height="' . $config['drdeath_f1webtip_driver_img_height'] . '" alt="" />';
			$row['driver_car'] 			= $drivercar;
			$row['driver_team_name'] 	= $teams[$row['driver_team']]['team_name'];
			$drivers[$row['driver_id']]	= $row;
		}

		$db->sql_freeresult($result);

		return $drivers;
	}

	/**
	* get_formel_drivers_data
	*
	* Get all active formel drivers data for combobox
	* Returns all active drivers in array $drivers
	*/
	protected function get_formel_drivers_data()
	{
		global $db, $phpbb_container, $user;

		$table_drivers	= $phpbb_container->getParameter('tables.f1webtip.drivers');
		$drivers 		= array();

		$sql = 'SELECT *
			FROM ' . $table_drivers . '
			WHERE driver_disabled <> 1
			ORDER BY driver_name ASC';
		$result = $db->sql_query($sql);

		$counter = 1;

		while ($row = $db->sql_fetchrow($result))
		{
			$drivers[$counter] = $row;
			++$counter;
		}

		$drivers['0']['driver_id']   = '0';
		$drivers['0']['driver_name'] = $user->lang['FORMEL_DEFINE'];

		$db->sql_freeresult($result);

		return $drivers;
	}

	/**
	* checkarrayforvalue
	*
	* Checks if a driver is already in the array. (0 is an undefined driver)
	* Returns true or false
	* If returns true, the tip is invalid.
	*/
	protected function checkarrayforvalue($value, $array)
	{
		$ret = false;

		if ($value <> 0)
		{
			for ($i = 0; $i < count($array); ++$i)
			{
				if ($value == $array[$i])
				{
					$ret = true;
				}
			}
		}

		return $ret;
	}

	/**
	* f1webtip controller for route /f1webtip/{name}
	*
	* @param string		$name
	* @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function handle($name)
	{

		global $db, $user, $auth, $template, $cache, $request;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		global $phpbb_container, $phpbb_extension_manager, $phpbb_path_helper, $phpbb_log;

		include($phpbb_root_path . 'includes/functions_user.' . $phpEx);

		// Define the ext path. We will use it later for assigning the correct path to our local immages
		$ext_path = $phpbb_path_helper->update_web_root_path($phpbb_extension_manager->get_extension_path('drdeath/f1webtip', true));
		// Determine board url - we may need it later
		$board_url = generate_board_url() . '/';

		// This path is sent with the base template paths in the assign_vars()
		// call below. We need to correct it in case we are accessing from a
		// controller because the web paths will be incorrect otherwise.

		$phpbb_path_helper = $phpbb_container->get('path_helper');
		$corrected_path = $phpbb_path_helper->get_web_root_path();

		$web_path = (defined('PHPBB_USE_BOARD_URL_PATH') && PHPBB_USE_BOARD_URL_PATH) ? $board_url : $corrected_path;

		// Short names for the tables
		$table_races 	= $phpbb_container->getParameter('tables.f1webtip.races');
		$table_teams	= $phpbb_container->getParameter('tables.f1webtip.teams');
		$table_drivers 	= $phpbb_container->getParameter('tables.f1webtip.drivers');
		$table_wm 		= $phpbb_container->getParameter('tables.f1webtip.wm');
		$table_tips 	= $phpbb_container->getParameter('tables.f1webtip.tips');

		// Get formel config
		$formel_guests_allowed	= ($config['drdeath_f1webtip_guest_viewing'] == '1') ? true : false;
		$formel_forum_id 		= $config['drdeath_f1webtip_forum_id'];
		$formel_group_id 		= $config['drdeath_f1webtip_restrict_to'];
		$formel_mod_id 			= $config['drdeath_f1webtip_mod_id'];


		//
		// Check all permission to access the f1webtip
		//

		//If user is a bot.... redirect to the index.
		if ($this->user->data['is_bot'])
		{
			redirect(append_sid("{$phpbb_root_path}index.$phpEx"));
		}

		// If guest viewing is not allowed...
		if (!$formel_guests_allowed)
		{
			// Check if the user ist logged in.
			if (!$this->user->data['is_registered'])
			{
				// Not logged in ? Redirect to the loginbox.
				login_box('', $user->lang['LOGIN_INFO']);
			}
		}
		// At this point we have no bots, only registered user and if guest viewing is allowed we have also guests here.

		// Check if user has one of the formular 1 admin permission.
		// If user has one or more of these permissions, he gets also formular 1 moderator permissions.
		$is_admin = $auth->acl_gets('a_formel_settings', 'a_formel_drivers', 'a_formel_teams', 'a_formel_races');

		//Is the user member of the restricted group?
		$is_in_group = group_memberships($formel_group_id, $this->user->data['user_id'], true);

		// Debug
		// echo "is in group -> " . $is_in_group . " is admin -> " . $is_admin . " user id -> " . $this->user->data['user_id'] . " Moderator ID -> " . $formel_mod_id;

		// Check for : restricted group access - admin access - formular 1 moderator access
		if ($formel_group_id <> 0 && !$is_in_group && $is_admin <> 1 && $this->user->data['user_id'] <> $formel_mod_id)
		{
			$auth_msg = sprintf($user->lang['FORMEL_ACCESS_DENIED'], '<a href="' . append_sid($phpbb_root_path . "ucp.$phpEx?i=groups") . '" class="gen">', '</a>', '<a href="' . append_sid($phpbb_root_path . "index.$phpEx") . '" class="gen">', '</a>');
			trigger_error($auth_msg);
		}

		//
		// Creating breadcrumps
		//
		$this->template->assign_block_vars('navlinks', array(
			'U_VIEW_FORUM' => $this->helper->route('f1webtip_controller', array('name' => 'index')),
			'FORUM_NAME' => $this->user->lang['F1WEBTIP_PAGE'],
		   ));

		// Salting the form...yumyum ...
		add_form_key('drdeath/f1webtip');

		//
		// Switch to the selected modes....
		//

		switch ($name)
		{


			###########################
			###       RULES        ####
			###########################
			case 'rules':

				$page_title = $user->lang['FORMEL_TITLE'];

				// Creating breadcrumps rules
				$this->template->assign_block_vars('navlinks', array(
					'U_VIEW_FORUM' => $this->helper->route('f1webtip_controller', array('name' => 'rules')),
					'FORUM_NAME' => $this->user->lang['FORMEL_RULES'],
				   ));


				// Build rules
				$points_mentioned 	= $config['drdeath_f1webtip_points_mentioned'];
				$points_placed 		= $config['drdeath_f1webtip_points_placed'];
				$points_fastest 	= $config['drdeath_f1webtip_points_fastest'];
				$points_tired 		= $config['drdeath_f1webtip_points_tired'];
				$points_safetycar	= $config['drdeath_f1webtip_points_safety_car'];

				$point 				= $user->lang['FORMEL_RULES_POINT'];
				$points 			= $user->lang['FORMEL_RULES_POINTS'];

				if ($points_mentioned == '1')
				{
					$points_mentioned .= ' ' . $point;
				}
				else
				{
					$points_mentioned .= ' ' . $points;
				}

				if ($points_placed == '1')
				{
					$points_placed .= ' ' . $point;
				}
				else
				{
					$points_placed .= ' ' . $points;
				}

				if ($points_fastest == '1')
				{
					$points_fastest .= ' ' . $point;
				}
				else
				{
					$points_fastest .= ' ' . $points;
				}

				if ($points_tired == '1')
				{
					$points_tired .= ' ' . $point;
				}
				else
				{
					$points_tired .= ' ' . $points;
				}

				if ($points_safetycar == '1')
				{
					$points_safetycar .= ' ' . $point;
				}
				else
				{
					$points_safetycar .= ' ' . $points;
				}

				$points_total = 10 * ($points_mentioned + $points_placed) + $points_fastest + $points_tired + $points_safetycar;

				if ($points_total == '1')
				{
					$points_total .= ' ' . $point;
				}
				else
				{
					$points_total .= ' ' . $points;
				}

				$rules_mentioned 	= sprintf($user->lang['FORMEL_RULES_MENTIONED'] 	, $points_mentioned);
				$rules_placed 		= sprintf($user->lang['FORMEL_RULES_PLACED']		, $points_placed);
				$rules_fastest 		= sprintf($user->lang['FORMEL_RULES_FASTEST'] 		, $points_fastest);
				$rules_tired 		= sprintf($user->lang['FORMEL_RULES_TIRED'] 		, $points_tired);
				$rules_safetycar	= sprintf($user->lang['FORMEL_RULES_SAFETYCAR'] 	, $points_safetycar);
				$rules_total 		= sprintf($user->lang['FORMEL_RULES_TOTAL'] 		, $points_total);

				// Show headerbanner ?
				if ($config['drdeath_f1webtip_show_headbanner'])
				{
					$template->assign_block_vars('head_on', array());
				}

				$this->template->assign_vars(array(
					'S_RULES'					=> true,
					'U_FORMEL_INDEX' 			=> $this->helper->route('f1webtip_controller', array('name' => 'index')),
					'FORMEL_RULES_MENTIONED' 	=> $rules_mentioned,
					'FORMEL_RULES_PLACED' 		=> $rules_placed,
					'FORMEL_RULES_FASTEST' 		=> $rules_fastest,
					'FORMEL_RULES_TIRED' 		=> $rules_tired,
					'FORMEL_RULES_SAFETYCAR' 	=> $rules_safetycar,
					'FORMEL_RULES_TOTAL' 		=> $rules_total,
					'HEADER_IMG' 				=> $ext_path . 'images/' . $config['drdeath_f1webtip_headbanner2_img'],
					'HEADER_HEIGHT' 			=> $config['drdeath_f1webtip_head_height'],
					'HEADER_WIDTH' 				=> $config['drdeath_f1webtip_head_width'],

					'EXT_PATH'					=> $ext_path,
					'EXT_PATH_IMAGES'			=> $ext_path . 'images/',
				));

			break;


			###########################
			###       STATS        ####
			###########################
			case 'stats':

				$page_title = $user->lang['FORMEL_TITLE'];

				// Creating breadcrumps rules
				$this->template->assign_block_vars('navlinks', array(
					'U_VIEW_FORUM' => $this->helper->route('f1webtip_controller', array('name' => 'stats')),
					'FORUM_NAME' => $this->user->lang['FORMEL_STATISTICS'],
				   ));

				// Check buttons & data
				$show_drivers 	= $request->is_set_post('show_drivers');
				$show_teams 	= $request->is_set_post('show_teams');

				$mode = $request->variable('mode', '');

				// Show teams toplist
				if ($show_teams || $mode == 'teams')
				{
					$stat_table_title = $user->lang['FORMEL_TEAM_STATS'];

					// Get all teams
					$teams = $this->get_formel_teams();

					// Get all wm points and fill Top10 teams
					$sql = 'SELECT sum(wm_points) AS total_points, wm_team
						FROM ' . $table_wm . '
						GROUP BY wm_team
						ORDER BY total_points DESC';
					$result = $db->sql_query($sql);

					//Stop! we have to recalc the team WM points... maybe we have some penalty !
					$recalc_teams = array();

					while ($row = $db->sql_fetchrow($result))
					{
						$recalc_teams[$row['wm_team']]['total_points'] 	= $row['total_points'] - $teams[$row['wm_team']]['team_penalty'];
						$recalc_teams[$row['wm_team']]['team_name']		= $teams[$row['wm_team']]['team_name'];
						$recalc_teams[$row['wm_team']]['team_img']		= $teams[$row['wm_team']]['team_img'];
						$recalc_teams[$row['wm_team']]['team_car']		= $teams[$row['wm_team']]['team_car'];
					}
					// re-sort the teams. Big points first ;-)
					arsort($recalc_teams);

					$rank = $real_rank  = 0;
					$previous_points = false;

					foreach ($recalc_teams as $team_id => $team)
					{
						++$real_rank;

						if ($team['total_points'] <> $previous_points)
						{
							$rank = $real_rank;
							$previous_points = $team['total_points'];
						}

						$wm_teamname	= $team['team_name'];
						$wm_teamimg 	= $team['team_img'];
						$wm_teamcar 	= $team['team_car'];
						$wm_points		= $team['total_points'];
						$wm_teamimg 	= ( $wm_teamimg == '' ) ? '<img src="' . $ext_path . 'images/' . $config['drdeath_f1webtip_no_team_img'] . '" alt="" width="' . $config['drdeath_f1webtip_team_img_width'] . '" height="' . $config['drdeath_f1webtip_team_img_height'] . '" />' : '<img src="' . $ext_path . 'images/' . $wm_teamimg . '" alt="" width="' . $config['drdeath_f1webtip_team_img_width'] . '" height="' . $config['drdeath_f1webtip_team_img_height'] . '" />';
						$wm_teamcar 	= ( $wm_teamcar == '' ) ? '<img src="' . $ext_path . 'images/' . $config['drdeath_f1webtip_no_car_img']  . '" alt="" width="' . $config['drdeath_f1webtip_car_img_width']  . '" height="' . $config['drdeath_f1webtip_car_img_height']  . '" />' : '<img src="' . $ext_path . 'images/' . $wm_teamcar . '" alt="" width="' . $config['drdeath_f1webtip_car_img_width']  . '" height="' . $config['drdeath_f1webtip_car_img_height']  . '" />';

						if ($config['drdeath_f1webtip_show_gfx'] == 1)
						{
							$template->assign_block_vars('top_teams_gfx', array(
								'RANK' 			=> $rank,
								'WM_TEAMNAME' 	=> $wm_teamname,
								'WM_TEAMIMG' 	=> $wm_teamimg,
								'WM_TEAMCAR' 	=> $wm_teamcar,
								'WM_POINTS' 	=> $wm_points,
								)
							);
						}
						else
						{
							$template->assign_block_vars('top_teams', array(
								'RANK' 			=> $rank,
								'WM_TEAMNAME' 	=> $wm_teamname,
								'WM_POINTS' 	=> $wm_points,
								)
							);
						}
					}

					// Do we have some team points yet?
					if ($real_rank == 0)
					{
						if ($config['drdeath_f1webtip_show_gfx'] == 1)
						{
							$template->assign_block_vars('top_teams_gfx', array(
								'RANK' 			=> '',
								'WM_TEAMNAME' 	=> $user->lang['FORMEL_NO_RESULTS'],
								'WM_TEAMIMG' 	=> '',
								'WM_TEAMCAR' 	=> '',
								'WM_POINTS' 	=> '',
								)
							);
						}
						else
						{
							$template->assign_block_vars('top_teams', array(
								'RANK' 				=> '',
								'WM_TEAMNAME'		=> $user->lang['FORMEL_NO_RESULTS'],
								'WM_POINTS' 		=> '',
								)
							);
						}
					}

					$db->sql_freeresult($result);

				}
				// Show drivers toplist
				else if ($show_drivers || $mode == 'drivers')
				{
					$stat_table_title = $user->lang['FORMEL_DRIVER_STATS'];

					// Get all data
					$drivers 	= $this->get_formel_drivers();
					$teams 		= $this->get_formel_teams();

					//Get all first place winner, count all first places,  grep all gold medals...  Marker for first place: 25 WM Points
					$sql = 'SELECT 	count(wm_driver) as gold_medals,
									wm_driver
							FROM 	' . $table_wm . '
							WHERE 	wm_points = 25
							GROUP BY wm_driver
							ORDER BY gold_medals DESC';
					$result = $db->sql_query($sql);

					// Now put the gold medals into the $drivers array
					while ($row = $db->sql_fetchrow($result))
					{
						$drivers[$row['wm_driver']]['gold_medals']	= $row['gold_medals'];
					}

					// Get all wm points and fill top10 drivers
					$sql = 'SELECT sum(wm_points) AS total_points, wm_driver, wm_team
						FROM ' . $table_wm . '
						GROUP BY wm_driver
						ORDER BY total_points DESC';
					$result = $db->sql_query($sql);

					//Stop! we have to recalc the driver WM points... maybe we have some penalty !
					$recalc_drivers = array();

					while ($row = $db->sql_fetchrow($result))
					{
						$recalc_drivers[$row['wm_driver']]['total_points'] 	= $row['total_points'] - $drivers[$row['wm_driver']]['driver_penalty'];
						$recalc_drivers[$row['wm_driver']]['gold_medals']	= (isset($drivers[$row['wm_driver']]['gold_medals'])) ? $drivers[$row['wm_driver']]['gold_medals'] : 0;
						$recalc_drivers[$row['wm_driver']]['driver_name']	= $drivers[$row['wm_driver']]['driver_name'];
						$recalc_drivers[$row['wm_driver']]['driver_img']	= $drivers[$row['wm_driver']]['driver_img'];
						$recalc_drivers[$row['wm_driver']]['driver_car']	= $drivers[$row['wm_driver']]['driver_car'];
						$recalc_drivers[$row['wm_driver']]['team_img']		= $teams[$row['wm_team']]['team_img'];
					}

					// re-sort the drivers. Big points first ;-)
					arsort($recalc_drivers);

					$rank = 0;
					$previous_points = false;

					foreach ($recalc_drivers as $driver_id => $driver)
					{
						++$rank;

						$wm_drivername 	= $driver['driver_name'];
						$wm_driverimg 	= $driver['driver_img'];
						$wm_drivercar 	= $driver['driver_car'];
						$wm_driverteam 	= $driver['team_img'];
						$wm_driverteam 	= ( $wm_driverteam == '' ) ? '<img src="' . $ext_path . 'images/' . $config['drdeath_f1webtip_no_team_img'] . '" alt="" width="' . $config['drdeath_f1webtip_team_img_width'] . '" height="' . $config['drdeath_f1webtip_team_img_height'] . '" />' : '<img src="' . $ext_path . 'images/' . $wm_driverteam . '" alt="" width="' . $config['drdeath_f1webtip_team_img_width'] . '" height="' . $config['drdeath_f1webtip_team_img_height'] . '" />';

						if ($config['drdeath_f1webtip_show_gfx'] == 1)
						{
							$template->assign_block_vars('top_drivers_gfx', array(
								'RANK' 				=> $rank,
								'WM_DRIVERNAME' 	=> $wm_drivername,
								'WM_DRIVERIMG' 		=> $wm_driverimg,
								'WM_DRIVERCAR' 		=> $wm_drivercar,
								'WM_DRIVERTEAM' 	=> $wm_driverteam,
								'WM_POINTS' 		=> $driver['total_points'],
								)
							);
						}
						else
						{
							$template->assign_block_vars('top_drivers', array(
								'RANK' 				=> $rank,
								'WM_DRIVERNAME' 	=> $wm_drivername,
								'WM_POINTS' 		=> $driver['total_points'],
								)
							);
						}
					}

					// Do we have some driver points yet?
					if ($rank == 0)
					{
						if ($config['drdeath_f1webtip_show_gfx'] == 1)
						{
							$template->assign_block_vars('top_drivers_gfx', array(
								'RANK' 				=> '',
								'WM_DRIVERNAME' 	=> $user->lang['FORMEL_NO_RESULTS'],
								'WM_DRIVERIMG' 		=> '',
								'WM_DRIVERCAR' 		=> '',
								'WM_DRIVERTEAM' 	=> '',
								'WM_POINTS' 		=> '',
								)
							);
						}
						else
						{
							$template->assign_block_vars('top_drivers', array(
								'RANK' 				=> '',
								'WM_DRIVERNAME'		=> $user->lang['FORMEL_NO_RESULTS'],
								'WM_POINTS' 		=> '',
								)
							);
						}
					}

					$db->sql_freeresult($result);

				}
				// Show users toplist
				else
				{
					$stat_table_title = $user->lang['FORMEL_USER_STATS'];

					// Get all tips and fill top10
					$sql = 'SELECT sum(tip_points) AS total_points, tip_user
						FROM ' . $table_tips . '
						GROUP BY tip_user
						ORDER BY total_points DESC';
					$result = $db->sql_query($sql);

					$rank = $real_rank  = 0;
					$previous_points = false;
					$alt = 'USER_AVATAR';

					while ($row = $db->sql_fetchrow($result))
					{
						++$real_rank;

						if ($row['total_points'] <> $previous_points)
						{
							$rank = $real_rank;
							$previous_points = $row['total_points'];
						}

						$tip_user_row			= $this->get_formel_userdata($row['tip_user']);
						$tip_username_link		= get_username_string('full', $tip_user_row['user_id'], $tip_user_row['username'], $tip_user_row['user_colour'] );
						$tip_user_avatar 		= '';
						$show_avatar_switch 	= false;

						if ($config['drdeath_f1webtip_show_avatar'] == 1)
						{
								$tip_user_avatar = phpbb_get_user_avatar($tip_user_row);
								// No User Avatar? Display the "no_avatar.gif" from the prosilver styles
								if ($tip_user_avatar == '')
								{
									$tip_user_avatar = '<img src="' . $corrected_path . 'styles/prosilver/theme/images/no_avatar.gif" alt="" />';
								}

								$show_avatar_switch 	= true;
						}

						$template->assign_block_vars('top_tippers', array(
							'S_AVATAR_SWITCH'		=> $show_avatar_switch,
							'TIPPER_AVATAR'			=> $tip_user_avatar,
							'TIPPER_AVATAR_WIDTH'	=> $config['avatar_max_width'] + 10,
							'TIPPER_AVATAR_HEIGHT'	=> $config['avatar_max_height'] + 10,
							'TIPPER_NAME'			=> $tip_username_link,
							'RANK'					=> ($rank == 1 || $rank == 2 || $rank == 3) ? "<b>" . $rank . "</b>" : $rank,
							'TIPPER_POINTS'			=> $row['total_points'],
							)
						);
					}

					// Do we have some user tips yet?
					if ($real_rank == 0)
					{
						$show_avatar_switch 		= false;

						if ($config['drdeath_f1webtip_show_avatar'] == 1)
						{
							$show_avatar_switch 	= true;
						}

						$template->assign_block_vars('top_tippers', array(
							'S_AVATAR_SWITCH'		=> $show_avatar_switch,
							'TIPPER_AVATAR'			=> '',
							'TIPPER_AVATAR_WIDTH'	=> '',
							'TIPPER_AVATAR_HEIGHT'	=> '',
							'TIPPER_NAME' 			=> $user->lang['FORMEL_NO_TIPPS'],
							'TIPPER_POINTS' 		=> '',
							)
						);
					}

					$db->sql_freeresult($result);
				}

				// Show headerbanner ?
				if ($config['drdeath_f1webtip_show_headbanner'])
				{
					$template->assign_block_vars('head_on', array());
				}

				$template->assign_vars(array(
					'S_STATS'				=> true,
					'S_FORM_ACTION' 		=> $this->helper->route('f1webtip_controller', array('name' => 'stats')),
					'U_FORMEL_STATS' 		=> $this->helper->route('f1webtip_controller', array('name' => 'stats')),
					'HEADER_IMG' 			=> $ext_path . 'images/' . $config['drdeath_f1webtip_headbanner3_img'],
					'HEADER_HEIGHT' 		=> $config['drdeath_f1webtip_head_height'],
					'HEADER_WIDTH' 			=> $config['drdeath_f1webtip_head_width'],
					'L_STAT_TABLE_TITLE' 	=> $stat_table_title,
					'U_FORMEL' 				=> $this->helper->route('f1webtip_controller', array('name' => 'index')),
					'U_BACK_TO_TIPP' 		=> $this->helper->route('f1webtip_controller', array('name' => 'index')),

					'EXT_PATH'							=> $ext_path,
					'EXT_PATH_IMAGES'					=> $ext_path . 'images/',
					)
				);

			break;


			###########################
			###      RESULTS       ####
			###########################
			case 'results':

				// Set template vars
				$page_title = $user->lang['FORMEL_TITLE'];


				$template->assign_block_vars('navlinks', array(
					'U_VIEW_FORUM' => $this->helper->route('f1webtip_controller', array('name' => 'results')),
					'FORUM_NAME'	=> $user->lang['FORMEL_RESULTS_TITLE'],
					)
				);

				// Check URL hijacker . Access only for formel moderators or admins
				if ($this->user->data['user_id'] <> $formel_mod_id && $is_admin <> 1)
				{
					$auth_msg = sprintf($user->lang['FORMEL_MOD_ACCESS_DENIED'], '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'index')) . '" class="gen">', '</a>', '<a href="' . append_sid($phpbb_root_path . "index.$phpEx") . '" class="gen">', '</a>');
					trigger_error($auth_msg);
				}

				// Init some language vars
				$l_edit 	= $user->lang['FORMEL_EDIT'];
				$l_del 		= $user->lang['FORMEL_DELETE'];
				$l_add 		= $user->lang['FORMEL_RESULTS_ADD'];

				// Fetch all races
				$sql = 'SELECT *
					FROM ' . $table_races . '
					ORDER BY race_time ASC';
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$race_img 			= $row['race_img'];
					$race_id 			= $row['race_id'];
					$race_img 			= ($race_img == '') 				? '<img src="' . $ext_path . 'images/' . $config['drdeath_f1webtip_no_race_img'] . '" width="94" height="54" alt="" />' : '<img src="' . $ext_path . 'images/' . $race_img . '" width="94" height="54" alt="" />';
					$quali_buttons 		= ($row['race_quali'] == '0') 		? '<input class="button1" type="submit" name="quali"  value="' . $l_add . '" />' : '<input class="button1" type="submit" name="editquali"  value="' . $l_edit . '" />&nbsp;&nbsp;<input class="button1" type="submit" name="resetquali"  value="' . $l_del . '" />';
					$result_buttons 	= ($row['race_result'] == '0') 		? '<input class="button1" type="submit" name="result" value="' . $l_add . '" />' : '<input class="button1" type="submit" name="editresult" value="' . $l_edit . '" />&nbsp;&nbsp;<input class="button1" type="submit" name="resetresult" value="' . $l_del . '" />';

					if ($config['drdeath_f1webtip_show_gfxr'] == 1)
					{
						$template->assign_block_vars('racerow_gfxr', array(
							'RACEIMG'			=> $race_img,
							'QUALI_BUTTONS'		=> $quali_buttons,
							'RESULT_BUTTONS'	=> $result_buttons,
							'RACEID'			=> $race_id,
							'RACENAME'			=> $row['race_name'],
							'RACETIME'			=> $user->format_date($row['race_time']),
							'RACEDEAD'			=> $user->format_date($row['race_time'] - $config['drdeath_f1webtip_deadline_offset']),
							)
						);
					}
					else
					{
						$template->assign_block_vars('racerow', array(
							'QUALI_BUTTONS'		=> $quali_buttons,
							'RESULT_BUTTONS'	=> $result_buttons,
							'RACEID'			=> $race_id,
							'RACENAME'			=> $row['race_name'],
							'RACETIME'			=> $user->format_date($row['race_time']),
							'RACEDEAD'			=> $user->format_date($row['race_time'] - $config['drdeath_f1webtip_deadline_offset']),
							)
						);
					}
				}

				$db->sql_freeresult($result);

				$template->assign_vars(array(
					'S_RESULTS'						=> true,
					'S_FORM_ACTION'					=> $this->helper->route('f1webtip_controller', array('name' => 'addresults')),
					'U_FORMEL'						=> $this->helper->route('f1webtip_controller', array('name' => 'index')),
					'U_FORMEL_RESULTS'				=> $this->helper->route('f1webtip_controller', array('name' => 'results')),

					'EXT_PATH'							=> $ext_path,
					'EXT_PATH_IMAGES'					=> $ext_path . 'images/',
					)
				);

			break;


			###########################
			###    	ADDRESULTS     ####
			###########################
			case 'addresults':

				// Set template vars
				$page_title = $user->lang['FORMEL_TITLE'];

				$template->assign_block_vars('navlinks', array(
					'U_VIEW_FORUM' => $this->helper->route('f1webtip_controller', array('name' => 'results')),
					'FORUM_NAME'	=> $user->lang['FORMEL_RESULTS_TITLE'],
					)
				);

				// Check URL hijacker . Access only for formel moderators or admins
				if ($this->user->data['user_id'] <> $formel_mod_id && $is_admin <> 1)
				{
					$auth_msg = sprintf($user->lang['FORMEL_MOD_ACCESS_DENIED'], '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'index')) . '" class="gen">', '</a>', '<a href="' . append_sid($phpbb_root_path . "index.$phpEx") . '" class="gen">', '</a>');
					trigger_error($auth_msg);
				}

				// Check buttons & data

				$addresult 		= $request->is_set_post('addresult');
				$addeditresult 	= $request->is_set_post('addeditresult');
				$editresult 	= $request->is_set_post('editresult');

				$addquali 		= $request->is_set_post('addquali');
				$editquali	 	= $request->is_set_post('editquali');
				$quali 			= $request->is_set_post('quali');

				$reset 			= $request->is_set_post('reset');
				$resetquali 	= $request->is_set_post('resetquali');
				$resetresult 	= $request->is_set_post('resetresult');

				$results		= $request->variable('result'			,	''	);
				$race_abort 	= $request->variable('race_abort'		,	0	);
				$race_id		= $request->variable('race_id'			,	0	);

				// Init some vars
				$quali_array	= array();
				$result_array	= array();

				// Reset a quali
				if ($resetquali && $race_id <> 0)
				{
					// Check the salt... yumyum
					if (!check_form_key('drdeath/f1webtip'))
					{
						trigger_error('FORM_INVALID');
					}

					$sql_ary = array(
						'race_quali'		=> 0,
					);

					$sql = 'UPDATE ' . $table_races . '
						SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE race_id = ' . (int) $race_id;
					$db->sql_query($sql);

					$phpbb_log->add('mod', $user->data['user_id'], $user->ip, 'LOG_FORMEL_QUALI_DELETED', false, array('forum_id' => 0, 'topic_id' => 0, $race_id));

					$tipp_msg = sprintf($user->lang['FORMEL_RESULTS_DELETED'], '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'results')) . '" class="gen">', '</a>', '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'index')) . '" class="gen">', '</a>');
					trigger_error($tipp_msg);
				}

				// Reset a result
				if ($resetresult && $race_id <> 0)
				{
					// Check the salt... yumyum
					if (!check_form_key('drdeath/f1webtip'))
					{
						trigger_error('FORM_INVALID');
					}

					// Delete all WM points for this race
					$sql = 'DELETE
						FROM ' . $table_wm . '
						WHERE wm_race = ' . (int) $race_id;
					$db->sql_query($sql);

					// Delete the race result for this race
					$sql_ary = array(
						'race_result'	=> 0,
					);

					$sql = 'UPDATE ' . $table_races . '
						SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE race_id = ' . (int) $race_id;
					$db->sql_query($sql);

					// Delete all gathered tip points for this race
					$sql_ary = array(
						'tip_points'	=> 0,
					);

					$sql = 'UPDATE ' . $table_tips . '
						SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE tip_race = ' . (int) $race_id;
					$db->sql_query($sql);

					// Pull out a success message
					$phpbb_log->add('mod', $user->data['user_id'], $user->ip, 'LOG_FORMEL_RESULT_DELETED', false, array('forum_id' => 0, 'topic_id' => 0, $race_id));

					$tipp_msg = sprintf($user->lang['FORMEL_RESULTS_DELETED'], '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'results')) . '" class="gen">', '</a>', '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'index')) . '" class="gen">', '</a>');
					trigger_error($tipp_msg);
				}

				if (($reset || $resetresult || $resetquali) && $race_id == 0)
				{
					$reset_msg = sprintf($user->lang['FORMEL_RESULTS_ERROR'], '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'results')) . '" class="gen">', '</a>', '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'index')) . '" class="gen">', '</a>');
					trigger_error($reset_msg);
				}

				// Add a quali
				if ($addquali)
				{
					// Check the salt... yumyum
					if (!check_form_key('drdeath/f1webtip'))
					{
						trigger_error('FORM_INVALID');
					}

					if ($race_id <> 0)
					{
						//We have 11 Teams with 2 cars each --> 22 drivers
						for ($i = 0; $i < 22; ++$i)
						{
							$value = $request->variable('place' . ( $i + 1 ), 0);

							if ($this->checkarrayforvalue($value, $quali_array))
							{
								$phpbb_log->add('mod', $user->data['user_id'], $user->ip, 'LOG_FORMEL_QUALI_NOT_VALID', false, array('forum_id' => 0, 'topic_id' => 0, $race_id));

								$quali_msg = sprintf($user->lang['FORMEL_RESULTS_DOUBLE'], '<a href="javascript:history.back()" class="gen">', '</a>', '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'index')) . '" class="gen">', '</a>');
								trigger_error($quali_msg);
							}

							$quali_array[$i] = $value;
						}

						$new_quali = implode(",", $quali_array);

						$sql_ary = array(
							'race_quali'	=> $new_quali,
						);

						$sql = 'UPDATE ' . $table_races . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE race_id = ' . (int) $race_id;
						$db->sql_query($sql);

						$phpbb_log->add('mod', $user->data['user_id'], $user->ip, 'LOG_FORMEL_QUALI_ADDED', false, array('forum_id' => 0, 'topic_id' => 0, $race_id));

						$quali_msg = sprintf($user->lang['FORMEL_RESULTS_ACCEPTED'], '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'results')) . '" class="gen">', '</a>', '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'index')) . '" class="gen">', '</a>');
						trigger_error($quali_msg);
					}
				}

				// Add a result
				if ($addresult || $addeditresult)
				{
					// Check the salt... yumyum
					if (!check_form_key('drdeath/f1webtip'))
					{
						trigger_error('FORM_INVALID');
					}

					if ($race_id <> 0)
					{
						if ($addeditresult)
						{
							$sql = 'DELETE
								FROM ' . $table_wm . '
								WHERE wm_race = ' . (int) $race_id;
							$db->sql_query($sql);
						}

						for ($i = 0; $i < 10; ++$i)
						{
							$value = $request->variable('place' . ( $i + 1 ), 0);

							if ($this->checkarrayforvalue($value, $result_array))
							{
								$phpbb_log->add('mod', $user->data['user_id'], $user->ip, 'LOG_FORMEL_RESULT_NOT_VALID', false, array('forum_id' => 0, 'topic_id' => 0, $race_id));

								$result_msg = sprintf($user->lang['FORMEL_RESULTS_DOUBLE'], '<a href="javascript:history.back()" class="gen">', '</a>', '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'index')) . '" class="gen">', '</a>');
								trigger_error($result_msg);
							}

							$result_array[$i] = $value;
						}

						$result_array['10'] = $request->variable('place11', 0);	//['10'] --> fastest driver
						$result_array['11'] = $request->variable('place12', 0);	//['11'] --> tired count
						$result_array['12'] = $request->variable('place13', 0);	//['12'] --> count safety car deployment

						$new_result = implode(",", $result_array);

						$sql_ary = array(
							'race_result'	=> $new_result,
						);

						$sql = 'UPDATE ' . $table_races . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE race_id = ' . (int) $race_id;
						$db->sql_query($sql);

						// START points calc
						// Get tipp data and calc user points
						$sql = 'SELECT *
							FROM ' . $table_tips . '
							WHERE tip_race = ' . (int) $race_id;
						$result = $db->sql_query($sql);

						while ($row = $db->sql_fetchrow($result))
						{
							$user_tipp_points = 0;
							$current_user = $row['tip_user'];
							$current_tipp_array = explode(',', $row['tip_result']);
							$temp_results_array = array();

							for ($i = 0; $i < count($current_tipp_array) - 3; ++$i)
							{
								$temp_results_array[$i] = $result_array[$i];
							}

							for ($i = 0; $i < count($current_tipp_array) - 3; ++$i)
							{
								if ($current_tipp_array[$i] <> '0')
								{
									if ($this->checkarrayforvalue($current_tipp_array[$i], $temp_results_array))
									{
										$user_tipp_points += $config['drdeath_f1webtip_points_mentioned'];

										if ($current_tipp_array[$i] == $result_array[$i])
										{
											$user_tipp_points += $config['drdeath_f1webtip_points_placed'];
										}
									}
								}
							}

							if ($current_tipp_array['10'] == $result_array['10'] && $current_tipp_array['10'] <> 0)
							{
								$user_tipp_points += $config['drdeath_f1webtip_points_fastest'];
							}

							if ($current_tipp_array['11'] == $result_array['11'])
							{
								$user_tipp_points += $config['drdeath_f1webtip_points_tired'];
							}

							if ($current_tipp_array['12'] == $result_array['12'] )
							{
								$user_tipp_points += $config['drdeath_f1webtip_points_safety_car'];
							}

							$sql_ary = array(
								'tip_points'	=> $user_tipp_points,
							);

							$sql = 'UPDATE ' . $table_tips . '
								SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
								WHERE tip_race = ' . (int) $race_id . '
								AND tip_user = ' . (int) $current_user;
							$update = $db->sql_query($sql);
						}

						$db->sql_freeresult($result);

						// Calc wm points
						// Get drivers data
						$sql = 'SELECT *
							FROM ' . $table_drivers;
						$result = $db->sql_query($sql);

						while ($row = $db->sql_fetchrow($result))
						{
							$teams[$row['driver_id']] = $row['driver_team'];
						}

						$db->sql_freeresult($result);

						if ($race_abort == false)
						{
							// wm points:  25-18-15-12-10-8-6-4-2-1
							$wm = array();
							$wm['0'] = 25;		// first place
							$wm['1'] = 18;		// second place
							$wm['2'] = 15;		// third place
							$wm['3'] = 12;		// forth place
							$wm['4'] = 10;		// fifth place
							$wm['5'] = 8;		// sixth place
							$wm['6'] = 6;		// seventh place
							$wm['7'] = 4;		// eighth place
							$wm['8'] = 2;		// ninth place
							$wm['9'] = 1;		// tenth place
						}
						else
						// the race was aborted, we use now half points
						{
							// wm points:  12.5-9-7.5-6-5-4-3-2-1-0.5
							$wm = array();
							$wm['0'] = 12.5;	// first place
							$wm['1'] = 9;		// second place
							$wm['2'] = 7.5;		// third place
							$wm['3'] = 6;		// forth place
							$wm['4'] = 5;		// fifth place
							$wm['5'] = 4;		// sixth place
							$wm['6'] = 3;		// seventh place
							$wm['7'] = 2;		// eighth place
							$wm['8'] = 1;		// ninth place
							$wm['9'] = 0.5;		// tenth place
						}

						for ($i = 0; $i < count($result_array) - 3; ++$i)
						{
							$current_driver = $result_array[$i];

							if ($current_driver <> '0')
							{
								$current_team 	= $teams[$current_driver];
								$wm_points 		= $wm[$i];
								$sql_ary = array(
									'wm_race'	=> (int) $race_id,
									'wm_driver'	=> (int) $current_driver,
									'wm_team'	=> (int) $current_team,
									'wm_points'	=> $wm_points,
								);

								$db->sql_query('INSERT INTO ' . $table_wm . ' ' . $db->sql_build_array('INSERT', $sql_ary));
							}
						}
						// END points calc

						$phpbb_log->add('mod', $user->data['user_id'], $user->ip, 'LOG_FORMEL_RESULT_ADDED', false, array('forum_id' => 0, 'topic_id' => 0, $race_id));

						$result_msg = sprintf($user->lang['FORMEL_RESULTS_ACCEPTED'], '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'results')) . '" class="gen">', '</a>', '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'index')) . '" class="gen">', '</a>');
						trigger_error($result_msg);
					}
				}

				// Load add/edit quali
				if (($quali || $editquali) && $race_id <> 0)
				{
					if ($editquali)
					{
						// Get the race
						$sql = 'SELECT *
							FROM ' . $table_races . '
								WHERE race_id = ' . (int) $race_id;
						$result = $db->sql_query($sql);

						$row = $db->sql_fetchrow($result);
						$quali_array = explode(',', $row['race_quali']);
						$db->sql_freeresult($result);
					}

					// Fetch all drivers
					$sql = 'SELECT *
						FROM ' . $table_drivers . '
						ORDER BY driver_name ASC';
					$result = $db->sql_query($sql);

					$counter = 1;

					while ($row = $db->sql_fetchrow($result))
					{
						$drivers[$counter] = $row;
						++$counter;
					}

					$db->sql_freeresult($result);

					$drivers['0']['driver_id'] = '0';
					$drivers['0']['driver_name'] = $user->lang['FORMEL_DEFINE'];

					//We have 11 Teams with 2 cars each --> 22 drivers
					for ($i = 0; $i < 22; ++$i)
					{
						$position = ($i == 0) ? $user->lang['FORMEL_POLE'] : $i + 1 . '. ' . $user->lang['FORMEL_PLACE'];
						$box_name = 'place' . ($i + 1);

						$drivercombo = '<select name="' . $box_name . '" size="1">';

						for ($k = 0; $k < count($drivers); ++$k)
						{
							$this_driver_id = $drivers[$k]['driver_id'];
							$this_driver_name = $drivers[$k]['driver_name'];

							if (isset($quali_array[$i]))
							{
								$selected = ($this_driver_id == $quali_array[$i]) ? 'selected="selected"' : '';
							}
							else
							{
								$selected = '';
							}

							$drivercombo .= '<option value="' . $this_driver_id . '" ' . $selected . '>' . $this_driver_name . '</option>';
						}

						$drivercombo .= '</select>';

						$template->assign_block_vars('qualirow', array(
							'L_PLACE'		=> $position,
							'DRIVERCOMBO'	=> $drivercombo,
							)
						);
					}

					$template->assign_block_vars('quali', array());
				}

				// Load add or edit result
				if (($results || $editresult) && $race_id <> 0)
				{
					if ($editresult)
					{
						// Get the race
						$sql = 'SELECT *
							FROM ' . $table_races . '
							WHERE race_id = ' . (int) $race_id;
						$result = $db->sql_query($sql);

						$row = $db->sql_fetchrow($result);
						$result_array = explode(',', $row['race_result']);
						$db->sql_freeresult($result);
					}

					// Fetch all drivers
					$sql = 'SELECT *
						FROM ' . $table_drivers . '
						ORDER BY driver_name ASC';
					$result = $db->sql_query($sql);

					$counter = 1;

					while ($row = $db->sql_fetchrow($result))
					{
						$drivers[$counter] = $row;
						++$counter;
					}

					$db->sql_freeresult($result);

					$drivers['0']['driver_id'] = '0';
					$drivers['0']['driver_name'] = $user->lang['FORMEL_DEFINE'];

					for ($i = 0; $i < 10; ++$i)
					{
						$position = ($i == 0) ? $user->lang['FORMEL_RACE_WINNER'] : $i + 1 . '. ' . $user->lang['FORMEL_PLACE'];
						$box_name = 'place' . ($i + 1);

						$drivercombo = '<select name="' . $box_name . '" size="1">';

						for ($k = 0; $k < count($drivers); ++$k)
						{
							$this_driver_id = $drivers[$k]['driver_id'];
							$this_driver_name = $drivers[$k]['driver_name'];

							if (isset($result_array[$i]))
							{
								$selected = ($this_driver_id == $result_array[$i]) ? 'selected="selected"' : '';
							}
							else
							{
								$selected = '';
							}

							$drivercombo .= '<option value="' . $this_driver_id . '" ' . $selected . '>' . $this_driver_name . '</option>';
						}

						$drivercombo .= '</select>';

						$template->assign_block_vars('resultrow', array(
							'L_PLACE' 		=> $position,
							'DRIVERCOMBO' 	=> $drivercombo,
							)
						);
					}

					$drivercombo_pace = '<select name="place11" size="1">';

					for ($k = 0; $k < count($drivers); ++$k)
					{
						$this_driver_id = $drivers[$k]['driver_id'];
						$this_driver_name = $drivers[$k]['driver_name'];

						if (isset($result_array['10']))
						{
							$selected = ( $this_driver_id == $result_array['10']) ? 'selected="selected"' : '';
						}
						else
						{
							$selected = '';
						}

						$drivercombo_pace .= '<option value="' . $this_driver_id . '" ' . $selected . '>' . $this_driver_name . '</option>';
					}

					$drivercombo_pace .= '</select>';

					$combo_tired = '<select name="place12" size="1">';

					//We have 11 Teams with 2 cars each --> 22 drivers
					for ($k = 0; $k < 23; ++$k)
					{
						if (isset($result_array['11']))
						{
							$selected = ( $k == $result_array['11']) ? 'selected="selected"' : '';
						}
						else
						{
							$selected = '';
						}

						$combo_tired .= '<option value="' . $k . '" ' . $selected . '>' . $k . '</option>';
					}

					$combo_tired .= '</select>';

					$combo_safetycar = '<select name="place13" size="1">';

					//We assume to have no more then 10 safety car placed in a normal race ;-)
					for ($k = 0; $k < 11; ++$k)
					{
						if (isset($result_array['12']))
						{
							$selected = ($k == $result_array['12']) ? 'selected="selected"' : '';
						}
						else
						{
							$selected = '';
						}

						$combo_safetycar .= '<option value="' . $k . '" ' . $selected . '>' . $k . '</option>';
					}

					$combo_safetycar .= '</select>';

					$modus = ($editresult) ? 'addeditresult' : 'addresult';

					$template->assign_block_vars('result', array(
						'PACECOMBO' 		=> $drivercombo_pace,
						'MODE' 				=> $modus,
						'TIREDCOMBO' 		=> $combo_tired,
						'SAFETYCARCOMBO'	=> $combo_safetycar,
						)
					);
				}

				$template->assign_vars(array(
					'S_ADDRESULTS'					=> true,
					'S_FORM_ACTION' 				=> $this->helper->route('f1webtip_controller', array('name' => 'addresults')),
					'U_FORMEL' 						=> $this->helper->route('f1webtip_controller', array('name' => 'index')),
					'U_FORMEL_RESULTS' 				=> $this->helper->route('f1webtip_controller', array('name' => 'results')),
					'RACE_ID' 						=> $race_id,

					'EXT_PATH'							=> $ext_path,
					'EXT_PATH_IMAGES'					=> $ext_path . 'images/',
					)
				);

			break;


			###########################
			###       USERTIP      ####
			###########################
			case 'usertipp':

				// Set template vars
				$page_title = $user->lang['FORMEL_TITLE'];

				// Check buttons & data
				$tipp_id = $request->variable('tipp',	0);
				$race_id = $request->variable('race',	0);

				// Get current race and time
				$race 			= $this->get_formel_races();
				$results		= explode(",", $race[$race_id]['race_result']);
				$current_time	= time();

				// Get current tip
				$sql = 'SELECT *
					FROM ' . $table_tips . '
					WHERE tip_id = ' . (int) $tipp_id;
				$result = $db->sql_query($sql);

				$tipp_active = $db->sql_affectedrows($result);

				// Do the work only if there is a tip
				if ($tipp_active)
				{
					$tippdata = $db->sql_fetchrowset($result);
					$tipp_userdata = $this->get_formel_userdata($tippdata['0']['tip_user']);
					$db->sql_freeresult($result);

					// Get all drivers
					$sql = 'SELECT *
						FROM ' . $table_drivers . '
						ORDER BY driver_id ASC';
					$result = $db->sql_query($sql);

					while ($row = $db->sql_fetchrow($result))
					{
						$driver_name[$row['driver_id']] = $row['driver_name'];
					}

					$db->sql_freeresult($result);

					// Get all tip points
					$sql = 'SELECT sum(tip_points) AS total_points
						FROM ' . $table_tips . '
						WHERE tip_user = ' . (int) $tipp_userdata['user_id'];
					$result = $db->sql_query($sql);

					while ($row = $db->sql_fetchrow($result))
					{
						$tipper_all_points = $row['total_points'];
					}

					$db->sql_freeresult($result);

					// Build output

					$tipp_array 		= array();
					$tipper_name 		= get_username_string('username', $tipp_userdata['user_id'], $tipp_userdata['username'], $tipp_userdata['user_colour']);
					$tipp_user_colour	= get_username_string('colour', $tipp_userdata['user_id'], $tipp_userdata['username'], $tipp_userdata['user_colour']);
					$tipper_style		= ($tipp_user_colour) ? ' style="color: ' . $tipp_user_colour . '; font-weight: bold;"' : '' ;
					$tipper_link 		= ($tipper_name <> $user->lang['GUEST']) ? '<a href="' . append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . (int) $tipp_userdata['user_id']) . '"' . $tipper_style . ' onclick="window.open(this.href); return false">' . $tipper_name . '</a>' : $tipper_name;
					$tipper_points 		= $tippdata['0']['tip_points'];
					$tipp_array 		= explode(',', $tippdata['0']['tip_result']);
					$is_hidden			= ($race[$race_id]['race_time'] - $config['drdeath_f1webtip_deadline_offset']  <= $current_time ) ? false : true ;

					for ($i = 0; $i < count($tipp_array) - 3; ++$i)
					{
						$position 		= ($i == 0) ? $user->lang['FORMEL_RACE_WINNER'] : $i + 1 . '. ' . $user->lang['FORMEL_PLACE'];
						$driver_placed 	= (isset($driver_name[$tipp_array[$i]])) ? $driver_name[$tipp_array[$i]] : '';
						$driverid 		= (isset($tipp_array[$i])) ? $tipp_array[$i] : '';

						//Recalc Tipp Points for Place 1 - 10
						$single_points = 0;

						if (isset($results[$i]))
						{
							if (($driverid == $results[$i]) && $driverid <> 0)
							{
								$single_points += $config['drdeath_f1webtip_points_placed'];
							}
						}

						for ($j = 0; $j < count($tipp_array) - 3; ++$j)
						{
							if (isset($results[$j]))
							{
								if (($driverid == $results[$j]) && $driverid <> 0)
								{
									$single_points += $config['drdeath_f1webtip_points_mentioned'];
								}
							}
						}

						if ($single_points == 0)
						{
							$single_points='';
						}

						$template->assign_block_vars('user_drivers', array(
							'DRIVER_PLACED' 	=> ($is_hidden == true && $tipp_userdata['user_id'] <> $this->user->data['user_id']) ? $user->lang['FORMEL_HIDDEN'] : $driver_placed,
							'POSITION' 			=> $position,
							'SINGLE_POINTS' 	=> $single_points,
							)
						);
					}

					$fastest_driver_name 	= (isset($driver_name[$tipp_array['10']])) ? $driver_name[$tipp_array['10']] : '';
					$tired 					= (isset($tipp_array['11'])) ? $tipp_array['11'] : '';
					$safetycar				= (isset($tipp_array['12'])) ? $tipp_array['12'] : '';

					//Recalc tip points for fastest driver and tired count
					$single_fastest	= $single_tired = $single_safety_car = '';

					if (isset($results['10']) && $results['10'] <> 0)
					{
						if ($tipp_array['10'] == $results['10'])
						{
							$single_fastest += $config['drdeath_f1webtip_points_fastest'];
						}
					}

					if (isset($results['11']))
					{
						if ($tipp_array['11'] == $results['11'])
						{
							$single_tired += $config['drdeath_f1webtip_points_tired'];
						}
					}

					if (isset($results['12']))
					{
						if ($tipp_array['12'] == $results['12'])
						{
							$single_safety_car += $config['drdeath_f1webtip_points_safety_car'];
						}
					}

					$template->assign_block_vars('user_tipp', array(
						'TIPPER' 			=> $tipper_link,
						'POINTS' 			=> $tipper_points,
						'ALL_POINTS' 		=> $tipper_all_points,
						'FASTEST_DRIVER' 	=> (isset($fastest_driver_name)) 	? ($is_hidden == true && $tipp_userdata['user_id'] <> $this->user->data['user_id']) ? $user->lang['FORMEL_HIDDEN'] : $fastest_driver_name : '',
						'TIRED' 			=> (isset($tired)) 					? ($is_hidden == true && $tipp_userdata['user_id'] <> $this->user->data['user_id']) ? $user->lang['FORMEL_HIDDEN'] : $tired : '',
						'SAFETYCAR' 		=> (isset($safetycar)) 				? ($is_hidden == true && $tipp_userdata['user_id'] <> $this->user->data['user_id']) ? $user->lang['FORMEL_HIDDEN'] : $safetycar : '',
						'SINGLE_FASTEST' 	=> (isset($single_fastest)) 		? $single_fastest : '',
						'SINGLE_TIRED' 		=> (isset($single_tired)) 			? $single_tired : '',
						'SINGLE_SAFETY_CAR' => (isset($single_safety_car)) 		? $single_safety_car : '',
						)
					);
				}
				else
				{
					$template->assign_block_vars('no_tipp', array());
				}

				// Output global values
				$template->assign_vars(array(
					'S_USERTIPP'		=> true,
					)
				);

			break;


			###########################
			###       INDEX        ####
			###########################
			case 'index':
			default:

				$page_title 	= $user->lang['FORMEL_TITLE'];

				// Check buttons & data
				$next 			= $request->is_set_post('next');
				$prev 			= $request->is_set_post('prev');
				$place_my_tipp 	= $request->is_set_post('place_my_tipp');
				$edit_my_tipp 	= $request->is_set_post('edit_my_tipp');
				$del_tipp 		= $request->is_set_post('del_tipp');

				$race_offset 	= $request->variable('race_offset'	, 0);
				$race_id 		= $request->variable('race_id'		, 0);
				$user_id 		= $this->user->data['user_id'];
				$tipp_time 		= $request->variable('tipp_time'	, 0);
				$my_tipp_array 	= array();
				$my_tipp 		= '';

				//Define some vars
				$driver_team_name = $driverteamname = $gfxdrivercar = $gfxdrivercombo = $single_fastest	= $single_tired	= $single_safety_car = '';

				$current_time = time();

				// Check if the user want to see prev/next race
				if ($next)
				{
					++$race_offset;
				}
				else if ($prev)
				{
					--$race_offset;
				}

				// Delete a tip
				if ($del_tipp)
				{
					// Check the salt... yumyum
					if (!check_form_key('drdeath/f1webtip'))
					{
						trigger_error('FORM_INVALID');
					}

					$sql = 'DELETE
						FROM ' . $table_tips . '
						WHERE tip_user = ' . (int) $user_id . '
							AND tip_race = ' . (int) $race_id;
					$db->sql_query($sql);

					$phpbb_log->add('user', $user->data['user_id'], $user->ip, 'LOG_FORMEL_TIP_DELETED', false, array('reportee_id' => 0, $race_id));

					$tipp_msg = sprintf($user->lang['FORMEL_TIPP_DELETED'], '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'index')) . '" class="gen">', '</a>', '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '" class="gen">', '</a>');
					trigger_error( $tipp_msg);
				}

				// Add or edit a tip
				if (($place_my_tipp || $edit_my_tipp) && $tipp_time - $config['drdeath_f1webtip_deadline_offset'] >= time())
				{
					// Check the salt... yumyum
					if (!check_form_key('drdeath/f1webtip'))
					{
						trigger_error('FORM_INVALID');
					}

					for ($i = 0; $i < 10; ++$i)
					{
						$value = $request->variable('place' . ( $i + 1 ), 0);

						if ($this->checkarrayforvalue($value, $my_tipp_array))
						{
							$phpbb_log->add('user', $user->data['user_id'], $user->ip, 'LOG_FORMEL_TIP_NOT_VALID', false, array('reportee_id' => 0, $race_id));

							$tipp_msg = sprintf($user->lang['FORMEL_DUBLICATE_VALUES'], '<a href="javascript:history.back()" class="gen">', '</a>', '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '" class="gen">', '</a>');
							trigger_error($tipp_msg);
						}

						$my_tipp_array[$i] = $value;
					}

					$my_tipp_array['10'] 	= $request->variable('place11', 0); //['10'] --> fastest driver
					$my_tipp_array['11'] 	= $request->variable('place12', 0); //['11'] --> tired count
					$my_tipp_array['12'] 	= $request->variable('place13', 0); //['12'] --> count of safety car deployments

					$my_tipp 				= implode(",", $my_tipp_array);

					if ($place_my_tipp)
					{
						$sql_ary = array(
							'tip_user'		=> (int) $user_id,
							'tip_race'		=> (int) $race_id,
							'tip_result'	=> $my_tipp,
							'tip_points'	=> 0,
						);

						$db->sql_query('INSERT INTO ' . $table_tips . ' ' . $db->sql_build_array('INSERT', $sql_ary));

						$phpbb_log->add('user', $user->data['user_id'], $user->ip, 'LOG_FORMEL_TIP_GIVEN', false, array('reportee_id' => 0, $race_id));
					}
					else
					{
						$sql_ary = array(
							'tip_result'	=> $my_tipp,
						);

						$sql = 'UPDATE ' . $table_tips . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE tip_user = ' . (int) $user_id . '
								AND tip_race = ' . (int) $race_id;
						$db->sql_query($sql);

						$phpbb_log->add('user', $user->data['user_id'], $user->ip, 'LOG_FORMEL_TIP_EDITED', false, array('reportee_id' => 0, $race_id));
					}

					$tipp_msg = sprintf($user->lang['FORMEL_ACCEPTED_TIPP'], '<a href="' . $this->helper->route('f1webtip_controller', array('name' => 'index')) . '" class="gen">', '</a>', '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '" class="gen">', '</a>');
					trigger_error( $tipp_msg);
				}

				// Get all races
				$races = $this->get_formel_races();

				// Get all teams
				$teams = $this->get_formel_teams();

				// Get all drivers
				$drivers = $this->get_formel_drivers();
				$driver_combodata = $this->get_formel_drivers_data();

				//
				// Get all tips and fill top10
				//
				$sql = 'SELECT sum(tip_points) AS total_points, tip_user
					FROM ' . $table_tips . '
					GROUP BY tip_user
					ORDER BY total_points DESC LIMIT 5';
				$result = $db->sql_query($sql);

				$rank = $real_rank  = 0;
				$previous_points = false;

				while ($row = $db->sql_fetchrow($result))
				{
					++$real_rank;

					if ($row['total_points'] <> $previous_points)
					{
						$rank = $real_rank;
						$previous_points = $row['total_points'];
					}

					$tipp_user_row		= $this->get_formel_userdata($row['tip_user']);
					$tipp_username_link	= get_username_string('full', $tipp_user_row['user_id'], $tipp_user_row['username'], $tipp_user_row['user_colour']);

					$template->assign_block_vars('top_tippers', array(
						'TIPPER_NAME' 	=> $tipp_username_link,
						'RANK'			=> $rank,
						'TIPPER_POINTS' => $row['total_points'],
						)
					);
				}

				// Do we have some user tips yet?
				if ($real_rank == 0)
				{
					$template->assign_block_vars('top_tippers', array(
						'TIPPER_NAME' 	=> $user->lang['FORMEL_NO_TIPPS'],
						'RANK'			=> '',
						'TIPPER_POINTS' => '0',
						)
					);
				}

				$db->sql_freeresult($result);

				//
				//Get all first place winner, count all first places,  grep all gold medals...  Marker for first place: 25 WM Points
				//
				$sql = 'SELECT 	count(wm_driver) as gold_medals,
								wm_driver
						FROM 	' . $table_wm . '
						WHERE 	wm_points = 25
						GROUP BY wm_driver
						ORDER BY gold_medals DESC';
				$result = $db->sql_query($sql);

				// Now put the gold medals into the $drivers array
				while ($row = $db->sql_fetchrow($result))
				{
					$drivers[$row['wm_driver']]['gold_medals']	= $row['gold_medals'];
				}

				// Get all wm points and fill top10 drivers
				$sql = 'SELECT sum(wm_points) AS total_points, wm_driver
					FROM ' . $table_wm . '
					GROUP BY wm_driver
					ORDER BY total_points DESC';
				$result = $db->sql_query($sql);

				//Stop! we have to recalc the driver WM points... maybe we have some penalty !
				$recalc_drivers = array();

				while ($row = $db->sql_fetchrow($result))
				{
					$recalc_drivers[$row['wm_driver']]['total_points'] 	= $row['total_points'] - $drivers[$row['wm_driver']]['driver_penalty'];
					$recalc_drivers[$row['wm_driver']]['gold_medals']	= (isset($drivers[$row['wm_driver']]['gold_medals'])) ? $drivers[$row['wm_driver']]['gold_medals'] : 0;
					$recalc_drivers[$row['wm_driver']]['driver_name']	= $drivers[$row['wm_driver']]['driver_name'];
				}

				// re-sort the drivers. Big points first ;-)
				arsort($recalc_drivers);

				$rank = $limit = 0;
				$previous_points = false;

				foreach ($recalc_drivers as $driver_id => $driver)
				{
					if ($limit == 5)
					{
						break;
					}

					++$rank;

					$wm_drivername = $driver['driver_name'];

					$template->assign_block_vars('top_drivers', array(
						'RANK'			=> $rank,
						'WM_DRIVERNAME'	=> $wm_drivername,
						'WM_POINTS'		=> $driver['total_points'],
						)
					);

					++$limit;
				}

				// Do we have some driver points yet?
				if ($rank == 0)
				{
					$template->assign_block_vars('top_drivers', array(
						'RANK' 				=> '',
						'WM_DRIVERNAME'		=> $user->lang['FORMEL_NO_RESULTS'],
						'WM_POINTS' 		=> '0',
						)
					);
				}

				$db->sql_freeresult($result);

				//
				// Get all wm points and fill top10 teams
				//
				$sql = 'SELECT sum(wm_points) AS total_points, wm_team
					FROM ' . $table_wm . '
					GROUP BY wm_team
					ORDER BY total_points DESC';
				$result = $db->sql_query($sql);

				//Stop! we have to recalc the team WM points... maybe we have some penalty !
				$recalc_teams = array();

				while ($row = $db->sql_fetchrow($result))
				{
					$recalc_teams[$row['wm_team']]['total_points'] 	= $row['total_points'] - $teams[$row['wm_team']]['team_penalty'];
					$recalc_teams[$row['wm_team']]['team_name']		= $teams[$row['wm_team']]['team_name'];
					$recalc_teams[$row['wm_team']]['team_img']		= $teams[$row['wm_team']]['team_img'];
					$recalc_teams[$row['wm_team']]['team_car']		= $teams[$row['wm_team']]['team_car'];
				}

				// re-sort the teams. Big points first ;-)
				arsort($recalc_teams);

				$rank = $real_rank = $limit = 0;
				$previous_points = false;

				foreach ($recalc_teams as $team_id => $team)
				{
					if ($limit == 5)
					{
						break;
					}

					++$real_rank;

					if ($team['total_points'] <> $previous_points)
					{
						$rank = $real_rank;
						$previous_points = $team['total_points'];
					}

					$wm_teamname = $team['team_name'];
					$template->assign_block_vars('top_teams', array(
						'RANK'			=> $rank,
						'WM_TEAMNAME'	=> $wm_teamname,
						'WM_POINTS'		=> $team['total_points'],
						)
					);

					++$limit;
				}

				// Do we have some team points yet?
				if ($real_rank == 0)
				{
					$template->assign_block_vars('top_teams', array(
						'RANK' 				=> '',
						'WM_TEAMNAME'		=> $user->lang['FORMEL_NO_RESULTS'],
						'WM_POINTS' 		=> '0',
						)
					);
				}

				$db->sql_freeresult($result);

				//
				// Find current race
				//
				for ($i = 0; $i < count($races); ++$i)
				{
					if ($races[$i]['race_time'] > $current_time - $config['drdeath_f1webtip_event_change'])
					{
						// Check for a overflow
						$race_offset = ($i + $race_offset == count($races)) ? 0 - $i  : $race_offset;
						$race_offset = ($i + $race_offset < 0) ? count($races) - 1 - $i : $race_offset;

						// Define current race incl. user given offset
						$chosen_race = $i + $race_offset;

						$user_tipp_points = 0;
						$race_id = (int) $races[$chosen_race]['race_id'];
						$user_id = $this->user->data['user_id'];

						//Countdown data
						if ($config['drdeath_f1webtip_show_countdown'] == 1)
						{
							$event_stop	= date($races[$chosen_race]['race_time'] - $config['drdeath_f1webtip_deadline_offset']);
							$b_day		= $user->format_date($event_stop, 'd');
							$b_month	= $user->format_date($event_stop, 'n');
							$b_year		= $user->format_date($event_stop, 'Y');
							$b_hour		= $user->format_date($event_stop, 'H');
							$b_minute	= $user->format_date($event_stop, 'i');
							$b_second	= $user->format_date($event_stop, 's');

							switch ($b_month)
							{
								case 1:
										$b_month = 'January';
								break;

								case 2:
										$b_month = 'February';
								break;

								case 3:
										$b_month = 'March';
								break;

								case 4:
										$b_month = 'April';
								break;

								case 5:
										$b_month = 'May';
								break;

								case 6:
										$b_month = 'June';
								break;

								case 7:
										$b_month = 'July';
								break;

								case 8:
										$b_month = 'August';
								break;

								case 9:
										$b_month = 'September';
								break;

								case 10:
										$b_month = 'October';
								break;

								case 11:
										$b_month = 'November';
								break;

								case 12:
										$b_month = 'December';
								break;
							}

							$stop = $b_month . ' ' . $b_day . ', ' . $b_year . ' ' . $b_hour . ':' . $b_minute . ':' . $b_second;

							$countdown = "<script type=\"text/javascript\">
										// <![CDATA[
										var eventdate = new Date('" . $stop . "');
										function toSt(n)
										{
																	s=''
																	if(n<10) s+='0'
																	return s+n.toString();
										}
										function countdown()
										{
											d=new Date();
											count=Math.floor((eventdate.getTime()-d.getTime())/1000);
											if(count<=0)
											{
												var time_event = document.getElementById('time_event');
												var event_time = document.getElementById('event_time');
												time_event.style.display = 'none';
												event_time.style.display = '';
												return;
											}
											secs_count = toSt(count%60);
											count=Math.floor(count/60);
											mins_count = toSt(count%60);
											count=Math.floor(count/60);
											hours_count = toSt(count%24);
											count=Math.floor(count/24);
											days_count = count;
											document.getElementById('countdown').days.value = days_count;
											document.getElementById('countdown').hours.value = hours_count;
											document.getElementById('countdown').mins.value = mins_count;
											document.getElementById('countdown').secs.value = secs_count;
											window.setTimeout('countdown()',500);
										}
										// ]]>
										</script>";
						}

						// Get race image and data
						$race_img = $races[$chosen_race]['race_img'];
						$race_img = ($race_img == '') ? '<img src="' . $ext_path . 'images/' . $config['drdeath_f1webtip_no_race_img'] . '" width="' . $config['drdeath_f1webtip_race_img_width'] . '" height="' . $config['drdeath_f1webtip_race_img_height'] . '" alt="" />' : '<img src="' . $ext_path . 'images/' . $race_img . '" width="' . $config['drdeath_f1webtip_race_img_width'] . '" height="' . $config['drdeath_f1webtip_race_img_height'] . '" alt="" />';

						$template->assign_block_vars('racerow', array(
							'RACEIMG' 		=> $race_img,
							'RACENAME' 		=> $races[$chosen_race]['race_name'],
							'RACELENGTH' 	=> $races[$chosen_race]['race_length'] . ' km',
							'RACEDEBUT' 	=> $races[$chosen_race]['race_debut'],
							'RACEDISTANCE' 	=> $races[$chosen_race]['race_distance'] . ' km',
							'RACELAPS' 		=> $races[$chosen_race]['race_laps'],
							'RACETIME' 		=> $user->format_date($races[$chosen_race]['race_time']),
							'RACEDEAD' 		=> $user->format_date($races[$chosen_race]['race_time'] - $config['drdeath_f1webtip_deadline_offset']),
							)
						);

						if ($config['drdeath_f1webtip_show_gfxr'] == 1)
						{
							$template->assign_block_vars('racerow.racegfx', array());
						}

						// Find current tippers and their points
						// Get tip data
						$sql = 'SELECT *
							FROM ' . $table_tips . '
							WHERE tip_race = ' . (int) $race_id . '
								ORDER BY tip_points DESC';
						$result = $db->sql_query($sql);

						$tippers_active = $db->sql_affectedrows($result);
						$cur_counter = 1;

						while ($row = $db->sql_fetchrow($result))
						{
							$current_tippers_userdata 	= $this->get_formel_userdata($row['tip_user']);
							$current_tipp_id 			= $row['tip_id'];
							$current_tippers_username 	= get_username_string('username', $row['tip_user'], $current_tippers_userdata['username'], $current_tippers_userdata['user_colour'] );
							$current_tippers_colour		= get_username_string('colour'  , $row['tip_user'], $current_tippers_userdata['username'], $current_tippers_userdata['user_colour'] );
							$separator 					= ($cur_counter == $tippers_active) ? '': ', ';

							$template->assign_block_vars('tipps_made', array(
								'USERTIPP' 		=> $this->helper->route('f1webtip_controller', array('name' => 'usertipp', 'mode' => 'teams', 'tipp' => $current_tipp_id, 'race' => $chosen_race)),
								'SEPARATOR' 	=> $separator,
								'USERNAME' 		=> $current_tippers_username . ' (' . $row['tip_points'] . ')',
								'STYLE'			=> ($current_tippers_colour) ? ' style="color: ' . $current_tippers_colour . '; font-weight: bold;"' : '',
								)
							);

							++$cur_counter;
						}

						if ($tippers_active == 0)
						{
							$template->assign_block_vars('no_tipps_made', array());
						}

						$db->sql_freeresult($result);

						// Get tip data
						$sql = 'SELECT *
							FROM ' . $table_tips . '
							WHERE tip_race = ' . (int) $race_id . '
								AND tip_user = ' . (int) $user_id;
						$result = $db->sql_query($sql);

						$tipp_active 		= $db->sql_affectedrows($result);
						$delete_button 		= '';
						$tipp_button 		= $user->lang['FORMEL_ADD_TIPP'];
						$tipp_button_name 	= 'place_my_tipp';
						$tipp_data 			= $db->sql_fetchrowset($result);

						$db->sql_freeresult($result);

						// Check if a tip has been made before
						if ($tipp_active > 0)
						{
							$tipp_button		= $user->lang['FORMEL_EDIT_TIPP'];
							$tipp_button_name	= 'edit_my_tipp';
							$delete_button		= '&nbsp;<input class="button1" type="submit" name="del_tipp" value="' . $user->lang['FORMEL_DEL_TIPP'] . '" />';
							$tipp_array			= explode(",", $tipp_data['0']['tip_result']);
							$user_tipp_points	= $tipp_data['0']['tip_points'];

							for ($i = 0; $i < count($tipp_array) - 3; ++$i)
							{
								$results		= explode(",", $races[$chosen_race]['race_result']);
								$position		= ($i == 0) ? $user->lang['FORMEL_RACE_WINNER'] : $i + 1 . '. ' . $user->lang['FORMEL_PLACE'];
								$box_name		= 'place' . ($i + 1);
								$single_points	= '';

								if ($races[$chosen_race]['race_time'] - $config['drdeath_f1webtip_deadline_offset'] < $current_time)
								{
									//Actual race is over
									$driverid 			= (isset($drivers[$tipp_array[$i]]['driver_id']))			?	$drivers[$tipp_array[$i]]['driver_id']			:	'';
									$drivercombo 		= (isset($drivers[$tipp_array[$i]]['driver_name']))			?	$drivers[$tipp_array[$i]]['driver_name']		:	'';
									$driverteamname 	= (isset($drivers[$tipp_array[$i]]['driver_team_name']))	?	$drivers[$tipp_array[$i]]['driver_team_name']	:	'';
									$gfxdrivercar 		= (isset($drivers[$tipp_array[$i]]['driver_car']))			?	$drivers[$tipp_array[$i]]['driver_car']			:	'';
									$gfxdrivercombo 	= (isset($drivers[$tipp_array[$i]]['driver_img']))			?	$drivers[$tipp_array[$i]]['driver_img']			:	'';

									//Recalc tip points for every single placed tip
									if (isset($results[$i]))
									{
										if ($driverid == $results[$i])
										{
											$single_points += $config['drdeath_f1webtip_points_placed'];
										}
									}

									for ($j = 0; $j < count($tipp_array) - 3; ++$j)
									{
										if (isset($results[$j]))
										{
											if ($driverid == $results[$j])
											{
												$single_points += $config['drdeath_f1webtip_points_mentioned'];
											}
										}
									}

									if ($single_points == 0)
									{
										$single_points='';
									}
									// End recalc
								}
								else
								{
									//Actual race is not over
									$drivercombo = '<select name="' . $box_name . '" size="1">';

									for ($k = 0; $k < count($driver_combodata); ++$k)
									{
										$this_driver_id 	 = $driver_combodata[$k]['driver_id'];
										$this_driver_name 	 = $driver_combodata[$k]['driver_name'];
										$selected 			 = ($this_driver_id == $tipp_array[$i]) ? 'selected' : '';
										$drivercombo 		.= '<option value="' . $this_driver_id . '" ' . $selected . '>' . $this_driver_name . '</option>';
									}

									$drivercombo .= '</select>';
								}

								if ($config['drdeath_f1webtip_show_gfx'] == 1)
								{
									//Layout cosmetic
									if ($races[$chosen_race]['race_time'] - $config['drdeath_f1webtip_deadline_offset'] < $current_time)
									{
										//Race is over - Show driverimage and so on
										$template->assign_block_vars('gfx_users_tipp', array(
											'L_PLACE'			=>	'&nbsp;' . $position . '<br />',
											'DRIVERCOMBO'		=>	$drivercombo . '<br />',
											'DRIVERTEAMNAME'	=>	'&nbsp;' . $driverteamname,
											'GFXDRIVERCOMBO'	=>	$gfxdrivercombo,
											'GXFDRIVERCAR'		=>	$gfxdrivercar,
											'SINGLE_POINTS'		=>	$single_points,
											)
										);
									}
									else
									{
										// Race is not over - Show position instead of driverimage
										$template->assign_block_vars('gfx_users_tipp', array(
											'L_PLACE'			=>	'',
											'DRIVERCOMBO'		=>	$drivercombo,
											'DRIVERTEAMNAME'	=>	$driverteamname,
											'GFXDRIVERCOMBO'	=>	$position,
											'GXFDRIVERCAR'		=>	$gfxdrivercar,
											'SINGLE_POINTS'		=>	$single_points,
											)
										);
									}
								}
								else
								{
									$template->assign_block_vars('users_tipp', array(
										'L_PLACE'		=>	$position,
										'DRIVERCOMBO'	=>	$drivercombo,
										'SINGLE_POINTS'	=>	$single_points,
										)
									);
								}
							}

							if ($races[$chosen_race]['race_time'] - $config['drdeath_f1webtip_deadline_offset'] < $current_time)
							{
								//Actual Race is over
								$single_fastest		= '';
								$single_tired		= '';
								$single_safety_car 	= '';

								$drivercombo	= (isset($drivers[$tipp_array['10']]['driver_name'])) ? $drivers[$tipp_array['10']]['driver_name'] : '';
								$tiredcombo		= (isset($tipp_array['11'])) ? $tipp_array['11'] : '';
								$safetycarcombo	= (isset($tipp_array['12'])) ? $tipp_array['12'] : '';

								//Recalc tip points for fastest driver
								if (isset($results['10']) && $results['10'] <> 0)
								{
									if ($tipp_array['10'] == $results['10'])
									{
										$single_fastest += $config['drdeath_f1webtip_points_fastest'];
									}
								}

								//Recalc tip points for tired count
								if (isset($results['11']))
								{
									if ($tipp_array['11'] == $results['11'])
									{
										$single_tired += $config['drdeath_f1webtip_points_tired'];
									}
								}

								//Recalc tip points for correct count of safety car deployments
								if (isset($results['12']))
								{
									if ($tipp_array['12'] == $results['12'])
									{
										$single_safety_car += $config['drdeath_f1webtip_points_safety_car'];
									}
								}
							}
							else
							{
								//Actual Race is not over

								//Fastest Driver DropDown
								$drivercombo = '<select name="place11" size="1">';

								for ($k = 0; $k < count($driver_combodata); ++$k)
								{
									$this_driver_id		 = $driver_combodata[$k]['driver_id'];
									$this_driver_name	 = $driver_combodata[$k]['driver_name'];
									$selected			 = ($this_driver_id == $tipp_array['10']) ? 'selected' : '';
									$drivercombo		.= '<option value="' . $this_driver_id . '" ' . $selected .'>' . $this_driver_name . '</option>';
								}

								$drivercombo .= '</select>';

								//Count Tired DropDown
								$tiredcombo = '<select name="place12" size="1">';

								//We have 11 Teams with 2 cars each --> 22 drivers
								for ($k = 0; $k < 23; ++$k)
								{
									$selected 			 = ($k == $tipp_array['11']) ? 'selected' : '';
									$tiredcombo 		.= '<option value="' . $k . '" ' . $selected . '>' . $k . '</option>';
								}

								$tiredcombo .= '</select>';

								//Count Safety Car Deployments DropDown
								$safetycarcombo = '<select name="place13" size="1">';

								//We assume to have no more then 10 safety car placed in a normal race ;-)
								for ($k = 0; $k < 11; ++$k)
								{
									$selected 			 = ( $k == $tipp_array['12']) ? 'selected' : '';
									$safetycarcombo 	.= '<option value="' . $k . '" ' . $selected . '>' . $k . '</option>';
								}

								$safetycarcombo .= '</select>';
							}


							if ($config['drdeath_f1webtip_show_gfx'] == 1)
							{
								$template->assign_block_vars('extended_users_tipp_gfx', array(
									'TIREDCOMBO'		=> $tiredcombo,
									'DRIVERCOMBO'		=> $drivercombo,
									'SAFETYCARCOMBO'	=> $safetycarcombo,
									'GFXDRIVERCOMBO'	=> $gfxdrivercombo,
									'SINGLE_FASTEST'	=> $single_fastest,
									'SINGLE_TIRED'		=> $single_tired,
									'SINGLE_SAFETY_CAR'	=> $single_safety_car,
									)
								);
							}
							else
							{
								$template->assign_block_vars('extended_users_tipp', array(
									'TIREDCOMBO'		=> $tiredcombo,
									'DRIVERCOMBO'		=> $drivercombo,
									'SAFETYCARCOMBO'	=> $safetycarcombo,
									'GFXDRIVERCOMBO'	=> $gfxdrivercombo,
									'SINGLE_FASTEST'	=> $single_fastest,
									'SINGLE_TIRED'		=> $single_tired,
									'SINGLE_SAFETY_CAR'	=> $single_safety_car,
									)
								);
							}
						}

						// What to do if the user has no tip so far
						else
						{
							//Guests are not allowed to place a tip.
							if ($this->user->data['is_registered'])
							{
								if ($races[$chosen_race]['race_time'] - $config['drdeath_f1webtip_deadline_offset'] > $current_time)
								{
									//Actual Race is not over
									for ($i = 0; $i < 10; ++$i)
									{
										$position = ($i == 0) ? $user->lang['FORMEL_RACE_WINNER'] : $i + 1 . '. ' . $user->lang['FORMEL_PLACE'];
										$box_name = 'place' . ($i + 1);

										$drivercombo = '<select name="' . $box_name . '" size="1">';

										for ($k = 0; $k < count($driver_combodata); ++$k)
										{
											$this_driver_id		 = $driver_combodata[$k]['driver_id'];
											$this_driver_name	 = $driver_combodata[$k]['driver_name'];
											$drivercombo		.= '<option value="' . $this_driver_id . '">' . $this_driver_name . '</option>';
										}

										$drivercombo .= '</select>';

										$template->assign_block_vars('add_tipp', array(
											'L_PLACE'		=> $position,
											'DRIVERCOMBO'	=> $drivercombo,
											)
										);
									}

									//Fastest Driver DropDown
									$drivercombo = '<select name="place11" size="1">';

									for ($k = 0; $k < count($driver_combodata); ++$k)
									{
										$this_driver_id		 = $driver_combodata[$k]['driver_id'];
										$this_driver_name	 = $driver_combodata[$k]['driver_name'];
										$drivercombo 		.= '<option value="' . $this_driver_id . '">' . $this_driver_name . '</option>';
									}

									$drivercombo .= '</select>';

									//Count Tired DropDown
									$tiredcombo = '<select name="place12" size="1">';

									//We have 11 Teams with 2 cars each --> 22 drivers
									for ($k = 0; $k < 23; ++$k)
									{
										$tiredcombo .= '<option value="' . $k . '">' . $k . '</option>';
									}

									$tiredcombo .= '</select>';

									//Count Safety Car Deployments DropDown
									$safetycarcombo = '<select name="place13" size="1">';

									//We assume to have no more then 10 safety car placed in a normal race ;-)
									for ($k = 0; $k < 11; ++$k)
									{
										$safetycarcombo .= '<option value="' . $k . '">' . $k . '</option>';
									}

									$safetycarcombo .= '</select>';

									$template->assign_block_vars('extended_add_tipp', array(
										'TIREDCOMBO'		=> $tiredcombo,
										'DRIVERCOMBO'		=> $drivercombo,
										'SAFETYCARCOMBO'	=> $safetycarcombo,
										)
									);
								}
							}
							else
							{
								$template->assign_block_vars('add_tipp', array(
									'DRIVERCOMBO'	=> '<br /> ' . $user->lang['FORMEL_GUESTS_PLACE_NO_TIP'],
									)
								);
							}
						}

						// Checks for a saved quali
						if ($races[$chosen_race]['race_quali'] <> '0')
						{
							// Get the driver ids
							$quali = explode(",", $races[$chosen_race]['race_quali']);

							// Start output
							for ($j = 0; $j < count($quali); ++$j)
							{
								$current_driver_id = $quali[$j];
								$position = ($j == 0) ? $user->lang['FORMEL_POLE'].': ' : $j + 1 . '. ' . $user->lang['FORMEL_PLACE'] . ': ';

								if ($config['drdeath_f1webtip_show_gfx'] == 1)
								{
									$template->assign_block_vars('qualirow_gfx', array(
										'L_PLACE'			=> $position,
										'DRIVERIMG'			=> (isset($drivers[$current_driver_id]['driver_img'])) 			? $drivers[$current_driver_id]['driver_img'] 		: '',
										'DRIVERCAR'			=> (isset($drivers[$current_driver_id]['driver_car'])) 			? $drivers[$current_driver_id]['driver_car'] 		: '',
										'DRIVERNAME'		=> (isset($drivers[$current_driver_id]['driver_name'])) 		? $drivers[$current_driver_id]['driver_name'] 		: '',
										'DRIVERTEAMNAME'	=> (isset($drivers[$current_driver_id]['driver_team_name'])) 	? $drivers[$current_driver_id]['driver_team_name'] 	: '',
										)
									);
								}
								else
								{
									$template->assign_block_vars('qualirow', array(
										'L_PLACE'			=> $position,
										'DRIVERNAME'		=> (isset($drivers[$current_driver_id]['driver_name'])) 		? $drivers[$current_driver_id]['driver_name'] 		: '',
										)
									);
								}
							}
						}
						else
						{
							// If no quali was found
							$template->assign_block_vars('no_quali', array());
						}

						// Checks for a saved result
						if ($races[$chosen_race]['race_result'] <> '0')
						{
							// Get the driver ids
							$results = explode(",", $races[$chosen_race]['race_result']);

							// Start output
							for ($j = 0; $j < count($results) - 3; ++$j)
							{
								$current_driver_id = $results[$j];
								$position = ($j == 0) ? $user->lang['FORMEL_RACE_WINNER'].': ' : $j + 1 . '. ' . $user->lang['FORMEL_PLACE'] . ': ';

								if ($config['drdeath_f1webtip_show_gfx'] == 1)
								{
									$template->assign_block_vars('resultsrow_gfx', array(
										'L_PLACE'			=> $position,
										'DRIVERIMG'			=> (isset($drivers[$current_driver_id]['driver_img'])) 			? $drivers[$current_driver_id]['driver_img'] 		: '',
										'DRIVERCAR'			=> (isset($drivers[$current_driver_id]['driver_car'])) 			? $drivers[$current_driver_id]['driver_car'] 		: '',
										'DRIVERNAME'		=> (isset($drivers[$current_driver_id]['driver_name'])) 		? $drivers[$current_driver_id]['driver_name'] 		: '',
										'DRIVERTEAMNAME'	=> (isset($drivers[$current_driver_id]['driver_team_name'])) 	? $drivers[$current_driver_id]['driver_team_name'] 	: '',
										)
									);
								}
								else
								{
									$template->assign_block_vars('resultsrow', array(
										'L_PLACE'			=> $position,
										'DRIVERNAME'		=> (isset($drivers[$current_driver_id]['driver_name'])) 		? $drivers[$current_driver_id]['driver_name'] 		: '',
										)
									);
								}
							}

							if ($config['drdeath_f1webtip_show_gfx'] == 1)
							{
								$template->assign_block_vars('extended_results_gfx', array(
									'PACE'				=> (isset($drivers[$results['10']]['driver_name']))	? $drivers[$results['10']]['driver_name'] 	: '',
									'TIRED'				=> (isset($results['11'])) 							? $results['11'] 							: '',
									'SAFETYCAR'			=> (isset($results['12'])) 							? $results['12'] 							: '',
									'YOUR_POINTS'		=> $user_tipp_points,
									)
								);
							}
							else
							{
								$template->assign_block_vars('extended_results', array(
									'PACE'				=> (isset($drivers[$results['10']]['driver_name']))	? $drivers[$results['10']]['driver_name'] 	: '',
									'TIRED'				=> (isset($results['11'])) 							? $results['11'] 							: '',
									'SAFETYCAR'			=> (isset($results['12'])) 							? $results['12'] 							: '',
									'YOUR_POINTS'		=> $user_tipp_points,
									)
								);
							}
						}
						else
						{
							// If no result was found
							$template->assign_block_vars('no_results', array());
						}

						// Game over
						if ($races[$chosen_race]['race_time'] - $config['drdeath_f1webtip_deadline_offset'] < $current_time)
						{
							$template->assign_block_vars('game_over', array());
						}
						else
						{
							//Check if it is a registered user. Guests are not allowed to place, edit or delete a tip.
							if ($this->user->data['is_registered'])
							{
								$template->assign_block_vars('place_tipp', array(
									'DELETE_TIPP'	=> $delete_button,
									'L_PLACE_TIPP'	=> $tipp_button,
									'PLACE_TIPP'	=> $tipp_button_name,
									)
								);
							}
						}

						break;
					}
				}


				// Forum button
				$discuss_button = '';

				if ($formel_forum_id)
				{
					$formel_forum_url	= append_sid($phpbb_root_path . "viewforum.$phpEx?f=$formel_forum_id");
					$formel_forum_name	= $user->lang['FORMEL_FORUM'];
					$discuss_button		= '<input class="button1" type="button" onclick="window.location.href=\'' . $formel_forum_url . '\'" value="' . $formel_forum_name . '" />&nbsp;&nbsp;';
				}

				// Moderator switch and options
				$u_call_mod = append_sid($phpbb_root_path . "ucp.$phpEx?i=pm&amp;mode=compose&amp;u=$formel_mod_id");
				$l_call_mod = $user->lang['FORMEL_CALL_MOD'];

				//Check if user is formel moderator or has admin access
				if ($user_id == $formel_mod_id || ($is_admin == 1))
				{
					$u_call_mod = $this->helper->route('f1webtip_controller', array('name' => 'results'));
					$l_call_mod = $user->lang['FORMEL_MOD_BUTTON_TEXT'];

					$template->assign_block_vars('tipp_moderator', array());
				}

				// Show headerbanner ?
				if ($config['drdeath_f1webtip_show_headbanner'])
				{
					$template->assign_block_vars('head_on', array());
				}

				$this->template->assign_vars(array(
					'S_INDEX'							=> true,
					'S_COUNTDOWN'						=> ($config['drdeath_f1webtip_show_countdown'] == 1) ? true : false,
					'S_FORM_ACTION'						=> $this->helper->route('f1webtip_controller', array('name' => 'index')),
					'U_FORMEL_CALL_MOD'					=> $u_call_mod,
					'U_FORMEL_FORUM'					=> $discuss_button,
					'U_FORMEL_RULES' 					=> $this->helper->route('f1webtip_controller', array('name' => 'rules')),
					'U_FORMEL_STATISTICS'				=> $this->helper->route('f1webtip_controller', array('name' => 'stats')),
					'U_TOP_MORE_USERS'					=> $this->helper->route('f1webtip_controller', array('name' => 'stats', 'mode' => 'users')),
					'U_TOP_MORE_DRIVERS'				=> $this->helper->route('f1webtip_controller', array('name' => 'stats', 'mode' => 'drivers')),
					'U_TOP_MORE_TEAMS'					=> $this->helper->route('f1webtip_controller', array('name' => 'stats', 'mode' => 'teams')),
					'HEADER_IMG' 						=> $ext_path . 'images/' . $config['drdeath_f1webtip_headbanner1_img'],
					'HEADER_HEIGHT' 					=> $config['drdeath_f1webtip_head_height'],
					'HEADER_WIDTH' 						=> $config['drdeath_f1webtip_head_width'],
					'L_FORMEL_CALL_MOD'					=> $l_call_mod,
					'RACE_ID'							=> (isset($races[$chosen_race]['race_id']))   ? $races[$chosen_race]['race_id']   : 1,
					'RACE_TIME'							=> (isset($races[$chosen_race]['race_time'])) ? $races[$chosen_race]['race_time'] : 1,
					'RACE_OFFSET'						=> $race_offset,
					'COUNTDOWN'							=> (isset($countdown)) ? $countdown : '',

					'EXT_PATH'							=> $ext_path,
					'EXT_PATH_IMAGES'					=> $ext_path . 'images/',
				));

			break;
		}

		page_header($page_title);
		return $this->helper->render('f1webtip_body.html', $name);
	}
}
