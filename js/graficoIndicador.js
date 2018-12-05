
function dadosBanco(token,dataInicial,dataFinal,coordenacaoID,processosSelec, urlrota,tipo_relatorio,callback) {
    $.ajax({
    /*method: 'GET',
        url: urlrota,
        success: callback,
        error: function (error) {
        }*/

        type:'POST',
        url:urlrota,
        dataType: 'JSON',
        data: {
            "_method": 'POST',
            "_token": token,
            "dataInicial": dataInicial,
            "dataFinal": dataFinal,
            "coordenacaoID": coordenacaoID,
            "processosSelec": processosSelec,
            "tipo_relatorio": tipo_relatorio,
        },
        success:callback,
        error: function(error){
        }

    })
}

function filtrar(dados,argumento,argumento2){
            var data2 = new Array();
            dados.forEach(function(entry) {
                if(entry.parentID==argumento && entry.parentID2==argumento2) {
                    data2.push(entry);
                };
            });
            return { data2: data2} 
        }

$(document).ready(function () {
    function listaProc() {
        var checa = $("input:checkbox")
        var numElementos = checa.length;
        var processosSelec = "";
        for(var x=0; x<numElementos; x++){
            if(document.getElementById(checa[x].id).checked == true){
                if(x>0){
                    processosSelec = processosSelec + ',';
                }
                processosSelec = processosSelec +  document.getElementById(checa[x].id).value ;
            }
        }
        return processosSelec;
    };
    $('#buttonVoltar').hide(); 
    urlrota = $("#remove-step-form").attr('action');
    var token = document.getElementsByName("_token")[0].value;
    var dataInicial = document.getElementById("dataInicial").value;
    var dataFinal = document.getElementById("dataFinal").value;
    var coordenacaoID = document.getElementById("coordenacaoID").value;
    var tipo_relatorio = document.getElementById("tipo_relatorio").value;
    console.log(tipo_relatorio);
    dadosBanco(token,dataInicial,dataFinal,coordenacaoID,listaProc(),urlrota,tipo_relatorio,function(data){
        var parent = new Array()
        var parent2 = new Array()
        var tempo = new Array();
        var usuarios = new Array();
        parent.push('');
        parent2.push('');
        console.log(data);
        var novaData = filtrar(data,'','');
        novaData.data2.forEach(function(entry) {
            tempo.push(entry.val);
            usuarios.push(entry.arg);
        });
        //Filtro Usuario
        /*var select = document.getElementById("usuariosID");
        for(index in usuarios) {
            select.options[select.options.length] = new Option(usuarios[index], usuarios[index]);
        }*/
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: usuarios,
                datasets: [{
                    label: 'Indicador',
                    data: tempo,
                    backgroundColor: '#00E676',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                            xAxes: [{
                                stacked: false,
                                beginAtZero: true, 
                                suggestedMin: 0,
                                scaleLabel: {
                                    labelString: 'Month'
                                },
                                ticks: {
                                    stepSize: 1,
                                    min: 0,
                                    autoSkip: false
                                }
                            }],
                            yAxes: [{
                                beginAtZero: true, 
                                suggestedMin: 0,
                                ticks: {
                                    min: 0
                                }
                            }]
                        }
            }
        });
        //Filtro Usuario
       /* document.getElementById("usuariosID").onclick = function(evt)
        {  
            var teste = $('#usuariosID').val();
            console.log(teste);
        }*/
        
        document.getElementById("myChart").onclick = function(evt)
        {   
            var activePoints = myChart.getElementsAtEvent(evt);

            if(activePoints.length > 0) {
            //get the internal index of slice in pie chart
                var clickedElementindex = activePoints[0]["_index"];

            //get specific label by index 
                var label = myChart.data.labels[clickedElementindex];

            //get value by index      
                var value = myChart.data.datasets[0].data[clickedElementindex];
                if(parent.length==2){
                    parent.push(label);
                    if(parent.length>0){
                        $('#buttonVoltar').show();
                        //console.log(parent[parent.length-1]);
                    }
                    //console.log("Label-1 :"+parent[parent.length-1])
                    //console.log("Label2 :"+label)
                    var novaData = filtrar(data,parent[parent.length-2],label);
                    tempo.length=0;
                    usuarios.length=0;
                    novaData.data2.forEach(function(entry) {
                        tempo.push(entry.val);
                        usuarios.push(entry.arg);
                    });
                    myChart.update();
                    document.getElementById("data").innerHTML = "Data: " + parent[parent.length - 1];
                }
                if(parent.length==1){
                    parent.push(label);
                    if(parent.length>0){
                        $('#buttonVoltar').show();
                      }
                    var novaData = filtrar(data,label,'');
                    tempo.length=0;
                    usuarios.length=0;
                    novaData.data2.forEach(function(entry) {
                        tempo.push(entry.val);
                        usuarios.push(entry.arg);
                    });
                    myChart.update();
                    document.getElementById("nome_usuario").innerHTML = "Usuário: " + parent[parent.length - 1];
                }
        }
        }
        function voltar() {
            if(parent.length==2){
            
                parent.length=parent.length-1;
                var novaData = filtrar(data,parent[parent.length - 1] ,'');
                tempo.length=0;
                usuarios.length=0;
                novaData.data2.forEach(function(entry) {
                    tempo.push(entry.val);
                    usuarios.push(entry.arg);
                });
                myChart.update();
                if(parent.length<=1){
                    $('#buttonVoltar').hide(); 
                }
                document.getElementById("nome_usuario").innerHTML = "";
            } 
            if(parent.length==3){
                parent.length=parent.length-1;
                var novaData = filtrar(data,parent[parent.length - 1] ,'');
                tempo.length=0;
                usuarios.length=0;

                novaData.data2.forEach(function(entry) {
                    tempo.push(entry.val);
                    usuarios.push(entry.arg);
                });
                myChart.update();
                document.getElementById("data").innerHTML = parent[parent.length - 2];
                }           
        };
        document.getElementById("buttonVoltar").onclick = function() {voltar()};
        
        function marcarTodos() {
            var checa = $("input:checkbox")
            var numElementos = checa.length;
            for(var x=0; x<numElementos; x++){
                document.getElementById(checa[x].id).checked = true;
            }
        };
        function desmarcarTodos() {
            var checa = $("input:checkbox")
            var numElementos = checa.length;
            for(var x=0; x<numElementos; x++){
                document.getElementById(checa[x].id).checked = false;
            }
        };
        document.getElementById("buttonMarcar").onclick = function() {marcarTodos()};
        document.getElementById("buttonDesmarcar").onclick = function() {desmarcarTodos()};
    });
});
