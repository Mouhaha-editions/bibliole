<?php

namespace DalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Keyword
 *
 * @ORM\Table(name="keyword")
 * @ORM\Entity(repositoryClass="DalBundle\Repository\KeywordRepository")
 */
class Keyword
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
     * @ORM\Column(name="libelle", type="string", length=50, nullable=true)
     */
    private $libelle;

    /**
     * @var Livre[]
     *
     * @ORM\ManyToMany(targetEntity="DalBundle\Entity\Livre", mappedBy="keywords",cascade={"persist"})
     */
    private $livres;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->libelle;
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
     * @return Keyword
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
     * Add livre
     *
     * @param \DalBundle\Entity\Livre $livre
     *
     * @return Keyword
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
