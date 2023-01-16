<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;

class CategoryController extends AbstractController
{
    #[Route('/category/all', name: 'app_category_all')]
    public function showAllCategory(CategoryRepository $repo,
    NormalizerInterface $normalizer): Response
    {
        //stocker dans une variable les enregistrements de la base de données
        $data = $repo->findAll();
        return $this->json($data,200,[], ['groups'=>'cat']);
        //(tableau de donnée, code retour, entête http, groupe pour filtrer)
    }
}
