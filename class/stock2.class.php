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
    function Stock2($db){
	global $conf;
	
        $this->element="mouvement";
	
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
            $array =  split("\n", $tracking);
            $size = sizeof($array);
            for($i=0;$i<$size;$i++){ 
                $obj->class = $this->element;
                $obj->operateur = $user->login;
                $obj->datetime = $timestamp;
                $obj->tracking = $array[$i];
                $obj->codeprestataire =  $prestataire;
                $obj->codemouv = $code;
                $obj->reference = "";
                $obj->serie = "";
                $obj->emplacement = "";
                $col[$i] = $obj;
                $obj=null;
                
             }
             $this->couchdb->storeDocs($col);
          }
     }
     function createByButton($rowsid,$user,$codemouv){
            $array =  split(" ", $rowsid);
            $size = sizeof($array);
            $timestamp = dol_now();
            for($i=0;$i<$size;$i++){ 
                $doc =  $this->load($array[$i]);
                $obj->class = $this->element;
                $obj->_id = uniqid();
                $obj->operateur = $user->login;
                $obj->datetime = $timestamp;
                $obj->codemouv = $codemouv;
                $obj->codeprestataire = $doc->codeprestataire;
                $obj->tracking = $doc->tracking;
                $obj->reference = $doc->reference;
                $obj->serie = $doc->serie;
                $obj->emplacement = $doc->emplacement;
                $col[$i] = $obj;
                $obj=null;
            }
            $this->couchdb->storeDocs($col);
            return $col;   
            
     }
     public function getView($name,$startkey="",$endkey="")
     {
        global $conf;
        if($name=="list")
            return $this->couchdb->getView($this->class,$name);
        else if($name=="listByDate")
           return $this->couchdb->startkey($startkey)->endkey($endkey)->getView($this->class,$name);
        else 
            return $this->couchdb->group(true)->group_level(1)->getView($this->class,$name);
     }

     public function createView($name){
         
         $doc = $this->db->getDoc("_design/mouvement");
         $doc->views->$name->map='function(doc){
            if(doc.class=="mouvement" && doc.reference && doc.codeprestataire=="'.$name.'")		
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