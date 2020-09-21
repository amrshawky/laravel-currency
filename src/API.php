<?php

namespace AmrShawky\Currency;

use AmrShawky\Currency\Traits\HttpRequest;
use Closure;

abstract class API
{
    use HttpRequest;

    /**
     * @var null
     */
    protected $base_url  = null;

    /**
     * @var array
     */
    protected $query_params = [];

    /**
     * @var array
     */
    protected $query_params_callback = null;

    /**
     * @param Object $response
     *
     * @return mixed
     */
    protected abstract function getResults(Object $response);

    protected function buildQueryParams()
    {
        if ($this->query_params_callback !== null) {
            $this->query_params = call_user_func($this->query_params_callback);
        }

        if (!is_array($this->query_params)) {
            throw new \Exception('Query params should be an array!');
        }

        if (property_exists(get_called_class(), 'params') && !empty($this->params)) {
            foreach ($this->params as $key => $param) {
                $this->query_params[$key] = $param;
            }
        }
    }

    /**
     * @return mixed|null
     */
    public function get()
    {
        $this->buildQueryParams();

        $response = $this->request(
            $this->base_url,
            $this->query_params
        );

        return $response ? $this->getResults($response) : null;
    }

    /**
     * @param Closure $callback
     */
    protected function setQueryParams(Closure $callback)
    {
        $this->query_params_callback = $callback;
    }
}