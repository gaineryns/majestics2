<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tickets
 *
 * @ORM\Table(name="MTICKETAPP")
 * @ORM\Entity(repositoryClass="App\Repository\TicketsRepository", readOnly=true)
 */
class Tickets
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="GP_NATUREPIECEG", type="string", length=45, nullable=true)
     */
    private $nature;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="GP_DATEPIECE", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="GP_SOUCHE", type="integer", nullable=true)
     */
    private $souche;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="GP_HEURECREATION", type="datetime", nullable=true)
     */
    private $heureDeCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="ET_LIBELLE", type="string", length=45, nullable=true)
     */
    private $etablissement;

    /**
     * private $depotDocument;
     */


    /**
     * @var string
     *
     * @ORM\Column(name="GP_CAISSE", type="string", length=45, nullable=true)
     */
    private $caisse;


    private $vendeur;

    private $client;

    private $nom;

    private $prenom;


    private $codePostal;

    private $totalQte;

    private $totalHtDocument;

    private $totalTtc;

    private $remise;

    private $dateDeComptabilisation;

    private $dateCreationDoc;

    private $dateModificationDoc;

    private $utilisateurDOrigine;


    private $referenceInterneDoc;


    private $ville;


    private $modeDeCreation;


    private $documentComptabilise;


    private $societe;

    /**
     * @var integer
     *
     * @ORM\Column(name="GP_NUMERO", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @return string
     */
    public function getNature(): string
    {
        return $this->nature;
    }

    /**
     * @param string $nature
     */
    public function setNature(string $nature): void
    {
        $this->nature = $nature;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getSouche(): int
    {
        return $this->souche;
    }

    /**
     * @param int $souche
     */
    public function setSouche(int $souche): void
    {
        $this->souche = $souche;
    }

    /**
     * @return \DateTime
     */
    public function getHeureDeCreation(): \DateTime
    {
        return $this->heureDeCreation;
    }

    /**
     * @param \DateTime $heureDeCreation
     */
    public function setHeureDeCreation(\DateTime $heureDeCreation): void
    {
        $this->heureDeCreation = $heureDeCreation;
    }

    /**
     * @return string
     */
    public function getEtablissement(): string
    {
        return $this->etablissement;
    }

    /**
     * @param string $etablissement
     */
    public function setEtablissement(string $etablissement): void
    {
        $this->etablissement = $etablissement;
    }

    /**
     * @return string
     */
    public function getCaisse(): string
    {
        return $this->caisse;
    }

    /**
     * @param string $caisse
     */
    public function setCaisse(string $caisse): void
    {
        $this->caisse = $caisse;
    }

    /**
     * @return mixed
     */
    public function getVendeur()
    {
        return $this->vendeur;
    }

    /**
     * @param mixed $vendeur
     */
    public function setVendeur($vendeur): void
    {
        $this->vendeur = $vendeur;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client): void
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * @param mixed $codePostal
     */
    public function setCodePostal($codePostal): void
    {
        $this->codePostal = $codePostal;
    }

    /**
     * @return mixed
     */
    public function getTotalQte()
    {
        return $this->totalQte;
    }

    /**
     * @param mixed $totalQte
     */
    public function setTotalQte($totalQte): void
    {
        $this->totalQte = $totalQte;
    }

    /**
     * @return mixed
     */
    public function getTotalHtDocument()
    {
        return $this->totalHtDocument;
    }

    /**
     * @param mixed $totalHtDocument
     */
    public function setTotalHtDocument($totalHtDocument): void
    {
        $this->totalHtDocument = $totalHtDocument;
    }

    /**
     * @return mixed
     */
    public function getTotalTtc()
    {
        return $this->totalTtc;
    }

    /**
     * @param mixed $totalTtc
     */
    public function setTotalTtc($totalTtc): void
    {
        $this->totalTtc = $totalTtc;
    }

    /**
     * @return mixed
     */
    public function getRemise()
    {
        return $this->remise;
    }

    /**
     * @param mixed $remise
     */
    public function setRemise($remise): void
    {
        $this->remise = $remise;
    }

    /**
     * @return mixed
     */
    public function getDateDeComptabilisation()
    {
        return $this->dateDeComptabilisation;
    }

    /**
     * @param mixed $dateDeComptabilisation
     */
    public function setDateDeComptabilisation($dateDeComptabilisation): void
    {
        $this->dateDeComptabilisation = $dateDeComptabilisation;
    }

    /**
     * @return mixed
     */
    public function getDateCreationDoc()
    {
        return $this->dateCreationDoc;
    }

    /**
     * @param mixed $dateCreationDoc
     */
    public function setDateCreationDoc($dateCreationDoc): void
    {
        $this->dateCreationDoc = $dateCreationDoc;
    }

    /**
     * @return mixed
     */
    public function getDateModificationDoc()
    {
        return $this->dateModificationDoc;
    }

    /**
     * @param mixed $dateModificationDoc
     */
    public function setDateModificationDoc($dateModificationDoc): void
    {
        $this->dateModificationDoc = $dateModificationDoc;
    }

    /**
     * @return mixed
     */
    public function getUtilisateurDOrigine()
    {
        return $this->utilisateurDOrigine;
    }

    /**
     * @param mixed $utilisateurDOrigine
     */
    public function setUtilisateurDOrigine($utilisateurDOrigine): void
    {
        $this->utilisateurDOrigine = $utilisateurDOrigine;
    }

    /**
     * @return mixed
     */
    public function getReferenceInterneDoc()
    {
        return $this->referenceInterneDoc;
    }

    /**
     * @param mixed $referenceInterneDoc
     */
    public function setReferenceInterneDoc($referenceInterneDoc): void
    {
        $this->referenceInterneDoc = $referenceInterneDoc;
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param mixed $ville
     */
    public function setVille($ville): void
    {
        $this->ville = $ville;
    }

    /**
     * @return mixed
     */
    public function getModeDeCreation()
    {
        return $this->modeDeCreation;
    }

    /**
     * @param mixed $modeDeCreation
     */
    public function setModeDeCreation($modeDeCreation): void
    {
        $this->modeDeCreation = $modeDeCreation;
    }

    /**
     * @return mixed
     */
    public function getDocumentComptabilise()
    {
        return $this->documentComptabilise;
    }

    /**
     * @param mixed $documentComptabilise
     */
    public function setDocumentComptabilise($documentComptabilise): void
    {
        $this->documentComptabilise = $documentComptabilise;
    }

    /**
     * @return mixed
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * @param mixed $societe
     */
    public function setSociete($societe): void
    {
        $this->societe = $societe;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
    }

}