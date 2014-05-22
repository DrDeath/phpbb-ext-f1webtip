<?php
/**
*
* @package phpBB Extension - DrDeath F1WebTip
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace drdeath\f1webtip\cron\task\core;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

class email_reminder extends \phpbb\cron\task\base
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
    * @param \phpbb\config\config        $config
    * @param \phpbb\controller\helper    $helper
    * @param \phpbb\template\template    $template
    * @param \phpbb\user                $user
    */
    public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user)
    {
        $this->config = $config;
        $this->helper = $helper;
        $this->template = $template;
        $this->user = $user;
    }

    /**
    * Runs this cron task.
    *
    * @return null
    */
    public function run()
    {
        global $db, $user, $auth, $template, $cache, $request;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
        global $phpbb_container, $phpbb_extension_manager, $phpbb_path_helper, $phpbb_log;
        
		$table_races 	= $phpbb_container->getParameter('tables.f1webtip.races');
		$table_teams	= $phpbb_container->getParameter('tables.f1webtip.teams');
		$table_drivers 	= $phpbb_container->getParameter('tables.f1webtip.drivers');
		$table_wm 		= $phpbb_container->getParameter('tables.f1webtip.wm');
		$table_tips 	= $phpbb_container->getParameter('tables.f1webtip.tips');

		// No $user filled up at this point..... now we do so...
		$user->setup();
		
        // Update the last run timestamp to today (i.e. 6192013 --> 06.19.2013)
        $check_time = (int) gmdate('mdY',time());
        $this->config->set('drdeath_f1webtip_reminder_last_run', $check_time, true);


		//Mail Settings
		$use_queue 		= false;
		$used_method 	= NOTIFY_EMAIL;
		$priority 		= MAIL_NORMAL_PRIORITY;

		// Get F1 Webtip restricted group
		$formel_group_id 		= $config['drdeath_f1webtip_restrict_to'];

		// Uncomment the next line for sending the reminder mail to a special group. Replace 114 with the special group ID
		// $formel_group_id		= 114;

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

		$result = $db->sql_query($sql);

		$races = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

        
        // Debug Start: Reset cron lock 
        $config->set('cron_lock', '0');
        $config->set('drdeath_f1webtip_reminder_last_run', '1', true);
        // Debug End
        
		// If we found the race, get all data and send the mail
		foreach ($races as $race)
		{
			$race_id 		= $race['race_id'];

			// Update the race_mail status
			$sql_update = '	UPDATE  	' . $table_races . '
							SET 		race_mail = 1
							WHERE 		race_id = ' . $race_id ;

//			$result_mail = $db->sql_query($sql_update);

			// prepare some variables
			$race_name 		= $race['race_name'];
			$race_time		= $race['race_time'];

			// Get the race f1webtipp deadline.
			// Could have problems if your users live in different timezones.
			// In this case, remove the DEADLINETIME variable in email template
			$event_stop		= date($race_time - $config['drdeath_f1webtip_deadline_offset']);
			$b_day			= $user->format_date($event_stop, 'd');
			$b_month		= $user->format_date($event_stop, 'm');
			$b_year			= $user->format_date($event_stop, 'Y');
			$b_hour			= $user->format_date($event_stop, 'H');
			$b_minute		= $user->format_date($event_stop, 'i');
			$deadline_date 	= $b_day . '.' . $b_month . '.' . $b_year;
			$deadline_time	= $b_hour . ':' . $b_minute;

			$subject 		= $user->lang['FORMEL_TITLE'] . " - " . $user->lang['FORMEL_CURRENT_RACE']  . " : " . $race_name;
			$usernames 		= '';
			
			
			include_once($phpbb_root_path . 'includes/functions_messenger.' . $phpEx);
//			$messenger = new messenger($use_queue);

			$errored = false;
//			$messenger->headers('X-AntiAbuse: Board servername - ' . $config['server_name']);
//			$messenger->headers('X-AntiAbuse: User_id - ' . $user->data['user_id']);
//			$messenger->headers('X-AntiAbuse: Username - ' . $user->data['username']);
//			$messenger->headers('X-AntiAbuse: User IP - ' . $user->ip);
//			$messenger->subject(htmlspecialchars_decode($subject));
//			$messenger->set_mail_priority($priority);

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

			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{

				echo "<br/>" . $row['user_email'] . " - " . $row['username'] . "<br/>";
/*				// Send the messages
				$used_lang = $row['user_lang'];
				$messenger->to($row['user_email'], $row['username']);
				$messenger->template('cron_formel', $used_lang);

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
					$message = sprintf($user->lang['FORMEL_LOG_ERROR'], $row['user_email']);
					add_log('critical', 'LOG_ERROR_EMAIL', $message);
				}
				else
				{
					$usernames .= (($usernames != '') ? ', ' : '') . $row['username'];
				}
*/
			}
/*
			// Only if some emails have already been sent previously.
			if ($usernames <> '')
			{
				$message = sprintf($user->lang['FORMEL_LOG'], $usernames) ;
				add_log('admin', 'LOG_MASS_EMAIL', $message);

				//send admin email
				$used_lang 	= $user->data['user_lang'];
				$subject 	= sprintf($user->lang['FORMEL_MAIL_ADMIN'], $race_name);

				$messenger->to($config['board_email'], $config['sitename']);
				$messenger->subject(htmlspecialchars_decode($subject));
				$messenger->template('admin_send_email', $used_lang);
				$messenger->assign_vars(array(
					'CONTACT_EMAIL' => $config['board_contact'],
					'MESSAGE'		=> sprintf($user->lang['FORMEL_MAIL_ADMIN_MESSAGE'], $usernames),
					)
				);

				if (!($messenger->send($used_method)))
				{
					$message = sprintf($user->lang['FORMEL_LOG_ERROR'], $config['board_email']);
					add_log('critical', 'LOG_ERROR_EMAIL', $message);
				}
			}
*/
		}


        // Log the cronjob run
        add_log('admin', 'LOG_FORMEL_CRON');
        
        return;
    }

    /**
    * Returns whether this cron task can run, given current board configuration.
    *
    * @return bool
    */
    public function is_runnable()
    {
    	// Debug: Is allways runnable
        return true;
        
        // return (bool) $this->config['drdeath_f1webtip_reminder_enabled'];
    }

    /**
    * Returns whether this cron task should run now, because enough time
    * has passed since it was last run.
    *
    * @return bool
    */
    public function should_run()
    {
    	// Debug: should always raun
    	 return true;
    	 
        //$check_time = (int) gmdate('mdY',time());
        //return $this->config['drdeath_f1webtip_reminder_last_run'] != $check_time;
    }
}

?>