<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index()
    {
        helper('api_helper');

        if (!session()->has('token')) {
            return redirect()->to('/login');
        }

        $token = session()->get('token');

        $profile = callAPI('GET', 'https://take-home-test-api.nutech-integrasi.com/profile', false, $token);

        $balance = callAPI('GET', 'https://take-home-test-api.nutech-integrasi.com/balance', false, $token);

        $services = callAPI('GET', 'https://take-home-test-api.nutech-integrasi.com/services', false, $token);

        $banners = callAPI('GET', 'https://take-home-test-api.nutech-integrasi.com/banner', false, $token);

        return view('home/index', [
            'profile' => $profile['data'] ?? [],
            'balance' => $balance['data'] ?? [],
            'services' => $services['data'] ?? [],
            'banners' => $banners['data'] ?? [],
        ]);
    }
}
