<?php

namespace Craue\FormFlowDemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait EntityHasIdTrait {

	/**
	 * @var int
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

}
