<?php
/**
 *
 * Formula 1 WebTip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2014, Dr.Death, http://www.lpi-clan.de
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace drdeath\f1webtip\migrations\v10x;

class release_1_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['drdeath_f1webtip_version']) && version_compare($this->config['drdeath_f1webtip_version'], '1.0.0', '>=');
	}

	static public function depends_on()
	{
		return ['\phpbb\db\migration\data\v310\gold'];
	}

	/**
	 * Custom function query permission roles
	 *
	 * @return void
	 * @access public
	 */
	private function role_exists($role)
	{
		$sql = 'SELECT role_id
			FROM ' . ACL_ROLES_TABLE . "
			WHERE role_name = '" . $this->db->sql_escape($role) . "'";
		$result = $this->db->sql_query_limit($sql, 1);
		$role_id = $this->db->sql_fetchfield('role_id');
		$this->db->sql_freeresult($result);
		return $role_id;
	}

	public function update_data()
	{
		$data = [
			// Set the current version
			array['config.add', ['drdeath_f1webtip_version', '1.0.0']],

			// now populate some default config data
			['config.add', ['drdeath_f1webtip_mod_id'				, '2']],
			['config.add', ['drdeath_f1webtip_deadline_offset'		, '600']],
			['config.add', ['drdeath_f1webtip_forum_id'				, '0']],
			['config.add', ['drdeath_f1webtip_event_change'			, '86400']],
			['config.add', ['drdeath_f1webtip_points_mentioned'		, '1']],
			['config.add', ['drdeath_f1webtip_points_placed'		, '1']],
			['config.add', ['drdeath_f1webtip_points_fastest'		, '2']],
			['config.add', ['drdeath_f1webtip_points_tired'			, '2']],
			['config.add', ['drdeath_f1webtip_points_safety_car'	, '2']],
			['config.add', ['drdeath_f1webtip_restrict_to'			, '0']],
			['config.add', ['drdeath_f1webtip_no_driver_img'		, 'nodriver.jpg']],
			['config.add', ['drdeath_f1webtip_no_team_img'			, 'noteam.jpg']],
			['config.add', ['drdeath_f1webtip_no_car_img'			, 'nocar.jpg']],
			['config.add', ['drdeath_f1webtip_no_race_img'			, 'norace.jpg']],
			['config.add', ['drdeath_f1webtip_driver_img_height'	, '60']],
			['config.add', ['drdeath_f1webtip_driver_img_width'		, '48']],
			['config.add', ['drdeath_f1webtip_team_img_height'		, '48']],
			['config.add', ['drdeath_f1webtip_team_img_width'		, '120']],
			['config.add', ['drdeath_f1webtip_car_img_height'		, '50']],
			['config.add', ['drdeath_f1webtip_car_img_width'		, '140']],
			['config.add', ['drdeath_f1webtip_race_img_height'		, '120']],
			['config.add', ['drdeath_f1webtip_race_img_width'		, '210']],
			['config.add', ['drdeath_f1webtip_show_in_profile'		, '1']],
			['config.add', ['drdeath_f1webtip_show_in_viewtopic'	, '1']],
			['config.add', ['drdeath_f1webtip_show_gfx'				, '1']],
			['config.add', ['drdeath_f1webtip_show_gfxr'			, '1']],
			['config.add', ['drdeath_f1webtip_show_countdown'		, '1']],
			['config.add', ['drdeath_f1webtip_show_avatar'			, '1']],
			['config.add', ['drdeath_f1webtip_guest_viewing'		, '0']],
			['config.add', ['drdeath_f1webtip_reminder_enabled'		, '0']],
			['config.add', ['drdeath_f1webtip_reminder_last_run'	, '0']],
			['config.add', ['drdeath_f1webtip_season'				, '0']],
			['config.add', ['drdeath_f1webtip_show_headbanner'		, '1']],
			['config.add', ['drdeath_f1webtip_head_height'			, '60']],
			['config.add', ['drdeath_f1webtip_head_width'			, '468']],
			['config.add', ['drdeath_f1webtip_headbanner1_img'		, 'f1webtip_index.jpg']],
			['config.add', ['drdeath_f1webtip_headbanner2_img'		, 'f1webtip_rules.jpg']],
			['config.add', ['drdeath_f1webtip_headbanner3_img'		, 'f1webtip_stats.jpg']],

			// Alright, now lets add some modules to the ACP
			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_F1WEBTIP_TITLE'
			]],
			['module.add', [
				'acp',
				'ACP_F1WEBTIP_TITLE',
				[
					'module_basename'	=> '\drdeath\f1webtip\acp\main_module',
					'modes'				=> ['settings','races','teams','drivers'],
				],
			]],

			// Now to add some permission settings
			['permission.add', ['a_formel_races']],		// New global admin permission a_formel_races
			['permission.add', ['a_formel_teams']],		// New global admin permission a_formel_teams
			['permission.add', ['a_formel_drivers']],	// New global admin permission a_formel_drivers
			['permission.add', ['a_formel_settings']],	// New global admin permission a_formel_settings
		];

		// Before we add additional permissions to an existing standard role, we need to first check if this role actually exists.
		if ($this->role_exists('ROLE_ADMIN_FULL'))
		{
			// How about we give some default permissions then as well?
			$data[] = ['permission.permission_set', ['ROLE_ADMIN_FULL', 'a_formel_races']];		// Give ROLE_ADMIN_FULL a_formel_races permission
			$data[] = ['permission.permission_set', ['ROLE_ADMIN_FULL', 'a_formel_teams']];		// Give ROLE_ADMIN_FULL a_formel_teams permission
			$data[] = ['permission.permission_set', ['ROLE_ADMIN_FULL', 'a_formel_drivers']];	// Give ROLE_ADMIN_FULL a_formel_drivers permission
			$data[] = ['permission.permission_set', ['ROLE_ADMIN_FULL', 'a_formel_settings']];	// Give ROLE_ADMIN_FULL a_formel_settings permission
		}

		return $data;
	}

	public function update_schema()
	{
		return [
			// We have to create our own f1 webtip tables
			'add_tables'	=> [
				// F1 driver table
				$this->table_prefix . 'f1webtip_drivers'	=> [
					'COLUMNS'		=> [
						'driver_id'			=> ['UINT', null, 'auto_increment'],
						'driver_name'		=> ['VCHAR_UNI', ''],
						'driver_img'		=> ['VCHAR', ''],
						'driver_team'		=> ['UINT', 0],
						'driver_penalty'	=> ['DECIMAL', 0],
						'driver_disabled'	=> ['BOOL', 0],
						],
					'PRIMARY_KEY'	=> 'driver_id',
				],
				// F1 team table
				$this->table_prefix . 'f1webtip_teams'		=> [
					'COLUMNS'		=> [
						'team_id'			=> ['UINT', null, 'auto_increment'],
						'team_name'			=> ['VCHAR_UNI', ''],
						'team_img'			=> ['VCHAR', ''],
						'team_car'			=> ['VCHAR', ''],
						'team_penalty'		=> ['DECIMAL', 0],
						],
					'PRIMARY_KEY'	=> 'team_id',
				],
				// F1 race table
				$this->table_prefix . 'f1webtip_races'		=> [
					'COLUMNS'		=> [
						'race_id'			=> ['UINT', null, 'auto_increment'],
						'race_name'			=> ['VCHAR_UNI', ''],
						'race_img'			=> ['VCHAR', ''],
						'race_quali'		=> ['VCHAR', ''],
						'race_result'		=> ['VCHAR', ''],
						'race_time'			=> ['UINT:11', 0],
						'race_length'		=> ['VCHAR:8', ''],
						'race_laps'			=> ['UINT', 0],
						'race_distance'		=> ['VCHAR:8', ''],
						'race_debut'		=> ['UINT', 0],
						'race_mail'			=> ['BOOL', 0],
						],
					'PRIMARY_KEY'	=> 'race_id',
				],
				// F1 wm points table
				$this->table_prefix . 'f1webtip_wm'		=> [
					'COLUMNS'		=> [
						'wm_id'				=> ['UINT', null, 'auto_increment'],
						'wm_race'			=> ['UINT', 0],
						'wm_driver'			=> ['UINT', 0],
						'wm_team'			=> ['UINT', 0],
						'wm_points'			=> ['DECIMAL', 0],
						],
					'PRIMARY_KEY'	=> 'wm_id',
				],
				// F1 user tip table
				$this->table_prefix . 'f1webtip_tips'		=> [
					'COLUMNS'		=> [
						'tip_id'			=> ['UINT', null, 'auto_increment'],
						'tip_user'			=> ['UINT', 0],
						'tip_race'			=> ['UINT', 0],
						'tip_result'		=> ['VCHAR:60', 0],
						'tip_points'		=> ['UINT', 0],
						],
					'PRIMARY_KEY'	=> 'tip_id',
				],

			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_tables' => [
				$this->table_prefix . 'f1webtip_drivers',
				$this->table_prefix . 'f1webtip_teams',
				$this->table_prefix . 'f1webtip_races',
				$this->table_prefix . 'f1webtip_wm',
				$this->table_prefix . 'f1webtip_tips',
			],
		];
	}
}
