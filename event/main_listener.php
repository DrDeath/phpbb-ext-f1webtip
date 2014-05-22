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

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper	$helper		Controller helper object
	* @param \phpbb\template			$template	Template object
	*/
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template)
	{
		$this->helper = $helper;
		$this->template = $template;
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
			'U_F1WEBTIP_PAGE'	=> $this->helper->route('f1webtip_controller', array('name' => 'index')),
		));
	}

	public function add_page_viewonline($event)
	{
		global $user, $phpbb_container, $phpEx;

		if (strrpos($event['row']['session_page'], 'app.' . $phpEx . '/f1webtip') === 0)
		{
			$event['location'] = $user->lang('VIEWING_F1WEBTIPP');
			$event['location_url'] = $this->helper->route('f1webtip_controller', array('name' => 'index'));
		}
	}

	public function prepare_f1webtip_stats($event)
	{
		global $db, $auth, $config, $template, $phpbb_container, $user, $member;

		if ($config['drdeath_f1webtip_show_in_profile'])
		{
			// Check if this user has one of the formular 1 admin permission. If this user has one or more of these permissions, he gets also moderator permissions.
			$is_admin = $auth->acl_gets('a_formel_settings', 'a_formel_drivers', 'a_formel_teams', 'a_formel_races');

			//Is the user member of the restricted group?
			$is_in_group = group_memberships($config['drdeath_f1webtip_restrict_to'], $user->data['user_id'], true);
			
			if ($config['drdeath_f1webtip_restrict_to'] == 0 || $is_in_group || $is_admin == 1 || $user->data['user_id'] == $config['drdeath_f1webtip_mod_id'])
			{
				$tippers_rank		= $user->lang['FORMEL_PROFILE_NORANK'];
				$tippers_points		= 0;
				$race_done			= 0;

				$table_tips		= $phpbb_container->getParameter('tables.f1webtip.tips');
				$table_races	= $phpbb_container->getParameter('tables.f1webtip.races');
				
				// Get tip data for this user
				$sql = 'SELECT *, sum(tip_points) as total_points, count(tip_points) as tips_made
					FROM ' . $table_tips . '
					GROUP BY tip_user
					ORDER BY total_points DESC';
				$result = $db->sql_query($sql);

				$rank_count = $real_rank  = 1;
				$previous_points = false;

				while ($row = $db->sql_fetchrow($result))
				{
					if ($row['total_points'] != $previous_points)
					{
						$rank_count = $real_rank;
						$previous_points = $row['total_points'];
					}

					if ($row['tip_user'] == $member['user_id'])
					{
						$tippers_points	= $row['total_points'];
						$race_done		= $row['tips_made'];
						$tippers_rank	= sprintf($user->lang['FORMEL_PROFILE_RANK'], $rank_count);
						break;
					}
					$real_rank++;
				}

				$db->sql_freeresult($result);

				// Count total races with existing results
				$sql = 'SELECT *
					FROM ' . $table_races . '
					WHERE race_result <> 0';
				$result = $db->sql_query($sql);

				$race_total = $db->sql_affectedrows($result);
				$db->sql_freeresult($result);

				$template->assign_block_vars('f1webtip', array(
					'TIPPER_POINTS'		=> $tippers_points,
					'TIPPER_RANK'		=> $tippers_rank,
					'RACE_DONE'			=> sprintf($user->lang['FORMEL_PROFILE_TIPSS'], $race_done, $race_total),
					'U_FORMEL_STATS'	=> $this->helper->route('f1webtip_controller', array('name' => 'stats')),
				));
			}
		}
	}

	public function modify_f1webtip_post_row($event)
	{
		global $db, $auth, $config, $template, $phpbb_container, $user, $poster_id;
		
		if ($config['drdeath_f1webtip_show_in_viewtopic'])
		{

			// Check if this user has one of the formular 1 admin permission. If this user has one or more of these permissions, he gets also moderator permissions.
			$is_admin = $auth->acl_gets('a_formel_settings', 'a_formel_drivers', 'a_formel_teams', 'a_formel_races');

			//Is the user member of the restricted group?
			$is_in_group = group_memberships($config['drdeath_f1webtip_restrict_to'], $user->data['user_id'], true);

			if ($config['drdeath_f1webtip_restrict_to'] == 0 || $is_in_group || $is_admin == 1 || $user->data['user_id'] == $config['drdeath_f1webtip_mod_id'])
			{
				$tippers_rank	= $user->lang['FORMEL_PROFILE_NORANK'];
				$tippers_points	= 0;
				$race_done		= 0;
				
				$table_tips		= $phpbb_container->getParameter('tables.f1webtip.tips');
				$table_races	= $phpbb_container->getParameter('tables.f1webtip.races');

				// Get tipp data for this user
				$sql = 'SELECT *, sum(tip_points) as total_points, count(tip_points) as tips_made
					FROM ' . $table_tips . '
					GROUP BY tip_user
					ORDER BY total_points DESC';
				$result = $db->sql_query($sql);

				$rank_count = $real_rank  = 1;
				$previous_points = false;

				while ($row_f1 = $db->sql_fetchrow($result))
				{
					if ($row_f1['total_points'] != $previous_points)
					{
						$rank_count = $real_rank;
						$previous_points = $row_f1['total_points'];
					}

					if ($row_f1['tip_user'] == $poster_id)
					{
						$tippers_points	= $row_f1['total_points'];
						$race_done		= $row_f1['tips_made'];
						$tippers_rank	= sprintf($user->lang['FORMEL_PROFILE_RANK'], $rank_count);
						break;
					}

					$real_rank++;
				}

				$db->sql_freeresult($result);

				// Count total races with existing results
				$sql = 'SELECT *
					FROM ' . $table_races . '
					WHERE race_result <> 0';
				$result = $db->sql_query($sql);

				$race_total = $db->sql_affectedrows($result);
				$db->sql_freeresult($result);
				

				$f1webtip = array(
					'TIPPER_POINTS'		=> $tippers_points,
					'TIPPER_RANK'		=> $tippers_rank,
					'RACE_DONE'			=> sprintf($user->lang['FORMEL_PROFILE_TIPSS'], $race_done, $race_total),
					'U_FORMEL_STATS'	=> $this->helper->route('f1webtip_controller', array('name' => 'stats')),
					'U_FORMEL_WEB_TIPP'	=> $this->helper->route('f1webtip_controller', array('name' => 'index')),
				);

				// Add the new vars to the post_row array
				$f1webtip_array = array_merge($event['post_row'], $f1webtip);
				
				$event['post_row'] = $f1webtip_array;
			}
		}
	}
}
