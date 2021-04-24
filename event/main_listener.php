<?php
/**
 *
 * Formula 1 WebTip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2014, Dr.Death, http://www.lpi-clan.de
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace drdeath\f1webtip\event;

/**
 * @ignore
 */
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class main_listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return [
			'core.user_setup'							=> 'load_language_on_setup',
			'core.page_header'							=> 'add_page_header_link',
			'core.memberlist_prepare_profile_data'		=> 'prepare_f1webtip_stats',
			'core.viewtopic_modify_post_row'			=> 'modify_f1webtip_post_row',
			'core.viewonline_overwrite_location'		=> 'add_page_viewonline',
			'core.permissions'							=> 'add_formel_permission',
		];
	}

	/* @var string phpEx */
	protected $php_ext;

	/* @var Container */
	protected $phpbb_container;

	/* @var \phpbb\db\driver\driver_interface */
	protected $db;

	/* @var \phpbb\config\config */
	protected $config;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\auth\auth */
	protected $auth;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/* @var \phpbb\language\language */
	protected $language;

	/**
	* Constructor
	*
	* @param string									$php_ext
	* @param Container								$phpbb_container
	* @param \phpbb\db\driver\driver_interfacer		$db
	* @param \phpbb\config\config					$config
	* @param \phpbb\controller\helper				$helper		Controller helper object
	* @param \phpbb\auth\auth						$auth
	* @param \phpbb\template						$template	Template object
	* @param \phpbb\user							$user
	* @param \phpbb\language\language				$language
	*/
	public function __construct
	(
		$php_ext,
		Container $phpbb_container,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\config\config $config,
		\phpbb\controller\helper $helper,
		\phpbb\auth\auth $auth,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\language\language $language
	)
	{
		$this->php_ext 			= $php_ext;
		$this->phpbb_container 	= $phpbb_container;
		$this->db 				= $db;
		$this->config 			= $config;
		$this->helper 			= $helper;
		$this->auth				= $auth;
		$this->template 		= $template;
		$this->user 			= $user;
		$this->language 		= $language;
	}

	/**
	 * Load the drdeath f1webtip language file
	 * drdeath/f1webtip/language/xx/common.php
	 *
	 * @param \phpbb\event\data $event The event object
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'drdeath/f1webtip',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function add_page_header_link()
	{
		// Should the user be able to see the link to the f1webtip ?
		// Link should only visible if:
		// f1webtip is not restricted to a group and guests viewing is allowed and user is not a bot --> or
		// user is logged in
		// if user logged in and not member of restriced group then he could ask some one to let him in or not
		$formel_link	= 	(($this->config['drdeath_f1webtip_restrict_to'] == 0 && $this->config['drdeath_f1webtip_guest_viewing'] == '1' && !$this->user->data['is_bot']) || $this->user->data['is_registered']) ? true : false;

		$this->template->assign_vars([
			'S_F1WEBTIP_PAGE_LINK_ENABLED'	=> $formel_link,
			'U_F1WEBTIP_PAGE'				=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']),
			]
		);

	}

	public function add_page_viewonline($event)
	{
		if (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/f1webtip') === 0)
		{
			$event['location'] = $this->language->lang('VIEWING_F1WEBTIPP');
			$event['location_url'] = $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']);
		}
	}

	/**
	* Add administrative permissions to manage f1webtip
	*
	* @param \phpbb\event\data $event The event object
	* @return void
	* @access public
	*/
	public function add_formel_permission($event)
	{
		$permissions						= $event['permissions'];
		$categories							= $event['categories'];

		$categories['f1webtip']				= 'ACL_CAT_FORMEL';

		$permissions['a_formel_races']		= ['lang' => 'ACL_A_FORMEL_RACES',		'cat' => 'f1webtip'];
		$permissions['a_formel_teams']		= ['lang' => 'ACL_A_FORMEL_TEAMS',		'cat' => 'f1webtip'];
		$permissions['a_formel_drivers']	= ['lang' => 'ACL_A_FORMEL_DRIVERS',	'cat' => 'f1webtip'];
		$permissions['a_formel_settings']	= ['lang' => 'ACL_A_FORMEL_SETTINGS',	'cat' => 'f1webtip'];

		$event['categories']				= $categories;
		$event['permissions']				= $permissions;
	}


	public function prepare_f1webtip_stats($event)
	{
		if ($this->config['drdeath_f1webtip_show_in_profile'])
		{
			//Is the displayed user on the memberlist member of the restricted group?
			$is_in_group = group_memberships($this->config['drdeath_f1webtip_restrict_to'], $event['data']['user_id'], true);

			if ($this->config['drdeath_f1webtip_restrict_to'] == 0 || $is_in_group)
			{
				$tippers_rank		= $this->language->lang('FORMEL_PROFILE_NORANK');
				$tippers_points		= 0;
				$race_done			= 0;

				$table_tips		= $this->phpbb_container->getParameter('tables.f1webtip.tips');
				$table_races	= $this->phpbb_container->getParameter('tables.f1webtip.races');

				// Get tip data for this user
				$sql = 'SELECT *, sum(tip_points) as total_points, count(tip_points) as tips_made
					FROM ' . $table_tips . '
					GROUP BY tip_user
					ORDER BY total_points DESC';
				$result = $this->db->sql_query($sql);

				$rank_count = $real_rank  = 1;
				$previous_points = false;

				while ($row = $this->db->sql_fetchrow($result))
				{
					if ($row['total_points'] != $previous_points)
					{
						$rank_count = $real_rank;
						$previous_points = $row['total_points'];
					}

					if ($row['tip_user'] == $event['data']['user_id'])
					{
						$tippers_points	= $row['total_points'];
						$race_done		= $row['tips_made'];
						$tippers_rank	= $this->language->lang('FORMEL_PROFILE_RANK', $rank_count);
						break;
					}
					$real_rank++;
				}

				$this->db->sql_freeresult($result);

				// Count total races with existing results
				$sql = 'SELECT *
					FROM ' . $table_races . '
					WHERE race_result <> 0';
				$result = $this->db->sql_query($sql);

				$race_total = $this->db->sql_affectedrows($result);
				$this->db->sql_freeresult($result);

				$this->template->assign_block_vars('f1webtips', [
					'TIPPER_POINTS'		=> $tippers_points,
					'TIPPER_RANK'		=> $tippers_rank,
					'RACE_DONE'			=> $this->language->lang('FORMEL_PROFILE_TIPSS', $race_done, $race_total),
					'U_FORMEL_STATS'	=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'stats']),
					]
				);
			}
		}
	}

	public function modify_f1webtip_post_row($event)
	{
		if ($this->config['drdeath_f1webtip_show_in_viewtopic'])
		{
			//Is the poster member of the restricted group?
			$is_in_group = group_memberships($this->config['drdeath_f1webtip_restrict_to'], $event['post_row']['POSTER_ID'], true);

			if ($this->config['drdeath_f1webtip_restrict_to'] == 0 || $is_in_group)
			{
				$tippers_rank	= $this->language->lang('FORMEL_PROFILE_NORANK');
				$tippers_points	= 0;
				$race_done		= 0;

				$table_tips		= $this->phpbb_container->getParameter('tables.f1webtip.tips');
				$table_races	= $this->phpbb_container->getParameter('tables.f1webtip.races');

				// Get tipp data for this user
				$sql = 'SELECT *, sum(tip_points) as total_points, count(tip_points) as tips_made
					FROM ' . $table_tips . '
					GROUP BY tip_user
					ORDER BY total_points DESC';
				$result = $this->db->sql_query($sql);

				$rank_count = $real_rank  = 1;
				$previous_points = false;

				while ($row_f1 = $this->db->sql_fetchrow($result))
				{
					if ($row_f1['total_points'] != $previous_points)
					{
						$rank_count = $real_rank;
						$previous_points = $row_f1['total_points'];
					}

					if ($row_f1['tip_user'] == $event['post_row']['POSTER_ID'])
					{
						$tippers_points	= $row_f1['total_points'];
						$race_done		= $row_f1['tips_made'];
						$tippers_rank	= $this->language->lang('FORMEL_PROFILE_RANK', $rank_count);
						break;
					}

					$real_rank++;
				}

				$this->db->sql_freeresult($result);

				// Count total races with existing results
				$sql = 'SELECT *
					FROM ' . $table_races . '
					WHERE race_result <> 0';
				$result = $this->db->sql_query($sql);

				$race_total = $this->db->sql_affectedrows($result);
				$this->db->sql_freeresult($result);

				$f1webtip = [
					'TIPPER_POINTS'		=> $tippers_points,
					'TIPPER_RANK'		=> $tippers_rank,
					'RACE_DONE'			=> $this->language->lang('FORMEL_PROFILE_TIPSS', $race_done, $race_total),
					'U_FORMEL_STATS'	=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'stats']),
					'U_FORMEL_WEB_TIPP'	=> $this->helper->route('drdeath_f1webtip_controller', ['name' => 'index']),
				];

				// Add the new vars to the post_row array
				$f1webtip_array = array_merge($event['post_row'], $f1webtip);

				$event['post_row'] = $f1webtip_array;
			}
		}
	}
}
