<?php

namespace Esyede\BNI\Invoices;

use Esyede\BNI\Http\Request;
use Esyede\BNI\Helpers\Phone;
use Esyede\BNI\Exceptions\InvalidBNIException;
use DateTime;
use DateTimeZone;

class Invoice
{
    private $request;
    private $payloads = [];
    private $actionTypes = [
        'createbilling',
        'createbillingsms',
        'inquirybilling',
        'updatebilling',
    ];

    private $billingTypes = [
        'open_payment' => 'o',
        'fixed_payment' => 'c',
        'installment' => 'i',
        'minimum_payment' => 'm',
        'open_minimum_payment' => 'n',
        'open_maximum_payment' => 'x',
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->setClientId($this->request->config('client_id'));
    }

    /**
     * Set action type. Eg: 'fixed_payment' (string)
     *
     * @param string $value
     */
    public function setType($value)
    {
        if (! in_array($value, array_values($this->actionTypes))) {
            throw new InvalidBNIException("The 'type' value is invalid.");
        }

        $this->payloads['type'] = $value;
        return $this;
    }

    /**
     * Set client id. Eg: '001' (string)
     *
     * @param string $value
     */
    public function setClientId($value)
    {
        $this->payloads['client_id'] = $value;
        return $this;
    }

    /**
     * Set transaction id. Eg: '1230000001' (string)
     *
     * @param string $value
     */
    public function setTrxId($value)
    {
        $this->payloads['trx_id'] = $value;
        return $this;
    }

    /**
     * Set transaction amount. Eg: 100000 (int)
     *
     * @param innt $value
     */
    public function setTrxAmount($value)
    {
        $this->payloads['trx_amount'] = ((int) $value < 1) ? 0 : (int) $value;
        return $this;
    }

    /**
     * Set billing type. Eg: 'createbilling' (string)
     *
     * @param string $value
     */
    public function setBillingType($value)
    {
        if (! in_array($value, $this->billingTypes)) {
            throw new InvalidBNIException("The 'billing_type' value is invalid.");
        }

        $this->payloads['billing_type'] = $value;
        return $this;
    }

    /**
     * Set customer name. Eg: 'Asep Balon' (string)
     *
     * @param string $value
     */
    public function setCustomerName($value)
    {
        $this->payloads['customer_name'] = $value;
        return $this;
    }

    /**
     * Set customer email. Eg: 'asep.balon@gmail.com' (string)
     *
     * @param string $value
     */
    public function setCustomerEmail($value)
    {
        $this->payloads['customer_email'] = $value;
        return $this;
    }

    /**
     * Set customer phone. Eg: '08123123123' (string)
     *
     * @param string $value
     */
    public function setCustomerPhone($value)
    {
        $this->payloads['customer_phone'] = Phone::toZeroPrefix($value);
        return $this;
    }

    /**
     * Set customer virtual account number. Eg: '8001000000000001' (string)
     *
     * @param string $value
     */
    public function setVirtualAccount($value)
    {
        $this->payloads['virtual_account'] = $value;
        return $this;
    }

    /**
     * Set expired time. Will be converted to ISO8601 by this method.
     * Eg: '2016-03-01T16:00:00+07:00' (string)
     *
     * @param string $value
     * @param string $timezone
     */
    public function setDatetimeExpired($value, $timezone = 'Asia/Jakarta')
    {
        if (! is_string($value) || strlen($value) < 5) {
            throw new InvalidBNIException("The 'datetime_expired' value is invalid.");
        }

        $value = (new DateTime($value, new DateTimeZone($timezone)))->format('c');

        if (! is_string($value) || strlen($value) < 25) {
            throw new InvalidBNIException("The 'datetime_expired' value is invalid.");
        }

        $this->payloads['datetime_expired'] = $value;
        return $this;
    }

    /**
     * Set invoice description. Eg: 'Payment of transaction ABC' (string)
     *
     * @param string $value
     */
    public function setDescription($value)
    {
        $this->payloads['description'] = $value;
        return $this;
    }

    /**
     * Create the invoice.
     *
     * @return string
     */
    public function run()
    {
        return $this->request->post('/', $this->payloads);
    }
}