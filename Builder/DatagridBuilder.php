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

use Sonata\AdminBundle\Builder\DatagridBuilderInterface;

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Filter\FilterFactoryInterface;
use Sonata\AdminBundle\Guesser\TypeGuesserInterface;

use Sonata\PropelAdminBundle\Datagrid\Pager;
use Sonata\PropelAdminBundle\Datagrid\Datagrid;

use Symfony\Component\Form\FormFactory;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class DatagridBuilder implements DatagridBuilderInterface
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var FilterFactoryInterface
     */
    protected $filterFactory;

    /**
     * @var TypeGuesserInterface
     */
    protected $guesser;

    /**
     * @param \Symfony\Component\Form\FormFactory               $formFactory
     * @param \Sonata\AdminBundle\Filter\FilterFactoryInterface $filterFactory
     * @param \Sonata\AdminBundle\Guesser\TypeGuesserInterface  $guesser
     */
    public function __construct(FormFactory $formFactory, FilterFactoryInterface $filterFactory, TypeGuesserInterface $guesser)
    {
        $this->formFactory = $formFactory;
        $this->filterFactory = $filterFactory;
        $this->guesser = $guesser;
    }

    /**
     * @param AdminInterface $admin
     * @param FieldDescriptionInterface $fieldDescription
     */
    public function fixFieldDescription(AdminInterface $admin, FieldDescriptionInterface $fieldDescription)
    {
        $fieldDescription->setAdmin($admin);

        // filters are not required by default
        $fieldDescription->mergeOption('field_options', array('required' => false));
    }

    /**
     * @param DatagridInterface $datagrid
     * @param string $type
     * @param FieldDescriptionInterface $fieldDescription
     * @param AdminInterface $admin
     */
    public function addFilter(DatagridInterface $datagrid, $type = null, FieldDescriptionInterface $fieldDescription, AdminInterface $admin)
    {
        if ($type == null) {
            $guessType = $this->guesser->guessType($admin->getClass(), $fieldDescription->getName(), $admin->getModelManager());

            $type = $guessType->getType();

            $fieldDescription->setType($type);

            $fieldDescription->mergeOption('field_options', $guessType->getOptions());
        } else {
            $fieldDescription->setType($type);
        }

        $this->fixFieldDescription($admin, $fieldDescription);

        $admin->addFilterFieldDescription($fieldDescription->getName(), $fieldDescription);

        $fieldDescription->mergeOption('field_options', array('required' => false));
        $filter = $this->filterFactory->create($fieldDescription->getName(), $type, $fieldDescription->getOptions());
        if (!$filter->getLabel()) {
            $filter->setLabel($admin->getLabelTranslatorStrategy()->getLabel($fieldDescription->getName(), 'filter', 'label'));
        }

        $datagrid->addFilter($filter);
    }

    /**
     * Create a new Datagrid.
     *
     * @param AdminInterface $admin
     * @param array $values
     *
     * @return DatagridInterface
     */
    public function getBaseDatagrid(AdminInterface $admin, array $values = array())
    {
        $pager = new Pager();
        $pager->setCountColumn($admin->getModelManager()->getIdentifierFieldNames($admin->getClass()));

        $formBuilder = $this->formFactory->createNamedBuilder('filter', 'form', array(), array('csrf_protection' => false));

        return new Datagrid($admin->createQuery('list'), $admin->getList(), $pager, $formBuilder, $values);
    }
}
