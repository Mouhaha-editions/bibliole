<?php

namespace DalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Emprunt
 *
 * @ORM\Table(name="emprunt")
 * @ORM\Entity(repositoryClass="DalBundle\Repository\EmpruntRepository")
 */
class Emprunt
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Utilisateur
     * @ORM\ManyToOne(targetEntity="DalBundle\Entity\Utilisateur",inversedBy="emprunts")
     * @ORM\JoinColumn(name="id_utilisateur",referencedColumnName="id")
     */
    private $utilisateur;

    /**
     * @var Livre
     * @ORM\ManyToOne(targetEntity="DalBundle\Entity\Livre",inversedBy="emprunts")
     * @ORM\JoinColumn(name="id_livre",referencedColumnName="id")
     */
    private $livre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin_prevue", type="datetime")
     */
    private $dateFinPrevue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     */
    private $dateFin;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set utilisateur
     *
     * @param Utilisateur $utilisateur
     *
     * @return Emprunt
     */
    public function setUtilisateur(Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set livre
     *
     * @param Livre $livre
     *
     * @return Emprunt
     */
    public function setLivre(Livre $livre)
    {
        $this->livre = $livre;

        return $this;
    }

    /**
     * Get livre
     *
     * @return Livre
     */
    public function getLivre()
    {
        return $this->livre;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Emprunt
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }
    /**
     * Get dateDebutStr
     *
     * @return string
     */
    public function getDateDebutStr()
    {
        return $this->dateDebut !== null ? $this->dateDebut->format('d/m/Y'):'ERREUR';
    }

    /**
     * Set dateFinPrevue
     *
     * @param \DateTime $dateFinPrevue
     *
     * @return Emprunt
     */
    public function setDateFinPrevue($dateFinPrevue)
    {
        $this->dateFinPrevue = $dateFinPrevue;

        return $this;
    }

    /**
     * Get dateFinPrevue
     *
     * @return \DateTime
     */
    public function getDateFinPrevue()
    {
        return $this->dateFinPrevue;
    }
    /**
     * Get getDateFinPrevueStr
     *
     * @return string
     */
    public function getDateFinPrevueStr()
    {
        return $this->dateFinPrevue !== null ? $this->dateFinPrevue->format('d/m/Y'):'ERREUR';
    }
    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Emprunt
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }
    /**
     * Get getDateFinPrevueStr
     *
     * @return string
     */
    public function getDateFinStr()
    {
        return $this->dateFin !== null ? $this->dateFin->format('d/m/Y'):'ERREUR';
    }
}
