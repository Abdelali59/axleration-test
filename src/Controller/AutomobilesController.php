<?php

namespace App\Controller;

use App\Entity\Automobiles;
use App\Form\AutomobilesType;
use App\Repository\AutomobilesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Security('is_granted("ROLE_ADMIN")')]
class AutomobilesController extends AbstractController

{

    public function __construct(private EntityManagerInterface $em)
    {

    }

    /**
     * @param \App\Repository\AutomobilesRepository $automobilesRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/autos', name: 'app_show_autos')]
    public function index(AutomobilesRepository $automobilesRepository): Response
    {
        return $this->render('automobiles/index.html.twig', [
            'page_title' => 'Afficher les automobiles',
            'automobiles' => $automobilesRepository->findAutosWithCat()
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/autos/nouveau', name: 'app_add_auto')]
    public function add_auto(Request $request): Response
    {
        $auto = new Automobiles();
        $form = $this->createForm(AutomobilesType::class, $auto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($auto);
            $this->em->flush();
            $this->addFlash('success', 'L\'automobile <strong>' . $auto->getName() . '</strong> a bien été ajoutée dans la catégorie <strong>' . $auto->getCategory()->getName() . '</strong>');
            return $this->redirectToRoute('app_show_autos');
        }

        return $this->render('crud/add.html.twig', [
            'name' => 'une automobile',
            'form' => $form->createView()
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Automobiles $auto
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/autos/modifier/{id}', name: 'app_edit_auto', requirements: ["id" => "\d+"])]
    public function edit_auto(Request $request, Automobiles $auto): Response
    {
        $form = $this->createForm(AutomobilesType::class, $auto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($auto);
            $this->em->flush();

            $this->addFlash('success', 'L\'automobile <strong>' . $auto->getName() . '</strong> de la catégorie <strong>' . $auto->getCategory()->getName() . '</strong> a bien été modifiée');
            return $this->redirectToRoute('app_show_autos');
        }
        return $this->render('crud/edit.html.twig', [
            'form' => $form->createView(),
            'name' => 'une automobile',
        ]);
    }

    /**
     * @param \App\Entity\Automobiles $auto
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/autos/supprimer/{id}', name: 'app_delete_auto', requirements: ["id" => "\d+"], methods: 'DELETE')]
    #[ParamConverter('auto', options: ['id' => 'id'])]
    public function delete_auto(Automobiles $auto, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $auto->getId(), $request->get('_token'))) {
            $this->em->remove($auto);
            $this->em->flush();
            $this->addFlash('success', 'L\'automobile <strong>' . $auto->getName() . '</strong> de la catégorie <strong>' . $auto->getCategory()->getName() . '</strong> a bien été supprimée');

        }
        return $this->redirectToRoute('app_show_autos');
    }
}
