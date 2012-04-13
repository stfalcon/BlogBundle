<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class UploadedFile
{

    /**
     * File
     *
     * @var string $inlineUploadFile
     * @Assert\Image(
     *      mimeTypes = {"image/png", "image/jpeg", "image/gif"}
     * )
     */
    private $inlineUploadFile;

    /**
     * Set File
     *
     * @param string $inlineUploadFile
     *
     * @return void
     */
    public function setInlineUploadFile($inlineUploadFile)
    {
        $this->inlineUploadFile = $inlineUploadFile;
    }

    /**
     * Get File
     *
     * @return string
     */
    public function getInlineUploadFile()
    {
        return $this->inlineUploadFile;
    }
}