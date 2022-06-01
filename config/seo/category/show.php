<?php

return [
    [
        'property' => 'og:url',
        'content' =>  '{{url.category}}',
    ],
    [
        'property' => 'og:title',
        'content' => '{{category.name}} - {{site_name}}',
    ],
    [
        'property' => 'og:description',
        'content' => '{{category.description}}',
    ],
    [
        'property' => 'og:type',
        'content' => 'website',
    ],
];
