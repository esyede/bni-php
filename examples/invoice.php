<?php

require __DIR__ . '/../vendor/autoload.php';

use Esyede\BNI\Invoices\Invoice;
use Esyede\BNI\Http\Request;

$config = require __DIR__ . '/../config.php';

$request = new Request($config);

/*
|--------------------------------------------------------------------------
| Contoh create invoice
|--------------------------------------------------------------------------
*/
$trxId = '12345677';
$invoice = (new Invoice($request))
    ->setType('createBilling') // Penting! jenis transaksi
    ->setClientId($configs['client_id'])
    ->setTrxId($trxId)
    ->setTrxAmount(100000)
    ->setCustomerName('Asep Balon')
    ->run();

print_r($invoice); exit;

/*
|--------------------------------------------------------------------------
| Contoh inquiry status invoice
|--------------------------------------------------------------------------
*/

$trxId = '12345677';
$invoice = (new Invoice($request))
    ->setType('inquiryBilling') // Penting! jenis transaksi
    ->setClientId($configs['client_id'])
    ->setTrxId($trxId)
    ->run();

print_r($invoice); exit;

/*
|--------------------------------------------------------------------------
| Contoh update invoice
|--------------------------------------------------------------------------
*/
$trxId = '12345677';
$invoice = (new Invoice($bniRequest))
    ->setType('updateBilling') // Penting! jenis transaksi
    ->setClientId($configs['client_id'])
    ->setTrxAmount(100000)
    ->setCustomerName('Asep Balon')
    ->setTrxId($trxId)
    ->run();

print_r($invoice); exit;