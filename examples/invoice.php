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

$invoice = (new Invoice($request))
    ->setClientId($config['client_id'])
    ->setTrxAmount(100000)
    ->setCustomerName('Asep Balon')
    ->setCustomerEmail('asep.balon@gmail.com')
    ->setCustomerPhone('08123123123')
    ->setVirtualAccount('12300000112345678') // 12300000112345678 = '988' + '24975' + transactions.id
    ->setTrxId('12345678')
    ->setDatetimeExpired('now')
    ->setDescription('Payment of transaction ABC')
    ->setType('createBilling') // Penting! jenis transaksi
    ->run();

print_r($invoice); exit;

/*
|--------------------------------------------------------------------------
| Contoh inquiry status invoice
|--------------------------------------------------------------------------
*/

$invoice = (new Invoice($request))
    ->setClientId($config['client_id'])
    ->setTrxAmount(100000)
    ->setCustomerName('Asep Balon')
    ->setCustomerEmail('asep.balon@gmail.com')
    ->setCustomerPhone('08123123123')
    ->setVirtualAccount('12300000112345678') // 12300000112345678 = '988' + '24975' + transactions.id
    ->setTrxId('12345678')
    ->setDatetimeExpired('now')
    ->setDescription('Payment of transaction ABC')
    ->setType('inquiryBilling') // Penting! jenis transaksi
    ->run();

print_r($invoice); exit;

/*
|--------------------------------------------------------------------------
| Contoh update invoice
|--------------------------------------------------------------------------
*/

$invoice = (new Invoice($request))
    ->setClientId($config['client_id'])
    ->setTrxAmount(100000)
    ->setCustomerName('Asep Balon')
    ->setCustomerEmail('asep.balon@gmail.com')
    ->setCustomerPhone('08123123123')
    ->setVirtualAccount('12300000112345678') // 12300000112345678 = '988' + '24975' + transactions.id
    ->setTrxId('12345678')
    ->setDatetimeExpired('now')
    ->setDescription('Payment of transaction ABC')
    ->setType('updateBilling')
    ->run();

print_r($invoice); exit;