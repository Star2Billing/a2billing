
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
**/

\set ON_ERROR_STOP ON;

ALTER TABLE cc_tariffplan ADD COLUMN calleridprefix TEXT NOT NULL DEFAULT 'all'::text;



INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (150, 'GYD', 'Guyana Dollar (GYD)', 0.00527,  'USD');



ALTER TABLE cc_charge ADD COLUMN id_cc_did BIGINT ;
ALTER TABLE cc_charge ALTER COLUMN id_cc_did SET DEFAULT 0;

ALTER TABLE cc_did ADD COLUMN reserved INTEGER DEFAULT 0;

ALTER TABLE cc_iax_buddies ADD COLUMN id_cc_card INTEGER DEFAULT 0 NOT NULL;
ALTER TABLE cc_sip_buddies ADD COLUMN id_cc_card INTEGER DEFAULT 0 NOT NULL;

create table cc_did_use (
id 								BIGSERIAL NOT NULL ,
id_cc_card 						BIGINT,
id_did 							BIGINT NOT NULL,
reservationdate 				TIMESTAMP WITHOUT TIME ZONE NOT NULL default now(),
releasedate 					TIMESTAMP WITHOUT TIME ZONE,
activated 						INTEGER default 0,
month_payed 					INTEGER default 0
);
ALTER TABLE ONLY cc_did_use ADD CONSTRAINT cc_did_use_pkey PRIMARY KEY (id);

CREATE TABLE cc_prefix (
	id 							SERIAL NOT NULL,
	id_cc_country 				BIGINT,
	prefixe 					TEXT NOT NULL,
	destination 				TEXT NOT NULL
);
ALTER TABLE ONLY cc_prefix  ADD CONSTRAINT cc_prefix_pkey PRIMARY KEY (id);
ALTER TABLE cc_country ADD COLUMN countryprefix TEXT NOT NULL DEFAULT '0';

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
    id 							BIGSERIAL NOT NULL,
    name 						TEXT NOT NULL,
    periode 					INTEGER NOT NULL DEFAULT 1,
    type 						INTEGER NOT NULL DEFAULT 1,
    maxvalue 					numeric NOT NULL,
    minvalue 					numeric NOT NULL DEFAULT -1,
    id_trunk 					INTEGER ,
    status 						INTEGER NOT NULL DEFAULT 0,
    numberofrun 				INTEGER NOT NULL DEFAULT 0,
    numberofalarm 				INTEGER NOT NULL DEFAULT 0,
    datecreate 					TIMESTAMP without time zone DEFAULT now(),
    datelastrun 				TIMESTAMP without time zone DEFAULT now(),
    emailreport 				TEXT
);
ALTER TABLE ONLY cc_alarm
    ADD CONSTRAINT cc_alarm_pkey PRIMARY KEY (id);

CREATE TABLE cc_alarm_report (
    id 							BIGSERIAL NOT NULL,
    cc_alarm_id 				BIGINT NOT NULL,
    calculatedvalue 			numeric NOT NULL,
    daterun 					TIMESTAMP without time zone DEFAULT now()
);
ALTER TABLE ONLY cc_alarm_report
    ADD CONSTRAINT cc_alarm_report_pkey PRIMARY KEY (id);


CREATE TABLE cc_callback_spool (
    id 								BIGSERIAL NOT NULL,
    uniqueid 						TEXT ,
    entry_time 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    status 							TEXT ,
    server_ip 						TEXT ,
    num_attempt 					int NOT NULL DEFAULT 0,
    last_attempt_time 				TIMESTAMP WITHOUT TIME ZONE,
    manager_result 					TEXT ,
    agi_result 						TEXT ,
    callback_time 					TIMESTAMP WITHOUT TIME ZONE,
    channel 						TEXT ,
    exten 							TEXT ,
    context 						TEXT ,
    priority 						TEXT ,
    application 					TEXT ,
    data 							TEXT ,
    timeout 						TEXT ,
    callerid 						TEXT ,
    variable 						TEXT ,
    account 						TEXT ,
    async 							TEXT ,
    actionid 						TEXT ,
	id_server						INTEGER,
	id_server_group					INTEGER
) WITH OIDS;

ALTER TABLE ONLY cc_callback_spool
    ADD CONSTRAINT cc_callback_spool_pkey PRIMARY KEY (id);
ALTER TABLE ONLY cc_callback_spool
    ADD CONSTRAINT cc_callback_spool_uniqueid_key UNIQUE (uniqueid);


CREATE TABLE cc_server_manager (
    id 								BIGSERIAL NOT NULL,
	id_group						INTEGER DEFAULT 1,
    server_ip 						TEXT ,
    manager_host 					TEXT ,
    manager_username 				TEXT ,
    manager_secret 					TEXT ,
	lasttime_used		 			TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
) WITH OIDS;
ALTER TABLE ONLY cc_server_manager
    ADD CONSTRAINT cc_server_manager_pkey PRIMARY KEY (id);
INSERT INTO cc_server_manager (id_group, server_ip, manager_host, manager_username, manager_secret) VALUES (1, 'localhost', 'localhost', 'myasterisk', 'mycode');


CREATE TABLE cc_server_group (
	id								BIGSERIAL NOT NULL,
	name							TEXT ,
	description						TEXT
) WITH OIDS;
ALTER TABLE ONLY cc_server_group
    ADD CONSTRAINT cc_server_group_pkey PRIMARY KEY (id);
INSERT INTO cc_server_group (id, name, description) VALUES (1, 'default', 'default group of server');



CREATE TABLE cc_invoices (
    id 							BIGSERIAL NOT NULL,
    cardid 						BIGINT NOT NULL,
	orderref 					TEXT ,
    invoicecreated_date 		TIMESTAMP without time zone DEFAULT now(),
    cover_startdate 			TIMESTAMP without time zone,
	cover_enddate 				TIMESTAMP without time zone,
    amount 						numeric(15,5) DEFAULT 0,
	tax 						numeric(15,5) DEFAULT 0,
	total 						numeric(15,5) DEFAULT 0,
	invoicetype 				INTEGER ,
	filename 					TEXT
) WITH OIDS;

ALTER TABLE ONLY cc_invoices
    ADD CONSTRAINT cc_invoices_pkey PRIMARY KEY (id);
CREATE INDEX ind_cc_invoices ON cc_invoices USING btree (cover_startdate);


CREATE TABLE cc_invoice_history (
    id 							BIGSERIAL NOT NULL,
    invoiceid 					INTEGER NOT NULL,
    invoicesent_date 			TIMESTAMP without time zone DEFAULT now(),
	invoicestatus 				INTEGER
) WITH OIDS;
ALTER TABLE ONLY cc_invoice_history
    ADD CONSTRAINT cc_invoice_history_pkey PRIMARY KEY (id);
CREATE INDEX ind_cc_invoice_history ON cc_invoice_history USING btree (invoicesent_date);


CREATE TABLE cc_package_offer (
    id 							BIGSERIAL NOT NULL,
    creationdate 				TIMESTAMP without time zone DEFAULT now(),
    label 						TEXT NOT NULL,
    packagetype 				INTEGER NOT NULL,
	billingtype					INTEGER NOT NULL,
	startday 					INTEGER NOT NULL,
	freetimetocall 				INTEGER NOT NULL
);
-- packagetype : Free minute + Unlimited ; Free minute ; Unlimited ; Normal
-- billingtype : Monthly ; Weekly
-- startday : according to billingtype ; if monthly value 1-31 ; if Weekly value 1-7 (Monday to Sunday)


CREATE TABLE cc_card_package_offer (
    id 					BIGSERIAL NOT NULL,
	id_cc_card 			BIGINT NOT NULL,
	id_cc_package_offer BIGINT NOT NULL,
    date_consumption 	TIMESTAMP without time zone DEFAULT now(),
	used_secondes 		BIGINT NOT NULL
);
CREATE INDEX ind_cc_card_package_offer_id_card ON cc_card_package_offer USING btree (id_cc_card);
CREATE INDEX ind_cc_card_package_offer_id_package_offer ON cc_card_package_offer USING btree (id_cc_package_offer);
CREATE INDEX ind_cc_card_package_offer_date_consumption ON cc_card_package_offer USING btree (date_consumption);

ALTER TABLE cc_tariffgroup 	ADD COLUMN id_cc_package_offer 			BIGINT	 NOT NULL DEFAULT 0;
ALTER TABLE cc_ratecard 	ADD COLUMN freetimetocall_package_offer 	INTEGER NOT NULL DEFAULT 0;
ALTER TABLE cc_call 		ADD COLUMN id_card_package_offer 			INTEGER DEFAULT 0;



CREATE TABLE cc_subscription_fee (
    id 				BIGSERIAL NOT NULL,
    label 			TEXT NOT NULL,
	fee 			NUMERIC(12,4) NOT NULL,
	currency 		CHARACTER VARYING(3) DEFAULT 'USD'::character varying,
	status 			INTEGER NOT NULL DEFAULT 0,
    numberofrun 	INTEGER NOT NULL DEFAULT 0,
    datecreate 		TIMESTAMP(0) without time zone DEFAULT now(),
    datelastrun 	TIMESTAMP(0) without time zone DEFAULT now(),
    emailreport 	TEXT,
    totalcredit 	DOUBLE PRECISION NOT NULL DEFAULT 0,
    totalcardperform INTEGER NOT NULL DEFAULT 0
);
ALTER TABLE ONLY cc_subscription_fee
ADD CONSTRAINT cc_subscription_fee_pkey PRIMARY KEY (id);


ALTER TABLE cc_charge 	ADD COLUMN currency 				CHARACTER VARYING(3) DEFAULT 'USD'::CHARACTER VARYING;
ALTER TABLE cc_charge 	ADD COLUMN id_cc_subscription_fee 	BIGINT DEFAULT 0;


CREATE INDEX ind_cc_charge_id_cc_card				ON cc_charge USING btree (id_cc_card);
CREATE INDEX ind_cc_charge_id_cc_subscription_fee 	ON cc_charge USING btree (id_cc_subscription_fee);
CREATE INDEX ind_cc_charge_creationdate 			ON cc_charge USING btree (creationdate);


-- ## 	INSTEAD USE CC_CHARGE  ##
-- CREATE TABLE cc_subscription_fee_card (
--     id 						BIGSERIAL NOT NULL,
--     id_cc_card 				 NOT NULL,
-- 	id_cc_subscription_fee 	 NOT NULL,
--     datefee 				TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT now(),
--     fee 					NUMERIC(12,4) NOT NULL,
-- 	fee_converted			NUMERIC(12,4) NOT NULL,
-- 	currency 				CHARACTER VARYING(3) DEFAULT 'USD'::CHARACTER VARYING
-- );
-- ALTER TABLE ONLY cc_subscription_fee_card
-- ADD CONSTRAINT cc_subscription_fee_card_pkey PRIMARY KEY (id)
--
--
-- CREATE INDEX ind_cc_charge_id_cc_card 								ON cc_subscription_fee_card USING btree (id_cc_card);
-- CREATE INDEX ind_cc_subscription_fee_card_id_cc_subscription_fee 	ON cc_subscription_fee_card USING btree (id_cc_subscription_fee);
-- CREATE INDEX ind_cc_subscription_fee_card_datefee 					ON cc_subscription_fee_card USING btree (datefee);


CREATE TABLE cc_outbound_cid_group (
    id 					BIGSERIAL NOT NULL,
    creationdate 		TIMESTAMP(0) without time zone DEFAULT now(),
    group_name 			TEXT NOT NULL

);
ALTER TABLE ONLY cc_outbound_cid_group
ADD CONSTRAINT cc_outbound_cid_group_pkey PRIMARY KEY (id);


CREATE TABLE cc_outbound_cid_list (
    id 					BIGSERIAL NOT NULL,
	outbound_cid_group	BIGINT NOT NULL,
	cid					TEXT NOT NULL,
    activated 			INTEGER NOT NULL DEFAULT 0,
    creationdate 		TIMESTAMP(0) without time zone DEFAULT now()
);
ALTER TABLE ONLY cc_outbound_cid_list
ADD CONSTRAINT cc_outbound_cid_list_pkey PRIMARY KEY (id);

ALTER TABLE cc_ratecard ADD COLUMN id_outbound_cidgroup INTEGER NOT NULL DEFAULT -1;





INSERT INTO cc_templatemail VALUES ('payment', 'info@call-labs.com', 'Call-Labs', 'PAYMENT CONFIRMATION', 'Thank you for shopping at Call-Labs.

Shopping details is as below.

Item Name = <b>$itemName</b>
Item ID = <b>$itemID</b>
Amount = <b>$itemAmount</b>
Payment Method = <b>$paymentMethod</b>
Status = <b>$paymentStatus</b>


Kind regards,
YourDomain
', '');

INSERT INTO cc_templatemail VALUES ('invoice', 'info@call-labs.com', 'Call-Labs', 'A2BILLING INVOICE', 'Dear Customer.

Attached is the invoice.

Kind regards,
YourDomain
', '');

CREATE TABLE cc_payment_methods (
    id BIGSERIAL NOT NULL,
    payment_method TEXT NOT NULL,
    payment_filename TEXT NOT NULL,
    active CHARACTER VARYING(1) DEFAULT 'f' NOT NULL
);
ALTER TABLE ONLY cc_payment_methods
    ADD CONSTRAINT cc_payment_methods_pkey PRIMARY KEY (id);

INSERT INTO cc_payment_methods (payment_method,payment_filename,active) VALUES ('paypal','paypal.php','t');
INSERT INTO cc_payment_methods (payment_method,payment_filename,active) VALUES ('Authorize.Net','authorizenet.php','t');
INSERT INTO cc_payment_methods (payment_method,payment_filename,active) VALUES ('MoneyBookers','moneybookers.php','t');


CREATE TABLE cc_payments (
  id 						BIGSERIAL NOT NULL,
  customers_id 				CHARACTER VARYING(60) NOT NULL,
  customers_name 			TEXT NOT NULL,
  customers_email_address 	TEXT NOT NULL,
  item_name 				TEXT NOT NULL,
  item_id 					TEXT NOT NULL,
  item_quantity 			INTEGER NOT NULL DEFAULT 0,
  payment_method 			VARCHAR(32) NOT NULL,
  cc_type 					CHARACTER VARYING(20),
  cc_owner 					CHARACTER VARYING(64),
  cc_number 				CHARACTER VARYING(32),
  cc_expires 				CHARACTER VARYING(6),
  orders_status 			INTEGER NOT NULL,
  orders_amount 			numeric(14,6),
  last_modified 			TIMESTAMP WITHOUT TIME ZONE,
  date_purchased 			TIMESTAMP WITHOUT TIME ZONE,
  orders_date_finished 		TIMESTAMP WITHOUT TIME ZONE,
  currency 					CHARACTER VARYING(3),
  currency_value 			decimal(14,6)
);

ALTER TABLE ONLY cc_payments
    ADD CONSTRAINT cc_payments_pkey PRIMARY KEY (id);


CREATE TABLE cc_payments_status (
  id 						BIGSERIAL NOT NULL,
  status_id 				INTEGER NOT NULL,
  status_name 				CHARACTER VARYING(200) NOT NULL
);
ALTER TABLE ONLY cc_payments_status
    ADD CONSTRAINT cc_payments_status_pkey PRIMARY KEY (id);


INSERT INTO cc_payments_status (status_id,status_name) VALUES (-2, 'Failed');
INSERT INTO cc_payments_status (status_id,status_name) VALUES (-1, 'Denied');
INSERT INTO cc_payments_status (status_id,status_name) VALUES (0, 'Pending');
INSERT INTO cc_payments_status (status_id,status_name) VALUES (1, 'In-Progress');
INSERT INTO cc_payments_status (status_id,status_name) VALUES (2, 'Completed');
INSERT INTO cc_payments_status (status_id,status_name) VALUES (3, 'Processed');
INSERT INTO cc_payments_status (status_id,status_name) VALUES (4, 'Refunded');
INSERT INTO cc_payments_status (status_id,status_name) VALUES (5, 'Unknown');

CREATE TABLE cc_configuration (
  configuration_id 					BIGSERIAL NOT NULL,
  configuration_title 				CHARACTER VARYING(64) NOT NULL,
  configuration_key 				CHARACTER VARYING(64) NOT NULL,
  configuration_value 				CHARACTER VARYING(255) NOT NULL,
  configuration_description 		CHARACTER VARYING(255) NOT NULL,
  configuration_type 				INTEGER NOT NULL DEFAULT 0,
  use_function 						CHARACTER VARYING(255) NULL,
  set_function 						CHARACTER VARYING(255) NULL

);
ALTER TABLE ONLY cc_configuration
ADD CONSTRAINT cc_configuration_id_pkey PRIMARY KEY (configuration_id);


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



ALTER TABLE ONLY cc_card ADD COLUMN id_subscription_fee INTEGER DEFAULT 0, ADD COLUMN mac_addr VARCHAR(17) DEFAULT '00-00-00-00-00-00' NOT NULL;
ALTER TABLE ONLY cc_card
    ADD CONSTRAINT cons_cc_card_username UNIQUE (username);
ALTER TABLE ONLY cc_card
    ADD CONSTRAINT cons_cc_card_useralias UNIQUE (useralias);

UPDATE cc_ui_authen SET perms = '32767' WHERE userid = '1';
UPDATE cc_ui_authen SET perms = '32767' WHERE userid = '2';
ALTER TABLE cc_ui_authen ALTER COLUMN datecreation TYPE TIMESTAMP WITHOUT TIME ZONE,
    ALTER COLUMN datecreation SET DEFAULT now();
ALTER TABLE cc_invoices ADD COLUMN payment_date TIMESTAMP WITHOUT TIME ZONE;
ALTER TABLE cc_invoices ADD COLUMN payment_status INTEGER DEFAULT 0;
CREATE TABLE cc_epayment_log (
    id 				BIGSERIAL NOT NULL,
    cardid 			INTEGER NOT NULL DEFAULT 0,
	amount 			DOUBLE PRECISION NOT NULL DEFAULT 0,
	vat 			DOUBLE PRECISION NOT NULL DEFAULT 0,
	paymentmethod	CHARACTER VARYING(255) NOT NULL,
    cc_owner 		CHARACTER VARYING(255) NOT NULL,
    cc_number 		CHARACTER VARYING(255) NOT NULL,
    cc_expires 		CHARACTER VARYING(255) NOT NULL,
    creationdate 	TIMESTAMP(0) without time zone DEFAULT NOW(),
    status 			INTEGER NOT NULL DEFAULT 0
);
ALTER TABLE ONLY cc_epayment_log
ADD CONSTRAINT cc_epayment_log_pkey PRIMARY KEY (id);

INSERT INTO cc_templatemail VALUES ('epaymentverify', 'info@call-labs.com', 'Call-Labs', 'Epayment Gateway Security Verification Failed', 'Dear Administrator

Please check the Epayment Log, System has logged a Epayment Security failure. that may be a possible attack on epayment processing.

Time of Transaction: $time
Payment Gateway: $paymentgateway
Amount: $amount



Kind regards,
YourDomain
', '');

CREATE TABLE cc_system_log (
    id 								BIGSERIAL NOT NULL,
    iduser 							INTEGER NOT NULL DEFAULT 0,
    loglevel	 					INTEGER NOT NULL DEFAULT 0,
    action			 				TEXT NOT NULL,
    description						TEXT,
    data			 				TEXT,
	tablename						CHARACTER VARYING(255),
	pagename			 			CHARACTER VARYING(255),
	ipaddress						CHARACTER VARYING(255),
	creationdate  					TIMESTAMP(0) without time zone DEFAULT NOW()
);
ALTER TABLE ONLY cc_system_log
ADD CONSTRAINT cc_system_log_pkey PRIMARY KEY (id);


ALTER TABLE cc_iax_buddies ALTER COLUMN qualify TYPE CHARACTER VARYING(7);
ALTER TABLE cc_sip_buddies ALTER COLUMN qualify TYPE CHARACTER VARYING(7);
