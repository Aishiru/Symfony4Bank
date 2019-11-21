<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", inversedBy="creditTransactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creditAccount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", inversedBy="debitTransactions")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Expression(
     *     "this.getAmount() < value.getBalance()",
     *     message="This account cannot be debitted"
     * )
     */
    private $debitAccount;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreditAccount(): ?Account
    {
        return $this->creditAccount;
    }

    public function setCreditAccount(?Account $creditAccount): self
    {
        $this->creditAccount = $creditAccount;

        return $this;
    }

    public function getDebitAccount(): ?Account
    {
        return $this->debitAccount;
    }

    public function setDebitAccount(?Account $debitAccount): self
    {
        $this->debitAccount = $debitAccount;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
