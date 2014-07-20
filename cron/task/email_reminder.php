<?php
/**
*
* @package phpBB Extension - DrDeath F1WebTip
* @copyright (c) 2014 Dr.Death - www.lpi-clan.de
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace drdeath\f1webtip\cron\task;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

class email_reminder extends \phpbb\cron\task\base
{
	/* @var \phpbb\db\driver\driver_interface */
	protected $db;

	/* @var \phpbb\config\config */
	protected $config;

	/* @var \phpbb\user */
	protected $user;

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interfacer		$db
	* @param \phpbb\config\config					$config
	* @param \phpbb\user							$user
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\config\config $config, \phpbb\user $user)
	{
		$this->db 		= $db;
		$this->config 	= $config;
		$this->user 	= $user;
	}

	/**
	* Runs this cron task.
	*
	* @return null
	*/
	public function run()
	{
		global $phpbb_root_path, $phpEx;
		global $phpbb_container, $phpbb_extension_manager, $phpbb_path_helper, $phpbb_log;

		$table_races 	= $phpbb_container->getParameter('tables.f1webtip.races');
		$table_teams	= $phpbb_container->getParameter('tables.f1webtip.teams');
		$table_drivers 	= $phpbb_container->getParameter('tables.f1webtip.drivers');
		$table_wm 		= $phpbb_container->getParameter('tables.f1webtip.wm');
		$table_tips 	= $phpbb_container->getParameter('tables.f1webtip.tips');

		// Load extension language file
		$this->user->add_lang_ext('drdeath/f1webtip', 'common');

		// Update the last run timestamp to today (i.e. 5232014 --> 05/23/2013)
		$check_time = (int) gmdate('mdY',time());
		$this->config->set('drdeath_f1webtip_reminder_last_run', $check_time, true);

		// Debug Start: Reset cron lock
		// $this->config->set('cron_lock', '0');
		// $this->config->set('drdeath_f1webtip_reminder_last_run', '1', true);
		// Debug End

		//Mail Settings
		$use_queue 		= false;
		$used_method 	= NOTIFY_EMAIL;
		$priority 		= MAIL_NORMAL_PRIORITY;

		// Get F1 Webtip restricted group
		$formel_group_id 	= $this->config['drdeath_f1webtip_restrict_to'];

		// Uncomment the next line for sending the reminder mail to a special group. Replace 114 with the special group ID
		// $formel_group_id	= 114;

		// Time slot will be 3 days before the next race starts
		$current_time 		= time();
		$one_day 			= 86400;
		$time_slot 			= $one_day * 3;
		$current_time_slot 	=  $current_time + $time_slot;

		// Get the race which will start within the next 3 days and mail reminder was not sent
		$sql = 'SELECT 		*
				FROM 		' . $table_races . '
				WHERE 		race_time > ' . $current_time . '
					AND		race_time < ' . $current_time_slot . '
					AND		race_mail = 0
				ORDER BY 	race_time ASC';

		$result = $this->db->sql_query($sql);

		$races = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		// If we found the race, get all data and send the mail
		foreach ($races as $race)
		{
			$race_id 		= $race['race_id'];

			// Update the race_mail status
			$sql_update = '	UPDATE  	' . $table_races . '
							SET 		race_mail = 1
							WHERE 		race_id = ' . $race_id ;

			$result_mail = $this->db->sql_query($sql_update);

			// prepare some variables
			$race_name 		= $race['race_name'];
			$race_time		= $race['race_time'];

			// Get the race f1webtipp deadline.
			// Could have problems if your users live in different timezones.
			// In this case, remove the DEADLINETIME variable in email template

			$event_stop			= date($race_time - $this->config['drdeath_f1webtip_deadline_offset']);
			$user_date_format 	= $this->config['default_dateformat'];
			$user_timezone 		= $this->config['board_timezone'];

			$datetime = new \DateTime('now', new \DateTimeZone('UTC'));
			$datetime->setTimestamp($event_stop);
			$datetime->setTimezone(new \DateTimeZone($user_timezone));
			$datetime->format($user_date_format);
			$deadline  = $datetime->format($user_date_format);

			$b_day			= $datetime->format('d');
			$b_month		= $datetime->format('m');
			$b_year			= $datetime->format('Y');
			$b_hour			= $datetime->format('H');
			$b_minute		= $datetime->format('i');

			$deadline_date 	= $b_day . '.' . $b_month . '.' . $b_year;
			$deadline_time	= $b_hour . ':' . $b_minute;

			$subject 		= $this->user->lang['F1WEBTIP_PAGE'] . " - " . $race_name;
			$usernames 		= '';

			include_once($phpbb_root_path . 'includes/functions_messenger.' . $phpEx);
			$messenger = new \messenger($use_queue);

			$errored = false;
			$messenger->headers('X-AntiAbuse: Board servername - ' . $this->config['server_name']);
			$messenger->headers('X-AntiAbuse: User_id - ' . ANONYMOUS);
			$messenger->headers('X-AntiAbuse: Username - CRON TASK F1 WebTip Email Reminder');
			$messenger->subject(htmlspecialchars_decode($subject));
			$messenger->set_mail_priority($priority);

			$ext_path = $phpbb_path_helper->update_web_root_path($phpbb_extension_manager->get_extension_path('drdeath/f1webtip', true));

			// Get all the f1webtipp user (what user exactly ? All member of the restrict_to group and admin mails allowed)
			$sql = 'SELECT 		u.user_id,
								u.username,
								u.user_lang,
								u.user_email
					FROM 		' . USERS_TABLE . ' u , ' . USER_GROUP_TABLE . ' ug
					WHERE 		ug.group_id = ' . $formel_group_id . '
						AND 	u.user_id = ug.user_id
						AND		u.user_allow_massemail = 1
					GROUP BY	u.user_id
					ORDER BY 	u. username_clean ASC';

			$result = $this->db->sql_query($sql);

			while ($row = $this->db->sql_fetchrow($result))
			{
				// Send the messages
				$used_lang = $row['user_lang'];
				$mail_template_path = $ext_path . 'language/' . $used_lang . '/email/';

				$messenger->to($row['user_email'], $row['username']);
				$messenger->template('cron_formel', $used_lang, $mail_template_path);
				$messenger->assign_vars(array(
					'USERNAME'		=> $row['username'],
					'RACENAME'		=> $race_name,
					'DEADLINEDATE'	=> $deadline_date,
					'DEADLINETIME'	=> $deadline_time,
					)
				);

				if (!($messenger->send($used_method)))
				{
					$usernames .= (($usernames != '') ? ', ' : '') . $row['username']. '!';
					$message = sprintf($this->user->lang['FORMEL_LOG_ERROR'], $row['user_email']);
					$phpbb_log->add('critical', ANONYMOUS, '', 'LOG_ERROR_EMAIL', false, array($message));
				}
				else
				{
					$usernames .= (($usernames != '') ? ', ' : '') . $row['username'];
				}

			}

			// Only if some emails have already been sent previously.
			if ($usernames <> '')
			{
				//send admin email
				$used_lang 	= $this->config['default_lang'];
				$subject 	= sprintf($this->user->lang['FORMEL_MAIL_ADMIN'], $race_name);

				$messenger->to($this->config['board_email'], $this->config['sitename']);
				$messenger->subject(htmlspecialchars_decode($subject));
				$messenger->template('admin_send_email', $used_lang);
				$messenger->assign_vars(array(
					'CONTACT_EMAIL' => $this->config['board_contact'],
					'MESSAGE'		=> sprintf($this->user->lang['FORMEL_MAIL_ADMIN_MESSAGE'], $usernames),
					)
				);

				if (!($messenger->send($used_method)))
				{
					$message = sprintf($this->user->lang['FORMEL_LOG_ERROR'], $this->config['board_email']);
					$phpbb_log->add('critical', ANONYMOUS, '', 'LOG_ERROR_EMAIL', false, array($message));
				}
				else
				{
					$message = sprintf($this->user->lang['FORMEL_LOG'], $usernames) ;
					$phpbb_log->add('admin', ANONYMOUS, '', 'LOG_MASS_EMAIL', false, array($message));
				}
			}
		}

		// Log the cronjob run
		$phpbb_log->add('admin', ANONYMOUS, '', 'LOG_FORMEL_CRON');

		return;
	}

	/**
	* Returns whether this cron task can run, given current board configuration.
	*
	* @return bool
	*/
	public function is_runnable()
	{
		return (bool) $this->config['drdeath_f1webtip_reminder_enabled'];
	}

	/**
	* Returns whether this cron task should run now, because enough time
	* has passed since it was last run.
	*
	* @return bool
	*/
	public function should_run()
	{
		$check_time = (int) gmdate('mdY',time());
		return $this->config['drdeath_f1webtip_reminder_last_run'] <> $check_time;
	}
}
?>
