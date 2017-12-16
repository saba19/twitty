<?php
class User
{
    private $id;
    private $email;
    private $pass;

    public function __construct() {
        $this->id = -1;
        $this->email = "";
        $this->pass = "";
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getPass()
    {
        return $this->pass;
    }
    /**
     * This converts plain password to hashed
     *
     * @param mixed $pass
     * @return User
     */
    public function setPass($pass)
    {
        $this->pass = password_hash($pass,PASSWORD_BCRYPT,['cost'=>11]);
        return $this;
    }
    /**
     * this do no apply hashing
     *
     * @param $pass
     * @return User
     */
    public function setDirectPass($pass)
    {
        $this->pass = $pass; return $this;
    }

    public function saveToDB(PDO $connection) {
        if ($this->id == -1) {
            //Saving new user to DB
            $stmt = $connection->prepare(
                'INSERT INTO User (email, pass) 
                          VALUES (:email, :pass)'
            );
            $result = $stmt->execute([
                'email' => $this->getEmail(),
                'pass' => $this->getPass()
            ]);

            if ($result == true) {
                $this->id = $connection->lastInsertId();
                return true;
            } else {
                return false;
            }
        } else {
            $stmt =$connection->prepare(
                'UPDATE User SET email=:email, pass=:pass
                WHERE id=:id'
            );
            $result = $stmt->execute([
                'email' => $this->getEmail(),
                'pass' => $this->getPass(),
                'id' => $this->getId()
            ]);
            return (bool) $result;
        }
        return false;
    }

    static public function loadById(PDO $connection, $id) {
        $stmt = $connection->prepare('SELECT * FROM User WHERE id=:id');
        $result = $stmt->execute(['id'=>$id]);
        if($result && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->pass = $row['pass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }

    public function delete(PDO $connection) {
        if($this->getId()) {
            $stmt = $connection->prepare('DELETE FROM User WHERE id=:id');
            $result = $stmt->execute(['id'=>$this->getId()]);
            if($result) {
                $this->id = -1;
                return true;
            }
        }
        return false;
    }



    static public function loadByEmail(PDO $connection, $email) {
        $stmt = $connection->prepare('SELECT * FROM User WHERE email=:email');
        $res = $stmt->execute(['email'=>$email]);
        if($res && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->pass = $row['pass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }

    static public function login(PDO $connection, $email, $pass) {
        $stmt = "SELECT * FROM User WHERE email=:email";
        $res = $connection->prepare($stmt);
        $result = $res->execute([ 'email' => $email ]);
        if($result === true && $res->rowCount() > 0){
            $row = $res->fetch(PDO::FETCH_ASSOC);
            // verify if password is correct
            if(password_verify($pass, $row['pass'])) {
                $info = [
                    'id' => $row['id'],
                    'email' => $row['email']
                ];
                return $info;
            }
        }
        return false;
    }


    static public function getUserByEmail(PDO $connection, $email) {
        $stmt = $connection->prepare('SELECT * FROM User WHERE email=:email');
        $res = $stmt->execute(['email'=>$email]);
        if($res && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $user = new User();
            $user->setEmail($row['email']);
            $user->setPass($row['pass']);
            return $user;
        } else {
            return false;
        }
    }



}