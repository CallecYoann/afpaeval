<?php

namespace mediathequeBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Utilisateurs
 *
 * @ORM\Table(name="utilisateurs", indexes={@ORM\Index(name="emprunt_id", columns={"emprunt_id"}), @ORM\Index(name="reservation_id", columns={"reservation_id"})})
 * @ORM\Entity
 */
class Utilisateurs extends BaseUser 
{

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=250, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=250, nullable=false)
     */
    private $prenom;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    public function __construct() {
        parent::__construct();
        // your own logic
    }

    /**
     * @var \mediathequeBundle\Entity\Emprunt
     *
     * @ORM\ManyToOne(targetEntity="mediathequeBundle\Entity\Emprunt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="emprunt_id", referencedColumnName="id")
     * })
     */
    private $emprunt;

    /**
     * @var \mediathequeBundle\Entity\Reservation
     *
     * @ORM\ManyToOne(targetEntity="mediathequeBundle\Entity\Reservation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reservation_id", referencedColumnName="id")
     * })
     */
    private $reservation;

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Utilisateurs
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Utilisateurs
     */
    public function setPrenom($prenom) {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom() {
        return $this->prenom;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set emprunt
     *
     * @param \mediathequeBundle\Entity\Emprunt $emprunt
     *
     * @return Utilisateurs
     */
    public function setEmprunt(\mediathequeBundle\Entity\Emprunt $emprunt = null) {
        $this->emprunt = $emprunt;

        return $this;
    }

    /**
     * Get emprunt
     *
     * @return \mediathequeBundle\Entity\Emprunt
     */
    public function getEmprunt() {
        return $this->emprunt;
    }

    /**
     * Set reservation
     *
     * @param \mediathequeBundle\Entity\Reservation $reservation
     *
     * @return Utilisateurs
     */
    public function setReservation(\mediathequeBundle\Entity\Reservation $reservation = null) {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get reservation
     *
     * @return \mediathequeBundle\Entity\Reservation
     */
    public function getReservation() {
        return $this->reservation;
    }

}
