<?php

namespace Craue\FormFlowDemoBundle\Model;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2020 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
abstract class Regions {

	public static function getRegionsForCountry($country) {
		switch ($country) {
			case 'AT':
				return [
					// source: http://de.wikipedia.org/wiki/ISO_3166-2:AT
					'AT-1',
					'AT-2',
					'AT-3',
					'AT-4',
					'AT-5',
					'AT-6',
					'AT-7',
					'AT-8',
					'AT-9',
				];
			case 'CH':
				return [
					// source: http://de.wikipedia.org/wiki/ISO_3166-2:CH
					'CH-AG',
					'CH-AR',
					'CH-AI',
					'CH-BL',
					'CH-BS',
					'CH-BE',
					'CH-FR',
					'CH-GE',
					'CH-GL',
					'CH-GR',
					'CH-JU',
					'CH-LU',
					'CH-NE',
					'CH-NW',
					'CH-OW',
					'CH-SH',
					'CH-SZ',
					'CH-SO',
					'CH-SG',
					'CH-TI',
					'CH-TG',
					'CH-UR',
					'CH-VD',
					'CH-VS',
					'CH-ZG',
					'CH-ZH',
				];
			case 'DE':
				return [
					// source: http://de.wikipedia.org/wiki/ISO_3166-2:DE
					'DE-BW',
					'DE-BY',
					'DE-BE',
					'DE-BB',
					'DE-HB',
					'DE-HH',
					'DE-HE',
					'DE-MV',
					'DE-NI',
					'DE-NW',
					'DE-RP',
					'DE-SL',
					'DE-SN',
					'DE-ST',
					'DE-SH',
					'DE-TH',
				];
			case 'US':
				return [
					// source: http://de.wikipedia.org/wiki/Bundesstaat_der_Vereinigten_Staaten
					'US-AL',
					'US-AK',
					'US-AZ',
					'US-AR',
					'US-CA',
					'US-CO',
					'US-CT',
					'US-DE',
					'US-FL',
					'US-GA',
					'US-HI',
					'US-ID',
					'US-IL',
					'US-IN',
					'US-IA',
					'US-KS',
					'US-KY',
					'US-LA',
					'US-ME',
					'US-MD',
					'US-MA',
					'US-MI',
					'US-MN',
					'US-MS',
					'US-MO',
					'US-MT',
					'US-NE',
					'US-NV',
					'US-NH',
					'US-NJ',
					'US-NM',
					'US-NY',
					'US-NC',
					'US-ND',
					'US-OH',
					'US-OK',
					'US-OR',
					'US-PA',
					'US-RI',
					'US-SC',
					'US-SD',
					'US-TN',
					'US-TX',
					'US-UT',
					'US-VT',
					'US-VA',
					'US-WA',
					'US-WV',
					'US-WI',
					'US-WY',
				];
			default:
				return [];
		}
	}

}
