<?php
/* 
 * Copyright (C) 2012      Patrick Mary         <laube@hotmail.fr>
 * Copyright (C) 2012      Herve Prot		<herve.prot@symeos.com>
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
$res=@include("../../main.inc.php");
if (! $res) $res=@include("../main.inc.php");

dol_include_once("/stock2/class/stock2.class.php");

$object = new Stock2($db);


/*edit cell value */
if($_GET['json']=="edit"){
    $key = $_POST['key'];
    $id = $_POST['id'];
    $value = $_POST['value'];
    
    if($key != "codemouv"){
        try {
	    $object->fetch($id);
	    //$res = $object->set($key,$value);
	    $object->values->$key = $value;
	    $res = $object->update($user);
	    if( $res >0 )
	    {
		print $value;
	    }
	    else
	    {
		print $res."</br>";
		print_r($object->errors);
	    }
            exit;
        } catch (Exception $exc) {
            print $exc->getTraceAsString();
            exit;
        }
    }
    else{
        $code = substr($value, 4);
        $prestataire = strtoupper(substr($value,0,4));
        try {
	    $object->fetch($id);
	    $object->values->codeprestataire = $prestataire;
	    $object->values->codemouv = $code;
	    $res = $object->update($user);
	    if( $res >0 )
	    {
		print $prestataire.$code;
	    }
	    else
	    {
		print $res."</br>";
		print_r($object->errors);
	    }
            exit;
        } catch (Exception $exc) {
           print $exc->getTraceAsString();
           exit;
        }
    }
}


/*get mouvements*/
if($_GET['json']=="list")
{
    $output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => 0,
    "iTotalDisplayRecords" => 0,
    "aaData" => array()
    );
    $result = $object->getView("list");

    //print_r($result);
    //exit;
    $iTotal=  count($result->rows);
    $output["iTotalRecords"]=$iTotal;
    $output["iTotalDisplayRecords"]=$iTotal;

    foreach($result->rows AS $aRow){
        unset($aRow->value->class);
        unset($aRow->value->_rev);
        $output["aaData"][]=$aRow->value;
        unset($aRow);
    }
    header('Content-type: application/json');
    echo json_encode($output);
    exit;
}


/* add mouvements by menu rapid */
if(($_POST['tracking']!=null && $_POST['mouv'])){
    $object->create($_POST['tracking'],$_POST['mouv'],$user);  
    Header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
/*add mouvements by button */
if(isset($_POST['tableData'])){
    $col=array();
    if($_POST['tableData']!=""){
        $rowsid = trim($_POST['tableData']);
        $col = $object->createByButton($rowsid,$user,$_POST['codemouv']); 
    }
    header('Content-type: application/json');
    echo json_encode($col);
    exit;
}
/* active datatable js */
$arrayjs = array();
$arrayjs[0] = "/custom/stock2/lib/datatables/js/indicateurTracking.js";

llxHeader("","","","","","",$arrayjs);

print'<div class="row">';
print start_box("Saisie des mouvements","twelve","16-Download.png",true,true);

/*tableau de saisie rapide */

print'<form class="nice" action="'.$_SERVER['PHP_SELF'].'" method="post">';
print '<div style="text-align:center";>';
print'<h5 style="display:inline;" class="sepH_b"> Colis Scannés :</h5> <span class="cpt">0</span>';
print '</div>';
print '<div class="formRow elVal">';
    print '<label for="tracking">Numéro de Tracking</label>';
    print'<textarea id="tracking" name="tracking" placeholder="Numéro de Tracking" class="auto_expand expand" rows="2" cols="1">';
    print'</textarea>';
print '</div>';
print '<div class="formRow elVal">';
    print '<label for="codemouv">Code Mouvement</label>';
    print '<input type="text" placeholder="Code Mouvement" class="input-text small" name="mouv" id="codemouv">';
print'</div>';
print'<div class="formRow">';
    print'<button class="button small nice blue" type="submit">Ajouter</button>';
print'</div>';
print'</form>';

print end_box();
print'</div>';

print'<div class="row">';
print start_box("Liste des mouvements","twelve","16-List-w_-Images.png",false,false);

$i=0;
$obj=new stdClass();

/*table views */
print '<table class="display dt_act" id="mouvement">';

print'<thead>';
print'<tr>';
    print'<th>';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "_id";
    $obj->aoColumns[$i]->bVisible = false;
    $i++;
    print'<th class="essential">';
    print 'Nom operateur';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "UserCreate";
    $i++;
    print'<th class="essential">';
    print 'Date et heure';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "tms";
    $obj->aoColumns[$i]->sType="date";
    $obj->aoColumns[$i]->fnRender = 'function(obj) {
    if(obj.aData.tms)
    {
        var date = new Date(obj.aData.tms*1000);
        return date.toLocaleDateString()+" "+date.toLocaleTimeString();
    }
    else
        return null;
    }';
    $i++;
    print'<th class="essential">';
    print 'Numero de tracking';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "tracking";
    $obj->aoColumns[$i]->sClass = "edit";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $i++;
    print'<th class="essential">';
    print 'Mouvement colis';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "codemouv";
    $obj->aoColumns[$i]->sClass = "edit";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $obj->aoColumns[$i]->fnRender = 'function(obj) {
    var str = obj.aData.codeprestataire+obj.aData.codemouv;
    if(typeof str === "undefined")
        str = "";
        return str;
    }';
    $i++;
    print'<th class="essential">';
    print 'Référence pièce';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "reference";
    $obj->aoColumns[$i]->sClass = "edit";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $i++;
    print'<th class="essential">';
    print 'Numéro de série';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "serie";
    $obj->aoColumns[$i]->sClass = "edit";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $i++;
    print'<th class="essential">';
    print 'Emplacement';
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "emplacement";
    $obj->aoColumns[$i]->sClass = "edit";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $i++;
    print '<th class="chb_col"><input type="checkbox" class="chSel_all" /></th>';
    $obj->aoColumns[$i]->mDataProp = null;
    $obj->aoColumns[$i]->bSortable = false;
    $obj->aoColumns[$i]->sDefaultContent = false;
    $obj->aoColumns[$i]->sClass = "chb_col";
    $obj->aoColumns[$i]->fnRender = 'function(obj) {
        var str ="<input id="+obj.aData._id+" type=\"checkbox\" name=\"row_sel\"/>";
        return str;
    }';
    /* init ajax button */
    $obj->oTableTools->aButtons = array(array("sExtends"=>"ajax","sAjaxUrl"=> $_SERVER['PHP_SELF'],
    "fnClick"=>'function ( nButton, oConfig, oFlash ){
                var idrow="";
                jQuery("input[type=checkbox]:checked").each( 
                    function() { 
                    idrow += jQuery(this).attr("id");
                    idrow += " ";
                    } 
                ),
                jQuery.ajax( {
                "url": oConfig.sAjaxUrl,
                "data": [
                            { "name": "tableData", "value": idrow},
                            { "name": "codemouv", "value": "400"}
                            
                        ],
                "success": oConfig.fnAjaxComplete,
                "dataType": "json",
                "type": "POST",
                "cache": false,
                "error": function () {
                    alert( "Error detected when sending table data to server" );
                    }
                 } );
            }',
   "fnAjaxComplete"=>'function ( json ) {
                    var result =  oTable.fnAddData(json);
                     alert(result.length+" lignes copiés");
                    }',
    "sButtonText"=>"Sortir Stock"   
        
    ),array("sExtends"=>"ajax","sAjaxUrl"=> $_SERVER['PHP_SELF'],
    "fnClick"=>'function ( nButton, oConfig, oFlash ){
                var idrow="";
                jQuery("input[type=checkbox]:checked").each( 
                    function() { 
                    idrow += jQuery(this).attr("id");
                    idrow += " ";
                    } 
                ),
                jQuery.ajax( {
                "url": oConfig.sAjaxUrl,
                "data": [
                            { "name": "tableData", "value": idrow },
                            { "name": "codemouv", "value": "900"}
                        ],
                "success": oConfig.fnAjaxComplete,
                "dataType": "json",
                "type": "POST", 
                "cache": false,
                "error": function () {
                    alert( "Error detected when sending table data to server" );
                    }
                 } );
            }',
   "fnAjaxComplete"=>'function ( json ) {
                     var result =  oTable.fnAddData(json);
                     alert(result.length+" lignes copiés");
                    }',
    "sButtonText"=>"Mouvement Interne"
     ));
              
print'</tr>';
print'</thead>';
$i=0;
print'<tfoot>';
print'<tr>';
print'<th id='.$i.'></th>';
$i++;
print'<th id='.$i.'><input type="text" placeholder="' . $langs->trans("Search opérateur") . '" /></th>';
$i++;
print'<th id='.$i.'><input type="text" placeholder="' . $langs->trans("Search date et heure") . '" /></th>';
$i++;
print'<th id='.$i.'><input type="text" placeholder="' . $langs->trans("Search Num tracking") . '" /></th>';
$i++;
print'<th id='.$i.'><input type="text" placeholder="' . $langs->trans("Search Mouv colis") . '" /></th>';
$i++;
print'<th id='.$i.'><input type="text" placeholder="' . $langs->trans("Search Réf pièce") . '" /></th>';
$i++;
print'<th id='.$i.'><input type="text" placeholder="' . $langs->trans("Search Num série") . '" /></th>';
$i++;
print'<th id='.$i.'><input type="text" placeholder="' . $langs->trans("Search Emplacement") . '" /></th>';
$i++;
print'<th id='.$i.'></th>';
$i++;
print'</tr>';
print'</tfoot>';
print'<tbody>';
print'</tbody>';

print'</table>';
$obj->aaSorting = array(array(2, "desc"));

$object->_datatables($obj,"mouvement",true,true);


print end_box();
print '</div>';

llxFooter();

?>
