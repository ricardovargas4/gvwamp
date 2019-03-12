<div>
    <h2 style="font-weight:normal;font-size: 20px;">Olá, </h2>
    <p>Seguem abaixo as atividades com indicador não atingido:</p>
    <table border="2"  style="border: 1px solid black; border-collapse: collapse; padding: 5px; width:80%">
        <tr>
            <th>Login</th>
            <th>Processo</th>
            <th>Data do Indicador</th>
            <th>Periodicidade</th>
            <th>Data Meta</th>
            <th>Data Conciliada</th>


        </tr>
        @foreach($dados as $dt)
        <tr>
            <td> {{ $dt->user_id_FK->email }} </td>
            <td> {{ $dt->processo_id_FK->nome }} </td>
            <td> {{ $dt->data_informada ? \Carbon\Carbon::parse($dt->data_informada)->format('d/m/Y') : null }} </td>
            <td> {{ $dt->periodiciade_id_FK->nome}}</td>           
            <td> {{ $dt->data_meta ? \Carbon\Carbon::parse($dt->data_meta)->format('d/m/Y') : null }} </td>
            <td> {{ $dt->ultima_data ? \Carbon\Carbon::parse($dt->ultima_data)->format('d/m/Y') : null }} </td>
        </tr>
        @endforeach

    </table>
</div>
<br>
    <p>Se houver necessidade, acesse a ferramenta <a href="http://contabil.sicredi.net/gestaoavista/">Gestão À Vista</a> para realizar o expurgo.</p>
    <br>
    <p>Gerência Contábil - GCT</p>
    <p>Superintendência de Controladoria - SUC</p>
    <p>CAS-Confederação Sicredi-Porto Alegre</p>
