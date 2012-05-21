Propel Admin Class
==================

A basic ``Admin`` class for a Propel model is defined by this service:

The admin class should extend the ``Sonata\PropelAdminBundle\Admin\Admin`` class.

.. code-block:: yaml

    services:
        depot.admin:
            class: Ormigo\Bundle\TransactionBundle\Admin\DepotAdmin
            arguments:
                - ~
                - 'Ormigo\Bundle\TransactionBundle\Model\Depot'
                - ''
            tags:
                -
                    name: 'sonata.admin'
                    manager_type: 'propel'
                    group: 'Transactions'
                    label: 'Depot'
