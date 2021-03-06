<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PhoneRepository::class)]
class Phone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    /**
     * @Groups("phone:read")
     * @Assert\NotBlank
     */
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $model;

    /**
     * @Groups("phone:read")
     * @Assert\NotBlank
     */
    #[ORM\Column(type: 'text')]
    private ?string $description;

    /**
     * @Groups("phone:read")
     * @Assert\NotBlank
     */
    #[ORM\Column(type: 'integer')]
    private ?int $year;

    /**
     * @Groups("phone:read")
     * @Assert\NotBlank
     */
    #[ORM\Column(type: 'float')]
    private ?float $price;

    /**
     * @Groups("phone:read")
     */
    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'phones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Brand $brand;

    /**
     * @Groups("phone:read")
     */
    #[ORM\Column(type: 'uuid')]
    private Uuid $uuid;

    public function __construct()
    {
        $this->uuid = Uuid::v6();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

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
}
