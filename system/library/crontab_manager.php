<?php
class crontab_manager
{
    private $path;
    private $handle;
    private $cron_file;

    function __construct() {
        $this->path  = DIR_APPLICATION."crontab/";
        $this->handle    = 'crontab.txt';
        $this->cron_file = "{$this->path}{$this->handle}";
    }


    public function execTask() {
        $argument_count = func_num_args();

        try
        {
            if ( ! $argument_count) throw new Exception("There is nothing to execute, no arguments specified.");

            $arguments = func_get_args();

            $command_string = ($argument_count > 1) ? implode(" && ", $arguments) : $arguments[0];

            $stream = exec( $command_string,$output,$return_var);

            if ( $return_var <> 0) throw new Exception("Unable to execute the specified commands: <br />{$command_string}");

        }
        catch (Exception $e)
        {
            $this->error_message($e->getMessage());
        }

        return $this;
    }

    public function write_to_file($path=NULL, $handle=NULL) {

        if ( ! $this->crontab_file_exists())
        {
            $this->handle = (is_null($handle)) ? $this->handle : $handle;
            $this->path   = (is_null($path))   ? $this->path   : $path;
            $this->cron_file = "{$this->path}{$this->handle}";
        }

        return $this;
    }

    public function remove_file() {

        if ($this->crontab_file_exists()) $this->execTask("rm {$this->cron_file}");

        return $this;
    }

    public function append_cronjob($cron_jobs=NULL) {

        if (is_null($cron_jobs)) $this->error_message("Nothing to append!  Please specify a cron job or an array of cron jobs.");

        $append_cronfile = "echo '";

        $append_cronfile .= (is_array($cron_jobs)) ? implode("\n", $cron_jobs) : $cron_jobs;

        $append_cronfile .= "'  >> {$this->cron_file}";


        $install_cron = "crontab {$this->cron_file}";


        $this->write_to_file() -> execTask($append_cronfile,$install_cron)-> remove_file();

        return $this;
    }


    public function remove_crontab() {

        $this->exec("crontab -r")->remove_file();

        return $this;
    }

    private function crontab_file_exists() {

        return file_exists($this->cron_file);
    }

    private function error_message($error) {
        die("<pre style='color:#EE2711'>ERROR: {$error}</pre>");
    }

}




