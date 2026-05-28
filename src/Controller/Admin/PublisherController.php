<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\GameRepository;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/publisher', name: 'app_admin_publisher')]
class PublisherController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TranslatorInterface    $translator,
        private readonly GameRepository         $gameRepository,
        private readonly LoggerInterface        $logger,
    )
    {
    }

    #[Route('', name: '_index')]
    public function index(
        PublisherRepository $publisherRepository,
        PaginatorInterface  $paginator,
        Request             $request
    ): Response
    {
        $pagination = $paginator->paginate(
            $publisherRepository->getAll(),
            $request->query->getInt('page', 1), /* page number */
            12 /* limit per page */
        );
        $pagination->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('admin/publisher/index.html.twig', [
            'publishers' => $pagination
        ]);
    }

    #[Route('/new', '_new')]
    public function new(Request $request): Response
    {
        return $this->handleForm($request, new Publisher());
    }

    #[Route('/edit/{id}', '_edit')]
    public function edit(Request $request, Publisher $publisher): Response
    {
        return $this->handleForm($request, $publisher);
    }

    private function handleForm(Request $request, Publisher $publisher): Response
    {
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $type = 'success';
            try {
                $newGames = $publisher->getGames();
                // Handle logic if we remove a game from the Collection games of Publisher
                if ($publisher->getId() !== null) {
                    $previousGames = $this->gameRepository->findBy(['publisher' => $publisher]);
                    foreach($previousGames as $previousGame) {
                        if (!$newGames->contains($previousGame)) {
                            $previousGame->setPublisher(null);
                        }
                    }
                }

                foreach($newGames as $game) {
                    $game->setPublisher($publisher);
                }

                $this->em->persist($publisher);
                $this->em->flush();
            } catch (\Throwable $exception) {
                $type = 'danger';
                $this->logger->error($exception->getMessage());
            }
            $this->addFlash($type, $this->translator->trans('alert.game.new.' . $type, [], 'admin'));
            return $this->redirectToRoute('app_admin_publisher_index');
        }

        return $this->render('/admin/publisher/form.html.twig', [
            'form' => $form,
            'mode' => $publisher->getId() == null ? 'new' : 'edit',
            'publisher' => $publisher,
        ]);
    }
}
