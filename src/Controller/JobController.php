<?php

namespace App\Controller;
use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Job;
use App\Entity\Candidature;

class JobController extends AbstractController
{
    #[Route('/job', name: 'app_job')]
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $job = new Job();
        $job->setType('Architecte');
        $job->setCompany('OffShoreBox');
        $job->setDescription('web development');
        $job->setExpiresAt(new \DateTimeImmutable());
        $job->setEmail('arijtriki@gmail.com');
        $image = new Image();
        $image->setUrl('https://cdn.pixabay.com/photo/2015/10/30/10/03/gold-1013618_960_720.jpg');
        $image->setAlt('Job de rêves');
        $job->setImage($image);
        //Ajout de candidat
        $candidature1= new Candidature();
        $candidature1->setCandidat("Rania");
        $candidature1->setContenu("formation PHP");
        $candidature1->setDatec(new \DateTime());
        $candidature2= new Candidature();
        $candidature2->setCandidat("Jileni");
        $candidature2->setContenu("formation Symfony");
        $candidature2->setDatec(new \DateTime());
        $candidature1->setJob($job);
        $candidature2->setJob($job);
        $entityManager->persist($job);
        $entityManager->persist($candidature1);
        $entityManager->persist($candidature2);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        return $this->render('job/index.html.twig', [
            'id' =>$job->getId(),
        ]);
    }
    /**
* @Route("/job/{id}", name="job_show")
*/
public function show($id)
{
 $job = $this->getDoctrine()
 ->getRepository(Job::class)
 ->find($id);
 $em=$this->getDoctrine()->getManager();
 $listCandidatures=$em->getRepository(Candidature::class)
 ->findBy(['Job'=>$job]);
 if (!$job) {
 throw $this->createNotFoundException(
 'No job found for id '.$id
 );
 }
 return $this->render('job/show.html.twig',[
    'listCandidatures'=>$listCandidatures,
    'job'=>$job
 ]);
 return $this->render('job/show.html.twig', [
    'job' =>$job
 ]);
 }
   /**
* @Route("/", name="home")
*/
public function home(){
    $em=$this->getDoctrine()->getManager();
    $repo= $em->getRepository(Candidature::class);
    $lesCondidatures=$repo->findAll();
    return $this->render('job/home.html.twig',['lesCondidatures'=>$lesCondidatures ]);
}
}
