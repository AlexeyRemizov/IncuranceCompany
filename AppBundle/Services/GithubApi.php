<?php
namespace AppBundle\Services;

use AppBundle\Entity\Letter;
use AppBundle\Entity\RequestLetters;
use Doctrine\ORM\EntityRepository;
use Github\Client;

class GithubApi
{

    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getUserInfo($username)
    {
        try {
            $githubUser = $this->client->api('user')->show($username);
        } catch (\Exception $e) {
            $githubUser = null;
        }
        return $githubUser;
    }
}