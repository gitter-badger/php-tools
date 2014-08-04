<?php

class Err_not_found_404 extends Exception {
    
}

class Router {

    const FIXED_VAL = "fix";
    const VAR_VAL = "var";

    private $callers = array(Self::FIXED_VAL => array(), Self::VAR_VAL => array());

    public function assign($path, $fnct) {
        $type = preg_match("/\{([a-zA-Z\_\-0-9]+)\}/", $path) ? self::VAR_VAL : self::FIXED_VAL;
        $path = "/" . $path . "/";
        do {
            $path = str_replace("//", "/", $path);
        } while (strstr($path, "//"));
        $this->callers[$type][$path] = $fnct;
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
        if (array_key_exists($path, $this->callers[self::FIXED_VAL])) {
            return $this->callers[self::FIXED_VAL][$path]();
        } else {
            $pa = trim($path, "/");
            $p = explode("/", $pa);
            $params = array();

            foreach ($this->callers[self::VAR_VAL] as $k => $v) {
                $r = explode("/", trim($k, "/"));
                if (count($p) != count($r)) {
                    goto preg_fail;
                }
                $params = array();
                foreach ($r as $t => $ra) {
                    if (preg_match("/^([a-zA-Z\_\-0-9]+)$/", $ra)) {
                        if ($p[$t] != $ra) {
                            goto preg_fail;
                        }
                    } elseif (preg_match("/^\{([a-zA-Z\_\-0-9]+)\}$/", $ra)) {
                        $params[] = $p[$t];
                    }
                }
                preg_success: {
                    return call_user_func_array($v, $params);
                }
                preg_fail: {
                    continue;
                }
            }
        }
        throw new Err_not_found_404();
    }

}
