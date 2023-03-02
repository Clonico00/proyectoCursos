<?php

namespace Lib;

class Pages
{

    public function render(string $pageName, array $params = null): void
    {
        if ($params != null) {
            extract($params);
        }
        require_once "../views/$pageName.php";
    }

}