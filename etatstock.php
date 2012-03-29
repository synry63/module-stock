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
$arrayjs[0] = "/custom/stock/lib/datatables/js/jquery.dataTables.js";
$arrayjs[1] = "/custom/stock/lib/datatables/js/initDatatablesEtatstock.js";

llxHeader('', '', '', '', '', '', $arrayjs);

// init list entrepot option
   $options = '<option value="">&nbsp;</option>';
   $options .= '<option value="dell">Dell</option>';

print'<form class="entete_etatstock">';
print'<label>'.$langs->trans("Entrepôt : ") .'</label>';
print'<select>'.$options.'</select>';
print'<input type="submit" class="submit" value="'. $langs->trans("Selectionner").'">';
print'</form>';
/*table views */
print '<table cellpadding="0" cellspacing="0" border="0" class="display" id="etatstock">';

print'<thead>';
    print'<th class="sorting">';
    print 'Emplacement';
    print'</th>';
    print'<th class="sorting">';
    print 'Référence pièce';
    print'</th>';
    print'<th class="sorting">';
    print 'Numéro de série';
    print'</th>';
    print'<th class="sorting">';
    print 'Quantité';
    print'</th>';
print'</thead>';

print'<thead class="recherche">';
print'<tr>';
print'<td id="1"><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Réf pièce") . '" class="inputSearch"/></td>';
print'<td id="2"><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Num série") . '" class="inputSearch" /></td>';
print'</tr>';
print'</thead>';

print'<tbody>';
print'</tbody>';

print'</table>';

?>
