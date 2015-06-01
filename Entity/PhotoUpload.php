<?php

namespace Craue\FormFlowDemoBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2015 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class PhotoUpload {

	const UPLOAD_MAX_FILESIZE = '50k';

	/**
	 * @var UploadedFile
	 * @Assert\NotNull(groups={"flow_photoUpload_step1"})
	 * @Assert\Image(maxSize=PhotoUpload::UPLOAD_MAX_FILESIZE, groups={"flow_photoUpload_step1"})
	 */
	public $photo;

	/**
	 * @var string
	 */
	public $comment;

	public function getPhotoDataBase64Encoded() {
		return base64_encode(file_get_contents($this->photo->getPathname()));
	}

	public function getPhotoMimeType() {
		return $this->photo->getMimeType();
	}

}
