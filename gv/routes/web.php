<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();
//Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/auth/logout', 'Auth\AuthController@logout');


Auth::routes();
//Route::get('/homea', 'HomeController@hello');
//Route::get('/auth/logout', 'Auth\AuthController@logout');
//Route::get('/auth/logout', ['uses'=>'Auth\AuthController@logout', 'as'=>'logout']);
//Route::get('/auth/login', 'Auth\AuthController@login');
//Route::post('/login2', 'Auth\AuthController@login');
Route::post('/loginLdap', ['uses'=>'Auth\AuthController@login', 'as'=>'loginLdap']);
Route::get('/teste', 'AtividadeController@teste');
Route::get('/login',  ['uses'=>'Auth\AuthController@telaLogin', 'as'=>'telaLogin']);
Route::get('/', function () {
    return redirect('/home');// view('welcome');
});

Route::group(['middleware'=>'auth'],function(){

    Route::match(array('GET', 'POST'),'/relatorio/tempo',['uses'=>'HomeController@tempo','as'=>'home.tempo']);
    
    Route::get('/hello', ['uses'=>'HomeController@hello', 'as'=>'home.hello']);
    //Route::get('/dados/tempo/{dataInicial}/{dataFinal}/{coordenacaoID}', ['uses'=>'HomeController@dadosTempos', 'as'=>'home.dadosTempos']);
    Route::post('/dados/tempo', ['uses'=>'HomeController@dadosTempos', 'as'=>'home.dadosTempos']);

    //Route::get('/login','LoginController@Form');
    //Route::post('/login','LoginController@Login');
    Route::get('/logout',  ['uses'=>'Auth\AuthController@logout', 'as'=>'logout']);

    //Responsavel
    Route::get('/responsavel', ['uses'=>'ResponsavelController@lista', 'as'=>'responsavel.lista']);
    Route::get('/responsavel/?page={?}', ['uses'=>'ResponsavelController@lista', 'as'=>'responsavel.lista']);

    //Atividade
    Route::get('/home', ['uses'=>'AtividadeController@home', 'as'=>'atividade.home']);
    //Route::get('/home','AtividadeController@home');
    Route::get('/welcome','HomeController@index');

    Route::post('/atividade/iniciar', ['uses'=>'AtividadeController@iniciar', 'as'=>'atividade.iniciar']);
    Route::post('/atividade/parar',['uses'=>'AtividadeController@parar', 'as'=>'atividade.parar']);
    Route::get('/atividade/novo', ['uses'=>'AtividadeController@novo', 'as'=>'atividade.novo']);
    Route::post('/atividade/adiciona',['uses'=>'AtividadeController@adiciona', 'as'=>'atividade.adiciona']);
    Route::get('/atividade',['uses'=>'AtividadeController@filtro', 'as'=>'atividade.filtro']);
    Route::match(array('GET', 'POST'),'/atividade/filtro',['uses'=>'AtividadeController@filtro','as'=>'atividade.filtro']);
    Route::get('/atividade/remove/{id}/data_inicial/{data_inicial}/data_final/{data_final}', ['uses'=>'AtividadeController@remove','as'=>'atividade.remove']);
    Route::post('/atividade/salvaAlt',['uses'=>'AtividadeController@salvaAlt','as'=>'atividade.salvaAlt']);

    //Historico_Indicador
    Route::get('/historico_indic', ['uses'=>'Historico_indicController@filtro','as'=>'historico.filtro']);
    Route::match(array('GET', 'POST'),'/historico_indic/filtro',['uses'=>'Historico_indicController@filtro','as'=>'historico.filtro']);
    Route::match(array('GET', 'POST'),'/indicador_atrasado/filtro',['uses'=>'Historico_indicController@indicador_atrasado_filtro','as'=>'historico.indicador_atrasado_filtro']);
    
    //Demanda
    Route::get('/demanda', ['uses'=>'DemandaController@lista', 'as'=>'demanda.lista']);

    //Expurgo
    Route::get('/expurgo_indicador', ['uses'=>'Expurgo_IndicadorController@lista', 'as'=>'expurgo.lista']);
    Route::get('/expurgo_indicador/tela', ['uses'=>'Expurgo_IndicadorController@tela', 'as'=>'expurgo.tela']);
    Route::post('/expurgo_indicador/adiciona', ['uses'=>'Expurgo_IndicadorController@adiciona', 'as'=>'expurgo.adiciona']);

    Route::group(['middleware'=>'Verifica.Gestor'],function(){
    //Gestor
    
        //Tipo
        Route::get('/tipo', ['uses'=>'TipoController@lista', 'as'=>'tipo.lista']);

        //Processo
        Route::get('/processo', ['uses'=>'ProcessoController@lista', 'as'=>'processo.lista']);
        Route::get('/processo/novo', ['uses'=>'ProcessoController@novo', 'as'=>'processo.novo']);
        Route::post('/processo/adiciona', ['uses'=>'ProcessoController@adiciona', 'as'=>'processo.adiciona']);
        Route::get('/processo/altera/{id}',['uses'=>'ProcessoController@altera', 'as'=>'processo.altera']);
        Route::post('/processo/salvaAlt',['uses'=>'ProcessoController@salvaAlt', 'as'=>'processo.salvaAlt']);
        
        //Coordenacao
        Route::get('/coordenacao', ['uses'=>'CoordenacaoController@lista', 'as'=>'coordenacao.lista']);
        Route::get('/coordenacao/novo', ['uses'=>'CoordenacaoController@novo', 'as'=>'coordenacao.novo']);
        Route::post('/coordenacao/adiciona',['uses'=>'CoordenacaoController@adiciona', 'as'=>'coordenacao.adiciona']);
        Route::get('/coordenacao/altera/{id}',['uses'=>'CoordenacaoController@altera', 'as'=>'coordenacao.altera']);
        Route::post('/coordenacao/salvaAlt',['uses'=>'CoordenacaoController@salvaAlt', 'as'=>'coordenacao.salvaAlt']);

        //Periodicidade
        Route::get('/periodicidade', ['uses'=>'PeriodicidadeController@lista', 'as'=>'periodicidade.lista']);
        Route::get('/periodicidade/novo', ['uses'=>'PeriodicidadeController@novo', 'as'=>'periodicidade.novo']);
        Route::post('/periodicidade/adiciona',['uses'=>'PeriodicidadeController@adiciona', 'as'=>'periodicidade.adiciona']);
        Route::get('/periodicidade/altera/{id}',['uses'=>'PeriodicidadeController@altera', 'as'=>'periodicidade.altera']);
        Route::post('/periodicidade/salvaAlt',['uses'=>'PeriodicidadeController@salvaAlt', 'as'=>'periodicidade.salvaAlt']);
        
        //Responsavel
        Route::get('/responsavel/remove/{id}', ['uses'=>'ResponsavelController@remove', 'as'=>'responsavel.remove']);
        Route::get('/responsavel/novo', ['uses'=>'ResponsavelController@novo', 'as'=>'responsavel.novo']);
        Route::post('/responsavel/adiciona',['uses'=>'ResponsavelController@adiciona', 'as'=>'responsavel.adiciona']);
        Route::get('/responsavel/altera/{id}', ['uses'=>'ResponsavelController@altera', 'as'=>'responsavel.altera']);
        Route::post('/responsavel/salvaAlt',['uses'=>'ResponsavelController@salvaAlt', 'as'=>'responsavel.salvaAlt']);

        //Historico_Indicador
        Route::post('/historico_indic/adiciona',['uses'=>'Historico_indicController@adiciona', 'as'=>'historico.adiciona']);
        Route::post('/historico_indic/salvaAlt',['uses'=>'Historico_indicController@salvaAlt', 'as'=>'historico.salvaAlt']);
        Route::get('/historico_indic/remove/{id}/data_inicial/{data_inicial}/data_final/{data_final}', ['uses'=>'Historico_indicController@remove', 'as'=>'historico.remove']);

        //Classificacao
        Route::get('/classificacao', ['uses'=>'ClassificacaoController@lista', 'as'=>'classificacao.lista']);
        Route::get('/classificacao/remove/{id}', ['uses'=>'ClassificacaoController@remove', 'as'=>'classificacao.remove']);
        Route::post('/classificacao/adiciona',['uses'=>'ClassificacaoController@adiciona', 'as'=>'classificacao.adiciona']);
        Route::post('/classificacao/salvaAlt',['uses'=>'ClassificacaoController@salvaAlt', 'as'=>'classificacao.salvaAlt']);

        //Demanda
        Route::get('/demanda/remove/{id}', ['uses'=>'DemandaController@remove', 'as'=>'demanda.remove']);
        Route::post('/demanda/adiciona',['uses'=>'DemandaController@adiciona', 'as'=>'demanda.adiciona']);
        Route::post('/demanda/salvaAlt',['uses'=>'DemandaController@salvaAlt', 'as'=>'demanda.salvaAlt']);

        //Expurgo
        Route::post('/expurgo_indicador/aprovar',['uses'=>'Expurgo_IndicadorController@aprovar', 'as'=>'expurgo.aprovar']);
        Route::post('/expurgo_indicador/reprovar',['uses'=>'Expurgo_IndicadorController@reprovar', 'as'=>'expurgo.reprovar']);

        //Usuario
        Route::get('/usuario/remove/{id}', ['uses'=>'UserController@remove', 'as'=>'usuario.remove']);
        Route::post('/usuario/adiciona',['uses'=>'UserController@adiciona', 'as'=>'usuario.adiciona']);
        Route::post('/usuario/salvaAlt',['uses'=>'UserController@salvaAlt', 'as'=>'usuario.salvaAlt']);
        Route::get('/usuario', ['uses'=>'UserController@lista', 'as'=>'usuario.lista']);
    });


    Route::group(['middleware'=>'Verifica.Desenvolvedor'],function(){
    //Adm
    //Processo
        Route::get('/processo/remove/{id}',  ['uses'=>'ProcessoController@remove', 'as'=>'processo.remove']);

        //Tipo
        Route::get('/tipo/novo', ['uses'=>'TipoController@novo', 'as'=>'tipo.novo']);
        Route::post('/tipo/adiciona',['uses'=>'TipoController@adiciona', 'as'=>'tipo.adiciona']);
        Route::get('/tipo/altera/{id}',['uses'=>'TipoController@altera', 'as'=>'tipo.altera']);
        Route::post('/tipo/salvaAlt',['uses'=>'TipoController@salvaAlt', 'as'=>'tipo.salvaAlt']);
        Route::get('/tipo/remove/{id}', ['uses'=>'TipoController@remove', 'as'=>'tipo.remove']);
            
        //Coordenacao
        Route::get('/coordenacao/remove/{id}', ['uses'=>'CoordenacaoController@remove', 'as'=>'coordenacao.remove']);

        //Periodicidade
        Route::get('/periodicidade/remove/{id}',  ['uses'=>'PeriodicidadeController@remove', 'as'=>'periodicidade.remove']);

        //Expurgo
        Route::get('/expurgo_indicador/lista/remove/{id}', ['uses'=>'Expurgo_IndicadorController@remove', 'as'=>'expurgo.remove']);
    });
    
});

Route::get('/testeSchedule',function(){
    $exitCode = Artisan::call('schedule:run');
    return '<h1>Schedule criado</h1>'; 
});

Route::get('/testeIndic',function(){
    $exitCode = Artisan::call('ProcIndicador:indicador');
    return '<h1>Executado Indicador</h1>'; 
});

Route::get('/cache', function(){

    /*
    $usuario_id = \Auth::id();
    $usuario = \App\User::find($usuario_id);
    if ($usuario->nivel > 1){
        return redirect()->route('home.index');
    }
    */

    //exec('composer dump-autoload');
    //echo '<h1>Dump Autoload</h1>';

    
    //Clear Cache facade value:
    $exitCode = Artisan::call('cache:clear');
    echo '<h1>Cache facade value cleared</h1>';

    //Reoptimized class loader:
    $exitCode = Artisan::call('optimize');
    echo '<h1>Reoptimized class loader</h1>';

    //Route cache:
    //$exitCode = Artisan::call('route:cache');
    //echo '<h1>Routes cached</h1>';

    //Clear Route cache:
    $exitCode = Artisan::call('route:clear');
    echo '<h1>Route cache cleared</h1>';

    //Clear View cache:
    $exitCode = Artisan::call('view:clear');
    echo '<h1>View cache cleared</h1>';

    //Clear Config cache:
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';

    //Clear View cache:
    //$exitCode = Artisan::call('schedule:run');
    //return '<h1>Schedule criado</h1>';

});
