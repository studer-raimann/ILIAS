<?php
require_once(dirname(__FILE__) . '/../Statement/class.arStatementCollection.php');
require_once('class.arHaving.php');

/**
 * Class arWhereCollection
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 2.0.7
 */
class arHavingCollection extends arStatementCollection
{

    /**
     * @return string
     */
    public function asSQLStatement() : string
    {
        $return = '';
        if ($this->hasStatements()) {
            $return .= ' HAVING ';
            $havings = $this->getHavings();
            $last = end($havings);
            foreach ($havings as $arWhere) {
                $return .= $arWhere->asSQLStatement($this->getAr());
                if ($arWhere !== $last) {
                    $return .= ' ' . $arWhere->getGlue() . ' ';
                }
            }
        }

        return $return;
    }

    /**
     * @return arHaving[]
     */
    public function getHavings() : array
    {
        return $this->statements;
    }
}
