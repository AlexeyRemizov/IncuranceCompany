<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface, \Serializable
{
    /**
     * @Groups({"user"})
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     * @Assert\Length(min=6, max=60)
     */
    private $password;

    /**
     * @Groups({"user"})
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    protected $roles;

    /**
     *
     * @Groups({"user"})
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    protected $avatar;
    /**
     * @Groups({"user"})
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    protected $avatarThumbnail;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Letter", mappedBy="user")
     */
    protected $letters;
    /**
     * @Groups({"user"})
     */
    protected $token;

    /**
     * @Assert\Image(
     *     minWidth = 200,
     *     maxWidth = 1000,
     *     minHeight = 200,
     *     maxHeight = 1000,
     *     maxSize="2M",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg"}
     * ),
     * @Assert\NotBlank()
     */
    protected $file;

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }


    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }


    public function getUsername()
    {
        return $this->email;
    }

    public function getEmail()
    {
        return $this->getUsername();
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->roles
        ));
    }

    /** @see \Serializable::unserialize()
     * @param array $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->discriminator
            ) = unserialize($serialized);
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->letters = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->getPath().$this->avatar;
    }

    /**
     * Set avatarThumbnail
     *
     * @param string $avatarThumbnail
     *
     * @return User
     */
    public function setAvatarThumbnail($avatarThumbnail)
    {
        $this->avatarThumbnail = $avatarThumbnail;

        return $this;
    }

    /**
     * Get avatarThumbnail
     *
     * @return string
     */
    public function getAvatarThumbnail()
    {
        return $this->getPath().$this->avatarThumbnail;
    }

    /**
     * Add letter
     *
     * @param \AppBundle\Entity\Letter $letter
     *
     * @return User
     */
    public function addLetter(\AppBundle\Entity\Letter $letter)
    {
        $this->letters[] = $letter;

        return $this;
    }

    /**
     * Remove letter
     *
     * @param \AppBundle\Entity\Letter $letter
     */
    public function removeLetter(\AppBundle\Entity\Letter $letter)
    {
        $this->letters->removeElement($letter);
    }

    /**
     * Get letters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLetters()
    {
        return $this->letters;
    }

    public function getPath()
    {
        return $_SERVER['HTTP_HOST'].'/uploads/';
    }
}
