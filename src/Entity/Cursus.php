<?php

namespace App\Entity;

use App\Repository\CursusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CursusRepository::class)]
class Cursus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cursuses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Theme $theme = null;

    #[ORM\Column]
    private ?float $price = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'cursuses')]
    private Collection $clients;

    /**
     * @var Collection<int, Lesson>
     */
    #[ORM\OneToMany(targetEntity: Lesson::class, mappedBy: 'cursus')]
    private Collection $lessons;

    #[ORM\OneToOne(mappedBy: 'cursus', cascade: ['persist', 'remove'])]
    private ?CertifCursus $certifCursus = null;

    /**
     * @var Collection<int, CertifLesson>
     */
    #[ORM\OneToMany(targetEntity: CertifLesson::class, mappedBy: 'cursus')]
    private Collection $certifLessons;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->lessons = new ArrayCollection();
        $this->certifLessons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(User $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
        }

        return $this;
    }

    public function removeClient(User $client): static
    {
        $this->clients->removeElement($client);

        return $this;
    }

    /**
     * @return Collection<int, Lesson>
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    public function addLesson(Lesson $lesson): static
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
            $lesson->setCursus($this);
        }

        return $this;
    }

    public function removeLesson(Lesson $lesson): static
    {
        if ($this->lessons->removeElement($lesson)) {
            // set the owning side to null (unless already changed)
            if ($lesson->getCursus() === $this) {
                $lesson->setCursus(null);
            }
        }

        return $this;
    }

    public function getCertifCursus(): ?CertifCursus
    {
        return $this->certifCursus;
    }

    public function setCertifCursus(CertifCursus $certifCursus): static
    {
        // set the owning side of the relation if necessary
        if ($certifCursus->getCursus() !== $this) {
            $certifCursus->setCursus($this);
        }

        $this->certifCursus = $certifCursus;

        return $this;
    }

    /**
     * @return Collection<int, CertifLesson>
     */
    public function getCertifLessons(): Collection
    {
        return $this->certifLessons;
    }

    public function addCertifLesson(CertifLesson $certifLesson): static
    {
        if (!$this->certifLessons->contains($certifLesson)) {
            $this->certifLessons->add($certifLesson);
            $certifLesson->setCursus($this);
        }

        return $this;
    }

    public function removeCertifLesson(CertifLesson $certifLesson): static
    {
        if ($this->certifLessons->removeElement($certifLesson)) {
            // set the owning side to null (unless already changed)
            if ($certifLesson->getCursus() === $this) {
                $certifLesson->setCursus(null);
            }
        }

        return $this;
    }
}
