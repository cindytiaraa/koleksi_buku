<?php
require 'vendor/autoload.php';

if (class_exists('Picqer\Barcode\BarcodeGeneratorPNG')) {
    echo "Class Picqer\Barcode\BarcodeGeneratorPNG exists\n";
} else {
    echo "Class Picqer\Barcode\BarcodeGeneratorPNG NOT found\n";
}
