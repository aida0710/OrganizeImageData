<?php

namespace lazyperson0710\OrganizeImageData;


abstract class App {

    abstract public function execute();

    /**
     * @param string $message
     */
    protected function message(string $message): void {
        echo escapeshellcmd($message);
    }

    /**
     * @param string $message
     */
    protected function line(string $message): void {
        echo escapeshellcmd($message) . "\n";
    }

    /**
     * @param string $message
     * @return string
     */
    protected function ask(string $message): string {
        $this->message($message);
        return trim(fgets(STDIN));
    }

}