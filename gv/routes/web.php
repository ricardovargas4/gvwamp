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

Route::group(['middleware'=>'auth'],function(){

    //Route::get('/login','LoginController@Form');
    //Route::post('/login','LoginController@Login');
    Route::get('/logout','LoginController@Logout');

    Route::get('/processo', 'ProcessoController@lista');
    Route::get('/processo/remove/{id}', 'ProcessoController@remove');
    Route::get('/processo/novo', 'ProcessoController@novo');
    Route::post('/processo/adiciona','ProcessoController@adiciona');
    Route::get('/processo/altera/{id}','ProcessoController@altera');
    Route::post('/processo/salvaAlt','ProcessoController@salvaAlt');

    Route::get('/tipo', 'TipoController@lista');
    Route::get('/tipo/remove/{id}', 'TipoController@remove');
    Route::get('/tipo/novo', 'TipoController@novo');
    Route::post('/tipo/adiciona','TipoController@adiciona');
    Route::get('/tipo/altera/{id}','TipoController@altera');
    Route::post('/tipo/salvaAlt','TipoController@salvaAlt');

    Route::get('/coordenacao', 'CoordenacaoController@lista');
    Route::get('/coordenacao/remove/{id}', 'CoordenacaoController@remove');
    Route::get('/coordenacao/novo', 'CoordenacaoController@novo');
    Route::post('/coordenacao/adiciona','CoordenacaoController@adiciona');
    Route::get('/coordenacao/altera/{id}','CoordenacaoController@altera');
    Route::post('/coordenacao/salvaAlt','CoordenacaoController@salvaAlt');

    Route::get('/periodicidade', 'PeriodicidadeController@lista');
    Route::get('/periodicidade/remove/{id}', 'PeriodicidadeController@remove');
    Route::get('/periodicidade/novo', 'PeriodicidadeController@novo');
    Route::post('/periodicidade/adiciona','PeriodicidadeController@adiciona');
    Route::get('/periodicidade/altera/{id}','PeriodicidadeController@altera');
    Route::post('/periodicidade/salvaAlt','PeriodicidadeController@salvaAlt');

    Route::get('/responsavel', 'ResponsavelController@lista');
    Route::get('/responsavel/remove/{id}', 'ResponsavelController@remove');
    Route::get('/responsavel/novo', 'ResponsavelController@novo');
    Route::post('/responsavel/adiciona','ResponsavelController@adiciona');
    Route::get('/responsavel/altera/{id}','ResponsavelController@altera');
    Route::post('/responsavel/salvaAlt','ResponsavelController@salvaAlt');


    Route::get('/home','AtividadeController@home');
    Route::get('/welcome','HomeController@index');
    Route::post('/atividade/iniciar','AtividadeController@iniciar');
    Route::post('/atividade/parar','AtividadeController@parar');

    Route::get('/atividade/novo', 'AtividadeController@novo');
    Route::post('/atividade/adiciona','AtividadeController@adiciona');
    Route::get('/atividade','AtividadeController@filtro');
    //Route::post('/atividade/filtro','AtividadeController@lista');
    Route::match(array('GET', 'POST'),'/atividade/filtro',['uses'=>'AtividadeController@filtro','as'=>'atividade.filtro']);
    //Route::get('/atividade/altera/{id}','AtividadeController@altera');
    //Route::get('/atividade/remove/{id}', 'AtividadeController@remove');
    Route::get('/atividade/remove/{id}/data_inicial/{data_inicial}/data_final/{data_final}', 'AtividadeController@remove');
    Route::post('/atividade/salvaAlt','AtividadeController@salvaAlt');

    Route::get('/historico_indic', 'Historico_indicController@filtro');
    Route::match(array('GET', 'POST'),'/historico_indic/filtro',['uses'=>'Historico_indicController@filtro','as'=>'hist.filtro']);
    Route::post('/historico_indic/adiciona','Historico_indicController@adiciona');
    Route::post('/historico_indic/salvaAlt','Historico_indicController@salvaAlt');
    Route::get('/historico_indic/remove/{id}/data_inicial/{data_inicial}/data_final/{data_final}', 'Historico_indicController@remove');

    Route::get('/classificacao', 'ClassificacaoController@lista');
    Route::get('/classificacao/remove/{id}', 'ClassificacaoController@remove');
    Route::post('/classificacao/adiciona','ClassificacaoController@adiciona');
    Route::post('/classificacao/salvaAlt','ClassificacaoController@salvaAlt');

    Route::get('/demanda', 'DemandaController@lista');
    Route::get('/demanda/remove/{id}', 'DemandaController@remove');
    Route::post('/demanda/adiciona','DemandaController@adiciona');
    Route::post('/demanda/salvaAlt','DemandaController@salvaAlt');

    Route::get('/expurgo_indicador/lista', 'Expurgo_IndicadorController@lista');
    Route::get('/expurgo_indicador/lista/remove/{id}', 'Expurgo_IndicadorController@remove');
    Route::post('/expurgo_indicador/adiciona', 'Expurgo_IndicadorController@adiciona');

    Route::get('/usuario', 'UserController@lista');
    Route::get('/usuario/remove/{id}', 'UserController@remove');
    Route::post('/usuario/adiciona','UserController@adiciona');
    Route::post('/usuario/salvaAlt','UserController@salvaAlt');
    

    Route::post('/relatorio/tempo', 'Controller@tempo');

    Route::get('/chartjs', 'HomeController@chartjs');
    Route::get('/hello', 'HomeController@hello');
    Route::get('/dados/tempo', 'HomeController@dadosTempos');

});

Route::get('/teste',function(){
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
