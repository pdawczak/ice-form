<?php
namespace Ice\FormBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CountryType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'expanded'=>false,
            'multiple'=>false,
            'preferred_choices' => array('GBR'),
            'choices'=>array(
                'AFG' => 'Afghanistan',
                'ALA' => 'Aland Islands',
                'ALB' => 'Albania',
                'DZA' => 'Algeria',
                'ASM' => 'American Samoa',
                'AND' => 'Andorra',
                'AGO' => 'Angola',
                'AIA' => 'Anguilla',
                'ATA' => 'Antarctica',
                'ATG' => 'Antigua and Barbuda',
                'ARG' => 'Argentina',
                'ARM' => 'Armenia',
                'ABW' => 'Aruba',
                'AUS' => 'Australia',
                'AUT' => 'Austria',
                'AZE' => 'Azerbaijan',
                'BHS' => 'Bahamas',
                'BHR' => 'Bahrain',
                'BGD' => 'Bangladesh',
                'BRB' => 'Barbados',
                'BLR' => 'Belarus',
                'BEL' => 'Belgium',
                'BLZ' => 'Belize',
                'BEN' => 'Benin',
                'BMU' => 'Bermuda',
                'BTN' => 'Bhutan',
                'BOL' => 'Bolivia',
                'BIH' => 'Bosnia and Herzegovina',
                'BWA' => 'Botswana',
                'BVT' => 'Bouvet Island',
                'BRA' => 'Brazil',
                'IOT' => 'British Indian Ocean Territory',
                'BOT' => 'British Overseas Territories',
                'BRN' => 'Brunei Darussalam',
                'BGR' => 'Bulgaria',
                'BFA' => 'Burkina Faso',
                'BDI' => 'Burundi',
                'KHM' => 'Cambodia',
                'CMR' => 'Cameroon',
                'CAN' => 'Canada',
                'CPV' => 'Cape Verde',
                'CYM' => 'Cayman Islands',
                'CAF' => 'Central African Republic',
                'TCD' => 'Chad',
                'CHI' => 'Channel Islands',
                'CHL' => 'Chile',
                'CHN' => 'China',
                'CXR' => 'Christmas Island',
                'CCK' => 'Cocos (Keeling) Islands',
                'CLG' => 'College Address',
                'COL' => 'Colombia',
                'COM' => 'Comoros',
                'COG' => 'Congo',
                'COD' => 'Congo, The Democratic Republic',
                'COK' => 'Cook Islands',
                'CRI' => 'Costa Rica',
                'CIV' => 'Cote D\'Ivoire',
                'HRV' => 'Croatia',
                'CUB' => 'Cuba',
                'CYP' => 'Cyprus',
                'CZE' => 'Czech Republic',
                'DNK' => 'Denmark',
                'DJI' => 'Djibouti',
                'DMA' => 'Dominica',
                'DOM' => 'Dominican Republic',
                'TLS' => 'East Timor',
                'ECU' => 'Ecuador',
                'EGY' => 'Egypt',
                'SLV' => 'El Salvador',
                'GNQ' => 'Equatorial Guinea',
                'ERI' => 'Eritrea',
                'EST' => 'Estonia',
                'ETH' => 'Ethiopia',
                'FLK' => 'Falkland Islands (Malvinas)',
                'FRO' => 'Faroe Islands',
                'FJI' => 'Fiji',
                'FIN' => 'Finland',
                'MKD' => 'Fmr Yugoslav Rep of Macedonia',
                'FRA' => 'France',
                'GUF' => 'French Guiana',
                'PYF' => 'French Polynesia',
                'ATF' => 'French Southern Territories',
                'GAB' => 'Gabon',
                'GMB' => 'Gambia',
                'GEO' => 'Georgia',
                'DEU' => 'Germany',
                'GHA' => 'Ghana',
                'GIB' => 'Gibraltar',
                'GRC' => 'Greece',
                'GRL' => 'Greenland',
                'GRD' => 'Grenada',
                'GLP' => 'Guadeloupe',
                'GUM' => 'Guam',
                'GTM' => 'Guatemala',
                'GGY' => 'Guernsey',
                'GIN' => 'Guinea',
                'GNB' => 'Guinea-Bissau',
                'GUY' => 'Guyana',
                'HTI' => 'Haiti',
                'HMD' => 'Heard and McDonald Islands',
                'VAT' => 'Holy See (Vatican City State)',
                'HND' => 'Honduras',
                'HKG' => 'Hong Kong',
                'HUN' => 'Hungary',
                'ISL' => 'Iceland',
                'IND' => 'India',
                'IDN' => 'Indonesia',
                'IRN' => 'Iran (Islamic Republic Of)',
                'IRQ' => 'Iraq',
                'IRL' => 'Ireland',
                'IMN' => 'Isle of Man',
                'ISR' => 'Israel',
                'ITA' => 'Italy',
                'JAM' => 'Jamaica',
                'JPN' => 'Japan',
                'JEY' => 'Jersey',
                'JOR' => 'Jordan',
                'KAZ' => 'Kazakhstan',
                'KEN' => 'Kenya',
                'KIR' => 'Kiribati',
                'PRK' => 'Korea, Democratic People\'s Rep',
                'KOR' => 'Korea, Republic of',
                'KOS' => 'Kosovo',
                'KWT' => 'Kuwait',
                'KGZ' => 'Kyrgyzstan',
                'LAO' => 'Lao People\'s Democratic Rep',
                'LVA' => 'Latvia',
                'LBN' => 'Lebanon',
                'LSO' => 'Lesotho',
                'LBR' => 'Liberia',
                'LBY' => 'Libyan Arab Jamahiriya',
                'LIE' => 'Liechtenstein',
                'LTU' => 'Lithuania',
                'LUX' => 'Luxembourg',
                'MAC' => 'Macao',
                'MDG' => 'Madagascar',
                'MWI' => 'Malawi',
                'MYS' => 'Malaysia',
                'MDV' => 'Maldives',
                'MLI' => 'Mali',
                'MLT' => 'Malta',
                'MHL' => 'Marshall Islands',
                'MTQ' => 'Martinique',
                'MRT' => 'Mauritania',
                'MUS' => 'Mauritius',
                'MYT' => 'Mayotte',
                'MEX' => 'Mexico',
                'FSM' => 'Micronesia, Federated States',
                'MDA' => 'Moldova, Republic of',
                'MCO' => 'Monaco',
                'MNG' => 'Mongolia',
                'MON' => 'Montenegro',
                'MSR' => 'Montserrat',
                'MAR' => 'Morocco',
                'MOZ' => 'Mozambique',
                'MMR' => 'Myanmar',
                'NAM' => 'Namibia',
                'NRU' => 'Nauru',
                'NPL' => 'Nepal',
                'NLD' => 'Netherlands',
                'ANT' => 'Netherlands Antilles',
                'NCL' => 'New Caledonia',
                'NZL' => 'New Zealand',
                'NIC' => 'Nicaragua',
                'NER' => 'Niger',
                'NGA' => 'Nigeria',
                'NIU' => 'Niue',
                'NFK' => 'Norfolk Island',
                'MNP' => 'Northern Mariana Islands',
                'NOR' => 'Norway',
                'OMN' => 'Oman',
                'PAK' => 'Pakistan',
                'PLW' => 'Palau',
                'PSE' => 'Palestinian Territory, Occupie',
                'PAN' => 'Panama',
                'PNG' => 'Papua New Guinea',
                'PRY' => 'Paraguay',
                'PER' => 'Peru',
                'PHL' => 'Philippines',
                'PCN' => 'Pitcairn',
                'POL' => 'Poland',
                'PRT' => 'Portugal',
                'PRI' => 'Puerto Rico',
                'QAT' => 'Qatar',
                'MNE' => 'Republic of Montenegro',
                'SRB' => 'Republic of Serbia',
                'REU' => 'Reunion',
                'ROU' => 'Romania',
                'RUS' => 'Russian Federation',
                'RWA' => 'Rwanda',
                'BLM' => 'Saint Barthelemy',
                'SHN' => 'Saint Helena',
                'KNA' => 'Saint Kitts and Nevis',
                'LCA' => 'Saint Lucia',
                'MAF' => 'Saint Martin',
                'SPM' => 'Saint Pierre and Miquelon',
                'WSM' => 'Samoa',
                'SMR' => 'San Marino',
                'STP' => 'Sao Tome and Principe',
                'SAU' => 'Saudi Arabia',
                'SEN' => 'Senegal',
                'SAM' => 'Serbia and Montenegro',
                'SYC' => 'Seychelles',
                'SLE' => 'Sierra Leone',
                'SGP' => 'Singapore',
                'SVK' => 'Slovakia',
                'SVN' => 'Slovenia',
                'SLB' => 'Solomon Islands',
                'SOM' => 'Somalia',
                'ZAF' => 'South Africa',
                'ESP' => 'Spain',
                'LKA' => 'Sri Lanka',
                'VCT' => 'St Vincent and the Grenadines',
                'STL' => 'Stateless',
                'SGS' => 'Sth Georgia & Sth Sandwich Is',
                'SDN' => 'Sudan',
                'SUR' => 'Suriname',
                'SJM' => 'Svalbard and Jan Mayen',
                'SWZ' => 'Swaziland',
                'SWE' => 'Sweden',
                'CHE' => 'Switzerland',
                'SYR' => 'Syrian Arab Republic',
                'TWN' => 'Taiwan, Province of China',
                'TJK' => 'Tajikistan',
                'TZA' => 'Tanzania, United Republic of',
                'THA' => 'Thailand',
                'TGO' => 'Togo',
                'TKL' => 'Tokelau',
                'TON' => 'Tonga',
                'TTO' => 'Trinidad and Tobago',
                'TUN' => 'Tunisia',
                'TUR' => 'Turkey',
                'TKM' => 'Turkmenistan',
                'TCA' => 'Turks and Caicos Islands',
                'TUV' => 'Tuvalu',
                'UGA' => 'Uganda',
                'UKR' => 'Ukraine',
                'ARE' => 'United Arab Emirates',
                'GBR' => 'United Kingdom',
                'USA' => 'United States',
                'UNK' => 'Unknown',
                'URY' => 'Uruguay',
                'UMI' => 'US Minor Outlying Islands',
                'UZB' => 'Uzbekistan',
                'VUT' => 'Vanuatu',
                'VEN' => 'Venezuela',
                'VNM' => 'Viet Nam',
                'VGB' => 'Virgin Islands (British)',
                'VIR' => 'Virgin Islands (U.S.)',
                'WLF' => 'Wallis and Futuna Islands',
                'ESH' => 'Western Sahara',
                'YEM' => 'Yemen',
                'YUG' => 'Yugoslavia',
                'ZMB' => 'Zambia',
                'ZWE' => 'Zimbabwe',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'country';
    }
}