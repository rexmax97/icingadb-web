<?php

/* Icinga DB Web | (c) 2020 Icinga GmbH | GPLv2 */

namespace Icinga\Module\Icingadb\Widget\Detail;

use Icinga\Module\Icingadb\Hook\ExtensionHook\ObjectDetailExtensionHook;
use Icinga\Module\Icingadb\Widget\EmptyState;
use ipl\Html\Html;
use ipl\Stdlib\Filter;

class HostDetail extends ObjectDetail
{
    protected $serviceSummary;

    public function __construct($object, $serviceSummary)
    {
        parent::__construct($object);

        $this->serviceSummary = $serviceSummary;
    }

    protected function createServiceStatistics()
    {
        if ($this->serviceSummary->services_total > 0) {
            $services = new ServiceStatistics($this->serviceSummary);
            $services->setBaseFilter(Filter::equal('host.name', $this->object->name));
        } else {
            $services = new EmptyState(t('This host has no services'));
        }

        return [Html::tag('h2', t('Services')), $services];
    }

    protected function assemble()
    {
        $this->add(ObjectDetailExtensionHook::injectExtensions([
            0   => $this->createPluginOutput(),
            190 => $this->createServiceStatistics(),
            300 => $this->createActions(),
            301 => $this->createNotes(),
            400 => $this->createComments(),
            401 => $this->createDowntimes(),
            500 => $this->createGroups(),
            501 => $this->createNotifications(),
            600 => $this->createCheckStatistics(),
            601 => $this->createPerformanceData(),
            700 => $this->createCustomVars(),
            701 => $this->createFeatureToggles()
        ], $this->createExtensions()));
    }
}
