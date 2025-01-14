<?php

/* Icinga DB Web | (c) 2020 Icinga GmbH | GPLv2 */

namespace Icinga\Module\Icingadb\Common;

use Icinga\Module\Icingadb\Model\Host;
use ipl\Web\Url;

abstract class HostLinks
{
    public static function acknowledge(Host $host)
    {
        return Url::fromPath('icingadb/host/acknowledge', ['name' => $host->name]);
    }

    public static function addComment(Host $host)
    {
        return Url::fromPath('icingadb/host/add-comment', ['name' => $host->name]);
    }

    public static function checkNow(Host $host)
    {
        return Url::fromPath('icingadb/host/check-now', ['name' => $host->name]);
    }

    public static function scheduleCheck(Host $host)
    {
        return Url::fromPath('icingadb/host/schedule-check', ['name' => $host->name]);
    }

    public static function comments(Host $host)
    {
        return Url::fromPath('icingadb/comments', ['host.name' => $host->name]);
    }

    public static function downtimes(Host $host)
    {
        return Url::fromPath('icingadb/downtimes', ['host.name' => $host->name]);
    }

    public static function history(Host $host)
    {
        return Url::fromPath('icingadb/host/history', ['name' => $host->name]);
    }

    public static function removeAcknowledgement(Host $host)
    {
        return Url::fromPath('icingadb/host/remove-acknowledgement', ['name' => $host->name]);
    }

    public static function scheduleDowntime(Host $host)
    {
        return Url::fromPath('icingadb/host/schedule-downtime', ['name' => $host->name]);
    }

    public static function sendCustomNotification(Host $host)
    {
        return Url::fromPath('icingadb/host/send-custom-notification', ['name' => $host->name]);
    }

    public static function processCheckresult(Host $host)
    {
        return Url::fromPath('icingadb/host/process-checkresult', ['name' => $host->name]);
    }

    public static function toggleFeatures(Host $host)
    {
        return Url::fromPath('icingadb/host/toggle-features', ['name' => $host->name]);
    }

    public static function services(Host $host)
    {
        return Url::fromPath('icingadb/host/services', ['name' => $host->name]);
    }
}
