..  include:: /Includes.rst.txt

..  _configuration-options:

============
Configuration options
============

When registering a container you have the following options:

- :ref:`required fields <required>`
- :ref:`optional fields <optional>`
- :ref:`enable tt-content fields <tt-content>`

.. _required:
Required
==

.. confval:: CType

   :type: string
   :Path: `$config['your-ctype']`

    The CType used to identify your container

.. confval:: label

   :type: string
   :Path: `$config['your-ctype']['label']`

    The label of your container

.. confval:: description

   :type: string
   :Path: `$config['your-ctype']['description']`

    The description of your container, will be displayed for example in the content element wizard

.. confval:: columnConfiguration

   :type: array
   :Default: EXT:container/Resources/Public/Icons/Extension.svg
   :Path: `$config['your-ctype']['columnConfiguration']`

    The configuration for your containers columns

    See: https://github.com/b13/container?tab=readme-ov-file#adding-your-own-container-element

.. _optional:
Optional
==

Also see https://github.com/b13/container?tab=readme-ov-file#methods-of-the-containerconfiguration-object

.. confval:: icon

   :type: string
   :Default: EXT:container/Resources/Public/Icons/Extension.svg
   :Path: `$config['your-ctype']['icon']`

   An icon file, or existing icon identifier representing your container

.. confval:: backendTemplate

   :type: string
   :Default: EXT:container/Resources/Private/Templates/Container.html
   :Path: `$config['your-ctype']['backendTemplate']`

    The Fluid template used for the backend view

.. confval:: gridTemplate

   :type: string
   :Default: EXT:container/Resources/Private/Templates/Grid.html
   :Path: `$config['your-ctype']['gridTemplate']`

    Template for grid

.. confval:: registerInNewContentElementWizard

   :type: boolean
   :Default: true
   :Path: `$config['your-ctype']['registerInNewContentElementWizard']`

    Template for grid

.. confval:: saveAndCloseInNewContentElementWizard

   :type: boolean
   :Default: true
   :Path: `$config['your-ctype']['saveAndCloseInNewContentElementWizard']`

    Template for grid

.. confval:: group

   :type: string
   :Default: container
   :Path: `$config['your-ctype']['group']`

   CType select item group

   The default value can be changed to `$_EXTKEY.'_container'` (myext_container) if you call the register function with the optional parameter

.. _tt-content:
Enable tt-content fields
==

.. confval:: header

   :type: boolean
   :Default: false
   :Path: `$config['your-ctype']['header']`

   - if true, will add the full headers palette (header, subheader, header_link,etc.)
   - if false, will only add the header field

.. confval:: bodytext

   :type: boolean
   :Default: false
   :Path: `$config['your-ctype']['bodytext']`

   if true, will add the bodytext field with richtext enabled

.. confval:: media

   :type: boolean
   :Default: false
   :Path: `$config['your-ctype']['media']`

   If true will add the media `--div--` with the `assets` field

.. confval:: settings

   :type: boolean
   :Default: false
   :Path: `$config['your-ctype']['settings']`

   If true, will add an empty palette `containerSettings` in a new `--div--` "Container"
   If you configure custom fields for containers you could add them here, e.g. with `ExtensionManagementUtility::addFieldsToPalette('tt_content', 'containerSettings', 'tx_yourfield')`

.. confval:: flexform

   :type: string|boolean
   :Default: false
   :Path: `$config['your-ctype']['flexform']`

   If _not_ false, the palette `containerFlexform` will be added to the Container `--div--`, which contains the `pi_flexform` field

   If it contains a FILE string to a flexform xml, this will be added with `ExtensionManagementUtility::addPiFlexFormValue()` for this CType

   Example: `'flexform' => 'FILE:EXT:my_ext/Configuration/Flexforms/MyContainerSettings.xml',`


.. confval:: additionalFields

    :type: boolean
    :Default: false
    :Path: `$config['your-ctype']['additionalFields']`

    If true, will add an empty palette `containerAdditionalFields` to the Extended tab
    If you configure custom fields for containers you could add them here, e.g. with `ExtensionManagementUtility::addFieldsToPalette('tt_content', 'containerAdditionalFields', 'tx_yourfield')`


.. confval:: columnsOverrides

 :type: array
 :Default: false
 :Path: `$config['your-ctype']['columnsOverrides']`

 Changed or added ['columns'] field display definitions. See :ref:`columnsOverrides <t3tca:confval-types-columnsoverrides>`

