<?php
$res=@include("../main.inc.php");					// For root directory
if (! $res) $res=@include("../../main.inc.php");	// For "custom" directory
dol_include_once("/stock/class/stock.class.php");

$object = new Stock($couch);
if($_POST['column']=="codemouv") print $object->test();
else print $object->updateDoc();

?>
