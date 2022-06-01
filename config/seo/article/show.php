<?php

return [
    [
        'property' => 'og:url',
        'content' =>  '{{url.article}}',
    ],
    [
        'property' => 'og:title',
        'content' => '{{article.title}} - {{site_name}}',
    ],
    [
        'property' => 'og:description',
        'content' => '{{article.body}}',
    ],
    [
        'property' => 'og:type',
        'content' => 'article',
    ],
    [
        'property' => 'article:published_time',
        'content' => '{{article.created_at}}',
    ],
    [
        'property' => 'article:modified_time',
        'content' => '{{article.updated_at}}',
    ],
    [
        'property' => 'article:tags',
        'content' =>  [
            '_type' => 'loop',
            'dataSelector' => 'article.tags',
            'template' => '{{tag.name}}'
        ],
    ],
];
