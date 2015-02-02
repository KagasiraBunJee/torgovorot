<?php

namespace Application\UserBundle\Entity;
 
use Symfony\Component\Security\User\UserProviderInterface;
 
use Doctrine\ORM\EntityRepository;
 
class UserRepository extends EntityRepository implements UserProviderInterface
{
/**
* @param string $username
* @return \Torgovorot\TorgBundle\Entity\Users
*/
    public function loadUserByEmail($email)
    {
        return $this->findOneBy(array('email' => $email));
    }
}
