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

$URL = $_SESSION[$guid]['absoluteURL'].'/index.php?q=/modules/Lost Property/lostProperty_submit.php';
if (isActionAccessible($guid, $connection2, '/modules/Lost Property/lostProperty_submit.php') == false) {
  //Acess denied
  $URL .= '&return=error0';
  header("Location: {$URL}");
}
else{
  //get stuff from lostProperty_submit.php
  $type = $_POST['atype'];
  $addate = $_POST['addate'];
  $where = empty($_POST['wherelost']) ? null : $_POST['wherelost'];
  $date1 = $_POST['Date1'];
  $date2 = $_POST['Date2'];
  $text = $_POST['information'];
  //get file if uploaded
  if (!empty($_FILES['file']['tmp_name'])) {
    $fileUploader = new Gibbon\FileUploader($pdo, $gibbon->session);
    $file = (isset($_FILES['file']))? $_FILES['file'] : null;
    // Upload the file, return the /uploads relative path
    $attachment = $fileUploader->uploadFromPost($file, $_SESSION[$guid]['username']);
  }
  else{
    $attachment= null;
  }
  $timesubmit = date('Y-m-d H:i:s');
  try {
    $data = array('gibbonPersonID' => $_SESSION[$guid]['gibbonPersonID']);
    $sql = 'SELECT submitime FROM lostPropertyItem WHERE gibbonPersonID=:gibbonPersonID ORDER BY submitime DESC LIMIT 0, 1';
    $result = $connection2->prepare($sql);
    $result->execute($data);
  } catch (PDOException $e){
    $URL .= '&return=error2';
    header("Location: {$URL}");
    exit();
  }

  if ($result->rowCount() == 1) {
    $row = $result->fetch();
    $date3 = new DateTime($row['submitime']);
    $date4 = new DateTime($timesubmit);
    $dateDiffer = $date3->diff($date4);
    if ($dateDiffer->format('%D') < 1) {
      $URL .= "&return=error1";
      header("Location: {$URL}");
      exit();
    }
  }

  try {
    //INSERT INTO database
    $data = array('gibbonPersonID' => $_SESSION[$guid]['gibbonPersonID'], 'item' => $type, 'daterange1' => dateConvert($guid, $date1), 'daterange2' => dateConvert($guid, $date2), 'place' => $where, 'characteristics' => $text, 'file' => $attachment,
    'submitime' => $timesubmit);
    $sql = "INSERT INTO lostPropertyItem SET gibbonPersonID=:gibbonPersonID, item=:item, daterange1=:daterange1, daterange2=:daterange2, place=:place, characteristics=:characteristics, file=:file, submitime=:submitime";
    $result = $connection2->prepare($sql);
    $result->execute($data);
  } catch (PDOException $e){
    $URL .= '&return=error2';
    header("Location: {$URL}");
    exit();
  }

  try {
    $sql = "SELECT gibbonPersonID FROM gibbonStaff";
    $result = $connection2->query($sql);
    $row = $result->fetch();
  } catch (PDOException $e){
    $URL .= '&return=error2';
    header("Location: {$URL}");
    exit();
  }
  //notify staffs
  $text = sprintf(__m('%1$s has reported a lost property. Please see if you can find it, thank you for your time.'), $_SESSION[$guid]['preferredName'].' '.$_SESSION[$guid]['surname']);
  $actionLink = "/index.php?q=/modules/Lost Property/lostProperty_manage.php";
  setNotification($connection2, $guid, $row['gibbonPersonID'], $text, 'Lost Property', $actionLink);
  $URL .= "&return=success1";
  header("Location: {$URL}");
}
