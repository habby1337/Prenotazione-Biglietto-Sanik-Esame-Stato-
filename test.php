<?php

include "./vendor/phpqrcode/qrlib.php";

QRcode::png('PHP QR Code :)', 'test.png', 'L', 4, 2);

?>