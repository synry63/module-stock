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
 * 	\brief      flux mouvement
 * 	\version    $Id: mouvement.php,v 1.00 2012/03/22 16:15:05 synry63 Exp $
 */
$res=@include("../../main.inc.php");									// For "custom" directory
if (! $res) $res=@include("../main.inc.php");

 // init list entrepot option
   $options = '<option value="">&nbsp;</option>';
   $options .= '<option value="dell">Dell</option>';


/* active datatable js */
$arrayjs = array();
$arrayjs[0] = "/custom/stock/lib/datatables/js/jquery.jeditable.js";
$arrayjs[1] = "/custom/stock/lib/datatables/js/jquery.dataTables.js";
$arrayjs[2] = "/custom/stock/lib/datatables/js/initXHR.js";
$arrayjs[3] = "/custom/stock/lib/datatables/js/KeyTable.js";
$arrayjs[4] = "/custom/stock/lib/datatables/js/initDatatablesInventaire.js";
$arrayjs[5] = "/custom/stock/lib/datatables/js/addData.js";

llxHeader('', '', '', '', '', '', $arrayjs);

/* inventaire total */ 
print'<form class="inventaire_total">';
print'<div class="entete">';
    print'<h3>Inventaire Total </h3>';
    print'<div class="choixentrepot">';
        print '<label>'.$langs->trans("Entrepôt : ").'</label>';
        print'<select>'.$options.'</select>';
        print'<a href="javascript:void(0);" onclick="fn();">' . $langs->trans("Lancer").'</a>';
    print'</div>';
    print'<span class="compteur"> Scanné : 0 </span>';
print'</div>';
print'<textarea class="emplacement_numeropiece_numserie"  placeholder="' . $langs->trans("emplacement;n°pièce;S/N") .'">';
print'</textarea>';
print'<input class="entrepot" type="text" placeholder="' . $langs->trans("Entrepôt") .'"/>';
print'<input type="submit" class="submit" value="'. $langs->trans("ajouter").'">';
print'</form>';

/* inventaire partiel */
print'<form class="inventaire_partiel">';
print'<div class="entete">';
print'<h3>Inventaire Partiel </h3>';
print'</div>';
print'<div class="piece_archive">';
    print'<p> Scanné : 0 </p>';
    print'<textarea class="emplacement_numeropiece_numserie"   placeholder="' . $langs->trans("Saisie numéro de pièces") .'">';
    print'</textarea>';
    print'<input class="entrepot" type="text" placeholder="' . $langs->trans("Entrepôt") .'"/>';
    print'<input type="button" class="submit" value="'. $langs->trans("Archiver").'">';
print'</div>';
print'<div class="piece_inventaire">';
    print'<p> Scanné : 0 </p>';
    print'<textarea  class="emplacement_numeropiece_numserie" placeholder="' . $langs->trans("emplacement;n°pièce;S/N") .'">';
    print'</textarea>';
    print'<input class="entrepot" type="text"   placeholder="' . $langs->trans("Entrepôt") .'"/>';
    print'<input type="button" class="submit" value="'. $langs->trans("Ajouter").'">';
print'</div>';
print'</form>';
print '<table cellpadding="0" cellspacing="0" border="0" class="display" id="mouvement">';

/*table views */
print'<thead>';
    print'<th class="sorting">';
    print 'Nom operateur';
    print'</th>';
    print'<th class="sorting">';
    print 'Date et heure';
    print'</th>';
    print'<th class="sorting">';
    print 'Emplacement';
    print'</th>';
    print'<th class="sorting">';
    print 'Référence pièce';
    print'</th>';
    print'<th class="sorting">';
    print 'Numéro de série';
    print'</th>';
print'</thead>';

print'<thead class="recherche">';
print'<tr>';
print'<td id="1"><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search opérateur") . '" class="inputSearch"/></td>';
print'<td id="2"><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search date et heure") . '" class="inputSearch" /></td>';
print'</tr>';
print'</thead>';


print'<tbody>';
print'</tbody>';

print'</table>';
print'<br/>';
print'<p style="margin:10px;"> Total pièces Scannées : 1 </p>';

?>
