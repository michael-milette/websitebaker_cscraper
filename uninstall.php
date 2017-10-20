<?php
/**
  Website Baker Module: cScraper
  Developed for the Open Source Content Management System WebsiteBaker (http://websitebaker.org).
  Copyright (C) 2011 TNG Consulting Inc. All rights reserved.
  Contact: http://www.tngconsulting.ca

  Langugage: PHP
  Purpose: Backend - Executed when uninstalling the module. Drops tables used
    by this module.

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

// delete all database search table entries made by this module
$database->query("DELETE FROM `" .TABLE_PREFIX ."search` WHERE `name` = 'module' AND `value` = 'cscraper'");
$database->query("DELETE FROM `" .TABLE_PREFIX ."search` WHERE `extra` = 'cscraper'");

// delete the module database table
$database->query("DROP TABLE `" .TABLE_PREFIX ."mod_cscraper`");

?>