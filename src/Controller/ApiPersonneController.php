<?php

namespace App\Controller;

use App\Repository\PersonneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiPersonneController extends AbstractController
{
    #[Route('/api/personne', name: 'app_api_personne')]
    public function index(PersonneRepository $rep, NormalizerInterface $normalizer)
    {
        $personnes = $rep->findAll();
        $normalized = $normalizer->normalize($personnes, null, [
            'groups' => 'personne:read'
        ]);
        $json = json_encode($normalized);

        $reponse = new Response($json, 200, ['Content-Type' => 'application/json']);
        return $reponse;

        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/ApiPersonneController.php',
        // ]);
    }
}
