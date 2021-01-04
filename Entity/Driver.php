<?php

namespace Craue\FormFlowDemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="craue_formflowdemo_driver")
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2021 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Driver {

	use EntityHasIdTrait;

	/**
	 * @var Vehicle[]
	 * @ORM\OneToMany(targetEntity="Vehicle", mappedBy="driver")
	 * @ORM\JoinColumn(name="vehicle_id", referencedColumnName="id", nullable=false)
	 * @Assert\NotNull(groups={"flow_createVehicle_step4"})
	 */
	public $vehicles;

	/**
	 * @var string
	 * @ORM\Column(name="firstname", type="string", nullable=false)
	 * @Assert\NotBlank(groups={"flow_createVehicle_step4"})
	 */
	public $firstname;

	/**
	 * @var string
	 * @ORM\Column(name="lastname", type="string", nullable=false)
	 * @Assert\NotBlank(groups={"flow_createVehicle_step4"})
	 */
	public $lastname;

	public function __construct() {
		$this->vehicles = new ArrayCollection();
	}

}
