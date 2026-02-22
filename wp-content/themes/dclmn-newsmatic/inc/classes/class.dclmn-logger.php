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
        $yearDir = $this->logPath . current_time('Y');
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

    public function log($time, $level, $record) {
        pobj(func_get_args());
        $record = $time . "\t" . strtoupper($level) . "\t" . $record . PHP_EOL;
        @file_put_contents($this->logPath, $record, FILE_APPEND);
    }

    public function shouldHandle($context, $level) {
        return true;
    }

    public function getLogPath() {
        return $this->logPath;
    }
}
