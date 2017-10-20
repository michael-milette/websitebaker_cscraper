<?php
/**
  Website Baker Module: cScraper
  Developed for the Open Source Content Management System WebsiteBaker (http://websitebaker.org).
  Copyright (C) 2011 TNG Consulting Inc. All rights reserved.
  Contact: http://www.tngconsulting.ca

  Langugage: PHP
  Purpose: Backend - To delete a page section.

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

//	delete the row of the module table which contains the actual page
$database->query("DELETE FROM `" .TABLE_PREFIX ."mod_cscraper` WHERE `section_id` = '$section_id'");

?>