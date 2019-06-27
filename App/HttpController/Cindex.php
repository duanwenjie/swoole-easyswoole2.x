<?php
/**
 * Description
 * User: duanwenjie
 * Date: 2019/6/27
 * Time: 9:27 AM
 */

namespace App\HttpController;
use App\ViewController;
class Cindex extends ViewController
{
    function index()
    {
        // TODO: Implement index() method.
        $this->fetch('Cindex/index');
    }

}