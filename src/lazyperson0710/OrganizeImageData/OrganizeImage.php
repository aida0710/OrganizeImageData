<?php

namespace lazyperson0710\OrganizeImageData;

use Error;
use lazyperson0710\OrganizeImageData\io\ImageFileManagement;

require_once "./App.php";
require_once "./io/ImageFileManagement.php";

class OrganizeImage extends App {

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
        //処理はすべて最後にまとめる感じで
        $this->line("以下の質問で不正の値が入力された場合否定として処理を続行しますのでご注意ください");
        $this->line("========================================");
        $result = $this->answerConfirmation($this->ask(implode(",", ImageFileManagement::Extensions) . "以外のファイルを削除しますか？(y/n): "), 1);
        if ($result) {
            $this->line(implode(",", ImageFileManagement::Extensions) . "以外のファイル削除を開始しました...");
            $count = ImageFileManagement::getInstance()->deleteOtherFile();
            $this->line(implode(",", ImageFileManagement::Extensions) . "以外のファイルを削除し、" . $count . " 個のファイルが削除されました");
        } else {
            $this->line("ファイル削除をキャンセルしました");
        }
        $this->line("========================================");
        $result = $this->answerConfirmation($this->ask("最上ディレクトリ以外のディレクトリを内部ファイルを移動した後に全て削除し最上ディレクトリにファイルを全て集めてもよろしいですか？(y/n): "), 1);
        if ($result) {
            $this->line("ファイル移動を開始しました...");
            $count = ImageFileManagement::getInstance()->moveFile();
            ImageFileManagement::getInstance()->emptyDirectoryDelete();
            $this->line("ファイルを移動し、" . $count . " 個のファイルが移動されました");
        } else {
            $this->line("ファイル移動をキャンセルしました");
        }
        $this->line("========================================");
        $result = $this->answerConfirmation($this->ask("入力した値以下のファイルサイズの画像を削除しますか？削除しない場合は0を入力してください(単位:kb): "), 3);
        if ($result) {
        } else {
            $this->line("ファイル移動をキャンセルしました");
        }
        organizingType(false);
        function organizingType(bool $recursion): void {
            (new OrganizeImage)->line("========================================");
            if ($recursion) {
                (new OrganizeImage)->line("Error : 不明な値が入力された為再入力を要求します");
            }
            (new OrganizeImage)->line("画像をどのように整理しますか？");
            (new OrganizeImage)->line("1. 年 月 日 までフォルダを作成");
            (new OrganizeImage)->line("2. 年 月 までフォルダを作成");
            (new OrganizeImage)->line("3. 年 までフォルダを作成");
            $result = (new OrganizeImage)->answerConfirmation((new OrganizeImage)->ask("番号を入力してください: "), 2);
            if ($result) {
                return;
            } else {
                organizingType(true);
            }
        }

        $this->line("ソート処理を開始しました");
    }

    public function answerConfirmation(string $question, int $type): bool|int {
        switch ($type) {
            case 1:
                if (($question === 'y' || $question === 'yes')) {
                    return true;
                } else {
                    return false;
                }
            case 3:
                if (is_numeric($question)) {
                    return (int)$question;
                } else {
                    return 0;
                }
            default:
                throw new Error("typeが不正です");
        }
    }

}