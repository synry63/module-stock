<?php
/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
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
 * 	\defgroup   mymodule     Module MyModule
 *  \brief      Example of a module descriptor.
 *				Such a file must be copied into htdocs/mymodule/core/modules directory.
 *  \file       htdocs/mymodule/core/modules/modMyModule.class.php
 *  \ingroup    mymodule
 *  \brief      Description and activation file for module MyModule
 */
include_once(DOL_DOCUMENT_ROOT ."/core/modules/DolibarrModules.class.php");


/**
 *  Description and activation class for module MyModule
 */
class modStockFlow extends DolibarrModules
{
	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modStockFlow($db)
	{
        global $langs,$conf;
		
		
		parent::__construct();

        $this->db = $db;

		// Id for module (must be unique).
		// Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
		$this->numero = 470;
		//$this->rights_class = 'stock2';
		$this->family = "other";
		$this->name = preg_replace('/^mod/i','',get_class($this));
		$this->description = "Gestion des mouvements de stock avec code à barre";
		$this->version = '1.0';
		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		// Where to store the module in setup page (0=common,1=interface,2=others,3=very specific)
		$this->special = 0;
		$this->picto='sending';
		$this->moddir="StockFlow";

		//$this->module_parts = array();

		$this->dirs = array("/stockflow/temp");

		// Config pages. Put here list of php page, stored into mymodule/admin directory, to use to setup module.
		//$this->config_page_url = array("mysetuppage.php@mymodule");

		// Dependencies
		$this->depends = array();		// List of modules id that must be enabled if this module is enabled
		$this->requiredby = array();	// List of modules id to disable if this one is disabled
		$this->phpmin = array(5,3);					// Minimum version of PHP required by module
		$this->need_dolibarr_version = array(3,2);	// Minimum version of Dolibarr required by module
		$this->langfiles = array("stock2@stockflow");

		$this->const = array();

		//$this->tabs = array();

		// Dictionnaries
		//if (! isset($conf->stock2->enabled)) $conf->stock2->enabled=0;
		//    $this->dictionnaries=array();

		// Boxes
		// Add here list of php file(s) stored in core/boxes that contains class to show a box.
		$this->boxes = array();			// List of boxes
		$r=0;

		// Permissions
		$this->rights = array();		// Permission array used by this module
		$r=0;

		// Main menu entries
		$this->menus = array();			// List of menus to add
		$r=0;

		// Add here entries to declare new menus
		//
		// Example to declare a new Top Menu entry and its Left menu entry:
		 $this->menu[$r]=array(	'fk_menu'=>0,
		     '_id'=>'menu:stockflow',
		    'type'=>'top',
		    'titre'=>'Gestion Stock',
		    'url'=>'/stockflow/mouvement.php',
		    'langs'=>'',
		    'position'=>50,
		    'enabled'=>'$conf->stockflow->enabled',
		    'perms'=>'1',
		    'target'=>'',
		    'user'=>2);
		 $r++;
		 $this->menu[$r]=array(	'fk_menu'=>'menu:stockflow',
		    'type'=>'',
		    '_id'=>'menu:mouvementstockflow',
		    'titre'=>'Mouvement',
		    'url'=>'/stockflow/mouvement.php',
		    'langs'=>'',
		    'position'=>0,
		    'enabled'=> '$conf->stockflow->enabled',
		    'perms'=>'1',
		    'target'=>'',
		    'user'=>2);
		$r++;
                $this->menu[$r]=array(	'fk_menu'=>'menu:stockflow',
		    'type'=>'',
		    '_id'=>'menu:etatstockflow',
		    'titre'=>'Etat stock',
		    'url'=>'/stockflow/etatstockProd.php',
		    'langs'=>'',
		    'position'=>1,
		    'enabled'=> '$conf->stockflow->enabled',
		    'perms'=>'1',
		    'target'=>'',
		    'user'=>2);	
		$r++;
		$this->menu[$r]=array(	'fk_menu'=>'menu:etatstockflow',
		    'type'=>'',
		    '_id'=>'menu:etatstockflowref',
		    'titre'=>'Etat stock par référence',
		    'url'=>'/stockflow/etatstockProd.php',
		    'langs'=>'',
		    'position'=>1,
		    'enabled'=> '$conf->stockflow->enabled',
		    'perms'=>'1',
		    'target'=>'',
		    'user'=>2);	
		$r++;
		$this->menu[$r]=array(	'fk_menu'=>'menu:etatstockflow',
		    'type'=>'',
		    '_id'=>'menu:etatstockflowspot',
		    'titre'=>'Etat stock par emplacement',
		    'url'=>'/stockflow/etatstockSpot.php',
		    'langs'=>'',
		    'position'=>1,
		    'enabled'=>'$conf->stockflow->enabled',
		    'perms'=>'1',
		    'target'=>'',
		    'user'=>2);	
		$r++;
                $this->menu[$r]=array(	'fk_menu'=>'menu:stockflow',
		    'type'=>'',
		    '_id'=>'menu:inventairestockflow',
		    'titre'=>'Inventaire',
		    'url'=>'/stockflow/inventaire.php',
		    'langs'=>'',
		    'position'=>2,
		    'enabled'=>'$conf->stockflow->enabled',
		    'perms'=>'1',
		    'target'=>'',
		    'user'=>2);
		$r++;
                $this->menu[$r]=array(	'fk_menu'=>'menu:stockflow',
		    'type'=>'',
		    '_id'=>'menu:facturationsstockflow',
		    'titre'=>'Facturation',
		    'url'=>'/stockflow/facturation.php',
		    'langs'=>'',
		    'position'=>3,
		    'enabled'=>'$conf->stockflow->enabled',
		    'perms'=>'1',
		    'target'=>'',
		    'user'=>2);
		
		
	}

	/**
	 *		Function called when module is enabled.
	 *		The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 *		It also creates data directories
	 *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
	 */
	function init($options='')
	{
		$sql = array();

		//$result=$this->create_view();

		return $this->_init($sql, $options);
	}

	/**
	 *		Function called when module is disabled.
	 *      Remove from database constants, boxes and permissions from Dolibarr database.
	 *		Data directories are not deleted
	 *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
	 */
	function remove($options='')
	{
		$sql = array();

		return $this->_remove($sql, $options);
	}


	function create_view()
    {
        
		$json= '{
   "_id": "_design/StockFlow",
   "language": "javascript",
   "views": {
       "list": {
           "map": "function(doc) {\n  if(doc.class && doc.class==\"StockFlow\")\t\n  emit(doc._id, doc);\n}"
       },
       "Collection": {
           "map": "function(doc) {\n  if(doc.class==\"StockFlow\")\t\t\n  \temit(doc.Collection, doc);\n}",
           "reduce": "function(keys, values) {\n  return true;\n}"
       },
       "CountProduct": {
           "map": "function(doc){\n\tif(doc.class==\"StockFlow\")\n\t{\n\t\tif(doc.flowId == 300 || doc.flowId == 700)\n        \t\temit([doc.Collection,doc.Product,doc.Spot], 1);\n\t\tif(doc.flowId == 400 || doc.flowId == 800)\n        \t\temit([doc.Collection,doc.Product,doc.Spot], -1);\n\t}\n}",
           "reduce": "function(keys, values) {\n  return sum(values)\n}"
       },
       "target_id": {
           "map": "function(doc) {\n  if(doc.class && doc.class==\"StockFlow\")\n  \temit(doc._id, {_id:doc._id, _rev:doc._rev});\n}"
       },
       "listByDate": {
           "map": "function(doc) {\n  if(doc.class && doc.class==\"StockFlow\"){\t\t\t\n  \temit(doc.tms, doc);\n   }\t\n}"
       },
       "CountSpot": {
           "map": "function(doc){\n\tif(doc.class==\"StockFlow\")\n\t{\n\t\tif(doc.flowId == 300 || doc.flowId == 700)\n        \t\temit([doc.Collection,doc.Spot,doc.Product], 1);\n\t\tif(doc.flowId == 400 || doc.flowId == 800)\n        \t\temit([doc.Collection,doc.Spot,doc.Product], -1);\n\t}\n}",
           "reduce": "function(keys, values) {\n  return sum(values)\n}"
       }
   },
   "updates": {
       "in-place": "function (doc, req) {\n var field = req.query.field;\n var value = req.query.value;\n doc[field] = value;\n  return [doc, value];\n }"
   }
}';
		
		$obj = json_decode($json);
		try {
            $result = $this->couchdb->getDoc($obj->_id);
        } catch (Exception $e) {}
		
		if(!empty($result))
		{
			$obj->_rev = $result->_rev;
		}

        try {
            $this->couchdb->storeDoc($obj);
        } catch (Exception $e) {
            echo "Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
            exit(1);
        }
		return 1;
    }
}

?>
