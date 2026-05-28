<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Country;
use App\Entity\Publisher;
use App\Form\CountryType;
use App\Form\PublisherType;
use App\Repository\CountryRepository;
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

#[Route('/admin/country', name: 'app_admin_country')]
class CountryController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TranslatorInterface    $translator,
        private readonly CountryRepository      $countryRepository,
    )
    { }

    #[Route('', name: '_index')]
    public function index(
        PaginatorInterface  $paginator,
        Request             $request
    ): Response
    {
        $pagination = $paginator->paginate(
            $this->countryRepository->getAll(),
            $request->query->getInt('page', 1), /* page number */
            12 /* limit per page */
        );
        $pagination->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('admin/country/index.html.twig', [
            'countries' => $pagination
        ]);
    }

    #[Route('/new', '_new')]
    public function new(Request $request): Response
    {
        return $this->handleForm($request, new Country());
    }

    #[Route('/edit/{id}', '_edit')]
    public function edit(Request $request, Country $country): Response
    {
        return $this->handleForm($request, $country);
    }

    private function handleForm(Request $request, Country $country): Response
    {
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $type = 'success';
            try {
                $this->em->persist($country);
                $this->em->flush();
            } catch (\Throwable $exception) {
                $type = 'danger';
            }
            $this->addFlash($type, $this->translator->trans('alert.country.new.' . $type, [], 'admin'));
            return $this->redirectToRoute('app_admin_country_index');
        }

        return $this->render('/admin/country/form.html.twig', [
            'form' => $form,
            'mode' => $country->getId() == null ? 'new' : 'edit',
            'country' => $country,
        ]);
    }
}
