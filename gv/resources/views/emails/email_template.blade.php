<div>
    <h2>Olá, </h2>
    <p>Essas datas estão com carga horária superior a 10h, favor ajustar:</p>
    <table border="2"  style="border: 1px solid black; border-collapse: collapse; padding: 5px; width:80%">
        <tr>
            <th>Login</th>
            <th>Data</th>
            <th>Horas</th>
        </tr>
        @foreach($dados as $dt)
        <tr>
            <td> {{ $dt->email }} </td>
            <td> {{ $dt->data_conciliacao }} </td>
            <td> {{ $dt->horas }} </td>
        </tr>
        @endforeach

    </table>
</div>
<br>
    <p>Acesse a ferramenta <a href="http://contabil.sicredi.net/gestaoavista/">Gestão À Vista</a> para realizar os ajustes.</p>
    <br>
    <p>Gerência Contábil - GCT</p>
    <p>Superintendência de Controladoria - SUC</p>
    <p>CAS-Confederação Sicredi-Porto Alegre</p>
