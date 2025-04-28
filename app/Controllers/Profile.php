<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Profile extends BaseController
{
    public function index()
    {
        helper('api_helper');

        if (!session()->has('token')) {
            return redirect()->to('/login');
        }

        $token = session()->get('token');

        $profile = callAPI('GET', 'https://take-home-test-api.nutech-integrasi.com/profile', false, $token);

        return view('profile/index', [
            'profile' => $profile['data'] ?? [],
        ]);
    }

    public function update()
    {
        helper('api_helper');

        if (!session()->has('token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $input = $this->request->getJSON(true);
        $token = session()->get('token');

        $response = callAPI('PUT', 'https://take-home-test-api.nutech-integrasi.com/profile/update', [
            'first_name' => $input['first_name'] ?? '',
            'last_name' => $input['last_name'] ?? ''
        ], $token);

        if (isset($response['status']) && $response['status'] == 0) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profil berhasil diperbarui'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $response['message'] ?? 'Update gagal'
            ]);
        }
    }

    public function uploadPhoto()
    {
        if (!session()->has('token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $file = $this->request->getFile('file');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File tidak valid'
            ]);
        }

        $token = session()->get('token');

        $cfile = new \CURLFile($file->getTempName(), $file->getMimeType(), $file->getName());

        $ch = curl_init('https://take-home-test-api.nutech-integrasi.com/profile/image');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'file' => $cfile
        ]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'accept: application/json',
            'Content-Type: multipart/form-data'
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Gagal upload foto: $error"
            ]);
        }

        $data = json_decode($response, true);

        if (isset($data['status']) && $data['status'] == 0) {
            return $this->response->setJSON([
                'success' => true,
                'message' => $data['message'] ?? 'Foto berhasil diubah'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $data['message'] ?? 'Upload foto gagal'
            ]);
        }
    }
}
