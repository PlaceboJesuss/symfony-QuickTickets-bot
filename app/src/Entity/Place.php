<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Place
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(nullable: false)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: false)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Performance>
     */
    #[ORM\OneToMany(targetEntity: Performance::class, mappedBy: 'place', orphanRemoval: true)]
    private Collection $performances;

    /**
     * @var Collection<int, MessengerUser>
     */
    #[ORM\ManyToMany(targetEntity: MessengerUser::class, inversedBy: 'places')]
    #[ORM\JoinTable(
        name: 'place_messenger_user',
        joinColumns: [
            new ORM\JoinColumn(name: 'place_id', referencedColumnName: 'id')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'chat_id', referencedColumnName: 'chat_id'),
            new ORM\JoinColumn(name: 'messenger_id', referencedColumnName: 'messenger_id')
        ]
    )]
    private Collection $messengerUsers;

    public function __construct()
    {
        $this->performances = new ArrayCollection();
        $this->messengerUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Performance>
     */
    public function getPerformances(): Collection
    {
        return $this->performances;
    }

    public function addPerformance(Performance $performance): static
    {
        if (!$this->performances->contains($performance)) {
            $this->performances->add($performance);
            $performance->setPlace($this);
        }

        return $this;
    }

    public function removePerformance(Performance $performance): static
    {
        if ($this->performances->removeElement($performance)) {
            // set the owning side to null (unless already changed)
            if ($performance->getPlace() === $this) {
                $performance->setPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MessengerUser>
     */
    public function getMessengerUsers(): Collection
    {
        return $this->messengerUsers;
    }

    public function addMessengerUser(MessengerUser $messengerUser): static
    {
        if (!$this->messengerUsers->contains($messengerUser)) {
            $this->messengerUsers->add($messengerUser);
        }

        return $this;
    }

    public function removeMessengerUser(MessengerUser $messengerUser): static
    {
        $this->messengerUsers->removeElement($messengerUser);

        return $this;
    }

    #[ORM\PrePersist]
    public function setDefaultCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setDefaultUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
