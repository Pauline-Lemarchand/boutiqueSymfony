<?php

namespace App\Entity;

use App\Repository\ContactsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactsRepository::class)]
class Contacts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $lastnameContact = null;

    #[ORM\Column(length: 255)]
    private ?string $firstnameContact = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $messageContact = null;

    #[ORM\Column(length: 255)]
    private ?string $emailContact = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastnameContact(): ?string
    {
        return $this->lastnameContact;
    }

    public function setLastnameContact(string $lastnameContact): self
    {
        $this->lastnameContact = $lastnameContact;

        return $this;
    }

    public function getFirstnameContact(): ?string
    {
        return $this->firstnameContact;
    }

    public function setFirstnameContact(string $firstnameContact): self
    {
        $this->firstnameContact = $firstnameContact;

        return $this;
    }

    public function getMessageContact(): ?string
    {
        return $this->messageContact;
    }

    public function setMessageContact(string $messageContact): self
    {
        $this->messageContact = $messageContact;

        return $this;
    }

    public function getEmailContact(): ?string
    {
        return $this->emailContact;
    }

    public function setEmailContact(string $emailContact): self
    {
        $this->emailContact = $emailContact;

        return $this;
    }
}
