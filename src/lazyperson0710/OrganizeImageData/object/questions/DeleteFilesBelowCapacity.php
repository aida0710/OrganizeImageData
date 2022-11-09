<?php

declare(strict_types = 1);
namespace lazyperson0710\OrganizeImageData\object\questions;

use lazyperson0710\OrganizeImageData\object\Setting;

require_once "./object/Setting.php";

class DeleteFilesBelowCapacity extends Setting {

    public function getName(): string {
        return "DeleteFilesBelowCapacity";
    }

    public function getFirstMessage(): array {
        return [
            "指定容量以下のファイルを削除しますか？",
            "入力された容量はkb単位で指定されます",
            "削除が不要な場合は0を入力してください",
            "例: 100 = 100kb以下削除",
        ];
    }

    public function getQuestionMessage(): string {
        return "容量を入力してください(単位:kb): ";
    }

    public function getAnswerFilter(): array {
        return [];
    }

    public function getAnswerType(): string {
        return "int";
    }

    public function execution(string|int $answer): void {
        return;
    }

}