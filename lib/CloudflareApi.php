<?php

class CloudflareApi {
    private $apiKey;
    private $email;
    private $baseUrl = 'https://api.cloudflare.com/client/v4';

    public function __construct($apiKey, $email) {
        $this->apiKey = $apiKey;
        $this->email = $email;
    }

    private function request($method, $endpoint, $data = null) {
        $url = $this->baseUrl . $endpoint;
        $headers = [
            'X-Auth-Email: ' . $this->email,
            'X-Auth-Key: ' . $this->apiKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function listDnsRecords($zoneId) {
        return $this->request('GET', "/zones/{$zoneId}/dns_records");
    }

    public function getDnsRecord($zoneId, $recordId) {
        return $this->request('GET', "/zones/{$zoneId}/dns_records/{$recordId}");
    }

    public function addDnsRecord($zoneId, $type, $name, $content, $ttl = 1) {
        $data = [
            'type' => $type,
            'name' => $name,
            'content' => $content,
            'ttl' => $ttl
        ];
        return $this->request('POST', "/zones/{$zoneId}/dns_records", $data);
    }

    public function updateDnsRecord($zoneId, $recordId, $type, $name, $content, $ttl = 1) {
        $data = [
            'type' => $type,
            'name' => $name,
            'content' => $content,
            'ttl' => $ttl
        ];
        return $this->request('PUT', "/zones/{$zoneId}/dns_records/{$recordId}", $data);
    }

    public function deleteDnsRecord($zoneId, $recordId) {
        return $this->request('DELETE', "/zones/{$zoneId}/dns_records/{$recordId}");
    }
}