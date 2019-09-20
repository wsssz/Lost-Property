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
use Gibbon\Forms\Prefab\BulkActionForm;
use Gibbon\Tables\DataTable;
use Gibbon\Module\LostProperty\Domain\LostPropertyGateway;

if (isActionAccessible($guid, $connection2, '/modules/Lost Property/lostProperty_manage.php') == false) {
  //Acess denied
  echo "<div class='error'>";
  echo __('You do not have access to this action.');
  echo '</div>';
}
else{

  $page->breadcrumbs->add(__m('Manage Lost Property Reports'));

  $returns = array();
  $returns['success1']= __m('Your notification has been successfully sent and the report has been deleted.');
  $returns['warning2'] = __m('Your notification has been sent successfully, but the report has not been deleted due to a database error.');
  if (isset($_GET['return'])) {
    returnProcess($guid, $_GET['return'], null, $returns);
  }
  
  echo '<h2>'.__m('Manage Lost Property Reports').'</h2>';
  echo '<p>'.__m("On this page, staffs can manage lost propety reports. If the lost property is found, you can click on the present icon to send a notification back to the submitter.
  If a lost property has been reported for long, you may delete it.").'</p>';
  $lostPropertyGateway = $container->get(LostPropertyGateway::class);
  $search = isset($_REQUEST['search'])? $_REQUEST['search'] : '';

  //QUERY
  $criteria = $lostPropertyGateway->newQueryCriteria()
    ->searchBy($lostPropertyGateway->getSearchableColumns(), $search)
    ->sortBy('submitime', 'DESC')
    ->fromPOST();

  //SEARCH
  $form = Form::create('searchForm', $_SESSION[$guid]['absoluteURL'].'/index.php?q=/modules/'.$_SESSION[$guid]['module'].'/lostProperty_manage.php');
  $form->setClass('noIntBorder fullWidth');

  $row = $form->addRow();
    $row->addLabel('search', __m('Search Lost Property'))->description(__m('Searches the item column'));
    $row->addTextField('search')->setValue($criteria->getSearchText());

  $row = $form->addRow();
    $row->addSearchSubmit($gibbon->session, __('Clear Search'));

  echo $form->getOutput();

  echo '<h2>'.__('Reports').'</h2><br/>';

  $lostProperty = $lostPropertyGateway->querylostProperty($criteria);

  //Bulk Action
  $form = BulkActionForm::create('bulkAction', $_SESSION[$guid]['absoluteURL'] . '/modules/'.$_SESSION[$guid]['module'].'/lostProperty_manage_deleteProcess.php');

  $bulkActions = array('Delete'  => __('Delete'));
  $col = $form->createBulkActionColumn($bulkActions);
  $col->addSubmit(__('Go'));

  //DATA TABLE
  $table = $form->addRow()->addDataTable('lostProperty', $criteria)->withData($lostProperty);

  $table->addMetaData('bulkActions', $col);
  $table->addExpandableColumn('characteristics')->format(function($lostitem){
    $output = '<p id= "description"><strong>'.__('Description').'</strong></p>';
    $output .= nl2brr($lostitem['characteristics']).'<br/>';
    return $output;
  });
  $table->addColumn('submitter', __('Submitter'))
    ->sortable(['submitter.surname', 'submitter.preferredName'])
    ->format(function($person){
        return '<b>'.$person['preferredName'].', '.$person['surname'].'</b>';
    });
  $table->addColumn('item', __('Item'));
  $table->addColumn('submitime', __m('Submit time'));
  $table->addColumn('place', __m('Place'))
    ->format(function($lostitem){
      $output = !is_null($lostitem['place']) ? $lostitem['place'] : __('Unknown');
      return $output;
    });
  $table->addColumn('date range', __m('Date'))
    ->sortable(['lostPropertyItem.daterange1'])
    ->format(function($lostitem){
        if ($lostitem['daterange1'] == 0000-00-00){
          $output = __('Unknown');
        }
        else if ($lostitem['daterange1'] == $lostitem['daterange2']){
          $output = $lostitem['daterange1'];
        }
        else {
          $output = $lostitem['daterange1'].' to '.$lostitem['daterange2'];
        }
        return $output;
      });
  $table->addColumn('file', __('File'))
    ->format(function($lostitem) use ($guid){
        $output = !is_null($lostitem['file']) ? "<a target='_blank' href='".$_SESSION[$guid]['absoluteURL'].'/'.$lostitem['file']."'>".__($guid, 'View').'</>' : __('None');
        return $output;
      });
  $table->addActionColumn()
    ->addParam('lostPropertyID')
    ->format(function($row, $actions){
        $actions->addAction('sendmessage', __m('Item found!'))
          ->setURL('/modules/Lost Property/lostProperty_manage_sendMessage.php')
          ->setIcon('gift_pink');
        $actions->addAction('delete', __('Delete'))
          ->setURL('/modules/Lost Property/lostProperty_manage_delete.php');
      });
  $table->addCheckboxColumn('lostPropertyID');

  echo $form->getOutput();
}
