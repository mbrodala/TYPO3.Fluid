<?php
namespace NamelessCoder\Fluid\Core\Parser\SyntaxTree\Expression;

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

use NamelessCoder\Fluid\Core\Parser;
use NamelessCoder\Fluid\Core\Parser\SyntaxTree\AbstractNode;
use NamelessCoder\Fluid\Core\Rendering\RenderingContextInterface;
use NamelessCoder\Fluid\Core\Variables\VariableExtractor;

/**
 * Base class for nodes based on (shorthand) expressions.
 */
abstract class AbstractExpressionNode extends AbstractNode implements ExpressionNodeInterface {

	/**
	 * Contents of the text node
	 *
	 * @var string
	 */
	protected $expression;

	/**
	 * @var array
	 */
	protected $matches = array();

	/**
	 * Constructor.
	 *
	 * @param string $expression The original expression that created this node.
	 * @param array $matches Matches extracted from expression
	 * @throws Parser\Exception
	 */
	public function __construct($expression, array $matches) {
		$this->expression = trim($expression, " \t\n\r\0\x0b");
		$this->matches = $matches;
	}

	/**
	 * Return the text associated to the syntax tree. Text from child nodes is
	 * appended to the text in the node's own text.
	 *
	 * @param RenderingContextInterface $renderingContext
	 * @return string the text stored in this node/subtree.
	 */
	public function evaluate(RenderingContextInterface $renderingContext) {
		return call_user_func_array(array(get_called_class(), 'evaluateExpression'), array($renderingContext, $this->expression, $this->matches));
	}

	/**
	 * Getter for returning the expression before parsing.
	 *
	 * @return string
	 */
	public function getExpression() {
		return $this->expression;
	}

	/**
	 * @return array
	 */
	public function getMatches() {
		return $this->matches;
	}

	/**
	 * @param string $part
	 * @return string
	 */
	protected static function trimPart($part) {
		return trim($part, " \t\n\r\0\x0b{}");
	}

	/**
	 * @param mixed $candidate
	 * @param RenderingContextInterface $renderingContext
	 * @return mixed
	 */
	protected static function getTemplateVariableOrValueItself($candidate, RenderingContextInterface $renderingContext) {
		$variables = $renderingContext->getVariableProvider()->getAll();
		$extractor = new VariableExtractor();
		$suspect = $extractor->getByPath($variables, $candidate);
		if (NULL === $suspect) {
			return $candidate;
		}
		return $suspect;
	}

}
