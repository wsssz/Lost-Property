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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

function delete_lost_property_report($lostPropertyID, $connection2, $URL, $message = false){

  try {
      $data = array('lostPropertyID' => $lostPropertyID);
      $sql = 'SELECT * FROM lostPropertyItem WHERE lostPropertyID=:lostPropertyID';
      $result = $connection2->prepare($sql);
      $result->execute($data);
  } catch (PDOException $e) {
    $URL .= $message == true ? '&return=warning2' : '&return=error2';
    header("Location: {$URL}");
    exit();
  }

  if ($result->rowCount() != 1) {
    $URL .= $message == true ? '&return=warning2' : '&return=error2';
    header("Location: {$URL}");
    exit();
  }
    //Write to database
  try {
      $data = array('lostPropertyID' => $lostPropertyID);
      $sql = 'DELETE FROM lostPropertyItem WHERE lostPropertyID=:lostPropertyID';
      $result = $connection2->prepare($sql);
      $result->execute($data);
  } catch (PDOException $e) {
    $URL .= $message == true ? '&return=warning2' : '&return=error2';
    header("Location: {$URL}");
    exit();
  }
}

function implode_array_or($array){

  if (count($array) == 1) {
    $_SESSION['imploded'] = current($array);
  }
  else {
    $copy = $array;
    unset($copy[count($copy) - 1]);
    $_SESSION['imploded'] = implode(', ', $copy).', '.__('or').' '.$array[count($array) - 1];
  }
}
