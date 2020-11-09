<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85735-MethodAndPropertyInDocumentTemplate.html
 */
final class DocumentTemplateAddStyleSheetRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, DocumentTemplate::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'addStyleSheet')) {
            return null;
        }

        $args = $node->args;

        if (! isset($args[0], $args[1])) {
            return null;
        }

        $href = $this->getValue($args[1]->value);
        $title = isset($args[2]) ? $this->getValue($args[2]->value) : '';
        $relation = isset($args[3]) ? $this->getValue($args[3]->value) : 'stylesheet';

        return $this->createMethodCall(
            $this->createStaticCall(
                GeneralUtility::class,
                'makeInstance',
                [$this->createClassConstantReference(PageRenderer::class)]
            ), 'addCssFile', [$href, $relation, 'screen', $title]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use PageRenderer::addCssFile instead of DocumentTemplate::addStyleSheet() ', [
            new CodeSample(<<<'PHP'
$documentTemplate = GeneralUtility::makeInstance(DocumentTemplate::class);
$documentTemplate->addStyleSheet('foo', 'foo.css');
PHP
            , <<<'PHP'
GeneralUtility::makeInstance(PageRenderer::class)->addCssFile('foo.css', 'stylesheet', 'screen', '');
PHP
        ),
        ]);
    }
}
