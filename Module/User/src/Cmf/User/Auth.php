<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\User;

use Cmf\System\Application;
use Cmf\System\Message;
use Cmf\User\Auth\Adapter\AdapterInterface;
use Cmf\User\Model\Entity\User;

/**
 * Class for auth
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Auth
{
    const ERR_INCORRECT_LOGIN_OR_PASSWORD = 'err_incorrectLoginOrPassword';
    const ERR_ACCOUNT_NOT_ACTIVATED = 'err_accountNotActivated';
    const ERR_ACCOUNT_BLOCKED = 'err_accountBlocked';
    const ERR_ACCOUNT_DELETED = 'err_accountDeleted';

    /** @var bool */
    protected $rememberUser = false;

    /** @var int */
    protected $idUser;

    /** @var User */
    protected $user = false;

    /** @var string */
    protected $message;

    /** @var string */
    protected $entity = 'Cmf\User\Model\Entity\User';

    /**
     * @param bool $remember
     * @return Auth
     */
    public function setRememberUser($remember)
    {
        $this->rememberUser = (bool)$remember;

        return $this;
    }

    /**
     * @param AdapterInterface $adapter
     * @return bool
     */
    public function authenticate(AdapterInterface $adapter)
    {
        $result = false;
        $lng = \Cmf\Language\Factory::get($this);
        /** @var $user User */
        if ($user = $adapter->authenticate()) {
            $_SESSION['cmf_auth_user_id'] = $user->getId();
            $_SESSION['cmf_auth_remember_user'] = $this->rememberUser;
            $this->setSessionLifeTime();
            switch ($user->getStatus()) {
                case User::STATUS_ACTIVE:
                    $this->user = $user;
                    $result = true;

                    break;
                case User::STATUS_NOT_CONFIRMED:
                    $this->message = new Message($lng[self::ERR_ACCOUNT_NOT_ACTIVATED], Message::TYPE_ERROR);

                    break;
                case User::STATUS_DELETED:
                    $this->message = new Message($lng[self::ERR_ACCOUNT_DELETED], Message::TYPE_ERROR);

                    break;
                default:
                    $this->message = new Message($lng[self::ERR_ACCOUNT_BLOCKED], Message::TYPE_ERROR);
            }
        } else {
            $this->message = new Message($lng[self::ERR_INCORRECT_LOGIN_OR_PASSWORD], Message::TYPE_ERROR);
        }

        return $result;
    }

    /**
     * @return Auth
     */
    protected function setSessionLifeTime()
    {
        if ($this->rememberUser) {
            ini_set('session.gc_maxlifetime', 31622501);
            ini_set('session.cookie_lifetime', 31622401);
        } else {
            ini_set('session.gc_maxlifetime', 0);
            ini_set('session.cookie_lifetime', 0);
        }
        session_write_close();
        session_start();

        return $this;
    }

    public function logout()
    {
        if (isset($_SESSION['cmf_auth_remember_user'])) {
            unset($_SESSION['cmf_auth_remember_user']);
        }
        if (isset($_SESSION['cmf_auth_user_id'])) {
            unset($_SESSION['cmf_auth_user_id']);
        } else {
            return false;
        }

        session_destroy();
        setcookie(session_name(), '', time() - 10);

        return true;
    }

    /**
     * @return User
     * @throws \Cmf\User\Auth\Exception
     */
    public function getUser()
    {
        if (false !== $this->user) {
            return $this->user;
        }

        $em = Application::getEntityManager();
        /** @var $repository \Cmf\User\Model\Repository\UserRepository */
        $repository = $em->getRepository($this->entity);


        if (!$this->idUser) {
            $this->idUser = isset($_SESSION['cmf_auth_user_id']) ? $_SESSION['cmf_auth_user_id'] : null;
        }

        if (!$this->idUser) {
            $this->user = $repository->getGuest();
            if (!$this->user) {
                throw new \Cmf\User\Auth\Exception('User "Guest" not found in DB.');
            }
            $this->idUser = $this->user->getId();
        }

        if (!$this->user) {
            $this->user = $em->getRepository($this->entity)->find($this->idUser);
        }

        return $this->user;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
