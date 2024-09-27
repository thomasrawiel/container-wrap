..  include:: /Includes.rst.txt

.. _start:

=========
Container wrap
=========

:Extension key:
    container_wrap

:Package name:
    traw/container-wrap

:Version:
    1.1

:Language:
    en

:Author:
    Thomas Rawiel

:License:
    This document is published under the
    `Creative Commons BY 4.0 <https://creativecommons.org/licenses/by/4.0/>`__
    license.

:Rendered:
    |today|

----

A collection of functions that wrap around EXT:container's Container registry,
which make it easier to adjust/ change the registered container's TCA.

----

What does it do?
================

EXT:container let's you create nested content elements ("containers") in a very simple way.

See :ref:`EXT:container's Documentation <ext-container-docs>`


However, if you want to add TYPO3's default fields like header, subheader, header_link, assets etc. to your container, you have to configure the corresponding TCA in your own distribution extension.

This extension provides functions that aim to make this step easier and more straight-forward.


----

..  card-grid::
    :columns: 1
    :columns-md: 2
    :gap: 4
    :class: pb-4
    :card-height: 100

    ..  card:: :ref:`Installation <installation>`

        Install EXT:container_wrap

    .. card:: :ref:`How to use <reference>`

        Register containers, add additional fields, change allowed fields

.. _issues: https://github.com/thomasrawiel/container-wrap/issues

For any feedback, suggestions or anything else regarding this extension, please use the :ref:`Github issue tracker <issues>`

.. _ext-container-docs: https://github.com/b13/container?tab=readme-ov-file#extcontainer---a-typo3-extension-for-creating-nested-content-elements


.. Table of Contents

.. toctree::
   :maxdepth: 3
   :titlesonly:
   :hidden:

   Installation/Index
   Reference/Index


.. Meta Menu

.. toctree::
   :hidden:

   Sitemap
