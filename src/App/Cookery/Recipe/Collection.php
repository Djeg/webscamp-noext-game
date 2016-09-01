<?php

namespace App\Cookery\Recipe;

interface Collection
{
    public function all();

    public function one($identifier);
}
