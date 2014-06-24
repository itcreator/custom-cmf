<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Html\Attribute;

use Cmf\Form\Element\AbstractElement;
use Cmf\Structure\Collection\AssociateCollection;

use Doctrine\ORM\EntityNotFoundException;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class AttributeCollection extends AssociateCollection
{
    /** @var AbstractElement|null */
    protected $formElement;

    /**
     * @param AbstractElement $element
     * @return $this
     */
    public function setFormElement(AbstractElement $element)
    {
        $this->formElement = $element;

        return $this;
    }

    /**
     * @return AbstractElement|null
     */
    public function getFormElement()
    {
        return $this->formElement;
    }

    /**
     * @return string
     */
    public function render()
    {
        $str = '';
        foreach ($this as $key => $value) {
            if ('name' == $key && $this->formElement instanceof AbstractElement) {
                continue;
            }
            /** @var $value  \Cmf\Component\Html\Expression */
            if ($value instanceof \Cmf\Component\Html\Expression) {
                $safeValue = $value->getText();
            } elseif ($value instanceof \Doctrine\ORM\Proxy\Proxy) {
                try {
                    $value->__load();
                    $safeValue = htmlspecialchars($value);
                } catch (EntityNotFoundException $e) {
                    $safeValue = '';
                }
            } else {
                $safeValue = htmlspecialchars($value);
            }

            $str .= ' ' . $key . '="' . $safeValue . '"';
        }

        if ($this->formElement instanceof AbstractElement) {
            $str .= sprintf(' name="%s"', $this->formElement->getFullName());
        }

        return $str;
    }
}
