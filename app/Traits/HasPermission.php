<?php

namespace App\Traits;

use Illuminate\Support\Arr;


trait HasPermission
{
    protected $abilities = [
        'index'     => 'read',
        'create'    => 'create',
        'store'     => 'create',
        'show'      => 'detail',
        'edit'      => 'update',
        'update'    => 'update',
        'menu'      => 'menu',
        'destroy'   => 'delete',
    ];

    public function callAction($method, $parameters)
    {
        $action = Arr::get($this->abilities, $method);

        if (!$action) {
            return parent::callAction($method, $parameters);
        }

        $staticPath = ltrim(request()->route()->getCompiled()->getStaticPrefix(), '/');
        $urlMenu    = urlMenu();

        if (in_array($staticPath, $urlMenu)) {
            $this->authorize("$action $staticPath");
        }

        return parent::callAction($method, $parameters);
    }
}
