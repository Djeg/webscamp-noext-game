<?php

namespace App\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Symfony\Form\Type\RecipeType;

class RecipesController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $recipes = $this
            ->getDoctrine()
            ->getRepository('App:Recipe')
            ->findAllAndPaginate($request)
        ;

        return $this->render(':Recipes:index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    /**
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $recipe = $this
            ->getDoctrine()
            ->getRepository('App:Recipe')
            ->find($id)
        ;

        if (null === $recipe) {
            throw new NotFoundHttpException();
        }

        return $this->render(':Recipes:show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(RecipeType::class, null, [
            'action' => $this->generateUrl('app_recipes_create'),
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($form->getData());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Thanks to propose this recipe !');

            return $this->redirectToRoute('app_recipes_index');
        }

        return $this->render(':Recipes:new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
