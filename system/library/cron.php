<?php
class cron{
    private $tasks = array();

    public function __construct() {
        $this->runCronDate = array (
            "minute" 		=> date("i"),
            "hour" 			=> date("H"),
            "day" 			=> date("d"),
            "dayofweek" 	=> date("N"),
            "dayofmonth" 	=> date("j")
        );
    }

    public function __get($name) {
        return $this->registry->get($name);
    }

    public function isRun() {
        return count($this->tasks)? true: false;
    }

    public function run($registry) {
        $this->registry = $registry;
        foreach ($this->tasks as $task) {
            $this->load->controller($task['controller'], $task['parameters']);
        }
    }

    /**
     * Call the controller at a specific time
     *
     * @param string $controller Name of conroller and method, for example: setting/store/index
     * @param array $time Array of elements: minute, hour, day, dayofweek, dayofmonth. A value can be: * (any number); the number, range of numbers; eg 1-10. All values are separated by commas, for example "1,4,10-15,20". If you need to perform every script, for example, 5 minutes, then enter the value "* /5"
     **/
    public function call($controller, $time, $parameters = array()) {
        if (true  || $this->isTime($time)) {
            $this->tasks[] = array("controller" => $controller, "parameters" => $parameters);
        }
    }

    private function isTime($time) {
        if (!is_array($time))
            return false;

        $minute = false;
        $hour = false;
        $day = false;
        $dayofweek = false;
        $dayofmonth = false;

        foreach (array("minute", "hour", "day", "dayofweek", "dayofmonth") as $param) {
            //    echo "$param+$time[$param]    ";
            if(!isset($time[$param]))
                return false;
            else {
                if (trim($time[$param]) == "")
                    return false;
                else if (!preg_match('/[\d\-*,\/]/', $time[$param]))
                    return false;
                else {
                    $temp = explode(",",$time[$param]);
                    foreach ($temp as $t) {
                        //	    echo "---".$t."--- ";
                        if (trim($t) == "")
                            continue;
                        elseif ($t == "*") {
                            $$param = true;
                            //          echo"1+ $param +${$param} ";
                            break;
                        }
                        elseif (is_numeric($t)) {
                            //	    echo "num".$param."=".$t;
                            //	    echo $this->runCronDate[$param];
                            if ($this->runCronDate[$param] == $t) {
                                $$param = true;
                                //             echo"2+$param + ${$param} ";
                                break;
                            }
                        }
                        else {
                            if (preg_match('/[\-]/', $time[$param])) {
                                list($from, $to) = explode("-", $t, 2);
                                if (!is_numeric($from) || !is_numeric($to) || $from > $to)
                                    return false;
                                if ($this->runCronDate[$param] >= $from && $this->runCronDate[$param] <= $to) {
                                    $$param = true;
                                    //              echo"11+$param+ ${$param} ";
                                    break;
                                }
                            }
                            elseif (preg_match('/[\/]/', $time[$param])) {
                                list($any, $step) = explode("/", $t, 2);
                                //	echo $t;
                                //	echo "    ";
                                //	echo $this->runCronDate[$param]." - $any, $step";
                                if ($any != "*" || !is_numeric($step))
                                    return false;
                                if ($this->runCronDate[$param] % $step == 0) {
                                    $$param = true;
                                    //          echo"12+$param+ ${$param} ";
                                    break;
                                }
                            }
                            else
                                return false;
                        }

                    }
                }
            }
        }
        echo "minute=$minute hour=$hour day=$day dayofweek=$dayofweek dayofmonth=$dayofmonth ";
        if ($minute && $hour && $day && $dayofweek && $dayofmonth)
            return true;
        return false;
    }
}

