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

use ICanBoogie\ActiveRecord\Criterion;
use ICanBoogie\ActiveRecord\Query;

class PeriodCriterion extends Criterion
{
	public function alter_query_with_value(Query $query, $value)
	{
		if ($value === 'older')
		{
			return $query->where('YEAR(date) > ?', date('Y') - 5); // TODO-20120801: "5" should be configurable
		}

		return $query->where('YEAR(date) = ?', $value);
	}
}