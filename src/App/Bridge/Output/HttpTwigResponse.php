<?php

namespace App\Bridge\Output;

use App\Cookery\Output;
use Symfony\Component\HttpFoundation\Response;

class HttpTwigResponse implements Output
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function out($subject, array $parameters = [])
    {
        return new Response($this->twig->render($subject, $parameters));
    }
}
