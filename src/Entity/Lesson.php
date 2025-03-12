<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonRepository::class)]
class Lesson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lessons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cursus $cursus = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'lessons')]
    private Collection $client;

    /**
     * @var Collection<int, CertifCursus>
     */
    #[ORM\OneToMany(targetEntity: CertifCursus::class, mappedBy: 'cursus')]
    private Collection $certifCursuses;

    #[ORM\OneToOne(mappedBy: 'lesson', cascade: ['persist', 'remove'])]
    private ?CertifLesson $certifLesson = null;

    public function __construct()
    {
        $this->client = new ArrayCollection();
        $this->certifCursuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCursus(): ?Cursus
    {
        return $this->cursus;
    }

    public function setCursus(?Cursus $cursus): static
    {
        $this->cursus = $cursus;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
    public function getClient(): Collection
    {
        return $this->client;
    }

    public function addClient(User $client): static
    {
        if (!$this->client->contains($client)) {
            $this->client->add($client);
        }

        return $this;
    }

    public function removeClient(User $client): static
    {
        $this->client->removeElement($client);

        return $this;
    }

    /**
     * @return Collection<int, CertifCursus>
     */
    public function getCertifCursuses(): Collection
    {
        return $this->certifCursuses;
    }

    public function addCertifCursus(CertifCursus $certifCursus): static
    {
        if (!$this->certifCursuses->contains($certifCursus)) {
            $this->certifCursuses->add($certifCursus);
            $certifCursus->setCursus($this);
        }

        return $this;
    }

    public function removeCertifCursus(CertifCursus $certifCursus): static
    {
        if ($this->certifCursuses->removeElement($certifCursus)) {
            // set the owning side to null (unless already changed)
            if ($certifCursus->getCursus() === $this) {
                $certifCursus->setCursus(null);
            }
        }

        return $this;
    }

    public function getCertifLesson(): ?CertifLesson
    {
        return $this->certifLesson;
    }

    public function setCertifLesson(CertifLesson $certifLesson): static
    {
        // set the owning side of the relation if necessary
        if ($certifLesson->getLesson() !== $this) {
            $certifLesson->setLesson($this);
        }

        $this->certifLesson = $certifLesson;

        return $this;
    }
}
