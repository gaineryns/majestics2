<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="entry")
 * @ORM\Entity(repositoryClass="App\Repository\EntryRepository")
 */
class Entry
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $category;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $division_magasin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_porte;

    /**
     * @ORM\Column(type="string",length=100, nullable=true)
     */
    private $magasin;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datetime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $entree;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sortie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $entree_totale;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getDivisionMagasin()
    {
        return $this->division_magasin;
    }

    /**
     * @param mixed $division_magasin
     */
    public function setDivisionMagasin($division_magasin)
    {
        $this->division_magasin = $division_magasin;
    }

    /**
     * @return mixed
     */
    public function getIdPorte()
    {
        return $this->id_porte;
    }

    /**
     * @param mixed $id_porte
     */
    public function setIdPorte($id_porte)
    {
        $this->id_porte = $id_porte;
    }

    /**
     * @return mixed
     */
    public function getMagasin()
    {
        return $this->magasin;
    }

    /**
     * @param mixed $magasin
     */
    public function setMagasin($magasin)
    {
        $this->magasin = $magasin;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param mixed $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return mixed
     */
    public function getEntree()
    {
        return $this->entree;
    }

    /**
     * @param mixed $entree
     */
    public function setEntree($entree)
    {
        $this->entree = $entree;
    }

    /**
     * @return mixed
     */
    public function getSortie()
    {
        return $this->sortie;
    }

    /**
     * @param mixed $sortie
     */
    public function setSortie($sortie)
    {
        $this->sortie = $sortie;
    }

    /**
     * @return mixed
     */
    public function getEntreeTotale()
    {
        return $this->entree_totale;
    }

    /**
     * @param mixed $entree_totale
     */
    public function setEntreeTotale($entree_totale)
    {
        $this->entree_totale = $entree_totale;
    }

    /**
     * @return mixed
     */
    public function getSortieTotale()
    {
        return $this->sortie_totale;
    }

    /**
     * @param mixed $sortie_totale
     */
    public function setSortieTotale($sortie_totale)
    {
        $this->sortie_totale = $sortie_totale;
    }

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sortie_totale;

    // add your own fields
}
