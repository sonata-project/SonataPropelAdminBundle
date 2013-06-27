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

use Sonata\AdminBundle\Builder\ShowBuilderInterface;

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection;

use Sonata\AdminBundle\Guesser\TypeGuesserInterface;
        
/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class ShowBuilder implements ShowBuilderInterface
{
    /**
     * @var TypeGuesserInterface
     */
    protected $guesser;

    /**
     * @param \Sonata\AdminBundle\Guesser\TypeGuesserInterface $guesser
     */
    public function __construct(TypeGuesserInterface $guesser)
    {
        $this->guesser   = $guesser;
    }
    
    /**
     * @param array $options
     *
     * @return void
     */
    public function getBaseList(array $options = array())
    {
        // TODO: Implement getBaseList() method.
    }

    /**
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionCollection $list
     * @param null $type
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionInterface $fieldDescription
     * @param \Sonata\AdminBundle\Admin\AdminInterface $admin
     *
     * @return void
     */
    public function addField(FieldDescriptionCollection $list, $type = null, FieldDescriptionInterface $fieldDescription, AdminInterface $admin)
    {
        if ($type == null) {
            $guessType = $this->guesser->guessType($admin->getClass(), $fieldDescription->getName(), $admin->getModelManager());
            $fieldDescription->setType($guessType->getType());
        } else {
            $fieldDescription->setType($type);
        }

        $this->fixFieldDescription($admin, $fieldDescription);
        $admin->addShowFieldDescription($fieldDescription->getName(), $fieldDescription);

        $list->add($fieldDescription);
    }

    /**
     * @param \Sonata\AdminBundle\Admin\AdminInterface $admin
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionInterface $fieldDescription
     *
     * @return void
     */
    public function fixFieldDescription(AdminInterface $admin, FieldDescriptionInterface $fieldDescription)
    {
        $fieldDescription->setAdmin($admin);
    }
}
