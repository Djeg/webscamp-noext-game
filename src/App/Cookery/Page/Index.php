<?php

namespace App\Cookery\Page;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use App\Symfony\Repository\RecipesList;
use App\Cookery\Recipe\Collection;

class Index
{
    private $recipesList;

    private $templating;

    public function __construct(
        Collection $recipesList,
        EngineInterface $templating
    ) {
        $this->recipesList = $recipesList;
        $this->templating = $templating;
    }

    public function __invoke()
    {
        $recipes = $this->recipesList->all();

        return $this->templating->renderResponse(':Recipes:index.html.twig', [
            'recipes' => $recipes,
        ]);
    }
}
