<?php

namespace League\HTMLToMarkdown\Converter;

use League\HTMLToMarkdown\ElementInterface;

class LinkConverter implements ConverterInterface
{
    /**
     * @param ElementInterface $element
     *
     * @return string
     */
    public function convert(ElementInterface $element)
    {
        $href = $element->getAttribute('href');
        $title = $element->getAttribute('title');
        $text = trim($element->getValue());

        if (!$href && !$title && !$text) {
            return '';
        }

        // If it's an anchor link, just return either the text or the title
        if ($href === '#') {
            return empty($text) ? $text : $title;
        }

        // Replace ) for \) because reddit interprets that as the closure of the link markdown
        if (mb_strpos($href, ')') !== false) {
            $href = str_replace(')', '\)', $href);
        }

        if ($title !== '') {
            $markdown = '[' . $text . '](' . $href . ' "' . $title . '")';
        } elseif ($href === $text) {
            $markdown = '<' . $href . '>';
        } else {
            $markdown = '[' . $text . '](' . $href . ')';
        }

        if (!$href) {
            $markdown = html_entity_decode($element->getChildrenAsString());
        }

        return $markdown;
    }

    /**
     * @return string[]
     */
    public function getSupportedTags()
    {
        return array('a');
    }
}
