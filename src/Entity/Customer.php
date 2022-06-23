<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV6;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomerAssert;

/**
 * @CustomerAssert\UniqueCustomerByResellerClass(mode="strict")
 */
#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    /**
     * @Groups("customer:read")
     * @Assert\Length(
     *      min = 2,
     *      max = 20,
     *      minMessage = "Le prénom doit contenir au minimum {{ limit }} caractères.",
     *      maxMessage = "Le prénom doit contenir au maximum {{ limit }} caractères."
     * )
     * @Assert\Regex(
     *     "/^\pL+([- ']\pL+)*$/u",
     *     message="Le prénom n'est pas valide."
     * )
     */
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $firstname;

    /**
     * @Groups("customer:read")
     * @Assert\Length(
     *      min = 2,
     *      max = 20,
     *      minMessage = "Le nom doit contenir au minimum {{ limit }} caractères.",
     *      maxMessage = "Le nom doit contenir au maximum {{ limit }} caractères."
     * )
     * @Assert\Regex(
     *     "/^\pL+([- ']\pL+)*$/u",
     *     message="Le nom n'est pas valide."
     * )
     */
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $lastname;

    /**
     * @Groups("customer:read")
     * @Assert\Email(
     *     message = "L'email n'est pas valide."
     * )
     * @Assert\NotBlank(
     *     message="L'email doit être renseigné."
     * )
     */
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $email;

    /**
     * @Groups("customer:read")
     */
    #[ORM\ManyToOne(targetEntity: Reseller::class, inversedBy: 'customers')]
    private ?Reseller $reseller;

    /**
     * @Groups("customer:read")
     */
    #[ORM\Column(type: 'uuid')]
    private Uuid $uuid;

    /**
     * @Groups("customer:read")
     */
    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->uuid = Uuid::v6();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getReseller(): ?Reseller
    {
        return $this->reseller;
    }

    public function setReseller(?Reseller $reseller): self
    {
        $this->reseller = $reseller;

        return $this;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
