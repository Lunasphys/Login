<?php

namespace Log;

class Log {
    private string $message;
    private string $date;

    public function __construct(string $message) {
        $this->message = $message;
        $this->date = date('Y-m-d H:i:s');
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function __toString(): string {
        return $this->date . ' - ' . $this->message;
    }

    public function __destruct() {
        $file = fopen('log.txt', 'a+');
        fwrite($file, $this->__toString() . PHP_EOL);
        fclose($file);
    }
}