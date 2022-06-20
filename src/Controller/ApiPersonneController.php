<?php

namespace App\Controller;

use App\Repository\PersonneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiPersonneController extends AbstractController
{
    #[Route('/api/personne', name: 'app_api_personne')]
    public function index(PersonneRepository $rep, SerializerInterface $serializer)
    {
        // VERSION 1
        // $personnes = $rep->findAll();
        // $json = $serializer->serialize($personnes, 'json', [
        //     'groups' => 'personne:read'
        // ]);
        // $reponse = new Response($json, 200, ['content-type' => 'application/json']);
        // return $reponse;

        // VERSION 2 COURTE
        $personnes = $rep->findAll();
        return $this->json($personnes, 200, [], ['groups' => 'personne:read']);

    }
}
