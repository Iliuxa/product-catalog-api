<?php

namespace App\Service;

use App\Exception\Notice;

class DaDataService
{
    private const string url = 'http://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party';

    public function __construct(
        private readonly string $token,
    )
    {
    }

    public function isValidInn(string $inn): bool
    {
        $data = ['query' => $inn];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Token ' . $this->token
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || empty($response)) {
            throw new Notice('Не удалось проверить существование организации');
        }

        $result = json_decode($response, true);
        return !empty($result['suggestions']);
    }
}