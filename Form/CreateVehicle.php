<?php

namespace Craue\FormFlowDemoBundle\Form;

use Craue\FormFlowDemoBundle\Entity\Vehicle;
use Symfony\Component\Validator\Constraints as Assert;

class CreateVehicle {

	/**
	 * @Assert\Valid
	 */
	public $vehicle;

	/**
	 * @var bool
	 * @Assert\NotNull(groups={"flow_createVehicle_step3"})
	 * @Assert\Type(type="boolean", groups={"flow_createVehicle_step3"})
	 */
	public $addDriver = true;

	/**
	 * @Assert\Valid
	 */
	public $driver;

	public function __construct() {
		$this->vehicle = new Vehicle();
	}

}
