<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminPublisherController extends AbstractController
{
    #[Route('/admin/publisher', name: 'app_admin_publisher')]
    public function index(PublisherRepository $publisherRepository): Response
    {
        $publishers = $publisherRepository->findAll();
        return $this->render('admin_publisher/index.html.twig', [
            'publishers' => $publishers,
        ]);
    }

    #[Route('/admin/publisher/modify/{id}', name: 'app_admin_publisher_modify')]
    public function modify(PublisherRepository $publisherRepository,
                         Request $request,
                         EntityManagerInterface $entityManager,
                         int $id): Response
    {
        $publisher = $publisherRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($publisher);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_publisher');
        }

        return $this->render('admin_publisher/modify.html.twig', [
            'publisher' => $publisher,
            'form' => $form
        ]);
    }

    #[Route('/admin/publisher/add', name: 'app_admin_publisher_add')]
    public function add(Request $request,
                         EntityManagerInterface $entityManager): Response
    {
        $publisher = new Publisher();

        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($publisher);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_publisher');
        }

        return $this->render('admin_publisher/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/publisher/delete/{id}', name: 'app_admin_publisher_delete')]
    public function delete(PublisherRepository $publisherRepository,
                           Request $request,
                           EntityManagerInterface $entityManager,
                           int $id): Response
    {
        $publisher = $publisherRepository->findOneBy(['id' => $id]);

        if ($publisher)
        {
            $entityManager->remove($publisher);
            $entityManager->flush();
        }


        return $this->redirectToRoute('app_admin_publisher');
    }
}
