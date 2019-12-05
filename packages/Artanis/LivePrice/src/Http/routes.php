<?php
Route::group(['middleware' => ['web']], function () {
    // Route::view('/purchase', 'gapsap::index');

    Route::post('/liveprice', 'Artanis\GapSap\Http\Controllers\LivePriceController@index')->defaults('_config', [
        'view' => 'liveprice::index'
    ])->name('liveprice.index');
});
