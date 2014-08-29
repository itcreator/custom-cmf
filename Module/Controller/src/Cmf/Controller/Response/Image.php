<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Controller\Response;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Image extends AbstractResponse
{
    const TYPE_JPEG = 'image/jpeg';
    const TYPE_GIF = 'image/gif';
    const TYPE_PNG = 'image/png';

    /** @var string  */
    protected $type = self::TYPE_PNG;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function handle()
    {
        $image = $this->renderData['image'];
        ob_start();
        imagepng($image);
        $data = ob_get_clean();
        imagedestroy($image);

        header("Content-type: {$this->type}");

        return $data;
    }
}
