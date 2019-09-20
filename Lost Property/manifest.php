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

//This file describes the module, including database tables

//Basic variables
$name="Lost Property" ; //The name of the variable as it appears to users. Needs to be unique to installation. Also the name of the folder that holds the unit.
$description="Allow people to report lost properties and staffs to manage them." ; //Short text description
$entryURL="lostProperty_submit.php" ; //The landing page for the unit, used in the main menu
$type="Additional" ; //Do not change.
$category="Other" ; //The main menu area to place the module in
$version="0.0.00" ; //Verson number
$author="Wesley Sze" ; //Your name
$url="" ; //Your URL

//Module tables & gibbonSettings entries
$moduleTables[0]="CREATE TABLE lostPropertyItem (lostPropertyID INTEGER PRIMARY KEY AUTO_INCREMENT, gibbonPersonID INTEGER(10) NOT NULL, item varchar(50) NOT NULL, place varchar(20) NULL DEFAULT NULL, submitime TIMESTAMP NOT NULL, daterange1 DATE NULL DEFAULT NULL, daterange2 DATE NULL DEFAULT NULL, characteristics VARCHAR(1000) NOT NULL, file varchar(300) NULL DEFAULT NULL)" ;
//One array entry for every database table you need to create. Might be nice to preface the table name with the module name, to keep the db neat.
//Also can be used to put data into gibbonSettings. Other sql can be run, but resulting data will not be cleaned up on uninstall.

//Action rows
//One array per action
$actionRows[0]["name"]="Lost Property Report" ; //The name of the action (appears to user in the right hand side module menu)
$actionRows[0]["precedence"]="1"; //If it is a grouped action, the precedence controls which is highest action in group
$actionRows[0]["category"]="Report Lost Property" ; //Optional: subgroups for the right hand side module menu
$actionRows[0]["description"]="Users can go to this page to report lost properties." ; //Text description
$actionRows[0]["URLList"]="lostProperty_submit.php" ; //List of pages included in this action
$actionRows[0]["entryURL"]="lostProperty_submit.php" ; //The landing action for the page.
$actionRows[0]["defaultPermissionAdmin"]="Y" ; //Default permission for built in role Admin
$actionRows[0]["defaultPermissionTeacher"]="Y" ; //Default permission for built in role Teacher
$actionRows[0]["defaultPermissionStudent"]="Y" ; //Default permission for built in role Student
$actionRows[0]["defaultPermissionParent"]="N" ; //Default permission for built in role Parent
$actionRows[0]["defaultPermissionSupport"]="Y" ; //Default permission for built in role Support
$actionRows[0]["categoryPermissionStaff"]="Y" ; //Should this action be available to user roles in the Staff category?
$actionRows[0]["categoryPermissionStudent"]="Y" ; //Should this action be available to user roles in the Student category?
$actionRows[0]["categoryPermissionParent"]="N" ; //Should this action be available to user roles in the Parent category?
$actionRows[0]["categoryPermissionOther"]="N" ; //Should this action be available to user roles in the Other category?

$actionRows[1]["name"]="Manage Lost Property Reports" ; //The name of the action (appears to user in the right hand side module menu)
$actionRows[1]["precedence"]="2"; //If it is a grouped action, the precedence controls which is highest action in group
$actionRows[1]["category"]="Manage Reports" ; //Optional: subgroups for the right hand side module menu
$actionRows[1]["description"]="Staffs may view lost property reports and manage them." ; //Text description
$actionRows[1]["URLList"]="lostProperty_manage.php, lostProperty_manage_sendMessage.php" ; //List of pages included in this action
$actionRows[1]["entryURL"]="lostProperty_manage.php" ; //The landing action for the page.
$actionRows[1]["defaultPermissionAdmin"]="Y" ; //Default permission for built in role Admin
$actionRows[1]["defaultPermissionTeacher"]="Y" ; //Default permission for built in role Teacher
$actionRows[1]["defaultPermissionStudent"]="N" ; //Default permission for built in role Student
$actionRows[1]["defaultPermissionParent"]="N" ; //Default permission for built in role Parent
$actionRows[1]["defaultPermissionSupport"]="Y" ; //Default permission for built in role Support
$actionRows[1]["categoryPermissionStaff"]="Y" ; //Should this action be available to user roles in the Staff category?
$actionRows[1]["categoryPermissionStudent"]="N" ; //Should this action be available to user roles in the Student category?
$actionRows[1]["categoryPermissionParent"]="N" ; //Should this action be available to user roles in the Parent category?
$actionRows[1]["categoryPermissionOther"]="N" ; //Should this action be available to user roles in the Other category?

?>
