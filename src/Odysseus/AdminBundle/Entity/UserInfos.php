<?php

namespace Odysseus\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserInfos
 *
 * @ORM\Table(name="user_infos", indexes={@ORM\Index(name="fk_user_infos_User1_idx", columns={"User_id"}), @ORM\Index(name="fk_user_infos_country1_idx", columns={"country_id"})})
 * @ORM\Entity
 */
class UserInfos
{
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
     * @ORM\Column(name="civility", type="string", length=45, nullable=false)
     */
    private $civility;
    
    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=256, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=256, nullable=false)
     */
    private $lastName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=100, nullable=true)
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="address1", type="string", length=256, nullable=false)
     */
    private $address1;

    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=256, nullable=true)
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=45, nullable=false)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=45, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=45, nullable=true)
     */
    private $telephone;
    
    /**
     * @var string
     *
     * @ORM\Column(name="mobile_phone", type="string", length=45, nullable=false)
     */
    private $mobilePhone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime", nullable=false)
     */
    private $modifiedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=false)
     */
    private $isDefault = false;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_builling", type="boolean", nullable=false)
     */
    private $isBuilling = false;

    /**
     * @var \Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    private $country;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="infos", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="User_id", referencedColumnName="id")
     * })
     */
    private $user;



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
     * Set firstName
     *
     * @param string $firstName
     * @return UserInfos
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return UserInfos
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set address1
     *
     * @param string $address1
     * @return UserInfos
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string 
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     * @return UserInfos
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string 
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     * @return UserInfos
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string 
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return UserInfos
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return UserInfos
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return UserInfos
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     * @return UserInfos
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime 
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set isDefault
     *
     * @param integer $isDefault
     * @return UserInfos
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return integer 
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Set isBuilling
     *
     * @param integer $isBuilling
     * @return UserInfos
     */
    public function setIsBuilling($isBuilling)
    {
        $this->isBuilling = $isBuilling;

        return $this;
    }

    /**
     * Get isBuilling
     *
     * @return integer 
     */
    public function getIsBuilling()
    {
        return $this->isBuilling;
    }

    /**
     * Set country
     *
     * @param \Odysseus\AdminBundle\Entity\Country $country
     * @return UserInfos
     */
    public function setCountry(\Odysseus\AdminBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Odysseus\AdminBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set user
     *
     * @param \Odysseus\AdminBundle\Entity\User $user
     * @return UserInfos
     */
    public function setUser(\Odysseus\AdminBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Odysseus\AdminBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set civility
     *
     * @param string $civility
     * @return UserInfos
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;

        return $this;
    }

    /**
     * Get civility
     *
     * @return string 
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * Set mobilePhone
     *
     * @param string $mobilePhone
     * @return UserInfos
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    /**
     * Get mobilePhone
     *
     * @return string 
     */
    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return UserInfos
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }
    
    public function getFullName(){
        return ucfirst($this->firstName).' '.strtoupper($this->lastName);
    }
}
