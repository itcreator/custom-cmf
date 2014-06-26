<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\User\Form;

use Cmf\Captcha\Form\Element\Captcha;
use Cmf\Data\Validator\NotInDb;
use Cmf\Data\Validator\StrLen;
use Cmf\Form\Element\ReadAndAgree;
use Cmf\Form\Element\Submit;
use Cmf\Form\Form;
use Cmf\User\Form\Element\Email;
use Cmf\User\Form\Element\Login;
use Cmf\User\Form\Element\Password;
use Cmf\User\Form\Element\PasswordConfirmation;

/**
 * Registration form
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Register extends Form
{
    /** @var string */
    protected $entity = 'Cmf\User\Model\Entity\User';

    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        parent::__construct($params);

        $login = new Login();
        $login->addValidator(new NotInDb($this->entity, 'userName'));

        $email = new Email();
        $email->addValidator(new NotInDb($this->entity, 'email'));

        $password = new Password();
        $password->addValidator(new StrLen(6, 100));

        $lng = \Cmf\Language\Factory::get($this);
        $this
            ->setElement($login)
            ->setElement($email)
            ->setElement($password)
            ->setElement(new PasswordConfirmation(['element' => $password]))
            ->setElement(new Captcha())
            ->setElement(new ReadAndAgree())
            ->setElement(new Submit(['value' => $lng['registerBtnTitle']]));
    }
}
