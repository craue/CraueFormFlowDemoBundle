<?php

namespace Craue\FormFlowDemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="craue_formflowdemo_verhicle")
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2022 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Vehicle {

	use EntityHasIdTrait;

	/**
	 * @var int
	 * @ORM\Column(name="number_of_wheels", type="integer", nullable=false)
	 * @Assert\Choice(callback="getValidWheels", groups={"flow_createVehicle_step1"}, strict=true)
	 * @Assert\NotBlank(groups={"flow_createVehicle_step1"})
	 */
	public $numberOfWheels;

	/**
	 * @var string
	 * @ORM\Column(name="engine", type="string", nullable=true)
	 * @Assert\Choice(callback="getValidEngines", groups={"flow_createVehicle_step2"}, strict=true)
	 * @Assert\NotBlank(groups={"flow_createVehicle_step2"})
	 */
	public $engine;

	/**
	 * @var Driver
	 * @ORM\ManyToOne(targetEntity="Driver", inversedBy="vehicles", cascade={"persist", "remove"})
	 * @Assert\Valid
	 */
	public $driver;

	public function canHaveEngine() {
		return $this->numberOfWheels === 4;
	}

	public static function getValidWheels() {
		return [
			2,
			4,
		];
	}

	public static function getValidEngines() {
		return [
			'ELECTRIC',
			'GAS',
			'NATURAL_GAS',
		];
	}

}
