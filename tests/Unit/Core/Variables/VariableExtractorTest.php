<?php
namespace NamelessCoder\Fluid\Tests\Unit\Core\Variables;

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

use NamelessCoder\Fluid\Core\Variables\StandardVariableProvider;
use NamelessCoder\Fluid\Core\Variables\VariableExtractor;
use NamelessCoder\Fluid\Tests\Unit\ViewHelpers\Fixtures\UserWithoutToString;
use NamelessCoder\Fluid\Tests\UnitTestCase;

/**
 * Class VariableExtractorTest
 */
class VariableExtractorTest extends UnitTestCase {

	/**
	 * @param mixed $subject
	 * @param string $path
	 * @param mixed $expected
	 * @test
	 * @dataProvider getPathTestValues
	 */
	public function testGetByPath($subject, $path, $expected) {
		$result = VariableExtractor::extract($subject, $path);
		$this->assertEquals($expected, $result);
	}

	/**
	 * @return array
	 */
	public function getPathTestValues() {
		$namedUser = new UserWithoutToString('Foobar Name');
		$unnamedUser = new UserWithoutToString('');
		return array(
			array(NULL, '', NULL),
			array(array('foo' => 'bar'), 'foo', 'bar'),
			array(array('foo' => 'bar'), 'foo.invalid', NULL),
			array(array('user' => $namedUser), 'user.name', 'Foobar Name'),
			array(array('user' => $unnamedUser), 'user.name', ''),
			array(array('user' => $namedUser), 'user.named', TRUE),
			array(array('user' => $unnamedUser), 'user.named', FALSE),
			array(array('user' => $namedUser), 'user.invalid', NULL)
		);
	}

	/**
	 * @param mixed $subject
	 * @param string $path
	 * @param mixed $expected
	 * @test
	 * @dataProvider getAccessorsForPathTestValues
	 */
	public function testGetAccessorsForPath($subject, $path, $expected) {
		$result = VariableExtractor::extractAccessors($subject, $path);
		$this->assertEquals($expected, $result);
	}

	/**
	 * @return array
	 */
	public function getAccessorsForPathTestValues() {
		$namedUser = new UserWithoutToString('Foobar Name');
		$inArray = array('user' => $namedUser);
		$inArrayAccess = new StandardVariableProvider($inArray);
		$inPublic = (object) $inArray;
		$asArray = VariableExtractor::ACCESSOR_ARRAY;
		$asGetter = VariableExtractor::ACCESSOR_GETTER;
		$asPublic = VariableExtractor::ACCESSOR_PUBLICPROPERTY;
		return array(
			array(NULL, '', array()),
			array(array('inArray' => $inArray), 'inArray.user', array($asArray, $asArray)),
			array(array('inArray' => $inArray), 'inArray.user.name', array($asArray, $asArray, $asGetter)),
			array(array('inArrayAccess' => $inArrayAccess), 'inArrayAccess.user.name', array($asArray, $asArray, $asGetter)),
			array(array('inPublic' => $inPublic), 'inPublic.user.name', array($asArray, $asPublic, $asGetter))
		);
	}

	/**
	 * @param mixed $subject
	 * @param string $path
	 * @param string $accessor
	 * @param mixed $expected
	 * @test
	 * @dataProvider getExtractRedectAccessorTestValues
	 */
	public function testExtractRedetectsAccessorIfUnusableAccessorPassed($subject, $path, $accessor, $expected) {
		$result = VariableExtractor::extract($subject, 'test', array($accessor));
		$this->assertEquals($expected, $result);
	}

	/**
	 * @return array
	 */
	public function getExtractRedectAccessorTestValues() {
		return array(
			array(array('test' => 'test'), 'test', NULL, 'test'),
			array(array('test' => 'test'), 'test', 'garbageextractionname', 'test'),
			array(array('test' => 'test'), 'test', VariableExtractor::ACCESSOR_PUBLICPROPERTY, 'test'),
			array(array('test' => 'test'), 'test', VariableExtractor::ACCESSOR_GETTER, 'test'),
			array(array('test' => 'test'), 'test', VariableExtractor::ACCESSOR_ASSERTER, 'test'),
			array((object) array('test' => 'test'), 'test', VariableExtractor::ACCESSOR_ARRAY, 'test'),
			array((object) array('test' => 'test'), 'test', VariableExtractor::ACCESSOR_ARRAY, 'test'),
		);
	}

}
