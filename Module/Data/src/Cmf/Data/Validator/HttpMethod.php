<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Data\Validator;

use Cmf\Form\Form;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class HttpMethod extends AbstractValidator
{
    const INVALID_METHOD = 'invalidHttpMethod';

    /** @var Form */
    protected $form;

    /**
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function isValid($value)
    {
        if (false == $result = (strtolower($_SERVER['REQUEST_METHOD']) == $this->form->getAttributes()->method)) {
            $lng = \Cmf\Language\Factory::get($this);
            $this->messages[self::INVALID_METHOD] = $lng[self::INVALID_METHOD];
        }

        return $result;
    }
}
