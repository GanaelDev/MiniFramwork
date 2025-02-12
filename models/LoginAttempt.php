<?php

/**
 * Represents a login attempt.
 */
class LoginAttempt {
    private ?int $id = null;
    private ?string $ipAddress = null;
    private ?Datetime $attemptDate = null;
    private ?string $email = null;

    /**
     * Constructor for LoginAttempt.
     * @param array $data An associative array of initial values for the model.
     */
    public function __construct(array $data = []) {
        $this->hydrate($data);
    }

    /**
     * Hydrates the object with the provided data.
     */
    public function hydrate(array $data) {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    // Getters and setters for each property

    public function getId(): ?int {
        return $this->id;
    }

    public function getIpAddress(): ?string {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress) {
        $this->ipAddress = $ipAddress;
    }

    public function getAttemptDate(): ?Datetime {
        return $this->attemptDate;
    }

    public function setAttemptDate(Datetime $date) {
        $this->attemptDate = $date;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }
}

?>
