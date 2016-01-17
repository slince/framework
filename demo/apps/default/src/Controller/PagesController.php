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

    function show($id)
    {
        $link = $this->getContainer()->get('cache')->read('link', function() use ($id){
            return $this->loadModel('Links')->get($id);
        });
        var_dump($link);
        //$this->render('show');
    }
}