<?php declare(strict_types=1);
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Composite for nesting multiple purifiers
 * @author Michael Jansen <mjansen@databay.de>
 */
class ilHtmlPurifierComposite implements ilHtmlPurifierInterface
{
    /** @var ilHtmlPurifierInterface[]  */
    protected array $purifiers = [];

    /**
     * Adds a node to composite
     * @param ilHtmlPurifierInterface $purifier Instance of ilHtmlPurifierInterface
     * @return bool True if instance could be added, otherwise false
     */
    public function addPurifier(ilHtmlPurifierInterface $purifier) : bool
    {
        $key = array_search($purifier, $this->purifiers, true);
        if (false === $key) {
            $this->purifiers[] = $purifier;
            return true;
        }

        return false;
    }

    /**
     * Removes a node from composite
     * @param ilHtmlPurifierInterface $purifier Instance of ilHtmlPurifierInterface
     * @return bool True if instance could be removed, otherwise false
     */
    public function removePurifier(ilHtmlPurifierInterface $purifier) : bool
    {
        $key = array_search($purifier, $this->purifiers, true);
        if (false === $key) {
            return false;
        }
        unset($this->purifiers[$key]);

        return true;
    }

    public function purify(string $html) : string
    {
        foreach ($this->purifiers as $purifier) {
            $html = $purifier->purify($html);
        }

        return $html;
    }

    public function purifyArray(array $htmlCollection) : array
    {
        foreach ($htmlCollection as $key => $html) {
            if (!is_string($html)) {
                throw new InvalidArgumentException(sprintf(
                    'The element on index %s is not of type string: %s',
                    $key,
                    print_r($html, true)
                ));
            }
        }

        foreach ($htmlCollection as $key => $html) {
            foreach ($this->purifiers as $purifier) {
                $html = $purifier->purify($html);
            }

            $htmlCollection[$key] = $html;
        }

        return $htmlCollection;
    }
}
