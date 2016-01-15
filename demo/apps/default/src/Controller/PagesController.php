<?php
namespace DefaultApplication\Controller;

use Slince\Application\Controller;

class PagesController extends Controller
{

    function index()
    {
        $this->response->setContent('home page');
        return $this->response;
    }

    function show($c, $id)
    {
        $link = $this->loadModel('Links')->get($id);
        print_r($link);
    }
}