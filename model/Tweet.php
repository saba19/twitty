<?php
/**
 * Created by PhpStorm.
 * User: karolina
 * Date: 01.11.17
 * Time: 17:12
 */

class Tweets
{
    private $id;
    private $userId;
    private $text;
    private $creationDate;


    public function __construct()
    {
        $this->id =-1;
        $this->userId = '';
        $this->text = '';
        $this->creationDate= (new \DateTime("Y-m-d H:i:s"));
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
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param mixed $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }


    static public function loadTweetById(\PDO $conn, $id){
        $stmt= $conn->prepare('SELECT * FROM Tweets WHERE id=:id');
        $res= $stmt->execute(['id'=>$id]);
        if ($res && $stmt->rowCount()>0) {
            $row=$stmt->fetch();
            $tweets = new Tweets();
            $tweets->id = $row ["id"];
            $tweets->userId= $row ["userId"];
            $tweets->setText($row["text"]);
            $tweets->setCreationDate($row["creationDate"]);
            return $tweets;
        }
        return null;

    }


    static public function loadAllTweetsByUserId(\PDO $conn, $userId){
        $stmt= $conn->query('SELECT userId, text, creationDate FROM `Tweets` JOIN User ON Tweets.userId = User.id WHERE userId=:userId');
        $res=[];
        foreach ($stmt->fetchAll() as $row){
            $tweets= new Tweets();
            $tweets->id= $row["id"];
            $tweets->userId=$row["userId"];
            $tweets->setText($row["text"]);
            $tweets->setCreationDate($row["creationDate"]);
            $res[]= $tweets;
        }
        return $res;
    }



    static public function loadAllTweets(\PDO $conn) {
        $stmt= $conn->query('SELECT * FROM `Tweets`');
        $res=[];
        foreach ($stmt->fetchAll() as $row) {
            $tweets= new Tweets();
            $tweets->id= $row["id"];
            $tweets->userId=$row["userId"];
            $tweets->setText($row["text"]);
            $tweets->setCreationDate($row["creationDate"]);
            $res[]= $tweets;
        }
        return $res;

    }



    public function saveToDB(\PDO $conn){
        if (!$this->getId()){
            $stmt= $conn->prepare('INSERT INTO `Tweets` (`userId`, `text`, `creationDate`)
            VALUES ( :userId, :text, :creationDate)'
            );
            $res= $stmt->execute([
                'userId'=>$this->getUserId(),
                'text'=> $this->getText(),
                'creationDate'=>$this->getCreationDate()
            ]);
            if ($res!==false){
                $this->id = $conn->lastInsertId();
                return true;
            }

        } else {
            $stmt= $conn->prepare ('UPDATE Tweets SET userId=:userId, text=:text, creatinDate=:creationDate WHERE id=:id');
            $res = $stmt->execute([
                'userId'=>$this->getUserId(),
                'text'=> $this->getText(),
                'creationDate'=>$this->getCreationDate(),
                'id'=> $this->getId()
            ]);
            return(bool) $res;
        }
        return false;
    }







}