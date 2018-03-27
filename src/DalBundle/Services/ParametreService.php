<?php
/**
 * Created by PhpStorm.
 * User: pierr
 * Date: 26/03/2018
 * Time: 23:04
 */

namespace DalBundle\Services;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ParametreService
{
    /** @var EntityManager */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function get($name, $default = null)
    {
        $param = $this->em->getRepository('DalBundle:Parametre')->findOneBy(['name' => $name]);
        return $param != null ? $param->getValue() : $default;
    }


}