<?php

require_once("BaseDao.php");
require_once("models/User.php");

/**
 * DAO for Users
 * Provides methods for fetching, inserting, updating, and deleting users.
 */
class UserDao extends BaseDao
{
    /**
     * Fetch all Users
     * @return array An array of User objects
     */
    public function getAll(): array
    {
        $query = "SELECT id as Id, nom as lastName, prenom as firstName, email, telephone as phone, mot_de_passe as password, date_creation as creationDateString, date_derniere_connexion as lastLoginDateString, id_profile as profileId FROM Utilisateurs;";
        $datas = $this->execRequest($query, null);
        $result = [];

        foreach ($datas as $data) {
            array_push($result, new User($data));
        }
        return $result;
    }

    /**
     * Fetch a User by their ID
     * @param int $id The user's ID
     * @return ?User The User object or null if not found
     */
    public function getById(int $id): ?User
    {
        $query =  "SELECT id as Id, nom as lastName, prenom as firstName, email, telephone as phone, mot_de_passe as password, date_creation as creationDateString, date_derniere_connexion as lastLoginDateString, id_profile as profileId FROM Utilisateurs WHERE id = :id;";
        $param = [':id' => $id];
        $datas = $this->execRequest($query, $param);
        $result = null;

        if ($data = $datas->fetch()) {
            $result = new User($data);
        }
        return $result;
    }

    /**
     * Insert a new User
     * @param User $user The user to insert
     */
    public function insert(User $user)
    {
        $query = 'INSERT INTO Utilisateurs ( email,  mot_de_passe) VALUES ( :email, :hashed_password);';
        $param = [
            ':email' => $user->getEmail(),
            ':hashed_password' => $user->getPassword(),
        ];

        $this->execRequest($query, $param);
    }

    /**
     * Delete a User
     * @param int $id The user's ID
     * @return bool True if at least one row was affected
     */
    public function delete(int $id): bool
    {
        $query = "DELETE FROM Utilisateurs WHERE id = :id;";
        $param = [':id' => $id];
        $result = $this->execRequest($query, $param);
        return $result->rowCount() > 0;
    }

    /**
     * Update a User
     * @param User $user The user to update
     * @return bool True if at least one row was affected
     */
    public function update(User $user): bool
    {
        $query = "UPDATE Utilisateurs SET email = :email, mot_de_passe = :hashed_password WHERE id = :id;";
        $param = [
            ':id' => $user->getId(),
            ':email' => $user->getEmail(),
            ':hashed_password' => $user->getPassword(),
        ];

        $result = $this->execRequest($query, $param);
        return $result->rowCount() > 0;
    }


    public function getPaginate(int $numPerPage = 50, int $page = 0): array
    {

        $query = "SELECT * FROM Utilisateurs LIMIT :numPerPage OFFSET :notshow;";
        $param = [
            ':numPerPage' => $numPerPage,
            ':notshow' => $numPerPage * $page,
        ];
        $datas = $this->execRequest($query, $param);
        $result = [];

        foreach ($datas as $data) {
            array_push($result, new User($data));
        }
        return $result;
    }

    /**
     * Récupère un utilisateur par son nom d'utilisateur et son mot de passe.
     * 
     * @param string $username Le nom d'utilisateur de l'utilisateur.
     * @param string $password Le mot de passe de l'utilisateur.
     * @return ?User L'utilisateur correspondant ou null s'il n'est pas trouvé.
     */
    public function getByLogin(string $username, string $password): ?User
    {
        $query = "SELECT * FROM Utilisateurs WHERE email = :username";
        $params = [':username' => $username];
        $result = $this->execRequest($query, $params);
        $user = $result->fetch();
        if ($user &&  hash('sha256',$password) == $user['password'] )
        {
            return new User($user);
        }

        return null;
    }
}