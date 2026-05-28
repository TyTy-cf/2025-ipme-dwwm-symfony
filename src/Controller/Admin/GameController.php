<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/game', name: 'app_admin_game')]
class GameController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface    $translator,
        private LoggerInterface        $logger,
    )
    {
    }

    #[Route('', name: '_index')]
    public function index(
        GameRepository     $gameRepository,
        PaginatorInterface $paginator,
        Request            $request
    ): Response
    {
        $pagination = $paginator->paginate(
            $gameRepository->getAll(),
            $request->query->getInt('page', 1), /* page number */
            10 /* limit per page */
        );
        $pagination->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('admin/game/index.html.twig', [
            'games' => $pagination
        ]);
    }

    #[Route('/new', '_new')]
    public function new(Request $request): Response
    {
        return $this->handleForm($request, new Game());
    }

    #[Route('/edit/{id}', '_edit')]
    public function edit(Request $request, Game $game): Response
    {
        return $this->handleForm($request, $game);
    }

    private function handleForm(Request $request, Game $game): Response
    {
        $addPublisher = $request->get('addPublisher', false);
        $form = $this->createForm(GameType::class, $game, [
            'add_publisher' => $addPublisher && $game->getId() === null,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $type = 'success';
            try {
                if ($game->getPublisher()->getId() === null) {
                    $this->em->persist($game->getPublisher());
                }
                $this->em->persist($game);
                $this->em->flush();
            } catch (\Throwable $exception) {
                $type = 'danger';
                dump($exception->getMessage());
                $this->logger->error($exception->getMessage());
            }
            $this->addFlash($type, $this->translator->trans('alert.game.new.' . $type, [], 'admin'));
            return $this->redirectToRoute('app_admin_game_index');
        }

        return $this->render('/admin/game/form.html.twig', [
            'form' => $form,
            'mode' => $game->getId() == null ? 'new' : 'edit',
            'game' => $game,
            'addPublisher' => !$addPublisher,
        ]);
    }
}
