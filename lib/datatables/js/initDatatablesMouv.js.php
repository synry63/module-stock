<?php
/* init dataTable */ 
print'<script>';
print'columns = ["operateur","datetime","tracking","codemouv","reference","serie","emplacement","check"];';
print'$(document).ready(function() {
oTable = $(\'#mouvement\').dataTable( {
                 "bFilter": false,
                "iDisplayLength": 10,
                "aLengthMenu": [[10,25, 50, 100,1000, -1], [10,25, 50, 100,1000,"All"]],
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "serverprocess.php?tracking='.$tfix.'&mouv='.$_POST['mouv'].'",
                "aoColumns": [
                    { "mDataProp": "_id" },
                    { "mDataProp": "operateur" },
                    { "mDataProp": "datetime" },
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
                    { "mDataProp": null }, 
                ],
                "aoColumnDefs": [ {"bVisible": false, "aTargets": [ 0 ]} ],
                "bPaginate": true,
                /*"oLanguage": {
                    "sUrl": "../lib/datatables/langs/"+lang+".txt"
                        
                },*/
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
    });
});
';
print'</script>';
?>