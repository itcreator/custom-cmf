<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Data\Filter;

/**
 * Filter for html editors
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class FilterEditorStripTags extends StripTags
{
    /**
     * Set all allowed tags
     */
    public function __construct()
    {
        $this->setTagsAllowed([
            'span',
            'p' => ['style'],
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'pre' => ['class'],
            'address', 'strong', 'b', 'i', 'u', 'em', 'strike', 'ol', 'ul', 'li', 'sub', 'sup',
            'img' => ['src', 'alt', 'width', 'height', 'style'],
            'a' => ['href', 'title', 'target'],
            'table' => ['style', 'cellpadding', 'cellspacing', 'border'],
            'thead', 'tr',
            'td' => ['colspan', 'rowspan'],
            'th' => ['colspan', 'rowspan'],
            'caption', 'tbody',
        ]);

        $this->commentsAllowed = false;
    }
}
