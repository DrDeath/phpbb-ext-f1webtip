<?php
/**
*
* @package phpBB Extension - DrDeath F1WebTip
* @copyright (c) 2018 Dr.Death - www.lpi-clan.de
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace drdeath\f1webtip\migrations\seasons;

class season_update_2018 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['drdeath_f1webtip_season']) && version_compare($this->config['drdeath_f1webtip_season'], '2018', '>=');
	}

	static public function depends_on()
	{
		return array('\drdeath\f1webtip\migrations\v10x\release_1_0_0');
	}

	public function update_data()
	{
		return array(
			//Call the Season Data routine
			array('custom', array(
				array(&$this, 'season_2018')
			)),
			// Set the current version
			array('config.update', array(
				'drdeath_f1webtip_season', '2018'
			)),
		);
	}

	// $value is equal to the value returned on the previous call (false if this is the first time it is run)
	public function season_2018($value)
	{

		global $db, $user, $auth, $template, $cache, $request;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		global $phpbb_container, $phpbb_extension_manager;

		$table_drivers 	= $this->table_prefix . 'f1webtip_drivers';
		$table_teams	= $this->table_prefix . 'f1webtip_teams';
		$table_races 	= $this->table_prefix . 'f1webtip_races';

		if ($this->db_tools->sql_table_exists($table_drivers))
		{
			// before we fill anything in this table, we truncate it. Maybe someone missed an old installation.
			$db->sql_query('TRUNCATE TABLE ' . $table_drivers);

			$sql_ary = array();

			# -- Team 1 Mercedes F1 Team
			$sql_ary[] = array('driver_id' => 44, 'driver_name' => 'Hamilton, Lewis',		'driver_img' => '',	'driver_team' => 1,);
			$sql_ary[] = array('driver_id' => 77, 'driver_name' => 'Bottas, Valtteri',		'driver_img' => '',	'driver_team' => 1,);

			# -- Team 2 Red Bull Racing
			$sql_ary[] = array('driver_id' => 3,  'driver_name' => 'Ricciardo, Daniel',		'driver_img' => '',	'driver_team' => 2,);
			$sql_ary[] = array('driver_id' => 33, 'driver_name' => 'Verstappen, Max',		'driver_img' => '',	'driver_team' => 2,);

			# -- Team 3 Scuderia Ferrari
			$sql_ary[] = array('driver_id' => 5,  'driver_name' => 'Vettel, Sebastian', 	'driver_img' => '', 'driver_team' => 3,);
			$sql_ary[] = array('driver_id' => 7,  'driver_name' => 'Räikkönen, Kimi',		'driver_img' => '',	'driver_team' => 3,);

			# -- Team 4 Force India F1 Team
			$sql_ary[] = array('driver_id' => 11, 'driver_name' => 'Perez, Sergio',			'driver_img' => '',	'driver_team' => 4,);
			$sql_ary[] = array('driver_id' => 31, 'driver_name' => 'Ocon, Esteban',			'driver_img' => '',	'driver_team' => 4,);

			# -- Team 5 Williams F1 Team
			$sql_ary[] = array('driver_id' => 18, 'driver_name' => 'Stroll, Lance',			'driver_img' => '',	'driver_team' => 5,);
			$sql_ary[] = array('driver_id' => 35, 'driver_name' => 'Sirotkin, Sergei',		'driver_img' => '',	'driver_team' => 5,);

			# -- Team 6 McLaren Honda
			$sql_ary[] = array('driver_id' => 2,  'driver_name' => 'Vandoorne, Stoffel',	'driver_img' => '',	'driver_team' => 6,);
			$sql_ary[] = array('driver_id' => 14, 'driver_name' => 'Alonso, Fernando',		'driver_img' => '',	'driver_team' => 6,);

			# -- Team 7 Scuderia Toro Rosso
			$sql_ary[] = array('driver_id' => 28, 'driver_name' => 'Hartley, Brendon',		'driver_img' => '',	'driver_team' => 7,);
			$sql_ary[] = array('driver_id' => 10, 'driver_name' => 'Gasly, Pierre',			'driver_img' => '',	'driver_team' => 7,);

			# -- Team 8 Haas
			$sql_ary[] = array('driver_id' => 8,  'driver_name' => 'Grosjean, Romain',		'driver_img' => '',	'driver_team' => 8,);
			$sql_ary[] = array('driver_id' => 20, 'driver_name' => 'Magnussen, Kevin',		'driver_img' => '',	'driver_team' => 8,);

			# -- Team 9 Renault F1 Team
			$sql_ary[] = array('driver_id' => 27, 'driver_name' => 'Hülkenberg, Nico',		'driver_img' => '',	'driver_team' => 9,);
			$sql_ary[] = array('driver_id' => 55, 'driver_name' => 'Sainz, Carlos',		'driver_img' => '',	'driver_team' => 9,);

			# -- Team 10 Sauber F1 Team
			$sql_ary[] = array('driver_id' => 9,  'driver_name' => 'Ericsson, Marcus',		'driver_img' => '',	'driver_team' => 10,);
			$sql_ary[] = array('driver_id' => 16, 'driver_name' => 'Leclerc, Charles',		'driver_img' => '',	'driver_team' => 10,);

			$db->sql_multi_insert($table_drivers, $sql_ary);
		}

		if ($this->db_tools->sql_table_exists($table_teams))
		{
			// before we fill anything in this table, we truncate it. Maybe someone missed an old installation.
			$db->sql_query('TRUNCATE TABLE ' . $table_teams);

			$sql_ary = array();

			$sql_ary[] = array('team_id' => 1,  'team_name' => 'Mercedes F1 Team', 			'team_img' => '', 'team_car' => '',);
			$sql_ary[] = array('team_id' => 2,  'team_name' => 'Red Bull Racing', 			'team_img' => '', 'team_car' => '',);
			$sql_ary[] = array('team_id' => 3,  'team_name' => 'Scuderia Ferrari', 			'team_img' => '', 'team_car' => '',);
			$sql_ary[] = array('team_id' => 4,  'team_name' => 'Force India F1 Team', 		'team_img' => '', 'team_car' => '',);
			$sql_ary[] = array('team_id' => 5,  'team_name' => 'Williams F1 Team', 			'team_img' => '', 'team_car' => '',);
			$sql_ary[] = array('team_id' => 6,  'team_name' => 'McLaren Honda', 			'team_img' => '', 'team_car' => '',);
			$sql_ary[] = array('team_id' => 7,  'team_name' => 'Scuderia Toro Rosso', 		'team_img' => '', 'team_car' => '',);
			$sql_ary[] = array('team_id' => 8,  'team_name' => 'Haas F1 Team', 				'team_img' => '', 'team_car' => '',);
			$sql_ary[] = array('team_id' => 9,  'team_name' => 'Renault F1 Team',	 		'team_img' => '', 'team_car' => '',);
			$sql_ary[] = array('team_id' => 10, 'team_name' => 'Sauber F1 Team', 			'team_img' => '', 'team_car' => '',);

			$db->sql_multi_insert($table_teams, $sql_ary);
		}

		if ($this->db_tools->sql_table_exists($table_races))
		{
			// before we fill anything in this table, we truncate it. Maybe someone missed an old installation.
			$db->sql_query('TRUNCATE TABLE ' . $table_races);

			$sql_ary = array();

			$sql_ary[] = array('race_id' => 1,  'race_name' => 'Australien / Melbourne', 		'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1521961200, 'race_length' => '5,303', 'race_laps' => 58, 'race_distance' => '307,574', 'race_debut' => 1996,);
			$sql_ary[] = array('race_id' => 2,  'race_name' => 'Bahrain / Sachir',				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1523199600, 'race_length' => '5,412', 'race_laps' => 57, 'race_distance' => '308,238', 'race_debut' => 2004,);
			$sql_ary[] = array('race_id' => 3,  'race_name' => 'China / Shanghai', 				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1523772000, 'race_length' => '5,451', 'race_laps' => 56, 'race_distance' => '305,066', 'race_debut' => 2004,);
			$sql_ary[] = array('race_id' => 4,  'race_name' => 'Aserbaidschan / Baku', 			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1525006800, 'race_length' => '6,003', 'race_laps' => 51, 'race_distance' => '306,049', 'race_debut' => 2016,);
			$sql_ary[] = array('race_id' => 5,  'race_name' => 'Spanien / Barcelona', 			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1526212800, 'race_length' => '4,655', 'race_laps' => 66, 'race_distance' => '307,104', 'race_debut' => 1991,);
			$sql_ary[] = array('race_id' => 6,  'race_name' => 'Monaco / Monte Carlo', 			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1527422400, 'race_length' => '3,337', 'race_laps' => 78, 'race_distance' => '260,286', 'race_debut' => 1950,);
			$sql_ary[] = array('race_id' => 7,  'race_name' => 'Kanada / Montreal', 			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1528653600, 'race_length' => '4,361', 'race_laps' => 70, 'race_distance' => '305,270', 'race_debut' => 1978,);
			$sql_ary[] = array('race_id' => 8,  'race_name' => 'Frankreich / Le Castellet', 	'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1529863200, 'race_length' => '5,759', 'race_laps' => 53, 'race_distance' => '310,633', 'race_debut' => 1971,);
			$sql_ary[] = array('race_id' => 9,  'race_name' => 'Österreich / Spielberg', 		'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1530446400, 'race_length' => '4,318', 'race_laps' => 71, 'race_distance' => '306,452', 'race_debut' => 1970,);
			$sql_ary[] = array('race_id' => 10, 'race_name' => 'Großbritannien / Silverstone', 	'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1531051200, 'race_length' => '5,891', 'race_laps' => 52, 'race_distance' => '306,198', 'race_debut' => 1950,);
			$sql_ary[] = array('race_id' => 11, 'race_name' => 'Deutschland / Hockenheim', 		'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1532260800, 'race_length' => '4,574', 'race_laps' => 67, 'race_distance' => '306,458', 'race_debut' => 1970,);
			$sql_ary[] = array('race_id' => 12, 'race_name' => 'Ungarn / Budapest', 			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1532865600, 'race_length' => '4,381', 'race_laps' => 70, 'race_distance' => '306,630', 'race_debut' => 1986,);
			$sql_ary[] = array('race_id' => 13, 'race_name' => 'Belgien / Spa-Francorchamps', 	'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1535284800, 'race_length' => '7,004', 'race_laps' => 44, 'race_distance' => '308,052', 'race_debut' => 1950,);
			$sql_ary[] = array('race_id' => 14, 'race_name' => 'Italien / Monza', 				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1535889600, 'race_length' => '5,793', 'race_laps' => 53, 'race_distance' => '306,720', 'race_debut' => 1950,);
			$sql_ary[] = array('race_id' => 15, 'race_name' => 'Singapur / Singapur', 			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1537099200, 'race_length' => '5,065', 'race_laps' => 61, 'race_distance' => '308,828', 'race_debut' => 2008,);
			$sql_ary[] = array('race_id' => 16,  'race_name' => 'Russland / Sochi', 			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1538308800, 'race_length' => '5,848', 'race_laps' => 53, 'race_distance' => '309,745', 'race_debut' => 2014,);
			$sql_ary[] = array('race_id' => 17, 'race_name' => 'Japan / Suzuka', 				'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1538888400, 'race_length' => '5,807', 'race_laps' => 53, 'race_distance' => '307,471', 'race_debut' => 1987,);
			$sql_ary[] = array('race_id' => 18, 'race_name' => 'USA / Austin', 		 			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1540148400, 'race_length' => '5,513', 'race_laps' => 56, 'race_distance' => '308,405', 'race_debut' => 2012,);
			$sql_ary[] = array('race_id' => 19, 'race_name' => 'Mexico / Mexico City', 			'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1540753200, 'race_length' => '4,304', 'race_laps' => 71, 'race_distance' => '305,354', 'race_debut' => 1963,);
			$sql_ary[] = array('race_id' => 20, 'race_name' => 'Brasilien / São Paulo', 		'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1541952000, 'race_length' => '4,309', 'race_laps' => 71, 'race_distance' => '305,909', 'race_debut' => 1973,);
			$sql_ary[] = array('race_id' => 21, 'race_name' => 'Arabische Emirate / Abu Dhabi', 'race_img' => '', 'race_quali' => '0', 'race_result' => '0', 'race_time' => 1543150800, 'race_length' => '5,554', 'race_laps' => 55, 'race_distance' => '305,355', 'race_debut' => 2009,);

			$db->sql_multi_insert($table_races, $sql_ary);
		}

		return;
	}
}
