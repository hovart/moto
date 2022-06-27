<?php
/**
 * Scheduled export configuration use by the module export_catalog
 *
 * @category  Prestashop
 * @category  Module
 * @author    Samdha <contact@samdha.net>
 * @copyright Samdha
 * @license   commercial license see license.txt
 */

class Samdha_ExportCatalog_Export extends Samdha_ExportCatalog_Configuration
{
    protected $extension = '.export';

    /**
     * load configuration from a file
     * @param  string $filename file to load
     * @return boolean           true if succeeded
     */
    public function loadFromFile($filename)
    {
        $result = parent::loadFromFile($filename);
        if ($result) {
            if (!isset($this->datas['days']) || !is_array($this->datas['days'])) {
                $this->datas['days'] = array();
            }
            if (count($this->datas['days']) == 7) {
                // empty array means all days
                $this->datas['days'] = array();
            }
            if (!isset($this->datas['ftp'])) {
                $this->datas['ftp'] = '0';
            }
            if (!isset($this->datas['ftp_host'])) {
                $this->datas['ftp_host'] = '';
            }
            if (!isset($this->datas['ftp_login'])) {
                $this->datas['ftp_login'] = '';
            }
            if (!isset($this->datas['ftp_password'])) {
                $this->datas['ftp_password'] = '';
            }
            if (!isset($this->datas['ftp_folder'])) {
                $this->datas['ftp_folder'] = '';
            }
            if (isset($this->datas['id_employee'])) {
                $this->datas['employees'] = array($this->datas['id_employee']);
                unset($this->datas['id_employee']);
            }
            if (isset($this->datas['hour'])) {
                $this->datas['hours'] = array($this->datas['hour']);
                unset($this->datas['hour']);
            }
            if (isset($this->datas['minute'])) {
                $this->datas['minutes'] = array($this->datas['minute']);
                unset($this->datas['minute']);
            }

            if (!isset($this->datas['hours']) || !is_array($this->datas['hours'])) {
                $this->datas['hours'] = array(0);
            }
            if (count($this->datas['hours']) == 24) {
                // empty array means all hours
                $this->datas['hours'] = array();
            }
            if (!isset($this->datas['minutes'])
                || !is_array($this->datas['minutes'])
                || empty($this->datas['minutes'])) {
                $this->datas['minutes'] = array(0);
            }
            if (!isset($this->datas['simple'])) {
                $this->datas['simple'] = '1';
            }
        }
        return $result;
    }

    /**
     * set default configuration
     * @return void
     */
    protected function initDefault()
    {
        $this->name = ''; // export name
        $this->datas = array(
            'model' => '', // associated export model
            'employees' => array(), // employees that will receive the file by mail
            'folder' => '', // directory where the file will be copied
            'days' => array(), // day when run the export
            'minutes' => array(0), // hours when run the export
            'hours' => array(0), // minutes when run the export
            'ftp' => '0', // send the file by ftp
            'ftp_host' => '',
            'ftp_login' => '',
            'ftp_password' => '',
            'ftp_folder' => '',
        );
    }

    public function save()
    {
        $this->datas['next_run'] = $this->getNextRun();
        return parent::save();
    }

    /**
     * get when the export should be run next time
     * @return timestamp
     */
    public function getNextRun()
    {
        $wanted_minutes = array();
        $hours = empty($this->datas['hours'])?range(0, 23):$this->datas['hours'];
        foreach ($hours as $hour) {
            foreach ($this->datas['minutes'] as $minute) {
                $wanted_minutes[] = 60 * $hour + $minute;
            }
        }
        sort($wanted_minutes);

        $day = (int) date('w');
        $current_minute = 60 * date('G') + date('i');
        $days = empty($this->datas['days'])?range(0, 6):$this->datas['days'];
        for ($i = 0; $i <= 7; $i++) {
            if (in_array(($day + $i) % 7, $days)) {
                foreach ($wanted_minutes as $wanted_minute) {
                    if ($current_minute <= $wanted_minute) {
                        return mktime(0, $wanted_minute, 0, date('n'), date('j') + $i);
                    }
                }
            }
            $current_minute = 0;
        }
        // should not append
        return PHP_INT_MAX;
    }

    /**
     * get associated export model
     * @return object Samdha_ExportCatalog_Model
     */
    public function getModel()
    {
        return new Samdha_ExportCatalog_Model($this->directory, $this->module, $this->datas['model']);
    }

    /**
     * Do the export
     * the file to export has been generated before by the model
     * it will be deleted by this methods
     *
     * @param  string $filename file to export
     * @return void
     */
    public function export($filename)
    {
        $model = $this->getModel();
        $model->datas['last_run'] = time();
        $model->save();
        $model->restoreContext();

        // send by mail if needed
        $file_attachment = array(
            'content' => $this->module->samdha_tools->fileGetContents($filename),
            'name' => strftime($model->datas['filename']),
            'mime' => 'text/csv'
        );
        foreach ($this->datas['employees'] as $id_employee) {
            $employee = new Employee((int) $id_employee);
            if (Validate::isLoadedObject($employee)) {
                Mail::Send(
                    Configuration::get('PS_LANG_DEFAULT'),
                    $this->module->name,
                    $this->module->name,
                    array(),
                    $employee->email,
                    $employee->firstname.' '.$employee->lastname,
                    null,
                    null,
                    $file_attachment,
                    null,
                    $this->module->mail_path
                );
            }
        }

        // save in folder if needed
        if ($this->datas['folder']) {
            $new_name = _PS_ROOT_DIR_.$this->datas['folder'].strftime($model->datas['filename']);
            if ($this->module->samdha_tools->copy($filename, $new_name)) {
                chmod($new_name, 0755);
            }
        }
        // send by ftp if needed
        if ($this->datas['ftp']) {
            $new_name = 'ftp://'.$this->datas['ftp_login'].':'.$this->datas['ftp_password']
                .'@'.$this->datas['ftp_host'].'/'.trim($this->datas['ftp_folder'], '/')
                .'/'.strftime($model->datas['filename']);
            try {
                $this->module->tools->copyToFTP($filename, $new_name);
            } catch (Exception $e) {
                $this->module->errors[] = $e->getMessage();
            }
        }

        // deleted the file if still exists
        if (file_exists($filename)) {
            try {
                unlink($filename);
            } catch (Exception $e) {
                $this->module->errors[] = $e->getMessage();
            }
        }
    }

    public function __get($var)
    {
        switch ($var) {
            case 'last_run_formated':
                $date = date('Y-m-d H:i:s', $this->datas['last_run']);
                return Tools::displayDate($date, $this->module->context->language->id, true);
            case 'next_run_formated':
                $date = date('Y-m-d H:i:s', $this->datas['next_run']);
                return Tools::displayDate($date, $this->module->context->language->id, true);
            default:
                return null;
        }
    }
}
