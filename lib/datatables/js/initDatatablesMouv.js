/* init dataTable */ 
$(document).ready(function() {
columns = ["","id","operateur","datetime","tracking","codemouv","reference","serie","emplacement","check"];
oTable = $('#mouvement').dataTable( {
                "bFilter": true,
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
   var keys = new KeyTable( {
        "table": document.getElementById('mouvement')
    });         
   /* Apply a return key event to each cell in the table */
	keys.event.action( null, null, function (nCell) {
		/* Block KeyTable from performing any events while jEditable is in edit mode */
		keys.block = true;
		/* Initialise the Editable instance for this table */
		//$(nCell).editable( "test.php", {
                //$('td', oTable.fnGetNodes()).editable( "test.php", {
                //"callback": function( sValue ) {
          //          var aPos = oTable.fnGetPosition( this );
          //          oTable.fnUpdate(sValue, aPos[0], aPos[1],false,false);
                //},
               // "onblur": 'submit', 
                /*"submitdata":function () {
                    var posi = oTable.fnGetPosition(this);
                    var idrow = oTable.fnGetData(posi[0],0);
						return {
							"idrow": idrow
						};
                },*/
              //  "onreset": function(){ 
				
		//		setTimeout( function () {keys.block = false;}, 0); 
		//	}
            var posi = keys.fnGetCurrentPosition();	    
            if(posi[0]!=0 && posi[0]!=1){    
            $(nCell).editable( "test.php", {
                "callback": function( sValue ) {
                     // var aPos = oTable.fnGetPosition( this );
                     // oTable.fnUpdate(sValue, aPos[0], aPos[1],true,true);
                        keys.block = false;
			//return sVal;
		},
                "submitdata":function () {
                    var t = nCell;
                  //  var t = $(t[class!="readonly"]his).attr("class");
                    var posi = oTable.fnGetPosition(this);
                    var idrow = oTable.fnGetData(posi[0],0);
						return {
							"idrow": idrow
						}; 
         
                    
                    },
                    "onblur": 'submit',
                    "onreset": function(){ 
				setTimeout( function () {keys.block = false;}, 0); 
                               // keys.block = false;  
                    }
                } /*{ 
			"onblur": function(){ 
                          setTimeout( function () {keys.block = false;}, 0);  
                          keys.block = false;  
                        },
			"onreset": function(){ 
				setTimeout( function () {keys.block = false;}, 0); 
			}
		}*/
            );
                /* Dispatch click event to go into edit mode - Saf 4 needs a timeout... */
		setTimeout( function () {$(nCell).click();}, 0 );
        }
        else keys.block = false;
    });
/* search on column */ 
$("thead input").keyup( function () {
		/* Filter on the column (the index) of this element */
                var index = $("thead input").index(this);
		oTable.fnFilter( this.value, index );
} );
//$("label").css("visibility","hidden");
$("label").hide();
});
