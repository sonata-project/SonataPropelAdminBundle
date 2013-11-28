<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Tests\Functionnal;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class WebTestCase extends BaseWebTestCase
{
    protected static $application;

    public static function setUpBeforeClass()
    {
        self::runCommand('propel:build');
        self::runCommand('propel:sql:insert --force');
        self::runCommand('propel:fixtures:load');
    }

    protected static function runCommand($command, $hideOutput = true)
    {
        $application = self::getApplication();

        return $application->run(new StringInput($command), $hideOutput ? new NullOutput() : null);
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $kernel = self::createKernel();
            $kernel->boot();

            self::$application = new Application($kernel);
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }
}
