<?php

/* Copyright (c) 2015 Richard Klees, Extended GPL, see docs/LICENSE */

namespace CaT\Filter;

/**
 * Factory to build filters.
 *
 * A filter is a way to build a predicate from some inputs.
 */
class FilterGUIFactory {

	/**
	 * Get the gui of Dateperiod Filter
	 *
	 * @param	Filter		$filter
	 * @param	string		$path 
	 * @return	FilterGUI
	 */
	public function dateperiod_gui(Filters\DatePeriod $filter, $path) {
		require_once ("Services/ReportsRepository/classes/class.catFilterDatePeriodGUI.php");
		return new \catFilterDatePeriodGUI($filter, $path);
	}

	/**
	 * Get the gui of Option Filter
	 *
	 * @param	Filter		$filter
	 * @param	string		$path 
	 * @return	FilterGUI
	 */
	public function option_gui(Filters\Option $filter, $path) {
		require_once ("Services/ReportsRepository/classes/class.catFilterOptionGUI.php");
		return new \catFilterOptionGUI($filter, $path);
	}

	/**
	 * Get the gui of Multiselect Filter
	 *
	 * @param	Filter		$filter
	 * @param	string		$path 
	 * @return	FilterGUI
	 */
	public function multiselect_gui(Filters\Multiselect $filter, $path) {
		require_once ("Services/ReportsRepository/classes/class.catFilterMultiselectGUI.php");
		return new \catFilterMultiselectGUI($filter, $path);
	}

	/**
	 * Get the gui of Text Filter
	 *
	 * @param	Filter		$filter
	 * @param	string		$path 
	 * @return	FilterGUI
	 */
	public function text_gui(Filters\Text $filter, $path) {
		require_once ("Services/ReportsRepository/classes/class.catFilterTextGUI.php");
		return new \catFilterTextGUI($filter, $path);
	}

	/**
	 * Get the gui of OneOf Filter
	 *
	 * @param	Filter		$filter
	 * @param	string		$path 
	 * @return	FilterGUI
	 */
	public function one_of_gui(Filters\OneOf $filter, $path) {
		require_once ("Services/ReportsRepository/classes/class.catFilterOneOfGUI.php");
		return new \catFilterOneOfGUI($filter, $path);
	}
}