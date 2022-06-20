<?php

namespace App\Controller;

use App\Repository\AdresseRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ApiAdresseController extends AbstractController
{
    #[Route('/api/adresse', name: 'app_api_adresse')]
    public function index(SerializerInterface $serializer, AdresseRepository $adresses)
    {
        $adresses = $adresses->findAll();
        $json = $serializer->serialize($adresses, 'json', [
            'groups' => 'adresse:read'
        ]);
        $reponse = new JsonResponse($json, 200, [], true);
        return $reponse;
    }
}
