<?php

namespace Icybee\Modules\Press\Releases;

use ICanBoogie\Module;

return [

	Module::T_TITLE => 'Press release',
	Module::T_DESCRIPTION => "Introduces the <q>Press Release</q> content type.",
	Module::T_CATEGORY => 'contents',
	Module::T_EXTENDS => 'contents',
	Module::T_MODELS => [

		'primary' => 'inherit'
	],

	Module::T_NAMESPACE => __NAMESPACE__
];