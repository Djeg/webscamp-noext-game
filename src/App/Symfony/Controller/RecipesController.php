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
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RecipesController
{
    private $doctrine;

    private $templating;

    private $formFactory;

    private $router;

    private $tokens;

    public function __construct(
        RegistryInterface $doctrine,
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $router,
        TokenStorageInterface $tokens
    ) {
        $this->doctrine = $doctrine;
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->tokens = $tokens;
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $recipes = $this
            ->doctrine
            ->getRepository('App:Recipe')
            ->findAllAndPaginate($request)
        ;

        return $this->templating->renderResponse(':Recipes:index.html.twig', [
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
            ->doctrine
            ->getRepository('App:Recipe')
            ->find($id)
        ;

        if (null === $recipe) {
            throw new NotFoundHttpException();
        }

        return $this->templating->renderResponse(':Recipes:show.html.twig', [
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
        $form = $this->formFactory->create(RecipeType::class, null, [
            'action' => $this->router->generate('app_recipes_create'),
            'user' => $this->getUser(),
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->retrieveAndLoginUserFromRecipe($form->getData(), $request);
            $form->getData()->setCreatedBy($user);

            $this->doctrine->getManager()->persist($form->getData());
            $this->doctrine->getManager()->flush();

            $request->getSession()->getFlashBag()->add('success', 'Thanks to propose this recipe !');

            return new RedirectResponse($this->router->generate('app_recipes_index'));
        }

        return $this->templating->renderResponse(':Recipes:new.html.twig', [
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
            ->doctrine
            ->getRepository('App:User')
            ->findUserLike($recipe->getCreatedBy())
        ;

        if (null === $wellKnownUser) {
            $wellKnownUser = $recipe->getCreatedBy();

            $this->doctrine->getManager()->persist($wellKnownUser);
            $this->doctrine->getManager()->flush();
        }

        $token = new UsernamePasswordToken($wellKnownUser, $wellKnownUser->getPassword(), "app", $wellKnownUser->getRoles());
        $this->tokens->setToken($token);

        return $wellKnownUser;
    }

    protected function getUser()
    {
        return null === $this->tokens->getToken() ?
            null :
            !is_object($user = $this->tokens->getToken()->getUser()) ? null : $user
        ;
    }
}
