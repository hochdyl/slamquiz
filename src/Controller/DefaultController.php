<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Repository\QuizRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/",name="default")
     */
    public function index(QuizRepository $qr)
    {
        $hasAccess = $this->isGranted('ROLE_USER');

        if ( $hasAccess ) {
            $quiz = $qr->findAll();

            return $this->render('default/index.html.twig', [
                'access' => true,
                'quiz' => $quiz,
            ]);
        } else{
            return $this->render('default/index.html.twig', [
                'access' => false,
            ]);
        }
    }
}