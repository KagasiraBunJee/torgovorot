<?php
namespace Torgovorot\TorgBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use Torgovorot\TorgBundle\Entity\Users;

class Registration
{
    /**
     * @Assert\Type(type="Torgovorot\TorgBundle\Entity\Users")
     * @Assert\Valid()
     */
    protected $user;

    /**
     * @Assert\NotBlank()
     * @Assert\True()
     */
    protected $termsAccepted;

    public function setUser(Users $user)
    {
        $user->setPassHex(md5($user->getPassNorm()));
        $user->setAccessLvl(0);
        $user->setRegisterTime(date("Y-m-d H:i:s"));
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }

    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = (Boolean) $termsAccepted;
    }
}
