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

/**
 * 	\file       
 * 	\ingroup    
 * 	\brief      flux mouvement
 * 	\version    $Id: mouvement.php,v 1.00 2012/03/22 16:15:05 synry63 Exp $
 */
$res=@include("../../main.inc.php");									// For "custom" directory
if (! $res) $res=@include("../main.inc.php");
dol_include_once("/stock2/class/stock2.class.php");

$object = new Stock2($db);

if($_GET['json']=="list")
{
    $output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => 0,
    "iTotalDisplayRecords" => 0,
    "aaData" => array()
    );
    
    $viewname = $_GET['Collection'];
	
	$keystart[0]=$viewname;
        $keyend[0]=$viewname;
        $keyend[1]= new stdClass();
	
    try {
       $result = $object->getView("CountSpot",3,'',$keystart,$keyend);
    } catch (Exception $exc) {
	print $exc->getMessage();
    }

    $iTotal= count($result->rows);
    $output["iTotalRecords"]=$iTotal;
    $output["iTotalDisplayRecords"]=$iTotal;
    $i=0;
    foreach($result->rows as $aRow){
	
	$element = new stdClass();
	$element->Collection = $aRow->key[0];
	$element->Spot = $aRow->key[1];
	$element->Product = $aRow->key[2];
	$element->total = $aRow->value;

        $output["aaData"][]=$element;
        
        unset($element);
        
    }
    
    header('Content-type: application/json');
    echo json_encode($output);
    exit;
}
llxHeader();
// init list entrepot option
try {
	$result = $object->getView("Collection",1);
} catch (Exception $e) {
	dol_print_error('',$e->getMessage());
}

$h=0;
foreach ($result->rows as $aRow)
{
	$head[$h][0] = "#";
	$head[$h][1] = $langs->trans($aRow->key);
	$head[$h][2] = $aRow->key;
	$h++;
}

print'<div class="row">';
print start_box("Liste des stocks par référence produit","twelve","16-List-w_-Images.png",false,$head);

$langs->load('stock2@stock2');


foreach ($result->rows as $aRow)
{
	print '<article class="tab_pane">';
	/*table views */
	table($object, $aRow->key);
	print '</article>';
}


print end_box();
print '</div>';

llxFooter();

function table($object, $key)
{
	global $langs;
	
	$i=0;
	$obj=new stdClass();

	print '<table class="display dt_act" id="'.$key.'">';

	print'<thead>';
	print'<tr>';
	
	print'<th class="essential">';
	print $langs->trans("Product");
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "Product";
	$obj->aoColumns[$i]->sDefaultContent = "";
	$i++;
   
	print'<th>';
	print $langs->trans("Spot");
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "Spot";
	$obj->aoColumns[$i]->sDefaultContent = "";
	$obj->aoColumns[$i]->bVisible = true;
	$i++;
	
	print'<th class="essential">';
	print $langs->trans("Total");
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "total";
	$obj->aoColumns[$i]->sDefaultContent = "";
	$obj->aoColumns[$i]->sClass = "center";
	print'</tr>';
	print'</thead>';
	$obj->fnDrawCallback = "function(oSettings){
                if ( oSettings.aiDisplay.length == 0 )
                {
                    return;
                }
                var nTrs = jQuery('#".$key." tbody tr');
                var iColspan = nTrs[0].getElementsByTagName('td').length;
                var sLastGroup = '';
                for ( var i=0 ; i<nTrs.length ; i++ )
                {
                    var iDisplayIndex = oSettings._iDisplayStart + i;
                     var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData['Product'];
                         if (sGroup!=null && sGroup!='' && sGroup != sLastGroup)
                            {
                                var nGroup = document.createElement('tr');
                                var nCell = document.createElement('td');
                                nCell.colSpan = iColspan;
                                nCell.className = 'group';
                                nCell.innerHTML = sGroup;
                                nGroup.appendChild( nCell );
                                nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
                                sLastGroup = sGroup;
                            }
                    
                    
                }
	}";
    
	$i=0;
	print'<tfoot>';
	print'</tfoot>';

	$obj->aaSorting=array(array(0,'asc'));
	$obj->sAjaxSource= $_SERVER['PHP_SELF']."?json=list&Collection=".$key;

	print'<tbody>';
	print'</tbody>';
	print'</table>';

	print $object->datatablesCreate($obj,$key,true);
}

?>
