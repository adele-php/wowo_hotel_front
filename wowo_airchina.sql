/*
Navicat MySQL Data Transfer

Source Server         : 192.168.18.121
Source Server Version : 50621
Source Host           : 192.168.18.121:3306
Source Database       : wowo_airchina

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2017-10-11 17:35:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `air_alipay_notice_log`
-- ----------------------------
DROP TABLE IF EXISTS `air_alipay_notice_log`;
CREATE TABLE `air_alipay_notice_log` (
`id`  int(12) NOT NULL AUTO_INCREMENT ,
`notify_time`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`notify_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`notify_id`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`out_trade_no`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`subject`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`trade_no`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`gmt_create`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`gmt_payment`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`gmt_close`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`refund_status`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`gmt_refund`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`seller_email`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`buyer_email`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`seller_id`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`buyer_id`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`total_fee`  float(10,2) NULL DEFAULT NULL ,
`trade_status`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=16

;

-- ----------------------------
-- Table structure for `air_comment`
-- ----------------------------
DROP TABLE IF EXISTS `air_comment`;
CREATE TABLE `air_comment` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`score`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL ,
`comment`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
`img`  mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
`username`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL ,
`mobile`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL ,
`order_no`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL ,
`room_name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci
AUTO_INCREMENT=20

;

-- ----------------------------
-- Table structure for `air_log`
-- ----------------------------
DROP TABLE IF EXISTS `air_log`;
CREATE TABLE `air_log` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`log`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=4

;

-- ----------------------------
-- Table structure for `air_order`
-- ----------------------------
DROP TABLE IF EXISTS `air_order`;
CREATE TABLE `air_order` (
`order_no`  int(10) NOT NULL AUTO_INCREMENT ,
`wowo_order_no`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`contact_tel`  varchar(12) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`contact_username`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`coupon_code`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`city_id`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`order_status`  int(10) NOT NULL DEFAULT 0 ,
`room_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`check_in`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`check_out`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`total_money`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`confirm_type`  int(3) NOT NULL DEFAULT 1 ,
`hotel_id`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`rate_code`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`mobile`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`confirm_code`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`room_count`  int(5) NULL DEFAULT NULL ,
`adult_count`  int(5) NULL DEFAULT NULL ,
`children_count`  int(5) NULL DEFAULT NULL ,
`children_ages`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`is_pay`  int(3) NOT NULL DEFAULT 0 ,
`is_cancel`  int(3) NOT NULL DEFAULT 1 ,
`cancel_time`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`pay_date_limit`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`spread_no`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`spread_money`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`refund_money`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`hotel_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`order_no`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=138

;

-- ----------------------------
-- Table structure for `air_order_difference_log`
-- ----------------------------
DROP TABLE IF EXISTS `air_order_difference_log`;
CREATE TABLE `air_order_difference_log` (
`id`  int(12) NOT NULL AUTO_INCREMENT ,
`order_no`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`wowo_order_no`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`order_status`  int(5) NOT NULL ,
`spread_no`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`spread_money`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`order_log_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`order_log_remark`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`order_log_time`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`pay_date_limit`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=4

;

-- ----------------------------
-- Table structure for `air_order_reconfirm_log`
-- ----------------------------
DROP TABLE IF EXISTS `air_order_reconfirm_log`;
CREATE TABLE `air_order_reconfirm_log` (
`id`  int(12) NOT NULL AUTO_INCREMENT ,
`confirm_code`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`hotel_id`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`is_cancel`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`order_log_remark`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`order_log_time`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`order_log_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`order_no`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`order_status`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`rate_code`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`room_count`  int(5) NULL DEFAULT NULL ,
`room_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`total_money`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`wowo_order_no`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`pay_date_limit`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=47

;

-- ----------------------------
-- Table structure for `air_order_status_log`
-- ----------------------------
DROP TABLE IF EXISTS `air_order_status_log`;
CREATE TABLE `air_order_status_log` (
`id`  int(12) NOT NULL AUTO_INCREMENT ,
`order_no`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`wowo_order_no`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`order_status`  int(5) NULL DEFAULT NULL ,
`refund_money`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`order_log_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`order_log_remark`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`order_log_time`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=152

;

-- ----------------------------
-- Table structure for `air_test`
-- ----------------------------
DROP TABLE IF EXISTS `air_test`;
CREATE TABLE `air_test` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=2659

;

-- ----------------------------
-- Auto increment value for `air_alipay_notice_log`
-- ----------------------------
ALTER TABLE `air_alipay_notice_log` AUTO_INCREMENT=16;

-- ----------------------------
-- Auto increment value for `air_comment`
-- ----------------------------
ALTER TABLE `air_comment` AUTO_INCREMENT=20;

-- ----------------------------
-- Auto increment value for `air_log`
-- ----------------------------
ALTER TABLE `air_log` AUTO_INCREMENT=4;

-- ----------------------------
-- Auto increment value for `air_order`
-- ----------------------------
ALTER TABLE `air_order` AUTO_INCREMENT=138;

-- ----------------------------
-- Auto increment value for `air_order_difference_log`
-- ----------------------------
ALTER TABLE `air_order_difference_log` AUTO_INCREMENT=4;

-- ----------------------------
-- Auto increment value for `air_order_reconfirm_log`
-- ----------------------------
ALTER TABLE `air_order_reconfirm_log` AUTO_INCREMENT=47;

-- ----------------------------
-- Auto increment value for `air_order_status_log`
-- ----------------------------
ALTER TABLE `air_order_status_log` AUTO_INCREMENT=152;

-- ----------------------------
-- Auto increment value for `air_test`
-- ----------------------------
ALTER TABLE `air_test` AUTO_INCREMENT=2659;
