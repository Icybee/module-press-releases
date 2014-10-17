<?php

namespace Icybee\Modules\Press\Releases;

use ICanBoogie\Module\Descriptor;

return [

	Descriptor::TITLE => 'Press release',
	Descriptor::DESCRIPTION => "Introduces the <q>Press Release</q> content type.",
	Descriptor::CATEGORY => 'contents',
	Descriptor::INHERITS => 'contents',
	Descriptor::MODELS => [

		'primary' => 'inherit'
	],

	Descriptor::NS => __NAMESPACE__
];