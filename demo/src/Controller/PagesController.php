<?php
namespace App\Controller;

use Slince\Application\Controller;

class PagesController extends Controller
{
    function index()
    {
        //echo 'hello';
        //$this->render();
//         $this->loadModel('Articles');
echo 123;
    }
    function show()
    {
        echo 'hi show';
        trigger_error(E_USER_ERROR);
    }
}