<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\User\Model\Field;

use Cmf\Component\Field\AbstractFieldConfig;
use Cmf\Data\Filter\Trim;
use Cmf\Data\Source\ArraySource;
use Cmf\Data\Validator\KeyExist;
use Cmf\Data\Validator\StrLen;
use Cmf\User\Model\Entity\User;

/**
 * Field config for users
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class UserFieldConfig extends AbstractFieldConfig
{
    /**
     * @return $this
     */
    protected function init()
    {
        $lng = \Cmf\Language\Factory::get($this);

        $userStatuses = [
            User::STATUS_NOT_CONFIRMED => [
                'value' => User::STATUS_NOT_CONFIRMED,
                'title' => $lng['user_status_notConfirmed']
            ],
            User::STATUS_ACTIVE => [
                'value' => User::STATUS_ACTIVE,
                'title' => $lng['user_status_active']
            ],
            User::STATUS_BLOCKED => [
                'value' => User::STATUS_BLOCKED,
                'title' => $lng['user_status_blocked']
            ],
            User::STATUS_DELETED => [
                'value' => User::STATUS_DELETED,
                'title' => $lng['user_status_deleted']
            ],
        ];

        $this->config = [
            'id' => ['display' => 'checkbox', 'title' => 'id', 'fieldType' => 'Id', 'input' => 'Hidden'],
            'userName' => [
                'title' => $lng['userName'],
                'input' => 'Cmf\User\Form\Element\Login',
                'decorator' => 'ItemLink',
                'urlParams' => ['controller' => 'user', 'action' => 'read', 'module' => 'Cmf\User'],
                'fields' => ['id' => 'id'],
                'sortable' => true,
                'required' => true,
                'unique' => true,
            ],
            'firstName' => [
                'title' => $lng['firstName'],
                'sortable' => true,
                'validators' => [new StrLen(2, 100)],
                'filters' => [new Trim()],
            ],
            'lastName' => [
                'title' => $lng['lastName'],
                'sortable' => true,
                'validators' => [new StrLen(2, 100)],
                'filters' => [new Trim()],
            ], 'email' => [
                'input' => 'Cmf\User\Form\Element\Email',
                'title' => $lng['email'],
                'decorator' => 'MailTo',
                'sortable' => true,
                'required' => true,
                'unique' => true,
            ],
            'fatherName' => [
                'title' => $lng['fatherName'],
                'validators' => [new StrLen(2, 100)],
                'filters' => [new Trim()],
            ],
            'status' => [
                'title' => $lng['status'],
                'decorator' => 'StatusString',
                'dataSource' => new ArraySource($userStatuses),
                'input' => 'Select',
                'required' => true,
                'validators' => [new KeyExist($userStatuses)],
                'filters' => [new Trim()],
            ],
            'password' => [
                'title' => $lng['password'],
                'display' => 'none',
                'fieldType' => 'String',
                'input' => 'Password',
                'required' => true,
                'validators' => [
                    new StrLen(6, 100)],
                'hideInList' => true,
            ],
        ];

        //
        // Email:
        // List:         Name,     Title,    list_decorator,      sortable,        filterable,
        // Create:       Name,     Title,    input,                   validators,     filters,       required
        // Update:       Name,     Title,    input,                   validators,     filters,       required
        // Delete:
        // Read:         Name,     Title,   item_decorator

        return $this;
    }
}
