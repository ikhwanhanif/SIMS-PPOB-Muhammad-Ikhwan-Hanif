<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Transaction extends BaseController
{
    public function topup()
    {
        helper('api_helper');

        if (!session()->has('token')) {
            return redirect()->to('/login');
        }

        $token = session()->get('token');

        $profile = callAPI('GET', 'https://take-home-test-api.nutech-integrasi.com/profile', false, $token);
        $balance = callAPI('GET', 'https://take-home-test-api.nutech-integrasi.com/balance', false, $token);

        return view('transaction/topup', [
            'profile' => $profile['data'] ?? [],
            'balance' => $balance['data'] ?? [],
        ]);
    }

    public function processTopup()
    {
        helper('api_helper');

        if (!session()->has('token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $input = $this->request->getJSON(true);

        if (!$input || empty($input['amount'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nominal Top Up wajib diisi!'
            ]);
        }

        $amount = (int) $input['amount'];
        $token = session()->get('token');

        $response = callAPI('POST', 'https://take-home-test-api.nutech-integrasi.com/topup', [
            'top_up_amount' => $amount
        ], $token);

        if (isset($response['status']) && $response['status'] == 0) {
            return $this->response->setJSON([
                'success' => true,
                'message' => $response['message'] ?? 'Top Up Berhasil'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $response['message'] ?? 'Top Up Gagal'
            ]);
        }
    }

    public function history()
    {
        helper('api_helper');
    
        if (!session()->has('token')) {
            return redirect()->to('/login');
        }
    
        $token = session()->get('token');
    
        $profile = callAPI('GET', 'https://take-home-test-api.nutech-integrasi.com/profile', false, $token);

        $balance = callAPI('GET', 'https://take-home-test-api.nutech-integrasi.com/balance', false, $token);

        $transactionResponse = callAPI('GET', 'https://take-home-test-api.nutech-integrasi.com/transaction/history?offset=0&limit=100', false, $token);
    
        $transactions = [];
        if (isset($transactionResponse['data']['records']) && is_array($transactionResponse['data']['records'])) {
            $transactions = $transactionResponse['data']['records'];
        }
    
        return view('transaction/history', [
            'profile' => $profile['data'] ?? [],
            'balance' => $balance['data'] ?? [],
            'transactions' => $transactions,
        ]);
    }
    
}
