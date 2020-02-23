<?php
/**
*
* @package phpBB Extension - DrDeath F1WebTip
* @copyright (c) 2014 Dr.Death - www.lpi-clan.de
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace drdeath\f1webtip\cron\task;

use Symfony\Component\DependencyInjection\Container;
use \phpbb\language\language;
use \phpbb\language\language_file_loader;

class email_reminder extends \phpbb\cron\task\base
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
		\phpbb\user $user,
		\phpbb\language\language $language
	)
	{
		$this->root_path				= $root_path;
		$this->php_ext 					= $php_ext;
		$this->phpbb_container			= $phpbb_container;
		$this->phpbb_extension_manager 	= $phpbb_extension_manager;
		$this->phpbb_path_helper		= $phpbb_path_helper;
		$this->db 						= $db;
		$this->config 					= $config;
		$this->phpbb_log 				= $log;
		$this->user 					= $user;
		$this->language 				= $language;
	}

	/*
	* Format a timestamp in user's choosen date/time format and time zone
	*
	* Code borrowed from Mike-on-Tour
	*
	* @param 	string		$user_lang			addressed user's language
	* @param 	string		$user_timezone		addressed user's time zone
	* @param 	string		$user_dateformat	addressed user's date/time format
	* @param 	int			$user_timestamp		addressed user's php timestamp (registration date, last login, reminder mails as UNIX timestamp from users table)
	*
	* @return 	string							the timestamp in user's choosen date/time format and time zone as DateTime string
	*/
	private function format_date_time($user_lang, $user_timezone, $user_dateformat, $user_timestamp)
	{
		$default_tz = date_default_timezone_get();
		$date = new \DateTime('now', new \DateTimeZone($default_tz));
		$date->setTimestamp($user_timestamp);
		$date->setTimezone(new \DateTimeZone($user_timezone));
		$time = $date->format($user_dateformat);

		// Instantiate a new language class (with its own loader), set the user's chosen language and translate the date/time string
		$lang = new language(new language_file_loader($this->root_path, $this->php_ext));
		$lang->set_user_language($user_lang);

		// Find all words in date/time string and replace them with the translations from user's language
		preg_match_all("/[a-zA-Z]+/", $time, $matches, PREG_PATTERN_ORDER);
		if (sizeof ($matches[0]) > 0)
		{
			foreach ($matches[0] as $value)
			{
				$time = preg_replace("/".$value."/", $lang->lang(array('datetime', $value)), $time);
			}
		}

		// return the formatted and translated time in users timezone
		return $time;
	}

	/**
	* Runs this cron task.
	*
	* @return null
	*/
	public function run()
	{
		$table_races 	= $this->phpbb_container->getParameter('tables.f1webtip.races');
		$table_teams	= $this->phpbb_container->getParameter('tables.f1webtip.teams');
		$table_drivers 	= $this->phpbb_container->getParameter('tables.f1webtip.drivers');
		$table_wm 		= $this->phpbb_container->getParameter('tables.f1webtip.wm');
		$table_tips 	= $this->phpbb_container->getParameter('tables.f1webtip.tips');

		// Load extension language file
		$this->language->add_lang('common', 'drdeath/f1webtip');

		// Update the last run timestamp to today (i.e. 5232014 --> 05/23/2013)
		$check_time = (int) gmdate('mdY',time());
		$this->config->set('drdeath_f1webtip_reminder_last_run', $check_time, true);

		//Mail Settings
		$use_queue 		= false;
		$used_method 	= NOTIFY_EMAIL;
		$priority 		= MAIL_NORMAL_PRIORITY;

		// Get F1 Webtip restricted group
		$formel_group_id 	= $this->config['drdeath_f1webtip_restrict_to'];

		// Time slot will be 3 days before the next race starts
		$current_time 		= time();
		$one_day 			= 86400;
		$time_slot 			= $one_day * 3;
		$current_time_slot 	=  $current_time + $time_slot;

		// Get the race which will start within the next 3 days and mail reminder was not sent
		$sql = "SELECT 		*
				FROM 		$table_races
				WHERE 		race_time > $current_time
					AND		race_time < $current_time_slot
					AND		race_mail = 0
				ORDER BY 	race_time ASC";

		$result = $this->db->sql_query($sql);

		$races = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		// If we found the race, get all data and send the mail
		foreach ($races as $race)
		{
			$race_id 		= $race['race_id'];

			// Update the race_mail status to prevent sending mails again for the same race
			$sql_ary = array(
				'race_mail'	=> 1,
			);

			$sql = 'UPDATE ' . $table_races . '
				SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
				WHERE race_id = ' . (int) $race_id;
			$this->db->sql_query($sql);

			// prepare some variables
			$race_name 		= $race['race_name'];
			$race_time		= $race['race_time'];

			// Get the race f1webtipp deadline.
			$event_stop		= date($race_time - $this->config['drdeath_f1webtip_deadline_offset']);

			$subject 		= $this->language->lang('F1WEBTIP_PAGE') . " - " . $race_name;
			$usernames 		= '';

			include_once($this->root_path . 'includes/functions_messenger.' . $this->php_ext);
			$messenger = new \messenger($use_queue);

			$errored = false;
			$messenger->headers('X-AntiAbuse: Board servername - ' . $this->config['server_name']);
			$messenger->headers('X-AntiAbuse: User_id - ' . ANONYMOUS);
			$messenger->headers('X-AntiAbuse: Username - CRON TASK F1 WebTip Email Reminder');
			$messenger->subject(htmlspecialchars_decode($subject));
			$messenger->set_mail_priority($priority);

			$ext_path = $this->phpbb_path_helper->update_web_root_path($this->phpbb_extension_manager->get_extension_path('drdeath/f1webtip', true));

			// Get all the f1webtipp user
			// what user exactly ?
			// All member of the restrict_to group, admin mass mails allowed, user is normal (active) or founder
			$sql = 'SELECT 		u.user_id,
								u.username,
								u.user_lang,
								u.user_timezone,
								u.user_dateformat,
								u.user_email
					FROM 		' . USERS_TABLE . ' u , ' . USER_GROUP_TABLE . " ug
					WHERE 		ug.group_id = $formel_group_id
						AND 	u.user_id = ug.user_id
						AND		u.user_allow_massemail = 1
						AND		(u.user_type = " . USER_NORMAL . ' OR u.user_type = ' . USER_FOUNDER . ')
					GROUP BY	u.user_id
					ORDER BY 	u. username_clean ASC';

			$result = $this->db->sql_query($sql);

			while ($row = $this->db->sql_fetchrow($result))
			{
				// grep the user time zone, time format and language
				$user_timezone		= $row['user_timezone'];
				$user_dateformat	= $row['user_dateformat'];
				$user_lang			= $row['user_lang'];

				$deadline			= $this->format_date_time($user_lang, $user_timezone, $user_dateformat, $event_stop);
				// Send the messages

				$mail_template_path = $ext_path . 'language/' . $user_lang . '/email/';

				$messenger->to($row['user_email'], $row['username']);
				$messenger->template('cron_formel', $user_lang, $mail_template_path);
				$messenger->assign_vars(array(
					'USERNAME'		=> $row['username'],
					'RACENAME'		=> $race_name,
					'DEADLINEDATE'	=> $this->format_date_time($row['user_lang'], $row['user_timezone'], $row['user_dateformat'], $event_stop),
					)
				);

				if (!($messenger->send($used_method)))
				{
					$usernames .= (($usernames != '') ? ', ' : '') . $row['username']. '!';
					$message = sprintf($this->language->lang('FORMEL_LOG_ERROR'), $row['user_email']);
					$this->phpbb_log->add('critical', ANONYMOUS, '', 'LOG_ERROR_EMAIL', false, array($message));
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
				$user_lang 	= $this->config['default_lang'];
				$subject 	= sprintf($this->language->lang('FORMEL_MAIL_ADMIN'), $race_name);

				$messenger->to($this->config['board_email'], $this->config['sitename']);
				$messenger->subject(htmlspecialchars_decode($subject));
				$messenger->template('admin_send_email', $user_lang);
				$messenger->assign_vars(array(
					'CONTACT_EMAIL' => $this->config['board_contact'],
					'MESSAGE'		=> sprintf($this->language->lang('FORMEL_MAIL_ADMIN_MESSAGE'), $usernames),
					)
				);

				if (!($messenger->send($used_method)))
				{
					$message = sprintf($this->language->lang('FORMEL_LOG_ERROR'), $this->config['board_email']);
					$this->phpbb_log->add('critical', ANONYMOUS, '', 'LOG_ERROR_EMAIL', false, array($message));
				}
				else
				{
					$message = sprintf($this->language->lang('FORMEL_LOG'), $usernames) ;
					$this->phpbb_log->add('admin', ANONYMOUS, '', 'LOG_MASS_EMAIL', false, array($message));
				}
			}
		}

		// Log the cronjob run
		$this->phpbb_log->add('admin', ANONYMOUS, '', 'LOG_FORMEL_CRON');

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
