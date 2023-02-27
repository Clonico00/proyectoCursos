<?php

namespace Controllers;

use Lib\Pages;
use Models\Ponente;

class ApiPonenteController
{
    private Ponente $ponente;
    private Pages $pages;

    public function __construct()
    {
        $this->ponente = new Ponente();
        $this->pages = new Pages();
    }



}