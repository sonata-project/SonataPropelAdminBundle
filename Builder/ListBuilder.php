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

use Sonata\AdminBundle\Builder\ListBuilderInterface;

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection;
use Sonata\AdminBundle\Guesser\TypeGuesserInterface;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class ListBuilder implements ListBuilderInterface
{
    /**
     * @var TypeGuesserInterface
     */
    protected $guesser;

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
     * @return \Sonata\AdminBundle\Admin\FieldDescriptionCollection
     */
    public function getBaseList(array $options = array())
    {
        return new FieldDescriptionCollection();
    }

    /**
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionCollection $list
     * @param null|mixed $type
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionInterface $fieldDescription
     * @param \Sonata\AdminBundle\Admin\AdminInterface $admin
     */
    public function addField(FieldDescriptionCollection $list, $type = null, FieldDescriptionInterface $fieldDescription, AdminInterface $admin)
    {
        $this->buildField($type, $fieldDescription, $admin);
        $admin->addListFieldDescription($fieldDescription->getName(), $fieldDescription);

        $list->add($fieldDescription);
    }

    /**
     * @param \Sonata\AdminBundle\Admin\AdminInterface $admin
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionInterface $fieldDescription
     */
    public function fixFieldDescription(AdminInterface $admin, FieldDescriptionInterface $fieldDescription)
    {
        $fieldDescription->setAdmin($admin);

        if (!$fieldDescription->getTemplate()) {
            $fieldDescription->setTemplate($this->getTemplate($fieldDescription->getType()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildField($type = null, FieldDescriptionInterface $fieldDescription, AdminInterface $admin)
    {
        if ($type == null) {
            $guessType = $this->guesser->guessType($admin->getClass(), $fieldDescription->getName(), $admin->getModelManager());
            $fieldDescription->setType($guessType->getType());
        } else {
            $fieldDescription->setType($type);
        }

        $this->fixFieldDescription($admin, $fieldDescription);
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
