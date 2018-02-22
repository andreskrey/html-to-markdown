<?php

namespace League\HTMLToMarkdown\Converter;

use League\HTMLToMarkdown\ElementInterface;

class ImageConverter implements ConverterInterface
{
    /**
     * @param ElementInterface $element
     *
     * @return string
     */
    public function convert(ElementInterface $element)
    {
        $src = $element->getAttribute('src');
        $alt = $element->getAttribute('alt');
        $title = $element->getAttribute('title');

        if ((!$src && !$alt && !$title) || strpos($src, 'data:') === 0) {
            return '';
        }

        // Replace ) for \) because reddit interprets that as the closure of the link markdown
        if (mb_strpos($src, ')') !== false) {
            $src = str_replace(')', '\)', $src);
        }

        if (!trim($alt)) {
            $alt = 'Image';
        }

        if ($title !== '') {
            // No newlines added. <img> should be in a block-level element.
            $markdown = '[' . $alt . '](' . $src . ' "' . $title . '")';
        } else {
            $markdown = '[' . $alt . '](' . $src . ')';
        }

        return $markdown;
    }

    /**
     * @return string[]
     */
    public function getSupportedTags()
    {
        return array('img');
    }
}
