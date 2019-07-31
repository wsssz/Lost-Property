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

use Gibbon\Forms\Form;

if (isActionAccessible($guid, $connection2, '/modules/Lost Property/lostProperty_manage_sendMessage.php') == false) {
    //Acess denied
    echo "<div class='error'>";
    echo __('You do not have access to this action.');
    echo '</div>';
}
else {
  $page->breadcrumbs->add(__m('Manage Lost Property Reports'), 'lostProperty_manage.php')
                    ->add(__m('Send notification back to submitter'));
  $lostPropertyID = $_GET['lostPropertyID'];

  if (isset($_GET['return'])) {
      returnProcess($guid, $_GET['return'], null, null);
  }
  if ($lostPropertyID == '') {
    echo "<div class='error'>";
    echo __('You have not specified one or more required parameters.');
    echo '</div>';
  }
  else {
    try {
      $data = array('lostPropertyID' => $lostPropertyID);
      $sql = 'SELECT * FROM lostPropertyItem WHERE lostPropertyID=:lostPropertyID';
      $result = $connection2->prepare($sql);
      $result->execute($data);
    } catch (PDOException $e) {
      echo "<div class='error'>".$e->getMessage().'</div>';
    }
    if ($result->rowCount() != 1) {
      echo "<div class='error'>";
      echo __('The selected record does not exist, or you do not have access to it.');
      echo '</div>';
    }
    else {

      echo '<h2>'.__m('The lost property has been found!').'</h2>';
      echo '<p>'.__m('Please provide the owner with the following information.').'</p>';

      //create form
      $form = Form::create('action', $_SESSION[$guid]['absoluteURL']."/modules/Lost Property/lostProperty_manage_sendMessageProcess.php?lostPropertyID=$lostPropertyID");
      $form->addHiddenValue('address', $_SESSION[$guid]['address']);

      $whichday = array(
        __m('today'),
        __m('tomorrow'),
        __m('monday'),
        __m('tuesday'),
        __m('wednesday'),
        __m('thursday'),
        __m('friday'),
      );
      $whatime = array(
        __m('tutor time'),
        __m('break 1'),
        __m('break 2'),
        __m('the end of school'),
      );
      //found in where
      $row = $form->addRow();
        $row->addLabel('place', __m('Where was the lost property found?'));
        $row->addTextField('place');
      //collect in where
      $row = $form->addRow();
        $row->addLabel('collect', __m('Where should the submitter collect it?'));
        $row->addTextField('collect')->isRequired();
      //what day/days
      $row = $form->addRow();
        $row->addLabel('whatday', __m('On which day/days can the submitter collect it?'))->description(__m('You must at least select one'));
        $row->addCheckbox('whatday')->fromArray($whichday)->addCheckAllNone()->isRequired();
      //what time
      $row = $form->addRow();
        $row->addLabel('whattime', __m('At what time can the submitter come on the day/days you selected just now?'))->description(__m('You must at least select one'));
        $row->addCheckbox('whattime')->fromArray($whatime)->addCheckAllNone()->isRequired();
      //submit
      $row = $form->addRow();
        $row->addFooter();
        $row->addSubmit(__m('Send notification'));
      echo $form->getOutput();
    }
  }
}
