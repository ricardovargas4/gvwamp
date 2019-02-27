$(document).ready(function () {
    var testeid = document.querySelectorAll('[id^="Btn"]');
    for (var index = 0; index < testeid.length; index++) {
        var text = testeid[index].id;
        if (text.match(/Btn.*/)) {
            document.getElementById(text).onclick = function() {
                var text = this.id;
                myString = text.replace(/\D/g,'');
                var teste = document.getElementById("DtConc"+myString).value;
                if (teste == "") {
                    alert("É necessário preencher a Data Conciliada.");
                    return false;
                }else {
                    var currentTime = new Date();
                    var parts = teste.split('/');
                    var mydate = new Date(parts[2], parts[1]-1,parts[0]); 
                    if(mydate>currentTime){
                        alert("A data é maior do que a data atual.");
                        return false;
                    }
                }
            };
        }
    }
});




