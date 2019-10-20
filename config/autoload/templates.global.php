<?php

/**
 * This file is part of coisa/movie-list.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/movie-list
 * @copyright Copyright (c) 2019 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

return [
    'templates' => [
        'extension' => 'phtml',
        'paths'     => [
            'error'    => __DIR__ . '/../../templates/error/',
            'layout'   => __DIR__ . '/../../templates/layout/',
            'pages'    => __DIR__ . '/../../templates/pages/',
            'partials' => __DIR__ . '/../../templates/partials/',
        ],
    ],
];
