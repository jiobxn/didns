<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/config.php';
require_once '../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

if (!isset($_GET['data'])) {
    header('HTTP/1.0 400 Bad Request');
    exit('Missing data parameter');
}

$data = $_GET['data'];

try {
    $qrCode = QrCode::create($data)
        ->setSize(300)
        ->setMargin(10);

    $writer = new PngWriter();
    $result = $writer->write($qrCode);

    header('Content-Type: ' . $result->getMimeType());
    echo $result->getString();
} catch (Exception $e) {
    header('HTTP/1.0 500 Internal Server Error');
    echo 'Error: ' . $e->getMessage();
}