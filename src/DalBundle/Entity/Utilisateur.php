<?php

namespace DalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\Expr\Base;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Auteur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="DalBundle\Repository\AuteurRepository")
 */
class Utilisateur extends BaseUser
{

    use TimestampableEntity;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    /**
     * @var string
     * @ORM\Column(name="nom",type="string", nullable=true)
     */
    protected $nom;
    /**
     * @var string
     * @ORM\Column(name="prenom",type="string", nullable=false)
     */
    protected $prenom;

    /**
     * @var Classe
     * @ORM\ManyToOne(targetEntity="DalBundle\Entity\Classe",inversedBy="utilisateurs",cascade={"persist"})
     * @ORM\JoinColumn(name="id_classe", nullable=true)
     */
    protected $classe;

    /**
     * @var Emprunt[]
     *
     * @ORM\OneToMany(targetEntity="DalBundle\Entity\Emprunt",mappedBy="utilisateur",cascade={"persist"})
     */
    private $emprunts;

    public function __construct()
    {
        parent::__construct();
        $this->emprunts = new ArrayCollection();

        // your own logic
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Utilisateur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Utilisateur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }


    /**
     * Set classe
     *
     * @param \DalBundle\Entity\Classe $classe
     *
     * @return Utilisateur
     */
    public function setClasse(\DalBundle\Entity\Classe $classe = null)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return \DalBundle\Entity\Classe
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Add emprunt
     *
     * @param \DalBundle\Entity\Emprunt $emprunt
     *
     * @return Utilisateur
     */
    public function addEmprunt(\DalBundle\Entity\Emprunt $emprunt)
    {
        $this->emprunts[] = $emprunt;

        return $this;
    }

    /**
     * Remove emprunt
     *
     * @param \DalBundle\Entity\Emprunt $emprunt
     */
    public function removeEmprunt(\DalBundle\Entity\Emprunt $emprunt)
    {
        $this->emprunts->removeElement($emprunt);
    }

    /**
     * Get emprunts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmprunts()
    {
        return $this->emprunts;
    }

    public function __toString(){
        return $this->prenom." ".$this->nom.($this->getClasse() !== null ? " - ".$this->getClasse()->__toString():'');
    }
}
