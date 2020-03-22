<?php

namespace controllers;

use models\User\Users;

class AdminController extends Controller
{
    public $userList;

    public function index()
    {
        $this->renderView('admin');
    }

    public function getFilesByUser(int $userId)
    {
        $res = Users::getFilesCount($userId);
        return $res;
    }

    public function users($sortOrder = 'asc')
    {
        $result = Users::getAllUsers($sortOrder);
        $this->renderView('admin', ['users' => $this->changeResult($result), 'userFiles'=> $this->getFilesByUser($_SESSION['user_id'])]);
    }

    public function usersdesc($sortOrder = 'desc')
    {
        $result = Users::getAllUsers($sortOrder);
        $this->userList = $this->changeResult($result);
        $this->renderView('admin', ['users' => $this->changeResult($result)]);

    }

    private function changeResult($result)
    {
        for ($i = 0; $i < count($result); $i++) {
            if ((int)$result[$i]['age'] < 18) {
                $result[$i]['age'] = 'Несовершеннолетний';
            } else {
                $result[$i]['age'] = 'совершеннолетний';
            }
        }

        return $result;
    }
}
