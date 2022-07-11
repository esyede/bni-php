<?php

require __DIR__ . '/../vendor/autoload.php';

use Esyede\BNI\Http\Request;
use Esyede\BNI\Invoices\Callback;

$config = require __DIR__ . '/../config.php';

$request = new Request($config);
$callback = new Callback($request);
$callback->handle(function ($data) {
    // dd($data);

    // update database
    // DB::table('invoices')
    //     ->where('refence_id', $data->trx_id)
    //     ->update(['status' => 'PAID']);

    // do something else..
});