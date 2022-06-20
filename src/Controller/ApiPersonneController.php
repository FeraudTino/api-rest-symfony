<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ApiPersonneController extends AbstractController
{
    #[Route('/api/personne', name: 'app_api_personne', methods: ['GET'])]
    public function index(PersonneRepository $rep)
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

    #[Route('/api/personne', name: 'app_api_personne_add', methods: ['POST'])]
    public function add(EntityManagerInterface $em, SerializerInterface $serializer, Request $request)
    {
        $data = $request->getContent();
        try {
            $personne = $serializer->deserialize($data, Personne::class, 'json');
            $em->persist($personne);
            $em->flush();
            return $this->json($personne, 201, [], ['groups' => 'personne:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    #[Route('/api/personne/{id}', name: 'app_api_personne_find', methods: ['GET'])]
    public function find(PersonneRepository $rep, $id, SerializerInterface $serializer)
    {
        $personne = $rep->find($id);
        if (!$personne) {
            return $this->json([
                'status' => 404,
                'message' => 'Personne not found'
            ], 404);
        }
        return $this->json($personne, 200, [], ['groups' => 'personne:read']);
    }

    #[Route('/api/personne/{id}', name: 'app_api_personne_find', methods: ['GET'])]
    public function findshowadresse(PersonneRepository $rep, $id, SerializerInterface $serializer)
    {
        $personne = $rep->find($id);
        if (!$personne) {
            return $this->json([
                'status' => 404,
                'message' => 'Personne not found'
            ], 404);
        }
        return $this->json($personne->getAdresse(), 200, [], ['groups' => 'personne:read']);
    }

    #[Route('/api/personne/{id}/adresse/{id_adresse}', name: 'app_api_personne_find', methods: ['GET'])]
    public function findshowOneadresse(PersonneRepository $rep, $id, SerializerInterface $serializer, $id_adresse)
    {
        $personne = $rep->find($id);
        if (!$personne) {
            return $this->json([
                'status' => 404,
                'message' => 'Personne not found'
            ], 404);
        }
        $adresses = $personne->getAdresse();
        if (!$adresses) {
            return $this->json([
                'status' => 404,
                'message' => 'Adresse not found'
            ], 404);
        }

        foreach ($adresses as $adresse) {
            ($adresse->getId() == $id_adresse) ? $result = $adresse : null;
        }

        if(!$result) {
            return $this->json([
                'status' => 404,
                'message' => 'Adresse not found'
            ], 404);
        }else{
            return $this->json($result, 200, [], ['groups' => 'personne:read']);
        }
    }

    #[Route('/api/personne/{id}', name: 'app_api_personne_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em, SerializerInterface $serializer, Request $request)
    {
        $personne = $em->getRepository(Personne::class)->find($id);
        if ($personne) {
            $em->remove($personne);
            $em->flush();
            return $this->json([
                'status' => 204,
                'message' => "Personne avec l'identifiant $id supprimée avec succès"
            ]);
        }
        return $this->json([
            'status' => 404,
            'message' => "Aucune correspondance avec l'identifiant $id"
        ]);
    }

    #[Route('/api/personne/{id}', name: 'app_api_personne_put', methods: ['PUT'])]
    public function edit(int $id, EntityManagerInterface $em, SerializerInterface $serializer, Request $request)
    {
        $data = $request->getContent();
        try {
            $personne = $serializer->deserialize($data, Personne::class, 'json');
            if ($id != $personne->getId()) {
                return $this->json([
                    'status' => 400,
                    'message' => "Incohérence : l'identifiant $id ne correspond pas à l'identifiant reçu dans le body"
                ]);
            }
            $p = $em->getRepository(Personne::class)->find($id);
            if (!$p) {
                return $this->json([
                    'status' => 404,
                    'message' => "Aucune correspondance avec l'identifiant $id"
                ]);
            }
            $p = $personne;
            $em->flush();
            return $this->json($p, 202, [], ['groups' => 'personne:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }
}
