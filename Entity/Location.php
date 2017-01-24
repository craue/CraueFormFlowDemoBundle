<?php

namespace Craue\FormFlowDemoBundle\Entity;

use Craue\FormFlowDemoBundle\Model\Regions;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2017 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Location {

	/**
	 * @var string
	 * @ORM\Column(name="country", type="string", nullable=false)
	 * @Assert\NotBlank(groups={"flow_createLocation_step1"})
	 * @Assert\Country(groups={"flow_createLocation_step1"})
	 */
	public $country;

	/**
	 * @var string
	 * @ORM\Column(name="region", type="string", nullable=true)
	 * @Assert\NotBlank(groups={"flow_createLocation_step2"})
	 */
	public $region;

	public function canHaveRegion() {
		return count(Regions::getRegionsForCountry($this->country)) > 0;
	}

}
