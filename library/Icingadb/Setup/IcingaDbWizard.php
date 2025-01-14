<?php

/* Icinga DB Web | (c) 2020 Icinga GmbH | GPLv2 */

namespace Icinga\Module\Icingadb\Setup;

use Icinga\Application\Platform;
use Icinga\Module\Setup\Forms\SummaryPage;
use Icinga\Module\Setup\Requirement\PhpModuleRequirement;
use Icinga\Module\Setup\Requirement\PhpVersionRequirement;
use Icinga\Module\Setup\Requirement\WebLibraryRequirement;
use Icinga\Module\Setup\RequirementSet;
use Icinga\Module\Setup\Setup;
use Icinga\Module\Setup\SetupWizard;
use Icinga\Web\Form;
use Icinga\Web\Request;
use Icinga\Web\Wizard;

class IcingaDbWizard extends Wizard implements SetupWizard
{
    protected function init()
    {
        $this->addPage(new WelcomePage());
        $this->addPage(new DbResourcePage());
        $this->addPage(new RedisPage());
        $this->addPage(new ApiTransportPage());
        $this->addPage(new SummaryPage(['name' => 'setup_icingadb_summary']));
    }

    public function setupPage(Form $page, Request $request)
    {
        if ($page->getName() === 'setup_icingadb_summary') {
            $page->setSummary($this->getSetup()->getSummary());
            $page->setSubjectTitle('Icinga DB Web');
        }
    }

    public function getSetup()
    {
        $pageData = $this->getPageData();
        $setup = new Setup();

        $setup->addStep(new DbResourceStep($pageData['setup_icingadb_resource']));
        $setup->addStep(new RedisStep($pageData['setup_icingadb_redis']));
        $setup->addStep(new ApiTransportStep($pageData['setup_icingadb_api_transport']));

        return $setup;
    }

    public function getRequirements()
    {
        $set = new RequirementSet();

        $phpVersion = Platform::getPhpVersion();

        if (
            version_compare($phpVersion, '7.0', '>=')
            && version_compare($phpVersion, '7.3', '<')
        ) {
            $set->add(new PhpVersionRequirement([
                'optional'      => true,
                'condition'     => ['>=', '7.3'],
                'description'   => t(
                    'For Icinga DB Web PHP 7.3+ is highly recommended.'
                    . ' Older versions still work, but may stop doing so with near future updates.'
                )
            ]));
        } else {
            $set->add(new PhpVersionRequirement([
                'condition'     => ['>=', '7.3'],
                'description'   => t('Icinga DB Web requires PHP version 7.3.')
            ]));
        }

        $set->add(new WebLibraryRequirement([
            'condition'     => ['icinga-php-library', '>=', '0.7.0'],
            'alias'         => 'Icinga PHP library',
            'description'   => t('The Icinga PHP library (IPL) is required for Icinga DB Web')
        ]));

        $set->add(new WebLibraryRequirement([
            'condition'     => ['icinga-php-thirdparty', '>=', '0.10.0'],
            'alias'         => 'Icinga PHP Thirdparty',
            'description'   => t('The Icinga PHP Thirdparty library is required for Icinga DB Web')
        ]));

        $set->add(new PhpModuleRequirement([
            'condition'     => 'libxml',
            'alias'         => 'libxml',
            'description'   => t('For check plugins that output HTML the libxml extension is required')
        ]));

        $set->add(new PhpModuleRequirement([
            'condition'     => 'curl',
            'alias'         => 'cURL',
            'description'   => t(
                'To send external commands over Icinga 2\'s API, the cURL module for PHP is required.'
            )
        ]));

        return $set;
    }
}
