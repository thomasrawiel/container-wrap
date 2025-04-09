<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Container wrapper functions',
    'description' => 'Wrapper functions to make configuring b13/container easier',
    'category' => 'misc',
    'author' => 'Thomas Rawiel',
    'author_email' => 'thomas.rawiel@gmail.com',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '2.2.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.0.0-13.9.99',
            'container' => '1.4.0-3.99.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'content_defender' => '',
        ],
    ],
];
