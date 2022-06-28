<?php

require __DIR__ . '/../vendor/autoload.php';

use Esyede\BNI\Invoices\Invoice;
use Esyede\BNI\Http\Request;

$config = require __DIR__ . '../config.php';

$request = new Request($config);

/*
|--------------------------------------------------------------------------
| Contoh create invoice
|--------------------------------------------------------------------------
*/

$invoice = new Invoice($request);
$invoice->setClientId($config['client_id'])
    ->setTrxId('1230000001')
    ->setTrxAmount(100000)
    ->setBillingType('fixed_payment')
    ->setCustomerName('Asep Balon')
    ->setCustomerEmail('asep.balon@gmail.com')
    ->setCustomerPhone('08123123123')
    ->setVirtualAccount('8001000000000001')
    ->setDatetimeExpired('2022-06-29 16:00:00')
    ->setDescription('Payment of transaction ABC')
    ->setType('createbilling')
    ->run();

print_r($invoice); exit;

/*
|--------------------------------------------------------------------------
| Contoh inquiry status invoice
|--------------------------------------------------------------------------
*/

$invoice = new Invoice($request);
$invoice->setClientId($config['client_id'])
    ->setTrxId('1230000001')
    ->setTrxAmount(100000)
    ->setBillingType('fixed_payment')
    ->setCustomerName('Asep Balon')
    ->setCustomerEmail('asep.balon@gmail.com')
    ->setCustomerPhone('08123123123')
    ->setVirtualAccount('8001000000000001')
    ->setDatetimeExpired('2022-06-29 16:00:00')
    ->setDescription('Payment of transaction ABC')
    ->setType('inquirybilling')
    ->run();

print_r($invoice); exit;

/*
|--------------------------------------------------------------------------
| Contoh update invoice
|--------------------------------------------------------------------------
*/

$invoice = new Invoice($request);
$invoice->setClientId($config['client_id'])
    ->setTrxId('1230000001')
    ->setTrxAmount(100000)
    ->setBillingType('fixed_payment')
    ->setCustomerName('Asep Balon')
    ->setCustomerEmail('asep.balon@gmail.com')
    ->setCustomerPhone('08123123123')
    ->setVirtualAccount('8001000000000001')
    ->setDatetimeExpired('2022-06-29 16:00:00')
    ->setDescription('Payment of transaction ABC')
    ->setType('updatebilling')
    ->run();

print_r($invoice); exit;