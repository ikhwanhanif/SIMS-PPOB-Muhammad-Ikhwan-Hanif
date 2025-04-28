<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Purchase extends BaseController
{
    public function index($service_code)
    {
        helper('api_helper');

        if (!session()->has('token')) {
            return redirect()->to('/login');
        }

        $token = session()->get('token');

        $profile = callAPI('GET', 'https://take-home-test-api.nutech-integrasi.com/profile', false, $token);
        $balance = callAPI('GET', 'https://take-home-test-api.nutech-integrasi.com/balance', false, $token);

        $serviceResponse = callAPI('GET', 'https://take-home-test-api.nutech-integrasi.com/services', false, $token);
        $services = $serviceResponse['data'] ?? [];

        $service = null;
        foreach ($services as $s) {
            if ($s['service_code'] == $service_code) {
                $service = $s;
                break;
            }
        }

        if (!$service) {
            return redirect()->to('/');
        }

        return view('purchase/index', [
            'profile' => $profile['data'] ?? [],
            'balance' => $balance['data'] ?? [],
            'service' => $service,
        ]);
    }

    public function process()
    {
        helper('api_helper');

        if (!session()->has('token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $input = $this->request->getJSON(true);

        if (!$input || empty($input['service_code'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Service Code tidak boleh kosong'
            ]);
        }

        $token = session()->get('token');

        $serviceResponse = callAPI('GET', 'https://take-home-test-api.nutech-integrasi.com/services', false, $token);
        $services = $serviceResponse['data'] ?? [];

        $transaction_amount = 0;
        foreach ($services as $s) {
            if ($s['service_code'] == $input['service_code']) {
                $transaction_amount = $s['service_tariff'];
                break;
            }
        }

        if ($transaction_amount <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Service tidak ditemukan atau tarif tidak valid'
            ]);
        }

        $response = callAPI('POST', 'https://take-home-test-api.nutech-integrasi.com/transaction', [
            'service_code' => $input['service_code'],
            'transaction_amount' => (int) $transaction_amount,
        ], $token);

        if (isset($response['status']) && $response['status'] == 0) {
            return $this->response->setJSON([
                'success' => true,
                'message' => $response['message'] ?? 'Pembelian berhasil'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $response['message'] ?? 'Pembelian gagal'
            ]);
        }
    }
}
