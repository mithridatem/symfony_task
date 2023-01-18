<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Util;
use App\Entity\Task;
use App\Repository\UtilRepository;
use App\Repository\TaskRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;

class UtilController extends AbstractController
{
    #[Route('/util/all', name: 'app_util_all', methods: 'GET')]
    public function showAllUtil(UtilRepository $repo, 
    NormalizerInterface $normalizer): Response
    {
        //tableau d'objet utilisateur (récupéré depuis la BDD)
        $data = $repo->findAll();
        return $this->json($data, 200, ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
        'Access-Control-Allow-Methods'=>'GET'],
         ['groups'=>'utils']);
    }

    //fonction qui retourne en json une categorie par son id
    #[Route('/util/id/{value}', name: 'app_util_id', methods: 'GET')]
    public function showUtilById(UtilRepository $repo,
    NormalizerInterface $normalizer, $value): Response
    {
        //stocker dans une variable les enregistrements de la base de données
        $data = $repo->find($value);
        //stocker dans une variable l'objet utilisateur (BDD)
        //test si data est égal à null retourne json erreur
        if($data == null){
            return $this->json(['error'=>'L\'utilisateur n\'existe pas'],200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET']);
        }
        //sinon reourne le json
        else{
            return $this->json($data,200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET'],
            ['groups'=>'utils']);
            //(tableau de donnée, code retour, entête http, groupe pour filtrer)
        }
    }
    //fonction qui retourne en json un utilisateur par son nom
    #[Route('/util/name/{value}', name: 'app_util_name', methods: 'GET')]
    public function showUtilByName(UtilRepository $repo,
    NormalizerInterface $normalizer, $value,): Response
    {
        //stocker dans une variable les enregistrements de la base de données
        $data = $repo->findOneBy(['name'=>$value]);
        //test si data est égal à null retourne json erreur
        if($data == null){
            return $this->json(['error'=>'L\'utilisateur n\'existe pas'],200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET']);
        }
        //sinon reourne le json
        else{
            return $this->json($data,200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET'],
            ['groups'=>'utils']);
            //(tableau de donnée, code retour, entête http, groupe pour filtrer)
        }
    }
    //fonction qui ajoute une nouvel utilisateur depuis un json version deserialize
    #[Route('/util/add', name: 'app_util_add', methods: 'POST')]
    public function addUtil(EntityManagerInterface $manager,
    Request $request,SerializerInterface $serializer
    ): Response
    {
        //récupération du json
        $json = $request->getContent();
        //instancier un nouvel objet Util
        $util = new Util();
        //transformer le json en objet
        $recup = $serializer->deserialize($json, Util::class, 'json');
        //setter la valeur de name (de recup) dans l'attribut name de l'objet util
        $util->setName($recup->getName());
        //setter la valeur de first_name (de recup) dans l'attribut name de l'objet util
        $util->setFirstName($recup->getFirstName());
        //setter la valeur de mail (de recup) dans l'attribut name de l'objet util
        $util->setMail($recup->getMail());
        //setter la valeur de password (de recup) dans l'attribut name de l'objet util
        $util->setPassword(password_hash($recup->getPassword(),PASSWORD_DEFAULT ));
        //stocker dans manager le nouvel objet Util
        $manager->persist($util);
        //insertion en BDD
        $manager->flush();
        //afficher l'objet
        dd($util);
    }
    //fonction qui ajoute une nouvel utilisateur depuis un json version decode
    #[Route('/util/add2', name: 'app_util_add2', methods: 'POST')]
    public function addUtil2(EntityManagerInterface $manager,
    Request $request,SerializerInterface $serializer
    ): Response
    {
        //récupération du json
        $json = $request->getContent();
        //instancier un nouvel objet Util
        $util = new Util();
        //décoder le json
        $recup = $serializer->decode($json , 'json');
        //setter la valeur de name (de recup) dans l'attribut name de l'objet util
        $util->setName($recup['name']);
        //setter la valeur de first_name (de recup) dans l'attribut name de l'objet util
        $util->setFirstName($recup['first_name']);
        //setter la valeur de mail (de recup) dans l'attribut name de l'objet util
        $util->setMail($recup['mail']);
        //setter la valeur de password (de recup) dans l'attribut name de l'objet util
        $util->setPassword(password_hash($recup['password'],PASSWORD_DEFAULT ));
        //stocker dans manager le nouvel objet Util
        $manager->persist($util);
        //insertion en BDD
        $manager->flush();
        //afficher l'objet
        dd($util);
    }
    //fonction qui supprime un utilisateur par son ID
    #[Route('/util/delete/{id}', name: 'app_util_delete', methods: 'DELETE')]
    public function deleteUtil2(EntityManagerInterface $manager,
    Request $request, UtilRepository $repo, TaskRepository $taskRepo, $id
    ): Response
    {
        //récupérer l'utilisateur dans une variable
        $util = $repo->find($id);
        //stocker dans une variable un tableau d'objet task (BDD)
        $tasks = $taskRepo->findBy(['util'=>$util]);
        //si l'utilisateur n'existe pas affiche une erreur
        if($util == null){
            return $this->json(['error'=>'L\'utilisateur n\'existe pas'],200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'DELETE']);
        }
        else if($tasks != null){
            return $this->json(['error'=>'L\'utilisateur est lié à des taches'],200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'GET']);
        }
        //sinon le supprime et affiche un message
        else{
            $manager->remove($util);
            $manager->flush();
            return $this->json(['info'=>'L\'utilisateur '.$id.' à bien été supprimé'],200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'DELETE']);
        }
    }
    //mise à jour d'un utilisateur depuis un fichier JSON avec PATCH
    #[Route('/util/update/{id}', name: 'app_util_update', methods: 'PATCH')]
    public function updateUtil(EntityManagerInterface $manager,
    Request $request, UtilRepository $repo, $id, SerializerInterface $serializer
    ): Response
    {
        //récupérer l'objet utilisateur
        $util = $repo->find($id);
        //test si l'utilisateur existe
        if($util != null){
            //récupération du json
            $json = $request->getContent();
            //décoder le json
            $recup = $serializer->decode($json , 'json');
            //test si les données sont identiques
            if($util->getName()== $recup['name'] AND $util->getFirstName()== $recup['first_name']
            AND $util->getMail()== $recup['mail']){
                return $this->json(['error'=>'Aucune modification à apporter'],200,
                ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
                'Access-Control-Allow-Methods'=>'PATCH']);
            }
            //sinon on met à jour l'utilisateur
            else{
                //setter la valeur de name (de recup) dans l'attribut name de l'objet util
                $util->setName($recup['name']);
                //setter la valeur de first_name (de recup) dans l'attribut first_name de l'objet util
                $util->setFirstName($recup['first_name']);
                //setter la valeur de mail (de recup) dans l'attribut mail de l'objet util
                $util->setMail($recup['mail']);
                //setter la valeur du mot de passe (de recup) dans l'attribut password de l'objet util
                $util->setPassword(password_hash($recup['password'], PASSWORD_DEFAULT));
                //on stocke l'objet dans le manager
                $manager->persist($util);
                //on sauvegarde les modifications dans la base de données.
                $manager->flush();
                return $this->json(['info'=>'L\'utilisateur '.$util->getName().' a été modifié'],200,
                ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
                'Access-Control-Allow-Methods'=>'PATCH']);
            }
        }
        //si l'utilisateur n'existe pas
        else{
            return $this->json(['error'=>'L\'utilisateur n\'existe pas'],200,
            ['Content-Type'=>'application/json','Access-Control-Allow-Origin'=> '*',
            'Access-Control-Allow-Methods'=>'PATCH']);
        }
    }
}
