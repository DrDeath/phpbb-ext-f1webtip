<?php
/**
 *
 * Formula 1 WebTip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2014, Dr.Death, http://www.lpi-clan.de
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace drdeath\f1webtip\migrations\seasons;

class season_update_2021 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['drdeath_f1webtip_season']) && version_compare($this->config['drdeath_f1webtip_season'], '2021', '>=');
	}

	public static function depends_on()
	{
		return ['\drdeath\f1webtip\migrations\v10x\release_1_0_0'];
	}

	public function update_data()
	{
		return [
			//Call the Season Data routine
			['custom', [
				[&$this, 'season_2021']
			]],
			// Set the current version
			['config.update', [
				'drdeath_f1webtip_season', '2021'
			]],
		];
	}

	public function season_2021()
	{
		global $db;

		$table_drivers 	= $this->table_prefix . 'f1webtip_drivers';
		$table_teams	= $this->table_prefix . 'f1webtip_teams';
		$table_races 	= $this->table_prefix . 'f1webtip_races';
		$table_tips 	= $this->table_prefix . 'f1webtip_tips';
		$table_wm 		= $this->table_prefix . 'f1webtip_wm';

		if ($this->db_tools->sql_table_exists($table_drivers))
		{
			// before we fill anything in this table, we delete the content. Maybe someone missed an old installation.
			switch ($db->get_sql_layer())
			{
				case 'sqlite3':
					$db->sql_query('DELETE FROM ' . $table_drivers);
				break;

				default:
					$db->sql_query('TRUNCATE TABLE ' . $table_drivers);
				break;
			}

			$sql_ary = [];

			# -- Team 1 Mercedes F1 Team
			$sql_ary[] = ['driver_id' => 44, 'driver_name' => 'Hamilton, Lewis',		'driver_img' => '',	'driver_team' => 1,];
			$sql_ary[] = ['driver_id' => 77, 'driver_name' => 'Bottas, Valtteri',		'driver_img' => '',	'driver_team' => 1,];

			# -- Team 2 Scuderia Ferrari
			$sql_ary[] = ['driver_id' => 55, 'driver_name' => 'Sainz, Carlos',			'driver_img' => '',	'driver_team' => 2,];
			$sql_ary[] = ['driver_id' => 16, 'driver_name' => 'Leclerc, Charles',		'driver_img' => '',	'driver_team' => 2,];

			# -- Team 3 Red Bull Racing
			$sql_ary[] = ['driver_id' => 33, 'driver_name' => 'Verstappen, Max',		'driver_img' => '',	'driver_team' => 3,];
			$sql_ary[] = ['driver_id' => 11, 'driver_name' => 'Perez, Sergio',			'driver_img' => '',	'driver_team' => 3,];

			# -- Team 4 McLaren F1 Team
			$sql_ary[] = ['driver_id' => 4,  'driver_name' => 'Norris, Lando',			'driver_img' => '',	'driver_team' => 4,];
			$sql_ary[] = ['driver_id' => 3,  'driver_name' => 'Ricciardo, Daniel',		'driver_img' => '',	'driver_team' => 4,];

			# -- Team 5 Alpine F1 Team
			$sql_ary[] = ['driver_id' => 14, 'driver_name' => 'Alonso, Fernandes',		'driver_img' => '',	'driver_team' => 5,];
			$sql_ary[] = ['driver_id' => 31, 'driver_name' => 'Ocon, Esteban',			'driver_img' => '',	'driver_team' => 5,];

			# -- Team 6 Alpha Tauri F1 Team
			$sql_ary[] = ['driver_id' => 10, 'driver_name' => 'Gasly, Pierre',			'driver_img' => '',	'driver_team' => 6,];
			$sql_ary[] = ['driver_id' => 22, 'driver_name' => 'Tsunoda, Yuki',			'driver_img' => '',	'driver_team' => 6,];

			# -- Team 7 Aston Martin F1 Team
			$sql_ary[] = ['driver_id' => 5,  'driver_name' => 'Vettel, Sebastian', 		'driver_img' => '', 'driver_team' => 7,];
			$sql_ary[] = ['driver_id' => 18, 'driver_name' => 'Stroll, Lance',			'driver_img' => '',	'driver_team' => 7,];

			# -- Team 8 Alfa Romeo Racing
			$sql_ary[] = ['driver_id' => 7,  'driver_name' => 'Räikkönen, Kimi',		'driver_img' => '',	'driver_team' => 8,];
			$sql_ary[] = ['driver_id' => 99, 'driver_name' => 'Giovinazzi, Antonio',	'driver_img' => '',	'driver_team' => 8,];

			# -- Team 9 Haas F1 Team
			$sql_ary[] = ['driver_id' => 47, 'driver_name' => 'Schumacher, Mick',		'driver_img' => '',	'driver_team' => 9,];
			$sql_ary[] = ['driver_id' => 9,  'driver_name' => 'Masepin, Nikita',		'driver_img' => '',	'driver_team' => 9,];

			# -- Team 10 Williams Racing
			$sql_ary[] = ['driver_id' => 63, 'driver_name' => 'Russell, George',		'driver_img' => '',	'driver_team' => 10,];
			$sql_ary[] = ['driver_id' => 6,  'driver_name' => 'Latifi, Nicholas',		'driver_img' => '',	'driver_team' => 10,];

			$db->sql_multi_insert($table_drivers, $sql_ary);
		}

		if ($this->db_tools->sql_table_exists($table_teams))
		{
			// before we fill anything in this table, we delete the content. Maybe someone missed an old installation.
			switch ($db->get_sql_layer())
			{
				case 'sqlite3':
					$db->sql_query('DELETE FROM ' . $table_teams);
				break;

				default:
					$db->sql_query('TRUNCATE TABLE ' . $table_teams);
				break;
			}

			$sql_ary = [];

			$sql_ary[] = ['team_id' => 1,  'team_name' => 'Mercedes F1 Team',			'team_img' => '', 'team_car' => '',];
			$sql_ary[] = ['team_id' => 2,  'team_name' => 'Scuderia Ferrari',			'team_img' => '', 'team_car' => '',];
			$sql_ary[] = ['team_id' => 3,  'team_name' => 'Red Bull Racing',			'team_img' => '', 'team_car' => '',];
			$sql_ary[] = ['team_id' => 4,  'team_name' => 'McLaren F1 Team',			'team_img' => '', 'team_car' => '',];
			$sql_ary[] = ['team_id' => 5,  'team_name' => 'Alpine F1 Team',				'team_img' => '', 'team_car' => '',];
			$sql_ary[] = ['team_id' => 6,  'team_name' => 'Alpha Tauri F1 Team',		'team_img' => '', 'team_car' => '',];
			$sql_ary[] = ['team_id' => 7,  'team_name' => 'Aston Martin F1 Team',		'team_img' => '', 'team_car' => '',];
			$sql_ary[] = ['team_id' => 8,  'team_name' => 'Alfa Romeo Racing',			'team_img' => '', 'team_car' => '',];
			$sql_ary[] = ['team_id' => 9,  'team_name' => 'Haas F1 Team',				'team_img' => '', 'team_car' => '',];
			$sql_ary[] = ['team_id' => 10, 'team_name' => 'Williams Racing',			'team_img' => '', 'team_car' => '',];

			$db->sql_multi_insert($table_teams, $sql_ary);
		}

		if ($this->db_tools->sql_table_exists($table_races))
		{
			// before we fill anything in this table, we delete the content. Maybe someone missed an old installation.
			switch ($db->get_sql_layer())
			{
				case 'sqlite3':
					$db->sql_query('DELETE FROM ' . $table_races);
				break;

				default:
					$db->sql_query('TRUNCATE TABLE ' . $table_races);
				break;
			}

			$sql_ary = [];

			$sql_ary[] = ['race_id' => 1,  'race_name' => 'Bahrain / Sachir',				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1616943600, 'race_length' => '5,412', 'race_laps' => 57, 'race_distance' => '308,238', 'race_debut' => 2004,];
			$sql_ary[] = ['race_id' => 2,  'race_name' => 'Italien / Imola',				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1618750800, 'race_length' => '4.959', 'race_laps' => 63, 'race_distance' => '309,267', 'race_debut' => 1980,];
			$sql_ary[] = ['race_id' => 3,  'race_name' => 'Portugal / Portimao',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1619960400, 'race_length' => '4,635', 'race_laps' => 66, 'race_distance' => '308,877', 'race_debut' => 2020,];
			$sql_ary[] = ['race_id' => 4,  'race_name' => 'Spanien / Barcelona',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1620565200, 'race_length' => '4,655', 'race_laps' => 66, 'race_distance' => '307,104', 'race_debut' => 1991,];
			$sql_ary[] = ['race_id' => 5,  'race_name' => 'Monaco / Monte Carlo',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1621774800, 'race_length' => '3,337', 'race_laps' => 78, 'race_distance' => '260,286', 'race_debut' => 1950,];
			$sql_ary[] = ['race_id' => 6,  'race_name' => 'Aserbaidschan / Baku',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1622980800, 'race_length' => '6,003', 'race_laps' => 51, 'race_distance' => '306,049', 'race_debut' => 2016,];
			$sql_ary[] = ['race_id' => 7,  'race_name' => 'Kanada / Montreal',				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1623607200, 'race_length' => '4,361', 'race_laps' => 70, 'race_distance' => '305,270', 'race_debut' => 1978,];
			$sql_ary[] = ['race_id' => 8,  'race_name' => 'Frankreich / Le Castellet',		'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1624798800, 'race_length' => '5,759', 'race_laps' => 53, 'race_distance' => '310,633', 'race_debut' => 1971,];
			$sql_ary[] = ['race_id' => 9,  'race_name' => 'Österreich / Spielberg',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1625403600, 'race_length' => '4,318', 'race_laps' => 71, 'race_distance' => '306,452', 'race_debut' => 1970,];
			$sql_ary[] = ['race_id' => 10, 'race_name' => 'Großbritannien / Silverstone',	'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1626616800, 'race_length' => '5,891', 'race_laps' => 52, 'race_distance' => '306,198', 'race_debut' => 1950,];
			$sql_ary[] = ['race_id' => 11, 'race_name' => 'Ungarn / Budapest',				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1627822800, 'race_length' => '4,381', 'race_laps' => 70, 'race_distance' => '306,630', 'race_debut' => 1986,];
			$sql_ary[] = ['race_id' => 12, 'race_name' => 'Belgien / Spa-Francorchamps',	'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1630242000, 'race_length' => '7,004', 'race_laps' => 44, 'race_distance' => '308,052', 'race_debut' => 1950,];
			$sql_ary[] = ['race_id' => 13, 'race_name' => 'Niederlande / Zandvoort',		'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1630846800, 'race_length' => '4,252', 'race_laps' => 72, 'race_distance' => '306,144', 'race_debut' => 1952,];
			$sql_ary[] = ['race_id' => 14, 'race_name' => 'Italien / Monza',				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1631451600, 'race_length' => '5,793', 'race_laps' => 53, 'race_distance' => '306,720', 'race_debut' => 1950,];
			$sql_ary[] = ['race_id' => 15, 'race_name' => 'Russland / Sochi',				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1632657600, 'race_length' => '5,848', 'race_laps' => 53, 'race_distance' => '309,745', 'race_debut' => 2014,];
			$sql_ary[] = ['race_id' => 16, 'race_name' => 'Singapur / Singapur',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1633262400, 'race_length' => '5,065', 'race_laps' => 61, 'race_distance' => '308,828', 'race_debut' => 2008,];
			$sql_ary[] = ['race_id' => 17, 'race_name' => 'Japan / Suzuka',					'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1633842000, 'race_length' => '5,807', 'race_laps' => 53, 'race_distance' => '307,471', 'race_debut' => 1987,];
			$sql_ary[] = ['race_id' => 18, 'race_name' => 'USA / Austin',					'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1635102000, 'race_length' => '5,513', 'race_laps' => 56, 'race_distance' => '308,405', 'race_debut' => 2012,];
			$sql_ary[] = ['race_id' => 19, 'race_name' => 'Mexico / Mexico City',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1635706800, 'race_length' => '4,304', 'race_laps' => 71, 'race_distance' => '305,354', 'race_debut' => 1963,];
			$sql_ary[] = ['race_id' => 20, 'race_name' => 'Brasilien / São Paulo',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1636909200, 'race_length' => '4,309', 'race_laps' => 71, 'race_distance' => '305,909', 'race_debut' => 1973,];
			$sql_ary[] = ['race_id' => 21, 'race_name' => 'Australien / Melbourne',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1637474400, 'race_length' => '5,303', 'race_laps' => 58, 'race_distance' => '307,574', 'race_debut' => 1996,];
			$sql_ary[] = ['race_id' => 22, 'race_name' => 'Saudi-Arabien / Dschidda',		'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1638720000, 'race_length' => '0',     'race_laps' => 0,  'race_distance' => '0',       'race_debut' => 2021,];
			$sql_ary[] = ['race_id' => 23, 'race_name' => 'Arabische Emirate / Abu Dhabi',	'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1639314000, 'race_length' => '5,554', 'race_laps' => 55, 'race_distance' => '305,355', 'race_debut' => 2009,];

			$db->sql_multi_insert($table_races, $sql_ary);
		}

		switch ($db->get_sql_layer())
		{
			// We have to update the sequences (auto increments) for postgres manually after migration
			case 'postgres':
				$db->sql_query("SELECT SETVAL('" . $table_drivers . "_seq', COALESCE( MAX( driver_id ), 1) ) FROM " . $table_drivers );
				$db->sql_query("SELECT SETVAL('" . $table_teams   . "_seq', COALESCE( MAX( team_id )  , 1) ) FROM " . $table_teams   );
				$db->sql_query("SELECT SETVAL('" . $table_races   . "_seq', COALESCE( MAX( race_id )  , 1) ) FROM " . $table_races   );
				$db->sql_query("SELECT SETVAL('" . $table_tips    . "_seq', COALESCE( MAX( tip_id )   , 1) ) FROM " . $table_tips    );
				$db->sql_query("SELECT SETVAL('" . $table_wm      . "_seq', COALESCE( MAX( wm_id )    , 1) ) FROM " . $table_wm      );
			break;

			default:
			break;
		}

		return;
	}
}
