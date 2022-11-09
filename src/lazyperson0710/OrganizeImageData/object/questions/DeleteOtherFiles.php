<?php

declare(strict_types = 1);
namespace lazyperson0710\OrganizeImageData\object\questions;

use lazyperson0710\OrganizeImageData\io\ImageFileManagement;
use lazyperson0710\OrganizeImageData\object\Setting;
use lazyperson0710\OrganizeImageData\OrganizeImage;

require_once "./object/Setting.php";

class DeleteOtherFiles extends Setting {

    public const Extensions = [
        'jpg',
        'jpeg',
        'png',
    ];

    public function getName(): string {
        return "DeleteOtherFiles";
    }

    public function getFirstMessage(): array {
        return [];
    }

    public function getQuestionMessage(): string {
        return implode(",", self::Extensions) . "以外のファイルを削除しますか？(y/n): ";
    }

    public function getAnswerFilter(): array {
        return [
            "y",
            "yes",
            "n",
            "no",
        ];
    }

    public function getAnswerType(): string {
        return "string";
    }

    public function execution(string|int $answer): void {
        switch ($answer) {
            case "y":
            case "yes":
                $answer = true;
                break;
            case "n":
            case "no":
                $answer = false;
                break;
        }
        if ($answer) {
            OrganizeImage::getInstance()->line(implode(",", self::Extensions) . "以外のファイル削除を開始しました...");
            ImageFileManagement::getInstance()->checkImagesFolder();
            $list = ImageFileManagement::getInstance()->imageDirectoryList;
            $count = 0;
            foreach ($list as $key => $file) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                if (!in_array(mb_strtolower($extension), self::Extensions)) {
                    unlink($file);
                    unset(ImageFileManagement::getInstance()->imageDirectoryList[$key]);
                    $count++;
                }
            }
            OrganizeImage::getInstance()->line(implode(",", self::Extensions) . "以外のファイルを削除し、" . $count . " 個のファイルが削除されました");
        } else {
            OrganizeImage::getInstance()->line("ファイル削除をキャンセルしました");
        }
    }

}