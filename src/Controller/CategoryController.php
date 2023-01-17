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
    //fonction qui retourne en json toutes les categories
    #[Route('/category/all', name: 'app_category_all', methods: 'GET')]
    public function showAllCategory(CategoryRepository $repo,
    NormalizerInterface $normalizer): Response
    {
        //stocker dans une variable les enregistrements de la base de données
        $data = $repo->findAll();
        return $this->json($data,200,
        ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
        'Access-Control-Allow-Methods'=>'GET'],
         ['groups'=>'cat']);
        //(tableau de donnée, code retour, entête http, groupe pour filtrer)
    }
    //fonction qui retourne en json une categorie par son id
    #[Route('/category/id/{value}', name: 'app_category_id', methods: 'GET')]
    public function showCategoryById(CategoryRepository $repo,
    NormalizerInterface $normalizer, $value): Response
    {
        //stocker dans une variable les enregistrements de la base de données
        $data = $repo->find($value);
        //test si data est égal à null retourne json erreur
        if($data == null){
            return $this->json(['error'=>'La categorie n\'existe pas'],200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET']);
        }
        //sinon reourne le json
        else{
            return $this->json($data,200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET'],
            ['groups'=>'cat']);
            //(tableau de donnée, code retour, entête http, groupe pour filtrer)
        }
    }

    //fonction qui retourne en json une categorie par son nom
    #[Route('/category/name/{value}', name: 'app_category_name', methods: 'GET')]
    public function showCategoryByName(CategoryRepository $repo,
    NormalizerInterface $normalizer, $value,): Response
    {
        //stocker dans une variable les enregistrements de la base de données
        $data = $repo->findOneBy(['name'=>$value]);
        //test si data est égal à null retourne json erreur
        if($data == null){
            return $this->json(['error'=>'La categorie n\'existe pas'],200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET']);
        }
        //sinon reourne le json
        else{
            return $this->json($data,200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET'],
            ['groups'=>'cat']);
            //(tableau de donnée, code retour, entête http, groupe pour filtrer)
        }
    }

    //fonction qui ajoute une nouvelle catégorie depuis un json
    #[Route('/category/add', name: 'app_category_add', methods: 'POST')]
    public function addCategory(CategoryRepository $repo,
    EntityManagerInterface $manager, Request $request,
    SerializerInterface $serializer
    ): Response
    {
        //récupération du json
        $json = $request->getContent();
        //instancier un nouvel objet catégorie
        $cat = new Category();
        //transformer le json en objet
        $recup = $serializer->deserialize($json, Category::class, 'json');
        //setter la valeur de name (de recup) dans l'attribut name de l'objet cat
        $cat->setName($recup->getName());
        //stocker dans manager le nouvel objet category
        $manager->persist($cat);
        //insertion en BDD
        $manager->flush();
        //afficher l'objet
        dd($cat);
    }

}
