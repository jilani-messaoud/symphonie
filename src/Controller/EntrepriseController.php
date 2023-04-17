<?php

namespace App\Controller;

use App\Entity\Entreprise;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name: 'app_entreprise')]
    public function index(): Response
    {
        $entreprise=new Entreprise();
        $entreprise->setTitre("djosoft");
        $entreprise->setEmail("djo.messaoud@gmail.com");
        $entreprise->setSpecialite("Data science");
        $entreprise->setCreatedAt(new \DateTime());
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($entreprise);
        $entityManager->flush();
        return $this->render('entreprise/index.html.twig', [
            'id' => $entreprise->getId(),
        ]);
    }
        /**
* @Route("/entreprise/{id}", name="entreprise_show")
*/
public function show($id)
{
 $entreprise = $this->getDoctrine()
 ->getRepository(Entreprise::class)
 ->find($id);
 
 if(!$entreprise){
    throw $this->createNotFoundException(
    'No entreprise found for id '.$id
    );}
    return $this->render('entreprise/show.html.twig', [
        'entreprise' =>$entreprise
     ]);
 
 }

}