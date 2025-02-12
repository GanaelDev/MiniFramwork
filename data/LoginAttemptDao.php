<?php

require_once("BaseDao.php");
require_once("models/LoginAttempt.php");

/**
 * Data access object for LoginAttempt.
 */
class LoginAttemptDao extends BaseDao {

    /**
     * Retrieves a login attempt by ID.
     * @param int $id The login attempt ID.
     * @return ?LoginAttempt The retrieved login attempt, or null if not found.
     */
    public function getById(int $id): ?LoginAttempt {
        $query = "SELECT id AS id, ip AS ipAddress, date_tentative AS attemptDate, email AS email FROM Tentative_Connexion WHERE id = :id";
        $params = [':id' => $id];
        $data = $this->execRequest($query, $params)->fetch();
        return $data ? new LoginAttempt($data) : null;
    }

    /**
     * Inserts a new login attempt.
     * @param LoginAttempt $loginAttempt The login attempt to insert.
     */
    public function insert($ip, $email) {
        $query = "INSERT INTO Tentative_Connexion (ip, email) VALUES (:ip, :email)";
        $params = [
            ':ip' => $ip,
            ':email' => $email
        ];
        $this->execRequest($query, $params);
    }

    /**
     * Retrieves login attempts by IP.
     * @param string $ip The IP address.
     * @return ?LoginAttempt The login attempt, or null if not found.
     */
    public function getByIp(string $ip): ?LoginAttempt {
        $query = "SELECT id AS id, ip AS ipAddress, date_tentative AS attemptDate, email AS email FROM Tentative_Connexion WHERE ip = :ip";
        $params = [':ip' => $ip];
        $data = $this->execRequest($query, $params)->fetch();
        return $data ? new LoginAttempt($data) : null;
    }

    /**
     * Checks if an IP has 3 or more failed login attempts in the last 5 minutes.
     * @param string $ip The IP address.
     * @return bool True if there are 3 or more failed attempts within 5 minutes, false otherwise.
     */
    public function getOk(string $ip): bool {
        $query = "SELECT COUNT(*) FROM Tentative_Connexion WHERE ip = :ip AND date_tentative >= NOW() - INTERVAL 5 MINUTE";
        $params = [':ip' => $ip];
        return $this->execRequest($query, $params)->fetchColumn() >= 3;
    }

}

?>
