<?php
/**
*
* @package phpBB Extension - DrDeath F1WebTip
* @copyright (c) 2014 Dr.Death - www.lpi-clan.de
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'							=> 'load_language_on_setup',
			'core.page_header'							=> 'add_page_header_link',
			'core.memberlist_prepare_profile_data'		=> 'prepare_f1webtip_stats',
			'core.viewtopic_modify_post_row'			=> 'modify_f1webtip_post_row',
			'core.viewonline_overwrite_location'		=> 'add_page_viewonline',
		);
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
	public function __construct($php_ext, Container $phpbb_container, \phpbb\db\driver\driver_interface $db, \phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\auth\auth $auth, \phpbb\template\template $template, \phpbb\user $user, \phpbb\language\language $language)
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

	public function load_language_on_setup($event)
	{
		$lang_set_ext 		= $event['lang_set_ext'];
		$lang_set_ext[] 	= array(
			'ext_name' 		=> 'drdeath/f1webtip',
			'lang_set' 		=> 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function add_page_header_link($event)
	{
		$this->template->assign_vars(array(
			'U_F1WEBTIP_PAGE'	=> $this->helper->route('drdeath_f1webtip_controller', array('name' => 'index')),
		));
	}

	public function add_page_viewonline($event)
	{
		if (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/f1webtip') === 0)
		{
			$event['location'] = $this->language->lang('VIEWING_F1WEBTIPP');
			$event['location_url'] = $this->helper->route('drdeath_f1webtip_controller', array('name' => 'index'));
		}
	}

	public function prepare_f1webtip_stats($event)
	{
		if ($this->config['drdeath_f1webtip_show_in_profile'])
		{
			// Check if this user has one of the formular 1 admin permission. If this user has one or more of these permissions, he gets also moderator permissions.
			$is_admin = $this->auth->acl_gets('a_formel_settings', 'a_formel_drivers', 'a_formel_teams', 'a_formel_races');

			//Is the user member of the restricted group?
			$is_in_group = group_memberships($this->config['drdeath_f1webtip_restrict_to'], $this->user->data['user_id'], true);

			if ($this->config['drdeath_f1webtip_restrict_to'] == 0 || $is_in_group || $is_admin == 1 || $this->user->data['user_id'] == $this->config['drdeath_f1webtip_mod_id'])
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
						$tippers_rank	= sprintf($this->language->lang('FORMEL_PROFILE_RANK'), $rank_count);
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

				$this->template->assign_block_vars('f1webtip', array(
					'TIPPER_POINTS'		=> $tippers_points,
					'TIPPER_RANK'		=> $tippers_rank,
					'RACE_DONE'			=> sprintf($this->language->lang('FORMEL_PROFILE_TIPSS'), $race_done, $race_total),
					'U_FORMEL_STATS'	=> $this->helper->route('drdeath_f1webtip_controller', array('name' => 'stats')),
				));
			}
		}
	}

	public function modify_f1webtip_post_row($event)
	{
		if ($this->config['drdeath_f1webtip_show_in_viewtopic'])
		{
			// Check if this user has one of the formular 1 admin permission. If this user has one or more of these permissions, he gets also moderator permissions.
			$is_admin = $this->auth->acl_gets('a_formel_settings', 'a_formel_drivers', 'a_formel_teams', 'a_formel_races');

			//Is the user member of the restricted group?
			$is_in_group = group_memberships($this->config['drdeath_f1webtip_restrict_to'], $this->user->data['user_id'], true);

			if ($this->config['drdeath_f1webtip_restrict_to'] == 0 || $is_in_group || $is_admin == 1 || $this->user->data['user_id'] == $this->config['drdeath_f1webtip_mod_id'])
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
						$tippers_rank	= sprintf($this->language->lang('FORMEL_PROFILE_RANK'), $rank_count);
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

				$f1webtip = array(
					'TIPPER_POINTS'		=> $tippers_points,
					'TIPPER_RANK'		=> $tippers_rank,
					'RACE_DONE'			=> sprintf($this->language->lang('FORMEL_PROFILE_TIPSS'), $race_done, $race_total),
					'U_FORMEL_STATS'	=> $this->helper->route('drdeath_f1webtip_controller', array('name' => 'stats')),
					'U_FORMEL_WEB_TIPP'	=> $this->helper->route('drdeath_f1webtip_controller', array('name' => 'index')),
				);

				// Add the new vars to the post_row array
				$f1webtip_array = array_merge($event['post_row'], $f1webtip);

				$event['post_row'] = $f1webtip_array;
			}
		}
	}
}
