<?php
/**
  Website Baker Module: cScraper
  Developed for the Open Source Content Management System WebsiteBaker (http://websitebaker.org).
  Copyright (C) 2011 TNG Consulting Inc. All rights reserved.
  Contact: http://www.tngconsulting.ca

  Langugage: PHP
  Purpose: Backend - Modify settings for a particular section of a page.

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

// include core functions of WB 2.7 to edit the optional module CSS files (frontend.css, backend.css)
@include_once(WB_PATH .'/framework/module.functions.php');

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
*	INLCUDE BACKEND.CSS INTO THE HTML BODY OF THE PAGE IF WB < 2.6.6
*	NOTE: THIS WAY MODULES BECOME DOWNWARD COMPATIBLE WITH OLDER WB RELEASES
*/
// check if backend.css file needs to be included into the <body></body> of modify.php
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH .'/modules/cscraper/backend.css')) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/cscraper/backend.css');
	echo "\n</style>\n";
}

// obtain data from module DB-table of the current displayed page (unique page defined via section_id)
$sql_result = $database->query("SELECT * FROM `" .TABLE_PREFIX ."mod_cscraper` WHERE `section_id` = '$section_id'");

// store all results (fields) in the array $sql_row
$sql_row = $sql_result->fetchRow();

// check if the DB-Field simple_output contains a value, if not use Content Scraper as default value.
// Note: before displaying a string in a text field, one needs to convert special characters into entities.
// Characters like " do not show up in text fields if not converted to entities.
// This also prevents that embedded Javascript/PHP/HTML tags are parsed by the browser.
$sql_row['url']     = htmlspecialchars(stripslashes($sql_row['url']));
$sql_row['start']   = htmlspecialchars(stripslashes($sql_row['start']));
$sql_row['end']     = htmlspecialchars(stripslashes($sql_row['end']));
$sql_row['search']  = htmlspecialchars(stripslashes($sql_row['search']));
$sql_row['replace'] = htmlspecialchars(stripslashes($sql_row['replace']));
$sql_row['search2']  = htmlspecialchars(stripslashes($sql_row['search2']));
$sql_row['replace2'] = htmlspecialchars(stripslashes($sql_row['replace2']));
$sql_row['search3']  = htmlspecialchars(stripslashes($sql_row['search3']));
$sql_row['replace3'] = htmlspecialchars(stripslashes($sql_row['replace3']));
$sql_row['content'] = htmlspecialchars(stripslashes($sql_row['content']));

// write out heading
echo '<h2>' .$MOD_CSCRAPER['TXT_HEADING_B'] .'</h2>';

// include the button to edit the optional module CSS files (function added with WB 2.7)
// Note: CSS styles for the button are defined in backend.css (div class="mod_moduledirectory_edit_css")
// Remember to replace the string cscraper below with the module directory of your module
// Place this call outside of any <form></form> construct!!!
if(function_exists('edit_module_css')) {
	edit_module_css('cscraper');
}

// create the form with text outputs and buttons below using mixed HTML and PHP code
?>
<form name="edit" action="<?php echo WB_URL; ?>/modules/cscraper/save.php" method="post" style="margin: 0;">
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">

	<table class="mod_cscraper_table_b" cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr><td valign="top" width="70px"></td></tr>
		<tr>
			<td valign="top"><h2><? echo $TEXT['WEBSITE']; ?>: </h2></td>
			<td><input name="url" type="text" value="<?php echo $sql_row['url']; ?>" style="width: 55%;"> <?php echo $MOD_CSCRAPER['TXT_URL_EXAMPLE']; ?></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td valign="top" colspan="2"><h2>Filters</h2>
    <table cellpadding="0" cellspacing="0" border="0" width="95%">
      <tr>
        <td width="70px"><?php echo $TEXT['START']; ?>:</td><td><input name="start" type="text" value="<?php echo $sql_row['start']; ?>" style="width:95%;"></td>
        <td width="70px"><?php echo $TEXT['END'];   ?>:</td><td><input name="end"   type="text" value="<?php echo $sql_row['end'];   ?>" style="width:95%;"></td>
      </tr>
      <tr>
        <td><?php echo $TEXT['SEARCH'];              ?>:</td><td><input name="search"  type="text" value="<?php echo $sql_row['search'];  ?>" style="width:95%;"></td>
        <td><?php echo $MOD_CSCRAPER['TXT_REPLACE']; ?>:</td><td><input name="replace" type="text" value="<?php echo $sql_row['replace']; ?>" style="width:95%;"></td>
      </tr>
      <tr>
        <td><?php echo $TEXT['SEARCH'];              ?>:</td><td><input name="search2"  type="text" value="<?php echo $sql_row['search2'];  ?>" style="width:95%;"></td>
        <td><?php echo $MOD_CSCRAPER['TXT_REPLACE']; ?>:</td><td><input name="replace2" type="text" value="<?php echo $sql_row['replace2']; ?>" style="width:95%;"></td>
      </tr>
      <tr>
        <td><?php echo $TEXT['SEARCH'];              ?>:</td><td><input name="search3"  type="text" value="<?php echo $sql_row['search3'];  ?>" style="width:95%;"></td>
        <td><?php echo $MOD_CSCRAPER['TXT_REPLACE']; ?>:</td><td><input name="replace3" type="text" value="<?php echo $sql_row['replace3']; ?>" style="width:95%;"></td>
      </tr>
    </table>
    </td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2"><h2><? echo $TEXT['MODIFY_CONTENT']; ?></h2></td></tr>
		<tr>
			<td valign="top"><?php echo $TEXT['TEXT']; ?>:</td>
      <td><textarea name="content" rows="10" style="width:95%;"><?php echo $sql_row['content']; ?></textarea><br />
      <input name="scrape" type="checkbox"><?php echo $MOD_CSCRAPER['TXT_SCRAPE_HELP']; ?> (<? echo $MOD_CSCRAPER['TXT_MODIFIED_F'] .date($MOD_CSCRAPER['DATE_FORMAT_F'], $sql_row['modified_when']); ?>)
      </td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
	</table>

	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="left">
			<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;">
		</td>
		<td align="right">
			<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/index.php'; return false;" style="width: 100px; margin-top: 5px;" />
		</td>
	</tr>
  </table>
</form>
