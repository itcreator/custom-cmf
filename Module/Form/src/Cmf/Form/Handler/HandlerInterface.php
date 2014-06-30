<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Form\Handler;

/**
 * Interface for form handler
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
interface HandlerInterface
{
    /**
     * @return $this;
     */
    public function initForm();

    /** \Form\Form */
    public function getForm();

    /**
     * @param array|null $data
     * @return bool
     */
    public function handle(array $data = null);
}
