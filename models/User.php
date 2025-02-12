<?php
require_once("Profile.php");
require_once("data/ProfileDao.php");

/**
 * Represents a user
 */
class User
{
    private ?int $id = null;
    private ?string $email = null;
    private ?string $password = null; // mot_de_passe
    private ?Datetime $creationDate = null; // date_creation
    private ?Datetime $lastLoginDate = null; // date_derniere_connexion

    /**
     * Constructor taking parameters to hydrate the object
     */
    public function __construct($data)
    {
        $this->hydrate($data);
    }

    /**
     * Setter for the last name
     * @param string $lastName The user's last name
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Getter for the id
     * @return int The user's id
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Getter for the email
     * @return string The user's email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for the email
     * @param string $email The user's email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * Getter for the password
     * @return string The user's password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Setter for the password
     * @param string $password The user's password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * Getter for the creation date
     * @return Datetime The user's creation date
     */
    public function getCreationDate(): ?Datetime
    {
        return $this->creationDate;
    }

    /**
     * Setter for the last login date
     * @param Datetime $date The last login date
     */
    public function setCreationDateTime(Datetime $date)
    {
        $this->creationDate = $date;
    }

    /**
     * Setter de la date d'expiration du token grace à un string
     * @param string $expirationDate La date d'expiration du token
     */
    public function setCreationDateString(string $creationDate)
    {
        $this->creationDate = new DateTime(date($creationDate));
    }

    /**
     * Setter for the last login date
     * @param Datetime $date The last login date
     */
    public function getCreationDateString()
    {
        
        return $this->creationDate ? $this->creationDate->format('Y-m-d H:i:s') : '';
    }

    /**
     * Getter for the last login date
     * @return Datetime The user's last login date
     */
    public function getLastLoginDate(): ?Datetime
    {
        return $this->lastLoginDate;
    }

    /**
     * Setter for the last login date
     * @param Datetime $date The last login date
     */
    public function setLastLoginDateTime(Datetime $date)
    {
        $this->lastLoginDate = $date;
    }

    /**
     * Setter de la date d'expiration du token grace à un string
     * @param string $expirationDate La date d'expiration du token
     */
    public function setLastLoginDateString(?string $lastLoginDate)
    {
        if(isset($lastLoginDate) && !empty($lastLoginDate))
        {
            $this->lastLoginDate = new DateTime(date($lastLoginDate));
        }
    }
    /**
     * Setter for the last login date
     * @param Datetime $date The last login date
     */
    public function getLastLoginDateString()
    {
        return  $this->lastLoginDate ? $this->lastLoginDate->format('Y-m-d H:i:s') : '';
    }

     /**
     * Hydration function to set data from the array into the user
     */
    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}
?>