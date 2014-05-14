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

		switch ($name)
		{
			###########################
			###       RULES        ####
			###########################
			case 'rules':

				// Build rules
				$points_mentioned 	= $this->config['drdeath_f1webtip_points_mentioned'];
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
			###       INDEX        ####
			###########################
			case 'index':	
			default:
		
				// Show headerbanner ?
				if ($config['drdeath_f1webtip_show_headbanner'])
				{
					$template->assign_block_vars('head_on', array());
				}
			
				$this->template->assign_vars(array(
					'S_INDEX'							=> true,
					'U_ACTION'							=> $this->u_action,
					'U_FORMEL_RULES' 					=> $this->helper->route('f1webtip_controller', array('name' => 'rules')),
					'HEADER_IMG' 						=> $ext_path . 'images/' . $config['drdeath_f1webtip_headbanner1_img'],
					'HEADER_HEIGHT' 					=> $config['drdeath_f1webtip_head_height'],
					'HEADER_WIDTH' 						=> $config['drdeath_f1webtip_head_width'],
				
					'EXT_PATH'							=> $ext_path,
					'EXT_PATH_IMAGES'					=> $ext_path . 'images/',
				));
				
			break;
		}

		return $this->helper->render('f1webtip_body.html', $name);
	}
}
