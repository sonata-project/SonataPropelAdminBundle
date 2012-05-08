Model Filter
============

A ``ModelFilter`` is provided to be added as a datagrid filter.

The counterpart for the forms is already provided by the Propel bridge;
you can simply use the ``ModelType`` as you would do on any ``Form``.

.. code-block:: php

    <?php

    namespace Acme\Bundle\LibraryBundle\Admin;

    use Sonata\AdminBundle\Admin\Admin;
    use Sonata\AdminBundle\Form\FormMapper;
    use Sonata\AdminBundle\Datagrid\DatagridMapper;

    class Book extends Admin
    {
        protected $baseRouteName = 'acme_library_book_admin';

        protected function configureFormFields(FormMapper $formMapper)
        {
            $formMapper
                ->add('author', 'model', array(
                    'class' => 'Acme\Bundle\LibraryBundle\Author',
                ), array())
            ;
        }

        protected function configureDatagridFilters(DatagridMapper $datagridMapper)
        {
            $datagridMapper
                ->add('id')
                ->add('author_id', 'propel_model', array(), null, array(
                    'class' => 'Acme\Bundle\LibraryBundle\Author',
                ))
            ;
        }
    }
