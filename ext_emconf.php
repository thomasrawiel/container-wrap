<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Container wrapper functions',
    'description' => 'Wrapper functions to make configuring b13/container easier',
    'category' => 'misc',
    'author' => 'Thomas Rawiel',
    'author_email' => 'thomas.rawiel@gmail.com',
    'state' => 'stable',
    'version' => '2.4.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-14.9.99',
            'container' => '3.0.0-3.99.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'content_defender' => '',
        ],
    ],
];
