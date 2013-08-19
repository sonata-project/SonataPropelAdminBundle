Templates
=========

You can customize the global layout by tweaking the ``SonataAdminBundle`` configuration.

.. code-block:: yaml

    sonata_admin:
        templates:
            # default global templates
            layout:  SonataAdminBundle::standard_layout.html.twig
            ajax:    SonataAdminBundle::ajax_layout.html.twig

            # default value if done set, actions templates, should extend global templates
            list:    SonataAdminBundle:CRUD:list.html.twig
            show:    SonataAdminBundle:CRUD:show.html.twig
            edit:    SonataAdminBundle:CRUD:edit.html.twig


You can also customize field types by adding types in the ``config.yml`` file. The default values are :

.. code-block:: yaml

    sonata_propel_orm_admin:
        templates:
            types:
                list:
                    array:      SonataAdminBundle:CRUD:list_array.html.twig
                    boolean:    SonataAdminBundle:CRUD:list_boolean.html.twig
                    date:       SonataAdminBundle:CRUD:list_date.html.twig
                    time:       SonataAdminBundle:CRUD:list_time.html.twig
                    datetime:   SonataAdminBundle:CRUD:list_datetime.html.twig
                    text:       SonataAdminBundle:CRUD:list_string.html.twig
                    trans:      SonataAdminBundle:CRUD:list_trans.html.twig
                    string:     SonataAdminBundle:CRUD:list_string.html.twig
                    smallint:   SonataAdminBundle:CRUD:list_string.html.twig
                    bigint:     SonataAdminBundle:CRUD:list_string.html.twig
                    integer:    SonataAdminBundle:CRUD:list_string.html.twig
                    decimal:    SonataAdminBundle:CRUD:list_string.html.twig
                    identifier: SonataAdminBundle:CRUD:list_string.html.twig
                    currency:   SonataAdminBundle:CRUD:list_currency.html.twig
                    percent:    SonataAdminBundle:CRUD:list_percent.html.twig

                show:
                    array:      SonataAdminBundle:CRUD:show_array.html.twig
                    boolean:    SonataAdminBundle:CRUD:show_boolean.html.twig
                    date:       SonataAdminBundle:CRUD:show_date.html.twig
                    time:       SonataAdminBundle:CRUD:show_time.html.twig
                    datetime:   SonataAdminBundle:CRUD:show_datetime.html.twig
                    text:       SonataAdminBundle:CRUD:base_show_field.html.twig
                    trans:      SonataAdminBundle:CRUD:show_trans.html.twig
                    string:     SonataAdminBundle:CRUD:base_show_field.html.twig
                    smallint:   SonataAdminBundle:CRUD:base_show_field.html.twig
                    bigint:     SonataAdminBundle:CRUD:base_show_field.html.twig
                    integer:    SonataAdminBundle:CRUD:base_show_field.html.twig
                    decimal:    SonataAdminBundle:CRUD:base_show_field.html.twig
                    currency:   SonataAdminBundle:CRUD:base_currency.html.twig
                    percent:    SonataAdminBundle:CRUD:base_percent.html.twig

.. note::

    By default, if the ``SonataIntlBundle`` classes are available, then the numeric and date fields will be
    localized with the current user locale.
