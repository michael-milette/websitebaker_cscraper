<?php
/**
  Website Baker Module: cScraper
  Developed for the Open Source Content Management System WebsiteBaker (http://websitebaker.org).
  Copyright (C) 2011 TNG Consulting Inc. All rights reserved.
  Contact: http://www.tngconsulting.ca

  Langugage: PHP
  Purpose: Frontend - Displays content in webpage.

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

/**
*	MODULE LANGUAGE SUPPORT IS INTRODUCED WITH THE LINES BELOW
*	NOTE: IF YOU ADD LANGUAGE SUPPORT TO YOUR MODULE, MAKE SURE THAT THE DEFAULT LANGUAGE
*	EN.. ENGLISH IS AVAILABLE IN ANY CASE
*/
// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/cscraper/languages/' .LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(WB_PATH .'/modules/cscraper/languages/EN.php');
} else {
	// a module language file exists for the language defined by the user, load it
		require_once(WB_PATH .'/modules/cscraper/languages/' .LANGUAGE .'.php');
}

/**
*	INLCUDE FRONTEND.CSS INTO THE HTML BODY OF THE PAGE IF WB < 2.6.7 OR REGISTER_MODFILES FUNCTION
* IN THE INDEX.PHP OF YOUR TEMPLATE IS NOT USED
*	NOTE: THIS WAY MODULES BECOME DOWNWARD COMPATIBLE WITH OLDER WB RELEASES
*/
// check if frontend.css file needs to be included into the <body></body> of view.php
if((!function_exists('register_frontend_modfiles') || !defined('MOD_FRONTEND_CSS_REGISTERED')) && 
	file_exists(WB_PATH .'/modules/cscraper/frontend.css')) {
	echo '<style type="text/css">';
  include(WB_PATH .'/modules/cscraper/frontend.css');
  echo "\n</style>\n";
} 

/**
*	THE FUNCTIONS AND SETTINGS OF YOUR MODULE IN THE WB FRONTED ARE DEFINED BELOW THIS LINE HERE
*
*	The code below extracts the text stored in the database, removes the contained slashes and
*	outputs the clean text to the view.php. Some CSS defininitions are used to demonstrate the
*	usage of the external frontend.css file.
*/

// obtain data from module DB-table of the current displayed page (unique page defined via section_id)
$sql_result = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_cscraper` WHERE `section_id` = '$section_id'");

// check if query was sucessfull
if($sql_result->numRows() > 0) {
	// store all results (fields) in the array $sql_row
	$sql_row = $sql_result->fetchRow();

	// NOTE: if you do not want that PHP/Javascript code is parsed in the frontend, make use of strip_tags, htmlspecialchars
	// strip_tags not really required here, as tags were already removed in save.php
	$content = $sql_row['content'];

	// extract modification date/time (convert timestamp into human readable format using format string in language file)
	$last_modified = date($MOD_CSCRAPER['DATE_FORMAT_F'], $sql_row['modified_when']);
} else {
	$content = '';
	$last_modified = '';
}

if($content != '') {
	// output the cscraper text from the database and the text defined in the language file
	echo stripslashes($content);
	// write out modification time
	//if($last_modified != "") {
	//	echo "<p>".$MOD_CSCRAPER['TXT_MODIFIED_F'] .$last_modified."</p>";
	//}

} else { 
	// no database entry found show a warning
	echo '<p class="mod_cscraper_warning_f">' .$MOD_CSCRAPER['TXT_ERROR_F'] .'</p>';
}
?>