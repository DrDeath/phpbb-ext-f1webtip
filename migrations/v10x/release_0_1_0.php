<?php
/**
*
* @package phpBB Extension - DrDeath F1WebTip
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace drdeath\f1webtip\migrations\v10x;

class release_0_1_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['drdeath_f1webtip_version']) && version_compare($this->config['drdeath_f1webtip_version'], '0.1.0', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\beta3');
	}

	public function update_data()
	{
		return array(
			// Set the current version
			array('config.add', array('drdeath_f1webtip_version', '0.1.0')),

			// now populate some default config data
			array('config.add', array('drdeath_f1webtip_mod_id', '2')),
			array('config.add', array('drdeath_f1webtip_deadline_offset', '600')),
			array('config.add', array('drdeath_f1webtip_forum_id', '0')),
			array('config.add', array('drdeath_f1webtip_event_change', '86400')),
			array('config.add', array('drdeath_f1webtip_points_mentioned', '1')),
			array('config.add', array('drdeath_f1webtip_points_placed', '1')),
			array('config.add', array('drdeath_f1webtip_points_fastest', '2')),
			array('config.add', array('drdeath_f1webtip_points_tired', '2')),
			array('config.add', array('drdeath_f1webtip_points_safety_car', '2')),
			array('config.add', array('drdeath_f1webtip_restrict_to', '0')),
			array('config.add', array('drdeath_f1webtip_no_driver_img', 'nodriver.jpg')),
			array('config.add', array('drdeath_f1webtip_no_team_img', 'noteam.jpg')),
			array('config.add', array('drdeath_f1webtip_no_car_img', 'nocar.jpg')),
			array('config.add', array('drdeath_f1webtip_no_race_img', 'norace.jpg')),
			array('config.add', array('drdeath_f1webtip_driver_img_height', '60')),
			array('config.add', array('drdeath_f1webtip_driver_img_width', '48')),
			array('config.add', array('drdeath_f1webtip_team_img_height', '48')),
			array('config.add', array('drdeath_f1webtip_team_img_width', '120')),
			array('config.add', array('drdeath_f1webtip_car_img_height', '50')),
			array('config.add', array('drdeath_f1webtip_car_img_width', '140')),
			array('config.add', array('drdeath_f1webtip_race_img_height', '120')),
			array('config.add', array('drdeath_f1webtip_race_img_width', '210')),
			array('config.add', array('drdeath_f1webtip_show_in_profile', '1')),
			array('config.add', array('drdeath_f1webtip_show_in_viewtopic', '1')),
			array('config.add', array('drdeath_f1webtip_show_gfx', '1')),
			array('config.add', array('drdeath_f1webtip_show_gfxr', '1')),
			array('config.add', array('drdeath_f1webtip_show_countdown', '1')),
			array('config.add', array('drdeath_f1webtip_show_avatar', '1')),
			array('config.add', array('drdeath_f1webtip_guest_viewing', '0')),
			array('config.add', array('drdeath_f1webtip_reminder_enabled', '0')),
			array('config.add', array('drdeath_f1webtip_reminder_last_run', '0')),
			array('config.add', array('drdeath_f1webtip_season', '0')),
			array('config.add', array('drdeath_f1webtip_show_headbanner', '1')),
			array('config.add', array('drdeath_f1webtip_head_height', '60')),
			array('config.add', array('drdeath_f1webtip_head_width', '468')),
			array('config.add', array('drdeath_f1webtip_headbanner1_img', 'f1webtip_index.jpg')),
			array('config.add', array('drdeath_f1webtip_headbanner2_img', 'f1webtip_rules.jpg')),
			array('config.add', array('drdeath_f1webtip_headbanner3_img', 'f1webtip_stats.jpg')),


			// Alright, now lets add some modules to the ACP
			array('module.add', array(
 				'acp',
 				'ACP_CAT_DOT_MODS',
 				'ACP_F1WEBTIP_TITLE'
 			)),
			array('module.add', array(
				'acp',
				'ACP_F1WEBTIP_TITLE',
				array(
					'module_basename'	=> '\drdeath\f1webtip\acp\main_module',
					'modes'				=> array('settings','races','teams','drivers'),
				),
			)),


			// Now to add some permission settings
			array('permission.add', array('a_formel_races')), // New global admin permission a_formel_races
			array('permission.add', array('a_formel_teams')), // New global admin permission a_formel_teams
			array('permission.add', array('a_formel_drivers')), // New global admin permission a_formel_drivers
			array('permission.add', array('a_formel_settings')), // New global admin permission a_formel_settings

			// How about we give some default permissions then as well?
			array('permission.permission_set', array('ROLE_ADMIN_FULL', 'a_formel_races')), // Give ROLE_ADMIN_FULL a_formel_races permission
			array('permission.permission_set', array('ROLE_ADMIN_FULL', 'a_formel_teams')), // Give ROLE_ADMIN_FULL a_formel_teams permission
			array('permission.permission_set', array('ROLE_ADMIN_FULL', 'a_formel_drivers')), // Give ROLE_ADMIN_FULL a_formel_drivers permission
			array('permission.permission_set', array('ROLE_ADMIN_FULL', 'a_formel_settings')), // Give ROLE_ADMIN_FULL a_formel_settings permission
		);
	}


	public function update_schema()
	{
		return array(
			// We have to create our own f1 webtip tables
			'add_tables'    => array(
				// F1 driver table
				$this->table_prefix . 'f1webtip_drivers'        => array(
					'COLUMNS'        => array(
						'driver_id'			=> array('UINT', NULL, 'auto_increment'),
						'driver_name'		=> array('VCHAR_UNI', ''),
						'driver_img'		=> array('VCHAR', ''),
						'driver_team'		=> array('UINT', 0),
						'driver_penalty'	=> array('DECIMAL', 0),
						'driver_disabled'	=> array('BOOL', 0),
						),
					'PRIMARY_KEY'	=> 'driver_id',
				),
				// F1 team table
				$this->table_prefix . 'f1webtip_teams'        => array(
					'COLUMNS'        => array(
						'team_id'			=> array('UINT', NULL, 'auto_increment'),
						'team_name'			=> array('VCHAR_UNI', ''),
						'team_img'			=> array('VCHAR', ''),
						'team_car'			=> array('VCHAR', ''),
						'team_penalty'		=> array('DECIMAL', 0),
						),
					'PRIMARY_KEY'	=> 'team_id',
				),
				// F1 race table
				$this->table_prefix . 'f1webtip_races'        => array(
					'COLUMNS'        => array(
						'race_id'			=> array('UINT', NULL, 'auto_increment'),
						'race_name'			=> array('VCHAR_UNI', ''),
						'race_img'			=> array('VCHAR', ''),
						'race_quali'		=> array('VCHAR', ''),
						'race_result'		=> array('VCHAR', ''),
						'race_time'			=> array('UINT:11', 0),
						'race_length'		=> array('VCHAR:8', ''),
						'race_laps'			=> array('UINT', 0),
						'race_distance'		=> array('VCHAR:8', ''),
						'race_debut'		=> array('UINT', 0),
						'race_mail'			=> array('BOOL', 0),
						),
					'PRIMARY_KEY'	=> 'race_id',
				),
				// F1 wm points table
				$this->table_prefix . 'f1webtip_wm'        => array(
					'COLUMNS'        => array(
						'wm_id'				=> array('UINT', NULL, 'auto_increment'),
						'wm_race'			=> array('UINT', 0),
						'wm_driver'			=> array('UINT', 0),
						'wm_team'			=> array('UINT', 0),
						'wm_points'			=> array('DECIMAL', 0),
						),
					'PRIMARY_KEY'	=> 'wm_id',
				),
				// F1 user tip table
				$this->table_prefix . 'f1webtip_tips'        => array(
					'COLUMNS'        => array(
						'tip_id'			=> array('UINT', NULL, 'auto_increment'),
						'tip_user'			=> array('UINT', 0),
						'tip_race'			=> array('UINT', 0),
						'tip_result'		=> array('VCHAR:60', 0),
						'tip_points'		=> array('UINT', 0),
						),
					'PRIMARY_KEY'	=> 'tip_id',
				),

			),
		);
	}


	public function revert_schema()
	{
		return array(
			'drop_tables' => array(
				$this->table_prefix . 'f1webtip_drivers',
				$this->table_prefix . 'f1webtip_teams',
				$this->table_prefix . 'f1webtip_races',
				$this->table_prefix . 'f1webtip_wm',
				$this->table_prefix . 'f1webtip_tips',
			),
		);
	}

}
