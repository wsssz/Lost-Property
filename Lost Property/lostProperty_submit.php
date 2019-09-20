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

if (isActionAccessible($guid, $connection2, '/modules/Lost Property/lostProperty_submit.php') == false) {
  //Acess denied
  echo "<div class='error'>";
  echo __('You do not have access to this action.');
  echo '</div>';
}
else{

  $page->breadcrumbs->add(__m('Lost Property Report'));
  $returns = array();
  $returns['success1']= __m('Your lost property has been reported successfully. For our convenice, you cannot report another lost property in the next 24 hours.');
  $returns['error1']= __m('Your request failed because you have already reported a lost property in the past 24 hours');
  if (isset($_GET['return'])) {
    returnProcess($guid, $_GET['return'], null, $returns);
  }

  echo '<h2>'.__m('Lost Property Report').'</h2>';
  echo '<p>'.__m('On this page, you can report your lost properties.').'<br/><br/>'.
  __m('As a reminder, if you find properties that belong to other people, please hand them in to staffs.').'</p>';

//create form
  $form = Form::create('action', $_SESSION[$guid]['absoluteURL'].'/modules/'.$_SESSION[$guid]['module'].'/lostProperty_submitProcess.php');
  $form->addHiddenValue('address', $_SESSION[$guid]['address']);
//what is lost
  $row = $form->addRow();
    $row->addLabel('atype', __('Item'))->description(__m('What did you lose?'));
    $row->addTextField('atype')->isRequired()->placeholder();
//where is it lost
  $row = $form->addRow();
    $row->addLabel('wherelost', __m("Where did you lost it?"))->description(__('Optional'));
    $row->addTextField('wherelost')->placeholder();
//select date
  $row = $form->addRow();
    $row->addLabel('addate', __m("Do you know when was it lost?"));
    $row->addYesNo('addate')->isRequired()->placeholder();
//date 1
  $form->toggleVisibilityByClass('daterange')->onSelect('addate')->when('Y');
  $row = $form->addRow()->setClass('daterange');
    $row->addLabel('Date1', __('From Date'))->description(__m('If you know the exact date, put the same one in the 2 boxes.'));
    $row->addDate('Date1')->isRequired();
//date 2
  $row = $form->addRow()->setClass('daterange');
    $row->addLabel('Date2', __('To Date'));
    $row->addDate('Date2')->isRequired();
//description
  $row = $form->addRow();
    $row->addLabel('information', __('Description'))->description(__m('Describe your lost property so that it can be identified, such as its brand, its appearance and more.'));
    $row->addTextArea('information')->setRows(5)->isRequired();
//file
  $files = "'.png','.jpg','.pdf'";
  $row = $form->addRow();
    $row->addLabel('file', __('File'))->description(__m('Upload an image of your lost property for better identification. (Optional)'));
    $row->addFileUpload('file')->setMaxUpload(7)->accepts($files);
//submit
  $row = $form->addRow();
    $row->addFooter();
    $row->addSubmit();
  echo $form->getOutput();
}
