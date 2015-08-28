<?php
namespace App\Controller;

use Slince\Application\Controller;

class PagesController extends Controller
{
    function index()
    {
//         echo 'hello';
        $this->render();
        $articles = $this->loadModel('Articles')->find('all')->first()->toArray();
        print_r($articles);
    }
    function show()
    {
        echo 'hi show';
        trigger_error(E_USER_ERROR);
    }
}