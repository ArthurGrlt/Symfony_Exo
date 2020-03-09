<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @UniqueEntity("email")
 */
class Users
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     * min = 1,
     * minMessage = "Veuillez entrer un nom")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     * min = 1,
     * minMessage = "Veuillez entrer un prénom")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email(
     * message = "Cette adresse email est déjà utilisée"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $inscription;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tasks", mappedBy="userId")
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->inscription = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getInscription(): ?\DateTimeInterface
    {
        return $this->inscription;
    }

    public function setInscription(\DateTimeInterface $inscription): self
    {
        $this->inscription = $inscription;

        return $this;
    }

    /**
     * @return Collection|Tasks[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Tasks $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setUserId($this);
        }

        return $this;
    }

    public function removeTask(Tasks $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getUserId() === $this) {
                $task->setUserId(null);
            }
        }

        return $this;
    }
}
