<?php
namespace Web\Controller;

use Slince\Application\Controller;

class PagesController extends Controller
{

    function index()
    {
        $this->response->setContent('home page');
        return $this->response;
    }
    function show($id)
    {
        echo print_r($id);exit;
    }
}