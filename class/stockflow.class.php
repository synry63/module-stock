<?php
/* Copyright (C) 2010-2011 Herve Prot           <herve.prot@symeos.com>
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
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */

/**
 *		\file       htdocs/custom/stock2
 *		\ingroup    crm
 *		\brief      
 *		\version    
 */

require_once(DOL_DOCUMENT_ROOT ."/core/class/commonobject.class.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/functions2.lib.php");

class StockFlow extends CommonObject
{
    
    /*Constructor */
    function __construct($db){
	
	parent::__construct($db);
	
        return 1;
    }
    
    function create($tracking,$codemouv,$user){
        global $conf, $langs;
       // date_default_timezone_set('UTC');
       // $s = time(); 
         $timestamp = dol_now();
         
         $code = substr($codemouv, 4);
         $prestataire = substr($codemouv,0,4);
         $tracking = trim($tracking);
         if($tracking!=""){
            $array =  explode("\n", $tracking);
            $size = sizeof($array);
            for($i=0;$i<$size;$i++){ 
                $obj->class = get_class($this);
		$obj->UserCreate = $user->login;
                $obj->tms = $timestamp;
                $obj->Tracking = $array[$i];
                $obj->Collection = strtoupper($prestataire);
                $obj->flowId = (int)$code;
                $col[$i] = $obj;
                $obj=null;
                
             }
             $this->couchdb->storeDocs($col);
          }
     }
     function UpdateAttachments($mois,$annee,$path){
        $id =$mois.$annee;
        try{
        $doc = $this->couchdb->getDoc($id);
        $ok = $this->couchdb->storeAttachment($doc,$path);
        }
        catch (Exception $e) {
           $obj->_id=$id;
           $ok = $this->couchdb->storeAttachment($obj,$path);
        }    
     }
	
	function createByButton($rowsid,$user,$codemouv)
	{
		$array =  explode(" ", $rowsid);
		$size = sizeof($array);
		$timestamp = dol_now();
		$colIn = array();
		for($i=0;$i<$size;$i++){ 
			$this->fetch($array[$i]);
			unset($this->values->_id);
			unset($this->values->_rev);
			$this->values->UserCreate = $user->login;
			$this->values->tms = $timestamp;
			$this->values->flowId = (int)$codemouv;
			if($codemouv == 400)
				unset($this->values->Tracking);
			if($codemouv == 800) // sortie du stock et ré-entrée
			{
				$in = clone $this->values;
				unset($in->Spot);
				$in->flowId = 700;
				$colIn[] = $in;// Add a new enter in stock
			}
			$col[$i] = $this->values;
		}
		
		$out = array_merge($col,$colIn);
			
		$result = $this->couchdb->storeDocs($out);
		foreach ($result as $key => $aRow)
		{
			$out[$key]->_id = $aRow->id;
		}
		return $out;
	}
}
?>