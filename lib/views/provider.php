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

use ICanBoogie\ActiveRecord\Query;

class Provider extends \Icybee\Views\Contents\Provider
{
	/**
	 * The different years of the available records.
	 *
	 * @var array[]int
	 */
	protected $years;

	/**
	 * If the view type is `list` records are grouped by year.
	 *
	 * @see Icybee\Views\Contents.Provider::__invoke()
	 */
	public function __invoke()
	{
		$is_list = ($this->view->type === 'list');

		if ($is_list)
		{
			$this->years = $this->module->model->select('DISTINCT YEAR(date) as `year`')
			->own->visible->order('`year` DESC')
			->all(\PDO::FETCH_COLUMN);
		}

		$rc = parent::__invoke();

		if ($is_list && $rc)
		{
			$groups = array();

			foreach ($rc as $record)
			{
				$month = substr($record->date, 0, 7) . '-01';

				$groups[$month][] = $record;
			}

			return $groups;
		}

		return $rc;
	}

	/**
	 * Adds the `period` condition.
	 *
	 * @see Icybee\Views\Nodes.Provider::alter_conditions()
	 */
	protected function alter_conditions(array $conditions)
	{
		return parent::alter_conditions($conditions) + array
		(
			'period' => $this->years ? $this->years[0] : null
		);
	}

	/**
	 * Support for the 'period' condition.
	 *
	 * Older records are gathered under the "older" period, otherwise 'period' is a year.
	 *
	 * @see Icybee\Views\Contents.Provider::alter_query()
	 */
	protected function alter_query(Query $query, array $conditions)
	{
		if (isset($conditions['period']))
		{
			$period = $conditions['period'];

			if ($period === 'older')
			{
				$query->where('YEAR(date) > ?', date('Y') - 5); // TODO-20120801: "5" should be configurable
			}
			else
			{
				$query->where('YEAR(date) = ?', $period);
			}
		}

		return parent::alter_query($query, $conditions);
	}

	/**
	 * If the view type is `list` the context is altered with the `tabs` property.
	 *
	 * @see Icybee\Views\Nodes.Provider::alter_context()
	 */
	protected function alter_context(\BlueTihi\Context $context, Query $query, array $conditions)
	{
		$context = parent::alter_context($context, $query, $conditions);

		if ($this->view->type == 'list')
		{
			$period = $this->conditions['period'];
			$query_string = '?' . http_build_query(array('page' => null), '', '&amp;');
			$tabs = '<ul class="nav nav-tabs">';

			foreach ($this->years as $year)
			{
				$tabs .= '<li' . ($year == $period ? ' class="active"' : '') . '><a href="' . $query_string . '&amp;period=' . $year . '">' . $year . '</a></li>' . PHP_EOL;
			}

			$tabs .= '</ul>';

			$context['tabs'] = $tabs;
		}

		return $context;
	}
}