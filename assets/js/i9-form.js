/**
 * Created by Adee on 4/10/2018.
 */

var doc_selection = [{}];
doc_selection['citizen']            = {'n_a':'N/A','U.S. Passport':'U.S. Passport','U.S. Passport Card':'U.S. Passport Card'};
doc_selection['noncitizen']         = {'n_a':'N/A','U.S. Passport':'U.S. Passport','U.S. Passport Card':'U.S. Passport Card'};
doc_selection['permanent-resident'] = {'n_a':'N/A','Perm. Resident Card (Form I-551)':'Perm. Resident Card (Form I-551)','Alien Reg. Receipt Card (Form I-551)':'Alien Reg. Receipt Card (Form I-551)','Foreign Passport with Temp. I-551 Stamp':'Foreign Passport with Temp. I-551 Stamp','Foreign Passport with Temp. I-551 MRIV':'Foreign Passport with Temp. I-551 MRIV','Receipt Form I-94/I-94A w/I-551 stamp, photo':'Receipt Form I-94/I-94A w/I-551 stamp, photo','Receipt Replacement Perm. Res. Card (Form I-551)':'Receipt Replacement Perm. Res. Card (Form I-551)'};
doc_selection['alien-work']         = {'n_a':'N/A','Employment Auth. Document (Form I-766)':'Employment Auth. Document (Form I-766)','Foreign Passport, work-authorized nonimmigrant':'Foreign Passport, work-authorized nonimmigrant','FSM Passport with Form I-94':'FSM Passport with Form I-94','RMI Passport with Form I-94':'RMI Passport with Form I-94','Receipt Replacement EAD (Form I-766)':'Receipt Replacement EAD (Form I-766)','Receipt Form I-94/I-94A w/refugee stamp (or RE class of admission)':'Receipt Form I-94/I-94A w/refugee stamp (or RE class of admission)','Receipt Foreign Passport, work-authorized nonimmigrant':'Receipt Foreign Passport, work-authorized nonimmigrant','Receipt FSM Passport with Form I-94':'Receipt FSM Passport with Form I-94','Receipt RMI Passport with Form I-94':'Receipt RMI Passport with Form I-94'};

var doc_part1 = [{}];
var listc_doc = [{}];
var listc_auth = [{}];
var listb_doc = {};
var listb_auth = [{}];
var doc_part2_doc = [{}];
var doc_part2_auth = [{}];
var doc_part3_doc = [{}];
var doc_part3_auth = [{}];
var countries =
    {
        "AF": "Afghanistan", "AL": "Albania", "DZ": "Algeria", "AS": "American Samoa", "AD": "AndorrA", "AO": "Angola", "AI": "Anguilla", "AQ": "Antarctica", "AG": "Antigua and Barbuda",
        "AR": "Argentina", "AM": "Armenia", "AW": "Aruba", "AU": "Australia", "AT": "Austria", "AZ": "Azerbaijan", "BS": "Bahamas", "BH": "Bahrain", "BD": "Bangladesh", "BB": "Barbados", "BY": "Belarus", "BE": "Belgium",
        "BZ": "Belize", "BJ": "Benin", "BM": "Bermuda", "BT": "Bhutan", "BO": "Bolivia", "BA": "Bosnia and Herzegovina", "BW": "Botswana", "BV": "Bouvet Island", "BR": "Brazil", "IO": "British Indian Ocean Territory",
        "BN": "Brunei Darussalam", "BG": "Bulgaria",
        "BF": "Burkina Faso","BI": "Burundi","KH": "Cambodia","CM": "Cameroon","CA": "Canada","CV": "Cape Verde","KY": "Cayman Islands","CF": "Central African Republic","TD": "Chad","CL": "Chile","CN": "China",
        "CX": "Christmas Island","CC": "Cocos (Keeling) Islands","CO": "Colombia","KM": "Comoros","CG": "Congo","CD": "Congo, The Democratic Republic of the","CK": "Cook Islands","CR": "Costa Rica",
        "CI": "Cote D'Ivoire","HR": "Croatia","CU": "Cuba","CY": "Cyprus","CZ": "Czech Republic","DK": "Denmark","DJ": "Djibouti","DM": "Dominica","DO": "Dominican Republic","EC": "Ecuador","EG": "Egypt",
        "SV": "El Salvador","GQ": "Equatorial Guinea","ER": "Eritrea","EE": "Estonia","ET": "Ethiopia","FK": "Falkland Islands (Malvinas)","FO": "Faroe Islands","FJ": "Fiji","FI": "Finland","FR": "France",
        "GF": "French Guiana","PF": "French Polynesia","TF": "French Southern Territories","GA": "Gabon","GM": "Gambia","GE": "Georgia","DE": "Germany","GH": "Ghana","GI": "Gibraltar","GR": "Greece",
        "GL": "Greenland","GD": "Grenada","GP": "Guadeloupe","GU": "Guam","GT": "Guatemala","GG": "Guernsey","GN": "Guinea","GW": "Guinea-Bissau","GY": "Guyana","HT": "Haiti","HM": "Heard Island and Mcdonald Islands",
        "VA": "Holy See (Vatican City State)","HN": "Honduras","HK": "Hong Kong","HU": "Hungary","IS": "Iceland","IN": "India","ID": "Indonesia","IR": "Iran, Islamic Republic Of","IQ": "Iraq","IE": "Ireland",
        "IM": "Isle of Man","IL": "Israel","IT": "Italy","JM": "Jamaica","JP": "Japan","JE": "Jersey","JO": "Jordan","KZ": "Kazakhstan","KE": "Kenya","KI": "Kiribati","KP": "Korea, Democratic People'S Republic of",
        "KR": "Korea, Republic of","KW": "Kuwait","KG": "Kyrgyzstan","LA": "Lao People'S Democratic Republic","LV": "Latvia","LB": "Lebanon","LS": "Lesotho","LR": "Liberia","LY": "Libyan Arab Jamahiriya",
        "LI": "Liechtenstein","LT": "Lithuania","LU": "Luxembourg","MO": "Macao","MK": "Macedonia, The Former Yugoslav Republic of","MG": "Madagascar","MW": "Malawi","MY": "Malaysia","MV": "Maldives",
        "ML": "Mali","MT": "Malta","MH": "Marshall Islands","MQ": "Martinique","MR": "Mauritania","MU": "Mauritius","YT": "Mayotte","MX": "Mexico","FM": "Micronesia, Federated States of","MD": "Moldova, Republic of",
        "MC": "Monaco","MN": "Mongolia","MS": "Montserrat","MA": "Morocco","MZ": "Mozambique","MM": "Myanmar","NA": "Namibia","NR": "Nauru","NP": "Nepal","NL": "Netherlands","AN": "Netherlands Antilles",
        "NC": "New Caledonia","NZ": "New Zealand","NI": "Nicaragua","NE": "Niger","NG": "Nigeria","NU": "Niue","NF": "Norfolk Island","MP": "Northern Mariana Islands","NO": "Norway","OM": "Oman","PK": "Pakistan",
        "PW": "Palau","PS": "Palestinian Territory, Occupied","PA": "Panama","PG": "Papua New Guinea","PY": "Paraguay","PE": "Peru","PH": "Philippines","PN": "Pitcairn","PL": "Poland","PT": "Portugal",
        "QA": "Qatar","RE": "Reunion","RO": "Romania","RU": "Russian Federation","RW": "RWANDA","SH": "Saint Helena","KN": "Saint Kitts and Nevis","LC": "Saint Lucia","PM": "Saint Pierre and Miquelon",
        "VC": "Saint Vincent and the Grenadines","WS": "Samoa","SM": "San Marino","ST": "Sao Tome and Principe","SA": "Saudi Arabia","SN": "Senegal","CS": "Serbia and Montenegro","SC": "Seychelles",
        "SL": "Sierra Leone","SG": "Singapore","SK": "Slovakia","SI": "Slovenia","SB": "Solomon Islands","SO": "Somalia","ZA": "South Africa","GS": "South Georgia and the South Sandwich Islands",
        "ES": "Spain","LK": "Sri Lanka","SD": "Sudan","SR": "Suriname","SJ": "Svalbard and Jan Mayen","SZ": "Swaziland","SE": "Sweden","CH": "Switzerland","SY": "Syrian Arab Republic","TW": "Taiwan, Province of China",
        "TJ": "Tajikistan","TZ": "Tanzania, United Republic of","TH": "Thailand","TL": "Timor-Leste","TG": "Togo","TK": "Tokelau","TO": "Tonga","TT": "Trinidad and Tobago","TN": "Tunisia","TR": "Turkey",
        "TM": "Turkmenistan","TC": "Turks and Caicos Islands","TV": "Tuvalu","UG": "Uganda","UA": "Ukraine","AE": "United Arab Emirates","GB": "United Kingdom","US": "United States","UM": "United States Minor Outlying Islands",
        "UY": "Uruguay","UZ": "Uzbekistan","VU": "Vanuatu","VE": "Venezuela","VN": "Viet Nam","VG": "Virgin Islands, British","VI": "Virgin Islands, U.S.","WF": "Wallis and Futuna","EH": "Western Sahara",
        "YE": "Yemen","ZM": "Zambia","ZW": "Zimbabwe"
    };

var us_states =
    
        {
            'AL': 'Alabama',
            'AK' : 'Alaska',
            'AS' : 'American Samoa',
            'AZ' : 'Arizona',
            'AR' : 'Arkansas',
            'CA' : 'California',
            'CO' : 'Colorado',
            'CT' : 'Connecticut',
            'DE' : 'Delaware',
            'DC' : 'Dist of Columbia',
            'FL' : 'Florida',
            'GA' : 'Georgia',
            'GU' : 'Guam',
            'HI' : 'Hawaii',
            'ID' : 'Idaho',
            'IL' : 'Illinois',
            'IN' : 'Indiana',
            'IA' : 'Iowa',
            'KS' : 'Kansas',
            'KY' : 'Kentucky',
            'LA' : 'Louisiana',
            'ME' : 'Maine',
            'MH' : 'Marshall Islands',
            'MD' : 'Maryland',
            'MA' : 'Massachusetts',
            'MI' : 'Michigan',
            'FM' : 'Micronesia',
            'MN' : 'Minnesota',
            'MS' : 'Mississippi',
            'MO' : 'Missouri',
            'MT' : 'Montana',
            'NE' : 'Nebraska',
            'NV' : 'Nevada',
            'NH' : 'New Hampshire',
            'NJ' : 'New Jersey',
            'NM' : 'New Mexico',
            'NY' : 'New York',
            'NC' : 'North Carolina',
            'ND' : 'North Dakota',
            'MP' : 'Northern Mariana Islands',
            'OH' : 'Ohio',
            'OK' : 'Oklahoma',
            'OR' : 'Oregon',
            'PW' : 'Palau',
            'PA' : 'Pennsylvania',
            'PR' : 'Puerto Rico',
            'RI' : 'Rhode Island',
            'SC' : 'South Carolina',
            'SD' : 'South Dakota',
            'TN' : 'Tennessee',
            'TX' : 'Texas',
            'UT' : 'Utah',
            'VT' : 'Vermont',
            'VI' : 'Virgin Islands (US)',
            'VA' : 'Virginia',
            'WA' : 'Washington',
            'WV' : 'West Virginia',
            'WI' : 'Wisconsin',
            'WY' : 'Wyoming'
    };

    countries = us_states;

var canadian_states =
    {
    "AB": "Alberta",
    "BC": "British Columbia",
    "MB": "Manitoba",
    "NB": "New Brunswick",
    "NL": "Newfoundland and Labrador",
    "NS": "Nova Scotia",
    "NT": "Northwest Territories",
    "NU": "Nunavut",
    "ON": "Ontario",
    "PE": "Prince Edward Island",
    "QC": "Quebec",
    "SK": "Saskatchewan",
    "YT": "Yukon"
}
doc_part1['n_a'] = {'n_a':'N/A'};
doc_part1['U.S. Passport'] = {'U.S. Department of State':'U.S. Department of State','n_a':'N/A'};
doc_part1['U.S. Passport Card'] = {'U.S. Department of State':'U.S. Department of State','n_a':'N/A'};
doc_part1['Perm. Resident Card (Form I-551)'] = {'U.S. Citizenship and Immigration Services':'U.S. Citizenship and Immigration Services','DOJ Immigration and Naturalization Service':'DOJ Immigration and Naturalization Service','n_a':'N/A'};
doc_part1['Alien Reg. Receipt Card (Form I-551)'] = {'DOJ Immigration and Naturalization Service':'DOJ Immigration and Naturalization Service','n_a':'N/A'};
doc_part1['Foreign Passport with Temp. I-551 Stamp'] = countries;
doc_part1['Foreign Passport with Temp. I-551 MRIV'] = countries;
doc_part1['Receipt Form I-94/I-94A w/I-551 stamp, photo'] = {'U.S. Department of Homeland Security':'U.S. Department of Homeland Security','n_a':'N/A'};
doc_part1['Receipt Replacement Perm. Res. Card (Form I-551)'] = {'U.S. Citizenship and Immigration Services':'U.S. Citizenship and Immigration Services','n_a':'N/A'};
doc_part1['Employment Auth. Document (Form I-766)'] = {'U.S. Citizenship and Immigration Services':'U.S. Citizenship and Immigration Services','n_a':'N/A'};
doc_part1['Foreign Passport, work-authorized nonimmigrant'] = countries;
doc_part1['FSM Passport with Form I-94'] = {'Federated States of Micronesia':'Federated States of Micronesia','n_a':'N/A'};
doc_part1['RMI Passport with Form I-94'] = {'Republic of the Marshall Islands':'Republic of the Marshall Islands','n_a':'N/A'};
doc_part1['Receipt Replacement EAD (Form I-766)'] = {'U.S. Citizenship and Immigration Services':'U.S. Citizenship and Immigration Services','n_a':'N/A'};
doc_part1['Receipt Form I-94/I-94A w/refugee stamp (or RE class of admission)'] = {'U.S. Department of Homeland Security':'U.S. Department of Homeland Security','n_a':'N/A'};
doc_part1['Receipt Foreign Passport, work-authorized nonimmigrant'] = countries;
doc_part1['Receipt FSM Passport with Form I-94'] = {'Federated States of Micronesia':'Federated States of Micronesia','n_a':'N/A'};
doc_part1['Receipt RMI Passport with Form I-94'] = {'Republic of the Marshall Islands':'Republic of the Marshall Islands','n_a':'N/A'};

doc_part2_doc['n_a'] = {'n_a':'N/A'};
doc_part2_doc['U.S. Passport'] = {'n_a':'N/A'};
doc_part2_doc['U.S. Passport Card'] = {'n_a':'N/A'};
doc_part2_doc['Perm. Resident Card (Form I-551)'] = {'n_a':'N/A'};
doc_part2_doc['Alien Reg. Receipt Card (Form I-551)'] = {'n_a':'N/A'};
doc_part2_doc['Foreign Passport with Temp. I-551 Stamp'] = {'Temporary I-551 Stamp':'Temporary I-551 Stamp','n_a':'N/A'};
doc_part2_doc['Foreign Passport with Temp. I-551 MRIV'] = {'mach_readable':'Machine-readable immigrant visa (MRIV)','n_a':'N/A'};
doc_part2_doc['Receipt Form I-94/I-94A w/I-551 stamp, photo'] = {'n_a':'N/A'};
doc_part2_doc['Receipt Replacement Perm. Res. Card (Form I-551)'] = {'n_a':'N/A'};
doc_part2_doc['Employment Auth. Document (Form I-766)'] = {'n_a':'N/A'};
doc_part2_doc['Receipt Replacement EAD (Form I-766)'] = {'n_a':'N/A'};
doc_part2_doc['Receipt Form I-94/I-94A w/refugee stamp (or RE class of admission)'] = {'n_a':'N/A'};
doc_part2_doc['Foreign Passport, work-authorized nonimmigrant'] = {'Form I-94/I-94A':'Form I-94/I-94A', 'Receipt Form I-94/I-94A':'Receipt Form I-94/I-94A','n_a':'N/A'};
doc_part2_doc['Receipt Foreign Passport, work-authorized nonimmigrant']  = {'Form I-94/I-94A':'Form I-94/I-94A', 'Receipt Form I-94/I-94A':'Receipt Form I-94/I-94A','n_a':'N/A'};
doc_part2_doc['FSM Passport with Form I-94'] = {'Form I-94/I-94A':'Form I-94/I-94A', 'Receipt Form I-94/I-94A':'Receipt Form I-94/I-94A','n_a':'N/A'};
doc_part2_doc['RMI Passport with Form I-94'] = {'Form I-94/I-94A':'Form I-94/I-94A', 'Receipt Form I-94/I-94A':'Receipt Form I-94/I-94A','n_a':'N/A'};
doc_part2_doc['Receipt FSM Passport with Form I-94'] = {'Form I-94/I-94A':'Form I-94/I-94A', 'Receipt Form I-94/I-94A':'Receipt Form I-94/I-94A','n_a':'N/A'};
doc_part2_doc['Receipt RMI Passport with Form I-94'] = {'Form I-94/I-94A':'Form I-94/I-94A', 'Receipt Form I-94/I-94A':'Receipt Form I-94/I-94A','n_a':'N/A'};

doc_part2_auth['n_a'] = {'n_a':'N/A'};
doc_part2_auth['U.S. Passport'] = {'n_a':'N/A'};
doc_part2_auth['U.S. Passport Card'] = {'n_a':'N/A'};
doc_part2_auth['Perm. Resident Card (Form I-551)'] = {'n_a':'N/A'};
doc_part2_auth['Alien Reg. Receipt Card (Form I-551)'] = {'n_a':'N/A'};
doc_part2_auth['Foreign Passport with Temp. I-551 Stamp'] = {'U.S. Citizenship and Immigration Services':'U.S. Citizenship and Immigration Services','DOJ Immigration and Naturalization Service':'DOJ Immigration and Naturalization Service','n_a':'N/A'};
doc_part2_auth['Foreign Passport with Temp. I-551 MRIV'] = {'U.S. Department of State':'U.S. Department of State','n_a':'N/A'};
doc_part2_auth['Receipt Form I-94/I-94A w/I-551 stamp, photo'] = {'n_a':'N/A'};
doc_part2_auth['Receipt Replacement Perm. Res. Card (Form I-551)'] = {'n_a':'N/A'};
doc_part2_auth['Employment Auth. Document (Form I-766)'] = {'n_a':'N/A'};
doc_part2_auth['Receipt Replacement EAD (Form I-766)'] = {'n_a':'N/A'};
doc_part2_auth['Receipt Form I-94/I-94A w/refugee stamp (or RE class of admission)'] = {'n_a':'N/A'};
doc_part2_auth['Foreign Passport, work-authorized nonimmigrant'] = {'U.S. Citizenship and Immigration Services':'U.S. Citizenship and Immigration Services', 'U.S. Customs and Border Protection':'U.S. Customs and Border Protection','n_a':'N/A'};
doc_part2_auth['Receipt Foreign Passport, work-authorized nonimmigrant']  = {'U.S. Citizenship and Immigration Services':'U.S. Citizenship and Immigration Services', 'U.S. Customs and Border Protection':'U.S. Customs and Border Protection','n_a':'N/A'};
doc_part2_auth['FSM Passport with Form I-94'] = {'U.S. Citizenship and Immigration Services':'U.S. Citizenship and Immigration Services', 'U.S. Customs and Border Protection':'U.S. Customs and Border Protection','n_a':'N/A'};
doc_part2_auth['RMI Passport with Form I-94'] = {'U.S. Citizenship and Immigration Services':'U.S. Citizenship and Immigration Services', 'U.S. Customs and Border Protection':'U.S. Customs and Border Protection','n_a':'N/A'};
doc_part2_auth['Receipt FSM Passport with Form I-94'] = {'U.S. Citizenship and Immigration Services':'U.S. Citizenship and Immigration Services', 'U.S. Customs and Border Protection':'U.S. Customs and Border Protection','n_a':'N/A'};
doc_part2_auth['Receipt RMI Passport with Form I-94'] = {'U.S. Citizenship and Immigration Services':'U.S. Citizenship and Immigration Services', 'U.S. Customs and Border Protection':'U.S. Customs and Border Protection','n_a':'N/A'};

doc_part3_doc['n_a'] = {'n_a':'N/A'};
doc_part3_doc['U.S. Passport'] = {'n_a':'N/A'};
doc_part3_doc['U.S. Passport Card'] = {'n_a':'N/A'};
doc_part3_doc['Perm. Resident Card (Form I-551)'] = {'n_a':'N/A'};
doc_part3_doc['Alien Reg. Receipt Card (Form I-551)'] = {'n_a':'N/A'};
doc_part3_doc['Foreign Passport with Temp. I-551 Stamp'] = {'n_a':'N/A'};
doc_part3_doc['Foreign Passport with Temp. I-551 MRIV'] = {'n_a':'N/A'};
doc_part3_doc['Receipt Form I-94/I-94A w/I-551 stamp, photo'] = {'n_a':'N/A'};
doc_part3_doc['Receipt Replacement Perm. Res. Card (Form I-551)'] = {'n_a':'N/A'};
doc_part3_doc['Employment Auth. Document (Form I-766)'] = {'n_a':'N/A'};
doc_part3_doc['Receipt Replacement EAD (Form I-766)'] = {'n_a':'N/A'};
doc_part3_doc['FSM Passport with Form I-94'] = {'n_a':'N/A'};
doc_part3_doc['RMI Passport with Form I-94'] = {'n_a':'N/A'};
doc_part3_doc['Receipt Form I-94/I-94A w/refugee stamp (or RE class of admission)'] = {'n_a':'N/A'};
doc_part3_doc['Foreign Passport, work-authorized nonimmigrant'] = {'n_a':'N/A','Form I-20':'Form I-20', 'Form DS-2019':'Form DS-2019'};
doc_part3_doc['Receipt Foreign Passport, work-authorized nonimmigrant']  = {'n_a':'N/A','Form I-20':'Form I-20', 'Form DS-2019':'Form DS-2019'};
doc_part3_doc['Receipt FSM Passport with Form I-94'] = {'n_a':'N/A','Form I-20':'Form I-20', 'Form DS-2019':'Form DS-2019'};
doc_part3_doc['Receipt RMI Passport with Form I-94'] = {'n_a':'N/A','Form I-20':'Form I-20', 'Form DS-2019':'Form DS-2019'};

doc_part3_auth['n_a'] = {'n_a':'N/A'};
doc_part3_auth['Form I-20'] = {'U.S. Immigration and Customs Enforcement':'U.S. Immigration and Customs Enforcement','US DOJ INS':'US DOJ INS','n_a':'N/A'};
doc_part3_auth['Form DS-2019'] = {'U.S. Department of State':'U.S. Department of State','n_a':'N/A'};

listc_doc['citizen']            = {'n_a':'N/A','Social Security Card (Unrestricted)':'Social Security Card (Unrestricted)','Form FS-545':'Form FS-545','Form DS-1350':'Form DS-1350','Form FS-240':'Form FS-240','U.S. Birth certificate':'U.S. Birth certificate','Native American tribal document':'Native American tribal document','Form I-197':'Form I-197','Form I-179':'Form I-179','Employment auth. document (DHS)':'Employment auth. document (DHS)','Receipt Replace. Unrestricted SS Card':'Receipt Replace. Unrestricted SS Card','Receipt Replacement Birth Certificate':'Receipt Replacement Birth Certificate','Receipt Replace. Native American Tribal Doc.':'Receipt Replace. Native American Tribal Doc.','Receipt Replace. Employment Auth. Doc. (DHS)':'Receipt Replace. Employment Auth. Doc. (DHS)'};
listc_doc['noncitizen']         = {'n_a':'N/A','Social Security Card (Unrestricted)':'Social Security Card (Unrestricted)','Form FS-545':'Form FS-545','Form DS-1350':'Form DS-1350','Form FS-240':'Form FS-240','U.S. Birth certificate':'U.S. Birth certificate','Native American tribal document':'Native American tribal document','Form I-197':'Form I-197','Form I-179':'Form I-179','Employment auth. document (DHS)':'Employment auth. document (DHS)','Receipt Replace. Unrestricted SS Card':'Receipt Replace. Unrestricted SS Card','Receipt Replacement Birth Certificate':'Receipt Replacement Birth Certificate','Receipt Replace. Native American Tribal Doc.':'Receipt Replace. Native American Tribal Doc.','Receipt Replace. Employment Auth. Doc. (DHS)':'Receipt Replace. Employment Auth. Doc. (DHS)'};
listc_doc['permanent-resident'] = {'n_a':'N/A','Social Security Card (Unrestricted)':'Social Security Card (Unrestricted)','Native American tribal document':'Native American tribal document','Employment auth. document (DHS)':'Employment auth. document (DHS)','Receipt Replace. Native American Tribal Doc.':'Receipt Replace. Native American Tribal Doc.','Receipt Replace. Unrestricted SS Card':'Receipt Replace. Unrestricted SS Card','Receipt Replace. Employment Auth. Doc. (DHS)':'Receipt Replace. Employment Auth. Doc. (DHS)'};
listc_doc['alien-work']         = {'n_a':'N/A','Social Security Card (Unrestricted)':'Social Security Card (Unrestricted)','Native American tribal document':'Native American tribal document','Employment auth. document (DHS)':'Employment auth. document (DHS)','Receipt Replace. Native American Tribal Doc.':'Receipt Replace. Native American Tribal Doc.','Receipt Replace. Unrestricted SS Card':'Receipt Replace. Unrestricted SS Card','Receipt Replace. Employment Auth. Doc. (DHS)':'Receipt Replace. Employment Auth. Doc. (DHS)'};

listc_auth['n_a']    = {'n_a':'N/A'};
listc_auth['Social Security Card (Unrestricted)']    = {'Social Security Administration':'Social Security Administration','U.S. Department of Health and Human Services':'U.S. Department of Health and Human Services','Social Security Board':'Social Security Board','Department of Health, Education and Welfare':'Department of Health, Education and Welfare','n_a':'N/A'};
listc_auth['Form FS-545'] = {'U.S. Department of State':'U.S. Department of State','n_a':'N/A'};
listc_auth['Form DS-1350'] = {'U.S. Department of State':'U.S. Department of State','n_a':'N/A'};
listc_auth['Form FS-240'] = {'U.S. Department of State':'U.S. Department of State','n_a':'N/A'};
listc_auth['U.S. Birth certificate']     = {'write' :'Write here'};
listc_auth['Native American tribal document']    = {'write' :'Write here'};
listc_auth['Form I-197']     = {'DOJ Immigration and Naturalization Service' :'DOJ Immigration and Naturalization Service','n_a':'N/A'};
listc_auth['Form I-179']     = {'DOJ Immigration and Naturalization Service' :'DOJ Immigration and Naturalization Service','n_a':'N/A'};
listc_auth['Employment auth. document (DHS)'] = {'DHS' :'DHS'};
listc_auth['Receipt Replace. Unrestricted SS Card']  = {'Social Security Administration' :'Social Security Administration','n_a':'N/A'};
listc_auth['Receipt Replacement Birth Certificate']  = {'write' :'Write here'};
listc_auth['Receipt Replace. Native American Tribal Doc.']  = {'write' :'Write here'};
listc_auth['Receipt Replace. Employment Auth. Doc. (DHS)'] = {'DHS' :'DHS'};
listc_auth['Employment auth. document (DHS)'] = {'DHS' :'DHS'};

listb_doc = {
    'n_a':'N/A',"Driver's license issued by state/territory":"Driver's license issued by state/territory",'ID card issued by state/territory':"ID card issued by state/territory",'Government ID':'Government ID','School ID':'School ID','Voter registration card':'Voter registration card','U.S. Military card':'U.S. Military card','U.S. Military draft record':'U.S. Military draft record',"Military dependent's ID card":"Military dependent's ID card",'uscg':"USCG Merchant Mariner card",'Native American tribal document':"Native American tribal document",
    "Canadian driver's license":"Canadian driver's license",'School record (under age 18)':"School record (under age 18)",'Report card (under age 18)':"Report card (under age 18)",'Clinic record (under age 18)':"Clinic record (under age 18)",'Doctor record (under age 18)':"Doctor record (under age 18)",'Hospital record (under age 18)':"Hospital record (under age 18)",'Day-care record (under age 18)':"Day-care record (under age 18)",'Nursery School record (under age 18)':"Nursery School record (under age 18)",
    'Individual under Age 18':"Individual under Age 18",'Special Placement':"Special Placement","Receipt Replacement Driver's License":"Receipt Replacement Driver's License",'Receipt Replacement ID Card':"Receipt Replacement ID Card",'Receipt Replacement Government ID':"Receipt Replacement Government ID",'Receipt Replacement School ID':"Receipt Replacement School ID",'Receipt Replacement Voter Reg. Card':"Receipt Replacement Voter Reg. Card",'Receipt Replacement U.S. Military card':"Receipt Replacement U.S. Military card",
    'Receipt Replacement Military Draft Record':'Receipt Replacement Military Draft Record','Receipt Replacement Military Dep. Card':'Receipt Replacement Military Dep. Card','Receipt Replacement Merchant Mariner Card':'Receipt Replacement Merchant Mariner Card','Receipt Replacement Native American Tribal Doc':'Receipt Replacement Native American Tribal Doc','Receipt Replacement Canadian DL.':'Receipt Replacement Canadian DL.',
    'Receipt Replacement School Record (under age 18)':'Receipt Replacement School Record (under age 18)', 'Receipt Replacement Report Card (under age 18)':'Receipt Replacement Report Card (under age 18)','Receipt Replacement Clinic Record (under age 18)':'Receipt Replacement Clinic Record (under age 18)','Receipt Replacement Doctor  Record (under age 18)':'Receipt Replacement Doctor  Record (under age 18)',
    'Receipt Replace. Hospital Record (under age 18)':'Receipt Replace. Hospital Record (under age 18)','Receipt Replace. Day-Care Record (under age 18)':'Receipt Replace. Day-Care Record (under age 18)','Receipt Replace. Nursery School Record (under age 18)':'Receipt Replace. Nursery School Record (under age 18)'
};

listb_auth['n_a'] = {'n_a':'N/A'};
listb_auth["Driver's license issued by state/territory"] = countries;
listb_auth['ID card issued by state/territory'] = countries;
listb_auth['Government ID'] = {'write' :'Write here'};
listb_auth['School ID'] = {'write' :'Write here'};
listb_auth['Voter registration card'] = {'write' :'Write here'};
listb_auth['U.S. Military card'] = {'write' :'Write here'};
listb_auth['U.S. Military draft record'] = {'write' :'Write here'};
listb_auth["Military dependent's ID card"] = {'write' :'Write here'};
listb_auth['uscg'] = {'coast_guard' :'U.S. Coast Guard','n_a':'N/A'};
listb_auth['Native American tribal document'] = {'write' :'Write here'};
listb_auth["Canadian driver's license"] = canadian_states;
listb_auth['School record (under age 18)'] = {'write' :'Write here'};
listb_auth['Report card (under age 18)'] = {'write' :'Write here'};
listb_auth['Clinic record (under age 18)'] = {'write' :'Write here'};
listb_auth['Doctor record (under age 18)'] = {'write' :'Write here'};
listb_auth['Hospital record (under age 18)'] = {'write' :'Write here'};
listb_auth['Day-care record (under age 18)'] = {'write' :'Write here'};
listb_auth['Nursery School record (under age 18)'] = {'write' :'Write here'};
listb_auth['Individual under Age 18'] = {'n_a':'N/A'};
listb_auth['Special Placement'] = {'n_a':'N/A'};
listb_auth["Receipt Replacement Driver's License"] = countries;
listb_auth['Receipt Replacement ID Card'] = countries;
listb_auth['Receipt Replacement Government ID'] = {'write' :'Write here'};
listb_auth['Receipt Replacement School ID'] = {'write' :'Write here'};
listb_auth['Receipt Replacement Voter Reg. Card'] = {'write' :'Write here'};
listb_auth['Receipt Replacement U.S. Military card'] = {'write' :'Write here'};
listb_auth['Receipt Replacement Military Draft Record'] = {'write' :'Write here'};
listb_auth['Receipt Replacement Military Dep. Card'] = {'write' :'Write here'};
listb_auth['Receipt Replacement Merchant Mariner Card'] = {'coast_guard' :'U.S. Coast Guard','n_a':'N/A'};
listb_auth['Receipt Replacement Native American Tribal Doc'] = {'write' :'Write here'};
listb_auth['Receipt Replacement Canadian DL.'] = canadian_states;
listb_auth['Receipt Replacement School Record (under age 18)'] = {'write' :'Write here'};
listb_auth['Receipt Replacement Report Card (under age 18)'] = {'write' :'Write here'};
listb_auth['Receipt Replacement Clinic Record (under age 18)'] = {'write' :'Write here'};
listb_auth['Receipt Replacement Doctor  Record (under age 18)'] = {'write' :'Write here'};
listb_auth['Receipt Replace. Hospital Record (under age 18)'] = {'write' :'Write here'};
listb_auth['Receipt Replace. Day-Care Record (under age 18)'] = {'write' :'Write here'};
listb_auth['Receipt Replace. Nursery School Record (under age 18)'] = {'write' :'Write here'};


var i9_manager = (function () {

    var fetch_doc_selection  = function (option) {
        return doc_selection[option];
    }

    var fetch_auth_selection = function (option) {
        return doc_part1[option];
    }

    var fetch_part2_doc  = function (option) {
        return doc_part2_doc[option];
    }

    var fetch_part2_auth = function (option) {
        return doc_part2_auth[option];
    }

    var fetch_part3_doc  = function (option) {
        return doc_part3_doc[option];
    }

    var fetch_part3_auth = function (option) {
        return doc_part3_auth[option];
    }

    var fetch_listc_doc = function (option) {
        return listc_doc[option];
    }

    var fetch_listc_auth = function (option) {
        return listc_auth[option];
    }

    var fetch_listb_auth = function (option) {
        return listb_auth[option];
    }

    var clear_section2  = function () {
        $('#list-b-fields').find('select').val('n_a');
        $('#list-b-fields').find('input:text').val('N/A');
    }

    var clear_section1  = function () {
        $('.list-a-fields').find('select').val('n_a');
        $('.list-a-fields').find('input:text').val('N/A');
    }

    return{

        fill_part1_title: function (option) {
            var data_for_select = fetch_doc_selection(option);
            $('#section2_lista_part1_document_title').html('');
            $.each(data_for_select,function(index, item){
                $('#section2_lista_part1_document_title').append('<option value="'+index+'">'+item+'</option>')
            });
        },

        fill_part1_authority: function (option) {
            var data_for_select = fetch_auth_selection(option);
            var data_for_part2_doc = fetch_part2_doc(option);
            var data_for_part2_auth = fetch_part2_auth(option);
            var data_for_part3_doc = fetch_part3_doc(option);
            $('#section2_lista_part1_issuing_authority').html('');
            $('#section2_lista_part2_document_title').html('');
            $('#section2_lista_part2_issuing_authority').html('');
            $('#section2_lista_part3_document_title').html('');
            if(data_for_select != undefined){
                $.each(data_for_select,function(index, item){
                    $('#section2_lista_part1_issuing_authority').append('<option value="'+index+'">'+item+'</option>')
                });
            }
            if(data_for_part2_doc != undefined){
                $.each(data_for_part2_doc,function(index, item){
                    $('#section2_lista_part2_document_title').append('<option value="'+index+'">'+item+'</option>')
                });
            }
            if(data_for_part2_auth != undefined){
                $.each(data_for_part2_auth,function(index, item){
                    $('#section2_lista_part2_issuing_authority').append('<option value="'+index+'">'+item+'</option>')
                });
            }
            if(data_for_part3_doc != undefined){
                $.each(data_for_part3_doc,function(index, item){
                    $('#section2_lista_part3_document_title').append('<option value="'+index+'">'+item+'</option>')
                });
            }
            clear_section2();
        },

        fill_part3_auth: function (option) {
            var part3_auth = fetch_part3_auth(option);
            $('#section2_lista_part3_issuing_authority').html('');
            if(part3_auth != undefined){
                $.each(part3_auth,function(index, item){
                    $('#section2_lista_part3_issuing_authority').append('<option value="'+index+'">'+item+'</option>')
                });
            }
        },

        fill_listb: function(){
            $.each(listb_doc,function(index, item){
                $('#section2_listb_document_title').append('<option value="'+index+'">'+item+'</option>')
            });
        },

        fill_list_b_auth: function(option, select){
            var data_for_select = fetch_listb_auth(option);
            $('#section2_listb_issuing_authority').html('');
            $('#list_b_write').remove();
            $('.list_b_auth').show();
            $('#list_b_auth_text').hide(0);
            $('input[name="listb-auth-select-input"][value="select"]').prop('checked', true);
            $.each(data_for_select,function(index, item){
                let val = select != undefined ? select : '';
                if(index == 'write'){
                    $('#list_b_auth_select').before(`<input id="list_b_write" value="`+val+`" type="text" placeholder="Write Here" name="section2_listb_issuing_authority" id="section2_listb_issuing_authority" class="form-control">`);
                    $('.list_b_auth').hide();
                }else{
                    $('#section2_listb_issuing_authority').append('<option value="'+index+'">'+item+'</option>')
                }
            });
            clear_section1();
        },

        fill_list_c: function (option) {
            var data_for_select = fetch_listc_doc(option);
            $('#section2_listc_document_title').html('');
            $.each(data_for_select,function(index, item){
                $('#section2_listc_document_title').append('<option value="'+index+'">'+item+'</option>')
            });
        },

        fill_list_c_auth: function(option, select){
            var data_for_select = fetch_listc_auth(option);
            $('#section2_listc_issuing_authority').html('');
            $('#list_c_write').remove();
            $('.list_c_auth').show();
            $('#listc_extra_field').hide();
            
            $('#list_c_auth_text').hide(0);
            $('input[name="listc-auth-select-input"][value="select"]').prop('checked', true);

            if(option == 'Employment auth. document (DHS)' || option == 'Employment auth. document (DHS)' || option == 'Receipt Replace. Employment Auth. Doc. (DHS)'){
                $('#listc_extra_field').show();
                option == 'Receipt Replace. Employment Auth. Doc. (DHS)' ? $('#section2_listc_extra_field').val('List C #7 Receipt') : $('#section2_listc_extra_field').val('List C #7');
            }
            $.each(data_for_select,function(index, item){
                let val = select != undefined ? select : '';
                if(index == 'write'){
                    $('#list_c_auth_select').before(`<input id="list_c_write" value="`+val+`" type="text" placeholder="Write Here" name="section2_listc_issuing_authority" id="section2_listc_issuing_authority" class="form-control">`);
                    $('.list_c_auth').hide();
                }else {
                    $('#section2_listc_issuing_authority').append('<option value="' + index + '">' + item + '</option>')
                }
            });
            clear_section1();
        }
    }

})();


$(document).on('change','input[name=listb-auth-select-input]',function(){
    var value = $( 'input[name=listb-auth-select-input]:checked' ).val();
    console.log(value);
    if(value == 'input'){
        $('#list_b_auth_text').show();
        $('#list_b_auth_select').hide();
    }else{
        $('#list_b_auth_select').show();
        $('#list_b_auth_text').hide();
    }
});

$(document).on('change','input[name=listc-auth-select-input]',function(){
    var value = $( 'input[name=listc-auth-select-input]:checked' ).val();
    if(value == 'input'){
        $('#list_c_auth_text').show();
        $('#list_c_auth_select').hide();
    }else{
        $('#list_c_auth_select').show();
        $('#list_c_auth_text').hide();
    }
});
$(document).on('change','input[name=lista_part1_doc_select_input]',function(){
    var value = $( 'input[name=lista_part1_doc_select_input]:checked' ).val();
    if(value == 'input'){
        $('#lista_part1_doc_text').show();
        $('#lista_part1_doc_select').hide();
    }else{
        $('#lista_part1_doc_select').show();
        $('#lista_part1_doc_text').hide();
    }
});
$(document).on('change','input[name=lista_part2_doc_select_input]',function(){
    var value = $( 'input[name=lista_part2_doc_select_input]:checked' ).val();
    if(value == 'input'){
        $('#lista_part2_doc_text').show();
        $('#lista_part2_doc_select').hide();
    }else{
        $('#lista_part2_doc_select').show();
        $('#lista_part2_doc_text').hide();
    }
});
$(document).on('change','input[name=lista_part3_doc_select_input]',function(){
    var value = $( 'input[name=lista_part3_doc_select_input]:checked' ).val();
    if(value == 'input'){
        $('#lista_part3_doc_text').show();
        $('#lista_part3_doc_select').hide();
    }else{
        $('#lista_part3_doc_select').show();
        $('#lista_part3_doc_text').hide();
    }
});
$(document).on('change','input[name=lista_part1_issuing_select_input]',function(){
    var value = $( 'input[name=lista_part1_issuing_select_input]:checked' ).val();
    if(value == 'input'){
        $('#lista_part1_issuing_text').show();
        $('#lista_part1_issuing_select').hide();
    }else{
        $('#lista_part1_issuing_select').show();
        $('#lista_part1_issuing_text').hide();
    }
});
$(document).on('change','input[name=lista_part2_issuing_select_input]',function(){
    var value = $( 'input[name=lista_part2_issuing_select_input]:checked' ).val();
    if(value == 'input'){
        $('#lista_part2_issuing_text').show();
        $('#lista_part2_issuing_select').hide();
    }else{
        $('#lista_part2_issuing_select').show();
        $('#lista_part2_issuing_text').hide();
    }
});
$(document).on('change','input[name=lista_part3_issuing_select_input]',function(){
    var value = $( 'input[name=lista_part3_issuing_select_input]:checked' ).val();
    if(value == 'input'){
        $('#lista_part3_issuing_text').show();
        $('#lista_part3_issuing_select').hide();
    }else{
        $('#lista_part3_issuing_select').show();
        $('#lista_part3_issuing_text').hide();
    }
});
