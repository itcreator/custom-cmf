<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Field;

use Doctrine\ORM\EntityNotFoundException;

/**
 * Field for *-to-one relations
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Foreign extends AbstractField
{
    /**
     * @param array $config
     * @param mixed $value
     */
    public function __construct(array $config, $value = null)
    {
        parent::__construct($config, $value);

        $method = 'get' . $config['foreign']['fieldName'];

        try {
            $this->setValue($value);
              $value = $value->$method();
        } catch (EntityNotFoundException $e) {
            $value = '';
        }
        
        $field = \Cmf\Component\Field\Factory::create($config['foreign'], $value);
        $field->setEntity($this->getValue());

        $className = $this->config->decorator ? $this->config->decorator : 'String';
        $className = '\Cmf\Component\Field\Decorator\\' . $className;
        $this->decorator = new $className($field);
    }
}
