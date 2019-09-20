<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

include '../../gibbon.php';
require_once __DIR__ . '/moduleFunctions.php';

$URL = $_SESSION[$guid]['absoluteURL'].'/index.php?q=/modules/Lost Property/lostProperty_manage.php';
$lostPropertyID = $_REQUEST['lostPropertyID'];
if (isActionAccessible($guid, $connection2, '/modules/Lost Property/lostProperty_manage.php') == false) {
  $URL .= '&return=error0';
  header("Location: {$URL}");
}
else {
  if(is_array($lostPropertyID)){
    if (count($lostPropertyID) < 1) {
        $URL .= '&return=error3';
        header("Location: {$URL}");
        exit();
    }
    else {
      $lostProperty = $lostPropertyID;
      foreach ($lostProperty as $lostPropertyID) {
        delete_lost_property_report($lostPropertyID, $connection2, $URL);
      }
    }
  }
  else {
    delete_lost_property_report($lostPropertyID, $connection2, $URL);
  }

  $URL .= '&return=success0';
  header("Location: {$URL}");
}
