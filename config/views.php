<?php

namespace Icybee\Modules\Press\Releases;

use Icybee\Modules\Views\ViewOptions as Options;

return [

	'press.releases' => [

		Options::DIRECTIVE_INHERITS => 'contents',

		'list' => [

			Options::ASSETS => [

				'../public/page.css'

			]
		]
	]
];
