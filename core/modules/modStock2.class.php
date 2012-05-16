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
class modStock2 extends DolibarrModules
{
	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modStock2($db)
	{
        global $langs,$conf;

        $this->db = $db;

		// Id for module (must be unique).
		// Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
		$this->numero = 470;
		$this->rights_class = 'stock2';
		$this->family = "other";
		$this->name = preg_replace('/^mod/i','',get_class($this));
		$this->description = "Gestion de stock avec code à barre";
		$this->version = '1.0';
		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		// Where to store the module in setup page (0=common,1=interface,2=others,3=very specific)
		$this->special = 0;
		$this->picto='generic';

		$this->module_parts = array();

		$this->dirs = array();

		// Config pages. Put here list of php page, stored into mymodule/admin directory, to use to setup module.
		$this->config_page_url = array("mysetuppage.php@mymodule");

		// Dependencies
		$this->depends = array();		// List of modules id that must be enabled if this module is enabled
		$this->requiredby = array();	// List of modules id to disable if this one is disabled
		$this->phpmin = array(5,3);					// Minimum version of PHP required by module
		$this->need_dolibarr_version = array(3,2);	// Minimum version of Dolibarr required by module
		$this->langfiles = array("stock2@stock2");

		$this->const = array();

		$this->tabs = array();

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
		     '_id'=>'menu:stock2',
		    'type'=>'top',
		    'titre'=>'Gestion Stock',
		    'url'=>'/stock2/mouvement.php',
		    'langs'=>'',
		    'position'=>50,
		    'enabled'=>true,
		    'perms'=>'1',
		    'target'=>'',
		    'user'=>2);
		 $r++;
		 $this->menu[$r]=array(	'fk_menu'=>'menu:stock2',
		    'type'=>'',
		    '_id'=>'menu:mouvementstock2',
		    'titre'=>'Mouvement',
		    'url'=>'/stock2/mouvement.php',
		    'langs'=>'',
		    'position'=>0,
		    'enabled'=> true,
		    'perms'=>'1',
		    'target'=>'',
		    'user'=>2);
		$r++;
                $this->menu[$r]=array(	'fk_menu'=>'menu:stock2',
		    'type'=>'',
		    '_id'=>'menu:etatstock2',
		    'titre'=>'Etat stock',
		    'url'=>'/stock2/etatstockProd.php',
		    'langs'=>'',
		    'position'=>1,
		    'enabled'=> true,
		    'perms'=>'1',
		    'target'=>'',
		    'user'=>2);	
		$r++;
		$this->menu[$r]=array(	'fk_menu'=>'menu:etatstock2',
		    'type'=>'',
		    '_id'=>'menu:etatstock2ref',
		    'titre'=>'Etat stock par référence',
		    'url'=>'/stock2/etatstockProd.php',
		    'langs'=>'',
		    'position'=>1,
		    'enabled'=> true,
		    'perms'=>'1',
		    'target'=>'',
		    'user'=>2);	
		$r++;
		$this->menu[$r]=array(	'fk_menu'=>'menu:etatstock2',
		    'type'=>'',
		    '_id'=>'menu:etatstock2spot',
		    'titre'=>'Etat stock par emplacement',
		    'url'=>'/stock2/etatstockSpot.php',
		    'langs'=>'',
		    'position'=>1,
		    'enabled'=> true,
		    'perms'=>'1',
		    'target'=>'',
		    'user'=>2);	
		$r++;
                $this->menu[$r]=array(	'fk_menu'=>'menu:stock2',
		    'type'=>'',
		    '_id'=>'menu:inventairestock2',
		    'titre'=>'Inventaire',
		    'url'=>'/stock2/inventaire.php',
		    'langs'=>'',
		    'position'=>2,
		    'enabled'=>true,
		    'perms'=>'1',
		    'target'=>'',
		    'user'=>2);
		$r++;
                $this->menu[$r]=array(	'fk_menu'=>'menu:stock2',
		    'type'=>'',
		    '_id'=>'menu:facturationsstock2',
		    'titre'=>'Facturation',
		    'url'=>'/stock2/facturation.php',
		    'langs'=>'',
		    'position'=>3,
		    'enabled'=>true,
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

		$result=$this->load_tables();

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


	/**
	 *		Create tables, keys and data required by module
	 * 		Files llx_table1.sql, llx_table1.key.sql llx_data.sql with create table, create keys
	 * 		and create data commands must be stored in directory /mymodule/sql/
	 *		This function is called by this->init
	 *
	 * 		@return		int		<=0 if KO, >0 if OK
	 */
	function load_tables()
	{
		return $this->_load_tables('/mymodule/sql/');
	}
}

?>
