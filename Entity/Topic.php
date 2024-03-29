<?php

namespace Craue\FormFlowDemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="craue_formflowdemo_topic")
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2022 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Topic {

	use EntityHasIdTrait;

	/**
	 * @var string
	 * @ORM\Column(name="title", type="string", nullable=false)
	 * @Assert\NotBlank(groups={"flow_createTopic_step1"})
	 */
	public $title;

	/**
	 * @var string
	 * @ORM\Column(name="description", type="string", nullable=true)
	 */
	public $description;

	/**
	 * @var string
	 * @ORM\Column(name="category", type="string", nullable=false)
	 * @Assert\Choice(callback="getValidCategories", groups={"flow_createTopic_step1"}, strict=true)
	 * @Assert\NotBlank(groups={"flow_createTopic_step1"})
	 */
	public $category;

	/**
	 * @var string
	 * @ORM\Column(name="comment", type="text", nullable=true)
	 */
	public $comment;

	/**
	 * @var string
	 * @ORM\Column(name="details", type="text", nullable=true)
	 * @Assert\NotBlank(groups={"flow_createTopic_step3"})
	 */
	public $details;

	public function isBugReport() {
		return $this->category === 'BUG_REPORT';
	}

	public static function getValidCategories() {
		return [
			'DISCUSSION',
			'BUG_REPORT',
			'SUPPORT_REQUEST',
		];
	}

}
