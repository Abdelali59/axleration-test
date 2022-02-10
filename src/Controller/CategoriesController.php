<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Security('is_granted("ROLE_ADMIN")')]
class CategoriesController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @param \App\Repository\CategoriesRepository $categoriesRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/categories', name: 'app_categories')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('categories/index.html.twig', [
            'page_title' => 'Ajouter une catégorie',
            'categories' => $categoriesRepository->findAll()
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/categories/nouveau', name: 'app_add_category')]
    public function add_category(Request $request): Response
    {
        $cat = new Categories();
        $form = $this->createForm(CategoriesType::class, $cat);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($cat);
            $this->em->flush();

            $this->addFlash('success', 'La catégorie <strong>' . $cat->getName() . '</strong> a été ajoutée avec succès !');

            return $this->redirectToRoute('app_categories');
        }
        return $this->render('crud/add.html.twig', [
            'name' => 'une catégorie',
            'form' => $form->createView()
        ]);
    }

    /**
     * @param \App\Entity\Categories $cat
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/categories/modifier/{id}', name: 'app_edit_category', requirements: ["id" => "\d+"])]
    public function edit_category(Categories $cat, Request $request): Response
    {
        $form = $this->createForm(CategoriesType::class, $cat);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($cat);
            $this->em->flush();

            $this->addFlash('success', 'La catégorie <strong>' . $cat->getName() . '</strong> a été éditée avec succès !');

            return $this->redirectToRoute('app_categories');
        }
        return $this->render('crud/edit.html.twig', [
            'name' => 'une catégorie',
            'form' => $form->createView()
        ]);
    }

    /**
     * @param \App\Entity\Categories $cat
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/categories/supprimer/{id}', name: 'app_delete_category', requirements: ["id" => "\d+"], methods: 'DELETE')]
    #[ParamConverter('cat', options: ['id' => 'id'])]
    public function delete_category(Categories $cat, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cat->getId(), $request->get('_token'))) {
            $this->em->remove($cat);
            $this->em->flush();
            $this->addFlash('success', 'La catégorie <strong>' . $cat->getName() . '</strong> a bien été supprimée');

        }
        return $this->redirectToRoute('app_categories');
    }
}
