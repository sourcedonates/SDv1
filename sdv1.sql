/**
 * SDv1 SQL File
 * 
 * The Database for SDv1
 * 
 * This file is Part of SousrceDonatesv1
 * SousrceDonatesv1 is free software: you can redistribute it and/or modify
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * 
 * @package    SousrceDonatesv1
 * @author     Werner Maisl
 * @copyright  (c) 2012-2014 - Werner Maisl
 * @license    GNU AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 */



SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sd_test`
--
CREATE DATABASE IF NOT EXISTS `sd_test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `sd_test`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `openid_identity` text NOT NULL,
  `email` text NOT NULL,
  `permissions` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(10) NOT NULL AUTO_INCREMENT,
  `category_name` text,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table for the Donation Item Cats' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'skins'),
(2, 'misc');

-- --------------------------------------------------------

--
-- Table structure for table `donators`
--

CREATE TABLE IF NOT EXISTS `donators` (
  `steamid` varchar(64) DEFAULT NULL,
  `tag` varchar(128) NOT NULL,
  `level` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='BDI-Talbe';

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `plan_id` text,
  `category_id` int(10) DEFAULT NULL,
  `item_name` text,
  `item_picture` text,
  `store_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='This is the Table for the Items at the Donation Plan' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `plan_id`, `category_id`, `item_name`, `item_picture`, `store_id`) VALUES
(1, '1,2,3,4', 1, 'Item 1', NULL, NULL),
(2, '1,2,3,4', 1, 'skin 2', NULL, NULL),
(3, '1,2,3,4', 1, 'skin3', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(19) NOT NULL,
  `payer_email` varchar(75) NOT NULL,
  `mc_gross` float(9,2) NOT NULL,
  `steam_id` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `date` date NOT NULL,
  `provider` text NOT NULL,
  `plan_id` int(10) NOT NULL,
  `forum_userid` int(10) NOT NULL,
  `maxmind_return` text NOT NULL,
  `trial` text,
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `txn_id` (`txn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pending-orders`
--

CREATE TABLE IF NOT EXISTS `pending-orders` (
  `pending_id` int(11) NOT NULL AUTO_INCREMENT,
  `txn_id` tinytext,
  `user_email` text NOT NULL,
  `steam_id` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `plan_id` int(11) NOT NULL,
  `amount` int(11) DEFAULT NULL,
  `forum_userid` int(11) DEFAULT NULL,
  `add_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `maxmind_return` text NOT NULL,
  PRIMARY KEY (`pending_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE IF NOT EXISTS `plans` (
  `plan_id` int(10) NOT NULL AUTO_INCREMENT,
  `plan_name` tinytext,
  `plan_description` tinytext,
  `plan_price` double DEFAULT '0',
  `plan_time` int(100) DEFAULT '0',
  `plan_color` text,
  `sm_groupid` int(11) DEFAULT NULL,
  `bdi_level` int(11) DEFAULT NULL,
  `store_credits` int(11) DEFAULT NULL,
  `forum_usergroup` int(11) DEFAULT NULL,
  `trial_time` int(11) NOT NULL DEFAULT '10',
  `trial_timeout` int(11) NOT NULL DEFAULT '7',
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`plan_id`, `plan_name`, `plan_description`, `plan_price`, `plan_time`, `plan_color`, `sm_groupid`, `bdi_level`, `store_credits`, `forum_usergroup`, `trial_time`, `trial_timeout`, `hidden`) VALUES
(1, 'Basic', 'A Basic Package', 5, 60, '000000', NULL, NULL, NULL, NULL, 0, 0, 0),
(2, 'Medium', 'A Medium Package', 15, 180, '000000', NULL, NULL, NULL, NULL, 0, 0, 0),
(3, 'Big', 'A Big Package', 30, 360, '000000', NULL, NULL, NULL, NULL, 0, 0, 0),
(4, 'Veteran', 'A Veteran Package', 50, 0, '000000', NULL, NULL, NULL, NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('15b8180bccf3866d32fd38cc2937a21e', '86.140.197.15', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36', 1379960813, ''),
('1b948a1485e46fd0cf8cb09a74e0b73d', '94.224.75.227', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36', 1379950623, ''),
('1e2999116216f880c715676e691b6282', '86.140.197.15', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36', 1379960813, ''),
('30b998162fd9ce5e09a19ccd1779594d', '94.224.75.227', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36', 1379950623, ''),
('47ea85b07c554634e7510dae69b88706', '94.224.75.227', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36', 1379873346, 'a:2:{s:9:"user_data";s:0:"";s:7:"plan_id";s:1:"1";}'),
('540daeaffceb329ba10641b3e06fd818', '89.242.43.183', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36', 1379960738, ''),
('818d96d9bcc6c0a65f95cd471067cf7d', '94.224.75.227', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36', 1379863422, ''),
('8253d7c956137a6ceb53e39550223c4c', '89.242.43.183', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36', 1379960738, 'a:2:{s:9:"user_data";s:0:"";s:7:"plan_id";s:1:"3";}'),
('8cfda9a18bf37d0f07552ad5151afc18', '82.194.207.22', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; WOW64; Trident/6.0)', 1379874458, ''),
('955fb5a0b3703f43c250acc896e57b2b', '82.194.207.22', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; WOW64; Trident/6.0)', 1379874458, ''),
('c87585940fedfb53d2c00a6f8c3f26b0', '83.215.152.75', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36', 1379867260, 'a:3:{s:9:"user_data";s:0:"";s:7:"plan_id";s:1:"4";s:12:"oid_identity";s:80:"https://www.google.com/accounts/o8/id?id=AItOawli22OLu_W-7G-2mjeG0Iai5_u1ernn7oo";}'),
('e9137ba42d253cb2231d40f0264b7ad0', '83.215.152.75', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36', 1379867259, ''),
('ee5d0b20c3f6554783c6814f3bbd7a55', '94.224.75.227', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36', 1379873346, ''),
('f420c839aff3fa8ce1375fff3cb875bd', '94.224.75.227', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36', 1379863422, 'a:2:{s:9:"user_data";s:0:"";s:7:"plan_id";s:1:"1";}');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` text NOT NULL,
  `value` text NOT NULL,
  `description` text,
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=80 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `value`, `description`, `setting_id`) VALUES
('payment_pp_enable', '1', 'Enable PayPal as Payment Provider', 1),
('payment_pp_email', '', 'PayPal Reciever Email', 2),
('payment_pp_currency', 'EUR', 'The PayPal Currency', 3),
('payment_pp_use_sandbox', '0', 'Use the PayPal Sandbox', 4),
('payment_pg_enable', '0', 'Enable PayGol Support', 5),
('payment_pg_currency', 'EUR', 'The PayGol Currency', 6),
('payment_pg_serviceid', '', 'The PayGol ServiceID', 7),
('site_payment_goal', '500', 'Payment Goal of the Site', 8),
('site_language', 'english', 'Language of the Site (Not implemented)', 9),
('integration_forum_system', 'ipb', 'Your Forum System', 10),
('site_approval_list', '0', 'Enable the Approval list', 11),
('testmode_enabled', '0', 'Enable the Site Testmode', 12),
('testmode_payer_email', 'test@test.com', '', 13),
('testmode_user', 'TestUser', '', 14),
('testmode_planid', '1', '', 15),
('testmode_steamid', 'STEAM_X:X:YYYY', '', 16),
('testmode_price', '10', '', 17),
('integration_forum_enabled', '0', 'Use the Forum Integration', 18),
('integration_bdi_enabled', '0', 'Use the BDI Integration', 19),
('integration_sb_enabled', '0', 'Use the SourceBans Support', 20),
('community_name', 'Test Community', 'The Name of your Community', 21),
('community_email', 'arno.thas@telenet.be\r\n', 'The Email of the Community', 22),
('email_useragent', '', 'The server path to Sendmail.', 23),
('email_protocol', 'smtp', 'The server path to Sendmail.', 24),
('email_mailpath', '', 'The server path to Sendmail.', 25),
('email_charset', 'utf-8', 'Charset of the Email', 26),
('email_smtp_host', '', 'The server path to Sendmail.', 27),
('email_smtp_user', '', 'The server path to Sendmail.', 28),
('email_smtp_pass', '', 'The server path to Sendmail.', 29),
('email_smtp_port', '25', 'The server path to Sendmail.', 30),
('email_smtp_timeout', '5', 'The server path to Sendmail.', 31),
('email_mailtype', 'html', 'The server path to Sendmail.', 32),
('integration_forum_usertable', '', 'The User-Table in your Forum', 33),
('integration_forum_stdusrgrp', '', 'The ID of the Standard Usergroup in your Forum', 34),
('testmode_forum_userid', '2', '', 35),
('community_logo', 'img/logo.png', 'The URL to the Logo of your community', 36),
('community_currency', '€', 'The currency, that is displayed at the Plans', 37),
('integration_forum_url', '', 'The URL to your Forum', 38),
('community_url', 'http://www.zep-gaming.com\r\n', 'The URL to your community', 39),
('site_plan_suffix', ' Package', 'Suffix of the Packages/Plans', 40),
('community_currency_long', 'euro', 'The Long Name of the currency', 41),
('community_skins_use', '0', '1 if you are using skins', 42),
('community_skins_id', '2', 'The category id of the Skins Category', 43),
('community_hats_use', '0', '1 if you are using Hats', 44),
('community_hats_id', '0', 'The category id of the Hats Category', 45),
('site_error_email', '0', 'Send Email on Error', 46),
('integration_mysql_admins_enabled', '0', 'Use the Mysql Admin System', 47),
('payment_mollie_use_paysafe', '0', 'Use Paysafecard', 48),
('payment_mollie_enable', '0', 'Use Mollie for  PaysafeCard and iDeal\n', 49),
('payment_mollie_partnerid', '', 'Mollie Partnerid', 50),
('payment_mollie_profile_key', '', 'Mollie Profile Key', 51),
('payment_mollie_use_ideal', '0', 'Use iDeal', 52),
('payment_mollie_ideal_testmode', '0', 'Testmode for iDeal', 53),
('database_server_host', '', 'Host of the Servers DB', 54),
('database_server_user', '', 'User of the Servers DB', 55),
('database_server_db', '', 'Database Name of the Servers DB', 56),
('database_server_port', '', 'Port of the Servers DB', 57),
('database_server_prefix', '', 'Prefix of the Servers DB', 58),
('database_server_password', '', NULL, 59),
('database_forum_db', '', NULL, 60),
('database_forum_host', '', NULL, 61),
('database_forum_port', '', NULL, 62),
('database_forum_prefix', '', NULL, 63),
('database_forum_user', '', NULL, 64),
('database_forum_password', '', NULL, 65),
('integration_forum_grouptable', '', 'The Table where the Forum Groups are stored', 66),
('integration_forum_exception_usergroups', '', '!! DO NOT CHANGE !!', 67),
('community_inform_admin_donation', '0', '1 if the admin should be informed about new donations', 68),
('integration_steamid_autofill', '0', 'If the System should search for the SteamID in the integration DB', 69),
('email_attachments', 'img/email/pattern.jpg', 'enter the different attachments here seperated by a ;', 70),
('email_extra_info', '', 'The Value of the Extra info variable', 71),
('maxmind_enable', '0', 'Maxmind fraud protection', 72),
('maxmind_license_key', '', 'Maxmind license key', 73),
('maxmind_one_time_check', '1', 'If donor has passed fraud check once, avoid disable fraud check for all his new orders.', 74),
('maxmind_risk_tolerance', '4.99', 'Maxmind risk tolerance, between 0.01 to 100.00', 75),
('maxmind_proxy_tolerance', '1.49', 'Maxmind proxy tolerance, between 0.01 to 4.00', 76),
('system_version', 'dev 0.5', 'The Version Number of SourceDonates', 77),
('integration_store_enabled', '0', 'Store Integration', 78),
('integration_store_remove', '1', 'Remove Store ITEMS if the donation is expired', 79);

-- --------------------------------------------------------

--
-- Table structure for table `trial`
--

CREATE TABLE IF NOT EXISTS `trial` (
  `trial_id` int(11) NOT NULL AUTO_INCREMENT,
  `trial_steamid` text NOT NULL,
  `trial_planid` int(11) NOT NULL,
  `trial_timeout` int(11) NOT NULL,
  PRIMARY KEY (`trial_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` text NOT NULL,
  `email` text NOT NULL,
  `last_donation` date NOT NULL,
  `plan_id` int(11) NOT NULL,
  `plan_exp_date` text,
  `status` int(11) NOT NULL DEFAULT '0',
  `steam_id` text NOT NULL,
  `forum_oldusrgroup` int(11) DEFAULT NULL,
  `forum_olddispgroup` int(11) DEFAULT NULL,
  `forum_userid` int(11) DEFAULT NULL,
  `maxmind_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
