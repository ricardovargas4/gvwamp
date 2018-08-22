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
Route::get('/auth/logout', 'Auth\AuthController@logout');
Route::get('/auth/login', 'Auth\AuthController@login');
Route::post('/login2', 'Auth\AuthController@login');
Route::get('/teste', 'AtividadeController@teste');
//Route::get('/login', 'Auth\AuthController@login');

Route::group(['middleware'=>'auth'],function(){

    
    Route::post('/relatorio/tempo', 'Controller@tempo');

    Route::get('/chartjs', 'HomeController@chartjs');
    Route::get('/hello', 'HomeController@hello');
    Route::get('/dados/tempo', 'HomeController@dadosTempos');

    //Route::get('/login','LoginController@Form');
    //Route::post('/login','LoginController@Login');
    Route::get('/logout','LoginController@Logout');

    //Responsavel
    Route::get('/responsavel', 'ResponsavelController@lista');

    //Atividade
    Route::get('/home','AtividadeController@home');
    Route::get('/welcome','HomeController@index');
    Route::post('/atividade/iniciar','AtividadeController@iniciar');
    Route::post('/atividade/parar','AtividadeController@parar');
    Route::get('/atividade/novo', 'AtividadeController@novo');
    Route::post('/atividade/adiciona','AtividadeController@adiciona');
    Route::get('/atividade','AtividadeController@filtro');
    Route::match(array('GET', 'POST'),'/atividade/filtro',['uses'=>'AtividadeController@filtro','as'=>'atividade.filtro']);
    Route::get('/atividade/remove/{id}/data_inicial/{data_inicial}/data_final/{data_final}', 'AtividadeController@remove');
    Route::post('/atividade/salvaAlt','AtividadeController@salvaAlt');

    //Historico_Indicador
    Route::get('/historico_indic', 'Historico_indicController@filtro');
    Route::match(array('GET', 'POST'),'/historico_indic/filtro',['uses'=>'Historico_indicController@filtro','as'=>'hist.filtro']);
    Route::get('/indicador_atrasado', 'Historico_indicController@indicador_atrasado_filtro');
    Route::match(array('GET', 'POST'),'/indicador_atrasado/filtro',['uses'=>'Historico_indicController@indicador_atrasado_filtro','as'=>'hist.indicador_atrasado_filtro']);
    
    //Demanda
    Route::get('/demanda', 'DemandaController@lista');

    //Expurgo
    Route::get('/expurgo_indicador', 'Expurgo_IndicadorController@lista');
    Route::get('/expurgo_indicador/tela', 'Expurgo_IndicadorController@tela');
    Route::post('/expurgo_indicador/adiciona', 'Expurgo_IndicadorController@adiciona');

    
});


Route::group(['middleware'=>'Verifica.Gestor'],function(){
//Gestor
   
    //Tipo
    Route::get('/tipo', 'TipoController@lista');

    //Processo
    Route::get('/processo', 'ProcessoController@lista');
    Route::get('/processo/novo', 'ProcessoController@novo');
    Route::post('/processo/adiciona','ProcessoController@adiciona');
    Route::get('/processo/altera/{id}','ProcessoController@altera');
    Route::post('/processo/salvaAlt','ProcessoController@salvaAlt');
    
    //Coordenacao
    Route::get('/coordenacao', 'CoordenacaoController@lista');
    Route::get('/coordenacao/novo', 'CoordenacaoController@novo');
    Route::post('/coordenacao/adiciona','CoordenacaoController@adiciona');
    Route::get('/coordenacao/altera/{id}','CoordenacaoController@altera');
    Route::post('/coordenacao/salvaAlt','CoordenacaoController@salvaAlt');

    //Periodicidade
    Route::get('/periodicidade', 'PeriodicidadeController@lista');
    Route::get('/periodicidade/novo', 'PeriodicidadeController@novo');
    Route::post('/periodicidade/adiciona','PeriodicidadeController@adiciona');
    Route::get('/periodicidade/altera/{id}','PeriodicidadeController@altera');
    Route::post('/periodicidade/salvaAlt','PeriodicidadeController@salvaAlt');
    
    //Responsavel
    Route::get('/responsavel/remove/{id}', 'ResponsavelController@remove');
    Route::get('/responsavel/novo', 'ResponsavelController@novo');
    Route::post('/responsavel/adiciona','ResponsavelController@adiciona');
    Route::get('/responsavel/altera/{id}','ResponsavelController@altera');
    Route::post('/responsavel/salvaAlt','ResponsavelController@salvaAlt');

    //Historico_Indicador
    Route::post('/historico_indic/adiciona','Historico_indicController@adiciona');
    Route::post('/historico_indic/salvaAlt','Historico_indicController@salvaAlt');
    Route::get('/historico_indic/remove/{id}/data_inicial/{data_inicial}/data_final/{data_final}', 'Historico_indicController@remove');

    //Classificacao
    Route::get('/classificacao', 'ClassificacaoController@lista');
    Route::get('/classificacao/remove/{id}', 'ClassificacaoController@remove');
    Route::post('/classificacao/adiciona','ClassificacaoController@adiciona');
    Route::post('/classificacao/salvaAlt','ClassificacaoController@salvaAlt');

    //Demanda
    Route::get('/demanda/remove/{id}', 'DemandaController@remove');
    Route::post('/demanda/adiciona','DemandaController@adiciona');
    Route::post('/demanda/salvaAlt','DemandaController@salvaAlt');

    //Expurgo
    Route::post('/expurgo_indicador/aprovar','Expurgo_IndicadorController@aprovar');
    Route::post('/expurgo_indicador/reprovar','Expurgo_IndicadorController@reprovar');

    //Usuario
    Route::get('/usuario/remove/{id}', 'UserController@remove');
    Route::post('/usuario/adiciona','UserController@adiciona');
    Route::post('/usuario/salvaAlt','UserController@salvaAlt');
    Route::get('/usuario', 'UserController@lista');
});


Route::group(['middleware'=>'Verifica.Desenvolvedor'],function(){
//Adm
//Processo
    Route::get('/processo/remove/{id}', 'ProcessoController@remove');

    //Tipo
    Route::get('/tipo/novo', 'TipoController@novo');
    Route::post('/tipo/adiciona','TipoController@adiciona');
    Route::get('/tipo/altera/{id}','TipoController@altera');
    Route::post('/tipo/salvaAlt','TipoController@salvaAlt');
    Route::get('/tipo/remove/{id}', 'TipoController@remove');
        
    //Coordenacao
    Route::get('/coordenacao/remove/{id}', 'CoordenacaoController@remove');

    //Periodicidade
    Route::get('/periodicidade/remove/{id}', 'PeriodicidadeController@remove');

    //Expurgo
    Route::get('/expurgo_indicador/lista/remove/{id}', 'Expurgo_IndicadorController@remove');
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
