<?php

namespace DalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="DalBundle\Repository\CategorieRepository")
 */
class Categorie
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
     * @ORM\Column(name="libelle", type="string", length=50, nullable=false)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=5, nullable=false)
     */
    private $code;
    /**
     * @var Livre
     *
     * @ORM\OneToMany(targetEntity="DalBundle\Entity\Livre",mappedBy="categorie")
     *
     *
     */
    private $livres;

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getLibelle();
    }

    public function __construct()
    {
        $this->livres = new ArrayCollection();
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Categorie
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Categorie
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Add livre
     *
     * @param \DalBundle\Entity\Livre $livre
     *
     * @return Categorie
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
}
