<?php

namespace App\Cookery\Page;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use App\Symfony\Repository\RecipesList;

class Index
{
    private $recipesList;

    private $templating;

    public function __construct(
        RecipesList $recipesList,
        EngineInterface $templating
    ) {
        $this->recipesList = $recipesList;
        $this->templating = $templating;
    }

    public function __invoke()
    {
        $recipes = $this->recipesList->findAll();

        return $this->templating->renderResponse(':Recipes:index.html.twig', [
            'recipes' => $recipes,
        ]);
    }
}
