<?php

namespace App\Cookery;

interface Output
{
    public function out($subject, array $parameters = []);
}
