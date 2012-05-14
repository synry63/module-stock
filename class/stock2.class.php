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

class Stock2 extends CommonObject
{
    public $element;
    
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
                $obj->tracking = $array[$i];
                $obj->codeprestataire = strtoupper($prestataire);
                $obj->codemouv = $code;
                $col[$i] = $obj;
                $obj=null;
                
             }
             $this->couchdb->storeDocs($col);
          }
     }
     function createByButton($rowsid,$user,$codemouv){
            $array =  explode(" ", $rowsid);
            $size = sizeof($array);
            $timestamp = dol_now();
            for($i=0;$i<$size;$i++){ 
                $this->fetch($array[$i]);
                unset($this->values->_id);
		unset($this->values->_rev);
                $this->values->UserCreate = $user->login;
                $this->values->tms = $timestamp;
                $this->values->codemouv = $codemouv;
		if($codemouv == 400)
		    unset($this->values->tracking);
                $col[$i] = $this->values;
            }
            $result = $this->couchdb->storeDocs($col);
	    foreach ($result as $key => $aRow)
	    {
		$col[$key]->_id = $aRow->id;
	    }
            return $col;
     }
     
     public function getView($name,$startkey="",$endkey="")
     {
        global $conf;
        if($name=="list")
            return $this->couchdb->getView(get_class ($this),$name);
        else if($name=="listByDate")
           return $this->couchdb->startkey($startkey)->endkey($endkey)->getView(get_class ($this),$name);
        else 
            return $this->couchdb->group(true)->group_level(1)->getView(get_class ($this),$name);
     }

     public function createView($name){
         
	 $name = strtoupper($name);
	 
         $doc = $this->db->getDoc("_design/".get_class());
         $doc->views->$name->map='function(doc){
            if(doc.class=="'.get_class($this).'" && doc.reference && doc.codeprestataire=="'.$name.'")		
                emit(doc.reference, {"reference":doc.reference,"serie":doc.serie,"emplacement":doc.emplacement,"codemouv":doc.codemouv});
             
            }';
         $doc->views->$name->reduce='function(key, values, rereduce){
                    var cpt =0;
                    for(var i= 0; i < values.length; i++){
                        if(values[i].codemouv=="300" || values[i].codemouv=="900") cpt++;
                        if(values[i].codemouv=="400") cpt--;
                    }
                    var out={"reference":values[0].reference,"serie":values[0].serie,"emplacement":values[0].emplacement,"quantite":cpt}		
                    return out;
            }';
        //$doc->set($fi$field = $obj;eld,$value);
         //$doc->record();
        try {
        //$test ='';    
         $result = $this->couchdb->storeDoc($doc);
        //header('Content-type: application/json');    
        //print_r (json_encode($obj[0]));
  
    } catch (Exception $e) {
        echo "Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
        exit();
    }


     }
}
?>