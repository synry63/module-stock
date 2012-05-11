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
dol_include_once("/stock/class/stock.class.php");

$object = new Stock($couch);

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
if($_GET['json'])
{
    $output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => 0,
    "iTotalDisplayRecords" => 0,
    "aaData" => array()
    );
    $viewname="dell";
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
        
        /*unset($aRow->value->class);
        unset($aRow->value->_id);
        unset($aRow->value->_rev);
        unset($aRow->value->codemouv);
        unset($aRow->value->codeprestataire);
        unset($aRow->value->operateur);
        unset($aRow->value->datetime);
        unset($aRow->value->tracking);*/

        $output["aaData"][]=$aRow->value;
       // $key =$output["aaData"][$i]->reference==$result2->rows[$i]->key){
        //    $output["aaData"][$i]->quantite=$result2[$i]->value;
        
        unset($aRow);
        
    }
    
    header('Content-type: application/json');
    echo json_encode($output);
    exit;
}
/* active datatable js */
$arrayjs = array();
$arrayjs[0] = "/custom/stock/lib/datatables/js/jquery.dataTables.js";

//$arrayjs[1] = "/custom/stock/lib/datatables/js/initDatatablesEtatstock.js";
llxHeader('', '', '', '', '', '', $arrayjs);
// init list entrepot option
$arrayp = array("dell","cisc");
$i=0;

    if($_POST['entrepot']==$arrayp[$i])
        $options = '<option selected="selected" value="dell">Dell</option>';
    else $options = '<option  value="dell">Dell</option>';
$i++;
    if($_POST['entrepot']==$arrayp[$i])
        $options .= '<option selected="selected" value="cisc">Cisco</option>';
    else $options .= '<option value="cisc">Cisco</option>';
$i++;

// init type affichage option
$arrayt = array("empl","piece");
$i=0;
    if($_POST['display']==$arrayt[$i]){
        $optionsDisplay = '<option selected="selected" value="empl">Par Emplacement</option>';
    }
    else{
        $optionsDisplay = '<option value="empl">Par Emplacement</option>';
    }
$i++;    
    if($_POST['display']==$arrayt[$i]){
        $optionsDisplay .= '<option selected="selected"  value="piece">Par Pièce</option>';
    }
    else{
        $optionsDisplay .= '<option  value="piece">Par Pièce</option>';
    }
print'<form class="entete_etatstock" action="etatstock.php" method="post">';

print'<label>'.$langs->trans("Affichage : ") .'</label>';
print'<select id="display" name="display">'.$optionsDisplay.'</select>';

print'<label>'.$langs->trans("Entrepôt : ") .'</label>';
print'<select id="entrepot" name="entrepot">'.$options.'</select>';

print'<input type="submit" class="submit" value="'. $langs->trans("Valider").'">';
print'</form>';

$i=0;
$obj=new stdClass();
/*table views */
print '<table cellpadding="0" cellspacing="0" border="0" class="display" id="etatstock">';
if($_POST['display']=="piece"){
print'<thead>';
    print'<th class="sorting">';
    print ' ';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "reference";
    $obj->aoColumns[$i]->fnRender = '%function(obj) {
    var str = obj.aData.reference;
    if(typeof str === $undefined$)
        str = null;
        return str;
    }%';    
    $i++;
    
    print'<th class="sorting">';
    print 'Emplacement';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "emplacement";
    $obj->aoColumns[$i]->fnRender = '%function(obj) {
    var str = obj.aData.emplacement;
    if(typeof str === $undefined$)
        str = null;
        return str;
    }%';   
    $i++;
    
    print'<th class="sorting">';
    print 'Numéro de série';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "serie";
    $obj->aoColumns[$i]->fnRender = '%function(obj) {
    var str = obj.aData.serie;
    if(typeof str === $undefined$)
        str = null;
        return str;
    }%';  
    $i++;
    
   print'<th class="sorting">';
    print 'Quantité';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "quantite";
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


}
else{   
print'<thead>';
    print'<th class="sorting">';
    print 'Emplacement';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "emplacement";
    $obj->aoColumns[$i]->fnRender = '%function(obj) {
    var str = obj.aData.emplacement;
    if(typeof str === $undefined$)
        str = null;
        return str;
    }%';    
    $i++;
    print'<th class="sorting">';
    print 'Référence pièce';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "reference";
    $obj->aoColumns[$i]->fnRender = '%function(obj) {
    var str = obj.aData.reference;
    if(typeof str === $undefined$)
        str = null;
        return str;
    }%';  
    $i++;
    
    print'<th class="sorting">';
    print 'Numéro de série';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "serie";
    $obj->aoColumns[$i]->fnRender = '%function(obj) {
    var str = obj.aData.serie;
    if(typeof str === $undefined$)
        str = null;
        return str;
    }%';   
    $i++;
    
   print'<th class="sorting">';
    print 'Quantité';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "quantite";
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
    

}
$obj->fnServerParams ='%function ( aoData ) {
        aoData.push( { "name": "entrepot", "value": "'.$_POST['entrepot'].'" },
                     { "name": "display", "value": "'.$_POST['display'].'" });
    }%';
$obj->aoColumnDefs=array(array("bVisible"=>false,"aTargets"=>array(0)));
$obj->aaSortingFixed=array(array(0,'asc'));
$obj->aaSorting=array(array(1,'asc'));
$i=1;
print'<thead class="recherche">';
print'<tr>';
print'<td id='.$i.'><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Réf pièce") . '" class="inputSearch"/></td>';
$i++;
print'<td id='.$i.'><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Num série") . '" class="inputSearch" /></td>';
$i++;
print'<td id='.$i.'><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Quantité") . '" class="inputSearch" /></td>';

print'</tr>';
print'</thead>';

print'<tbody>';
print'</tbody>';

print $object->_datatables($obj,"#etatstock");

print'</table>';

print'<script type="text/javascript" charset="utf-8">
       $(document).ready(function() {';
       /*seach on column */
print '$("thead input").keyup( function () {
		/* Filter on the column (the index) of this element */
		var index = $("thead input").index(this)+1;
                oTable.fnFilter( this.value, (index));
});';
print'});';
print '</script>';

?>
