<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Auth extends Controller
{
    public function __construct()
    {
        helper('api_helper');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function processLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $response = callAPI('POST', 'https://take-home-test-api.nutech-integrasi.com/login', [
            'email' => $email,
            'password' => $password
        ]);

        if (isset($response['status']) && $response['status'] === 0) {
            session()->set('token', $response['data']['token']);
            return redirect()->to('/home')->with('success', 'Login Berhasil!');
        } else {
            return redirect()->back()->with('error', $response['message'] ?? 'Login Gagal!');
        }
    }

    public function register()
    {
        return view('auth/register');
    }

    public function processRegister()
    {
        $first_name = $this->request->getPost('first_name');
        $last_name = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $response = callAPI('POST', 'https://take-home-test-api.nutech-integrasi.com/registration', [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $password
        ]);

        if (isset($response['status']) && $response['status'] === 0) {
            return redirect()->to('/login')->with('success', 'Registrasi Berhasil, Silakan Login!');
        } else {
            return redirect()->back()->with('error', $response['message'] ?? 'Registrasi Gagal!');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logout Berhasil!');
    }
}
