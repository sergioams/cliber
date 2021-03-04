<?php
namespace App\Entity;

use App\Repository\CurrencyTransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class CurrencyTransaction
 * @ORM\Entity(repositoryClass=CurrencyTransactionRepository::class)
 * @ORM\Table(name="currency_transactions")
 */
class CurrencyTransactionEntity{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="decimal", scale=5, name="amount", nullable=false)
     */
    protected $amount;

    /**
     * @ORM\Column(type="string", length=4, name="to_currency", nullable=false)
     */
    protected $toCurrency;

    /**
     * @ORM\Column(type="string", length=4, name="from_currency", nullable=false)
     */
    protected $fromCurrency;
    
    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     */
    protected $createdAt;


    public function getId(){
        return $this->id;
    }

    public function getAmount(){
        return $this->amount;
    }
    public function setAmount($amount){
        $this->amount = $amount;
    }

    public function getFromCurrency(){
        return $this->fromCurrency;
    }
    public function setFromCurrency($fromCurrency){
        $this->fromCurrency = $fromCurrency;
    }

    public function getToCurrency(){
        return $this->toCurrency;
    }
    public function setToCurrency($toCurrency){
        $this->toCurrency = $toCurrency;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }
    public function setCreatedAt(){
        $this->createdAt = new \DateTime('now');
    }
}
