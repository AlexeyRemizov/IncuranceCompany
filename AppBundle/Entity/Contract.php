<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contract
 *
 * @ORM\Table(name="contract")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContractRepository")
 */
class Contract
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="contracts", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateIN", type="string")
     */
    private $dateIN;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOFF", type="string")
     */
    private $dateOFF;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var int
     *
     * @ORM\Column(name="vinID", type="integer")
     */
    private $vinID;

    /**
     * @var int
     *
     * @ORM\Column(name="registrationID", type="integer")
     */
    private $registrationID;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePay", type="string")
     */
    private $datePay;

    /**
     * @var int
     *
     * @ORM\Column(name="sumpay", type="integer")
     */
    private $sumpay;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Contract
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set dateIN
     *
     * @param \DateTime $dateIN
     *
     * @return Contract
     */
    public function setDateIN($dateIN)
    {
        $this->dateIN = $dateIN;

        return $this;
    }

    /**
     * Get dateIN
     *
     * @return \DateTime
     */
    public function getDateIN()
    {
        return $this->dateIN;
    }

    /**
     * Set dateOFF
     *
     * @param \DateTime $dateOFF
     *
     * @return Contract
     */
    public function setDateOFF($dateOFF)
    {
        $this->dateOFF = $dateOFF;

        return $this;
    }

    /**
     * Get dateOFF
     *
     * @return \DateTime
     */
    public function getDateOFF()
    {
        return $this->dateOFF;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Contract
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set vinID
     *
     * @param integer $vinID
     *
     * @return Contract
     */
    public function setVinID($vinID)
    {
        $this->vinID = $vinID;

        return $this;
    }

    /**
     * Get vinID
     *
     * @return int
     */
    public function getVinID()
    {
        return $this->vinID;
    }

    /**
     * Set registrationID
     *
     * @param integer $registrationID
     *
     * @return Contract
     */
    public function setRegistrationID($registrationID)
    {
        $this->registrationID = $registrationID;

        return $this;
    }

    /**
     * Get registrationID
     *
     * @return int
     */
    public function getRegistrationID()
    {
        return $this->registrationID;
    }

    /**
     * Set datePay
     *
     * @param \DateTime $datePay
     *
     * @return Contract
     */
    public function setDatePay($datePay)
    {
        $this->datePay = $datePay;

        return $this;
    }

    /**
     * Get datePay
     *
     * @return \DateTime
     */
    public function getDatePay()
    {
        return $this->datePay;
    }

    /**
     * Set sumpay
     *
     * @param integer $sumpay
     *
     * @return Contract
     */
    public function setSumpay($sumpay)
    {
        $this->sumpay = $sumpay;

        return $this;
    }

    /**
     * Get sumpay
     *
     * @return int
     */
    public function getSumpay()
    {
        return $this->sumpay;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Letter
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}

