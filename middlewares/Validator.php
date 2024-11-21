<?php

require_once("models/User.php");
require_once("data/UserDao.php");
require_once("models/Token.php");
require_once("data/TokenDao.php");
require_once("data/BlockedConnectionDao.php");
require_once("data/LoginAttemptDao.php");



/*
une fois que l'utilisateur appuis sur la login
On a le login et mdp et on veut le User et le Token
*/
class Validator
{

    /**
     * Demande au dao si l'utilisateur existe et le renvoie
     * @param string Le nom de l'utilisateur saisie
     * @param string Le mot de passe en claire
     * @return User L'utilisateur ou null si couple pas dans la bdd
     */
    private function TestLogin(string $Username, string $Password): ?User
    {
        include("config.php");
        $hash = $Password . $salt;
        $dao = new UserDao();
        return ($dao->getByLogin($Username, $hash));
    }

    /**
     * Dis si l'ip est bloqué 
     * @param string L'ip
     * @return bool True si l'ip est autoriser, False si l'ip est bloqué
     */
    private function TestIp(string $ip): bool
    {
        include("config.php");
        if (session_status() != 2) {
            session_start();
        }
        $res = true;
        $daoBlocking = new BlockedConnectionDao();
        $daoLoginAttempt = new LoginAttemptDao();
        if (isset($_SESSION['Blocked']) && $res) {
            $res = false;
        }
        // BLOCKING 
        else if ($res && $daoBlocking->getOk($ip)) {
            $res = false;

        }
        // FREQUENCY
        else if ($res && $daoLoginAttempt->getOk($ip)) {
            $daoBlocking->insert($ip);
            $res = false;

        }
        return $res;
    }

    /**
     * Insert la tentative de connexion
     * 
     */
    private function insertTentativeIp(string $ip, string $username)
    {
        $dao = new LoginAttemptDao();
        $dao->insert($ip, $username);
    }

    /**
     * Grace au jeton stocker en session on va chercher en bdd le token complet
     * @param string le jeton
     * @return Token Le token ou null si couple pas dans la bdd
     */
    private function TestToken(string $token): ?Token
    {
        $dao = new TokenDao();
        return ($dao->getByID($token));
    }

    /**
     * Générer le token et l'initialise en plus de l'ajouté en bdd
     * @param int l'id de l'utilisateur pour le token
     * @return Token Le token créer
     */
    private function Connexion(int $idUser): Token
    {
        $expirationDate = new DateTime(date("Y-m-d H:i:s"));
        $expirationDate->modify('+10 minutes');
        $res = new Token();
        $res->setToken(hash("sha256", session_id()));
        $res->setExpirationDateTime($expirationDate);
        $res->setIdUser($idUser);

        $dao = new TokenDao();

        $dao->insert($res);

        return $res;
    }

    /**
     * Met à jour l'expiration du token et en bdd
     * @param Token le token qui sera à mettre à jour
     * @return Token le token modifier si on l'as bien modifier
     */
    public function Update(Token $token): ?Token
    {
        $token->UpDate();

        $dao = new TokenDao();
        $count = $dao->Update($token);

        $res = null;
        if ($count) {
            $res = $token;
        }
        return $res;
    }

    /**
     * Nous dis si oui ou non on peut effectuer l'action
     * vérifie le token en session puis si rien en session en bdd 
     * @return bool true s'il à le droit et false sinon
     */
    public function ValidateToken(): bool
    {
        $res = false;
        /*
            j'ouvre la session 
            je prend son id 
            je regarde le token en bdd / en session
            si token valide
            update de 10min
            et on renvoie sur l'action demandé   TRUE
        */
        if (session_status() != 2) {
            session_start();
        }
        $jeton = null;
        if (isset($_SESSION['Token'])) {
            $jeton = $_SESSION['Token'];
            if ($jeton != $this->TestToken($jeton->getToken())) {
                $jeton = null;
            }
        } else {
            $temp = $this->TestToken(hash("sha256", session_id()));
            if (isset($temp)) {
                $jeton = $temp;
            }
        }
        if (isset($jeton) && $jeton->Valide()) {
            $this->Update($jeton);
            $res = true;
        }
        return $res;
    }

    /**
     * Nous dis si oui ou non on peut se connecté avec un username et un mot de passe
     * as généré le token de l'utilisateur
     * Va set les variables de sessions 
     * @param string le nom de compte de l'utilisateur
     * @param string le mot de passe de l'utilsiateur
     * @return bool true s'il à le droit et false sinon
     */
    public function ValidateUser(string $username, string $password): bool
    {
        $res = false;
        $ip = $_SERVER['REMOTE_ADDR'];
        $Ip_ok = $this->TestIp($ip);

        if (isset($username) && isset($password) && $Ip_ok) {
            
            /*
            session_start();
            je stock le user dans la session
            je génère le token
            je regénère l'id
            je le stock dans la sessions le token
            on renvoie vers la page d'acceuil           TRUE
            */
            $user = $this->TestLogin($username, $password);

            if ($user?->getId() != null) {
                if (session_status() != 2) {
                    session_start();
                }
                session_regenerate_id();
                $token = $this->Connexion($user->getId());
                $_SESSION['User'] = $user;
                $_SESSION['Token'] = $token;
                
                $res = true;
            } else {
               $this->insertTentativeIp($ip, $username);
            }
        } else if (!$Ip_ok) {
            $_SESSION['Blocked'] = true;
        }

        return $res;
    }
}
// la session coté client éxpire à la fermeture du navigateur et dans 24min coté serveur

/*
je vérifie si je reçois des strings, 
    si oui
        je test username et mdp
        si == null 
            on renvoie sur le index.php (page de login avec un message en rouge)     FALSE
        sinon
            session_start();
            je stock le user dans la session
            je génère le token
            je regénère l'id
            je le stock dans la sessions le token
            on renvoie vers la page d'acceuil           TRUE
    si non
        j'ouvre la session 
        je prend son id 
        je regarde le token en bdd / en session
        si token valide
            update de 10min
            et on renvoie sur l'action demandé   TRUE
        sinon
            on renvoie sur la page de connexion   FALSE




*/



?>