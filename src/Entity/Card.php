<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="text")
     */
    private $front_main_content;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $front_subcontent;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $back_main_content;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $back_subcontent;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $front_clue;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $back_clue;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="integer")
     */
    private $stage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getFrontMainContent(): ?string
    {
        return $this->front_main_content;
    }

    public function setFrontMainContent(?string $front_main_content): self
    {
        $this->front_main_content = $front_main_content;

        return $this;
    }

    public function getFrontSubcontent(): ?string
    {
        return $this->front_subcontent;
    }

    public function setFrontSubcontent(?string $front_subcontent): self
    {
        $this->front_subcontent = $front_subcontent;

        return $this;
    }

    public function getBackMainContent(): ?string
    {
        return $this->back_main_content;
    }

    public function setBackMainContent(?string $back_main_content): self
    {
        $this->back_main_content = $back_main_content;

        return $this;
    }

    public function getBackSubcontent(): ?string
    {
        return $this->back_subcontent;
    }

    public function setBackSubcontent(?string $back_subcontent): self
    {
        $this->back_subcontent = $back_subcontent;

        return $this;
    }

    public function getFrontClue(): ?string
    {
        return $this->front_clue;
    }

    public function setFrontClue(?string $front_clue): self
    {
        $this->front_clue = $front_clue;

        return $this;
    }

    public function getBackClue(): ?string
    {
        return $this->back_clue;
    }

    public function setBackClue(?string $back_clue): self
    {
        $this->back_clue = $back_clue;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getStage(): ?int
    {
        return $this->stage;
    }

    public function setStage(int $stage): self
    {
        $this->stage = $stage;

        return $this;
    }
}
