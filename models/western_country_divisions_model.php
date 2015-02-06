<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This file is a part of Open Cloud API Project.
 *
 * Open Cloud API project tries to provide free APIs for internet apps
 * to fetch public structured data (such as country list) or some
 * common computing services (such as generating QR code).
 *
 * For more information, please refer to:
 *
 *		http://www.fullstackengineer.net/zh/project/open-cloud-api-zh
 *		http://www.fullstackengineer.net/en/project/open-cloud-api-en
 *
 * Copyright (C) 2015 WEI Yongming
 * <http://www.fullstackengineer.net/zh/engineer/weiyongming>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class Western_country_divisions_model extends CI_Model {

	protected $_state_provinces = array(
		'US' => array(
			'AL' => 'Alabama',
			'AK' => 'Alaska',
			'AZ' => 'Arizona',
			'AR' => 'Arkansas',
			'CA' => 'California',
			'CO' => 'Colorado',
			'CT' => 'Connecticut',
			'DE' => 'Delaware',
			'FL' => 'Florida',
			'GA' => 'Georgia',
			'HI' => 'Hawaii',
			'ID' => 'Idaho',
			'IL' => 'Illinois',
			'IN' => 'Indiana',
			'IA' => 'Iowa',
			'KS' => 'Kansas',
			'KY' => 'Kentucky',
			'LA' => 'Louisiana',
			'ME' => 'Maine',
			'MD' => 'Maryland',
			'MA' => 'Massachusetts',
			'MI' => 'Michigan',
			'MN' => 'Minnesota',
			'MS' => 'Mississippi',
			'MO' => 'Missouri',
			'MT' => 'Montana',
			'NE' => 'Nebraska',
			'NV' => 'Nevada',
			'NH' => 'New Hampshire',
			'NJ' => 'New Jersey',
			'NM' => 'New Mexico',
			'NY' => 'New York',
			'NC' => 'North Carolina',
			'ND' => 'North Dakota',
			'OH' => 'Ohio',
			'OK' => 'Oklahoma',
			'OR' => 'Oregon',
			'PA' => 'Pennsylvania',
			'RI' => 'Rhode Island',
			'SC' => 'South Carolina',
			'SD' => 'South Dakota',
			'TN' => 'Tennessee',
			'TX' => 'Texas',
			'UT' => 'Utah',
			'VT' => 'Vermont',
			'VA' => 'Virginia',
			'WA' => 'Washington',
			'DC' => 'Washington, DC',
			'WV' => 'West Virginia',
			'WI' => 'Wisconsin',
			'WY' => 'Wyoming'
		),

		'CA' => array(
			'AB' => 'Alberta',
			'BC' => 'British Columbia',
			'MB' => 'Manitoba',
			'NB' => 'New Brunswick',
			'NF' => 'Newfoundland',
			'NT' => 'Northwest Territories',
			'NS' => 'Nova Scotia',
			'NU' => 'Nunavut',
			'ON' => 'Ontario',
			'PE' => 'Prince Edward Island',
			'QC' => 'Quebec',
			'SK' => 'Saskatchewan',
			'YT' => 'Yukon'
		),

		'AU' => array(
			'AAT' => 'Australian Antarctic Territory',
			'ACT' => 'Australian Capital Territory',
			'NT' => 'Northern Territory',
			'NSW' => 'New South Wales',
			'QLD' => 'Queensland',
			'SA' => 'South Australia',
			'TAS' => 'Tasmania',
			'VIC' => 'Victoria',
			'WA' => 'Western Australia',
		),

		'DE' => array(
			'BW' => 'Baden-Württemberg',
			'BY' => 'Bayern',
			'BE' => 'Berlin',
			'BB' => 'Brandenburg',
			'HB' => 'Bremen',
			'HH' => 'Hamburg',
			'HE' => 'Hessen',
			'MV' => 'Mecklenburg-Vorpommern',
			'NI' => 'Niedersachsen',
			'NW' => 'Nordrhein-Westfalen',
			'RP' => 'Rheinland-Pfalz',
			'SL' => 'Saarland',
			'SN' => 'Sachsen',
			'ST' => 'Sachsen-Anhalt',
			'SH' => 'Schleswig-Holstein',
			'TH' => 'Thüringen'
		),

		'FR' => array(
			'01' => 'Ain',
			'02' => 'Aisne',
			'03' => 'Allier',
			'04' => 'Alpes-de-Haute-Provence',
			'05' => 'Haute-Alpes',
			'06' => 'Alpes-Maritimes',
			'07' => 'Ardèche',
			'08' => 'Ardennes',
			'09' => 'Ariège',
			'10' => 'Aube',
			'11' => 'Aude',
			'12' => 'Aveyron',
			'13' => 'Bouches-du-Rhône',
			'14' => 'Calvados',
			'15' => 'Cantal',
			'16' => 'Charente',
			'17' => 'Charente-Maritime',
			'18' => 'Cher',
			'19' => 'Corrèze',
			'2A' => 'Corse-du-Sud',
			'2B' => 'Haute-Corse',
			'21' => 'Côte-d\'Or',
			'22' => 'Côte-d\'Armor',
			'23' => 'Creuse',
			'24' => 'Dordogne',
			'25' => 'Doubs',
			'26' => 'Drôme',
			'27' => 'Eure',
			'28' => 'Eure-et-Loir',
			'29' => 'Finistère',
			'30' => 'Gard',
			'31' => 'Haute-Garonne',
			'32' => 'Gers',
			'33' => 'Gironde',
			'34' => 'Hérault',
			'35' => 'Ille-et-Vilaine',
			'36' => 'Indre',
			'37' => 'Indre-et-Loire',
			'38' => 'Isère',
			'39' => 'Jura',
			'40' => 'Landes',
			'41' => 'Loir-et-Cher',
			'42' => 'Loire',
			'43' => 'Haute-Loire',
			'44' => 'Loire-Atlantique',
			'45' => 'Loiret',
			'46' => 'Lot',
			'47' => 'Lot-et-Garonne',
			'48' => 'Lozère',
			'49' => 'Maine-et-Loire',
			'50' => 'Manche',
			'51' => 'Marne',
			'52' => 'Haute-Marne',
			'53' => 'Mayenne',
			'54' => 'Meurthe-et-Moselle',
			'55' => 'Meuse',
			'56' => 'Morbihan',
			'57' => 'Moselle',
			'58' => 'Nièvre',
			'59' => 'Nord',
			'60' => 'Oise',
			'61' => 'Orne',
			'62' => 'Pas-de-Calais',
			'63' => 'Puy-de-Dôme',
			'64' => 'Pyrénées-Atlantiques',
			'65' => 'Hautes-Pyrénées',
			'66' => 'Pyrénées-Orientales',
			'67' => 'Bas-Rhin',
			'68' => 'Haut-Rhin',
			'69' => 'Rhône',
			'70' => 'Haute-Saône',
			'71' => 'Saône-et-Loire',
			'72' => 'Sarthe',
			'73' => 'Savoie',
			'74' => 'Haute-Savoie',
			'75' => 'Paris',
			'76' => 'Seine-Maritime',
			'77' => 'Seine-et-Marne',
			'78' => 'Yvelines',
			'79' => 'Deux-Sèvres',
			'80' => 'Somme',
			'81' => 'Tarn',
			'82' => 'Tarn-et-Garonne',
			'83' => 'Var',
			'84' => 'Vaucluse',
			'85' => 'Vendée',
			'86' => 'Vienne',
			'87' => 'Haute-Vienne',
			'88' => 'Vosges',
			'89' => 'Yonne',
			'90' => 'Territoire de Belfort',
			'91' => 'Essonne',
			'92' => 'Hauts-de-Seine',
			'93' => 'Seine-Saint-Denis',
			'94' => 'Val-de-Marne',
			'95' => 'Val-d\'Oise'
		),

		'GB' => array(
			'ANGLES' => 'Anglesey',
			'BRECK' => 'Brecknockshire',
			'CAERN' => 'Caernarfonshire',
			'CARMA' => 'Carmathenshire',
			'CARDIG' => 'Cardiganshire',
			'DENBIG' => 'Denbighshire',
			'FLINTS' => 'Flintshire',
			'GLAMO' => 'Glamorgan',
			'MERION' => 'Merioneth',
			'MONMOUTH' => 'Monmouthshire',
			'MONTG' => 'Mongtomeryshire',
			'PEMBR' => 'Pembrokeshire',
			'RADNOR' => 'Radnorshire',
			'ARBERD' => 'Aberdeenshire',
			'ANGUS' => 'Angus',
			'ARGYLL' => 'Argyllshire',
			'AYRSH' => 'Ayrshire',
			'BANFF' => 'Banffshire',
			'BERWICK' => 'Berwickshire',
			'BUTE' => 'Buteshire',
			'CROMART' => 'Cromartyshire',
			'CAITH' => 'Caithness',
			'CLACKM' => 'Clackmannanshire',
			'DUMFR' => 'Dumfriesshire',
			'DUNBART' => 'Dunbartonshire',
			'EASTL' => 'East Lothian',
			'FIFE' => 'Fife',
			'INVERN' => 'Inverness-shire',
			'KINCARD' => 'Kincardineshire',
			'KINROSS' => 'Kinross-shire',
			'KIRKCUD' => 'Kircudbrightshire',
			'LANARK' => 'Lanarkshire',
			'MIDLOTH' => 'Midlothian',
			'MORAY' => 'Morayshire',
			'NAIRN' => 'Nairnshire',
			'ORKNEY' => 'Orkeny',
			'PEEBLESS' => 'Peeblesshire',
			'PERTH' => 'Perthshire',
			'RENFREW' => 'Renfrewshire',
			'ROSSSH' => 'Ross-shire',
			'ROXBURGH' => 'Roxburghshire',
			'SELKIRK' => 'Selkirkshire',
			'SHETLAND' => 'Shetland',
			'STIRLING' => 'Stirlingshire',
			'SUTHER' => 'Sutherland',
			'WESTL' => 'West Lothian',
			'WIGTOWN' => 'Wigtownshire',
			'MERSEYSIDE' => 'Merseyside',
			'BEDS' => 'Bedfordshire',
			'LONDON' => 'London',
			'BERKS' => 'Berkshire',
			'BUCKS' => 'Buckinghamshire',
			'CAMBS' => 'Cambridgeshire',
			'CHESH' => 'Cheshire',
			'CORN' => 'Cornwall',
			'CUMB' => 'Cumberland',
			'DERBY' => 'Derbyshire',
			'DEVON' => 'Devon',
			'DORSET' => 'Dorset',
			'DURHAM' => 'Durham',
			'ESSEX' => 'Essex',
			'GLOUS' => 'Gloucestershire',
			'HANTS' => 'Hampshire',
			'HEREF' => 'Herefordshire',
			'HERTS' => 'Hertfordshire',
			'HUNTS' => 'Huntingdonshire',
			'KENT' => 'Kent',
			'LANCS' => 'Lancashire',
			'LEICS' => 'Leicestershire',
			'LINCS' => 'Lincolnshire',
			'MIDDLE' => 'Middlesex',
			'NORF' => 'Norfolk',
			'NHANTS' => 'Northamptonshire',
			'NTHUMB' => 'Northumberland',
			'NOTTS' => 'Nottinghamshire',
			'OXON' => 'Oxfordshire',
			'RUTL' => 'Rutland',
			'SHROPS' => 'Shropshire',
			'SOM' => 'Somerset',
			'STAFFS' => 'Staffordshire',
			'SUFF' => 'Suffolk',
			'SURREY' => 'Surrey',
			'SUSS' => 'Sussex',
			'WARKS' => 'Warwickshire',
			'WESTMOR' => 'Westmorland',
			'WILTS' => 'Wiltshire',
			'WORCES' => 'Worcestershire',
			'YORK' => 'Yorkshire'
		),

		'IE' => array(
			'CO ANTRIM' => 'County Antrim',
			'CO ARMAGH' => 'County Armagh',
			'CO DOWN' => 'County Down',
			'CO FERMANAGH' => 'County Fermanagh',
			'CO DERRY' => 'County Londonderry',
			'CO TYRONE' => 'County Tyrone',
			'CO CAVAN' => 'County Cavan',
			'CO DONEGAL' => 'County Donegal',
			'CO MONAGHAN' => 'County Monaghan',
			'CO DUBLIN' => 'County Dublin',
			'CO CARLOW' => 'County Carlow',
			'CO KILDARE' => 'County Kildare',
			'CO KILKENNY' => 'County Kilkenny',
			'CO LAOIS' => 'County Laois',
			'CO LONGFORD' => 'County Longford',
			'CO LOUTH' => 'County Louth',
			'CO MEATH' => 'County Meath',
			'CO OFFALY' => 'County Offaly',
			'CO WESTMEATH' => 'County Westmeath',
			'CO WEXFORD' => 'County Wexford',
			'CO WICKLOW' => 'County Wicklow',
			'CO GALWAY' => 'County Galway',
			'CO MAYO' => 'County Mayo',
			'CO LEITRIM' => 'County Leitrim',
			'CO ROSCOMMON' => 'County Roscommon',
			'CO SLIGO' => 'County Sligo',
			'CO CLARE' => 'County Clare',
			'CO CORK' => 'County Cork',
			'CO KERRY' => 'County Kerry',
			'CO LIMERICK' => 'County Limerick',
			'CO TIPPERARY' => 'County Tipperary',
			'CO WATERFORD' => 'County Waterford'
		),

		'NL' => array(
			'DR' => 'Drente',
			'FL' => 'Flevoland',
			'FR' => 'Friesland',
			'GL' => 'Gelderland',
			'GR' => 'Groningen',
			'LB' => 'Limburg',
			'NB' => 'Noord Brabant',
			'NH' => 'Noord Holland',
			'OV' => 'Overijssel',
			'UT' => 'Utrecht',
			'ZH' => 'Zuid Holland',
			'ZL' => 'Zeeland'
		),

		'BR' => array(
			'AC' => 'Acre',
			'AL' => 'Alagoas',
			'AM' => 'Amazonas',
			'AP' => 'Amapa',
			'BA' => 'Baia',
			'CE' => 'Ceara',
			'DF' => 'Distrito Federal',
			'ES' => 'Espirito Santo',
			'FN' => 'Fernando de Noronha',
			'GO' => 'Goias',
			'MA' => 'Maranhao',
			'MG' => 'Minas Gerais',
			'MS' => 'Mato Grosso do Sul',
			'MT' => 'Mato Grosso',
			'PA' => 'Para',
			'PB' => 'Paraiba',
			'PE' => 'Pernambuco',
			'PI' => 'Piaui',
			'PR' => 'Parana',
			'RJ' => 'Rio de Janeiro',
			'RN' => 'Rio Grande do Norte',
			'RO' => 'Rondonia',
			'RR' => 'Roraima',
			'RS' => 'Rio Grande do Sul',
			'SC' => 'Santa Catarina',
			'SE' => 'Sergipe',
			'SP' => 'Sao Paulo',
			'TO' => 'Tocatins'
		),

		'IT' => array(
			'AG' => 'Agrigento',
			'AL' => 'Alessandria',
			'AN' => 'Ancona',
			'AO' => 'Aosta',
			'AR' => 'Arezzo',
			'AP' => 'Ascoli Piceno',
			'AT' => 'Asti',
			'AV' => 'Avellino',
			'BA' => 'Bari',
			'BT' => 'Barletta-Andria-Trani',
			'BL' => 'Belluno',
			'BN' => 'Benevento',
			'BG' => 'Bergamo',
			'BI' => 'Biella',
			'BO' => 'Bologna',
			'BZ' => 'Bolzano',
			'BS' => 'Brescia',
			'BR' => 'Brindisi',
			'CA' => 'Cagliari',
			'CL' => 'Caltanissetta',
			'CB' => 'Campobasso',
			'CI' => 'Carbonia-Iglesias',
			'CE' => 'Caserta',
			'CT' => 'Catania',
			'CZ' => 'Catanzaro',
			'CH' => 'Chieti',
			'CO' => 'Como',
			'CS' => 'Cosenza',
			'CR' => 'Cremona',
			'KR' => 'Crotone',
			'CN' => 'Cuneo',
			'EN' => 'Enna',
			'FM' => 'Fermo',
			'FE' => 'Ferrara',
			'FI' => 'Firenze',
			'FG' => 'Foggia',
			'FC' => 'Forlì-Cesena',
			'FR' => 'Frosinone',
			'GE' => 'Genova',
			'GO' => 'Gorizia',
			'GR' => 'Grosseto',
			'IM' => 'Imperia',
			'IS' => 'Isernia',
			'AQ' => 'L’Aquila',
			'SP' => 'La Spezia',
			'LT' => 'Latina',
			'LE' => 'Lecce',
			'LC' => 'Lecco',
			'LI' => 'Livorno',
			'LO' => 'Lodi',
			'LU' => 'Lucca',
			'MC' => 'Macerata',
			'MN' => 'Mantova',
			'MS' => 'Massa e Carrara',
			'MT' => 'Matera',
			'VS' => 'Medio Campidano',
			'ME' => 'Messina',
			'MI' => 'Milano',
			'MO' => 'Modena',
			'MB' => 'Monza e Brianza',
			'NA' => 'Napoli',
			'NO' => 'Novara',
			'NU' => 'Nuoro',
			'OG' => 'Ogliastra',
			'OT' => 'Olbia-Tempio',
			'OR' => 'Oristano',
			'PD' => 'Padova',
			'PA' => 'Palermo',
			'PR' => 'Parma',
			'PV' => 'Pavia',
			'PG' => 'Perugia',
			'PU' => 'Pesaro e Urbino',
			'PE' => 'Pescara',
			'PC' => 'Piacenza',
			'PI' => 'Pisa',
			'PT' => 'Pistoia',
			'PN' => 'Pordenone',
			'PZ' => 'Potenza',
			'PO' => 'Prato',
			'RG' => 'Ragusa',
			'RA' => 'Ravenna',
			'RC' => 'Reggio Calabria',
			'RE' => 'Reggio Emilia',
			'RI' => 'Rieti',
			'RN' => 'Rimini',
			'RM' => 'Roma',
			'RO' => 'Rovigo',
			'SA' => 'Salerno',
			'SS' => 'Sassari',
			'SV' => 'Savona',
			'SI' => 'Siena',
			'SR' => 'Siracusa',
			'SO' => 'Sondrio',
			'TA' => 'Taranto',
			'TE' => 'Teramo',
			'TR' => 'Terni',
			'TO' => 'Torino',
			'TP' => 'Trapani',
			'TN' => 'Trento',
			'TV' => 'Treviso',
			'TS' => 'Trieste',
			'UD' => 'Udine',
			'VA' => 'Varese',
			'VE' => 'Venezia',
			'VB' => 'Verbano-Cusio-Ossola',
			'VC' => 'Vercelli',
			'VR' => 'Verona',
			'VV' => 'Vibo Valentia',
			'VI' => 'Vicenza',
			'VT' => 'Viterbo'
		)
	);

	public function __construct() {
		// Call the Model constructor
		parent::__construct();
	}

	public function init () {
		$message = '';

		$this->load->database ();
		foreach ($this->_state_provinces as $c_code => $c_states) {
			$alpha_2_code = $c_code;
			switch ($c_code) {
			case 'US':
			case 'CA':
			case 'AU':
			case 'GB':
			case 'IE':
				$lang = 'en';
				break;
			case 'DE':
				$lang = 'de';
				break;
			case 'FR':
				$lang = 'fr';
				break;
			case 'NL':
				$lang = 'nl';
				break;
			case 'BR':
				$lang = 'pt';
				break;
			case 'IT':
				$lang = 'it';
				break;
			}

			$query = $this->db->query ('SELECT numeric_code FROM api_country_codes WHERE alpha_2_code=?',
					array ($alpha_2_code));
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$numeric_code = $row->numeric_code;
			}
			else {
				$message .= "Bad alpha_2 code: $alpha_2_code" . PHP_EOL;
				continue;
			}

			$i = 1;
			foreach ($c_states as $s_code => $s_name) {
				$adm_code = "$c_code-$s_code";
				if (preg_match('/^[0-9]*$/', $s_code)) {
					$division_id = ($numeric_code * 1024) + (int)$s_code;
				}
				else {
					$division_id = ($numeric_code * 1024) + $i;
				}
				++$i;

				$message .= "division: $division_id, $lang, $adm_code, $s_name" . PHP_EOL;

				$this->db->query ('INSERT INTO api_country_divisions (division_id, locale, name, adm_code)
			VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=?',
					array ($division_id, $lang . '_' . $alpha_2_code, $s_name, $adm_code, $s_name));

				$this->db->query ('INSERT INTO api_country_division_localized_names (division_id, locale, localized_name)
			VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE localized_name=?',
					array ($division_id, $lang, $s_name, $s_name));

			}
		}
	}
}

/* End of file access_token_model.php */
