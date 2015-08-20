<?php
use Slince\Application\WebApplication;
use Symfony\Component\HttpFoundation\Request;

$config = include __DIR__ . '/../config/bootstrap.php';

$request = Request::createFromGlobals();
$web = new WebApplication($config);
$response = $web->runWith($request);
$response->send();
