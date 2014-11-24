<?php

/*
 * This file is part of the Icybee package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Icybee\Modules\Press\Releases;

use Icybee\Modules\Views\ViewOptions;

/**
 * Introduces the Press Release content type.
 */
class Module extends \Icybee\Modules\Contents\Module
{
	/**
	 * Override `list` provider and add CSS asset.
	 */
	protected function lazy_get_views()
	{
		return \ICanBoogie\array_merge_recursive(parent::lazy_get_views(), [

			'list' => [

				ViewOptions::ASSETS => [

					'css' => [ DIR . 'public/page.css' ]
				]
			]
		]);
	}
}
