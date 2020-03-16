<?php
namespace controllers;

class IndexController extends Controller
{
    public function index()
    {
        if(!empty($_SESSION['user_id'])) {
            header('Location: /profile');
        } else {
            $this->renderView('index');
        }
    }
}