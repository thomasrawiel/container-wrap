..  include:: /Includes.rst.txt

..  _add-fields:

============
Additional fields
============

Additional tt_content fields can be added to your container by :ref:`using the additional fields config when registering containers <tt-content>`


In the scenario where you want to enable or disable additional fields after you have registered the containers, e.g. in a theme extension,
simply call the corresponding functions

.. hint::
    Only the configuration options 'header', 'bodytext', 'media', 'settings', 'flexform' can be changed with these functions

Enable fields for one container
==

.. confval:: setupShowItemForContainer(string $cType, array $configuration)

   :CType: The container CType to change
   :configuration: An array containing the additional fields that should be changed

.. code-block:: php
    //Enable header, media and bodytext fields for `b13-2cols-with-header-container`
    \TRAW\ContainerWrap\Configuration\Container::setupShowItemForContainer(
        'b13-2cols-with-header-container',
        [
            'header' => true,
            'media' => true,
            'bodytext' => true,
        ]
    );

Enable fields for multiple containers
==

.. confval:: setupShowItemForContainers(array $containerCTypes, array $configuration): void

   :containerCTypes: An array containing the CTypes of containers
   :configuration: An array containing the additional fields that should be changed

.. code-block:: php
    //Enable the header palette for these 3 containers
    \TRAW\ContainerWrap\Configuration\Container::setupShowItemForContainers(
        [
            'my-container-1',
            'my-container-2',
            'my-container-3',
        ],
        [
            'header' => true,
        ]
    );

Enable fields for all containers
==

.. confval:: setupShowItemForAllContainers(array $configuration, array $exceptions = [])

   :configuration: An array containing the additional fields that should be changed
   :exceptions: **(Optional)** An array containing container CTypes where the change should **NOT** be applied

.. code-block:: php
    //Enable the bodytext field for all containers, except these 2 containers
    \TRAW\ContainerWrap\Configuration\Container::setupShowItemForAllContainers(
        [
            'bodytext' => true,
        ],
        [
            'my-container-3',
            'my-container-4'
        ]
    );