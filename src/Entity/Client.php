<?php
/*
 * Объект "Клиент"
 * 
 * (c) Алексей Третьяков <ralfzeit@gmail.com>
 * 
 */

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: '0')]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $agree = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Education $education_id = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $score = null;

    /**
     * Возвращает id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Возвращает имя
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Устанавливает имя
     * 
     * @param $name Имя
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Возвращает фамилию
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * Устанавливает фамилию
     * 
     * @param $surname Фамилия
     */
    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Возвращает номер телефона
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Устанавливает номер телефона
     * 
     * @param $phone Номер мобильного телефона в формате 7xxxxxxxxxx
     */
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Возвращает адрес электронной почты
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Устанавливает адрес электронной почты
     * 
     * @param $email Адрес электронной почты
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Возвращает информацию, согласен ли клиент на обработку данных
     */
    public function isAgree(): ?bool
    {
        return $this->agree;
    }

    /**
     * Устанавливает согласие на обработку данных
     * 
     * @param $agree Согласие (true или false)
     */
    public function setAgree(bool $agree): static
    {
        $this->agree = $agree;

        return $this;
    }

    /**
     * Возвращает образование
     */
    public function getEducationId(): ?Education
    {
        return $this->education_id;
    }

    /**
     * Устанавливает образование
     * 
     * @param $Education Объект типа Образование
     */
    public function setEducationId(?Education $education_id): static
    {
        $this->education_id = $education_id;

        return $this;
    }

    /**
     * Возвращает актуальный скоринг
     */
    public function getScore(): ?int
    {
        return $this->score;
    }

    /**
     * Устанавливает скоринг
     * 
     * @param $score Скоринг
     */
    public function setScore(?int $score): static
    {
        $this->score = $score;

        return $this;
    }
}
