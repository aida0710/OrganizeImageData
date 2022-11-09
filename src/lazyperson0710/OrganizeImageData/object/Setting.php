<?php

namespace lazyperson0710\OrganizeImageData\object;

abstract class Setting {

    final public function __construct() {
    }

    /**
     * 質問文のタイトル
     * cli上には表示されませんがメモリ内の配列保存の際に使用されます
     * マルチバイト文字や重複した名前は止めてください
     * ※名前が重複していた場合は起動時にエラーが出力されます
     *
     * @return string
     */
    abstract public function getName(): string;

    /**
     * 最初に送信するメッセージ
     * また、質問文ではない為ご注意ください
     * 空配列の場合は送信されません
     *
     * @return array
     */
    abstract public function getFirstMessage(): array;

    /**
     * 入力可能メッセージを送信します
     * 質問文などを想定しています
     *
     * @return string
     */
    abstract public function getQuestionMessage(): string;

    /**
     * 回答されたときに許容する値を配列で指定できます
     *
     *
     *
     * 空配列にした場合はフィルターが無効化されます
     *
     * @return array
     */
    abstract public function getAnswerFilter(): array;

    /**
     * 型を指定したい場合に記述してください
     * 対応している型はstring,int,boolの三つのみです
     *
     * @return string
     */
    abstract public function getAnswerType(): string;

    /**
     * 最後に実行する処理クラスを記述
     *
     * @param string|int $answer
     * @return void
     */
    abstract public function execution(string|int $answer): void;
}