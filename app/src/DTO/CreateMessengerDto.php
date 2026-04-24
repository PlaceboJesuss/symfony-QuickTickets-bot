<?php

namespace App\DTO;

use App\Enums\MessengerTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class CreateMessengerDto
{
    #[Assert\NotBlank]
    #[Assert\Choice(callback: [self::class, 'getAvailableTypes'])]
    private ?string $type = null;

    #[Assert\NotBlank]
    private ?string $token = null;

    #[Assert\NotBlank]
    private ?string $name = null;

    public function __construct(?string $type = null, ?string $token = null, ?string $name = null)
    {
        $this->type = $type;
        $this->token = $token;
        $this->name = $name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public static function getAvailableTypes(): array
    {
        return array_map(
            fn($e) => $e->value,
            MessengerTypeEnum::cases()
        );
    }
}
