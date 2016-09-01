<?php

namespace App\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Symfony\Form\Type\RecipeType;
use App\Symfony\Entity\Recipe;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

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
            'user' => $this->getUser(),
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->retrieveAndLoginUserFromRecipe($form->getData(), $request);
            $form->getData()->setCreatedBy($user);

            $this->getDoctrine()->getManager()->persist($form->getData());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Thanks to propose this recipe !');

            return $this->redirectToRoute('app_recipes_index');
        }

        return $this->render(':Recipes:new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Recipe $recipe
     * @param Request $request
     *
     * @return User
     */
    protected function retrieveAndLoginUserFromRecipe(Recipe $recipe, Request $request)
    {
        $wellKnownUser = $this
            ->getDoctrine()
            ->getRepository('App:User')
            ->findUserLike($recipe->getCreatedBy())
        ;

        if (null === $wellKnownUser) {
            $wellKnownUser = $recipe->getCreatedBy();

            $this->getDoctrine()->getManager()->persist($wellKnownUser);
            $this->getDoctrine()->getManager()->flush();
        }

        $token = new UsernamePasswordToken($wellKnownUser, $wellKnownUser->getPassword(), "app", $wellKnownUser->getRoles());
        $this->get('security.token_storage')->setToken($token);

        return $wellKnownUser;
    }
}
