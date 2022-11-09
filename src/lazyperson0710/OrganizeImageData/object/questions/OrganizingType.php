<?php

declare(strict_types = 1);
namespace lazyperson0710\OrganizeImageData\object\questions;

use lazyperson0710\OrganizeImageData\io\ImageFileManagement;
use lazyperson0710\OrganizeImageData\object\Setting;
use lazyperson0710\OrganizeImageData\OrganizeImage;

require_once "./object/Setting.php";

class OrganizingType extends Setting {

    public function getName(): string {
        return "OrganizingType";
    }

    public function getFirstMessage(): array {
        return [
            "画像をどのように整理しますか？",
            "1. 年 月 日 までフォルダを作成",
            "2. 年 月 までフォルダを作成",
            "3. 年 までフォルダを作成",
        ];
    }

    public function getQuestionMessage(): string {
        return "番号を入力してください: ";
    }

    public function getAnswerFilter(): array {
        return [
            1,
            2,
            3,
        ];
    }

    public function getAnswerType(): string {
        return "int";
    }
    //    (new OrganizeImage)->line("1. 年 月 日 までフォルダを作成");
    //    (new OrganizeImage)->line("2. 年 月 までフォルダを作成");
    //    (new OrganizeImage)->line("3. 年 までフォルダを作成");
    public function execution(string|int $answer): void {
        OrganizeImage::getInstance()->line("画像を整理するために日付データを取得しています...");
        ImageFileManagement::getInstance()->checkImagesFolder();
        $fileList = ImageFileManagement::getInstance()->setFileList(ImageFileManagement::ImageDirectory, true);
        OrganizeImage::getInstance()->line("画像の日付データの取得が完了しました");
        OrganizeImage::getInstance()->line("画像の整理を開始します");
        $count = 0;
        $path = ImageFileManagement::ImageDirectory;

        switch ($answer) {
            case 1:
                OrganizeImage::getInstance()->line("type : 年 月 日 までフォルダを作成");
                foreach ($fileList as $value) {
                    var_dump($value);
                    if ($value['date'] === false) {
                        continue;
                    } else $value['date'] = (int)$value['date'];
                    if (!is_dir($path. "/". date("Y", $value['date']))) {
                        mkdir($path. "/". date("Y", $value['date']));
                    }
                    if (!is_dir($path. "/". date("Y", $value['date']). "/". date("m", $value['date']))) {
                        mkdir($path. "/". date("Y", $value['date']). "/". date("m", $value['date']));
                    }
                    if (!is_dir($path. "/". date("Y", $value['date']). "/". date("m", $value['date']). "/". date("d", $value['date']))) {
                        mkdir($path. "/". date("Y", $value['date']). "/". date("m", $value['date']). "/". date("d", $value['date']));
                    }
                    if (rename($path. "/". basename($value['file']), $path. "/". date("Y", $value['date']). "/". date("m", $value['date']). "/". date("d", $value['date']). "/". basename($value['file']))) {
                        $count++;
                    }
                }
                OrganizeImage::getInstance()->line("画像の整理を完了しました");
                OrganizeImage::getInstance()->line("整理した画像の数: ". $count);
            case 2:
            case 3:
                break;
        }
    }
}