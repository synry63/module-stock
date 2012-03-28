function fnClickAddRow() {
   /*var data =  $("#tracking").val();
   var row = [
    ".1",
    ".2",
    ".3",
    ".4",
    ".5",
    ".6",
    ".7" ];

   var xhr = getXMLHttpRequest();
   xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
                        var t = xhr.response;
			oTable.fnAddData(row, true);
                         
                }
                
      };
   xhr.open("GET", "data.php?data="+data,true);
   xhr.send(null);*/
   oTable.fnDraw(); 
}