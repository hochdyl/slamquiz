<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkoutRepository")
 * @ORM\Table(name="tbl_workout")
 */
class Workout
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $started_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ended_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $current_question_number;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $last_question_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $completed;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $score;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Quiz", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="workout", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $associated_user;

    public function __construct()
    {
        $this->setStartedAt(new \DateTime());
        $this->setCurrentQuestionNumber(0);
        $this->setCompleted(false);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->started_at;
    }

    public function setStartedAt(\DateTimeInterface $started_at): self
    {
        $this->started_at = $started_at;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeInterface
    {
        return $this->ended_at;
    }

    public function setEndedAt(?\DateTimeInterface $ended_at): self
    {
        $this->ended_at = $ended_at;

        return $this;
    }

    public function getCurrentQuestionNumber(): ?int
    {
        return $this->current_question_number;
    }

    public function setCurrentQuestionNumber(?int $current_question_number): self
    {
        $this->current_question_number = $current_question_number;

        return $this;
    }

    public function getLastQuestionId(): ?int
    {
        return $this->last_question_id;
    }

    public function setLastQuestionId(?int $last_question_id): self
    {
        $this->last_question_id = $last_question_id;

        return $this;
    }

    public function getCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(?float $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getQuiz(): ?quiz
    {
        return $this->quiz;
    }

    public function setQuiz(quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getAssociatedUser(): ?User
    {
        return $this->associated_user;
    }

    public function setAssociatedUser(User $associated_user): self
    {
        $this->associated_user = $associated_user;

        return $this;
    }
}
