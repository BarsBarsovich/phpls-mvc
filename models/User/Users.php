<?php

namespace models\User;

use models\File\Files;
use PDO;
use PDOException;

class Users
{
    const ADULT_AGE = 18;
    protected $table = 'users';


    public static function createUser($data)
    {
        $user = new self();
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->name = !empty($data['name']) ? $data['name'] : '';
        $user->age = $data['age'];
        $user->avatar = $data['avatar'];
        try {
            self::addUserToDatabase($user);

        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die;
        }
    }

    public static function checkUser($data)
    {
        $user = new self();
        $user->email = $data['email'];
        $user->password = $data['password'];
        $userId = self::getUserFromDatabase($user);
        return $userId;
    }


    private static function getUserFromDatabase($user)
    {
        $pdo = new PDO('mysql://localhost:3306/mvc', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $query = $pdo->prepare("select id from mvc.users where email=:email and password = :pass");

        $query->execute(['email' => $user->email, 'pass' => $user->password]);
        $res = $query->fetch();
        return $res['id'];

    }

    private static function addUserToDatabase($user)
    {
        $pdo = new PDO('mysql://localhost:3306/mvc', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $query = $pdo->prepare("insert into mvc.users (name, email, age, password) values (:name, :email, :age, :password)");
        $query->execute([
            'name' => $user->name,
            'email' => $user->email,
            'age' => $user->age,
            'password' => $user->password
        ]);
        $res = $pdo->query('select last_insert_id() as id')->fetch();
        $userId = $res['id'];


        if ($user->avatar) {
            $files = new Files($userId, $user->avatar['name']);
            $files->saveFileToDb();
        }
    }

    public static function getFilesCount($userId)
    {
        $pdo = new PDO('mysql://localhost:3306/mvc', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $query = $pdo->prepare("select count(id) as count from mvc.files where user_id= :userId");
        $query->execute(['userId' => $userId]);
        $res = $query->fetch()['count'];
        return $res;
    }

    public static function getAllUsers($sortMode)
    {
        $pdo = new PDO('mysql://localhost:3306/mvc', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $queryResult;
        if ($sortMode === 'asc') {
            $queryResult = $pdo->query('select * from mvc.users order by age asc')->fetchAll();
        } else {
            $queryResult = $pdo->query('select * from mvc.users order by age desc')->fetchAll();
        }

        return $queryResult;
    }
}