<?php

namespace  App\Tests\Controller;

use App\Dto\DtoInterface;
use PHPUnit\Framework\TestCase;

class BasicTestCase extends TestCase
{
    protected function sendRequest(string $url, string $method, array|DtoInterface $data = []): array
    {
        $ch = curl_init(BASE_URL . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ['status' => $status, 'body' => json_decode($response, true)];
    }
}