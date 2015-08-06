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
use Symfony\Component\Form\Guess\TypeGuess;

/**
 * Base type guesser.
 */
abstract class AbstractTypeGuesser implements TypeGuesserInterface
{
    /**
     * {@inheritdoc}
     */
    public function guessType($class, $property, ModelManagerInterface $modelManager)
    {
        $guesser = new PropelTypeGuesser();
        $guessedType = $guesser->guessType($class, $property);

        if ($guessedType->getType() === 'checkbox') {
            return new TypeGuess('boolean', $guessedType->getOptions(), $guessedType->getConfidence());
        }

        return $guessedType;
    }
}
