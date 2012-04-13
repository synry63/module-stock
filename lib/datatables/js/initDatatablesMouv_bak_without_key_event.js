/* init dataTable */ 
$(document).ready(function() {
columns = ["","id","operateur","datetime","tracking","codemouv","reference","serie","emplacement","check"];
oTable = $('#mouvement').dataTable( {
                 "bFilter": false,
                "iDisplayLength": 10,
                "aLengthMenu": [[10,25, 50, 100,1000, -1], [10,25, 50, 100,1000,"All"]],
                "bProcessing": true,
                "bServerSide": false,
                //"sAjaxSource": "serverprocess.php?tracking='.$tfix.'&mouv='.$_POST['mouv'].'",
                
                "bPaginate": true,
                /*"oLanguage": {
                    "sUrl": "../lib/datatables/langs/"+lang+".txt"
                        
                },*/
                "sDom": 'T<"clear">lfrtip'
                /*"oTableTools": {
                    "sSwfPath": "../lib/datatables/swf/copy_cvs_xls_pdf.swf",
                    "aButtons": [
                    "xls"	
                    ]
                }*/
            });

   //var keys = new KeyTable( {
   //     "table": document.getElementById('mouvement')
   // });         
   /* Apply a return key event to each cell in the table */
//	keys.event.action( null, null, function (nCell) {
		/* Block KeyTable from performing any events while jEditable is in edit mode */
//		keys.block = true;
		
		/* Initialise the Editable instance for this table */
		//$(nCell).editable( "test.php", {
                $('td', oTable.fnGetNodes()).editable( "test.php", {
                "callback": function( sValue ) {
                    var aPos = oTable.fnGetPosition( this );
                    oTable.fnUpdate(sValue, aPos[0], aPos[1],false,false);
                },
                "submitdata":function () {
                    var posi = oTable.fnGetPosition(this);
                    var idrow = oTable.fnGetData(posi[0],0);
						return {
							"idrow": idrow
						};
                },
                "onreset": function(){ 
				
		//		setTimeout( function () {keys.block = false;}, 0); 
			}
            } );
                /* Dispatch click event to go into edit mode - Saf 4 needs a timeout... */
	//	setTimeout( function () {$(nCell).click();}, 0 );
    });
