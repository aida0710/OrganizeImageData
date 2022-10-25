<?php

namespace lazyperson0710\OrganizeImageData;



class Main {

    public function __construct() {
        require_once "./OrganizeImage.php";
        $app = new OrganizeImage();
        $app->execute();
    }

}

(new Main());