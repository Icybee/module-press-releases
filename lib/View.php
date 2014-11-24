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

use BlueTihi\Context;
use Brickrouge\A;

class View extends \Icybee\Modules\Contents\View
{
	protected function provide($provider, array $conditions)
	{
		$records = parent::provide($provider, $conditions);

		if ($this->type == 'list')
		{
			$groups = [];

			foreach ($records as $record)
			{
				$month = substr($record->date, 0, 7) . '-01';

				$groups[$month][] = $record;
			}

			$records = $groups;
		}

		return $records;
	}

	protected function alter_conditions(array &$conditions)
	{
		parent::alter_conditions($conditions);

		if (empty($conditions['period']))
		{
			// TODO-20141017: use the provider query
			$years = $this
			->module
			->model
			->visible
			->own
			->similar_site
			->select('DISTINCT YEAR(date) as `year`')
			->order('`year` DESC')
			->limit(1)
			->all(\PDO::FETCH_COLUMN);

			$conditions['period'] = current($years);
		}
	}

	protected function alter_context(Context $context)
	{
		if ($this->type == 'list')
		{
			$provider = $this->provider;

			$query = clone $provider->initial_query;
			$years = $query->select('DISTINCT YEAR(date) as `year`')
			->order('`year` DESC')
			->all(\PDO::FETCH_COLUMN);

			$period = empty($provider->conditions['period']) ? $years[0] : $provider->conditions['period'];

			$tabs = '<ul class="nav nav-tabs">';

			foreach ($years as $year)
			{
				$url = '?' . http_build_query([ 'page' => null, 'period' => $year ]);

				$a = new A($year, $url);

				$tabs .= '<li' . ($year == $period ? ' class="active"' : '') . '>' . $a . '</li>';
			}

			$tabs .= '</ul>';

			$context['tabs'] = $tabs;
		}

		return $context;
	}
}
