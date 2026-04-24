<?php

namespace App\Entity;

use App\Enums\MessengerTypeEnum;
use App\Repository\MessengerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessengerRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Messenger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column(nullable: false)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: false)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, MessengerUser>
     */
    #[ORM\OneToMany(targetEntity: MessengerUser::class, mappedBy: 'messenger', orphanRemoval: true)]
    private Collection $messengerUsers;

    public function __construct()
    {
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getMessengerType(): ?MessengerTypeEnum
    {
        return MessengerTypeEnum::tryFrom($this->type);
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

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

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
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
            $messengerUser->setMessenger($this);
        }

        return $this;
    }

    public function removeMessengerUser(MessengerUser $messengerUser): static
    {
        if ($this->messengerUsers->removeElement($messengerUser)) {
            // set the owning side to null (unless already changed)
            if ($messengerUser->getMessenger() === $this) {
                $messengerUser->setMessenger(null);
            }
        }

        return $this;
    }
}
