<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="letters")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LetterRepository")
 */
class Letter
{
    const STATUS_NEW = 10;
    const STATUS_USER_NOT_FOUND = 20;
    const STATUS_HIDDEN_EMAIL = 30;
    const STATUS_WITHOUT_WEATHER    = 40;
    const STATUS_SUCCESS    = 50;
    const STATUS_SEND_ERROR    = 60;


    /**
     * @Groups({"letter"})
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"letter"})
     * @Assert\NotBlank()
     * @ORM\Column(type="string", nullable=true)
     */
    protected $username;
    /**
     * @Groups({"letter"})
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;
    /**
     * @Groups({"letter"})
     * @ORM\Column(type="string", nullable=true)
     */
    protected $location;
    /**
     * @Groups({"letter"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;
    /**
     * @Groups({"letter"})
     * @ORM\Column(type="text", nullable=true)
     */
    protected $weather;

    /**
     * @Groups({"letter"})
     * @ORM\Column(type="text", nullable=true)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="letters", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @Groups({"letter"})
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=100)
     * @ORM\Column(type="text")
     */
    protected $message;

    /**
     *
     * @Groups({"letter"})
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @Groups({"letter"})
     */
    public $textStatus;


    public function __construct()
    {
        $this->status = self::STATUS_NEW;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }



    /**
     * Set username
     *
     * @param string $username
     *
     * @return Letter
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Letter
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return Letter
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Letter
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set weather
     *
     * @param string $weather
     *
     * @return Letter
     */
    public function setWeather($weather)
    {
        $this->weather = $weather;

        return $this;
    }

    /**
     * Get weather
     *
     * @return string
     */
    public function getWeather()
    {
        return $this->weather;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Letter
     */
    public function setStatus($status)
    {
        $this->status = $status;
        $this->setTextStatus($status);
        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        $this->setTextStatus($this->status);
        return $this->status;

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

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setTextStatus($status)
    {
        switch ($status) {
            case self::STATUS_NEW:
                $this->textStatus = 'New letter';
                break;
            case self::STATUS_USER_NOT_FOUND:
                $this->textStatus = 'Github user not found';
                break;
            case self::STATUS_HIDDEN_EMAIL:
                $this->textStatus = 'Github user hide your email. Letter not sent';
                break;
            case self::STATUS_WITHOUT_WEATHER:
                $this->textStatus = 'Letter sent, but without weather(blank or incorrect location of the user)';
                break;
            case self::STATUS_SUCCESS:
                $this->textStatus = 'Letter successfully sent';
                break;
            case self::STATUS_SEND_ERROR:
                $this->textStatus = 'The letter was not delivered';
                break;
            default:
                $this->textStatus = '';

        }
    }
    public function getTextStatus()
    {
        return $this->textStatus;
    }

}
