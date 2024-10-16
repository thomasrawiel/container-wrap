..  include:: /Includes.rst.txt

..  _installation:

============
Installation
============

Install this extension via composer::

    composer require traw/container-wrap

or add it to your extension's composer.json file::

    "require": {
        "typo3/cms-core": "^12 || ^13",
        "traw/container-wrap": "^2.0"
    }

No additional configuration needed.

If you wish to allow/disallow CTypes in your container columns, it is recommended to install EXT:content_defender

    "require": {
        "typo3/cms-core": "^12 || ^13",
        "traw/container-wrap": "^2.0",
        "ichhabrecht/content-defender": "^3.4"
    }

Note: Currently, there's no version for TYPO3 13
