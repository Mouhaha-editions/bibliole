<?php

namespace DalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Livre
 *
 * @ORM\Table(name="livre")
 * @ORM\Entity(repositoryClass="DalBundle\Repository\LivreRepository")
 */
class Livre
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
     *
     * @ORM\Column(name="titre", type="text", length=255, nullable=true)
     */
    private $titre;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer", nullable=false)
     */
    private $numero;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero_tome", type="integer", nullable=true)
     */
    private $numeroTome;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="text", nullable=true)
     */
    private $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="isbn", type="text", length=50, nullable=true)
     */
    private $isbn;

    /**
     * @var Auteur
     *
     * @ORM\ManyToOne(targetEntity="Auteur", inversedBy="livres")
     *
     * @ORM\JoinColumn(name="auteur", referencedColumnName="id")
     *
     */
    private $auteur;

    /**
     * @var Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie",inversedBy="livres")
     *
     * @ORM\JoinColumn(name="categorie", referencedColumnName="id")
     *
     */
    private $categorie;

    /**
     * @var Keyword[]
     *
     * @ORM\ManyToMany(targetEntity="Keyword", inversedBy="livres",cascade={"persist"})
     * @ORM\JoinTable(name="keyword_livre")
     */
    private $keywords;

    /**
     * @var Emprunt[]
     *
     * @ORM\OneToMany(targetEntity="DalBundle\Entity\Emprunt",mappedBy="livre")
     */
    private $emprunts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->keywords = new ArrayCollection();
        $this->emprunts = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitre();
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
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Livre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get isbn
     *
     * @return integer
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * Set isbn
     *
     * @param integer $isbn
     *
     * @return Livre
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set resume
     *
     * @param string $resume
     *
     * @return Livre
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \DalBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set categorie
     *
     * @param \DalBundle\Entity\Categorie $categorie
     *
     * @return Livre
     */
    public function setCategorie(\DalBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Add keyword
     *
     * @param \DalBundle\Entity\Keyword $keyword
     *
     * @return Livre
     */
    public function addKeyword(\DalBundle\Entity\Keyword $keyword)
    {
        $this->keywords[] = $keyword;

        return $this;
    }

    /**
     * Remove keyword
     *
     * @param \DalBundle\Entity\Keyword $keyword
     */
    public function removeKeyword(\DalBundle\Entity\Keyword $keyword)
    {
        $this->keywords->removeElement($keyword);
    }

    /**
     * Get keywords
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return Livre
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numeroTome
     *
     * @return integer
     */
    public function getNumeroTome()
    {
        return $this->numeroTome;
    }

    /**
     * Set numeroTome
     *
     * @param integer $numeroTome
     *
     * @return Livre
     */
    public function setNumeroTome($numeroTome)
    {
        $this->numeroTome = $numeroTome;

        return $this;
    }

    public function getTranche()
    {
        return $this->categorie != null && $this->getAuteur() != null ? $this->categorie->getCode() . "-" . strtoupper(substr($this->getAuteur()->getNom(), 0, 3)) : "XXXXXXXXXXXXXXXXXX";
    }

    /**
     * Get auteur
     *
     * @return \DalBundle\Entity\Auteur
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set auteur
     *
     * @param \DalBundle\Entity\Auteur $auteur
     *
     * @return Livre
     */
    public function setAuteur(\DalBundle\Entity\Auteur $auteur = null)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Livre
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
     * @return Livre
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Add emprunt
     *
     * @param \DalBundle\Entity\Emprunt $emprunt
     *
     * @return Livre
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
}
