<?php

require_once '../lib/CloudflareApi.php';

class Dns {
    private $api;
    private $zoneId;

    public function __construct() {
        $this->api = new CloudflareApi(CLOUDFLARE_API_KEY, CLOUDFLARE_EMAIL);
        $this->zoneId = CLOUDFLARE_ZONE_ID;
    }

    public function getAllRecords() {
        $response = $this->api->listDnsRecords($this->zoneId);
        if (!$response['success']) {
            throw new Exception('获取DNS记录失败：' . ($response['errors'][0]['message'] ?? '未知错误'));
        }
        return $response['result'];
    }

    public function getRecord($recordId) {
        $response = $this->api->getDnsRecord($this->zoneId, $recordId);
        if (!$response['success']) {
            throw new Exception('获取DNS记录失败：' . ($response['errors'][0]['message'] ?? '未知错误'));
        }
        return $response['result'];
    }

    public function addRecord($type, $name, $content, $ttl = 1) {
        return $this->api->addDnsRecord($this->zoneId, $type, $name, $content, $ttl);
    }

    public function updateRecord($recordId, $type, $name, $content, $ttl = 1) {
        return $this->api->updateDnsRecord($this->zoneId, $recordId, $type, $name, $content, $ttl);
    }

    public function deleteRecord($recordId) {
        return $this->api->deleteDnsRecord($this->zoneId, $recordId);
    }
}