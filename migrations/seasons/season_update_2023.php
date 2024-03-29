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

class season_update_2023 extends \phpbb\db\migration\container_aware_migration
{
	public function effectively_installed()
	{
		return isset($this->config['drdeath_f1webtip_season']) && version_compare($this->config['drdeath_f1webtip_season'], '2023', '>=');
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
				[&$this, 'season_2023']
			]],
			// Set the current version
			['config.update', [
				'drdeath_f1webtip_season', '2023'
			]],
		];
	}

	public function season_2023()
	{
		$db = $this->container->get('dbal.conn');

		$table_drivers 	= $this->table_prefix . 'f1webtip_drivers';
		$table_teams	= $this->table_prefix . 'f1webtip_teams';
		$table_races 	= $this->table_prefix . 'f1webtip_races';

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

			$sql_ary[] = ['team_name' => 'Mercedes F1 Team',	'team_img' => '', 'team_car' => '',]; # 'team_id' =>  1
			$sql_ary[] = ['team_name' => 'Scuderia Ferrari',	'team_img' => '', 'team_car' => '',]; # 'team_id' =>  2
			$sql_ary[] = ['team_name' => 'Red Bull Racing',		'team_img' => '', 'team_car' => '',]; # 'team_id' =>  3
			$sql_ary[] = ['team_name' => 'McLaren F1 Team',		'team_img' => '', 'team_car' => '',]; # 'team_id' =>  4
			$sql_ary[] = ['team_name' => 'Alpine F1 Team',		'team_img' => '', 'team_car' => '',]; # 'team_id' =>  5
			$sql_ary[] = ['team_name' => 'Alpha Tauri F1 Team',	'team_img' => '', 'team_car' => '',]; # 'team_id' =>  6
			$sql_ary[] = ['team_name' => 'Aston Martin F1 Team','team_img' => '', 'team_car' => '',]; # 'team_id' =>  7
			$sql_ary[] = ['team_name' => 'Alfa Romeo Racing',	'team_img' => '', 'team_car' => '',]; # 'team_id' =>  8
			$sql_ary[] = ['team_name' => 'Haas F1 Team',		'team_img' => '', 'team_car' => '',]; # 'team_id' =>  9
			$sql_ary[] = ['team_name' => 'Williams Racing',		'team_img' => '', 'team_car' => '',]; # 'team_id' => 10

			$db->sql_multi_insert($table_teams, $sql_ary);
		}

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
			$sql_ary[] = ['driver_name' => 'Hamilton, Lewis',		'driver_img' => '',	'driver_team' => 1,];
			$sql_ary[] = ['driver_name' => 'Russell, George',		'driver_img' => '',	'driver_team' => 1,];

			# -- Team 2 Scuderia Ferrari
			$sql_ary[] = ['driver_name' => 'Sainz, Carlos',			'driver_img' => '',	'driver_team' => 2,];
			$sql_ary[] = ['driver_name' => 'Leclerc, Charles',		'driver_img' => '',	'driver_team' => 2,];

			# -- Team 3 Red Bull Racing
			$sql_ary[] = ['driver_name' => 'Verstappen, Max',		'driver_img' => '',	'driver_team' => 3,];
			$sql_ary[] = ['driver_name' => 'Perez, Sergio',			'driver_img' => '',	'driver_team' => 3,];

			# -- Team 4 McLaren F1 Team
			$sql_ary[] = ['driver_name' => 'Norris, Lando',			'driver_img' => '',	'driver_team' => 4,];
			$sql_ary[] = ['driver_name' => 'Piastri, Oscar',		'driver_img' => '',	'driver_team' => 4,];

			# -- Team 5 Alpine F1 Team
			$sql_ary[] = ['driver_name' => 'Gasly, Pierre',			'driver_img' => '',	'driver_team' => 5,];
			$sql_ary[] = ['driver_name' => 'Ocon, Esteban',			'driver_img' => '',	'driver_team' => 5,];

			# -- Team 6 Alpha Tauri F1 Team
			$sql_ary[] = ['driver_name' => 'De Vries, Nyck',		'driver_img' => '',	'driver_team' => 6,];
			$sql_ary[] = ['driver_name' => 'Tsunoda, Yuki',			'driver_img' => '',	'driver_team' => 6,];

			# -- Team 7 Aston Martin F1 Team
			$sql_ary[] = ['driver_name' => 'Alonso, Fernando',	 	'driver_img' => '', 'driver_team' => 7,];
			$sql_ary[] = ['driver_name' => 'Stroll, Lance',			'driver_img' => '',	'driver_team' => 7,];

			# -- Team 8 Alfa Romeo Racing
			$sql_ary[] = ['driver_name' => 'Bottas, Valtteri',		'driver_img' => '',	'driver_team' => 8,];
			$sql_ary[] = ['driver_name' => 'Zhou, Guanyu',			'driver_img' => '',	'driver_team' => 8,];

			# -- Team 9 Haas F1 Team
			$sql_ary[] = ['driver_name' => 'Hülkenberg, Nico',		'driver_img' => '',	'driver_team' => 9,];
			$sql_ary[] = ['driver_name' => 'Magnussen, Kevin',		'driver_img' => '',	'driver_team' => 9,];

			# -- Team 10 Williams Racing
			$sql_ary[] = ['driver_name' => 'Albon, Alexander',		'driver_img' => '',	'driver_team' => 10,];
			$sql_ary[] = ['driver_name' => 'Sargeant, Logan',		'driver_img' => '',	'driver_team' => 10,];

			$db->sql_multi_insert($table_drivers, $sql_ary);
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

			$sql_ary[] = ['race_name' => 'Bahrain / Sachir',				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1678028400, 'race_length' => '5,412', 'race_laps' => 57, 'race_distance' => '308,238', 'race_debut' => 2004,];
			$sql_ary[] = ['race_name' => 'Saudi-Arabien / Dschidda',		'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1679245200, 'race_length' => '6,175', 'race_laps' => 50, 'race_distance' => '308,450', 'race_debut' => 2021,];
			$sql_ary[] = ['race_name' => 'Australien / Melbourne',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1680411600, 'race_length' => '5,303', 'race_laps' => 58, 'race_distance' => '307,574', 'race_debut' => 1996,];
			$sql_ary[] = ['race_name' => 'Aserbaidschan / Baku',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1682852400, 'race_length' => '6,003', 'race_laps' => 51, 'race_distance' => '306,049', 'race_debut' => 2016,];
			$sql_ary[] = ['race_name' => 'Miami / Miami',					'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1683487800, 'race_length' => '5,410', 'race_laps' => 57, 'race_distance' => '308.370', 'race_debut' => 2022,];
			$sql_ary[] = ['race_name' => 'Italien / Imola',					'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1684674000, 'race_length' => '4.959', 'race_laps' => 63, 'race_distance' => '309,267', 'race_debut' => 1980,];
			$sql_ary[] = ['race_name' => 'Monaco / Monte Carlo',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1685278800, 'race_length' => '3,337', 'race_laps' => 78, 'race_distance' => '260,286', 'race_debut' => 1950,];
			$sql_ary[] = ['race_name' => 'Spanien / Barcelona',				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1685883600, 'race_length' => '4,655', 'race_laps' => 66, 'race_distance' => '307,104', 'race_debut' => 1991,];
			$sql_ary[] = ['race_name' => 'Kanada / Montreal',				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1687111200, 'race_length' => '4,361', 'race_laps' => 70, 'race_distance' => '305,270', 'race_debut' => 1978,];
			$sql_ary[] = ['race_name' => 'Österreich / Spielberg',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1688302800, 'race_length' => '4,318', 'race_laps' => 71, 'race_distance' => '306,452', 'race_debut' => 1970,];
			$sql_ary[] = ['race_name' => 'Großbritannien / Silverstone',	'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1688911200, 'race_length' => '5,891', 'race_laps' => 52, 'race_distance' => '306,198', 'race_debut' => 1950,];
			$sql_ary[] = ['race_name' => 'Ungarn / Budapest',				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1690117200, 'race_length' => '4,381', 'race_laps' => 70, 'race_distance' => '306,630', 'race_debut' => 1986,];
			$sql_ary[] = ['race_name' => 'Belgien / Spa-Francorchamps',		'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1690722000, 'race_length' => '7,004', 'race_laps' => 44, 'race_distance' => '308,052', 'race_debut' => 1950,];
			$sql_ary[] = ['race_name' => 'Niederlande / Zandvoort',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1693141200, 'race_length' => '4,252', 'race_laps' => 72, 'race_distance' => '306,144', 'race_debut' => 1952,];
			$sql_ary[] = ['race_name' => 'Italien / Monza',					'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1693746000, 'race_length' => '5,793', 'race_laps' => 53, 'race_distance' => '306,720', 'race_debut' => 1950,];
			$sql_ary[] = ['race_name' => 'Singapur / Singapur',				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1694952000, 'race_length' => '5,065', 'race_laps' => 61, 'race_distance' => '308,828', 'race_debut' => 2008,];
			$sql_ary[] = ['race_name' => 'Japan / Suzuka',					'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1695531600, 'race_length' => '5,807', 'race_laps' => 53, 'race_distance' => '307,471', 'race_debut' => 1987,];
			$sql_ary[] = ['race_name' => 'Qatar / Doha',					'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1696773600, 'race_length' => '5,380', 'race_laps' => 57, 'race_distance' => '306,660', 'race_debut' => 2023,];
			$sql_ary[] = ['race_name' => 'USA / Austin',					'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1698001200, 'race_length' => '5,513', 'race_laps' => 56, 'race_distance' => '308,405', 'race_debut' => 2012,];
			$sql_ary[] = ['race_name' => 'Mexico / Mexico City',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1698609600, 'race_length' => '4,304', 'race_laps' => 71, 'race_distance' => '305,354', 'race_debut' => 1963,];
			$sql_ary[] = ['race_name' => 'Brasilien / São Paulo',			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1699203600, 'race_length' => '4,309', 'race_laps' => 71, 'race_distance' => '305,909', 'race_debut' => 1973,];
			$sql_ary[] = ['race_name' => 'USA / Las Vegas',					'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1700294400, 'race_length' => '3,650', 'race_laps' => 75, 'race_distance' => '273,750', 'race_debut' => 1981,];
			$sql_ary[] = ['race_name' => 'Arabische Emirate / Abu Dhabi',	'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1701003600, 'race_length' => '5,554', 'race_laps' => 55, 'race_distance' => '305,355', 'race_debut' => 2009,];

			$db->sql_multi_insert($table_races, $sql_ary);
		}

		return;
	}
}
