<?php

/** @var $this \Icinga\Application\Modules\Module */

use Icinga\Application\Icinga;
use Icinga\Application\Modules\Manager;
use Icinga\Application\Modules\Module;

 $this->provideHook('ApplicationState');
 $this->provideHook('X509/Sni');
 $this->provideHook('health', 'IcingaHealth');
 $this->provideHook('health', 'RedisHealth');

$mm = Icinga::app()->getModuleManager();
if (! $mm->hasEnabled('monitoring')) {
    $monitoring = new Module(Icinga::app(), 'monitoring', $mm->getModuleDir('monitoring'));

    $launchedConfigScript = new ReflectionProperty(Module::class,'triedToLaunchConfigScript');
    $launchedConfigScript->setAccessible(true);
    $launchedConfigScript->setValue($monitoring, true);

    $registerAutoloader = new ReflectionMethod(Module::class, 'registerAutoloader');
    $registerAutoloader->setAccessible(true);
    $registerAutoloader->invoke($monitoring);

    $registered = new ReflectionProperty(Module::class, 'registered');
    $registered->setAccessible(true);
    $registered->setValue($monitoring, true);

    $loadedModules = new ReflectionProperty(Manager::class, 'loadedModules');
    $loadedModules->setAccessible(true);
    $loadedModules->setValue($mm, $loadedModules->getValue($mm) + ['monitoring' => $monitoring]);

    $this->provideNavigationItem('host-action', $this->translate('Host Action'));
    $this->provideNavigationItem('service-action', $this->translate('Service Action'));
}
