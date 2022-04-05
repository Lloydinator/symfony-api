<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class TestController 
{
    public function helloWorld(): Response
    {
        return new Response(
            '<html><body>Hello world!</body></html>'
        );
    }
}