<?php
// if the bundle is within a symfony project, try to reuse the project's autoload
$autoload = __DIR__.'/../../../../../../app/autoload.php';

// if the bundle is the project, try to use the composer's autoload for the tests
$composerAutoload = __DIR__.'/../vendor/autoload.php';

if (is_file($autoload)) {
    $loader = include $autoload;
} elseif (is_file($composerAutoload)) {
    $loader = include $composerAutoload;
} else {
    die('Unable to find autoload.php file, please use composer to load dependencies:

wget http://getcomposer.org/composer.phar
php composer.phar install

Visit http://getcomposer.org/ for more information.

');
}

$loader->add('Sonata\PropelAdminBundle\Tests', __DIR__);
$loader->add('Sonata\TestBundle', __DIR__ . '/Fixtures/App/src');
