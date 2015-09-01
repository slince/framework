<?php
use Symfony\Component\HttpFoundation\Request;

include __DIR__ . '/../config/bootstrap.php';

$request = Request::createFromGlobals();
$response = $webApp->handle($request)->run();
$response->send();