<?php

namespace ICanBoogie\Modules\Press\Releases;

use ICanBoogie\ActiveRecord\Query;

class Provider extends \Icybee\Views\Contents\Provider
{
	protected $years;

	public function __invoke()
	{
		if ($this->view->type == 'list')
		{
			$this->years = $years = $this->module->model->select('YEAR(date)')->own->visible->group('YEAR(date)')->order('YEAR(date) DESC')->all(\PDO::FETCH_COLUMN);
		}

		$rc = parent::__invoke();

		if ($this->view->type == 'list' && $rc)
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

			if ($period == 'older')
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

	protected function alter_context(\BlueTihi\Context $context, Query $query, array $conditions)
	{
		$context = parent::alter_context($context, $query, $conditions);

		if ($this->view->type == 'list')
		{
			$period = $this->conditions['period'];

			$query_string = '?' . http_build_query
			(
				array
				(
					'page' => null
				),

				'', '&amp;'
			);

			$tabs = '<ul class="nav nav-tabs">';

			foreach ($this->years as $year)
			{
				$tabs .= '<li' . ($year == $period ? ' class="active"' : '') . '><a href="' . $query_string . '&amp;period=' . $year . '" title="Afficher les documents de ' . $year . '">' . $year . '</a></li>' . PHP_EOL;
			}

			$tabs .= '</ul>';

			$context['tabs'] = $tabs;
		}

		return $context;
	}
}