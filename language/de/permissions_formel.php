<?php
/**
 * Formula 1 WebTip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2014, Dr.Death, http://www.lpi-clan.de
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

/**
 * DO NOT CHANGE.
 */
if (!defined('IN_PHPBB')) {
    exit;
}

if (empty($lang) || !is_array($lang)) {
    $lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

// Adding the permissions
// F1 WebTip permissions
$lang = array_merge($lang, [
    'ACL_CAT_FORMEL'		      => 'F1 Webtipp',
    'ACL_A_FORMEL_SETTINGS'	=> 'Kann F1 WebTipp Einstellungen verwalten',
    'ACL_A_FORMEL_DRIVERS'	 => 'Kann F1 WebTipp Fahrer verwalten',
    'ACL_A_FORMEL_TEAMS'	   => 'Kann F1 WebTipp Teams verwalten',
    'ACL_A_FORMEL_RACES'	   => 'Kann F1 WebTipp Rennen verwalten',
]);
