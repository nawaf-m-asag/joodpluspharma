<?php

namespace Botble\Medical;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('services');
    }
}
