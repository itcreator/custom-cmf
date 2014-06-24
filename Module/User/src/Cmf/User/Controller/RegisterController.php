<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\User\Controller;

use Cmf\Controller\AbstractController;
use Cmf\Controller\Response\Forward;
use Cmf\Controller\Response\PostRedirectGet;
use Cmf\Mail\Mailer;
use Cmf\Permission\Model\Entity\Role;
use Cmf\System\Application;
use Cmf\System\Message;
use Cmf\User\Auth;
use Cmf\User\Auth\Adapter\DoctrineEntity;
use Cmf\User\Model\Entity\User;
use Cmf\User\Model\Repository\UserRepository;
use Cmf\View\Helper\HelperFactory;

/**
 * Registration controller
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class RegisterController extends AbstractController
{
    const MSG_DB_ERROR = 'dbError';
    const MSG_REGISTRATION_OK = 'registrationOk';
    const MSG_ACTIVATION_OK = 'activationOk';
    const ERR_BAD_PARAMS = 'errBadParams';
    const ERR_USER_NOT_FOUND = 'errUserNotFound';
    const ERR_USER_ALREADY_ACTIVATED = 'errUserAlreadyActivated';

    /** @var string */
    protected $entity = '\Cmf\User\Model\Entity\User';

    /**
     * @param User $user
     * @return $this;
     */
    protected function sendActivationMail(User $user)
    {
        $view = Application::getViewProcessor();
        $url = Application::getUrlBuilder()->build([
            'controller' => 'register',
            'action' => 'activation',
            'module' => 'Cmf\User',
            'code' => $user->getActivationCode(),
            'idUser' => $user->getId(),
        ]);

        $activationUrl = 'http://' . $_SERVER['HTTP_HOST'] . $url;

        $config = Application::getConfigManager()->loadForModule('Cmf\System', 'site');
        $data = [
            'siteName' => $config->siteTitle,
            'userName' => $user->getUserName(),
            'activationUrl' => $activationUrl,
        ];

        $message = $view->render('Cmf/User/mail/registrationOk', $data);
        Mailer::send('Registration in  CMF', $message, $user->getEmail(), $user->getUserName());

        return $this;
    }

    /**
     * @return array|PostRedirectGet
     */
    public function defaultAction()
    {
        $response = null;
        $registerForm = new \Cmf\User\Form\Register();
        if ($registerForm->isSubmitted()) {
            $registerForm->getValuesFromRequest();
            if ($registerForm->validate()) {
                $user = new User();
                $user->setUserName($registerForm->getElement('login')->getValue());
                $user->setPassword($registerForm->getElement('password')->getValue());
                $user->setEmail($registerForm->getElement('email')->getValue());
                $user->setRegistrationTime(time());
                $user->setStatus(User::STATUS_NOT_CONFIRMED);
                $user->setActivationCode(UserRepository::generateActivationCode());

                try {
                    $em = $this->getEntityManager();
                    $em->persist($user);
                    $em->flush();
                    $result = true;
                } catch (\Exception $e) {
                    $lng = \Cmf\Language\Factory::get($this);
                    $registerForm->getMessages()->setItem(new Message($lng[self::MSG_DB_ERROR]));
                    $result = false;
                }

                if ($result) {
                    $this->sendActivationMail($user);

                    $response = new PostRedirectGet($this);
                    $response->setRedirectPath([
                        'module' => 'user',
                        'controller' => 'register',
                        'action' => 'registrationOk',
                    ]);
                }
            } else {
                $registerForm->getElement('captcha')->setValue('');
            }
        }
        if (!$response) {
            $response = ['form' => $registerForm];
        }

        return $response;
    }

    /**
     * @return Forward
     */
    public function registrationOkAction()
    {
        $lng = \Cmf\Language\Factory::get($this);
        HelperFactory::getMessageBox()->addMessage($lng[self::MSG_REGISTRATION_OK]);

        return $this->forward('default', 'Index', 'Cmf\Index');
    }

    /**
     * @return array|PostRedirectGet
     */
    public function activationAction()
    {
        $request = Application::getRequest();
        $idUser = $request->get('idUser');
        $code = $request->get('code');
        $lng = \Cmf\Language\Factory::get($this);

        if (!$idUser || !$code) {
            return $this->forward404($lng[self::ERR_BAD_PARAMS]);
        }

        $em = $this->getEntityManager();
        /** @var $user User */
        $user = $em->getRepository($this->entity)->findOneBy(['id' => $idUser]);

        if (User::STATUS_NOT_CONFIRMED != $user->getStatus()) {
            return $this->forward404($lng[self::ERR_USER_ALREADY_ACTIVATED]);
        }

        if (!$user || $user->getActivationCode() != $code) {
            return $this->forward404($lng[self::ERR_USER_NOT_FOUND]);
        }

        //activation of a new user
        $user->setStatus(User::STATUS_ACTIVE);
        $user->setActivationCode(null);

        //TODO: replace to unidirectional ???
        $role = $em->getRepository('Cmf\Permission\Model\Entity\Role')
            ->findOneBy(['name' => Role::ROLE_USER]);
        $user->addRole($role);

        $em->persist($user);

        $em->flush();

        //authenticate
        $adapter = new DoctrineEntity($em, $user);

        Application::getAuth()->authenticate($adapter);

        $response = new PostRedirectGet($this);
        $response->setRedirectPath([
            'module' => 'user',
            'controller' => 'register',
            'action' => 'activationOk',
        ]);

        return $response;
    }

    /**
     * @return Forward
     */
    public function activationOkAction()
    {
        $lng = \Cmf\Language\Factory::get($this);
        HelperFactory::getMessageBox()->addMessage($lng[self::MSG_ACTIVATION_OK]);

        return $this->forward('default', 'Index', 'Cmf\Index');
    }
}
