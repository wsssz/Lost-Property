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

$lostPropertyID = $_GET['lostPropertyID'];
$URL = $_SESSION[$guid]['absoluteURL']."/index.php?q=/modules/Lost Property/lostProperty_manage_sendMessage.php&lostPropertyID=$lostPropertyID";
$URLManage = $_SESSION[$guid]['absoluteURL']."/index.php?q=/modules/Lost Property/lostProperty_manage.php";
if (isActionAccessible($guid, $connection2, '/modules/Lost Property/lostProperty_manage_sendMessage.php') == false) {
    $URL .= '&return=error0';
    header("Location: {$URL}");
}
else {
  try {
    $data = array('lostPropertyID' => $lostPropertyID);
    $sql = 'SELECT * FROM lostPropertyItem WHERE lostPropertyID=:lostPropertyID';
    $result = $connection2->prepare($sql);
    $result->execute($data);
    $row = $result->fetch();
  } catch (PDOException $e) {
    $URL .= '&return=error2';
    header("Location: {$URL}");
    exit();
  }
  if ($result->rowCount() != 1) {
    $URL .= '&return=error2';
    header("Location: {$URL}");
  }
  else {
    $where = $_POST['place'];
    $collect = $_POST['collect'];
    $whereOut = empty($where) ? null : ' '.sprintf(__('in %1$s'), $where);
    $day = $_POST['whatday'];
    $time = $_POST['whattime'];
    if (count($time) < 1 or count($day) < 1) {
      $URL .= '&return=error1';
      header("Location: {$URL}");
    }
    else {
      implode_array_or($time);
      $truetime = $_SESSION['imploded'];

      if (count($day) == 7){
        $trueday = __m('any day');
      }
      else{
        implode_array_or($day);
        $trueday = $_SESSION['imploded'];
      }

      $text = sprintf(__m('Your lost Property has been found%1$s,'), $whereOut).' ';
      $text .= $where == $collect
      ? sprintf(__m('please go to collect it during %1$s on %2$s.'), $truetime, $trueday)
      : sprintf(__m('please come to collect it in %1$s during %2$s on %3$s.'), $collect, $truetime, $trueday);
      setNotification($connection2, $guid, $row['gibbonPersonID'], $text, 'Lost Property', '');
      delete_lost_property_report($lostPropertyID, $connection2, $URLManage, true);

      $URLManage .= '&return=success1';
      header("Location: {$URLManage}");
    }
  }
}
