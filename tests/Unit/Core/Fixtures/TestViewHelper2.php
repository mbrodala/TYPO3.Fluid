<?php
namespace NamelessCoder\Fluid\Tests\Unit\Core\Fixtures;

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

use NamelessCoder\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class TestViewHelper2
 */
class TestViewHelper2 extends AbstractViewHelper {

	/**
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument('param1', 'integer', 'P1 Stuff', TRUE);
		$this->registerArgument('param2', 'array', 'P2 Stuff', TRUE);
		$this->registerArgument('param2', 'string', 'P3 Stuff', FALSE, 'default');
	}

	/**
	 * My comments.
	 *
	 * @return void
	 */
	public function render() {
	}

}
