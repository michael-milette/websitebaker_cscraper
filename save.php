<?php
/**
  Website Baker Module: cScraper
  Developed for the Open Source Content Management System WebsiteBaker (http://websitebaker.org).
  Copyright (C) 2011 TNG Consulting Inc. All rights reserved.
  Contact: http://www.tngconsulting.ca

  Langugage: PHP
  Purpose: Backend - Saves modified settings and parsed HTML to database.

  Licence: The contents of this file is free software.
    You can redistribute and/or modify them under the terms of the
    GNU General Public License - version 3 or later, as published
    by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  Warranty: The contents of this file is distributed in the hope that it
    will be useful, but WITHOUT ANY WARRANTY; without even the implied
    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
**/

// manually include the config.php file (defines the required constants)
require('../../config.php');

function ParseHTML($sTagStart,$sTagEnd,$sHTML)
{
   if($sHTML > "") {
     $iStartPos = stripos($sHTML,$sTagStart);
     $iStartPos = ($sTagStart > "" and $iStartPos > 0) ? $iStartPos + strlen($sTagStart):0;
     $iEndPos   = ($sTagEnd   > "") ? stripos($sHTML,$sTagEnd,$iStartPos):strlen($sHTML);
     return substr($sHTML,$iStartPos,$iEndPos - $iStartPos);
   } else {
     return "";
   }
}

// tell the admin wrapper to actualize the DB settings when this page was last updated
$update_when_modified = true;
// include the admin wrapper script (includes framework/class.admin.php)
require(WB_PATH.'/modules/admin.php');

// get data send via POST using function defined in framework/class.wb.php (equivalent to $_POST['cscraper'])
// $admin->get_post prevents output of warnings if the specified value does not exist
$cs_url      = strip_tags($admin->get_post('url'));
$cs_start    = $admin->get_post('start');
$cs_end      = $admin->get_post('end');
$cs_search   = $admin->get_post('search');
$cs_replace  = $admin->get_post('replace');
$cs_search2  = $admin->get_post('search2');
$cs_replace2 = $admin->get_post('replace2');
$cs_search3  = $admin->get_post('search3');
$cs_replace3 = $admin->get_post('replace3');
$cs_content  = $admin->get_post('content');
$cs_scraper  = $admin->get_post('scrape');

// escape special characters (', ", \, NULL byte) before  writing to the database to prevent SQL-injections!!!
// make use of add_slashes (defined in framework/class.wb.php) to prevent double escaping of data derived 
// via GET, POST or COOKIES, if magic_quotes_gpc is enabled in the php.ini!!!
if($cs_scraper) {
  $cs_content = @file_get_contents($cs_url);
  if($cs_content > "") {
    $cs_content = ParseHTML(stripslashes($cs_start),stripslashes($cs_end),$cs_content);
    $cs_content = str_replace(stripslashes($cs_search ),stripslashes($cs_replace ),$cs_content);
    $cs_content = str_replace(stripslashes($cs_search2),stripslashes($cs_replace2),$cs_content);
    $cs_content = str_replace(stripslashes($cs_search3),stripslashes($cs_replace3),$cs_content);
  }
}

$cs_url      = mysql_real_escape_string($cs_url);
$cs_content  = mysql_real_escape_string($cs_content);
$cs_start    = mysql_real_escape_string($cs_start);
$cs_end      = mysql_real_escape_string($cs_end);
$cs_search   = mysql_real_escape_string($cs_search);
$cs_replace  = mysql_real_escape_string($cs_replace);
$cs_search2  = mysql_real_escape_string($cs_search2);
$cs_replace2 = mysql_real_escape_string($cs_replace2);
$cs_search3  = mysql_real_escape_string($cs_search3);
$cs_replace3 = mysql_real_escape_string($cs_replace3);

// now write the text to the database, add unix timestamp to store modification date and time
$sql_query = "UPDATE `" .TABLE_PREFIX ."mod_cscraper` SET `url`='$cs_url',`start`='$cs_start',`end`='$cs_end',`search`='$cs_search',`replace`='$cs_replace',`search2`='$cs_search2',`replace2`='$cs_replace2',`search3`='$cs_search3',`replace3`='$cs_replace3',`content`='$cs_content',`modified_when` = '".time()."' WHERE `section_id`='$section_id'";
$database->query($sql_query);

// check if there is a database error, otherwise say successful (functions defined or included via modules/admin.php)
if($database->is_error()) {
	$admin->print_error($database->get_error(), $js_back);
} else {
	$admin->print_success($MESSAGE['PAGES']['SAVED'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// print admin footer
$admin->print_footer()

?>