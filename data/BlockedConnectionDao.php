<?php

require_once("BaseDao.php");
require_once("models/BlockedConnection.php");

/**
 * Data access object for BlockedConnection.
 */
class BlockedConnectionDao extends BaseDao {

    /**
     * Retrieves a blocked connection by ID.
     * @param int $id The blocked connection ID.
     * @return ?BlockedConnection The retrieved blocked connection, or null if not found.
     */
    public function getById(int $id): ?BlockedConnection {
        $query = "SELECT id AS id, ip AS ipAddress FROM Connexion_bloquer WHERE id = :id";
        $params = [':id' => $id];
        $data = $this->execRequest($query, $params)->fetch();
        return $data ? new BlockedConnection($data) : null;
    }

    /**
     * Inserts a new blocked connection.
     * @param BlockedConnection $blockedConnection The blocked connection to insert.
     */
    public function insert(string $ip) {
        $query = "INSERT INTO Connexion_bloquer (ip) VALUES (:ip)";
        $params = [':ip' => $ip];
        $this->execRequest($query, $params);
    }

        /**
     * Retrieves a blocked connection by IP.
     * @param string $ip The IP address.
     * @return ?BlockedConnection The blocked connection, or null if not found.
     */
    public function getByIp(string $ip): ?BlockedConnection {
        $query = "SELECT id AS id, ip AS ipAddress FROM Connexion_bloquer WHERE ip = :ip";
        $params = [':ip' => $ip];
        $data = $this->execRequest($query, $params)->fetch();
        return $data ? new BlockedConnection($data) : null;
    }

    /**
     * Checks if a connection is blocked for a given IP in the last 25 minutes.
     * @param string $ip The IP address.
     * @return bool True if the connection is blocked within 25 minutes, false otherwise.
     */
    public function getOk(string $ip): bool {
        $query = "SELECT COUNT(*) FROM Connexion_bloquer WHERE ip = :ip AND TIMESTAMPDIFF(MINUTE, Connexion_bloquer.date_blocage, NOW() - INTERVAL 25 MINUTE) <= 25";
        $params = [':ip' => $ip];
        return $this->execRequest($query, $params)->fetchColumn() > 0;
    }

}

?>
