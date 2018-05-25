<?php
class ModelToolSetCron extends Model {

    public function setCron($cron_date)
    {
        $sql1 = "TRUNCATE `" . DB_PREFIX . "cron_task`";

        $this->db->query($sql1);

        foreach ($cron_date as $key => $value) {

                            $sql2 = "INSERT INTO `" . DB_PREFIX . "cron_task` (`task_name`,`interval`,`time_begin`,`time_end`,`day_begin`,`day_end`,`dayofmonth`,`month`)
                            VALUES('" . $value['task_name'] . "','" . $value['interval'] . "','" . $value['time_begin'] . "','" . $value['time_end'] . "','" . $value['day_begin'] . "','" . $value['day_end'] . "','" . $value['dayofmonth'] . "','" . $value['month']."' )";
                            $this->db->query($sql2);
        }

    }

    public function getCronValue()
    {

       $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cron_task` ");


        return $query->rows;
    }

    public function addTask($cron_date)
    {
        $sql2 = "INSERT INTO `" . DB_PREFIX . "cron_task` (`task_name`,`task_title`,`interval`,`time_begin`,`time_end`,`day_begin`,`day_end`,`dayofmonth`,`month`) 
        VALUES('" . $cron_date['task_name'] . "','" . $this->db->escape($cron_date['task_title']) . "','" . $cron_date['interval'] . "','" .$cron_date['time_begin'] . "','" . $cron_date['time_end'] . "','" . $cron_date['day_begin'] . "','" .$cron_date['day_end'] . "','" . $cron_date['dayofmonth'] . "','" . $cron_date['month']."' )";
        $this->db->query($sql2);

    }

    public function editTask($task_id, $cron_date) {
      $sql=("UPDATE `" . DB_PREFIX . "cron_task`
                SET `task_name` ='" .$cron_date['task_name']. "', `task_title` = '" . $this->db->escape($cron_date['task_title']) . "',`interval` = '" . $cron_date['interval'] . "', `time_begin` = '" .$cron_date['time_begin'] . "',
                `time_end` = '" . $cron_date['time_end'] . "', `day_begin` = '" . $cron_date['day_begin'] . "', `day_end` = '" .$cron_date['day_end'] . "',
                `dayofmonth` = '" . $cron_date['dayofmonth'] . "' ,`month` = '" . $cron_date['month']."'
                WHERE task_id = '$task_id'");
        $this->db->query($sql);
    }

    public function deleteTask($task_id){

        $this->db->query("DELETE FROM `" . DB_PREFIX . "cron_task` WHERE task_id = '" . (int)$task_id . "'");
    }

    public function getTaskId($task_id){

        $query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "cron_task`  WHERE task_id = '" . (int)$task_id . "'");

        return $query->row;
    }

    public function getTaskName($task_name){

        $query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "cron_task`  WHERE task_name = '" . $task_name . "'");

        return $query->row;
    }

}