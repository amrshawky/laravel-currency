<?php

namespace AmrShawky\Currency\Traits;

trait ParamsOverload
{
    /**
     * @var
     */
    protected $params = [];

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return $this
     * @throws \Exception
     */
    public function __call(string $name , array $arguments)
    {
        if (!method_exists(get_called_class(), $name)) {
            if (property_exists(get_called_class(), 'available_params') && in_array($name, $this->available_params)) {
                if (empty($arguments)) {
                    throw new \Exception("Method require one parameter");
                }
                $this->params[$name] = $arguments[0];
                return $this;
            }
        }
        throw new \Exception("Method {$name}() not found");
    }
}