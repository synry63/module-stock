/* init dataTable */ 
$(document).ready(function() {
oTable = $('#mouvement').dataTable( {
                 "bFilter": false,
                "iDisplayLength": 10,
                "aLengthMenu": [[10,25, 50, 100,1000, -1], [10,25, 50, 100,1000,"All"]],
                "bProcessing": true,
                "bServerSide": false,
                "sAjaxSource": "exemplemouv",
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

   var keys = new KeyTable( {
        "table": document.getElementById('mouvement')
    });         
   /* Apply a return key event to each cell in the table */
	keys.event.action( null, null, function (nCell) {
		/* Block KeyTable from performing any events while jEditable is in edit mode */
		keys.block = true;
		
		/* Initialise the Editable instance for this table */
		$(nCell).editable( function (sVal) {
			/* Submit function (local only) - unblock KeyTable */
			keys.block = false;
			return sVal;
		}, { 
			"onblur": 'submit', 
			"onreset": function(){ 
				/* Unblock KeyTable, but only after this 'esc' key event has finished. Otherwise
				 * it will 'esc' KeyTable as well
				 */
				setTimeout( function () {keys.block = false;}, 0); 
			}
		} ); 
                /* Dispatch click event to go into edit mode - Saf 4 needs a timeout... */
		setTimeout( function () { $(nCell).click(); }, 0 );
    });
});