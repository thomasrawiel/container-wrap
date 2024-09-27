..  include:: /Includes.rst.txt

..  _register:
.. _containergithub: https://github.com/b13/container-example/blob/master/Configuration/TCA/Overrides/tt_content.php

============
Register Containers
============

..  note::
    Every configuration mentioned should be placed in your extension's `Configuration/TCA/Overrides/tt_content.php`

.. _register_b13:
Register containers with EXT:container alone
=============

With EXT:container you would typically go ahead and register each container seperately or in a foreach loop if you want to register multiple containers, e.g.

..  code-block:: php
    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
        (
            new \B13\Container\Tca\ContainerConfiguration(
                'b13-2cols-with-header-container', // CType
                '2 Column Container With Header', // label
                'Some Description of the Container', // description
                [
                    [
                        ['name' => 'header', 'colPos' => 200, 'colspan' => 2, 'allowed' => ['CType' => 'header, textmedia, b13-2cols']]
                    ],
                    [
                        ['name' => 'left side', 'colPos' => 201],
                        ['name' => 'right side', 'colPos' => 202, 'maxitems' => 1]
                    ]
                ] // grid configuration
            )
        )
        // override default configurations
        ->setIcon('EXT:container_example/Resources/Public/Icons/b13-2cols-with-header-container.svg')
        ->setSaveAndCloseInNewContentElementWizard(false)
    );

(taken from :ref:`EXT:container_example <containergithub>`)

Then you would need to add your fields manually via `$GLOBALS['TCA']['tt_content']` or functions like `addTcaColumns`, `addFieldsToPalette`, etc. from TYPO3s `ExtensionManagementUtility`
and maybe manipulate the `showitem` config of your content element.


.. _register_traw:
Register containers with EXT:container-wrap
=============

In your `Configuration/TCA/Overrides/tt_content.php` create an array with your container configurations:

..  code-block:: php
    $containers = [
      'b13-2cols-with-header-container' => [ //CType
          'label' => '2 Column Container With Header', //label
          'description' => 'Some Description of the Container', //description
          'columnConfiguration' => [
              [
                  ['name' => 'header', 'colPos' => 200, 'colspan' => 2, 'allowed' => ['CType' => 'header, textmedia, b13-2cols']]
              ],
              [
                  ['name' => 'left side', 'colPos' => 201],
                  ['name' => 'right side', 'colPos' => 202, 'maxitems' => 1]
              ]
          ], //grid configuration
          //optional keys:
          'icon' => 'EXT:container/Resources/Public/Icons/container-1col.svg',
          'backendTemplate'=>'EXT:lin_container/Resources/Private/Templates/Backend/Container.html',
          'gridTemplate' => 'EXT:container/Resources/Private/Templates/Grid.html',
          'registerInNewContentElementWizard' => true,
          'saveAndCloseInNewContentElementWizard' => true,
          'group' => 'my-container-group', //CType select item group
          'header' => true,
          'bodytext' => false,
          'media' => false,
          'settings' => true,
          'flexform' => false,
      ],
      'my-container-2' => [
          // ...
          // config for my-container-2
          // ...
      ]
    ];
and call the register function

.. code-block:: php
    \TRAW\ContainerWrap\Configuration\Container::registerContainers($containers);

..  note::
    Minimum required config keys are: `CType` (the array key),   `label`, `description` and `columnConfiguration`. Every other configuration has a default value.
    See :ref:`Configuration options <configuration-options>`



