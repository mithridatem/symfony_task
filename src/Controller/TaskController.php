<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Entity\Util;
use App\Entity\Category;
use App\Repository\UtilRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;

class TaskController extends AbstractController
{
    //fonction qui retourne en json toutes les taches
    #[Route('/task/all', name: 'app_task_all', methods: 'GET')]
    public function showAllUtil(TaskRepository $repo, 
    NormalizerInterface $normalizer): Response
    {
        //tableau d'objet utilisateur (récupéré depuis la BDD)
        $data = $repo->findAll();
        return $this->json($data, 200, ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
        'Access-Control-Allow-Methods'=>'GET'],
         ['groups'=>'tasks']);
    }

    //fonction qui retourne en json une tache par son id
    #[Route('/task/id/{value}', name: 'app_task_id', methods: 'GET')]
    public function showTaskById(TaskRepository $repo,
    NormalizerInterface $normalizer, $value): Response
    {
        //stocker dans une variable les enregistrements de la base de données
        $data = $repo->find($value);
        //test si data est égal à null retourne json erreur
        if($data == null){
            return $this->json(['error'=>'La tache n\'existe pas'],200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET']);
        }
        //sinon reourne le json
        else{
            return $this->json($data,200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET'],
            ['groups'=>'tasks']);
            //(tableau de donnée, code retour, entête http, groupe pour filtrer)
        }
    }

    //fonction qui retourne en json une tache par son nom
    #[Route('/task/name/{value}', name: 'app_task_name', methods: 'GET')]
    public function showTaskByName(TaskRepository $repo,
    NormalizerInterface $normalizer, $value,): Response
    {
        //stocker dans une variable les enregistrements de la base de données
        $data = $repo->findOneBy(['name'=>$value]);
        //test si data est égal à null retourne json erreur
        if($data == null){
            return $this->json(['error'=>'La tache n\'existe pas'],200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET']);
        }
        //sinon reourne le json
        else{
            return $this->json($data,200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET'],
            ['groups'=>'tasks']);
            //(tableau de donnée, code retour, entête http, groupe pour filtrer)
        }
    }

    //fonction qui retourne en json les taches qui ont le même nom de l'auteur
    #[Route('/task/utilname/{value}', name: 'app_task_utilname', methods: 'GET')]
    public function showTaskByUtilName(TaskRepository $taskRepo,
    UtilRepository $utilRepo, NormalizerInterface $normalizer, $value): Response
    {
        //stocker dans une variable l'objet utilisateur (BDD)
        $user = $utilRepo->findOneBy(['name'=>$value]);
        //stocker dans une variable un tableau d'objet task (BDD)
        $tasks = $taskRepo->findBy(['util'=>$user]);
        //test si l'utilisateur n'existe pas
        if($user == null){
            return $this->json(['error'=>'L\'utilisateur '.$value.' n\'existe pas'],200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET']);
        }
        //test si aucune tache à été trouvé
        else if($tasks == null){
            return $this->json(['error'=>'Aucune tache n\'a été trouvé'],200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET']);
        }
        //sinon reourne le json de toutes les taches qui correspondent (qui sont ratachées à l'utilisateur)
        else{
            return $this->json($tasks,200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET'],
            ['groups'=>'tasks']);
            //(tableau de donnée, code retour, entête http, groupe pour filtrer)
        }
    }

    //fonction qui retourne en json les taches qui ont le même nom de catégorie
    #[Route('/task/catName/{value}', name: 'app_task_catname', methods: 'GET')]
    public function showTaskByCatName(TaskRepository $taskRepo, CategoryRepository $catRepo,
    NormalizerInterface $normalizer, $value): Response
    {   
        //stocker dans une variable objet category
        $cat = $catRepo->findOneBy(['name'=>$value]);
        //stocker dans une variable un tableau d'objet task (BDD)
        $tasks = $taskRepo->findByNameCat($value);
        //test si la catégorie n'existe pas
        if($cat == null){
            return $this->json(['error'=>'La catégorie '.$value.' n\'existe pas'],200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET']);
        }
        //test si aucune tache à été trouvé
        else if($tasks == null){
            return $this->json(['error'=>'Aucune tache n\'a été trouvé'],200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET']);
        }
        //sinon reourne le json de toutes les taches qui correspondent (qui sont ratachées à l'utilisateur)
        else{
            return $this->json($tasks,200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET'],
            ['groups'=>'tasks']);
            //(tableau de donnée, code retour, entête http, groupe pour filtrer)
        }
    }

}
