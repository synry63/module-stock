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

/* active datatable js */
$arrayjs = array();
$arrayjs[0] = "/custom/stock/lib/datatables/js/jquery.jeditable.js";
$arrayjs[1] = "/custom/stock/lib/datatables/js/jquery.dataTables.js";
$arrayjs[2] = "/custom/stock/lib/datatables/js/initXHR.js";
$arrayjs[3] = "/custom/stock/lib/datatables/js/KeyTable.js";
$arrayjs[4] = "/custom/stock/lib/datatables/js/initDatatablesMouv.js";
$arrayjs[5] = "/custom/stock/lib/datatables/js/addData.js";

llxHeader('', '', '', '', '', '', $arrayjs);
print'<form class="mouvement">';
print'<div class="entete">';
    print'<h3>Saisie pièce</h3>';
    print'<p class="compteur"> Scanné : 0 </p>';
print'</div>';
print'<textarea class="tracking"  placeholder="' . $langs->trans("num tracking") .'">';
print'</textarea>';
print'<input class="entrepot" type="text" placeholder="' . $langs->trans("Entrepôt") .'"/>';
print'<input type="submit" class="submit" value="'. $langs->trans("ajouter").'">';
print'</form>';
/*table views */
print '<table cellpadding="0" cellspacing="0" border="0" class="display" id="mouvement">';

print'<thead>';
    print'<th class="sorting">';
    print 'Nom operateur';
    print'</th>';
    print'<th class="sorting">';
    print 'Date et heure';
    print'</th>';
    print'<th class="sorting">';
    print 'Numero de tracking';
    print'</th>';
    print'<th class="sorting">';
    print 'Mouvement colis';
    print'</th>';
    print'<th class="sorting">';
    print 'Référence pièce';
    print'</th>';
    print'<th class="sorting">';
    print 'Numéro de série';
    print'</th>';
    print'<th class="sorting">';
    print 'Emplacement';
    print'</th>';    
     print'<th class="sorting">';
    print 'Check';
    print'</th>';    
    
print'</thead>';


print'<thead class="recherche">';
print'<tr>';
print'<td id="1"><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search opérateur") . '" class="inputSearch"/></td>';
print'<td id="2"><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search date et heure") . '" class="inputSearch" /></td>';
print'<td id="3"><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Num tracking") . '" class="inputSearch" /></td>';
print'<td id="4"><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Mouv colis") . '" class="inputSearch" /></td>';
print'<td id="5"><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Réf pièce") . '" class="inputSearch" /></td>';
print'<td id="6"><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Num série") . '" class="inputSearch" /></td>';
print'<td id="7"><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Emplacement") . '" class="inputSearch" /></td>';

print'</tr>';
print'</thead>';


print'<tbody>';
print'</tbody>';

print'</table>';

?>
