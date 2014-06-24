<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\ActionLink;

use Cmf\Component\Link\ActionLink;
use Cmf\Component\Field\AbstractField;
use Cmf\Component\Link\Constants as LinkConstant;
use Cmf\Structure\Collection\LazyCollection;
use Cmf\System\Application;

/**
 * Action link factory
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Factory
{
    protected static $defaultParams = [
        'url' => [],
        'title' => '',
        'type' => LinkConstant::TYPE_LINK,
        'identifier' => false,
        'actions' => [],
    ];

    /**
     * @param array $params
     * @param AbstractField $primaryIdField
     * @return bool|\Cmf\Component\Link\ActionLink
     */
    public static function createLink(array $params, AbstractField $primaryIdField = null)
    {
        $params = array_merge(self::$defaultParams, $params);

        $app = Application::getInstance();
        if (!in_array($app->getMvcRequest()->getActionName(), $params['actions'])) {
            return false;
        }

        $urlParams = $params['url'];

        if (is_array($urlParams) && $params['identifier']) {
            $urlParams[$params['identifier']] = $primaryIdField->getValue();
        }

        return new ActionLink($urlParams, $params['title']);
    }

    /**
     * @param AbstractConfig $config
     * @param AbstractField $primaryIdField
     * @return Collection
     */
    public static function createLinks(AbstractConfig $config = null, AbstractField $primaryIdField = null)
    {
        $init = function (LazyCollection $links) use ($config, $primaryIdField) {
            if (!$config) {
                return;
            }

            $actionLinks = $config->getConfig();

            $evm = $links->getEventManager();

            $argv = ['actionLinks' => $actionLinks];
            $eventName = Collection::EVENT_ACTION_LINK_GETTING_AFTER;
            $evm->trigger($eventName, $links, $argv, function ($links) use (&$actionLinks) {
                if ($links) {
                    $actionLinks = $links;
                }
            });

            foreach ($actionLinks['items'] as $itemParams) {
                if ($link = self::createLink($itemParams, $primaryIdField)) {
                    $links->setItem($link);
                }
            }

        };

        $links = new Collection($init);

        return $links;
    }
}
