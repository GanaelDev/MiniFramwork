<?php
    include("config.php");
    /**
     * Base des DAO qui à un PDO et les méthodes pour executé le sql
     */
    class BaseDao{

        private ?PDO $pdo = null;
            
        /**
         * Execute une requetes avec une seule valeurs retournée
         * @param string $req La requete sql prête à paramétré
         * @param array $params Les paramètres dans un tableaux
         * @return mixed Les données retournées par la BD dans un tableau associtif
         */
        public function queryOne(string $req, array $params)
        {
            $data=null;
            $r = $this->getDB()->prepare($req);
            $r->execute($params);
            $data = $r->fetch(PDO::FETCH_ASSOC);
            return $data;
        }

        /**
         * Execute une requetes avec plusieurs valeurs retournée
         * @param string $req La requete sql prête à paramétré
         * @param array $params Les paramètres dans un tableaux
         * @return mixed Les données retournées par la BD dans un tableau associtif
         */
        public function queryAll(string $req, array $params)
        {
            $data=null;
            $r = $this->getDB()->prepare($req);
            $r->execute($params); 
            $data = $r->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }

        /**
         * Execute une requete SQL (sans retour)
         * @param string $req La requete sql prête à paramétré
         * @param array $params Les paramètres dans un tableaux
         */
        public function execute(string $req, array $params)
        {
            $r = $this->getDB()->prepare($req);
            $r->execute($params);
        }

        protected function execRequest(string $sql, array $params = null) : PDOStatement
        {
            if($params == null)
            {
                $res = $this->getDB()->query($sql);
            }
            else{
                $res = $this->getDB()->prepare($sql);
                $res->execute($params);
            }
            return $res;
        }
        
        /**
         * La création du du PDO s'il est null
         * @return PDO le PDO créer en attribut
         */
        private function getDB():PDO
        {
            if($this->pdo == null)
            {
                include("config.php");
                $this->pdo = new PDO("mysql:host=$server;dbname=$db",$user,$password);
                $this->pdo->exec("SET AUTOCOMMIT=1;SET GLOBAL event_scheduler = ON;");
            }
            return $this->pdo;
        }
    }

?>