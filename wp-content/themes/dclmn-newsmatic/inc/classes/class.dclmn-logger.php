<?php

class DCLMN_Logger {
    protected $logPath;

    public function __construct($name = 'network') {
        if ($this->logPath = wp_get_upload_dir()['basedir'].'/logs') {
            $dir = $this->getDirectoryPath();
            $this->logPath = $dir . DIRECTORY_SEPARATOR . current_time('Y-m-d') . '-' . $name . '.log';
        } else {}
    }

    public function getDirectoryPath() {
        $yearDir = trailingslashit($this->logPath) . current_time('Y');
        $monthDir = $yearDir . DIRECTORY_SEPARATOR . current_time('m');

        $this->createDir(wp_get_upload_dir()['basedir'].'/logs');
        $this->createDir($this->logPath);
        $this->createDir($yearDir);
        $this->createDir($monthDir);

        return $monthDir;
    }

    public function createDir($dir) {
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0775, 1)) {
                die('Cannot create directory ' . $dir);
            }
        }
    }

    public function log($message, $level) {
        if (!is_string($message)) {
            $message = print_r($message,1);
        }
        $time = date('Y-m-d H:i:s');
        $record = $time . "\t" . strtoupper($level) . "\t" . $message . PHP_EOL;
        file_put_contents($this->logPath, $record, FILE_APPEND);
    }

    public function shouldHandle($context, $level) {
        return true;
    }

    public function getLogPath() {
        return $this->logPath;
    }
}
