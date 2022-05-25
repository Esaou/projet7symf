<?php

namespace App\Entity;

use App\Repository\ResellerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ResellerRepository::class)]
class Reseller implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    /**
     * @Groups("customer:read")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Le nom doit contenir au minimum {{ limit }} caractères.",
     *      maxMessage = "Le nom doit contenir au maximum {{ limit }} caractères."
     * )
     * @Assert\NotBlank
     */
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name;

    /**
     * @Groups("customer:read")
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas valide."
     * )
     * @Assert\NotBlank
     */
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $email;

    /**
     * @Assert\Regex(
     *     "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[#-+!*$@%_])([#-+!*$@%_\w]{8,100})$/",
     *     message="Le mot de passe doit contenir au moins 1 chiffre, une lettre minuscule, majuscule, un caractère spécial et 8 caractères minimum !"
     * )
     * @Assert\NotBlank
     * @Assert\NotCompromisedPassword()
     */
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $password;

    #[ORM\OneToMany(mappedBy: 'reseller', targetEntity: Customer::class)]
    private $customers;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

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
        $this->customers = new ArrayCollection();
        $this->uuid = Uuid::v6();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setReseller($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getReseller() === $this) {
                $customer->setReseller(null);
            }
        }

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
