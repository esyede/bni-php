<?php

namespace Esyede\BNI\Invoices;

use Esyede\BNI\Http\Request;
use Esyede\BNI\Helpers\Crypter;
use Closure;

class Callback
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Closure $handler)
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        if (JSON_ERROR_NONE !== json_last_error() || ! isset($data->client_id)) {
            $this->response(['999', 'Invalid IPN callback data']);
        }

        if ($data->client_id !== $this->request->config('client_id')) {
            $this->response(['999', 'Invalid IPN client']);
        }

        $decrypted = Crypter::decrypt(
            $data['data'],
            $this->request->config('client_id'),
            $this->request->config('client_secret')
        );

        if (is_null($decrypted) || ! is_array($decrypted)) {
            $this->response(['999', 'Unable to decrypt the data. Different timestamp?']);
        }

        $handler($decrypted);

        $this->response(['status' => '000', 'message' => '']);
    }

    private function response($statusCode, $message = null)
    {
        $data = ['status' => $statusCode];

        if ($message) {
            $data['message'] = $message;
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}