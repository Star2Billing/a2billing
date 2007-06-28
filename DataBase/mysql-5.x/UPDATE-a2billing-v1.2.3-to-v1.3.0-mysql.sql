
ALTER TABLE cc_tariffplan ADD COLUMN calleridprefix CHAR(30) NOT NULL DEFAULT 'all';


INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (150, 'GYD', 'Guyana Dollar (GYD)', 0.00527,  'USD');



ALTER TABLE cc_charge ADD COLUMN id_cc_did BIGINT ;
ALTER TABLE cc_charge ALTER COLUMN id_cc_did SET DEFAULT 0;

ALTER TABLE cc_did ADD COLUMN reserved INT DEFAULT 0;

ALTER TABLE cc_iax_buddies ADD COLUMN id_cc_card INT DEFAULT 0 NOT NULL;
ALTER TABLE cc_sip_buddies ADD COLUMN id_cc_card INT DEFAULT 0 NOT NULL;

CREATE TABLE cc_did_use (
    id 								BIGINT NOT NULL AUTO_INCREMENT,
    id_cc_card 						BIGINT,
    id_did 							BIGINT NOT NULL,
    reservationdate 				TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    releasedate 					TIMESTAMP,
    activated 						INT DEFAULT 0,
    month_payed 					INT DEFAULT 0,
    PRIMARY KEY (id)
);

	
CREATE TABLE cc_prefix (
	id 								BIGINT NOT NULL AUTO_INCREMENT,
	id_cc_country 					BIGINT,
	prefixe 						VARCHAR(50) NOT NULL,
	destination 					VARCHAR(100) NOT NULL,
	PRIMARY KEY (id)
);
	
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Afghanistan','93','1');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Albania','355','2');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Algeria','213','3');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('American Samoa','684','4');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Andorra','376','5');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Angola','244','6');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Anguilla','1264','7');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Antarctica','672','8');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Antigua','1268',9);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Argentina','54','10');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Armenia','374','11');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Aruba','297','12');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ascension','247',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Australia','61','13');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Australian External Territories','672','13');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Austria','43','14');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Azerbaijan','994','15');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bahamas','1242','16');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bahrain','973','17');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bangladesh','880','18');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Barbados','1246','19');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Barbuda','1268',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Belarus','375','20');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Belgium','32','21');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Belize','501','22');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Benin','229','23');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bermuda','1441','24');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bhutan','975','25');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bolivia','591','26');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bosnia & Herzegovina','387','27');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Botswana','267','28');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brazil','55','30');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brasil Telecom','5514','30');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brazil Telefonica','5515','30');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brazil Embratel','5521','30');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brazil Intelig','5523','30');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brazil Telemar','5531','30');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brazil mobile phones','550','30');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('British Virgin Islands','1284','31');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brunei Darussalam','673','32');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bulgaria','359','33');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Burkina Faso','226','34');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Burundi','257','35');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cambodia','855','36');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cameroon','237','37');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Canada','1','38');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cape Verde Islands','238','39');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cayman Islands','1345','40');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Central African Republic','236','41');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Chad','235','42');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Chatham Island (New Zealand)','64',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Chile','56','43');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('China (PRC)','86','44');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Christmas Island','618','45');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cocos-Keeling Islands','61','46');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Colombia','57','47');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Colombia Mobile Phones','573','47');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Colombia Orbitel','575','47');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Colombia ETB','577','47');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Colombia Telecom','579','47');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Comoros','269','48');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Congo','242','49');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Congo, Dem. Rep. of  (former Zaire)','243',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cook Islands','682','51');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Costa Rica','506','52');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Côte d''Ivoire (Ivory Coast)','225','53');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Croatia','385','54');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cuba','53','55');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cuba (Guantanamo Bay)','5399','55');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Curaâo','599',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cyprus','357','56');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Czech Republic','420','57');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Denmark','45','58');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Diego Garcia','246','241');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Djibouti','253','59');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Dominica','1767','60');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Dominican Republic','1809','61');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('East Timor','670','211');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Easter Island','56',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ecuador','593','62');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Egypt','20','63');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('El Salvador','503','64');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ellipso (Mobile Satellite service)','8812',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('EMSAT (Mobile Satellite service)','88213',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Equatorial Guinea','240','65');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Eritrea','291','66');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Estonia','372','67');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ethiopia','251','68');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Falkland Islands (Malvinas)','500','69');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Faroe Islands','298','70');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Fiji Islands','679','71');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Finland','358','72');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('France','33','73');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('French Antilles','596','74');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('French Guiana','594','75');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('French Polynesia','689','76');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Gabonese Republic','241','77');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Gambia','220','78');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Georgia','995','79');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Germany','49','80');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ghana','233','81');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Gibraltar','350','82');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Global Mobile Satellite System (GMSS)','881',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('ICO Global','8810-8811',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ellipso','8812-8813',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Iridium','8816-8817',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Globalstar','8818-8819',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Globalstar (Mobile Satellite Service)','8818-8819',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Greece','30','83');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Greenland','299','84');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Grenada','1473','85');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Guadeloupe','590','86');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Guam','1671','87');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Guantanamo Bay','5399',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Guatemala','502','88');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Guinea-Bissau','245','90');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Guinea','224','89');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Guyana','592','91');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Haiti','509','92');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Honduras','504','95');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Hong Kong','852','96');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Hungary','36','97');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('ICO Global (Mobile Satellite Service)','8810-8811',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Iceland','354','98');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('India','91','99');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Indonesia','62','100');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Inmarsat (Atlantic Ocean - East)','871','242');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Inmarsat (Atlantic Ocean - West)','874','242');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Inmarsat (Indian Ocean)','873','242');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Inmarsat (Pacific Ocean)','872','242');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Inmarsat SNAC','870','242');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('International Freephone Service','800',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('International Shared Cost Service (ISCS)','808',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Iran','98','101');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Iraq','964','102');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ireland','353','103');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Iridium (Mobile Satellite service)','8816-8817',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Israel','972','104');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Italy','39','105');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Jamaica','1876','106');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Japan','81','107');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Jordan','962','108');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Kazakhstan','7','109');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Kenya','254','110');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Kiribati','686','111');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Korea (North)','850','112');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Korea (South)','82','113');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Kuwait','965','114');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Kyrgyz Republic','996','115');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Laos','856','116');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Latvia','371','117');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Lebanon','961','118');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Lesotho','266','119');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Liberia','231','120');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Libya','218','121');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Liechtenstein','423','122');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Lithuania','370','123');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Luxembourg','352','124');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Macao','853','125');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Macedonia (Former Yugoslav Rep of.)','389','126');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Madagascar','261','127');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Malawi','265','128');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Malaysia','60','129');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Maldives','960','130');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Mali Republic','223','131');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Malta','356','132');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Marshall Islands','692','133');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Martinique','596','134');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Mauritania','222','135');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Mauritius','230','136');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Mayotte Island','269','137');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Mexico','52','138');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Micronesia, (Federal States of)','691','139');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Midway Island','1808',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Moldova','373','140');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Monaco','377','141');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Mongolia','976','142');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Montserrat','1664','143');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Morocco','212','144');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Mozambique','258','145');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Myanmar','95','146');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Namibia','264','147');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Nauru','674','148');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Nepal','977','149');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Netherlands','31','150');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Netherlands Antilles','599','151');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Nevis','1869',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('New Caledonia','687','152');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('New Zealand','64','153');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Nicaragua','505','154');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Niger','227','155');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Nigeria','234','156');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Niue','683','157');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Norfolk Island','672','158');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Northern Marianas Islands(Saipan, Rota, & Tinian)','1670','159');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Norway','47','160');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Oman','968','161');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Pakistan','92','162');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Palau','680','163');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Palestinian Settlements','970','164');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Panama','507','165');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Papua New Guinea','675','166');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Paraguay','595','167');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Peru','51','168');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Philippines','63','169');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Poland','48','171');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Portugal','351','172');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Puerto Rico','1787','173');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Qatar','974','174');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Réunion Island','262','175');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Romania','40','176');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Russia','7','177');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Rwandese Republic','250','178');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('St. Helena','290','179');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('St. Kitts/Nevis','1869','180');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('St. Lucia','1758','181');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('St. Pierre & Miquelon','508','182');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('St. Vincent & Grenadines','1784','183');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('San Marino','378','185');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('São Tomé and Principe','239','186');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Saudi Arabia','966','187');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Senegal','221','188');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Serbia and Montenegro','381',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Seychelles Republic','248','189');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Sierra Leone','232','190');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Singapore','65','191');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Slovak Republic','421','192');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Slovenia','386','193');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Solomon Islands','677','194');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Somali Democratic Republic','252','195');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('South Africa','27','196');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Spain','34','198');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Sri Lanka','94','199');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Sudan','249','200');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Suriname','597','201');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Swaziland','268','203');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Sweden','46','204');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Switzerland','41','205');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Syria','963','206');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Taiwan','886','207');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Tajikistan','992','208');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Tanzania','255','209');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Thailand','66','210');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Thuraya (Mobile Satellite service)','88216',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Togolese Republic','228','212');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Tokelau','690','213');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Tonga Islands','676','214');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Trinidad & Tobago','1868','215');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Tunisia','216','216');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Turkey','90','217');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Turkmenistan','993','218');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Turks and Caicos Islands','1649','219');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Tuvalu','688','220');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Uganda','256','221');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ukraine','380','222');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('United Arab Emirates','971','223');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('United Kingdom','44','224');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('United States of America','1','225');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('US Virgin Islands','1340','225');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Universal Personal Telecommunications (UPT)','878',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Uruguay','598','227');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Uzbekistan','998','228');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Vanuatu','678','229');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Vatican City','39',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela','58','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela Etelix','58102','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela http://www.multiphone.net.ve','58107','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela CANTV','58110','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela Convergence Comunications','58111','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela Telcel, C.A.','58114','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela Totalcom Venezuela','58119','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela Orbitel de Venezuela, C.A. ENTEL Venezuela','58123','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela LD Telecomunicaciones, C.A.','58150','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela Telecomunicaciones NGTV','58133','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela Veninfotel Comunicaciones','58199','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Vietnam','84','231');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Wake Island','808',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Wallis and Futuna Islands','681',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Western Samoa','685','184');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Yemen','967','236');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Zambia','260','238');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Zanzibar','255',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Zimbabwe','263','239');



ALTER TABLE cc_country ADD COLUMN countryprefix VARCHAR(10) NOT NULL DEFAULT 0;

UPDATE cc_country SET countryprefix = '93' WHERE id = '1';
UPDATE cc_country SET countryprefix = '355' WHERE id = '2';
UPDATE cc_country SET countryprefix = '213' WHERE id = '3';
UPDATE cc_country SET countryprefix = '684' WHERE id = '4';
UPDATE cc_country SET countryprefix = '376' WHERE id = '5';
UPDATE cc_country SET countryprefix = '244' WHERE id = '6';
UPDATE cc_country SET countryprefix = '1264' WHERE id = '7';
UPDATE cc_country SET countryprefix = '672' WHERE id = '8';
UPDATE cc_country SET countryprefix = '54' WHERE id = '10';
UPDATE cc_country SET countryprefix = '374' WHERE id = '11';
UPDATE cc_country SET countryprefix = '297' WHERE id = '12';
UPDATE cc_country SET countryprefix = '61' WHERE id = '13';
UPDATE cc_country SET countryprefix = '43' WHERE id = '14';
UPDATE cc_country SET countryprefix = '994' WHERE id = '15';
UPDATE cc_country SET countryprefix = '1242' WHERE id = '16';
UPDATE cc_country SET countryprefix = '973' WHERE id = '17';
UPDATE cc_country SET countryprefix = '880' WHERE id = '18';
UPDATE cc_country SET countryprefix = '1246' WHERE id = '19';
UPDATE cc_country SET countryprefix = '375' WHERE id = '20';
UPDATE cc_country SET countryprefix = '32' WHERE id = '21';
UPDATE cc_country SET countryprefix = '501' WHERE id = '22';
UPDATE cc_country SET countryprefix = '229' WHERE id = '23';
UPDATE cc_country SET countryprefix = '1441' WHERE id = '24';
UPDATE cc_country SET countryprefix = '975' WHERE id = '25';
UPDATE cc_country SET countryprefix = '591' WHERE id = '26';
UPDATE cc_country SET countryprefix = '387' WHERE id = '27';
UPDATE cc_country SET countryprefix = '267' WHERE id = '28';
UPDATE cc_country SET countryprefix = '55' WHERE id = '30';
UPDATE cc_country SET countryprefix = '1284' WHERE id = '31';
UPDATE cc_country SET countryprefix = '673' WHERE id = '32';
UPDATE cc_country SET countryprefix = '359' WHERE id = '33';
UPDATE cc_country SET countryprefix = '226' WHERE id = '34';
UPDATE cc_country SET countryprefix = '257' WHERE id = '35';
UPDATE cc_country SET countryprefix = '855' WHERE id = '36';
UPDATE cc_country SET countryprefix = '237' WHERE id = '37';
UPDATE cc_country SET countryprefix = '1' WHERE id = '38';
UPDATE cc_country SET countryprefix = '238' WHERE id = '39';
UPDATE cc_country SET countryprefix = '1345' WHERE id = '40';
UPDATE cc_country SET countryprefix = '236' WHERE id = '41';
UPDATE cc_country SET countryprefix = '235' WHERE id = '42';
UPDATE cc_country SET countryprefix = '56' WHERE id = '43';
UPDATE cc_country SET countryprefix = '86' WHERE id = '44';
UPDATE cc_country SET countryprefix = '618' WHERE id = '45';
UPDATE cc_country SET countryprefix = '61' WHERE id = '46';
UPDATE cc_country SET countryprefix = '57' WHERE id = '47';
UPDATE cc_country SET countryprefix = '269' WHERE id = '48';
UPDATE cc_country SET countryprefix = '242' WHERE id = '49';
UPDATE cc_country SET countryprefix = '243' WHERE id = '50';
UPDATE cc_country SET countryprefix = '682' WHERE id = '51';
UPDATE cc_country SET countryprefix = '506' WHERE id = '52';
UPDATE cc_country SET countryprefix = '225' WHERE id = '53';
UPDATE cc_country SET countryprefix = '385' WHERE id = '54';
UPDATE cc_country SET countryprefix = '53' WHERE id = '55';
UPDATE cc_country SET countryprefix = '357' WHERE id = '56';
UPDATE cc_country SET countryprefix = '420' WHERE id = '57';
UPDATE cc_country SET countryprefix = '45' WHERE id = '58';
UPDATE cc_country SET countryprefix = '246' WHERE id = '241';
UPDATE cc_country SET countryprefix = '253' WHERE id = '59';
UPDATE cc_country SET countryprefix = '1767' WHERE id = '60';
UPDATE cc_country SET countryprefix = '1809' WHERE id = '61';
UPDATE cc_country SET countryprefix = '593' WHERE id = '62';
UPDATE cc_country SET countryprefix = '20' WHERE id = '63';
UPDATE cc_country SET countryprefix = '503' WHERE id = '64';
UPDATE cc_country SET countryprefix = '240' WHERE id = '65';
UPDATE cc_country SET countryprefix = '291' WHERE id = '66';
UPDATE cc_country SET countryprefix = '372' WHERE id = '67';
UPDATE cc_country SET countryprefix = '251' WHERE id = '68';
UPDATE cc_country SET countryprefix = '500' WHERE id = '69';
UPDATE cc_country SET countryprefix = '298' WHERE id = '70';
UPDATE cc_country SET countryprefix = '679' WHERE id = '71';
UPDATE cc_country SET countryprefix = '358' WHERE id = '72';
UPDATE cc_country SET countryprefix = '33' WHERE id = '73';
UPDATE cc_country SET countryprefix = '596' WHERE id = '74';
UPDATE cc_country SET countryprefix = '594' WHERE id = '75';
UPDATE cc_country SET countryprefix = '689' WHERE id = '76';
UPDATE cc_country SET countryprefix = '241' WHERE id = '77';
UPDATE cc_country SET countryprefix = '220' WHERE id = '78';
UPDATE cc_country SET countryprefix = '995' WHERE id = '79';
UPDATE cc_country SET countryprefix = '49' WHERE id = '80';
UPDATE cc_country SET countryprefix = '233' WHERE id = '81';
UPDATE cc_country SET countryprefix = '350' WHERE id = '82';
UPDATE cc_country SET countryprefix = '30' WHERE id = '83';
UPDATE cc_country SET countryprefix = '299' WHERE id = '84';
UPDATE cc_country SET countryprefix = '1473' WHERE id = '85';
UPDATE cc_country SET countryprefix = '590' WHERE id = '86';
UPDATE cc_country SET countryprefix = '1671' WHERE id = '87';
UPDATE cc_country SET countryprefix = '502' WHERE id = '88';
UPDATE cc_country SET countryprefix = '245' WHERE id = '90';
UPDATE cc_country SET countryprefix = '224' WHERE id = '89';
UPDATE cc_country SET countryprefix = '592' WHERE id = '91';
UPDATE cc_country SET countryprefix = '509' WHERE id = '92';
UPDATE cc_country SET countryprefix = '504' WHERE id = '95';
UPDATE cc_country SET countryprefix = '852' WHERE id = '96';
UPDATE cc_country SET countryprefix = '36' WHERE id = '97';
UPDATE cc_country SET countryprefix = '354' WHERE id = '98';
UPDATE cc_country SET countryprefix = '91' WHERE id = '99';
UPDATE cc_country SET countryprefix = '62' WHERE id = '100';
UPDATE cc_country SET countryprefix = '98' WHERE id = '101';
UPDATE cc_country SET countryprefix = '964' WHERE id = '102';
UPDATE cc_country SET countryprefix = '353' WHERE id = '103';
UPDATE cc_country SET countryprefix = '972' WHERE id = '104';
UPDATE cc_country SET countryprefix = '39' WHERE id = '105';
UPDATE cc_country SET countryprefix = '1876' WHERE id = '106';
UPDATE cc_country SET countryprefix = '81' WHERE id = '107';
UPDATE cc_country SET countryprefix = '962' WHERE id = '108';
UPDATE cc_country SET countryprefix = '7' WHERE id = '109';
UPDATE cc_country SET countryprefix = '254' WHERE id = '110';
UPDATE cc_country SET countryprefix = '686' WHERE id = '111';
UPDATE cc_country SET countryprefix = '850' WHERE id = '112';
UPDATE cc_country SET countryprefix = '82' WHERE id = '113';
UPDATE cc_country SET countryprefix = '965' WHERE id = '114';
UPDATE cc_country SET countryprefix = '996' WHERE id = '115';
UPDATE cc_country SET countryprefix = '856' WHERE id = '116';
UPDATE cc_country SET countryprefix = '371' WHERE id = '117';
UPDATE cc_country SET countryprefix = '961' WHERE id = '118';
UPDATE cc_country SET countryprefix = '266' WHERE id = '119';
UPDATE cc_country SET countryprefix = '231' WHERE id = '120';
UPDATE cc_country SET countryprefix = '218' WHERE id = '121';
UPDATE cc_country SET countryprefix = '423' WHERE id = '122';
UPDATE cc_country SET countryprefix = '370' WHERE id = '123';
UPDATE cc_country SET countryprefix = '352' WHERE id = '124';
UPDATE cc_country SET countryprefix = '853' WHERE id = '125';
UPDATE cc_country SET countryprefix = '389' WHERE id = '126';
UPDATE cc_country SET countryprefix = '261' WHERE id = '127';
UPDATE cc_country SET countryprefix = '265' WHERE id = '128';
UPDATE cc_country SET countryprefix = '60' WHERE id = '129';
UPDATE cc_country SET countryprefix = '960' WHERE id = '130';
UPDATE cc_country SET countryprefix = '223' WHERE id = '131';
UPDATE cc_country SET countryprefix = '356' WHERE id = '132';
UPDATE cc_country SET countryprefix = '692' WHERE id = '133';
UPDATE cc_country SET countryprefix = '596' WHERE id = '134';
UPDATE cc_country SET countryprefix = '222' WHERE id = '135';
UPDATE cc_country SET countryprefix = '230' WHERE id = '136';
UPDATE cc_country SET countryprefix = '269' WHERE id = '137';
UPDATE cc_country SET countryprefix = '52' WHERE id = '138';
UPDATE cc_country SET countryprefix = '691' WHERE id = '139';
UPDATE cc_country SET countryprefix = '1808' WHERE id = '140';
UPDATE cc_country SET countryprefix = '377' WHERE id = '141';
UPDATE cc_country SET countryprefix = '976' WHERE id = '142';
UPDATE cc_country SET countryprefix = '1664' WHERE id = '143';
UPDATE cc_country SET countryprefix = '212' WHERE id = '144';
UPDATE cc_country SET countryprefix = '258' WHERE id = '145';
UPDATE cc_country SET countryprefix = '95' WHERE id = '146';
UPDATE cc_country SET countryprefix = '264' WHERE id = '147';
UPDATE cc_country SET countryprefix = '674' WHERE id = '148';
UPDATE cc_country SET countryprefix = '977' WHERE id = '149';
UPDATE cc_country SET countryprefix = '31' WHERE id = '150';
UPDATE cc_country SET countryprefix = '599' WHERE id = '151';
UPDATE cc_country SET countryprefix = '687' WHERE id = '152';
UPDATE cc_country SET countryprefix = '64' WHERE id = '153';
UPDATE cc_country SET countryprefix = '505' WHERE id = '154';
UPDATE cc_country SET countryprefix = '227' WHERE id = '155';
UPDATE cc_country SET countryprefix = '234' WHERE id = '156';
UPDATE cc_country SET countryprefix = '683' WHERE id = '157';
UPDATE cc_country SET countryprefix = '672' WHERE id = '158';
UPDATE cc_country SET countryprefix = '1670' WHERE id = '159';
UPDATE cc_country SET countryprefix = '47' WHERE id = '160';
UPDATE cc_country SET countryprefix = '968' WHERE id = '161';
UPDATE cc_country SET countryprefix = '92' WHERE id = '162';
UPDATE cc_country SET countryprefix = '680' WHERE id = '163';
UPDATE cc_country SET countryprefix = '970' WHERE id = '164';
UPDATE cc_country SET countryprefix = '507' WHERE id = '165';
UPDATE cc_country SET countryprefix = '675' WHERE id = '166';
UPDATE cc_country SET countryprefix = '595' WHERE id = '167';
UPDATE cc_country SET countryprefix = '51' WHERE id = '168';
UPDATE cc_country SET countryprefix = '63' WHERE id = '169';
UPDATE cc_country SET countryprefix = '48' WHERE id = '171';
UPDATE cc_country SET countryprefix = '351' WHERE id = '172';
UPDATE cc_country SET countryprefix = '1787' WHERE id = '173';
UPDATE cc_country SET countryprefix = '974' WHERE id = '174';
UPDATE cc_country SET countryprefix = '262' WHERE id = '175';
UPDATE cc_country SET countryprefix = '40' WHERE id = '176';
UPDATE cc_country SET countryprefix = '7' WHERE id = '177';
UPDATE cc_country SET countryprefix = '250' WHERE id = '178';
UPDATE cc_country SET countryprefix = '290' WHERE id = '179';
UPDATE cc_country SET countryprefix = '1869' WHERE id = '180';
UPDATE cc_country SET countryprefix = '1758' WHERE id = '181';
UPDATE cc_country SET countryprefix = '508' WHERE id = '182';
UPDATE cc_country SET countryprefix = '1784' WHERE id = '183';
UPDATE cc_country SET countryprefix = '685' WHERE id = '184';
UPDATE cc_country SET countryprefix = '378' WHERE id = '185';
UPDATE cc_country SET countryprefix = '239' WHERE id = '186';
UPDATE cc_country SET countryprefix = '966' WHERE id = '187';
UPDATE cc_country SET countryprefix = '221' WHERE id = '188';
UPDATE cc_country SET countryprefix = '248' WHERE id = '189';
UPDATE cc_country SET countryprefix = '232' WHERE id = '190';
UPDATE cc_country SET countryprefix = '65' WHERE id = '191';
UPDATE cc_country SET countryprefix = '421' WHERE id = '192';
UPDATE cc_country SET countryprefix = '386' WHERE id = '193';
UPDATE cc_country SET countryprefix = '677' WHERE id = '194';
UPDATE cc_country SET countryprefix = '252' WHERE id = '195';
UPDATE cc_country SET countryprefix = '27' WHERE id = '196';
UPDATE cc_country SET countryprefix = '34' WHERE id = '198';
UPDATE cc_country SET countryprefix = '94' WHERE id = '199';
UPDATE cc_country SET countryprefix = '249' WHERE id = '200';
UPDATE cc_country SET countryprefix = '597' WHERE id = '201';
UPDATE cc_country SET countryprefix = '268' WHERE id = '203';
UPDATE cc_country SET countryprefix = '46' WHERE id = '204';
UPDATE cc_country SET countryprefix = '41' WHERE id = '205';
UPDATE cc_country SET countryprefix = '963' WHERE id = '206';
UPDATE cc_country SET countryprefix = '886' WHERE id = '207';
UPDATE cc_country SET countryprefix = '992' WHERE id = '208';
UPDATE cc_country SET countryprefix = '255' WHERE id = '209';
UPDATE cc_country SET countryprefix = '66' WHERE id = '210';
UPDATE cc_country SET countryprefix = '228' WHERE id = '212';
UPDATE cc_country SET countryprefix = '690' WHERE id = '213';
UPDATE cc_country SET countryprefix = '676' WHERE id = '214';
UPDATE cc_country SET countryprefix = '1868' WHERE id = '215';
UPDATE cc_country SET countryprefix = '216' WHERE id = '216';
UPDATE cc_country SET countryprefix = '90' WHERE id = '217';
UPDATE cc_country SET countryprefix = '993' WHERE id = '218';
UPDATE cc_country SET countryprefix = '1649' WHERE id = '219';
UPDATE cc_country SET countryprefix = '688' WHERE id = '220';
UPDATE cc_country SET countryprefix = '256' WHERE id = '221';
UPDATE cc_country SET countryprefix = '380' WHERE id = '222';
UPDATE cc_country SET countryprefix = '971' WHERE id = '223';
UPDATE cc_country SET countryprefix = '44' WHERE id = '224';
UPDATE cc_country SET countryprefix = '1' WHERE id = '225';
UPDATE cc_country SET countryprefix = '598' WHERE id = '227';
UPDATE cc_country SET countryprefix = '998' WHERE id = '228';
UPDATE cc_country SET countryprefix = '678' WHERE id = '229';
UPDATE cc_country SET countryprefix = '58' WHERE id = '230';
UPDATE cc_country SET countryprefix = '84' WHERE id = '231';
UPDATE cc_country SET countryprefix = '1284' WHERE id = '232';
UPDATE cc_country SET countryprefix = '808' WHERE id = '233';
UPDATE cc_country SET countryprefix = '591' WHERE id = '234';
UPDATE cc_country SET countryprefix = '967' WHERE id = '236';
UPDATE cc_country SET countryprefix = '260' WHERE id = '238';
UPDATE cc_country SET countryprefix = '263' WHERE id = '239';
UPDATE cc_country SET countryprefix = '670' WHERE id = '243';




CREATE TABLE cc_alarm (
    id 									BIGINT NOT NULL AUTO_INCREMENT,
    name text 							NOT NULL,
    periode 							INT NOT NULL DEFAULT 1,
    type 								INT NOT NULL DEFAULT 1,
    maxvalue 							FLOAT NOT NULL,
    minvalue 							FLOAT NOT NULL DEFAULT -1,
    id_trunk 							INT,
    status 								INT NOT NULL DEFAULT 0,
    numberofrun 						INT NOT NULL DEFAULT 0,
    numberofalarm 						INT NOT NULL DEFAULT 0,   
	datecreate    						TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,	
	datelastrun    						TIMESTAMP,
    emailreport 						VARCHAR(50),
    PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;


 CREATE TABLE cc_alarm_report (
    id 									BIGINT NOT NULL AUTO_INCREMENT,
    cc_alarm_id 						BIGINT NOT NULL,
    calculatedvalue 					float NOT NULL,
    daterun 							TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);





CREATE TABLE cc_callback_spool (
    id 								BIGINT NOT NULL AUTO_INCREMENT,
    uniqueid 						VARCHAR(40),
    entry_time 						TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status 							VARCHAR(80),
    server_ip 						VARCHAR(40),
    num_attempt 					INT NOT NULL DEFAULT 0,
    last_attempt_time 				TIMESTAMP,
    manager_result 					VARCHAR(60),
    agi_result 						VARCHAR(60),
    callback_time 					TIMESTAMP,
    channel 						VARCHAR(60),
    exten 							VARCHAR(60),
    context 						VARCHAR(60),
    priority 						VARCHAR(60),
    application 					VARCHAR(60),
    data 							VARCHAR(60),
    timeout 						VARCHAR(60),
    callerid 						VARCHAR(60),
    variable 						VARCHAR(100),
    account 						VARCHAR(60),
    async 							VARCHAR(60),
    actionid 						VARCHAR(60),
	id_server						INT,
	id_server_group					INT,
    PRIMARY KEY (id),
    UNIQUE cc_callback_spool_uniqueid_key (uniqueid)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;

CREATE TABLE cc_server_manager (
    id 								BIGINT NOT NULL AUTO_INCREMENT,
	id_group						INT DEFAULT 1,
    server_ip 						VARCHAR(40),
    manager_host 					VARCHAR(50),
    manager_username 				VARCHAR(50),
    manager_secret 					VARCHAR(50),
	lasttime_used		 			TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;

INSERT INTO cc_server_manager (id_group, server_ip, manager_host, manager_username, manager_secret) VALUES (1, 'localhost', 'localhost', 'myasterisk', 'mycode');


CREATE TABLE cc_server_group (
	id 								BIGINT NOT NULL AUTO_INCREMENT,
	name 							VARCHAR(60),
	description						MEDIUMTEXT,
    PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;
INSERT INTO cc_server_group (id, name, description) VALUES (1, 'default', 'default group of server');




CREATE TABLE cc_invoices (
    id 								INT NOT NULL AUTO_INCREMENT,    
    cardid 							BIGINT NOT NULL,
	orderref 						VARCHAR(50),
    invoicecreated_date 			TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	cover_startdate 				TIMESTAMP,
    cover_enddate 					TIMESTAMP,	
    amount 							DECIMAL(15,5) DEFAULT 0,
	tax 							DECIMAL(15,5) DEFAULT 0,
	total 							DECIMAL(15,5) DEFAULT 0,
	invoicetype 					INT ,
	filename 						VARCHAR(250),
    PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;
CREATE INDEX ind_cc_invoices ON cc_invoices (cover_startdate);


CREATE TABLE cc_invoice_history (
    id 								INT NOT NULL AUTO_INCREMENT,    
    invoiceid 						INT NOT NULL,	
    invoicesent_date 				TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    invoicestatus 					INT,
    PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;
CREATE INDEX ind_cc_invoice_history ON cc_invoice_history (invoicesent_date);




CREATE TABLE cc_package_offer (
    id 					BIGINT NOT NULL AUTO_INCREMENT,
    creationdate 		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    label 				VARCHAR(70) NOT NULL,
    packagetype 		INT NOT NULL,
	billingtype 		INT NOT NULL,
	startday 			INT NOT NULL,
	freetimetocall 		INT NOT NULL,
    PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;
-- packagetype : Free minute + Unlimited ; Free minute ; Unlimited ; Normal
-- billingtype : Monthly ; Weekly 
-- startday : according to billingtype ; if monthly value 1-31 ; if Weekly value 1-7 (Monday to Sunday) 


CREATE TABLE cc_card_package_offer (
    id 					BIGINT NOT NULL AUTO_INCREMENT,
	id_cc_card 			BIGINT NOT NULL,
	id_cc_package_offer BIGINT NOT NULL,
    date_consumption 	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	used_secondes 		BIGINT NOT NULL,
    PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;
CREATE INDEX ind_cc_card_package_offer_id_card 			ON cc_card_package_offer (id_cc_card);
CREATE INDEX ind_cc_card_package_offer_id_package_offer ON cc_card_package_offer (id_cc_package_offer);
CREATE INDEX ind_cc_card_package_offer_date_consumption ON cc_card_package_offer (date_consumption);


ALTER TABLE cc_tariffgroup 	ADD COLUMN id_cc_package_offer 			BIGINT NOT NULL DEFAULT 0;
ALTER TABLE cc_ratecard 	ADD COLUMN freetimetocall_package_offer INT NOT NULL DEFAULT 0;
ALTER TABLE cc_call 		ADD COLUMN id_card_package_offer 		INT DEFAULT 0;


CREATE TABLE cc_subscription_fee (
    id 									BIGINT NOT NULL AUTO_INCREMENT,
    label 								TEXT NOT NULL,
    fee 								FLOAT DEFAULT 0 NOT NULL,	
	currency 							CHAR(3) DEFAULT 'USD',
    status 								INT DEFAULT '0' NOT NULL,
    numberofrun 						INT DEFAULT '0' NOT NULL,
    datecreate 							TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datelastrun 						TIMESTAMP,
    emailreport 						TEXT,
    totalcredit 						FLOAT NOT NULL DEFAULT 0,
    totalcardperform 					INT DEFAULT '0' NOT NULL,
    PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;


ALTER TABLE cc_charge 	ADD COLUMN currency 				CHAR(3) DEFAULT 'USD';
ALTER TABLE cc_charge 	ADD COLUMN id_cc_subscription_fee 	BIGINT DEFAULT '0';

CREATE INDEX ind_cc_charge_id_cc_card				ON cc_charge (id_cc_card);
CREATE INDEX ind_cc_charge_id_cc_subscription_fee 	ON cc_charge (id_cc_subscription_fee);
CREATE INDEX ind_cc_charge_creationdate 			ON cc_charge (creationdate);


-- ## 	INSTEAD USE CC_CHARGE  ##
-- CREATE TABLE cc_subscription_fee_card (
--     id 						BIGINT NOT NULL AUTO_INCREMENT,
--     id_cc_card 				BIGINT NOT NULL,
--     id_cc_subscription_fee 	BIGINT NOT NULL,
--     datefee 				TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     fee 					FLOAT DEFAULT 0 NOT NULL,	
-- 	fee_converted 			FLOAT DEFAULT 0 NOT NULL,
-- 	currency 				CHAR(3) DEFAULT 'USD',
--     PRIMARY KEY (id)
-- )ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;
-- 
-- CREATE INDEX ind_cc_subscription_fee_card_id_cc_card  				ON cc_subscription_fee_card (id_cc_card);
-- CREATE INDEX ind_cc_subscription_fee_card_id_cc_subscription_fee 	ON cc_subscription_fee_card (id_cc_subscription_fee);
-- CREATE INDEX ind_cc_subscription_fee_card_datefee 					ON cc_subscription_fee_card (datefee);


-- Table Name: cc_outbound_cid_group
-- For outbound CID Group
-- group_name: Name of the Group Created.

CREATE TABLE cc_outbound_cid_group (
    id 					INT NOT NULL AUTO_INCREMENT,
    creationdate 		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    group_name			VARCHAR(70) NOT NULL,    
    PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;


-- Table Name: cc_outbound_cid_list
-- For outbound CIDs 
-- outbound_cid_group: Foreign Key of the CID Group
-- cid: Caller ID
-- activated Field for Activated or Disabled t=activated.

CREATE TABLE cc_outbound_cid_list (
    id 					INT NOT NULL AUTO_INCREMENT,
	outbound_cid_group	INT NOT NULL,
	cid					CHAR(100) NULL,    
    activated 			INT	NOT NULL DEFAULT 0,
    creationdate 		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,    
    PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;

-- id_outbound_cidgroup: Outbound Calls CID Group Name
ALTER TABLE cc_ratecard ADD COLUMN id_outbound_cidgroup INT NOT NULL DEFAULT -1;






INSERT INTO cc_templatemail VALUES ('payment', 'info@call-labs.com', 'Call-Labs', 'PAYMENT CONFIRMATION', 'Thank you for shopping at Call-Labs.

Shopping details is as below.

Item Name = <b>$itemName</b>
Item ID = <b>$itemID</b>
Amount = <b>$itemAmount</b>
Payment Method = <b>$paymentMethod</b>
Status = <b>$paymentStatus</b>


Kind regards,
Call Labs
', '');

INSERT INTO cc_templatemail VALUES ('invoice', 'info@call-labs.com', 'Call-Labs', 'A2BILLING INVOICE', 'Dear Customer.

Attached is the invoice.

Kind regards,
Call Labs
', '');

-- Payment Methods Table
CREATE TABLE cc_payment_methods (
    id 									INT NOT NULL AUTO_INCREMENT,
    payment_method 						CHAR(100) NOT NULL,
    payment_filename 					CHAR(200) NOT NULL,
    active 								CHAR(1) DEFAULT 'f' NOT NULL,
    PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;

Insert INTO cc_payment_methods (payment_method,payment_filename,active) VALUES ('paypal','paypal.php','t');
Insert INTO cc_payment_methods (payment_method,payment_filename,active) VALUES ('Authorize.Net','authorizenet.php','t');
Insert INTO cc_payment_methods (payment_method,payment_filename,active) VALUES ('MoneyBookers','moneybookers.php','t');


CREATE TABLE cc_payments (
  id 									INT NOT NULL AUTO_INCREMENT,
  customers_id 							VARCHAR(60) NOT NULL,
  customers_name 						VARCHAR(200) NOT NULL,
  customers_email_address 				VARCHAR(96) NOT NULL,
  item_name 							VARCHAR(127),
  item_id 								VARCHAR(127),
  item_quantity 						INT NOT NULL DEFAULT 0,
  payment_method 						VARCHAR(32) NOT NULL,
  cc_type 								VARCHAR(20),
  cc_owner 								VARCHAR(64),
  cc_number 							VARCHAR(32),
  cc_expires 							VARCHAR(4),
  orders_status 						INT (5) NOT NULL,
  orders_amount 						DECIMAL(14,6),
  last_modified 						DATETIME,
  date_purchased 						DATETIME,
  orders_date_finished 					DATETIME,
  currency 								CHAR(3),
  currency_value 						DECIMAL(14,6),
  PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;

-- Payment Status Lookup Table
CREATE TABLE cc_payments_status (
  id 									INT NOT NULL AUTO_INCREMENT,
  status_id 							INT NOT NULL,
  status_name 							VARCHAR(200) NOT NULL,
  PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;

Insert INTO cc_payments_status (status_id,status_name) VALUES (-2, 'Failed');
Insert INTO cc_payments_status (status_id,status_name) VALUES (-1, 'Denied');
Insert INTO cc_payments_status (status_id,status_name) VALUES (0, 'Pending');
Insert INTO cc_payments_status (status_id,status_name) VALUES (1, 'In-Progress');
Insert INTO cc_payments_status (status_id,status_name) VALUES (2, 'Completed');
Insert INTO cc_payments_status (status_id,status_name) VALUES (3, 'Processed');
Insert INTO cc_payments_status (status_id,status_name) VALUES (4, 'Refunded');
Insert INTO cc_payments_status (status_id,status_name) VALUES (5, 'Unknown');


CREATE TABLE cc_configuration (
  configuration_id 							INT NOT NULL AUTO_INCREMENT,
  configuration_title 						VARCHAR(64) NOT NULL,
  configuration_key 						VARCHAR(64) NOT NULL,
  configuration_value 						VARCHAR(255) NOT NULL,
  configuration_description 				VARCHAR(255) NOT NULL,
  configuration_type 						INT NOT NULL DEFAULT 0,
  use_function 								VARCHAR(255) NULL,
  set_function 								VARCHAR(255) NULL,
  PRIMARY KEY (configuration_id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;

INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) VALUES ('Login Username', 'MODULE_PAYMENT_AUTHORIZENET_LOGIN', 'testing', 'The login username used for the Authorize.net service');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) VALUES ('Transaction Key', 'MODULE_PAYMENT_AUTHORIZENET_TXNKEY', 'Test', 'Transaction Key used for encrypting TP data');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) VALUES ('Transaction Mode', 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE', 'Test', 'Transaction mode used for processing orders', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) VALUES ('Transaction Method', 'MODULE_PAYMENT_AUTHORIZENET_METHOD', 'Credit Card', 'Transaction method used for processing orders', 'tep_cfg_select_option(array(\'Credit Card\', \'eCheck\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) VALUES ('Customer Notifications', 'MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER', 'False', 'Should Authorize.Net e-mail a receipt to the customer?', 'tep_cfg_select_option(array(\'True\', \'False\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) VALUES ('Enable Authorize.net Module', 'MODULE_PAYMENT_AUTHORIZENET_STATUS', 'True', 'Do you want to accept Authorize.net payments?', 'tep_cfg_select_option(array(\'True\', \'False\'), ');

INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) VALUES ('Enable PayPal Module', 'MODULE_PAYMENT_PAYPAL_STATUS', 'True', 'Do you want to accept PayPal payments?','tep_cfg_select_option(array(\'True\', \'False\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) VALUES ('E-Mail Address', 'MODULE_PAYMENT_PAYPAL_ID', 'you@yourbusiness.com', 'The e-mail address to use for the PayPal service');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) VALUES ('Transaction Currency', 'MODULE_PAYMENT_PAYPAL_CURRENCY', 'Selected Currency', 'The currency to use for credit card transactions', 'tep_cfg_select_option(array(\'Selected Currency\',\'USD\',\'CAD\',\'EUR\',\'GBP\',\'JPY\'), ');

INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) VALUES ('E-Mail Address', 'MODULE_PAYMENT_MONEYBOOKERS_ID', 'you@yourbusiness.com', 'The eMail address to use for the moneybookers service');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) VALUES ('Referral ID', 'MODULE_PAYMENT_MONEYBOOKERS_REFID', '989999', 'Your personal Referral ID from moneybookers.com');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) VALUES ('Transaction Currency', 'MODULE_PAYMENT_MONEYBOOKERS_CURRENCY', 'Selected Currency', 'The default currency for the payment transactions', 'tep_cfg_select_option(array(\'Selected Currency\',\'EUR\', \'USD\', \'GBP\', \'HKD\', \'SGD\', \'JPY\', \'CAD\', \'AUD\', \'CHF\', \'DKK\', \'SEK\', \'NOK\', \'ILS\', \'MYR\', \'NZD\', \'TWD\', \'THB\', \'CZK\', \'HUF\', \'SKK\', \'ISK\', \'INR\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) VALUES ('Transaction Language', 'MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE', 'Selected Language', 'The default language for the payment transactions', 'tep_cfg_select_option(array(\'Selected Language\',\'EN\', \'DE\', \'ES\', \'FR\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) VALUES ('Enable moneybookers Module', 'MODULE_PAYMENT_MONEYBOOKERS_STATUS', 'True', 'Do you want to accept moneybookers payments?','tep_cfg_select_option(array(\'True\', \'False\'), ');


ALTER TABLE cc_card ADD COLUMN id_subscription_fee INT DEFAULT 0, ADD COLUMN mac_addr CHAR(17) DEFAULT '00-00-00-00-00-00' NOT NULL;

UPDATE cc_ui_authen SET perms = '32767' WHERE userid = '1';
UPDATE cc_ui_authen SET perms = '32767' WHERE userid = '2';

ALTER TABLE cc_invoices ADD COLUMN payment_date TIMESTAMP;
ALTER TABLE cc_invoices ADD COLUMN payment_status INT DEFAULT 0;

CREATE TABLE cc_epayment_log (
    id 								INT NOT NULL AUTO_INCREMENT,
    cardid 							INT DEFAULT 0 NOT NULL,
    amount 							FLOAT DEFAULT 0 NOT NULL,
	vat 							FLOAT DEFAULT 0 NOT NULL,
    paymentmethod	 				CHAR(50) NOT NULL,     
  	cc_owner 						VARCHAR(64),
  	cc_number 						VARCHAR(32),
  	cc_expires 						VARCHAR(7),						   
    creationdate  					TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status 							INT DEFAULT 0 NOT NULL,
    PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;


INSERT INTO cc_templatemail VALUES ('epaymentverify', 'info@call-labs.com', 'Call-Labs', 'Epayment Gateway Security Verification Failed', 'Dear Administrator

Please check the Epayment Log, System has logged a Epayment Security failure. that may be a possible attack on epayment processing.

Time of Transaction: $time
Payment Gateway: $paymentgateway
Amount: $amount



Kind regards,
Call Labs
', '');


CREATE TABLE cc_system_log (
    id 								INT NOT NULL AUTO_INCREMENT,
    iduser 							INT DEFAULT 0 NOT NULL,
    loglevel	 					INT DEFAULT 0 NOT NULL,
    action			 				TEXT NOT NULL,
    description						MEDIUMTEXT,    
    data			 				BLOB,
	tablename						VARCHAR(255),
	pagename			 			VARCHAR(255),
	ipaddress						VARCHAR(255),
	creationdate  					TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;



ALTER TABLE cc_iax_buddies CHANGE qualify qualify char(7);
ALTER TABLE cc_sip_buddies CHANGE qualify qualify char(7);

CREATE TABLE cc_config (
  	id 								INT NOT NULL auto_increment,
	config_title		 			VARCHAR(64) NOT NULL,
	config_key 						VARCHAR(64) NOT NULL,
	config_value 					TEXT NOT NULL,
	config_description 				TEXT NOT NULL,
	config_valuetype				INT NOT NULL DEFAULT 0,	
	config_group_id 				INT NOT NULL,
	config_listvalues				TEXT,
	PRIMARY KEY (id)
);

CREATE TABLE cc_config_group (
  	id 								INT NOT NULL auto_increment,
	group_title 					VARCHAR(64) NOT NULL,	
	group_description 				VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);

INSERT INTO cc_config_group (id, group_title, group_description) VALUES (NULL, 'global', 'This configuration group handles the global settings for application.');
INSERT INTO cc_config_group (id, group_title, group_description) VALUES (NULL, 'callback', 'This configuration group handles calllback settings.');
INSERT INTO cc_config_group (id, group_title, group_description) VALUES (NULL, 'webcustomerui', 'This configuration group handles Web Customer User Interface.');
INSERT INTO cc_config_group (id, group_title, group_description) VALUES (NULL, 'sip-iax-info', 'SIP & IAX client configuration information.');
INSERT INTO cc_config_group (id, group_title, group_description) VALUES (NULL, 'epayment_method', 'Epayment Methods Configuration.');
INSERT INTO cc_config_group (id, group_title, group_description) VALUES (NULL, 'signup', 'This configuration group handles the signup related settings.');
INSERT INTO cc_config_group (id, group_title, group_description) VALUES (NULL, 'backup', 'This configuration group handles the backup/restore related settings.');
INSERT INTO cc_config_group (id, group_title, group_description) VALUES (NULL, 'webui', 'This configuration group handles the WEBUI and API Configuration.');
INSERT INTO cc_config_group (id, group_title, group_description) VALUES (NULL, 'peer_friend', 'This configuration group handles the WEBUI and API Configuration.');
INSERT INTO cc_config_group (id, group_title, group_description) VALUES (NULL, 'log-files', 'This configuration group handles the Log Files Directory Paths.');
INSERT INTO cc_config_group (id, group_title, group_description) VALUES (NULL, 'agi-conf1', 'This configuration group handles the AGI Configuration.');



INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Card Number length', 'interval_len_cardnumber', '10-15', 'Card Number length, You can define a Range e.g:10-15.',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Card Alias Length', 'len_aliasnumber', '15', 'Card Number Alias Length e.g: 15.',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Voucher Lenght', 'len_voucher', '15', 'Voucher Number Length.',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Base Currency', 'base_currency', 'usd', 'Base Currency to use for application.',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Invoice Image', 'invoice_image', '10', 'Image to Display on the Top of Invoice',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Admin Email', 'admin_email', '10', 'Web Administrator Email Address.',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('DID Bill Payment Day', 'didbilling_daytopay', '5', 'DID Bill Payment Day of Month',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Manager Host', 'manager_host', 'localhost', 'Manager Host Address',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Manager User ID', 'manager_username', 'myastersik', 'Manger Host User Name',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Manager Password', 'manager_secret', 'mycode', 'Manager Host Password',0 , 1);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Context Callback', 'context_callback', '10', 'Contaxt to use in Callback',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Extension', 'extension', '1000', 'Extension to call while callback.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Avoid Repeat Duration', 'sec_avoid_repeate', '10', 'Number of seconds before the call-back can be re-initiated from the web page to prevent repeated and unwanted calls.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Time out', 'timeout', '20', 'if the callback doesnt succeed within the value below, then the call is deemed to have failed.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Answer on Call', 'answer_call', 'yes', 'if we want to manage the answer on the call.',1 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('No of Predictive Calls', 'nb_predictive_call', '10', 'number of calls an agent will do when the call button is clicked.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Delay for Availability', 'nb_day_wait_before_retry', '1', 'Number of days to wait before the number becomes available to call again.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('PD Contect', 'context_preditctivedialer', 'a2billing-predictivedialer', 'The context to redirect the call for the predictive dialer.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Max Time to call', 'predictivedialer_maxtime_tocall', '10', 'When a call is made we need to limit the call duration : amount in seconds.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('PD Caller ID', 'callerid', '10', 'Set the callerID for the predictive dialer and call-back.',0 , 2);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Callback CallPlan ID', 'all_callback_tariff', '1', 'ID Call Plan to use when you use the all-callback mode, check the ID in the "list Call Plan" - WebUI.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Server Group ID', 'id_server_group', '1', 'Define the group of servers that are going to be used by the callback.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Audio Intro', 'callback_audio_intro', 'prepaid-callback_intro', 'Audio intro message when the callback is initiate.',0 , 2);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Signup URL', 'signup_page_url', '', 'url of the signup page to show up on the sign in page (if empty no link will show up).',0 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Payment Method', 'paymentmethod', 'yes', 'Enable or disable the payment methods; yes for multi-payment or no for single payment method option.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Personal Info', 'personalinfo', 'yes', 'Enable or disable the page which allow customer to modify its personal information.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Payment Info', 'customerinfo', 'yes', 'Enable display of the payment interface - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('SIP/IAX Info', 'sipiaxinfo', 'yes', 'Enable display of the sip/iax info - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('CDR', 'cdr', 'yes', 'Enable the Call history - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Invoices', 'invoice', 'yes', 'Enable invoices - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Voucher Screen', 'voucher', 'yes', 'Enable the voucher screen - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Paypal', 'paypal', 'yes', 'Enable the paypal payment buttons - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Speed Dial', 'speeddial', 'yes', 'Allow Speed Dial capabilities - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('DID', 'did', 'yes', 'Enable the DID (Direct Inwards Dialling) interface - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('RateCard', 'ratecard', 'yes', 'Show the ratecards - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Simulator', 'simulator', 'yes', 'Offer simulator option on the customer interface - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('CallBack', 'callback', 'yes', 'Enable the callback option on the customer interface - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Predictive Dialer', 'predictivedialer', '10', 'Enable the predictivedialer option on the customer interface - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('WebPhone', 'webphone', 'yes', 'Let users use SIP/IAX Webphone (Options : yes/no).',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('WebPhone Server', 'webphoneserver', 'localhost', 'IP address or domain name of asterisk server that would be used by the web-phone.',0 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Caller ID', 'callerid', 'yes', 'Let the users add new callerid.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Password', 'password', 'yes', 'Let the user change the webui password.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('CallerID Limit', 'limit_callerid', '5', 'The total number of callerIDs for CLI Recognition that can be add by the customer.',0 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Error Email', 'error_email', 'root@localhost', 'Email address to send the notification and error report - new DIDs assigned will also be emailed..',0 , 3);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Trunk Name', 'sip_iax_info_trunkname', 'call-labs', 'Trunk Name to show in sip/iax info.',0 , 4);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Codecs Allowed', 'sip_iax_info_allowcodec', 'g729', 'Allowed Codec, ulaw, gsm, g729.',0 , 4);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Host', 'sip_iax_info_host', 'call-labs.com', 'Host information.',0 , 4);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('IAX Parms', 'iax_additional_parameters', 'canreinvite = no', 'IAX Additional Parameters.',0 , 4);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('SIP Parms', 'sip_additional_parameters', 'trustrpid = yes | sendrpid = yes | canreinvite = no', 'SIP Additional Parameters.',0 , 4);


INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Enable', 'enable', 'yes', 'Enable/Disable.',1 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('HTTP Server', 'http_server', 'http://www.call-labs.com', 'Set the Server Address of Website, It should be empty for productive Servers.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('HTTPS Server', 'https_server', 'https://www.call-labs.com', 'https://localhost - Enter here your Secure Server Address, should not be empty for productive servers.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Server IP/Domain', 'http_cookie_domain', '26.63.165.200', 'Enter your Domain Name or IP Address, eg, 26.63.165.200.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Secure Server IP/Domain', 'https_cookie_domain', '26.63.165.200', 'Enter your Secure server Domain Name or IP Address, eg, 26.63.165.200.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Application Path', 'http_cookie_path', '/A2BCustomer_UI/', 'Enter the Physical path of your Application on your server.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Secure Application Path', 'https_cookie_path', '/A2BCustomer_UI/', 'Enter the Physical path of your Application on your Secure Server.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Application Physical Path', 'dir_ws_http_catalog', '/A2BCustomer_UI/', 'Enter the Physical path of your Application on your server.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Secure Application Physical Path', 'dir_ws_https_catalog', '/A2BCustomer_UI/', 'Set the callerID for .',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Enable SSL', 'enable_ssl', 'yes', 'secure webserver for checkout procedure?',1 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('HTTP Domain', 'http_domain', '26.63.165.200', 'Http Address.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Directory Path', 'dir_ws_http', '/~areski/svn/a2billing/payment/A2BCustomer_UI/', 'Directory Path.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Payment Amount', 'purchase_amount', '1:2:5:10:20', 'define the different amount of purchase that would be available - 5 amount maximum (5:10:15).',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Item Name', 'item_name', 'Credit Purchase', 'Item name that would be display to the user when he will buy credit.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Currency Code', 'currency_code', 'USD', 'Currency for the Credit purchase, only one can be define here.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Paypal Payment URL', 'paypal_payment_url', 'https://www.sandbox.paypal.com/cgi-bin/webscr', 'Define here the URL of paypal gateway the payment (to test with paypal sandbox).',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Paypal Verify URL', 'paypal_verify_url', 'www.sandbox.paypal.com', 'paypal transaction verification url.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Authorize.NET Payment URL', 'authorize_payment_url', 'https://test.authorize.net/gateway/transact.dll', 'Define here the URL of Authorize gateway.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('PayPal Store Name', 'store_name', 'Asterisk2Billing', 'paypal store name to show in the paypal site when customer will go to pay.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Transaction Key', 'callerid', 'asdf1212fasd121554sd4f5s45sdf', 'Transaction Key for security of Epayment Max length of 60 Characters.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Secret Word', 'moneybookers_secretword', 'areski', 'Moneybookers secret word.',0 , 5);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Enable', 'enable_signup', 'yes', 'Enable Signup Module.',1 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Captcha Security', 'enable_captcha', 'yes', 'enable Captcha on the signup module (value : YES or NO).',1 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Credit', 'credit', '0', 'amount of credit applied to a new user.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('CallPlan ID List', 'callplan_id_list', '1,2', 'the list of id of call plans which will be shown in signup.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Card Activation', 'activated', 'no', 'Specify whether the card is created as active or pending.',1 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Access Type', 'simultaccess', '0', 'Simultaneous or non concurrent access with the card - 0 = INDIVIDUAL ACCESS or 1 = SIMULTANEOUS ACCESS.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Paid Type', 'typepaid', '0', 'PREPAID CARD  =  0 - POSTPAY CARD  =  1.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Credit Limit', 'creditlimit', '0', 'Define credit limit, which is only used for a POSTPAY card.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Run Service', 'runservice', '0', 'Authorise the recurring service to apply on this card  -  Yes 1 - No 0.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Enable Expire', 'enableexpire', '0', 'Enable the expiry of the card  -  Yes 1 - No 0.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Date Format', 'expirationdate', '', 'Expiry Date format YYYY-MM-DD HH:MM:SS. For instance 2004-12-31 00:00:00',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Expire Limit', 'expiredays', '0', 'The number of days after which the card will expire.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Create SIP', 'sip_account', 'yes', 'Create a sip account from signup ( default : yes ).',1 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Create IAX', 'iax_account', 'yes', 'Create an iax account from signup ( default : yes ).',1 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Activate Card', 'activatedbyuser', 'yes', 'active card after the new signup. if No, the Signup confirmation is needed and an email will be sent to the user with a link for activation (need to put the link into the Signup mail template).',1 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Customer Interface URL', 'urlcustomerinterface', 'http://localhost/A2BCustomer_UI/', 'url of the customer interface to display after activation.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Asterisk Reload', 'reload_asterisk_if_sipiax_created', 'no', 'Define if you want to reload Asterisk when a SIP / IAX Friend is created at signup time.',1 , 6);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Backup Path', 'backup_path', '/tmp', 'Path to store backup of database.',0 , 7);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('GZIP Path', 'gzip_exe', '/bin/gzip', 'Path for gzip.',0 , 7);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('GunZip Path', 'gunzip_exe', '/bin/gunzip', 'Path for gunzip .',0 , 7);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('MySql Dump Path', 'mysqldump', '/usr/bin/mysqldump', 'path for mysqldump.',0 , 7);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('PGSql Dump Path', 'pg_dump', '/usr/bin/pg_dump', 'path for pg_dump.',0 , 7);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('MySql Path', 'mysql', '/usr/bin/mysql', 'Path for MySql.',0 , 7);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('PSql Path', 'psql', '/usr/bin/psql', 'Path for PSql.',0 , 7);


INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('SIP File Path', 'buddy_sip_file', '/etc/asterisk/additional_a2billing_sip.conf', 'Path to store the asterisk configuration files SIP.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('IAX File Path', 'buddy_iax_file', '/etc/asterisk/additional_a2billing_iax.conf', 'Path to store the asterisk configuration files IAX.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('API Security Key', 'api_security_key', 'Ae87v56zzl34v', 'API have a security key to validate the http request, the key has to be sent after applying md5, Valid characters are [a-z,A-Z,0-9].',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Authorized IP', 'api_ip_auth', '127.0.0.1', 'API to restrict the IPs authorised to make a request, Define The the list of ips separated by '';''.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Admin Email', 'email_admin', 'root@localhost', 'Administative Email.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('MOH Directory', 'dir_store_mohmp3', '/var/lib/asterisk/mohmp3', 'MOH (Music on Hold) base directory.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('MOH Classes', 'num_musiconhold_class', '10', 'Number of MOH classes you have created in musiconhold.conf : acc_1, acc_2... acc_10 class	etc....',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Display Help', 'show_help', 'yes', 'Display the help section inside the admin interface  (YES - NO).',1 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Max File Upload Size', 'my_max_file_size_import', '1024000', 'File Upload parameters, PLEASE CHECK ALSO THE VALUE IN YOUR PHP.INI THE LIMIT IS 2MG BY DEFAULT .',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Audio Directory Path', 'dir_store_audio', '/var/lib/asterisk/sounds/a2billing', 'Not used yet, The goal is to upload files and use them in the IVR.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Max Audio File Size', 'my_max_file_size_audio', '3072000', 'upload maximum file size.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Extensions Allowed', 'file_ext_allow', 'gsm, mp3, wav', 'File type extensions permitted to be uploaded such as "gsm, mp3, wav" (separated by ,).',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Muzic Files Allowed', 'file_ext_allow_musiconhold', 'mp3', 'File type extensions permitted to be uploaded for the musiconhold such as "gsm, mp3, wav" (separate by ,).',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Link Audio', 'link_audio_file', 'no', 'Enable link on the CDR viewer to the recordings. (YES - NO).',1 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Monitor Path', 'monitor_path', '/var/spool/asterisk/monitor', 'Path to link the recorded monitor files.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Monitor Format', 'monitor_formatfile', 'gsm', 'FORMAT OF THE RECORDED MONITOR FILE.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Invoice Icon', 'show_icon_invoice', 'yes', 'Display the icon in the invoice.',1 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Show Top Frame', 'show_top_frame', 'no', 'Display the top frame (useful if you want to save space on your little tiny screen ) .',1 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Currency', 'currency_choose', 'usd, eur, cad, hkd', 'Allow the customer to chose the most appropriate currency ("all" can be used).',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Card Export Fields', 'card_export_field_list', 'creationdate, username, credit, lastname, firstname', 'Fields to export in csv format from cc_card table.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Vouvher Export Fields', 'voucher_export_field_list', 'id, voucher, credit, tag, activated, usedcardnumber, usedate, currency', 'Field to export in csv format from cc_voucher table.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Advance Mode', 'advanced_mode', 'no', 'Advanced mode - Display additional configuration options on the ratecard (progressive rates, musiconhold, ...).',1 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('SIP/IAX Delete', 'delete_fk_card', 'yes', 'Delete the SIP/IAX Friend & callerid when a card is deleted.',1 , 8);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Type', 'type', 'friend', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Allow', 'allow', 'ulaw, alaw, gsm, g729', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Context', 'context', 'a2billing', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('PD Caller ID', 'nat', 'yes', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('AMA Flag', 'amaflag', 'billing', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Qualify', 'qualify', 'yes', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Host', 'host', 'dynamic', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('DTMF Mode', 'dtmfmode', 'RFC2833', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Alarm Log File', 'cront_alarm', '/tmp/cront_a2b_alarm.log', 'To disable application logging, remove/comment the log file name aside service.',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto refill Log File', 'cront_autorefill', '/tmp/cront_a2b_autorefill.log', 'To disable application logging, remove/comment the log file name aside service.',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Bactch Process Log File', 'cront_batch_process', '/tmp/cront_a2b_batch_process.log', 'To disable application logging, remove/comment the log file name aside service .',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('DID Billing Log File', 'cront_bill_diduse', '/tmp/cront_a2b_bill_diduse.log', 'To disable application logging, remove/comment the log file name aside service .',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Subscription Fee Log File', 'cront_subscriptionfee', '/tmp/cront_a2b_subscription_fee.log', 'To disable application logging, remove/comment the log file name aside service.',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Currency Cront Log File', 'cront_currency_update', '/tmp/cront_a2b_currency_update.log', 'To disable application logging, remove/comment the log file name aside service.',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Invoice Cront Log File', 'cront_invoice', '/tmp/cront_a2b_invoice.log', 'To disable application logging, remove/comment the log file name aside service.',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Cornt Log File', 'cront_check_account', '/tmp/cront_a2b_check_account.log', 'To disable application logging, remove/comment the log file name aside service .',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Paypal Log File', 'paypal', '/tmp/a2billing_paypal.log', 'paypal log file, to log all the transaction & error.',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('EPayment Log File', 'epayment', '/tmp/a2billing_epayment.log', 'epayment log file, to log all the transaction & error .',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('ECommerce Log File', 'api_ecommerce', '/tmp/api_ecommerce_request.log', 'Log file to store the ecommerce API requests .',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Callback Log File', 'api_callback', '/tmp/api_callback_request.log', 'Log file to store the CallBack API requests.',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('AGI Log File', 'agi', '/tmp/a2billing_agi.log', 'File to log.',0 , 10);


INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Debug', 'debug', '1', 'The debug level 0=none, 1=low, 2=normal, 3=all.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Asterisk Version', 'asterisk_version', '1_2', 'Asterisk Version Information, 1_1,1_2,1_4 By Default it will take 1_2 or higher .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Answer Call', 'answer_call', 'yes', 'Manage the answer on the call.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Play Audio', 'play_audio', 'yes', 'Play audio - this will disable all stream file but not the Get Data , for wholesale ensure that the authentication works and than number_try = 1.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Say GoodBye', 'say_goodbye', 'no', 'play the goodbye message when the user has finished.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Play Language Menu', 'play_menulanguage', 'no', 'enable the menu to choose the language, press 1 for English, pulsa 2 para el español, Pressez 3 pour Français',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Force Language', 'force_language', '', 'force the use of a language, if you dont want to use it leave the option empty, Values : ES, EN, FR, etc... (according to the audio you have installed).',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Intro Prompt', 'intro_prompt', '', 'Introduction prompt : to specify an additional prompt to play at the beginning of the application .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Min Call Credit', 'min_credit_2call', '0', 'Minimum amount of credit to use the application .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Min Bill Duration', 'min_duration_2bill', '0', 'this is the minimum duration in seconds of a call in order to be billed any call with a length less than min_duration_2bill will have a 0 cost useful not to charge callers for system errors when a call was answered but it actually didn''t connect.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Not Enough Credit', 'notenoughcredit_cardnumber', 'yes', 'if user doesn''t have enough credit to call a destination, prompt him to enter another cardnumber .',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('New Caller ID', 'notenoughcredit_assign_newcardnumber_cid', 'yes', 'if notenoughcredit_cardnumber = YES  then	assign the CallerID to the new cardnumber.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Use DNID', 'use_dnid', 'no', 'if YES it will use the DNID and try to dial out, without asking for the phonenumber to call.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Not Use DNID', 'no_auth_dnid', '2400,2300', 'list the dnid on which you want to avoid the use of the previous option "use_dnid" .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Try Count', 'number_try', '3', 'number of times the user can dial different number.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Force CallPlan', 'force_callplan_id', '', 'this will force to select a specific call plan by the Rate Engine.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Say Balance After Auth', 'say_balance_after_auth', 'yes', 'Play the balance to the user after the authentication (values : yes - no).',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Say Balance After Call', 'say_balance_after_call', 'no', 'Play the balance to the user after the call (values : yes - no).',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Say Rate', 'say_rateinitial', 'no', 'Play the initial cost of the route (values : yes - no)',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Say Duration', 'say_timetocall', 'yes', 'Play the amount of time that the user can call (values : yes - no).',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto Set CLID', 'auto_setcallerid', 'yes', 'enable the setup of the callerID number before the outbound is made, by default the user callerID value will be use.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Force CLID', 'force_callerid', '', 'If auto_setcallerid is enabled, the value of force_callerid will be set as CallerID.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('CLID Sanitize', 'cid_sanitize', 'NO', 'If force_callerid is not set, then the following option ensures that CID is set to one of the card''s configured caller IDs or blank if none available.(NO - disable this feature, caller ID can be anything, CID - Caller ID must be one of the customers caller IDs, DID - Caller ID must be one of the customers DID nos, BOTH - Caller ID must be one of the above two items)',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('CLID Enable', 'cid_enable', 'NO', 'enable the callerid authentication if this option is active the CC system will check the CID of caller  .',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Ask PIN', 'cid_askpincode_ifnot_callerid', 'yes', 'if the CID does not exist, then the caller will be prompt to enter his cardnumber .',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto CLID', 'cid_auto_assign_card_to_cid', 'yes', 'if the callerID authentication is enable and the authentication fails then the user will be prompt to enter his cardnumber;this option will bound the cardnumber entered to the current callerID so that next call will be directly authenticate.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto Create Card', 'cid_auto_create_card', 'no', 'if the callerID is captured on a2billing, this option will create automatically a new card and add the callerID to it.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto Create Card Length', 'cid_auto_create_card_len', '10', 'set the length of the card that will be auto create (ie, 10).',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto Create Card Type', 'cid_auto_create_card_typepaid', 'POSTPAY', 'billing type of the new card( value : POSTPAY or PREPAY) .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto Create Card Credit', 'cid_auto_create_card_credit', '0', 'amount of credit of the new card.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto Create Card Limit', 'cid_auto_create_card_credit_limit', '1000', 'if postpay, define the credit limit for the card.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto Create Card TariffGroup', 'cid_auto_create_card_tariffgroup', '6', 'the tariffgroup to use for the new card (this is the ID that you can find on the admin web interface) .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto CLID Security', 'callerid_authentication_over_cardnumber', 'NO', 'to check callerID over the cardnumber authentication (to guard against spoofing).',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('SIP Call', 'sip_iax_friends', 'NO', 'enable the option to call sip/iax friend for free (values : YES - NO).',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('SIP Call Prefix', 'sip_iax_pstn_direct_call_prefix', '555', 'if SIP_IAX_FRIENDS is active, you can define a prefix for the dialed digits to call a pstn number .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Direct Call', 'sip_iax_pstn_direct_call', 'no', 'this will enable a prompt to enter your destination number. if number start by sip_iax_pstn_direct_call_prefix we do directly a sip iax call, if not we do a normal call.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('IVR Voucher Refill', 'ivr_voucher', 'NO', 'enable the option to refill card with voucher in IVR (values : YES - NO) .',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('IVR Voucher Prefix', 'ivr_voucher_prefix', '8', 'if ivr_voucher is active, you can define a prefix for the voucher number to refill your card, values : number - don''t forget to change prepaid-refill_card_with_voucher audio accordingly .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('IVR Low Credit', 'jump_voucher_if_min_credit', 'NO', 'When the user credit are below the minimum credit to call min_credit jump directly to the voucher IVR menu  (values: YES - NO) .',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Dail Command Parms', 'dialcommand_param', '|60|HRrL(%timeout%:61000:30000)', 'More information about the Dial : http://voip-info.org/wiki-Asterisk+cmd+dial<br>30 :  The timeout parameter is optional. If not specifed, the Dial command will wait indefinitely, exiting only when the originating channel hangs up, or all the dialed channels return a busy or error condition. Otherwise it specifies a maximum time, in seconds, that the Dial command is to wait for a channel to answer.<br>H: Allow the caller to hang up by dialing * <br>r: Generate a ringing tone for the calling party<br>R: Indicate ringing to the calling party when the called party indicates ringing, pass no audio until answered.<br>m: Provide Music on Hold to the calling party until the called channel answers.<br>L(x[:y][:z]): Limit the call to ''x'' ms, warning when ''y'' ms are left, repeated every ''z'' ms)<br>%timeout% tag is replaced by the calculated timeout according the credit & destination rate!.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('SIP/IAX Dial Command Parms', 'dialcommand_param_sipiax_friend', '|60|HL(3600000:61000:30000)', 'by default (3600000  =  1HOUR MAX CALL).',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Outbound Call', 'switchdialcommand', 'NO', 'Define the order to make the outbound call<br>YES -> SIP/dialedphonenumber@gateway_ip - NO  SIP/gateway_ip/dialedphonenumber<br>Both should work exactly the same but i experimented one case when gateway was supporting dialedphonenumber@gateway_ip, So in case of trouble, try it out.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Failover Retry Limit', 'failover_recursive_limit', '2', 'failover recursive search - define how many time we want to authorize the research of the failover trunk when a call fails (value : 0 - 20) .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Max Time', 'maxtime_tocall_negatif_free_route', '5400', 'For free calls, limit the duration: amount in seconds  .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Send Reminder', 'send_reminder', 'NO', 'Send a reminder email to the user when they are under min_credit_2call  .',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Record Call', 'record_call', 'NO', 'enable to monitor the call (to record all the conversations) value : YES - NO .',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Monitor File Format', 'monitor_formatfile', 'gsm', 'format of the recorded monitor file.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('AGI Force Currency', 'agi_force_currency', '', 'Force to play the balance to the caller in a predefined currency, to use the currency set for by the customer leave this field empty.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Currency Associated', 'currency_association', 'usd:dollars,mxn:pesos,eur:euros,all:credit', 'Define all the audio (without file extensions) that you want to play according to currency (use , to separate, ie "usd:prepaid-dollar,mxn:pesos,eur:Euro,all:credit").',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('File Enter Destination', 'file_conf_enter_destination', 'prepaid-enter-dest', 'Please enter the file name you want to play when we prompt the calling party to enter the destination number, file_conf_enter_destination = prepaid-enter-number-u-calling-1-or-011.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('File Language Menu', 'file_conf_enter_menulang', 'prepaid-menulang2', 'Please enter the file name you want to play when we prompt the calling party to choose the prefered language .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Bill Callback', 'callback_bill_1stleg_ifcall_notconnected', 'YES', 'Define if you want to bill the 1st leg on callback even if the call is not connected to the destination.',1 , 11);

CREATE TABLE cc_timezone (
    id 								INT NOT NULL AUTO_INCREMENT,
    gmtzone							VARCHAR(255),
    gmttime		 					VARCHAR(255),
	gmtoffset						BIGINT NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;

INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-12:00) International Date Line West', 'GMT-12:00', '-43200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-11:00) Midway Island,Samoa', 'GMT-11:00', '-39600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-10:00) Hawaii', 'GMT-10:00', '-36000');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-09:00) Alaska', 'GMT-09:00', '-32400');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-08:00) Pacific Time (US & Canada) Tijuana', 'GMT-08:00', '-28800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-07:00) Arizona', 'GMT+07:00', '-25200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-07:00) Chihuahua, La Paz, Mazatlan', 'GMT+07:00', '-25200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-07:00) Mountain Time(US & Canada)', 'GMT+07:00', '-25200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-06:00) Central America', 'GMT+06:00', '-21600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-06:00) Central Time (US & Canada)', 'GMT+06:00', '-21600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-06:00) Guadalajara, Mexico City, Monterrey', 'GMT+06:00', '-21600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-06:00) Saskatchewan', 'GMT+06:00', '-21600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-05:00) Bagota, LIMA,Quito', 'GMT+05:00', '-18000');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-05:00) Eastern Time (US & Canada)', 'GMT+05:00', '-18000');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-05:00) Indiana (East)', 'GMT+05:00', '-18000');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-04:00) Atlantic Time (Canada)', 'GMT+04:00', '-14400');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-04:00) Caracas, La Paz', 'GMT+13:00', '-14400');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-04:00) Santiago', 'GMT+04:00', '-14400');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-03:30) NewFoundland', 'GMT+03:30', '-12600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-03:00) Brasillia', 'GMT+03:00', '-10800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-03:00) Buenos Aires, Georgetown', 'GMT+03:00', '-10800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-03:00) Greenland', 'GMT+03:00', '-10800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-03:00) Mid-Atlantic', 'GMT+03:00', '-10800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-01:00) Azores', 'GMT+01:00', '-3600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT-01:00) Cape Verd Is.', 'GMT+01:00', '-3600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT) Casablanca, Monrovia', 'GMT+13:00', '0');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT) Greenwich Mean Time : Dublin, Edinburgh, Lisbon,  London', 'GMT', '0');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm,Vienna', 'GMT+01:00', '3600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Pragua', 'GMT+01:00', '3600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+01:00) Brussels, Copenhagen, Madrid, Paris', 'GMT+01:00', '3600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb', 'GMT+01:00', '3600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+01:00) West Central Africa', 'GMT+01:00', '3600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+02:00) Athens, Istanbul, Minsk', 'GMT+02:00', '7200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+02:00) Bucharest', 'GMT+02:00', '7200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+02:00) Cairo', 'GMT+02:00', '7200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+02:00) Harere,Pretoria', 'GMT+02:00', '7200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+02:00) Helsinki, Kyiv, Riga, Sofia,Tallinn, Vilnius', 'GMT+02:00', '7200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+02:00) Jeruasalem', 'GMT+02:00', '7200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+03:00) Baghdad', 'GMT+03:00', '10800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+03:00) Kuwait, Riyadh', 'GMT+03:00', '10800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+03:00) Moscow, St.Petersburg,Volgograd', 'GMT+03:00', '10800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+03:00) Nairobi', 'GMT+03:00', '10800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+03:30) Tehran', 'GMT+03:30', '10800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+04:00)Abu Dhabi, Muscat', 'GMT+04:00', '14400');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+04:00) Baku, Tbillisi, Yerevan', 'GMT+04:00', '14400');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+04:30) Kabul', 'GMT+04:30', '16200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+05:00) Ekaterinburg', 'GMT+05:00', '18000');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+05:00) Islamabad, Karachi,Tashkent', 'GMT+05:00', '18000');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi', 'GMT+05:30', '19800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+05:45) Kathmandu', 'GMT+05:45', '20700');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+06:00) Almaty, Novosibirsk', 'GMT+06:00', '21600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+06:00) Astana, Dhaka', 'GMT+06:00', '21600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+06:00) Sri Jayawardenepura', 'GMT+06:00', '21600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+06:30) Rangoon', 'GMT+13:00', '23400');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+07:00) Bangkok, Honoi, Jakarta', 'GMT+07:00', '25200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+07:00) Krasnoyarsk', 'GMT+07:00', '25200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+08:00) Beijiing,Chongging, Honk King, Urumqi', 'GMT+08:00', '28800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+08:00) Irkutsk, Ulaan Bataar', 'GMT+08:00', '28800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+08:00) Kaula Lampur, Singapore', 'GMT+08:00', '28800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+08:00) Perth', 'GMT+08:00', '28800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+08:00) Taipei', 'GMT+08:00', '28800');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+09:00) Osaka, Sapporo, Tokyo', 'GMT+09:00', '32400');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+09:00) Seoul', 'GMT+09:00', '32400');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+09:00) Yakutsk', 'GMT+09:00', '32400');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+09:00) Adelaide', 'GMT+09:00', '32400');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+09:30) Darwin', 'GMT+10:00', '34200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+10:00) Brisbane', 'GMT+10:00', '36000');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+10:00) Canberra, Melbourne, Sydney', 'GMT+10:00', '36000');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+10:00) Guam, Port Moresby', 'GMT+10:00', '36000');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+10:00) Hobart', 'GMT+10:00', '36000');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+10:00) Vladivostok', 'GMT+10:00', '36000');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+11:00) Magadan, Solomon Is. , New Caledonia', 'GMT+11:00', '39600');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+12:00) Auckland, Wellington', 'GMT+1200', '43200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+12:00) Fiji, Kamchatka, Marshall Is.', 'GMT+12:00', '43200');
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES (NULL, '(GMT+13:00) Nuku alofa', 'GMT+13:00', '46800');

ALTER TABLE cc_card ADD COLUMN id_timezone INT DEFAULT 0;