<?php
namespace App\Controller;

use Slince\Application\Controller;

class PagesController extends Controller
{
    function index()
    {
        echo 'hello';
    }
    function show()
    {
        echo 'hi show';
        trigger_error(E_USER_ERROR);
    }
}