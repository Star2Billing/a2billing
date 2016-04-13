<?php

namespace Factory;


use Contracts\Factory\Factory;
use Smarty_Security;

class SmartyFactory implements Factory{

    /**
     * @return \Smarty
     * @throws \SmartyException
     */
    public static function getInstance()
    {
        $smarty = new \SmartyBC();

        $securityPolicy = new Smarty_Security($smarty);
        $securityPolicy->php_handling = \Smarty::PHP_ALLOW;
        $smarty->enableSecurity($securityPolicy);

        return $smarty;
    }
}