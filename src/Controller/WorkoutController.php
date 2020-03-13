<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\Workout;
use App\Form\WorkoutType;
use App\Repository\QuizRepository;
use App\Repository\WorkoutRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/workout")
 */
class WorkoutController extends AbstractController
{
    /**
     * @Route("/", name="workout_index", methods={"GET"})
     */
    public function index(WorkoutRepository $workoutRepository): Response
    {
        return $this->render('workout/index.html.twig', [
            'workouts' => $workoutRepository->findAll(),
        ]);
    }

    /**
     * @Route("/active", name="workout_active", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $em, UserInterface $user, WorkoutRepository $wr, QuestionRepository $qer, QuizRepository $qzr): Response
    {
        $quizId = $request->query->has('quiz');
        $questionNb = $request->query->has('currentQuestion');
        $this->em = $em;

        $userId = $user->getId();
        
        $workout = $wr->findOneByUserId($userId);
        if (!$workout) {
            $workout = new Workout();
            $quiz = $qzr->findOneById($quizId);
            $workout->setQuiz($quiz);
            $workout->setAssociatedUser($userId);
        }

        $workout->setCurrentQuestionNumber($question+1);

        $this->em->persist($workout);
        $this->em->flush();

        $questionNb = $workout->getCurrentQuestionNumber();
        $categories = $workout->getQuiz()->getCategories();

        $question = $qer->findOneRandomByCategories($categories);

        if ($questionNb < $workout->getQuiz()->getQuestionsNb()) {
            $userRoles = $user->getRoles();
            $roleType = 'USER';
    
            foreach ($userRoles as $role) {
                if ($role == 'ROLE_ADMIN' || $role == 'ROLE_SUPERADMIN') $roleType = 'ADMIN';
            }
            
            switch ($roleType) {
                case 'USER' :
                    return $this->render('workout/question.html.twig', [
                        'questionText' => $question->getText(),
                    ]);
                break;
    
                case 'ADMIN' :
                    return $this->render('workout/question.html.twig', [
                        'questionText' => $question->getText(),
                    ]);
                break;
            }
        } else {
            return $this->render('index.html.twig');
        }
 


        // Workout first create
        $workout->setAssociatedUser($userId);
        $workout->setQuiz($quiz);
        

        $em->persist($workout);
        $em->flush();

        return $this->redirectToRoute('workout_index');
    }

    /**
     * @Route("/{id}", name="workout_show", methods={"GET"})
     */
    public function show(Workout $workout): Response
    {
        return $this->render('workout/show.html.twig', [
            'workout' => $workout,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="workout_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Workout $workout): Response
    {
        $form = $this->createForm(WorkoutType::class, $workout);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('workout_index');
        }

        return $this->render('workout/edit.html.twig', [
            'workout' => $workout,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="workout_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Workout $workout): Response
    {
        if ($this->isCsrfTokenValid('delete'.$workout->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($workout);
            $entityManager->flush();
        }

        return $this->redirectToRoute('workout_index');
    }

    /**
     * @Route("/{id}/summary", name="workout_summary", methods={"GET","POST"})
     */
    public function summary(Quiz $quiz)
    {
        return $this->render('workout/summary.html.twig', [
            'quiz' => $quiz,
        ]);
    }
}
