<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Builder;

use Sonata\AdminBundle\Admin\FieldDescriptionCollection;
use Sonata\AdminBundle\Guesser\TypeGuesserInterface;

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
abstract class BaseBuilder
{
    /**
     * @var TypeGuesserInterface
     */
    protected $guesser;

    /**
     * @var array
     */
    protected $templates = array();

    /**
     * @param TypeGuesserInterface  $guesser
     * @param array                 $templates
     */
    public function __construct(TypeGuesserInterface $guesser, array $templates = array())
    {
        $this->guesser   = $guesser;
        $this->templates = $templates;
    }

    /**
     * @param array $options
     *
     * @return void
     */
    public function getBaseList(array $options = array())
    {
        return new FieldDescriptionCollection();
    }

    /**
     * Finds the template to use for a given field type.
     *
     * @param string $type
     *
     * @return string
     */
    protected function getTemplate($type)
    {
        if (!isset($this->templates[$type])) {
            return null;
        }

        return $this->templates[$type];
    }
}
