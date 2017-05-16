<?php
namespace AppBundle\Services;

use AppBundle\Entity\Letter;
use AppBundle\Entity\RequestLetters;
use AppBundle\Entity\User;
use AppBundle\Services\Mailer\AppEmailsGenerator;
use AppBundle\Services\Weather\AppWeatherInterface;
use Cmfcmf\OpenWeatherMap\CurrentWeather;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class LetterManager
{
    /** @var  array */
    protected $letters;

    /** @var  EntityRepository */
    protected $em;

    /** @var AppEmailsGenerator  */
    protected $mailer;

    /** @var AppWeatherInterface  */
    protected $weatherService;

    /** @var  GithubApi */
    protected $github;


    public function __construct(EntityManager $em, GithubApi $githubApi, AppEmailsGenerator $mailer, AppWeatherInterface $weatherService)
    {
        $this->em = $em;
        $this->github = $githubApi;
        $this->mailer = $mailer;
        $this->weatherService = $weatherService;
    }
    public function handleLetters(RequestLetters $requestLetters, User $user)
    {
        /** @var Letter $letter */
        foreach ($requestLetters->getLetters() as $letter) {
            $letter->setUser($user);
            $letter->setCreated(new \DateTime());

            $this->em->persist($letter);
            // Getting user info from github
            $githubResponse = $this->github->getUserInfo($letter->getUsername());

            if (!$githubResponse) {
                $letter->setStatus(Letter::STATUS_USER_NOT_FOUND);
                continue;
            }

            $email = $githubResponse['email'];
            if (!$email) {
                $letter->setStatus(Letter::STATUS_HIDDEN_EMAIL);
                continue;
            }
            $location = $githubResponse['location'];
            $letter->setName($githubResponse['name']);
            $letter->setLocation($location);

            if ($location) {
                /** @var CurrentWeather $weather */
                $weather = $this->weatherService->getWeather($location);
                if ($weather) {
                    $letter->setWeather($weather);
                    $letter->setStatus(Letter::STATUS_SUCCESS);
                } else {
                    $letter->setStatus(Letter::STATUS_WITHOUT_WEATHER);
                }
            }
            $letter->setEmail($email);
            try {
                $this->mailer->sendLetter($letter);
            } catch (\Exception $e ) {
                $letter->setStatus(Letter::STATUS_SEND_ERROR);
            }

        }
        $this->em->flush();

        return $requestLetters->getLetters();
    }
    /**
     * @return array
     */
    public function getLetters()
    {
        return $this->letters;
    }

}