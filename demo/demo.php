<?php
use Slince\Applicaion\WebApplication;
use Slince\Config\Repository;
use Symfony\Component\HttpFoundation\Request;

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/config/init.php';

$request = Request::createFromGlobals();
$web = new WebApplication($config);
$web->run($request);
