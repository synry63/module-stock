<?php
/* 
 * Copyright (C) 2012      Patrick Mary           <laube@hotmail.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * 	\file       
 * 	\ingroup    
 * 	\brief      
 * 	\version    
 */
$res=@include("../main.inc.php");					// For root directory
if (! $res) $res=@include("../../main.inc.php");	// For "custom" directory
dol_include_once("/stock/class/stock.class.php");
$object = new Stock($couch);

if($_GET['tracking']!="" && $_GET['mouv']!=""){
    $object->create($_GET['tracking'],$_GET['mouv'],$user);
}

$result = $object->getList();
$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => 0,
    "iTotalDisplayRecords" => 0,
    "aaData" => array()
);
if($result){
    $iTotal=  count($result->rows);
    $output["iTotalRecords"]=$iTotal;
    $output["iTotalDisplayRecords"]=$iTotal;

    foreach($result->rows AS $aRow){
        unset($aRow->value->class);
        unset($aRow->value->_rev);

        $output["aaData"][]=$aRow->value;
        unset($aRow);
    }
 /*   for($i=$iTotal;$i>0;$i--){
        $aRow = $result->rows[$i-1];
        unset($aRow->value->class);
        unset($aRow->value->_rev);
        $output["aaData"][]=$aRow->value;
        unset($aRow);

    }
*/
}
header('Content-type: application/json');
echo json_encode($output);
?>
