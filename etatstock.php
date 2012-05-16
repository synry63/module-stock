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

/*if($_GET['entrepot'] && $_GET['display']){
     //$result = $object->getView("listByPrestataire",$_GET['entrepot']);
    $result = $object->getView("list"); 
    foreach($result->rows AS $aRow){
            unset($aRow->value->class);
            unset($aRow->value->_id);
            unset($aRow->value->_rev);
            unset($aRow->value->codemouv);
            unset($aRow->value->codeprestataire);
            unset($aRow->value->operateur);
            unset($aRow->value->datetime);
            unset($aRow->value->tracking);
            $col[] = $aRow->value;
     }
     header('Content-type: application/json');
     echo json_encode($col);
     exit;
}*/
if($_GET['json']=="list")
{
    $output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => 0,
    "iTotalDisplayRecords" => 0,
    "aaData" => array()
    );
    $viewname="DELL";
    if($_GET['entrepot']) $viewname = $_GET['entrepot'];
    try {
       $result = $object->getView($viewname);
    } catch (Exception $exc) {
      $object->createView($viewname);
      $result = $object->getView($viewname);
    }

    //$result2 = $object->getNb();
    //print_r($result);
    //exit;
    $iTotal= count($result->rows);
    $output["iTotalRecords"]=$iTotal;
    $output["iTotalDisplayRecords"]=$iTotal;
    $i=0;
    foreach($result->rows AS $aRow){

        $output["aaData"][]=$aRow->value;
        
        unset($aRow);
        
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
print start_box("Liste des stocks","twelve","16-List-w_-Images.png",false,$head);

$langs->load('stock2@stock2');

$i=0;
$obj=new stdClass();
/*table views */
print '<table class="display dt_act" id="etatstock">';
if($_POST['display']=="piece"){
print'<thead>';
    print'<th>';
    print ' ';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "reference";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $i++;
    
    print'<th class="essential">';
    print 'Emplacement';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "emplacement";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $i++;
    
    print'<th class="essential">';
    print 'Numéro de série';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "serie";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $i++;
    
   print'<th class="essential">';
    print 'Quantité';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "quantite";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $obj->aoColumns[$i]->sClass = "center";

    
print'</thead>';

$obj->fnDrawCallback = "%function(oSettings){
                if ( oSettings.aiDisplay.length == 0 )
                {
                    return;
                }
                var nTrs = jQuery('#etatstock tbody tr');
                var iColspan = nTrs[0].getElementsByTagName('td').length;
                var sLastGroup = '';
                for ( var i=0 ; i<nTrs.length ; i++ )
                {
                    var iDisplayIndex = oSettings._iDisplayStart + i;
                     var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData['reference'];
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
    }%";

$i=1;
print'<tfoot>';
print'<tr>';
print'<td></td>';
print'<td id='.$i.'><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Emplacement") . '"/></td>';
$i++;
print'<td id='.$i.'><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Num série") . '"/></td>';
$i++;
print'<td id='.$i.'><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Quantité") .  '"/></td>';
print'</tr>';
print'</tfoot>';
}
else{   
	print'<thead>';
	print'<tr>';
	print'<th>';
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "_id";
	$obj->aoColumns[$i]->bVisible = false;
	$i++;
	print'<th>';
	print $langs->trans("Spot");
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "Spot";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $i++;
    print'<th class="essential">';
    print $langs->trans("Product");
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "Product";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $i++;
    
    print'<th class="essential">';
    print $langs->trans("SN");
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "SN";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $i++;
    
   print'<th class="essential">';
    print $langs->trans("Nb");
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "total";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $obj->aoColumns[$i]->sClass = "center";
print'</tr>';
print'</thead>';

$obj->fnDrawCallback = "%function(oSettings){
                if ( oSettings.aiDisplay.length == 0 )
                {
                    return;
                }
                var nTrs = jQuery('#etatstock tbody tr');
                var iColspan = nTrs[0].getElementsByTagName('td').length;
                var sLastGroup = '';
                for ( var i=0 ; i<nTrs.length ; i++ )
                {
                    var iDisplayIndex = oSettings._iDisplayStart + i;
                     var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData['emplacement'];
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
    }%";
    
$i=0;
print'<tfoot>';
print'<tr>';
print'<th></th>';
print'<th></th>';
print'<th id='.$i.'><input type="text" placeholder="' . $langs->trans("Search Réf pièce") . '"/></th>';
$i++;
print'<th id='.$i.'><input type="text" placeholder="' . $langs->trans("Search Num série") . '"/></th>';
$i++;
print'<th id='.$i.'><input type="text" placeholder="' . $langs->trans("Search Quantité") .  '"/></th>';
print'</tr>';
print'</tfoot>';

}
$obj->fnServerParams ='%function ( aoData ) {
        aoData.push( { "name": "entrepot", "value": "'.$_POST['entrepot'].'" },
                     { "name": "display", "value": "'.$_POST['display'].'" });
    }%';
$obj->aoColumnDefs=array(array("bVisible"=>false,"aTargets"=>array(0)));
$obj->aaSortingFixed=array(array(0,'asc'));
$obj->aaSorting=array(array(1,'asc'));

print'<tbody>';
print'</tbody>';
print'</table>';

print $object->datatablesCreate($obj,"etatstock",true);

print end_box();
print '</div>';

llxFooter();

?>
