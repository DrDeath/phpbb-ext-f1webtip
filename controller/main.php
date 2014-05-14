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
	* f1webtip controller for route /f1webtip/{name}
	*
	* @param string		$name
	* @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function handle($name)
	{
	
		global $db, $user, $auth, $template, $cache, $request;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		global $phpbb_container, $phpbb_extension_manager, $phpbb_path_helper;

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

		switch ($name)
		{
			###########################
			###       RULES        ####
			###########################
			case 'rules':
			
				$page_title = $user->lang['FORMEL_TITLE'];
				
				// Creating breadcrumps index
				$this->template->assign_block_vars('navlinks', array(
					'U_VIEW_FORUM' => $this->helper->route('f1webtip_controller', array('name' => 'index')),
					'FORUM_NAME' => $this->user->lang['F1WEBTIP_PAGE'],
				   ));
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
					'U_ACTION'					=> $this->u_action,
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

				// Creating breadcrumps index
				$this->template->assign_block_vars('navlinks', array(
					'U_VIEW_FORUM' => $this->helper->route('f1webtip_controller', array('name' => 'index')),
					'FORUM_NAME' => $this->user->lang['F1WEBTIP_PAGE'],
				   ));
				// Creating breadcrumps rules
				$this->template->assign_block_vars('navlinks', array(
					'U_VIEW_FORUM' => $this->helper->route('f1webtip_controller', array('name' => 'stats')),
					'FORUM_NAME' => $this->user->lang['FORMEL_STATISTICS'],
				   ));
				   

				// Check buttons & data
				$show_drivers 	= $request->is_set_post('show_drivers');
				$show_teams 	= $request->is_set_post('show_teams');

				// Show teams toplist
				if ($show_teams)
				{
					//todo
					$stat_table_title = $user->lang['FORMEL_TEAM_STATS'];
				}
				// Show drivers toplist
				else if ($show_drivers)
				{
					//todo
					$stat_table_title = $user->lang['FORMEL_DRIVER_STATS'];
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
			###       INDEX        ####
			###########################
			case 'index':	
			default:
		
				$page_title 	= $user->lang['FORMEL_TITLE'];
				
				// Creating breadcrumps
				$this->template->assign_block_vars('navlinks', array(
					'U_VIEW_FORUM' => $this->helper->route('f1webtip_controller', array('name' => 'index')),
					'FORUM_NAME' => $this->user->lang['F1WEBTIP_PAGE'],
				   ));
				
				// Show headerbanner ?
				if ($config['drdeath_f1webtip_show_headbanner'])
				{
					$template->assign_block_vars('head_on', array());
				}
			

				   
				$this->template->assign_vars(array(
					'S_INDEX'							=> true,
					'U_ACTION'							=> $this->u_action,
					'U_FORMEL_RULES' 					=> $this->helper->route('f1webtip_controller', array('name' => 'rules')),
					'U_FORMEL_STATISTICS'				=> $this->helper->route('f1webtip_controller', array('name' => 'stats')),
					'HEADER_IMG' 						=> $ext_path . 'images/' . $config['drdeath_f1webtip_headbanner1_img'],
					'HEADER_HEIGHT' 					=> $config['drdeath_f1webtip_head_height'],
					'HEADER_WIDTH' 						=> $config['drdeath_f1webtip_head_width'],
				
					'EXT_PATH'							=> $ext_path,
					'EXT_PATH_IMAGES'					=> $ext_path . 'images/',
				));
				
			break;
		}
		page_header($page_title);
		return $this->helper->render('f1webtip_body.html', $name);
	}
}
