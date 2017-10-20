<?php
/**
  Website Baker Module: cScraper
  Developed for the Open Source Content Management System WebsiteBaker (http://websitebaker.org).
  Copyright (C) 2011 TNG Consulting Inc. All rights reserved.
  Contact: http://www.tngconsulting.ca

  Langugage: PHP
  Purpose: Backend - Is executed during the installation of the the module.
    Creates tables used by this module.

  Licence: The contents of this file is free software.
    You can redistribute and/or modify them under the terms of the
    GNU General Public License - version 3 or later, as published
    by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  Warranty: The contents of this file is distributed in the hope that it
    will be useful, but WITHOUT ANY WARRANTY; without even the implied
    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

**/

// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: index.php'));  

// delete existing module DB-table (start with a clean database)
$database->query("DROP TABLE IF EXISTS `" .TABLE_PREFIX ."mod_cscraper`");

// create a new, clean module DB-table (you need to change the fields added according your needs!!!)
$mod_create_table = 'CREATE TABLE `' .TABLE_PREFIX .'mod_cscraper` ( '
	. '`section_id` INT NOT NULL DEFAULT \'0\','
	. '`page_id` INT NOT NULl DEFAULT \'0\','
	. '`url` TEXT NOT NULL DEFAULT \'\','
	. '`start` TEXT NOT NULL DEFAULT \'\','
	. '`end` TEXT NOT NULL DEFAULT \'\','
	. '`search` TEXT NOT NULL DEFAULT \'\','
	. '`replace` TEXT NOT NULL DEFAULT \'\','
	. '`search2` TEXT NOT NULL DEFAULT \'\','
	. '`replace2` TEXT NOT NULL DEFAULT \'\','
	. '`search3` TEXT NOT NULL DEFAULT \'\','
	. '`replace3` TEXT NOT NULL DEFAULT \'\','
	. '`content` LONGTEXT NOT NULL DEFAULT \'\','
	. '`modified_when` INT(11) NOT NULL DEFAULT \'0\','
	. 'PRIMARY KEY (section_id)'
	. ' )';
$database->query($mod_create_table);

/**
*	ADD THE CODE BELOW TO YOUR install.php FILE IF YOU WANT THAT USERS CAN SEARCH INFORMATION STORED
*	IN YOUR MODUL DB-TABLES. IF YOUR MODULE DB-TABLES DO NOT CONTAIN ANY INFORMATION YOU WANT BE FOUND
*	BY THE WB SEARCH FUNCTION, SIMLY DELETE THE LINES BELOW. 
*  NOTE: DO NOT DELETE THE VERY LAST LINE CONTAINING THE CLOSING PHP TAG ?>
*/
# ADD 1st MODULE SEARCH ROW TO THE DATABASE
$search_info = array(
	'page_id'				=>	'page_id',
	'title'					=>	'page_title',
	'link'					=>	'link',
	'description'		=>	'description',
	'content'   		=>	'content',
	'modified_when'	=>	'modified_when',
	'modfified_by'	=>	'modified_by'
	);
$search_info = serialize($search_info);
$database->query("INSERT INTO `" .TABLE_PREFIX ."search` (`name`,`value`,`extra`) 
	VALUES ('module', 'cscraper', '$search_info')");

# ADD 2nd MODULE SEARCH ROW TO THE DATABASE
$search_info = "SELECT [TP]pages.page_id, [TP]pages.page_title,	[TP]pages.link, [TP]pages.description, 
	[TP]pages.modified_when, [TP]pages.modified_by	FROM [TP]mod_cscraper, [TP]pages WHERE ";
$database->query("INSERT INTO `" .TABLE_PREFIX ."search` (`name`,`value`,`extra`) 
	VALUES ('query_start', '$search_info', 'cscraper')");

# ADD 3rd MODULE SEARCH ROW TO THE DATABASE
$search_info = " [TP]pages.page_id = [TP]mod_cscraper.page_id AND [TP]mod_cscraper.simple_output [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'";
$database->query("INSERT INTO `".TABLE_PREFIX."search` (`name`,`value`,`extra`) 
	VALUES ('query_body', '$search_info', 'cscraper')");

# ADD 4th MODULE SEARCH ROW TO THE DATABASE
$search_info = "";
$database->query("INSERT INTO `".TABLE_PREFIX."search` (`name`,`value`,`extra`) 
	VALUES ('query_end', '$search_info', 'cscraper')");
	
// insert blank row to the module table (there needs to be at least on row for the search to work)
$database->query("INSERT INTO `".TABLE_PREFIX."mod_cscraper` (`page_id`,`section_id`) VALUES ('0','0')");

?>