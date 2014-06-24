<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Block;

use Cmf\Block\Response\AbstractResponse;
use Cmf\Block\Response\Html;
use Cmf\System\Application;

/**
 * Blocks manager
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class BlockManager
{
    /**
     * @param string $blockName
     * @param array $params
     * @return AbstractBlock
     * @throws Exception
     */
    public function createBlockInstance($blockName, array $params = [])
    {
        if (!class_exists($blockName)) {
            throw new Exception(sprintf('Class block with name "%s" not found', $blockName));
        }

        $loader = Application::getClassLoader();
        if (!$loader->findFile($blockName)) {
            throw new Exception(sprintf('Class for block with name "%s" not found', $blockName));
        }

        $block = new $blockName($params);
        if (!($block instanceof AbstractBlock)) {
            throw new Exception('Block should be instance of Cmf\Block\AbstractBlock');
        }

        return $block;
    }

    /**
     * @param array|\Cmf\Block\Response\AbstractResponse $response
     * @param AbstractBlock $block
     * @return Response\AbstractResponse|Response\Html
     * @throws Exception
     */
    protected function purifyResponse($response, AbstractBlock $block)
    {
        if (is_array($response)) {
            $response = new Html($block, $response);
        } elseif (!($response instanceof AbstractResponse)) {
            throw new Exception('Incorrect block response type');
        }

        return $response;
    }

    /**
     * @param string $blockName
     * @param array $params
     * @return string
     * @throws Exception
     */
    public function renderBlock($blockName, array $params = [])
    {
        $block = $this->createBlockInstance($blockName, $params);
        $response = $this->purifyResponse($block->handle(), $block);
        $result = '';

        while (1) {
            $result = $response->handle();
            if ($result instanceof AbstractBlock) {
                $block = $response;
                $response = $this->purifyResponse($block->handle(), $block);
            } elseif (is_string($result)) {
                break;
            } else {
                throw new Exception('Block response is incorrect');
            }
        }

        return $result;
    }

    /**
     * @param string $containerName
     * @param array $params
     * @return string
     */
    public function renderContainer($containerName, array $params = [])
    {
        $em = Application::getEntityManager();

        /** @var $repository \Cmf\Block\Model\Repository\BindingRepository */
        $repository = $em->getRepository('\Cmf\Block\Model\Entity\Binding');
        $bindings = $repository->getBlocksByContainerName($containerName);
        $result = '';

        /** @var $binding \Cmf\Block\Model\Entity\Binding */
        foreach ($bindings as $binding) {
            $result .= $this->renderBlock($binding->getBlock()->getClass(), $params);
        }

        return $result;
    }
}
