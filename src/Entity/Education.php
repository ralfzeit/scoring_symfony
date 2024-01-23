<?php
/*
 * Объект "Образование"
 * 
 * (c) Алексей Третьяков <ralfzeit@gmail.com>
 * 
 */

namespace App\Entity;

use App\Repository\EducationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Класс "Образование"
 */
#[ORM\Entity(repositoryClass: EducationRepository::class)]
class Education
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * Возвращает id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Возвращает название
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Устанавливает название
     * 
     * @param $title Название уровня образования
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }
}
