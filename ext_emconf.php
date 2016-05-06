<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'VXVR Booster',
    'description' => 'Boosts your TYPO3 instance with class preloading',
    'category' => 'misc',
    'constraints' => array(
        'depends' => array(
            'typo3' => '7.6.0-7.99.99',
        ),
        'conflicts' => array(),
        'suggests' => array(),
    ),
    'state' => 'beta',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearcacheonload' => true,
    'author' => 'Oliver Eglseder',
    'author_email' => 'php@vxvr.de',
    'author_company' => 'vxvr.de',
    'version' => '0.1.0',
);
