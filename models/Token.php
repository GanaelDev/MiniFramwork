<?php
    require_once("User.php");
    require_once("data/UserDao.php");


    /**
     * Représente un token
     */
    class Token{
        private ?string $token = null;
        private ?Datetime $expirationDate = null;
        private ?int $idUser = null;

        /**
         * Constructeur prenant en paramètres les données pour les hydrater
         */
        public function __construct($data = []) {
            $this->hydrate($data);
        }

        /**
         * Getter du token
         * @return string Le token
         */
        public function getToken() : ?string{
            return $this->token;
        }
        /**
         * Setter du token
         * @param string $token Le token
         */
        public function setToken(string $token){
            $this->token = $token;
        }

        /**
         * Getter de la date d'expiration du token
         * @return Datetime La date d'expiration du token
         */
        public function getExpirationDate() : ?Datetime{
            return $this->expirationDate;
        }
        /**
         * Getter de la date d'expiration du token en string
         * @return string La date d'expiration du token en string
         */
        public function getExpirationDateString() : ?string{
            return $this->expirationDate->format('Y-m-d H:i:s');
        }

        /**
         * Setter de la date d'expiration du token
         * @param Datetime $expirationDate La date d'expiration du token
         */
        public function setExpirationDateTime(Datetime $expirationDate){
            $this->expirationDate = $expirationDate;
        }
        /**
         * Setter de la date d'expiration du token grace à un string
         * @param string $expirationDate La date d'expiration du token
         */
        public function setExpirationDate(string $expirationDate){
            $this->expirationDate = New DateTime(date($expirationDate));
        }

        /**
         * Getter de l'utilisateur du token
         * @return User L'utilisateur du token
         */
        public function getUser() : User{
            $userDao = new UserDao();
            $user = $userDao?->getById($this->idUser); // Get l'objet User dans la BD avec l'id

            return $user;
        }

        /**
         * Getter l'id de l'utilisateur du token
         * @return int l'id de L'utilisateur du token
         */
        public function getIdUser() : int{
            return $this->idUser;
        }

        /**
         * Setter de l'utilisateur du token, set l'id de l'utilisateur seulement
         * @param int $user L'id de l'utilisateur du token
         */
        public function setIdUser(?int $idUser){
            $this->idUser = $idUser;
        }

        /**
         *  Fonction d'hydratation, met les données de l'array dans le token
         */
        public function hydrate(array $donnees){
            foreach($donnees as $key => $value){
                $method = 'set'.ucfirst($key);
                if(method_exists($this, $method)){
                    $this->$method($value);
                }
            }
        }

        /**
         * Dis si le token est valide
         * @return bool true s'il n'a pas expiré et false
         */
        public function Valide():bool
        {
            $now = new DateTime(date("Y-m-d H:i:s"));//string  (le format DATETIME de MySQL)
            return $this->getExpirationDate() > $now;     
        }

        /**
         * Augmente la date d'expiration de 10min
         */
        public function UpDate()
        {
            $now = new DateTime(date("Y-m-d H:i:s"));
            $this->setExpirationDateTime($now->modify("+10 minutes"));
        }
    }

?>