/*
 Navicat Premium Data Transfer

 Source Server         : INN
 Source Server Type    : MySQL
 Source Server Version : 100113
 Source Host           : 192.168.2.4:3306
 Source Schema         : ecommerce_android_app

 Target Server Type    : MySQL
 Target Server Version : 100113
 File Encoding         : 65001

 Date: 20/10/2020 15:24:34
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tbl_admin
-- ----------------------------
DROP TABLE IF EXISTS `tbl_admin`;
CREATE TABLE `tbl_admin`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `Telefono` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `password` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_role` enum('100','101','102') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Activo` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_admin
-- ----------------------------
INSERT INTO `tbl_admin` VALUES (1, 'admin', NULL, NULL, '48be5998bcb2860054f8628846081c91bd6d8dc09b4171da407a92b8d9b88893', 'endscom@gmail.com', 'Administrator', '100', 'S');
INSERT INTO `tbl_admin` VALUES (2, 'f10', 'ADELA MAYERLY SERRATO', '82449100', '83159f93dd189bebe488f23408fdec10f1c850613cc1f7230e0527b7cd9d46c0', 'F10@gmail.com', 'Administrator', '100', 'S');

-- ----------------------------
-- Table structure for tbl_category
-- ----------------------------
DROP TABLE IF EXISTS `tbl_category`;
CREATE TABLE `tbl_category`  (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `category_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`category_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_category
-- ----------------------------
INSERT INTO `tbl_category` VALUES (20, 'MEDICINA', '4934-2019-10-08.png');

-- ----------------------------
-- Table structure for tbl_comment
-- ----------------------------
DROP TABLE IF EXISTS `tbl_comment`;
CREATE TABLE `tbl_comment`  (
  `id_coment` int(100) NOT NULL AUTO_INCREMENT,
  `orden_code` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `orden_comment` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date_coment` datetime(0) NULL DEFAULT NULL,
  `player_id` varbinary(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id_coment`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_comment
-- ----------------------------
INSERT INTO `tbl_comment` VALUES (1, 'F05-EJ6FMESQS', '<p>xq no existia articulo</p>\r\n', '2020-09-30 05:54:51', 0x61646D696E);
INSERT INTO `tbl_comment` VALUES (2, 'F05-GIS3ZDVVQ', '<p>pedido debe llegar a las 10:00</p>\r\n', '2020-10-05 03:06:10', 0x61646D696E);

-- ----------------------------
-- Table structure for tbl_config
-- ----------------------------
DROP TABLE IF EXISTS `tbl_config`;
CREATE TABLE `tbl_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_symbol` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `currency_id` int(3) NOT NULL,
  `tax` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `app_fcm_key` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `package_name` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'com.app.ecommerce',
  `onesignal_app_id` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `onesignal_rest_api_key` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `providers` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'onesignal',
  `protocol_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'http://',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_config
-- ----------------------------
INSERT INTO `tbl_config` VALUES (1, '$', 105, '0', 'AAAAi0xpGmM:APA91bHRbQ9Aek7n5htSlUDhTnd7EWFWwxqZdB-uEfBlJJhR1MF3MC6Jgx2unzqd3PH3tVbFwlINxJ766FLtOrVOtKT9e-6Cl5cUVazAWOZnxnhwYBbnZytrEGnxhHdbRVUuXnQT_LWe', 'com.app.ecommerce', '936fcc79-79d0-4f34-8af6-391a6c6e539f', 'MjFjYmU1YTEtYzg4ZS00ZTE4LWI4YzUtMzI3NGUwYmRjNjQw', 'onesignal', 'http://');

-- ----------------------------
-- Table structure for tbl_currency
-- ----------------------------
DROP TABLE IF EXISTS `tbl_currency`;
CREATE TABLE `tbl_currency`  (
  `currency_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `currency_code` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `currency_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`currency_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 165 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_currency
-- ----------------------------
INSERT INTO `tbl_currency` VALUES (1, 'AFA', 'Afghanistan afghani');
INSERT INTO `tbl_currency` VALUES (2, 'ALL', 'Albanian lek');
INSERT INTO `tbl_currency` VALUES (3, 'DZD', 'Algerian dinar');
INSERT INTO `tbl_currency` VALUES (4, 'AOR', 'Angolan kwanza reajustado');
INSERT INTO `tbl_currency` VALUES (5, 'ARS', 'Argentine peso');
INSERT INTO `tbl_currency` VALUES (6, 'AMD', 'Armenian dram');
INSERT INTO `tbl_currency` VALUES (7, 'AWG', 'Aruban guilder');
INSERT INTO `tbl_currency` VALUES (8, 'AUD', 'Australian dollar');
INSERT INTO `tbl_currency` VALUES (9, 'AZN', 'Azerbaijanian new manat');
INSERT INTO `tbl_currency` VALUES (10, 'BSD', 'Bahamian dollar');
INSERT INTO `tbl_currency` VALUES (11, 'BHD', 'Bahraini dinar');
INSERT INTO `tbl_currency` VALUES (12, 'BDT', 'Bangladeshi taka');
INSERT INTO `tbl_currency` VALUES (13, 'BBD', 'Barbados dollar');
INSERT INTO `tbl_currency` VALUES (14, 'BYN', 'Belarusian ruble');
INSERT INTO `tbl_currency` VALUES (15, 'BZD', 'Belize dollar');
INSERT INTO `tbl_currency` VALUES (16, 'BMD', 'Bermudian dollar');
INSERT INTO `tbl_currency` VALUES (17, 'BTN', 'Bhutan ngultrum');
INSERT INTO `tbl_currency` VALUES (18, 'BOB', 'Bolivian boliviano');
INSERT INTO `tbl_currency` VALUES (19, 'BWP', 'Botswana pula');
INSERT INTO `tbl_currency` VALUES (20, 'BRL', 'Brazilian real');
INSERT INTO `tbl_currency` VALUES (21, 'GBP', 'British pound');
INSERT INTO `tbl_currency` VALUES (22, 'BND', 'Brunei dollar');
INSERT INTO `tbl_currency` VALUES (23, 'BGN', 'Bulgarian lev');
INSERT INTO `tbl_currency` VALUES (24, 'BIF', 'Burundi franc');
INSERT INTO `tbl_currency` VALUES (25, 'KHR', 'Cambodian riel');
INSERT INTO `tbl_currency` VALUES (26, 'CAD', 'Canadian dollar');
INSERT INTO `tbl_currency` VALUES (27, 'CVE', 'Cape Verde escudo');
INSERT INTO `tbl_currency` VALUES (28, 'KYD', 'Cayman Islands dollar');
INSERT INTO `tbl_currency` VALUES (29, 'XOF', 'CFA franc BCEAO');
INSERT INTO `tbl_currency` VALUES (30, 'XAF', 'CFA franc BEAC');
INSERT INTO `tbl_currency` VALUES (31, 'XPF', 'CFP franc');
INSERT INTO `tbl_currency` VALUES (32, 'CLP', 'Chilean peso');
INSERT INTO `tbl_currency` VALUES (33, 'CNY', 'Chinese yuan renminbi');
INSERT INTO `tbl_currency` VALUES (34, 'COP', 'Colombian peso');
INSERT INTO `tbl_currency` VALUES (35, 'KMF', 'Comoros franc');
INSERT INTO `tbl_currency` VALUES (36, 'CDF', 'Congolese franc');
INSERT INTO `tbl_currency` VALUES (37, 'CRC', 'Costa Rican colon');
INSERT INTO `tbl_currency` VALUES (38, 'HRK', 'Croatian kuna');
INSERT INTO `tbl_currency` VALUES (39, 'CUP', 'Cuban peso');
INSERT INTO `tbl_currency` VALUES (40, 'CZK', 'Czech koruna');
INSERT INTO `tbl_currency` VALUES (41, 'DKK', 'Danish krone');
INSERT INTO `tbl_currency` VALUES (42, 'DJF', 'Djibouti franc');
INSERT INTO `tbl_currency` VALUES (43, 'DOP', 'Dominican peso');
INSERT INTO `tbl_currency` VALUES (44, 'XCD', 'East Caribbean dollar');
INSERT INTO `tbl_currency` VALUES (45, 'EGP', 'Egyptian pound');
INSERT INTO `tbl_currency` VALUES (46, 'SVC', 'El Salvador colon');
INSERT INTO `tbl_currency` VALUES (47, 'ERN', 'Eritrean nakfa');
INSERT INTO `tbl_currency` VALUES (48, 'EEK', 'Estonian kroon');
INSERT INTO `tbl_currency` VALUES (49, 'ETB', 'Ethiopian birr');
INSERT INTO `tbl_currency` VALUES (50, 'EUR', 'EU euro');
INSERT INTO `tbl_currency` VALUES (51, 'FKP', 'Falkland Islands pound');
INSERT INTO `tbl_currency` VALUES (52, 'FJD', 'Fiji dollar');
INSERT INTO `tbl_currency` VALUES (53, 'GMD', 'Gambian dalasi');
INSERT INTO `tbl_currency` VALUES (54, 'GEL', 'Georgian lari');
INSERT INTO `tbl_currency` VALUES (55, 'GHS', 'Ghanaian new cedi');
INSERT INTO `tbl_currency` VALUES (56, 'GIP', 'Gibraltar pound');
INSERT INTO `tbl_currency` VALUES (57, 'XAU', 'Gold (ounce)');
INSERT INTO `tbl_currency` VALUES (58, 'XFO', 'Gold franc');
INSERT INTO `tbl_currency` VALUES (59, 'GTQ', 'Guatemalan quetzal');
INSERT INTO `tbl_currency` VALUES (60, 'GNF', 'Guinean franc');
INSERT INTO `tbl_currency` VALUES (61, 'GYD', 'Guyana dollar');
INSERT INTO `tbl_currency` VALUES (62, 'HTG', 'Haitian gourde');
INSERT INTO `tbl_currency` VALUES (63, 'HNL', 'Honduran lempira');
INSERT INTO `tbl_currency` VALUES (64, 'HKD', 'Hong Kong SAR dollar');
INSERT INTO `tbl_currency` VALUES (65, 'HUF', 'Hungarian forint');
INSERT INTO `tbl_currency` VALUES (66, 'ISK', 'Icelandic krona');
INSERT INTO `tbl_currency` VALUES (67, 'XDR', 'IMF special drawing right');
INSERT INTO `tbl_currency` VALUES (68, 'INR', 'Indian rupee');
INSERT INTO `tbl_currency` VALUES (69, 'IDR', 'Indonesian rupiah');
INSERT INTO `tbl_currency` VALUES (70, 'IRR', 'Iranian rial');
INSERT INTO `tbl_currency` VALUES (71, 'IQD', 'Iraqi dinar');
INSERT INTO `tbl_currency` VALUES (72, 'ILS', 'Israeli new shekel');
INSERT INTO `tbl_currency` VALUES (73, 'JMD', 'Jamaican dollar');
INSERT INTO `tbl_currency` VALUES (74, 'JPY', 'Japanese yen');
INSERT INTO `tbl_currency` VALUES (75, 'JOD', 'Jordanian dinar');
INSERT INTO `tbl_currency` VALUES (76, 'KZT', 'Kazakh tenge');
INSERT INTO `tbl_currency` VALUES (77, 'KES', 'Kenyan shilling');
INSERT INTO `tbl_currency` VALUES (78, 'KWD', 'Kuwaiti dinar');
INSERT INTO `tbl_currency` VALUES (79, 'KGS', 'Kyrgyz som');
INSERT INTO `tbl_currency` VALUES (80, 'LAK', 'Lao kip');
INSERT INTO `tbl_currency` VALUES (81, 'LVL', 'Latvian lats');
INSERT INTO `tbl_currency` VALUES (82, 'LBP', 'Lebanese pound');
INSERT INTO `tbl_currency` VALUES (83, 'LSL', 'Lesotho loti');
INSERT INTO `tbl_currency` VALUES (84, 'LRD', 'Liberian dollar');
INSERT INTO `tbl_currency` VALUES (85, 'LYD', 'Libyan dinar');
INSERT INTO `tbl_currency` VALUES (86, 'LTL', 'Lithuanian litas');
INSERT INTO `tbl_currency` VALUES (87, 'MOP', 'Macao SAR pataca');
INSERT INTO `tbl_currency` VALUES (88, 'MKD', 'Macedonian denar');
INSERT INTO `tbl_currency` VALUES (89, 'MGA', 'Malagasy ariary');
INSERT INTO `tbl_currency` VALUES (90, 'MWK', 'Malawi kwacha');
INSERT INTO `tbl_currency` VALUES (91, 'MYR', 'Malaysian ringgit');
INSERT INTO `tbl_currency` VALUES (92, 'MVR', 'Maldivian rufiyaa');
INSERT INTO `tbl_currency` VALUES (93, 'MRO', 'Mauritanian ouguiya');
INSERT INTO `tbl_currency` VALUES (94, 'MUR', 'Mauritius rupee');
INSERT INTO `tbl_currency` VALUES (95, 'MXN', 'Mexican peso');
INSERT INTO `tbl_currency` VALUES (96, 'MDL', 'Moldovan leu');
INSERT INTO `tbl_currency` VALUES (97, 'MNT', 'Mongolian tugrik');
INSERT INTO `tbl_currency` VALUES (98, 'MAD', 'Moroccan dirham');
INSERT INTO `tbl_currency` VALUES (99, 'MZN', 'Mozambique new metical');
INSERT INTO `tbl_currency` VALUES (100, 'MMK', 'Myanmar kyat');
INSERT INTO `tbl_currency` VALUES (101, 'NAD', 'Namibian dollar');
INSERT INTO `tbl_currency` VALUES (102, 'NPR', 'Nepalese rupee');
INSERT INTO `tbl_currency` VALUES (103, 'ANG', 'Netherlands Antillian guilder');
INSERT INTO `tbl_currency` VALUES (104, 'NZD', 'New Zealand dollar');
INSERT INTO `tbl_currency` VALUES (105, 'NIO', 'Nicaraguan cordoba oro');
INSERT INTO `tbl_currency` VALUES (106, 'NGN', 'Nigerian naira');
INSERT INTO `tbl_currency` VALUES (107, 'KPW', 'North Korean won');
INSERT INTO `tbl_currency` VALUES (108, 'NOK', 'Norwegian krone');
INSERT INTO `tbl_currency` VALUES (109, 'OMR', 'Omani rial');
INSERT INTO `tbl_currency` VALUES (110, 'PKR', 'Pakistani rupee');
INSERT INTO `tbl_currency` VALUES (111, 'XPD', 'Palladium (ounce)');
INSERT INTO `tbl_currency` VALUES (112, 'PAB', 'Panamanian balboa');
INSERT INTO `tbl_currency` VALUES (113, 'PGK', 'Papua New Guinea kina');
INSERT INTO `tbl_currency` VALUES (114, 'PYG', 'Paraguayan guarani');
INSERT INTO `tbl_currency` VALUES (115, 'PEN', 'Peruvian nuevo sol');
INSERT INTO `tbl_currency` VALUES (116, 'PHP', 'Philippine peso');
INSERT INTO `tbl_currency` VALUES (117, 'XPT', 'Platinum (ounce)');
INSERT INTO `tbl_currency` VALUES (118, 'PLN', 'Polish zloty');
INSERT INTO `tbl_currency` VALUES (119, 'QAR', 'Qatari rial');
INSERT INTO `tbl_currency` VALUES (120, 'RON', 'Romanian new leu');
INSERT INTO `tbl_currency` VALUES (121, 'RUB', 'Russian ruble');
INSERT INTO `tbl_currency` VALUES (122, 'RWF', 'Rwandan franc');
INSERT INTO `tbl_currency` VALUES (123, 'SHP', 'Saint Helena pound');
INSERT INTO `tbl_currency` VALUES (124, 'WST', 'Samoan tala');
INSERT INTO `tbl_currency` VALUES (125, 'STD', 'Sao Tome and Principe dobra');
INSERT INTO `tbl_currency` VALUES (126, 'SAR', 'Saudi riyal');
INSERT INTO `tbl_currency` VALUES (127, 'RSD', 'Serbian dinar');
INSERT INTO `tbl_currency` VALUES (128, 'SCR', 'Seychelles rupee');
INSERT INTO `tbl_currency` VALUES (129, 'SLL', 'Sierra Leone leone');
INSERT INTO `tbl_currency` VALUES (130, 'XAG', 'Silver (ounce)');
INSERT INTO `tbl_currency` VALUES (131, 'SGD', 'Singapore dollar');
INSERT INTO `tbl_currency` VALUES (132, 'SBD', 'Solomon Islands dollar');
INSERT INTO `tbl_currency` VALUES (133, 'SOS', 'Somali shilling');
INSERT INTO `tbl_currency` VALUES (134, 'ZAR', 'South African rand');
INSERT INTO `tbl_currency` VALUES (135, 'KRW', 'South Korean won');
INSERT INTO `tbl_currency` VALUES (136, 'LKR', 'Sri Lanka rupee');
INSERT INTO `tbl_currency` VALUES (137, 'SDG', 'Sudanese pound');
INSERT INTO `tbl_currency` VALUES (138, 'SRD', 'Suriname dollar');
INSERT INTO `tbl_currency` VALUES (139, 'SZL', 'Swaziland lilangeni');
INSERT INTO `tbl_currency` VALUES (140, 'SEK', 'Swedish krona');
INSERT INTO `tbl_currency` VALUES (141, 'CHF', 'Swiss franc');
INSERT INTO `tbl_currency` VALUES (142, 'SYP', 'Syrian pound');
INSERT INTO `tbl_currency` VALUES (143, 'TWD', 'Taiwan New dollar');
INSERT INTO `tbl_currency` VALUES (144, 'TJS', 'Tajik somoni');
INSERT INTO `tbl_currency` VALUES (145, 'TZS', 'Tanzanian shilling');
INSERT INTO `tbl_currency` VALUES (146, 'THB', 'Thai baht');
INSERT INTO `tbl_currency` VALUES (147, 'TOP', 'Tongan paanga');
INSERT INTO `tbl_currency` VALUES (148, 'TTD', 'Trinidad and Tobago dollar');
INSERT INTO `tbl_currency` VALUES (149, 'TND', 'Tunisian dinar');
INSERT INTO `tbl_currency` VALUES (150, 'TRY', 'Turkish lira');
INSERT INTO `tbl_currency` VALUES (151, 'TMT', 'Turkmen new manat');
INSERT INTO `tbl_currency` VALUES (152, 'AED', 'UAE dirham');
INSERT INTO `tbl_currency` VALUES (153, 'UGX', 'Uganda new shilling');
INSERT INTO `tbl_currency` VALUES (154, 'XFU', 'UIC franc');
INSERT INTO `tbl_currency` VALUES (155, 'UAH', 'Ukrainian hryvnia');
INSERT INTO `tbl_currency` VALUES (156, 'UYU', 'Uruguayan peso uruguayo');
INSERT INTO `tbl_currency` VALUES (157, 'USD', 'US dollar');
INSERT INTO `tbl_currency` VALUES (158, 'UZS', 'Uzbekistani sum');
INSERT INTO `tbl_currency` VALUES (159, 'VUV', 'Vanuatu vatu');
INSERT INTO `tbl_currency` VALUES (160, 'VEF', 'Venezuelan bolivar fuerte');
INSERT INTO `tbl_currency` VALUES (161, 'VND', 'Vietnamese dong');
INSERT INTO `tbl_currency` VALUES (162, 'YER', 'Yemeni rial');
INSERT INTO `tbl_currency` VALUES (163, 'ZMK', 'Zambian kwacha');
INSERT INTO `tbl_currency` VALUES (164, 'ZWL', 'Zimbabwe dollar');

-- ----------------------------
-- Table structure for tbl_fcm_template
-- ----------------------------
DROP TABLE IF EXISTS `tbl_fcm_template`;
CREATE TABLE `tbl_fcm_template`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Notification',
  `link` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_fcm_template
-- ----------------------------
INSERT INTO `tbl_fcm_template` VALUES (30, 'Contenido de la notificaciones', '8842-2019-11-14.jpg', 'Titulo de la noticaciones', '');

-- ----------------------------
-- Table structure for tbl_fcm_token
-- ----------------------------
DROP TABLE IF EXISTS `tbl_fcm_token`;
CREATE TABLE `tbl_fcm_token`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_unique_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_version` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `os_version` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `device_model` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `device_manufacturer` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_fcm_token
-- ----------------------------
INSERT INTO `tbl_fcm_token` VALUES (1, 'e7VLLnt7uQU:APA91bHjFHxF0aTvKIGm1rXU2RCfWOarsEkcEWBfUI9_8JnHmalgrAP0ECZutOaFlfVJBGdGuZ5j9y8p9F-21lcKSAOw3pf-sUtiBLQRYExW6lJxTNIyMILU233Pa-1qkTAy3Mt-3bUp', '87b66d0f1dc0b0ce', '6 (3.3.0)', 'Nougat 7.1.1', 'Android SDK built for x86', 'Google', '2019-01-31 03:54:28');

-- ----------------------------
-- Table structure for tbl_help
-- ----------------------------
DROP TABLE IF EXISTS `tbl_help`;
CREATE TABLE `tbl_help`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_help
-- ----------------------------
INSERT INTO `tbl_help` VALUES (1, 'Contact us', '<p>Do you still feel confused by the system that you need? Do not worry, please contact us now! Gladly we will help resolve your needs.</p>\r\n\r\n<p>121 King Street, Melbourne<br />\r\nVictoria 3000 Australia</p>\r\n\r\n<p>Email : help.solodroid@gmail.com</p>\r\n');
INSERT INTO `tbl_help` VALUES (2, 'Profile', '<p>E-Commerce / Online Shop App is a mobile commerce system which run under Android platform that used for promotion and selling your product with single application. With powerful Admin Panel can manage the order, create category and product menu, also can configurations such as currency, tax, user, ect from admin panel on the web. you can add, update, update or change that product menu, category, tax, currency and change admin password with generate password, etc. This application created by Android for client side and then PHP MySQL for Admin side. Run under Android platform which is the most popular operating system in the world. Using this application you can save your money and time in creating application for ecommerce or online shop application.</p>\r\n');
INSERT INTO `tbl_help` VALUES (3, 'Shipping', '<p>For shipping, we use a number of shipping services (couriers) JNE, TIKI, and Pos Indonesia.</p>\r\n\r\n<p>Product prices do not include shipping costs, shipping costs depend on the weight and volume of goods, destination address and courier used.</p>');
INSERT INTO `tbl_help` VALUES (4, 'Payment', '<p>Solomerce will send information on the number of total expenditures and postage to your email address, for details please check your email!</p>\r\n\r\n        <p>Payment can be made by transfer to :</p>\r\n\r\n        <p><b>PayPal</b></p>\r\n        <ul>\r\n            <li>E-Mail : dimas.yanpradipta@gmail.com</li>\r\n        </ul>\r\n\r\n        <p><b>BANK BCA</b></p>\r\n        <ul>\r\n            <li>Account Number : 12345678</li>\r\n            <li>Account Name : Solodroid Developer</li>\r\n        </ul>\r\n\r\n        <p><b>BANK MANDIRI</b></p>\r\n        <ul>\r\n            <li>Account Number : 12345678</li>\r\n            <li>Account Name : Solodroid Developer</li>\r\n        </ul>\r\n\r\n        <p><b>BANK BRI</b></p>\r\n        <ul>\r\n            <li>Account Number : 12345678</li>\r\n            <li>Account Name : Solodroid Developer</li>\r\n        </ul>\r\n\r\n        <p><b>BANK BNI</b></p>\r\n        <ul>\r\n            <li>Account Number : 12345678</li>\r\n            <li>Account Name : Solodroid Developer</li>\r\n        </ul>\r\n\r\n        <p><b>Please note :</b></p>\r\n        <ul>\r\n            <li>Transfer between accounts BCA, Mandiri, BNI and BRI, is a way of sending money the fastest we have\r\n                received and to be confirmed soon</li>\r\n            <li>Cash deposits, usually can be received on the same day</li>\r\n            <li>Transfers between banks, may be received on the same day, it could be 1-2 days after the transfer\r\n                is made, especially if done on weekends (Friday)</li>\r\n        </ul>\r\n\r\n        <p>We will give you confirmation once the money goes into the account.</p>\r\n\r\n        <p><b>Pay in Place (CoD - Cash on Delivery) :</b></p>\r\n        <ul>\r\n            <li>Currently, a special Jakarta, Depok, Bekasi and Bogor City City could pay on the spot - CoD.</li>\r\n            <li>Long time sent according to a schedule, or meet according to the agreement.</li>\r\n            <li>Payment is given to the Courier, a number of Memorandum.</li>\r\n            <li>Minimal expenditure of $10 USD</li>\r\n        </ul>');
INSERT INTO `tbl_help` VALUES (5, 'Como Ordenar', '<p><strong>How To Shop At Solomerce Apps :</strong></p>\r\n\r\n<ul>\r\n	<li>Shopping through the shopping cart, select the items that will be purchased in accordance with your wishes.</li>\r\n	<li>Continue by filling the form e-mail with details of the total price.</li>\r\n	<li>after you place an order, we will immediately check the conditions, the availability (and prices if there are changes in the price), as well as the shipping of the product that you yourselves message, therefore DO NOT send / transfer money before there is confirmation from us via phone / SMS or email.</li>\r\n	<li>Upon confirmation from us, please send / transfer payment to one of the following bank account</li>\r\n	<li>We only accept cash payments by wire transfer to a bank account.</li>\r\n</ul>\r\n');

-- ----------------------------
-- Table structure for tbl_order
-- ----------------------------
DROP TABLE IF EXISTS `tbl_order`;
CREATE TABLE `tbl_order`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code_vendedor` int(100) NULL DEFAULT NULL,
  `name_vendedor` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `code_cliente` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `address` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `shipping` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date_time` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
  `created_at` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `updated_at` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `order_list` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `order_total` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `comment` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `player_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_order
-- ----------------------------
INSERT INTO `tbl_order` VALUES (45, 'F05-EJ6FMESQS', NULL, NULL, NULL, 'F05', '00062', 'FARMACIA DANCING - RUC  2012705470004L', 'DETALLE: SEMAFOROS DEL DANCING 1C AL SUR, MANAGUA', 'str_shipping', '2020-09-30 09:53:49', NULL, NULL, '5 [10118022] - ANASTROZOL 1 MG TAB RECUBIERTA 28/CAJA (NAPROD) 5+1 5625.45 NIO,\n\n\nOrden : 5625.45 NIO\nImpuesto : 0.0 % : 0.0 NIO\nTotal : 5625.45 NIO', '5625.45 NIO', '', '0', '1b654686-f67c-490b-ace5-631d729e26f9');
INSERT INTO `tbl_order` VALUES (46, 'F05-BMBA1Z35W', NULL, NULL, NULL, 'F05', '00050', 'FARMACIA CORPUS CHRISTI #2', 'DETALLE: KM 8 CARRETERA NORTE ½C ABAJO, MANAGUA', 'str_shipping', '2020-09-30 11:42:53', NULL, NULL, '1 [10118101] - CISPLATINO 1MG/ML SOLUCION INY I.V 10 ML/VIAL 1/CAJA (NAPROD) 0 137.21 NIO,\n\n\nOrden : 137.21 NIO\nImpuesto : 0.0 % : 0.0 NIO\nTotal : 137.21 NIO', '137.21 NIO', '', '0', '1b654686-f67c-490b-ace5-631d729e26f9');
INSERT INTO `tbl_order` VALUES (47, 'F05-R261O5FO2', NULL, NULL, NULL, 'F05', '00050', 'FARMACIA CORPUS CHRISTI #2', 'DETALLE: KM 8 CARRETERA NORTE ½C ABAJO, MANAGUA', 'str_shipping', '2020-09-30 12:05:04', '2020-09-30 12:04:57', '2020-09-30 20:09:58', '1 [10118072] - CARBOPLATINO 10MG/ML SOLUCION INY I.V 15 ML/FAM 1/CAJA  (NAPROD) 0 686.03 NIO,\n\n\nOrden : 686.03 NIO\nImpuesto : 0.0 % : 0.0 NIO\nTotal : 686.03 NIO', '686.03 NIO', '', '2', '1b654686-f67c-490b-ace5-631d729e26f9');
INSERT INTO `tbl_order` VALUES (48, 'F05-GIS3ZDVVQ', NULL, NULL, NULL, 'F05', '01788', 'FARMACIA SANTA ANA - RUC 0010401750004G', 'DETALLE: REPARTO SHICK II ETAPA ESCUELA ADVENTISTA ½C ABAJO, MANAGUA', 'str_shipping', '2020-10-05 15:04:48', '2020-10-05 15:04:25', '2020-10-05 15:07:03', '10 [17303011] - SALBUTAMOL 100 MCG/DOSIS SUSPENSION PARA INHALACION FRASCO 1/CAJA  (HEILONGJIANG) 10+4 795.6 NIO,\n\n25 [17303041] - BROMURO DE IPRATROPIO 20 MCG/DOSIS SUSPENSION PARA INHALACION 200 DOSIS/FRASCO 1/CAJA  (HEILONGJIANG) 25+8 5021.25 NIO,\n\n10 [10415012] - ENOXAPARINA 40 MG /0.4 ML SOL. INY JERINGA 1/CAJA (GLAND PHARMA) 10+1 1667.1 NIO,\n\n\nOrden : 7483.950000000001 NIO\nImpuesto : 0.0 % : 0.0 NIO\nTotal : 7483.950000000001 NIO', '7483.950000000001 NIO', '', '1', '1b654686-f67c-490b-ace5-631d729e26f9');

-- ----------------------------
-- Table structure for tbl_product
-- ----------------------------
DROP TABLE IF EXISTS `tbl_product`;
CREATE TABLE `tbl_product`  (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_sku` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `product_price` double NOT NULL,
  `product_status` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_image` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_description` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 138 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_product
-- ----------------------------
INSERT INTO `tbl_product` VALUES (122, 'ALENTIN® (Memantina) 10mg Tabletas Recubiertas 30/Caja (GUMA PHARMA)', '11717081', 343.08, 'Available', '1570551245_ALENTIN-2-300x154.png', '', 98, 20);
INSERT INTO `tbl_product` VALUES (123, 'ALERNON® (Desloratadina) 5mg Tabletas Recubiertas 100/Caja (GUMA PHARMA)', '11704011', 1384.62, 'Available', '1570551349_MONTAJE-ALERNON-300x154.png', '', 509, 20);
INSERT INTO `tbl_product` VALUES (124, 'BACTELID® (Linezolid) 600 mg Tabletas Recubiertas 10/Caja (GUMA PHARMA)', '11705011', 9230, 'Available', '1570551400_MONTAJE-BACTELID-300x154.png', '', 5, 20);
INSERT INTO `tbl_product` VALUES (125, 'GUMAZOL® (Esomeprazol) 40mg Capsulas de Liberacion Retardada 30/Caja (GUMA PHARMA)', '11713041', 923.08, 'Available', '1570551492_MONTAJE-GUMAZOL-300x154.png', '', 35, 20);
INSERT INTO `tbl_product` VALUES (126, 'LEPIREL® (Levosulpirida) 25mg Tabletas 30/Caja (GUMA PHARMA)', '11713031', 473.08, 'Available', '1570551545_MONTAJE-LEPIREL-300x154.png', '', 3030, 20);
INSERT INTO `tbl_product` VALUES (127, 'GABAPENTINA 300 MG TABLETA 30/CAJA (INTERMED)', '10506032', 100, 'Available', '1599517691__6083376.JPG', '<h2>Paragraphs</h2>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</p>\r\n\r\n<p>Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. Aenean dignissim pellentesque felis.</p>\r\n\r\n<p>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.</p>\r\n\r\n<p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas auguae, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</p>\r\n\r\n<p>Phasellus ultrices nulla quis nibh. Quisque a lectus. Donec consectetuer ligula vulputate sem tristique cursus. Nam nulla quam, gravida non, commodo a, sodales sit amet, nisi.</p>\r\n', 100, 20);
INSERT INTO `tbl_product` VALUES (128, 'INMUNOFORCE (METISOPRINOL) 500 MG CAPSULAS 20/FRASCO 1/CAJA (QUIMIFAR)', '13223041', 100, 'Available', '1599521092__6083061.JPG', '<h2>Paragraphs</h2>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</p>\r\n\r\n<p>Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. Aenean dignissim pellentesque felis.</p>\r\n\r\n<p>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.</p>\r\n\r\n<p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas auguae, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</p>\r\n\r\n<p>Phasellus ultrices nulla quis nibh. Quisque a lectus. Donec consectetuer ligula vulputate sem tristique cursus. Nam nulla quam, gravida non, commodo a, sodales sit amet, nisi.</p>\r\n\r\n<h2>Lists</h2>\r\n\r\n<ul>\r\n	<li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>\r\n	<li>Aliquam tincidunt mauris eu risus.</li>\r\n	<li>Vestibulum auctor dapibus neque.</li>\r\n	<li>Nunc dignissim risus id metus.</li>\r\n	<li>Cras ornare tristique elit.</li>\r\n	<li>Vivamus vestibulum ntulla nec ante.</li>\r\n	<li>Praesent placerat risus quis eros.</li>\r\n	<li>Fusce pellentesque suscipit nibh.</li>\r\n	<li>Integer vitae libero ac risus egestas placerat.</li>\r\n	<li>Vestibulum commodo felis quis tortor.</li>\r\n	<li>Ut aliquam sollicitudin leo.</li>\r\n	<li>Cras iaculis ultricies nulla.</li>\r\n	<li>Donec quis dui at dolor tempor interdum.</li>\r\n</ul>\r\n', 100, 20);
INSERT INTO `tbl_product` VALUES (129, 'ZINFEC (AZITROMICINA 500 MG) TABLETAS RECUBIERTAS 100/CAJA (UNIMARK-INDIA)', '18805014', 100, 'Available', '1599521304__6095220.JPG', '<h2>Paragraphs</h2>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</p>\r\n\r\n<p>Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. Aenean dignissim pellentesque felis.</p>\r\n\r\n<p>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.</p>\r\n\r\n<p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas auguae, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</p>\r\n\r\n<p>Phasellus ultrices nulla quis nibh. Quisque a lectus. Donec consectetuer ligula vulputate sem tristique cursus. Nam nulla quam, gravida non, commodo a, sodales sit amet, nisi.</p>\r\n', 100, 20);
INSERT INTO `tbl_product` VALUES (130, 'LECARBI (LEVODOPA 250 MG + CARBIDOPA 25 MG TABLETAS RECUBIERTAS 30/CAJA (UNIMARK-INDIA)', '18824014', 100, 'Available', '1599859505__6095673.png', '<h2>Paragraphs</h2>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti.</p>\r\n\r\n<p>Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.</p>\r\n\r\n<p>Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.</p>\r\n\r\n<p>Suspendisse mauris. Fusce accumsan mollis eros. Pellentesque a diam sit amet mi ullamcorper vehicula. Integer adipiscing risus a sem. Nullam quis massa sit amet nibh viverra malesuada. Nunc sem lacus, accumsan quis, faucibus non, congue vel, arcu. Ut scelerisque hendrerit tellus. Integer sagittis. Vivamus a mauris eget arcu gravida tristique. Nunc iaculis mi in ante. Vivamus imperdiet nibh feugiat est.</p>\r\n\r\n<p>Ut convallis, sem sit amet interdum consectetuer, odio augue aliquam leo, nec dapibus tortor nibh sed augue. Integer eu magna sit amet metus fermentum posuere. Morbi sit amet nulla sed dolor elementum imperdiet. Quisque fermentum. Cum sociis natoque penatibus et magnis xdis parturient montes, nascetur ridiculus mus. Pellentesque adipiscing eros ut libero. Ut condimentum mi vel tellus. Suspendisse laoreet. Fusce ut est sed dolor gravida convallis. Morbi vitae ante. Vivamus ultrices luctus nunc. Suspendisse et dolor. Etiam dignissim. Proin malesuada adipiscing lacus. Donec metus. Curabitur gravida</p>\r\n\r\n<h2>Lists</h2>\r\n\r\n<ul>\r\n	<li>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat</li>\r\n	<li>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</li>\r\n	<li>Phasellus ultrices nulla quis nibh. Quisque a lectus. Donec consectetuer ligula vulputate sem tristique cursus. Nam nulla quam, gravida non, commodo a, sodales sit amet, nisi.</li>\r\n	<li>Pellentesque fermentum dolor. Aliquam quam lectus, facilisis auctor, ultrices ut, elementum vulputate, nunc.</li>\r\n	<li>Sed adipiscing ornare risus. Morbi est est, blandit sit amet, sagittis vel, euismod vel, velit. Pellentesque egestas sem. Suspendisse commodo ullamcorper magna.</li>\r\n	<li>Nulla sed leo. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</li>\r\n	<li>Fusce lacinia arcu et nulla. Nulla vitae mauris non felis mollis faucibus.</li>\r\n	<li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</li>\r\n	<li>Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. Aenean dignissim pellentesque felis.</li>\r\n	<li>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.</li>\r\n</ul>\r\n', 100, 20);
INSERT INTO `tbl_product` VALUES (131, 'anastrozol 1 Mg Tab Recubierta 28/Caja (Naprod)', '10118022', 100, 'Available', '1601479730_Docetaxel_80mg_2ml-4.png', '<h2>Paragraphs</h2>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</p>\r\n\r\n<p>Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. Aenean dignissim pellentesque felis.</p>\r\n\r\n<p>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.</p>\r\n\r\n<p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas auguae, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</p>\r\n\r\n<p>Phasellus ultrices nulla quis nibh. Quisque a lectus. Donec consectetuer ligula vulputate sem tristique cursus. Nam nulla quam, gravida non, commodo a, sodales sit amet, nisi.</p>\r\n\r\n<h2>Lists</h2>\r\n\r\n<ul>\r\n	<li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>\r\n	<li>Aliquam tincidunt mauris eu risus.</li>\r\n	<li>Vestibulum auctor dapibus neque.</li>\r\n	<li>Nunc dignissim risus id metus.</li>\r\n	<li>Cras ornare tristique elit.</li>\r\n	<li>Vivamus vestibulum ntulla nec ante.</li>\r\n	<li>Praesent placerat risus quis eros.</li>\r\n	<li>Fusce pellentesque suscipit nibh.</li>\r\n	<li>Integer vitae libero ac risus egestas placerat.</li>\r\n	<li>Vestibulum commodo felis quis tortor.</li>\r\n	<li>Ut aliquam sollicitudin leo.</li>\r\n	<li>Cras iaculis ultricies nulla.</li>\r\n	<li>Donec quis dui at dolor tempor interdum.</li>\r\n</ul>\r\n', 100, 20);
INSERT INTO `tbl_product` VALUES (132, 'Carboplatino 10mg/ml Solucion iny i.V 15 ml/FaM 1/Caja (Naprod)', '10118072', 100, 'Available', '1601479935_CARBOPLATINO 10 MG.png', '<h2>Paragraphs</h2>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</p>\r\n\r\n<p>Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. Aenean dignissim pellentesque felis.</p>\r\n\r\n<p>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.</p>\r\n\r\n<p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas auguae, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</p>\r\n\r\n<p>Phasellus ultrices nulla quis nibh. Quisque a lectus. Donec consectetuer ligula vulputate sem tristique cursus. Nam nulla quam, gravida non, commodo a, sodales sit amet, nisi.</p>\r\n\r\n<h2>Lists</h2>\r\n\r\n<ul>\r\n	<li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>\r\n	<li>Aliquam tincidunt mauris eu risus.</li>\r\n	<li>Vestibulum auctor dapibus neque.</li>\r\n	<li>Nunc dignissim risus id metus.</li>\r\n	<li>Cras ornare tristique elit.</li>\r\n	<li>Vivamus vestibulum ntulla nec ante.</li>\r\n	<li>Praesent placerat risus quis eros.</li>\r\n	<li>Fusce pellentesque suscipit nibh.</li>\r\n	<li>Integer vitae libero ac risus egestas placerat.</li>\r\n	<li>Vestibulum commodo felis quis tortor.</li>\r\n	<li>Ut aliquam sollicitudin leo.</li>\r\n	<li>Cras iaculis ultricies nulla.</li>\r\n	<li>Donec quis dui at dolor tempor interdum.</li>\r\n</ul>\r\n', 100, 20);
INSERT INTO `tbl_product` VALUES (133, 'Lenalidomida 25 mg Capsulas 10/Frasco 1/Caja (Naprod)', '10115012', 100, 'Available', '1601480025_Lenalidomida 25 mg.png', '<h2>Paragraphs</h2>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</p>\r\n\r\n<p>Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. Aenean dignissim pellentesque felis.</p>\r\n\r\n<p>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.</p>\r\n\r\n<p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas auguae, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</p>\r\n\r\n<p>Phasellus ultrices nulla quis nibh. Quisque a lectus. Donec consectetuer ligula vulputate sem tristique cursus. Nam nulla quam, gravida non, commodo a, sodales sit amet, nisi.</p>\r\n\r\n<h2>Lists</h2>\r\n\r\n<ul>\r\n	<li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>\r\n	<li>Aliquam tincidunt mauris eu risus.</li>\r\n	<li>Vestibulum auctor dapibus neque.</li>\r\n	<li>Nunc dignissim risus id metus.</li>\r\n	<li>Cras ornare tristique elit.</li>\r\n	<li>Vivamus vestibulum ntulla nec ante.</li>\r\n	<li>Praesent placerat risus quis eros.</li>\r\n	<li>Fusce pellentesque suscipit nibh.</li>\r\n	<li>Integer vitae libero ac risus egestas placerat.</li>\r\n	<li>Vestibulum commodo felis quis tortor.</li>\r\n	<li>Ut aliquam sollicitudin leo.</li>\r\n	<li>Cras iaculis ultricies nulla.</li>\r\n	<li>Donec quis dui at dolor tempor interdum.</li>\r\n</ul>\r\n', 100, 20);
INSERT INTO `tbl_product` VALUES (134, 'Bicalutamida 50 mg Tabletas Recubiertas 100/Frasco 1/Caja (Naprod)', '10118081', 100, 'Available', '1601480081_Bicalutamida 50 mg.png', '<h2>Paragraphs</h2>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</p>\r\n\r\n<p>Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. Aenean dignissim pellentesque felis.</p>\r\n\r\n<p>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.</p>\r\n\r\n<p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas auguae, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</p>\r\n\r\n<p>Phasellus ultrices nulla quis nibh. Quisque a lectus. Donec consectetuer ligula vulputate sem tristique cursus. Nam nulla quam, gravida non, commodo a, sodales sit amet, nisi.</p>\r\n\r\n<h2>Lists</h2>\r\n\r\n<ul>\r\n	<li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>\r\n	<li>Aliquam tincidunt mauris eu risus.</li>\r\n	<li>Vestibulum auctor dapibus neque.</li>\r\n	<li>Nunc dignissim risus id metus.</li>\r\n	<li>Cras ornare tristique elit.</li>\r\n	<li>Vivamus vestibulum ntulla nec ante.</li>\r\n	<li>Praesent placerat risus quis eros.</li>\r\n	<li>Fusce pellentesque suscipit nibh.</li>\r\n	<li>Integer vitae libero ac risus egestas placerat.</li>\r\n	<li>Vestibulum commodo felis quis tortor.</li>\r\n	<li>Ut aliquam sollicitudin leo.</li>\r\n	<li>Cras iaculis ultricies nulla.</li>\r\n	<li>Donec quis dui at dolor tempor interdum.</li>\r\n</ul>\r\n', 100, 20);
INSERT INTO `tbl_product` VALUES (135, 'Cisplatino 1mg/ml Solucion iny i.V 50 ml/Vial 1/Caja (Naprod)', '10118111', 100, 'Available', '1601480241_Cisplatino 50.png', '<p><strong>V&iacute;a de Administraci&oacute;n:</strong><strong>&nbsp;Intravenosa.</strong><br />\r\n<strong>Indicaciones:</strong>&nbsp;En el tratamiento de tumores ov&aacute;ricos metast&aacute;sicos, c&aacute;ncer avanzado<br />\r\nde vejiga, tumores testiculares metaf&iacute;sicos.<br />\r\n<strong>Presentaci&oacute;n:</strong>&nbsp;Caja con vial de 5o ml de soluci&oacute;n inyectable e inserto.<br />\r\n<strong>Modalidad de Venta:</strong>&nbsp;Bajo Prescripci&oacute;n M&eacute;dica.</p>\r\n', 100, 20);
INSERT INTO `tbl_product` VALUES (136, 'Cisplatino 1mg/ml Solucion iny i.V 10 ml/Vial 1/Caja (Naprod)', '10118101', 100, 'Available', '1601480270_Cisplatino 10.png', '<p><strong>V&iacute;a de Administraci&oacute;n</strong>: Intravenosa.<br />\r\n<strong>Indicaciones</strong>: Tratamiento de tumores testiculares metast&aacute;sicos , tumores ov&aacute;ri-<br />\r\ncos, metast&aacute;sico en terapia combinada, c&aacute;ncer avanzado en vejiga en terapia<br />\r\ncomo agente &uacute;nico.<br />\r\n<strong>Presentaci&oacute;n</strong>: Caja con vial de lo ml de soluci&oacute;n inyectable e inserto.<br />\r\n<strong>Modalidad de Venta.</strong>&nbsp;Bajo Prescripci&oacute;n M&eacute;dica.</p>\r\n', 100, 20);
INSERT INTO `tbl_product` VALUES (137, 'Docetaxel 20 Mg/0.5 ml Sol. iny 0.5 ml/Vial 1/Caja Refrigerado (Naprod)', '10118161', 100, 'Available', '1601480375_Docetaxel 20.png', '<p><strong>V&iacute;a</strong><strong>&nbsp;de Administraci&oacute;n:</strong><strong>&nbsp;Intravenosa.</strong><br />\r\n<strong>Indicaciones: Tratamiento</strong>&nbsp;de pacientes con c&aacute;ncer de mama avanzado metast&aacute;sico<br />\r\ndespu&eacute;s de una quimioetrapia anterior fallada para pacientes con carcinoma<br />\r\nde pulm&oacute;n no microcitico avanzado y para el c&aacute;ncer de pr&oacute;stata metast&aacute;sico<br />\r\nandr&oacute;geno independiente.<br />\r\n<strong>Presentaci&oacute;n.</strong>&nbsp;Caja con i frasco de producto + i frasco de solvente e inserto.<br />\r\n<strong>Modalidad de Venta,</strong>&nbsp;Bajo Prescripci&oacute;n M&eacute;dica.</p>\r\n', 100, 20);

-- ----------------------------
-- Table structure for tbl_purchase_code
-- ----------------------------
DROP TABLE IF EXISTS `tbl_purchase_code`;
CREATE TABLE `tbl_purchase_code`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_purchase_code` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_purchase_code
-- ----------------------------
INSERT INTO `tbl_purchase_code` VALUES (2, '111111111111111111111111111111111111');

-- ----------------------------
-- Table structure for tbl_setting
-- ----------------------------
DROP TABLE IF EXISTS `tbl_setting`;
CREATE TABLE `tbl_setting`  (
  `Variable` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Value` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_setting
-- ----------------------------
INSERT INTO `tbl_setting` VALUES ('Tax', '0');
INSERT INTO `tbl_setting` VALUES ('Currency', 'USD');

-- ----------------------------
-- Table structure for tbl_shipping
-- ----------------------------
DROP TABLE IF EXISTS `tbl_shipping`;
CREATE TABLE `tbl_shipping`  (
  `shipping_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipping_name` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`shipping_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_shipping
-- ----------------------------
INSERT INTO `tbl_shipping` VALUES (1, 'Tipo 01');
INSERT INTO `tbl_shipping` VALUES (2, 'Tipo 02');

SET FOREIGN_KEY_CHECKS = 1;
