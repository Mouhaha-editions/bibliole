<?php

namespace DalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Auteur
 *
 * @ORM\Table(name="auteur")
 * @ORM\Entity(repositoryClass="DalBundle\Repository\AuteurRepository")
 */
class Auteur
{

    use TimestampableEntity;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=50, nullable=true)
     */
    private $prenom;

    /**
     * @var Livre[]
     * @ORM\OneToMany(targetEntity="DalBundle\Entity\Livre",mappedBy="auteur")
     */
    private $livres;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
    }

    public function __toString(){
        return $this->nom." ".$this->prenom;
    }
    public function __toAutocomplete(){
        return $this->nom." ".$this->prenom;
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Auteur
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
     * @return Auteur
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
     * Add livre
     *
     * @param \DalBundle\Entity\Livre $livre
     *
     * @return Auteur
     */
    public function addLivre(\DalBundle\Entity\Livre $livre)
    {
        $this->livres[] = $livre;

        return $this;
    }

    /**
     * Remove livre
     *
     * @param \DalBundle\Entity\Livre $livre
     */
    public function removeLivre(\DalBundle\Entity\Livre $livre)
    {
        $this->livres->removeElement($livre);
    }

    /**
     * Get livres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLivres()
    {
        return $this->livres;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Auteur
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Auteur
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }
}
