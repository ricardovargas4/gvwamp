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
                }
            };
        }
    }
});




