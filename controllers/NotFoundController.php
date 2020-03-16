<?php

namespace controllers;

class NotFoundController extends Controller
{
    public function index(){
        $this->renderView('404');
    }
}