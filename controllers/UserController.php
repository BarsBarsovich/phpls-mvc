<?php

namespace controllers;

use models\File\Files;
use models\User\Users;

class UserController extends Controller
{
    public function index()
    {
        if (!empty($_SESSION['user_id'])) {
//            header('Location: /profile');
            $this->renderView('upload');
        } else {
            $this->renderView('register');
        }
    }

    public function login()
    {
        $this->renderView('login');
    }

    // регистратция нового пользователя
    public function register()
    {
        try {
            if (!empty($_FILES['avatar'])) {
                $this->userData['avatar'] = $_FILES['avatar'];
            }
            $user = Users::createUser($this->userData);
            $message = 'Регистрация прошла успешно. Можете перейти на страницу входа в <a href="/user">Личный кабинет</a>';
        } catch (\Exception $exception) {
            $message = 'Не удалось зарегистрироваться из-за ошибки: ' . $exception->getMessage();
        } finally {
            $this->renderView('register');
        }
    }

    public function auth()
    {
        $userId = Users::checkUser($this->userData);
        if ($userId) {
            $_SESSION['user_id'] = $userId;
            $res = Users::getFilesCount($userId);
            echo 'Uploaded Per User ' . $res;
            $this->renderView('upload');
        } else {
            echo 'User not found';
        }
    }

    public function uploadFile()
    {
        try {
            if (!empty($_FILES['avatar'])) {
                $this->userData['avatar'] = $_FILES['avatar'];
//                Files::upload($_SESSION['user_id'],$_FILES);
                $file = new Files($_SESSION['user_id'], $this->userData['avatar']['name']);
                $file->saveFileToDb();
            }
        } catch (\Exception $exception) {

        }
    }
}
