<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tickets
 *
 * @ORM\Table(name="tickets")
 * @ORM\Entity(repositoryClass="App\Repository\TicketsRepository")
 */
class Tickets
{
    /**
     * @var string
     *
     * @ORM\Column(name="Nature", type="string", length=45, nullable=true)
     */
    private $nature;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="Souche", type="integer", nullable=true)
     */
    private $souche;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Heure_de_creation", type="datetime", nullable=true)
     */
    private $heureDeCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="Etablissement", type="string", length=45, nullable=true)
     */
    private $etablissement;

    /**
     * @var integer
     *
     * @ORM\Column(name="Depot_document", type="integer", nullable=true)
     */
    private $depotDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="Caisse", type="string", length=45, nullable=true)
     */
    private $caisse;

    /**
     * @var string
     *
     * @ORM\Column(name="Vendeur", type="string", length=45, nullable=true)
     */
    private $vendeur;

    /**
     * @var string
     *
     * @ORM\Column(name="Client", type="string", length=45, nullable=true)
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=45, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="Prenom", type="string", length=45, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="Code_postal", type="string", length=45, nullable=true)
     */
    private $codePostal;

    /**
     * @var integer
     *
     * @ORM\Column(name="Total_Qte", type="integer", nullable=true)
     */
    private $totalQte;

    /**
     * @var string
     *
     * @ORM\Column(name="Total_HT_document", type="string", length=45, nullable=true)
     */
    private $totalHtDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="Total_TTC", type="string", length=45, nullable=true)
     */
    private $totalTtc;

    /**
     * @var integer
     *
     * @ORM\Column(name="__remise", type="integer", nullable=true)
     */
    private $remise;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_de_comptabilisation", type="datetime", nullable=true)
     */
    private $dateDeComptabilisation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_creation_doc_", type="datetime", nullable=true)
     */
    private $dateCreationDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="Date_modification_doc_", type="string", length=45, nullable=true)
     */
    private $dateModificationDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="Utilisateur_d_origine", type="string", length=45, nullable=true)
     */
    private $utilisateurDOrigine;

    /**
     * @var integer
     *
     * @ORM\Column(name="Reference_interne_doc_", type="bigint", nullable=true)
     */
    private $referenceInterneDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="Ville", type="string", length=45, nullable=true)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="Mode_de_creation", type="string", length=45, nullable=true)
     */
    private $modeDeCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="Document_comptabilise", type="string", length=45, nullable=true)
     */
    private $documentComptabilise;

    /**
     * @var string
     *
     * @ORM\Column(name="Societe", type="string", length=45, nullable=true)
     */
    private $societe;

    /**
     * @var integer
     *
     * @ORM\Column(name="Numero", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
    public function setNature(string $nature)
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
    public function setDate(\DateTime $date)
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
    public function setSouche(int $souche)
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
    public function setHeureDeCreation(\DateTime $heureDeCreation)
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
    public function setEtablissement(string $etablissement)
    {
        $this->etablissement = $etablissement;
    }

    /**
     * @return int
     */
    public function getDepotDocument(): int
    {
        return $this->depotDocument;
    }

    /**
     * @param int $depotDocument
     */
    public function setDepotDocument(int $depotDocument)
    {
        $this->depotDocument = $depotDocument;
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
    public function setCaisse(string $caisse)
    {
        $this->caisse = $caisse;
    }

    /**
     * @return string
     */
    public function getVendeur(): string
    {
        return $this->vendeur;
    }

    /**
     * @param string $vendeur
     */
    public function setVendeur(string $vendeur)
    {
        $this->vendeur = $vendeur;
    }

    /**
     * @return string
     */
    public function getClient(): string
    {
        return $this->client;
    }

    /**
     * @param string $client
     */
    public function setClient(string $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom(string $prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getCodePostal(): string
    {
        return $this->codePostal;
    }

    /**
     * @param string $codePostal
     */
    public function setCodePostal(string $codePostal)
    {
        $this->codePostal = $codePostal;
    }

    /**
     * @return int
     */
    public function getTotalQte(): int
    {
        return $this->totalQte;
    }

    /**
     * @param int $totalQte
     */
    public function setTotalQte(int $totalQte)
    {
        $this->totalQte = $totalQte;
    }

    /**
     * @return string
     */
    public function getTotalHtDocument(): string
    {
        return $this->totalHtDocument;
    }

    /**
     * @param string $totalHtDocument
     */
    public function setTotalHtDocument(string $totalHtDocument)
    {
        $this->totalHtDocument = $totalHtDocument;
    }

    /**
     * @return string
     */
    public function getTotalTtc(): string
    {
        return $this->totalTtc;
    }

    /**
     * @param string $totalTtc
     */
    public function setTotalTtc(string $totalTtc)
    {
        $this->totalTtc = $totalTtc;
    }

    /**
     * @return int
     */
    public function getRemise(): int
    {
        return $this->remise;
    }

    /**
     * @param int $remise
     */
    public function setRemise(int $remise)
    {
        $this->remise = $remise;
    }

    /**
     * @return \DateTime
     */
    public function getDateDeComptabilisation(): \DateTime
    {
        return $this->dateDeComptabilisation;
    }

    /**
     * @param \DateTime $dateDeComptabilisation
     */
    public function setDateDeComptabilisation(\DateTime $dateDeComptabilisation)
    {
        $this->dateDeComptabilisation = $dateDeComptabilisation;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreationDoc(): \DateTime
    {
        return $this->dateCreationDoc;
    }

    /**
     * @param \DateTime $dateCreationDoc
     */
    public function setDateCreationDoc(\DateTime $dateCreationDoc)
    {
        $this->dateCreationDoc = $dateCreationDoc;
    }

    /**
     * @return string
     */
    public function getDateModificationDoc(): string
    {
        return $this->dateModificationDoc;
    }

    /**
     * @param string $dateModificationDoc
     */
    public function setDateModificationDoc(string $dateModificationDoc)
    {
        $this->dateModificationDoc = $dateModificationDoc;
    }

    /**
     * @return string
     */
    public function getUtilisateurDOrigine(): string
    {
        return $this->utilisateurDOrigine;
    }

    /**
     * @param string $utilisateurDOrigine
     */
    public function setUtilisateurDOrigine(string $utilisateurDOrigine)
    {
        $this->utilisateurDOrigine = $utilisateurDOrigine;
    }

    /**
     * @return int
     */
    public function getReferenceInterneDoc(): int
    {
        return $this->referenceInterneDoc;
    }

    /**
     * @param int $referenceInterneDoc
     */
    public function setReferenceInterneDoc(int $referenceInterneDoc)
    {
        $this->referenceInterneDoc = $referenceInterneDoc;
    }

    /**
     * @return string
     */
    public function getVille(): string
    {
        return $this->ville;
    }

    /**
     * @param string $ville
     */
    public function setVille(string $ville)
    {
        $this->ville = $ville;
    }

    /**
     * @return string
     */
    public function getModeDeCreation(): string
    {
        return $this->modeDeCreation;
    }

    /**
     * @param string $modeDeCreation
     */
    public function setModeDeCreation(string $modeDeCreation)
    {
        $this->modeDeCreation = $modeDeCreation;
    }

    /**
     * @return string
     */
    public function getDocumentComptabilise(): string
    {
        return $this->documentComptabilise;
    }

    /**
     * @param string $documentComptabilise
     */
    public function setDocumentComptabilise(string $documentComptabilise)
    {
        $this->documentComptabilise = $documentComptabilise;
    }

    /**
     * @return string
     */
    public function getSociete(): string
    {
        return $this->societe;
    }

    /**
     * @param string $societe
     */
    public function setSociete(string $societe)
    {
        $this->societe = $societe;
    }

    /**
     * @return int
     */
    public function getNumero(): int
    {
        return $this->numero;
    }

    /**
     * @param int $numero
     */
    public function setNumero(int $numero)
    {
        $this->numero = $numero;
    }


}

