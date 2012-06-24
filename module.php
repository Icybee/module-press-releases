<?php

/*
 * This file is part of the Icybee package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\Modules\Press\Releases;

/**
 * Introduces the Press Release content type.
 */
class Module extends \ICanBoogie\Modules\Contents\Module
{
	/**
	 * Override `list` provider and add CSS asset.
	 *
	 * @see ICanBoogie\Modules\Contents.Module::get_views()
	 */
	protected function get_views()
	{
		return \ICanBoogie\array_merge_recursive
		(
			parent::get_views(), array
			(
				'list' => array
				(
					'provider' => __NAMESPACE__ . '\Provider',
					'assets' => array
					(
						'css' => array(__DIR__ . '/public/page.css')
					)
				)
			)
		);
	}
}