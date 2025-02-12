<?php
/**
 * Represents a user profile
 */
class Profile {
    private ?int $id = null;

    public function __construct($data) {
        $this->hydrate($data);
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function hydrate(array $data) {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    private function setId(int $id) {
        $this->id = $id;
    }

}
?>
