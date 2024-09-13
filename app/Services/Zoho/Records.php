<?php

namespace App\Services\Zoho;

use Exception;
use Illuminate\Support\Facades\Http;

class Records
{    
    private string $path;

    public function __construct(string $module)
    {
        $this->path = '/crm/v2/' . $module;
    }

    public function insert(array $data): array 
    {
        $response = Http::zoho()->post($this->path, [
            'data' => [$data,],
        ])->json();

        if ($response['data'][0]['status'] != 'success') {
            throw new Exception($response['data'][0]['message']);
        }

        return $response['data'][0];
    }
}