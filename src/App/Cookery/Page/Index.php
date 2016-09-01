<?php

namespace App\Cookery\Page;

use App\Cookery\Recipe\Collection;
use App\Cookery\Output;

class Index
{
    private $recipesList;

    private $output;

    public function __construct(
        Collection $recipesList,
        Output $output
    ) {
        $this->recipesList = $recipesList;
        $this->output = $output;
    }

    public function __invoke()
    {
        $recipes = $this->recipesList->all();

        return $this->output->out(':Recipes:index.html.twig', [
            'recipes' => $recipes,
        ]);
    }
}
