<?php
class AIUploader
{
    public static $id_advancedimporter_block;
    public static $id_advancedimporter_flow;
    public static $id_shop;

    public static function ftpUploader($block)
    {
        if (empty($block->port)) {
            $block->port = 21;
        }
        if (empty($block->sourcedir)) {
            $block->sourcedir = '.';
        }
        if (empty($block->destinationdir)) {
            $block->destinationdir = _PS_MODULE_DIR_.'advancedimporter/flows/import/queue/';
        }
        if (!preg_match('/.*\/$/', $block->destinationdir)) {
            $block->destinationdir .= '/';
        }

        $conn_id = ftp_connect($block->host, $block->port);
        $login_result = ftp_login($conn_id, $block->user, $block->password);

        if (!$login_result) {
            throw new Exception("Cannot connect to '$block->host' with user '$block->user'");
        }

        // List files
        if (!($files = ftp_nlist($conn_id, $block->sourcedir))) {
            throw new Exception("Cannot read directory '$block->sourcedir' from ftp server '$block->host'");
        }

        // Download files
        foreach ($files as $file) {

            $dirname = dirname($file);
            if ($dirname !== '.') {
                ftp_chdir($conn_id, $dirname);
                $file = basename($file);
            }

            if (!ftp_get(
                $conn_id,
                $block->destinationdir.$file.'.tmp',
                $file,
                FTP_ASCII
            )) {
                throw new Exception("Cannot donwload the file '$file' from ftp server '$block->host'");
            }

            rename($block->destinationdir.$file.'.tmp', $block->destinationdir.$file);
        }
    }

    public static function httpUploader($block)
    {
        try {
            ini_set('default_socket_timeout', 500);
        } catch (Exception $e) {
            error_log('Cannot define default_socket_timeout');
        }
        if (!isset($block->sourcepath)) {
            throw new Exception('Unknow source path');
        }
        if (empty($block->destinationpath)) {
            $pathinfo = pathinfo($block->sourcepath);
            $block->destinationpath = $pathinfo['basename'];
            if (!preg_match('/\.xml$/', $block->destinationpath)) {
                $block->destinationpath = $block->destinationpath . '.xml';
            }
        }
        $date = new DateTime();
        $str_date = $date->format('Ymd-His');
        $block->destinationpath = _PS_MODULE_DIR_.'advancedimporter/flows/import/queue/'
            .str_replace('%date%', $str_date, $block->destinationpath);
			
        if (!self::fileExists((string) $block->sourcepath)) {
            Log::sys(
                self::$id_advancedimporter_block,
                self::$id_advancedimporter_flow,
                "Nothing to upload : {$block->sourcepath} do not exists"
            );

            return;
        }
        copy($block->sourcepath, $block->destinationpath.'.tmp');
        rename($block->destinationpath.'.tmp', $block->destinationpath);
    }

    public static function fileExists($file_path)
    {
        try {
            $file_headers = get_headers($file_path);
        } catch (Exception $e) {
            return true;
        }

        if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        } else {
            return true;
        }
    }
}