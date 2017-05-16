<?php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class RequestLetters
{
    /**
     * @Assert\Valid
     * @Assert\Count(min=1,max=5)
     */
    protected $letters;

    /**
     * @return mixed
     */
    public function getLetters()
    {
        return $this->letters;
    }

    /**
     * @param mixed $letters
     */
    public function setLetters($letters)
    {
        $this->letters = $letters;
    }

}