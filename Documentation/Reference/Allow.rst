..  include:: /Includes.rst.txt

..  _allow:

============
Allow / Disallow CTypes
============

.. _content-defender: https://github.com/IchHabRecht/content_defender/blob/main/README.md

This requires the extension `ichhabrecht/content-defender` to be installed.

It is possible to allow and disallow CTypes when registering your container configurations, as well as set a maxitems

See :ref:`Content Defender README <content-defender>`

Typically you would want to set these in your initial `columnConfiguration` (see :ref:`register_traw`)

.. code-block:: php
    'columnConfiguration' => [
        [
            [
                'name' => 'Content',
                'colPos' => 100,
                'allowed' => [
                    'CType' => 'textmedia',
                ],
            ],
        ],
    ]

.. code-block:: php
    'columnConfiguration' => [
        [
            [
                'name' => 'Sidebar',
                'colPos' => 100,
                'disallowed' => [
                    'CType' => 'list',
                ],
            ],
        ],
    ]


.. _function-reference:
In the scenario where you want to allow or disallow CTypes after you have registered the containers, e.g. in a theme extension,
simply call the corresponding functions


.. confval:: allowInAllContainers(array $cTypes, array $exceptions = [])

   :cTypes: an array of CTypes that should be (additionally) allowed in all containers
   :exceptions: **(Optional)** an array of container CTypes where this change should **NOT** be applied

   The given CTypes are added to the allowed CTypes of **all** containers.

.. confval:: allowInSpecificContainers(array $cTypes, array $allowInContainers, array $allowInColumns = [])

   :cTypes: an array of CTypes that should be (additionally) allowed in all containers
   :allowInContainers: an array of container CTypes, where this change should be applied
   :allowInColumns: **(Optional)** an array of columns where this change should be applied, if omitted the change will be applied to all columns of the container

   The given CTypes are added to the allowed CTypes of the given containers

.. hint::
   If a container/column doesn't already have allowed CTypes, only the CTypes added with these allow* functions will be allowed


.. confval:: allowInAllContainers(array $cTypes, array $exceptions = [])

   :cTypes: an array of CTypes that should be (additionally) allowed in all containers
   :exceptions: **(Optional)** an array of container CTypes where this change should **NOT** be applied

   The given CTypes are added to the allowed CTypes of **all** containers.

.. confval:: disallowInSpecificContainers(array $cTypes, array $disallowInContainers, array $disallowInColumns = [])

   :cTypes: an array of CTypes that should be (additionally) allowed in all containers
   :allowInContainers: an array of container CTypes, where this change should be applied
   :allowInColumns: **(Optional)** an array of columns where this change should be applied, if omitted the change will be applied to all columns of the container

   The given CTypes are added to the allowed CTypes of the given containers


.. code-block:: php
    //disallow any list-plugin in all containers
    \TRAW\ContainerWrap\Configuration\Container::disallowInAllContainers(['list']);

    //disallow image CType in my-container-1 and my-containr-2 if the colPos is 101 or 102
    \TRAW\ContainerWrap\Configuration\Container::disallowInSpecificContainers(
        ['image'],
        ['my-container-1', 'my-container-2'],
        [101, 102]
    );

    //allow textmedia CType in all containers, except my-container-1
    \TRAW\ContainerWrap\Configuration\Container::allowInAllContainers(['textmedia'], ['my-container-1']);