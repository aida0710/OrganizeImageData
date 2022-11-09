<?php

namespace lazyperson0710\OrganizeImageData\object;

use lazyperson0710\OrganizeImageData\object\questions\DeleteFilesBelowCapacity;
use lazyperson0710\OrganizeImageData\object\questions\DeleteOtherFiles;
use lazyperson0710\OrganizeImageData\object\questions\OrganizingType;

require_once "./object/questions/OrganizingType.php";
require_once "./object/questions/DeleteOtherFiles.php";
require_once "./object/questions/DeleteFilesBelowCapacity.php";

class Question {

    public function getQuestions(): array {
        return [
            new DeleteOtherFiles(),
            new DeleteFilesBelowCapacity(),
            new OrganizingType(),
        ];
    }

}