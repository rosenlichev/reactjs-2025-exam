<?php

use Illuminate\Support\Collection;

/**
 * Abstract Service Class
 * 
 */
abstract class CwsBaseService
{

    public $vars;

    public function __construct($vars = [])
    {
        $this->setVars($vars);
    }

    /**
     * Основна функция за сетване на параметрите
     *
     * @param  mixed  $vars
     * @return void
     */
    public function setVars($vars)
    {
        if ($vars instanceof Collection) {
            $this->vars = $vars;
        } else {
            $this->vars = collect($vars);
        }

        return $vars;
    }

}
