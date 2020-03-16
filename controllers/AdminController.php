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
        $this->changeResult($result);

        // Вот так почему то результат не поменялся
//        foreach ($result as $row) {
//            if ((int)$row['age'] < 18) {
//                echo 'less 18';
//                $result['age'] = 'Несовершеннолетний';
//            } else {
//                echo 'more 18';
//                $result['age'] = 'совершеннолетний';
//            }
//        }
        echo '<pre>';
        print_r($result);
    }

    public function usersdesc($sortOrder = 'desc')
    {
        $result = Users::getAllUsers($sortOrder);
        $this->changeResult($result);
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

        $this->userList = $result;
    }
}
