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
 * 	\brief      facturation
 * 	\version    $Id: mouvement.php,v 1.00 2012/03/22 16:15:05 synry63 Exp $
 */
$res = @include("../../main.inc.php");         // For "custom" directory
if (!$res)
    $res = @include("../main.inc.php");
dol_include_once("/stockflow/class/stockflow.class.php");
dol_include_once("/stockflow/lib/phptoexcel/Classes/PHPExcel.php");

if (!$user->rights->facture->creer) accessforbidden();

$arraym = array("", "Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin","Juillet","Août","Septembre","Octobre","Novembre","Decembre");
$sizem = sizeof($arraym);
$maxa = 2020;
$optionM = "";
$optionA = "";
for ($i = 1; $i < $sizem; $i++) {
    if($_POST['mois']==$i){
        $optionM .= '<option selected="selected" value="' . $i . '">' . $arraym[$i] . '</option>';
    }
    else{
         $optionM .= '<option  value="' . $i . '">' . $arraym[$i] . '</option>';
    }
    
}
for ($i = 2012; $i <= $maxa; $i++) {
    if($_POST['annee']==$i){
        $optionA .= '<option selected="selected" value="' . $i . '">' . $i . '</option>';
    }
    else{
        $optionA .= '<option  value="' . $i . '">' . $i . '</option>';
    }
}


$arrayjs = array();
llxHeader('', '', '', '', '', '', $arrayjs);

print'<div class="row">';
print start_box("Facturation","twelve","16-Download.png",true);

print'<form class="nice custom" action="facturation.php" method="post" style>';

print '<div class="formRow" style>';
    print '<label for="nice_select">Année</label>';
    print '<select  name="annee" id="nice_select">';
        print $optionA;
    print'</select>';    
print '</div>';
print '<div class="formRow" style>';
    print '<label for="nice_select2">Mois</label>';
    print '<select  name="mois" id="nice_select2">';
        print $optionM;
    print'</select>';    
print'</div>';
print'<div class="formRow" style>';
    print'<button class="button small nice blue" type="submit">Valider</button>';
print'</div>';
print'</form>';

print end_box();
print'</div>';

print'<div class="row">';
print start_box("Excel","twelve","16-List-w_-Images.png",false);
if ($_POST) {
    $mois = $_POST['mois'];
    $annee = $_POST['annee'];
    $object = new StockFlow($db);
    $timestampd = mktime(0, 0, 0, $mois, 1, $annee); 
    $timestampf = mktime(0, 0, 0, ($mois + 1), 1, $annee); 
    $viewname = "listByDate";
    $result = $object->getView($viewname, 0, '',$timestampd, $timestampf);
	
    $array = $result->rows;
    if (sizeof($array) != 0) {
        $objPHPExcel = new PHPExcel();
        // Add some data
        $i = 1;
        //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow('1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
        foreach ($array AS $aRow) {
            $timestamp = $aRow->value->tms;
            $date = date("d/m/Y", $timestamp);
            $heure = date("H:i:s", $timestamp);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $aRow->value->UserCreate)
                    ->setCellValue('B' . $i, $date)
                    ->setCellValue('C' . $i, $heure)
                    ->setCellValue('D' . $i, $aRow->value->Tracking)
                    ->setCellValue('E' . $i, $aRow->value->Collection . $aRow->value->flowId);
            $i++;
        }
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Save Excel 2007 file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        try {
            $url =str_replace('htdocs','documents',DOL_MAIN_URL_ROOT);
            $path = DOL_DATA_ROOT.'/facture/facturation.php';
           
            $objWriter->save(str_replace('.php', '.xlsx', $path));
            print'<div style="font-size:14px;" class="alert-box success">'.date('H:i:s').' Fichier créé avec success
                     <a href="javascript:void(0)" class="close">×</a>
                  </div>';
            print "<p style='font-size:20px;'> Fichier Excel Généré pour ".$langs->trans(date("F",$timestampd))." ".date("Y",$timestampd)." : <a href=".$url.'/facture/facturation.xlsx'.">ICI</a></p>\n";
        } catch (Exception $e) {
            print '<div style="font-size:14px;" class="alert-box error">
                '.date('H:i:s').' Erreur lors de la création du fichier 
                  <a href="javascript:void(0)" class="close">×</a>
                </div>';
        }
    }
    else print '<div style="font-size:14px;" class="alert-box error">
                '.date('H:i:s').' Aucun mouvement sur cette période
                  <a href="javascript:void(0)" class="close">×</a>
                </div>';
        
        
}
print end_box();
print '</div>';

llxFooter();
?>
