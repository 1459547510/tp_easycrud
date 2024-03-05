<?php

namespace Easycrud;

use Easycrud\command\Crud;
use think\Service as ThinkService;

class Service extends ThinkService
{
    public function boot()
    {
        $this->commands([
            Crud::class
        ]);
    }
}
