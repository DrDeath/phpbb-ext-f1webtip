<?php
/**
 *
 * Formula 1 WebTip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2014, Dr.Death, http://www.lpi-clan.de
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace drdeath\f1webtip\controller;

/**
 * @ignore
 */
use Symfony\Component\DependencyInjection\Container;
use phpbb\exception\http_exception;
use DateTime;

class main
{
	/* @var string phpBB root path */
	protected $root_path;

	/* @var string phpEx */
	protected $php_ext;

	/* @var Container */
	protected $phpbb_container;

	/* @var \phpbb\extension\manager */
	protected $phpbb_extension_manager;

	/* @var \phpbb\path_helper */
	protected $phpbb_path_helper;

	/* @var \phpbb\db\driver\driver_interface */
	protected $db;

	/* @var \phpbb\config\config */
	protected $config;

	/* @var \phpbb\log\log_interface */
	protected $log;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\auth\auth */
	protected $auth;

	/* @var \phpbb\request\request_interface */
	protected $request;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/* @var \phpbb\language\language */
	protected $language;

	/**
	* Constructor
	*
	* @param string									$root_path
	* @param string									$php_ext
	* @param Container								$phpbb_container
	* @param \phpbb\extension\manager				$phpbb_extension_manager
	* @param \phpbb\path_helper						$phpbb_path_helper
	* @param \phpbb\db\driver\driver_interfacer		$db
	* @param \phpbb\config\config					$config
	* @param \phpbb\log\log_interface 				$log
	* @param \phpbb\controller\helper				$helper
	* @param \phpbb\auth\auth						$auth
	* @param \phpbb\request\request_interface 		$request
	* @param \phpbb\template\template				$template
	* @param \phpbb\user							$user
	* @param \phpbb\language\language				$language
	*/
	public function __construct
	(
		$root_path,
		$php_ext,
		Container $phpbb_container,
		\phpbb\extension\manager $phpbb_extension_manager,
		\phpbb\path_helper $phpbb_path_helper,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\config\config $config,
		\phpbb\log\log_interface $log,
		\phpbb\controller\helper $helper,
		\phpbb\auth\auth $auth,
		\phpbb\request\request_interface $request,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\language\language $language
	)
	{
		$this->root_path					= $root_path;
		$this->php_ext 						= $php_ext;
		$this->phpbb_container 				= $phpbb_container;
		$this->phpbb_extension_manager 		= $phpbb_extension_manager;
		$this->phpbb_path_helper			= $phpbb_path_helper;
		$this->db 							= $db;
		$this->config 						= $config;
		$this->phpbb_log 					= $log;
		$this->helper 						= $helper;
		$this->auth							= $auth;
		$this->request						= $request;
		$this->template 					= $template;
		$this->user 						= $user;
		$this->language 					= $language;
	}


	/**
	* get_formel_userdata
	*
	* Get username, user_colour from a user_id
	* Returns user_id, username, user_colour if user_id was found.
	*/
	protected function get_formel_userdata($user_id)
	{
		$sql = 'SELECT user_id, username, user_colour, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height
				FROM ' . USERS_TABLE . '
				WHERE user_id = ' . (int) $user_id . '
					AND user_id <> ' . ANONYMOUS;
		$result = $this->db->sql_query($sql);

		$row = $this->db->sql_fetchrow($result);

		$this->db->sql_freeresult($result);

		return $row;
	}


	/**
	* get_formel_races
	*
	* Get all formel races data
	* Returns all races in $races
	*/
	protected function get_formel_races()
	{
		$table_races	= $this->phpbb_container->getParameter('tables.f1webtip.races');

		$sql = 'SELECT *
				FROM ' . $table_races . '
				ORDER BY race_time ASC';
		$result = $this->db->sql_query($sql);

		$races = $this->db->sql_fetchrowset($result);

		$this->db->sql_freeresult($result);

		// If no races exists, we have to create an empty race array to prevent errors
		if ($races == null)
		{
			$races = [
				[
					'race_id'		=> 0,
					'race_name'		=> '',
					'race_img'		=> '',
					'race_quali'	=> 0,
					'race_result'	=> 0,
					'race_time'		=> 0,
					'race_length'	=> '0',
					'race_laps'		=> '0',
					'race_distance'	=> '0',
					'race_debut'	=> 0,
					'race_mail'		=> 1,
				]
			];
		}

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
		$table_teams	= $this->phpbb_container->getParameter('tables.f1webtip.teams');
		$teams 			= [];

		$sql = 'SELECT *
				FROM ' . $table_teams;
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$teams[$row['team_id']] = $row;
		}

		$this->db->sql_freeresult($result);

		// If no teams exists, we have to create an empty team array to prevent errors
		if ($teams == null)
		{
			$teams = [
				[
					'team_id'		=> 0,
					'team_name'		=> '',
					'team_img'		=> '',
					'team_car'		=> 0,
					'team_penalty'	=> 0,
				]
			];
		}

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
		$teams 			= $this->get_formel_teams();
		$table_drivers	= $this->phpbb_container->getParameter('tables.f1webtip.drivers');
		$drivers 		= [];

		$sql = 'SELECT *
			FROM ' . $table_drivers . '
			ORDER BY driver_id ASC';
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			if ($row['driver_team'] != 0)
			{
				$drivercar = ($teams[$row['driver_team']]['team_car'] != '') ? $teams[$row['driver_team']]['team_car'] : $this->config['drdeath_f1webtip_no_car_img'];
			}
			else
			{
				$drivercar = $this->config['drdeath_f1webtip_no_car_img'];
			}

			$row['driver_img'] 			= ($row['driver_img'] == '') ? $this->config['drdeath_f1webtip_no_driver_img'] : $row['driver_img'];
			$row['driver_car'] 			= $drivercar;
			$row['driver_team_name'] 	= $teams[$row['driver_team']]['team_name'];
			$drivers[$row['driver_id']]	= $row;
		}

		$this->db->sql_freeresult($result);

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
		$table_drivers	= $this->phpbb_container->getParameter('tables.f1webtip.drivers');
		$drivers 		= [];

		$sql = 'SELECT *
			FROM ' . $table_drivers . '
			WHERE driver_disabled <> 1
			ORDER BY driver_name ASC';
		$result = $this->db->sql_query($sql);

		$counter = 1;

		while ($row = $this->db->sql_fetchrow($result))
		{
			$drivers[$counter] = $row;
			++$counter;
		}

		$drivers['0']['driver_id']   = '0';
		$drivers['0']['driver_name'] = $this->language->lang('FORMEL_DEFINE');

		$this->db->sql_freeresult($result);

		return $drivers;
	}

	/**
	* checkarrayforvalue
	*
	* Checks if a driver is already in the array.
	* Value 0 is an unset driver and may occur more than once
	* Returns true or false
	* If returns true, the tip is invalid.
	*/
	protected function checkarrayforvalue($value, $array)
	{
		$ret = false;

		if ($value != 0)
		{
			if (in_array($value, $array))
			{
				$ret = true;
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
		// load extension language
		$this->language->add_lang('common', 'drdeath/f1webtip');

		// Define the ext path. We will use it later for assigning the correct path to our local immages
		$ext_path = $this->phpbb_path_helper->update_web_root_path($this->phpbb_extension_manager->get_extension_path('drdeath/f1webtip', true));

		// This path is sent with the base template paths in the assign_vars()
		// call below. We need to correct it in case we are accessing from a
		// controller because the web paths will be incorrect otherwise.

		$this->phpbb_path_helper = $this->phpbb_container->get('path_helper');
		$corrected_path = $this->phpbb_path_helper->get_web_root_path();

		// Short names for the tables
		$table_races 	= $this->phpbb_container->getParameter('tables.f1webtip.races');
		$table_drivers 	= $this->phpbb_container->getParameter('tables.f1webtip.drivers');
		$table_wm 		= $this->phpbb_container->getParameter('tables.f1webtip.wm');
		$table_tips 	= $this->phpbb_container->getParameter('tables.f1webtip.tips');

		// Get formel config
		$formel_guests_allowed	= ($this->config['drdeath_f1webtip_guest_viewing'] == '1') ? true : false;
		$formel_forum_id 		= $this->config['drdeath_f1webtip_forum_id'];
		$formel_group_id 		= $this->config['drdeath_f1webtip_restrict_to'];
		$formel_mod_id 			= $this->config['drdeath_f1webtip_mod_id'];

		//
		// Check all permission to access the f1webtip
		//

		//If user is a bot, redirect to the index.
		if ($this->user->data['is_bot'])
		{
			redirect(append_sid("{$this->root_path}index." . $this->php_ext));
		}

		// If guest viewing is not allowed, redirect to login box
		if (!$formel_guests_allowed)
		{
			// Check if the user ist logged in.
			if (!$this->user->data['is_registered'])
			{
				// Not logged in? Redirect to the loginbox.
				login_box('', $this->language->lang('NO_AUTH_OPERATION'));
			}
		}
		// At this point we have no bots, only registered user and if guest viewing is allowed we have also guests here.

		if (!function_exists('group_memberships'))
		{
			include($this->root_path . 'includes/functions_user.' . $this->php_ext);
		}

		// Check if user has one of the formular 1 admin permission.
		// If user has one or more of these permissions, he gets also formular 1 moderator permissions.
		$is_admin = $this->auth->acl_gets('a_formel_settings', 'a_formel_drivers', 'a_formel_teams', 'a_formel_races');

		//Is the user member of the restricted group?
		$is_in_group = group_memberships($formel_group_id, $this->user->data['user_id'], true);

		/**
		* Deny access if
		*
		* restricted group	is enabled					and
		* user				is not in this group		and
		* user				is not formular 1 admin		and
		* user				is not formular 1 moderator
		*/
		if ($formel_group_id != 0 && !$is_in_group && $is_admin != 1 && $this->user->data['user_id'] != $formel_mod_id)
		{
			$auth_msg = $this->language->lang('FORMEL_ACCESS_DENIED', '<a href="' . append_sid($this->root_path . "ucp." . $this->php_ext . "?i=groups") . '" class="gen">', '</a>', '<a href="' . append_sid($this->root_path . "index." . $this->php_ext) . '" class="gen">', '</a>');
			trigger_error($auth_msg);
		}

		//
		// Creating breadcrumps
		//
		$this->template->assign_block_vars('navlinks', [
			'U_VIEW_FORUM'	=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']),
			'FORUM_NAME'	=> $this->language->lang('F1WEBTIP_PAGE'),
			]);

		// Salting the form... yumyum...
		add_form_key('drdeath/f1webtip');

		//
		// Switch to the selected modes...
		//
		switch ($name)
		{

			###########################
			###       RULES        ####
			###########################
			case 'rules':

				$page_title = $this->language->lang('FORMEL_TITLE');

				// Creating breadcrumps rules
				$this->template->assign_block_vars('navlinks', [
					'U_VIEW_FORUM'	=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'rules']),
					'FORUM_NAME'	=> $this->language->lang('FORMEL_RULES'),
					]);

				// Build rules
				$points_fastest 	= $this->config['drdeath_f1webtip_points_fastest'];
				$points_mentioned 	= $this->config['drdeath_f1webtip_points_mentioned'];
				$points_placed 		= $this->config['drdeath_f1webtip_points_placed'];
				$points_safetycar	= $this->config['drdeath_f1webtip_points_safety_car'];
				$points_tired 		= $this->config['drdeath_f1webtip_points_tired'];

				$points_total 		= 10 * ($points_mentioned + $points_placed) + $points_fastest + $points_tired + $points_safetycar;

				$points_fastest		.= ' ' . $this->language->lang('FORMEL_RULES_POINTS', (int) $points_fastest);
				$points_mentioned	.= ' ' . $this->language->lang('FORMEL_RULES_POINTS', (int) $points_mentioned);
				$points_placed		.= ' ' . $this->language->lang('FORMEL_RULES_POINTS', (int) $points_placed);
				$points_safetycar	.= ' ' . $this->language->lang('FORMEL_RULES_POINTS', (int) $points_safetycar);
				$points_tired		.= ' ' . $this->language->lang('FORMEL_RULES_POINTS', (int) $points_tired);
				$points_total		.= ' ' . $this->language->lang('FORMEL_RULES_POINTS', (int) $points_total);

				$rules_fastest 		= $this->language->lang('FORMEL_RULES_FASTEST'		, $points_fastest);
				$rules_mentioned 	= $this->language->lang('FORMEL_RULES_MENTIONED'	, $points_mentioned);
				$rules_placed 		= $this->language->lang('FORMEL_RULES_PLACED'		, $points_placed);
				$rules_safetycar	= $this->language->lang('FORMEL_RULES_SAFETYCAR'	, $points_safetycar);
				$rules_tired 		= $this->language->lang('FORMEL_RULES_TIRED'		, $points_tired);
				$rules_total 		= $this->language->lang('FORMEL_RULES_TOTAL'		, $points_total);

				// Show headerbanner ?
				if ($this->config['drdeath_f1webtip_show_headbanner'])
				{
					$this->template->assign_block_vars('heads_on', []);
				}

				$this->template->assign_vars([
					'EXT_PATH_IMAGES'			=> $ext_path . 'images/',
					'FORMEL_RULES_FASTEST' 		=> $rules_fastest,
					'FORMEL_RULES_MENTIONED' 	=> $rules_mentioned,
					'FORMEL_RULES_PLACED' 		=> $rules_placed,
					'FORMEL_RULES_SAFETYCAR' 	=> $rules_safetycar,
					'FORMEL_RULES_TIRED' 		=> $rules_tired,
					'FORMEL_RULES_TOTAL' 		=> $rules_total,
					'HEADER_HEIGHT' 			=> $this->config['drdeath_f1webtip_head_height'],
					'HEADER_IMG' 				=> $ext_path . 'images/banners/' . $this->config['drdeath_f1webtip_headbanner2_img'],
					'HEADER_WIDTH' 				=> $this->config['drdeath_f1webtip_head_width'],
					'S_FORM_ACTION'				=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']),
					'S_RULES'					=> true,
				]);

			break;

			###########################
			###       STATS        ####
			###########################
			case 'stats':

				$page_title = $this->language->lang('FORMEL_TITLE');

				// Creating breadcrumps rules
				$this->template->assign_block_vars('navlinks', [
					'U_VIEW_FORUM'	=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'stats']),
					'FORUM_NAME'	=> $this->language->lang('FORMEL_STATISTICS'),
					]);

				// Check buttons & data
				$show_drivers 	= $this->request->is_set_post('show_drivers');
				$show_teams 	= $this->request->is_set_post('show_teams');

				$mode = $this->request->variable('mode', '');

				// Show teams toplist
				if ($show_teams || $mode == 'teams')
				{
					$stat_table_title = $this->language->lang('FORMEL_TEAM_STATS');

					// Get all teams
					$teams = $this->get_formel_teams();

					// Get all wm points and fill Top10 teams
					$sql = 'SELECT sum(wm_points) AS total_points, wm_team
						FROM ' . $table_wm . '
						GROUP BY wm_team
						ORDER BY total_points DESC';
					$result = $this->db->sql_query($sql);

					//Stop! we have to recalc the team WM points... maybe we have some penalty !
					$recalc_teams = [];

					while ($row = $this->db->sql_fetchrow($result))
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

					foreach ($recalc_teams as $team)
					{
						++$real_rank;

						if ($team['total_points'] != $previous_points)
						{
							$rank = $real_rank;
							$previous_points = $team['total_points'];
						}

						$wm_teamname	= $team['team_name'];
						$wm_teamimg 	= $team['team_img'];
						$wm_teamcar 	= $team['team_car'];
						$wm_points		= $team['total_points'];
						$wm_teamimg 	= ( $wm_teamimg == '' ) ? $this->config['drdeath_f1webtip_no_team_img'] : $wm_teamimg;
						$wm_teamcar 	= ( $wm_teamcar == '' ) ? $this->config['drdeath_f1webtip_no_car_img']  : $wm_teamcar;

						$this->template->assign_block_vars(($this->config['drdeath_f1webtip_show_gfx'] == 1) ? 'top_teams_gfx' : 'top_teams', [
							'EXT_PATH'			=> $ext_path,
							'RANK' 				=> $rank,
							'WM_POINTS' 		=> $wm_points,
							'WM_TEAMCAR_HEIGHT'	=> $this->config['drdeath_f1webtip_car_img_height'],
							'WM_TEAMCAR_WIDTH'	=> $this->config['drdeath_f1webtip_car_img_width'],
							'WM_TEAMCAR' 		=> $wm_teamcar,
							'WM_TEAMIMG_HEIGHT'	=> $this->config['drdeath_f1webtip_team_img_height'],
							'WM_TEAMIMG_WIDTH'	=> $this->config['drdeath_f1webtip_team_img_width'],
							'WM_TEAMIMG' 		=> $wm_teamimg,
							'WM_TEAMNAME' 		=> $wm_teamname,
							]
						);
					}

					// Do we have some team points yet?
					if ($real_rank == 0)
					{
						$this->template->assign_block_vars(($this->config['drdeath_f1webtip_show_gfx'] == 1) ? 'top_teams_gfx' : 'top_teams', [
							'RANK' 			=> '',
							'WM_POINTS' 	=> '',
							'WM_TEAMCAR' 	=> '',
							'WM_TEAMIMG' 	=> '',
							'WM_TEAMNAME' 	=> $this->language->lang('FORMEL_NO_RESULTS'),
							]
						);
					}

					$this->db->sql_freeresult($result);

				}
				// Show drivers toplist
				else if ($show_drivers || $mode == 'drivers')
				{
					$stat_table_title = $this->language->lang('FORMEL_DRIVER_STATS');

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
					$result = $this->db->sql_query($sql);

					// Now put the gold medals into the $drivers array
					while ($row = $this->db->sql_fetchrow($result))
					{
						$drivers[$row['wm_driver']]['gold_medals']	= $row['gold_medals'];
					}

					$this->db->sql_freeresult($result);

					// Get all wm points and fill top10 drivers
					$sql = 'SELECT sum(wm_points) AS total_points, wm_driver, wm_team
						FROM ' . $table_wm . '
						GROUP BY wm_driver, wm_team
						ORDER BY total_points DESC';
					$result = $this->db->sql_query($sql);

					//Stop! we have to recalc the driver WM points... maybe we have some penalty !
					$recalc_drivers = [];

					while ($row = $this->db->sql_fetchrow($result))
					{
						$recalc_drivers[$row['wm_driver']]['total_points'] 	= (isset($drivers[$row['wm_driver']]['driver_penalty'])) 	? $row['total_points'] - $drivers[$row['wm_driver']]['driver_penalty'] 	: $row['total_points'];
						$recalc_drivers[$row['wm_driver']]['gold_medals']	= $drivers[$row['wm_driver']]['gold_medals'] 				?? 0;
						$recalc_drivers[$row['wm_driver']]['driver_name']	= $drivers[$row['wm_driver']]['driver_name'] 				?? '';
						$recalc_drivers[$row['wm_driver']]['driver_img']	= $drivers[$row['wm_driver']]['driver_img']					?? '';
						$recalc_drivers[$row['wm_driver']]['driver_car']	= $drivers[$row['wm_driver']]['driver_car']					?? '';
						$recalc_drivers[$row['wm_driver']]['team_img']		= $teams[$row['wm_team']]['team_img']						?? '';
					}

					// re-sort the drivers. Big points first ;-)
					arsort($recalc_drivers);

					$rank = 0;
					$previous_points = false;

					foreach ($recalc_drivers as $driver)
					{
						++$rank;

						$wm_drivername 	= $driver['driver_name'];
						$wm_driverimg 	= $driver['driver_img'];
						$wm_drivercar 	= $driver['driver_car'];
						$wm_driverteam 	= $driver['team_img'];
						$wm_driverteam 	= ( $wm_driverteam == '' ) ? $this->config['drdeath_f1webtip_no_team_img'] : $wm_driverteam;

						$this->template->assign_block_vars(($this->config['drdeath_f1webtip_show_gfx'] == 1) ? 'top_drivers_gfx' : 'top_drivers', [
							'EXT_PATH'				=> $ext_path,
							'RANK' 					=> $rank,
							'WM_DRIVERCAR_HEIGHT'	=> $this->config['drdeath_f1webtip_car_img_height'],
							'WM_DRIVERCAR_WIDTH'	=> $this->config['drdeath_f1webtip_car_img_width'],
							'WM_DRIVERCAR' 			=> $wm_drivercar,
							'WM_DRIVERIMG_HEIGHT'	=> $this->config['drdeath_f1webtip_driver_img_height'],
							'WM_DRIVERIMG_WIDTH'	=> $this->config['drdeath_f1webtip_driver_img_width'],
							'WM_DRIVERIMG' 			=> $wm_driverimg,
							'WM_DRIVERNAME' 		=> $wm_drivername,
							'WM_DRIVERTEAM_HEIGHT'	=> $this->config['drdeath_f1webtip_team_img_height'],
							'WM_DRIVERTEAM_WIDTH'	=> $this->config['drdeath_f1webtip_team_img_width'],
							'WM_DRIVERTEAM' 		=> $wm_driverteam,
							'WM_POINTS' 			=> $driver['total_points'],
							]
						);
					}

					// Do we have some driver points yet?
					if ($rank == 0)
					{
						$this->template->assign_block_vars(($this->config['drdeath_f1webtip_show_gfx'] == 1) ? 'top_drivers_gfx' : 'top_drivers', [
							'RANK' 					=> '',
							'WM_DRIVERCAR' 			=> '',
							'WM_DRIVERIMG' 			=> '',
							'WM_DRIVERNAME' 		=> $this->language->lang('FORMEL_NO_RESULTS'),
							'WM_DRIVERTEAM' 		=> '',
							'WM_POINTS' 			=> '',
							]
						);
					}

					$this->db->sql_freeresult($result);

				}
				// Show users toplist
				else
				{
					$stat_table_title = $this->language->lang('FORMEL_USER_STATS');

					// Get all tips and fill top10
					$sql = 'SELECT sum(tip_points) AS total_points, tip_user
						FROM ' . $table_tips . '
						GROUP BY tip_user
						ORDER BY total_points DESC';
					$result = $this->db->sql_query($sql);

					$rank = $real_rank  = 0;
					$previous_points = false;

					while ($row = $this->db->sql_fetchrow($result))
					{
						++$real_rank;

						if ($row['total_points'] != $previous_points)
						{
							$rank = $real_rank;
							$previous_points = $row['total_points'];
						}

						$tip_user_row			= $this->get_formel_userdata($row['tip_user']);
						$tip_username_link		= get_username_string('full', $tip_user_row['user_id'], $tip_user_row['username'], $tip_user_row['user_colour'] );
						$tip_user_avatar 		= '';
						$show_avatar_switch 	= false;

						if ($this->config['drdeath_f1webtip_show_avatar'] == 1)
						{
								$tip_user_avatar = phpbb_get_user_avatar($tip_user_row);

								// No User Avatar? Display the "no_avatar.gif" from the prosilver styles
								if ($tip_user_avatar == '')
								{
									$tip_user_avatar = false;
								}

								$show_avatar_switch = true;
						}

						$this->template->assign_block_vars('top_tippers', [
							'CORRECTED_PATH'		=> $corrected_path,
							'RANK'					=> ($rank == 1 || $rank == 2 || $rank == 3) ? "<b>" . $rank . "</b>" : $rank,
							'S_AVATAR_SWITCH'		=> $show_avatar_switch,
							'TIPPER_AVATAR_HEIGHT'	=> $this->config['avatar_max_height'] + 10,
							'TIPPER_AVATAR_WIDTH'	=> $this->config['avatar_max_width'] + 10,
							'TIPPER_AVATAR'			=> $tip_user_avatar,
							'TIPPER_NAME'			=> $tip_username_link,
							'TIPPER_POINTS'			=> $row['total_points'],
							]
						);
					}

					// Do we have some user tips yet?
					if ($real_rank == 0)
					{
						$show_avatar_switch 		= false;

						if ($this->config['drdeath_f1webtip_show_avatar'] == 1)
						{
							$show_avatar_switch 	= true;
						}

						$this->template->assign_block_vars('top_tippers', [
							'S_AVATAR_SWITCH'		=> $show_avatar_switch,
							'TIPPER_AVATAR_HEIGHT'	=> '0',
							'TIPPER_AVATAR_WIDTH'	=> '0',
							'TIPPER_AVATAR'			=> '',
							'TIPPER_NAME' 			=> $this->language->lang('FORMEL_NO_TIPPS'),
							'TIPPER_POINTS' 		=> '',
							]
						);
					}

					$this->db->sql_freeresult($result);
				}

				// Show headerbanner ?
				if ($this->config['drdeath_f1webtip_show_headbanner'])
				{
					$this->template->assign_block_vars('heads_on', []);
				}

				$this->template->assign_vars([
					'EXT_PATH_IMAGES'		=> $ext_path . 'images/',
					'HEADER_HEIGHT' 		=> $this->config['drdeath_f1webtip_head_height'],
					'HEADER_IMG' 			=> $ext_path . 'images/banners/' . $this->config['drdeath_f1webtip_headbanner3_img'],
					'HEADER_WIDTH' 			=> $this->config['drdeath_f1webtip_head_width'],
					'L_STAT_TABLE_TITLE' 	=> $stat_table_title,
					'S_FORM_ACTION' 		=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'stats']),
					'S_STATS'				=> true,
					'U_BACK_TO_TIPP' 		=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']),
					'U_FORMEL_STATS' 		=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'stats']),
					'U_FORMEL' 				=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']),
					]
				);

			break;

			###########################
			###      RESULTS       ####
			###########################
			case 'results':

				// Set template vars
				$page_title = $this->language->lang('FORMEL_TITLE');

				$this->template->assign_block_vars('navlinks', [
					'U_VIEW_FORUM' 	=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'results']),
					'FORUM_NAME'	=> $this->language->lang('FORMEL_RESULTS_TITLE'),
					]
				);

				// Check URL hijacker . Access only for formel moderators or admins
				if ($this->user->data['user_id'] != $formel_mod_id && $is_admin != 1)
				{
					$auth_msg = $this->language->lang('FORMEL_MOD_ACCESS_DENIED', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']) . '" class="gen">', '</a>', '<a href="' . append_sid($this->root_path . "index." . $this->php_ext) . '" class="gen">', '</a>');
					trigger_error($auth_msg);
				}

				// Fetch all races
				$sql = 'SELECT *
					FROM ' . $table_races . '
					ORDER BY race_time ASC';
				$result = $this->db->sql_query($sql);

				while ($row = $this->db->sql_fetchrow($result))
				{
					$race_img 				= $row['race_img'];
					$race_id 				= $row['race_id'];
					$race_img 				= ($race_img == '') 				? $this->config['drdeath_f1webtip_no_race_img'] : $race_img;
					$quali_buttons 			= ($row['race_quali']  == '0') 		? 'add' : 'edit';
					$result_buttons 		= ($row['race_result'] == '0') 		? 'add' : 'edit';

					$this->template->assign_block_vars(($this->config['drdeath_f1webtip_show_gfxr'] == 1) ? 'racerows_gfxr' : 'racerows', [
						'EXT_PATH'			=> $ext_path,
						'QUALI_BUTTONS'		=> $quali_buttons,
						'RACEDEAD'			=> $this->user->format_date($row['race_time'] - $this->config['drdeath_f1webtip_deadline_offset'], false, true),
						'RACEID'			=> $race_id,
						'RACEIMG'			=> $race_img,
						'RACENAME'			=> $row['race_name'],
						'RACETIME'			=> $this->user->format_date($row['race_time'], false, true),
						'RESULT_BUTTONS'	=> $result_buttons,
						]
					);
				}

				$this->db->sql_freeresult($result);

				$this->template->assign_vars([
					'EXT_PATH_IMAGES'		=> $ext_path . 'images/',
					'S_FORM_ACTION'			=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'addresults']),
					'S_RESULTS'				=> true,
					'U_FORMEL_RESULTS'		=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'results']),
					'U_FORMEL'				=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']),
					]
				);

			break;

			###########################
			###     ADDRESULTS     ####
			###########################
			case 'addresults':

				// Set template vars
				$page_title = $this->language->lang('FORMEL_TITLE');

				$this->template->assign_block_vars('navlinks', [
					'U_VIEW_FORUM'	=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'results']),
					'FORUM_NAME'	=> $this->language->lang('FORMEL_RESULTS_TITLE'),
					]
				);

				// Check URL hijacker . Access only for formel moderators or admins
				if ($this->user->data['user_id'] != $formel_mod_id && $is_admin != 1)
				{
					$auth_msg = $this->language->lang('FORMEL_MOD_ACCESS_DENIED', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']) . '" class="gen">', '</a>', '<a href="' . append_sid($this->root_path . "index." . $this->php_ext) . '" class="gen">', '</a>');
					trigger_error($auth_msg);
				}

				// Check buttons & data

				$addresult 		= $this->request->is_set_post('addresult');
				$addeditresult 	= $this->request->is_set_post('addeditresult');
				$editresult 	= $this->request->is_set_post('editresult');

				$addquali 		= $this->request->is_set_post('addquali');
				$editquali	 	= $this->request->is_set_post('editquali');
				$quali 			= $this->request->is_set_post('quali');

				$reset 			= $this->request->is_set_post('reset');
				$resetquali 	= $this->request->is_set_post('resetquali');
				$resetresult 	= $this->request->is_set_post('resetresult');

				$results		= $this->request->variable('result'				,	''	);
				$race_abort 	= $this->request->variable('race_abort'			,	0	);
				$race_double	= $this->request->variable('race_double'		,	0	);
				$race_id		= $this->request->variable('race_id'			,	0	);
				$racename		= $this->request->variable('racename'			,	'', true	);

				// Init some vars
				$quali_array	= [];
				$result_array	= [];

				//We have 10 Teams with 2 cars each --> 20 drivers
				$places			= ($quali||$addquali||$editquali) ? 20 : 10;

				// Reset a quali
				if ($resetquali && $race_id != 0)
				{
					// Check the salt... yumyum
					if (!check_form_key('drdeath/f1webtip'))
					{
						trigger_error('FORM_INVALID');
					}

					$sql_ary = [
						'race_quali'	=> 0,
					];

					$sql = 'UPDATE ' . $table_races . '
						SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE race_id = ' . (int) $race_id;
					$this->db->sql_query($sql);

					$this->phpbb_log->add('mod', $this->user->data['user_id'], $this->user->ip, 'LOG_FORMEL_QUALI_DELETED', false, ['forum_id' => 0, 'topic_id' => 0, $racename . ' (ID ' . $race_id . ')']);

					$tipp_msg = $this->language->lang('FORMEL_RESULTS_DELETED', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'results']) . '" class="gen">', '</a>', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']) . '" class="gen">', '</a>');
					trigger_error($tipp_msg);
				}

				// Reset a result
				if ($resetresult && $race_id != 0)
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
					$this->db->sql_query($sql);

					// Delete the race result for this race
					$sql_ary = [
						'race_result'	=> 0,
					];

					$sql = 'UPDATE ' . $table_races . '
						SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE race_id = ' . (int) $race_id;
					$this->db->sql_query($sql);

					// Delete all gathered tip points for this race
					$sql_ary = [
						'tip_points'	=> 0,
					];

					$sql = 'UPDATE ' . $table_tips . '
						SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE tip_race = ' . (int) $race_id;
					$this->db->sql_query($sql);

					// Pull out a success message
					$this->phpbb_log->add('mod', $this->user->data['user_id'], $this->user->ip, 'LOG_FORMEL_RESULT_DELETED', false, ['forum_id' => 0, 'topic_id' => 0, $racename . ' (ID ' . $race_id . ')']);

					$tipp_msg = $this->language->lang('FORMEL_RESULTS_DELETED', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'results']) . '" class="gen">', '</a>', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']) . '" class="gen">', '</a>');
					trigger_error($tipp_msg);
				}

				if (($reset || $resetresult || $resetquali) && $race_id == 0)
				{
					$reset_msg = $this->language->lang('FORMEL_RESULTS_ERROR', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'results']) . '" class="gen">', '</a>', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']) . '" class="gen">', '</a>');
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

					if ($race_id != 0)
					{
						//We have 10 Teams with 2 cars each --> 20 drivers
						for ($i = 0; $i < 20; ++$i)
						{
							$value = $this->request->variable('place' . ( $i + 1 ), 0);

							if ($this->checkarrayforvalue($value, $quali_array))
							{
								$this->phpbb_log->add('mod', $this->user->data['user_id'], $this->user->ip, 'LOG_FORMEL_QUALI_NOT_VALID', false, ['forum_id' => 0, 'topic_id' => 0, $racename . ' (ID ' . $race_id . ')']);

								$quali_msg = $this->language->lang('FORMEL_RESULTS_DOUBLE', '<a href="javascript:history.back()" class="gen">', '</a>', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']) . '" class="gen">', '</a>');
								trigger_error($quali_msg);
							}

							$quali_array[$i] = $value;
						}

						$new_quali = implode(",", $quali_array);

						$sql_ary = [
							'race_quali'	=> $new_quali,
						];

						$sql = 'UPDATE ' . $table_races . '
							SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE race_id = ' . (int) $race_id;
						$this->db->sql_query($sql);

						$this->phpbb_log->add('mod', $this->user->data['user_id'], $this->user->ip, 'LOG_FORMEL_QUALI_ADDED', false, ['forum_id' => 0, 'topic_id' => 0, $racename . ' (ID ' . $race_id . ')']);

						$quali_msg = $this->language->lang('FORMEL_RESULTS_ACCEPTED', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'results']) . '" class="gen">', '</a>', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']) . '" class="gen">', '</a>');
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

					if ($race_id != 0)
					{
						if ($addeditresult)
						{
							$sql = 'DELETE
								FROM ' . $table_wm . '
								WHERE wm_race = ' . (int) $race_id;
							$this->db->sql_query($sql);
						}

						for ($i = 0; $i < 10; ++$i)
						{
							$value = $this->request->variable('place' . ( $i + 1 ), 0);

							if ($this->checkarrayforvalue($value, $result_array))
							{
								$this->phpbb_log->add('mod', $this->user->data['user_id'], $this->user->ip, 'LOG_FORMEL_RESULT_NOT_VALID', false, ['forum_id' => 0, 'topic_id' => 0, $racename . ' (ID ' . $race_id . ')']);

								$result_msg = $this->language->lang('FORMEL_RESULTS_DOUBLE', '<a href="javascript:history.back()" class="gen">', '</a>', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']) . '" class="gen">', '</a>');
								trigger_error($result_msg);
							}

							$result_array[$i] = $value;
						}

						$result_array['10'] = $this->request->variable('place11', 0);	//['10'] --> fastest driver
						$result_array['11'] = $this->request->variable('place12', 0);	//['11'] --> tired count
						$result_array['12'] = $this->request->variable('place13', 0);	//['12'] --> count safety car deployment

						$new_result = implode(",", $result_array);

						$sql_ary = [
							'race_result'	=> $new_result,
						];

						$sql = 'UPDATE ' . $table_races . '
							SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE race_id = ' . (int) $race_id;
						$this->db->sql_query($sql);

						// START points calc
						// Get tipp data and calc user points
						$sql = 'SELECT *
							FROM ' . $table_tips . '
							WHERE tip_race = ' . (int) $race_id;
						$result = $this->db->sql_query($sql);

						while ($row = $this->db->sql_fetchrow($result))
						{
							$user_tipp_points = 0;
							$current_user = $row['tip_user'];
							$current_tipp_array = explode(',', $row['tip_result']);
							$temp_results_array = [];

							for ($i = 0; $i < count($current_tipp_array) - 3; ++$i)
							{
								$temp_results_array[$i] = $result_array[$i];
							}

							for ($i = 0; $i < count($current_tipp_array) - 3; ++$i)
							{
								if ($current_tipp_array[$i] != '0')
								{
									if ($this->checkarrayforvalue($current_tipp_array[$i], $temp_results_array))
									{
										$user_tipp_points += $this->config['drdeath_f1webtip_points_mentioned'];

										if ($current_tipp_array[$i] == $result_array[$i])
										{
											$user_tipp_points += $this->config['drdeath_f1webtip_points_placed'];
										}
									}
								}
							}

							// array['10'] : fastest driver
							if ($current_tipp_array['10'] == $result_array['10'] && $current_tipp_array['10'] != 0)
							{
								$user_tipp_points += $this->config['drdeath_f1webtip_points_fastest'];
							}

							// array['11'] : tired driver
							if ($current_tipp_array['11'] == $result_array['11'])
							{
								$user_tipp_points += $this->config['drdeath_f1webtip_points_tired'];
							}

							// array['12'] : safety_car
							if ($current_tipp_array['12'] == $result_array['12'] )
							{
								$user_tipp_points += $this->config['drdeath_f1webtip_points_safety_car'];
							}

							$sql_ary = [
								'tip_points'	=> $user_tipp_points,
							];

							$sql = 'UPDATE ' . $table_tips . '
								SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
								WHERE tip_race = ' . (int) $race_id . '
									AND tip_user = ' . (int) $current_user;
							$this->db->sql_query($sql);
						}

						$this->db->sql_freeresult($result);

						// Calc wm points
						// Get drivers data
						$sql = 'SELECT *
							FROM ' . $table_drivers;
						$result = $this->db->sql_query($sql);

						while ($row = $this->db->sql_fetchrow($result))
						{
							$teams[$row['driver_id']] = $row['driver_team'];
						}

						$this->db->sql_freeresult($result);

						// wm points:  25-18-15-12-10-8-6-4-2-1
						$wm = [];
						$wm['0'] = ($race_abort == false) ? 25 : 12.5;	// first place
						$wm['1'] = ($race_abort == false) ? 18 : 9;		// second place
						$wm['2'] = ($race_abort == false) ? 15 : 7.5;	// third place
						$wm['3'] = ($race_abort == false) ? 12 : 6;		// forth place
						$wm['4'] = ($race_abort == false) ? 10 : 5;		// fifth place
						$wm['5'] = ($race_abort == false) ? 8 : 4;		// sixth place
						$wm['6'] = ($race_abort == false) ? 6 : 3;		// seventh place
						$wm['7'] = ($race_abort == false) ? 4 : 2;		// eighth place
						$wm['8'] = ($race_abort == false) ? 2 : 1;		// ninth place
						$wm['9'] = ($race_abort == false) ? 1 : 0.5;	// tenth place

						// the race has double points, i.e. it is the last race of the season
						if ($race_double == true)
						{
							for ($i = 0; $i < count($wm); ++$i)
							{
								$wm[$i] = $wm[$i] *2;
							}
						}

						for ($i = 0; $i < count($result_array) - 3; ++$i)
						{
							$current_driver = $result_array[$i];

							// give 1 additional point for fastest lap
							// $result_array['10'] --> fastest driver
							if ($current_driver == $result_array['10'])
							{
								$wm[$i] = $wm[$i] + 1;
							}

							if ($current_driver != '0')
							{
								$current_team 	= $teams[$current_driver];
								$wm_points 		= $wm[$i];
								$sql_ary = [
									'wm_race'	=> (int) $race_id,
									'wm_driver'	=> (int) $current_driver,
									'wm_team'	=> (int) $current_team,
									'wm_points'	=> $wm_points,
								];

								$this->db->sql_query('INSERT INTO ' . $table_wm . ' ' . $this->db->sql_build_array('INSERT', $sql_ary));
							}
						}
						// END points calc

						$this->phpbb_log->add('mod', $this->user->data['user_id'], $this->user->ip, 'LOG_FORMEL_RESULT_ADDED', false, ['forum_id' => 0, 'topic_id' => 0, $racename . ' (ID ' . $race_id . ')']);

						$result_msg = $this->language->lang('FORMEL_RESULTS_ACCEPTED', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'results']) . '" class="gen">', '</a>', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']) . '" class="gen">', '</a>');
						trigger_error($result_msg);
					}
				}

				// Load add/edit quali
				if (($quali || $editquali) && $race_id != 0)
				{
					if ($editquali)
					{
						// Get the race
						$sql = 'SELECT *
							FROM ' . $table_races . '
								WHERE race_id = ' . (int) $race_id;
						$result = $this->db->sql_query($sql);

						$row = $this->db->sql_fetchrow($result);
						$quali_array = explode(',', $row['race_quali']);
						$this->db->sql_freeresult($result);
					}

					// Fetch all available drivers
					$sql = 'SELECT *
						FROM ' . $table_drivers . '
						WHERE driver_disabled <> 1
						ORDER BY driver_name ASC';
					$result = $this->db->sql_query($sql);

					$counter = 1;

					while ($row = $this->db->sql_fetchrow($result))
					{
						$drivers[$counter] = $row;
						++$counter;
					}

					$this->db->sql_freeresult($result);

					$drivers['0']['driver_id'] = '0';
					$drivers['0']['driver_name'] = $this->language->lang('FORMEL_DEFINE');

					//We have 10 Teams with 2 cars each --> 20 drivers
					for ($i = 0; $i < 20; ++$i)
					{
						$position			= ($i == 0) ? $this->language->lang('FORMEL_POLE') : $i + 1 . '. ' . $this->language->lang('FORMEL_PLACE');
						$box_name			= 'place' . ($i + 1);
						$option_list_driver	= '';

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

							$option_list_driver .= '<option value="' . $this_driver_id . '" ' . $selected . '>' . $this_driver_name . '</option>';
						}

						$this->template->assign_block_vars('qualirows', [
							'BOX_NAME'				=> $box_name,
							'L_PLACE'				=> $position,
							'OPTION_LIST_DRIVER'	=> $option_list_driver,
							]
						);
					}

					$this->template->assign_block_vars('qualifications', []);

					$this->template->assign_vars([
							'PLACES'		=> $places,
							'S_QUALI'		=> true,
							]
						);
				}

				// Load add or edit result
				if (($results || $editresult) && $race_id != 0)
				{
					if ($editresult)
					{
						// Get the race
						$sql = 'SELECT *
							FROM ' . $table_races . '
							WHERE race_id = ' . (int) $race_id;
						$result = $this->db->sql_query($sql);

						$row = $this->db->sql_fetchrow($result);
						$result_array = explode(',', $row['race_result']);
						$this->db->sql_freeresult($result);
					}

					// Fetch all available drivers
					$sql = 'SELECT *
						FROM ' . $table_drivers . '
						WHERE driver_disabled <> 1
						ORDER BY driver_name ASC';
					$result = $this->db->sql_query($sql);

					$counter = 1;

					while ($row = $this->db->sql_fetchrow($result))
					{
						$drivers[$counter] = $row;
						++$counter;
					}

					$this->db->sql_freeresult($result);

					$drivers['0']['driver_id'] = '0';
					$drivers['0']['driver_name'] = $this->language->lang('FORMEL_DEFINE');

					for ($i = 0; $i < 10; ++$i)
					{
						$position			= ($i == 0) ? $this->language->lang('FORMEL_RACE_WINNER') : $i + 1 . '. ' . $this->language->lang('FORMEL_PLACE');
						$box_name			= 'place' . ($i + 1);
						$option_list_driver	= '';

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

							$option_list_driver .= '<option value="' . $this_driver_id . '" ' . $selected . '>' . $this_driver_name . '</option>';
						}

						$this->template->assign_block_vars('resultsrow', [
							'BOX_NAME'				=> $box_name,
							'L_PLACE'				=> $position,
							'OPTION_LIST_DRIVER'	=> $option_list_driver,
							]
						);
					}

					$option_list_pace = '';

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

						$option_list_pace .= '<option value="' . $this_driver_id . '" ' . $selected . '>' . $this_driver_name . '</option>';
					}

					$option_list_tired = '';

					//We have 10 Teams with 2 cars each --> 20 drivers
					for ($k = 0; $k < 21; ++$k)
					{
						if (isset($result_array['11']))
						{
							$selected = ( $k == $result_array['11']) ? 'selected="selected"' : '';
						}
						else
						{
							$selected = '';
						}

						$option_list_tired .= '<option value="' . $k . '" ' . $selected . '>' . $k . '</option>';
					}

					$option_list_safetycar = '';

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

						$option_list_safetycar .= '<option value="' . $k . '" ' . $selected . '>' . $k . '</option>';
					}

					$mode = ($editresult) ? 'addeditresult' : 'addresult';

					$this->template->assign_block_vars('results', [
						'MODE' 					=> $mode,
						'OPTION_LIST_PACE' 		=> $option_list_pace,
						'OPTION_LIST_SAFETYCAR'	=> $option_list_safetycar,
						'OPTION_LIST_TIRED' 	=> $option_list_tired,
						]
					);
				}

				$this->template->assign_vars([
					'EXT_PATH_IMAGES'		=> $ext_path . 'images/',
					'PLACES'				=> $places,
					'RACE_ID' 				=> $race_id,
					'RACENAME' 				=> $racename,
					'S_ADDRESULTS'			=> true,
					'S_FORM_ACTION' 		=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'addresults']),
					'U_FORMEL_RESULTS' 		=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'results']),
					'U_FORMEL' 				=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']),
					]
				);

			break;

			###########################
			###       USERTIP      ####
			###########################
			case 'usertipp':

				// Set template vars
				$page_title = $this->language->lang('FORMEL_TITLE');

				// Check buttons & data
				$tipp_id = $this->request->variable('tipp',	0);
				$race_id = $this->request->variable('race',	0);

				// Get current race and time
				$race 			= $this->get_formel_races();
				$results		= explode(",", $race[$race_id]['race_result']);
				$current_time	= time();

				// Get current tip
				$sql = 'SELECT *
					FROM ' . $table_tips . '
					WHERE tip_id = ' . (int) $tipp_id;
				$result = $this->db->sql_query($sql);

				$tipp_active = $this->db->sql_affectedrows($result);

				// Do the work only if there is a tip
				if ($tipp_active)
				{
					$tippdata = $this->db->sql_fetchrowset($result);
					$tipp_userdata = $this->get_formel_userdata($tippdata['0']['tip_user']);
					$this->db->sql_freeresult($result);

					// Get all drivers
					$sql = 'SELECT *
						FROM ' . $table_drivers . '
						ORDER BY driver_id ASC';
					$result = $this->db->sql_query($sql);

					$driver_name = [];

					while ($row = $this->db->sql_fetchrow($result))
					{
						$driver_name[$row['driver_id']] = $row['driver_name'];
					}

					$this->db->sql_freeresult($result);

					$tipper_all_points = '';

					// Get all tip points
					$sql = 'SELECT sum(tip_points) AS total_points
						FROM ' . $table_tips . '
						WHERE tip_user = ' . (int) $tipp_userdata['user_id'];
					$result = $this->db->sql_query($sql);

					while ($row = $this->db->sql_fetchrow($result))
					{
						$tipper_all_points = $row['total_points'];
					}

					$this->db->sql_freeresult($result);

					// Build output

					$tipp_array 		= [];
					$tipper_name 		= get_username_string('username', $tipp_userdata['user_id'], $tipp_userdata['username'], $tipp_userdata['user_colour']);
					$tipp_user_colour	= get_username_string('colour', $tipp_userdata['user_id'], $tipp_userdata['username'], $tipp_userdata['user_colour']);
					$tipper_style		= ($tipp_user_colour) ? ' style="color: ' . $tipp_user_colour . '; font-weight: bold;"' : '' ;
					$tipper_link 		= ($tipper_name != $this->language->lang('GUEST')) ? '<a href="' . append_sid("{$this->root_path}memberlist." . $this->php_ext, 'mode=viewprofile&amp;u=' . (int) $tipp_userdata['user_id']) . '"' . $tipper_style . ' onclick="window.open(this.href); return false">' . $tipper_name . '</a>' : $tipper_name;
					$tipper_points 		= $tippdata['0']['tip_points'];
					$tipp_array 		= explode(',', $tippdata['0']['tip_result']);
					$is_hidden			= ($race[$race_id]['race_time'] - $this->config['drdeath_f1webtip_deadline_offset']  <= $current_time ) ? false : true ;

					for ($i = 0; $i < count($tipp_array) - 3; ++$i)
					{
						$position 		= ($i == 0) ? $this->language->lang('FORMEL_RACE_WINNER') : $i + 1 . '. ' . $this->language->lang('FORMEL_PLACE');
						$driver_placed 	= $driver_name[$tipp_array[$i]] ?? '';
						$driverid 		= $tipp_array[$i] ?? '';

						//Recalc Tipp Points for Place 1 - 10
						$single_points = 0;

						if (isset($results[$i]))
						{
							if (($driverid == $results[$i]) && $driverid != 0)
							{
								$single_points += $this->config['drdeath_f1webtip_points_placed'];
							}
						}

						for ($j = 0; $j < count($tipp_array) - 3; ++$j)
						{
							if (isset($results[$j]))
							{
								if (($driverid == $results[$j]) && $driverid != 0)
								{
									$single_points += $this->config['drdeath_f1webtip_points_mentioned'];
								}
							}
						}

						if ($single_points == 0)
						{
							$single_points='';
						}

						$this->template->assign_block_vars('user_drivers', [
							'DRIVER_PLACED'	=> ($is_hidden == true && $tipp_userdata['user_id'] != $this->user->data['user_id']) ? $this->language->lang('FORMEL_HIDDEN') : $driver_placed,
							'POSITION'		=> $position,
							'SINGLE_POINTS'	=> $single_points,
							]
						);
					}

					$fastest_driver_name 	= $driver_name[$tipp_array['10']] ?? '';
					$tired 					= $tipp_array['11'] ?? '';
					$safetycar				= $tipp_array['12'] ?? '';

					//Recalc tip points for fastest driver, tired count and safety cars
					$single_fastest	= $single_tired = $single_safety_car = '';

					if (isset($results['10']) && $results['10'] != 0)
					{
						if ($tipp_array['10'] == $results['10'])
						{
							$single_fastest = $this->config['drdeath_f1webtip_points_fastest'];
						}
					}

					if (isset($results['11']))
					{
						if ($tipp_array['11'] == $results['11'])
						{
							$single_tired = $this->config['drdeath_f1webtip_points_tired'];
						}
					}

					if (isset($results['12']))
					{
						if ($tipp_array['12'] == $results['12'])
						{
							$single_safety_car = $this->config['drdeath_f1webtip_points_safety_car'];
						}
					}

					$this->template->assign_block_vars('user_tipps', [
						'ALL_POINTS' 		=> $tipper_all_points,
						'FASTEST_DRIVER' 	=> (isset($fastest_driver_name)) 	? ($is_hidden == true && $tipp_userdata['user_id'] != $this->user->data['user_id']) ? $this->language->lang('FORMEL_HIDDEN') : $fastest_driver_name : '',
						'POINTS' 			=> $tipper_points,
						'SAFETYCAR' 		=> (isset($safetycar)) 				? ($is_hidden == true && $tipp_userdata['user_id'] != $this->user->data['user_id']) ? $this->language->lang('FORMEL_HIDDEN') : $safetycar : '',
						'SINGLE_FASTEST' 	=> $single_fastest 					?? '',
						'SINGLE_SAFETY_CAR' => $single_safety_car 				?? '',
						'SINGLE_TIRED' 		=> $single_tired 					?? '',
						'TIPPER' 			=> $tipper_link,
						'TIRED' 			=> (isset($tired)) 					? ($is_hidden == true && $tipp_userdata['user_id'] != $this->user->data['user_id']) ? $this->language->lang('FORMEL_HIDDEN') : $tired : '',
						]
					);
				}
				else
				{
					$this->template->assign_block_vars('no_tipps', []);
				}

				// Output global values
				$this->template->assign_vars([
					'S_USERTIPP'		=> true,
					]
				);

			break;

			###########################
			###       INDEX        ####
			###########################
			case 'index':
			default:

				// honeypot
				if ($this->request->variable('honeypot', '', true) != '')
				{
					throw new http_exception(401, 'NOT_AUTHORISED');
				}

				$page_title 	= $this->language->lang('FORMEL_TITLE');

				// Check buttons & data
				$del_tipp 		= $this->request->is_set_post('del_tipp');
				$edit_my_tipp 	= $this->request->is_set_post('edit_my_tipp');
				$next 			= $this->request->is_set_post('next');
				$place_my_tipp 	= $this->request->is_set_post('place_my_tipp');
				$prev 			= $this->request->is_set_post('prev');
				$race_id 		= $this->request->variable('race_id'		, 0);
				$race_offset 	= $this->request->variable('race_offset'	, 0);
				$racename 		= $this->request->variable('racename'		, '', true);
				$tipp_time 		= $this->request->variable('tipp_time'		, 0);
				$user_id 		= $this->user->data['user_id'];

				//Define some vars
				$chosen_race 		= '';
				$countdown_stop 	= '';
				$driverteamname 	= '';
				$gfxdrivercar 		= '';
				$gfxdrivercombo 	= '';
				$my_tipp 			= '';
				$my_tipp_array 		= [];
				$places				= 10;
				$single_fastest		= '';
				$single_safety_car 	= '';
				$single_tired 		= '';

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
					$this->db->sql_query($sql);

					$this->phpbb_log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_FORMEL_TIP_DELETED', false, ['reportee_id' => 0, $racename . ' (ID ' . $race_id . ')']);

					$tipp_msg = $this->language->lang('FORMEL_TIPP_DELETED', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']) . '" class="gen">', '</a>', '<a href="' . append_sid("{$this->root_path}index." . $this->php_ext) . '" class="gen">', '</a>');
					trigger_error( $tipp_msg);
				}

				// Add or edit a tip
				if (($place_my_tipp || $edit_my_tipp) && $tipp_time - $this->config['drdeath_f1webtip_deadline_offset'] >= time())
				{
					// Check the salt... yumyum
					if (!check_form_key('drdeath/f1webtip'))
					{
						trigger_error('FORM_INVALID');
					}

					for ($i = 0; $i < 10; ++$i)
					{
						$value = $this->request->variable('place' . ( $i + 1 ), 0);

						if ($this->checkarrayforvalue($value, $my_tipp_array))
						{
							$this->phpbb_log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_FORMEL_TIP_NOT_VALID', false, ['reportee_id' => 0, $racename . ' (ID ' . $race_id . ')']);

							$tipp_msg = $this->language->lang('FORMEL_DUBLICATE_VALUES', '<a href="javascript:history.back()" class="gen">', '</a>', '<a href="' . append_sid("{$this->root_path}index." . $this->php_ext) . '" class="gen">', '</a>');
							trigger_error($tipp_msg);
						}

						$my_tipp_array[$i] = $value;
					}

					$my_tipp_array['10'] 	= $this->request->variable('place11', 0); //['10'] --> fastest driver
					$my_tipp_array['11'] 	= $this->request->variable('place12', 0); //['11'] --> tired count
					$my_tipp_array['12'] 	= $this->request->variable('place13', 0); //['12'] --> count of safety car deployments

					$my_tipp 				= implode(",", $my_tipp_array);

					if ($place_my_tipp)
					{
						// Prevent inserting more than one tipp
						$sql = 'DELETE
							FROM ' . $table_tips . '
							WHERE tip_user = ' . (int) $user_id . '
								AND tip_race = ' . (int) $race_id;
						$this->db->sql_query($sql);

						// Now insert new tipp
						$sql_ary = [
							'tip_user'		=> (int) $user_id,
							'tip_race'		=> (int) $race_id,
							'tip_result'	=> $my_tipp,
							'tip_points'	=> 0,
						];

						$this->db->sql_query('INSERT INTO ' . $table_tips . ' ' . $this->db->sql_build_array('INSERT', $sql_ary));

						$this->phpbb_log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_FORMEL_TIP_GIVEN', false, ['reportee_id' => 0, $racename . ' (ID ' . $race_id . ')']);
					}
					else
					{
						$sql_ary = [
							'tip_result'	=> $my_tipp,
						];

						$sql = 'UPDATE ' . $table_tips . '
							SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE tip_user = ' . (int) $user_id . '
								AND tip_race = ' . (int) $race_id;
						$this->db->sql_query($sql);

						$this->phpbb_log->add('user', $this->user->data['user_id'], $this->user->ip, 'LOG_FORMEL_TIP_EDITED', false, ['reportee_id' => 0, $racename . ' (ID ' . $race_id . ')']);
					}

					$tipp_msg = $this->language->lang('FORMEL_ACCEPTED_TIPP', '<a href="' . $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']) . '" class="gen">', '</a>', '<a href="' . append_sid("{$this->root_path}index." . $this->php_ext) . '" class="gen">', '</a>');
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
				$result = $this->db->sql_query($sql);

				$rank = $real_rank  = 0;
				$previous_points = false;

				while ($row = $this->db->sql_fetchrow($result))
				{
					++$real_rank;

					if ($row['total_points'] != $previous_points)
					{
						$rank = $real_rank;
						$previous_points = $row['total_points'];
					}

					$tipp_user_row		= $this->get_formel_userdata($row['tip_user']);
					$tipp_username_link	= get_username_string('full', $tipp_user_row['user_id'], $tipp_user_row['username'], $tipp_user_row['user_colour']);

					$this->template->assign_block_vars('top_tippers', [
						'TIPPER_NAME'	=> $tipp_username_link,
						'RANK'			=> $rank,
						'TIPPER_POINTS'	=> $row['total_points'],
						]
					);
				}

				// Do we have some user tips yet?
				if ($real_rank == 0)
				{
					$this->template->assign_block_vars('top_tippers', [
						'TIPPER_NAME'	=> $this->language->lang('FORMEL_NO_TIPPS'),
						'RANK'			=> '',
						'TIPPER_POINTS' => '0',
						]
					);
				}

				$this->db->sql_freeresult($result);

				//
				//Get all first place winner, count all first places,  grep all gold medals...  Marker for first place: 25 WM Points
				//
				$sql = 'SELECT 	count(wm_driver) as gold_medals,
								wm_driver
						FROM 	' . $table_wm . '
						WHERE 	wm_points = 25
						GROUP BY wm_driver
						ORDER BY gold_medals DESC';
				$result = $this->db->sql_query($sql);

				// Now put the gold medals into the $drivers array
				while ($row = $this->db->sql_fetchrow($result))
				{
					$drivers[$row['wm_driver']]['gold_medals']	= $row['gold_medals'];
				}

				$this->db->sql_freeresult($result);

				// Get all wm points and fill top10 drivers
				$sql = 'SELECT sum(wm_points) AS total_points, wm_driver
					FROM ' . $table_wm . '
					GROUP BY wm_driver
					ORDER BY total_points DESC';
				$result = $this->db->sql_query($sql);

				//Stop! we have to recalc the driver WM points... maybe we have some penalty !
				$recalc_drivers = [];

				while ($row = $this->db->sql_fetchrow($result))
				{
					$recalc_drivers[$row['wm_driver']]['total_points'] 	= (isset($drivers[$row['wm_driver']]['driver_penalty'])) 	? $row['total_points'] - $drivers[$row['wm_driver']]['driver_penalty'] : $row['total_points'];
					$recalc_drivers[$row['wm_driver']]['gold_medals']	= $drivers[$row['wm_driver']]['gold_medals'] 				?? 0;
					$recalc_drivers[$row['wm_driver']]['driver_name']	= $drivers[$row['wm_driver']]['driver_name'] 				?? '';
				}

				// re-sort the drivers. Big points first ;-)
				arsort($recalc_drivers);

				$rank = $limit = 0;

				foreach ($recalc_drivers as $driver)
				{
					if ($limit == 5)
					{
						break;
					}

					++$rank;

					$wm_drivername = $driver['driver_name'];

					$this->template->assign_block_vars('top_drivers', [
						'RANK'			=> $rank,
						'WM_DRIVERNAME'	=> $wm_drivername,
						'WM_POINTS'		=> $driver['total_points'],
						]
					);

					++$limit;
				}

				// Do we have some driver points yet?
				if ($rank == 0)
				{
					$this->template->assign_block_vars('top_drivers', [
						'RANK'			=> '',
						'WM_DRIVERNAME'	=> $this->language->lang('FORMEL_NO_RESULTS'),
						'WM_POINTS'		=> '0',
						]
					);
				}

				$this->db->sql_freeresult($result);

				//
				// Get all wm points and fill top10 teams
				//
				$sql = 'SELECT sum(wm_points) AS total_points, wm_team
					FROM ' . $table_wm . '
					GROUP BY wm_team
					ORDER BY total_points DESC';
				$result = $this->db->sql_query($sql);

				//Stop! we have to recalc the team WM points... maybe we have some penalty !
				$recalc_teams = [];

				while ($row = $this->db->sql_fetchrow($result))
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

				foreach ($recalc_teams as $team)
				{
					if ($limit == 5)
					{
						break;
					}

					++$real_rank;

					if ($team['total_points'] != $previous_points)
					{
						$rank = $real_rank;
						$previous_points = $team['total_points'];
					}

					$wm_teamname = $team['team_name'];
					$this->template->assign_block_vars('top_teams', [
						'RANK'			=> $rank,
						'WM_TEAMNAME'	=> $wm_teamname,
						'WM_POINTS'		=> $team['total_points'],
						]
					);

					++$limit;
				}

				// Do we have some team points yet?
				if ($real_rank == 0)
				{
					$this->template->assign_block_vars('top_teams', [
						'RANK'			=> '',
						'WM_TEAMNAME'	=> $this->language->lang('FORMEL_NO_RESULTS'),
						'WM_POINTS'		=> '0',
						]
					);
				}

				$this->db->sql_freeresult($result);

				//
				// Find current race
				//
				$race_selector = count($races) -1;

				for ($i = 0; $i < count($races); ++$i)
				{
					if ($races[$i]['race_time'] > $current_time - $this->config['drdeath_f1webtip_event_change'])
					{
						$race_selector = $i;
						break;
					}
				}

				// Check for a overflow
				$race_offset = ($race_selector + $race_offset == count($races)) ? 0 - $race_selector  : $race_offset;
				$race_offset = ($race_selector + $race_offset < 0) ? count($races) - 1 - $race_selector : $race_offset;

				// Define current race incl. user given offset
				$chosen_race = $race_selector + $race_offset;

				$user_tipp_points = 0;
				$race_id = (int) $races[$chosen_race]['race_id'];
				$user_id = $this->user->data['user_id'];

				//Countdown data
				if ($this->config['drdeath_f1webtip_show_countdown'] == 1)
				{
					$one_hour		= 3600;
					$dst			= (int) date('I') * $one_hour;
					//$utc_diff is the difference in seconds from UTC to local server time zone i.E. UTC + 1 hour
					$utc_diff		= 3600;
					// ToDo: Check if $offset_user is still valid if DST is ON or OFF.
					$offset_user	= $this->user->timezone->getOffset(new DateTime($this->config['board_timezone'])) - $utc_diff - $dst;
					$event_stop		= $races[$chosen_race]['race_time'] - $this->config['drdeath_f1webtip_deadline_offset'] - $offset_user;
					$b_day			= $this->user->format_date($event_stop, 'd');
					$b_month		= $this->user->format_date($event_stop, 'n');
					$b_year			= $this->user->format_date($event_stop, 'Y');
					$b_hour			= $this->user->format_date($event_stop, 'H');
					$b_minute		= $this->user->format_date($event_stop, 'i');
					$b_second		= $this->user->format_date($event_stop, 's');

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

					$countdown_stop 	= $b_month . ' ' . $b_day . ', ' . $b_year . ' ' . $b_hour . ':' . $b_minute . ':' . $b_second;
				}

				// Get race image and data
				$race_img = $races[$chosen_race]['race_img'];
				$race_img = ($race_img == '') ? $this->config['drdeath_f1webtip_no_race_img']: $race_img ;

				$this->template->assign_block_vars('racerows', [
					'EXT_PATH'			=> $ext_path,
					'RACEDEAD'			=> $this->user->format_date($races[$chosen_race]['race_time'] - $this->config['drdeath_f1webtip_deadline_offset'], false, true),
					'RACEDEBUT'			=> $races[$chosen_race]['race_debut'],
					'RACEDISTANCE'		=> $races[$chosen_race]['race_distance'],
					'RACEIMG_HEIGHT'	=> $this->config['drdeath_f1webtip_race_img_height'],
					'RACEIMG_WIDTH'		=> $this->config['drdeath_f1webtip_race_img_width'],
					'RACEIMG'			=> $race_img,
					'RACELAPS'			=> $races[$chosen_race]['race_laps'],
					'RACELENGTH'		=> $races[$chosen_race]['race_length'],
					'RACENAME'			=> $races[$chosen_race]['race_name'],
					'RACETIME'			=> $this->user->format_date($races[$chosen_race]['race_time'], false, true),
					]
				);

				if ($this->config['drdeath_f1webtip_show_gfxr'] == 1)
				{
					$this->template->assign_block_vars('racegfx', []);
				}

				// Find current tippers and their points
				// Get tip data
				$sql = 'SELECT *
					FROM ' . $table_tips . '
					WHERE tip_race = ' . (int) $race_id . '
						ORDER BY tip_points DESC';
				$result = $this->db->sql_query($sql);

				$tippers_active = $this->db->sql_affectedrows($result);
				$cur_counter = 1;

				while ($row = $this->db->sql_fetchrow($result))
				{
					$current_tippers_userdata	= $this->get_formel_userdata($row['tip_user']);
					$current_tipp_id			= $row['tip_id'];
					$current_tippers_username	= get_username_string('username', $row['tip_user'], $current_tippers_userdata['username'], $current_tippers_userdata['user_colour'] );
					$current_tippers_colour		= get_username_string('colour'  , $row['tip_user'], $current_tippers_userdata['username'], $current_tippers_userdata['user_colour'] );
					$separator					= ($cur_counter == $tippers_active) ? '': ', ';

					$this->template->assign_block_vars('tipps_made', [
						'SEPARATOR'		=> $separator,
						'STYLE'			=> ($current_tippers_colour) ? ' style="color: ' . $current_tippers_colour . '; font-weight: bold;"' : '',
						'USERNAME'		=> $current_tippers_username . ' (' . $row['tip_points'] . ')',
						'USERTIPP'		=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'usertipp', 'mode' => 'teams', 'tipp' => $current_tipp_id, 'race' => $chosen_race]),
						]
					);

					++$cur_counter;
				}

				if ($tippers_active == 0)
				{
					$this->template->assign_block_vars('no_tipps_made', []);
				}

				$this->db->sql_freeresult($result);

				// Get tip data
				$sql = 'SELECT *
					FROM ' . $table_tips . '
					WHERE tip_race = ' . (int) $race_id . '
						AND tip_user = ' . (int) $user_id;
				$result = $this->db->sql_query($sql);

				$tipp_active		= $this->db->sql_affectedrows($result);
				$delete_button		= false;
				$tipp_button		= $this->language->lang('FORMEL_ADD_TIPP');
				$tipp_button_name	= 'place_my_tipp';
				$tipp_data			= $this->db->sql_fetchrowset($result);

				$this->db->sql_freeresult($result);

				// Check if a tip has been made before
				if ($tipp_active > 0)
				{
					$tipp_button		= $this->language->lang('FORMEL_EDIT_TIPP');
					$tipp_button_name	= 'edit_my_tipp';
					$delete_button		= true;
					$tipp_array			= explode(",", $tipp_data['0']['tip_result']);
					$user_tipp_points	= $tipp_data['0']['tip_points'];
					$driver_fastest		= '';
					$tired_cars			= '';
					$safetycars			= '';

					for ($i = 0; $i < count($tipp_array) - 3; ++$i)
					{
						$results				= explode(",", $races[$chosen_race]['race_result']);
						$position				= ($i == 0) ? $this->language->lang('FORMEL_RACE_WINNER') : $i + 1 . '. ' . $this->language->lang('FORMEL_PLACE');
						$box_name				= 'place' . ($i + 1);
						$single_points			= 0;
						$drivername				= '';
						$option_list_driver		= '';
						$option_list_pace		= '';
						$option_list_tired		= '';
						$option_list_safetycar	= '';

						if ($races[$chosen_race]['race_time'] - $this->config['drdeath_f1webtip_deadline_offset'] < $current_time)
						{
							//Actual race is over
							$driverid			= $drivers[$tipp_array[$i]]['driver_id']		??	'';
							$drivername			= $drivers[$tipp_array[$i]]['driver_name']		??	'';
							$driverteamname		= $drivers[$tipp_array[$i]]['driver_team_name']	??	'';
							$gfxdrivercar		= $drivers[$tipp_array[$i]]['driver_car']		??	'';
							$gfxdrivercombo		= $drivers[$tipp_array[$i]]['driver_img']		??	'';

							//Recalc tip points for every single placed tip
							if (isset($results[$i]))
							{
								if ($driverid == $results[$i])
								{
									$single_points += $this->config['drdeath_f1webtip_points_placed'];
								}
							}

							for ($j = 0; $j < count($tipp_array) - 3; ++$j)
							{
								if (isset($results[$j]))
								{
									if ($driverid == $results[$j])
									{
										$single_points += $this->config['drdeath_f1webtip_points_mentioned'];
									}
								}
							}
							// End recalc
						}
						else
						{
							//Actual race is not over

							for ($k = 0; $k < count($driver_combodata); ++$k)
							{
								$this_driver_id		 = $driver_combodata[$k]['driver_id'];
								$this_driver_name	 = $driver_combodata[$k]['driver_name'];
								$selected			 = ($this_driver_id == $tipp_array[$i]) ? 'selected' : '';
								$option_list_driver	.= '<option value="' . $this_driver_id . '" ' . $selected . '>' . $this_driver_name . '</option>';
							}
						}

						if ($single_points == 0)
						{
							$single_points='';
						}

						if ($this->config['drdeath_f1webtip_show_gfx'] == 1)
						{
							//Layout cosmetic
							if ($races[$chosen_race]['race_time'] - $this->config['drdeath_f1webtip_deadline_offset'] < $current_time)
							{
								//Race is over - Show driverimage and so on
								$this->template->assign_block_vars('gfx_users_tipp', [
									'DRIVERNAME'			=>	$drivername,
									'DRIVERTEAMNAME'		=>	$driverteamname,
									'EXT_PATH'				=>	$ext_path,
									'GFXDRIVERCOMBO_HEIGHT'	=>	$this->config['drdeath_f1webtip_driver_img_height'],
									'GFXDRIVERCOMBO_WIDTH'	=>	$this->config['drdeath_f1webtip_driver_img_width'],
									'GFXDRIVERCOMBO'		=>	$gfxdrivercombo,
									'GXFDRIVERCAR'			=>	$gfxdrivercar,
									'L_PLACE'				=>	$position,
									'S_RACE_OVER'			=>	true,
									'SINGLE_POINTS'			=>	$single_points,
									]
								);
							}
							else
							{
								// Race is not over - Show position instead of driverimage
								$this->template->assign_block_vars('gfx_users_tipp', [
									'BOX_NAME'				=>	$box_name,
									'DRIVERTEAMNAME'		=>	$driverteamname,
									'EXT_PATH'				=>	$ext_path,
									'GFXDRIVERCOMBO'		=>	$position,
									'GXFDRIVERCAR'			=>	$gfxdrivercar,
									'OPTION_LIST_DRIVER'	=>	$option_list_driver,
									'S_RACE_OVER'			=>	false,
									'SINGLE_POINTS'			=>	$single_points,
									]
								);
							}
						}
						else
						{
							// Simple layout without images
							$race_over = false;

							if ($races[$chosen_race]['race_time'] - $this->config['drdeath_f1webtip_deadline_offset'] < $current_time)
							{
								$race_over = true;
							}

							$this->template->assign_block_vars('users_tipp', [
								'BOX_NAME'				=>	$box_name,
								'DRIVERNAME'			=>	$drivername,
								'L_PLACE'				=>	$position,
								'OPTION_LIST_DRIVER'	=>	$option_list_driver,
								'S_RACE_OVER'			=>	$race_over,
								'SINGLE_POINTS'			=>	$single_points,
								]
							);
						}
					}

					if ($races[$chosen_race]['race_time'] - $this->config['drdeath_f1webtip_deadline_offset'] < $current_time)
					{
						//Actual Race is over
						$race_over 			= true;
						$single_fastest		= '';
						$single_tired		= '';
						$single_safety_car 	= '';

						$driver_fastest	= $drivers[$tipp_array['10']]['driver_name']	?? '';
						$tired_cars		= $tipp_array['11']								?? '';
						$safetycars		= $tipp_array['12']								?? '';

						//Recalc tip points for fastest driver
						if (isset($results['10']) && $results['10'] != 0)
						{
							if ($tipp_array['10'] == $results['10'])
							{
								$single_fastest = $this->config['drdeath_f1webtip_points_fastest'];
							}
						}

						//Recalc tip points for tired count
						if (isset($results['11']))
						{
							if ($tipp_array['11'] == $results['11'])
							{
								$single_tired = $this->config['drdeath_f1webtip_points_tired'];
							}
						}

						//Recalc tip points for correct count of safety car deployments
						if (isset($results['12']))
						{
							if ($tipp_array['12'] == $results['12'])
							{
								$single_safety_car = $this->config['drdeath_f1webtip_points_safety_car'];
							}
						}
					}
					else
					{
						//Actual Race is not over
						$race_over = false;

						//Fastest Driver DropDown
						for ($k = 0; $k < count($driver_combodata); ++$k)
						{
							$this_driver_id		 = $driver_combodata[$k]['driver_id'];
							$this_driver_name	 = $driver_combodata[$k]['driver_name'];
							$selected			 = ($this_driver_id == $tipp_array['10']) ? 'selected' : '';
							$option_list_pace	.= '<option value="' . $this_driver_id . '" ' . $selected .'>' . $this_driver_name . '</option>';
						}

						//Count Tired DropDown
						//We have 10 Teams with 2 cars each --> 20 drivers
						for ($k = 0; $k < 21; ++$k)
						{
							$selected			 = ($k == $tipp_array['11']) ? 'selected' : '';
							$option_list_tired	.= '<option value="' . $k . '" ' . $selected . '>' . $k . '</option>';
						}

						//Count Safety Car Deployments DropDown
						//We assume to have no more then 10 safety car placed in a normal race ;-)
						for ($k = 0; $k < 11; ++$k)
						{
							$selected			 	 = ( $k == $tipp_array['12']) ? 'selected' : '';
							$option_list_safetycar	.= '<option value="' . $k . '" ' . $selected . '>' . $k . '</option>';
						}
					}

					$this->template->assign_block_vars(($this->config['drdeath_f1webtip_show_gfx'] == 1) ? 'extended_users_tipp_gfx' : 'extended_users_tipp', [
						'DRIVER_FASTEST'		=> $driver_fastest,
						'GFXDRIVERCOMBO'		=> $gfxdrivercombo,
						'OPTION_LIST_PACE' 		=> $option_list_pace,
						'OPTION_LIST_SAFETYCAR'	=> $option_list_safetycar,
						'OPTION_LIST_TIRED' 	=> $option_list_tired,
						'S_RACE_OVER'			=> $race_over,
						'SAFETYCARS'			=> $safetycars,
						'SINGLE_FASTEST'		=> $single_fastest,
						'SINGLE_SAFETY_CAR'		=> $single_safety_car,
						'SINGLE_TIRED'			=> $single_tired,
						'TIRED_CARS'			=> $tired_cars,
						]
					);
				}

				// What to do if the user has no tip so far
				else
				{
					//Guests are not allowed to place a tip.
					if ($this->user->data['is_registered'])
					{
						if ($races[$chosen_race]['race_time'] - $this->config['drdeath_f1webtip_deadline_offset'] > $current_time)
						{
							//Actual Race is not over
							for ($i = 0; $i < 10; ++$i)
							{
								$position = ($i == 0) ? $this->language->lang('FORMEL_RACE_WINNER') : $i + 1 . '. ' . $this->language->lang('FORMEL_PLACE');
								$box_name = 'place' . ($i + 1);
								$option_list_driver = '';

								for ($k = 0; $k < count($driver_combodata); ++$k)
								{
									$this_driver_id		 = $driver_combodata[$k]['driver_id'];
									$this_driver_name	 = $driver_combodata[$k]['driver_name'];
									$option_list_driver	.= '<option value="' . $this_driver_id . '">' . $this_driver_name . '</option>';
								}

								$this->template->assign_block_vars('add_tipps', [
									'BOX_NAME'				=> $box_name,
									'L_PLACE'				=> $position,
									'OPTION_LIST_DRIVER'	=> $option_list_driver,
									]
								);
							}

							//Fastest Driver DropDown
							$option_list_pace = '';

							for ($k = 0; $k < count($driver_combodata); ++$k)
							{
								$this_driver_id		 = $driver_combodata[$k]['driver_id'];
								$this_driver_name	 = $driver_combodata[$k]['driver_name'];
								$option_list_pace	.= '<option value="' . $this_driver_id . '">' . $this_driver_name . '</option>';
							}

							//Count Tired DropDown
							$option_list_tired = '';

							//We have 10 Teams with 2 cars each --> 20 drivers
							for ($k = 0; $k < 21; ++$k)
							{
								$option_list_tired .= '<option value="' . $k . '">' . $k . '</option>';
							}

							//Count Safety Car Deployments DropDown
							$option_list_safetycar = '';

							//We assume to have no more then 10 safety car placed in a normal race ;-)
							for ($k = 0; $k < 11; ++$k)
							{
								$option_list_safetycar .= '<option value="' . $k . '">' . $k . '</option>';
							}

							$this->template->assign_block_vars('extended_add_tipps', [
								'OPTION_LIST_PACE' 		=> $option_list_pace,
								'OPTION_LIST_SAFETYCAR'	=> $option_list_safetycar,
								'OPTION_LIST_TIRED' 	=> $option_list_tired,
								'S_GUEST'				=> false,
								]
							);
						}
					}
					else
					{
						$this->template->assign_block_vars('add_tipps', [
							'S_GUEST'			=> true,
							]
						);
					}
				}

				// Checks for a saved quali
				if ($races[$chosen_race]['race_quali'] != '0')
				{
					// Get the driver ids
					$quali = explode(",", $races[$chosen_race]['race_quali']);

					// Start output
					for ($j = 0; $j < count($quali); ++$j)
					{
						$current_driver_id = $quali[$j];
						$position = ($j == 0) ? $this->language->lang('FORMEL_POLE') . ': ' : $j + 1 . '. ' . $this->language->lang('FORMEL_PLACE') . ': ';

						$this->template->assign_block_vars(($this->config['drdeath_f1webtip_show_gfx'] == 1) ? 'qualirows_gfx' : 'qualirows', [
							'DRIVERCAR'			=> $drivers[$current_driver_id]['driver_car'] 			?? '',
							'DRIVERIMG_HEIGHT'	=> $this->config['drdeath_f1webtip_driver_img_height'],
							'DRIVERIMG_WIDTH'	=> $this->config['drdeath_f1webtip_driver_img_width'],
							'DRIVERIMG'			=> $drivers[$current_driver_id]['driver_img'] 			?? '',
							'DRIVERNAME'		=> $drivers[$current_driver_id]['driver_name'] 			?? '',
							'DRIVERTEAMNAME'	=> $drivers[$current_driver_id]['driver_team_name']		?? '',
							'EXT_PATH'			=> $ext_path,
							'L_PLACE'			=> $position,
							]
						);
					}
				}
				else
				{
					// If no quali was found
					$this->template->assign_block_vars('no_qualifyings', []);
				}

				// Checks for a saved result
				if ($races[$chosen_race]['race_result'] != '0')
				{
					// Get the driver ids
					$results = explode(",", $races[$chosen_race]['race_result']);

					// Start output
					for ($j = 0; $j < count($results) - 3; ++$j)
					{
						$current_driver_id = $results[$j];
						$position = ($j == 0) ? $this->language->lang('FORMEL_RACE_WINNER') . ': ' : $j + 1 . '. ' . $this->language->lang('FORMEL_PLACE') . ': ';

						$this->template->assign_block_vars(($this->config['drdeath_f1webtip_show_gfx'] == 1) ? 'resultsrow_gfx' : 'resultsrow', [
							'DRIVERCAR'			=> $drivers[$current_driver_id]['driver_car'] 			?? '',
							'DRIVERIMG_HEIGHT'	=> $this->config['drdeath_f1webtip_driver_img_height'],
							'DRIVERIMG_WIDTH'	=> $this->config['drdeath_f1webtip_driver_img_width'],
							'DRIVERIMG'			=> $drivers[$current_driver_id]['driver_img'] 			?? '',
							'DRIVERNAME'		=> $drivers[$current_driver_id]['driver_name'] 			?? '',
							'DRIVERTEAMNAME'	=> $drivers[$current_driver_id]['driver_team_name'] 	?? '',
							'EXT_PATH'			=> $ext_path,
							'L_PLACE'			=> $position,
							]
						);
					}

					$this->template->assign_block_vars(($this->config['drdeath_f1webtip_show_gfx'] == 1) ? 'extended_results_gfx' : 'extended_results', [
						'PACE'				=> $drivers[$results['10']]['driver_name'] 	?? '',
						'SAFETYCAR'			=> $results['12'] 							?? '',
						'TIRED'				=> $results['11'] 							?? '',
						'YOUR_POINTS'		=> $user_tipp_points,
						]
					);

					// tell the responsive style that we have a result, we can now switch from showing race qualification to race result
					$this->template->assign_vars([
							'S_RESULT_EXISTS'	=> true,
							]
						);
				}
				else
				{
					// If no result was found
					$this->template->assign_block_vars('no_results', []);
				}

				// Game over
				if ($races[$chosen_race]['race_time'] - $this->config['drdeath_f1webtip_deadline_offset'] < $current_time)
				{
					$this->template->assign_block_vars('games_over', []);
				}
				else
				{
					//Check if it is a registered user. Guests are not allowed to place, edit or delete a tip.
					if ($this->user->data['is_registered'])
					{
						$this->template->assign_block_vars('place_tipps', [
							'DELETE_TIPP'	=> $delete_button,
							'L_PLACE_TIPP'	=> $tipp_button,
							'PLACE_TIPP'	=> $tipp_button_name,
							]
						);
					}
				}

				// Moderator switch and options
				$u_call_mod = append_sid($this->root_path . "ucp." . $this->php_ext . "?i=pm&amp;mode=compose&amp;u=$formel_mod_id");
				$l_call_mod = $this->language->lang('FORMEL_CALL_MOD');

				//Check if user is formel moderator or has admin access
				if ($user_id == $formel_mod_id || ($is_admin == 1))
				{
					$u_call_mod = $this->helper->route('drdeath_f1webtip_controller', ['name' => 'results']);
					$l_call_mod = $this->language->lang('FORMEL_MOD_BUTTON_TEXT');

					$this->template->assign_block_vars('tipp_moderator', []);
				}

				// Show headerbanner ?
				if ($this->config['drdeath_f1webtip_show_headbanner'])
				{
					$this->template->assign_block_vars('heads_on', []);
				}

				$this->template->assign_vars([
					'COUNTDOWN_STOP'					=> $countdown_stop,
					'EXT_PATH_IMAGES'					=> $ext_path . 'images/',
					'HEADER_HEIGHT' 					=> $this->config['drdeath_f1webtip_head_height'],
					'HEADER_IMG' 						=> $ext_path . 'images/banners/' . $this->config['drdeath_f1webtip_headbanner1_img'],
					'HEADER_WIDTH' 						=> $this->config['drdeath_f1webtip_head_width'],
					'L_FORMEL_CALL_MOD'					=> $l_call_mod,
					'PLACES'							=> $places,
					'RACE_ID'							=> $races[$chosen_race]['race_id']   ?? 1,
					'RACE_OFFSET'						=> $race_offset,
					'RACE_TIME'							=> $races[$chosen_race]['race_time'] ?? 1,
					'RACENAME'							=> $races[$chosen_race]['race_name'] ?? '',
					'S_CALL_MOD'						=> ($this->user->data['is_registered'] == 1) ? true : false,
					'S_COUNTDOWN'						=> ($this->config['drdeath_f1webtip_show_countdown'] == 1) ? true : false,
					'S_DISCUSS_BUTTON'					=> ($formel_forum_id == 0) ? false : true,
					'S_FORM_ACTION'						=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']),
					'S_INDEX'							=> true,
					'U_FORMEL_CALL_MOD'					=> $u_call_mod,
					'U_FORMEL_FORUM'					=> append_sid($this->root_path . "viewforum." . $this->php_ext . "?f=$formel_forum_id"),
					'U_FORMEL_RULES' 					=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'rules']),
					'U_FORMEL_STATISTICS'				=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'stats']),
					'U_TOP_MORE_DRIVERS'				=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'stats', 'mode' => 'drivers']),
					'U_TOP_MORE_TEAMS'					=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'stats', 'mode' => 'teams']),
					'U_TOP_MORE_USERS'					=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'stats', 'mode' => 'users']),
				]);

			break;
		}

		page_header($page_title);
		return $this->helper->render('f1webtip_body.html', $name);
	}
}
