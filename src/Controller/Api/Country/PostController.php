<?php

declare(strict_types=1);

namespace App\Controller\Api\Country;

use App\Entity\Country;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PostController extends AbstractController
{

    /**
     * @throws ExceptionInterface
     */
    public function index(
        Request                $request,
        EntityManagerInterface $em,
        SerializerInterface    $serializer
    ): Response
    {
        $content = $request->getContent(); // Get body request => JSON inside HTTP query
        $data = json_decode($content, true); // deserialize json into array

        // instanciate the object properly with data inside array
        $country = (new Country())
            ->setName($data['name'])
            ->setCode($data['code'])
            ->setNationality($data['nationality'])
            ->setUrlFlag('https://flagcdn.com/32x24/' . $data['code'] . '.png')
            ->setSlug(strtolower($data['name']));

        // save object in database
        $em->persist($country);
        $em->flush();

        // serialize object based on the groups defined in entity
        $json = $serializer->serialize(
            $country, // Entity to serialize
            'json', // Output format
            ['groups' => ['country:item', 'country:collection']] // parameters ? Groups ?
        );

        // return the object serialized
        return new Response($json);
    }

}
