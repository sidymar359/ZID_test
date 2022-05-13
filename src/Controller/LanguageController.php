<?php

namespace App\Controller;

use App\Entity\Language;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
//use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api", name="api_")
 */
class LanguageController extends AbstractController
{
    /**
     * @Route("/language", name="language_index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $laguages = $doctrine->getManager()
            ->getRepository(Language::class)
            ->findAll();

        $data = [];

        foreach ($laguages as $language)
        {
            $data[] = [
                'id' => $language->getId(),
                'code' => $language->getCode(),
                'displayName' => $language->getDisplayName(),
            ];
        }

        return $this->json($data);
    }

    /**
     * @Route("/language", name="language_new", methods={"POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
 
        $language = new Language();
        $language->setCode($request->request->get('code'));
        $language->setDisplayName($request->request->get('displayName'));
 
        $entityManager->persist($language);
        $entityManager->flush();
 
        return $this->json('Created new languages successfully with id ' . $language->getId());
    }
    /**
     * @Route("/language/{id}", name="language_show", methods={"GET"})
     */
    public function show(int $id, ManagerRegistry $doctrine): Response
    {
        $language = $doctrine->getManager()
            ->getRepository(Language::class)
            ->find($id);
 
        if (!$language) {
 
            return $this->json('No language found for id' . $id, 404);
        }
 
        $data =  [
            'id' => $language->getId(),
            'name' => $language->getCode(),
            'description' => $language->getDisplayName(),
        ];
         
        return $this->json($data);
    }
    /**
     * @Route("/languag/{id}", name="project_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $language = $entityManager->getRepository(Language::class)->find($id);
 
        if (!$language) {
            return $this->json('No lan$language found for id' . $id, 404);
        }
 
        $language->setName($request->request->get('name'));
        $language->setDescription($request->request->get('description'));
        $entityManager->flush();
 
        $data =  [
            'id' => $language->getId(),
            'code' => $language->getCode(),
            'displayName' => $language->getDisplayName(),
        ];
         
        return $this->json($data);
    }
    /**
     * @Route("/language/{id}", name="project_delete", methods={"DELETE"})
     */
    public function delete(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $language = $entityManager->getRepository(Language::class)->find($id);
 
        if (!$language) {
            return $this->json('No language found for id' . $id, 404);
        }
 
        $entityManager->remove($language);
        $entityManager->flush();
 
        return $this->json('Deleted a language successfully with id ' . $id);
    }
}   
