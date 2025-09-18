<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[IsGranted('ROLE_ADMIN')]
final class AdminCategoryController extends AbstractController
{
    #[Route('/admin/category/nouvelle', name: 'app_admin_category_new', methods: ['GET', 'POST'])]
    public function index(
        Request                $request,
        FileUploaderService    $fileUploaderService,
        EntityManagerInterface $entityManager
    ): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère l'objet FileUpload qui vient d'être envoyé
            // $form->get('image')->getData()
            $filename = $fileUploaderService->uploadFile(
                $form->get('image')->getData(),
                '/category'
            );
            $category->setImage($filename);
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Catégorie créée !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin_category/index.html.twig', [
            'form' => $form,
        ]);
    }
}
