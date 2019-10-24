<?php namespace ILIAS\GlobalScreen\Scope\MainMenu\Factory\Item;

use ILIAS\GlobalScreen\Scope\MainMenu\Factory\AbstractChildItem;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\hasContent;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\hasSymbol;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\hasTitle;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\supportsAsynchronousLoading;
use ILIAS\UI\Component\Component;
use ILIAS\UI\Component\Symbol\Symbol;

/**
 * Class Complex
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class Complex extends AbstractChildItem implements hasContent, hasTitle, hasSymbol, supportsAsynchronousLoading
{

    /**
     * @var
     */
    private $content;
    /**
     * @var string
     */
    private $title = '';
    /**
     * @var Symbol
     */
    private $symbol;


    /**
     * @param Component $ui_component
     *
     * @return Complex
     */
    public function withContent(Component $ui_component) : hasContent
    {
        $clone = clone($this);
        $clone->content = $ui_component;

        return $clone;
    }


    /**
     * @return Component
     */
    public function getContent() : Component
    {
        return $this->content;
    }


    /**
     * @param string $title
     *
     * @return Complex
     */
    public function withTitle(string $title) : hasTitle
    {
        $clone = clone($this);
        $clone->title = $title;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getTitle() : string
    {
        return $this->title;
    }


    /**
     * @inheritDoc
     */
    public function withSymbol(Symbol $symbol) : hasSymbol
    {
        $clone = clone($this);
        $clone->symbol = $symbol;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getSymbol() : Symbol
    {
        return $this->symbol;
    }


    /**
     * @inheritDoc
     */
    public function hasSymbol() : bool
    {
        return $this->symbol instanceof Symbol;
    }


    /**
     * @inheritDoc
     */
    public function supportsAsynchronousLoading() : bool
    {
        return true;
    }
}
