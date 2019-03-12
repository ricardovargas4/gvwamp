<div>
    <h2 style="font-weight:normal;font-size: 20px;">Olá, </h2>
    <p>Seguem abaixo as alterações de responsabilidade que ocorreram no útimo dia:</p>
    <table border="2"  style="border: 1px solid black; border-collapse: collapse; padding: 5px; width:80%">
        <tr>
            <th>Login</th>
            <th>Processo</th>
            <th>Data</th>
            <th>Tipo de Alteração</th>
        </tr>
        @foreach($dados as $dt)
        <tr>
            <td> {{ $dt->user_FK->email }} </td>
            <td> {{ $dt->processo_FK->nome }} </td>
            <td> {{ $dt->DATA_ALTERACAO ? \Carbon\Carbon::parse($dt->DATA_ALTERACAO)->format('d/m/Y') : null }} </td>
            <td> {{ $dt->TIPO}}</td>           
        </tr>
        @endforeach

    </table>
</div>
<br>
    <p>Gerência Contábil - GCT</p>
    <p>Superintendência de Controladoria - SUC</p>
    <p>CAS-Confederação Sicredi-Porto Alegre</p>
{{dd('1')}}