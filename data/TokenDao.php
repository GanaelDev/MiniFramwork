<?php

    require_once("BaseDao.php");
    require_once("models/Token.php");

    /**
     * DAO des Tokens
     * Avec le getall, getbyid, delete, insert, Update
     */
    class TokenDao extends BaseDao
    {
        /**
         * Get all des Tokens
         * @return array Un tableau avec tout les Jetons 
         */
        public function getAll():array
        {
            $q = "SELECT jeton as token, date_expiration as expirationDate, id_utilisateur as IdUser  FROM Jetons;";
            $datas = $this->execRequest($q,null);
            $res = [];
            foreach($datas as $data)
            {
                array_push($res,new Token($data));
            }
            return $res;
        }

        /**
         * GetById des Jetons donne tout sur un Token grace à son jeton 
         * @param string l'id du Token
         * @return ?Token Le Token complet s'il est dedans sinon null
         */
        public function getByID(string $token):?Token
        {
            $q = "SELECT jeton as token, date_expiration as expirationDate, id_utilisateur as IdUser FROM Jetons WHERE jeton = :token;";
            $param = array();
            $param[':token'] = $token;
            $donnees = $this->execRequest($q,$param);
            $res = null;
            if($donnee = $donnees->fetch())
            {
                $res = new Token($donnee);
            }
            return $res;
        }

        /**
         * Insertion d'un nouveaux Token
         * @param Token le Token à ajouté
         */
        public function insert(Token $token)
        {
            $q = 'INSERT INTO Jetons(jeton, date_expiration , id_utilisateur) VALUES (:token,:ExpirationDate,:idUser);';
            $param = array();
            $param[':token'] = $token->getToken();
            $param[':ExpirationDate'] = $token->getExpirationDateString();
            $param[':idUser'] = $token->getIdUser();

            $donnees = $this->execRequest($q,$param);
        }

        /**
         * Suppression d'un Token
         * @param string le jeton du Token à supprimer
         * @return bool true si au moins une ligne est affecter
         */
        public function Delete(string $token): bool
        {
            $q = "DELETE FROM Jetons WHERE jeton  = :token";
            $param = array();
            $param[':token'] = $token;
            $donnees = $this->execRequest($q,$param);
            return $donnees->rowCount() > 0;
        }

        /**
         * Update d'un Token change que la date d'expiration
         * @param Token le Token modifier
         * @return bool true si au moins une ligne est affecter
         */
        public function Update(Token $token): bool
        {
            $q = "UPDATE Jetons SET date_expiration = :ExpirationDate WHERE jeton = :token";
            $param = array();
            
            $param[':token'] = $token->getToken();
            $param[':ExpirationDate'] = $token->getExpirationDateString();

            $donnees = $this->execRequest($q,$param);

            return $donnees->rowCount() > 0;

        }

        
        /**
         * Suppression de tous les Jetons d'un utilisateur
         * @param int l'id de l'utilisateur
         * @return bool true si au moins une ligne est affecter
         */
        public function DeleteAllUser(int $iduser): bool
        {
            $q = "DELETE FROM Jetons WHERE id_utilisateur  = :idUser";
            $param = array();
            $param[':idUser'] = $iduser;
            $donnees = $this->execRequest($q,$param);
            return $donnees->rowCount() > 0;
        }
    }

?>