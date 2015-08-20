<?php
use Slince\Application\WebApplication;
use Symfony\Component\HttpFoundation\Request;

$config = include __DIR__ . '/../config/bootstrap.php';

$request = Request::createFromGlobals();
$webApp = new WebApplication($config, $request);
$response = $webApp->run();
$response->send();
