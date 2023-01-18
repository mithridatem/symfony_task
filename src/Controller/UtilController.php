<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Util;
use App\Repository\UtilRepository;
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
}
