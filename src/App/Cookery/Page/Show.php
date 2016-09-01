<?php

namespace App\Cookery\Page;

use App\Cookery\Recipe\Collection;
use App\Cookery\Output;

class Show
{
    private $recipes;

    private $output;

    public function __construct(Collection $recipes, Output $output)
    {
        $this->recipes = $recipes;
        $this->output = $output;
    }

    public function __invoke($recipeIdentifier)
    {
        $recipe = $this
            ->recipes
            ->one($recipeIdentifier)
        ;

        return $this->output->out(':Recipes:show.html.twig', [
            'recipe' => $recipe,
        ]);
    }
}
