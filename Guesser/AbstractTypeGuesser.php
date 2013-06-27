<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Guesser;

use Sonata\AdminBundle\Guesser\TypeGuesserInterface;
use Sonata\AdminBundle\Model\ModelManagerInterface;

use Symfony\Bridge\Propel1\Form\PropelTypeGuesser;

/**
 * Base type guesser
 */
abstract class AbstractTypeGuesser implements TypeGuesserInterface
{
    /**
     * {@inheritDoc}
     */
    public function guessType($class, $property, ModelManagerInterface $modelManager)
    {
        $guesser = new PropelTypeGuesser();

        return $guesser->guessType($class, $property);
    }
}
