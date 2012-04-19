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
 *		\file       htdocs/custom/stock
 *		\ingroup    crm
 *		\brief      
 *		\version    
 */

require_once(DOL_DOCUMENT_ROOT ."/core/class/commonobject.class.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/functions2.lib.php");

class Stock extends couchDocument
{
    var $db;
    /*Constructor */
    function Stock (couchClient $db){
        parent::__construct($db);
        $this->db = $db;
    }
    function create($tracking,$codemouv,$user){
        global $conf, $langs;
         
         $code = substr($codemouv, 4);
         $prestataire = substr($codemouv,0,4);
         $tracking = trim($tracking);
         if($tracking!=""){
            $array =  split(" ", $tracking);
            $size = sizeof($array);
            for($i=0;$i<$size;$i++){ 
                $obj->class = "mouvement";
                $obj->operateur = $user->login;
                $obj->datetime = "28/08/12 12h58";
                $obj->tracking = $array[$i];
                $obj->codeprestataire =  $prestataire;
                $obj->codemouv = $code;
                $obj->reference = "";
                $obj->serie = "";
                $obj->emplacement = "";
                $col[$i] = $obj;
                $obj=null;
                
            }
            try {
                $this->db->storeDocs($col);
                } catch (Exception $e) {
                $this->error="Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
                dol_syslog("Ego::Create ".$this->error, LOG_ERR);
                return -1;
                }
         }
           
    }
    function getList(){
          global $conf;
          try {
             return $this->db->getView('mouvement','list'); 
          } catch (Exception $exc) {
              return -1;
          }

          
    }
    function updateDoc(){
        $key = $_POST['idrow'];
        $field = $_POST['column'];
        $value = $_POST['value'];
        
        try {
        $doc =  $this->load($key);
        $doc->set($field,$value);
        $doc->record();
         return $value;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
           return ""; 
        }
    }
    function test(){
        $key = $_POST['idrow'];
        $value = $_POST['value'];
        $code = substr($value, 4);
        $prestataire = substr($value,0,4);
        try {
        $doc =  $this->load($key);
        $doc->set("codeprestataire",$prestataire);
        $doc->set("codemouv",$code);
        $doc->record();
        return $value;
         } catch (Exception $exc) {
            echo $exc->getTraceAsString();
           return ""; 
        }
    }

}
?>