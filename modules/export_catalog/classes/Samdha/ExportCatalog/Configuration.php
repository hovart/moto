<?php
/**
 * Configuration class use by the module export_catalog
 *
 * Configuration are saved in json files
 *
 * @category  Prestashop
 * @category  Module
 * @author    Samdha <contact@samdha.net>
 * @copyright Samdha
 * @license   commercial license see license.txt
 */

class Samdha_ExportCatalog_Configuration
{
    /* directory where the configuration files are saved */
    public $directory;
    /* configuration datas */
    public $datas = array();
    /* configuration name */
    public $name = '';
    /* configuration will be saved in $directory.$filename.$extension */
    public $filename;
    public $module;

    /* default configuration file extension */
    protected $extension = '.json';

    public function __construct($directory, $module, $filename = null)
    {
        $this->directory = $directory;
        $this->module = $module;
        $this->loadFromFile($filename);
        if (!$this->filename) {
            $this->initDefault();
        }
    }

    /**
     * get all valid configuration files
     * in $directory with extension $extension
     *
     * return array(filename => configuration name)
     * @return array configuration files
     */
    public function getFiles()
    {
        $configurations = array();
        // don't use glob() it can be blocked for "security reason"
        $files = array_diff(scandir($this->directory), array('..', '.'));
        if (is_array($files)) {
            $unnamed_count = 1;
            $extension_length = Tools::strlen($this->extension);
            foreach ($files as $file) {
                if (Tools::substr($file, -$extension_length) === $this->extension) {
                    $configuration = $this->module->samdha_tools->jsonDecode(
                        $this->module->samdha_tools->fileGetContents($this->directory.$file),
                        true
                    );
                    if ($configuration) {
                        $physical_name = basename($file, $this->extension);
                        // If a configuration doesn't have name create one
                        if (!trim($configuration['name'])) {
                            $configuration['name'] = 'Unnamed '.$unnamed_count;
                            $unnamed_count++;
                        }
                        $configurations[$physical_name] = $configuration['name'];
                    }
                }
            }
        }
        return $configurations;
    }

    /**
     * save configuration on disk
     * $this->filename will be updated
     *
     * @return false/integer the number of bytes that were written to
     * the file, or FALSE on failure
     */
    public function save()
    {
        $json = array(
            'name' => $this->name,
            'datas' => $this->datas
        );
        if (!$this->filename) {
            $this->filename = $this->getNewFilename();
        }

        $filename = $this->directory.$this->filename.$this->extension;
        return file_put_contents($filename, $this->module->samdha_tools->jsonEncode($json));
    }

    /**
     * load configuration from file
     * $this->filename will be updated
     *
     * @param  string $filename filename to load without extension
     * @return boolean           true on success
     */
    public function loadFromFile($filename)
    {
        $result = false;
        if (!file_exists($filename)) {
            $filename = $this->directory.$filename.$this->extension;
        }

        if (file_exists($filename)) {
            $configuration = $this->module->samdha_tools->jsonDecode(
                $this->module->samdha_tools->fileGetContents($filename),
                true
            );
            if ($configuration
                && isset($configuration['datas'])
                && isset($configuration['name'])
            ) {
                $this->datas = $configuration['datas'];
                $this->name = $configuration['name'];
                $this->filename = basename($filename, $this->extension);
                $result = true;
            }
        }
        return $result;
    }

    /**
     * delete configuration file from disk
     *
     * @return void
     */
    public function delete()
    {
        if ($this->filename) {
            $filename = $this->directory.$this->filename.$this->extension;
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
    }

    /**
     * set default configuration
     * @return void
     */
    protected function initDefault()
    {
        $this->name = ''; // configuration name
        $this->datas = array(); // configuration datas
    }

    /**
     * load configuration from array
     * array(
     *     'name' => configuration name,
     *     'datas' => array(
     *         key => value
     *     )
     * )
     * @param  array $configuration configuration to load
     * @return void
     */
    public function loadFromArray($configuration)
    {
        $this->name = $configuration['name'];
        $this->datas = array_merge($this->datas, $configuration['datas']);
    }

    /**
     * Return a sha1 filename
     *
     * @return string Sha1 unique filename
     */
    public function getNewFilename()
    {
        do {
            $filename = sha1(microtime());
        } while (file_exists($this->directory.$filename.$this->extension));
        return $filename;
    }

    /**
     * Check if working directory is writable
     *
     * @return boolean
     */
    public function checkWritableDir()
    {
        return is_writable($this->directory);
    }

    /**
     * send configuration file to browser
     * @return void doesn't not return
     */
    public function sendToBrowser()
    {
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        $filename = $this->name.$this->extension;
        header('Content-disposition: attachment; filename="'.$filename.'"');
        readfile($this->directory.$this->filename.$this->extension);
        die();
    }

    /**
     * load configuration from a file
     * does NOT update $this->filename
     * @param  string $filename full filename with path and extension
     * @return boolean           true on success
     */
    public function copyFromFile($filename)
    {
        $result = false;
        $old_filename = $this->filename;
        if ($this->loadFromFile($filename)) {
            $this->filename = $old_filename;
            $this->save();
            $result = true;
        }
        return $result;
    }
}
