<?php
/* Copyright (C) 2011-2012 Patrick Mary           <laube@hotmail.fr>
 
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
 *	    \file       lib/datatables/js/initDatatablesMouv.js.php
 *      
 *		\brief      init dataTable 
 *		\version    $Id: initDatatables.js,v 1.5 2012/04/14 20:54:12 synry63 Exp $
 */

print'<script>';
print'var lang="fr_FR";';
print'columns = ["operateur","datetime","tracking","codemouv","reference","serie","emplacement","check"];';
print'$(document).ready(function() {
oTable = $(\'#mouvement\').dataTable( {
                "bFilter": true,
                "iDisplayLength": -1,
                "aLengthMenu": [[10,25, 50, 100,1000, -1], [10,25, 50, 100,1000,"All"]],
                "bProcessing": true,
                "bServerSide": false,
                "bPaginate": true,
                "sAjaxSource": "serverprocess.php?tracking='.$tfix.'&mouv='.$_POST['mouv'].'",
                "aoColumns": [
                    { "mDataProp": "_id" },
                    { "mDataProp": "operateur","sClass": "readonly"},
                    { "mDataProp": "datetime","sClass": "readonly" },
                    { "mDataProp": "tracking" },
                    { "mDataProp": "codemouv", "bUseRendered": true, "bSearchable": false,
                        "fnRender": function(obj) {
                            var str = obj.aData.codeprestataire+obj.aData.codemouv;
                                
                            return str;
                            }
                    },
                    { "mDataProp": "reference" },
                    { "mDataProp": "serie" },
                    { "mDataProp": "emplacement" },
                    { "mDataProp": null,"sClass": "readonly" }, 
                ],
                "aoColumnDefs": [ {"bVisible": false, "aTargets": [ 0 ]} ],
                
                "oLanguage": {
                    "sUrl": "lib/datatables/langs/"+lang+".txt"
                        
                },
                "sDom": \'T<"clear">lfrtip\'
                /*"oTableTools": {
                    "sSwfPath": "../lib/datatables/swf/copy_cvs_xls_pdf.swf",
                    "aButtons": [
                    "xls"	
                    ]
                }*/
            });   
   var keys = new KeyTable( {
        "table": document.getElementById(\'mouvement\')
    });         
   /* Apply a return key event to each cell in the table */
	keys.event.action( null, null, function (nCell) {
		/* Block KeyTable from performing any events while jEditable is in edit mode */
		keys.block = true;
                
		/* readonly column verification */
                var column = keys.fnGetCurrentPosition()[0];	    
                if(column!=0 && column!=1 && column!=7){    
                
                    /* Initialise the Editable instance for this table */
                    $(nCell).editable( "edit.php", {
                    "callback": function( sValue ) {
                    keys.block = false;
                    },
                    "submitdata":function () {
                        var posi = oTable.fnGetPosition(nCell);
                        var idrow = oTable.fnGetData(posi[0],0);
                        var column = columns[posi[1]];
                                                    return {
                                                            "idrow": idrow,
                                                            "column": column                                                        
                                                    };
                    }
                    }, { 
                            "onblur": \'submit\', 
                            "onreset": function(){ 
                                    setTimeout( function () {keys.block = false;}, 0); 
                            }
                    } );
                    /* Dispatch click event to go into edit mode - Saf 4 needs a timeout... */
                    setTimeout( function () {$(nCell).click();}, 0 );
                    }
                else keys.block = false;
    });
});
/* search on column */ 
$("thead input").keyup( function () {
		/* Filter on the column (the index) of this element */
		var index = $("thead input").index(this)+1;
                oTable.fnFilter( this.value, (index));
});

';
print'</script>';
?>