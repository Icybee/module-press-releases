<?php

namespace ICanBoogie;

return array
(
	Module::T_TITLE => 'Press release',
	Module::T_DESCRIPTION => "Introduces the <q>Press Release</q> content type",
	Module::T_CATEGORY => 'contents',
	Module::T_EXTENDS => 'contents',
	Module::T_MODELS => array
	(
		'primary' => 'inherit'
	)
);