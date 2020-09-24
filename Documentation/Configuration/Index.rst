.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

General Usage
-------------

First you include the basic TypoScript settings into your page template (TYPO3 8.7 or above):

|include-typoscript-template|

For TYPO3 7.6 you have to include the default TypoScripts manually in your setup and constants:

::
    # Setup
    <INCLUDE_TYPOSCRIPT: source="FILE: EXT:t2_scss/Configuration/TypoScript/setup.typoscript">

    # Constants
    <INCLUDE_TYPOSCRIPT: source="FILE: EXT:t2_scss/Configuration/TypoScript/constants.typoscript">

To start watching a folder for SCSS files use the following TypoScript:

::
    plugin.tx_t2scss {
        files {
            inputFolder = path/to/your/scss/files/
            outputFolder = path/to/your/compiled/css/files/
        }
    }

Settings
--------

::
    plugin.tx_t2scss {
        # change file extensions that will be watched (default: scss, sass)
        files.extensions = scss,sass
        settings {
            # this turns on/off the extension (default: 1)
            activeCompiler = 1

            # delete generated CSS files with no corresponding source file (recommended)
            unlinkCssFilesWithNoSourceFile = 1

            # compiled CSS file will be compressed if you check this (recommended)
            compressed = 1

            # turns off the caching and compiles on every request (not recommended in production)
            forceMode = 0

            include {
                # default settings for includeCSS
                default {
                    media = all
                    title =
                    compress = 1
                    allWrap =
                    excludeFromConcatenation = 0
                }

                # configure includeCSS settings per file
                my_scss_file_name {
                    media = print
                }
            }
        }
    }
