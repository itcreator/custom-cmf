<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Form\Handler;

use Cmf\Form\Form;
use Cmf\System\Application;

/**
 * Abstract class for form handler
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractHandler implements HandlerInterface
{
    /** @var array  */
    protected $formParams = [];

    /** @var Form */
    protected $form;

    /** @var array  */
    protected $handlerParams = [];

    /**
     * @param array $formParams
     * @param array $handlerParams
     */
    public function __construct(array $formParams = [], $handlerParams = [])
    {
        $this->formParams = $formParams;
        $this->handlerParams = array_merge($this->handlerParams, $handlerParams);

        $this->initForm();
    }

    /**
     * @return \Cmf\Form\Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function prepareFormData(array $data = null)
    {
        if (null == $data) {
            $request = Application::getRequest();
            $data = $request->getVars($this->form->getMethod());
        }

        return $data;
    }
}
