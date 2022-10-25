<?php

namespace lazyperson0710\OrganizeImageData;

use lazyperson0710\OrganizeImageData\io\ImageFileManagement;

require_once "./App.php";
require_once "./io/ImageFileManagement.php";

class OrganizeImage extends App {

    public const ImageDirectory = '../../../Images';
    private array $imageDirectoryList = [];

    /**
     * imageフォルダの中身を確認する
     * 現在枚数を表示して
     * 画像をどのように整理するか選択させる
     * 選択したらyes / noで確認する
     * yesなら整理を開始する
     *
     * progressも出来たらいいな！
     *
     * 種類
     * 年 月 日 までフォルダを作成
     * 年 月 までフォルダを作成
     * 年 までフォルダを作成
     */
    /**
     * @return void
     */
    public function execute(): void {
        $this->line("imageフォルダの内容ファイル確認を開始します...");
        $count = ImageFileManagement::getInstance()->countImage();
        $this->line("確認処理が終了しました");
        $this->line("現在の画像の枚数は" . $count . "枚です");
        $this->line("========================================");
        $question2 = $this->answerConfirmation($this->ask(implode(",", ImageFileManagement::Extensions) . "以外のファイルを削除しますか？(y/n): "), $type = 1);
        if ($question2 === "yes") {
            $this->line(implode(",", ImageFileManagement::Extensions) . "以外のファイル削除を開始しました...");
            $count = ImageFileManagement::getInstance()->deleteOtherFile();
            $this->line(implode(",", ImageFileManagement::Extensions) . "以外のファイルを削除し、" . $count . " 個のファイルが削除されました");
        } else {
            $this->line("ファイル削除をキャンセルしました");
        }
        $this->line("========================================");
        $question3 = $this->answerConfirmation($this->ask("最上ディレクトリ以外のディレクトリを内部ファイルを移動した後に全て削除し最上ディレクトリにファイルを全て集めてもよろしいですか？(y/n): "), $type = 1);
        if ($question3 === "yes") {
            $this->line("ファイル移動を開始しました...");
            $count = ImageFileManagement::getInstance()->moveFile();
            ImageFileManagement::getInstance()->emptyDirectoryDelete();
            $this->line("ファイルを移動し、" . $count . " 個のファイルが移動されました");
        } else {
            $this->line("ファイル移動をキャンセルしました");
        }
        $this->line("========================================");
        $this->line("画像をどのように整理しますか？");
        $this->line("1. 年 月 日 までフォルダを作成");
        $this->line("2. 年 月 までフォルダを作成");
        $this->line("3. 年 までフォルダを作成");
        $number = $this->ask("番号を入力してください: ");
    }

    public function answerConfirmation(string $question, int $type): string {
        switch ($type) {
            case 1:
                if (($question === 'y' || $question === 'yes')) {
                    return "yes";
                } else {
                    return "no";
                }
            default:
                throw new \Error("typeが不正です");
        }
    }

}