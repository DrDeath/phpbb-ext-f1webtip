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
	protected $phpbb_root_path;
	protected $php_ext;
	protected $config;
	protected $db;
	protected $table_prefix;

	/**
	* Constructor.
	*
	* @param string $phpbb_root_path The root path
	* @param string $php_ext The PHP extension
	* @param phpbb_config $config The config
	* @param phpbb_db_driver $db The db connection
	*/
	public function __construct($phpbb_root_path, $php_ext, \phpbb\config\config $config, \phpbb\db\driver\driver $db )
	{
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->config = $config;
		$this->db = $db;
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

		// Update the last run timestamp to today (i.e. 6192013 --> 06.19.2013)
		$check_time = (int) gmdate('mdY',time());
		$this->config->set('drdeath_f1webtip_reminder_last_run', $check_time, true);
		

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
		// return true;
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
		return $this->config['drdeath_f1webtip_reminder_last_run'] != $check_time;
	}
}

?>