<?php

namespace lazyperson0710\OrganizeImageData;

use Error;
use lazyperson0710\OrganizeImageData\io\ImageFileManagement;
use lazyperson0710\OrganizeImageData\object\Question;
use lazyperson0710\OrganizeImageData\object\questions\DeleteFilesBelowCapacity;
use lazyperson0710\OrganizeImageData\object\questions\DeleteOtherFiles;
use lazyperson0710\OrganizeImageData\object\questions\OrganizingType;
use lazyperson0710\OrganizeImageData\object\Setting;

require_once "./object/questions/OrganizingType.php";
require_once "./object/questions/DeleteOtherFiles.php";
require_once "./object/questions/DeleteFilesBelowCapacity.php";
require_once "./io/ImageFileManagement.php";
require_once "./object/Question.php";

class OrganizeImage {

    private static OrganizeImage $instance;
    private array $questionAnswer = [];
    private const LINE = "----------------------------------------";

    /**
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
        foreach ((new Question())->getQuestions() as $questionClass) {
            $this->line(self::LINE);
            if (!$questionClass instanceof Setting) return;
            $answer = $this->answer($questionClass);
            $this->questionAnswer[] = [
                'class' => $questionClass->getName(),
                'answer' => $answer,
            ];
        }
        $this->line(self::LINE);
        foreach ($this->questionAnswer as $question) {
            $class = match ($question['class']) {
                'DeleteOtherFiles' => new DeleteOtherFiles(),
                'DeleteFilesBelowCapacity' => new DeleteFilesBelowCapacity(),
                'OrganizingType' => new OrganizingType(),
                default => throw new Error("クラスが存在しません"),
            };
            if (!$class instanceof Setting) {
                $this->line("Error : 設定クラスが存在しません");
                return;
            }
            $class->execution($question['answer']);
        }
        //progressbar
        //内容の確認
    }

    public function answer(Setting $questionClass, ?bool $injustice = false): string|int {
        if ($injustice) {
            $this->line(self::LINE);
            $this->line("Error : 不明な値が入力された為再入力を要求します");
        }
        if ($questionClass->getFirstMessage() !== []) {
            foreach ($questionClass->getFirstMessage() as $message) {
                $this->line($message);
            }
        }
        $answer = $this->ask($questionClass->getQuestionMessage());
        switch ($questionClass->getAnswerType()) {
            case "string":
                if ($questionClass->getAnswerFilter() !== []) {
                    if (in_array($answer, $questionClass->getAnswerFilter()) === false) {
                        return $this->answer($questionClass, true);
                    }
                }
                return $answer;
            case "int":
                if (is_numeric($answer)) {
                    if ($questionClass->getAnswerFilter() !== []) {
                        if (in_array((int)$answer, $questionClass->getAnswerFilter()) === false) {
                            return $this->answer($questionClass, true);
                        }
                    }
                    return (int)$answer;
                } else {
                    return $this->answer($questionClass, true);
                }
            default:
                throw new Error("Error : Question設定クラスに不明な型指定がされています");
        }
    }

    /**
     * @param string $message
     */
    public function line(string $message): void {
        echo escapeshellcmd($message) . "\n";
    }

    /**
     * @param string $message
     * @return string
     */
    public function ask(string $message): string {
        echo escapeshellcmd($message);
        return trim(fgets(STDIN));
    }

    public static function getInstance(): OrganizeImage {
        if (!isset(self::$instance)) {
            self::$instance = new OrganizeImage();
        }
        return self::$instance;
    }

}