<?php

/**
 * @author decima
 * Router class
 */
class Router {

    const FIXED_VAL = "fix";
    const VAR_VAL = "var";
    const TYPE_FILE = "file";
    const TYPE_FUNCTION = "function";
    const TYPE_UNKNOWN = "unknown";

    private $callers = array(Self::FIXED_VAL => array(), Self::VAR_VAL => array());

    public function assign($path, $fnct) {
        $ftype = self::TYPE_UNKNOWN;
        if (is_callable($fnct)) {
            $ftype = self::TYPE_FUNCTION;
        } elseif (file_exists($fnct)) {
            $ftype = self::TYPE_FILE;
        }
        $type = preg_match("/\{([a-zA-Z\_\-0-9]+)\}/", $path) ? self::VAR_VAL : self::FIXED_VAL;
        $path = "/" . $path . "/";
        do {
            $path = str_replace("//", "/", $path);
        } while (strstr($path, "//"));
        $path = addslashes($path);
        $this->callers[$type][$path] = new stdClass();
        $this->callers[$type][$path]->type = $ftype;
        $this->callers[$type][$path]->action = $fnct;
    }

    private function open_file($object, $params = array()) {
        $str = file_get_contents($object->action);
        foreach ($params as $k => $v) {
            $str = str_replace($k, $v, $str);
        }
        return $str;
    }

    public function proceed() {
        $path = "/";
        if (isset($_GET["route"])) {
            $path = $_GET["route"];
        }
        $path = "/" . $path . "/";
        do {
            $path = str_replace("//", "/", $path);
        } while (strstr($path, "//"));
        $path = addslashes($path);

        if (array_key_exists($path, $this->callers[self::FIXED_VAL])) {
            if ($this->callers[self::FIXED_VAL][$path]->type == self::TYPE_FILE)
                return $this->open_file($this->callers[self::FIXED_VAL][$path]);
            else if ($this->callers[self::FIXED_VAL][$path]->type == self::TYPE_FUNCTION)
                return call_user_func($this->callers[self::FIXED_VAL][$path]->action);
            else
                return $this->callers[self::FIXED_VAL][$path]->action;
        } else {
            $pa = trim($path, "/");
            $p = explode("/", $pa);
            $params = array();

            foreach ($this->callers[self::VAR_VAL] as $k => $v) {
                $r = explode("/", trim($k, "/"));
                if (count($p) != count($r)) {
                    goto preg_fail;
                }
                $params2 = array();
                $params = array();
                foreach ($r as $t => $ra) {
                    if (preg_match("/^([a-zA-Z\_\-0-9]+)$/", $ra)) {
                        if ($p[$t] != $ra) {
                            goto preg_fail;
                        }
                    } elseif (preg_match("/^\{([a-zA-Z\_\-0-9]+)\}$/", $ra, $e)) {
                        $params[] = $p[$t];
                        $params2[$e[0]] = $p[$t];
                    }
                }
                preg_success: {
                    if ($this->callers[self::VAR_VAL][$k]->type == self::TYPE_FILE)
                        return $this->open_file($this->callers[self::VAR_VAL][$k], $params2);
                    else if ($this->callers[self::VAR_VAL][$k]->type == self::TYPE_FUNCTION)
                        return call_user_func_array($v->action, $params);
                    else
                        return $this->callers[self::VAR_VAL][$k]->action;
                }
                preg_fail: {
                    continue;
                }
            }
        }
        throw new Err_not_found_404();
    }

}

class Err_not_found_404 extends Exception {}
