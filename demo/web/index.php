<?php
include __DIR__ . '/../config/bootstrap.php';
$response = $webApp->run();
$response->send();