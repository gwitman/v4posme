/*
 Navicat MySQL Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100427 (10.4.27-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : posme

 Target Server Type    : MySQL
 Target Server Version : 100427 (10.4.27-MariaDB)
 File Encoding         : 65001

 Date: 19/03/2026 15:41:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tb_account
-- ----------------------------
DROP TABLE IF EXISTS `tb_account`;
CREATE TABLE `tb_account`  (
  `accountID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `accountTypeID` int NOT NULL DEFAULT 0,
  `accountLevelID` int NOT NULL DEFAULT 0,
  `parentAccountID` int NULL DEFAULT NULL,
  `classID` int NULL DEFAULT NULL,
  `accountNumber` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isOperative` tinyint(1) NOT NULL DEFAULT 0,
  `statusID` int NOT NULL DEFAULT 0,
  `currencyID` int NOT NULL DEFAULT 0,
  `createdBy` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `createdIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `createdAt` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`accountID`) USING BTREE,
  INDEX `IDX_ACCOUNT_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNT_002`(`accountTypeID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNT_003`(`accountLevelID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNT_004`(`parentAccountID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNT_005`(`classID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNT_006`(`accountNumber` ASC) USING BTREE,
  INDEX `IDX_ACCOUNT_007`(`statusID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNT_008`(`currencyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 55 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_account_level
-- ----------------------------
DROP TABLE IF EXISTS `tb_account_level`;
CREATE TABLE `tb_account_level`  (
  `companyID` int NOT NULL DEFAULT 0,
  `accountLevelID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lengthTotal` int NOT NULL DEFAULT 0,
  `split` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lengthGroup` int NULL DEFAULT NULL,
  `isOperative` tinyint(1) NOT NULL DEFAULT 0,
  `createdBy` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `createdIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `createdAt` int NULL DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`accountLevelID`) USING BTREE,
  INDEX `IDX_ACCOUNT_LEVEL_001`(`companyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_account_tmp
-- ----------------------------
DROP TABLE IF EXISTS `tb_account_tmp`;
CREATE TABLE `tb_account_tmp`  (
  `accountID` int NOT NULL AUTO_INCREMENT,
  `accountParentID` int NULL DEFAULT NULL,
  `n1` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `n2` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `n3` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `n4` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `n5` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nivel` int NULL DEFAULT NULL,
  `operative` bit(1) NULL DEFAULT NULL,
  `balance` decimal(30, 8) NULL DEFAULT NULL,
  PRIMARY KEY (`accountID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3025 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_account_type
-- ----------------------------
DROP TABLE IF EXISTS `tb_account_type`;
CREATE TABLE `tb_account_type`  (
  `companyID` int NOT NULL DEFAULT 0,
  `accountTypeID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(350) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `naturaleza` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `createdBy` int NOT NULL DEFAULT 0,
  `createdAt` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `createdOn` datetime NOT NULL DEFAULT current_timestamp,
  `createdIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `isActive` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`accountTypeID`) USING BTREE,
  INDEX `IDX_ACCOUNT_TYPE_001`(`accountTypeID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNT_TYPE_002`(`companyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_accounting_balance
-- ----------------------------
DROP TABLE IF EXISTS `tb_accounting_balance`;
CREATE TABLE `tb_accounting_balance`  (
  `accountBalanceID` int NOT NULL AUTO_INCREMENT,
  `componentCycleID` int NOT NULL DEFAULT 0,
  `componentPeriodID` int NOT NULL DEFAULT 0,
  `companyID` int NOT NULL DEFAULT 0,
  `componentID` int NOT NULL DEFAULT 0,
  `accountID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `balance` decimal(18, 8) NULL DEFAULT NULL,
  `debit` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  `credit` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  `classID` int NULL DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`accountBalanceID`) USING BTREE,
  INDEX `IDX_ACCOUNTING_BALANCE_001`(`componentCycleID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNTING_BALANCE_002`(`componentPeriodID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNTING_BALANCE_003`(`companyID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNTING_BALANCE_004`(`componentID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNTING_BALANCE_005`(`accountID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNTING_BALANCE_006`(`branchID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNTING_BALANCE_007`(`classID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_accounting_balance_temp
-- ----------------------------
DROP TABLE IF EXISTS `tb_accounting_balance_temp`;
CREATE TABLE `tb_accounting_balance_temp`  (
  `accountingBalanceTempID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `loginID` int NOT NULL DEFAULT 0,
  `tocken` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `accountID` int NOT NULL DEFAULT 0,
  `parentAccountID` int NULL DEFAULT NULL,
  `accountNumber` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isOperative` bit(18) NOT NULL DEFAULT b'0',
  `statusID` int NOT NULL DEFAULT 0,
  `accountTypeID` int NOT NULL DEFAULT 0,
  `naturaleza` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `balanceStart` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  `debit` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  `credit` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  `balanceEnd` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  PRIMARY KEY (`accountingBalanceTempID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_accounting_cycle
-- ----------------------------
DROP TABLE IF EXISTS `tb_accounting_cycle`;
CREATE TABLE `tb_accounting_cycle`  (
  `componentCycleID` int NOT NULL AUTO_INCREMENT,
  `componentPeriodID` int NOT NULL DEFAULT 0,
  `companyID` int NOT NULL DEFAULT 0,
  `componentID` int NOT NULL DEFAULT 0,
  `number` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `startOn` datetime NOT NULL DEFAULT current_timestamp,
  `endOn` datetime NOT NULL DEFAULT current_timestamp,
  `statusID` int NOT NULL DEFAULT 0,
  `isActive` tinyint(1) NOT NULL DEFAULT 0,
  `createdBy` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `createdAt` int NULL DEFAULT NULL,
  `createdIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`componentCycleID`) USING BTREE,
  INDEX `IDX_ACCOUNTING_CYCLE_001`(`componentPeriodID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNTING_CYCLE_002`(`companyID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNTING_CYCLE_003`(`componentID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNTING_CYCLE_004`(`statusID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 265 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_accounting_period
-- ----------------------------
DROP TABLE IF EXISTS `tb_accounting_period`;
CREATE TABLE `tb_accounting_period`  (
  `componentPeriodID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `componentID` int NOT NULL DEFAULT 0,
  `number` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `startOn` datetime NOT NULL DEFAULT current_timestamp,
  `endOn` datetime NOT NULL DEFAULT current_timestamp,
  `statusID` int NOT NULL DEFAULT 0,
  `isActive` tinyint(1) NOT NULL DEFAULT 0,
  `createdBy` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `createdAt` int NULL DEFAULT NULL,
  `createdIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`componentPeriodID`) USING BTREE,
  INDEX `IDX_ACCOUNTING_PERIOD_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNTING_PERIOD_002`(`componentID` ASC) USING BTREE,
  INDEX `IDX_ACCOUNTING_PERIOD_003`(`statusID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_bank
-- ----------------------------
DROP TABLE IF EXISTS `tb_bank`;
CREATE TABLE `tb_bank`  (
  `bankID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `accountNumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `accountID` int NULL DEFAULT 0,
  `currencyID` int NULL DEFAULT 0,
  `balance` decimal(19, 8) NULL DEFAULT 0.00000000,
  `managerID` int NULL DEFAULT NULL,
  `cardNumber` int NULL DEFAULT NULL,
  `dateExpired` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `urlBank` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `statusID` int NULL DEFAULT NULL,
  `invoiceable` tinyint NULL DEFAULT NULL,
  `isActive` int NULL DEFAULT 1,
  `comisionPos` decimal(18, 8) NULL DEFAULT 0.00000000,
  `comisionSave` decimal(18, 8) NULL DEFAULT 0.00000000,
  PRIMARY KEY (`bankID`) USING BTREE,
  INDEX `IDX_BANK_001`(`accountID` ASC) USING BTREE,
  INDEX `IDX_BANK_002`(`currencyID` ASC) USING BTREE,
  INDEX `IDX_BANK_003`(`companyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_bank_cheque
-- ----------------------------
DROP TABLE IF EXISTS `tb_bank_cheque`;
CREATE TABLE `tb_bank_cheque`  (
  `chequeID` int NOT NULL AUTO_INCREMENT,
  `chequeNumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `statusID` int NULL DEFAULT NULL,
  `bankID` int NULL DEFAULT NULL,
  `currencyID` int NULL DEFAULT NULL,
  `valueInitial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `valueCurrent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `valueFinal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `serie` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `managerID` int NULL DEFAULT NULL,
  `isActive` tinyint NULL DEFAULT NULL,
  PRIMARY KEY (`chequeID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_biblia
-- ----------------------------
DROP TABLE IF EXISTS `tb_biblia`;
CREATE TABLE `tb_biblia`  (
  `versiculoID` int NOT NULL AUTO_INCREMENT,
  `orden` int NOT NULL DEFAULT 0,
  `dia` int NOT NULL DEFAULT 0,
  `capitulo` int NOT NULL DEFAULT 0,
  `libro` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'N',
  `versiculo` varchar(1500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`versiculoID`) USING BTREE,
  INDEX `IDX_BIBLIA_001`(`dia` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 425 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_branch
-- ----------------------------
DROP TABLE IF EXISTS `tb_branch`;
CREATE TABLE `tb_branch`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'N/D',
  `createdOn` datetime NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `address` varchar(1200) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `serie` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`branchID`) USING BTREE,
  INDEX `IDX_BRANCH_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_BRANCH_002`(`companyID` ASC, `branchID` ASC, `isActive` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_caller
-- ----------------------------
DROP TABLE IF EXISTS `tb_caller`;
CREATE TABLE `tb_caller`  (
  `callerID` int NOT NULL AUTO_INCREMENT,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`callerID`) USING BTREE,
  INDEX `IDX_CALLER_001`(`callerID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_cash_box
-- ----------------------------
DROP TABLE IF EXISTS `tb_cash_box`;
CREATE TABLE `tb_cash_box`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `cashBoxID` int NOT NULL AUTO_INCREMENT,
  `cashBoxCode` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(550) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `statusID` int NOT NULL DEFAULT 0,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`cashBoxID`) USING BTREE,
  INDEX `IDX_CASH_BOX_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_003`(`cashBoxCode` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_004`(`statusID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_005`(`cashBoxID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_cash_box_session
-- ----------------------------
DROP TABLE IF EXISTS `tb_cash_box_session`;
CREATE TABLE `tb_cash_box_session`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `cashBoxID` int NOT NULL DEFAULT 0,
  `cashBoxSessionID` int NOT NULL AUTO_INCREMENT,
  `startOn` datetime NOT NULL DEFAULT current_timestamp,
  `endOn` datetime NULL DEFAULT NULL,
  `statusID` int NOT NULL DEFAULT 0,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  `userID` int NULL DEFAULT NULL,
  `transactionMasterIDOpen` int NULL DEFAULT NULL,
  `transactionMasterIDClosed` int NULL DEFAULT NULL,
  `currencyID` int NULL DEFAULT 0,
  PRIMARY KEY (`cashBoxSessionID`) USING BTREE,
  INDEX `IDX_CASH_BOX_SESSION_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_SESSION_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_SESSION_003`(`cashBoxID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_SESSION_004`(`cashBoxSessionID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_SESSION_005`(`statusID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_SESSION_006`(`userID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_cash_box_session_transaction_master
-- ----------------------------
DROP TABLE IF EXISTS `tb_cash_box_session_transaction_master`;
CREATE TABLE `tb_cash_box_session_transaction_master`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `cashBoxID` int NOT NULL DEFAULT 0,
  `cashBoxSessionID` int NOT NULL DEFAULT 0,
  `transactionID` int NOT NULL DEFAULT 0,
  `transactionMasterID` int NOT NULL DEFAULT 0,
  `cashBoxSessionTransactionMasterID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`cashBoxSessionTransactionMasterID`) USING BTREE,
  INDEX `IDX_CASH_BOX_SESSION_TRANSACTION_MASTER_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_SESSION_TRANSACTION_MASTER_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_SESSION_TRANSACTION_MASTER_003`(`cashBoxID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_SESSION_TRANSACTION_MASTER_004`(`cashBoxSessionID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_SESSION_TRANSACTION_MASTER_005`(`transactionID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_SESSION_TRANSACTION_MASTER_006`(`transactionMasterID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7025 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_cash_box_user
-- ----------------------------
DROP TABLE IF EXISTS `tb_cash_box_user`;
CREATE TABLE `tb_cash_box_user`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `userID` int NOT NULL DEFAULT 0,
  `cashBoxID` int NOT NULL DEFAULT 0,
  `typeID` int NOT NULL DEFAULT 0,
  `cashBoxUserID` int NOT NULL AUTO_INCREMENT,
  `isPrimary` int NULL DEFAULT 0,
  `isActive` int NULL DEFAULT 0,
  PRIMARY KEY (`cashBoxUserID`) USING BTREE,
  INDEX `IDX_CASH_BOX_USER_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_USER_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_USER_003`(`userID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_USER_004`(`cashBoxID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_USER_005`(`typeID` ASC) USING BTREE,
  INDEX `IDX_CASH_BOX_USER_006`(`cashBoxUserID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 520 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_catalog
-- ----------------------------
DROP TABLE IF EXISTS `tb_catalog`;
CREATE TABLE `tb_catalog`  (
  `catalogID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `orden` int NOT NULL DEFAULT 0,
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `publicCatalogSystemName` varchar(500) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`catalogID`) USING BTREE,
  INDEX `IDX_CATALOG_001`(`catalogID` ASC) USING BTREE,
  INDEX `IDX_CATALOG_002`(`catalogID` ASC, `isActive` ASC) USING BTREE,
  INDEX `IDX_CATALOG_003`(`name` ASC, `isActive` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 111 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_catalog_item
-- ----------------------------
DROP TABLE IF EXISTS `tb_catalog_item`;
CREATE TABLE `tb_catalog_item`  (
  `catalogID` int NOT NULL DEFAULT 0,
  `catalogItemID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `display` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `flavorID` int NULL DEFAULT NULL,
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `sequence` int NULL DEFAULT NULL,
  `parentCatalogID` int NULL DEFAULT NULL,
  `parentCatalogItemID` int NULL DEFAULT NULL,
  `ratio` decimal(19, 8) NOT NULL DEFAULT 1.00000000,
  `reference1` varchar(150) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `reference2` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `reference3` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `reference4` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `isActive` tinyint NULL DEFAULT 1,
  PRIMARY KEY (`catalogItemID`) USING BTREE,
  INDEX `IDX_CATALOG_ITEM_001`(`catalogID` ASC) USING BTREE,
  INDEX `IDX_CATALOG_ITEM_002`(`flavorID` ASC) USING BTREE,
  INDEX `IDX_CATALOG_ITEM_003`(`parentCatalogID` ASC) USING BTREE,
  INDEX `IDX_CATALOG_ITEM_004`(`parentCatalogItemID` ASC) USING BTREE,
  INDEX `IDX_CATALOG_ITEM_005`(`catalogItemID` ASC) USING BTREE,
  INDEX `IDX_CATALOG_ITEM_006`(`catalogID` ASC, `catalogItemID` ASC) USING BTREE,
  INDEX `IDX_CATALOG_ITEM_007`(`catalogID` ASC, `flavorID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5162 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_catalog_item_convertion
-- ----------------------------
DROP TABLE IF EXISTS `tb_catalog_item_convertion`;
CREATE TABLE `tb_catalog_item_convertion`  (
  `catalogItemConvertionID` int NOT NULL AUTO_INCREMENT,
  `componentID` int NOT NULL DEFAULT 0,
  `companyID` int NOT NULL DEFAULT 0,
  `catalogID` int NOT NULL DEFAULT 0,
  `catalogItemID` int NOT NULL DEFAULT 0,
  `targetCatalogItemID` int NULL DEFAULT NULL,
  `ratio` decimal(18, 4) NULL DEFAULT NULL,
  `registerDate` datetime NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`catalogItemConvertionID`) USING BTREE,
  INDEX `IDX_CATALOG_ITEM_CONVERSATION_001`(`componentID` ASC) USING BTREE,
  INDEX `IDX_CATALOG_ITEM_CONVERSATION_002`(`companyID` ASC) USING BTREE,
  INDEX `IDX_CATALOG_ITEM_CONVERSATION_003`(`catalogID` ASC) USING BTREE,
  INDEX `IDX_CATALOG_ITEM_CONVERSATION_004`(`catalogItemID` ASC) USING BTREE,
  INDEX `IDX_CATALOG_ITEM_CONVERSATION_005`(`targetCatalogItemID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_center_cost
-- ----------------------------
DROP TABLE IF EXISTS `tb_center_cost`;
CREATE TABLE `tb_center_cost`  (
  `classID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `accountLevelID` int NOT NULL DEFAULT 0,
  `parentAccountID` int NULL DEFAULT NULL,
  `parentClassID` int NULL DEFAULT NULL,
  `number` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `createdBy` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `createdAt` int NULL DEFAULT NULL,
  `createdIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`classID`) USING BTREE,
  INDEX `IDX_CENTER_COST_001`(`classID` ASC) USING BTREE,
  INDEX `IDX_CENTER_COST_002`(`companyID` ASC) USING BTREE,
  INDEX `IDX_CENTER_COST_003`(`accountLevelID` ASC) USING BTREE,
  INDEX `IDX_CENTER_COST_004`(`parentAccountID` ASC) USING BTREE,
  INDEX `IDX_CENTER_COST_005`(`parentClassID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_company
-- ----------------------------
DROP TABLE IF EXISTS `tb_company`;
CREATE TABLE `tb_company`  (
  `companyID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT 'N/D',
  `address` varchar(550) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT 'N/D',
  `createdOn` datetime NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT 1,
  `flavorID` int NOT NULL DEFAULT 0,
  `type` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `abreviature` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `namePublic` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`companyID`) USING BTREE,
  INDEX `IDX_COMPANY_001`(`flavorID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_002`(`companyID` ASC, `isActive` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_company_component
-- ----------------------------
DROP TABLE IF EXISTS `tb_company_component`;
CREATE TABLE `tb_company_component`  (
  `componentID` int NOT NULL DEFAULT 0,
  `companyID` int NOT NULL DEFAULT 0,
  `companyComponentID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`companyComponentID`) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_001`(`componentID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_002`(`companyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 67 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_company_component_concept
-- ----------------------------
DROP TABLE IF EXISTS `tb_company_component_concept`;
CREATE TABLE `tb_company_component_concept`  (
  `companyID` int NOT NULL DEFAULT 0,
  `componentID` int NOT NULL DEFAULT 0,
  `componentItemID` int NOT NULL DEFAULT 0,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `valueIn` decimal(18, 8) NULL DEFAULT NULL,
  `valueOut` decimal(18, 8) NULL DEFAULT NULL,
  `companyComponentConceptID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`companyComponentConceptID`) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_CONCEPT_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_CONCEPT_002`(`componentID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_CONCEPT_003`(`componentItemID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6063 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_company_component_flavor
-- ----------------------------
DROP TABLE IF EXISTS `tb_company_component_flavor`;
CREATE TABLE `tb_company_component_flavor`  (
  `companyID` int NOT NULL DEFAULT 0,
  `componentID` int NOT NULL DEFAULT 0,
  `componentItemID` int NOT NULL DEFAULT 0,
  `flavorID` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `companyComponentFlavorID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`companyComponentFlavorID`) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_FLAVOR_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_FLAVOR_002`(`componentID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_FLAVOR_003`(`componentItemID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_FLAVOR_004`(`flavorID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_FLAVOR_005`(`companyID` ASC, `componentID` ASC, `componentItemID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 247 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_company_component_item_dataview
-- ----------------------------
DROP TABLE IF EXISTS `tb_company_component_item_dataview`;
CREATE TABLE `tb_company_component_item_dataview`  (
  `companyID` int NOT NULL DEFAULT 0,
  `componentID` int NOT NULL DEFAULT 0,
  `dataViewID` int NOT NULL DEFAULT 0,
  `callerID` int NOT NULL DEFAULT 0,
  `flavorID` int NOT NULL DEFAULT 0,
  `companyComponentItemDataviewID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`companyComponentItemDataviewID`) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_ITEM_DATAVIEW_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_ITEM_DATAVIEW_002`(`componentID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_ITEM_DATAVIEW_003`(`dataViewID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_ITEM_DATAVIEW_004`(`callerID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_COMPONENT_ITEM_DATAVIEW_005`(`flavorID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 89 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_company_component_relation
-- ----------------------------
DROP TABLE IF EXISTS `tb_company_component_relation`;
CREATE TABLE `tb_company_component_relation`  (
  `companyComponentRelationID` int NOT NULL AUTO_INCREMENT,
  `componentIDSource` int NULL DEFAULT NULL,
  `componentItemIDSource` int NULL DEFAULT NULL,
  `componentIDTarget` int NULL DEFAULT NULL,
  `componentItemIDTarget` int NULL DEFAULT NULL,
  `isActive` int NULL DEFAULT NULL,
  `note` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `lastActivityOn` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`companyComponentRelationID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 118 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_company_currency
-- ----------------------------
DROP TABLE IF EXISTS `tb_company_currency`;
CREATE TABLE `tb_company_currency`  (
  `currencyID` int NOT NULL DEFAULT 0,
  `companyID` int NOT NULL DEFAULT 0,
  `simb` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `companyCurrencyID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`companyCurrencyID`) USING BTREE,
  INDEX `IDX_COMPANY_CURRENCY_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_CURRENCY_002`(`currencyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_company_dataview
-- ----------------------------
DROP TABLE IF EXISTS `tb_company_dataview`;
CREATE TABLE `tb_company_dataview`  (
  `companyID` int NOT NULL DEFAULT 0,
  `dataViewID` int NOT NULL DEFAULT 0,
  `callerID` int NOT NULL DEFAULT 0,
  `componentID` int NOT NULL DEFAULT 0,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `sqlScript` varchar(5000) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `visibleColumns` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `nonVisibleColumns` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `summaryColumns` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `formatColumns` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `flavorID` int NULL DEFAULT 0,
  `companyDataViewID` int NOT NULL AUTO_INCREMENT,
  `formatColumnsHeader` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `jsonConfiguration` varchar(5000) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`companyDataViewID`) USING BTREE,
  INDEX `IDX_COMPANY_DATAVIEW_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_DATAVIEW_002`(`dataViewID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_DATAVIEW_003`(`callerID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_DATAVIEW_004`(`componentID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_DATAVIEW_005`(`companyID` ASC, `dataViewID` ASC, `callerID` ASC, `componentID` ASC, `isActive` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 393 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_company_default_dataview
-- ----------------------------
DROP TABLE IF EXISTS `tb_company_default_dataview`;
CREATE TABLE `tb_company_default_dataview`  (
  `companyID` int NOT NULL DEFAULT 0,
  `componentID` int NOT NULL DEFAULT 0,
  `dataViewID` int NOT NULL DEFAULT 0,
  `callerID` int NOT NULL DEFAULT 0,
  `targetComponentID` int NULL DEFAULT NULL,
  `companyDefaultDataviewID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`companyDefaultDataviewID`) USING BTREE,
  INDEX `IDX_COMPANY_DEFAULT_DATAVIEW_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_DEFAULT_DATAVIEW_002`(`componentID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_DEFAULT_DATAVIEW_003`(`dataViewID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_DEFAULT_DATAVIEW_004`(`callerID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_DEFAULT_DATAVIEW_005`(`targetComponentID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_DEFAULT_DATAVIEW_006`(`companyID` ASC, `componentID` ASC, `callerID` ASC, `targetComponentID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 332 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_company_page_setting
-- ----------------------------
DROP TABLE IF EXISTS `tb_company_page_setting`;
CREATE TABLE `tb_company_page_setting`  (
  `customPageID` int NOT NULL AUTO_INCREMENT,
  `namei` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keyi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `controller` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `flavorID` int NULL DEFAULT NULL,
  `element` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `valuei` varchar(4000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `isActive` int NULL DEFAULT NULL,
  PRIMARY KEY (`customPageID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 72 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_company_page_setting_large
-- ----------------------------
DROP TABLE IF EXISTS `tb_company_page_setting_large`;
CREATE TABLE `tb_company_page_setting_large`  (
  `customPageLargeID` int NOT NULL AUTO_INCREMENT,
  `namei` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keyi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `controller` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `flavorID` int NULL DEFAULT NULL,
  `element` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `valuei` blob NULL,
  `isActive` int NULL DEFAULT NULL,
  PRIMARY KEY (`customPageLargeID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_company_parameter
-- ----------------------------
DROP TABLE IF EXISTS `tb_company_parameter`;
CREATE TABLE `tb_company_parameter`  (
  `parameterID` int NOT NULL DEFAULT 0,
  `companyID` int NOT NULL DEFAULT 0,
  `display` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `value` varchar(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `customValue` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `companyParameterID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`companyParameterID`) USING BTREE,
  INDEX `IDX_COMPANY_PARAMETER_001`(`parameterID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_PARAMETER_002`(`companyID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_PARAMETER_003`(`parameterID` ASC, `companyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 294 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_company_subelement_audit
-- ----------------------------
DROP TABLE IF EXISTS `tb_company_subelement_audit`;
CREATE TABLE `tb_company_subelement_audit`  (
  `elementID` int NOT NULL DEFAULT 0,
  `subElementID` int NOT NULL DEFAULT 0,
  `companyID` int NOT NULL DEFAULT 0,
  `companySubelementAudiID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`companySubelementAudiID`) USING BTREE,
  INDEX `IDX_COMPANY_SUBELEMENT_AUDIT_001`(`elementID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_SUBELEMENT_AUDIT_002`(`subElementID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_SUBELEMENT_AUDIT_003`(`companyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_company_subelement_obligatory
-- ----------------------------
DROP TABLE IF EXISTS `tb_company_subelement_obligatory`;
CREATE TABLE `tb_company_subelement_obligatory`  (
  `companyID` int NOT NULL DEFAULT 0,
  `elementID` int NOT NULL DEFAULT 0,
  `subElementID` int NOT NULL DEFAULT 0,
  `companySubelementObligatoryID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`companySubelementObligatoryID`) USING BTREE,
  INDEX `IDX_COMPANY_SUBELEMENT_OBLIGATORI_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_SUBELEMENT_OBLIGATORI_002`(`elementID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_SUBELEMENT_OBLIGATORI_003`(`subElementID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_component
-- ----------------------------
DROP TABLE IF EXISTS `tb_component`;
CREATE TABLE `tb_component`  (
  `componentID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`componentID`) USING BTREE,
  INDEX `IDX_COMPONENT_001`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 136 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_component_audit
-- ----------------------------
DROP TABLE IF EXISTS `tb_component_audit`;
CREATE TABLE `tb_component_audit`  (
  `componentAuditID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `elementID` int NULL DEFAULT NULL,
  `elementItemID` int NULL DEFAULT NULL,
  `modifiedOn` datetime NULL DEFAULT NULL,
  `modifiedAt` int NULL DEFAULT NULL,
  `modifiedIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `modifiedBy` int NULL DEFAULT NULL,
  PRIMARY KEY (`componentAuditID`) USING BTREE,
  INDEX `IDX_COMPONENT_AUDIT_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_COMPONENT_AUDIT_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_COMPONENT_AUDIT_003`(`elementID` ASC) USING BTREE,
  INDEX `IDX_COMPONENT_AUDIT_004`(`elementItemID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_component_audit_detail
-- ----------------------------
DROP TABLE IF EXISTS `tb_component_audit_detail`;
CREATE TABLE `tb_component_audit_detail`  (
  `componentAuditDetailID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `componentAuditID` int NOT NULL DEFAULT 0,
  `fieldID` int NULL DEFAULT NULL,
  `oldValue` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `newValue` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `note` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`componentAuditDetailID`) USING BTREE,
  INDEX `IDX_COMPONENT_AUDIT_DETAIL_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_COMPONENT_AUDIT_DETAIL_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_COMPONENT_AUDIT_DETAIL_003`(`componentAuditID` ASC) USING BTREE,
  INDEX `IDX_COMPONENT_AUDIT_DETAIL_004`(`fieldID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_component_autorization
-- ----------------------------
DROP TABLE IF EXISTS `tb_component_autorization`;
CREATE TABLE `tb_component_autorization`  (
  `componentAutorizationID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`componentAutorizationID`) USING BTREE,
  INDEX `IDX_COMPONENT_AUTORIZATION_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_COMPONENT_AUTORIZATION_002`(`componentAutorizationID` ASC, `companyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_component_autorization_detail
-- ----------------------------
DROP TABLE IF EXISTS `tb_component_autorization_detail`;
CREATE TABLE `tb_component_autorization_detail`  (
  `companyID` int NOT NULL DEFAULT 0,
  `componentAutorizationID` int NOT NULL DEFAULT 0,
  `componentID` int NOT NULL DEFAULT 0,
  `workflowID` int NOT NULL DEFAULT 0,
  `workflowStageID` int NOT NULL DEFAULT 0,
  `componentAurotizationDetailID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`componentAurotizationDetailID`) USING BTREE,
  INDEX `IDX_COMPONENT_AUTORIZATSION_DETAIL_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_COMPONENT_AUTORIZATSION_DETAIL_002`(`componentAutorizationID` ASC) USING BTREE,
  INDEX `IDX_COMPONENT_AUTORIZATSION_DETAIL_003`(`componentID` ASC) USING BTREE,
  INDEX `IDX_COMPONENT_AUTORIZATSION_DETAIL_004`(`workflowID` ASC) USING BTREE,
  INDEX `IDX_COMPONENT_AUTORIZATSION_DETAIL_005`(`workflowStageID` ASC) USING BTREE,
  INDEX `IDX_COMPONENT_AUTORIZATSION_DETAIL_006`(`companyID` ASC, `componentAutorizationID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 277 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_component_element
-- ----------------------------
DROP TABLE IF EXISTS `tb_component_element`;
CREATE TABLE `tb_component_element`  (
  `elementID` int NOT NULL DEFAULT 0,
  `componentID` int NOT NULL DEFAULT 0,
  `componentElementID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`componentElementID`) USING BTREE,
  INDEX `IDX_COMPONENT_ELEMENT_001`(`elementID` ASC) USING BTREE,
  INDEX `IDX_COMPONENT_ELEMENT_002`(`componentID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 344 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_counter
-- ----------------------------
DROP TABLE IF EXISTS `tb_counter`;
CREATE TABLE `tb_counter`  (
  `counterID` int NOT NULL AUTO_INCREMENT,
  `componentID` int NOT NULL DEFAULT 0,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `componentItemID` int NULL DEFAULT NULL,
  `initialValue` int NULL DEFAULT NULL,
  `currentValue` int NULL DEFAULT NULL,
  `seed` int NULL DEFAULT NULL,
  `serie` varchar(10) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `length` int NULL DEFAULT NULL,
  PRIMARY KEY (`counterID`) USING BTREE,
  INDEX `IDX_COUNTER_001`(`componentID` ASC) USING BTREE,
  INDEX `IDX_COUNTER_002`(`companyID` ASC) USING BTREE,
  INDEX `IDX_COUNTER_003`(`branchID` ASC) USING BTREE,
  INDEX `IDX_COUNTER_004`(`componentItemID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 83 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_credit_line
-- ----------------------------
DROP TABLE IF EXISTS `tb_credit_line`;
CREATE TABLE `tb_credit_line`  (
  `creditLineID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(400) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`creditLineID`) USING BTREE,
  INDEX `IDX_CREDIT_LINE_001`(`companyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_currency
-- ----------------------------
DROP TABLE IF EXISTS `tb_currency`;
CREATE TABLE `tb_currency`  (
  `currencyID` int NOT NULL AUTO_INCREMENT,
  `simbol` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`currencyID`) USING BTREE,
  INDEX `IDX_CURRENCY_001`(`currencyID` ASC) USING BTREE,
  INDEX `IDX_CURRENCY_002`(`name` ASC, `isActive` ASC) USING BTREE,
  INDEX `IDX_CURRENCY_003`(`currencyID` ASC, `isActive` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_customer
-- ----------------------------
DROP TABLE IF EXISTS `tb_customer`;
CREATE TABLE `tb_customer`  (
  `customerID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `entityID` int NOT NULL DEFAULT 0,
  `customerNumber` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `identificationType` int NULL DEFAULT NULL,
  `identification` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `countryID` int NULL DEFAULT NULL,
  `stateID` int NULL DEFAULT NULL,
  `cityID` int NULL DEFAULT NULL,
  `location` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `address` varchar(1500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `currencyID` int NULL DEFAULT NULL,
  `clasificationID` int NULL DEFAULT NULL,
  `categoryID` int NULL DEFAULT NULL,
  `subCategoryID` int NULL DEFAULT NULL,
  `customerTypeID` int NULL DEFAULT NULL,
  `birthDate` date NULL DEFAULT NULL,
  `statusID` int NULL DEFAULT NULL,
  `typePay` int NULL DEFAULT NULL,
  `payConditionID` int NULL DEFAULT NULL,
  `sexoID` int NULL DEFAULT NULL,
  `typeFirm` int NULL DEFAULT 0 COMMENT 'Tipo de Firma',
  `reference1` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `createdIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `createdBy` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `createdAt` int NULL DEFAULT NULL,
  `isActive` bit(1) NULL DEFAULT NULL,
  `balancePoint` decimal(10, 2) NULL DEFAULT NULL,
  `phoneNumber` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dateContract` date NULL DEFAULT NULL,
  `entityContactID` int NULL DEFAULT NULL COMMENT 'Persona que contacto al cliente',
  `reference3` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference4` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference5` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference6` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `budget` decimal(10, 2) NULL DEFAULT 0.00,
  `modifiedOn` date NULL DEFAULT NULL,
  `formContactID` int NULL DEFAULT 0,
  `balanceDol` decimal(10, 2) NULL DEFAULT 0.00,
  `balanceCor` decimal(10, 2) NULL DEFAULT 0.00,
  `entityReferenceID` int NULL DEFAULT NULL,
  `allowWhatsappPromotions` bit(1) NULL DEFAULT b'0' COMMENT 'Acepta promociones por whatapp',
  `allowWhatsappCollection` bit(1) NULL DEFAULT b'0' COMMENT 'Acepta cobro por whatsapp',
  `dateTimeLastMessageReceipt` datetime NULL DEFAULT NULL,
  `dateTimeLastMessageSend` datetime NULL DEFAULT NULL,
  `isMessageNoRead` smallint NULL DEFAULT NULL,
  PRIMARY KEY (`customerID`) USING BTREE,
  INDEX `IDX_CUSTOMER_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_003`(`entityID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_004`(`customerNumber` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_005`(`identificationType` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_006`(`countryID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_007`(`stateID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_008`(`cityID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_009`(`currencyID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_010`(`clasificationID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_011`(`categoryID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_012`(`subCategoryID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_013`(`customerTypeID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_014`(`statusID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_015`(`typePay` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_016`(`payConditionID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_017`(`sexoID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_018`(`typeFirm` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_019`(`entityContactID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_020`(`formContactID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 196 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_customer_consultas_sin_riesgo
-- ----------------------------
DROP TABLE IF EXISTS `tb_customer_consultas_sin_riesgo`;
CREATE TABLE `tb_customer_consultas_sin_riesgo`  (
  `requestID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `file` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `userID` int NOT NULL DEFAULT 0,
  `createdOn` datetime NOT NULL DEFAULT '1980-01-01 00:00:00',
  `createdBy` int NOT NULL DEFAULT 0,
  `createdIn` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `createdAt` int NOT NULL DEFAULT 0,
  `modifiedOn` datetime NOT NULL DEFAULT '1980-01-01 00:00:00',
  `isPay` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`requestID`) USING BTREE,
  INDEX `IDX_CUSTOMER_CONSULTA_SIN_RIESGO_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CONSULTA_SIN_RIESGO_002`(`requestID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CONSULTA_SIN_RIESGO_003`(`id` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CONSULTA_SIN_RIESGO_004`(`userID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 302 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_customer_conversation
-- ----------------------------
DROP TABLE IF EXISTS `tb_customer_conversation`;
CREATE TABLE `tb_customer_conversation`  (
  `conversationID` int NOT NULL AUTO_INCREMENT,
  `entityIDSource` int NULL DEFAULT NULL,
  `entityIDTarget` int NULL DEFAULT NULL,
  `componentIDSource` int NULL DEFAULT NULL,
  `componentIDTarget` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `statusID` int NULL DEFAULT NULL,
  `messageCounter` int NULL DEFAULT NULL,
  `messgeConterNotRead` int NULL DEFAULT NULL,
  `reference1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `messageSendOn` datetime NULL DEFAULT NULL,
  `messageReceiptOn` datetime NULL DEFAULT NULL,
  `lastActivityOn` datetime NULL DEFAULT NULL,
  `lastMessage` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `isActive` smallint NULL DEFAULT NULL,
  PRIMARY KEY (`conversationID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_customer_credit
-- ----------------------------
DROP TABLE IF EXISTS `tb_customer_credit`;
CREATE TABLE `tb_customer_credit`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `entityID` int NOT NULL DEFAULT 0,
  `limitCreditDol` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `balanceDol` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `incomeDol` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `customerCreditID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`customerCreditID`) USING BTREE,
  INDEX `IDX_CUSTOMER_CREIDT_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREIDT_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREIDT_003`(`entityID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 185 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_customer_credit_amoritization
-- ----------------------------
DROP TABLE IF EXISTS `tb_customer_credit_amoritization`;
CREATE TABLE `tb_customer_credit_amoritization`  (
  `creditAmortizationID` int NOT NULL AUTO_INCREMENT,
  `customerCreditDocumentID` int NOT NULL DEFAULT 0,
  `dateApply` date NOT NULL DEFAULT '1980-01-01',
  `balanceStart` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `interest` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `capital` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `share` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `balanceEnd` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `remaining` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `shareCapital` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `dayDelay` int NOT NULL DEFAULT 0,
  `note` varchar(350) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `statusID` int NOT NULL DEFAULT 0,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  `sequence` int NULL DEFAULT 0,
  PRIMARY KEY (`creditAmortizationID`) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_AMORITIZATION_001`(`customerCreditDocumentID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_AMORITIZATION_002`(`dateApply` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_AMORITIZATION_003`(`statusID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2128 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_customer_credit_clasification
-- ----------------------------
DROP TABLE IF EXISTS `tb_customer_credit_clasification`;
CREATE TABLE `tb_customer_credit_clasification`  (
  `clasificationID` int NOT NULL AUTO_INCREMENT,
  `entityID` int NOT NULL DEFAULT 0,
  `dateHistory` date NOT NULL DEFAULT '1980-01-01',
  `numberShareLate` int NOT NULL DEFAULT 0,
  `amountCapitalLate` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  `amountInterestLate` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  `maxDayMora` int NOT NULL DEFAULT 0,
  `numberCreditAbiertos` int NOT NULL DEFAULT 0,
  `numberCreditSaneados` int NOT NULL DEFAULT 0,
  `numberCreditCancelados` int NOT NULL DEFAULT 0,
  `amountCapitalAbierto` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  `amountCapitalSaneado` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  `amountCapitalCancelado` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  `summary` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`clasificationID`) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_CLASIFICATION_001`(`entityID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 654 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_customer_credit_document
-- ----------------------------
DROP TABLE IF EXISTS `tb_customer_credit_document`;
CREATE TABLE `tb_customer_credit_document`  (
  `customerCreditDocumentID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `entityID` int NOT NULL DEFAULT 0,
  `customerCreditLineID` int NOT NULL DEFAULT 0,
  `documentNumber` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `dateOn` date NOT NULL DEFAULT '1980-01-01',
  `amount` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `interes` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `term` int NOT NULL DEFAULT 0,
  `balance` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `balanceProvicioned` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `exchangeRate` decimal(18, 4) NOT NULL DEFAULT 1.0000,
  `currencyID` int NOT NULL DEFAULT 1,
  `reference1` varchar(4500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference3` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `statusID` int NOT NULL DEFAULT 0,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  `typeAmortization` int NOT NULL DEFAULT 0,
  `periodPay` int NOT NULL DEFAULT 0,
  `providerIDCredit` int NOT NULL DEFAULT 0,
  `reportSinRiesgo` int NOT NULL DEFAULT 1,
  PRIMARY KEY (`customerCreditDocumentID`) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_DOCUMENT_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_DOCUMENT_002`(`entityID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_DOCUMENT_003`(`customerCreditLineID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_DOCUMENT_004`(`documentNumber` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_DOCUMENT_005`(`currencyID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_DOCUMENT_006`(`typeAmortization` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 120 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_customer_credit_document_entity_related
-- ----------------------------
DROP TABLE IF EXISTS `tb_customer_credit_document_entity_related`;
CREATE TABLE `tb_customer_credit_document_entity_related`  (
  `ccEntityRelatedID` int NOT NULL AUTO_INCREMENT,
  `customerCreditDocumentID` int NOT NULL DEFAULT 0,
  `entityID` int NOT NULL DEFAULT 0,
  `type` int NOT NULL DEFAULT 0 COMMENT 'Permite saber el tipo de obligacion, DEUDOR O FIADOR',
  `typeCredit` int NOT NULL DEFAULT 4 COMMENT 'Tipo de Credito, Consumo, Vivienda',
  `statusCredit` int NOT NULL DEFAULT 1 COMMENT 'Estado del Credito , Saneado, Vigente, etc',
  `typeGarantia` int NOT NULL DEFAULT 4 COMMENT 'Aval, Fiduciario, Pagare, etc',
  `typeRecuperation` int NOT NULL DEFAULT 1 COMMENT 'Forma de Recuperacion Recuperacion Normal, Arreglo de pago, Cobro Extra judicial',
  `ratioDesembolso` decimal(10, 8) NOT NULL DEFAULT 0.00000000 COMMENT 'Para reportar a la sin riesgo se multiplica este valor por el desembolso',
  `ratioBalance` decimal(10, 8) NOT NULL DEFAULT 0.00000000 COMMENT 'Para reportar a la sin riresgo se multiplica este valor po rel Saldo',
  `ratioBalanceExpired` decimal(10, 8) NOT NULL DEFAULT 0.00000000 COMMENT 'Para reportar a la sin riesgo saldo vencido',
  `ratioShare` decimal(10, 8) NOT NULL DEFAULT 0.00000000 COMMENT 'Para reportar a la sin riego se multiplica este valor por la cuota',
  `createdOn` datetime NOT NULL DEFAULT current_timestamp,
  `createdBy` int NOT NULL DEFAULT 0,
  `createdIn` varchar(120) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `createdAt` int NOT NULL DEFAULT 0,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`ccEntityRelatedID`) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_DOCUMENT_ENTITY_RELATED_001`(`customerCreditDocumentID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_DOCUMENT_ENTITY_RELATED_002`(`entityID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 951 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_customer_credit_external_sharon
-- ----------------------------
DROP TABLE IF EXISTS `tb_customer_credit_external_sharon`;
CREATE TABLE `tb_customer_credit_external_sharon`  (
  `customerID` int NOT NULL,
  `companyID` int NULL DEFAULT NULL,
  `TIPO_DE_ENTIDAD` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `NUMERO_CORRELATIVO` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `FECHA_DE_REPORTE` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `DEPARTAMENTO` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `NUMERO_DE_CEDULA_O_RUC` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `NOMBRE_DE_PERSONA` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `TIPO_DE_CREDITO` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `FECHA_DE_DESEMBOLSO` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `TIPO_DE_OBLIGACION` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `MONTO_AUTORIZADO` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `PLAZO` int NULL DEFAULT NULL,
  `FRECUENCIA_DE_PAGO` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `SALDO_DEUDA` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `ESTADO` varchar(450) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `MONTO_VENCIDO` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `ANTIGUEDAD_DE_MORA` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `TIPO_DE_GARANTIA` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `FORMA_DE_RECUPERACION` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `NUMERO_DE_CREDITO` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `VALOR_DE_LA_CUOTA` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `isActive` bit(1) NOT NULL DEFAULT b'1',
  `createdOn` date NOT NULL
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_customer_credit_external_sharon_tmp
-- ----------------------------
DROP TABLE IF EXISTS `tb_customer_credit_external_sharon_tmp`;
CREATE TABLE `tb_customer_credit_external_sharon_tmp`  (
  `companyName` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `customerID` int NOT NULL,
  `dateCredit` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `documentNumber` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `customerName` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `customerIdentification` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `customerPhone` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `amountAurotize` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `plazo` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `formPay` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `amountShare` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `amountBalance` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `dayMora` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `address` varchar(450) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `isActive` bit(1) NOT NULL DEFAULT b'1',
  `createdOn` date NOT NULL
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_customer_credit_line
-- ----------------------------
DROP TABLE IF EXISTS `tb_customer_credit_line`;
CREATE TABLE `tb_customer_credit_line`  (
  `customerCreditLineID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `entityID` int NOT NULL DEFAULT 0,
  `creditLineID` int NOT NULL DEFAULT 0,
  `accountNumber` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `currencyID` int NOT NULL DEFAULT 0,
  `typeAmortization` int NOT NULL DEFAULT 0,
  `limitCredit` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `balance` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `interestYear` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `interestPay` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `totalPay` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `totalDefeated` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `dateOpen` date NOT NULL DEFAULT '1980-01-01',
  `periodPay` int NOT NULL DEFAULT 0,
  `dateLastPay` date NULL DEFAULT NULL,
  `term` int NULL DEFAULT NULL,
  `note` varchar(550) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `statusID` int NOT NULL DEFAULT 0,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  `dayExcluded` int NULL DEFAULT NULL,
  PRIMARY KEY (`customerCreditLineID`) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_LINE_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_LINE_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_LINE_003`(`entityID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_LINE_004`(`creditLineID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_LINE_005`(`accountNumber` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_LINE_006`(`currencyID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_LINE_007`(`typeAmortization` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_LINE_008`(`statusID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_CREDIT_LINE_009`(`periodPay` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 540 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_customer_frecuency_actuations
-- ----------------------------
DROP TABLE IF EXISTS `tb_customer_frecuency_actuations`;
CREATE TABLE `tb_customer_frecuency_actuations`  (
  `customerFrecuencyActuations` int NOT NULL AUTO_INCREMENT,
  `entityID` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `situationID` int NULL DEFAULT NULL,
  `frecuencyContactID` int NULL DEFAULT NULL,
  `isActive` int NULL DEFAULT NULL,
  `isApply` int NULL DEFAULT NULL,
  PRIMARY KEY (`customerFrecuencyActuations`) USING BTREE,
  INDEX `IDX_CUSTOMER_FRECUENCY_ACTION_001`(`customerFrecuencyActuations` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_FRECUENCY_ACTION_002`(`entityID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_FRECUENCY_ACTION_003`(`situationID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_FRECUENCY_ACTION_004`(`frecuencyContactID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_FRECUENCY_ACTION_005`(`createdOn` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_customer_payment_method
-- ----------------------------
DROP TABLE IF EXISTS `tb_customer_payment_method`;
CREATE TABLE `tb_customer_payment_method`  (
  `customerPaymentMethod` int NOT NULL AUTO_INCREMENT,
  `entityID` int NULL DEFAULT NULL,
  `statusID` int NULL DEFAULT NULL,
  `isActive` bit(1) NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `expirationDate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `cvc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `typeId` int NULL DEFAULT NULL,
  PRIMARY KEY (`customerPaymentMethod`) USING BTREE,
  INDEX `IDX_CUSTOMER_PAYMENT_METHOD_001`(`customerPaymentMethod` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_PAYMENT_METHOD_002`(`entityID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_PAYMENT_METHOD_003`(`statusID` ASC) USING BTREE,
  INDEX `IDX_CUSTOMER_PAYMENT_METHOD_004`(`typeId` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 42 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_dataview
-- ----------------------------
DROP TABLE IF EXISTS `tb_dataview`;
CREATE TABLE `tb_dataview`  (
  `dataViewID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `sqlScript` varchar(5000) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `visibleColumns` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `nonVisibleColumns` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `callerID` int NOT NULL DEFAULT 0,
  `componentID` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`dataViewID`) USING BTREE,
  INDEX `IDX_DATAVIEW_001`(`callerID` ASC) USING BTREE,
  INDEX `IDX_DATAVIEW_002`(`componentID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 263 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_element
-- ----------------------------
DROP TABLE IF EXISTS `tb_element`;
CREATE TABLE `tb_element`  (
  `elementID` int NOT NULL AUTO_INCREMENT,
  `elementTypeID` int NOT NULL DEFAULT 0,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `columnAutoIncrement` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`elementID`) USING BTREE,
  INDEX `IDX_ELEMENT_001`(`elementTypeID` ASC) USING BTREE,
  INDEX `IDX_ELEMENT_002`(`elementTypeID` ASC, `name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 415 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_element_type
-- ----------------------------
DROP TABLE IF EXISTS `tb_element_type`;
CREATE TABLE `tb_element_type`  (
  `elementTypeID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`elementTypeID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_employee
-- ----------------------------
DROP TABLE IF EXISTS `tb_employee`;
CREATE TABLE `tb_employee`  (
  `employeeID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `entityID` int NOT NULL DEFAULT 0,
  `employeNumber` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `numberIdentification` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `identificationTypeID` int NULL DEFAULT NULL,
  `socialSecurityNumber` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `address` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `countryID` int NULL DEFAULT NULL,
  `stateID` int NULL DEFAULT NULL,
  `cityID` int NULL DEFAULT NULL,
  `departamentID` int NULL DEFAULT NULL,
  `areaID` int NULL DEFAULT NULL,
  `clasificationID` int NULL DEFAULT NULL,
  `categoryID` int NULL DEFAULT NULL,
  `reference1` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `typeEmployeeID` int NULL DEFAULT NULL,
  `hourCost` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `comissionPorcentage` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `parentEmployeeID` int NULL DEFAULT NULL,
  `startOn` date NULL DEFAULT NULL,
  `endOn` date NULL DEFAULT NULL,
  `statusID` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `createdIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `createdAt` int NULL DEFAULT NULL,
  `createdBy` int NULL DEFAULT NULL,
  `isActive` bit(1) NULL DEFAULT NULL,
  `vacationBalanceDay` int NULL DEFAULT NULL,
  `amountSaving` decimal(10, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`employeeID`) USING BTREE,
  INDEX `IDX_EMPLOYEE_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_003`(`entityID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_004`(`employeNumber` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_005`(`numberIdentification` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_006`(`identificationTypeID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_007`(`countryID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_008`(`stateID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_009`(`departamentID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_010`(`areaID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_011`(`clasificationID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_012`(`categoryID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_013`(`typeEmployeeID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_014`(`parentEmployeeID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_employee_calendar_pay
-- ----------------------------
DROP TABLE IF EXISTS `tb_employee_calendar_pay`;
CREATE TABLE `tb_employee_calendar_pay`  (
  `calendarID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `accountingCycleID` int NOT NULL DEFAULT 0,
  `name` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `number` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `typeID` int NOT NULL DEFAULT 0,
  `currencyID` int NOT NULL DEFAULT 0,
  `statusID` int NOT NULL DEFAULT 0,
  `description` varchar(1500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `createdBy` int NOT NULL DEFAULT 0,
  `createdAt` int NOT NULL DEFAULT 0,
  `createdOn` datetime NOT NULL DEFAULT '1980-01-01 00:00:00',
  `createdIn` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `weekID` int NULL DEFAULT NULL,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`calendarID`) USING BTREE,
  INDEX `IDX_EMPLOYEE_CALENDAR_PAY_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_CALENDAR_PAY_002`(`accountingCycleID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_CALENDAR_PAY_003`(`typeID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_CALENDAR_PAY_004`(`currencyID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_CALENDAR_PAY_005`(`statusID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 52 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_employee_calendar_pay_detail
-- ----------------------------
DROP TABLE IF EXISTS `tb_employee_calendar_pay_detail`;
CREATE TABLE `tb_employee_calendar_pay_detail`  (
  `calendarDetailID` int NOT NULL AUTO_INCREMENT,
  `calendarID` int NOT NULL DEFAULT 0,
  `employeeID` int NOT NULL DEFAULT 0,
  `plus_salary` decimal(18, 2) NOT NULL DEFAULT 0.00,
  `plus_commission` decimal(18, 2) NOT NULL DEFAULT 0.00,
  `plus_bonus` decimal(18, 2) NULL DEFAULT NULL,
  `minus_adelantos` decimal(18, 2) NOT NULL DEFAULT 0.00,
  `minus_deduction_for_loans` decimal(18, 2) NULL DEFAULT NULL,
  `minus_deduction_for_late_arrival` decimal(18, 2) NULL DEFAULT NULL,
  `minus_inss` decimal(18, 2) NULL DEFAULT NULL,
  `inss_patronal` decimal(18, 2) NULL DEFAULT NULL,
  `minus_ir` decimal(18, 2) NULL DEFAULT NULL,
  `saving` decimal(18, 2) NULL DEFAULT NULL,
  `equal_neto` decimal(18, 2) NOT NULL DEFAULT 0.00,
  `reference1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference3` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`calendarDetailID`) USING BTREE,
  INDEX `IDX_EMPLOYEE_CALENDAR_PAY_DETAIL_001`(`employeeID` ASC) USING BTREE,
  INDEX `IDX_EMPLOYEE_CALENDAR_PAY_DETAIL_002`(`calendarID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 67 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_entity
-- ----------------------------
DROP TABLE IF EXISTS `tb_entity`;
CREATE TABLE `tb_entity`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `entityID` int NOT NULL AUTO_INCREMENT,
  `createdAt` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `createdBy` bigint NULL DEFAULT NULL,
  `createdIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `imagenBiometric` varchar(500) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`entityID`) USING BTREE,
  INDEX `IDX_ENTITY_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_003`(`entityID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 812 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_entity_account
-- ----------------------------
DROP TABLE IF EXISTS `tb_entity_account`;
CREATE TABLE `tb_entity_account`  (
  `entityAccountID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `componentID` int NOT NULL DEFAULT 0,
  `componentItemID` int NOT NULL DEFAULT 0,
  `name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `accountTypeID` int NOT NULL DEFAULT 0,
  `currencyID` int NOT NULL DEFAULT 0,
  `classID` int NULL DEFAULT NULL,
  `balance` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `creditLimit` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `maxCredit` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `debitLimit` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `maxDebit` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `statusID` int NOT NULL DEFAULT 0,
  `accountID` int NULL DEFAULT NULL,
  `createdBy` int NOT NULL DEFAULT 0,
  `createdOn` datetime NOT NULL DEFAULT '1980-01-01 00:00:00',
  `createdIn` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `createdAt` int NOT NULL DEFAULT 0,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`entityAccountID`) USING BTREE,
  INDEX `IDX_ENTITY_ACCOUNT_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_ACCOUNT_002`(`componentID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_ACCOUNT_003`(`componentItemID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_ACCOUNT_004`(`accountTypeID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_ACCOUNT_005`(`currencyID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_ACCOUNT_006`(`classID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_ACCOUNT_007`(`statusID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_ACCOUNT_008`(`accountID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 460 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_entity_email
-- ----------------------------
DROP TABLE IF EXISTS `tb_entity_email`;
CREATE TABLE `tb_entity_email`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `entityID` int NOT NULL DEFAULT 0,
  `entityEmailID` bigint NOT NULL AUTO_INCREMENT,
  `email` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isPrimary` tinyint NULL DEFAULT NULL,
  PRIMARY KEY (`entityEmailID`) USING BTREE,
  INDEX `IDX_ENTITY_EMAIL_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_EMAIL_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_EMAIL_003`(`entityID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 90 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_entity_location
-- ----------------------------
DROP TABLE IF EXISTS `tb_entity_location`;
CREATE TABLE `tb_entity_location`  (
  `entityLocationID` int NOT NULL AUTO_INCREMENT,
  `entityID` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `isActive` int NULL DEFAULT NULL,
  `latituded` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `longituded` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `userName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `companyName` varchar(600) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`entityLocationID`) USING BTREE,
  INDEX `IDX_ENTITY_LOCATION_001`(`entityLocationID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_LOCATION_002`(`entityID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_LOCATION_003`(`entityID` ASC, `isActive` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_entity_phone
-- ----------------------------
DROP TABLE IF EXISTS `tb_entity_phone`;
CREATE TABLE `tb_entity_phone`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `entityID` int NOT NULL DEFAULT 0,
  `entityPhoneID` bigint NOT NULL AUTO_INCREMENT,
  `typeID` int NULL DEFAULT NULL,
  `number` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isPrimary` tinyint NULL DEFAULT NULL,
  PRIMARY KEY (`entityPhoneID`) USING BTREE,
  INDEX `IDX_ENTITY_PHONE_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_PHONE_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_PHONE_003`(`entityID` ASC) USING BTREE,
  INDEX `IDX_ENTITY_PHONE_004`(`typeID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 990 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_error
-- ----------------------------
DROP TABLE IF EXISTS `tb_error`;
CREATE TABLE `tb_error`  (
  `errorID` int NOT NULL AUTO_INCREMENT,
  `tagID` int NULL DEFAULT NULL,
  `notificated` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `message` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isActive` tinyint NULL DEFAULT NULL,
  `isRead` tinyint NULL DEFAULT NULL,
  `userID` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `readOn` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`errorID`) USING BTREE,
  INDEX `IDX_ERROR_002`(`tagID` ASC) USING BTREE,
  INDEX `IDX_ERROR_003`(`isActive` ASC, `isRead` ASC, `userID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 51336 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_estadistica_categorias
-- ----------------------------
DROP TABLE IF EXISTS `tb_estadistica_categorias`;
CREATE TABLE `tb_estadistica_categorias`  (
  `claseID` int NOT NULL DEFAULT 0,
  `categoriaID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `startValue` decimal(18, 5) NULL DEFAULT NULL,
  `endValue` decimal(18, 5) NULL DEFAULT NULL,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`categoriaID`) USING BTREE,
  INDEX `IDX_ESTADISTICA_CATEGORIA_001`(`claseID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_estadistica_clases
-- ----------------------------
DROP TABLE IF EXISTS `tb_estadistica_clases`;
CREATE TABLE `tb_estadistica_clases`  (
  `companyID` int NOT NULL DEFAULT 0,
  `claseID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `isActive` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`claseID`) USING BTREE,
  INDEX `IDX_ESTADISTICA_CLASES_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_ESTADISTICA_CLASES_002`(`claseID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_exchange_rate
-- ----------------------------
DROP TABLE IF EXISTS `tb_exchange_rate`;
CREATE TABLE `tb_exchange_rate`  (
  `currencyID` int NOT NULL DEFAULT 0,
  `companyID` int NOT NULL DEFAULT 0,
  `date` date NOT NULL DEFAULT '1980-01-01',
  `targetCurrencyID` int NOT NULL DEFAULT 0,
  `ratio` double NULL DEFAULT NULL,
  `value` decimal(19, 4) NOT NULL DEFAULT 0.0000,
  `exchangeRateID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`exchangeRateID`) USING BTREE,
  INDEX `IDX_EXCHANGE_RATE_001`(`currencyID` ASC) USING BTREE,
  INDEX `IDX_EXCHANGE_RATE_002`(`companyID` ASC) USING BTREE,
  INDEX `IDX_EXCHANGE_RATE_003`(`targetCurrencyID` ASC) USING BTREE,
  INDEX `IDX_EXCHANGE_RATE_004`(`date` ASC) USING BTREE,
  INDEX `IDX_EXCHANGE_RATE_005`(`currencyID` ASC, `companyID` ASC, `date` ASC, `targetCurrencyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21917 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_fixed_assent
-- ----------------------------
DROP TABLE IF EXISTS `tb_fixed_assent`;
CREATE TABLE `tb_fixed_assent`  (
  `companyID` int NULL DEFAULT NULL,
  `branchID` int NULL DEFAULT NULL,
  `fixedAssentID` int NOT NULL AUTO_INCREMENT,
  `fixedAssentCode` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `modelNumber` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `marca` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `colorID` int NULL DEFAULT NULL,
  `chasisNumber` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference1` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `year` int NULL DEFAULT NULL,
  `asignedEmployeeID` int NULL DEFAULT NULL,
  `categoryID` int NULL DEFAULT NULL,
  `typeID` int NULL DEFAULT NULL,
  `typeDepresiationID` int NULL DEFAULT NULL,
  `yearOfUtility` int NULL DEFAULT NULL,
  `priceStart` decimal(28, 8) NULL DEFAULT NULL,
  `isForaneo` tinyint(1) NULL DEFAULT 0,
  `statusID` int NOT NULL DEFAULT 0,
  `createdIn` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `createdOn` datetime NOT NULL DEFAULT current_timestamp,
  `createdAt` int NOT NULL DEFAULT 0,
  `createdBy` int NOT NULL DEFAULT 0,
  `countryID` int NULL DEFAULT 0,
  `cityID` int NULL DEFAULT 0,
  `municipalityID` int NULL DEFAULT 0,
  `address` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `areaID` int NULL DEFAULT NULL,
  `projectID` int NULL DEFAULT NULL,
  `duration` int NULL DEFAULT NULL,
  `typeFixedAssentID` int NULL DEFAULT NULL,
  `startOn` datetime NULL DEFAULT NULL,
  `ratio` decimal(10, 2) NULL DEFAULT NULL,
  `currencyID` int NULL DEFAULT NULL,
  `currentAmount` decimal(10, 2) NULL DEFAULT NULL,
  `settlementAmount` decimal(10, 2) NULL DEFAULT NULL,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`fixedAssentID`) USING BTREE,
  INDEX `IDX_FIDEX_ASSENT_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_FIDEX_ASSENT_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_FIDEX_ASSENT_003`(`fixedAssentCode` ASC) USING BTREE,
  INDEX `IDX_FIDEX_ASSENT_004`(`colorID` ASC) USING BTREE,
  INDEX `IDX_FIDEX_ASSENT_005`(`asignedEmployeeID` ASC) USING BTREE,
  INDEX `IDX_FIDEX_ASSENT_006`(`categoryID` ASC) USING BTREE,
  INDEX `IDX_FIDEX_ASSENT_007`(`typeID` ASC) USING BTREE,
  INDEX `IDX_FIDEX_ASSENT_008`(`typeDepresiationID` ASC) USING BTREE,
  INDEX `IDX_FIDEX_ASSENT_009`(`statusID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_indicator
-- ----------------------------
DROP TABLE IF EXISTS `tb_indicator`;
CREATE TABLE `tb_indicator`  (
  `indicadorID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `code` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `label` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT 0,
  `script` varchar(5000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `posfix` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `prefix` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  `isGroup` bit(1) NULL DEFAULT b'0',
  PRIMARY KEY (`indicadorID`) USING BTREE,
  INDEX `IDX_INDICATOR_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_INDICATOR_002`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_indicator_history
-- ----------------------------
DROP TABLE IF EXISTS `tb_indicator_history`;
CREATE TABLE `tb_indicator_history`  (
  `companyID` int NOT NULL DEFAULT 0,
  `indicatorID` int NOT NULL DEFAULT 0,
  `dateOn` date NOT NULL DEFAULT '1980-01-01',
  `value` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `indicatorHistoryID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`indicatorHistoryID`) USING BTREE,
  INDEX `IDX_INDICATOR_HISTORY_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_INDICATOR_HISTORY_002`(`indicatorID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_indicator_tmp
-- ----------------------------
DROP TABLE IF EXISTS `tb_indicator_tmp`;
CREATE TABLE `tb_indicator_tmp`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `loginID` int NOT NULL DEFAULT 0,
  `tokenID` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `indicadorID` int NOT NULL DEFAULT 0,
  `value` decimal(18, 2) NOT NULL DEFAULT 0.00,
  `indicatorTmpID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`indicatorTmpID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_item
-- ----------------------------
DROP TABLE IF EXISTS `tb_item`;
CREATE TABLE `tb_item`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `inventoryCategoryID` int NOT NULL DEFAULT 0,
  `itemID` int NOT NULL AUTO_INCREMENT,
  `familyID` int NULL DEFAULT NULL,
  `itemNumber` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `barCode` varchar(1200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `name` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `description` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `unitMeasureID` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `displayID` int NULL DEFAULT NULL,
  `capacity` int NULL DEFAULT NULL,
  `displayUnitMeasureID` int NULL DEFAULT NULL,
  `defaultWarehouseID` int NULL DEFAULT NULL,
  `quantity` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `quantityMax` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `quantityMin` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `cost` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `reference1` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference3` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `statusID` int NULL DEFAULT NULL,
  `isPerishable` tinyint(1) NULL DEFAULT NULL,
  `factorBox` decimal(18, 4) NULL DEFAULT NULL,
  `factorProgram` decimal(18, 4) NULL DEFAULT NULL,
  `createdIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `createdAt` int NULL DEFAULT NULL,
  `createdBy` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `isInvoiceQuantityZero` tinyint NULL DEFAULT 0,
  `isServices` tinyint NULL DEFAULT 0,
  `currencyID` int NOT NULL DEFAULT 1,
  `isInvoice` int NOT NULL DEFAULT 1,
  `realStateWallInCloset` tinyint NULL DEFAULT 0,
  `realStatePiscinaPrivate` tinyint NULL DEFAULT 0,
  `realStateClubPiscina` tinyint NULL DEFAULT 0,
  `realStateAceptanMascota` tinyint NULL DEFAULT 0,
  `realStateContractCorrentaje` tinyint NULL DEFAULT 0,
  `realStatePlanReference` tinyint NULL DEFAULT 0,
  `realStateLinkYoutube` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `realStateLinkPaginaWeb` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `realStateLinkPhontos` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `realStateLinkGoogleMaps` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `realStateLinkOther` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `realStateStyleKitchen` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `realStateRoomServices` tinyint NULL DEFAULT 0,
  `realStateRoomBatchServices` tinyint NULL DEFAULT 0,
  `realStateReferenceUbicacion` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `realStateReferenceZone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `realStateReferenceCondominio` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `realStateEmployerAgentID` int NULL DEFAULT NULL,
  `realStateCountryID` int NULL DEFAULT NULL,
  `realStateStateID` int NULL DEFAULT NULL,
  `realStateCityID` int NULL DEFAULT NULL,
  `modifiedOn` datetime NULL DEFAULT NULL,
  `realStateRooBatchVisit` tinyint NULL DEFAULT 0,
  `realStateGerenciaExclusive` int NULL DEFAULT 0,
  `realStatePhone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `realStateEmail` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `dateLastUse` datetime NULL DEFAULT NULL,
  `quantityInvoice` decimal(18, 4) NULL DEFAULT NULL,
  `realStateDateExpired` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`itemID`) USING BTREE,
  INDEX `IDX_ITEM_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_ITEM_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_ITEM_003`(`inventoryCategoryID` ASC) USING BTREE,
  INDEX `IDX_ITEM_004`(`familyID` ASC) USING BTREE,
  INDEX `IDX_ITEM_005`(`itemNumber` ASC) USING BTREE,
  INDEX `IDX_ITEM_006`(`barCode`(1000) ASC) USING BTREE,
  INDEX `IDX_ITEM_007`(`unitMeasureID` ASC) USING BTREE,
  INDEX `IDX_ITEM_008`(`displayID` ASC) USING BTREE,
  INDEX `IDX_ITEM_009`(`displayUnitMeasureID` ASC) USING BTREE,
  INDEX `IDX_ITEM_010`(`defaultWarehouseID` ASC) USING BTREE,
  INDEX `IDX_ITEM_011`(`statusID` ASC) USING BTREE,
  INDEX `IDX_ITEM_012`(`currencyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29142 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_item_category
-- ----------------------------
DROP TABLE IF EXISTS `tb_item_category`;
CREATE TABLE `tb_item_category`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `inventoryCategoryID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `createdIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `createdBy` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `createdAt` int NULL DEFAULT NULL,
  PRIMARY KEY (`inventoryCategoryID`) USING BTREE,
  INDEX `IDX_ITEM_CATEGORY_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_ITEM_CATEGORY_002`(`branchID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_item_config_loto
-- ----------------------------
DROP TABLE IF EXISTS `tb_item_config_loto`;
CREATE TABLE `tb_item_config_loto`  (
  `itemConfigLotoID` int NOT NULL AUTO_INCREMENT,
  `isActive` int NOT NULL DEFAULT 1,
  `maxSale` decimal(19, 2) NOT NULL DEFAULT 1.00,
  `turno1Inicio` int NOT NULL DEFAULT 0,
  `turno1Fin` int NOT NULL DEFAULT 9,
  `turno2Inicio` int NOT NULL DEFAULT 9,
  `turno2Fin` int NOT NULL DEFAULT 14,
  `turno3Inicio` int NOT NULL DEFAULT 14,
  `turno3Fin` int NOT NULL DEFAULT 22,
  `itemID` int NOT NULL DEFAULT 1,
  PRIMARY KEY (`itemConfigLotoID`) USING BTREE,
  INDEX `IDX_ITEM_CONFIG_LOTO_001`(`itemID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_item_data_sheet
-- ----------------------------
DROP TABLE IF EXISTS `tb_item_data_sheet`;
CREATE TABLE `tb_item_data_sheet`  (
  `itemDataSheetID` int NOT NULL AUTO_INCREMENT,
  `itemID` int NOT NULL DEFAULT 0,
  `version` int NOT NULL DEFAULT 0,
  `statusID` int NOT NULL DEFAULT 0,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `createdOn` datetime NOT NULL DEFAULT '1980-01-01 00:00:00',
  `createdBy` int NOT NULL DEFAULT 0,
  `createdIn` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `createdAt` int NOT NULL DEFAULT 0,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`itemDataSheetID`) USING BTREE,
  INDEX `IDX_ITEM_DATASHEET_001`(`itemID` ASC) USING BTREE,
  INDEX `IDX_ITEM_DATASHEET_002`(`statusID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_item_data_sheet_detail
-- ----------------------------
DROP TABLE IF EXISTS `tb_item_data_sheet_detail`;
CREATE TABLE `tb_item_data_sheet_detail`  (
  `itemDataSheetDetailID` int NOT NULL AUTO_INCREMENT,
  `itemDataSheetID` int NOT NULL DEFAULT 0,
  `itemID` int NOT NULL DEFAULT 0,
  `quantity` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `cost` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `relatedItemID` int NOT NULL DEFAULT 0,
  `isActive` tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (`itemDataSheetDetailID`) USING BTREE,
  INDEX `IDX_ITEM_DATASHEET_DETAIL_001`(`itemDataSheetID` ASC) USING BTREE,
  INDEX `IDX_ITEM_DATASHEET_DETAIL_002`(`itemID` ASC) USING BTREE,
  INDEX `IDX_ITEM_DATASHEET_DETAIL_003`(`relatedItemID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_item_import
-- ----------------------------
DROP TABLE IF EXISTS `tb_item_import`;
CREATE TABLE `tb_item_import`  (
  `itemNumber` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fisico` int NULL DEFAULT NULL,
  `sistema` int NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_item_sku
-- ----------------------------
DROP TABLE IF EXISTS `tb_item_sku`;
CREATE TABLE `tb_item_sku`  (
  `skuID` int NOT NULL AUTO_INCREMENT,
  `itemID` int NOT NULL,
  `catalogItemID` int NOT NULL,
  `value` decimal(10, 2) NOT NULL,
  `price` decimal(19, 8) NULL DEFAULT 0.00000000,
  `predeterminado` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`skuID`) USING BTREE,
  INDEX `IDX_WAREHOUSE_SKU_001`(`itemID` ASC) USING BTREE,
  INDEX `IDX_WAREHOUSE_SKU_002`(`catalogItemID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 45903 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_item_warehouse
-- ----------------------------
DROP TABLE IF EXISTS `tb_item_warehouse`;
CREATE TABLE `tb_item_warehouse`  (
  `itemWarehouseId` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `warehouseID` int NOT NULL DEFAULT 0,
  `itemID` int NOT NULL DEFAULT 0,
  `quantity` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `cost` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `quantityMax` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `quantityMin` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  PRIMARY KEY (`itemWarehouseId`) USING BTREE,
  INDEX `IDX_ITEM_WAREHOUSE_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_ITEM_WAREHOUSE_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_ITEM_WAREHOUSE_003`(`warehouseID` ASC) USING BTREE,
  INDEX `IDX_ITEM_WAREHOUSE_004`(`itemID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 46777 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_item_warehouse_expired
-- ----------------------------
DROP TABLE IF EXISTS `tb_item_warehouse_expired`;
CREATE TABLE `tb_item_warehouse_expired`  (
  `itemWarehouseExpiredID` int NOT NULL AUTO_INCREMENT,
  `warehouseID` int NOT NULL,
  `itemID` int NOT NULL,
  `companyID` int NOT NULL,
  `quantity` decimal(10, 2) NULL DEFAULT NULL,
  `lote` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dateExpired` datetime NOT NULL,
  PRIMARY KEY (`itemWarehouseExpiredID`) USING BTREE,
  INDEX `IDX_ITEM_WAREHOUSE_EXPIRED_001`(`warehouseID` ASC) USING BTREE,
  INDEX `IDX_ITEM_WAREHOUSE_EXPIRED_002`(`itemID` ASC) USING BTREE,
  INDEX `IDX_ITEM_WAREHOUSE_EXPIRED_003`(`companyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1358 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_journal_entry
-- ----------------------------
DROP TABLE IF EXISTS `tb_journal_entry`;
CREATE TABLE `tb_journal_entry`  (
  `journalEntryID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `journalNumber` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `journalDate` date NOT NULL DEFAULT '1980-01-01',
  `tb_exchange_rate` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  `createdOn` datetime NULL DEFAULT NULL,
  `createdIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `createdAt` int NULL DEFAULT NULL,
  `createdBy` int NULL DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 0,
  `isApplied` tinyint(1) NOT NULL DEFAULT 0,
  `titleTemplated` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'N/A',
  `isTemplated` tinyint(1) NOT NULL DEFAULT 0,
  `statusID` int NOT NULL DEFAULT 0,
  `note` varchar(550) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference1` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference3` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `debit` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `credit` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `journalTypeID` int NOT NULL DEFAULT 0,
  `currencyID` int NOT NULL DEFAULT 0,
  `accountingCycleID` int NOT NULL DEFAULT 0,
  `entryName` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `isModule` bit(1) NOT NULL DEFAULT b'0',
  `transactionMasterID` int NOT NULL DEFAULT 0,
  `transactionID` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`journalEntryID`) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_002`(`journalNumber` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_003`(`statusID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_004`(`journalTypeID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_005`(`currencyID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_006`(`accountingCycleID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_007`(`transactionMasterID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_008`(`transactionID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_journal_entry_detail
-- ----------------------------
DROP TABLE IF EXISTS `tb_journal_entry_detail`;
CREATE TABLE `tb_journal_entry_detail`  (
  `journalEntryDetailID` int NOT NULL AUTO_INCREMENT,
  `journalEntryID` int NOT NULL DEFAULT 0,
  `companyID` int NOT NULL DEFAULT 0,
  `accountID` int NOT NULL DEFAULT 0,
  `isActive` tinyint(1) NOT NULL DEFAULT 0,
  `classID` int NULL DEFAULT NULL,
  `debit` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  `credit` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  `note` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isApplied` tinyint(1) NULL DEFAULT NULL,
  `branchID` int NULL DEFAULT NULL,
  `tb_exchange_rate` decimal(18, 8) NOT NULL DEFAULT 0.00000000,
  PRIMARY KEY (`journalEntryDetailID`) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_DETAIL_001`(`journalEntryID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_DETAIL_002`(`companyID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_DETAIL_003`(`accountID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_DETAIL_004`(`classID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_DETAIL_005`(`branchID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_journal_entry_detail_summary
-- ----------------------------
DROP TABLE IF EXISTS `tb_journal_entry_detail_summary`;
CREATE TABLE `tb_journal_entry_detail_summary`  (
  `companyID` int NULL DEFAULT NULL,
  `branchID` int NULL DEFAULT NULL,
  `loginID` int NULL DEFAULT NULL,
  `tocken` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `journalEntryID` int NULL DEFAULT NULL,
  `accountID` int NULL DEFAULT NULL,
  `parentAccountID` int NULL DEFAULT NULL,
  `debit` decimal(18, 8) NULL DEFAULT NULL,
  `credit` decimal(18, 8) NULL DEFAULT NULL,
  `journalEntryDetailSummaryID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`journalEntryDetailSummaryID`) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_DETAIL_SUMMARY_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_DETAIL_SUMMARY_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_DETAIL_SUMMARY_003`(`loginID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_DETAIL_SUMMARY_004`(`journalEntryID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_DETAIL_SUMMARY_005`(`accountID` ASC) USING BTREE,
  INDEX `IDX_JOURNAL_ENTRY_DETAIL_SUMMARY_006`(`parentAccountID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_kardex
-- ----------------------------
DROP TABLE IF EXISTS `tb_kardex`;
CREATE TABLE `tb_kardex`  (
  `itemID` int NOT NULL DEFAULT 0,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `warehouseID` int NOT NULL DEFAULT 0,
  `kardexID` int NOT NULL AUTO_INCREMENT,
  `kardexCode` int NULL DEFAULT NULL,
  `kardexDate` datetime NULL DEFAULT NULL,
  `sign` int NOT NULL DEFAULT 0,
  `transactionID` int NULL DEFAULT NULL,
  `transactionMasterID` int NULL DEFAULT NULL,
  `transactionDetailID` int NULL DEFAULT NULL,
  `movementOn` datetime NOT NULL DEFAULT '1980-01-01 00:00:00',
  `oldQuantity` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `oldQuantityWarehouse` decimal(18, 4) NULL DEFAULT NULL,
  `oldCost` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `oldCostWarehouse` decimal(18, 4) NULL DEFAULT NULL,
  `transactionQuantity` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `transactionCost` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `newQuantity` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `newQuantityWarehouse` decimal(18, 4) NULL DEFAULT NULL,
  `newCost` decimal(18, 4) NOT NULL DEFAULT 0.0000,
  `newCostWarehouse` decimal(18, 4) NULL DEFAULT NULL,
  `quantityInWarehouseCurrent` decimal(19, 4) NOT NULL DEFAULT 0.0000,
  `quantityInCurrent` decimal(19, 4) NOT NULL DEFAULT 0.0000,
  PRIMARY KEY (`kardexID`) USING BTREE,
  INDEX `IDX_KARDEX_001`(`itemID` ASC) USING BTREE,
  INDEX `IDX_KARDEX_002`(`companyID` ASC) USING BTREE,
  INDEX `IDX_KARDEX_003`(`branchID` ASC) USING BTREE,
  INDEX `IDX_KARDEX_004`(`warehouseID` ASC) USING BTREE,
  INDEX `IDX_KARDEX_005`(`transactionID` ASC) USING BTREE,
  INDEX `IDX_KARDEX_006`(`transactionMasterID` ASC) USING BTREE,
  INDEX `IDX_KARDEX_007`(`transactionDetailID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2117 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_legal
-- ----------------------------
DROP TABLE IF EXISTS `tb_legal`;
CREATE TABLE `tb_legal`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `entityID` int NOT NULL DEFAULT 0,
  `comercialName` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT 'N/D',
  `legalName` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT 'N/D',
  `address` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT 'N/D',
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `legalID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`legalID`) USING BTREE,
  INDEX `IDX_LEGAL_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_LEGAL_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_LEGAL_003`(`entityID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 768 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_list_price
-- ----------------------------
DROP TABLE IF EXISTS `tb_list_price`;
CREATE TABLE `tb_list_price`  (
  `companyID` int NOT NULL DEFAULT 0,
  `listPriceID` int NOT NULL AUTO_INCREMENT,
  `startOn` datetime NOT NULL DEFAULT '1980-01-01 00:00:00',
  `endOn` datetime NULL DEFAULT NULL,
  `name` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(550) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `statusID` int NOT NULL DEFAULT 0,
  `createdOn` datetime NOT NULL DEFAULT '1980-01-01 00:00:00',
  `createdIn` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `createdBy` int NOT NULL DEFAULT 0,
  `createdAt` int NOT NULL DEFAULT 0,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`listPriceID`) USING BTREE,
  INDEX `IDX_LIST_PRICE_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_LIST_PRICE_002`(`statusID` ASC) USING BTREE,
  INDEX `IDX_LIST_PRICE_003`(`companyID` ASC, `startOn` ASC, `endOn` ASC, `isActive` ASC) USING BTREE,
  INDEX `IDX_LIST_PRICE_004`(`listPriceID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_log
-- ----------------------------
DROP TABLE IF EXISTS `tb_log`;
CREATE TABLE `tb_log`  (
  `logID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `loginID` int NOT NULL DEFAULT 0,
  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `procedureName` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `code` int NOT NULL DEFAULT 0,
  `description` varchar(1500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `createdOn` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`logID`) USING BTREE,
  INDEX `IDX_LOG_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_LOG_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_LOG_003`(`loginID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 454 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_log_messeger
-- ----------------------------
DROP TABLE IF EXISTS `tb_log_messeger`;
CREATE TABLE `tb_log_messeger`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `errno` int NOT NULL,
  `errtype` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `errstr` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `errfile` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `errline` int NOT NULL,
  `user_agent` varchar(450) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_log_session
-- ----------------------------
DROP TABLE IF EXISTS `tb_log_session`;
CREATE TABLE `tb_log_session`  (
  `session_id` varchar(40) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `userID` int NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(120) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `last_activity` varchar(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `last_update` datetime NULL DEFAULT NULL,
  `user_data` text CHARACTER SET latin1 COLLATE latin1_general_ci NULL,
  PRIMARY KEY (`session_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_master_kardex_temp
-- ----------------------------
DROP TABLE IF EXISTS `tb_master_kardex_temp`;
CREATE TABLE `tb_master_kardex_temp`  (
  `userID` int NOT NULL DEFAULT 0,
  `tokenID` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `companyID` int NOT NULL DEFAULT 0,
  `itemID` int NOT NULL DEFAULT 0,
  `itemNumber` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `itemName` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `minKardexID` int NOT NULL DEFAULT 0,
  `quantityInicial` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `costInicial` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `quantityInput` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `costInput` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `quantityOutput` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `costOutput` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `masterKardexTempID` int NOT NULL AUTO_INCREMENT,
  `itemCategoryName` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`masterKardexTempID`) USING BTREE,
  INDEX `IDX_USER`(`userID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_ITEM`(`itemID` ASC, `companyID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_ITEM_USER`(`itemID` ASC, `userID` ASC, `companyID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_USER`(`userID` ASC, `companyID` ASC) USING BTREE,
  INDEX `IDX_COMPANY_ITEM_USER_MINKARDEX`(`userID` ASC, `minKardexID` ASC, `companyID` ASC, `itemID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_membership
-- ----------------------------
DROP TABLE IF EXISTS `tb_membership`;
CREATE TABLE `tb_membership`  (
  `membershipID` int NOT NULL AUTO_INCREMENT,
  `roleID` int NOT NULL DEFAULT 0,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `userID` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`membershipID`) USING BTREE,
  INDEX `IDX_MEMBERSHIP_001`(`roleID` ASC) USING BTREE,
  INDEX `IDX_MEMBERSHIP_002`(`companyID` ASC) USING BTREE,
  INDEX `IDX_MEMBERSHIP_003`(`branchID` ASC) USING BTREE,
  INDEX `IDX_MEMBERSHIP_004`(`userID` ASC) USING BTREE,
  INDEX `IDX_MEMBERSHIP_005`(`companyID` ASC, `branchID` ASC, `userID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1953722898 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_menu_element
-- ----------------------------
DROP TABLE IF EXISTS `tb_menu_element`;
CREATE TABLE `tb_menu_element`  (
  `companyID` int NOT NULL DEFAULT 0,
  `elementID` int NOT NULL DEFAULT 0,
  `menuElementID` int NOT NULL AUTO_INCREMENT,
  `parentMenuElementID` int NULL DEFAULT NULL,
  `display` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `address` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `orden` varchar(40) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `icon` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `nivel` int NOT NULL DEFAULT 0,
  `typeMenuElementID` int NOT NULL DEFAULT 0,
  `isActive` tinyint NOT NULL DEFAULT 1,
  `iconWindowForm` varchar(1200) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `formRedirectWindowForm` varchar(500) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `typeUrlRedirect` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `description` varchar(2000) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `styleCss` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `template` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `templateSnagit` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `typeApp` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`menuElementID`) USING BTREE,
  INDEX `IDX_MENU_ELEMENT_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_MENU_ELEMENT_002`(`elementID` ASC) USING BTREE,
  INDEX `IDX_MENU_ELEMENT_003`(`parentMenuElementID` ASC) USING BTREE,
  INDEX `IDX_MENU_ELEMENT_004`(`typeMenuElementID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 299 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_naturales
-- ----------------------------
DROP TABLE IF EXISTS `tb_naturales`;
CREATE TABLE `tb_naturales`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `entityID` int NOT NULL DEFAULT 0,
  `firstName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `lastName` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `address` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `statusID` int NOT NULL DEFAULT 0 COMMENT 'Catalogo de Estado Civil',
  `profesionID` int NOT NULL DEFAULT 0 COMMENT 'Catalogo de Profesion u Oficio',
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `naturalesID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`naturalesID`) USING BTREE,
  INDEX `IDX_NATURALES_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_NATURALES_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_NATURALES_003`(`entityID` ASC) USING BTREE,
  INDEX `IDX_NATURALES_004`(`statusID` ASC) USING BTREE,
  INDEX `IDX_NATURALES_005`(`profesionID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 791 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_notification
-- ----------------------------
DROP TABLE IF EXISTS `tb_notification`;
CREATE TABLE `tb_notification`  (
  `notificationID` int NOT NULL AUTO_INCREMENT,
  `errorID` int NULL DEFAULT NULL,
  `from` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `to` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `subject` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `message` varchar(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `summary` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tagID` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `isActive` bit(1) NULL DEFAULT NULL,
  `phoneFrom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phoneTo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `programDate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `programHour` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sendOn` datetime NULL DEFAULT NULL,
  `sendEmailOn` datetime NULL DEFAULT NULL,
  `sendWhatsappOn` datetime NULL DEFAULT NULL,
  `addedCalendarGoogle` bit(1) NULL DEFAULT NULL,
  `quantityOcupation` int NULL DEFAULT 0,
  `quantityDisponible` int NULL DEFAULT 0,
  `googleCalendarEventID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `isRead` smallint NULL DEFAULT 0,
  `entityIDSource` int NULL DEFAULT NULL,
  `entityIDTarget` int NULL DEFAULT NULL,
  PRIMARY KEY (`notificationID`) USING BTREE,
  INDEX `IDX_NOTIFICATION_001`(`errorID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49356 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Tabla de Notificaciones' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_parameter
-- ----------------------------
DROP TABLE IF EXISTS `tb_parameter`;
CREATE TABLE `tb_parameter`  (
  `parameterID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `isRequiered` tinyint(1) NOT NULL DEFAULT 0,
  `isEdited` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`parameterID`) USING BTREE,
  INDEX `IDX_PARAMETER_001`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 298 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_price
-- ----------------------------
DROP TABLE IF EXISTS `tb_price`;
CREATE TABLE `tb_price`  (
  `companyID` int NOT NULL DEFAULT 0,
  `listPriceID` int NOT NULL DEFAULT 0,
  `itemID` int NOT NULL DEFAULT 0,
  `priceID` int NOT NULL AUTO_INCREMENT,
  `typePriceID` int NOT NULL DEFAULT 0,
  `percentage` decimal(19, 8) NOT NULL DEFAULT 0.00000000,
  `price` decimal(19, 8) NOT NULL DEFAULT 0.00000000,
  `percentageCommision` decimal(19, 8) NOT NULL DEFAULT 0.00000000,
  PRIMARY KEY (`priceID`) USING BTREE,
  INDEX `IDX_PRICE_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_PRICE_002`(`listPriceID` ASC) USING BTREE,
  INDEX `IDX_PRICE_003`(`itemID` ASC) USING BTREE,
  INDEX `IDX_PRICE_004`(`typePriceID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 209843 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_provider
-- ----------------------------
DROP TABLE IF EXISTS `tb_provider`;
CREATE TABLE `tb_provider`  (
  `providerID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `entityID` int NOT NULL DEFAULT 0,
  `providerNumber` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `numberIdentification` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `identificationTypeID` int NULL DEFAULT NULL,
  `providerType` int NULL DEFAULT NULL,
  `providerCategoryID` int NULL DEFAULT NULL,
  `providerClasificationID` int NULL DEFAULT NULL,
  `reference1` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `payConditionID` int NULL DEFAULT NULL,
  `isLocal` tinyint(1) NULL DEFAULT NULL,
  `countryID` int NULL DEFAULT NULL,
  `stateID` int NULL DEFAULT NULL,
  `cityID` int NULL DEFAULT NULL,
  `address` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `currencyID` int NULL DEFAULT NULL,
  `statusID` int NULL DEFAULT NULL,
  `deleveryDay` int NULL DEFAULT NULL,
  `deleveryDayReal` int NULL DEFAULT NULL,
  `distancia` decimal(18, 4) NULL DEFAULT NULL,
  `createdIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `createdBy` int NULL DEFAULT NULL,
  `createdAt` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `isActive` bit(1) NULL DEFAULT NULL,
  `balanceDol` decimal(10, 2) NULL DEFAULT 0.00,
  `balanceCor` decimal(10, 2) NULL DEFAULT 0.00,
  PRIMARY KEY (`providerID`) USING BTREE,
  INDEX `IDX_PROVIDER_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_003`(`entityID` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_004`(`providerNumber` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_005`(`identificationTypeID` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_006`(`providerType` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_007`(`providerCategoryID` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_008`(`providerClasificationID` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_009`(`payConditionID` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_010`(`countryID` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_011`(`stateID` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_012`(`cityID` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_013`(`currencyID` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_014`(`statusID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 277 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_provider_item
-- ----------------------------
DROP TABLE IF EXISTS `tb_provider_item`;
CREATE TABLE `tb_provider_item`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `entityID` int NOT NULL DEFAULT 0,
  `itemID` int NOT NULL DEFAULT 0,
  `providerItemID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`providerItemID`) USING BTREE,
  INDEX `IDX_PROVIDER_ITEM_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_ITEM_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_ITEM_003`(`entityID` ASC) USING BTREE,
  INDEX `IDX_PROVIDER_ITEM_004`(`itemID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9642 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_public_catalog
-- ----------------------------
DROP TABLE IF EXISTS `tb_public_catalog`;
CREATE TABLE `tb_public_catalog`  (
  `publicCatalogID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `systemName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `statusID` int NULL DEFAULT NULL,
  `orden` int NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `isActive` bit(1) NULL DEFAULT NULL,
  `flavorID` int NULL DEFAULT 0,
  PRIMARY KEY (`publicCatalogID`) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_001`(`statusID` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_002`(`flavorID` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_003`(`publicCatalogID` ASC, `flavorID` ASC, `isActive` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 214 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_public_catalog_detail
-- ----------------------------
DROP TABLE IF EXISTS `tb_public_catalog_detail`;
CREATE TABLE `tb_public_catalog_detail`  (
  `publicCatalogDetailID` int NOT NULL AUTO_INCREMENT,
  `publicCatalogID` int NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `display` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `flavorID` int NULL DEFAULT NULL,
  `description` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sequence` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `parentCatalogDetailID` int NULL DEFAULT NULL,
  `ratio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `parentName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `isActive` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference5` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference6` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference7` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference8` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference9` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference10` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference11` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference12` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference13` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference14` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference15` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference16` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference17` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference18` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference19` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference20` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference21` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference22` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference23` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `refecence24` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference25` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`publicCatalogDetailID`) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_001`(`publicCatalogID` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_002`(`flavorID` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_003`(`parentCatalogDetailID` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_004`(`isActive` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_005`(`reference1` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_006`(`reference2` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_007`(`reference3` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_008`(`reference4` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_009`(`reference5` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_010`(`reference6` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_011`(`reference7` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_012`(`reference8` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_013`(`reference9` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_014`(`reference10` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_015`(`reference11` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_016`(`reference12` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_017`(`reference13` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_018`(`reference14` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_019`(`reference15` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_020`(`reference16` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_021`(`reference17` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_022`(`reference18` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_023`(`reference19` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_024`(`reference20` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_025`(`reference21` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_026`(`reference22` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_027`(`reference23` ASC) USING BTREE,
  INDEX `IDX_PUBLIC_CATALOG_DETAIL_028`(`refecence24` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 772 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_razones_financieras_tmp
-- ----------------------------
DROP TABLE IF EXISTS `tb_razones_financieras_tmp`;
CREATE TABLE `tb_razones_financieras_tmp`  (
  `rzID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `loginID` int NOT NULL DEFAULT 0,
  `token` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `sequence` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `value` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `simbol` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`rzID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3530 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_relationship
-- ----------------------------
DROP TABLE IF EXISTS `tb_relationship`;
CREATE TABLE `tb_relationship`  (
  `relationshipID` int NOT NULL AUTO_INCREMENT,
  `employeeID` int NOT NULL DEFAULT 0,
  `customerID` int NOT NULL DEFAULT 0,
  `startOn` date NOT NULL DEFAULT '1980-01-01',
  `endOn` date NOT NULL DEFAULT '1980-01-01',
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  `orderNo` int NULL DEFAULT NULL,
  `reference1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference3` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `customerIDAfter` int NULL DEFAULT NULL,
  `descripcion` varchar(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference4` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference5` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`relationshipID`) USING BTREE,
  INDEX `IDX_RELATIONSHIP_001`(`employeeID` ASC) USING BTREE,
  INDEX `IDX_RELATIONSHIP_002`(`customerID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 521 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_remember
-- ----------------------------
DROP TABLE IF EXISTS `tb_remember`;
CREATE TABLE `tb_remember`  (
  `rememberID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `description` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `period` int NOT NULL DEFAULT 0,
  `day` int NOT NULL DEFAULT 0,
  `statusID` int NOT NULL DEFAULT 0,
  `lastNotificationOn` datetime NULL DEFAULT NULL,
  `isTemporal` bit(1) NOT NULL DEFAULT b'0',
  `createdBy` int NOT NULL DEFAULT 0,
  `createdOn` datetime NOT NULL DEFAULT current_timestamp,
  `createdIn` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `createdAt` int NOT NULL DEFAULT 0,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  `tagID` int NULL DEFAULT 0,
  `leerFile` int NULL DEFAULT 0,
  PRIMARY KEY (`rememberID`) USING BTREE,
  INDEX `IDX_REMEMBER_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_REMEMBER_002`(`statusID` ASC) USING BTREE,
  INDEX `IDX_REMEMBER_003`(`period` ASC) USING BTREE,
  INDEX `IDX_REMEMBER_004`(`rememberID` ASC) USING BTREE,
  INDEX `IDX_REMEMBER_005`(`tagID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 65 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_reporting
-- ----------------------------
DROP TABLE IF EXISTS `tb_reporting`;
CREATE TABLE `tb_reporting`  (
  `reportID` int NOT NULL AUTO_INCREMENT,
  `keyi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `namei` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `display` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `urlReport` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `urlReportProcess` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `ranking` int NULL DEFAULT NULL,
  `isMobile` int NULL DEFAULT NULL,
  `queryi` varchar(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `needAutenticated` int NULL DEFAULT NULL,
  `isActive` int NULL DEFAULT NULL,
  `flavorID` int NULL DEFAULT 0,
  PRIMARY KEY (`reportID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_reporting_parameter
-- ----------------------------
DROP TABLE IF EXISTS `tb_reporting_parameter`;
CREATE TABLE `tb_reporting_parameter`  (
  `reportParameterID` int NOT NULL AUTO_INCREMENT,
  `reportID` int NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `display` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `orden` int NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `isActive` int NULL DEFAULT NULL,
  `datasource` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`reportParameterID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 73 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_reporting_result
-- ----------------------------
DROP TABLE IF EXISTS `tb_reporting_result`;
CREATE TABLE `tb_reporting_result`  (
  `reportResultID` int NOT NULL AUTO_INCREMENT,
  `reportID` int NULL DEFAULT NULL,
  `resultNumber` int NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `prefix` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sumary` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reportStyle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `width` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sufix` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `isSequence` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sequence` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tableTitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tableWidth` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tableNumberColumn` int NULL DEFAULT NULL,
  `isActive` int NULL DEFAULT NULL,
  PRIMARY KEY (`reportResultID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 113 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_role
-- ----------------------------
DROP TABLE IF EXISTS `tb_role`;
CREATE TABLE `tb_role`  (
  `roleID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `isAdmin` tinyint(1) NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `urlDefault` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `createdBy` int NOT NULL DEFAULT 0,
  `typeApp` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`roleID`) USING BTREE,
  INDEX `IDX_ROLE_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_ROLE_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_ROLE_003`(`roleID` ASC, `companyID` ASC, `branchID` ASC, `isActive` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1064 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_role_autorization
-- ----------------------------
DROP TABLE IF EXISTS `tb_role_autorization`;
CREATE TABLE `tb_role_autorization`  (
  `companyID` int NOT NULL DEFAULT 0,
  `componentAutorizationID` int NOT NULL DEFAULT 0,
  `roleID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `roleAurotizationID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`roleAurotizationID`) USING BTREE,
  INDEX `IDX_ROLE_AUROTIZATION_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_ROLE_AUROTIZATION_002`(`componentAutorizationID` ASC) USING BTREE,
  INDEX `IDX_ROLE_AUROTIZATION_003`(`roleID` ASC) USING BTREE,
  INDEX `IDX_ROLE_AUROTIZATION_004`(`branchID` ASC) USING BTREE,
  INDEX `IDX_ROLE_AUROTIZATION_005`(`companyID` ASC, `componentAutorizationID` ASC) USING BTREE,
  INDEX `IDX_ROLE_AUROTIZATION_006`(`companyID` ASC, `roleID` ASC, `branchID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2336 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_subelement
-- ----------------------------
DROP TABLE IF EXISTS `tb_subelement`;
CREATE TABLE `tb_subelement`  (
  `elementID` int NOT NULL DEFAULT 0,
  `subElementID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `workflowID` int NULL DEFAULT NULL,
  `catalogID` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`subElementID`) USING BTREE,
  INDEX `IDX_SUBELEMENT_001`(`elementID` ASC) USING BTREE,
  INDEX `IDX_SUBELEMENT_002`(`workflowID` ASC) USING BTREE,
  INDEX `IDX_SUBELEMENT_003`(`catalogID` ASC) USING BTREE,
  INDEX `IDX_SUBELEMENT_004`(`elementID` ASC, `name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 340 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_tag
-- ----------------------------
DROP TABLE IF EXISTS `tb_tag`;
CREATE TABLE `tb_tag`  (
  `tagID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `description` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sendEmail` bit(1) NULL DEFAULT NULL,
  `sendNotificationApp` bit(1) NULL DEFAULT NULL,
  `sendSMS` bit(1) NULL DEFAULT NULL,
  `isActive` bit(1) NULL DEFAULT NULL,
  PRIMARY KEY (`tagID`) USING BTREE,
  INDEX `IDX_TAG_001`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci COMMENT = 'tabla para almacenar los tag de notificaciones' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction`;
CREATE TABLE `tb_transaction`  (
  `transactionID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `workflowID` int NULL DEFAULT NULL,
  `isCountable` tinyint(1) NULL DEFAULT NULL,
  `reference1` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference3` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `generateTransactionNumber` tinyint(1) NULL DEFAULT NULL,
  `decimalPlaces` int NULL DEFAULT NULL,
  `journalTypeID` int NULL DEFAULT NULL,
  `signInventory` int NULL DEFAULT NULL,
  `isRevert` tinyint(1) NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`transactionID`) USING BTREE,
  INDEX `IDX_TRANSACTION_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_002`(`workflowID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_003`(`journalTypeID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 69 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_causal
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_causal`;
CREATE TABLE `tb_transaction_causal`  (
  `companyID` int NOT NULL DEFAULT 0,
  `transactionID` int NOT NULL DEFAULT 0,
  `transactionCausalID` int NOT NULL AUTO_INCREMENT,
  `branchID` int NULL DEFAULT NULL,
  `name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `warehouseSourceID` int NULL DEFAULT NULL,
  `warehouseTargetID` int NULL DEFAULT NULL,
  `isDefault` bit(1) NOT NULL DEFAULT b'0',
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`transactionCausalID`) USING BTREE,
  INDEX `IDX_TRANSACTION_CAUSAL_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_CAUSAL_002`(`transactionID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_CAUSAL_003`(`branchID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_CAUSAL_004`(`warehouseSourceID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_CAUSAL_005`(`warehouseTargetID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 86 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_concept
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_concept`;
CREATE TABLE `tb_transaction_concept`  (
  `conceptID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `transactionID` int NOT NULL DEFAULT 0,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `orden` int NULL DEFAULT NULL,
  `sign` int NULL DEFAULT NULL,
  `visible` int NULL DEFAULT NULL,
  `base` decimal(10, 8) NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`conceptID`) USING BTREE,
  INDEX `IDX_TRANSACTION_CONCEPT_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_CONCEPT_002`(`transactionID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 57 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_master
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_master`;
CREATE TABLE `tb_transaction_master`  (
  `transactionMasterID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `transactionNumber` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `transactionID` int NOT NULL DEFAULT 0,
  `branchID` int NULL DEFAULT NULL,
  `transactionCausalID` int NULL DEFAULT NULL,
  `entityID` int NULL DEFAULT NULL,
  `transactionOn` datetime NULL DEFAULT NULL,
  `transactionOn2` datetime NULL DEFAULT NULL,
  `statusIDChangeOn` datetime NULL DEFAULT NULL,
  `componentID` int NULL DEFAULT NULL,
  `note` varchar(3500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sign` smallint NULL DEFAULT NULL,
  `currencyID` int NULL DEFAULT NULL,
  `currencyID2` int NULL DEFAULT NULL,
  `exchangeRate` decimal(18, 4) NULL DEFAULT NULL,
  `reference1` varchar(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference3` varchar(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference4` varchar(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `descriptionReference` varchar(4000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `statusID` int NULL DEFAULT NULL,
  `amount` decimal(18, 4) NULL DEFAULT NULL,
  `tax1` decimal(18, 4) NULL DEFAULT NULL,
  `tax2` decimal(18, 4) NULL DEFAULT NULL,
  `tax3` decimal(18, 4) NULL DEFAULT NULL,
  `tax4` decimal(18, 4) NULL DEFAULT NULL,
  `discount` decimal(18, 4) NULL DEFAULT NULL,
  `subAmount` decimal(18, 4) NULL DEFAULT NULL,
  `isApplied` tinyint(1) NULL DEFAULT NULL,
  `journalEntryID` int NULL DEFAULT NULL,
  `classID` int NULL DEFAULT NULL,
  `areaID` int NULL DEFAULT NULL,
  `priorityID` int NULL DEFAULT NULL,
  `sourceWarehouseID` int NULL DEFAULT NULL,
  `targetWarehouseID` int NULL DEFAULT NULL,
  `createdBy` int NULL DEFAULT NULL,
  `createdAt` int NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `createdIn` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `isTemplate` int NULL DEFAULT 0,
  `periodPay` int NULL DEFAULT NULL,
  `nextVisit` datetime NULL DEFAULT NULL,
  `numberPhone` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `notificationID` int NULL DEFAULT NULL,
  `printerQuantity` int NULL DEFAULT 0,
  `entityIDSecondary` int NULL DEFAULT NULL,
  `dayExcluded` int NULL DEFAULT NULL,
  PRIMARY KEY (`transactionMasterID`) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_002`(`transactionNumber` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_003`(`transactionID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_004`(`branchID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_005`(`transactionCausalID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_006`(`entityID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_007`(`componentID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_008`(`currencyID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_009`(`currencyID2` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_010`(`statusID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_011`(`journalEntryID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_012`(`classID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_013`(`areaID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_014`(`priorityID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_015`(`sourceWarehouseID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_016`(`targetWarehouseID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_017`(`periodPay` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_018`(`notificationID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_019`(`entityIDSecondary` ASC) USING BTREE,
  INDEX `IDX_TRANSACTOIN_MASTER_020`(`transactionMasterID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTOIN_MASTER_021`(`companyID` ASC, `transactionNumber` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2208 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_master_concept
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_master_concept`;
CREATE TABLE `tb_transaction_master_concept`  (
  `companyID` int NOT NULL DEFAULT 0,
  `transactionID` int NOT NULL DEFAULT 0,
  `transactionMasterID` int NOT NULL DEFAULT 0,
  `componentID` int NULL DEFAULT NULL,
  `componentItemID` int NULL DEFAULT NULL,
  `conceptID` int NULL DEFAULT NULL,
  `value` decimal(18, 4) NULL DEFAULT 0.0000,
  `currencyID` int NULL DEFAULT NULL,
  `exchangeRate` decimal(10, 4) NULL DEFAULT NULL,
  `transactionMasterConceptID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`transactionMasterConceptID`) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_COMCEPT_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_COMCEPT_002`(`transactionID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_COMCEPT_003`(`transactionMasterID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_COMCEPT_004`(`componentID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_COMCEPT_005`(`componentItemID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_COMCEPT_006`(`conceptID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_COMCEPT_007`(`currencyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5686 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_master_denomination
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_master_denomination`;
CREATE TABLE `tb_transaction_master_denomination`  (
  `transactionMasterDenominationID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 1,
  `transactionID` int NOT NULL DEFAULT 1,
  `transactionMasterID` int NOT NULL DEFAULT 1,
  `isActive` int NOT NULL DEFAULT 1,
  `componentID` int NOT NULL DEFAULT 1,
  `catalogItemID` int NOT NULL DEFAULT 1,
  `currencyID` int NOT NULL DEFAULT 1,
  `exchangeRate` decimal(19, 8) NOT NULL DEFAULT 1.00000000,
  `quantity` int NOT NULL DEFAULT 1,
  `ratio` decimal(19, 8) NOT NULL DEFAULT 1.00000000,
  `reference1` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `reference2` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`transactionMasterDenominationID`) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DENOMINATION_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DENOMINATION_002`(`transactionID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DENOMINATION_003`(`transactionMasterID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DENOMINATION_004`(`componentID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DENOMINATION_005`(`catalogItemID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DENOMINATION_006`(`currencyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2704 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_master_detail
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_master_detail`;
CREATE TABLE `tb_transaction_master_detail`  (
  `companyID` int NOT NULL DEFAULT 0,
  `transactionID` int NOT NULL DEFAULT 0,
  `transactionMasterID` int NOT NULL DEFAULT 0,
  `transactionMasterDetailID` int NOT NULL AUTO_INCREMENT,
  `componentID` int NULL DEFAULT NULL,
  `componentItemID` int NULL DEFAULT NULL,
  `promotionID` int NULL DEFAULT NULL,
  `amount` decimal(18, 4) NULL DEFAULT NULL,
  `cost` decimal(18, 4) NULL DEFAULT NULL,
  `quantity` decimal(18, 4) NULL DEFAULT NULL,
  `discount` decimal(18, 4) NULL DEFAULT NULL,
  `unitaryAmount` decimal(18, 4) NULL DEFAULT NULL,
  `tax1` decimal(18, 4) NULL DEFAULT NULL,
  `tax2` decimal(18, 4) NULL DEFAULT NULL,
  `tax3` decimal(18, 4) NULL DEFAULT NULL,
  `tax4` decimal(18, 4) NULL DEFAULT NULL,
  `unitaryCost` decimal(18, 4) NULL DEFAULT NULL,
  `unitaryPrice` decimal(18, 4) NULL DEFAULT NULL,
  `reference1` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference3` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference4` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference5` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference6` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference7` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `descriptionReference` varchar(800) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `exchangeRateReference` decimal(18, 8) NULL DEFAULT NULL,
  `catalogStatusID` int NULL DEFAULT NULL,
  `inventoryStatusID` int NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `quantityStock` decimal(18, 4) NULL DEFAULT NULL,
  `quantiryStockInTraffic` decimal(18, 4) NULL DEFAULT NULL,
  `quantityStockUnaswared` decimal(18, 4) NULL DEFAULT NULL,
  `remaingStock` decimal(18, 4) NULL DEFAULT NULL,
  `lote` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `expirationDate` datetime NULL DEFAULT NULL,
  `inventoryWarehouseSourceID` int NULL DEFAULT NULL,
  `inventoryWarehouseTargetID` int NULL DEFAULT NULL,
  `itemFormulatedApplied` tinyint(1) NOT NULL DEFAULT 0,
  `typePriceID` int NOT NULL DEFAULT 0,
  `skuCatalogItemID` int NOT NULL DEFAULT 0,
  `skuQuantity` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `skuQuantityBySku` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `skuFormatoDescription` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `itemNameLog` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `amountCommision` decimal(19, 8) NOT NULL DEFAULT 0.00000000,
  `itemNameDescriptionLog` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`transactionMasterDetailID`) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_002`(`transactionID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_003`(`transactionMasterID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_004`(`componentID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_005`(`componentItemID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_006`(`catalogStatusID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_007`(`inventoryStatusID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_008`(`inventoryWarehouseSourceID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_009`(`inventoryWarehouseTargetID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_010`(`typePriceID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_011`(`skuCatalogItemID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4065 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_master_detail_credit
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_master_detail_credit`;
CREATE TABLE `tb_transaction_master_detail_credit`  (
  `transactionMasterDetailCreditID` int NOT NULL AUTO_INCREMENT,
  `transactionMasterID` int NOT NULL DEFAULT 0,
  `transactionMasterDetailID` int NOT NULL DEFAULT 0,
  `capital` decimal(19, 8) NOT NULL DEFAULT 0.00000000,
  `interest` decimal(19, 8) NOT NULL DEFAULT 0.00000000,
  `dayDalay` int NOT NULL DEFAULT 0,
  `interestMora` decimal(19, 8) NOT NULL DEFAULT 0.00000000,
  `currencyID` int NOT NULL DEFAULT 0,
  `exchangeRate` decimal(19, 8) NOT NULL DEFAULT 0.00000000,
  `reference1` varchar(1500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference3` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference4` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference5` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference6` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference7` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference8` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference9` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`transactionMasterDetailCreditID`) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_CREDIT_001`(`transactionMasterID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_CREDIT_002`(`transactionMasterDetailID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_CREDIT_003`(`currencyID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1123 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_master_detail_references
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_master_detail_references`;
CREATE TABLE `tb_transaction_master_detail_references`  (
  `transactionMasterDetailID` int NOT NULL,
  `transactionMasterDetailRefereceID` int NOT NULL AUTO_INCREMENT,
  `componentID` int NULL DEFAULT NULL,
  `componentItemID` int NULL DEFAULT NULL,
  `quantity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `reference1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sales` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `isActive` smallint NULL DEFAULT NULL,
  `precio1` decimal(10, 2) NULL DEFAULT 0.00,
  `precio2` decimal(10, 2) NULL DEFAULT 0.00,
  `precio3` decimal(10, 2) NULL DEFAULT 0.00,
  `precio4` decimal(10, 2) NULL DEFAULT 0.00,
  `precio5` decimal(10, 2) NULL DEFAULT 0.00,
  `precio6` decimal(10, 2) NULL DEFAULT 0.00,
  PRIMARY KEY (`transactionMasterDetailRefereceID`) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_REFERENCE_001`(`componentItemID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_REFERENCE_002`(`transactionMasterDetailID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_REFERENCE_003`(`transactionMasterDetailRefereceID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_DETAIL_REFERENCE_004`(`transactionMasterDetailID` ASC, `componentItemID` ASC, `isActive` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 164 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_master_detail_temp
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_master_detail_temp`;
CREATE TABLE `tb_transaction_master_detail_temp`  (
  `transactionMasterDetailTemporalID` int NOT NULL AUTO_INCREMENT,
  `token` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `companyID` int NOT NULL DEFAULT 0,
  `transactionID` int NOT NULL DEFAULT 0,
  `transactionMasterID` int NOT NULL DEFAULT 0,
  `transactionMasterDetailID` int NOT NULL,
  `componentID` int NOT NULL,
  `componentItemID` int NULL DEFAULT NULL,
  `promotionID` int NULL DEFAULT NULL,
  `amount` decimal(18, 4) NULL DEFAULT NULL,
  `cost` decimal(18, 4) NULL DEFAULT NULL,
  `quantity` decimal(18, 4) NULL DEFAULT NULL,
  `discount` decimal(18, 4) NULL DEFAULT NULL,
  `unitaryAmount` decimal(18, 4) NULL DEFAULT NULL,
  `tax1` decimal(18, 4) NULL DEFAULT NULL,
  `tax2` decimal(18, 4) NULL DEFAULT NULL,
  `tax3` decimal(18, 4) NULL DEFAULT NULL,
  `tax4` decimal(18, 4) NULL DEFAULT NULL,
  `unitaryCost` decimal(18, 4) NULL DEFAULT NULL,
  `unitaryPrice` decimal(18, 4) NULL DEFAULT NULL,
  `reference1` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference3` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference4` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference5` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference6` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference7` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `descriptionReference` varchar(800) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `exchangeRateReference` decimal(18, 8) NULL DEFAULT NULL,
  `catalogStatusID` int NULL DEFAULT NULL,
  `inventoryStatusID` int NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `quantityStock` decimal(18, 4) NULL DEFAULT NULL,
  `quantiryStockInTraffic` decimal(18, 4) NULL DEFAULT NULL,
  `quantityStockUnaswared` decimal(18, 4) NULL DEFAULT NULL,
  `remaingStock` decimal(18, 4) NULL DEFAULT NULL,
  `expirationDate` datetime NULL DEFAULT NULL,
  `inventoryWarehouseSourceID` int NULL DEFAULT NULL,
  `inventoryWarehouseTargetID` int NULL DEFAULT NULL,
  `itemFormulatedApplied` tinyint(1) NOT NULL DEFAULT 0,
  `typePriceID` int NOT NULL DEFAULT 0,
  `skuCatalogItemID` int NOT NULL DEFAULT 0,
  `skuQuantity` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `skuQuantityBySku` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `skuFormatoDescription` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `itemNameLog` varchar(450) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`transactionMasterDetailTemporalID`) USING BTREE,
  INDEX `IDX_transaction_concept`(`companyID` ASC, `transactionID` ASC, `transactionMasterID` ASC, `componentID` ASC, `componentItemID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 91 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_master_info
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_master_info`;
CREATE TABLE `tb_transaction_master_info`  (
  `transactionMasterInfoID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NOT NULL DEFAULT 0,
  `transactionID` int NOT NULL DEFAULT 0,
  `transactionMasterID` int NOT NULL DEFAULT 0,
  `zoneID` int NULL DEFAULT NULL,
  `routeID` int NULL DEFAULT NULL,
  `mesaID` int NOT NULL DEFAULT 0,
  `referenceClientName` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `referenceClientIdentifier` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `changeAmount` decimal(19, 9) NOT NULL DEFAULT 0.000000000,
  `receiptAmountPoint` decimal(10, 2) NULL DEFAULT NULL,
  `receiptAmount` decimal(19, 9) NULL DEFAULT NULL,
  `receiptAmountDol` decimal(19, 5) NOT NULL DEFAULT 0.00000,
  `reference1` varchar(1500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `receiptAmountBank` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `receiptAmountBankID` int NULL DEFAULT 0,
  `receiptAmountBankReference` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `receiptAmountBankDol` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `receiptAmountBankDolID` int NULL DEFAULT 0,
  `receiptAmountBankDolReference` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `receiptAmountCard` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `receiptAmountCardBankID` int NULL DEFAULT 0,
  `receiptAmountCardBankReference` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `receiptAmountCardDol` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `receiptAmountCardBankDolID` int NULL DEFAULT 0,
  `receiptAmountCardBankDolReference` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`transactionMasterInfoID`) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_INFO_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_INFO_002`(`transactionID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_INFO_003`(`transactionMasterID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_INFO_004`(`zoneID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_INFO_005`(`routeID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_INFO_006`(`mesaID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_INFO_007`(`receiptAmountBankID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_INFO_008`(`receiptAmountBankDolID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_INFO_009`(`receiptAmountCardBankID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_INFO_010`(`receiptAmountCardBankDolID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 671 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_master_purchase
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_master_purchase`;
CREATE TABLE `tb_transaction_master_purchase`  (
  `companyID` int NOT NULL DEFAULT 0,
  `transactionID` int NOT NULL DEFAULT 0,
  `transactionMasterID` int NOT NULL DEFAULT 0,
  `purchaseTypeID` int NOT NULL DEFAULT 0,
  `transportTypeID` int NOT NULL DEFAULT 0,
  `transactionMasterPurchaseID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`transactionMasterPurchaseID`) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_PURCHASE_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_PURCHASE_002`(`transactionID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_PURCHASE_003`(`transactionMasterID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_PURCHASE_004`(`purchaseTypeID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_PURCHASE_005`(`transportTypeID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_master_references
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_master_references`;
CREATE TABLE `tb_transaction_master_references`  (
  `transactionMasterReferenceID` int NOT NULL AUTO_INCREMENT,
  `transactionMasterID` int NULL DEFAULT NULL,
  `transactionReferenceNumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `isActive` bit(1) NULL DEFAULT NULL,
  `reference1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `refernece4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `refernece5` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference6` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference7` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference8` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `referecne9` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference10` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference11` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference12` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference13` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference14` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference15` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference16` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference17` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference18` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference19` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference20` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference21` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference22` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`transactionMasterReferenceID`) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_REFERENCE_001`(`transactionMasterID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_REFERENCE_002`(`transactionMasterReferenceID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_MASTER_REFERENCE_003`(`transactionReferenceNumber` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 213 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_master_summary_concept_tmp
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_master_summary_concept_tmp`;
CREATE TABLE `tb_transaction_master_summary_concept_tmp`  (
  `ID` int NOT NULL AUTO_INCREMENT,
  `companyID` int NULL DEFAULT NULL,
  `branchID` int NULL DEFAULT NULL,
  `loginID` int NULL DEFAULT NULL,
  `transactionID` int NULL DEFAULT NULL,
  `transactionMasterID` int NULL DEFAULT NULL,
  `transactionMasterCausalID` int NULL DEFAULT NULL,
  `journalEntryID` int NULL DEFAULT NULL,
  `currencyID` int NULL DEFAULT NULL,
  `transactionNumber` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `transactionDate` datetime NULL DEFAULT NULL,
  `exchangeRate` decimal(26, 8) NULL DEFAULT NULL,
  `conceptID` int NULL DEFAULT NULL,
  `value` decimal(26, 8) NULL DEFAULT NULL,
  `reference1` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference2` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `reference3` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `IDX_1`(`companyID` ASC, `branchID` ASC, `loginID` ASC, `transactionID` ASC) USING BTREE,
  INDEX `IDX_2`(`companyID` ASC, `branchID` ASC, `loginID` ASC, `transactionID` ASC, `transactionMasterID` ASC, `transactionMasterCausalID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27594 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_profile_detail
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_profile_detail`;
CREATE TABLE `tb_transaction_profile_detail`  (
  `companyID` int NOT NULL DEFAULT 0,
  `transactionID` int NOT NULL DEFAULT 0,
  `transactionCausalID` int NOT NULL DEFAULT 0,
  `profileDetailID` int NOT NULL AUTO_INCREMENT,
  `conceptID` int NOT NULL DEFAULT 0,
  `accountID` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `classID` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sign` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`profileDetailID`) USING BTREE,
  INDEX `IDX_TRANSACTION_PROFILE_DETAIL_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_PROFILE_DETAIL_002`(`transactionID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_PROFILE_DETAIL_003`(`transactionCausalID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_PROFILE_DETAIL_004`(`conceptID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_PROFILE_DETAIL_005`(`accountID` ASC) USING BTREE,
  INDEX `IDX_TRANSACTION_PROFILE_DETAIL_006`(`classID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 111 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_transaction_profile_detail_tmp
-- ----------------------------
DROP TABLE IF EXISTS `tb_transaction_profile_detail_tmp`;
CREATE TABLE `tb_transaction_profile_detail_tmp`  (
  `companyID` int NULL DEFAULT NULL,
  `branchID` int NULL DEFAULT NULL,
  `loginID` int NULL DEFAULT NULL,
  `transactionID` int NULL DEFAULT NULL,
  `transactionMasterID` int NULL DEFAULT NULL,
  `transactionCausalID` int NULL DEFAULT NULL,
  `conceptID` int NULL DEFAULT NULL,
  `accountID` int NULL DEFAULT NULL,
  `classID` int NULL DEFAULT NULL,
  `debit` decimal(26, 8) NULL DEFAULT NULL,
  `credit` decimal(26, 8) NULL DEFAULT NULL,
  `transactionProfileDetailTmpID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`transactionProfileDetailTmpID`) USING BTREE,
  INDEX `IDX_1`(`companyID` ASC, `branchID` ASC, `loginID` ASC, `transactionID` ASC) USING BTREE,
  INDEX `IDX_2`(`companyID` ASC, `branchID` ASC, `loginID` ASC, `transactionID` ASC, `transactionMasterID` ASC, `transactionCausalID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 157 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_type_menu_element
-- ----------------------------
DROP TABLE IF EXISTS `tb_type_menu_element`;
CREATE TABLE `tb_type_menu_element`  (
  `typeMenuElementID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`typeMenuElementID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_user
-- ----------------------------
DROP TABLE IF EXISTS `tb_user`;
CREATE TABLE `tb_user`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `userID` int NOT NULL AUTO_INCREMENT,
  `nickname` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `password` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `createdOn` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `isActive` tinyint(1) NULL DEFAULT NULL,
  `email` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `createdBy` int NOT NULL DEFAULT 0,
  `employeeID` int NOT NULL DEFAULT 0,
  `useMobile` int NOT NULL DEFAULT 0,
  `phone` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `lastPayment` datetime NULL DEFAULT NULL,
  `comercio` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `token_google_calendar` varchar(1200) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `locationID` int NULL DEFAULT 0,
  PRIMARY KEY (`userID`) USING BTREE,
  INDEX `IDX_USER_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_USER_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_USER_003`(`userID` ASC) USING BTREE,
  INDEX `IDX_USER_004`(`employeeID` ASC) USING BTREE,
  INDEX `IDX_USER_005`(`nickname` ASC, `password` ASC, `isActive` ASC) USING BTREE,
  INDEX `IDX_USER_006`(`locationID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1130 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_user_permission
-- ----------------------------
DROP TABLE IF EXISTS `tb_user_permission`;
CREATE TABLE `tb_user_permission`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `elementID` int NOT NULL DEFAULT 0,
  `roleID` int NOT NULL DEFAULT 0,
  `userPermissionID` int NOT NULL AUTO_INCREMENT,
  `selected` int NULL DEFAULT NULL,
  `inserted` int NULL DEFAULT NULL,
  `deleted` int NULL DEFAULT NULL,
  `edited` int NULL DEFAULT NULL,
  PRIMARY KEY (`userPermissionID`) USING BTREE,
  INDEX `IDX_USER_PERMISSION_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_USER_PERMISSION_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_USER_PERMISSION_003`(`elementID` ASC) USING BTREE,
  INDEX `IDX_USER_PERMISSION_004`(`roleID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 72538 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_user_tag
-- ----------------------------
DROP TABLE IF EXISTS `tb_user_tag`;
CREATE TABLE `tb_user_tag`  (
  `tagID` int NOT NULL DEFAULT 0,
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `userID` int NOT NULL DEFAULT 0,
  `userTagID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`userTagID`) USING BTREE,
  INDEX `IDX_USER_TAG_001`(`tagID` ASC) USING BTREE,
  INDEX `IDX_USER_TAG_002`(`companyID` ASC) USING BTREE,
  INDEX `IDX_USER_TAG_003`(`branchID` ASC) USING BTREE,
  INDEX `IDX_USER_TAG_004`(`userID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5928 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_user_warehouse
-- ----------------------------
DROP TABLE IF EXISTS `tb_user_warehouse`;
CREATE TABLE `tb_user_warehouse`  (
  `companyID` int NOT NULL DEFAULT 0,
  `branchID` int NOT NULL DEFAULT 0,
  `userID` int NOT NULL DEFAULT 0,
  `warehouseID` int NOT NULL DEFAULT 0,
  `userWarehouseID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`userWarehouseID`) USING BTREE,
  INDEX `IDX_USER_WAREHOUSE_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_USER_WAREHOUSE_002`(`branchID` ASC) USING BTREE,
  INDEX `IDX_USER_WAREHOUSE_003`(`userID` ASC) USING BTREE,
  INDEX `IDX_USER_WAREHOUSE_004`(`warehouseID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4203 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_warehouse
-- ----------------------------
DROP TABLE IF EXISTS `tb_warehouse`;
CREATE TABLE `tb_warehouse`  (
  `companyID` int NOT NULL DEFAULT 0,
  `warehouseID` int NOT NULL AUTO_INCREMENT,
  `branchID` int NOT NULL DEFAULT 0,
  `number` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `address` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `statusID` int NOT NULL DEFAULT 0,
  `isActive` bit(1) NOT NULL DEFAULT b'0',
  `createdBy` int NOT NULL DEFAULT 0,
  `createdOn` datetime NOT NULL DEFAULT '1980-01-01 00:00:00',
  `createdIn` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `createdAt` int NOT NULL DEFAULT 0,
  `typeWarehouse` int NOT NULL DEFAULT 0,
  `emailResponsability` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`warehouseID`) USING BTREE,
  INDEX `IDX_WAREHOUSE_001`(`companyID` ASC) USING BTREE,
  INDEX `IDX_WAREHOUSE_002`(`warehouseID` ASC) USING BTREE,
  INDEX `IDX_WAREHOUSE_003`(`branchID` ASC) USING BTREE,
  INDEX `IDX_WAREHOUSE_004`(`statusID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_workflow
-- ----------------------------
DROP TABLE IF EXISTS `tb_workflow`;
CREATE TABLE `tb_workflow`  (
  `workflowID` int NOT NULL AUTO_INCREMENT,
  `componentID` int NOT NULL DEFAULT 0,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`workflowID`) USING BTREE,
  INDEX `IDX_WORKFLOW_001`(`workflowID` ASC) USING BTREE,
  INDEX `IDX_WORKFLOW_002`(`componentID` ASC) USING BTREE,
  INDEX `IDX_WORKFLOW_003`(`workflowID` ASC, `isActive` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 86 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_workflow_stage
-- ----------------------------
DROP TABLE IF EXISTS `tb_workflow_stage`;
CREATE TABLE `tb_workflow_stage`  (
  `componentID` int NOT NULL DEFAULT 0,
  `workflowID` int NOT NULL DEFAULT 0,
  `workflowStageID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `display` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `flavorID` int NULL DEFAULT NULL,
  `editableParcial` tinyint(1) NULL DEFAULT NULL,
  `editableTotal` tinyint(1) NULL DEFAULT NULL,
  `eliminable` tinyint(1) NULL DEFAULT NULL,
  `aplicable` tinyint(1) NULL DEFAULT NULL COMMENT 'Este campo es util para saber si el documento debe de aumentar o disminuir inventario o para saver si el documento debe de ser contabilizado',
  `vinculable` tinyint(1) NULL DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 0,
  `isInit` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`workflowStageID`) USING BTREE,
  INDEX `IDX_WORKFLOW_STAGE_001`(`componentID` ASC) USING BTREE,
  INDEX `IDX_WORKFLOW_STAGE_002`(`workflowID` ASC) USING BTREE,
  INDEX `IDX_WORKFLOW_STAGE_003`(`workflowStageID` ASC) USING BTREE,
  INDEX `IDX_WORKFLOW_STAGE_004`(`workflowID` ASC, `flavorID` ASC, `isActive` ASC, `isInit` ASC) USING BTREE,
  INDEX `IDX_WORKFLOW_STAGE_005`(`workflowID` ASC, `flavorID` ASC, `aplicable` ASC, `isActive` ASC) USING BTREE,
  INDEX `IDX_WORKFLOW_STAGE_006`(`workflowStageID` ASC, `isActive` ASC) USING BTREE,
  INDEX `IDX_WORKFLOW_STAGE_007`(`workflowID` ASC, `flavorID` ASC, `isActive` ASC) USING BTREE,
  INDEX `IDX_WORKFLOW_STAGE_008`(`componentID` ASC, `workflowID` ASC, `workflowStageID` ASC, `isActive` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 207 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_workflow_stage_affect
-- ----------------------------
DROP TABLE IF EXISTS `tb_workflow_stage_affect`;
CREATE TABLE `tb_workflow_stage_affect`  (
  `workflowStageAffectID` int NOT NULL AUTO_INCREMENT,
  `transactionID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `flavorID` int NULL DEFAULT NULL,
  `transactionCausalID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `componentSourceID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `workflowSourceID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `workflowSourceStageID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `componentTargetID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `workflowTargetID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `workflowTargetStageID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `isActive` tinyint NULL DEFAULT NULL,
  `condition1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `condition2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `condition3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `reference3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`workflowStageAffectID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_workflow_stage_change_log
-- ----------------------------
DROP TABLE IF EXISTS `tb_workflow_stage_change_log`;
CREATE TABLE `tb_workflow_stage_change_log`  (
  `componentID` int NOT NULL DEFAULT 0,
  `workflowID` int NOT NULL DEFAULT 0,
  `workflowStageID` int NOT NULL DEFAULT 0,
  `workflowStageChangeLogID` int NOT NULL AUTO_INCREMENT,
  `componentItemID` int NULL DEFAULT NULL,
  `description` varchar(250) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL,
  `createdOn` datetime NULL DEFAULT NULL,
  `createdBy` int NULL DEFAULT NULL,
  PRIMARY KEY (`workflowStageChangeLogID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tb_workflow_stage_relation
-- ----------------------------
DROP TABLE IF EXISTS `tb_workflow_stage_relation`;
CREATE TABLE `tb_workflow_stage_relation`  (
  `componentID` int NOT NULL DEFAULT 0,
  `workflowID` int NOT NULL DEFAULT 0,
  `workflowStageID` int NOT NULL DEFAULT 0,
  `workflowStageTargetID` int NOT NULL DEFAULT 0,
  `workflowStageRelationID` int NOT NULL AUTO_INCREMENT,
  `necesitaAuth` tinyint(1) NULL DEFAULT NULL,
  `AuthRolID` int NULL DEFAULT NULL,
  PRIMARY KEY (`workflowStageRelationID`) USING BTREE,
  INDEX `IDX_WOFKFLOW_STAGE_RELATION_001`(`componentID` ASC) USING BTREE,
  INDEX `IDX_WOFKFLOW_STAGE_RELATION_002`(`workflowID` ASC) USING BTREE,
  INDEX `IDX_WOFKFLOW_STAGE_RELATION_003`(`workflowStageID` ASC) USING BTREE,
  INDEX `IDX_WOFKFLOW_STAGE_RELATION_004`(`workflowStageTargetID` ASC) USING BTREE,
  INDEX `IDX_WOFKFLOW_STAGE_RELATION_005`(`workflowStageRelationID` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 368 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- View structure for vw_contabilidad_comprobantes
-- ----------------------------
DROP VIEW IF EXISTS `vw_contabilidad_comprobantes`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_contabilidad_comprobantes` AS select `je`.`journalNumber` AS `CodigoComprobante`,`je`.`journalDate` AS `FechaComprobante`,`je`.`tb_exchange_rate` AS `TipoCambioComprobante`,`ws`.`name` AS `EstadoComprobante`,`je`.`debit` AS `DebitoComprobante`,`je`.`credit` AS `CrditoComprobante`,`ci`.`name` AS `TipoComprobante`,`cur`.`simbol` AS `MonedaComprobante`,`cc`.`description` AS `CentroCostoCuenta`,concat('\'',`a`.`accountNumber`) AS `CodigoCuenta`,`a`.`name` AS `NombreCuenta`,`jed`.`debit` AS `DebitoCuenta`,`jed`.`credit` AS `CreditoCuenta`,`act`.`name` AS `TipoCuenta`,`je`.`entryName` AS `BeneficiarioComprobante`,`je`.`note` AS `NotaComprobante` from (((((((`tb_journal_entry` `je` join `tb_journal_entry_detail` `jed` on(`je`.`journalEntryID` = `jed`.`journalEntryID`)) join `tb_account` `a` on(`a`.`accountID` = `jed`.`accountID`)) join `tb_account_type` `act` on(`act`.`accountTypeID` = `a`.`accountTypeID`)) join `tb_center_cost` `cc` on(`cc`.`classID` = `a`.`classID`)) join `tb_workflow_stage` `ws` on(`je`.`statusID` = `ws`.`workflowStageID`)) join `tb_catalog_item` `ci` on(`ci`.`catalogItemID` = `je`.`journalTypeID`)) join `tb_currency` `cur` on(`cur`.`currencyID` = `je`.`currencyID`)) where `je`.`isActive` = 1 and `jed`.`isActive` = 1 order by `je`.`journalDate` desc,`je`.`createdOn` desc ;

-- ----------------------------
-- View structure for vw_cxc_customer_list_real_estate
-- ----------------------------
DROP VIEW IF EXISTS `vw_cxc_customer_list_real_estate`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_cxc_customer_list_real_estate` AS SELECT
	`c`.`entityID` AS `entityID`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', `c`.`customerNumber`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`customerNumber`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`customerNumber`, '</span>' ) ELSE `c`.`customerNumber` 
END AS `Codigo`,
`c`.`dateContract` AS `Contacto`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', cast( `c`.`modifiedOn` AS DATE ), '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', cast( `c`.`modifiedOn` AS DATE ), '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', cast( `c`.`modifiedOn` AS DATE ), '</span>' ) ELSE cast( `c`.`modifiedOn` AS DATE ) 
END AS `Modificacion`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', `nat`.`firstName`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `nat`.`firstName`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `nat`.`firstName`, '</span>' ) ELSE `nat`.`firstName` 
END AS `Cliente`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', `sex`.`name`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `sex`.`name`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `sex`.`name`, '</span>' ) ELSE `sex`.`name` 
END AS `Sexo`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', ifnull(( SELECT min( `ue`.`email` ) FROM `tb_entity_email` `ue` WHERE `ue`.`entityID` = `c`.`entityID` ), '' ), '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', ifnull(( SELECT min( `ue`.`email` ) FROM `tb_entity_email` `ue` WHERE `ue`.`entityID` = `c`.`entityID` ), '' ), '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', ifnull(( SELECT min( `ue`.`email` ) FROM `tb_entity_email` `ue` WHERE `ue`.`entityID` = `c`.`entityID` ), '' ), '</span>' ) ELSE ifnull(( SELECT min( `ue`.`email` ) FROM `tb_entity_email` `ue` WHERE `ue`.`entityID` = `c`.`entityID` ), '' ) 
END AS `Email`,
CASE
		
		WHEN `ws`.`name` = 'ACTIVO' THEN
		concat( ' <span style="font-weight: bold;color:#000000" >', `ws`.`name`, '</span>' ) 
	WHEN `ws`.`name` = 'INACTIVO' THEN
	concat( ' <span style="font-weight: bold;color:red" >', `ws`.`name`, '</span>' ) 
	WHEN `ws`.`name` = 'NO CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:red" >', `ws`.`name`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `ws`.`name`, '</span>' ) 
	WHEN `ws`.`name` = 'EN CONTACTACION' THEN
	concat( ' <span style="font-weight: bold;color:#000000" >', `ws`.`name`, '</span>' ) 
	WHEN `ws`.`name` = 'SEGUIMIENTO' THEN
	concat( ' <span style="font-weight: bold;color:yellow" >', `ws`.`name`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `ws`.`name`, '</span>' ) ELSE concat( ' <span style="font-weight: bold;color:" >', `ws`.`name`, '</span>' ) 
END AS `Estado`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', `clas`.`name`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `clas`.`name`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `clas`.`name`, '</span>' ) ELSE `clas`.`name` 
END AS `Clasificacion`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', `cat`.`name`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `cat`.`name`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `cat`.`name`, '</span>' ) ELSE `cat`.`name` 
END AS `Categoria`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', `c`.`budget`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`budget`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`budget`, '</span>' ) ELSE `c`.`budget` 
END AS `Presupuesto`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', `c`.`phoneNumber`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`phoneNumber`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`phoneNumber`, '</span>' ) ELSE `c`.`phoneNumber` 
END AS `Telefono`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', `c`.`location`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`location`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`location`, '</span>' ) ELSE `c`.`location` 
END AS `Ubicacion Interes`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', ifnull( `agent`.`firstName`, 'N/D' ), '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', ifnull( `agent`.`firstName`, 'N/D' ), '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', ifnull( `agent`.`firstName`, 'N/D' ), '</span>' ) ELSE ifnull( `agent`.`firstName`, 'N/D' ) 
END AS `Agente`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', `c`.`reference1`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`reference1`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`reference1`, '</span>' ) ELSE `c`.`reference1` 
END AS `Encuentra 24`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', `c`.`reference2`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`reference2`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`reference2`, '</span>' ) ELSE `c`.`reference2` 
END AS `Mensaje`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', `c`.`reference3`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`reference3`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`reference3`, '</span>' ) ELSE `c`.`reference3` 
END AS `Comentario 1`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', `c`.`reference4`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`reference4`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`reference4`, '</span>' ) ELSE `c`.`reference4` 
END AS `Comentario 2`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', `c`.`reference5`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`reference5`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `c`.`reference5`, '</span>' ) ELSE `c`.`reference5` 
END AS `Ubicacion`,
CASE
		
		WHEN `ws`.`name` = 'NO CERRADO' THEN
		concat( ' <span style="font-weight: bold;color:red" >', `cont`.`name`, '</span>' ) 
	WHEN `ws`.`name` = 'CERRADO' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `cont`.`name`, '</span>' ) 
	WHEN `ws`.`name` = 'PROCESO DE CIERRE' THEN
	concat( ' <span style="font-weight: bold;color:#00FF00" >', `cont`.`name`, '</span>' ) ELSE `cont`.`name` 
END AS `Forma de contacto` ,

sb_cat.`name`  as `Sub Categoria` ,
sb_cat.catalogItemID as subCategoryID 

FROM
	((((((((
								    `tb_customer` `c`
								  JOIN `tb_workflow_stage` `ws` ON ( `ws`.`workflowStageID` = `c`.`statusID` ))
							  JOIN `tb_naturales` `nat` ON ( `c`.`entityID` = `nat`.`entityID` ))
						  JOIN `tb_catalog_item` `sex` ON ( `sex`.`catalogItemID` = `c`.`sexoID` ))
					  JOIN `tb_catalog_item` `clas` ON ( `clas`.`catalogItemID` = `c`.`clasificationID` ))
				  JOIN `tb_catalog_item` `cat` ON ( `cat`.`catalogItemID` = `c`.`categoryID` ))
				JOIN `tb_catalog_item` `sb_cat` ON ( `sb_cat`.`catalogItemID` = `c`.subCategoryID ))
			JOIN `tb_catalog_item` `cont` ON ( `cont`.`catalogItemID` = `c`.`formContactID` ))
	LEFT JOIN `tb_naturales` `agent` ON ( `agent`.`entityID` = `c`.`entityContactID` )) 
WHERE
	`c`.`isActive` = 1 
	AND `c`.`companyID` = 2  
ORDER BY
	`c`.`createdOn` DESC ;

-- ----------------------------
-- View structure for vw_gerencia_balance
-- ----------------------------
DROP VIEW IF EXISTS `vw_gerencia_balance`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_gerencia_balance` AS select `cco`.`description` AS `CentroCosto`,concat('\'',`a`.`accountNumber`,'\' ',`a`.`name`) AS `Cuenta`,date_format(`acc`.`endOn`,'%Y') AS `Ano`,date_format(`acc`.`endOn`,'%Y-%m') AS `Mes`,date_format(`acc`.`endOn`,'%m') AS `MesOnly`,`acb`.`balance` AS `C$saldoInicial`,if(`att`.`naturaleza` = 'D',`acb`.`balance` + (`acb`.`debit` - `acb`.`credit`),`acb`.`balance` + (`acb`.`credit` - `acb`.`debit`)) AS `C$saldoFinal`,if(`att`.`naturaleza` = 'D',`acb`.`debit` - `acb`.`credit`,`acb`.`credit` - `acb`.`debit`) AS `C$saldoMensual` from ((((`tb_account` `a` join `tb_accounting_balance` `acb` on(`a`.`accountID` = `acb`.`accountID`)) join `tb_accounting_cycle` `acc` on(`acc`.`componentCycleID` = `acb`.`componentCycleID`)) join `tb_account_type` `att` on(`att`.`accountTypeID` = `a`.`accountTypeID`)) join `tb_center_cost` `cco` on(`cco`.`classID` = `a`.`classID`)) where `acc`.`startOn` <= curdate() and `acc`.`startOn` >= curdate() - interval 72 month order by `a`.`accountNumber`,`acc`.`startOn` ;

-- ----------------------------
-- View structure for vw_gerencia_customer
-- ----------------------------
DROP VIEW IF EXISTS `vw_gerencia_customer`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_gerencia_customer` AS select `c`.`customerNumber` AS `customerNumber`,`nat`.`firstName` AS `firstName`,`c`.`identification` AS `identification`,`c`.`birthDate` AS `birthDate` from (`tb_customer` `c` join `tb_naturales` `nat` on(`c`.`entityID` = `nat`.`entityID`)) where `c`.`isActive` = 1 and `nat`.`isActive` = 1 ;

-- ----------------------------
-- View structure for vw_gerencia_desembolsos_detalle
-- ----------------------------
DROP VIEW IF EXISTS `vw_gerencia_desembolsos_detalle`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_gerencia_desembolsos_detalle` AS select `emple`.`employeNumber` AS `Colaborador`,`natemp`.`firstName` AS `NombreColaborador`,`cus`.`customerNumber` AS `Cliente`,`nat`.`firstName` AS `NombreCliente`,`ccc`.`documentNumber` AS `Factura`,`cca`.`creditAmortizationID` AS `creditAmortizationID`,`cca`.`dateApply` AS `FechaCuota`,date_format(`cca`.`dateApply`,'%Y') AS `AnoCuota`,date_format(`cca`.`dateApply`,'%Y-%m') AS `Mes1Cuota`,date_format(`cca`.`dateApply`,'%m') AS `Mes2Cuota`,round(if(`ccc`.`currencyID` = 2,`cca`.`balanceStart` * `r`.`ratio`,`cca`.`balanceStart`),2) AS `C$BalanceStartCuota`,round(if(`ccc`.`currencyID` = 2,`cca`.`interest` * `r`.`ratio`,`cca`.`interest`),2) AS `C$InteresCuota`,round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) AS `C$CapitalCuota`,round(if(`ccc`.`currencyID` = 2,`cca`.`balanceEnd` * `r`.`ratio`,`cca`.`balanceEnd`),2) AS `C$BalanceEndCuota`,round(if(`ccc`.`currencyID` = 2,`cca`.`share` * `r`.`ratio`,`cca`.`share`),2) AS `C$ShareCuota`,round(if(`ccc`.`currencyID` = 2,`cca`.`remaining` * `r`.`ratio`,`cca`.`remaining`),2) AS `C$RemainingCuota`,round(if(`ccc`.`currencyID` = 2,`cca`.`shareCapital` * `r`.`ratio`,`cca`.`shareCapital`),2) AS `C$shareCapital`,`ws2`.`name` AS `EstadoCuota`,case when `cca`.`dayDelay` > 0 then `cca`.`dayDelay` when `cca`.`dateApply` < current_timestamp() and `cca`.`remaining` > 0 then to_days(current_timestamp()) - to_days(`cca`.`dateApply`) else 0 end AS `diasAtrazoCuota`,`cur`.`name` AS `Moneda`,`ractual`.`ratio` AS `TipoCambioActual`,case when `cca`.`remaining` = 0 then round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` >= `cca`.`interest` then round(if(`ccc`.`currencyID` = 2,(`cca`.`share` - `cca`.`remaining`) * `r`.`ratio`,`cca`.`share` - `cca`.`remaining`),2) when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` < `cca`.`interest` then round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) when `cca`.`statusID` = 81 then round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) else 0 end AS `C$CapitalPagado`,round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) - case when `cca`.`remaining` = 0 then round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` >= `cca`.`interest` then round(if(`ccc`.`currencyID` = 2,(`cca`.`share` - `cca`.`remaining`) * `r`.`ratio`,`cca`.`share` - `cca`.`remaining`),2) when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` < `cca`.`interest` then round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) when `cca`.`statusID` = 81 then round(if(`ccc`.`currencyID` = 2,`cca`.`capital` * `r`.`ratio`,`cca`.`capital`),2) else 0 end AS `C$CapitalPendiente`,case when `cca`.`remaining` = 0 then round(if(`ccc`.`currencyID` = 2,`cca`.`interest` * `r`.`ratio`,`cca`.`interest`),2) when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` >= `cca`.`interest` then 0 when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` < `cca`.`interest` then round(if(`ccc`.`currencyID` = 2,(`cca`.`interest` - `cca`.`remaining`) * `r`.`ratio`,`cca`.`interest` - `cca`.`remaining`),2) when `cca`.`statusID` = 81 then round(if(`ccc`.`currencyID` = 2,`cca`.`interest` * `r`.`ratio`,`cca`.`interest`),2) else 0 end AS `C$IntaresPagado`,round(if(`ccc`.`currencyID` = 2,`cca`.`interest` * `r`.`ratio`,`cca`.`interest`),2) - case when `cca`.`remaining` = 0 then round(if(`ccc`.`currencyID` = 2,`cca`.`interest` * `r`.`ratio`,`cca`.`interest`),2) when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` >= `cca`.`interest` then 0 when `cca`.`remaining` <> `cca`.`share` and `cca`.`remaining` < `cca`.`interest` then round(if(`ccc`.`currencyID` = 2,(`cca`.`interest` - `cca`.`remaining`) * `r`.`ratio`,`cca`.`interest` - `cca`.`remaining`),2) when `cca`.`statusID` = 81 then round(if(`ccc`.`currencyID` = 2,`cca`.`interest` * `r`.`ratio`,`cca`.`interest`),2) else 0 end AS `C$InteresPendiente` from (((((((((((`tb_customer_credit_document` `ccc` join `tb_workflow_stage` `ws` on(`ccc`.`statusID` = `ws`.`workflowStageID`)) join `tb_customer` `cus` on(`ccc`.`entityID` = `cus`.`entityID`)) join `tb_naturales` `nat` on(`cus`.`entityID` = `nat`.`entityID`)) join `tb_exchange_rate` `r` on(`r`.`date` = `ccc`.`dateOn`)) join `tb_customer_credit_amoritization` `cca` on(`ccc`.`customerCreditDocumentID` = `cca`.`customerCreditDocumentID`)) join `tb_workflow_stage` `ws2` on(`cca`.`statusID` = `ws2`.`workflowStageID`)) join `tb_currency` `cur` on(`ccc`.`currencyID` = `cur`.`currencyID`)) join `tb_exchange_rate` `ractual` on(`ractual`.`date` = curdate() and `ractual`.`currencyID` = 1)) left join `tb_relationship` `rls` on(`rls`.`customerID` = `cus`.`entityID`)) left join `tb_employee` `emple` on(`rls`.`employeeID` = `emple`.`entityID`)) left join `tb_naturales` `natemp` on(`emple`.`entityID` = `natemp`.`entityID`)) where `ccc`.`isActive` = 1 and `r`.`currencyID` = 1 and `cca`.`isActive` = 1 ;

-- ----------------------------
-- View structure for vw_gerencia_desembolsos_resumen
-- ----------------------------
DROP VIEW IF EXISTS `vw_gerencia_desembolsos_resumen`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_gerencia_desembolsos_resumen` AS select `cu`.`customerNumber` AS `CodigoCliente`,`nat`.`firstName` AS `Nombre`,`cur`.`simbol` AS `Moneda`,year(current_timestamp()) - year(`cu`.`birthDate`) AS `Edad`,if(`cc`.`exchangeRate` < 1,round(`cc`.`amount` / `cc`.`exchangeRate`,2),round(`cc`.`amount`,2)) AS `C$Monto`,if(`cc`.`exchangeRate` < 1,round(`cc`.`balance` / `cc`.`exchangeRate`,2),round(`cc`.`balance`,2)) AS `C$Balance`,if(`cc`.`exchangeRate` < 1,round(`cc`.`balanceProvicioned` / `cc`.`exchangeRate`,2),round(`cc`.`balanceProvicioned`,2)) AS `C$Provisionado`,`ws`.`display` AS `Estado`,`cc`.`interes` AS `Interes`,`cc`.`term` AS `Plazo`,`cc`.`exchangeRate` AS `TipoCambio`,`cc`.`dateOn` AS `Fecha`,`cix`.`name` AS `TipoAmortizacion`,`cix2`.`name` AS `PeriodoPago`,date_format(`cc`.`dateOn`,'%Y') AS `Anio`,date_format(`cc`.`dateOn`,'%Y-%m') AS `Mes`,date_format(`cc`.`dateOn`,'%m') AS `MesUnicamente`,`cc`.`documentNumber` AS `Factura` from ((((((`tb_customer_credit_document` `cc` join `tb_naturales` `nat` on(`cc`.`entityID` = `nat`.`entityID`)) join `tb_customer` `cu` on(`nat`.`entityID` = `cu`.`entityID`)) join `tb_currency` `cur` on(`cc`.`currencyID` = `cur`.`currencyID`)) join `tb_workflow_stage` `ws` on(`ws`.`workflowStageID` = `cc`.`statusID`)) join `tb_catalog_item` `cix` on(`cix`.`catalogItemID` = `cc`.`typeAmortization`)) join `tb_catalog_item` `cix2` on(`cix2`.`catalogItemID` = `cc`.`periodPay`)) where `cc`.`isActive` = 1 ;

-- ----------------------------
-- View structure for vw_gerencia_estado_resultado_001
-- ----------------------------
DROP VIEW IF EXISTS `vw_gerencia_estado_resultado_001`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_gerencia_estado_resultado_001` AS select `c`.`Cuenta` AS `Cuenta`,`c`.`Ano` AS `Ano`,`c`.`Mes` AS `Mes`,`c`.`MesOnly` AS `MesOnly`,`c`.`C$saldoInicial` * 1 AS `C$saldoInicial`,`c`.`C$saldoFinal` * 1 AS `C$saldoFinal`,`c`.`C$saldoMensual` * 1 AS `C$saldoMensual` from `vw_gerencia_balance` `c` where `c`.`Cuenta` like '\'04-%' union select `c`.`Cuenta` AS `Cuenta`,`c`.`Ano` AS `Ano`,`c`.`Mes` AS `Mes`,`c`.`MesOnly` AS `MesOnly`,`c`.`C$saldoInicial` * -1 AS `C$saldoInicial`,`c`.`C$saldoFinal` * -1 AS `C$saldoFinal`,`c`.`C$saldoMensual` * -1 AS `C$saldoMensual` from `vw_gerencia_balance` `c` where `c`.`Cuenta` like '\'05-%' union select `c`.`Cuenta` AS `Cuenta`,`c`.`Ano` AS `Ano`,`c`.`Mes` AS `Mes`,`c`.`MesOnly` AS `MesOnly`,`c`.`C$saldoInicial` * -1 AS `C$saldoInicial`,`c`.`C$saldoFinal` * -1 AS `C$saldoFinal`,`c`.`C$saldoMensual` * -1 AS `C$saldoMensual` from `vw_gerencia_balance` `c` where `c`.`Cuenta` like '\'06-%' ;

-- ----------------------------
-- View structure for vw_gerencia_estado_resultado_002
-- ----------------------------
DROP VIEW IF EXISTS `vw_gerencia_estado_resultado_002`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_gerencia_estado_resultado_002` AS select `tx`.`Ano` AS `Ano`,`tx`.`Mes` AS `Mes`,`tx`.`MesOnly` AS `MesOnly`,sum(`tx`.`C$saldoInicial`) AS `C$saldoInicial`,sum(`tx`.`C$saldoFinal`) AS `C$saldoFinal`,sum(`tx`.`C$saldoMensual`) AS `C$saldoMensual` from `vw_gerencia_estado_resultado_001` `tx` group by `tx`.`Ano`,`tx`.`Mes`,`tx`.`MesOnly` order by `tx`.`Ano`,`tx`.`Mes` ;

-- ----------------------------
-- View structure for vw_inventory_list_item_real_estate
-- ----------------------------
DROP VIEW IF EXISTS `vw_inventory_list_item_real_estate`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_inventory_list_item_real_estate` AS select `i`.`itemID` AS `Codigo interno`,concat('<a href="[[BASE_URL]]','/app_inventory_item/edit/companyID/',`i`.`companyID`,'/itemID/',`i`.`itemID`,'" ',' target="_blank" >',`i`.`itemID`,'</a>') AS `itemID`,date_format(`i`.`createdOn`,'%Y-%m-%d') AS `createdOn`,`i`.`itemNumber` AS `Codigo`,`i`.`name` AS `Nombre`,`i`.`barCode` AS `Pagina Web Url`,concat('',`i`.`barCode`,'') AS `Pagina Web`,if(ifnull(`i`.`isPerishable`,0) = 0,'No','Si') AS `Amueblado`,`i`.`capacity` AS `Aires`,`i`.`quantityMin` AS `Niveles`,`i`.`quantityMax` AS `Hora de visita`,`i`.`factorBox` AS `Baños`,`i`.`factorProgram` AS `Habitaciones`,`cat`.`name` AS `Diseño de propiedad`,`tipo`.`name` AS `Tipo de casa`,`pro`.`name` AS `Proposito`,'Dolares' AS `Moneda`,date_format(`i`.`createdOn`,'%Y-%m-%d') AS `Fecha de enlistamiento`,date_format(`i`.`modifiedOn`,'%Y-%m-%d') AS `Fecha de actualizacion`,(select `ii`.`price` from `tb_price` `ii` where `ii`.`itemID` = `i`.`itemID` and `ii`.`typePriceID` = 154 limit 1) AS `Precio Venta`,(select `ii`.`price` from `tb_price` `ii` where `ii`.`itemID` = `i`.`itemID` and `ii`.`typePriceID` = 155 limit 1) AS `Precio Renta`,if(ifnull(`i`.`isServices`,0) = 0,'No','Si') AS `Disponible`,`i`.`reference1` AS `Area de contruccion M2`,`i`.`reference2` AS `Area de terreno V2`,`i`.`reference3` AS `ID Encuentra 24`,if(ifnull(`i`.`realStateRoomBatchServices`,0) = 0,'No','Si') AS `Baño de servicio`,if(ifnull(`i`.`realStateRooBatchVisit`,0) = 0,'No','Si') AS `Baño de visita`,if(ifnull(`i`.`realStateRoomServices`,0) = 0,'No','Si') AS `Cuarto de servicio`,if(ifnull(`i`.`realStateWallInCloset`,0) = 0,'No','Si') AS `Walk in closet`,if(ifnull(`i`.`realStatePiscinaPrivate`,0) = 0,'No','Si') AS `Piscina privada`,if(ifnull(`i`.`realStateClubPiscina`,0) = 0,'No','Si') AS `Area club con piscina`,if(ifnull(`i`.`realStateAceptanMascota`,0) = 0,'No','Si') AS `Acepta mascota`,if(ifnull(`i`.`realStateContractCorrentaje`,0) = 0,'No','Si') AS `Corretaje`,if(ifnull(`i`.`realStatePlanReference`,0) = 0,'No','Si') AS `Plan de referido`,`i`.`realStateLinkYoutube` AS `Link Youtube Url`,concat('<a href="',`i`.`realStateLinkYoutube`,'"  target="_blank" >',`i`.`realStateLinkYoutube`,'</a>') AS `Link Youtube`,`i`.`realStateLinkPaginaWeb` AS `Pagina Web Link Url`,concat('<a href="',`i`.`realStateLinkPaginaWeb`,'" target="_blank" >',`i`.`realStateLinkPaginaWeb`,'</a>') AS `Pagina Web Link`,`i`.`realStateLinkPhontos` AS `Foto Url`,concat('<a href="',`i`.`realStateLinkPhontos`,'" target="_blank" >',`i`.`realStateLinkPhontos`,'</a>') AS `Foto`,`i`.`realStateLinkGoogleMaps` AS `Google Url`,concat('<a href="',`i`.`realStateLinkGoogleMaps`,'" target="_blank" >',`i`.`realStateLinkGoogleMaps`,'</a>') AS `Google`,`i`.`realStateLinkOther` AS `Otros Link Url`,concat('<a href="',`i`.`realStateLinkOther`,'"  target="_blank" >',`i`.`realStateLinkOther`,'</a>') AS `Otros Link`,`i`.`realStateStyleKitchen` AS `Estilo de cocina`,ifnull(`nat`.`firstName`,'N/D') AS `Agente`,`i`.`realStateReferenceZone` AS `Zona`,`i`.`realStateReferenceCondominio` AS `Condominio`,`i`.`realStateReferenceUbicacion` AS `Ubicacion`,`excl`.`name` AS `Exclusividad de agente`,`country`.`name` AS `Pais`,`state`.`name` AS `Estado`,`city`.`name` AS `Ciudad`,ifnull(`i`.`realStatePhone`,'') AS `Telefono`,`i`.`isActive` AS `isActive` from (((((((((`tb_item` `i` join `tb_item_category` `cat` on(`cat`.`inventoryCategoryID` = `i`.`inventoryCategoryID`)) join `tb_catalog_item` `tipo` on(`tipo`.`catalogItemID` = `i`.`familyID`)) join `tb_catalog_item` `pro` on(`pro`.`catalogItemID` = `i`.`displayID`)) join `tb_currency` `cur` on(`cur`.`currencyID` = `i`.`currencyID`)) left join `tb_naturales` `nat` on(`nat`.`entityID` = `i`.`realStateEmployerAgentID`)) join `tb_catalog_item` `excl` on(`excl`.`catalogItemID` = `i`.`realStateGerenciaExclusive`)) join `tb_catalog_item` `country` on(`country`.`catalogItemID` = `i`.`realStateCountryID`)) join `tb_catalog_item` `state` on(`state`.`catalogItemID` = `i`.`realStateStateID`)) join `tb_catalog_item` `city` on(`city`.`catalogItemID` = `i`.`realStateCityID`)) where `i`.`companyID` = 2 order by `i`.`createdOn` desc ;

-- ----------------------------
-- View structure for vw_sales_inventory
-- ----------------------------
DROP VIEW IF EXISTS `vw_sales_inventory`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_sales_inventory` AS select `tm`.`createdOn` AS `createdOn`,extract(day from `tm`.`createdOn`) AS `createdOnDay`,`cu`.`name` AS `currency`,`t`.`name` AS `tipo`,`tc`.`name` AS `causal`,`tm`.`transactionNumber` AS `transactionNumber`,`ws`.`name` AS `statusName`,`comp`.`name` AS `companiaName`,`w`.`name` AS `warehouseName`,`cus`.`customerNumber` AS `customerNumber`,`nat`.`firstName` AS `firstName`,`i`.`itemNumber` AS `itemNumber`,`i`.`name` AS `name`,`cat`.`name` AS `categoryName`,1 / `tm`.`exchangeRate` AS `tipoCambio`,`td`.`quantity` AS `quantity`,`td`.`unitaryCost` AS `unitaryCost`,`td`.`quantity` * `td`.`unitaryCost` AS `cost`,if(`tm`.`currencyID` = 1,`td`.`unitaryAmount`,1 / `tm`.`exchangeRate` * `td`.`unitaryAmount`) AS `unitaryAmount`,if(`tm`.`currencyID` = 1,`td`.`amount`,1 / `tm`.`exchangeRate` * `td`.`amount`) AS `amount`,if(`tm`.`currencyID` = 1,`td`.`amount`,1 / `tm`.`exchangeRate` * `td`.`amount`) - `td`.`quantity` * `td`.`unitaryCost` AS `utility` from (((((((((((`tb_transaction_master` `tm` join `tb_transaction_master_detail` `td` on(`td`.`transactionMasterID` = `tm`.`transactionMasterID`)) join `tb_company` `comp` on(`comp`.`companyID` = `tm`.`companyID`)) join `tb_warehouse` `w` on(`w`.`warehouseID` = `tm`.`sourceWarehouseID`)) join `tb_workflow_stage` `ws` on(`ws`.`workflowStageID` = `tm`.`statusID`)) join `tb_currency` `cu` on(`tm`.`currencyID` = `cu`.`currencyID`)) join `tb_transaction` `t` on(`t`.`transactionID` = `tm`.`transactionID`)) join `tb_transaction_causal` `tc` on(`tm`.`transactionCausalID` = `tc`.`transactionCausalID`)) join `tb_item` `i` on(`i`.`itemID` = `td`.`componentItemID`)) join `tb_item_category` `cat` on(`cat`.`inventoryCategoryID` = `i`.`inventoryCategoryID`)) join `tb_naturales` `nat` on(`tm`.`entityID` = `nat`.`entityID`)) join `tb_customer` `cus` on(`cus`.`entityID` = `nat`.`entityID`)) where `tm`.`isActive` = 1 and `td`.`isActive` = 1 ;

-- ----------------------------
-- View structure for vw_sin_riesgo_reporte_clientes
-- ----------------------------
DROP VIEW IF EXISTS `vw_sin_riesgo_reporte_clientes`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_sin_riesgo_reporte_clientes` AS select date_format(current_timestamp(),'%d/%m/%Y') AS `FECHA REPORTE`,replace(`cus`.`identification`,'-','') AS `IDENTIFICACION`,'N' AS `TIPO DE PERSONA`,'NICARAGUENSE' AS `NACIONALIDAD`,case when `sexo`.`display` = 'FEMENINO' then 'F' else 'M' end AS `SEXO`,date_format(`cus`.`birthDate`,'%d/%m/%Y') AS `FECHA DE NACIMIENTO`,'SOL' AS `ESTADO CIVIL`,`cus`.`address` AS `DIRECCION`,'08' AS `DEPARTAMENTO`,'84' AS `MUNICIPIO`,`cus`.`address` AS `DIRECCION DE TRABAJO`,'08' AS `DEPARTAMENTO DE TRABAJO`,'84' AS `MUNICIPIO DE TRABAJO`,if((select `ph`.`number` from `tb_entity_phone` `ph` where `ph`.`entityID` = `nat`.`entityID` and `ph`.`isPrimary` = 1 limit 1) is null,'',(select `ph`.`number` from `tb_entity_phone` `ph` where `ph`.`entityID` = `nat`.`entityID` and `ph`.`isPrimary` = 1 limit 1)) AS `TELEFONO DOMICILIAR`,if((select `ph`.`number` from `tb_entity_phone` `ph` where `ph`.`entityID` = `nat`.`entityID` and `ph`.`isPrimary` = 1 limit 1) is null,'',(select `ph`.`number` from `tb_entity_phone` `ph` where `ph`.`entityID` = `nat`.`entityID` and `ph`.`isPrimary` = 1 limit 1)) AS `TELEFONO TRABAJO`,if((select `ph`.`number` from `tb_entity_phone` `ph` where `ph`.`entityID` = `nat`.`entityID` and `ph`.`isPrimary` = 1 limit 1) is null,'',(select `ph`.`number` from `tb_entity_phone` `ph` where `ph`.`entityID` = `nat`.`entityID` and `ph`.`isPrimary` = 1 limit 1)) AS `CELULAR`,'' AS `CORREO ELECTRONICO`,'COMERCIANTE' AS `OCUPACION`,'PULPERIA' AS `ACTIVIDAD ECONOMICA`,'DETALLE' AS `SECTOR` from ((`tb_naturales` `nat` join `tb_customer` `cus` on(`nat`.`entityID` = `cus`.`entityID`)) join `tb_catalog_item` `sexo` on(`cus`.`sexoID` = `sexo`.`catalogItemID`)) where `nat`.`isActive` = 1 ;

-- ----------------------------
-- View structure for vw_sin_riesgo_reporte_creditos
-- ----------------------------
DROP VIEW IF EXISTS `vw_sin_riesgo_reporte_creditos`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_sin_riesgo_reporte_creditos` AS select `cc`.`companyID` AS `companyID`,`cc`.`customerCreditDocumentID` AS `customerCreditDocumentID`,`cc`.`entityID` AS `entityID`,'03' AS `TIPO DE ENTIDAD`,'552' AS `NUMERO CORRELATIVO`,date_format(current_timestamp(),'%d/%m/%Y') AS `FECHA DE REPORTE`,'08' AS `DEPARTAMENTO`,replace(`c`.`identification`,'-','') AS `NUMERO DE CEDULA O RUC`,concat(`nat`.`firstName`,' ',convert(`nat`.`lastName` using utf8)) AS `NOMBRE DE PERSONA`,right(concat('0000',`tipocredito`.`sequence`),2) AS `TIPO DE CREDITO`,date_format(`cc`.`dateOn`,'%d/%m/%Y') AS `FECHA DE DESEMBOLSO`,right(concat('0000',`obli`.`sequence`),2) AS `TIPO DE OBLIGACION`,round(`FN_CALCULATE_EXCHANGE_RATE`(2,cast(current_timestamp() as date),`cc`.`currencyID`,1,`cc`.`amount`) * `p`.`ratioDesembolso`,2) AS `MONTO AUTORIZADO`,if(round(case when `cc`.`periodPay` = 190 then `cc`.`term` * 30 when `cc`.`periodPay` = 188 then `cc`.`term` * 7 when `cc`.`periodPay` = 189 then `cc`.`term` * 14 else 0 end / 30,0) = 0,1,round(case when `cc`.`periodPay` = 190 then `cc`.`term` * 30 when `cc`.`periodPay` = 188 then `cc`.`term` * 7 when `cc`.`periodPay` = 189 then `cc`.`term` * 14 else 0 end / 30,0)) AS `PLAZO`,case when `cc`.`periodPay` = 190 then '05' when `cc`.`periodPay` = 188 then '07' when `cc`.`periodPay` = 189 then '06' else 0 end AS `FRECUENCIA DE PAGO`,case when `cc`.`statusID` = 82 then 0 else round(`FN_CALCULATE_EXCHANGE_RATE`(2,cast(current_timestamp() as date),`cc`.`currencyID`,1,`cc`.`balance`) * `p`.`ratioBalance`,2) end AS `SALDO DEUDA`,case when `estadosinriesgo`.`sequence` = 1 then case when `ws`.`workflowStageID` not in (93,92,82) and cast(current_timestamp() as date) > (select max(`xl`.`dateApply`) from `tb_customer_credit_amoritization` `xl` where `xl`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`) then '02' when `ws`.`workflowStageID` not in (93,92,82) and cast(current_timestamp() as date) > (select min(`xl`.`dateApply`) from `tb_customer_credit_amoritization` `xl` where `xl`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID` and `xl`.`remaining` > 0) then '02' when `ws`.`workflowStageID` = 83 then 'N/D' when `ws`.`workflowStageID` = 92 then '08' when `ws`.`workflowStageID` = 82 then '03' when `ws`.`workflowStageID` = 77 then '01' else right(concat('0000',`estadosinriesgo`.`sequence`),2) end else right(concat('0000',`estadosinriesgo`.`sequence`),2) end AS `ESTADO`,round((select ifnull(round(case when `cc`.`typeAmortization` = 196 then avg(`FN_CALCULATE_EXCHANGE_RATE`(2,cast(current_timestamp() as date),`cc`.`currencyID`,1,`cx`.`balanceStart`)) else sum(`FN_CALCULATE_EXCHANGE_RATE`(2,cast(current_timestamp() as date),`cc`.`currencyID`,1,`cx`.`capital`)) end,2),0) from `tb_customer_credit_amoritization` `cx` where `cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID` and `cx`.`isActive` = 1 and `cx`.`remaining` > 0 and `cx`.`statusID` = 78 and `cx`.`dateApply` < cast(current_timestamp() as date)) * `p`.`ratioBalanceExpired`,2) AS `MONTO VENCIDO`,(select ifnull(to_days(current_timestamp()) - to_days(min(`cx`.`dateApply`)),0) from `tb_customer_credit_amoritization` `cx` where `cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID` and `cx`.`isActive` = 1 and `cx`.`remaining` > 0 and `cx`.`statusID` = 78 and `cx`.`dateApply` < cast(current_timestamp() as date)) AS `ANTIGUEDAD DE MORA`,right(concat('0000',`tipogarantia`.`sequence`),2) AS `TIPO DE GARANTIA`,case when `recuperacion`.`sequence` = 1 then case when `ws`.`workflowStageID` = 83 then '01' when `ws`.`workflowStageID` = 92 then '08' when `ws`.`workflowStageID` = 82 then '01' when `ws`.`workflowStageID` = 77 and (select ifnull(to_days(current_timestamp()) - to_days(min(`cx`.`dateApply`)),0) from `tb_customer_credit_amoritization` `cx` where `cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID` and `cx`.`isActive` = 1 and `cx`.`remaining` > 0 and `cx`.`statusID` = 78 and `cx`.`dateApply` < cast(current_timestamp() as date)) between 30 and 59 then '03' when `ws`.`workflowStageID` = 77 and (select ifnull(to_days(current_timestamp()) - to_days(min(`cx`.`dateApply`)),0) from `tb_customer_credit_amoritization` `cx` where `cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID` and `cx`.`isActive` = 1 and `cx`.`remaining` > 0 and `cx`.`statusID` = 78 and `cx`.`dateApply` < cast(current_timestamp() as date)) > 60 then '04' when `ws`.`workflowStageID` = 77 then '01' else right(concat('0000',`recuperacion`.`sequence`),2) end else right(concat('0000',`recuperacion`.`sequence`),2) end AS `FORMA DE RECUPERACION`,`cc`.`documentNumber` AS `NUMERO DE CREDITO`,round(case when `ci`.`catalogItemID` = 196 then case when `cc`.`periodPay` = 190 then `FN_CALCULATE_EXCHANGE_RATE`(2,cast(current_timestamp() as date),`cc`.`currencyID`,1,`cc`.`balance`) * (`cc`.`interes` / 12 * `cc`.`term` / 100 + 1) / `cc`.`term` when `cc`.`periodPay` = 188 then `FN_CALCULATE_EXCHANGE_RATE`(2,cast(current_timestamp() as date),`cc`.`currencyID`,1,`cc`.`balance`) * (`cc`.`interes` / 52 * `cc`.`term` / 100 + 0) / `cc`.`term` else 0 end else (select avg(`FN_CALCULATE_EXCHANGE_RATE`(2,cast(current_timestamp() as date),`cc`.`currencyID`,1,`cp`.`share`)) from `tb_customer_credit_amoritization` `cp` where `cp`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`) end * `p`.`ratioShare`,2) AS `VALOR DE LA CUOTA` from ((((((((((((`tb_customer_credit_document` `cc` join `tb_currency` `cur` on(`cc`.`currencyID` = `cur`.`currencyID`)) join `tb_workflow_stage` `ws` on(`cc`.`statusID` = `ws`.`workflowStageID`)) join `tb_catalog_item` `ci` on(`cc`.`typeAmortization` = `ci`.`catalogItemID`)) join `tb_customer_credit_document_entity_related` `p` on(`cc`.`customerCreditDocumentID` = `p`.`customerCreditDocumentID`)) join `tb_catalog_item` `obli` on(`obli`.`catalogItemID` = `p`.`type`)) join `tb_catalog_item` `tipocredito` on(`tipocredito`.`catalogItemID` = `p`.`typeCredit`)) join `tb_catalog_item` `tipogarantia` on(`tipogarantia`.`catalogItemID` = `p`.`typeGarantia`)) join `tb_catalog_item` `frepago` on(`frepago`.`catalogItemID` = `cc`.`periodPay`)) join `tb_catalog_item` `recuperacion` on(`recuperacion`.`catalogItemID` = `p`.`typeRecuperation`)) join `tb_catalog_item` `estadosinriesgo` on(`estadosinriesgo`.`catalogItemID` = `p`.`statusCredit`)) join `tb_naturales` `nat` on(`p`.`entityID` = `nat`.`entityID`)) join `tb_customer` `c` on(`nat`.`entityID` = `c`.`entityID`)) where `cc`.`isActive` = 1 and `cc`.`entityID` <> 309 and replace(`c`.`identification`,'-','') not in ('0000000000000B','0000000000000A','0000000000000C','0000000000000P','0000000000000K','2811803890004R','2912906610000G','2911206850000P','0000000000000T') and `ws`.`workflowStageID` <> 83 ;

-- ----------------------------
-- View structure for vw_sin_riesgo_reporte_creditos_to_systema
-- ----------------------------
DROP VIEW IF EXISTS `vw_sin_riesgo_reporte_creditos_to_systema`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_sin_riesgo_reporte_creditos_to_systema` AS select `i`.`companyID` AS `companyID`,`i`.`TIPO DE ENTIDAD` AS `TIPO_DE_ENTIDAD`,`i`.`NUMERO CORRELATIVO` AS `NUMERO_CORRELATIVO`,`i`.`FECHA DE REPORTE` AS `FECHA_DE_REPORTE`,`i`.`DEPARTAMENTO` AS `DEPARTAMENTO`,`i`.`NUMERO DE CEDULA O RUC` AS `NUMERO_DE_CEDULA_O_RUC`,`i`.`NOMBRE DE PERSONA` AS `NOMBRE_DE_PERSONA`,`i`.`TIPO DE CREDITO` AS `TIPO_DE_CREDITO`,`i`.`FECHA DE DESEMBOLSO` AS `FECHA_DE_DESEMBOLSO`,`i`.`TIPO DE OBLIGACION` AS `TIPO_DE_OBLIGACION`,`i`.`MONTO AUTORIZADO` AS `MONTO_AUTORIZADO`,`i`.`PLAZO` AS `PLAZO`,`i`.`FRECUENCIA DE PAGO` AS `FRECUENCIA_DE_PAGO`,`i`.`SALDO DEUDA` AS `SALDO_DEUDA`,`i`.`ESTADO` AS `ESTADO`,`i`.`MONTO VENCIDO` AS `MONTO_VENCIDO`,`i`.`ANTIGUEDAD DE MORA` AS `ANTIGUEDAD_DE_MORA`,`i`.`TIPO DE GARANTIA` AS `TIPO_DE_GARANTIA`,`i`.`FORMA DE RECUPERACION` AS `FORMA_DE_RECUPERACION`,`i`.`NUMERO DE CREDITO` AS `NUMERO_DE_CREDITO`,`i`.`VALOR DE LA CUOTA` AS `VALOR_DE_LA_CUOTA` from `vw_sin_riesgo_reporte_creditos` `i` ;

-- ----------------------------
-- View structure for vw_transaccion_master_concept_232425
-- ----------------------------
DROP VIEW IF EXISTS `vw_transaccion_master_concept_232425`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vw_transaccion_master_concept_232425` AS select `tm`.`transactionMasterID` AS `transactionMasterID`,`t`.`name` AS `Descripcion`,`tm`.`createdOn` AS `Fecha`,`tm`.`transactionNumber` AS `Documento`,`cur`.`name` AS `Moneda`,`tc`.`name` AS `Concepto`,`tmd`.`value` AS `Valor`,`comp`.`name` AS `Componente`,`td`.`componentItemID` AS `componentItemID`,`td`.`reference1` AS `Referencia1` from ((((((`tb_transaction_master` `tm` join `tb_transaction` `t` on(`tm`.`transactionID` = `t`.`transactionID`)) join `tb_transaction_master_detail` `td` on(`tm`.`transactionMasterID` = `td`.`transactionMasterID`)) join `tb_transaction_master_concept` `tmd` on(`td`.`transactionMasterID` = `tmd`.`transactionMasterID` and `td`.`componentItemID` = `tmd`.`componentItemID`)) join `tb_currency` `cur` on(`tm`.`currencyID` = `cur`.`currencyID`)) join `tb_transaction_concept` `tc` on(`tmd`.`conceptID` = `tc`.`conceptID`)) join `tb_component` `comp` on(`comp`.`componentID` = `td`.`componentID`)) where `tm`.`companyID` = 2 and `tm`.`isActive` = 1 and `tm`.`transactionID` in (23,24,25) ;

-- ----------------------------
-- Function structure for fn_calculate_exchange_rate
-- ----------------------------
DROP FUNCTION IF EXISTS `fn_calculate_exchange_rate`;
delimiter ;;
CREATE FUNCTION `fn_calculate_exchange_rate`(`prCompanyID` INT, `prDate` DATE, `prCurrencySourceID` INT, `prCurrencyTargetID` INT, `prValorToConvert` DECIMAL(21,11))
 RETURNS decimal(10,4)
BEGIN

	DECLARE currencyIDDefault INT DEFAULT 0;

	DECLARE currencyIDSource  INT DEFAULT 0;

	DECLARE currencyIDTarget  INT DEFAULT 0;

	DECLARE ratio_1 DECIMAL(18,8) DEFAULT 1; 

	DECLARE ratio_2 DECIMAL(18,8) DEFAULT 1;

  

	

	SET currencyIDDefault = (

		SELECT c.currencyID	

		FROM tb_parameter p 

		INNER JOIN tb_company_parameter cp ON p.parameterID = cp.parameterID 

		INNER JOIN tb_currency c ON  cp.value = c.name 

		WHERE p.name = 'ACCOUNTING_CURRENCY_NAME_FUNCTION' and c.isActive = 1 and cp.companyID = prCompanyID LIMIT 1 

	);

	SET currencyIDSource  = prCurrencySourceID;

	SET currencyIDTarget  = prCurrencyTargetID;	



	

	IF currencyIDSource != currencyIDTarget THEN

		SET ratio_1 = (

				SELECT ratio 

				FROM tb_exchange_rate e 

				where 

					e.companyID = prCompanyID and 

					e.currencyID = currencyIDSource and 

					e.targetCurrencyID = currencyIDTarget AND 

					e.`date` =  prDate

			);

	END IF;

	



	  

	

	IF currencyIDSource = currencyIDTarget THEN		

		RETURN ROUND((1 * prValorToConvert),2);

	ELSEIF currencyIDSource = 1 THEN 

		

		RETURN ROUND((prValorToConvert * ratio_1) ,2) ; 

	ELSE  

		

		RETURN ROUND((prValorToConvert * ratio_1),2) ; 

	END IF;

	



END
;;
delimiter ;

-- ----------------------------
-- Function structure for fn_get_access_ready
-- ----------------------------
DROP FUNCTION IF EXISTS `fn_get_access_ready`;
delimiter ;;
CREATE FUNCTION `fn_get_access_ready`(`prCompanyID` INT, `prUserID` INT, `prElementID` INT, `prRowCreatedBy` INT, `prRowCreatedAt` INT)
 RETURNS int(11)
  DETERMINISTIC
  COMMENT 'Obtener el nivel de acceso de un usuario'
BEGIN
	
	
	
	DECLARE varAcceso INT DEFAULT 0;
	DECLARE varIsAdmin INT DEFAULT 0;
	DECLARE varBranch INT DEFAULT 0;
		
	
	SET varBranch = (
	SELECT 
		p.branchID  
	FROM 
		tb_user u
		inner join tb_membership p on 
			u.userID = p.userID 
		inner join tb_role r on 
			p.roleID = r.roleID 
	where
 		u.userID = prUserID and 
		u.companyID =  prCompanyID
	limit 1 
	);
	
	
	SET varIsAdmin = (
	SELECT 
		r.isAdmin 
	FROM 
		tb_user u
		inner join tb_membership p on 
			u.userID = p.userID 
		inner join tb_role r on 
			p.roleID = r.roleID 
	where
 		u.userID = prUserID and 
		u.companyID =  prCompanyID
	limit 1 
	);
	
	
	SET varAcceso = (
	SELECT 
		per.selected 
	FROM 
		tb_user u
		inner join tb_membership p on 
			u.userID = p.userID 
		inner join tb_role r on 
			p.roleID = r.roleID 
		inner join tb_user_permission per on 
			r.roleID = per.roleID and 
			r.companyID = per.companyID 
		inner join tb_element el on 
			per.elementID = el.elementID 
	WHERE  
		u.userID = prUserID and 
		u.companyID =  prCompanyID and 
		per.elementID  = prElementID
	LIMIT 1
	) ;
		
	
	


	
	IF varIsAdmin = 1 THEN 
		RETURN 1;
	END IF;
	
	
	IF (varAcceso IS NULL) OR (varAcceso = -1) THEN 
		RETURN 0;
	
	ELSEIF  (varAcceso = 0 ) THEN 
		RETURN  1;
	
	ELSEIF  (varAcceso = 1 ) THEN 
		IF (prRowCreatedAt = varBranch) THEN 
			RETURN 1;
		ELSE 
			RETURN 0;
		END IF;
	
	ELSEIF  (varAcceso = 2 ) THEN 
		IF (prRowCreatedBy = prUserID) THEN 
			RETURN 1;
		ELSE 
			RETURN 0;
		END IF;
	
	ELSE
		RETURN 0;		
	END IF;
	
END
;;
delimiter ;

-- ----------------------------
-- Function structure for fn_get_nombre_del_dia
-- ----------------------------
DROP FUNCTION IF EXISTS `fn_get_nombre_del_dia`;
delimiter ;;
CREATE FUNCTION `fn_get_nombre_del_dia`(fecha DATE)
 RETURNS varchar(20) CHARSET utf8mb4 COLLATE utf8mb4_general_ci
  DETERMINISTIC
BEGIN
    DECLARE nombre_dia VARCHAR(20);

    -- Aseguramos que el idioma sea español
    SET lc_time_names = 'es_ES';

    -- Obtenemos el nombre del día en español
    SET nombre_dia = DAYNAME(fecha);

    -- Reemplazamos tildes por letras sin acento
    SET nombre_dia = REPLACE(nombre_dia, 'á', 'a');
    SET nombre_dia = REPLACE(nombre_dia, 'é', 'e');
    SET nombre_dia = REPLACE(nombre_dia, 'í', 'i');
    SET nombre_dia = REPLACE(nombre_dia, 'ó', 'o');
    SET nombre_dia = REPLACE(nombre_dia, 'ú', 'u');

    RETURN nombre_dia;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for fn_get_number_phone
-- ----------------------------
DROP FUNCTION IF EXISTS `fn_get_number_phone`;
delimiter ;;
CREATE FUNCTION `fn_get_number_phone`(prCompanyID INT,
    prPhone VARCHAR(255))
 RETURNS varchar(50) CHARSET utf8mb4 COLLATE utf8mb4_general_ci
  DETERMINISTIC
BEGIN
    DECLARE vResult VARCHAR(255);

    -- 1. Quitar espacios y caracteres + ( ) #
    SET vResult = REGEXP_REPLACE(prPhone, '[\\s\\+\\(\\)\\#]', '');

    -- 2. Si contiene ".us"
    IF LOCATE('.us', vResult) > 0 THEN
        
        -- dejar solo números
        SET vResult = REGEXP_REPLACE(vResult, '[^0-9]', '');

        -- si tiene 11 dígitos y empieza con 505 quitar el 505
        IF LENGTH(vResult) = 11 AND LEFT(vResult,3) = '505' THEN
            SET vResult = SUBSTRING(vResult,4);
        END IF;
		ELSE
        
        -- si no contiene .us, validar directamente
        IF LENGTH(vResult) = 11 AND LEFT(vResult,3) = '505' THEN
            SET vResult = SUBSTRING(vResult,4);
        END IF;

    END IF;

    RETURN vResult;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for fn_get_provider_id
-- ----------------------------
DROP FUNCTION IF EXISTS `fn_get_provider_id`;
delimiter ;;
CREATE FUNCTION `fn_get_provider_id`(`prCompanyID` INT, `prUserID` INT)
 RETURNS int(11)
BEGIN

	DECLARE varProviderID INT DEFAULT 0;

	

	SET varProviderID = (

	SELECT 

		u.employeeID as providerID    

	FROM 

		tb_user u

		inner join tb_membership p on 

			u.userID = p.userID 

		inner join tb_role r on 

			p.roleID = r.roleID

		inner join tb_provider pro on 

			pro.entityID = u.employeeID 

	where

		u.userID = prUserID and 

		u.companyID = prCompanyID and 

		u.isActive = 1 and 

		r.name = 'INVERSIONISTA' LIMIT 1);

		

	IF (varProviderID IS NULL) OR (varProviderID = 0) THEN 

		RETURN 0;

	ELSE

		RETURN varProviderID;		

	END IF;



END
;;
delimiter ;

-- ----------------------------
-- Function structure for fn_insertar_string_n
-- ----------------------------
DROP FUNCTION IF EXISTS `fn_insertar_string_n`;
delimiter ;;
CREATE FUNCTION `fn_insertar_string_n`(texto LONGTEXT,

    marcador VARCHAR(50),

    n INT)
 RETURNS longtext CHARSET utf8mb4 COLLATE utf8mb4_general_ci
  DETERMINISTIC
BEGIN

    DECLARE resultado LONGTEXT DEFAULT '';

    DECLARE inicio INT DEFAULT 1;

    DECLARE longitud INT;



    SET longitud = CHAR_LENGTH(texto);



    WHILE inicio <= longitud DO

        SET resultado = CONCAT(resultado, SUBSTRING(texto, inicio, n), marcador);

        SET inicio = inicio + n;

    END WHILE;



    RETURN resultado;

END
;;
delimiter ;

-- ----------------------------
-- Function structure for fn_translate_transaction_master_info_amounts
-- ----------------------------
DROP FUNCTION IF EXISTS `fn_translate_transaction_master_info_amounts`;
delimiter ;;
CREATE FUNCTION `fn_translate_transaction_master_info_amounts`(`prCompanyID` INT, 
	`prFlavorID` INT, 
	`prTransactionID` INT,
	`prCurrencyFunction` VARCHAR(50), -- moneda que queremos 
	`prCurrencyReport` VARCHAR(50), -- no usado en la lógica actual 
	`prCurrencyReportConvert` VARCHAR(50), -- moneda final para el reporte ('Cordoba', 'Dolar', 'None') 
	`prTransactionCurrencyID` INT, -- 1 = Córdoba, 2 = Dólar 
	`prExchangeRate` DECIMAL(18,8), -- tasa USD/C$
	`prTransactionAmount` DECIMAL(18,6), -- amount 
	`prTransactionAmountExt` DECIMAL(18,6),-- amountExt
	`prField` VARCHAR(30))
 RETURNS varchar(50) CHARSET utf8mb4 COLLATE utf8mb4_general_ci
  READS SQL DATA 
  DETERMINISTIC
BEGIN 
	DECLARE vTransCurrency VARCHAR(10); 
	DECLARE vFuncCurrency VARCHAR(10); 
	DECLARE vConvCurrency VARCHAR(10);
	DECLARE vBaseAmount DECIMAL(18,6); 
	DECLARE vResult DECIMAL(18,6);
	 -- Solo para transactionID = 19 
	-- IF prTransactionID <> 19 THEN 
	-- RETURN NULL; 
	-- END IF; 
	SET vFuncCurrency 								= UPPER(TRIM(prCurrencyFunction)); 
	SET vConvCurrency 								= UPPER(TRIM(prCurrencyReportConvert)); 
	-- Moneda de la transacción y asignación de campos 
	IF prTransactionCurrencyID 				= 1 THEN 
		SET vTransCurrency 							= 'CORDOBA'; 
	ELSEIF prTransactionCurrencyID 		= 2 THEN 
		SET vTransCurrency 							= 'DOLAR'; 
	ELSE 
		RETURN NULL; 
	END IF; 
	-- 1) Selección según el campo que esperamos 
	IF UPPER(TRIM(prField)) 					= 'CURRENCYNAME' THEN
		IF vConvCurrency 								= 'NONE' THEN
			RETURN CONCAT(UCASE(LEFT(vTransCurrency, 1)), 
                             LCASE(SUBSTRING(vTransCurrency, 2)));
		ELSE
			RETURN CONCAT(UCASE(LEFT(vConvCurrency, 1)), 
                             LCASE(SUBSTRING(vConvCurrency, 2)));
		END IF;
	ELSEIF UPPER(TRIM(prField)) 			= 'AMOUNT' THEN 
		SET vBaseAmount 								= prTransactionAmount;
	ELSEIF UPPER(TRIM(prField)) 			= 'AMOUNTEXT' THEN 
		SET vBaseAmount									= prTransactionAmountExt; 
	ELSEIF UPPER(TRIM(prField)) 			= 'CONVERT' THEN 
		SET vBaseAmount 								= prTransactionAmount; 
	ELSE 
		RETURN NULL; -- campo inválido 
	END IF; 
	-- 2) Inversión si la moneda de función ≠ moneda de transacción 
	IF vFuncCurrency 									<> vTransCurrency THEN 
		IF UPPER(TRIM(prField)) 				= 'AMOUNT' THEN 
			SET vBaseAmount 							= prTransactionAmountExt; 
		ELSE 
			SET vBaseAmount 							= prTransactionAmount; 
		END IF; 
	END IF; 
	-- 3) Conversión final solo si prCurrencyReportConvert <> 'NONE' 
	IF UPPER(TRIM(prField)) 						= 'CONVERT' THEN 
		IF vConvCurrency 									= 'NONE' THEN 
			SET vResult 										= vBaseAmount;
		ELSEIF vConvCurrency 							= vTransCurrency THEN
			SET vResult 										= vBaseAmount;
		ELSE 
			IF vFuncCurrency 								= 'CORDOBA' 
			AND vConvCurrency 							= 'DOLAR' THEN 
				IF prExchangeRate IS NULL 
				OR prExchangeRate 						= 0 THEN 
					RETURN NULL; 
				END IF; 
				SET vResult 									= vBaseAmount * prExchangeRate; 
			ELSEIF vFuncCurrency						= 'DOLAR' 
			AND vConvCurrency 							= 'CORDOBA' THEN 
				IF prExchangeRate IS NULL 
				OR prExchangeRate 						= 0 THEN 
					RETURN NULL; 
				END IF; 
				SET vResult 									= vBaseAmount / prExchangeRate; 
			ELSE -- mismas monedas → no cambiar 
				IF vFuncCurrency 							<> vTransCurrency THEN
					IF vFuncCurrency 						= 'CORDOBA' 
					AND vTransCurrency 					= 'DOLAR' THEN 
						IF prExchangeRate IS NULL 
						OR prExchangeRate 				= 0 THEN 
							RETURN NULL; 
						END IF; 
						SET vResult 							= vBaseAmount / prExchangeRate; 
					ELSEIF vFuncCurrency 				= 'DOLAR' 
					AND vTransCurrency 					= 'CORDOBA' THEN 
						IF prExchangeRate IS NULL 
						OR prExchangeRate 				= 0 THEN 
							RETURN NULL; 
						END IF; 
						SET vResult 							= vBaseAmount * prExchangeRate; 
					END IF;
				ELSE 
					SET vResult 								= vBaseAmount; 
				END IF;
			END IF; 
		END IF; 
		RETURN ROUND(vResult, 4);
	ELSE  RETURN ROUND(vBaseAmount, 4);
	END IF;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_account_balance
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_account_balance`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_account_balance`(IN `prCompanyID` INT, IN `prPeriodID` INT, IN `prCycleID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para obtener el tb_catalog de Cuenta de un Ciclo y sus Saldo'
BEGIN

	SELECT

		a.companyID,

		a.accountID,

		a.parentAccountID,

		a.accountNumber,

		a.name,

		a.isOperative,

		a.statusID,

		at.accountTypeID,

		at.naturaleza,

		ab.balance as balanceStart,

		ab.debit,

		ab.credit,		

		if(at.naturaleza = 'D',ab.balance + (ab.debit - ab.credit),ab.balance + (ab.credit - ab.debit)) as balanceEnd

	FROM 

		tb_accounting_balance ab 

		inner join tb_account a on

			ab.accountID = a.accountID and 

			ab.companyID = a.companyID

		inner join tb_account_type at on

			a.accountTypeID = at.accountTypeID and 

			a.companyID = at.companyID 

		inner join tb_accounting_cycle cc on 

			ab.componentCycleID = cc.componentCycleID and 

			ab.companyID = cc.companyID 

		inner join tb_accounting_period cp on 

			ab.componentPeriodID = cp.componentPeriodID and 

			ab.companyID = cp.companyID 

	WHERE			

		ab.isActive = 1 and 

		cp.isActive = 1 and 

		cc.isActive = 1 and 

		a.companyID = prCompanyID and 

		cp.componentPeriodID  = prPeriodID and 

		cc.componentCycleID   = prCycleID 

	ORDER BY

		a.accountNumber ; 

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_calculate_utility
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_calculate_utility`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_calculate_utility`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTocken` VARCHAR(250), IN `prPeriodID` INT, IN `prCycleID` INT, OUT `prUtility` DECIMAL(18,8))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para Calcular Utilidades del Ciclo'
BEGIN	

	DECLARE variableStart_ VARCHAR(1) DEFAULT '[';

	DECLARE variableEnd_ VARCHAR(1) DEFAULT ']';

	DECLARE expreStart_ VARCHAR(1) DEFAULT '(';

	DECLARE expreEnd_ VARCHAR(1) DEFAULT ')';

	DECLARE formulaUtitlity_ VARCHAR(250) DEFAULT '';

	DECLARE accountID_ INT;

	DECLARE accountNumber_ VARCHAR(50);

	DECLARE balanceEnd_ DECIMAL(18,8);	

	DECLARE first_ INT;

	DECLARE last_ INT;

	DECLARE dif_ INT;	

	SET @parameterName_ 	= 'ACCOUNTING_FORMULATE_OF_UTILITY';

	SET @utilityResult 		= '';

	SET @query 				= '';

	

   

	

	SET	formulaUtitlity_ = (

		SELECT 

			cp.`value` 

		FROM 

			`tb_parameter` p 

			INNER JOIN tb_company_parameter cp 

				on p.parameterID = cp.parameterID  

		WHERE 

			cp.companyID = prCompanyID and 

			p.name = @parameterName_

		LIMIT 1

	);	  



	INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

	VALUES(

		prCompanyID,prBranchID,prLoginID,prTocken,

		'pr_accounting_calculate_utility',1,'iniciando while',CURRENT_TIMESTAMP()

	);

		

	WHILE LOCATE(variableStart_,formulaUtitlity_) > 0 DO

		SET first_ 				= LOCATE(variableStart_,formulaUtitlity_);

		SET last_  				= LOCATE(variableEnd_,formulaUtitlity_);

		SET dif_ 				= last_ - first_;

		SET accountNumber_ 		= SUBSTRING(formulaUtitlity_,first_ + 1 , dif_ - 1);		

		

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_calculate_utility',

			1,concat('while ',accountNumber_),CURRENT_TIMESTAMP());

		

		SET accountID_  		   = (

			SELECT accountID 

			FROM 

				tb_account where companyID = prCompanyID and isActive = 1 and accountNumber = accountNumber_ 

			LIMIT 1

		);		

		SET accountID_ 				= IFNULL(accountID_,0);

		

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,prLoginID,prTocken,

			'pr_accounting_calculate_utility',1,

			concat('while acountID',accountID_),CURRENT_TIMESTAMP()

		);

		

		SET balanceEnd_ 		= (

				SELECT balanceEnd 

				from  

					tb_accounting_balance_temp where companyID = prCompanyID and 

					branchID = prBranchID and loginID = prLoginID and 

					accountID = accountID_ LIMIT 1);

					

		SET balanceEnd_			= IF(balanceEnd_ IS NULL,0,balanceEnd_);

		

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,prLoginID,

			prTocken,'pr_accounting_calculate_utility',1,

			concat('while acountID balanceEnd ',ROUND(balanceEnd_,2)),

			CURRENT_TIMESTAMP()

		);

		

		SET formulaUtitlity_  	= REPLACE(

			formulaUtitlity_,CONCAT(variableStart_,accountNumber_,variableEnd_),

			balanceEnd_

		);		

		

	END WHILE ;	 



	INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

	VALUES(

		prCompanyID,prBranchID,prLoginID,

		prTocken,'pr_accounting_calculate_utility',1,

		concat('fin while formula:',formulaUtitlity_),CURRENT_TIMESTAMP()

	);

		

	SET @query   		= CONCAT("SELECT ",expreStart_,formulaUtitlity_,expreEnd_," INTO @utilityResult ");

	PREPARE stmt FROM @query;  

    EXECUTE stmt;

    DEALLOCATE PREPARE stmt; 



	INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

	VALUES(

		prCompanyID,prBranchID,prLoginID,

		prTocken,'pr_accounting_calculate_utility',1,

		concat('fin while resultado:',@utilityResult),CURRENT_TIMESTAMP()

	);

	SET prUtility 		= @utilityResult;

	

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_checkaccount_to_delete
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_checkaccount_to_delete`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_checkaccount_to_delete`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prAccountID` INT, IN `prApp` VARCHAR(50), OUT `prResultMessage` VARCHAR(300), OUT `prResultCode` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para saber si se puede eliminar una cuenta contable'
BEGIN

	declare varBalance DECIMAL(26,8) default 0;

	declare varBalanceLast DECIMAL(26,8) default 0;

	

	set varBalance = (

	select 

		SUM(IF(att.naturaleza='D',jed.debit - jed.credit,jed.credit - jed.debit)) as amount

	from

		tb_journal_entry je  

		inner join	tb_journal_entry_detail jed on

			je.journalEntryID = jed.journalEntryID 

		inner join tb_account a on 

			jed.accountID = a.accountID 

		inner join tb_account_type att on 

			a.accountTypeID = att.accountTypeID 

		inner join tb_accounting_cycle acc on 

			je.accountingCycleID = acc.componentCycleID 

	where

		je.isActive = 1 and 

		jed.isActive = 1 and 

		acc.isActive = 1 and 

		je.companyID = prCompanyID and 

		jed.accountID = prAccountID and 

		acc.endOn <= current_timestamp()

	);



	set varBalanceLast = (

	select 

		SUM(IF(att.naturaleza='D',jed.debit - jed.credit,jed.credit - jed.debit)) as amount

	from

		tb_journal_entry je  

		inner join	tb_journal_entry_detail jed on

			je.journalEntryID = jed.journalEntryID 

		inner join tb_account a on 

			jed.accountID = a.accountID 

		inner join tb_account_type att on 

			a.accountTypeID = att.accountTypeID 

		inner join tb_accounting_cycle acc on 

			je.accountingCycleID = acc.componentCycleID 

	where

		je.isActive = 1 and 

		jed.isActive = 1 and 

		acc.isActive = 1 and 

		je.companyID = prCompanyID and 

		jed.accountID = prAccountID and 

		acc.startOn > current_timestamp()

	);

	

   set varBalance = (SELECT IF(varBalance is null,0,varBalance));

   set varBalanceLast = (SELECT IF(varBalanceLast is null,0,varBalanceLast));

   

   IF varBalanceLast <> 0 THEN

   	set prResultMessage 	= 'La cuenta es usada en ciclos mayores al actual';

   	set prResultCode 		= 0;

   ELSEIF varBalance <> 0 THEN 

   	set prResultMessage 	= 'La cuenta tiene un saldo != de 0';

   	set prResultCode 		= 0;

   ELSE 

   	set prResultMessage 	= 'SUCCESS';

   	set prResultCode 		= 1;   	

   END IF;



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_closed_cycle
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_closed_cycle`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_closed_cycle`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prCreatedIn` VARCHAR(50), IN `prTocken` VARCHAR(250), IN `prPeriodID` INT, IN `prCycleID` INT, OUT `prCodeError` INT, OUT `prMessageResult` VARCHAR(250))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para Cerrar un Ciclo Contable'
LBL_PROCEDURE:

BEGIN

	DECLARE componentID_ INT DEFAULT 4;		

	DECLARE workflowStageClosedPeriod_ INT DEFAULT 0;

	DECLARE workflowStageClosedCycle_ INT DEFAULT 0;

	DECLARE journalTypeIDCierre_ INT DEFAULT 0;	

	DECLARE utilityValue_ DECIMAL(19,8) DEFAULT 0;

	DECLARE totalDebit_ DECIMAL (18,8) DEFAULT 0;

	DECLARE totalCredit_ DECIMAL (18,8) DEFAULT 0;

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;	

	DECLARE workflowStageInitOfJournal_ INT DEFAULT 0;	

	DECLARE oldCycleID_ INT DEFAULT 0;

	DECLARE nextCycleID_ INT DEFAULT 0;

	DECLARE nextPeriodID_ INT DEFAULT 0;

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE accountIDResult_ INT DEFAULT 0;

	DECLARE journalEntryID_ INT DEFAULT 0;

	DECLARE resultTemp_ INT DEFAULT 0;	

	DECLARE journalNumber_ VARCHAR(50);

	DECLARE companyName_ VARCHAR(50);

	DECLARE accountNumber_ VARCHAR(50);

	DECLARE accountTypeResult VARCHAR(150);



	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_ACCOUNTTYPE_RESULT",accountTypeResult);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_JOURNALTYPE_CLOSED",journalTypeIDCierre_);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",workflowStageClosedCycle_);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED",workflowStageClosedPeriod_);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameSource,currencyIDNameTarget,exchangeRate_);	

	SET companyName_ 		= (select name from tb_company where companyID = prCompanyID);	

	CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_journal_entry","statusID",workflowStageInitOfJournal_ );	

	CALL pr_core_get_next_number (prCompanyID,"tb_journal_entry",prBranchID,journalTypeIDCierre_,journalNumber_);		

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_UTILITY_ACUMULATE',accountNumber_);

		

	SET accountIDResult_ = (

		SELECT accountID FROM tb_account where isActive = 1 and 

		companyID = prCompanyID and accountNumber = accountNumber_ LIMIT 1

	);	

	SET accountIDResult_ = IFNULL(accountIDResult_,0);

	

	

	SET oldCycleID_ 		= (

		SELECT 

			cc.componentCycleID 

		FROM 	

			tb_accounting_cycle cc inner join 

			tb_accounting_period cp on 

			cp.companyID = cc.companyID and 	

			cp.componentID = cc.componentID and 

			cp.componentPeriodID = cc.componentPeriodID 

		WHERE 	

			cc.companyID = prCompanyID AND 	

			cc.isActive = 1 and 	

			cp.isActive = 1 and 	

			cc.componentID = componentID_ AND 	

			cc.endOn < (		

						select 			

							cc2.startOn  		

						from 			

							tb_accounting_cycle cc2 		

						where 			

							cc2.componentCycleID = prCycleID 	

			) 

		ORDER BY 	

			cc.endOn DESC LIMIT 1 

		);

			

	SET nextCycleID_  	= (

			SELECT 

				cc.componentCycleID 

			FROM 	

				tb_accounting_cycle cc 

				inner join tb_accounting_period cp on 

					cp.companyID = cc.companyID and 	

					cp.componentID = cc.componentID and 

					cp.componentPeriodID = cc.componentPeriodID 

			WHERE 	

				cc.companyID = prCompanyID AND 	

				cc.isActive = 1 and 	

				cp.isActive = 1 and 	

				cc.componentID = componentID_ AND 	

				cc.startOn > (		

					select 			

						cc2.endOn  		

					from 			

						tb_accounting_cycle cc2 		

					where 			

						cc2.componentCycleID = prCycleID 	

					) 

				ORDER BY 	

					cc.startOn ASC LIMIT 1 

				);	

		

	SET nextPeriodID_ 	= (SELECT componentPeriodID FROM tb_accounting_cycle WHERE componentCycleID = nextCycleID_);	

	SET totalDebit_ 		= (

		SELECT SUM(ab.debit) 

		from 

			tb_accounting_balance ab 

			inner join tb_account a on ab.companyID = a.companyID and ab.accountID = a.accountID  

		where 

			ab.isActive = 1 and 

			ab.companyID = prCompanyID and 

			ab.componentID = componentID_ and 

			ab.componentPeriodID = prPeriodID and 

			ab.componentCycleID = prCycleID and 

			a.isActive = 1 and a.parentAccountID IS NULL

	);

		

		

	SET totalCredit_	= (

			SELECT SUM(ab.credit) 

			from 

				tb_accounting_balance  ab 

				inner join tb_account a on 

					ab.companyID = a.companyID and ab.accountID = a.accountID 

			where 

				ab.isActive = 1 and 

				ab.companyID = prCompanyID and 

				ab.componentID = componentID_ and 

				ab.componentPeriodID = prPeriodID and ab.componentCycleID = prCycleID and 

				a.isActive = 1 and a.parentAccountID IS NULL 

	);

	

	

	IF(

		(oldCycleID_ IS NOT NULL ) AND 

		(

			(

				SELECT componentCycleID 

				FROM tb_accounting_cycle 

				where 

					componentCycleID = oldCycleID_ and statusID <>  workflowStageClosedCycle_

			) IS NOT NULL 		

		) 

	) THEN

	

		SET prCodeError 	= 1;

		SET prMessageResult = 'EL CICLO ANTERIOR DEBE DE  ESTAR CERRADO...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',1,

			'El cilo anterior debe de estar cerrado',CURRENT_TIMESTAMP());

		LEAVE LBL_PROCEDURE;

		

		

	END IF;	

		IF nextCycleID_ IS NULL THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'NO PUEDE CERRAR EL CICLO, NO EXISTE UN SIGUIENTE CICLO CONTABLE...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,prLoginID,prTocken,

			'pr_accounting_closed_cycle',1,

			'No puede cerrar el ciclo, no existe un siguiente ciclo contable',CURRENT_TIMESTAMP()

		);

		LEAVE LBL_PROCEDURE;

	END IF;

		

	IF (

			(prCycleID IS NOT NULL) AND 

			(

					(

							SELECT componentCycleID 

							FROM tb_accounting_cycle 

							where 

								componentCycleID = prCycleID and statusID =  workflowStageClosedCycle_

					) IS NOT NULL 

			)

	) THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'EL CICLO ACTUAL NO DEBE DE ESTAR CERRADO...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,

			prLoginID,prTocken,

			'pr_accounting_closed_cycle',1,'El ciclo actual no debe de estar cerrado',

			CURRENT_TIMESTAMP()

		);

		LEAVE LBL_PROCEDURE;

	END IF;

	

	IF(

		(nextCycleID_ IS NOT NULL) AND 

		(

			(

				SELECT componentCycleID FROM tb_accounting_cycle 

				where componentCycleID = nextCycleID_ and statusID =  workflowStageClosedCycle_

			) IS NOT NULL 		

		)

	) THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'EL CICLO SIGUIENTE NO DEBE DE ESTAR CERRADO...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,prLoginID,

			prTocken,'pr_accounting_closed_cycle',1,

			'El ciclo siguiente no debe de estar cerrado',CURRENT_TIMESTAMP());

			LEAVE LBL_PROCEDURE;

	END IF;

	

	IF totalDebit_ <> totalCredit_ THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'LOS MOVIMIENTOS DE CICLO NO SON EQUIVALENTES, DEBITOS Y CREDITOS DIFIEREN EN IMPORTE...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(

			prCompanyID,prBranchID,prLoginID,

			prTocken,'pr_accounting_closed_cycle',1,

			'Los movimientos del ciclo no son equivalente, debitos y creditos difieren en importe',

			CURRENT_TIMESTAMP()

		);

		LEAVE LBL_PROCEDURE;

	END IF;

	

	

	CALL `pr_accounting_mayorizate_cycle` (prCompanyID ,prBranchID,prLoginID, prPeriodID , prCycleID ,resultTemp_); 

	

	DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;

	

	CALL `pr_accounting_mayorizate_cycle` (prCompanyID , prBranchID,prLoginID,nextPeriodID_ , nextCycleID_ ,resultTemp_); 	

	

	CALL `pr_accounting_initialize_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID);

	

	CALL `pr_accounting_calculate_utility` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID , utilityValue_);

	

	

	IF nextPeriodID_ = prPeriodID THEN			

		UPDATE tb_accounting_balance , tb_accounting_balance_temp

			SET tb_accounting_balance.balance = tb_accounting_balance_temp.balanceEnd		

		WHERE

			tb_accounting_balance.accountID 			= tb_accounting_balance_temp.accountID AND 			

			tb_accounting_balance_temp.companyID 	= prCompanyID AND  

			tb_accounting_balance_temp.branchID 	= prBranchID AND 

			tb_accounting_balance_temp.loginID 		= prLoginID AND 

			tb_accounting_balance.companyID 			= prCompanyID AND 

			tb_accounting_balance.componentID 		= componentID_ AND 

			tb_accounting_balance.componentPeriodID	= nextPeriodID_ AND 

			tb_accounting_balance.componentCycleID		= nextCycleID_;		

	END IF;

	

	

	IF nextPeriodID_ <> prPeriodID THEN	

		CALL `pr_accounting_mayorizate_account_tmp` (

			prCompanyID , prBranchID , prLoginID , prTocken , accountIDResult_, 0, 0, 0, utilityValue_

		);

						

		UPDATE tb_accounting_balance , tb_accounting_balance_temp

			SET tb_accounting_balance.balance = tb_accounting_balance_temp.balanceEnd		

		WHERE

			tb_accounting_balance.accountID 			= tb_accounting_balance_temp.accountID AND 			

			tb_accounting_balance_temp.companyID 	= prCompanyID AND  

			tb_accounting_balance_temp.branchID 	= prBranchID AND 

			tb_accounting_balance_temp.loginID 		= prLoginID AND 

			tb_accounting_balance.companyID 			= prCompanyID AND 

			tb_accounting_balance.componentID 			= componentID_ AND 

			tb_accounting_balance.componentPeriodID	= nextPeriodID_ AND 

			tb_accounting_balance.componentCycleID		= nextCycleID_;	 	

			

		INSERT INTO tb_journal_entry (

			companyID,journalNumber,journalDate,tb_exchange_rate,createdOn,

			createdIn,createdAt,createdBy,isActive,isApplied,statusID,note,journalTypeID,

			currencyID,accountingCycleID,entryName

		)

		VALUES(

			prCompanyID,journalNumber_,CURDATE(),exchangeRate_,CURRENT_TIMESTAMP(),'::1',

			prBranchID,prLoginID,1,0,workflowStageInitOfJournal_,

			CONCAT(CAST(utilityValue_ AS DECIMAL(19,2)),'/UTILIDAD'),journalTypeIDCierre_,

			currencyID_,prCycleID,'APP-CIERRE'

		);		

		SET journalEntryID_ = LAST_INSERT_ID();

	

			

		INSERT INTO tb_journal_entry_detail (

			journalEntryID,companyID,accountID,isActive,classID,debit,credit,

			note,isApplied,branchID,tb_exchange_rate

		) 

		SELECT 

			journalEntryID_ as journalEntryID,

			prCompanyID as companyID,

			a.accountID,

			1 as isActive,

			0 as classID,

			CASE 

				WHEN att.naturaleza = 'C' and t.balanceEnd > 0 THEN 

					t.balanceEnd

				WHEN att.naturaleza = 'D' and t.balanceEnd < 0 then 

					t.balanceEnd

			END as debit,

			CASE 

				WHEN att.naturaleza = 'D' and t.balanceEnd > 0 THEN 

					t.balanceEnd

				WHEN att.naturaleza = 'C' and t.balanceEnd < 0 THEN 

					t.balanceEnd

			END as credit,

			'' as note,

			1 as isApplied, 

			prBranchID as branchID,

			exchangeRate_ as exchangeRate

		FROM 

			tb_accounting_balance_temp t

			inner join tb_account a on 

				t.accountID = a.accountID 

			inner join tb_account_type att on 

				a.accountTypeID = att.accountTypeID 

		WHERE

			t.companyID 	= prCompanyID AND  

			t.branchID 		= prBranchID AND 

			t.loginID 		= prLoginID AND

			a.isOperative 	= 1 and 

			t.balanceEnd 	<> 0 and 

			a.accountNumber REGEXP accountTypeResult

		ORDER BY 

			a.accountNumber;  

			 

		INSERT INTO tb_journal_entry_detail (

			journalEntryID,companyID,accountID,isActive,classID,debit,credit,note,isApplied,branchID,tb_exchange_rate

		) 

		VALUES (

			journalEntryID_,

			prCompanyID ,

			accountIDResult_ , 

			1,

			0, 

			IF(utilityValue_ < 0 , utilityValue_ , 0) ,

			IF(utilityValue_ > 0 , utilityValue_ , 0) ,

			'' ,

			1 , 

			prBranchID ,

			exchangeRate_ );

			

				

		UPDATE tb_accounting_balance,tb_account 

			set tb_accounting_balance.balance = 0 

		where

			tb_accounting_balance.companyID 			= tb_account.companyID and 

			tb_accounting_balance.accountID 			= tb_account.accountID and 

			tb_accounting_balance.companyID 			= prCompanyID and 

			tb_accounting_balance.componentPeriodID 	= nextPeriodID_ and 

			tb_accounting_balance.componentCycleID 	= nextCycleID_ AND 

			tb_accounting_balance.branchID 			= prBranchID AND 

			tb_account.accountNumber REGEXP accountTypeResult;

		

	END IF;	

		

	

	

	DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;		

		

	IF nextPeriodID_ <> prPeriodID THEN	

		UPDATE tb_accounting_period set statusID = workflowStageClosedPeriod_ WHERE componentPeriodID = prPeriodID;

		UPDATE tb_accounting_cycle set statusID = workflowStageClosedCycle_ WHERE  componentCycleID = prCycleID;

	ELSE

		UPDATE tb_accounting_cycle set statusID = workflowStageClosedCycle_ WHERE  componentCycleID = prCycleID;

	END IF;

	

	SET prCodeError 	= 0;

	SET prMessageResult = 'SUCCESS';

	INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

	VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',0,'Success',CURRENT_TIMESTAMP());

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_financial_reason
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_financial_reason`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_financial_reason`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTocken` VARCHAR(50), IN `prPeriodID` INT, IN `prCycleID` INT, IN `prMonthOnly` INT, IN `prParameterName` VARCHAR(50), OUT `prResult` DECIMAL(18,5))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'procedimiento para el calculo de razones financieras'
BEGIN

	DECLARE variableStart_ VARCHAR(1) DEFAULT '[';

	DECLARE variableEnd_ VARCHAR(1) DEFAULT ']';

	DECLARE expreStart_ VARCHAR(1) DEFAULT '(';

	DECLARE expreEnd_ VARCHAR(1) DEFAULT ')';

	DECLARE formulaUtitlity_ VARCHAR(300) DEFAULT '';

	DECLARE naturaleza_ VARCHAR(5) DEFAULT '';

	DECLARE accountID_ INT;

	DECLARE accountNumber_ VARCHAR(50);

	DECLARE balanceEnd_ DECIMAL(18,8);	

	DECLARE balanceEndMonth_ DECIMAL(18,8);	

	DECLARE first_ INT;

	DECLARE last_ INT; 

	DECLARE dif_ INT;	 

	DECLARE resultTemp_ INT DEFAULT 0; 

	SET @utilityResult 	= 0;

	SET @query 			= '';

	

		DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;

		 

		CALL `pr_accounting_initialize_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID);

	

		SET	formulaUtitlity_ = (

		SELECT 

			cp.`value` 

		FROM 

			`tb_parameter` p 

			INNER JOIN tb_company_parameter cp 

				on p.parameterID = cp.parameterID  

		WHERE 

			cp.companyID = prCompanyID and 

			p.name = convert(prParameterName using latin1) collate latin1_general_ci

		LIMIT 1

	);	  

	





	WHILE LOCATE(variableStart_,formulaUtitlity_) > 0 DO

		SET first_ 				= LOCATE(variableStart_,formulaUtitlity_);

		SET last_  				= LOCATE(variableEnd_,formulaUtitlity_);

		SET dif_ 				= last_ - first_;

		SET accountNumber_ 		= SUBSTRING(formulaUtitlity_,first_ + 1 , dif_ - 1);		

		SET accountID_  		   = (SELECT accountID FROM tb_account where companyID = prCompanyID and isActive = 1 and accountNumber = accountNumber_ LIMIT 1);		

		SET naturaleza_			= (SELECT att.naturaleza FROM tb_account a inner join tb_account_type att on a.accountTypeID = att.accountTypeID where a.accountID = accountID_ LIMIT 1);

		SET balanceEnd_ 		   = (SELECT balanceEnd from  tb_accounting_balance_temp where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and accountID = accountID_ LIMIT 1);		

		SET balanceEnd_			= IFNULL(balanceEnd_,0); 

				

		SET balanceEndMonth_	   = (SELECT if(naturaleza_ = 'D',debit - credit, credit-debit )  from  tb_accounting_balance_temp where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and accountID = accountID_ LIMIT 1);		

		SET balanceEndMonth_		= IFNULL(balanceEndMonth_,0); 		

		SET balanceEnd_			= IF(prMonthOnly = 1 ,balanceEndMonth_,balanceEnd_);		

		SET formulaUtitlity_  	= REPLACE(formulaUtitlity_,CONCAT(variableStart_,accountNumber_,variableEnd_),balanceEnd_);				

	END WHILE ;	 





	SET @query   		= CONCAT("SELECT ",expreStart_,formulaUtitlity_,expreEnd_," INTO @utilityResult ");

	PREPARE stmt FROM @query;  

   EXECUTE stmt;

   DEALLOCATE PREPARE stmt; 

	SET prResult 		= CAST(IFNULL(@utilityResult,0) AS DECIMAL(18,5)); 

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_get_history_balance_by_account
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_get_history_balance_by_account`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_get_history_balance_by_account`(IN `prCompanyID` INT, IN `prAccountID` INT)
  SQL SECURITY INVOKER
BEGIN	

	DECLARE numberRow INT;

	CREATE TEMPORARY TABLE tblAccountBalance (startOnPeriod DATETIME,startOnCycle DATETIME,balance decimal(19,8),debit decimal(19,8),credit decimal(19,8),naturaleza varchar(1),balanceEnd decimal(19,4));



	INSERT INTO tblAccountBalance

	SELECT 

		cp.startOn as startOnPeriod,

		cc.startOn as startOnCycle,

		ab.balance,

		ab.debit,

		ab.credit,

		at.naturaleza,

		if(at.naturaleza = 'D',ab.balance + (ab.debit - ab.credit),ab.balance + (ab.credit - ab.debit)) as balanceEnd

	FROM 

		tb_accounting_balance ab 

		inner join tb_account a on

			ab.accountID = a.accountID and 

			ab.companyID = a.companyID

		inner join tb_account_type at on

			a.accountTypeID = at.accountTypeID and 

			a.companyID = at.companyID 

		inner join tb_accounting_cycle cc on 

			ab.componentCycleID = cc.componentCycleID and 

			ab.companyID = cc.companyID 

		inner join tb_accounting_period cp on 

			ab.componentPeriodID = cp.componentPeriodID and 

			ab.companyID = cp.companyID 

	WHERE	

		a.companyID = prCompanyID and 

		a.accountID = prAccountID and 

		ab.isActive = 1

	ORDER BY

		cc.startOn DESC 

	LIMIT 0,180;



	

	SELECT COUNT(*) INTO numberRow FROM tblAccountBalance;



	IF (numberRow IS NULL) OR (numberRow = 0) THEN

		SELECT 0 as count_register;

	ELSE

		SELECT startOnPeriod,startOnCycle,balance,debit,credit,naturaleza,balanceEnd FROM tblAccountBalance;

	END IF;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_get_report_auxiliar_account
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_get_report_auxiliar_account`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_get_report_auxiliar_account`(IN `prCompanyID` INT, IN `prPeriodID` INT, IN `prCycleIDStart` INT, IN `prCycleIDEnd` INT, IN `prAccountID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para Obtener los movimientos por cuenta'
LBL_PROCEDURE:

BEGIN

	DECLARE componentID_ INT DEFAULT 4;

	DECLARE startOn_ DATETIME;

	DECLARE endOn_ DATETIME;

	DECLARE balanceStart_ DECIMAL(18,8) DEFAULT 0;

	DECLARE balanceEnd_ DECIMAL(18,8) DEFAULT 0;

	DECLARE debit_ DECIMAL(18,8) DEFAULT 0;

	DECLARE credit_ DECIMAL(18,8) DEFAULT 0;

	DECLARE nature_ VARCHAR(1) DEFAULT 'D';

	

	SET startOn_ = (select startOn from tb_accounting_cycle where companyID = prCompanyID and componentPeriodID = prPeriodID and componentCycleID = prCycleIDStart);

	SET endOn_   = (select endOn from tb_accounting_cycle where companyID = prCompanyID and componentPeriodID = prPeriodID and componentCycleID = prCycleIDEnd);

	

		SELECT 

		a.accountNumber,

		a.name,

		a.description,

		att.naturaleza,

		a.isOperative,				

		c.name as money

	FROM

		tb_account a 

		inner join tb_account_type att on 

			a.companyID = att.companyID and 

			a.accountTypeID = att.accountTypeID 		

		inner join tb_currency c on 

			a.currencyID  = c.currencyID 

	where

		a.isActive = 1 and 

		att.isActive = 1 and 

		a.companyID = prCompanyID and 

		a.accountID = prAccountID;

	

		SET nature_  = (SELECT att.naturaleza FROM tb_account a inner join tb_account_type att on a.accountTypeID = att.accountTypeID  where a.isActive = 1 and att.isActive = 1 and a.companyID = prCompanyID AND a.accountID = prAccountID LIMIT 1  );

		

		SELECT balance INTO balanceStart_  FROM tb_accounting_balance where companyID = prCompanyID and componentID = componentID_ and componentPeriodID = prPeriodID and componentCycleID = prCycleIDStart and accountID = prAccountID;

	SET balanceStart_ = IF (balanceStart_ IS NULL,0,balanceStart_);

	SELECT balanceStart_ AS balanceStart;

	

		SELECT 

		je.journalNumber,

		je.note,

		je.reference1,

		ci.name as journalType,

		je.journalDate,

		jed.debit,

		jed.credit

	FROM 

		tb_journal_entry je 	

		inner join tb_journal_entry_detail jed on 

			je.companyID = jed.companyID and 

			je.journalEntryID = jed.journalEntryID 

		inner join tb_catalog_item ci on 

			je.journalTypeID = ci.catalogItemID 

	WHERE		

		je.isActive = 1 and 	

		jed.isActive = 1 and 

		jed.accountID = prAccountID and 

		je.journalDate between startOn_ and endOn_

	ORDER BY

		je.journalEntryID ;

	

		SELECT 		

		SUM(jed.debit),

		SUM(jed.credit)  

		INTO debit_,credit_

	FROM 

		tb_journal_entry je 	

		inner join tb_journal_entry_detail jed on 

			je.companyID = jed.companyID and 

			je.journalEntryID = jed.journalEntryID 

	WHERE		

		je.isActive = 1 and 	

		jed.isActive = 1 and 

		jed.accountID = prAccountID and 

		je.journalDate between startOn_ and endOn_

	ORDER BY

		je.journalDate;

	

	SET balanceEnd_ = IF (nature_ = 'D' ,balanceStart_ + (debit_ - credit_),balanceStart_ + (credit_- debit_ ));

	SELECT balanceEnd_ AS balanceEnd;

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_get_report_auxiliar_mov_tipo_comprobantes
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_get_report_auxiliar_mov_tipo_comprobantes`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_get_report_auxiliar_mov_tipo_comprobantes`(IN `prCompanyID` INT, IN `prJournalTypeID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME, IN `prExcludeSystem` INT, IN `prStringContainer` VARCHAR(500), IN `prClassID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para Obtener los movimientos por tipo de comprobantes'
LBL_PROCEDURE:

BEGIN

	select

		je.journalNumber,

		je.journalDate,

		ci.name as journalType,

		je.note,

		je.reference1,

		je.tb_exchange_rate,

		a.accountNumber,

		a.name as accountName, 

		jed.debit,

		jed.credit

	from

		tb_journal_entry je 

		inner join tb_journal_entry_detail jed on 

			je.companyID = jed.companyID and 

			je.journalEntryID = jed.journalEntryID 

		inner join tb_account a on 

			jed.accountID = a.accountID 

		inner join tb_catalog_item ci on 

			je.journalTypeID = ci.catalogItemID 

		left join tb_center_cost cc on 

			a.classID = cc.classID 

	where

		je.isActive = 1 and 

		jed.isActive = 1 and 

		je.companyID = prCompanyID and 

		(

			(je.journalTypeID = prJournalTypeID and prJournalTypeID <> -1 ) or 

			(prJournalTypeID = -1 ) 

		) and 

		je.journalDate between prStartOn and prEndOn and 

		(

			(0 = prExcludeSystem) or 

			(prExcludeSystem != 0 and isModule = 0 )

		) and 

		(

			(prStringContainer = '') or 

			(prStringContainer <> '' and concat(' ', je.note,' ' ) like concat('%',prStringContainer,'%')) or 

			(prStringContainer <> '' and 

				je.journalEntryID in (

					select 

						jex.journalEntryID 

					from  

						tb_journal_entry jex 

						inner join tb_journal_entry_detail jedx on 

							jex.journalEntryID = jedx.journalEntryID 

						inner join tb_account ax on 

							jedx.accountID = ax.accountID 

					where

						jex.journalDate between prStartOn and prEndOn  and 

						concat(' ',ax.name,' ') like concat('%',prStringContainer,'%')

				) 

			)

		)

		

	order by

		je.createdOn desc, je.journalNumber asc ,jed.debit,jed.credit;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_get_report_balance_general
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_get_report_balance_general`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_get_report_balance_general`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTocken` VARCHAR(250), IN `prPeriodID` INT, IN `prCycleID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para Obtener el Balance General de la Empresa'
LBL_PROCEDURE:

BEGIN

	DECLARE utilityValue_ DECIMAL(19,8) DEFAULT 0;

	DECLARE accountIDResult_ INT DEFAULT 0;	

	DECLARE resultTemp_ INT DEFAULT 0;	

	DECLARE accountNumber_ VARCHAR(50);

	DECLARE accountTypeResult VARCHAR(150);

	DECLARE varAccountNumberActivo VARCHAR(20);

	DECLARE varAccountNumberPasivo VARCHAR(20);

	DECLARE varAccountNumberCapital VARCHAR(20);



	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_ACTIVO',varAccountNumberActivo);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_PASIVO',varAccountNumberPasivo);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_CAPITAL',varAccountNumberCapital);

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_ACCOUNTTYPE_RESULT",accountTypeResult);	

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_UTILITY_PERIOD',accountNumber_);

	

	SET accountIDResult_ = (SELECT accountID FROM tb_account where isActive = 1 and companyID = prCompanyID and accountNumber = accountNumber_ LIMIT 1);		

	CALL `pr_accounting_mayorizate_cycle` (prCompanyID ,prBranchID,prLoginID, prPeriodID , prCycleID ,resultTemp_); 

	

	DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;	

	CALL `pr_accounting_initialize_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID);	

	CALL `pr_accounting_calculate_utility` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID , utilityValue_);	

	

	INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

	VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_get_report_balance_general',1,concat('utilityValue: ',utilityValue_),CURRENT_TIMESTAMP());

		

	IF utilityValue_ > 0 and  utilityValue_ is not null THEN	

		CALL `pr_accounting_mayorizate_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , accountIDResult_, 0 , 0  , utilityValue_  , utilityValue_);

	END IF;

	IF utilityValue_ < 0 and utilityValue_ is not null THEN	

		CALL `pr_accounting_mayorizate_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , accountIDResult_, 0, ABS(utilityValue_)  , 0  , utilityValue_);

	END IF; 

 

	SELECT p.accountID,p.parentAccountID,p.accountNumber,p.name,p.isOperative,p.statusID,p.accountTypeID,p.naturaleza,p.balanceStart,p.debit,p.credit,p.balanceEnd FROM tb_accounting_balance_temp p inner join tb_account a on  p.accountID = a.accountID inner join tb_account_level l on  a.accountLevelID = l.accountLevelID WHERE p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID and p.accountNumber NOT REGEXP accountTypeResult and l.lengthTotal <= 8 and p.accountNumber REGEXP CONCAT('^' , varAccountNumberActivo)   ORDER BY p.accountNumber;

	

	SELECT p.accountID,p.parentAccountID,p.accountNumber,p.name,p.isOperative,p.statusID,p.accountTypeID,p.naturaleza,p.balanceStart,p.debit,p.credit,p.balanceEnd FROM tb_accounting_balance_temp p inner join tb_account a on  p.accountID = a.accountID inner join tb_account_level l on  a.accountLevelID = l.accountLevelID WHERE p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID and p.accountNumber NOT REGEXP accountTypeResult and l.lengthTotal <= 8 and p.accountNumber REGEXP CONCAT('^' , varAccountNumberPasivo)   ORDER BY p.accountNumber;



	SELECT p.accountID,p.parentAccountID,p.accountNumber,p.name,p.isOperative,p.statusID,p.accountTypeID,p.naturaleza,p.balanceStart,p.debit,p.credit,p.balanceEnd FROM tb_accounting_balance_temp p inner join tb_account a on  p.accountID = a.accountID inner join tb_account_level l on  a.accountLevelID = l.accountLevelID WHERE p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID and p.accountNumber NOT REGEXP accountTypeResult and l.lengthTotal <= 8 and p.accountNumber REGEXP CONCAT('^' , varAccountNumberCapital)   ORDER BY p.accountNumber;



   

	SELECT 

		p.balanceEnd 

	FROM 

		tb_accounting_balance_temp p 

		inner join tb_account a on  p.accountID = a.accountID 

		inner join tb_account_level l on  a.accountLevelID = l.accountLevelID 

	WHERE 

		p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID and 

		p.accountNumber NOT REGEXP accountTypeResult and l.lengthTotal <= 3 and p.accountNumber 

		REGEXP CONCAT('^' , varAccountNumberActivo) and a.parentAccountID is null   

	ORDER BY p.accountNumber;

	

	

	SELECT 

		SUM(p.balanceEnd) as balanceEnd 

	FROM 

		tb_accounting_balance_temp p 

		inner join tb_account a on  p.accountID = a.accountID 

		inner join tb_account_level l on  a.accountLevelID = l.accountLevelID 

	WHERE 

		p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID and p.accountNumber 

		NOT REGEXP accountTypeResult and l.lengthTotal <= 3 and 

		p.accountNumber REGEXP CONCAT('^(' , varAccountNumberPasivo,'|' , varAccountNumberCapital,')') and 

		a.parentAccountID is null   

	ORDER BY 

		p.accountNumber;

		



	DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;		

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_get_report_balanza_de_comprobacion
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_get_report_balanza_de_comprobacion`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_get_report_balanza_de_comprobacion`(IN `prCompanyID` INT, IN `prPeriodID` INT, IN `prCycleID` INT, IN `prClassID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para Obtener la Balanza de Comprobacion'
LBL_PROCEDURE: 

BEGIN

	CALL pr_accounting_account_balance (prCompanyID,prPeriodID,prCycleID);

	

	SELECT 

			sum(ab.credit) as debit, 

			sum(ab.debit) as credit			

	FROM 

		tb_accounting_balance ab 

		inner join tb_account a on 

			ab.accountID = a.accountID and 

			ab.companyID = a.companyID 

		left join tb_center_cost cc on 

			a.classID = cc.classID 

	where 

		ab.companyID = prCompanyID AND 

		ab.componentPeriodID = prPeriodID and   

		ab.componentCycleID = prCycleID and 

		ab.isActive = 1 and 

		a.parentAccountID IS NULL;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_get_report_cash_flow
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_get_report_cash_flow`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_get_report_cash_flow`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prCycleID` INT, IN `prPeriodID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN

	DECLARE varAccountNumberCash VARCHAR(20);

	DECLARE varAccountNumberIngresosEgresos VARCHAR(600);

	DECLARE varJournalTypeCapital VARCHAR(10);

	DECLARE varJournalTypeDividendo VARCHAR(10);

	DECLARE varJournalTypeProvision VARCHAR(10);

	CREATE TEMPORARY TABLE TB_CASH_FLOW (accountType varchar(25),accountNumber varchar(50) ,account varchar(150),saldoInicial decimal(19,2),saldoFinal decimal(19,2));



	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_CASH',varAccountNumberCash);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_INGRESOS_EGRESOS',varAccountNumberIngresosEgresos);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_JOURNALTYPE_APORTECAPITAL',varJournalTypeCapital);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_JOURNALTYPE_DIVIDENDO',varJournalTypeDividendo);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_JOURNALTYPE_PROVISION',varJournalTypeProvision);

		

		

	insert into TB_CASH_FLOW 

	select 

		'ENTRADA' as accountType,

		concat("'",a.accountNumber) as accountNumber,

		'Inicial'  as account,

		round(ac.balance,2) as saldoInicial,

		round(

		if(

		att.naturaleza = 'D',

		(ac.balance + (ac.debit - ac.credit)),

		(ac.balance + (ac.credit - ac.debit))

		),2) as saldoFinal

	from 

		tb_account a

		inner join tb_account_type att on 

			a.accountTypeID = att.accountTypeID 

		inner join tb_accounting_balance ac on 

			ac.accountID = a.accountID 

		inner join tb_accounting_cycle acc on 

			ac.componentCycleID = acc.componentCycleID 

	where

		a.companyID = prCompanyID and 

		a.isActive 	= 1 and 

		acc.componentCycleID = prCycleID  and 

		a.accountNumber LIKE varAccountNumberCash 

	order by 

		a.accountNumber ; 

		

	insert into TB_CASH_FLOW  

	select 

		'ENTRADA' as accountType,

		"'04-XX-XX" as accountNumber,

		'Aporte al Capital' as account ,

		0 as saldoInicial,		

		if( sum( je.debit) is null ,0,  sum( je.debit) )  as saldoFinal 

	from  

		tb_journal_entry jed  

		inner join tb_journal_entry_detail je on 

			jed.journalEntryID = je.journalEntryID 

	where

		jed.companyID = prCompanyID and 

		jed.accountingCycleID = prCycleID and 

		je.debit > 0 and 

		jed.journalTypeID IN (varJournalTypeCapital);

		



		

		

	insert into TB_CASH_FLOW 

	select 

			es.accountType,

			es.accountNumber,

			es.account,

			es.saldoInicial,

			(es.saldoFinal - ifnull(prov.saldo,0)) as saldoFinal 

	from 

		(

		select 

			if(att.naturaleza = 'D','SALIDA','ENTRADA') as accountType,

			concat("'",a.accountNumber) as accountNumber,

			a.name as account,

			0 as saldoInicial,

			round(

			if(

			att.naturaleza = 'D',

			(ac.debit - ac.credit)* (-1),

			(ac.credit - ac.debit)

			),2) as saldoFinal

		from 

			tb_account a

			inner join tb_account_type att on 

				a.accountTypeID = att.accountTypeID 

			inner join tb_accounting_balance ac on 

				ac.accountID = a.accountID 

			inner join tb_accounting_cycle acc on 

				ac.componentCycleID = acc.componentCycleID 		

		where

			a.companyID = prCompanyID  and 

			a.isActive 	= 1 and 

			acc.componentCycleID = prCycleID and 

			a.accountNumber  REGEXP  varAccountNumberIngresosEgresos 		

		order by 

			4 desc,

			a.accountNumber 

		) es   

		left join (

			select 

				a.accountID,

				concat("'",a.accountNumber) as accountNumber,

				round(

				if(

					att.naturaleza = 'D',

					(SUM(jex.debit) - SUM(jex.credit))* (-1),

					(SUM(jex.credit) - SUM(jex.debit))

				),2) as saldo

			from 

				tb_journal_entry je 

				inner join tb_journal_entry_detail jex on 

					je.journalEntryID = jex.journalEntryID 

				inner join tb_account a on 

					jex.accountID = a.accountID 

				inner join tb_account_type att on 

					att.accountTypeID = a.accountTypeID 

			where 

				je.isActive = 1 and 

				je.journalTypeID = varJournalTypeProvision and 

				je.accountingCycleID = prCycleID 

			group by 

				a.accountID,a.accountNumber

		) prov on 

			es.accountNumber = prov.accountNumber ;

				

	

	insert into TB_CASH_FLOW  

	select 

		'SALIDA' as accountType,

		"'06-XX-XX" as accountNumber, 

		'Desembolso' as  account,	

		0 as saldoInicial,		

		if( sum( tm.subAmount) is null ,0,

	   sum( tm.subAmount)  * (-1))  as saldoFinal 

	from 

		tb_transaction_master tm 

		inner join tb_workflow_stage ws on 

			tm.statusID = ws.workflowStageID  

		inner join tb_journal_entry jed on 

			tm.journalEntryID = jed.journalEntryID

		inner join tb_accounting_cycle acc on 

			jed.accountingCycleID = acc.componentCycleID  

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = 19 AND 

		tm.isActive = 1 and 

		ws.vinculable = 1 and 

		acc.componentCycleID = prCycleID ; 

		

	insert into TB_CASH_FLOW  

	select 

		'SALIDA' as accountType,

		"'06-XX-XY" as accountNumber,

		'Provisiones' as account ,

		0 as saldoInicial,		

		if( sum( je.credit) is null ,0,  sum( je.credit) )  * -1  as saldoFinal 

	from  

		tb_journal_entry jed  

		inner join tb_journal_entry_detail je on 

			jed.journalEntryID = je.journalEntryID 

		inner join tb_account a on 

			je.accountID = a.accountID 

	where

		jed.companyID = prCompanyID and 

		jed.accountingCycleID = prCycleID and 

		je.credit > 0 and 

		a.accountNumber = '02-01-01-01'; 

		

		

	insert into TB_CASH_FLOW  

	select 

		'SALIDA' as accountType,

		"'06-XX-XX" as accountNumber,

		'Pago de Dividendo' as account ,

		0 as saldoInicial,		

		(if( sum( je.debit) is null ,0,  sum( je.debit) )) * -1  as saldoFinal 

	from  

		tb_journal_entry jed 

		inner join tb_journal_entry_detail je on 

			jed.journalEntryID = je.journalEntryID 

	where

		jed.companyID = prCompanyID and 

		jed.accountingCycleID = prCycleID and 

		je.debit > 0 and 

		je.isActive = 1 and 

		jed.journalTypeID = varJournalTypeDividendo; 

		

		UPDATE TB_CASH_FLOW set 

		accountType = IF(saldoFinal < 0,'SALIDA','ENTRADA' ) 

	where 

		saldoFinal <> 0 

		and account <> 'Inicial';

	

	

	SELECT p.accountType, p.accountNumber,p.account,p.saldoInicial,p.saldoFinal FROM TB_CASH_FLOW p order by p.accountType asc,p.accountNumber; 	

	DROP TABLE TB_CASH_FLOW ; 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_get_report_catalogo_de_cuenta
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_get_report_catalogo_de_cuenta`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_get_report_catalogo_de_cuenta`(IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para Obtener el tb_catalog de cuenta de la empresa'
LBL_PROCEDURE:

BEGIN

	select 

		a.accountNumber,

		a.name,

		a.description,

		a.isOperative,

		cc.name as money,

		al.name as nivel,

		al.lengthTotal,

		att.name as tipo,

		att.naturaleza 

	from

		tb_account a 

		inner join tb_account_type att on 

			a.companyID = att.companyID and 

			a.accountTypeID = att.accountTypeID

		inner join tb_account_level al on  

			a.companyID = al.companyID and 

			a.accountLevelID = al.accountLevelID

		inner join tb_currency cc on

			a.currencyID = cc.currencyID 

	WHERE

		a.companyID = prCompanyID and

		a.isActive = 1 and 

		att.isActive = 1 and 

		al.isActive = 1

	ORDER BY

		a.accountNumber;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_get_report_estado_resultado
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_get_report_estado_resultado`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_get_report_estado_resultado`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTocken` VARCHAR(250), IN `prPeriodID` INT, IN `prCycleID` INT, IN `prClassID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para Obtener el Estado de Resultado de la Empresa'
LBL_PROCEDURE:

BEGIN

	DECLARE utilityValue_ DECIMAL(19,8) DEFAULT 0;

	DECLARE accountIDResult_ INT DEFAULT 0;	

	DECLARE resultTemp_ INT DEFAULT 0;	

	DECLARE accountNumber_ VARCHAR(50);

	DECLARE varAccountNumberIngreso VARCHAR(20);

	DECLARE varAccountNumberCostos VARCHAR(20);

	DECLARE varAccountNumberGastos VARCHAR(20);

	SET @accountTypeResult = '';

	

	 





	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_INGRESO',varAccountNumberIngreso);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_COSTOS',varAccountNumberCostos);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_GASTOS',varAccountNumberGastos);

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_ACCOUNTTYPE_RESULT",@accountTypeResult);	

	

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_UTILITY_ACUMULATE',accountNumber_);

	SET accountIDResult_ = (

		SELECT 

			accountID 

		FROM 

			tb_account 

		where isActive = 1 and companyID = prCompanyID and accountNumber = accountNumber_ LIMIT 1

	);	

	

	CALL `pr_accounting_mayorizate_cycle` (prCompanyID ,prBranchID,prLoginID, prPeriodID , prCycleID ,resultTemp_); 

	

	DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;

		

	CALL `pr_accounting_initialize_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID);

	

	CALL `pr_accounting_calculate_utility` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID , utilityValue_);

		

	

	SELECT 

		p.accountID,p.parentAccountID,p.accountNumber,p.name,p.isOperative,p.statusID,

		p.accountTypeID,p.naturaleza,p.balanceStart,p.debit,p.credit,p.balanceEnd, 

		if (att.naturaleza = 'D', p.debit - p.credit, p.credit - p.debit ) as balanceMensual 

	FROM 

		tb_accounting_balance_temp p 

		inner join tb_account a on p.accountID = a.accountID 

		inner join tb_account_level l on a.accountLevelID = l.accountLevelID 

		inner join tb_account_type att on a.accountTypeID = att.accountTypeID 

	WHERE 

		p.companyID = prCompanyID AND 

		p.branchID = prBranchID AND p.loginID = prLoginID and 

		l.lengthTotal <= 8 and p.accountNumber REGEXP @accountTypeResult and 

		p.accountNumber REGEXP CONCAT('^',varAccountNumberIngreso) and

		(

			(prClassID = 0) OR 

			( 

				prClassID <> 0 AND 

				a.classID = prClassID

			)

		)

	ORDER BY p.accountNumber;

	

	SELECT 

		p.accountID,

		p.parentAccountID,p.accountNumber,p.name,p.isOperative,p.statusID,

		p.accountTypeID,p.naturaleza,p.balanceStart,p.debit,p.credit,p.balanceEnd, 

		if (att.naturaleza = 'D', p.debit - p.credit, p.credit - p.debit ) as balanceMensual 

	FROM 

		tb_accounting_balance_temp p 

		inner join tb_account a on p.accountID = a.accountID 

		inner join tb_account_level l on a.accountLevelID = l.accountLevelID 

		inner join tb_account_type att on a.accountTypeID = att.accountTypeID 

	WHERE 

		p.companyID = prCompanyID AND p.branchID = prBranchID AND 

		p.loginID = prLoginID and l.lengthTotal <= 8 and 

		p.accountNumber REGEXP @accountTypeResult and p.accountNumber REGEXP CONCAT('^',varAccountNumberCostos) and 

		(

			(prClassID = 0) OR 

			( 

				prClassID <> 0 AND 

				a.classID = prClassID

			)

		)

	ORDER BY 

		p.accountNumber;

		

	SELECT 

		p.accountID,

		p.parentAccountID,

		p.accountNumber,

		p.name,p.isOperative,p.statusID,p.accountTypeID,p.naturaleza,p.balanceStart,p.debit,p.credit,p.balanceEnd, 

		if (att.naturaleza = 'D', p.debit - p.credit, p.credit - p.debit ) as balanceMensual 

	FROM 

		tb_accounting_balance_temp p 

		inner join tb_account a on p.accountID = a.accountID 

		inner join tb_account_level l on a.accountLevelID = l.accountLevelID 

		inner join tb_account_type att on a.accountTypeID = att.accountTypeID 

	WHERE 

		p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID and l.lengthTotal <= 8 

		and p.accountNumber REGEXP @accountTypeResult and 

		p.accountNumber REGEXP CONCAT('^',varAccountNumberGastos) and 

		(

			(prClassID = 0) OR 

			( 

				prClassID <> 0 AND 

				a.classID = prClassID 

			)

		)

		

	ORDER BY 

		p.accountNumber; 

	

	

	

	

	SELECT 

		'utilidades',SUM(IFNULL(TX.balanceEnd,0)) as valor 

	FROM 

		(

			SELECT 

				p.balanceEnd as balanceEnd 

			FROM 

				tb_accounting_balance_temp p  

				inner join tb_account al on p.accountID = al.accountID 

				inner join tb_account_level l on al.accountLevelID = l.accountLevelID 

				inner join tb_account_type att on al.accountTypeID = att.accountTypeID 

			WHERE 

				p.companyID = prCompanyID AND p.branchID = prBranchID AND 

				p.loginID = prLoginID 

				

				and p.accountNumber 	REGEXP @accountTypeResult 

				and p.accountNumber REGEXP CONCAT('^',varAccountNumberIngreso) 

				and al.isOperative = 1 

				and(

					(prClassID = 0) OR  

					( 

						prClassID <> 0 AND 

						al.classID = prClassID

					) 

				) 

				



			union all 



			SELECT 

				p.balanceEnd * -1 as balanceEnd 

			FROM 

				tb_accounting_balance_temp p 

				inner join tb_account az on p.accountID = az.accountID 

				inner join tb_account_level l on az.accountLevelID = l.accountLevelID 

				inner join tb_account_type att on az.accountTypeID = att.accountTypeID 

			WHERE 

				p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID 

				

				and p.accountNumber REGEXP @accountTypeResult 

				and p.accountNumber REGEXP CONCAT('^',varAccountNumberCostos) 

				and az.isOperative = 1 

				and(

					(prClassID = 0) OR  

					( 

						prClassID <> 0 AND 

						az.classID = prClassID

					) 

				) 

				

			union all 



			SELECT 

				p.balanceEnd * -1 as balanceEnd 

			FROM 

				tb_accounting_balance_temp p 

				inner join tb_account ax on p.accountID = ax.accountID 

				inner join tb_account_level l on ax.accountLevelID = l.accountLevelID 

				inner join tb_account_type att on ax.accountTypeID = att.accountTypeID 

			WHERE 

				p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID 

				

				and p.accountNumber REGEXP @accountTypeResult 

				and p.accountNumber REGEXP CONCAT('^',varAccountNumberGastos) 

				and ax.isOperative = 1 

				and (

					(prClassID = 0) OR  

					( 

						prClassID <> 0 AND 

						ax.classID = prClassID

					) 

				) 

				

		) TX;

				

	

	SELECT 

		SUM(IFNULL(TX.balanceMensual,0)) as valor

	FROM 

		(

			SELECT 

				al.classID,

				if (att.naturaleza = 'D', p.debit - p.credit, p.credit - p.debit ) as balanceMensual 

			FROM 

				tb_accounting_balance_temp p  

				inner join tb_account al on p.accountID = al.accountID 

				inner join tb_account_level l on al.accountLevelID = l.accountLevelID 

				inner join tb_account_type att on al.accountTypeID = att.accountTypeID 

			WHERE 

				p.companyID = prCompanyID AND p.branchID = prBranchID AND 

				p.loginID = prLoginID 

				

				and p.accountNumber 	REGEXP @accountTypeResult 

				and p.accountNumber REGEXP CONCAT('^',varAccountNumberIngreso) 

				and al.isOperative = 1 

				and(

					(prClassID = 0) OR  

					( 

						prClassID <> 0 AND 

						al.classID = prClassID

					) 

				) 

				



			union all 



			SELECT 

				az.classID,

				if (att.naturaleza = 'D', p.debit - p.credit, p.credit - p.debit ) * -1 as balanceMensual 

			FROM 

				tb_accounting_balance_temp p 

				inner join tb_account az on p.accountID = az.accountID 

				inner join tb_account_level l on az.accountLevelID = l.accountLevelID 

				inner join tb_account_type att on az.accountTypeID = att.accountTypeID 

			WHERE 

				p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID 

				

				and p.accountNumber REGEXP @accountTypeResult 

				and p.accountNumber REGEXP CONCAT('^',varAccountNumberCostos) 

				and az.isOperative = 1 

				and(

					(prClassID = 0) OR  

					( 

						prClassID <> 0 AND 

						az.classID = prClassID

					) 

				) 

				

			union all 



			SELECT 

				ax.classID,

				if (att.naturaleza = 'D', p.debit - p.credit, p.credit - p.debit ) * -1 as balanceMensual 

			FROM 

				tb_accounting_balance_temp p 

				inner join tb_account ax on p.accountID = ax.accountID 

				inner join tb_account_level l on ax.accountLevelID = l.accountLevelID 

				inner join tb_account_type att on ax.accountTypeID = att.accountTypeID 

			WHERE 

				p.companyID = prCompanyID AND p.branchID = prBranchID AND p.loginID = prLoginID 

				

				and p.accountNumber REGEXP @accountTypeResult 

				and p.accountNumber REGEXP CONCAT('^',varAccountNumberGastos) 

				and ax.isOperative = 1 

				and (

					(prClassID = 0) OR  

					( 

						prClassID <> 0 AND 

						ax.classID = prClassID

					) 

				) 

				

		) TX;

	

	DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;		

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_get_report_presupuestory
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_get_report_presupuestory`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_get_report_presupuestory`(IN `prCompanyID` INT, IN `prPeriodID` INT, IN `prCycleID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);

	



	 

	select 

		concat("'",`a`.`accountNumber`,"'") AS `accountNumber`,

		`a`.`name` AS `accountName`,

		case  

						when  a.accountNumber = '05-01-12' then 300 

						when  a.accountNumber = '06-02-05' then 115  

						when  a.accountNumber = '06-03-01' then 390.00			

										

						when  a.accountNumber = '06-03-08' then 35.00 

						when  a.accountnUmber = '06-03-10' then 11.00 

						when  a.accountNumber = '06-03-09' then 11.00 

						when  a.accountNumber = '06-01-02' then 115.00	

						

						when  a.accountNumber = '06-03-06' then 32 																		

						when  a.accountNumber = '06-02-03' then 20 

						when  a.accountNumber = '06-01-03' then 347.82 																								

						when  a.accountNumber = '06-03-04' then 100.00 						

						when  a.accountNumber = '06-03-05' then 60 						



						

						when  a.accountNumber = '06-02-07' then 0

						when  a.accountNumber = '05-01-06' then 0

						when  a.accountNumber = '06-02-02' then 0	

						when  a.accountNumber = '06-02-04' then 0

						when  a.accountNumber = '06-01-01' then 0

						when  a.accountNumber = '06-03-25' then 0

						when  a.accountNumber = '06-03-03' then 0

			else 0 

		end as numberPresupuesto,

		round((if((`att`.`naturaleza` = 'D'),((`acb`.`debit` - `acb`.`credit`)),((`acb`.`credit` - `acb`.`debit`))) / exchangeRate_),2) *-1 AS `realPresupuesto` 

	from 

		(((`tb_account` `a` 

		join `tb_accounting_balance` `acb` on 

				((`a`.`accountID` = `acb`.`accountID`))) 

		join `tb_accounting_cycle` `acc` on 

				((`acc`.`componentCycleID` = `acb`.`componentCycleID`))) 

		join `tb_account_type` `att` 

				on((`att`.`accountTypeID` = `a`.`accountTypeID`))) 

	where 

		acc.componentPeriodID = prPeriodID 

		and acc.componentCycleID = prCycleID  

		and a.accountNumber IN (

			'06-02-07',

			'06-03-06',

			'06-02-05',

			'05-01-12',

			'05-01-06',

			'06-01-03',

			'06-03-05',

			'06-02-02',

			'06-02-03',

			'06-02-04',

			'06-03-01',

			'06-03-10',

			'06-03-09',

			'06-01-02',

			'06-03-08',

			'06-01-01',

			'06-03-04',

			'06-03-25',

			'06-03-03'

		)

	order by 

		`a`.`accountNumber`,

		`acc`.`startOn` ;

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_get_report_razon_financial
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_get_report_razon_financial`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_get_report_razon_financial`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTokenID` VARCHAR(50), IN `prPeriodID` INT, IN `prCycleID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'reporte de las razones financieras'
BEGIN

	 	DECLARE varRazon VARCHAR(150) DEFAULT '';		 

	 	DECLARE varDescripcion VARCHAR(150) DEFAULT ''; 

	 	DECLARE varValor NUMERIC(19,2) DEFAULT 0;		

		DECLARE resultTemp_ INT DEFAULT 0; 

	

		CALL `pr_accounting_mayorizate_cycle` (prCompanyID ,prBranchID,prLoginID, prPeriodID , prCycleID ,resultTemp_); 	

	

		DELETE FROM tb_razones_financieras_tmp where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID;

		

		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0  ,'ACCOUNTING_RF_RAZON_CIRCULANTE',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'CIRCULANTE',IF(varValor IS NULL,0,varValor),'%','001','ACTIVO CIRCULANTE / PASIVO CIRCUALNTE');



		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0 ,'ACCOUNTING_RF_ENDEUDAMIENTO',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'ENDEUDAMIENTO',IF(varValor IS NULL,0,varValor),'%','002','PASIVO / PATRIMONIO');



		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0 ,'ACCOUNTING_RF_UTILIDAD_ANUAL',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'UTILIDAD ANUAL',IF(varValor IS NULL,0,varValor),'C$','003','UTILIDAD');



		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,1 ,'ACCOUNTING_RF_UTILIDAD_MENSUAL',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'UTILIDAD MENSUAL',IF(varValor IS NULL,0,varValor),'C$','004','UTILIDAD');

 

		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0 ,'ACCOUNTING_RF_RENTABILIDAD_ANUAL',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'RENTABILIDAD ANUAL',IF(varValor IS NULL,0,varValor),'%','005','UTILIDAD / ACTIVOS');



	

	

		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0 ,'ACCOUNTING_RF_RENTABILIDAD_MENSUAL',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'RENTABILIDAD MENSUAL',IF(varValor IS NULL,0,varValor),'%','006','UTILIDAD / ACTIVOS');

 

 

 

 		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0 ,'ACCOUNTING_RF_RAZON_BANCO_BAC',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'SALDO EN BANCO BAC',IF(varValor IS NULL,0,varValor),'C$','007','[01-01-01-01] + [01-01-02-04]');

 

 

 		CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0 ,'ACCOUNTING_RF_RAZON_BANCO_BANPRO',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'SALDO EN BANCO BANPRO',IF(varValor IS NULL,0,varValor),' C$','008','[01-01-02-03] + [01-01-01-02]');

 

 

	 	CALL pr_accounting_financial_reason (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID,0 ,'ACCOUNTING_RF_RAZON_BANCO_AVANZ',varValor);

		INSERT INTO tb_razones_financieras_tmp (companyID,branchID,loginID,token,name,value,simbol,sequence,description)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,'SALDO EN BANCO AVANZ',IF(varValor IS NULL,0,varValor),' C$','009','[01-01-01-03]');

 



		SELECT 

			name,

			value, 

			simbol,

			description 

		FROM 

			tb_razones_financieras_tmp 

		where 

			companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID

		ORDER BY

			sequence;

			

		CALL pr_core_get_indicators (prCompanyID,prBranchID,prLoginID,prTokenID,prPeriodID,prCycleID) ; 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_import_account
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_import_account`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_import_account`()
  MODIFIES SQL DATA 
  COMMENT 'Procedimiento para importar cuentas'
BEGIN

								DECLARE varIDMin INT DEFAULT 0;

		DECLARE varIDMax INT DEFAULT 0;

		DECLARE varIDParent INT DEFAULT 0;

		DECLARE varIDNivel INT DEFAULT 0;

		DECLARE varCompanyID INT DEFAULT 2;

		DECLARE varStatusID INT DEFAULT 1; 

		DECLARE varCurrencyID INT DEFAULT 1;

		



		SET varIDMin 						= (SELECT accountID FROM tb_account_tmp a order by a.n1,a.n2,a.n3,a.n4,a.n5 asc limit 1);

		SET varIDMax 						= (SELECT accountID FROM tb_account_tmp a order by a.n1 desc,a.n2 desc,a.n3 desc,a.n4 desc,a.n5 asc limit 1); 



		WHILE (varIDMin <= varIDMax) and (varIDMin is not null)  DO	

		

				SET varIDNivel							= (SELECT a.nivel from tb_account_tmp a where a.accountID = varIDMin);

				SET varIDParent						= (SELECT accountID FROM tb_account_tmp a where a.nivel < varIDNivel  and  a.accountID < varIDMin order by a.n1 desc,a.n2 desc,a.n3 desc,a.n4 desc,a.n5 desc limit 1);

				update tb_account_tmp set accountParentID = varIDParent where accountID = varIDMin; 

				

								SET varIDMin 							= (SELECT accountID FROM tb_account_tmp a where a.accountID > varIDMin order by a.n1,a.n2,a.n3,a.n4,a.n5 asc limit 1);



		END WHILE;

		

	

				delete from tb_account;		

	

				insert into tb_account (companyID,accountTypeID,accountLevelID,parentAccountID,accountNumber,name,description,isOperative,statusID,currencyID,createdBy,createdOn,createdIn,createdAt,isActive)	

		select  

			 varCompanyID,

			 case 	

			 	when ac.n1 = '01' then 

			 		(select tx.accountTypeID from  tb_account_type tx where tx.isActive = 1 and tx.companyID = varCompanyID and tx.name = 'ACTIVO' )

			 	when ac.n1 = '02' then 

			 		(select tx.accountTypeID from  tb_account_type tx where tx.isActive = 1 and tx.companyID = varCompanyID and tx.name = 'PASIVO' )

			 	when ac.n1 = '03' then 

			 		(select tx.accountTypeID from  tb_account_type tx where tx.isActive = 1 and tx.companyID = varCompanyID and tx.name = 'CAPITAL' )

			 	when ac.n1 = '04' then 

			 		(select tx.accountTypeID from  tb_account_type tx where tx.isActive = 1 and tx.companyID = varCompanyID and tx.name = 'INGRESOS' )

			 	when ac.n1 = '05' then 

			 		(select tx.accountTypeID from  tb_account_type tx where tx.isActive = 1 and tx.companyID = varCompanyID and tx.name = 'COSTOS' )

			 	when ac.n1 = '06' then 

			 		(select tx.accountTypeID from  tb_account_type tx where tx.isActive = 1 and tx.companyID = varCompanyID and tx.name = 'GASTOS' )

			 end accountTypeID,

 			 l.accountLevelID,

			 			 ac.accountParentID,

			 concat(ac.n1,'-',ac.n2,'-',ac.n3,'-',ac.n4,'-',ac.n5) as number,

			 replace(ac.name,'*','') as name,

			 replace(ac.name,'','') as description,

			 ac.operative,

			 varStatusID,

			 varCurrencyID, 

			 2,

			 '2016-01-01',

			 '::1',

			 '2',

			 1

			 		from 

			tb_account_tmp ac 

			inner join tb_account_level l on 

				(ac.nivel = 0 and l.lengthTotal = 2 ) or

				(ac.nivel = 3 and l.lengthTotal = 5 ) or 

				(ac.nivel = 6 and l.lengthTotal = 8 ) or

				(ac.nivel = 9 and l.lengthTotal = 11 ) 

		where 

			l.isActive = 1 and 

			l.companyID = varCompanyID 

		order by 

			ac.n1,ac.n2,ac.n3,ac.n4,ac.n5; 

		

				update 

				tb_account,

				(

				select 

					a.accountID,

					a2.accountID as parentID 

				from 

					tb_account a

					inner join tb_account_tmp att on 

						a.parentAccountID = att.accountID 

					inner join tb_account a2 on 

						concat(att.n1,'-',att.n2,'-',att.n3,'-',att.n4,'-',att.n5) = a2.accountNumber 

				) tl

 		 set tb_account.parentAccountID = tl.parentID 

 		 where

	 		  tb_account.accountID = tl.accountID ; 

				

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_initialize_account_tmp
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_initialize_account_tmp`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_initialize_account_tmp`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTocken` VARCHAR(250), IN `prPeriodID` INT, IN `prCycleID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para inicializar la tabla temporal'
BEGIN

	INSERT INTO tb_accounting_balance_temp (companyID,branchID,loginID,accountID,parentAccountID,accountNumber,name,isOperative,statusID,accountTypeID,naturaleza,balanceStart,debit,credit,balanceEnd)

	SELECT

		a.companyID,

		prBranchID,

		prLoginID,

		a.accountID,

		a.parentAccountID,

		a.accountNumber,

		a.name,

		a.isOperative,

		a.statusID,

		at.accountTypeID,

		at.naturaleza,

		ab.balance as balanceStart,

		ab.debit,

		ab.credit,		

		if(at.naturaleza = 'D',ab.balance + (ab.debit - ab.credit),ab.balance + (ab.credit - ab.debit)) as balanceEnd

	FROM 

		tb_accounting_balance ab 

		inner join tb_account a on

			ab.accountID = a.accountID and 

			ab.companyID = a.companyID

		inner join tb_account_type at on

			a.accountTypeID = at.accountTypeID and 

			a.companyID = at.companyID 

		inner join tb_accounting_cycle cc on 

			ab.componentCycleID = cc.componentCycleID and 

			ab.companyID = cc.companyID 

		inner join tb_accounting_period cp on 

			ab.componentPeriodID = cp.componentPeriodID and 

			ab.companyID = cp.companyID 

	WHERE			

		a.isActive  = 1 and 

		ab.isActive = 1 and 

		cp.isActive = 1 and 

		cc.isActive = 1 and 

		a.companyID = prCompanyID and 

		cp.componentPeriodID  = prPeriodID and 

		cc.componentCycleID   = prCycleID 

	ORDER BY

		a.accountNumber ;

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_mayorizate_account
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_mayorizate_account`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_mayorizate_account`(IN `prCompanyID` INT, IN `prPeriodID` INT, IN `prCycleID` INT, IN `prAccountID` INT, IN `prBalance` DECIMAL(19,8), IN `prDebit` DECIMAL(19,8), IN `prCredit` DECIMAL(19,8))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para mayorizar cuentas'
BEGIN

	DECLARE parentAccountID_ INT;	

	SET parentAccountID_ 		= (SELECT parentAccountID FROM tb_account where companyID = prCompanyID and accountID = prAccountID);		

	SET max_sp_recursion_depth = 6; 

	

	IF parentAccountID_ IS NOT NULL  THEN

		CALL pr_accounting_mayorizate_account(prCompanyID,prPeriodID,prCycleID,parentAccountID_,prBalance,prDebit,prCredit);

	END IF ;	

	

	UPDATE tb_accounting_balance set balance = balance + prBalance , debit = debit + prDebit , credit = credit + prCredit where companyID = prCompanyID and accountID = prAccountID and componentPeriodID = prPeriodID and componentCycleID = prCycleID;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_mayorizate_account_tmp
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_mayorizate_account_tmp`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_mayorizate_account_tmp`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTocken` VARCHAR(250), IN `prAccountID` INT, IN `prBalance` DECIMAL(19,8), IN `prDebit` DECIMAL(19,8), IN `prCredit` DECIMAL(19,8), IN `prBalanceEnd` DECIMAL(19,8))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para mayorizar la tabla Temporal'
BEGIN

	DECLARE parentAccountID_ INT;	

	DECLARE naturaleza_ VARCHAR(1);

	SET max_sp_recursion_depth = 6; 

	SET parentAccountID_ 		= (SELECT parentAccountID FROM tb_accounting_balance_temp where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and  accountID = prAccountID);	

	SET naturaleza_ 				= (SELECT naturaleza FROM tb_accounting_balance_temp where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and  accountID = prAccountID);	

	

	IF parentAccountID_ IS NOT NULL  THEN

		CALL pr_accounting_mayorizate_account_tmp(prCompanyID,prBranchID,prLoginID,prTocken,parentAccountID_,prBalance,prDebit,prCredit,prBalanceEnd);

	END IF ;	

	

	UPDATE tb_accounting_balance_temp set balanceStart = balanceStart + prBalance , debit = debit + prDebit , credit = credit + prCredit,balanceEnd = balanceEnd + prBalanceEnd  where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and  accountID = prAccountID;		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_mayorizate_cycle
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_mayorizate_cycle`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_mayorizate_cycle`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prPeriodID` INT, IN `prCycleID` INT, OUT `prResult` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para mayorizar los comprobantes realizados'
LBL_PROCEDURE:

BEGIN

	DECLARE journalTypeClosed INT DEFAULT 0;

	DECLARE minAccountID INT;

	DECLARE maxAccountID INT;

	DECLARE debit_ decimal(19,8)  DEFAULT 0;

	DECLARE credit_ decimal(19,8)  DEFAULT 0;

	DECLARE balance_ decimal(19,8) DEFAULT 0;

	DECLARE componentAccountID 	INT DEFAULT 4;

	DECLARE workflowStageCycleClosed_ INT DEFAULT 0;

	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",workflowStageCycleClosed_);

	

		IF EXISTS(SELECT cc.companyID FROM tb_accounting_cycle cc where cc.companyID = prCompanyID and componentID = componentAccountID AND componentPeriodID = prPeriodID and componentCycleID = prCycleID AND statusID = workflowStageCycleClosed_ ) THEN

		SET prResult = 0;

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(prCompanyID,prBranchID,prLoginID,'','pr_accounting_mayorizate_cycle',1,'El ciclo ya esta cerrado',CURRENT_TIMESTAMP());

		LEAVE LBL_PROCEDURE;

	END IF;

	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_JOURNALTYPE_CLOSED",journalTypeClosed);

	

		DELETE FROM tb_journal_entry_detail_summary WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID;

	

		INSERT INTO tb_journal_entry_detail_summary(companyID,branchID,loginID,journalEntryID,accountID,parentAccountID,debit,credit)

	SELECT 

		prCompanyID,

		prBranchID,

		prLoginID,

		je.journalEntryID,

		a.accountID,

		a.parentAccountID,

		sum(jed.debit),

		sum(jed.credit)

	FROM

		tb_journal_entry je 

		inner join tb_journal_entry_detail jed on 

				je.journalEntryID = jed.journalEntryID and je.companyID = jed.companyID 

		inner join tb_workflow_stage ws on

				je.statusID = ws.workflowStageID 

		inner join tb_account a on 

				jed.accountID = a.accountID 

	WHERE

		je.companyID = prCompanyID and 

		je.accountingCycleID = prCycleID and 		

		je.isActive = 1 and 

		jed.isActive = 1 and 

		je.journalTypeID != journalTypeClosed and 

		(jed.debit + jed.credit)  > 0  

	group by

		accountID;	

	

		INSERT INTO tb_accounting_balance (componentCycleID,componentPeriodID,companyID,componentID,accountID,branchID,balance,debit,credit,classID,isActive)

	SELECT 

		prCycleID,

		prPeriodID,

		prCompanyID,

		componentAccountID,

		a.accountID,

		prBranchID,

		0 AS balance,

		0 as debit,

		0 as credit,

		0 as classID,

		1 AS isActive

	FROM 

		tb_account a

	WHERE  

		a.companyID = prCompanyID and 

		a.accountID NOT IN (SELECT accountID FROM tb_accounting_balance where companyID = prCompanyID and componentPeriodID = prPeriodID and componentCycleID = prCycleID and isActive = 1) AND 

		a.isActive = 1;

		 

		UPDATE tb_accounting_balance set debit = 0 , credit = 0 where companyID = prCompanyID and componentPeriodID = prPeriodID and componentCycleID = prCycleID;

	SET minAccountID = (SELECT MIN(accountID) FROM  tb_journal_entry_detail_summary );

	SET maxAccountID = (SELECT MAX(accountID) FROM  tb_journal_entry_detail_summary );

	

	WHILE (minAccountID <= maxAccountID) and (minAccountID is not null) DO	

		SET balance_ 					= 0;  

		SET debit_ 						= (SELECT sum(debit) FROM tb_journal_entry_detail_summary WHERE accountID = minAccountID);

		SET credit_ 					= (SELECT sum(credit) FROM tb_journal_entry_detail_summary WHERE accountID = minAccountID);	

		CALL pr_accounting_mayorizate_account(prCompanyID,prPeriodID,prCycleID,minAccountID,balance_,debit_,credit_);

		SET minAccountID 				= (SELECT MIN(accountID) FROM  tb_journal_entry_detail_summary where accountID > minAccountID);

	END WHILE; 

	

		DELETE FROM tb_journal_entry_detail_summary WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID;

	SET prResult = 1;

	INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

	VALUES(prCompanyID,prBranchID,prLoginID,'','pr_accounting_mayorizate_cycle',0,'Success',CURRENT_TIMESTAMP());

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_templated_to_journal
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_templated_to_journal`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_templated_to_journal`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prApp` VARCHAR(50), IN `prJournalEntryTemplated` INT, INOUT `prJournalEntryResult` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'procedimiento para crear un comprobante a partir de un template'
BEGIN

	

DECLARE varJournalTypeID INT DEFAULT 0;

DECLARE varWorkflowStageInit INT DEFAULT 0;

DECLARE varJournalNumber VARCHAR(150) DEFAULT '';

DECLARE varCycleID INT DEFAULT 0;

DECLARE varDate DATE;

DECLARE varCurrentSourceID INT;

DECLARE varCurrentTargetID INT;

DECLARE varCurrentSourceName VARCHAR(50);

DECLARE varCurrentTargetName VARCHAR(50);

DECLARE varExchangeRate DECIMAL(18,8);

DECLARE varStatusIDClosedCycle INT;

DECLARE varTmp VARCHAR(150) DEFAULT '';

DECLARE varComponentID INT DEFAULT 0;







CALL pr_core_get_parameter_value(prCompanyID, 'ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED',varTmp);

SET  varStatusIDClosedCycle = CAST(varTmp AS UNSIGNED);



SET varComponentID 		  	 =  (SELECT c.componentID FROM tb_component c where c.name = '0-CONTABILIDAD');		



SET varDate 				 =  CURDATE();



SET varCurrentSourceID 		 =  (SELECT c.currencyID FROM tb_journal_entry c where c.journalEntryID = prJournalEntryTemplated);



SET varCurrentTargetID 		 =  (case when varCurrentSourceID = 1 then 2 else 1 end);

SET varCurrentSourceName 	 =  (case when varCurrentSourceID = 1 then 'Cordoba' else 'Dolar' end);

SET varCurrentTargetName 	 =  (case when varCurrentTargetID = 1 then 'Cordoba' else 'Dolar' end);





SET varJournalTypeID 		 =  (SELECT c.journalTypeID FROM tb_journal_entry c where c.journalEntryID = prJournalEntryTemplated);



CALL pr_core_get_next_number(prCompanyID, 'tb_journal_entry', prBranchID, varJournalTypeID, varJournalNumber);







CALL pr_core_get_exchange_rate(prCompanyID,varDate,varCurrentTargetName,varCurrentSourceName,varExchangeRate); 







SET varCycleID 	= (select c.componentCycleID from tb_accounting_cycle c where c.isActive = 1 and c.companyID = prCompanyID and c.componentID = varComponentID and statusID != varStatusIDClosedCycle AND  varDate between c.startOn and c.endOn LIMIT 1);



CALL pr_core_get_workflow_stage_init(prCompanyID,'tb_journal_entry','statusID',varWorkflowStageInit);

 



INSERT INTO tb_journal_entry (

	companyID,

	journalNumber,

	entryName,

	journalDate, 

	tb_exchange_rate, 

	createdOn, 

	createdIn,

	createdAt,

	createdBy,

	isActive,

	isApplied,

	statusID,

	note,

	reference1,reference2,reference3,

	journalTypeID,currencyID,accountingCycleID,

	isModule,transactionMasterID,transactionID)

SELECT 

	companyID,

	varJournalNumber,

	entryName,

	varDate, 

	varExchangeRate, 

	now(), 

	createdIn,

	createdAt,

	prLoginID,

	1,

	0,

	varWorkflowStageInit,

	note,

	reference1,reference2,reference3,

	varJournalTypeID,varCurrentSourceID,varCycleID,

	0,0,0

FROM 

	tb_journal_entry lx	

where

	lx.journalEntryID = prJournalEntryTemplated;



SET prJournalEntryResult = LAST_INSERT_ID();	



 



INSERT INTO tb_journal_entry_detail (companyID,journalEntryID,accountID,classID,isActive,debit,credit,note,isApplied,branchID,tb_exchange_rate)

select 

	companyID,prJournalEntryResult,accountID,classID,isActive,debit,credit,note,0,prBranchID,varExchangeRate

from 

	tb_journal_entry_detail l

where

	l.journalEntryID = prJournalEntryTemplated;





END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_accounting_transaction_to_journal
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_accounting_transaction_to_journal`;
delimiter ;;
CREATE PROCEDURE `pr_accounting_transaction_to_journal`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTransactionID` INT, IN `prSourceName` VARCHAR(50), IN `prResult` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Este procedimiento es para contabilizar todos los documentos de los modulos que estaran involucrado a la contabilidad'
BEGIN

		DECLARE varIDMin INT DEFAULT 0;

		DECLARE varIDMax INT DEFAULT 0;

		DECLARE varTransactionMasterID INT DEFAULT 0;

		DECLARE varTransactionMasterCausalID INT DEFAULT 0;

		DECLARE varCurrencyID INT DEFAULT 0;

		DECLARE varExchangeRate DECIMAL(26,8) DEFAULT 0;

		DECLARE varTransactionOn DATETIME DEFAULT CURRENT_DATE;

		DECLARE varTransactionNumber VARCHAR(150) DEFAULT '';

		DECLARE varReference2 VARCHAR(150) DEFAULT '';		

		DECLARE varTransactionName VARCHAR(450) DEFAULT '';

		DECLARE varJournalTypeID INT DEFAULT 0;

		DECLARE varWorkflowStageInit INT DEFAULT 0;

		DECLARE varCycleID INT DEFAULT 0;

		DECLARE varJournalNumber VARCHAR(150) DEFAULT '';

		DECLARE varTmp VARCHAR(150) DEFAULT '';

		DECLARE varComponentID INT DEFAULT 0;

		DECLARE varStatusIDClosedCycle INT DEFAULT 0;

		DECLARE varJournalEntryID INT DEFAULT 0;

		DECLARE varDebit DECIMAL(26,8) DEFAULT 0;

		DECLARE varCredit DECIMAL(26,8) DEFAULT 0; 		

		DECLARE varIsRevert INT DEFAULT 0;

		DECLARE varTransactionIDOriginal INT DEFAULT 0;

		

		

		

		SET varComponentID 		  =  (SELECT c.componentID FROM tb_component c where c.name = '0-CONTABILIDAD');

		

		CALL pr_core_get_parameter_value(prCompanyID, 'ACCOUNTING_JOURNALENTRY_WORKFLOWSTAGE_FINISH',varTmp);

		SET  varWorkflowStageInit = CAST(varTmp AS UNSIGNED);

		

		CALL pr_core_get_parameter_value(prCompanyID, 'ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED',varTmp);

		SET  varStatusIDClosedCycle = CAST(varTmp AS UNSIGNED);

		

		DELETE  FROM tb_transaction_master_summary_concept_tmp where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID ; 

		DELETE  FROM tb_transaction_profile_detail_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID;

		

		SET varIsRevert 		= (SELECT t.isRevert FROM tb_transaction t  where t.transactionID = prTransactionID and t.companyID = prCompanyID);		

		SET varTransactionName 	= (SELECT c.name FROM tb_transaction c where c.transactionID = prTransactionID and c.companyID = prCompanyID limit 1 );

		

		

		IF varIsRevert IS NULL OR varIsRevert = 0 THEN 

			INSERT INTO tb_transaction_master_summary_concept_tmp(companyID,branchID,loginID,transactionID,transactionMasterID,transactionMasterCausalID,transactionNumber,transactionDate,exchangeRate,currencyID,conceptID,reference1,reference2,reference3,value)

			SELECT 

				tm.companyID,

				prBranchID,

				prLoginID,

				tm.transactionID,

				tm.transactionMasterID,

				tm.transactionCausalID,			

				tm.transactionNumber ,

				tm.transactionOn, 

				tm.exchangeRate,

				tm.currencyID, 

				tmc.conceptID,  

				tm.reference1,

				tm.reference2,

				tm.reference3,

				SUM(ROUND(tmc.value,2)) as value 

			FROM  

				tb_transaction_master tm 

				inner join tb_transaction tt on 

					tm.transactionID = tt.transactionID and 

					tm.companyID = tt.companyID 

				inner join tb_transaction_causal tcc on 

					tm.companyID = tcc.companyID and 

					tm.transactionID = tcc.transactionID and 

					tm.transactionCausalID = tcc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					tm.statusID = ws.workflowStageID 

				inner join tb_transaction_master_concept tmc on 

					tm.companyID = tmc.companyID and 

					tm.transactionID = tmc.transactionID and 

					tm.transactionMasterID = tmc.transactionMasterID 

			where 

				tm.isActive = 1 and 

				tm.companyID = prCompanyID and 

				tm.transactionID = prTransactionID  and 

				ws.aplicable = 1 and 

				tm.journalEntryID = 0 and 

				tt.isActive = 1 and 

				tt.isCountable = 1 and 

				tcc.isActive = 1 

			group by 

				tm.companyID,

				tm.transactionID,  

				tm.transactionMasterID,

				tm.transactionCausalID,			

				tm.transactionNumber ,

				tm.currencyID,

				tmc.conceptID,

				tm.reference1,

				tm.reference2,

				tm.reference3;

		

		

		

			insert into tb_transaction_profile_detail_tmp (companyID,branchID,loginID,transactionID,transactionMasterID,transactionCausalID,conceptID,accountID,classID,debit,credit)

			SELECT 

					distinct 

					prCompanyID,

					prBranchID,

					prLoginID,

					tmp.transactionID,

					tmp.transactionMasterID,

					tmp.transactionMasterCausalID,

					tp.conceptID,

					tp.accountID,

					tp.classID,				

					CASE WHEN tp.sign = 'D' then tmp.value else 0  END as debit,

					CASE WHEN tp.sign = 'C' then tmp.value else 0  END as credit

		    FROM 

					tb_transaction_profile_detail tp  

					inner join tb_transaction_concept tmc on 

						tp.companyID = tmc.companyID and 

						tp.transactionID = tmc.transactionID 

					inner join tb_account ac on 

						tp.accountID = ac.accountID 

					inner join tb_transaction_master_summary_concept_tmp tmp on 

						tp.companyID = tmp.companyID and 

						tp.transactionID = tmp.transactionID and 

						tp.transactionCausalID = tmp.transactionMasterCausalID and 

						tp.conceptID = tmp.conceptID 

			where 

					tp.companyID 					= prCompanyID and 	

					tmp.branchID 					= prBranchID and 

					tmp.loginID 					= prLoginID and 

					tmp.transactionID 				= prTransactionID and 

					tmc.isActive 					= 1;

		

			SET varIDMin 						= (SELECT MIN(transactionMasterID) FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID );

			SET varIDMax 						= (SELECT MAX(transactionMasterID) FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID );

			SET varJournalTypeID 				= (SELECT journalTypeID FROM tb_transaction WHERE companyID = prCompanyID and transactionID = prTransactionID);

			WHILE (varIDMin <= varIDMax) and (varIDMin is not null) DO	

					SET varTransactionMasterID 			= (SELECT transactionMasterID FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and transactionMasterID = varIDMin LIMIT 1);

					SET varTransactionMasterCausalID 	= (SELECT transactionMasterCausalID FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and  transactionMasterID = varIDMin LIMIT 1);

					SET varCurrencyID 					= (SELECT currencyID FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and  transactionMasterID = varIDMin LIMIT 1);

					SET varExchangeRate 				= (SELECT exchangeRate FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and  transactionMasterID = varIDMin LIMIT 1);

					SET varTransactionOn 				= (SELECT transactionDate FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and  transactionMasterID = varIDMin LIMIT 1);

					SET varTransactionNumber 			= (SELECT transactionNumber FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and  transactionMasterID = varIDMin LIMIT 1);

					SET varReference2 					= (SELECT CONCAT(IFNULL(reference1,'N/D'),'-',IFNULL(reference2,'N/D'),'-',IFNULL(reference3,'N/D')) FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and  transactionMasterID = varIDMin LIMIT 1);

					

					

					SET varCycleID 						= 

					(select c.componentCycleID from tb_accounting_cycle c where c.isActive = 1 and c.companyID = prCompanyID and c.componentID = varComponentID and statusID != varStatusIDClosedCycle AND  date(varTransactionOn) between c.startOn and c.endOn LIMIT 1);

					

					IF varCycleID IS NULL THEN

							INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

							VALUES(prCompanyID,prBranchID,prLoginID,prSourceName,'pr_accounting_transaction_to_journal',1,concat('Ciclo Contable Cerrado: ',varTransactionOn),CURRENT_TIMESTAMP());	  									

							SET varJournalEntryID  = 0;		

					ELSE

							SET varDebit  = (select SUM(c.debit) from tb_transaction_profile_detail_tmp c where c.companyID = prCompanyID and c.branchID = prBranchID and c.loginID = prLoginID and c.transactionID = prTransactionID and c.transactionCausalID = varTransactionMasterCausalID and c.transactionMasterID = varTransactionMasterID);

							SET varCredit = (select SUM(c.credit) from tb_transaction_profile_detail_tmp c where c.companyID = prCompanyID and c.branchID = prBranchID and c.loginID = prLoginID and c.transactionID = prTransactionID and c.transactionCausalID = varTransactionMasterCausalID and c.transactionMasterID = varTransactionMasterID);

							

							IF varDebit <> varCredit  THEN

									INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

									VALUES(prCompanyID,prBranchID,prLoginID,prSourceName,'pr_accounting_transaction_to_journal',1,concat('Partida Descuadrada Documento (',varTransactionNumber,')'),CURRENT_TIMESTAMP());	  

									SET varJournalEntryID  = 0;		

							ELSE 

									IF NOT (varDebit = 0 OR varDebit IS NULL) THEN

										CALL pr_core_get_next_number(prCompanyID, 'tb_journal_entry', prBranchID, varJournalTypeID, varJournalNumber);

										

										INSERT INTO tb_journal_entry (companyID,journalNumber,entryName,journalDate, tb_exchange_rate, createdOn, createdIn,createdAt,createdBy,isActive,isApplied,statusID,note,reference1,reference2,reference3,journalTypeID,currencyID,accountingCycleID,isModule,transactionMasterID,transactionID)

										VALUES(prCompanyID,varJournalNumber,'APP',varTransactionOn,varExchangeRate,CURRENT_DATE,'127.0.0.1',prBranchID,prLoginID,1,0,varWorkflowStageInit, CONCAT('AUTOMATIC','/',varTransactionNumber,'/',varTransactionName) ,varTransactionNumber,varReference2,'',varJournalTypeID,varCurrencyID,varCycleID,1,varTransactionMasterID,prTransactionID);

										SET varJournalEntryID = LAST_INSERT_ID();	

											  

										INSERT INTO tb_journal_entry_detail (companyID,journalEntryID,accountID,classID,isActive,debit,credit,note,isApplied,branchID,tb_exchange_rate)

										select 

												c.companyID,

												varJournalEntryID,

												c.accountID,

												c.classID,

												1,

												c.debit,

												c.credit,

												'',

												0,

												prBranchID,

												varExchangeRate

										from 

												tb_transaction_profile_detail_tmp c 

										where 

												c.companyID = prCompanyID and 

												c.branchID = prBranchID and 

												c.loginID = prLoginID and 

												c.transactionID = prTransactionID and 

												c.transactionCausalID = varTransactionMasterCausalID and 

												c.transactionMasterID = varTransactionMasterID; 

												

										UPDATE tb_transaction_master_summary_concept_tmp set journalEntryID =  varJournalEntryID where  companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID and transactionMasterID = varTransactionMasterID AND transactionMasterCausalID = varTransactionMasterCausalID;

									

									ELSE 

										SET varJournalEntryID  = 0;									

									END IF;		

							END IF;

					END IF;

					SET varIDMin 	= (SELECT MIN(transactionMasterID) FROM tb_transaction_master_summary_concept_tmp WHERE companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID and transactionID = prTransactionID AND transactionMasterID > varIDMin);

			END WHILE;

	

	

			INSERT INTO tb_journal_entry_detail (companyID,journalEntryID,accountID,classID,isActive,note,isApplied,branchID,tb_exchange_rate,debit,credit)

			SELECT 

				jed.companyID,

				jed.journalEntryID,

				jed.accountID,

				jed.classID,

				jed.isActive,

								'TMP_PROCEDURE_NSSYSTEM' as note,

				jed.isApplied, 

				jed.branchID,

				jed.tb_exchange_rate,

				sum(jed.debit) as debit,

				sum(jed.credit) as credit

				

			FROM

				tb_journal_entry je 

				inner join tb_journal_entry_detail jed on 

					je.journalEntryID = jed.journalEntryID 

			where

				je.journalEntryID in ( 

					select distinct lmx.journalEntryID 

					from 

						tb_transaction_master_summary_concept_tmp lmx 

					where

						lmx.companyID = prCompanyID and 

						lmx.branchID = prBranchID and 

						lmx.loginID = prLoginID and 

						lmx.transactionID = prTransactionID

				) and 

				(jed.debit + jed.credit) <> 0  

			group by 

				jed.companyID,

				jed.journalEntryID,

				jed.accountID,

				jed.classID,

				jed.isActive,

				jed.note,

				jed.isApplied,

				jed.branchID,

				jed.tb_exchange_rate; 

				

			

			DELETE FROM tb_journal_entry_detail 

			where journalEntryID in ( 

					select 

						distinct lmx.journalEntryID 

					from 

						tb_transaction_master_summary_concept_tmp lmx 

					where

						lmx.companyID = prCompanyID and 

						lmx.branchID = prBranchID and 

						lmx.loginID = prLoginID and 

						lmx.transactionID = prTransactionID 

			) and note <> 'TMP_PROCEDURE_NSSYSTEM';

			

			

			UPDATE tb_transaction_master,tb_transaction_master_summary_concept_tmp 

			SET tb_transaction_master.journalEntryID = tb_transaction_master_summary_concept_tmp.journalEntryID 

		    WHERE 

					tb_transaction_master.companyID								= tb_transaction_master_summary_concept_tmp.companyID and 

					tb_transaction_master.transactionID 						= tb_transaction_master_summary_concept_tmp.transactionID and 

					tb_transaction_master.transactionMasterID 					= tb_transaction_master_summary_concept_tmp.transactionMasterID and 

					tb_transaction_master.transactionCausalID 					= tb_transaction_master_summary_concept_tmp.transactionMasterCausalID  and 

					tb_transaction_master_summary_concept_tmp.companyID 		= prCompanyID and 

					tb_transaction_master_summary_concept_tmp.transactionID 	= prTransactionID and 

					tb_transaction_master_summary_concept_tmp.branchID 			= prBranchID and 

					tb_transaction_master_summary_concept_tmp.loginID 			= prLoginID;		

				

		ELSE

			SET varIDMin 						= (SELECT MIN(trevert.transactionMasterID) FROM tb_transaction_master  trevert inner join tb_transaction_master tm on trevert.reference1 = tm.transactionID and trevert.reference2 = tm.transactionMasterID and trevert.reference3 = tm.transactionNumber inner join tb_journal_entry je on tm.journalEntryID = je.journalEntryID inner join tb_journal_entry_detail jed on je.journalEntryID = jed.journalEntryID and je.companyID = jed.companyID where trevert.isActive = 1 and trevert.journalEntryID = 0 and trevert.transactionID = prTransactionID and trevert.companyID = prCompanyID and je.isActive = 1  );

			SET varIDMax 						= (SELECT MAX(trevert.transactionMasterID) FROM tb_transaction_master  trevert inner join tb_transaction_master tm on trevert.reference1 = tm.transactionID and trevert.reference2 = tm.transactionMasterID and trevert.reference3 = tm.transactionNumber inner join tb_journal_entry je on tm.journalEntryID = je.journalEntryID inner join tb_journal_entry_detail jed on je.journalEntryID = jed.journalEntryID and je.companyID = jed.companyID where trevert.isActive = 1 and trevert.journalEntryID = 0 and trevert.transactionID = prTransactionID and trevert.companyID = prCompanyID and je.isActive = 1  );

			SET varJournalTypeID 				= (SELECT journalTypeID FROM tb_transaction WHERE companyID = prCompanyID and transactionID = prTransactionID);

			WHILE (varIDMin <= varIDMax) and (varIDMin is not null) DO	

			

					SET varTransactionMasterID			= (SELECT trevert.transactionMasterID FROM tb_transaction_master  trevert inner join tb_transaction_master tm on 	trevert.reference1 = tm.transactionID and 		trevert.reference2 = tm.transactionMasterID and 		trevert.reference3 = tm.transactionNumber where 	 trevert.isActive = 1 and 	 trevert.journalEntryID = 0 and 	 trevert.transactionID = prTransactionID and 	 trevert.companyID = prCompanyID and 	 trevert.transactionMasterID =  varIDMin); 					

					SET varTransactionMasterCausalID 	= (SELECT trevert.transactionCausalID FROM tb_transaction_master  trevert inner join tb_transaction_master tm on 	trevert.reference1 = tm.transactionID and 		trevert.reference2 = tm.transactionMasterID and 		trevert.reference3 = tm.transactionNumber where 	 trevert.isActive = 1 and 	 trevert.journalEntryID = 0 and 	 trevert.transactionID = prTransactionID and 	 trevert.companyID = prCompanyID and 	 trevert.transactionMasterID =  varIDMin);

					SET varCurrencyID 					= (SELECT trevert.currencyID FROM tb_transaction_master  trevert inner join tb_transaction_master tm on 	trevert.reference1 = tm.transactionID and 		trevert.reference2 = tm.transactionMasterID and 		trevert.reference3 = tm.transactionNumber where 	 trevert.isActive = 1 and 	 trevert.journalEntryID = 0 and 	 trevert.transactionID = prTransactionID and 	 trevert.companyID = prCompanyID and 	 trevert.transactionMasterID =  varIDMin);

					SET varExchangeRate 				= (SELECT trevert.exchangeRate FROM tb_transaction_master  trevert inner join tb_transaction_master tm on 	trevert.reference1 = tm.transactionID and 		trevert.reference2 = tm.transactionMasterID and 		trevert.reference3 = tm.transactionNumber where 	 trevert.isActive = 1 and 	 trevert.journalEntryID = 0 and 	 trevert.transactionID = prTransactionID and 	 trevert.companyID = prCompanyID and 	 trevert.transactionMasterID =  varIDMin);

					SET varTransactionOn 				= (SELECT trevert.transactionOn FROM tb_transaction_master  trevert inner join tb_transaction_master tm on 	trevert.reference1 = tm.transactionID and 		trevert.reference2 = tm.transactionMasterID and 		trevert.reference3 = tm.transactionNumber where 	 trevert.isActive = 1 and 	 trevert.journalEntryID = 0 and 	 trevert.transactionID = prTransactionID and 	 trevert.companyID = prCompanyID and 	 trevert.transactionMasterID =  varIDMin);

					SET varTransactionNumber 			= (SELECT trevert.transactionNumber FROM tb_transaction_master  trevert inner join tb_transaction_master tm on 	trevert.reference1 = tm.transactionID and 		trevert.reference2 = tm.transactionMasterID and 		trevert.reference3 = tm.transactionNumber where 	 trevert.isActive = 1 and 	 trevert.journalEntryID = 0 and 	 trevert.transactionID = prTransactionID and 	 trevert.companyID = prCompanyID and 	 trevert.transactionMasterID =  varIDMin);

					SET varReference2 					= (SELECT CONCAT(IFNULL(trevert.reference1,'N/D'),'-',IFNULL(trevert.reference2,'N/D'),'-',IFNULL(trevert.reference3,'N/D'))    FROM tb_transaction_master  trevert inner join tb_transaction_master tm on 	trevert.reference1 = tm.transactionID and 		trevert.reference2 = tm.transactionMasterID and 		trevert.reference3 = tm.transactionNumber where 	 trevert.isActive = 1 and 	 trevert.journalEntryID = 0 and 	 trevert.transactionID = prTransactionID and 	 trevert.companyID = prCompanyID and 	 trevert.transactionMasterID =  varIDMin);					

					SET varCycleID 						= (select c.componentCycleID from tb_accounting_cycle c where c.isActive = 1 and c.companyID = prCompanyID and c.componentID = varComponentID and statusID != varStatusIDClosedCycle AND  varTransactionOn between c.startOn and c.endOn LIMIT 1);

					

					IF varCycleID IS NULL THEN

							INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

							VALUES(prCompanyID,prBranchID,prLoginID,prSourceName,'pr_accounting_transaction_to_journal',1,concat('Ciclo Contable Cerrado: ',varTransactionOn),CURRENT_TIMESTAMP());	  

									

							SET varJournalEntryID  = 0;		

					ELSE		

						CALL pr_core_get_next_number(prCompanyID, 'tb_journal_entry', prBranchID, varJournalTypeID, varJournalNumber);

						

						INSERT INTO tb_journal_entry (companyID,journalNumber,entryName,journalDate, tb_exchange_rate, createdOn, createdIn,createdAt,createdBy,isActive,isApplied,statusID,note,reference1,reference2,reference3,journalTypeID,currencyID,accountingCycleID,isModule,transactionMasterID,transactionID)

						VALUES(prCompanyID,varJournalNumber,'APP',varTransactionOn,varExchangeRate,CURRENT_DATE,'127.0.0.1',prBranchID,prLoginID,1,0,varWorkflowStageInit,'AUTOMATIC',varTransactionNumber,varReference2,'',varJournalTypeID,varCurrencyID,varCycleID,1,varTransactionMasterID,prTransactionID);

						SET varJournalEntryID = LAST_INSERT_ID();	

							  

						INSERT INTO tb_journal_entry_detail (companyID,journalEntryID,accountID,classID,isActive,debit,credit,note,isApplied,branchID,tb_exchange_rate)

						select 

								jed.companyID,

								varJournalEntryID,

								jed.accountID,

								jed.classID,

								1,

								jed.credit,

								jed.debit,

								'',

								0,

								prBranchID,

								varExchangeRate

						from 

								tb_transaction_master  trevert 

								inner join tb_transaction_master tm on 

									trevert.reference1 = tm.transactionID and 

									trevert.reference2 = tm.transactionMasterID and 

									trevert.reference3 = tm.transactionNumber 

								inner join tb_journal_entry je on 

									tm.journalEntryID = je.journalEntryID 

								inner join tb_journal_entry_detail jed on 

									je.journalEntryID = jed.journalEntryID and 

									je.companyID = jed.companyID 

						where 

							 trevert.isActive = 1 and 

							 trevert.journalEntryID = 0 and 

							 trevert.transactionID = prTransactionID and 

							 trevert.companyID = prCompanyID and 

							 trevert.transactionMasterID = varIDMin;   

								

								

						UPDATE tb_transaction_master set journalEntryID = varJournalEntryID where transactionMasterID = varIDMin;

					

					END IF;

					SET varIDMin 	= (SELECT MIN(trevert.transactionMasterID) FROM tb_transaction_master  trevert inner join tb_transaction_master tm on trevert.reference1 = tm.transactionID and trevert.reference2 = tm.transactionMasterID and trevert.reference3 = tm.transactionNumber inner join tb_journal_entry je on tm.journalEntryID = je.journalEntryID inner join tb_journal_entry_detail jed on je.journalEntryID = jed.journalEntryID and je.companyID = jed.companyID where trevert.isActive = 1 and trevert.journalEntryID = 0 and trevert.transactionID = prTransactionID and trevert.companyID = prCompanyID and je.isActive = 1 and trevert.transactionMasterID > varIDMin);

			END WHILE;

		END IF;

	

	

		SET prResult = 1;

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(prCompanyID,prBranchID,prLoginID,prSourceName,'pr_accounting_transaction_to_journal',0,'Success',CURRENT_TIMESTAMP());

		

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_app_invoice_survery_get_report
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_app_invoice_survery_get_report`;
delimiter ;;
CREATE PROCEDURE `pr_app_invoice_survery_get_report`(IN `prCompanyID` INT, 

	IN `prTokenID` VARCHAR(50), 

	IN `prUserID` INT, 

	IN `prDateTimeStart` DATETIME,

	IN `prDateTimeFinish` DATETIME,

	IN `prSurveryKey` VARCHAR(50))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN



  SELECT 

    ttm.transactionNumber,

    ttm.transactionOn,

    tc.customerNumber,

    CONCAT(tn.firstName, ' ', tn.lastName) as Cliente,

    ti.itemNumber,

    ti.name,

    ttmd.quantity,

    tcr.simbol as Moneda,

    ttm.amount

  FROM tb_transaction_master ttm

  INNER JOIN tb_transaction_master_detail ttmd ON ttm.transactionMasterID=ttmd.transactionMasterID

  INNER JOIN tb_currency tcr ON ttm.currencyID = tcr.currencyID

  INNER JOIN tb_customer tc ON ttm.entityID = tc.entityID

  INNER JOIN tb_naturales tn ON tc.entityID = tn.entityID

  INNER JOIN tb_item ti ON ttmd.componentItemID = ti.itemID

  WHERE ttm.transactionID=65 

    AND ti.reference1 = prSurveryKey 

    AND ttm.transactionOn BETWEEN prDateTimeStart AND prDateTimeFinish

    AND ttm.isActive = 1

  order BY ttm.transactionOn DESC;



  SELECT 

    ti.itemNumber as Codigo,

    ti.name as Descripcion,

    SUM(ttmd.quantity) as Cantidad,

    tcr.simbol as Moneda,

    SUM(ttm.amount) as Monto

  FROM tb_transaction_master ttm

  INNER JOIN tb_transaction_master_detail ttmd ON ttm.transactionMasterID=ttmd.transactionMasterID

  INNER JOIN tb_currency tcr ON ttm.currencyID = tcr.currencyID

  INNER JOIN tb_item ti ON ttmd.componentItemID = ti.itemID

  WHERE 

    ttm.transactionID=65 

    AND ti.reference1 = prSurveryKey 

    AND ttm.transactionOn BETWEEN prDateTimeStart AND prDateTimeFinish

    AND ttm.isActive = 1

  GROUP BY ti.itemNumber, tcr.simbol

  order BY ttm.transactionOn DESC;



  SELECT 

    tc.customerNumber,

    tn.firstName as Nombres,

    tn.lastName AS Apellidos,

    SUM(ttmd.quantity) as Cantidad,

    tcr.simbol as Moneda,

    SUM(ttm.amount) AS Monto

  FROM tb_transaction_master ttm

  INNER JOIN tb_transaction_master_detail ttmd ON ttm.transactionMasterID=ttmd.transactionMasterID

  INNER JOIN tb_currency tcr ON ttm.currencyID = tcr.currencyID

  INNER JOIN tb_customer tc ON ttm.entityID = tc.entityID

  INNER JOIN tb_naturales tn ON tc.entityID = tn.entityID

  INNER JOIN tb_item ti ON ttmd.componentItemID = ti.itemID

  WHERE 

    ttm.transactionID=65 

    AND ti.reference1 = prSurveryKey 

    AND ttm.transactionOn BETWEEN prDateTimeStart AND prDateTimeFinish

    AND ttm.isActive = 1

  GROUP BY tc.customerNumber, tn.firstName, tn.lastName;



  SELECT 

    ttm.transactionOn as Fecha,

    ttm.transactionNumber,

    SUM(ttmd.quantity) as Cantidad,

    tcr.simbol as Moneda,

    SUM(ttm.amount) AS Monto 

  FROM tb_transaction_master ttm

  INNER JOIN tb_transaction_master_detail ttmd ON ttm.transactionMasterID=ttmd.transactionMasterID

  INNER JOIN tb_currency tcr ON ttm.currencyID = tcr.currencyID

  INNER JOIN tb_item ti ON ttmd.componentItemID = ti.itemID

  WHERE 

    ttm.transactionID=65 

    AND ti.reference1 = prSurveryKey 

    AND ttm.transactionOn BETWEEN prDateTimeStart AND prDateTimeFinish

    AND ttm.isActive = 1

  GROUP BY ttm.transactionNumber, ttm.transactionOn, tcr.simbol

  order BY ttm.transactionOn DESC;



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_box_closed_box
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_box_closed_box`;
delimiter ;;
CREATE PROCEDURE `pr_box_closed_box`(IN prUserID INT, 
	IN prBranchID INT,
	IN prTokenID VARCHAR(150), 
	IN prCompanyID INT,  
	IN prTransactionMasterOpen INT, 
	IN prTransactionMasterClosed INT, 
	IN prCashBoxID INT, 
	IN prCashBoxSessionID INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'guarda el listado de transacciones que se guardan dentro de una session de caja'
BEGIN

	DELETE FROM tb_cash_box_session_transaction_master WHERE cashBoxSessionID = prCashBoxSessionID;
	INSERT INTO tb_cash_box_session_transaction_master (companyID,branchID,cashBoxID,cashBoxSessionID,transactionID,transactionMasterID)
	SELECT 
		prCompanyID,
		prBranchID,
		prCashBoxID,
		prCashBoxSessionID,
		c.transactionID,
		c.transactionMasterID
	FROM 
		tb_transaction_master c 
	WHERE 
		c.transactionMasterID between prTransactionMasterOpen and prTransactionMasterClosed;
		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_box_get_report_abonos
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_box_get_report_abonos`;
delimiter ;;
CREATE PROCEDURE `pr_box_get_report_abonos`(IN `prUserID` INT, IN `prTokenID` VARCHAR(150), IN `prCompanyID` INT, IN `prAuthorization` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME, 
IN `prUserIDFilter` INT,  IN `prBranchID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'lista de abonos de los clientes'
BEGIN
	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		
	DECLARE currencyIDNameSource VARCHAR(250);
	DECLARE currencyIDNameTarget VARCHAR(250);	
	DECLARE moneda_ VARCHAR(50); 
	DECLARE currencyID_ INT DEFAULT 0;
	DECLARE currencyIDTarget_ INT DEFAULT 0;
	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;
	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;
	DECLARE PERMISSION_NONE INT DEFAULT -1;
	DECLARE PERMISSION_ALL INT DEFAULT 0;
	DECLARE PERMISSION_BRANCH INT DEFAULT 1;
	DECLARE PERMISSION_ME INT DEFAULT 2; 
	DECLARE isAdmin_ INT DEFAULT   0; 
  DECLARE convert_ VARCHAR(50);	

	select 
		r.isAdmin into isAdmin_ 
	from 
		tb_user u 
		inner join tb_membership me on 
			u.userID = me.userID 
		inner join tb_role r on 
			me.roleID = r.roleID 
	where
		me.userID = prUserID and r.isAdmin = 1 limit 1 ;
	set isAdmin_ = (case when isAdmin_ is null then 0 else isAdmin_ end);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);
	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);
	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);
	CALL pr_core_get_parameter_value(prCompanyID, "ACCOUNTING_CURRENCY_NAME_REPORT_CONVERT", convert_);


	SELECT 
		tt.name as transactionName,
		cus.customerNumber,
		concat(nat.firstName,' ',nat.lastName) as firstName , 
		tm.transactionNumber ,		
		DATE_FORMAT(tm.transactionOn, '%Y-%m-%d %r')  as transactionOn,
		tm.amount as montoTotal ,
		ws.name as estado,
		tm.note,		
		tmd.reference1 as Fac,
		if(
			cur.name = 'Dolar',
			tmd.amount * (exchangeRate_  + currencyTargetSale),
			tmd.amount
		)  as montoCordoba,
    case 
			when convert_ = 'Dolar' and cur.name != 'Dolar'  then 
				tmd.amount * (exchangeRate_  )
       when convert_ = 'Cordoba' and cur.name != 'Cordoba'  then 
				tmd.amount / (exchangeRate_ )
			else 
				tmd.amount 
		end as  montoFac,
		case 
			when convert_ = 'None'  then 
      cur.`name`
			else 
				convert_ 
		end as moneda,
		(exchangeRate_  + currencyTargetSale) as tipoCambio ,
		PERMISSION_ME,
		prAuthorization,
		tm.createdBy ,
		us.nickname ,
		'' as conceptosName ,
		'' as conceptosSubName 
	FROM 
		tb_transaction_master tm			
		inner join tb_transaction tt on 
			tm.transactionID = tt.transactionID 
		inner join tb_customer cus on 
			tm.entityID = cus.entityID 
		inner join tb_naturales nat on 
			cus.entityID = nat.entityID 
		inner join tb_transaction_master_detail tmd on 
			tm.companyID = tmd.companyID and 
			tm.transactionID = tmd.transactionID and 
			tm.transactionMasterID = tmd.transactionMasterID 	
		inner join tb_workflow_stage ws on 
			tm.statusID = ws.workflowStageID  
		inner join tb_transaction_master tm2 on 
			tmd.reference1 = tm2.transactionNumber and 
			tmd.companyID = tm2.companyID and 
			tm.entityID = tm2.entityID 
		inner join tb_currency cur on 
			tm2.currencyID = cur.currencyID 
		inner join tb_user us on 
			us.userID = tm.createdBy 
		inner join tb_branch braus on 
			braus.branchID = us.locationID
		inner join tb_company comp on 
			comp.companyID = tm.companyID 
	where
		tm.transactionID in  (23,24,25) 		
		and 
		(
				(
					comp.flavorID = 326  and 
					tm.transactionID in (23)
				)  or 
				(
					comp.flavorID != 326 
				)
		)		
		and tm.isActive = 1 
		and tmd.isActive = 1 
		and 
		(
			(braus.branchID = prBranchID and prBranchID != 0 )
			or 
			(prBranchID = 0)
		)
		and tm.companyID = prCompanyID 
		and tm.transactionOn  between prStartOn and prEndOn 
		and  
		(
				fn_get_access_ready(
					prCompanyID  , 
					prUserID  , 173, 
					tm.createdBy  , 0 
				) = 1 
		) and 
		(
					  (tm.createdBy = prUserIDFilter and prUserIDFilter != 0 )
						or 
						(prUserIDFilter = 0)
		) and 
		ws.aplicable = 1   
	order by 
		tm.transactionOn;   
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_box_get_report_attendance
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_box_get_report_attendance`;
delimiter ;;
CREATE PROCEDURE `pr_box_get_report_attendance`(IN `prUserID` int,IN `prTokenID` varchar(150),IN `prCompanyID` int,IN `prAuthorization` int,IN `prStartOn` datetime,IN `prEndOn` datetime,IN `prUserIDFilter` int)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'lista de asistencia'
BEGIN

	

	

	

	SELECT 

		c.transactionNumber,

		c.createdOn,

		ws.`name` as estado,

		ci.`name` as prioridad, 

		nat.firstName,

		c.reference1  AS solvencia,

		c.reference2 AS proximoPago,

		c.reference4 AS diasProximoPago,

		c.reference3 AS vencimiento

	FROM 

		tb_transaction_master c 

		inner join tb_naturales nat on 

			c.entityID = nat.entityID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = c.statusID 

		inner join tb_catalog_item ci  on 

			ci.catalogItemID = c.priorityID 

	where 

		c.isActive = 1 and 

		c.transactionID = 32 

	order by 

		c.transactionNumber; 

	

	 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_box_get_report_closed
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_box_get_report_closed`;
delimiter ;;
CREATE PROCEDURE `pr_box_get_report_closed`(IN `prUserID` int,IN `prTokenID` varchar(150),IN `prCompanyID` int,IN `prAuthorization` int,IN `prStartOn` datetime,IN `prEndOn` datetime,IN `prUserIDFilter` int)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'lista de abonos de los clientes'
BEGIN
	
		DECLARE varCurrencyCompras INT DEFAULT 0;	
	DECLARE varCurrencyReporte INT DEFAULT 0;	
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);			
	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		
	DECLARE amount1 DECIMAL(18,4) DEFAULT 0;		
	DECLARE amount2 DECIMAL(18,4) DEFAULT 0;		
	DECLARE amount3 DECIMAL(18,4) DEFAULT 0;		
	DECLARE amount4 DECIMAL(18,4) DEFAULT 0;		
	
	DECLARE currencyIDNameSource VARCHAR(250);
	DECLARE currencyIDNameTarget VARCHAR(250);	
	DECLARE moneda_ VARCHAR(50); 
	DECLARE currencyID_ INT DEFAULT 0;
	DECLARE currencyIDTarget_ INT DEFAULT 0;
	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;
	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;
	DECLARE PERMISSION_NONE INT DEFAULT -1;
	DECLARE PERMISSION_ALL INT DEFAULT 0;
	DECLARE PERMISSION_BRANCH INT DEFAULT 1;
	DECLARE PERMISSION_ME INT DEFAULT 2; 		
	DECLARE isAdmin_ INT DEFAULT   0; 
	DECLARE varDif DECIMAL(19,4) DEFAULT 0;
	DECLARE varCompanyType VARCHAR(50);
	
	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	
	SET varCompanyType					= (SELECT c.type FROM tb_company c where c.companyID = prCompanyID);
	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		
	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

	
	select 
		r.isAdmin into isAdmin_ 
	from 
		tb_user u 
		inner join tb_membership me on 
			u.userID = me.userID 
		inner join tb_role r on 
			me.roleID = r.roleID 
	where
		me.userID = prUserID and r.isAdmin = 1 limit 1 ;
		
	
	set isAdmin_ = (case when isAdmin_ is null then 0 else isAdmin_ end);

	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);
	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);
	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);
	
	
	create table tb_tmp_closed (
		substitulo varchar(250),
		codigo varchar(50),
		nombre varchar(150),
		cantidad varchar(150),
		subtotal varchar(150),
		total decimal(19,2),
		moneda int ,
		tipoCambio decimal(19,2),
		comandoProce varchar(150),
		sumary varchar(50)
		
	);
	
	
	
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'MinMax',
		'Estado',
		'001.001',
		'PRI Y ULTI.',
		IFNULL(min(tm.transactionMasterID),0) as min,
		IFNULL(max(tm.transactionMasterID),0) as max,
		0 as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
	where 		 
		tm.isActive = 1 and 
		tm.companyID = prCompanyID and 
		tm.transactionID = 19  and 
		tm.statusID = 67  and 		
		tm.createdOn between prStartOn  and prEndOn ;
		
	update tb_tmp_closed set 
		cantidad = IFNULL((select u.transactionNumber from tb_transaction_master u where u.transactionMasterID = cantidad ),0),
		subtotal = IFNULL((select u.transactionNumber from tb_transaction_master u where u.transactionMasterID = subtotal ),0)
	where 
		codigo = '001.001';
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Estado',
		'001.002',
		'TRAN. Eli',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
	where 		 
		tm.isActive = 1 and 
		tm.transactionID = 19  and 
		tm.statusID = 68  and  
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Estado',
		'001.003',
		'TRAN. Reg',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 		
	where 		
		tm.isActive = 1 and 
		tm.transactionID = 19  and 
		tm.statusID in ( 66  ) and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Estado',
		'001.003',
		'TRAN. Apli',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 		
	where 		
		tm.isActive = 1 and 
		tm.companyID = prCompanyID and 
		tm.transactionID = 19  and 
		tm.statusID in (67  ) and 
		tm.createdOn between prStartOn  and prEndOn ;
	
	
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'Si',
		'Default',
		'Apertura C$',
		'002.001',
		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,
		IFNULL(sum(tmdd.quantity),0) as cantidad,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
		inner join tb_transaction_master_denomination tmdd  on 
			tmdd.transactionMasterID = tm.transactionMasterID 
		inner join tb_catalog_item ci on 
			tmdd.catalogItemID = ci.catalogItemID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 29  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tmdd.quantity > 0 and 
		tm.currencyID = 1 and 
		ci2.`name` = 'Apertura' and 
		tm.createdOn between prStartOn  and prEndOn 
	group by 
		ci.`name`; 
		
		
		
	
		
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'Si',
		'Default',
		'Apertura $',
		'003.001',
		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,
		IFNULL(sum(tmdd.quantity),0) as cantidad,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
		inner join tb_transaction_master_denomination tmdd  on 
			tmdd.transactionMasterID = tm.transactionMasterID 
		inner join tb_catalog_item ci on 
			tmdd.catalogItemID = ci.catalogItemID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 29  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tmdd.quantity > 0 and 
		tm.currencyID = 2 and 
		ci2.`name` = 'Apertura' and 
		tm.createdOn between prStartOn  and prEndOn 
	group by 
		ci.`name`; 
		
	
		
	
	
		
		
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		*
	from 
		(
		select 
			'Si',
			'Default',
			concat('Formas de Pago ', case when tm.currencyID = 2 then '$' else 'C$' end  ) ,
			'004.001',
			concat('Efectivo ' , case when tm.currencyID = 2 then '$' else 'C$' end),
			IFNULL(count(tmi.receiptAmount),0) as efectivo,
			IFNULL(sum(tmi.receiptAmount),0)as transferencia,
			IFNULL(sum(tmi.receiptAmount),0) as tarjeta,
			1 as moneda, 
			0 as tipoCambio 
		from 
			tb_transaction t 
			inner join tb_transaction_master tm  on 
				t.transactionID = tm.transactionID 
			inner join tb_transaction_causal tmc on 
				tm.transactionCausalID = tmc.transactionCausalID 
			inner join tb_workflow_stage ws on 
				ws.workflowStageID = tm.statusID 
			inner join tb_transaction_master_info tmi on 
				tmi.transactionMasterID = tm.transactionMasterID 
		where 
			t.transactionID = 19  and 
			tmc.transactionCausalID in (23  , 21  ) and 
			tm.isActive = 1 and 
			ws.aplicable = 1 and 			
			tmi.receiptAmount > 0 and 
			tm.companyID = prCompanyID and 			
			tm.createdOn between prStartOn  and prEndOn 
		
		union all 
		
		select 
			'Si',
			'Default',
			concat('Formas de Pago ' , case when tm.currencyID = 2 then '$' else 'C$' end ),
			'004.002',
			concat('Tarjeta ', case when tm.currencyID = 2 then '$' else 'C$' end),
			IFNULL(count(tmi.receiptAmountCard),0) as efectivo,
			IFNULL(sum(tmi.receiptAmountCard),0)as transferencia,
			IFNULL(sum(tmi.receiptAmountCard),0) as tarjeta,
			1 as moneda, 
			0 as tipoCambio 
		from 
			tb_transaction t 
			inner join tb_transaction_master tm  on 
				t.transactionID = tm.transactionID 
			inner join tb_transaction_causal tmc on 
				tm.transactionCausalID = tmc.transactionCausalID 
			inner join tb_workflow_stage ws on 
				ws.workflowStageID = tm.statusID 
			inner join tb_transaction_master_info tmi on 
				tmi.transactionMasterID = tm.transactionMasterID 
		where 
			t.transactionID = 19  and 
			tmc.transactionCausalID in (23  , 21  ) and 
			tm.isActive = 1 and 
			ws.aplicable = 1 and 			
			tmi.receiptAmountCard > 0 and 
			tm.companyID = prCompanyID and 
			tm.createdOn between prStartOn  and prEndOn 
			
		union all 
		
		select 
			'Si',
			'Default',
			concat('Formas de Pago ', case when tm.currencyID = 2 then '$' else 'C$' end),
			'004.003',
			concat('Transferencia ', case when tm.currencyID = 2 then '$' else 'C$' end ),
			IFNULL(count(tmi.receiptAmountBank),0) as efectivo,
			IFNULL(sum(tmi.receiptAmountBank),0)as transferencia,
			IFNULL(sum(tmi.receiptAmountBank),0) as tarjeta,
			1 as moneda, 
			0 as tipoCambio 
		from 
			tb_transaction t 
			inner join tb_transaction_master tm  on 
				t.transactionID = tm.transactionID 
			inner join tb_transaction_causal tmc on 
				tm.transactionCausalID = tmc.transactionCausalID 
			inner join tb_workflow_stage ws on 
				ws.workflowStageID = tm.statusID 
			inner join tb_transaction_master_info tmi on 
				tmi.transactionMasterID = tm.transactionMasterID 
		where 
			t.transactionID = 19  and 
			tmc.transactionCausalID in (23  , 21  ) and 
			tm.isActive = 1 and 
			ws.aplicable = 1 and 		
			tmi.receiptAmountBank > 0 and 
			tm.companyID = prCompanyID and 
			tm.createdOn between prStartOn  and prEndOn 
	) as kl
	where 
		kl.tarjeta > 0 ;
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		*
	from 
		(
			select 
				'Si',
				'Default',
				concat('Formas de Pago ', case when tm.currencyID = 2 then 'C$' else '$' end),
				'005.001',
				concat('Efectivo ', case when tm.currencyID = 2 then 'C$' else '$' end ),
				IFNULL(count(IFNULL(tmi.receiptAmountDol,0)),0) as efectivo,
				IFNULL(sum(IFNULL(tmi.receiptAmountDol,0)),0) as transferencia,
				IFNULL(sum(IFNULL(tmi.receiptAmountDol,0)),0) as tarjeta,
				1 as moneda, 
				0 as tipoCambio 
			from 
				tb_transaction t 
				inner join tb_transaction_master tm  on 
					t.transactionID = tm.transactionID 
				inner join tb_transaction_causal tmc on 
					tm.transactionCausalID = tmc.transactionCausalID 
				inner join tb_workflow_stage ws on 
					ws.workflowStageID = tm.statusID 
				inner join tb_transaction_master_info tmi on 
					tmi.transactionMasterID = tm.transactionMasterID 
			where 
				t.transactionID = 19  and 
				tmc.transactionCausalID in (23  , 21  ) and 
				tm.isActive = 1 and 
				ws.aplicable = 1 and 
				tmi.receiptAmountDol > 0 and 
				tm.companyID = prCompanyID and 
				tm.createdOn between prStartOn  and prEndOn 
				
			union all 
			
			select 
				'Si',
				'Default',
				concat('Formas de Pago ', case when tm.currencyID = 2 then 'C$' else '$' end),
				'005.002',
				concat('Tarjeta ',case when tm.currencyID = 2 then 'C$' else '$' end),
				IFNULL(count(IFNULL(tmi.receiptAmountCardDol,0)),0) as efectivo,
				IFNULL(sum(IFNULL(tmi.receiptAmountCardDol,0)),0) as transferencia,
				IFNULL(sum(IFNULL(tmi.receiptAmountCardDol,0)),0) as tarjeta,
				1 as moneda, 
				0 as tipoCambio 
			from 
				tb_transaction t 
				inner join tb_transaction_master tm  on 
					t.transactionID = tm.transactionID 
				inner join tb_transaction_causal tmc on 
					tm.transactionCausalID = tmc.transactionCausalID 
				inner join tb_workflow_stage ws on 
					ws.workflowStageID = tm.statusID 
				inner join tb_transaction_master_info tmi on 
					tmi.transactionMasterID = tm.transactionMasterID 
			where 
				t.transactionID = 19  and 
				tmc.transactionCausalID in (23  , 21  ) and 
				tm.isActive = 1 and 
				ws.aplicable = 1 and 
				tmi.receiptAmountCardDol > 0 and 
				tm.companyID = prCompanyID and 
				tm.createdOn between prStartOn  and prEndOn 
				
			union all 
			
			select 
				'Si',
				'Default',
				concat('Formas de Pago ',case when tm.currencyID = 2 then 'C$' else '$' end),
				'005.003',
				concat('Transferencia ',case when tm.currencyID = 2 then 'C$' else '$' end),
				IFNULL(count(IFNULL(tmi.receiptAmountBankDol,0)),0) as efectivo,
				IFNULL(sum(IFNULL(tmi.receiptAmountBankDol,0)),0) as transferencia,
				IFNULL(sum(IFNULL(tmi.receiptAmountBankDol,0)),0) as tarjeta,
				1 as moneda, 
				0 as tipoCambio 
			from 
				tb_transaction t 
				inner join tb_transaction_master tm  on 
					t.transactionID = tm.transactionID 
				inner join tb_transaction_causal tmc on 
					tm.transactionCausalID = tmc.transactionCausalID 
				inner join tb_workflow_stage ws on 
					ws.workflowStageID = tm.statusID 
				inner join tb_transaction_master_info tmi on 
					tmi.transactionMasterID = tm.transactionMasterID 
			where 
				t.transactionID = 19  and 
				tmc.transactionCausalID in (23  , 21  ) and 
				tm.isActive = 1 and 
				ws.aplicable = 1 and 
				tmi.receiptAmountBankDol > 0 and 
				tm.companyID = prCompanyID and 
				tm.createdOn between prStartOn  and prEndOn 
	) as kl
 where 
	kl.tarjeta > 0 ; 				
			
		
	
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.001',
		concat('Vent Cred ',case when tm.currencyID = 1 then 'C$' else '$' end),
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
	where 
		t.transactionID = 19  and 
		tmc.transactionCausalID in (22  , 24  ) and 
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.002',
		concat('Vent Cont ',case when tm.currencyID = 1 then 'C$' else '$' end),
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
	where 
		t.transactionID = 19  and 
		tmc.transactionCausalID in (23  , 21  ) and 
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
	
	
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.003',
		'Abonos C$',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tmi.receiptAmount),0) as subtotal,
		IFNULL(sum(tmi.receiptAmount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_master_info tmi on 
			tm.transactionMasterID = tmi.transactionMasterID  
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
		inner join tb_company com on 
			com.companyID = t.companyID 
	where
		tm.transactionID = 23 and 
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn and 
		tmi.receiptAmount > 0 ;
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.004',
		'Abonos $',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tmi.receiptAmountDol),0) as subtotal,
		IFNULL(sum(tmi.receiptAmountDol),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_master_info tmi on 
			tm.transactionMasterID = tmi.transactionMasterID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
		inner join tb_company com on 
			com.companyID = t.companyID 
	where 
		tm.transactionID = 23 and 			
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn and 
		tmi.receiptAmountDol > 0 ;
	
	
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.005',
		'Ing. Efectivo C$',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 29  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.currencyID = 1 and 		
		ci2.`name` != 'Apertura' and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.006',
		'Ing. Efectivo $',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 29  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.currencyID = 2 and 
		ci2.`name` != 'Apertura' and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
		
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.007',
		'Primas C$',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(
		sum(
			tmi.receiptAmount + 
			tmi.receiptAmountDol +
			tmi.receiptAmountBank + 
			tmi.receiptAmountBankDol + 
			tmi.receiptAmountCard + 
			tmi.receiptAmountCardDol 
		),
		0) as subtotal,
		IFNULL(
		sum(
			tmi.receiptAmount + 
			tmi.receiptAmountDol + 
			tmi.receiptAmountBank + 
			tmi.receiptAmountBankDol + 
			tmi.receiptAmountCard + 
			tmi.receiptAmountCardDol 
		),
		0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_transaction_master_info tmi on 
			tmi.transactionMasterID = tm.transactionMasterID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
	where 
		t.transactionID = 19  and 
		tmc.transactionCausalID in (22  , 24  ) and 
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
		
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.008',
		'Egr. Efectivo C$',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 30  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.currencyID = 1 and 
		ci2.`name` != 'Cierre' and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
	
	
			
			
			
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.009',
		'Egr. Efectivo $',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 30  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.currencyID = 2 and 
		ci2.`name` != 'Cierre' and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
	
	
		
	
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'Si',
		'Default',
		'Cierre C$',
		'007.001',
		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,
		IFNULL(sum(tmdd.quantity),0) as cantidad,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
		inner join tb_transaction_master_denomination tmdd  on 
			tmdd.transactionMasterID = tm.transactionMasterID 
		inner join tb_catalog_item ci on 
			tmdd.catalogItemID = ci.catalogItemID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 30  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tmdd.quantity > 0 and 
		tm.currencyID = 1 and 
		ci2.`name` = 'Cierre' and 
		tm.createdOn between prStartOn  and prEndOn 
	group by 
		ci.`name`; 
		
	
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'Si',
		'Default',
		'Cierre $',
		'008.001',
		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,
		IFNULL(sum(tmdd.quantity),0) as cantidad,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
		inner join tb_transaction_master_denomination tmdd  on 
			tmdd.transactionMasterID = tm.transactionMasterID 
		inner join tb_catalog_item ci on 
			tmdd.catalogItemID = ci.catalogItemID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 30  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tmdd.quantity > 0 and 
		tm.currencyID = 2 and 
		ci2.`name` = 'Cierre' and 
		tm.createdOn between prStartOn  and prEndOn 
	group by 
		ci.`name`; 
		
	
	
		
	
	
	
	IF varCompanyType != "galmcuts" THEN 

		SET varDif = 0;	
		
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Vent Cont C$' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Apertura C$' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Abonos C$' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Primas C$' );
		SET varDif = varDif - (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'EGR. Efectivo C$' );
		SET varDif = varDif - (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Cierre C$' );
		insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
		values ('No','Default','Diferencia','009.001','Dif C$',0,ifnull(varDif,0),ifnull(varDif,0),1,0);
		
		
		
		SET varDif = 0;
		
		
		
		SET varDif = varDif + (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Vent Cont $' );
		SET varDif = varDif + (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Apertura $' );	
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Abonos $' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Primas $' );
		SET varDif = varDif - (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'EGR. Efectivo $' );
		SET varDif = varDif - (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Cierre $' );
		
		insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
		values ('No','Default','Diferencia','009.002','Dif $',0,ifnull(varDif,0),ifnull(varDif,0),1,0);
		
	end if;
	
	
	IF varCompanyType = "galmcuts" THEN 

		SET varDif = 0;	
		
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Vent Cont C$' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Apertura C$' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Abonos C$' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Primas C$' );
		SET varDif = varDif - (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'EGR. Efectivo C$' );
		SET varDif = varDif - (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Cierre C$' );
		insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
		values ('No','Default','Saldo Final Caja','009.001','C$',0,ifnull(varDif,0),ifnull(varDif,0),1,0);
		
		
		
		SET varDif = 0;
		
		
		
		SET varDif = varDif + (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Vent Cont $' );
		SET varDif = varDif + (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Apertura $' );	
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Abonos $' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Primas $' );
		SET varDif = varDif - (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'EGR. Efectivo $' );
		SET varDif = varDif - (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Cierre $' );
		
		insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
		values ('No','Default','Saldo Final Caja','009.002','$',0,ifnull(varDif,0),ifnull(varDif,0),1,0);
		
	end if;
	
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,moneda,tipoCambio,cantidad,subtotal,total) 
	select 
		z.sumary,
		z.comandoProces,
		z.subtitulo,
		z.codigo,
		z.nombre,		
		z.moneda,
		z.tipoCambio,
		sum(z.cantidad) as cantidad,
		sum(z.subtotal) as subtotal,
		sum(z.total) as total
	from 
		(
			select 
				'Si' as sumary,
				'Default' as comandoProces,
				'Detalle de Venta' as subtitulo,
				'010.001' as codigo,
				i.`name` as nombre,		
				td.quantity as cantidad,
				td.amount as subtotal,
				td.amount as total,
				1 as moneda, 
				0 as tipoCambio 
			from 
				tb_transaction t 
				inner join tb_transaction_master tm  on 
					t.transactionID = tm.transactionID 
				inner join tb_transaction_master_detail td on 
					td.transactionMasterID = tm.transactionMasterID 
				inner join tb_item i on 
					td.componentItemID = i.itemID 
				inner join tb_item_category  cat on 
					cat.inventoryCategoryID = i.inventoryCategoryID 
				inner join tb_transaction_causal tmc on 
					tm.transactionCausalID = tmc.transactionCausalID 
				inner join tb_workflow_stage ws on 
					ws.workflowStageID = tm.statusID 
			where 
				t.transactionID = 19  and 
				tm.isActive = 1 and 
				ws.aplicable = 1 and 
				td.isActive = 1 and 
				tm.companyID = prCompanyID and 
				tm.createdOn between prStartOn  and prEndOn 	
			order by 
				cat.`name`,i.`name`
		) z 
	group by 
		z.sumary,
		z.comandoProces,
		z.subtitulo,
		z.codigo,
		z.nombre,		
		z.moneda,
		z.tipoCambio;
	
		
	
	SET amount1 = (select k.total from  tb_tmp_closed k where  k.nombre = 'Vent Cont C$');
	SET amount2 = (select k.total from  tb_tmp_closed k where  k.nombre = 'Efectivo $'  and k.substitulo = 'Formas de Pago $');
	SET amount3 = (select k.total from  tb_tmp_closed k where  k.nombre = 'Efectivo C$' and k.substitulo = 'Formas de Pago C$');
	SET amount4 = (select k.total from  tb_tmp_closed k where  k.nombre = 'Tarjeta C$' and k.substitulo = 'Formas de Pago C$');
	SET amount1 = IFNULL(amount1,0);
	SET amount2 = IFNULL(amount2,0);
	SET amount3 = IFNULL(amount3,0);
	SET amount4 = IFNULL(amount4,0);
	
	
	UPDATE tb_tmp_closed set 
		total = amount1 -  (amount2 * 36.5) - amount4,
		subtotal = amount1 -  (amount2 * 36.5) - amount4 
	WHERE 
		nombre = 'Efectivo C$' and 
		substitulo = 'Formas de Pago C$';
		
		
	
	
		
			
	select 
		sumary,
		comandoProce,
		substitulo,
		codigo,
		nombre,
		cantidad,
		subtotal,
		total,
		moneda,
		tipoCambio
	from 
		tb_tmp_closed 
	order by 
		codigo asc ;
		
			
	drop table tb_tmp_closed;		
	
	
	 
	 
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_box_get_report_closed_distrito4199
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_box_get_report_closed_distrito4199`;
delimiter ;;
CREATE PROCEDURE `pr_box_get_report_closed_distrito4199`(IN `prUserID` int,IN `prTokenID` varchar(150),IN `prCompanyID` int,IN `prAuthorization` int,IN `prStartOn` datetime,IN `prEndOn` datetime,IN `prUserIDFilter` int)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'lista de abonos de los clientes'
BEGIN
	
		DECLARE varCurrencyCompras INT DEFAULT 0;	
	DECLARE varCurrencyReporte INT DEFAULT 0;	
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);			
	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		
	DECLARE amount1 DECIMAL(18,4) DEFAULT 0;		
	DECLARE amount2 DECIMAL(18,4) DEFAULT 0;		
	DECLARE amount3 DECIMAL(18,4) DEFAULT 0;		
	DECLARE amount4 DECIMAL(18,4) DEFAULT 0;		
	
	DECLARE currencyIDNameSource VARCHAR(250);
	DECLARE currencyIDNameTarget VARCHAR(250);	
	DECLARE moneda_ VARCHAR(50); 
	DECLARE currencyID_ INT DEFAULT 0;
	DECLARE currencyIDTarget_ INT DEFAULT 0;
	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;
	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;
	DECLARE PERMISSION_NONE INT DEFAULT -1;
	DECLARE PERMISSION_ALL INT DEFAULT 0;
	DECLARE PERMISSION_BRANCH INT DEFAULT 1;
	DECLARE PERMISSION_ME INT DEFAULT 2; 		
	DECLARE isAdmin_ INT DEFAULT   0; 
	DECLARE varDif DECIMAL(19,4) DEFAULT 0;
	DECLARE varCompanyType VARCHAR(50);
	
	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	
	SET varCompanyType					= (SELECT c.type FROM tb_company c where c.companyID = prCompanyID);
	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		
	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

	
	select 
		r.isAdmin into isAdmin_ 
	from 
		tb_user u 
		inner join tb_membership me on 
			u.userID = me.userID 
		inner join tb_role r on 
			me.roleID = r.roleID 
	where
		me.userID = prUserID and r.isAdmin = 1 limit 1 ;
		
	
	set isAdmin_ = (case when isAdmin_ is null then 0 else isAdmin_ end);

	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);
	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);
	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);
	
	
	create table tb_tmp_closed (
		substitulo varchar(250),
		codigo varchar(50),
		nombre varchar(150),
		cantidad varchar(150),
		subtotal varchar(150),
		total decimal(19,2),
		moneda int ,
		tipoCambio decimal(19,2),
		comandoProce varchar(150),
		sumary varchar(50)
		
	);
	
	
	
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'MinMax',
		'Estado',
		'001.001',
		'PRI Y ULTI.',
		IFNULL(min(tm.transactionMasterID),0) as min,
		IFNULL(max(tm.transactionMasterID),0) as max,
		0 as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
	where 		 
		tm.isActive = 1 and 
		tm.companyID = prCompanyID and 
		tm.transactionID = 19  and 
		tm.statusID = 67  and 		
		tm.createdOn between prStartOn  and prEndOn ;
		
	update tb_tmp_closed set 
		cantidad = IFNULL((select u.transactionNumber from tb_transaction_master u where u.transactionMasterID = cantidad ),0),
		subtotal = IFNULL((select u.transactionNumber from tb_transaction_master u where u.transactionMasterID = subtotal ),0)
	where 
		codigo = '001.001';
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Estado',
		'001.002',
		'TRAN. Eli',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
	where 		 
		tm.isActive = 1 and 
		tm.transactionID = 19  and 
		tm.statusID = 68  and  
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Estado',
		'001.003',
		'TRAN. Reg',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 		
	where 		
		tm.isActive = 1 and 
		tm.transactionID = 19  and 
		tm.statusID in ( 66  ) and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Estado',
		'001.003',
		'TRAN. Apli',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 		
	where 		
		tm.isActive = 1 and 
		tm.companyID = prCompanyID and 
		tm.transactionID = 19  and 
		tm.statusID in (67  ) and 
		tm.createdOn between prStartOn  and prEndOn ;
	
	
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'Si',
		'Default',
		'Apertura C$',
		'002.001',
		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,
		IFNULL(sum(tmdd.quantity),0) as cantidad,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
		inner join tb_transaction_master_denomination tmdd  on 
			tmdd.transactionMasterID = tm.transactionMasterID 
		inner join tb_catalog_item ci on 
			tmdd.catalogItemID = ci.catalogItemID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 29  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tmdd.quantity > 0 and 
		tm.currencyID = 1 and 
		ci2.`name` = 'Apertura' and 
		tm.createdOn between prStartOn  and prEndOn 
	group by 
		ci.`name`; 
		
		
		
	
		
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'Si',
		'Default',
		'Apertura $',
		'003.001',
		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,
		IFNULL(sum(tmdd.quantity),0) as cantidad,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
		inner join tb_transaction_master_denomination tmdd  on 
			tmdd.transactionMasterID = tm.transactionMasterID 
		inner join tb_catalog_item ci on 
			tmdd.catalogItemID = ci.catalogItemID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 29  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tmdd.quantity > 0 and 
		tm.currencyID = 2 and 
		ci2.`name` = 'Apertura' and 
		tm.createdOn between prStartOn  and prEndOn 
	group by 
		ci.`name`; 
		
	
		
	
	
		
		
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		*
	from 
		(
		select 
			'Si',
			'Default',
			concat('Formas de Pago ', case when tm.currencyID = 2 then '$' else 'C$' end  ) ,
			'004.001',
			concat('Efectivo ' , case when tm.currencyID = 2 then '$' else 'C$' end),
			IFNULL(count(tmi.receiptAmount),0) as efectivo,
			IFNULL(sum(tmi.receiptAmount),0)as transferencia,
			IFNULL(sum(tmi.receiptAmount),0) as tarjeta,
			1 as moneda, 
			0 as tipoCambio 
		from 
			tb_transaction t 
			inner join tb_transaction_master tm  on 
				t.transactionID = tm.transactionID 
			inner join tb_transaction_causal tmc on 
				tm.transactionCausalID = tmc.transactionCausalID 
			inner join tb_workflow_stage ws on 
				ws.workflowStageID = tm.statusID 
			inner join tb_transaction_master_info tmi on 
				tmi.transactionMasterID = tm.transactionMasterID 
		where 
			t.transactionID = 19  and 
			tmc.transactionCausalID in (23  , 21  ) and 
			tm.isActive = 1 and 
			ws.aplicable = 1 and 			
			tmi.receiptAmount > 0 and 
			tm.companyID = prCompanyID and 			
			tm.createdOn between prStartOn  and prEndOn 
		
		union all 
		
		select 
			'Si',
			'Default',
			concat('Formas de Pago ' , case when tm.currencyID = 2 then '$' else 'C$' end ),
			'004.002',
			concat('Tarjeta ', case when tm.currencyID = 2 then '$' else 'C$' end),
			IFNULL(count(tmi.receiptAmountCard),0) as efectivo,
			IFNULL(sum(tmi.receiptAmountCard),0)as transferencia,
			IFNULL(sum(tmi.receiptAmountCard),0) as tarjeta,
			1 as moneda, 
			0 as tipoCambio 
		from 
			tb_transaction t 
			inner join tb_transaction_master tm  on 
				t.transactionID = tm.transactionID 
			inner join tb_transaction_causal tmc on 
				tm.transactionCausalID = tmc.transactionCausalID 
			inner join tb_workflow_stage ws on 
				ws.workflowStageID = tm.statusID 
			inner join tb_transaction_master_info tmi on 
				tmi.transactionMasterID = tm.transactionMasterID 
		where 
			t.transactionID = 19  and 
			tmc.transactionCausalID in (23  , 21  ) and 
			tm.isActive = 1 and 
			ws.aplicable = 1 and 			
			tmi.receiptAmountCard > 0 and 
			tm.companyID = prCompanyID and 
			tm.createdOn between prStartOn  and prEndOn 
			
		union all 
		
		select 
			'Si',
			'Default',
			concat('Formas de Pago ', case when tm.currencyID = 2 then '$' else 'C$' end),
			'004.003',
			concat('Transferencia ', case when tm.currencyID = 2 then '$' else 'C$' end ),
			IFNULL(count(tmi.receiptAmountBank),0) as efectivo,
			IFNULL(sum(tmi.receiptAmountBank),0)as transferencia,
			IFNULL(sum(tmi.receiptAmountBank),0) as tarjeta,
			1 as moneda, 
			0 as tipoCambio 
		from 
			tb_transaction t 
			inner join tb_transaction_master tm  on 
				t.transactionID = tm.transactionID 
			inner join tb_transaction_causal tmc on 
				tm.transactionCausalID = tmc.transactionCausalID 
			inner join tb_workflow_stage ws on 
				ws.workflowStageID = tm.statusID 
			inner join tb_transaction_master_info tmi on 
				tmi.transactionMasterID = tm.transactionMasterID 
		where 
			t.transactionID = 19  and 
			tmc.transactionCausalID in (23  , 21  ) and 
			tm.isActive = 1 and 
			ws.aplicable = 1 and 		
			tmi.receiptAmountBank > 0 and 
			tm.companyID = prCompanyID and 
			tm.createdOn between prStartOn  and prEndOn 
	) as kl
	where 
		kl.tarjeta > 0 ;
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		*
	from 
		(
			select 
				'Si',
				'Default',
				concat('Formas de Pago ', case when tm.currencyID = 2 then 'C$' else '$' end),
				'005.001',
				concat('Efectivo ', case when tm.currencyID = 2 then 'C$' else '$' end ),
				IFNULL(count(IFNULL(tmi.receiptAmountDol,0)),0) as efectivo,
				IFNULL(sum(IFNULL(tmi.receiptAmountDol,0)),0) as transferencia,
				IFNULL(sum(IFNULL(tmi.receiptAmountDol,0)),0) as tarjeta,
				1 as moneda, 
				0 as tipoCambio 
			from 
				tb_transaction t 
				inner join tb_transaction_master tm  on 
					t.transactionID = tm.transactionID 
				inner join tb_transaction_causal tmc on 
					tm.transactionCausalID = tmc.transactionCausalID 
				inner join tb_workflow_stage ws on 
					ws.workflowStageID = tm.statusID 
				inner join tb_transaction_master_info tmi on 
					tmi.transactionMasterID = tm.transactionMasterID 
			where 
				t.transactionID = 19  and 
				tmc.transactionCausalID in (23  , 21  ) and 
				tm.isActive = 1 and 
				ws.aplicable = 1 and 
				tmi.receiptAmountDol > 0 and 
				tm.companyID = prCompanyID and 
				tm.createdOn between prStartOn  and prEndOn 
				
			union all 
			
			select 
				'Si',
				'Default',
				concat('Formas de Pago ', case when tm.currencyID = 2 then 'C$' else '$' end),
				'005.002',
				concat('Tarjeta ',case when tm.currencyID = 2 then 'C$' else '$' end),
				IFNULL(count(IFNULL(tmi.receiptAmountCardDol,0)),0) as efectivo,
				IFNULL(sum(IFNULL(tmi.receiptAmountCardDol,0)),0) as transferencia,
				IFNULL(sum(IFNULL(tmi.receiptAmountCardDol,0)),0) as tarjeta,
				1 as moneda, 
				0 as tipoCambio 
			from 
				tb_transaction t 
				inner join tb_transaction_master tm  on 
					t.transactionID = tm.transactionID 
				inner join tb_transaction_causal tmc on 
					tm.transactionCausalID = tmc.transactionCausalID 
				inner join tb_workflow_stage ws on 
					ws.workflowStageID = tm.statusID 
				inner join tb_transaction_master_info tmi on 
					tmi.transactionMasterID = tm.transactionMasterID 
			where 
				t.transactionID = 19  and 
				tmc.transactionCausalID in (23  , 21  ) and 
				tm.isActive = 1 and 
				ws.aplicable = 1 and 
				tmi.receiptAmountCardDol > 0 and 
				tm.companyID = prCompanyID and 
				tm.createdOn between prStartOn  and prEndOn 
				
			union all 
			
			select 
				'Si',
				'Default',
				concat('Formas de Pago ',case when tm.currencyID = 2 then 'C$' else '$' end),
				'005.003',
				concat('Transferencia ',case when tm.currencyID = 2 then 'C$' else '$' end),
				IFNULL(count(IFNULL(tmi.receiptAmountBankDol,0)),0) as efectivo,
				IFNULL(sum(IFNULL(tmi.receiptAmountBankDol,0)),0) as transferencia,
				IFNULL(sum(IFNULL(tmi.receiptAmountBankDol,0)),0) as tarjeta,
				1 as moneda, 
				0 as tipoCambio 
			from 
				tb_transaction t 
				inner join tb_transaction_master tm  on 
					t.transactionID = tm.transactionID 
				inner join tb_transaction_causal tmc on 
					tm.transactionCausalID = tmc.transactionCausalID 
				inner join tb_workflow_stage ws on 
					ws.workflowStageID = tm.statusID 
				inner join tb_transaction_master_info tmi on 
					tmi.transactionMasterID = tm.transactionMasterID 
			where 
				t.transactionID = 19  and 
				tmc.transactionCausalID in (23  , 21  ) and 
				tm.isActive = 1 and 
				ws.aplicable = 1 and 
				tmi.receiptAmountBankDol > 0 and 
				tm.companyID = prCompanyID and 
				tm.createdOn between prStartOn  and prEndOn 
	) as kl
 where 
	kl.tarjeta > 0 ; 				
			
		
	
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.001',
		concat('Vent Cred ',case when tm.currencyID = 1 then 'C$' else '$' end),
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
	where 
		t.transactionID = 19  and 
		tmc.transactionCausalID in (22  , 24  ) and 
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.002',
		concat('Vent Cont ',case when tm.currencyID = 1 then 'C$' else '$' end),
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
	where 
		t.transactionID = 19  and 
		tmc.transactionCausalID in (23  , 21  ) and 
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
	
	
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.003',
		'Abonos C$',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tmi.receiptAmount),0) as subtotal,
		IFNULL(sum(tmi.receiptAmount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_master_info tmi on 
			tm.transactionMasterID = tmi.transactionMasterID  
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
		inner join tb_company com on 
			com.companyID = t.companyID 
	where
		tm.transactionID = 23 and 
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn and 
		tmi.receiptAmount > 0 ;
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.004',
		'Abonos $',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tmi.receiptAmountDol),0) as subtotal,
		IFNULL(sum(tmi.receiptAmountDol),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_master_info tmi on 
			tm.transactionMasterID = tmi.transactionMasterID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
		inner join tb_company com on 
			com.companyID = t.companyID 
	where 
		tm.transactionID = 23 and 			
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn and 
		tmi.receiptAmountDol > 0 ;
	
	
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.005',
		'Ing. Efectivo C$',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 29  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.currencyID = 1 and 		
		ci2.`name` != 'Apertura' and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.006',
		'Ing. Efectivo $',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 29  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.currencyID = 2 and 
		ci2.`name` != 'Apertura' and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
		
		
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.007',
		'Primas C$',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(
		sum(
			tmi.receiptAmount + 
			tmi.receiptAmountDol +
			tmi.receiptAmountBank + 
			tmi.receiptAmountBankDol + 
			tmi.receiptAmountCard + 
			tmi.receiptAmountCardDol 
		),
		0) as subtotal,
		IFNULL(
		sum(
			tmi.receiptAmount + 
			tmi.receiptAmountDol + 
			tmi.receiptAmountBank + 
			tmi.receiptAmountBankDol + 
			tmi.receiptAmountCard + 
			tmi.receiptAmountCardDol 
		),
		0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_transaction_master_info tmi on 
			tmi.transactionMasterID = tm.transactionMasterID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
	where 
		t.transactionID = 19  and 
		tmc.transactionCausalID in (22  , 24  ) and 
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
		
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.008',
		'Egr. Efectivo C$',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 30  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.currencyID = 1 and 
		ci2.`name` != 'Cierre' and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
	
	
			
			
			
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'No',
		'Default',
		'Operaciones',
		'006.009',
		'Egr. Efectivo $',
		IFNULL(count(tm.transactionMasterID),0) as cantidad,
		IFNULL(sum(tm.amount),0) as subtotal,
		IFNULL(sum(tm.amount),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 30  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.currencyID = 2 and 
		ci2.`name` != 'Cierre' and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and prEndOn ;
		
	
	
		
	
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'Si',
		'Default',
		'Cierre C$',
		'007.001',
		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,
		IFNULL(sum(tmdd.quantity),0) as cantidad,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
		inner join tb_transaction_master_denomination tmdd  on 
			tmdd.transactionMasterID = tm.transactionMasterID 
		inner join tb_catalog_item ci on 
			tmdd.catalogItemID = ci.catalogItemID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 30  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tmdd.quantity > 0 and 
		tm.currencyID = 1 and 
		ci2.`name` = 'Cierre' and 
		tm.createdOn between prStartOn  and prEndOn 
	group by 
		ci.`name`; 
		
	
		
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
	select 
		'Si',
		'Default',
		'Cierre $',
		'008.001',
		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,
		IFNULL(sum(tmdd.quantity),0) as cantidad,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,
		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,
		1 as moneda, 
		0 as tipoCambio 
	from 
		tb_transaction t 
		inner join tb_transaction_master tm  on 
			t.transactionID = tm.transactionID 
		inner join tb_transaction_causal tmc on 
			tm.transactionCausalID = tmc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
		inner join tb_transaction_master_denomination tmdd  on 
			tmdd.transactionMasterID = tm.transactionMasterID 
		inner join tb_catalog_item ci on 
			tmdd.catalogItemID = ci.catalogItemID 		
		inner join tb_catalog_item ci2 on 
			ci2.catalogItemID = tm.areaID 
	where 
		t.transactionID = 30  and 		
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.companyID = prCompanyID and 
		tmdd.quantity > 0 and 
		tm.currencyID = 2 and 
		ci2.`name` = 'Cierre' and 
		tm.createdOn between prStartOn  and prEndOn 
	group by 
		ci.`name`; 
		
	
	
		
	
	
	
	IF varCompanyType != "galmcuts" THEN 

		SET varDif = 0;	
		
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Vent Cont C$' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Apertura C$' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Abonos C$' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Primas C$' );
		SET varDif = varDif - (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'EGR. Efectivo C$' );
		SET varDif = varDif - (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Cierre C$' );
		insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
		values ('No','Default','Diferencia','009.001','Dif C$',0,ifnull(varDif,0),ifnull(varDif,0),1,0);
		
		
		
		SET varDif = 0;
		
		
		
		SET varDif = varDif + (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Vent Cont $' );
		SET varDif = varDif + (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Apertura $' );	
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Abonos $' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Primas $' );
		SET varDif = varDif - (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'EGR. Efectivo $' );
		SET varDif = varDif - (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Cierre $' );
		
		insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
		values ('No','Default','Diferencia','009.002','Dif $',0,ifnull(varDif,0),ifnull(varDif,0),1,0);
		
	end if;
	
	
	IF varCompanyType = "galmcuts" THEN 

		SET varDif = 0;	
		
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Vent Cont C$' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Apertura C$' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Abonos C$' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Primas C$' );
		SET varDif = varDif - (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'EGR. Efectivo C$' );
		SET varDif = varDif - (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Cierre C$' );
		insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
		values ('No','Default','Saldo Final Caja','009.001','C$',0,ifnull(varDif,0),ifnull(varDif,0),1,0);
		
		
		
		SET varDif = 0;
		
		
		
		SET varDif = varDif + (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Vent Cont $' );
		SET varDif = varDif + (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Apertura $' );	
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Abonos $' );
		SET varDif = varDif + (select IFNULL(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'Primas $' );
		SET varDif = varDif - (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.nombre) = 'EGR. Efectivo $' );
		SET varDif = varDif - (select ifnull(sum(u.total),0) from tb_tmp_closed u where upper(u.substitulo) = 'Cierre $' );
		
		insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 
		values ('No','Default','Saldo Final Caja','009.002','$',0,ifnull(varDif,0),ifnull(varDif,0),1,0);
		
	end if;
	
	
	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,moneda,tipoCambio,cantidad,subtotal,total) 
	select 
		z.sumary,
		z.comandoProces,
		z.subtitulo,
		z.codigo,
		z.nombre,		
		z.moneda,
		z.tipoCambio,
		sum(z.cantidad) as cantidad,
		sum(z.subtotal) as subtotal,
		sum(z.total) as total
	from 
		(
			select 
				'Si' as sumary,
				'Default' as comandoProces,
				'Detalle de Venta' as subtitulo,
				'010.001' as codigo,
				
				case
					when cat.`name` = 'AUTO_LAVADO' then 
						concat('<span style="color:red" >',i.`name`,'</span>')
					else 
						i.`name`
				end  as nombre,		
				
				td.quantity as cantidad,
				td.amount as subtotal,
				td.amount as total,
				1 as moneda, 
				0 as tipoCambio 
			from 
				tb_transaction t 
				inner join tb_transaction_master tm  on 
					t.transactionID = tm.transactionID 
				inner join tb_transaction_master_detail td on 
					td.transactionMasterID = tm.transactionMasterID 
				inner join tb_item i on 
					td.componentItemID = i.itemID 
				inner join tb_item_category  cat on 
					cat.inventoryCategoryID = i.inventoryCategoryID 
				inner join tb_transaction_causal tmc on 
					tm.transactionCausalID = tmc.transactionCausalID 
				inner join tb_workflow_stage ws on 
					ws.workflowStageID = tm.statusID 
			where 
				t.transactionID = 19  and 
				tm.isActive = 1 and 
				ws.aplicable = 1 and 
				td.isActive = 1 and 
				tm.companyID = prCompanyID and 
				tm.createdOn between prStartOn  and prEndOn 	
			order by 
				cat.`name`,i.`name`
		) z 
	group by 
		z.sumary,
		z.comandoProces,
		z.subtitulo,
		z.codigo,
		z.nombre,		
		z.moneda,
		z.tipoCambio;
	
		
	
	SET amount1 = (select k.total from  tb_tmp_closed k where  k.nombre = 'Vent Cont C$');
	SET amount2 = (select k.total from  tb_tmp_closed k where  k.nombre = 'Efectivo $'  and k.substitulo = 'Formas de Pago $');
	SET amount3 = (select k.total from  tb_tmp_closed k where  k.nombre = 'Efectivo C$' and k.substitulo = 'Formas de Pago C$');
	SET amount4 = (select k.total from  tb_tmp_closed k where  k.nombre = 'Tarjeta C$' and k.substitulo = 'Formas de Pago C$');
	SET amount1 = IFNULL(amount1,0);
	SET amount2 = IFNULL(amount2,0);
	SET amount3 = IFNULL(amount3,0);
	SET amount4 = IFNULL(amount4,0);
	
	
	UPDATE tb_tmp_closed set 
		total = amount1 -  (amount2 * 36.5) - amount4,
		subtotal = amount1 -  (amount2 * 36.5) - amount4 
	WHERE 
		nombre = 'Efectivo C$' and 
		substitulo = 'Formas de Pago C$';
		
		
	
	
		
			
	select 
		sumary,
		comandoProce,
		substitulo,
		codigo,
		nombre,
		cantidad,
		subtotal,
		total,
		moneda,
		tipoCambio
	from 
		tb_tmp_closed 
	order by 
		codigo asc ;
		
			
	drop table tb_tmp_closed;		
	
	
	 
	 
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_box_get_report_closed_glamcuts
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_box_get_report_closed_glamcuts`;
delimiter ;;
CREATE PROCEDURE `pr_box_get_report_closed_glamcuts`(IN `prUserID` int,IN `prTokenID` varchar(150),IN `prCompanyID` int,IN `prAuthorization` int,IN `prStartOn` datetime,IN `prEndOn` datetime,IN `prUserIDFilter` int)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'lista de abonos de los clientes'
BEGIN

	

	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);			

	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE moneda_ VARCHAR(50); 

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	DECLARE PERMISSION_NONE INT DEFAULT -1;

	DECLARE PERMISSION_ALL INT DEFAULT 0;

	DECLARE PERMISSION_BRANCH INT DEFAULT 1;

	DECLARE PERMISSION_ME INT DEFAULT 2; 		

	DECLARE isAdmin_ INT DEFAULT   0; 

	DECLARE varDif DECIMAL(19,4) DEFAULT 0;

	DECLARE varCompanyType VARCHAR(50);

	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCompanyType					= (SELECT c.type FROM tb_company c where c.companyID = prCompanyID);

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		



	

	select 

		r.isAdmin into isAdmin_ 

	from 

		tb_user u 

		inner join tb_membership me on 

			u.userID = me.userID 

		inner join tb_role r on 

			me.roleID = r.roleID 

	where

		me.userID = prUserID and r.isAdmin = 1 limit 1 ;

		

	

	set isAdmin_ = (case when isAdmin_ is null then 0 else isAdmin_ end);



	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	

	

	create table tb_tmp_closed (

		substitulo varchar(250),

		codigo varchar(50),

		nombre varchar(150),

		cantidad varchar(150),

		subtotal varchar(150),

		total decimal(19,2),

		moneda int ,

		tipoCambio decimal(19,2),

		comandoProce varchar(150),

		sumary varchar(50)

		

	);

	

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Apertura C$',

		'001.001',

		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,

		IFNULL(sum(tmdd.quantity),0) as cantidad,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_transaction_master_denomination tmdd  on 

			tmdd.transactionMasterID = tm.transactionMasterID 

		inner join tb_catalog_item ci on 

			tmdd.catalogItemID = ci.catalogItemID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 29  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tmdd.quantity > 0 and 

		tm.currencyID = 1 and 

		ci2.`name` = 'Apertura' and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		ci.`name`; 

		

		

		

	

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Gastos C$',

		'002.001',

		tm.reference1,

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 30  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.currencyID = 1 and 

		ci2.`name` != 'Cierre' and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		tm.reference1;

		

		

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Apertura $',

		'003.001',

		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,

		IFNULL(sum(tmdd.quantity),0) as cantidad,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_transaction_master_denomination tmdd  on 

			tmdd.transactionMasterID = tm.transactionMasterID 

		inner join tb_catalog_item ci on 

			tmdd.catalogItemID = ci.catalogItemID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 29  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tmdd.quantity > 0 and 

		tm.currencyID = 2 and 

		ci2.`name` = 'Apertura' and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		ci.`name`; 

		

	

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Gastos $',

		'004.001',

		tm.reference1,

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 30  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.currencyID = 2 and 

		ci2.`name` != 'Cierre' and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		tm.reference1;

		

		

		

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,moneda,tipoCambio,cantidad,subtotal,total) 

	select 

		z.sumary,

		z.comandoProces,

		z.subtitulo,

		z.codigo,

		z.nombre,		

		z.moneda,

		z.tipoCambio,

		sum(z.cantidad) as cantidad,

		sum(z.subtotal) as subtotal,

		sum(z.total) as total

	from 

		(

			select 

				'Si' as sumary,

				'Default' as comandoProces,

				'Detalle de Venta' as subtitulo,

				'005.001' as codigo,

				i.`name` as nombre,		

				td.quantity as cantidad,

				td.amount as subtotal,

				td.amount as total,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 

				inner join tb_transaction_master_detail td on 

					td.transactionMasterID = tm.transactionMasterID 

				inner join tb_item i on 

					td.componentItemID = i.itemID 

				inner join tb_item_category  cat on 

					cat.inventoryCategoryID = i.inventoryCategoryID 

				inner join tb_transaction_causal tmc on 

					tm.transactionCausalID = tmc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

			where 

				t.transactionID = 19  and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				td.isActive = 1 and 

				tm.companyID = prCompanyID and 

				tm.createdOn between prStartOn  and prEndOn 	

			order by 

				cat.`name`,i.`name`

		) z 

	group by 

		z.sumary,

		z.comandoProces,

		z.subtitulo,

		z.codigo,

		z.nombre,		

		z.moneda,

		z.tipoCambio;

	

	

	

		

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		*

	from 

		(

		select 

			'Si',

			'Default',

			concat('Formas de Pago ', case when tm.currencyID = 2 then '$' else 'C$' end  ) ,

			'006.001',

			concat('Efectivo ' , case when tm.currencyID = 2 then '$' else 'C$' end),

			IFNULL(count(tmi.receiptAmount),0) as efectivo,

			IFNULL(sum(tmi.receiptAmount),0)as transferencia,

			IFNULL(sum(tmi.receiptAmount),0) as tarjeta,

			1 as moneda, 

			0 as tipoCambio 

		from 

			tb_transaction t 

			inner join tb_transaction_master tm  on 

				t.transactionID = tm.transactionID 

			inner join tb_transaction_causal tmc on 

				tm.transactionCausalID = tmc.transactionCausalID 

			inner join tb_workflow_stage ws on 

				ws.workflowStageID = tm.statusID 

			inner join tb_transaction_master_info tmi on 

				tmi.transactionMasterID = tm.transactionMasterID 

		where 

			t.transactionID = 19  and 

			tmc.transactionCausalID in (23  , 21  ) and 

			tm.isActive = 1 and 

			ws.aplicable = 1 and 			

			tmi.receiptAmount > 0 and 

			tm.companyID = prCompanyID and 			

			tm.createdOn between prStartOn  and prEndOn 

		

		union all 

		

		select 

			'Si',

			'Default',

			concat('Formas de Pago ' , case when tm.currencyID = 2 then '$' else 'C$' end ),

			'006.002',

			concat('Tarjeta ', case when tm.currencyID = 2 then '$' else 'C$' end),

			IFNULL(count(tmi.receiptAmountCard),0) as efectivo,

			IFNULL(sum(tmi.receiptAmountCard),0)as transferencia,

			IFNULL(sum(tmi.receiptAmountCard),0) as tarjeta,

			1 as moneda, 

			0 as tipoCambio 

		from 

			tb_transaction t 

			inner join tb_transaction_master tm  on 

				t.transactionID = tm.transactionID 

			inner join tb_transaction_causal tmc on 

				tm.transactionCausalID = tmc.transactionCausalID 

			inner join tb_workflow_stage ws on 

				ws.workflowStageID = tm.statusID 

			inner join tb_transaction_master_info tmi on 

				tmi.transactionMasterID = tm.transactionMasterID 

		where 

			t.transactionID = 19  and 

			tmc.transactionCausalID in (23  , 21  ) and 

			tm.isActive = 1 and 

			ws.aplicable = 1 and 			

			tmi.receiptAmountCard > 0 and 

			tm.companyID = prCompanyID and 

			tm.createdOn between prStartOn  and prEndOn 

			

		union all 

		

		select 

			'Si',

			'Default',

			concat('Formas de Pago ', case when tm.currencyID = 2 then '$' else 'C$' end),

			'006.003',

			concat('Transferencia ', case when tm.currencyID = 2 then '$' else 'C$' end ),

			IFNULL(count(tmi.receiptAmountBank),0) as efectivo,

			IFNULL(sum(tmi.receiptAmountBank),0)as transferencia,

			IFNULL(sum(tmi.receiptAmountBank),0) as tarjeta,

			1 as moneda, 

			0 as tipoCambio 

		from 

			tb_transaction t 

			inner join tb_transaction_master tm  on 

				t.transactionID = tm.transactionID 

			inner join tb_transaction_causal tmc on 

				tm.transactionCausalID = tmc.transactionCausalID 

			inner join tb_workflow_stage ws on 

				ws.workflowStageID = tm.statusID 

			inner join tb_transaction_master_info tmi on 

				tmi.transactionMasterID = tm.transactionMasterID 

		where 

			t.transactionID = 19  and 

			tmc.transactionCausalID in (23  , 21  ) and 

			tm.isActive = 1 and 

			ws.aplicable = 1 and 		

			tmi.receiptAmountBank > 0 and 

			tm.companyID = prCompanyID and 

			tm.createdOn between prStartOn  and prEndOn 

	) as kl

	where 

		kl.tarjeta > 0 ;

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		*

	from 

		(

			select 

				'Si',

				'Default',

				concat('Formas de Pago ', case when tm.currencyID = 2 then 'C$' else '$' end),

				'007.001',

				concat('Efectivo ', case when tm.currencyID = 2 then 'C$' else '$' end ),

				IFNULL(count(IFNULL(tmi.receiptAmountDol,0)),0) as efectivo,

				IFNULL(sum(IFNULL(tmi.receiptAmountDol - (tmi.changeAmount * tm.exchangeRate) ,0)),0) as transferencia,

				IFNULL(sum(IFNULL(tmi.receiptAmountDol - (tmi.changeAmount * tm.exchangeRate) ,0)),0) as tarjeta,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 

				inner join tb_transaction_causal tmc on 

					tm.transactionCausalID = tmc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

				inner join tb_transaction_master_info tmi on 

					tmi.transactionMasterID = tm.transactionMasterID 

			where 

				t.transactionID = 19  and 

				tmc.transactionCausalID in (23  , 21  ) and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				tmi.receiptAmountDol > 0 and 

				tm.companyID = prCompanyID and 

				tm.createdOn between prStartOn  and prEndOn 

				

			union all 

			

			select 

				'Si',

				'Default',

				concat('Formas de Pago ', case when tm.currencyID = 2 then 'C$' else '$' end),

				'007.002',

				concat('Tarjeta ',case when tm.currencyID = 2 then 'C$' else '$' end),

				IFNULL(count(IFNULL(tmi.receiptAmountCardDol,0)),0) as efectivo,

				IFNULL(sum(IFNULL(tmi.receiptAmountCardDol,0)),0) as transferencia,

				IFNULL(sum(IFNULL(tmi.receiptAmountCardDol,0)),0) as tarjeta,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 

				inner join tb_transaction_causal tmc on 

					tm.transactionCausalID = tmc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

				inner join tb_transaction_master_info tmi on 

					tmi.transactionMasterID = tm.transactionMasterID 

			where 

				t.transactionID = 19  and 

				tmc.transactionCausalID in (23  , 21  ) and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				tmi.receiptAmountCardDol > 0 and 

				tm.companyID = prCompanyID and 

				tm.createdOn between prStartOn  and prEndOn 

				

			union all 

			

			select 

				'Si',

				'Default',

				concat('Formas de Pago ',case when tm.currencyID = 2 then 'C$' else '$' end),

				'007.003',

				concat('Transferencia ',case when tm.currencyID = 2 then 'C$' else '$' end),

				IFNULL(count(IFNULL(tmi.receiptAmountBankDol,0)),0) as efectivo,

				IFNULL(sum(IFNULL(tmi.receiptAmountBankDol,0)),0) as transferencia,

				IFNULL(sum(IFNULL(tmi.receiptAmountBankDol,0)),0) as tarjeta,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 

				inner join tb_transaction_causal tmc on 

					tm.transactionCausalID = tmc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

				inner join tb_transaction_master_info tmi on 

					tmi.transactionMasterID = tm.transactionMasterID 

			where 

				t.transactionID = 19  and 

				tmc.transactionCausalID in (23  , 21  ) and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				tmi.receiptAmountBankDol > 0 and 

				tm.companyID = prCompanyID and 

				tm.createdOn between prStartOn  and prEndOn 

	) as kl

 where 

	kl.tarjeta > 0 ; 				

		

		

			

	select 

		sumary,

		comandoProce,

		substitulo,

		codigo,

		nombre,

		cantidad,

		subtotal,

		total,

		moneda,

		tipoCambio

	from 

		tb_tmp_closed 

	order by 

		codigo asc ;

		

			

	drop table tb_tmp_closed;		

	

	

	 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_box_get_report_closed_gym
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_box_get_report_closed_gym`;
delimiter ;;
CREATE PROCEDURE `pr_box_get_report_closed_gym`(IN `prUserID` int,IN `prTokenID` varchar(150),IN `prCompanyID` int,IN `prAuthorization` int,IN `prStartOn` datetime,IN `prEndOn` datetime,IN `prUserIDFilter` int)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'lista de abonos de los clientes'
BEGIN

	

	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);			

	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE moneda_ VARCHAR(50); 

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	DECLARE PERMISSION_NONE INT DEFAULT -1;

	DECLARE PERMISSION_ALL INT DEFAULT 0;

	DECLARE PERMISSION_BRANCH INT DEFAULT 1;

	DECLARE PERMISSION_ME INT DEFAULT 2; 		

	DECLARE isAdmin_ INT DEFAULT   0; 

	DECLARE varDif DECIMAL(19,4) DEFAULT 0;

	DECLARE varCompanyType VARCHAR(50);

	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCompanyType					= (SELECT c.type FROM tb_company c where c.companyID = prCompanyID);

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		



	

	select 

		r.isAdmin into isAdmin_ 

	from 

		tb_user u 

		inner join tb_membership me on 

			u.userID = me.userID 

		inner join tb_role r on 

			me.roleID = r.roleID 

	where

		me.userID = prUserID and r.isAdmin = 1 limit 1 ;

		

	

	SET isAdmin_ 						= (case when isAdmin_ is null then 0 else isAdmin_ end);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 				= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	

	

	create table tb_tmp_closed (

		substitulo varchar(250),

		codigo varchar(50),

		nombre varchar(150),

		cantidad varchar(150),

		subtotal varchar(150),

		total decimal(19,2),

		moneda int ,

		tipoCambio decimal(19,2),

		comandoProce varchar(150),

		sumary varchar(50)

		

	);

	

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Apertura C$',

		'001.001',

		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,

		IFNULL(sum(tmdd.quantity),0) as cantidad,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_transaction_master_denomination tmdd  on 

			tmdd.transactionMasterID = tm.transactionMasterID 

		inner join tb_catalog_item ci on 

			tmdd.catalogItemID = ci.catalogItemID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 29  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tmdd.quantity > 0 and 

		tm.currencyID = 1 and 

		ci2.`name` = 'Apertura' and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		ci.`name`; 

		

		

		

	

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Gastos C$',

		'002.001',

		tm.reference1,

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 30  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.currencyID = 1 and 

		ci2.`name` != 'Cierre' and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		tm.reference1;

		

		

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Apertura $',

		'003.001',

		IFNULL(ci.`name`,'N/D')  as NombreDeMoneda,

		IFNULL(sum(tmdd.quantity),0) as cantidad,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as subtotal,

		IFNULL(sum(tmdd.quantity * tmdd.reference1),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_transaction_master_denomination tmdd  on 

			tmdd.transactionMasterID = tm.transactionMasterID 

		inner join tb_catalog_item ci on 

			tmdd.catalogItemID = ci.catalogItemID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 29  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.companyID = prCompanyID and 

		tmdd.quantity > 0 and 

		tm.currencyID = 2 and 

		ci2.`name` = 'Apertura' and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		ci.`name`; 

		

	

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,cantidad,subtotal,total,moneda,tipoCambio) 

	select 

		'Si',

		'Default',

		'Gastos $',

		'004.001',

		tm.reference1,

		IFNULL(count(tm.transactionMasterID),0) as cantidad,

		IFNULL(sum(tm.amount),0) as subtotal,

		IFNULL(sum(tm.amount),0) as total,

		1 as moneda, 

		0 as tipoCambio 

	from 

		tb_transaction t 

		inner join tb_transaction_master tm  on 

			t.transactionID = tm.transactionID 

		inner join tb_transaction_causal tmc on 

			tm.transactionCausalID = tmc.transactionCausalID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 		

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where 

		t.transactionID = 30  and 		

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.currencyID = 2 and 

		ci2.`name` != 'Cierre' and 

		tm.companyID = prCompanyID and 

		tm.createdOn between prStartOn  and prEndOn 

	group by 

		tm.reference1;

		

		

		

		

	

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,moneda,tipoCambio,cantidad,subtotal,total) 

	select 

		z.sumary,

		z.comandoProces,

		z.subtitulo,

		z.codigo,

		z.nombre,		

		z.moneda,

		z.tipoCambio,

		sum(z.cantidad) as cantidad,

		sum(z.subtotal) as subtotal,

		sum(z.total) as total

	from 

		(

			select 

				'Si' as sumary,

				'Default' as comandoProces,

				'Detalle de Venta' as subtitulo,

				'005.001' as codigo,

				i.`name` as nombre,		

				td.quantity as cantidad,

				td.amount as subtotal,

				td.amount as total,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 

				inner join tb_transaction_master_detail td on 

					td.transactionMasterID = tm.transactionMasterID 

				inner join tb_item i on 

					td.componentItemID = i.itemID 

				inner join tb_item_category  cat on 

					cat.inventoryCategoryID = i.inventoryCategoryID 

				inner join tb_transaction_causal tmc on 

					tm.transactionCausalID = tmc.transactionCausalID 

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

			where 

				t.transactionID = 19  and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				td.isActive = 1 and  

				tm.companyID = prCompanyID and 

				ifnull(i.realStateRoomBatchServices,0) = 0 and 

				tm.createdOn between prStartOn  and prEndOn 	

			order by 

				cat.`name`,i.`name`

		) z 

	group by 

		z.sumary,

		z.comandoProces,

		z.subtitulo,

		z.codigo,

		z.nombre,		

		z.moneda,

		z.tipoCambio;

	

	

	

		

	insert into tb_tmp_closed (sumary,comandoProce,substitulo,codigo,nombre,moneda,tipoCambio,cantidad,subtotal,total) 

	select 

		z.sumary,

		z.comandoProces,

		z.subtitulo,

		z.codigo,

		z.nombre,		

		z.moneda,

		z.tipoCambio,

		sum(z.cantidad) as cantidad,

		sum(z.subtotal) as subtotal,

		sum(z.total) as total

	from 

		(

			select 

				'Si' as sumary,

				'Default' as comandoProces,

				'Abonos' as subtitulo,

				'005.002' as codigo,

				nat.firstName as nombre,		

				1 as cantidad,

				tm.amount as subtotal,

				tm.amount as total,

				1 as moneda, 

				0 as tipoCambio 

			from 

				tb_transaction t 

				inner join tb_transaction_master tm  on 

					t.transactionID = tm.transactionID 			

				inner join tb_workflow_stage ws on 

					ws.workflowStageID = tm.statusID 

				inner join tb_naturales nat on 

					nat.entityID = tm.entityID 

			where 

				t.transactionID = 23   and 

				tm.isActive = 1 and 

				ws.aplicable = 1 and 

				tm.companyID = prCompanyID and 				

				tm.createdOn between prStartOn  and prEndOn 	

			order by 

				nat.firstName 

		) z 

	group by 

		z.sumary,

		z.comandoProces,

		z.subtitulo,

		z.codigo,

		z.nombre,		

		z.moneda,

		z.tipoCambio;

		

		

		

		

			

	select 

		sumary,

		comandoProce,

		substitulo,

		codigo,

		nombre,

		cantidad,

		subtotal,

		total,

		moneda,

		tipoCambio

	from 

		tb_tmp_closed 

	order by 

		codigo asc ;

		

			

	drop table tb_tmp_closed;		

	

	

	 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_box_get_report_closed_operation
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_box_get_report_closed_operation`;
delimiter ;;
CREATE PROCEDURE `pr_box_get_report_closed_operation`(IN `prUserID` INT, IN `prTokenID` VARCHAR(150), IN `prCompanyID` INT, IN `prUserBoxID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Reporte de cierre de caja'
BEGIN





SELECT 

  tm.createdOn as Fecha,

  tm.transactionNumber as Documento,

  tm.currencyID as CurrencyID,

  tm.amount as Monto,

  cus.customerNumber as Entidad,

  CONCAT(nat.firstName, ' ', nat.lastName) as NombreCliente,

  tm.reference1 as Referencia1,

  tm.reference2 as Referencia2,

  tm.reference3 as Referencia3,

  tm.reference4 as Referencia4,

  tm.note as Concepto,

  emp.employeNumber as CodigoEmpleado,

  '' as SubCategoria,

  '' as Categoria,

  tc.name as ESTADO,

  

  CASE 

    WHEN tm.currencyID = 1 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaLocal,

  

  CASE 

    WHEN tm.currencyID = 2 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaExt

FROM 

  tb_transaction_master tm

  inner join tb_transaction_causal tc on 

    tc.transactionCausalID = tm.transactionCausalID

  inner join tb_workflow_stage ws on tm.statusID = ws.workflowStageID

  INNER JOIN tb_customer cus on tm.entityID = cus.entityID

  INNER JOIN tb_naturales nat on cus.entityID = nat.entityID

  INNER JOIN tb_user us on tm.createdBy = us.userID

  INNER JOIN tb_employee emp on us.employeeID = emp.entityID

  INNER JOIN tb_currency cur1 on tm.currencyID = cur1.currencyID

  INNER JOIN tb_currency cur2 on tm.currencyID2 = cur2.currencyID

WHERE 

  tm.transactionID = 19  and 

  tm.isActive = 1 and 

  ws.aplicable = 1 and 

  tm.createdOn BETWEEN prStartOn AND prEndOn  and 

  (

    (tm.createdBy = prUserBoxID and prUserBoxID != 0 )

    or 

    (prUserBoxID = 0)

  );





SELECT 

  tm.createdOn as Fecha,

  tm.transactionNumber as Documento,

  tm.currencyID as CurrencyID,

  tm.amount as Monto,

  cus.customerNumber as Entidad,

  CONCAT(nat.firstName, ' ', nat.lastName) as NombreCliente,

  tm.reference1 as Referencia1,

  tm.reference2 as Referencia2,

  tm.reference3 as Referencia3,

  tm.reference4 as Referencia4,

  tm.note as Concepto,

  emp.employeNumber as CodigoEmpleado,

  '' as SubCategoria,

  '' as Categoria,

  ws.`name` as ESTADO,

  

  CASE 

    WHEN tm.currencyID = 1 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaLocal,

  

  CASE 

    WHEN tm.currencyID = 2 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaExt

FROM tb_transaction_master tm

inner join tb_workflow_stage ws on tm.statusID = ws.workflowStageID

INNER JOIN tb_customer cus on tm.entityID = cus.entityID

INNER JOIN tb_naturales nat on cus.entityID = nat.entityID

INNER JOIN tb_user us on tm.createdBy = us.userID

INNER JOIN tb_employee emp on us.employeeID = emp.entityID

INNER JOIN tb_currency cur1 on tm.currencyID = cur1.currencyID

INNER JOIN tb_currency cur2 on tm.currencyID2 = cur2.currencyID

WHERE tm.transactionID = 23 and tm.isActive = 1 and ws.aplicable = 1

and tm.createdOn BETWEEN prStartOn AND prEndOn

and 

  (

    (tm.createdBy = prUserBoxID and prUserBoxID != 0 )

    or 

    (prUserBoxID = 0)

  );





SELECT   

  tm.createdOn as Fecha,

  tm.transactionNumber as Documento,

  tm.currencyID as CurrencyID,

  tm.amount as Monto,

  caja.cashBoxCode as Entidad,

  caja.description as NombreCliente,

  '' as Referencia1,

  '' as Referencia2,

  '' as Referencia3,

  '' as Referencia4,

  tm.note as Concepto,

  usu.nickname as CodigoEmpleado,

  '' as SubCategoria,

  area.name as Categoria,

  ws.name as ESTADO,

  

  

  CASE 

    WHEN tm.currencyID = 1 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaLocal,

  

  CASE 

    WHEN tm.currencyID = 2 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaExt

  

FROM 

  tb_transaction_master tm

  LEFT JOIN tb_workflow_stage ws 

    ON tm.statusID = ws.workflowStageID 

  LEFT JOIN tb_cash_box caja 

    ON caja.cashBoxID = tm.classID 

  LEFT JOIN (

    SELECT 

      tcbu.cashBoxID, 

      tcbu.userID as userID 

    FROM tb_cash_box_user tcbu

    GROUP BY cashBoxID

  ) caja_usu 

    ON caja_usu.cashBoxID = caja.cashBoxID

  LEFT JOIN tb_user usu 

    ON usu.userID = caja_usu.userID  

  LEFT JOIN tb_catalog_item area 

    ON area.catalogItemID = tm.areaID 

  LEFT JOIN tb_currency cur1 on tm.currencyID = cur1.currencyID

  LEFT JOIN tb_currency cur2 on tm.currencyID2 = cur2.currencyID

WHERE 

  tm.transactionID = 29  and 

  tm.isActive = 1 and ws.aplicable = 1 and 

  tm.createdOn BETWEEN prStartOn AND prEndOn and 

  (

    (caja_usu.userID = prUserBoxID and prUserBoxID != 0 )

    or 

    (prUserBoxID = 0)

  );



SELECT   

  tm.createdOn as Fecha,

  tm.transactionNumber as Documento,

  tm.currencyID as CurrencyID,

  tm.amount as Monto,

  caja.cashBoxCode as Entidad,

  caja.description as NombreCliente,

  '' as Referencia1,

  '' as Referencia2,

  '' as Referencia3,

  '' as Referencia4,

  tm.note as Concepto,

  usu.nickname as CodigoEmpleado,

  '' as SubCategoria,

  area.name as Categoria,

  ws.name as ESTADO,

  

  

  CASE 

    WHEN tm.currencyID = 1 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaLocal,

  

  CASE 

    WHEN tm.currencyID = 2 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaExt

  

FROM 

  tb_transaction_master tm

  LEFT JOIN tb_workflow_stage ws 

    ON tm.statusID = ws.workflowStageID 

  LEFT JOIN tb_cash_box caja 

    ON caja.cashBoxID = tm.classID 

  LEFT JOIN (

    SELECT 

      tcbu.cashBoxID, 

      tcbu.userID as userID 

    FROM tb_cash_box_user tcbu

    GROUP BY cashBoxID

  ) caja_usu 

    ON caja_usu.cashBoxID = caja.cashBoxID

  LEFT JOIN tb_user usu 

    ON usu.userID = caja_usu.userID  

  LEFT JOIN tb_catalog_item area 

    ON area.catalogItemID = tm.areaID 

  LEFT JOIN tb_currency cur1 on tm.currencyID = cur1.currencyID

  LEFT JOIN tb_currency cur2 on tm.currencyID2 = cur2.currencyID

WHERE tm.transactionID = 30 and tm.isActive = 1 and ws.aplicable = 1

and tm.createdOn BETWEEN prStartOn AND prEndOn

and 

  (

    (caja_usu.userID = prUserBoxID and prUserBoxID != 0 )

    or 

    (prUserBoxID = 0)

  );





SELECT

  tm.createdOn as Fecha,

  tm.transactionNumber as Documento,

  tm.currencyID as CurrencyID,

  tm.amount as Monto,

  pro.providerNumber as Entidad,

  CONCAT(nat.firstName, ' ', nat.lastName) as NombreCliente,

  tm.reference1 as Referencia1,

  tm.reference2 as Referencia2,

  tm.reference3 as Referencia3,

  tm.reference4 as Referencia4,

  tm.note as Concepto,

  us.nickname as CodigoEmpleado,

  '' as SubCategoria,

  ci.name as Categoria,

  ws.name as ESTADO,



  CASE 

    WHEN tm.currencyID = 1 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaLocal,

  

  CASE 

    WHEN tm.currencyID = 2 THEN cur1.simbol

    ELSE cur2.simbol

  END as MonedaExt

FROM 

  tb_transaction_master tm

  inner join tb_workflow_stage ws on tm.statusID = ws.workflowStageID

  INNER JOIN tb_user us on tm.createdBy = us.userID

  INNER JOIN tb_currency cur1 on tm.currencyID = cur1.currencyID

  INNER JOIN tb_currency cur2 on tm.currencyID2 = cur2.currencyID

  INNER JOIN tb_catalog_item ci on ci.catalogItemID = tm.priorityID   

  LEFT JOIN tb_provider pro on tm.entityID = pro.entityID

  LEFT JOIN tb_naturales nat on pro.entityID = nat.entityID  

WHERE 

  tm.transactionID= 38 and 

  tm.isActive = 1 and 

  ws.aplicable = 1 and 

  tm.createdOn BETWEEN prStartOn AND prEndOn

and 

  (

    (tm.createdBy = prUserBoxID and prUserBoxID != 0 )

    or 

    (prUserBoxID = 0)

  );

  

  END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_box_get_report_input_cash
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_box_get_report_input_cash`;
delimiter ;;
CREATE PROCEDURE `pr_box_get_report_input_cash`(IN `prUserID` INT, IN `prTokenID` VARCHAR(150), IN `prCompanyID` INT, IN `prAuthorization` INT, IN `prStartOn` DATE, IN `prEndOn` DATE, IN `prUserIDFilter` INT , IN prConceptFilter VARCHAR(150) ,IN `prBranchID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'listado de ingresos y egresos de efectivo de la caja'
BEGIN
	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		
	DECLARE currencyIDNameSource VARCHAR(250);
	DECLARE currencyIDNameTarget VARCHAR(250);	
	DECLARE moneda_ VARCHAR(50); 
	DECLARE currencyID_ INT DEFAULT 0;
	DECLARE currencyIDTarget_ INT DEFAULT 0;
	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;
	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;
	DECLARE PERMISSION_NONE INT DEFAULT -1;
	DECLARE PERMISSION_ALL INT DEFAULT 0;
	DECLARE PERMISSION_BRANCH INT DEFAULT 1;
	DECLARE PERMISSION_ME INT DEFAULT 2; 
	DECLARE isAdmin_ INT DEFAULT   0;
  DECLARE convert_ VARCHAR(50);	 

	select 
		r.isAdmin into isAdmin_ 
	from 
		tb_user u 
		inner join tb_membership me on 
			u.userID = me.userID 
		inner join tb_role r on 
			me.roleID = r.roleID 
	where
		me.userID = prUserID and r.isAdmin = 1 limit 1 ;
	set isAdmin_ = (case when isAdmin_ is null then 0 else isAdmin_ end);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);
	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);
	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);
	CALL pr_core_get_parameter_value(prCompanyID, "ACCOUNTING_CURRENCY_NAME_REPORT_CONVERT", convert_);	
  drop temporary table if exists tb_tmp_split;
	create temporary table tb_tmp_split( val char(255) );
	set @sql = concat("insert into tb_tmp_split (val) values ('", replace(prConceptFilter, ",", "'),('"),"');");
	prepare stmt1 from @sql;
	execute stmt1;

	SELECT 
		tt.name as transactionName,	
		tm.transactionNumber ,
		tm.createdOn as transactionOn,
		tm.amount as montoTotal ,
		ws.name as estado,
		tm.note,		
		tmd.reference1 as Fac,
		if(
			cur.name = 'Dolar',
			tmd.amount * (exchangeRate_  + currencyTargetSale),
			tmd.amount
		)  as montoCordoba,
    case 
			when convert_ = 'Dolar' and cur.name != 'Dolar'  then 
				tmd.amount * (exchangeRate_  )
			when convert_ = 'Cordoba' and cur.name != 'Cordoba'  then 
				tmd.amount / (exchangeRate_ )
			else 
				tmd.amount 
		end as montoTransaccion,
		case 
			when convert_ = 'None'  then 
      cur.`name`
			else 
				convert_ 
		end as moneda ,
		(exchangeRate_  + currencyTargetSale) as tipoCambio ,
		PERMISSION_ME,
		prAuthorization,
		tm.createdBy ,
		us.nickname ,		
		ten.`name` as tipoEntrada,
		subten.`name` as tipoSubEntrada,
		if(LENGTH(tm.note)  > 0 , tm.note ,CONCAT(tm.reference1,'-',tm.reference2,'-',tm.reference3) )as note 
	FROM 
		tb_transaction_master tm
		inner join tb_transaction tt on 
			tm.transactionID = tt.transactionID 	
		inner join tb_transaction_master_detail tmd on 
			tm.companyID = tmd.companyID and 
			tm.transactionID = tmd.transactionID and 
			tm.transactionMasterID = tmd.transactionMasterID 	
		inner join tb_workflow_stage ws on 
			tm.statusID = ws.workflowStageID  	
    inner join tb_currency cur on 
			tm.currencyID = cur.currencyID 
		inner join tb_user us on 
			us.userID = tm.createdBy 
		left join tb_catalog_item ten on 
			ten.catalogItemID = tm.areaID 
		left join tb_catalog_item subten on 
			subten.catalogItemID = tm.priorityID 
	where
		tm.transactionID IN  (29 /*INGRESO A CAJA*/, 67 /*APERTURA DE CAJA*/ ) 		
		and tm.isActive = 1 
		and tmd.isActive = 1 
		and tm.companyID = prCompanyID 
		AND ws.aplicable = 1  
		and 
		(
			(tm.branchID = prBranchID and prBranchID != 0 )
			or 
			(prBranchID = 0)
		)
		and cast(tm.createdOn as date) between prStartOn and concat(prEndOn,' 23:59:59') 
		and  
		(
				fn_get_access_ready(
					prCompanyID  , 
					prUserID  , 173  , 
					tm.createdBy  , 0 
				) = 1 
		) and 
		(
					  (tm.createdBy = prUserIDFilter and prUserIDFilter != 0 )
						or 
						(prUserIDFilter = 0)
		) and 
		(
			(prConceptFilter = '-1') or
			(
					prConceptFilter != '-1' and 
					tm.areaID in 
					(
						select val  from tb_tmp_split 
					)
			) 
		)
	order by 
		tm.createdOn;   
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_box_get_report_output_cash
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_box_get_report_output_cash`;
delimiter ;;
CREATE PROCEDURE `pr_box_get_report_output_cash`(IN `prUserID` INT, IN `prTokenID` VARCHAR(150), IN `prCompanyID` INT, IN `prAuthorization` INT, IN `prStartOn` DATE, IN `prEndOn` DATE, IN `prUserIDFilter` INT,IN prConceptFilter VARCHAR(150) ,IN `prBranchID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'listado de ingresos y egresos de efectivo de la caja'
BEGIN
	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		
	DECLARE currencyIDNameSource VARCHAR(250);
	DECLARE currencyIDNameTarget VARCHAR(250);	
	DECLARE moneda_ VARCHAR(50); 
	DECLARE currencyID_ INT DEFAULT 0;
	DECLARE currencyIDTarget_ INT DEFAULT 0;
	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;
	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;
	DECLARE PERMISSION_NONE INT DEFAULT -1;
	DECLARE PERMISSION_ALL INT DEFAULT 0;
	DECLARE PERMISSION_BRANCH INT DEFAULT 1;
	DECLARE PERMISSION_ME INT DEFAULT 2; 
	DECLARE isAdmin_ INT DEFAULT   0;
  DECLARE convert_ VARCHAR(50);	 

	select 
		r.isAdmin into isAdmin_ 
	from 
		tb_user u 
		inner join tb_membership me on 
			u.userID = me.userID 
    inner join tb_role r on 
			me.roleID = r.roleID 
	where
		me.userID = prUserID and r.isAdmin = 1 limit 1 ;
	set isAdmin_ = (case when isAdmin_ is null then 0 else isAdmin_ end);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);
	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);
	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);
	CALL pr_core_get_parameter_value(prCompanyID, "ACCOUNTING_CURRENCY_NAME_REPORT_CONVERT", convert_);	
  drop temporary table if exists tb_tmp_split;
	create temporary table tb_tmp_split( val char(255) );
	set @sql = concat("insert into tb_tmp_split (val) values ('", replace(prConceptFilter, ",", "'),('"),"');");
	prepare stmt1 from @sql;
	execute stmt1;

	SELECT 			
		tt.name as transactionName,	
		tm.transactionNumber ,
		tm.transactionOn as transactionOn,
		tm.amount as montoTotal ,
		ws.name as estado,
		tm.note,		
		case 
			when tm.transactionID = 30  then 
				tmd.reference1 
			else 
				''
		end as Fac,
		case 
			when tm.transactionID = 30  then 
				if(
					cur.name = 'Dolar',
					tmd.amount * (exchangeRate_  + currencyTargetSale),
					tmd.amount
				)  
			else 
				tm.amount 
		end as montoCordoba,
		case 
			when convert_ = 'Dolar' and cur.name != 'Dolar'  then 
				tmd.amount * (exchangeRate_  )
			when convert_ = 'Cordoba' and cur.name != 'Cordoba'  then 
				tmd.amount / (exchangeRate_  )
			else 
				tmd.amount 
		end as montoTransaccion,
		case 
			when convert_ = 'None'  then 
      cur.`name`
			else 
				convert_ 
		end as moneda  ,
		(exchangeRate_  + currencyTargetSale) as tipoCambio ,
		PERMISSION_ME,
		prAuthorization,
		tm.createdBy ,
		us.nickname ,
		case 
			when tm.transactionID = 30  then  
				ten.`name`
			else 
				''
		end  as tipoSalida,
		case 
			when tm.transactionID = 30  then 
				subten.`name` 
			else 
				''
		end as tipoSubSalida,
		if(LENGTH(tm.note)  > 0 , tm.note ,CONCAT(tm.reference1,'-',tm.reference2,'-',tm.reference3) )as notev2
	FROM 
		tb_transaction_master tm
		inner join tb_transaction tt on 
			tm.transactionID = tt.transactionID 	
		left join tb_transaction_master_detail tmd on 
			tm.companyID = tmd.companyID and 
			tm.transactionID = tmd.transactionID and 
			tm.transactionMasterID = tmd.transactionMasterID 	and 
			tmd.isActive = 1 
		inner join tb_workflow_stage ws on 
			tm.statusID = ws.workflowStageID  	
		inner join tb_currency cur on 
			tm.currencyID = cur.currencyID 
		inner join tb_user us on 
			us.userID = tm.createdBy 
		left join tb_catalog_item ten on 
			ten.catalogItemID = tm.areaID 
		left join tb_catalog_item subten on 
			subten.catalogItemID = tm.priorityID 
	where
		tm.transactionID IN  (30  /*SALIDA DE CAJA*/ , 66 /*CIERRE DE CAJA*/ ) 		
		and tm.isActive = 1 
		and tm.companyID = prCompanyID 
		AND ws.aplicable = 1  
		and 
		(
			(tm.branchID = prBranchID and prBranchID != 0 )
			or 
			(prBranchID = 0)
		) 
		and cast(tm.transactionOn as date) between prStartOn and concat(prEndOn,' 23:59:59') 
		and  
		(			
				fn_get_access_ready(
					prCompanyID  , prUserID  , 
					173  , 
					tm.createdBy  , 0 
				) = 1 
		) and 
		(
			(tm.createdBy = prUserIDFilter and prUserIDFilter != 0 )
			or 
			(prUserIDFilter = 0)
		) and 
		(
			(prConceptFilter = '-1') or
			(
					prConceptFilter != '-1' and 
					tm.areaID in 
					(
						select val  from tb_tmp_split 
					)
			) 
		)
	order by 
		tm.transactionOn;   
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_box_get_report_reconciliation_deposit
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_box_get_report_reconciliation_deposit`;
delimiter ;;
CREATE PROCEDURE `pr_box_get_report_reconciliation_deposit`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT, IN `prEmployeeCode` VARCHAR(50),prStartOn DATETIME , prEndOn DATETIME)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN

	declare varDate date;

	declare varZonaHoraria int default 0; 

	 

  set varZonaHoraria = (

			select 				

				uu.value  

			from 

				tb_parameter u 

				inner join tb_company_parameter uu on 

					uu.parameterID = u.parameterID

			where 

				u.`name` = 'CORE_ZONA_HORARIA' and 

				uu.companyID = 2 

	);

	

	set varDate =  date_add(now(), interval varZonaHoraria hour);	

	set varDate =  date_add(prEndOn, interval varZonaHoraria hour);	

	set varDate =  prEndOn;

	set varDate =  date_add(varDate, interval 23 hour);	

	set varDate =  date_add(varDate, interval 59 MINUTE);	

	set varDate =  date_add(varDate, interval 59 SECOND);	

	

	select 

	emp.employeNumber as FiltroCode ,

	empn.firstName as FiltroName,

	

	

	emp.employeNumber as NoGestor , 

	empn.firstName as Gestor,

	

	cus.customerNumber as NoCliente,

	nat.firstName as Cliente,

	

	

	tm.transactionOn as Fecha,

	tm.transactionNumber as Documento,

	tm.amount as Monto,

	tm.currencyID ,

	cur.`name` as Moneda 

	

from 

	tb_transaction_master tm 

	inner join tb_currency cur on 

		cur.currencyID = tm.currencyID 

	inner join tb_workflow_stage ws on 

		ws.workflowStageID = tm.statusID 

	inner join tb_naturales nat on 

		nat.entityID = tm.entityID 

	inner join tb_customer cus on 

		cus.entityID = nat.entityID 

	inner join tb_user usr on 

		usr.userID = tm.createdBy 

	inner join tb_employee emp on 

		emp.entityID = usr.employeeID 

	inner join tb_naturales empn on 

		empn.entityID = emp.entityID 

where 

	tm.isActive = 1 and 

	tm.transactionID  in (23  ) and 

	ws.aplicable =  1 and 

	tm.transactionOn BETWEEN prStartOn AND varDate and 

	(

		(

			emp.employeNumber = prEmployeeCode and (prEmployeeCode != 'EMP00000000' )

		)

		or 

		(

			'EMP00000000' = prEmployeeCode 

		)

	)  ;



	 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_collection_get_report_commision_provider
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_collection_get_report_commision_provider`;
delimiter ;;
CREATE PROCEDURE `pr_collection_get_report_commision_provider`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT, IN `prStart` DATETIME, IN `prEnd` DATETIME, IN `prProviderID` INT)
  COMMENT 'Procedimiento para obtener la lista de movimientos y sus comisiones.'
BEGIN





CREATE TEMPORARY TABLE tmp_customer_info 					

(

	ID INT AUTO_INCREMENT PRIMARY KEY,

	TelefonoCobrador VARCHAR(50),

	NombreCobrador VARCHAR(150),

	

	CodigoProveedor VARCHAR(50),

	NombreProveedor VARCHAR(150),

	

	CodigoCliente VARCHAR(50),

	NombreCliente VARCHAR(150),

	TelefonoCliente VARCHAR(50),

	

	Factura VARCHAR(50),

	CodigoMovimiento VARCHAR(50),

	FechaMovimiento DATETIME,

	TipoMovimiento VARCHAR(80),

	FrecuenciaPagoMovimiento VARCHAR(50),

	PrimerFechaPagoMovimiento DATETIME,

	

	Balance DECIMAL(19,2),

	SaldoInicial DECIMAL(19,2),

	Abono DECIMAL(19,2),

	SaldoFinal DECIMAL(19,2),

	InteresTotalDelAbono DECIMAL(19,2),

	CapitalTotalDelAbono DECIMAL(19,2),

	CapitalDesembolso DECIMAL(19,2),

	InterestTotalDelCredito DECIMAL(19,2),

	GastoFijoMonto DECIMAL(19,2),

	GastoFijoPorcentaje DECIMAL(19,2),

	RendimientoCompartido DECIMAL(19,2),

	RendimientoXComision DECIMAL(19,2),

	RendimientoXProveedor DECIMAL(19,2),

	DepositoAProveedor DECIMAL(19,2) 	

); 

			

insert into tmp_customer_info	(	

	TelefonoCobrador ,	NombreCobrador ,CodigoProveedor,	NombreProveedor,CodigoCliente,NombreCliente,TelefonoCliente ,

	

	Factura,	CodigoMovimiento ,FechaMovimiento ,	TipoMovimiento,	FrecuenciaPagoMovimiento,PrimerFechaPagoMovimiento, 



	Balance,

	SaldoInicial,

	Abono ,

	SaldoFinal ,

	InteresTotalDelAbono ,

	CapitalTotalDelAbono ,

	CapitalDesembolso ,

	InterestTotalDelCredito ,

	GastoFijoMonto,

	GastoFijoPorcentaje ,

	RendimientoCompartido ,

	RendimientoXComision ,

	RendimientoXProveedor ,

	DepositoAProveedor 

	

)

select 

	

	'N/D' as TelefonoCobrador,

	

	'N/D' as NombreCobrador,

	

	proveedor.providerNumber as CodigoProveedor,

	if(prProviderID = 0,'TODOS',concat(prov.firstName ,' ',prov.lastName )) as NombreProveedor,

	

	cus.customerNumber as CodigoCliente,

	concat(cliente.firstName ,' ',cliente.lastName ) as NombreCliente,

	if(phoneCliente.number is null,'N/D',phoneCliente.number) as TelefonoCliente,

	

	movi.Factura,

	movi.CodigoMovimiento as CodigoTransaccion,

	movi.FechaMovimiento as FechaTransaccion,

	movi.TipoMovimiento as TipoTransaccion,

	resument.FrecuenciaPagoMovimiento,

	resument.PrimerFechaPagoMovimiento,

	

	resument.balance,

	CAST(movi.saldo_inicial AS DECIMAL(19,2)) as saldo_inicial,

	(movi.IMPORTE + movi.INTERES) as abono,

	CAST(movi.saldo_final AS DECIMAL(19,2)) as saldo_final,

	movi.INTERES,

	movi.IMPORTE,

	resument.desembolso,

	resument.interes,

	(resument.PorGastos / 100) * (movi.INTERES)  as GastosFijoMonto,

	resument.PorGastos as GastosFijoPorcentaje,

	(movi.INTERES  * (1 - (resument.PorGastos / 100))) as RendimientoCompartido,

	(movi.INTERES  * (1 - (resument.PorGastos / 100))) * 0.3 as RendimientoComision,

	(movi.INTERES  * (1 - (resument.PorGastos / 100))) * 0.7 as RendimientoProveedor,

	((movi.INTERES * (1 - (resument.PorGastos / 100))) * 0.7)  + movi.IMPORTE as DepositoProveedor

	

from

	tb_customer_credit_document ccc

	inner join tb_naturales cliente on 

		ccc.entityID = cliente.entityID		

	inner join tb_customer cus on 

		cus.companyID = cliente.companyID and 

		cus.entityID = cliente.entityID 

	inner join tb_provider proveedor on 

		ccc.companyID = proveedor.companyID and 

		ccc.providerIDCredit = proveedor.entityID 

	inner join tb_naturales prov on 

		prov.companyID = proveedor.companyID and 

		prov.entityID = proveedor.entityID 

	inner join tb_relationship relation on 

		ccc.entityID = relation.customerID 

	

	

	

	

	left join tb_entity_phone phoneCliente on 

		phoneCliente.entityID = ccc.entityID and phoneCliente.isPrimary = 1

	inner join (	

		select 

			tm.transactionNumber as CodigoMovimiento,

			td.reference1 as Factura ,

			tm.createdOn as FechaMovimiento,

			tm.amount as Monto,

			t.name as TipoMovimiento,

			tmc.transactionID ,

			td.reference2 as saldo_inicial,

			td.reference4 as saldo_final,			

			MAX(CASE WHEN tcon.name = "IMPORTE" THEN tmc.value END) as "IMPORTE",

			MAX(CASE WHEN tcon.name = "INTERES" THEN tmc.value END) as "INTERES" 

		from 

			tb_transaction_master tm 

			inner join tb_transaction_master_detail td on 

				tm.companyID = td.companyID and 

				tm.transactionID = td.transactionID and 

				tm.transactionMasterID = td.transactionMasterID 

			inner join tb_transaction t on 

				tm.transactionID = t.transactionID 			

			inner join tb_transaction_master_concept tmc on 

				tmc.companyID = td.companyID and 

				tmc.transactionID = td.transactionID and 

				tmc.transactionMasterID = td.transactionMasterID and 

				tmc.componentID = td.componentID and 

				tmc.componentItemID = td.componentItemID 

			inner join tb_transaction_concept tcon on 

				tcon.conceptID = tmc.conceptID 

		where

			tcon.name <> 'GANANCIA X T/C' and  

			CONVERT(tm.createdOn , DATE)  between prStart and prEnd and 

			tcon.name in ('IMPORTE','INTERES') and 

			tmc.transactionID in (24  , 23  , 25  )

		group by 

			tm.transactionNumber,

			td.reference1 ,

			tm.createdOn ,

			tm.amount ,

			t.name ,

			tmc.transactionID ,

			td.reference2 ,

			td.reference4 

	) movi on 

		movi.Factura = ccc.documentNumber

	inner join (

		select 

			c.customerCreditDocumentID,

			c.documentNumber,

			c.amount as desembolso,

			c.balance as balance,

			lxn.reference1 as PorGastos,			

			cit.name as  FrecuenciaPagoMovimiento,

			

			min(cl.dateApply) as  PrimerFechaPagoMovimiento,			

			sum(cl.interest) as interes,

			sum(cl.capital) as capital

		from 

			tb_customer_credit_document c

			inner join tb_catalog_item cit on 

				c.periodPay = cit.catalogItemID and 

				cit.catalogID = 43  

			inner join tb_customer_credit_amoritization cl on

				c.customerCreditDocumentID = cl.customerCreditDocumentID 

			inner join tb_transaction_master tml on 

				c.documentNumber = tml.transactionNumber and tml.transactionID = 19 

			inner join tb_transaction_master_detail_credit lxn on 

				tml.transactionMasterID = lxn.transactionMasterID  			

		group by 

			c.customerCreditDocumentID,

			c.documentNumber,

			c.amount ,

			c.balance,

			lxn.reference1 ,

			cit.name 

	) as resument on 

		resument.documentNumber = ccc.documentNumber

where

	(

		prProviderID = 0 

		and 

		(

		 	(

			 (ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0)

			)

		   or 

			(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		)

	)

	or 

	(

		prProviderID <> 0

		and 

		(

			ccc.providerIDCredit = prProviderID

		)

	);



		

select 	

	TelefonoCobrador ,	

	NombreCobrador ,

	CodigoProveedor,	

	NombreProveedor,

	CodigoCliente,

	NombreCliente,

	TelefonoCliente ,

	Factura as CodigoDesembolso,	

	CodigoMovimiento  as CodigoTransaccion,

	FechaMovimiento as FechaTransaccion,	

	TipoMovimiento as TipoTransaccion,	

	FrecuenciaPagoMovimiento,

	PrimerFechaPagoMovimiento,



	Balance,

	SaldoInicial,

	Abono ,

	SaldoFinal ,

	InteresTotalDelAbono ,

	CapitalTotalDelAbono ,

	CapitalDesembolso ,

	InterestTotalDelCredito ,

	GastoFijoMonto,

	GastoFijoPorcentaje ,

	RendimientoCompartido ,

	RendimientoXComision ,

	RendimientoXProveedor ,

	DepositoAProveedor 



from 

	tmp_customer_info x

order by 

	x.FechaMovimiento;



	

DROP TABLE tmp_customer_info;

		

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_collection_get_report_customer
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_collection_get_report_customer`;
delimiter ;;
CREATE PROCEDURE `pr_collection_get_report_customer`(IN `prUserID` INT, IN `prToken` VARCHAR(50), IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'lista de clientes de los gestores de credito'
BEGIN

	DECLARE isAdmin_ INT DEFAULT   0;



	

	select 

		r.isAdmin into isAdmin_ 

	from 

		tb_user u 

		inner join tb_membership me on 

			u.userID = me.userID 

		inner join tb_role r on 

			me.roleID = r.roleID 

	where

		me.userID = prUserID and r.isAdmin = 1 limit 1 ;

		

	

	set isAdmin_ = (case when isAdmin_ is null then 0 else isAdmin_ end);



	

	

	

	

	select 

		customer.entityID,

		customer.customerCreditDocumentID,

		customer.Gestor,

		customer.CodigoCliente,

		customer.Cliente, 

		customer.TipoTelefono,

		customer.Telefono,

		customer.Factura,

		facturas.dias_atrazo,

		facturas.dias_proximo_pago 

	from 

		(

		select 

			c.entityID,

			IFNULL( gestor.firstName,'**SIN GESTOR') as Gestor,

			c.customerNumber as CodigoCliente,

			concat(nat.firstName,' ',nat.lastName) as Cliente,

			ci.display as TipoTelefono,

			ph.number as Telefono,

			ccc.documentNumber as Factura,

			ccc.customerCreditDocumentID 

		from 	

			tb_customer_credit_document  ccc 

			inner join tb_workflow_stage ws on 

				ccc.statusID = ws.workflowStageID 				

			left join tb_entity_phone ph on 

				ph.entityID = ccc.entityID  and  ph.isPrimary = 1 

			left join tb_catalog_item ci on 

				ph.typeID = ci.catalogItemID 

			inner join tb_customer c on 

				ccc.entityID = c.entityID 

			inner join tb_naturales nat on 

				c.entityID = nat.entityID 

				

			left join tb_relationship r on 

				c.entityID = r.customerID 										

			left join tb_naturales gestor  on 

				r.employeeID = gestor.entityID   

				

		where

			( 

				

				(

				r.employeeID = ( 

										select 

												u.employeeID 

										from 

												tb_user u 

										where 

												u.userID = prUserID 

									) and isAdmin_ = 0 

				)

				or 

				 

				(

					fn_get_access_ready(prCompanyID  , prUserID  , 181  , 0  , 0  ) = 1 

				)

				or

				

				(

				isAdmin_ = 1  

				)

			)

			and ccc.isActive = 1 

			and ws.vinculable = 1 

		) customer 

		inner join (

			select 

				ccc.customerCreditDocumentID,

				max(

				if (

					cccd.dateApply < current_date(),

					DATEDIFF(cccd.dateApply,current_date()) * -1,

					0

				))  as dias_atrazo ,

				min(

				if (

					cccd.dateApply >= current_date(),

					DATEDIFF(cccd.dateApply,current_date()) ,

					0

				))  as dias_proximo_pago

			from 

				tb_customer_credit_document ccc 

				inner join tb_customer_credit_amoritization cccd on 

					ccc.customerCreditDocumentID = cccd.customerCreditDocumentID 

				inner join tb_workflow_stage ws2 on 

					cccd.statusID = ws2.workflowStageID 

			where

				cccd.remaining > 0 

				and ws2.vinculable = 1 

			group by 

				ccc.customerCreditDocumentID 

		

		) facturas on 

			customer.customerCreditDocumentID = facturas.customerCreditDocumentID 

	order by 

		facturas.dias_atrazo desc,

		facturas.dias_proximo_pago asc,

		customer.Cliente; 

	

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_collection_get_report_detalle_transaction
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_collection_get_report_detalle_transaction`;
delimiter ;;
CREATE PROCEDURE `pr_collection_get_report_detalle_transaction`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTokenID` VARCHAR(50), IN `prPeriodID` INT, IN `prCycleID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Reporte para ver el calculo de comisiones de los gestores'
BEGIN

	SELECT 

		T.firstName,

		T.mes,

		SUM(T.comision10) AS comision10,

		SUM(T.comision20) AS comision20,

		SUM(T.comision30) AS comision30,

		SUM(T.comision40) AS comision40,

		SUM(T.comision50) AS comision50,

		SUM(T.comision100) AS comision100

	FROM 

		(

			select 

				cobrador.firstName,

				date_format(tm.transactionOn,'%Y-%m') as mes,

				round((tmc.value * 0.1),2) as comision10 ,

				round((tmc.value * 0.2),2) as comision20 ,

				round((tmc.value * 0.3),2) as comision30 ,

				round((tmc.value * 0.4),2) as comision40 ,

				round((tmc.value * 0.5),2) as comision50 ,

				round((tmc.value * 1),2) as comision100 

			from 

				tb_transaction_master tm

				inner join tb_transaction_master_concept tmc on 

					tm.transactionMasterID = tmc.transactionMasterID 

				inner join tb_transaction_concept tc on 

					tmc.conceptID = tc.conceptID 

				inner join tb_naturales cobrador  on 

					cobrador.entityID = tm.reference3 

				inner join tb_employee em on  

					tm.reference3 is not null and 

					tm.reference3 = em.entityID  	

				inner join tb_journal_entry je on 

					tm.journalEntryID = je.journalEntryID 	

				inner join tb_accounting_cycle acc on 

					je.accountingCycleID = acc.componentCycleID 		

			where

				tm.transactionID = 23  and 

				tc.conceptID in ( 36   ) and 

				em.departamentID	= 373  and 

				acc.componentCycleID = prCycleID and 

				acc.componentPeriodID = prPeriodID 

		) T 

	GROUP BY 

		T.firstName,T.mes 

	ORDER BY 

		T.mes DESC, T.firstName ;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_collection_get_report_documents_credit
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_collection_get_report_documents_credit`;
delimiter ;;
CREATE PROCEDURE `pr_collection_get_report_documents_credit`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT,IN prDateStart DATETIME, IN prDateEnd DATETIME)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN

	DECLARE varMin DATETIME;
	DECLARE varMax DATETIME;
	DECLARE columnas_pivot VARCHAR(10000);
  DECLARE sql_query VARCHAR(10000);


	

	DROP TEMPORARY TABLE IF EXISTS tbl_document_temp;
	CREATE TEMPORARY TABLE tbl_document_temp AS
	select 
		d.customerCreditDocumentID,
		d.documentNumber,
		DATE(NOW()) as documentOn,
		0 as amount 
	from 
		tb_customer_credit_document d 
	where 
		d.isActive = 1 and 
		d.dateOn between  prDateStart and prDateEnd; 

		

		

	

	DROP TEMPORARY TABLE IF EXISTS tbl_abonos_temp;
	CREATE TEMPORARY TABLE tbl_abonos_temp AS
	select 
		td.reference1 as documentNumber,
		date(c.transactionOn ) as transactionOn,
		sum(td.amount) as amount 
	from 
		tb_transaction_master c 
		inner join tb_transaction_master_detail td on 
			c.transactionMasterID = td.transactionMasterID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = c.statusID 
	where 
		c.transactionID = 23  /*abonos*/ and 
		c.isActive = 1 and 
		td.isActive = 1 and 
		ws.aplicable = 1 and 
		c.transactionOn >=    (DATE(NOW()) - INTERVAL 10 DAY ) 
	group by 
		td.reference1,
		date(c.transactionOn ); 


	SET varMin = (DATE(NOW()) - INTERVAL 10 DAY );
	SET varMax = DATE(NOW());
	WHILE varMin < varMax DO   

		insert into tbl_document_temp(customerCreditDocumentID,documentNumber,documentOn,amount )
		select 
				d.customerCreditDocumentID,
				d.documentNumber,
				varMin as documentOn ,
				0 as amount 
			from 
				tb_customer_credit_document d 
			where 
				d.isActive = 1 and 
				d.dateOn between  prDateStart and prDateEnd; 

			

		SET varMin = varMin + INTERVAL 1 DAY;
	END WHILE;
	

	UPDATE 
		tbl_document_temp d
		JOIN tbl_abonos_temp a ON 
				d.documentNumber = a.documentNumber and 
				d.documentOn = a.transactionOn
		SET 
			d.amount = a.amount;
    

		SELECT 
				GROUP_CONCAT(
						CONCAT(
								'SUM(CASE WHEN documentOn = ''', 
								DATE(documentOn), 
								''' THEN amount ELSE 0 END) AS ',
								'`',
								DATE(documentOn) , 
								'`'
						)
						ORDER BY DATE(documentOn) DESC 
						SEPARATOR ', '
				) 

		INTO columnas_pivot
		FROM (
				SELECT DISTINCT DATE(documentOn) AS documentOn
				FROM tbl_document_temp
				ORDER BY DATE(documentOn) DESC 
		) AS fechas;



		DROP TEMPORARY TABLE IF EXISTS tbl_document_pivot_temp;
    SET sql_query = CONCAT(
        ' 
	      CREATE TEMPORARY TABLE tbl_document_pivot_temp AS 
				SELECT 
					 customerCreditDocumentID, ', columnas_pivot, ' ',
        'FROM 
					tbl_document_temp 
				',
        '
				GROUP BY 
						customerCreditDocumentID
				'
    );



		set @sql = sql_query;
		prepare stmt1 from @sql;
		execute stmt1;

	

	select 
		usu.nickname as orden,
		usu.nickname ,
		cast(cu.createdOn as date) as customerCreatedOn,
		cu.customerNumber,
		concat(nat.firstName,' ',nat.lastName) as customerName,
		d.documentNumber,
		leg.comercialName , 
		cu.identification, 
		sexo.`name` as sexo,
		cu.location, 
		(

			select 
				ph.number 
			from 
				tb_entity_phone ph 
			where 
				ph.entityID = cu.entityID 
			limit 1 
		) as phoneNumber ,
		civil.`name` as statusCivil,
		d.term,
		d.interes,
		d.amount as amountDocument,
		d.dateOn as dateDocument,
		(

			select 
				date(max(p.transactionOn)) 
			from 
				tb_transaction_master p 
				inner join tb_transaction_master_detail pd on 
					p.transactionMasterID = pd.transactionMasterID 
			where 
				p.isActive = 1 and 
				pd.isActive = 1 and 
				pd.reference1 = d.documentNumber 
		) as dateLastShareDocument,
		(
			select 
				sum(xaa.`share`) as total 
			from 
				tb_customer_credit_document xd 
				inner join tb_customer_credit_amoritization xaa on 
					xd.customerCreditDocumentID = xaa.customerCreditDocumentID 
			where 
				xaa.isActive = 1 and 
				xd.customerCreditDocumentID = d.customerCreditDocumentID 
		) as deudaTotal,
		(
				select 
					sum(s_td.amount) as montoPagado
				from 
					tb_transaction_master_detail s_td 
					inner join tb_transaction_master s_t on 
						s_td.transactionMasterID = s_t.transactionMasterID 
					inner join tb_workflow_stage s_ws on 
						s_ws.workflowStageID = s_t.statusID 
				where 
					s_td.isActive = 1 and 
					s_t.isActive = 1 and 
					s_t.transactionID = 23  and 
					s_ws.aplicable = 1 and 
					s_td.reference1 = d.documentNumber 
		) as montoPagado,
		ROUND(
			(
				IFNULL
				(
					(
						select 
							sum(s_td.amount) as montoPagado
						from 
							tb_transaction_master_detail s_td 
							inner join tb_transaction_master s_t on 
								s_td.transactionMasterID = s_t.transactionMasterID 
							inner join tb_workflow_stage s_ws on 
								s_ws.workflowStageID = s_t.statusID 
						where 
							s_td.isActive = 1 and 
							s_t.isActive = 1 and 
							s_t.transactionID = 23  and 
							s_ws.aplicable = 1 and 
							s_td.reference1 = d.documentNumber 
					),
					0
				)
				/
				(
					select 
						sum(xaa.`share`) as total 
					from 
						tb_customer_credit_document xd 
						inner join tb_customer_credit_amoritization xaa on 
							xd.customerCreditDocumentID = xaa.customerCreditDocumentID 
					where 
						xaa.isActive = 1 and 
						xd.customerCreditDocumentID = d.customerCreditDocumentID 
				)
			),
			2
		) * 100 AS avance ,  
		ROUND(
			(
				d.amount - 
				IFNULL
				(
					(
						select 
							sum(s_td.amount) as montoPagado
						from 
							tb_transaction_master_detail s_td 
							inner join tb_transaction_master s_t on 
								s_td.transactionMasterID = s_t.transactionMasterID 
							inner join tb_workflow_stage s_ws on 
								s_ws.workflowStageID = s_t.statusID 
						where 
							s_td.isActive = 1 and 
							s_t.isActive = 1 and 
							s_t.transactionID = 23  and 
							s_ws.aplicable = 1 and 
							s_td.reference1 = d.documentNumber 
					),
					0
				)
			),
			2
		) AS saldo ,  
		(
			select 
				if(ro.orderNo is null,0,ro.orderNo)  				
			from 
				tb_relationship ro 
			where 
				ro.isActive = 1 and 
				ro.employeeID = usu.employeeID and 
			  ro.customerID = cu.entityID 
			limit 1 		
		) as Orden , 
		d.currencyID ,
		IF(
			(
				select 
					ccd.customerCreditDocumentID
				from 
					tb_customer_credit_document ccd 
				where 
					ccd.entityID = cu.entityID and 
					ccd.isActive = 1 and 
					ccd.statusID in  (77  )
				limit 1 
			) IS NULL , 
			'CANCELADO',
			'ACTIVO'
		) as statusCustomer,
		ws.`name` as statusName,
		pe.`name` as periodPay,	
		i.* 
	from 
		tbl_document_pivot_temp i 
		inner join tb_customer_credit_document d on 
			d.customerCreditDocumentID = i.customerCreditDocumentID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = d.statusID 
		inner join tb_catalog_item pe on 
			pe.catalogItemID = d.periodPay 
		inner join tb_customer cu on 
			d.entityID = cu.entityID 
		inner join tb_naturales nat on 
			nat.entityID = cu.entityID 
		inner join tb_legal leg on 
			leg.entityID = cu.entityID 
		inner join tb_catalog_item sexo on 
			sexo.catalogItemID = cu.sexoID 
		inner join tb_catalog_item civil on 
			civil.catalogItemID = nat.statusID   
		inner join (
			select 
				distinct 
				r.customerID as entityIDCustomer,
				r.employeeID  
			from 
				tb_relationship  r 
			where 
				r.isActive = 1 
		) r on 
			r.entityIDCustomer = cu.entityID 
		inner join tb_user usu on 
			usu.employeeID = r.employeeID 
	WHERE 
		usu.isActive = 1 and 
		cu.isActive = 1 and 
		d.isActive = 1 and 
		d.dateOn  between  prDateStart and prDateEnd and 
		usu.isActive = 1 and 		
		usu.userID not in ( 2  , 666  )
	order by 
		22;
	

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_collection_get_report_document_credit
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_collection_get_report_document_credit`;
delimiter ;;
CREATE PROCEDURE `pr_collection_get_report_document_credit`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prDocumentNumber` VARCHAR(50))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN

	declare varMinFixex DATETIME;

	declare varMin DATETIME;

	declare varMax DATETIME;

	declare varAmount DECIMAL(19,8) default 0;

	

	

	DROP TEMPORARY TABLE IF EXISTS tbl_cliente_temp;

	CREATE TEMPORARY TABLE tbl_cliente_temp AS

	select 

		d.customerCreditDocumentID,

		cu.entityID,

	  a.creditAmortizationID,

		cu.customerNumber,

		concat(nat.firstName,' ',nat.lastName ) as nameCustomer,

		d.documentNumber ,

		a.dateApply,  

		(select sum(amo.`share`) from tb_customer_credit_amoritization amo where amo.customerCreditDocumentID = d.customerCreditDocumentID ) as capitalMoreInteres,

		a.`share` shareProgramin,

		0 as shareReal,

		0 as balanceStart,

		0 as balanceEnd 

	from 

		tb_customer_credit_document d 

		inner join tb_naturales nat on 

			nat.entityID = d.entityID 

		inner join tb_customer cu on 

			cu.entityID = nat.entityID 

		inner join tb_customer_credit_amoritization a on 

			a.customerCreditDocumentID = d.customerCreditDocumentID 

	where 

		d.documentNumber = prDocumentNumber  and 

		d.isActive = 1 and 

		a.isActive = 1 

	order by 

		a.dateApply desc ; 

		

	DROP TEMPORARY TABLE IF EXISTS tbl_cliente_temp2;

	CREATE TEMPORARY TABLE tbl_cliente_temp2 AS

	SELECT * FROM tbl_cliente_temp;

	

	

	DROP TEMPORARY TABLE IF EXISTS tbl_abonos_temp;

	CREATE TEMPORARY TABLE tbl_abonos_temp AS

	select 

		c.transactionMasterID,

		DATE(c.transactionOn) as transactionOn,

		c.amount  

	from 

		tb_transaction_master c 

		inner join tb_transaction_master_detail td on 

			c.transactionMasterID = td.transactionMasterID 

	WHERE 

		c.transactionID = 23  and 

		c.isActive = 1 and 

		td.isActive = 1 and 

		td.reference1 = prDocumentNumber;

		

		

	

	DROP TEMPORARY TABLE IF EXISTS tbl_date_temp ;

	CREATE TEMPORARY TABLE tbl_date_temp AS

	select 

		distinct 

		u.dateOn 

	from 

		(

			select 

				x.transactionOn as dateOn 

			from 

				tbl_abonos_temp x 

			union all 

			select 

				k.dateApply as dateOn 

			from 

				tbl_cliente_temp k 

			where 

				k.dateApply 

		) u ;

		

		

		

		set varMinFixex 	= (select min(dateOn) from tbl_date_temp  );

		set varMin 				= (select min(dateOn) from tbl_date_temp  );

		set varMax 				= (select max(dateOn) from tbl_date_temp  );

		WHILE varMin <= varMax DO 

				SET varAmount = (SELECT sum(k.amount) FROM tbl_abonos_temp k where k.transactionOn = varMin);

				SET varAmount = IFNULL(varAmount,0);

				

				IF NOT EXISTS ( select * from tbl_cliente_temp k where k.dateApply = varMin ) THEN 

								insert into tbl_cliente_temp(

											customerCreditDocumentID,

											entityID,

											creditAmortizationID,

											customerNumber,

											nameCustomer,

											documentNumber ,

											dateApply,  

											capitalMoreInteres,

											shareProgramin,

											shareReal,

											balanceStart,

											balanceEnd 

								)

								select

										customerCreditDocumentID,

										entityID,

										0 as creditAmortizationID,

										customerNumber,

										nameCustomer,

										documentNumber ,

										varMin,  

										capitalMoreInteres,

										shareProgramin,

										shareReal,

										balanceStart,

										balanceEnd 

								from 

									  tbl_cliente_temp2 t 

								where 

									  t.dateApply = varMinFixex;

				END IF;

				

				UPDATE tbl_cliente_temp set shareReal = varAmount where dateApply = varMin;

				

				

        

        SET varMin = (select min(dateOn) from tbl_date_temp k where k.dateOn > varMin  );

    END WHILE;

		

	  

		SELECT 

			c.customerNumber,

			c.phoneNumber, 

			cur.simbol ,

			t.customerCreditDocumentID,			

			t.entityID,

			t.creditAmortizationID,

			t.customerNumber,

			t.nameCustomer,

			t.documentNumber ,

			t.dateApply,  

			t.capitalMoreInteres,

			t.shareProgramin,

			t.shareReal,

			t.balanceStart,

			t.balanceEnd 

		FROM 

			tbl_cliente_temp t 

			inner join  tb_customer c on 

				t.entityID = c.entityID 

			inner join tb_customer_credit_document dd on 

				dd.customerCreditDocumentID = t.customerCreditDocumentID

			inner join tb_currency cur on 

				cur.currencyID = dd.currencyID 

		ORDER BY 

			t.dateApply desc ; 

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_collection_get_report_summary_credit
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_collection_get_report_summary_credit`;
delimiter ;;
CREATE PROCEDURE `pr_collection_get_report_summary_credit`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN prDate DATETIME)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN

	DECLARE varMin DATETIME;

	DECLARE varMax DATETIME;



	

	

	DROP TEMPORARY TABLE IF EXISTS tbl_user_data_temp;

	CREATE TEMPORARY TABLE tbl_user_data_temp AS 

	SELECT 

		u.nickname ,

		0 as countCustomer,

		0 as countCredit,

		0 as countCustomerAcumulados,

		0 as countCustomerCancel,

		0 as countCustomerNew,

		0 as countCustomerRecuperation,

		0 as amountCartera,

		0 as amountCapital 

	FROM 

		tb_user u 

	WHERE 

		u.isActive = 1 and 

		u.userID != 2 and 

		u.employeeID > 0 and 

		u.userID not in ( 2  , 666  );

		

	

	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				count(*) as count 

			from 

				tb_customer cu 

				inner join tb_entity cue on 

					cue.entityID = cu.entityID 

				inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID 

				inner join tb_user usu on 

					usu.employeeID = emp.entityID  

			where 

				cu.isActive = 1 and 

				cu.entityID in (

							select 

									sub_d.entityID 

							from 

								tb_customer_credit_document sub_d 

							where 

								sub_d.statusID = 77  and 

								sub_d.isActive = 1 

			)

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.countCustomer = o.count ; 



	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				count(*) as count  

			from 

				tb_customer cu 

				inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 				

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID 

				inner join tb_user usu on 

					usu.employeeID = emp.entityID  

				inner join tb_customer_credit_document d on 

					d.entityID = cu.entityID 

			where 

				cu.isActive = 1 and 

				d.isActive = 1 and 

				d.statusID in (77  ) 

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.countCredit = o.count ; 

		

	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				sum(amo.`share`) as count  

			from 

				tb_customer cu 

				inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 			

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID 

				inner join tb_user usu on 

					usu.employeeID = emp.entityID  

				inner join tb_customer_credit_document d on 

					d.entityID = cu.entityID 

				inner join tb_customer_credit_amoritization amo on 

					amo.customerCreditDocumentID = d.customerCreditDocumentID 

			where 

				cu.isActive = 1 and 

				d.isActive = 1 and 

				d.statusID in (77  )  

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.amountCartera = o.count ; 

		

		

	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				sum(amo.capital) as count  

			from 

				tb_customer cu 

				inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 			

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID 

				inner join tb_user usu on 

					usu.employeeID = emp.entityID  

				inner join tb_customer_credit_document d on 

					d.entityID = cu.entityID 

				inner join tb_customer_credit_amoritization amo on 

					amo.customerCreditDocumentID = d.customerCreditDocumentID 

			where 

				cu.isActive = 1 and 

				d.isActive = 1 and 

				d.statusID in (77  )  

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.amountCapital = o.count ; 

		

		

	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				count(*) as count  

			from 

				tb_customer cu 

				inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 	 

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID 

				inner join tb_user usu on 

					usu.employeeID = emp.entityID  

			where 

				cu.isActive = 1 and 

				cu.entityID not in (

					select 

						dd.entityID 

					from 

						tb_customer_credit_document dd 

					where 

						dd.isActive = 1 and 

						dd.statusID = 77  

				)   and 

				cu.entityID in (

					select 

						tm.entityID 

					from 

						tb_transaction_master tm  

					where 

						tm.isActive = 1 and 

						tm.transactionID = 23  and 

						tm.transactionOn  between 

									DATE_SUB(DATE_SUB(NOW(), INTERVAL 6 HOUR), INTERVAL 1 DAY) AND   

									DATE_SUB(NOW(), INTERVAL 6 HOUR) 

				) 

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.countCustomerCancel = o.count ; 

		

		

	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				count(*) as count  

			from 

				tb_customer cu 

				inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 	 

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID 

				inner join tb_user usu on 

					usu.employeeID = emp.entityID  

			where 

				cu.isActive = 1 and 

				cu.entityID not in (

					select 

						dd.entityID 

					from 

						tb_customer_credit_document dd 

					where 

						dd.isActive = 1 and 

						dd.statusID = 77  

				)   

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.countCustomerAcumulados = o.count ; 

		

	

	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				count(*) as count  

			from 

				tb_customer cu 

					inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 	 

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID   

				inner join tb_user usu on 

					usu.employeeID = emp.entityID 

			where 

				cu.isActive = 1 and 

				cu.entityID in (

					select 

						dd.entityID 

					from 

						tb_customer_credit_document dd 

					where 

						dd.isActive = 1 and 

						dd.statusID = 77  

				) and 

				cu.createdOn between 

						DATE_SUB(DATE_SUB(NOW(), INTERVAL 6 HOUR), INTERVAL 1 DAY) AND   

						DATE_SUB(NOW(), INTERVAL 6 HOUR) 

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.countCustomerNew = o.count ; 

		

		

	

	UPDATE 

		tbl_user_data_temp  d 

		JOIN (

			select 

				usu.nickname,

				count(*) as count  

			from 

				tb_customer cu 

				inner join (

						select 

							distinct 

							rl.customerID,

							rl.employeeID

						from 

							tb_relationship rl 

						where 

							rl.isActive = 1 

				) as  rl on 	 

					rl.customerID = cu.entityID 

				inner join tb_employee emp on 

					emp.entityID = rl.employeeID   

				inner join tb_user usu on 

					usu.employeeID = emp.entityID  

			where 

				cu.isActive = 1 and 

				cu.entityID in (

					select 

						dd.entityID 

					from 

						tb_customer_credit_document dd 

					where 

						dd.isActive = 1 and 

						dd.statusID = 77  

				)

			group by 

				usu.nickname 

		) o on 

				d.nickname = o.nickname 

	SET 

		d.countCustomerRecuperation = o.count ; 

		

		

	

	select 

		k.nickname ,

		k.countCustomer,

		k.countCredit,

		k.countCustomerAcumulados, 

		k.countCustomerCancel,

		k.countCustomerNew,

		k.countCustomerRecuperation,

		k.amountCartera,

		k.amountCapital 

	from 

		tbl_user_data_temp k ;

	

	

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_concept_helper_billing
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_concept_helper_billing`;
delimiter ;;
CREATE PROCEDURE `pr_concept_helper_billing`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procediminento que se utiliza para calcular los conceptos de la transaccion de otras salidas de inventario'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'ICOSTO';

	declare vrConceptNameIVA varchar(50) DEFAULT 'IVA';

	declare vrConceptNameIMPORTE varchar(50) DEFAULT 'IMPORTE';

	declare vrConceptNameDESCUENTO varchar(50) DEFAULT 'DESCUENTO';



	declare vrConceptIdICOSTO int default 0;

	declare vrConceptIdIVA int default 0;

	declare vrConceptIdIMPORTE int default 0;

	declare vrConceptIdDESCUENTO int default 0;

	

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdIVA  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameIVA and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdIMPORTE  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameIMPORTE and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdDESCUENTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameDESCUENTO and isActive = 1 limit 1;

			

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

		(tm.quantity * tm.unitaryCost) as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master_detail tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdIVA,

		(tm.quantity * tm.tax1) as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master_detail tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select  

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdIMPORTE,

		(tm.quantity * tm.unitaryAmount) as amountConcept,

		vrCurrencyID, 

		vrExchangeRate 

	from 

		tb_transaction_master_detail tm  

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdDESCUENTO,

		0, 

		vrCurrencyID, 

		vrExchangeRate 

	from 

		tb_transaction_master_detail tm  

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_concept_helper_calendarpay
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_concept_helper_calendarpay`;
delimiter ;;
CREATE PROCEDURE `pr_concept_helper_calendarpay`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN

			declare vrConceptNameSalario varchar(50) DEFAULT 'SALARIO';

	declare vrConceptNameAdelanto varchar(50) DEFAULT 'ADELANTO';

	declare vrConceptNameComision varchar(50) DEFAULT 'COMISION';

	declare vrConceptIdSalario int default 0;

	declare vrConceptIdAdelanto int default 0;

	declare vrConceptIdComision int default 0;

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	DECLARE varDolarName VARCHAR(20);

	DECLARE varDolarVenta VARCHAR(20);

	DECLARE varCordobaName VARCHAR(20);

	DECLARE varCurrencyIDDolar int default 0;

	DECLARE varCurrencyIDCordoba int default 0;

	

	

		CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_FUNCTION',varCordobaName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_REPORT',varDolarName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_EXCHANGE_SALE',varDolarVenta);

	

	select c.currencyID into varCurrencyIDCordoba  from tb_currency c where c.name = varCordobaName;

	select c.currencyID into varCurrencyIDDolar from tb_currency c where c.name = varDolarName;

	

		select tc.conceptID into vrConceptIdSalario  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameSalario and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdAdelanto  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameAdelanto and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdComision  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameComision and isActive = 1 limit 1;

	

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdSalario,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,((tm.amount + tm.cost)-unitaryAmount),((tm.amount+tm.cost) - tm.unitaryAmount) / tm.exchangeRateReference ) as amountConcept ,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID ,

		IFNULL(tm.exchangeRateReference,vrExchangeRate) as exchangeRate

	from

		tb_transaction_master_detail tm

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

	

	



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_concept_helper_cancelinvoice
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_concept_helper_cancelinvoice`;
delimiter ;;
CREATE PROCEDURE `pr_concept_helper_cancelinvoice`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Calcular los conceptos de la cancelacion de factura'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'IMPORTE';

	declare vrConceptNameINTERES varchar(50) DEFAULT 'INTERES';

	declare vrConceptNameGANANCIA_TC varchar(50) DEFAULT 'GANANCIA X T/C';

	declare vrConceptIdICOSTO int default 0;

	declare vrConceptIdINTERES int default 0;

	declare vrConceptIdGANANCIA_TC int default 0;

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	DECLARE varDolarName VARCHAR(20);

	DECLARE varDolarVenta VARCHAR(20);

	DECLARE varCordobaName VARCHAR(20);

	DECLARE varCurrencyIDDolar int default 0;

	DECLARE varCurrencyIDCordoba int default 0;

	

	

		CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_FUNCTION',varCordobaName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_REPORT',varDolarName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_EXCHANGE_SALE',varDolarVenta);

	

	select c.currencyID into varCurrencyIDCordoba  from tb_currency c where c.name = varCordobaName;

	select c.currencyID into varCurrencyIDDolar from tb_currency c where c.name = varDolarName;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdINTERES  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameINTERES and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdGANANCIA_TC  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameGANANCIA_TC and isActive = 1 limit 1;

	

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

				IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,tm.amount,tm.amount / tm.exchangeRateReference ) as amountConcept ,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID ,

		IFNULL(tm.exchangeRateReference,vrExchangeRate) as exchangeRate

	from

		tb_transaction_master_detail tm

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdGANANCIA_TC,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,0 ,round((tm.amount *  (tmx.exchangeRate + varDolarVenta) ) - (tm.amount /  tm.exchangeRateReference),2)) as amountConcept , 		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID,

		IFNULL(tm.exchangeRateReference,vrExchangeRate)  as exchangeRate

	from

		tb_transaction_master_detail tm

		inner join tb_transaction_master_detail_credit cc on

			tm.transactionMasterDetailID = cc.transactionMasterDetailID

		inner join tb_transaction_master tmx on

			tm.transactionMasterID = tmx.transactionMasterID

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

	



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_concept_helper_input_unpost
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_concept_helper_input_unpost`;
delimiter ;;
CREATE PROCEDURE `pr_concept_helper_input_unpost`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Entrada sin postear'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'ICOSTO';

	declare vrConceptNameIVA varchar(50) DEFAULT 'IVA';

	declare vrConceptNameIMPORTE varchar(50) DEFAULT 'IMPORTE';

	declare vrConceptNameDESCUENTO varchar(50) DEFAULT 'DESCUENTO';



	declare vrConceptIdICOSTO int default 0;

	declare vrConceptIdIVA int default 0;

	declare vrConceptIdIMPORTE int default 0;

	declare vrConceptIdDESCUENTO int default 0;

	

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdIVA  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameIVA and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdIMPORTE  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameIMPORTE and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdDESCUENTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameDESCUENTO and isActive = 1 limit 1;

			

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

		(tm.quantity * tm.unitaryCost) as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master_detail tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		0,

		0,

		vrConceptIdIVA,

		tm.tax1 as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		0,

		0,

		vrConceptIdDESCUENTO,

		tm.discount as amountConcept,

		vrCurrencyID, 

		vrExchangeRate 

	from 

		tb_transaction_master tm  

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		0, 

		0,

		vrConceptIdIMPORTE,

		(tm.subAmount + tm.tax1 - tm.discount ) as amountConcept,

		vrCurrencyID, 

		vrExchangeRate 

	from 

		tb_transaction_master tm  

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

			

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_concept_helper_other_input
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_concept_helper_other_input`;
delimiter ;;
CREATE PROCEDURE `pr_concept_helper_other_input`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para calcular los conceptos de Otras entradas a Inventario'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'ICOSTO';

	declare vrConceptIdICOSTO int default 0;

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

		(tm.quantity * tm.unitaryCost) as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master_detail tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_concept_helper_other_output
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_concept_helper_other_output`;
delimiter ;;
CREATE PROCEDURE `pr_concept_helper_other_output`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procediminento que se utiliza para calcular los conceptos de la transaccion de otras salidas de inventario'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'ICOSTO';

	declare vrConceptIdICOSTO int default 0;

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

		(tm.quantity * tm.unitaryCost) as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master_detail tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_concept_helper_provider
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_concept_helper_provider`;
delimiter ;;
CREATE PROCEDURE `pr_concept_helper_provider`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Calculo de concepto de Provisiones'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'ICOSTO';

	declare vrConceptNameINTERES varchar(50) DEFAULT 'INTERES';

	declare vrConceptNameGANANCIA_TC varchar(50) DEFAULT 'GANANCIA X T/C';

	declare vrConceptIdICOSTO int default 0;

	declare vrConceptIdINTERES int default 0;

	declare vrConceptIdGANANCIA_TC int default 0;

	

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	DECLARE varDolarName VARCHAR(20);

	DECLARE varDolarVenta VARCHAR(20);

	DECLARE varCordobaName VARCHAR(20);

	DECLARE varCurrencyIDDolar int default 0;

	DECLARE varCurrencyIDCordoba int default 0; 

	

	

		CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_FUNCTION',varCordobaName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_REPORT',varDolarName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_EXCHANGE_SALE',varDolarVenta);

	

	

	select c.currencyID into varCurrencyIDCordoba  from tb_currency c where c.name = varCordobaName;

	select c.currencyID into varCurrencyIDDolar from tb_currency c where c.name = varDolarName;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdINTERES  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameINTERES and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdGANANCIA_TC  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameGANANCIA_TC and isActive = 1 limit 1;

	

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

						IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,tm.amount,tm.amount / tm.exchangeRateReference ) as amountConcept ,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID,

		IFNULL(tm.exchangeRateReference,vrExchangeRate) as exchangeRate

	from

		tb_transaction_master_detail tm 

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdGANANCIA_TC,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,	0 ,round((tm.amount *  (tmx.exchangeRate + varDolarVenta) ) - (tm.amount /  tm.exchangeRateReference),2)) as amountConcept , 		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID,

		IFNULL(tm.exchangeRateReference,vrExchangeRate)  as exchangeRate

	from

		tb_transaction_master_detail tm 

		inner join tb_transaction_master tmx on

			tm.transactionMasterID = tmx.transactionMasterID

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;  

	



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_concept_helper_returns_provider
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_concept_helper_returns_provider`;
delimiter ;;
CREATE PROCEDURE `pr_concept_helper_returns_provider`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para calcular los conceptos de Devolucion de Compra'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'ICOSTO';

	declare vrConceptNameIVA varchar(50) DEFAULT 'IVA';

	declare vrConceptNameIMPORTE varchar(50) DEFAULT 'IMPORTE';

	declare vrConceptNameDESCUENTO varchar(50) DEFAULT 'DESCUENTO';



	declare vrConceptIdICOSTO int default 0;

	declare vrConceptIdIVA int default 0;

	declare vrConceptIdIMPORTE int default 0;

	declare vrConceptIdDESCUENTO int default 0;

	

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdIVA  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameIVA and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdIMPORTE  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameIMPORTE and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdDESCUENTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameDESCUENTO and isActive = 1 limit 1;			



	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

		(tm.quantity * tm.unitaryCost) as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master_detail tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		0,

		0,

		vrConceptIdIVA,

		tm.tax1 as amountConcept,

		vrCurrencyID,

		vrExchangeRate

	from 

		tb_transaction_master tm 

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		0,

		0,

		vrConceptIdDESCUENTO,

		tm.discount as amountConcept,

		vrCurrencyID, 

		vrExchangeRate 

	from 

		tb_transaction_master tm  

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select 

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		0, 

		0,

		vrConceptIdIMPORTE,

		(tm.subAmount + tm.tax1 - tm.discount ) as amountConcept,

		vrCurrencyID, 

		vrExchangeRate 

	from 

		tb_transaction_master tm  

	where

		tm.companyID = prCompanyID and 

		tm.transactionID = prTransactionID and 

		tm.transactionMasterID = prTransactionMasterID and 

		tm.isActive = 1;

		

		

	update tb_transaction_master_concept set value = 0 WHERE value is null and  transactionMasterID = prTransactionMasterID and transactionID = prTransactionID ;

		

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_concept_helper_salaryadvance
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_concept_helper_salaryadvance`;
delimiter ;;
CREATE PROCEDURE `pr_concept_helper_salaryadvance`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para Contabilizar Adelantos de Salario'
BEGIN

		declare vrConceptNameAdelanto varchar(50) DEFAULT 'ADELANTOS';

	declare vrConceptIdSalario int default 0;

	declare vrConceptIdAdelanto int default 0;

	declare vrConceptIdComision int default 0;

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	DECLARE varDolarName VARCHAR(20);

	DECLARE varDolarVenta VARCHAR(20);

	DECLARE varCordobaName VARCHAR(20);

	DECLARE varCurrencyIDDolar int default 0;

	DECLARE varCurrencyIDCordoba int default 0;

	

	

		CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_FUNCTION',varCordobaName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_REPORT',varDolarName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_EXCHANGE_SALE',varDolarVenta);

	

	select c.currencyID into varCurrencyIDCordoba  from tb_currency c where c.name = varCordobaName;

	select c.currencyID into varCurrencyIDDolar from tb_currency c where c.name = varDolarName;

	

		select tc.conceptID into vrConceptIdAdelanto  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameAdelanto and isActive = 1 limit 1;

	

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdAdelanto,

		IF (IFNULL(t.exchangeRate,vrExchangeRate) > 1,round(tm.amount  / t.exchangeRate,2),tm.amount) as amountConcept ,

		t.currencyID as currencyID ,

		t.exchangeRate as exchangeRate

	from

		tb_transaction_master t

		inner join tb_transaction_master_detail tm  on 

			t.transactionMasterID = tm.transactionMasterID and 

			t.transactionID = tm.transactionID 

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

	

	



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_concept_helper_share
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_concept_helper_share`;
delimiter ;;
CREATE PROCEDURE `pr_concept_helper_share`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para obtener los conceptos de los Abonos de Credito'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'IMPORTE';

	declare vrConceptNameINTERES varchar(50) DEFAULT 'INTERES';

	declare vrConceptNameGANANCIA_TC varchar(50) DEFAULT 'GANANCIA X T/C';

	declare vrConceptIdICOSTO int default 0;

	declare vrConceptIdINTERES int default 0;

	declare vrConceptIdGANANCIA_TC int default 0;

	

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	DECLARE varDolarName VARCHAR(20);

	DECLARE varDolarVenta VARCHAR(20);

	DECLARE varCordobaName VARCHAR(20);

	DECLARE varCurrencyIDDolar int default 0;

	DECLARE varCurrencyIDCordoba int default 0;

	

	

		CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_FUNCTION',varCordobaName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_REPORT',varDolarName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_EXCHANGE_SALE',varDolarVenta);

	

	

	select c.currencyID into varCurrencyIDCordoba  from tb_currency c where c.name = varCordobaName;

	select c.currencyID into varCurrencyIDDolar from tb_currency c where c.name = varDolarName;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdINTERES  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameINTERES and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdGANANCIA_TC  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameGANANCIA_TC and isActive = 1 limit 1;

	

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

						IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,cc.capital,cc.capital / tm.exchangeRateReference ) as amountConcept ,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID,

		IFNULL(tm.exchangeRateReference,vrExchangeRate) as exchangeRate

	from

		tb_transaction_master_detail tm

		inner join tb_transaction_master_detail_credit cc on

			tm.transactionMasterDetailID = cc.transactionMasterDetailID

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdGANANCIA_TC,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,	0 ,round((tm.amount *  (tmx.exchangeRate + varDolarVenta) ) - (tm.amount /  tm.exchangeRateReference),2)) as amountConcept , 		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID,

		IFNULL(tm.exchangeRateReference,vrExchangeRate)  as exchangeRate

	from

		tb_transaction_master_detail tm

		inner join tb_transaction_master_detail_credit cc on

			tm.transactionMasterDetailID = cc.transactionMasterDetailID

		inner join tb_transaction_master tmx on

			tm.transactionMasterID = tmx.transactionMasterID

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdINTERES,

						IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,cc.interest,cc.interest / tm.exchangeRateReference ) as amountConcept ,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID,

		IFNULL(tm.exchangeRateReference,vrExchangeRate)  as exchangeRate

	from

		tb_transaction_master_detail tm

		inner join tb_transaction_master_detail_credit cc on

			tm.transactionMasterDetailID = cc.transactionMasterDetailID

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_concept_helper_sharecapital
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_concept_helper_sharecapital`;
delimiter ;;
CREATE PROCEDURE `pr_concept_helper_sharecapital`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Crear los conceptos del abono al capital'
BEGIN

			declare vrConceptNameICOSTO varchar(50) DEFAULT 'IMPORTE';

	declare vrConceptNameINTERES varchar(50) DEFAULT 'INTERES';

	declare vrConceptNameGANANCIA_TC varchar(50) DEFAULT 'GANANCIA X T/C';

	declare vrConceptIdICOSTO int default 0;

	declare vrConceptIdINTERES int default 0;

	declare vrConceptIdGANANCIA_TC int default 0;

	declare vrCurrencyID int default 0;

	declare vrExchangeRate decimal(19,8) default 0;

	DECLARE varDolarName VARCHAR(20);

	DECLARE varDolarVenta VARCHAR(20);

	DECLARE varCordobaName VARCHAR(20);

	DECLARE varCurrencyIDDolar int default 0;

	DECLARE varCurrencyIDCordoba int default 0;

	

	

		CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_FUNCTION',varCordobaName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_CURRENCY_NAME_REPORT',varDolarName);

	CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_EXCHANGE_SALE',varDolarVenta);

	

	select c.currencyID into varCurrencyIDCordoba  from tb_currency c where c.name = varCordobaName;

	select c.currencyID into varCurrencyIDDolar from tb_currency c where c.name = varDolarName;

	

		select tc.conceptID into vrConceptIdICOSTO  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameICOSTO and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdINTERES  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameINTERES and isActive = 1 limit 1;

	select tc.conceptID into vrConceptIdGANANCIA_TC  from tb_transaction_concept tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and name = vrConceptNameGANANCIA_TC and isActive = 1 limit 1;

	

	select tc.currencyID,tc.exchangeRate into vrCurrencyID,vrExchangeRate  from tb_transaction_master tc where tc.companyID = prCompanyID and tc.transactionID = prTransactionID and tc.transactionMasterID = prTransactionMasterID  limit 1;

	

		delete from tb_transaction_master_concept where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdICOSTO,

				IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,tm.amount,tm.amount / tm.exchangeRateReference ) as amountConcept ,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID ,

		IFNULL(tm.exchangeRateReference,vrExchangeRate) as exchangeRate

	from

		tb_transaction_master_detail tm

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	

		insert into tb_transaction_master_concept (companyID,transactionID,transactionMasterID,componentID,componentItemID,conceptID,value,currencyID,exchangeRate)

	select

		tm.companyID,

		tm.transactionID,

		tm.transactionMasterID,

		tm.componentID,

		tm.componentItemID,

		vrConceptIdGANANCIA_TC,

		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,0 ,round((tm.amount *  (tmx.exchangeRate + varDolarVenta) ) - (tm.amount /  tm.exchangeRateReference),2)) as amountConcept , 		IF (IFNULL(tm.exchangeRateReference,vrExchangeRate) > 1,varCurrencyIDCordoba,varCurrencyIDDolar) as currencyID,

		IFNULL(tm.exchangeRateReference,vrExchangeRate)  as exchangeRate

	from

		tb_transaction_master_detail tm

		inner join tb_transaction_master_detail_credit cc on

			tm.transactionMasterDetailID = cc.transactionMasterDetailID

		inner join tb_transaction_master tmx on

			tm.transactionMasterID = tmx.transactionMasterID

	where

		tm.companyID = prCompanyID and

		tm.transactionID = prTransactionID and

		tm.transactionMasterID = prTransactionMasterID and

		tm.isActive = 1;

	



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_core_clear_data
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_core_clear_data`;
delimiter ;;
CREATE PROCEDURE `pr_core_clear_data`(IN `prCompanyID` INT, 
	IN `prBranchID` INT, 
	IN `prLoginID` INT, 
	OUT `prResult` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para limpiar los datos '
LBL_PROCEDURE:
BEGIN
			
		#Limpiar tablas de fechas de expiracion
		DELETE FROM tb_item_warehouse_expired WHERE quantity <= 0;
		DELETE FROM tb_item_warehouse_expired WHERE dateExpired is NULL;
		DELETE FROM tb_item_warehouse_expired WHERE dateExpired < '1900-01-01';
		DELETE e
		FROM tb_item_warehouse_expired AS e
		INNER JOIN tb_item_warehouse AS ws
				ON ws.itemID = e.itemID
			  AND ws.warehouseID = e.warehouseID
		WHERE ws.quantity <= 0;
		
		#Actualizar las cantidades generales 
		UPDATE tb_item d
		JOIN (
				SELECT 
						ws.itemID,
						SUM(ws.quantity) AS quantity
				FROM tb_item_warehouse ws
				GROUP BY ws.itemID
		) o
				ON d.itemID = o.itemID
		SET d.quantity = o.quantity;
			
			
		SET prResult = 1;
		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
		VALUES(
			prCompanyID,prBranchID,prLoginID,'',
			'pr_core_clear_data',
			0,0,CURRENT_TIMESTAMP());
				
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_core_get_exchange_rate
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_core_get_exchange_rate`;
delimiter ;;
CREATE PROCEDURE `pr_core_get_exchange_rate`(IN `prCompanyID` INT, IN `prDate` DATE, IN `prCurrencySource` VARBINARY(250), IN `prCurrencyTarget` VARBINARY(250), OUT `prExchangeRate` DECIMAL(18,8))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para obtener la tasa de cambio del dia '
LBL_PROCEDURE:

BEGIN

	DECLARE currencyIDDefault INT DEFAULT 0;

	DECLARE currencyIDSource  INT DEFAULT 0;

	DECLARE currencyIDTarget  INT DEFAULT 0;

	DECLARE ratio_1 DECIMAL(18,8) DEFAULT 1;

	DECLARE ratio_2 DECIMAL(18,8) DEFAULT 1;



	

	SET currencyIDDefault = (SELECT c.currencyID	FROM tb_parameter p INNER JOIN tb_company_parameter cp ON p.parameterID = cp.parameterID INNER JOIN tb_currency c ON  cp.value = c.name WHERE p.name = 'ACCOUNTING_CURRENCY_NAME_FUNCTION' and c.isActive = 1 and cp.companyID = prCompanyID LIMIT 1 );

	SET currencyIDSource  = (SELECT currencyID FROM tb_currency c WHERE c.name = prCurrencySource and isActive = 1 LIMIT 1);

	SET currencyIDTarget  = (SELECT currencyID FROM tb_currency c WHERE c.name = prCurrencyTarget and isActive = 1 LIMIT 1);

	

	

	IF currencyIDSource != currencyIDDefault THEN

		SELECT ratio INTO  ratio_1   FROM tb_exchange_rate e where e.companyID = prCompanyID and e.currencyID = currencyIDDefault and e.targetCurrencyID = currencyIDSource AND e.`date` =  prDate;

	END IF;

	

	IF currencyIDTarget != currencyIDDefault THEN 



		SELECT ratio INTO  ratio_2   FROM tb_exchange_rate e where e.companyID = prCompanyID and e.currencyID = currencyIDDefault and e.targetCurrencyID = currencyIDTarget AND e.`date` =  prDate;

	END IF;

	

	IF currencyIDSource = currencyIDTarget THEN 

		SET prExchangeRate =  1;

	ELSE 

		SET prExchangeRate = (1 * ratio_1) / ratio_2; 

	END IF; 

	  

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_core_get_indicators
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_core_get_indicators`;
delimiter ;;
CREATE PROCEDURE `pr_core_get_indicators`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prTokenID` VARCHAR(50), IN `prPeriodID` INT, IN `prCycleID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Obtiene la lista de indicadores'
BEGIN

	DECLARE minIndicatorID INT DEFAULT 0;

	DECLARE maxIndicatorID INT DEFAULT 0;

	DECLARE sqlScript VARCHAR(5000) DEFAULT '';	

	SET @utilityResult 	= 0;

	SET @query 				= '';

	SET @prPeriodID 		= prPeriodID;

	SET @prCycleID 		= prCycleID;

		

		delete from tb_indicator_tmp where companyID = prCompanyID and branchID = prBranchID and loginID = prLoginID;

	

		SET minIndicatorID = (SELECT MIN(indicadorID) FROM tb_indicator where companyID = prCompanyID);

	SET maxIndicatorID = (SELECT MAX(indicadorID) FROM tb_indicator where companyID = prCompanyID);

	WHILE minIndicatorID <= maxIndicatorID and minIndicatorID is not null DO 	

	

				SET @utilityResult 	= 0;

		IF EXISTS(select i.indicadorID from tb_indicator i where i.indicadorID = minIndicatorID and i.isGroup <> 1 LIMIT 1 ) THEN 		

			SET sqlScript 			= (SELECT i.script FROM tb_indicator i where i.indicadorID = minIndicatorID);

			SET @query    			= CONCAT(sqlScript);

			PREPARE stmt FROM @query;  

			EXECUTE stmt;

		   DEALLOCATE PREPARE stmt;   

		END IF;

		

				INSERT INTO tb_indicator_tmp (companyID,branchID,loginID,tokenID,indicadorID,value)

		VALUES(prCompanyID,prBranchID,prLoginID,prTokenID,minIndicatorID,  IFNULL(@utilityResult,0) );

		

		SET minIndicatorID					= (SELECT MIN(indicadorID) FROM tb_indicator  WHERE indicadorID > minIndicatorID and companyID = prCompanyID);

	END WHILE; 	

	

		select 

		i.name,

		i.`order`,

		i.code,

		i.label,

		i.description,

		i.posfix,

		i.prefix,

		i.isGroup,

		it.value

	from 

		tb_indicator i 

		inner join tb_indicator_tmp it on 

			i.indicadorID = it.indicadorID 

	where

		it.companyID = prCompanyID and 

		it.branchID = prBranchID and 

		it.loginID = prLoginID and 

		i.isActive = 1 

	order by 

		i.code,

		i.`order` ;

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_core_get_next_number
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_core_get_next_number`;
delimiter ;;
CREATE PROCEDURE `pr_core_get_next_number`(IN `prCompanyID` INT, IN `prComponent` VARBINARY(250), IN `prBranchID` INT, IN `prComponentItemID` INT, OUT `prNumber` VARCHAR(250))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para Obtener el siguiente numero de un Componente'
LBL_PROCEDURE:

BEGIN

	DECLARE componentID_ INT DEFAULT 0;

	DECLARE currentValue_ INT DEFAULT 0;

	DECLARE seed_ INT DEFAULT 0;

	DECLARE length_ INT DEFAULT 0;

	DECLARE counterID_ INT DEFAULT 0;

	DECLARE serie_ VARCHAR(10) DEFAULT '';

	DECLARE number_ VARCHAR(250) DEFAULT '';

	

		SET componentID_ 	= (SELECT c.componentID FROM tb_component c WHERE c.name = prComponent LIMIT 1);

	

		SELECT counterID,currentValue,seed,serie,`length` INTO counterID_,currentValue_,seed_,serie_,length_ FROM tb_counter WHERE companyID = prCompanyID AND componentID = componentID_ AND branchID = prBranchID  and componentItemID = prComponentItemID LIMIT 1;	

	

		UPDATE tb_counter set currentValue = currentValue + 1 WHERE counterID = counterID_;

	

		SET number_ 		= CAST(currentValue_ AS CHAR(250));

	SET number_			= LPAD(number_,length_ ,'0');

	SET prNumber 		= CONCAT(serie_,number_);

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_core_get_parameter_value
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_core_get_parameter_value`;
delimiter ;;
CREATE PROCEDURE `pr_core_get_parameter_value`(IN `prCompanyID` INT, IN `prParameter` VARBINARY(250), OUT `prValue` VARCHAR(250))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para obtener el valor de un parametro'
LBL_PROCEDURE:

BEGIN

	DECLARE value_ VARCHAR(250) DEFAULT '';	

	

	SET value_ 	= (SELECT cp.value FROM tb_parameter p  inner join tb_company_parameter cp on  p.parameterID = cp.parameterID WHERE cp.companyID = prCompanyID and  p.name = prParameter LIMIT 1 );

	SET prValue = value_;

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_core_get_workflow_stage_init
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_core_get_workflow_stage_init`;
delimiter ;;
CREATE PROCEDURE `pr_core_get_workflow_stage_init`(IN `prCompanyID` INT, IN `prTable` VARBINARY(250), IN `prField` VARBINARY(250), OUT `prWorkflowStageInit` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para obtener el estado inicial de un tb_workflow Asociado a una columna de una Tabla'
LBL_PROCEDURE:

BEGIN

	

	DECLARE elementTypeIDTable_ INT DEFAULT 2; 

	DECLARE workflowID_ INT DEFAULT 0;

	DECLARE flavorID_ INT DEFAULT 0;

	DECLARE componentIDWorkflow_ INT DEFAULT 2;

	DECLARE workflowStageInit_ INT DEFAULT 0;

	

		SET workflowID_ 			= (SELECT se.workflowID FROM tb_element e inner join tb_subelement se on e.elementID = se.elementID WHERE e.name = prTable and se.name = prField AND e.elementTypeID = elementTypeIDTable_ AND workflowID IS NOT NULL LIMIT 1);

	IF (workflowID_ IS NULL) THEN		

		LEAVE LBL_PROCEDURE;

	END IF;

	

		SET flavorID_ 				= (SELECT flavorID FROM tb_company_component_flavor c where c.companyID = prCompanyID and componentID = componentIDWorkflow_ AND componentItemID = workflowID_ LIMIT 1);

	IF (flavorID_ IS NULL) THEN		

		LEAVE LBL_PROCEDURE;

	END IF;

	

		SET workflowStageInit_ 		= (SELECT ws.workflowStageID  FROM tb_workflow_stage ws WHERE ws.workflowID = workflowID_ and ws.flavorID = flavorID_ AND isInit = 1 LIMIT 1 );

	IF (workflowStageInit_ IS NULL) THEN		

		LEAVE LBL_PROCEDURE;

	END IF;

	

	SET prWorkflowStageInit = workflowStageInit_;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_certificate_of_grades
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_certificate_of_grades`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_certificate_of_grades`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT,  IN `prGrado` int, IN `prYear` INT , IN prCustomerID INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Obtener certificado de nota '
BEGIN

	SET SESSION group_concat_max_len = 15000;

	

	

	SET @sqlTField = NULL;

	SELECT

	GROUP_CONCAT(DISTINCT CONCAT(

		' "',    ci.`name`  ,'" ' )

	)

	INTO @sqlTField	

	FROM 

		tb_catalog c 

		INNER JOIN tb_catalog_item ci  ON 

			c.catalogID = ci.catalogID 

	WHERE  

		c.isActive = 1 AND 

		c.catalogID = 102  

	ORDER BY 

	  ci.sequence ; 

		

		

	

	SET @sqlT = NULL;

	SELECT

	GROUP_CONCAT(DISTINCT CONCAT(

		'

				SUM(

					CASE WHEN 

						concat(uz.Mes ) = "', ci.`name`, '" THEN 

							uz.ValorCuantitativo 

					ELSE 

							0 

					END

				) 

				AS "',    ci.`name`  ,'" ' )

	)

	INTO @sqlT	

	FROM 

		tb_catalog c 

		INNER JOIN tb_catalog_item ci  ON 

			c.catalogID = ci.catalogID 

	WHERE  

		c.isActive = 1 AND 

		c.catalogID = 102  

	ORDER BY 

	  ci.sequence ; 



	

		

	SET @sqlT = 

	CONCAT(

		'

		SELECT 

			r1.Materia,

			

			

			`01-Enero`,

			`02-Febrero`,

			`03-Marzo`,

		  r1.Trimestral1,			

			ifnull(

				(

					select 

						ui1.`reference3` 

					from  

						tb_catalog_item ui1 

					where 

						ui1.catalogID = 98 /*cualitativo*/ and 

						r1.Trimestral1 between ui1.reference1 and ui1.reference2 

					limit 1

				),

				"ND"

			) as Trimestre1Cualitativo, 

			

			

			

			`04-Abril`,

			`05-Mayo`,

			`06-Junio`,

			r1.Trimestral2,

			ifnull(

				(

					select 

						ui1.`reference3` 

					from  

						tb_catalog_item ui1 

					where 

						ui1.catalogID = 98 /*cualitativo*/ and 

						r1.Trimestral2 between ui1.reference1 and ui1.reference2 

					limit 1

				),

				"ND"

			) as Trimestre2Cualitativo, 

			

			

			

			`07-Julio`,

			`08-Agosto`,

			`09-Septiembre`,

			r1.Trimestral3,

			ifnull(

				(

					select 

						ui1.`reference3` 

					from  

						tb_catalog_item ui1 

					where 

						ui1.catalogID = 98 /*cualitativo*/ and 

						r1.Trimestral3 between ui1.reference1 and ui1.reference2 

					limit 1

				),

				"ND"

			) as Trimestre3Cualitativo, 

			

			

			

			`10-Octubre`,

			`11-Noviembre`,

			`12-Diciembre`,

			r1.Trimestral4,

			ifnull(

				(

					select 

						ui1.`reference3` 

					from  

						tb_catalog_item ui1 

					where 

						ui1.catalogID = 98 /*cualitativo*/ and 

						r1.Trimestral4 between ui1.reference1 and ui1.reference2 

					limit 1

				),

				"ND"

			) as Trimestre4Cualitativo, 

			

			

			

			r1.Anualidad,

			ifnull(

				(

					select 

						ui1.`reference3` 

					from  

						tb_catalog_item ui1 

					where 

						ui1.catalogID = 98 /*cualitativo*/ and 

						r1.Anualidad between ui1.reference1 and ui1.reference2 

					limit 1

				),

				"ND"

			) as AnualidadCualitativo

			

			

		FROM 

			(

					SELECT 

						Materia, 

						

						`01-Enero`,

						`02-Febrero`,

						`03-Marzo`,

						ROUND((IFNULL(`01-Enero`,0) + IFNULL(`02-Febrero`,0) + IFNULL(`03-Marzo`,0) ) / 3,2) Trimestral1 ,

						

						`04-Abril`,

						`05-Mayo`,

						`06-Junio`,

						ROUND((IFNULL(`04-Abril`,0) + IFNULL(`05-Mayo`,0) + IFNULL(`06-Junio`,0) ) / 3,2) Trimestral2 ,

						

						`07-Julio`,

						`08-Agosto`,

						`09-Septiembre`,

						ROUND((IFNULL(`07-Julio`,0) + IFNULL(`08-Agosto`,0) + IFNULL(`09-Septiembre`,0) ) / 3,2) Trimestral3 ,

						

						`10-Octubre`,

						`11-Noviembre`,

						`12-Diciembre`,

						ROUND((IFNULL(`10-Octubre`,0) + IFNULL(`11-Noviembre`,0) + IFNULL(`12-Diciembre`,0) ) / 3,2) Trimestral4 ,

						

						

						(

							ROUND(

							(

								(IFNULL(`01-Enero`,0)) +

								(IFNULL(`02-Febrero`,0)) +

								(IFNULL(`03-Marzo`,0)) +

								(IFNULL(`04-Abril`,0)) +

								(IFNULL(`05-Mayo`,0)) +

								(IFNULL(`06-Junio`,0)) +

								(IFNULL(`07-Julio`,0)) +

								(IFNULL(`08-Agosto`,0)) +

								(IFNULL(`09-Septiembre`,0)) +

								(IFNULL(`10-Octubre`,0)) +

								(IFNULL(`11-Noviembre`,0)) +

								(IFNULL(`12-Diciembre`,0))

							) 

							/ 12,2)

						) as Anualidad 

						

						

					FROM 

						(

								SELECT 

										Materia, 

										', @sqlT, 

								'from 

										(		

												SELECT 

													mat.`name` as Materia,

													mes.name as Mes ,

													c.amount as ValorCuantitativo

												FROM 

													tb_transaction_master c 

													inner join tb_public_catalog_detail mat on 

														mat.publicCatalogDetailID = c.areaID 

													inner join tb_catalog_item mes on 

														mes.catalogID = 102 /*lista de meses*/ and 

														MONTH(c.transactionOn) = mes.sequence  								 		

												WHERE 

													c.isActive = 1 and 

													YEAR(c.transactionOn) = ',prYear,' /**/ and 

													c.classID = ',prGrado,' /*grado*/ and 

													c.entityID = ',prCustomerID,' /*customerID*/ 

										)  uz 

									group by  				

										uz.Materia

						) proc 

				) r1 

	');





	

	

	

	PREPARE stmt FROM @sqlT;

	EXECUTE stmt;

	DEALLOCATE PREPARE stmt;

	

	

	select 	

		c.customerNumber,

		concat(nat.firstName,' ',nat.lastName) as Nombre,

		prYear as Ano ,

		(SELECT ci.display from tb_catalog_item ci where ci.catalogItemID =  prGrado) as Grado  

	from 

		tb_customer c 

		inner join tb_naturales nat on 

			nat.entityID = c.entityID 

	where 

		c.entityID = prCustomerID;

		

	

	select 

		c.`name` as Nombre,

		c.display ,

		c.description, 		

		c.sequence,

		c.reference1,

		c.reference2 ,

		c.reference3

	from 

		tb_catalog_item c 

	where 

		c.catalogID = 98 

	order by 

		c.sequence;  

 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_collection_manager
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_collection_manager`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_collection_manager`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT, IN `prEmployeeCode` VARCHAR(50),prStartOn DATETIME , prEndOn DATETIME)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN

	declare varDate date;

	declare varZonaHoraria int default 0; 

	 

  set varZonaHoraria = (

			select 				

				uu.value  

			from 

				tb_parameter u 

				inner join tb_company_parameter uu on 

					uu.parameterID = u.parameterID

			where 

				u.`name` = 'CORE_ZONA_HORARIA' and 

				uu.companyID = 2 

	);

	

	

	

	set prEndOn =  date_add(prEndOn, interval 23 hour);	

	set prEndOn =  date_add(prEndOn, interval 59 minute);	

	set prEndOn =  date_add(prEndOn, interval 59 second);	

	

	

	select 

	

		

		case 

			when prEmployeeCode = 'EMP00000000' then 

				'EMP00000000' 

			else 

				tec.employerNumber 

		end as FiltroCode,  

		case 

			when prEmployeeCode = 'EMP00000000' then 

				'TODOS' 

			else 

				tec.employerName   

		end as FiltroName,  

		tec.employerNumber as NoGestor,

		tec.employerName as Gestor,

		

		

		

		c.customerNumber as NoCliente,

		concat(cn.firstName,' ',cn.lastName) as Cliente,

		c.phoneNumber as Telefono,

				

		CONCAT(

			c.address,

			IFNULL(tv.note,'')	

		) as Direccion, 

		

		ccc.documentNumber as Factura, 

		ws.name as Estado,

		cca.dateApply as Fecha,

		round(cca.`share`,2) as CuotaCompleta,

		round(cca.`remaining`,2) as Cuota,

		'__________________' as Abono,

		cur.name as Moneda,

		if(varDate <= date(cca.dateApply),'BLUE'  ,'RED'  )  as Atraso,

		if(varDate <= date(cca.dateApply),0,round(cca.remaining,2) )  as MontoTotalAtrazo,

		if(varDate <= date(cca.dateApply), round(cca.remaining,2),  0 )  as MontoTotalCobradoCorriente,

		if(varDate = cca.dateApply,1,0 )  as MontoTotalMetaDia

		

		

	from

		tb_customer c 

		inner join tb_naturales cn on 

			c.entityID = cn.entityID 

		inner join tb_customer_credit_document ccc on 

			c.entityID = ccc.entityID 

		inner join tb_customer_credit_amoritization cca on 

			ccc.customerCreditDocumentID = cca.customerCreditDocumentID 

		inner join tb_workflow_stage ws on 

			ccc.statusID = ws.workflowStageID 

		inner join tb_currency cur on 

			ccc.currencyID = cur.currencyID 

		left join tb_entity_phone ephone on 

			cn.entityID = ephone.entityID and ephone.isPrimary = 1 

			

		left join (			

							SELECT 

									cxx.entityID as clientID, 

									IFNULL(GROUP_CONCAT(nxx.firstName SEPARATOR ', '),'') AS employerName ,

									IFNULL(GROUP_CONCAT(exx.employeNumber SEPARATOR ', '),'') AS employerNumber 

							FROM 

								tb_customer cxx

								LEFT JOIN tb_relationship rxx ON 

									cxx.entityID = rxx.customerID and 

									rxx.isActive = 1 

								LEFT JOIN tb_employee exx ON 

									rxx.employeeID = exx.entityID

								LEFT JOIN tb_naturales nxx on 

									nxx.entityID = exx.entityID

								GROUP BY 

										cxx.entityID

								ORDER BY 

									exx.entityID asc 

		) tec on 

			tec.clientID = c.entityID  

		left join (

			select 

							tmx.entityID,

							max(tmx.transactionMasterID) IDMAx

						from 

							tb_transaction_master tmx 

						where 

							tmx.isActive = 1 and 

							tmx.transactionID = 35   

						group by 

							tmx.entityID 

		) tvm on 

			tvm.entityID = c.entityID 

		left join  tb_transaction_master tv on 

			tv.entityID = c.entityID  and 

			tv.transactionMasterID = tvm.IDMAx  

									

	where

		cca.remaining <> 0  		

		and 

		(

			cca.dateApply <= prEndOn 

			

		) 

		and  ws.vinculable = 1 

		and 

		(

			(

				tec.employerNumber = prEmployeeCode and (prEmployeeCode != 'EMP00000000' )

			)

			or 

			(

				'EMP00000000' = prEmployeeCode 

			)

		) 

		and 

		(	

					

			   	(

						(ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0)

					)

					

					or 

					(

						fn_get_access_ready(prCompanyID  , prUserID  , 174  , 0  , 0  ) = 1 

					)

					or

					

					(

						fn_get_provider_id (prCompanyID,prUserID) = 0 

					)

		)

	order by 

		tec.employerNumber,cn.firstName ,cca.dateApply;  

	 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_customer_credit
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_credit`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_customer_credit`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'cartera de credito diferenciada por moneda'
BEGIN
	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		
	DECLARE currencyIDNameSource VARCHAR(250);
	DECLARE currencyIDNameTarget VARCHAR(250);	
	DECLARE moneda_ VARCHAR(50); 
	DECLARE currencyID_ INT DEFAULT 0;
	DECLARE currencyIDTarget_ INT DEFAULT 0;
	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;
	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;
	DECLARE minClientID int DEFAULT 0;
	DECLARE maxClientID int DEFAULT 0;
	DECLARE maxDiasMora_ int  DEFAULT 0;
	DECLARE montoAtrazado_ DECIMAL(19,9) DEFAULT 0;
	DECLARE cantidadFactura_ INT DEFAULT 0;
	DECLARE customerNumber_ varchar(50); 
	DECLARE lastVisit_ varchar(550); 
	DECLARE documentNumber_ varchar(50); 
	DECLARE capitalPrestado_ DECIMAL(19,9) DEFAULT 0;
	DECLARE interesDebengado_ DECIMAL(19,9) DEFAULT 0;
	DECLARE capitalPagado_ DECIMAL(19,9) DEFAULT 0; 
	DECLARE interesPagado_ DECIMAL(19,9) DEFAULT 0;
	DECLARE proximoPago_ DATE;
	DECLARE maximoPago_ DATE;	
	DECLARE ultimoPagoFecha_ DATE;
	DECLARE montoProximoPago_ DECIMAL(19,9) DEFAULT 0;
	DECLARE capitalAtrazado_ DECIMAL(19,9) DEFAULT 0;
	DECLARE interesAtrazado_ DECIMAL(19,9) DEFAULT 0;
	
	CREATE TEMPORARY TABLE tmp_customer_credit 					( 
			ID INT AUTO_INCREMENT PRIMARY KEY,
			customerNumber varchar(50),legalName varchar(550),
			commercialName varchar(550),firstName varchar(550),lastName varchar(550),
			limitCredit decimal(19,9),balanceCredit decimal(19,9),
			moneda varchar(50),
			tipoCambio decimal(19,4),customerCreditLineID int,documentNumber varchar(50),
			statusDocument varchar(50),dateApply DATETIME,balanceStart DECIMAL(19,9),
			interest DECIMAL(19,9),capital DECIMAL(19,9),share DECIMAL(19,9),balanceEnd DECIMAL(19,9),
			remaining DECIMAL (19,9), dayDelay INT,statusShare varchar(50),
			direccion varchar(550),
			identification varchar(50) ,
			phone varchar(150),			
			lastShareNumber varchar(50),														
			dateLastShareNumber date,															
			amountLastShareNumber decimal(19,4),			
			employerName varchar(150),
			pais varchar(150),
			departamento varchar(150),
			municipio varchar(150),
			domicilio varchar(150),
			gps varchar(150),
			tipo_cobro varchar(150),
			tipo_factura varchar(150),
			observacion varchar(150) 
	); 

	CREATE TEMPORARY TABLE tmp_customer_credit_summary 			(
				ID INT AUTO_INCREMENT PRIMARY KEY,customerNumber varchar(50),
				legalName varchar(550),commercialName varchar(550),firstName varchar(550),
				lastName varchar(550),limitCredit decimal(19,9),balanceCredit decimal(19,9),				
				factura varchar(50),				
				tipoCambio decimal(19,4),				
				moneda varchar(50),				
				capitalPrestado decimal(19,9) default 0,				
				maxDiasMora int default 0,				
				montoAtrazado decimal(19,9) default 0,				
				capitalAtrazado decimal(19,9) default 0,				
				interesAtrazado decimal(19,9) default 0,																				
				capitalPagado decimal(19,9) default 0,																				
				interesPagado decimal(19,9) default 0,
				proximoPago DATE ,
				montoProximoPago decimal(19,9) default 0,
				ultimoPagoFecha DATE,																
				direccion varchar(550), 
				identification varchar(50),
				phone varchar(150),				
				lastShareNumber varchar(50),				
				dateLastShareNumber date,				
				amountLastShareNumber decimal(19,4),
				lastVisit varchar(550),
				remainingDocument decimal(19,4),
				employerName varchar(150),
			  pais varchar(150),
			  departamento varchar(150),
			  municipio varchar(150),
			  domicilio varchar(150),
			  gps varchar(150),
			  tipo_cobro varchar(150),
			  tipo_factura varchar(150),
			  observacion varchar(150) 			
  ); 
	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);
	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);
	

	insert into tmp_customer_credit (
		customerNumber ,legalName ,commercialName ,firstName ,lastName ,limitCredit ,
		balanceCredit,tipoCambio ,customerCreditLineID ,documentNumber ,statusDocument ,dateApply ,
		balanceStart ,interest ,capital ,share ,balanceEnd ,remaining, dayDelay ,
		statusShare,moneda ,direccion,identification,phone,
		lastShareNumber,dateLastShareNumber ,amountLastShareNumber	,
		employerName,
		pais,
		departamento,
		municipio,
		domicilio,
		gps,
		tipo_cobro,
		tipo_factura,
		observacion			
	)
	select 
		c.customerNumber,
		l.legalName,
		l.comercialName,
		n.firstName,
		n.lastName,
		ccl.limitCredit  AS limitCredit,
		ccl.balance  AS balance,
		tmc.exchangeRate AS TipoCambio,
		ccl.customerCreditLineID,
		ccd.documentNumber,
		ws1.name,
		cca.dateApply,
		cca.balanceStart,
		cca.interest,
		cca.capital,
		cca.share,
		cca.balanceEnd,
		cca.remaining, 
		cca.dayDelay as dayDelay,
		ws2.name		,
		cur.simbol  , 
		n.address as direccion,
		c.identification as identification ,
		IFNULL(
			(select  lm.number from tb_entity_phone lm where lm.entityID = e.entityID and lm.isPrimary = 1 limit 1 ),
			IFNULL(c.phoneNumber , 'N/A') 
		)  as phone,
		IFNULL(infoUltimaTransaccion.transactionNumber,'N/A') 	 as lastShareNumber,
		IFNULL(infoUltimaTransaccion.transactionOn,'N/A') 	 as dateLastShareNumber,
		IFNULL(infoUltimaTransaccion.amount,'0') 	 as amountLastShareNumber,
		nate.firstName as employerName ,
		
		pais.`name` as pais,
		departamento.`name` as departamento,
		municipio.`name` as municipio,
		c.location as domicilio,
		c.reference1 as gps,
		c.reference2 as tipo_cobro,
		c.reference3 as tipo_factura,
		c.reference4 as observacion			 
	from 
		tb_entity e
		inner join tb_customer c on 
			e.entityID = c.entityID
		inner join tb_catalog_item pais on 
			pais.catalogItemID = c.countryID 
		inner join tb_catalog_item departamento on 
			departamento.catalogItemID = c.stateID  
		inner join tb_catalog_item municipio on 
			municipio.catalogItemID = c.cityID 
			
		inner join tb_naturales n on 
			c.entityID = n.entityID  		
		inner join tb_legal l on 
			l.entityID = n.entityID
		inner join tb_customer_credit_line ccl on 
			e.entityID = ccl.entityID  
		inner join tb_customer_credit_document ccd on 
			ccd.entityID = e.entityID and 
			ccd.customerCreditLineID = ccl.customerCreditLineID 
		inner join tb_transaction_master tmc on  
			ccd.documentNumber = tmc.transactionNumber and 
			tmc.isActive = 1 and 
			ccd.isActive = 1 
		inner join tb_customer_credit_amoritization cca on 
			ccd.customerCreditDocumentID = cca.customerCreditDocumentID 
		inner join tb_workflow_stage ws1 on 
			ccd.statusID = ws1.workflowStageID 
		inner join tb_workflow_stage ws2 on  
			cca.statusID = ws2.workflowStageID
		inner join tb_currency cur on 
			tmc.currencyID = cur.currencyID 
		inner join tb_naturales nate on 
			nate.entityID = tmc.entityIDSecondary 
		left join (		
				select 
					lxp.customerCreditDocumentID,
					max(cxl.transactionMasterID) maxTransactionID 
				from 
					tb_transaction_master cxl 
					inner join tb_transaction_master_detail lmxl on 
						lmxl.transactionMasterID = cxl.transactionMasterID 
					inner join tb_customer_credit_document lxp on 
						lxp.documentNumber = lmxl.reference1 and 
						lxp.customerCreditDocumentID = lmxl.componentItemID  
				where  
					cxl.transactionID = 23  
				group by 
					lxp.customerCreditDocumentID 
					
		) ultimaTransaccion on  
			ultimaTransaccion.customerCreditDocumentID = ccd.customerCreditDocumentID 
		left join (
				select 
					lxp.customerCreditDocumentID,
					cxl.transactionMasterID, 
					cxl.transactionNumber,
					cxl.transactionOn,
					cxl.amount 
				from 
					tb_transaction_master cxl 
					inner join tb_transaction_master_detail lmxl on 
						lmxl.transactionMasterID = cxl.transactionMasterID  
					inner join tb_customer_credit_document lxp on 
						lxp.documentNumber = lmxl.reference1 and 
						lxp.customerCreditDocumentID = lmxl.componentItemID  
		) as infoUltimaTransaccion on 
			ultimaTransaccion.customerCreditDocumentID = infoUltimaTransaccion.customerCreditDocumentID and 
			ultimaTransaccion.maxTransactionID = infoUltimaTransaccion.transactionMasterID 
	where
		e.companyID 	= prCompanyID and
		c.isActive 		= 1 and 
		ccl.isActive 	= 1  and 
		ccd.isActive 	= 1 and 
		tmc.isActive 	= 1 and 
		ws1.name in ('REGISTRADO' ) and 
		(	
			   	(
						(ccd.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and 
						(fn_get_provider_id (prCompanyID,prUserID) != 0)
					)
					or 
					(fn_get_provider_id (prCompanyID,prUserID) = 0 )
		)
	order by 
		c.customerNumber,
		ccd.dateOn, 
		ccd.documentNumber,
		cca.creditAmortizationID;
		
		
	INSERT INTO tmp_customer_credit_summary (
		customerNumber ,legalName ,commercialName,firstName ,lastName ,factura,direccion,
		identification,phone,lastShareNumber,dateLastShareNumber ,amountLastShareNumber,employerName,
		pais,
		departamento,
		municipio,
		domicilio,
		gps,
		tipo_cobro,
		tipo_factura,
		observacion,					
		limitCredit,balanceCredit, remainingDocument	
		 
	)
	select 
		x.customerNumber ,
		x.legalName ,
		x.commercialName,
		x.firstName ,
		x.lastName ,
		x.documentNumber,
		x.direccion as direccion,
		x.identification,
		x.phone,
		x.lastShareNumber,
		x.dateLastShareNumber , 
		x.amountLastShareNumber,		
		x.employerName,
		x.pais,
		x.departamento,
		x.municipio,
		x.domicilio,
		x.gps,
		x.tipo_cobro,
		x.tipo_factura,
		x.observacion,		
		SUM(x.limitCredit) as limitCredit,
		SUM(x.balanceCredit) as balanceCredit,
		SUM(x.remaining) as remainingDocument
	from 
		tmp_customer_credit x
	group by 
		x.customerNumber ,
		x.legalName , 
		x.commercialName,
		x.firstName ,
		x.lastName,
		x.documentNumber ,
		x.direccion,
		x.identification,
		x.phone,
		x.lastShareNumber,
		x.dateLastShareNumber ,
		x.amountLastShareNumber,
		x.employerName,
		x.pais,
		x.departamento,
		x.municipio,
		x.domicilio,
		x.gps,
		x.tipo_cobro,
		x.tipo_factura,
		x.observacion;			
	
	SET minClientID = (SELECT MIN(ID) FROM tmp_customer_credit_summary);
	SET maxClientID = (SELECT MAX(ID) FROM tmp_customer_credit_summary);
	WHILE minClientID <= maxClientID and minClientID is not null DO 	
		SET documentNumber_				= (SELECT C.factura FROM tmp_customer_credit_summary C WHERE ID = minClientID LIMIT 1);
		SET customerNumber_				= (SELECT C.customerNumber FROM tmp_customer_credit_summary C WHERE ID = minClientID LIMIT 1);
		SET moneda_								= (SELECT C.moneda FROM tmp_customer_credit C WHERE documentNumber = documentNumber_ LIMIT 1);
		SET capitalPrestado_			= (SELECT balanceStart FROM tmp_customer_credit WHERE  documentNumber = documentNumber_ ORDER BY dateApply LIMIT 1  );
		SET maxDiasMora_					= (
																		SELECT DATEDIFF(NOW(),dateApply) 
																		FROM tmp_customer_credit C 
																		WHERE 
																			documentNumber = documentNumber_  and 
																			dateApply <= curdate() AND 
																			remaining > 0  
																		ORDER BY dateApply LIMIT 1 
																);
		SET montoAtrazado_				= (
																	SELECT SUM(C.remaining)  
																	FROM tmp_customer_credit C 
																	WHERE 
																		documentNumber = documentNumber_ and 
																		remaining > 0 and  
																		dateApply <= curdate() 
																);
		SET capitalAtrazado_ 			= (SELECT SUM(capital) FROM tmp_customer_credit WHERE  documentNumber = documentNumber_ and remaining > 0 AND dateApply <= curdate());
		SET interesAtrazado_ 			= (SELECT SUM(interest) FROM tmp_customer_credit WHERE  documentNumber = documentNumber_ and remaining > 0 AND dateApply <= curdate());
		SET capitalPagado_				= (SELECT SUM(capital)  FROM tmp_customer_credit WHERE  documentNumber = documentNumber_ and remaining <= 0);
		SET interesPagado_				= (SELECT SUM(interest) FROM tmp_customer_credit WHERE  documentNumber = documentNumber_ and remaining <= 0);
		
		
		SET proximoPago_				= (SELECT MIN(C.dateApply) FROM tmp_customer_credit C WHERE documentNumber = documentNumber_  and remaining > 0  );		
		SET montoProximoPago_		= (SELECT SUM(C.remaining)  FROM tmp_customer_credit C WHERE C.documentNumber = documentNumber_  and C.dateApply = proximoPago_ );  
		SET maximoPago_ 				= (SELECT MAX(C.dateApply) FROM tmp_customer_credit C WHERE documentNumber = documentNumber_  and remaining > 0  );
		SET lastVisit_ 					= (
																SELECT IFNULL(note ,'Ninguna')
																FROM tb_transaction_master c 
																INNER JOIN tb_customer cus on 
																	cus.entityID = c.entityID 
																WHERE 
																	c.transactionID = 35  and 
																	c.isActive = 1 and 
																	cus.customerNumber = customerNumber_  
																ORDER BY 
																	c.transactionMasterID DESC 
																LIMIT 0,1 
															);
		 
		 
		UPDATE 	tmp_customer_credit_summary set 
			tipoCambio 			= exchangeRate_,
			moneda 				= moneda_,
			capitalPrestado 	= IFNULL(capitalPrestado_,0),				
			maxDiasMora 		= IFNULL(maxDiasMora_,0),
			montoAtrazado 		= IFNULL(montoAtrazado_,0),
			capitalAtrazado		= capitalAtrazado_,
			interesAtrazado		= interesAtrazado_,
			capitalPagado 		= IFNULL(capitalPagado_,0),
			interesPagado 		= IFNULL(interesPagado_,0),
			
			proximoPago 		= IF(DATE(NOW()) > maximoPago_,'1900-01-01',proximoPago_),
			montoProximoPago 	= IF(DATE(NOW()) > maximoPago_, 0,montoProximoPago_)  ,
			ultimoPagoFecha 	= maximoPago_,
			lastVisit = lastVisit_
		WHERE 
			ID = minClientID;
			
		
		SET minClientID					= (SELECT MIN(ID) FROM tmp_customer_credit_summary  WHERE ID > minClientID);

	END WHILE; 
	

	SELECT 
		RIGHT(customerNumber,15) as customerNumber ,		
		CONCAT(LEFT(firstName,250),' ',LEFT(lastName,250)) as legalName,
		commercialName,
		firstName ,lastName ,limitCredit,balanceCredit,
		exchangeRate_ - currencyTargetPurchase  as tipoCambioCompra,exchangeRate_ + currencyTargetSale as tipoCambioVenta,
		factura,		
		moneda,
		capitalPrestado,		
		maxDiasMora,
		montoAtrazado,
		capitalAtrazado,
		interesAtrazado,
		capitalPagado,
		interesPagado,		 
		proximoPago,
		montoProximoPago ,
		ultimoPagoFecha,
		direccion ,
		identification,
		phone ,
		lastShareNumber ,
		dateLastShareNumber ,
		amountLastShareNumber ,
		lastVisit ,
		remainingDocument  ,
		employerName,		
		pais,
		departamento,
		municipio,
		domicilio,
		gps,
		tipo_cobro,
		tipo_factura,
		observacion		 
	FROM 
		tmp_customer_credit_summary
	ORDER BY 
		maxDiasMora desc, firstName;
	
	DROP TABLE tmp_customer_credit;
	DROP TABLE tmp_customer_credit_summary;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_customer_credit_by_user
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_credit_by_user`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_customer_credit_by_user`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'cartera de credito diferenciada por moneda'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE moneda_ VARCHAR(50); 

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	DECLARE minClientID int DEFAULT 0;

	DECLARE maxClientID int DEFAULT 0;

	DECLARE maxDiasMora_ int  DEFAULT 0;

	DECLARE montoAtrazado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE cantidadFactura_ INT DEFAULT 0;

	DECLARE customerNumber_ varchar(50); 

	DECLARE capitalPrestado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE interesDebengado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE capitalPagado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE interesPagado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE proximoPago_ DATE;

	DECLARE montoProximoPago_ DECIMAL(19,9) DEFAULT 0;

	

	CREATE TEMPORARY TABLE tmp_customer_credit 					(

																ID INT AUTO_INCREMENT PRIMARY KEY,

																customerNumber varchar(50),legalName varchar(150),commercialName varchar(150),firstName varchar(150),lastName varchar(150),

																limitCredit decimal(19,9),balanceCredit decimal(19,9),

																moneda varchar(50),

																tipoCambio decimal(19,4),customerCreditLineID int,documentNumber varchar(50),statusDocument varchar(50),dateApply DATETIME,balanceStart DECIMAL(19,9),interest DECIMAL(19,9),capital DECIMAL(19,9),share DECIMAL(19,9),balanceEnd DECIMAL(19,9),remaining DECIMAL (19,9), dayDelay INT,statusShare varchar(50)

																); 

	CREATE TEMPORARY TABLE tmp_customer_credit_summary 			(

																ID INT AUTO_INCREMENT PRIMARY KEY,

																customerNumber varchar(50),legalName varchar(150),commercialName varchar(150),firstName varchar(150),lastName varchar(150),

																limitCredit decimal(19,9),balanceCredit decimal(19,9),

																moneda varchar(50),

																tipoCambio decimal(19,4),maxDiasMora int default 0,cantidadFactura int default 0,montoAtrazado decimal(19,9) default 0,capitalPrestado decimal(19,9) default 0,capitalPagado decimal(19,9) default 0,interesPagado decimal(19,9) default 0,proximoPago DATE ,montoProximoPago decimal(19,9) default 0

																); 

	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

		CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	

	

	insert into tmp_customer_credit (customerNumber ,legalName ,commercialName ,firstName ,lastName ,limitCredit ,balanceCredit,tipoCambio ,customerCreditLineID ,documentNumber ,statusDocument ,dateApply ,balanceStart ,interest ,capital ,share ,balanceEnd ,remaining, dayDelay ,statusShare,moneda )

	select 

		c.customerNumber,

		l.legalName,

		l.comercialName,

		n.firstName,

		n.lastName,

		ccl.limitCredit  AS limitCredit,

		ccl.balance  AS balance,

		tmc.exchangeRate AS TipoCambio,

		ccl.customerCreditLineID,

		ccd.documentNumber,

		ws1.name,

		cca.dateApply,

		cca.balanceStart,

		cca.interest,

		cca.capital,

		cca.share,

		cca.balanceEnd,

		cca.remaining, 

		cca.dayDelay as dayDelay,

		ws2.name		,

		cur.simbol 

	from 

		tb_entity e

		inner join tb_customer c on 

			e.entityID = c.entityID

		inner join tb_naturales n on 

			c.entityID = n.entityID  

		inner join tb_legal l on 

			l.entityID = n.entityID

		inner join tb_customer_credit_line ccl on 

			e.entityID = ccl.entityID  

		inner join tb_customer_credit_document ccd on 

			ccd.entityID = e.entityID and 

			ccd.customerCreditLineID = ccl.customerCreditLineID 

		inner join tb_transaction_master tmc on  

			ccd.documentNumber = tmc.transactionNumber and 

			tmc.isActive = 1 and 

			ccd.isActive = 1 

		inner join tb_customer_credit_amoritization cca on 

			ccd.customerCreditDocumentID = cca.customerCreditDocumentID 

		inner join tb_workflow_stage ws1 on 

			ccd.statusID = ws1.workflowStageID 

		inner join tb_workflow_stage ws2 on 

			cca.statusID = ws2.workflowStageID

		inner join tb_currency cur on 

			tmc.currencyID = cur.currencyID 

		inner join tb_user us on 

			us.employeeID = c.entityID 

	where

		e.companyID 	= prCompanyID and

		c.isActive 		= 1 and 

		ccl.isActive 	= 1  and 

		ccd.isActive 	= 1 and 

		tmc.isActive 	= 1 and 

		ws1.name not in ('CANCELADO','ANULADO') and

		(	

			   	((ccd.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		)

	order by 

		c.customerNumber,ccd.dateOn, ccd.documentNumber,cca.creditAmortizationID;

		

	INSERT INTO tmp_customer_credit_summary (customerNumber ,legalName ,commercialName,firstName ,lastName ,moneda,limitCredit,balanceCredit )

	select 

		x.customerNumber ,

		x.legalName ,

		x.commercialName,

		x.firstName ,

		x.lastName ,

		x.moneda,

		SUM(x.limitCredit) as limitCredit,

		SUM(x.balanceCredit) as balanceCredit

	from 

		tmp_customer_credit x

	group by 

		x.customerNumber ,

		x.legalName ,

		x.commercialName,

		x.firstName ,

		x.lastName,

		x.moneda ; 



	SET minClientID = (SELECT MIN(ID) FROM tmp_customer_credit_summary);

	SET maxClientID = (SELECT MAX(ID) FROM tmp_customer_credit_summary);

	WHILE minClientID <= maxClientID and minClientID is not null DO 	

		SET customerNumber_				= (SELECT C.customerNumber FROM tmp_customer_credit_summary C WHERE ID = minClientID );

		SET moneda_						   = (SELECT C.moneda FROM tmp_customer_credit_summary C WHERE ID = minClientID );



		SET maxDiasMora_				   = (SELECT DATEDIFF(NOW(),dateApply) FROM tmp_customer_credit C WHERE customerNumber = customerNumber_  and moneda = moneda_ and dateApply <= curdate() AND remaining > 0  ORDER BY dateApply LIMIT 1 );

		SET cantidadFactura_			   = (SELECT COUNT(documentNumber) from (SELECT distinct C.documentNumber as documentNumber FROM tmp_customer_credit C WHERE customerNumber = customerNumber_  and moneda = moneda_ ) p );

		SET proximoPago_				   = (SELECT MIN(C.dateApply) FROM tmp_customer_credit C WHERE customerNumber = customerNumber_  and remaining > 0 and moneda = moneda_ );

		SET montoAtrazado_				= (SELECT SUM(C.remaining)  FROM tmp_customer_credit C WHERE customerNumber = customerNumber_ and moneda = moneda_ and dateApply <= curdate() );

		SET capitalPrestado_			   = (SELECT SUM(capital) FROM tmp_customer_credit WHERE  customerNumber = customerNumber_  and moneda = moneda_);

		SET capitalPagado_				= (SELECT SUM((share - remaining) - interest)  FROM tmp_customer_credit WHERE  customerNumber = customerNumber_ and moneda = moneda_ and dateApply <= curdate() );

		SET interesPagado_				= (SELECT SUM( IF (((share - remaining) - capital   ) < 0 , 0 , (share - remaining) - capital   ) )   FROM tmp_customer_credit WHERE  customerNumber = customerNumber_  and moneda = moneda_ and dateApply <= curdate());

		SET montoProximoPago_			= (SELECT SUM(C.remaining)  FROM tmp_customer_credit C WHERE C.customerNumber = customerNumber_ and moneda = moneda_ and C.dateApply = proximoPago_ );  

 

		 

		UPDATE 	tmp_customer_credit_summary set tipoCambio = exchangeRate_,  maxDiasMora = IFNULL(maxDiasMora_,0),montoAtrazado = IFNULL(montoAtrazado_,0),cantidadFactura = IFNULL(cantidadFactura_,0),capitalPrestado = capitalPrestado + IFNULL(capitalPrestado_,0),	capitalPagado = capitalPagado + IFNULL(capitalPagado_,0),interesPagado = interesPagado + IFNULL(interesPagado_,0),proximoPago = proximoPago_,montoProximoPago = montoProximoPago_ where ID = minClientID;

		SET minClientID					= (SELECT MIN(ID) FROM tmp_customer_credit_summary  WHERE ID > minClientID);



	END WHILE; 





		SELECT 

		customerNumber ,legalName ,commercialName,firstName ,lastName ,

		limitCredit,

		balanceCredit,

		tipoCambio - currencyTargetPurchase  as tipoCambioCompra,

		tipoCambio + currencyTargetSale as tipoCambioVenta,

		moneda,

		maxDiasMora,

		montoAtrazado,

		cantidadFactura,

		capitalPrestado,

		0 capitalPagado,

		0 interesPagado,

		proximoPago,

		montoProximoPago 

	FROM 

		tmp_customer_credit_summary

	ORDER BY 

		proximoPago ASC,customerNumber , legalName;

	

		DROP TABLE tmp_customer_credit;

	DROP TABLE tmp_customer_credit_summary;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_customer_credit_dolares
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_credit_dolares`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_customer_credit_dolares`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'reporte de lista de clientes de credito'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE moneda_ VARCHAR(50); 

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	DECLARE minClientID int DEFAULT 0;

	DECLARE maxClientID int DEFAULT 0;

	DECLARE maxDiasMora_ int  DEFAULT 0;

	DECLARE montoAtrazado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE cantidadFactura_ INT DEFAULT 0;

	DECLARE customerNumber_ varchar(50); 

	DECLARE capitalPrestado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE interesDebengado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE capitalPagado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE interesPagado_ DECIMAL(19,9) DEFAULT 0;

	DECLARE proximoPago_ DATE;

	DECLARE montoProximoPago_ DECIMAL(19,9) DEFAULT 0;

	

	CREATE TEMPORARY TABLE tmp_customer_credit 					(

																ID INT AUTO_INCREMENT PRIMARY KEY,

																customerNumber varchar(50),legalName varchar(150),commercialName varchar(150),firstName varchar(150),lastName varchar(150),

																limitCredit decimal(19,9),balanceCredit decimal(19,9),

																moneda varchar(50),

																tipoCambio decimal(19,4),customerCreditLineID int,documentNumber varchar(50),statusDocument varchar(50),dateApply DATETIME,balanceStart DECIMAL(19,9),interest DECIMAL(19,9),capital DECIMAL(19,9),share DECIMAL(19,9),balanceEnd DECIMAL(19,9),remaining DECIMAL (19,9), dayDelay INT,statusShare varchar(50)

																); 

	CREATE TEMPORARY TABLE tmp_customer_credit_summary 			(

																ID INT AUTO_INCREMENT PRIMARY KEY,

																customerNumber varchar(50),legalName varchar(150),commercialName varchar(150),firstName varchar(150),lastName varchar(150),

																limitCredit decimal(19,9),balanceCredit decimal(19,9),																

																tipoCambio decimal(19,4),maxDiasMora int default 0,cantidadFactura int default 0,montoAtrazado decimal(19,9) default 0,capitalPrestado decimal(19,9) default 0,capitalPagado decimal(19,9) default 0,interesPagado decimal(19,9) default 0,proximoPago DATE ,montoProximoPago decimal(19,9) default 0

																); 

	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

		CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	

	

	insert into tmp_customer_credit (customerNumber ,legalName ,commercialName ,firstName ,lastName ,limitCredit ,balanceCredit,tipoCambio ,customerCreditLineID ,documentNumber ,statusDocument ,dateApply ,balanceStart ,interest ,capital ,share ,balanceEnd ,remaining, dayDelay ,statusShare,moneda )

	select 

		c.customerNumber,

		l.legalName,

		l.comercialName,

		n.firstName,

		n.lastName,

		ccl.limitCredit  AS limitCredit,

		ccl.balance  AS balance,

		tmc.exchangeRate AS TipoCambio,

		ccl.customerCreditLineID,

		ccd.documentNumber,

		ws1.name,

		cca.dateApply,

		cca.balanceStart,

		cca.interest,

		cca.capital,

		cca.share,

		cca.balanceEnd,

		cca.remaining, 

		cca.dayDelay as dayDelay,

		ws2.name		,

		cur.name 

	from 

		tb_entity e

		inner join tb_customer c on 

			e.entityID = c.entityID

		inner join tb_naturales n on 

			c.entityID = n.entityID  

		inner join tb_legal l on 

			l.entityID = n.entityID

		inner join tb_customer_credit_line ccl on 

			e.entityID = ccl.entityID  

		inner join tb_customer_credit_document ccd on 

			ccd.entityID = e.entityID and 

			ccd.customerCreditLineID = ccl.customerCreditLineID 

		inner join tb_transaction_master tmc on  

			ccd.documentNumber = tmc.transactionNumber and 

			tmc.isActive = 1 and 

			ccd.isActive = 1 

		inner join tb_customer_credit_amoritization cca on 

			ccd.customerCreditDocumentID = cca.customerCreditDocumentID 

		inner join tb_workflow_stage ws1 on 

			ccd.statusID = ws1.workflowStageID 

		inner join tb_workflow_stage ws2 on 

			cca.statusID = ws2.workflowStageID

		inner join tb_currency cur on 

			tmc.currencyID = cur.currencyID 

	where

		e.companyID 	= prCompanyID and

		c.isActive 		= 1 and 

		ccl.isActive 	= 1  and 

		ccd.isActive 	= 1 and 

		tmc.isActive 	= 1 and 

		ws1.name not in ('CANCELADO','ANULADO') and 

		(	

			   	((ccd.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		)

	order by 

		c.customerNumber,ccd.dateOn, ccd.documentNumber,cca.creditAmortizationID;

		

	INSERT INTO tmp_customer_credit_summary (customerNumber ,legalName ,commercialName,firstName ,lastName ,limitCredit,balanceCredit )

	select 

		x.customerNumber ,

		x.legalName ,

		x.commercialName,

		x.firstName ,

		x.lastName ,	

		SUM(x.limitCredit) as limitCredit,

		SUM(x.balanceCredit) as balanceCredit

	from 

		tmp_customer_credit x

	group by 

		x.customerNumber ,

		x.legalName ,

		x.commercialName,

		x.firstName ,

		x.lastName ; 





	SET minClientID = (SELECT MIN(ID) FROM tmp_customer_credit_summary);

	SET maxClientID = (SELECT MAX(ID) FROM tmp_customer_credit_summary);

	WHILE minClientID <= maxClientID and minClientID is not null DO 	

		SET customerNumber_				= (SELECT C.customerNumber FROM tmp_customer_credit_summary C WHERE ID = minClientID );



		SET maxDiasMora_				   = (SELECT MAX(C.dayDelay) FROM tmp_customer_credit C WHERE customerNumber = customerNumber_  and dateApply <= curdate() );

		SET cantidadFactura_			   = (SELECT COUNT(documentNumber) from (SELECT distinct C.documentNumber as documentNumber FROM tmp_customer_credit C WHERE customerNumber = customerNumber_   ) p );

		SET proximoPago_				   = (SELECT MIN(C.dateApply) FROM tmp_customer_credit C WHERE customerNumber = customerNumber_  and remaining > 0 );

			

		SET montoAtrazado_				= (SELECT SUM( IF (C.moneda = currencyIDNameSource ,(C.remaining) 													/ (exchangeRate_ + currencyTargetSale) 							, C.remaining ))   FROM tmp_customer_credit C WHERE customerNumber = customerNumber_  and dateApply <= curdate() );

		SET capitalPrestado_			   = (SELECT SUM( IF (C.moneda = currencyIDNameSource ,(C.capital) 														/ (exchangeRate_ + currencyTargetSale)								, C.capital))  FROM tmp_customer_credit C WHERE  customerNumber = customerNumber_  );

		SET capitalPagado_				= (SELECT SUM( IF (C.moneda = currencyIDNameSource ,((share - remaining) - interest) 							/ (exchangeRate_ + currencyTargetSale)								,((share - remaining) - interest)))  FROM tmp_customer_credit C  WHERE  customerNumber = customerNumber_ and dateApply <= curdate() );

		SET interesPagado_				= (SELECT SUM( IF (C.moneda = currencyIDNameSource ,((share - remaining) - capital) 							/ (exchangeRate_ + currencyTargetSale) 							,((share - remaining) - capital)))  FROM tmp_customer_credit C WHERE  customerNumber = customerNumber_   and dateApply <= curdate());

		SET montoProximoPago_			= (SELECT SUM( IF (C.moneda = currencyIDNameSource ,(C.remaining) 													/ (exchangeRate_ + currencyTargetSale)								, C.remaining))   FROM tmp_customer_credit C WHERE C.customerNumber = customerNumber_ and C.dateApply = proximoPago_ );  



		 

		UPDATE 	tmp_customer_credit_summary set tipoCambio = exchangeRate_,  maxDiasMora = IFNULL(maxDiasMora_,0),montoAtrazado = IFNULL(montoAtrazado_,0),cantidadFactura = IFNULL(cantidadFactura_,0),capitalPrestado = capitalPrestado + IFNULL(capitalPrestado_,0),	capitalPagado = capitalPagado + IFNULL(capitalPagado_,0),interesPagado = interesPagado + IFNULL(interesPagado_,0),proximoPago = proximoPago_,montoProximoPago = montoProximoPago_ where ID = minClientID;

		SET minClientID					= (SELECT MIN(ID) FROM tmp_customer_credit_summary  WHERE ID > minClientID);

 

	END WHILE; 





		SELECT 

		customerNumber ,legalName ,commercialName,firstName ,lastName ,

		limitCredit,

		balanceCredit,

		tipoCambio - currencyTargetPurchase  as tipoCambioCompra,

		tipoCambio + currencyTargetSale as tipoCambioVenta,

		'Dolares' as moneda,

		maxDiasMora,

		montoAtrazado,

		cantidadFactura,

		capitalPrestado,capitalPagado,interesPagado,proximoPago,montoProximoPago 

	FROM  

		tmp_customer_credit_summary;

	

		DROP TABLE tmp_customer_credit;

	DROP TABLE tmp_customer_credit_summary;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_customer_expensas
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_expensas`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_customer_expensas`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN

	select 

		ccc.customerCreditDocumentID,

		ccc.documentNumber,

		ccc.dateOn,

		round(ccc.amount,2) as amount,

		cur.simbol

	from 

		tb_customer_credit_document ccc

		inner join tb_customer cu on 

			ccc.entityID = cu.entityID 

		inner join tb_user us on 

			cu.entityID = us.employeeID 

		inner join tb_workflow_stage ws on 

			ccc.statusID = ws.workflowStageID

		inner join tb_currency cur on 

			ccc.currencyID = cur.currencyID 

	where

		ws.vinculable = 1 

		and us.userID = prUserID and 

		(	

			   	((ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		);

		

	select 

		ccc.customerCreditDocumentID,

		ccc.documentNumber,

		cca.dateApply,

		round(cca.share,2) as cuota,

		round(cca.remaining,2) as remaining ,

		cur.simbol

	from 

		tb_customer_credit_document ccc

		inner join tb_customer cu on 

			ccc.entityID = cu.entityID 

		inner join tb_user us on 

			cu.entityID = us.employeeID 

		inner join tb_workflow_stage ws on 

			ccc.statusID = ws.workflowStageID

		inner join tb_customer_credit_amoritization cca on 

			ccc.customerCreditDocumentID = cca.customerCreditDocumentID 

		inner join tb_currency cur on 

			ccc.currencyID = cur.currencyID 

	where

		ws.vinculable = 1 

		and cca.remaining <> 0

		and us.userID = prUserID and 

		(	

			   	((ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		)

	order by 

		cca.customerCreditDocumentID,cca.dateApply ; 

		

		

	select 

		ccc.customerCreditDocumentID,

		tm.transactionNumber,

		cast(tm.createdOn as date) as createdOn,

		round(tmd.amount,2)  as amount,

		cur.simbol

	from 

		tb_customer_credit_document ccc

		inner join tb_customer cu on 

			ccc.entityID = cu.entityID 

		inner join tb_user us on 

			cu.entityID = us.employeeID 

		inner join tb_workflow_stage ws on 

			ccc.statusID = ws.workflowStageID

		inner join tb_transaction_master_detail tmd on 

			tmd.componentItemID = ccc.customerCreditDocumentID  

		inner join tb_transaction_master tm on 

			tmd.transactionMasterID = tm.transactionMasterID 

		inner join tb_currency cur on 

			ccc.currencyID = cur.currencyID 

	where

		ws.vinculable = 1 and 

		tmd.transactionID in (23,24,25)

		and us.userID = prUserID and 

		(	

			   	((ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		)

	order by 

		ccc.documentNumber,tm.createdOn;

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_customer_list
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_list`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_customer_list`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'lista de clientes'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	DECLARE type VARCHAR(250);

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

		

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	SET type = (SELECT U.type FROM tb_company U WHERE U.companyID = prCompanyID);

	

	

	select 

		t.customerNumber,

		t.customerName,

		t.identification,

		t.phone,

		t.email,

		t.Moneda,

		ROUND(sum(IF(t.balanceTotal IS NULL,0, t.balanceTotal)),2) as balanceTotal,

		ROUND(sum(IF(t.balanceTotalCapital IS NULL,0, t.balanceTotalCapital)),2) as balanceTotalCapital,

		ROUND(sum(IF(t.balanceTotalInteres IS NULL,0, t.balanceTotalInteres)),2) as balanceTotalInteres

		

	from 

		(

		select 

			c.customerNumber,

			concat(n.firstName , ' ' , n.lastName ) as customerName,

			c.identification,

			COALESCE(ep.number,c.phoneNumber) as phone,

			em.email as email ,

			

			IFNULL(cur.`name`,'N/D') as Moneda,

			IFNULL(ccc.currencyID,0) as currencyID ,

			

			(

				select 

					sum(u.remaining) 

				from 

					tb_customer_credit_amoritization u 

				where 

					u.customerCreditDocumentID = ccc.customerCreditDocumentID  and 

					u.remaining > 0 

			) as balanceTotal,

			

			(

				select 

					sum(u.capital) 

				from 

					tb_customer_credit_amoritization u 

				where 

					u.customerCreditDocumentID = ccc.customerCreditDocumentID  and 

					u.remaining > 0 

			) as balanceTotalCapital,

			

			(

				select 

					sum(u.interest) 

				from 

					tb_customer_credit_amoritization u 

				where 

					u.customerCreditDocumentID = ccc.customerCreditDocumentID  and 

					u.remaining > 0 

			) as balanceTotalInteres

			

			

			 

		from 

			tb_customer c

			inner join tb_entity e on 	

				c.entityID = e.entityID and 

				c.companyID = e.companyID  

			inner join tb_naturales n on 

				e.entityID = n.entityID and 

				e.companyID = n.companyID 

			left join tb_entity_phone ep on 

				e.entityID = ep.entityID and 

				e.companyID = ep.companyID and 

				ep.isPrimary = true

			left join tb_entity_email em on 

				e.entityID = em.entityID and 

				e.companyID = em.companyID and 

				em.isPrimary = true  

			left join tb_customer_credit_document ccc on 

				c.entityID = ccc.entityID 

			left join tb_workflow_stage ws on 

				ccc.statusID = ws.workflowStageID 

			left join tb_currency cur on 

				cur.currencyID = ccc.currencyID

		where

			c.isActive = 1 and 

			e.companyID = prCompanyID and 

			(

				(

					ws.aplicable in (1) and 

					type != 'globalpro' 

				) or 

				(

					type = 'globalpro'

				)

			)

			and 			

			(	

			   	((ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

			)

			

			

		) t 

	group by 

		t.customerNumber,

		t.customerName,

		t.identification,

		t.phone,

		t.email,

		t.Moneda;

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_customer_pay
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_pay`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_customer_pay`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prCustomerNumber` VARCHAR(50), IN `prReference` VARCHAR(50))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Lista de Pagos del Cliente'
BEGIN

	select 	
	  ROW_NUMBER() OVER (PARTITION BY tmd.reference1 ORDER BY transactionNumber) AS contador, 
		tm.companyID,
		tm.transactionID,
		tm.transactionMasterID,
		tm.createdOn,
		tm.transactionNumber,
		concat(
			'<a href="{base_url}/app_box_share/edit/companyID/2/transactionID/23/transactionMasterID/',
			tm.transactionMasterID,
			'" target="_blank" >',
			tm.transactionNumber,
			'</a>'
		) as transactionNumberLink,
		usr.nickname as userName,
		tm.note,
		tmd.amount as Pago,
		tmd.reference1,
		tcc.amount as MontoDesembolso,
		tcc.balance as Balance,
		cur.name as MonedaDesembolso,
		case 
			when tmd.reference2 is null then 
				0 
			when tmd.reference2 = '' then 
				0
			else
				tmd.reference2
		end  SaldoAterior,
		case 
			when tmd.reference4 is null then 
				0 
			when tmd.reference4 = '' then 
				0
			else
				tmd.reference4
		end  SaldoNuevo
	from 
		tb_transaction t 
		inner join tb_transaction_master tm on 
			t.transactionID = tm.transactionID and 
			t.companyID = tm.companyID 
		inner join tb_transaction_master_detail tmd on 
			tm.companyID = tmd.companyID and 
			tm.transactionID = tmd.transactionID and 
			tm.transactionMasterID = tmd.transactionMasterID 
		inner join tb_workflow_stage ws on  
			tm.statusID = ws.workflowStageID 
		inner join tb_transaction_master tm2 on 
			tmd.reference1 = tm2.transactionNumber and 
			tmd.companyID = tm2.companyID 
		inner join tb_workflow_stage ws2 on 
			tm2.statusID = ws2.workflowStageID 
		inner join tb_customer_credit_document tcc on 
			tm2.transactionNumber = tcc.documentNumber and 
			tm.entityID = tcc.entityID 
		inner join tb_customer c on 
			tm.entityID = c.entityID 
		inner join tb_currency cur on 
			tm.currencyID = cur.currencyID 
		inner join tb_user usr on 
			usr.userID = tm.createdBy 
	where
		t.companyID = prCompanyID  and 
		t.isActive = 1 and 
		t.transactionID in (23 ,24 ,25 ) and 
		tm.isActive =  1 and 
		tmd.isActive = 1 and 
		ws.aplicable = 1 and 
		tm2.isActive = 1 and 
		ws2.vinculable = 1 and 
		c.customerNumber = prCustomerNumber and 
		(	
			   	((tcc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))
					or 
					(fn_get_provider_id (prCompanyID,prUserID) = 0 )
		) and 
		(
				( tcc.documentNumber  = prReference and prReference != '0') or
				( prReference = '0' ) 
		)
	order by 
		tmd.reference1 desc ,tm.transactionNumber desc  ;

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_customer_pay_by_invoice
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_pay_by_invoice`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_customer_pay_by_invoice`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prCustomerNumber` VARCHAR(50),IN prInvoiceNumber  VARCHAR(50))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Lista de Pagos del Cliente'
BEGIN

	DECLARE varMin DATETIME;

	DECLARE varMax DATETIME;



	

	select 

		cu.customerNumber,

		CONCAT(nat.firstName,' ' , nat.lastName ) as customerName,

		cu.phoneNumber ,

		cu.location,

		cu.identification,

		

		c.dateOn as fechaDesembolso,

		round(c.amount,2) as montoDesembolso,

		round(c.interes,2) as interesDesembolso,

		c.term as plazo,

		ws.NAME as statusDesembolso,

		ci.NAME as frecuenciaDesembolso 

	from 

		tb_customer_credit_document c 

		inner join tb_customer cu on 

			cu.entityID = c.entityID 

		inner join tb_naturales nat on 

			nat.entityID = cu.entityID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = c.statusID 

		inner join tb_catalog_item ci on 

			ci.catalogItemID = c.periodPay 

	where 

		c.documentNumber = prInvoiceNumber;

		

		

	

	

	DROP TEMPORARY TABLE IF EXISTS tbl_abonos_temp;

	CREATE TEMPORARY TABLE tbl_abonos_temp AS

	select 

	  ROW_NUMBER() OVER (PARTITION BY tmd.reference1 ORDER BY transactionNumber) AS contador, 		

		tm.createdOn,

		date(tm.createdOn) as createdOnDate, 

		tm.transactionNumber,

		usr.nickname as userName,

		tm.note,

		tmd.amount as Pago,

		tmd.reference1,

		tcc.amount as MontoDesembolso,

		tcc.balance as Balance,

		cur.simbol as MonedaDesembolso,

		case 

			when tmd.reference2 is null then 

				0 

			when tmd.reference2 = '' then 

				0

			else

				tmd.reference2

		end  SaldoAterior,

		case 

			when tmd.reference4 is null then 

				0 

			when tmd.reference4 = '' then 

				0

			else

				tmd.reference4

		end  SaldoNuevo

	from 

		tb_transaction t 

		inner join tb_transaction_master tm on 

			t.transactionID = tm.transactionID and 

			t.companyID = tm.companyID 

		inner join tb_transaction_master_detail tmd on 

			tm.companyID = tmd.companyID and 

			tm.transactionID = tmd.transactionID and 

			tm.transactionMasterID = tmd.transactionMasterID 

		inner join tb_workflow_stage ws on  

			tm.statusID = ws.workflowStageID 

		inner join tb_transaction_master tm2 on 

			tmd.reference1 = tm2.transactionNumber and 

			tmd.companyID = tm2.companyID 

		inner join tb_workflow_stage ws2 on 

			tm2.statusID = ws2.workflowStageID 

		inner join tb_customer_credit_document tcc on 

			tm2.transactionNumber = tcc.documentNumber and 

			tm.entityID = tcc.entityID 

		inner join tb_customer c on 

			tm.entityID = c.entityID 

		inner join tb_currency cur on 

			tm.currencyID = cur.currencyID 

		inner join tb_user usr on 

			usr.userID = tm.createdBy 

	where

		t.companyID = prCompanyID  and 

		t.isActive = 1 and 

		t.transactionID in (23 ,24 ,25 ) and 

		tm.isActive =  1 and 

		tmd.isActive = 1 and 

		ws.aplicable = 1 and 

		tm2.isActive = 1 and 

		ws2.vinculable = 1 and 

		tcc.documentNumber = prInvoiceNumber 

	order by 

		tmd.reference1 desc ,tm.transactionNumber desc  ;

		

	

	DROP TEMPORARY TABLE IF EXISTS tbl_fecha_temp;

	CREATE TEMPORARY TABLE tbl_fecha_temp AS

	select 

		amo.dateApply  

	from 

		tb_customer_credit_document c 

		inner join tb_customer_credit_amoritization amo on 

			amo.customerCreditDocumentID = c.customerCreditDocumentID 

	where 

		c.documentNumber = prInvoiceNumber and 

		c.isActive = 1 and 

		amo.isActive = 1  

	order by 

		amo.dateApply ;

		

	

	SET varMin = (select min(dateApply) from tbl_fecha_temp );

	SET varMax = (select max(dateApply) from tbl_fecha_temp );

	WHILE varMin <= varMax DO 

	

	  IF NOT EXISTS (SELECT * FROM tbl_abonos_temp k where k.createdOnDate = varMin) THEN 

			INSERT INTO tbl_abonos_temp(

				contador, 		

				createdOn,

				createdOnDate, 

				transactionNumber,

				userName,

				note,

				Pago,

				reference1,

				MontoDesembolso,

				Balance,

				MonedaDesembolso,

				SaldoAterior,

				SaldoNuevo

			) VALUES

			(

				1,

				varMin,

				varMin,

				'',

				'',

				'',

				0,

				'',

				0,

				0,

				'',

				0,

				0

			);

		END IF;

		

		

	  

		SET varMin = (select min(dateApply) from tbl_fecha_temp where dateApply > varMin );

	END WHILE;

	

	

	SELECT 

		contador, 		

		createdOn,

		createdOnDate, 

		transactionNumber,

		userName,

		note,

		Pago,

		reference1,

		MontoDesembolso,

		Balance,

		MonedaDesembolso,

		SaldoAterior,

		SaldoNuevo

	FROM 

		tbl_abonos_temp kk 

	ORDER BY 

		kk.createdOnDate ASC ;

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_customer_sr_list
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_sr_list`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_customer_sr_list`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'muestra la lista de consultas elaboradas a sin riesgo'
BEGIN

	select 

		c.requestID ,

		c.name as cliente,

		c.id as cedulaCliente,

		c.`file` as file_,

		c.createdOn,

		ux.nickname as Usuario,

		IF(c.isPay = 1,'Pagado','Pendiente') as Estado

	from 

		tb_customer_consultas_sin_riesgo c

		inner join tb_user ux on 

			ux.userID = c.userID 

	where

		c.companyID = prCompanyID and 

        c.createdOn between prStartOn and prEndOn 

	order by 

		c.createdOn asc ;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_customer_status
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_customer_status`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_customer_status`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prCustomerNumber` VARCHAR(50), IN prStatOn DATETIME, IN prEndOn DATETIME)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Estado de cuenta del cliente'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;		
	DECLARE currencyIDNameSource VARCHAR(250);
	DECLARE currencyIDNameTarget VARCHAR(250);	
	DECLARE currencyID_ INT DEFAULT 0;
	DECLARE currencyIDTarget_ INT DEFAULT 0;
	DECLARE deudaTotalCordobas DECIMAL(19,0) DEFAULT 0;
	DECLARE deudaTotalDolares DECIMAL(19,0) DEFAULT 0;
	DECLARE varCompanyType VARCHAR(50);

	

	CREATE TEMPORARY TABLE tmp_customer_credit (
		ID INT AUTO_INCREMENT PRIMARY KEY,customerCreditDocumentID INT, 
		customerNumber VARCHAR(50), identificationType VARCHAR(50),
		firstName VARCHAR(150),lastName VARCHAR(150),comercialName VARCHAR(150),
		legalName VARCHAR(150),address VARCHAR(500),identification VARCHAR(50),
		country VARCHAR(50),state VARCHAR(50),city VARCHAR(120),birth DATE,statusClient VARCHAR(60),
		limitCreditCordoba DECIMAL(19,9),balanceCordoba DECIMAL(19,9), 
		deudaCordobas DECIMAL(19,9), deudaDolares DECIMAL(19,9), incomeCordoba DECIMAL(19,9),
		lineName VARCHAR(120),lineNumber VARCHAR(50) ,limitCreditCordobaLinea DECIMAL(19,9),
		balanceCordobaLinea DECIMAL(19,9),interestYearLine DECIMAL(19,9),
		termLine DECIMAL(19,9),periodPayLine VARCHAR(50),statusLine VARCHAR(50),
		documentNumber VARCHAR(50),documentOn DATE,amountDocument DECIMAL(19,9),
		interesDocument DECIMAL(19,9),termDocument DECIMAL(19,9),periodPayDocument VARCHAR(150),
		statusDocument VARCHAR(50),dateApplyAmori DATE,balanceStartAmori DECIMAL(19,9),
		interestAmori DECIMAL(19,9),capitalAmori DECIMAL(19,9),shareAmori DECIMAL(19,9),
		balanceEndAmori DECIMAL(19,9),remainingAmori DECIMAL(19,9),dayDelayAmori INT,
		statusShare VARCHAR(50),moneda VARCHAR(150),balanceDocument DECIMAL(19,9), 
		nota VARCHAR(1500), phoneNumber VARCHAR(255)
	); 


	SET varCompanyType  = (select c.type from tb_company c where c.companyID = prCompanyID);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);
	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);
	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameSource,currencyIDNameTarget,exchangeRate_);	


	INSERT INTO tmp_customer_credit (
		customerCreditDocumentID,customerNumber , 
		identificationType ,firstName ,lastName ,comercialName ,
		legalName ,address ,identification ,country ,state ,city ,birth ,
		statusClient ,limitCreditCordoba ,balanceCordoba ,
		deudaCordobas,deudaDolares ,incomeCordoba ,lineName ,
		lineNumber  ,limitCreditCordobaLinea ,balanceCordobaLinea ,
		interestYearLine ,termLine ,periodPayLine ,statusLine ,
		documentNumber ,documentOn ,amountDocument,balanceDocument ,
		interesDocument ,termDocument,periodPayDocument ,statusDocument ,
		dateApplyAmori ,balanceStartAmori ,interestAmori ,capitalAmori ,
		shareAmori ,balanceEndAmori ,remainingAmori ,dayDelayAmori ,
		statusShare , moneda,nota,phoneNumber
	)
	SELECT 
		ccd.customerCreditDocumentID,
		c.customerNumber,
		ci1.name as identificationType,
		n.firstName,
		n.lastName,
		l.comercialName,
		l.legalName,
		c.address,
		c.identification,
		ci2.name as country,
		ci3.name as state,
		ci4.name as city,
		c.birthDate,
		ws1.name as statusClient,
		round((cc.limitCreditDol / exchangeRate_),2) as limitCreditCordoba,
		round((cc.balanceDol / exchangeRate_),2) as balanceCordoba,
		IF(ccd.currencyID = 1,ccda.remaining, 0) AS deudaCordobas,
		IF(ccd.currencyID = 2,ccda.remaining, 0) AS deudaDolares,
		round((cc.incomeDol / exchangeRate_),2) as incomeCordoba ,
		cl.name,
		ccl.accountNumber,
		IF(ccl.currencyID = currencyID_,ccl.limitCredit,round(ccl.limitCredit / exchangeRate_,2)  ) AS limitCreditCordobaLinea,
		IF(ccl.currencyID = currencyID_,ccl.balance,round(ccl.balance / exchangeRate_,2)  ) AS balanceCordobaLinea,
		ccl.interestYear,
		ccl.term,
		ci5.name as periodPayLine,
		ws2.name as statusLine,
		ccd.documentNumber,
		ccd.dateOn,
		ccd.amount,
		if(ccd.balance < 0 , 0 , ccd.balance) as balance,
		ccd.interes,
		ccd.term,
		ci6.name as periodPayDocument,
		case 
			when ws3.name = 'REGISTRADO' then 
				'PENDIENTE' 
			else 
				ws3.name 
		end as statusDocument,
		ccda.dateApply,
		ccda.balanceStart,
		ccda.interest,
		ccda.capital,
		ccda.`share`,
		ccda.balanceEnd,
		ccda.remaining,
		ccda.dayDelay,
		ws4.name as statusShare,
		curx.name ,
		ccd.reference1 as nota,
		c.phoneNumber 
	FROM
		tb_entity e 
		inner join tb_customer c on 
			e.entityID = c.entityID and 
			e.companyID = c.companyID 
		inner join tb_naturales n on 
			c.entityID = n.entityID and
			c.companyID = n.companyID 
		inner join tb_legal l on 
			n.entityID = l.entityID and 
			n.companyID = l.companyID 
		inner join tb_customer_credit cc on 
			e.entityID = cc.entityID and 
			e.companyID = cc.companyID 
		inner join tb_customer_credit_line ccl on 
			cc.entityID = ccl.entityID  and 
			cc.companyID = ccl.companyID 
		inner join tb_credit_line cl on 
			ccl.creditLineID = cl.creditLineID 		
		inner join tb_catalog_item ci1 on 
			c.identificationType = ci1.catalogItemID 
		inner join tb_catalog_item ci2 on 
			c.countryID = ci2.catalogItemID 
		inner join tb_catalog_item ci3 on 
			c.stateID = ci3.catalogItemID 
		inner join tb_catalog_item ci4 on 
			c.cityID = ci4.catalogItemID 
		inner join tb_workflow_stage ws1 on 
			c.statusID = ws1.workflowStageID 
		inner join tb_catalog_item ci5 on 
			ccl.periodPay = ci5.catalogItemID 
		inner join tb_workflow_stage ws2 on 
			ccl.statusID = ws2.workflowStageID 
		left join (
			select 
				xlm.customerCreditDocumentID,
				xlm.companyID,
				xlm.entityID,
				xlm.customerCreditLineID,
				xlm.documentNumber,
				xlm.dateOn,
				xlm.amount,
				xlm.interes,
				xlm.term,
				xlm.balance,
				xlm.balanceProvicioned,
				xlm.exchangeRate,
				xlm.currencyID,
				xlm.reference1,
				xlm.reference2,
				xlm.reference3,
				xlm.statusID,
				xlm.isActive,
				xlm.typeAmortization,
				xlm.periodPay,
				xlm.providerIDCredit,
				xlm.reportSinRiesgo
			from 
				tb_customer_credit_document  xlm
			where 
				xlm.isActive = 1 and 
				(
					(xlm.dateOn BETWEEN prStatOn and prEndOn and prStatOn <> prEndOn) or 
					(prStatOn = prEndOn)
				) 				
		)  as ccd on 
			ccd.entityID =  c.entityID  
		left join tb_customer_credit_amoritization ccda on 
			ccd.customerCreditDocumentID = ccda.customerCreditDocumentID  
		left join tb_catalog_item ci6 on 
			ccd.periodPay = ci6.catalogItemID 		
		left join tb_workflow_stage ws3 on 
			ccd.statusID = ws3.workflowStageID 
		left  join tb_workflow_stage ws4 on 
				ccda.statusID = ws4.workflowStageID 	
		left join tb_transaction_master tmx on 	
				ccd.documentNumber = tmx.transactionNumber and 
				ccd.companyID = tmx.companyID and 
				ccd.entityID = tmx.entityID 
		left join tb_currency curx on 
			tmx.currencyID = curx.currencyID 
	where
		e.companyID = prCompanyID and 
		c.customerNumber = prCustomerNumber and 
		ws3.`name` != 'ANULADO' ;
		
				
	IF varCompanyType = 'jorgeRamirez' THEN 
	
		UPDATE 
					tmp_customer_credit d, 
					(
						select 
							tm.transactionNumber,
							GROUP_CONCAT(i.`name`) as nombre 
						from
							tb_transaction_master tm 
							inner join tb_transaction_master_detail td on 
								tm.transactionMasterID = td.transactionMasterID 
							inner join tb_item i on 
								i.itemID = td.componentItemID 
						where 
							tm.isActive = 1 and 
							td.isActive = 1
						group by 
							tm.transactionNumber 
					) oo
		SET 
			d.nota = oo.nombre 
		WHERE 
			d.documentNumber = oo.transactionNumber; 
			
	END IF;
	

	CREATE TEMPORARY TABLE tmp_customer_credit_v2 select * from tmp_customer_credit;
	CREATE TEMPORARY TABLE tmp_customer_credit_v3 select * from tmp_customer_credit;
	CREATE TEMPORARY TABLE tmp_customer_credit_v4 select * from tmp_customer_credit;
	CREATE TEMPORARY TABLE tmp_customer_credit_v5 select * from tmp_customer_credit;
	CREATE TEMPORARY TABLE tmp_customer_credit_v6 select * from tmp_customer_credit;
	CREATE TEMPORARY TABLE tmp_customer_credit_v7 select * from tmp_customer_credit;

	

	SELECT SUM(deudaCordobas) INTO deudaTotalCordobas
    FROM tmp_customer_credit;
  

   SELECT SUM(deudaDolares) INTO deudaTotalDolares
    FROM tmp_customer_credit;

    
	

	SELECT 
		DISTINCT 
		'INFORMACION_DEL_CLIENTE',
		customerNumber , 
		identificationType ,
		firstName ,
		lastName ,
		comercialName ,
		legalName ,
		address ,
		identification ,country ,
		state ,city ,birth ,
		statusClient ,limitCreditCordoba ,
		balanceCordoba ,
		deudaTotalCordobas AS deudaCordobas, deudaTotalDolares AS deudaDolares,
		incomeCordoba,
		x.phoneNumber 
	FROM 
		tmp_customer_credit x;
	

	SELECT 
		DISTINCT 
		'INFORMACION_DE_LINEA_CREDITO',
		lineName ,lineNumber  ,
		limitCreditCordobaLinea ,
		balanceCordobaLinea ,
		interestYearLine ,termLine ,
		periodPayLine ,
		statusLine 
	FROM 
		tmp_customer_credit x;


	SELECT 
		DISTINCT 
		'INFORMACION_DOCUMENTO_CREDITO',	
		lineNumber,	
		documentNumber ,documentOn ,amountDocument ,
		interesDocument ,
		interesDocument / 120 as interesDocumentMultiploDe120,
		termDocument ,
		periodPayDocument,
		statusDocument,moneda,
		amountDocument - balanceDocument as amountShare,
		balanceDocument,
		IFNULL((
			select 
				datediff(current_date(),min(p.dateApplyAmori)) 
			from 			 
				tmp_customer_credit_v2 p
			where
				p.customerCreditDocumentID = x.customerCreditDocumentID and 
				p.remainingAmori <> 0 
		),0)    as dayAtrazo,
		IFNULL((
			select 
				sum(p.remainingAmori) 
			from 			 
				tmp_customer_credit_v3 p
			where
				p.customerCreditDocumentID = x.customerCreditDocumentID and 
				p.remainingAmori <> 0 and 
				p.dateApplyAmori <= current_date() 
		),0)    as amountAtrazo ,
		(
			select 
				sum(tn.interestAmori) 
			from 
				tmp_customer_credit_v4 tn
			where
				tn.customerCreditDocumentID = x.customerCreditDocumentID 
		) as interestTotalMontoDocument,
		(
			select 
				max(p.dateApplyAmori)
			from 			 
				tmp_customer_credit_v5 p
			where
				p.customerCreditDocumentID = x.customerCreditDocumentID  
		)    as vencimientoUltimaCuota,
		IFNULL((
			select 
				AVG(p.dayDelayAmori) 
			from 			 
				tmp_customer_credit_v6 p
			where
				p.customerCreditDocumentID = x.customerCreditDocumentID and 
				p.dayDelayAmori <> 0 
		),0)    as promedioDiaPago ,
		IFNULL((
			select 
				p.dayDelayAmori 
			from 			 
				tmp_customer_credit_v7 p
			where
				p.customerCreditDocumentID = x.customerCreditDocumentID 
			ORDER BY 
				p.dateApplyAmori 				
			LIMIT 1
		),0)    as atrasoCancelacionDia ,
		x.nota 			
	FROM 
		tmp_customer_credit x 
	order by 
		documentOn;

	

	SELECT 
		DISTINCT 
		'INFORMACION_TABLA_AMORITIZACION',
		documentNumber ,
		dateApplyAmori ,balanceStartAmori ,
		interestAmori ,capitalAmori ,
		shareAmori ,balanceEndAmori ,remainingAmori ,dayDelayAmori ,statusShare
	FROM 
		tmp_customer_credit x  ;

	DROP TABLE tmp_customer_credit; 
	DROP TABLE tmp_customer_credit_v2; 
	DROP TABLE tmp_customer_credit_v3; 
	DROP TABLE tmp_customer_credit_v4; 
	DROP TABLE tmp_customer_credit_v5;
	DROP TABLE tmp_customer_credit_v6;
	DROP TABLE tmp_customer_credit_v7;
	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_document_contract
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_document_contract`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_document_contract`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prDocumentNumber` VARCHAR(50))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para obtener el contrato de credito'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE nameLayEmployee VARCHAR(250);

	DECLARE nameLayEmployeeEstadoCivil VARCHAR(250);

	DECLARE nameLayEmployeeQuinquenio VARCHAR(500);



	CALL pr_core_get_parameter_value(prCompanyID,"CXC_LAY_EMPLOYER_NAME",nameLayEmployee);

	CALL pr_core_get_parameter_value(prCompanyID,"CXC_LAY_EMPLOYER_STATUS_PUBLIC",nameLayEmployeeEstadoCivil);

	CALL pr_core_get_parameter_value(prCompanyID,"CXC_LAY_EMPLOYER_QUINQUENIO",nameLayEmployeeQuinquenio);

				

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);	

		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameSource,currencyIDNameTarget,exchangeRate_);	





	select 

		tx.legalName,

		tx.identification,

		tx.address,

		min(tx.dateApply) as fechInicial,

		tx.curridate as fechActual,

		tx.currencyName,

		tx.sexo,

		tx.term,

		tx.period,

		tx.createdOn,

		tx.NombreProveedor,

		tx.CedulaProveedor,

		tx.DireccionProveedor,

		tx.estadoCivil,

		tx.profesion,

		tx.Domicilio,

		tx.typeFirmaCustomer,

		tx.typeFirmaProvider,	

		round(fn_calculate_exchange_rate(tx.companyID,tx.dateOn,1  ,2  ,1  ),2) as TipoCambio,

		round(sum(tx.share),2) as cuota,

		round(sum(tx.shareDolares),2) as cuotaDolares, 

		(round(sum(tx.shareDolares),2) / tx.term) as montoCuota,

		tx.amountTotal,

		tx.receiptAmount, 

		max(tx.dateApply) as fechFinal,

		DATEDIFF(max(tx.dateApply),min(tx.dateApply)) as DuracionDelCredito,

		nameLayEmployee,

		nameLayEmployeeEstadoCivil,

		nameLayEmployeeQuinquenio, 

		documentNumber,

		Concepto,

		phoneNumber,

		lugarTrabajo,

		birthDate,

		CantidadProductos,

		

		phoneNumberTransactionMaster,

		referenceClientIdentifier,

		referenceClientName, 

		Zona,

		

		IFNULL((

				select (xp.reference2) from tb_transaction_master_detail_credit xp 

				where xp.transactionMasterID = tx.transactionMasterID ),'*******'

		) as LayWritePublicNumber,

		IFNULL((

				select (xp.reference1) from tb_transaction_master_detail_credit xp 

				where xp.transactionMasterID = tx.transactionMasterID ),'*******'

		) as LayPasoAnteMi,

		IFNULL((

				select (xp.reference3) from tb_transaction_master_detail_credit xp 

				where xp.transactionMasterID = tx.transactionMasterID ),'*******'

		) as LayPrimerLineaProtocolo,

		IFNULL((

				select (xpz.itemNameLog) from tb_transaction_master_detail xpz 

				where xpz.transactionMasterID = tx.transactionMasterID ),'*******'

		) as productNameLog

	from 

		(

			SELECT 

				zon.`name` as Zona,

				tmx.numberPhone as phoneNumberTransactionMaster,

				tmi.referenceClientIdentifier,

				tmi.referenceClientName,

				cu.birthDate,

				cu.phoneNumber,

				cu.reference1 as lugarTrabajo,

				tmx.transactionMasterID,

				c.companyID,

				leg.legalName,

				cu.identification,

				leg.address,

				c.dateOn,

				c.term,

				tmx.amount as amountTotal,

				tmx.createdOn,

				now() as curridate,

				amori.share,

				amori.dateApply,

				cur.name as currencyName ,		

				ci2.name as sexo,

				ci1.name as period,

				estadoCivil.display as estadoCivil,

				profesion.display as profesion,

				IF(c.currencyID = currencyID_,amori.share * c.exchangeRate,amori.share / c.exchangeRate  ) AS shareDolares,

				CONCAT(natProv.firstName , ' ',natProv.lastName ) as NombreProveedor,

				pro.numberIdentification as CedulaProveedor,

				pro.address as DireccionProveedor ,

				cu.location as Domicilio,

				firma.display as typeFirmaCustomer,

				'Ilegible' as typeFirmaProvider,

				c.documentNumber ,

				tmi.receiptAmount,

				(select xl.note from tb_transaction_master xl where xl.transactionNumber = c.documentNumber limit 1) as Concepto,

				( select 

					sum(xmld.quantity)

					from 

					tb_transaction_master_detail xmld 

					where xmld.transactionMasterID = tmx.transactionMasterID and xmld.isActive = 1  

				) as CantidadProductos

			FROM 

				tb_customer_credit_document c 

				inner join tb_transaction_master tmx on 

					c.companyID = tmx.companyID and 

					c.documentNumber = tmx.transactionNumber and 

					c.entityID = tmx.entityID 

				inner join tb_provider pro on 

					c.providerIDCredit = pro.entityID 

				inner join tb_naturales natProv on 

					pro.entityID = natProv.entityID 

				inner join tb_currency cur on 

					tmx.currencyID = cur.currencyID 

				inner join tb_customer_credit_line l on 

					c.customerCreditLineID = l.customerCreditLineID 

				inner join tb_customer cu on 

					l.entityID = cu.entityID and 

					l.companyID = cu.companyID 

				inner join tb_naturales nat on 

					cu.entityID = nat.entityID and 

					cu.companyID = nat.companyID

				inner join tb_legal leg on 

					nat.entityID = leg.entityID  and 

					nat.companyID = leg.companyID 

				inner join tb_entity ent on 

					leg.entityID = ent.entityID and 

					leg.companyID = ent.companyID 

				inner join tb_customer_credit_amoritization amori on 

					c.customerCreditDocumentID = amori.customerCreditDocumentID 

				inner join tb_workflow_stage ws1 on 

					l.statusID = ws1.workflowStageID 

				inner join tb_workflow_stage ws2 on 

					c.statusID = ws2.workflowStageID 

				inner join tb_workflow_stage ws3 on 

					amori.statusID = ws3.workflowStageID 

				inner join tb_catalog_item ci1 on 

					l.periodPay = ci1.catalogItemID 

				inner join tb_catalog_item ci2 on 

					cu.sexoID = ci2.catalogItemID 

				inner join tb_catalog_item estadoCivil on 

					estadoCivil.catalogItemID = nat.statusID 

				inner join tb_catalog_item profesion on

				 	profesion.catalogItemID = nat.profesionID 

				inner join tb_catalog_item firma on 

					cu.typeFirm = firma.catalogItemID

				inner join tb_transaction_master_info tmi on 

							tmi.transactionMasterID = tmx.transactionMasterID 

				inner join tb_catalog_item zon on 

					zon.catalogItemID = tmi.zoneID 

			where

				c.companyID = prCompanyID and 

				c.documentNumber = prDocumentNumber and 

				(	

			   	((c.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

				)

		) tx 

	group by 

		tx.legalName,

		tx.identification,

		tx.address,

		tx.dateOn,

		tx.curridate,

		tx.currencyName,

		tx.sexo,

		tx.term,

		tx.period,

		tx.createdOn,

		tx.NombreProveedor,

		tx.CedulaProveedor,

		tx.DireccionProveedor,

		tx.estadoCivil,

		tx.profesion,

		tx.Domicilio,

		tx.typeFirmaCustomer,

		tx.typeFirmaProvider,

		tx.phoneNumber,

		tx.lugarTrabajo,

		tx.birthDate,

		tx.CantidadProductos,

		tx.phoneNumberTransactionMaster,

		tx.referenceClientIdentifier,

		tx.referenceClientName,

		tx.Zona; 

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_document_credit
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_document_credit`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_document_credit`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prDocumentNumber` VARCHAR(50))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	

	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

		CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameSource,currencyIDNameTarget,exchangeRate_);	





	SELECT 

		cu.customerNumber,

		leg.legalName,

		leg.comercialName,

		nat.firstName,

		nat.lastName,

		l.accountNumber,

		IF(l.currencyID = currencyID_,l.limitCredit,l.limitCredit / exchangeRate_  ) AS limitCreditCordoba,

		IF(l.currencyID = currencyID_,l.balance,l.balance / exchangeRate_  ) AS balanceCordoba,

		l.interestYear,

		ci1.name as periodPay,

		l.term,

		ws1.name as statusLine,

		c.documentNumber,

		c.dateOn,

		c.amount,

		c.interes,

		c.term,

		ws2.name as statusDocument,

		amori.dateApply,

		round(amori.balanceStart,2) as balanceStart,

		round(amori.interest,2) as interest,

		round(amori.capital,2) as capital,

		round(amori.`share`,2) as share,

		round(amori.balanceEnd,2) as balanceEnd,

		round(amori.remaining,2) as remaining,

		amori.dayDelay,

		ws3.name as statusShare,

		cur.name as currencyName ,

		(CASE WHEN c.reference1 = '' THEN 'NO DETERMINADA' ELSE c.reference1 END)  as note

	FROM 

		tb_customer_credit_document c 

		inner join tb_transaction_master tmx on 

			c.companyID = tmx.companyID and 

			c.documentNumber = tmx.transactionNumber and 

			c.entityID = tmx.entityID 

		inner join tb_currency cur on 

			tmx.currencyID = cur.currencyID 

		inner join tb_customer_credit_line l on 

			c.customerCreditLineID = l.customerCreditLineID 

		inner join tb_customer cu on 

			l.entityID = cu.entityID 

		inner join tb_naturales nat on 

			cu.entityID = nat.entityID 

		inner join tb_legal leg on 

			nat.entityID = leg.entityID 

		inner join tb_entity ent on 

			leg.entityID = ent.entityID 

		inner join tb_customer_credit_amoritization amori on 

			c.customerCreditDocumentID = amori.customerCreditDocumentID 

		inner join tb_workflow_stage ws1 on 

			l.statusID = ws1.workflowStageID 

		inner join tb_workflow_stage ws2 on 

			c.statusID = ws2.workflowStageID 

		inner join tb_workflow_stage ws3 on 

			amori.statusID = ws3.workflowStageID 

		inner join tb_catalog_item ci1 on 

			l.periodPay = ci1.catalogItemID 

	where

		c.companyID = prCompanyID and 

		c.documentNumber = prDocumentNumber and 

		(	

			   	(

						(c.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and 

						(fn_get_provider_id (prCompanyID,prUserID) != 0)

					)

					or 

					( fn_get_provider_id (prCompanyID,prUserID) = 0 )

		) 

	order by 

		amori.dateApply; 

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_exchange_rate
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_exchange_rate`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_exchange_rate`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN



	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE moneda_ VARCHAR(50); 

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;



	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		



	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	

	

	select

		r.date as Fecha,

		'Cordoba' as Cordoba,

		round(r.ratio - currencyTargetPurchase,2) as Compra,

		round(r.ratio + currencyTargetSale,2) as Venta,

		round(r.ratio,2) as Oficial,

		'Dolar' as Dolar

	from 

		tb_exchange_rate r

	where 

		r.date between date_sub(curdate(),interval 5 day) and date_add(curdate(), interval 1 month);

	

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_info_proyect
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_info_proyect`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_info_proyect`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

		CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameSource,currencyIDNameTarget,exchangeRate_);	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

	

	

	select 

		lm.customerNumber ,

		lm.legalName,

		lm.Dates as Fecha,

		date_format(lm.Dates,'%Y-%m') as FechaPeriodo,

		lm.capital,

		lm.interest,

		lm.cuota,

		lm.remaining ,

		'Dolares' as Moneda 

	from 

		(

			select 

				c.customerNumber,

				l.legalName,

				a.dateApply as Dates,

				SUM(IF(cur.currencyID = currencyIDTarget_,a.interest,a.interest / tmc.exchangeRate  )) as interest,

				SUM(IF(cur.currencyID = currencyIDTarget_,a.capital,a.capital / tmc.exchangeRate  )) as capital,

				SUM(IF(cur.currencyID = currencyIDTarget_,a.share,a.share / tmc.exchangeRate  )) as cuota,

				SUM(IF(cur.currencyID = currencyIDTarget_,a.remaining,a.remaining / tmc.exchangeRate  )) as remaining

			from 

				tb_customer_credit_document dcd 

				inner join tb_transaction_master tmc on  

					dcd.documentNumber = tmc.transactionNumber and 

					dcd.companyID = tmc.companyID and 

					dcd.entityID = tmc.entityID and 

					tmc.isActive = 1 and 

					dcd.isActive = 1 

				inner join tb_currency cur on 

					tmc.currencyID = cur.currencyID 

				inner join tb_customer_credit_amoritization a on 

					dcd.customerCreditDocumentID = a.customerCreditDocumentID 

				inner join tb_entity e on 

					dcd.entityID = e.entityID 

				inner join tb_customer c on 

					e.entityID = c.entityID 

				inner join tb_legal l on 

					c.entityID = l.entityID 

				inner join tb_workflow_stage ws on 

					dcd.statusID = ws.workflowStageID 

			where

					a.dateApply between (now() + interval -3 month ) and  (now() + interval 3 month )

					and dcd.isActive = 1 

					and ws.vinculable = 1 

					and dcd.companyID = prCompanyID  and 

					(	

			   		((dcd.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

						or 

						(fn_get_provider_id (prCompanyID,prUserID) = 0 )

					)

			group by 

				c.customerNumber,

				l.legalName,

				a.dateApply

		) lm ;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_interes_periodo
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_interes_periodo`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_interes_periodo`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'reporte para ver los intereses pagado y capital pagado en un intervalo de fecha'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyTargetSale DECIMAL(19,9) DEFAULT 0;

	DECLARE currencyTargetPurchase  DECIMAL(19,9) DEFAULT 0;

	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

		CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_);	

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_SALE",currencyTargetSale);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_EXCHANGE_PURCHASE",currencyTargetPurchase);

		  

		  

	select 

		cus.customerNumber,

		leg.legalName ,

		ccc.dateOn as documentFecha,

		ccc.documentNumber,

		tm.createdOn as transactionFecha,

		tm.transactionNumber,

		t.name as transactionName,

		ROUND(IF (ccc.currencyID = currencyID_ ,ccc.balance / exchangeRate_ ,ccc.balance),2) as balance, 	

		ROUND(IF (ccc.currencyID = currencyID_ ,tmdc.capital / exchangeRate_,tmdc.capital),2) as capital,		

		ROUND(IF (ccc.currencyID = currencyID_ ,tmdc.interest / exchangeRate_,tmdc.interest),2) as interest,

		exchangeRate_ as exchangeRate	,

		exchangeRate_ - currencyTargetSale as sale,

		exchangeRate_ + currencyTargetPurchase as purchase

	from 

		tb_transaction_master tm 

		inner join tb_transaction t on 

			tm.transactionID = t.transactionID 

		inner join tb_workflow_stage ws on 

			tm.statusID = ws.workflowStageID 

		inner join tb_customer cus on 

			tm.entityID = cus.entityID 

		inner join tb_legal leg on 

			cus.entityID = leg.entityID 

		inner join tb_transaction_master_detail tmd on 

			tm.transactionMasterID = tmd.transactionMasterID 

		inner join tb_customer_credit_document ccc on 

			tmd.componentItemID = ccc.customerCreditDocumentID 

		inner join tb_transaction_master_detail_credit tmdc on 

			tmd.transactionMasterDetailID = tmdc.transactionMasterDetailID 

	where

		tm.isActive = 1 and 

		ws.eliminable = 0 and 

		tm.transactionID in (23,24,25) and 

		cast(tm.createdOn as date) between prStartOn and prEndOn and 

		tm.companyID = prCompanyID and 

		(	

			   	((ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

		)

	order by 

		tm.createdOn DESC; 

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_invoice_by_customer
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_invoice_by_customer`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_invoice_by_customer`(IN `prCompanyID` INT, 

	IN `prTokenID` VARCHAR(50), 

	IN `prUserID` INT, 

	IN `prDateTimeStart` DATETIME,

	IN `prDateTimeFinish` DATETIME,

	IN `prCustomerEntityID` VARCHAR(50))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN

	

		select

			cu.customerNumber as Cliente  ,

			n.firstName as Nombre,

		  cu.identification as Identificacion 

		from 

			tb_naturales n 

			inner join tb_customer cu on 

				cu.entityID = n.entityID 

		where 

			cu.entityID = prCustomerEntityID; 

				

				

		select 

			tm.transactionOn as Fecha ,

			tm.transactionNumber as Factura,

			i.itemNumber as Codigo,

			i.`name` as Producto,

			td.quantity as Cantidad ,

			td.unitaryPrice  as Precio,

			td.amount as Monto 

			

		from 

			tb_transaction_master tm 

			inner join tb_transaction_master_detail td on 

				td.transactionMasterID = tm.transactionMasterID 

			inner join tb_item i on 

				i.itemID = td.componentItemID 

		where 

			tm.isActive = 1 and 

			tm.createdOn BETWEEN prDateTimeStart and prDateTimeFinish and 

			tm.entityID = prCustomerEntityID and 

			tm.transactionID = 19  ; 

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_movement_customer
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_movement_customer`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_movement_customer`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prCustomerNumber` VARCHAR(50))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Estado de cuenta del cliente'
BEGIN

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;		

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;		

	declare maxID int default 0;

	declare minID int default 0; 

	declare varBalance decimal(19,9);

	declare varEntityID int default 0;

	

	

	

	 

	CREATE TEMPORARY TABLE tmp_customer_credit (

		ID INT AUTO_INCREMENT PRIMARY KEY,

		entityID INT,

		transactionNumber varchar(50),

		transactionOn datetime,

		itemNumber varchar(50),

		itemName varchar(150),

		quantity decimal(19,9),

		unitaryPrice decimal(19,9),

		amount decimal(19,9),

		balance decimal(19,9),

		nota varchar(2500)

	); 

	

	CREATE TEMPORARY TABLE tmp_customer_credit_orden (

		ID INT AUTO_INCREMENT PRIMARY KEY,

		entityID INT,

		transactionNumber varchar(50),

		transactionOn datetime,

		itemNumber varchar(50),

		itemName varchar(150),

		quantity decimal(19,9),

		unitaryPrice decimal(19,9),

		amount decimal(19,9),

		balance decimal(19,9),

		nota varchar(2500)

	); 

 



	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameSource,currencyIDNameTarget,exchangeRate_);	

	SET varEntityID = (SELECT cu.entityID FROM tb_customer cu where cu.customerNumber = prCustomerNumber );	 

	

	

	INSERT INTO tmp_customer_credit (

	  entityID,

		transactionNumber ,

		transactionOn ,

		itemNumber ,

		itemName,

		quantity ,

		unitaryPrice ,

		amount ,

		balance,

		nota 

	)	

	select 

		 tm.entityID,

		 tm.transactionNumber,

		 tm.createdOn,

		 i.barCode,

		 i.`name` as nameProducto,

		 tmd.quantity ,

		 tmd.unitaryPrice,

		 tmd.amount,

		 0,

		 tm.note

	from 

		tb_transaction_master tm 

		inner join tb_transaction_master_detail tmd on 

			tm.transactionMasterID = tmd.transactionMasterID

		inner join tb_item i on 

			i.itemID = tmd.componentItemID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

	where

		tm.transactionID = 19  and 

		tm.isActive = 1 and 

		tmd.isActive = 1 and 

		tm.transactionCausalID in  (22,24)     and 

		ws.aplicable = 1 and 

		tm.entityID = varEntityID;

	

	

	

	INSERT INTO tmp_customer_credit (

		entityID,

		transactionNumber ,

		transactionOn ,

		itemNumber ,

		itemName,

		quantity ,

		unitaryPrice ,

		amount ,

		balance ,

		nota

	)		

	select 

		 tm.entityID,

		 tm.transactionNumber,

		 tm.createdOn,

		 '' as barCode,

		 'Prima' nameProducto,

		 0 as quantity ,

		 0 as unitaryPrice,

		 tmdi.receiptAmount * -1,

		 0 ,

		 tm.note

	from 

		tb_transaction_master tm 

		inner join tb_transaction_master_info tmdi on 

			tm.transactionMasterID = tmdi.transactionMasterID 	

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

	where

		tm.transactionID = 19  and 

		tm.isActive = 1 and 	

		tm.transactionCausalID in (22,24)   and 

		ws.aplicable = 1 and 

		tm.entityID = varEntityID;

	

	

	

	INSERT INTO tmp_customer_credit (

	  entityID,

		transactionNumber ,

		transactionOn ,

		itemNumber ,

		itemName,

		quantity ,

		unitaryPrice ,

		amount ,

		balance,

		nota 

	)		

	select 

		 tm.entityID,

		 tm.transactionNumber,

		 tm.createdOn,

		 '' as barCode,

		 'Abono' as nameProducto,

		 0 as quantity ,

		 0 as unitaryPrice,

		 tmc.capital * -1 ,

		 0 ,

		 tm.note

	from 

		tb_transaction_master tm 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_transaction_master_detail tmd on 

			tm.transactionMasterID = tmd.transactionMasterID 

		inner join tb_transaction_master_detail_credit tmc on 

			tmd.transactionMasterDetailID = tmc.transactionMasterDetailID 

	where 

		tm.isActive = 1 and 

		tm.transactionID = 23  and 

		ws.aplicable = 1 and 

		tm.entityID = varEntityID;

	

	insert into tmp_customer_credit_orden (

	  entityID,

		transactionNumber ,

		transactionOn ,

		itemNumber ,

		itemName,

		quantity ,

		unitaryPrice ,

		amount ,

		balance,

		nota 

	)

	select 

		entityID,

		transactionNumber ,

		transactionOn ,

		itemNumber ,

		itemName,

		quantity ,

		unitaryPrice ,

		amount ,

		balance,

		nota

	from 

		tmp_customer_credit t 

	order by 

		t.transactionOn asc ,t.ID asc ;

	

	

	

	set minID 			= (select min(i.ID) from tmp_customer_credit_orden i );

	set maxID 			= (select max(i.ID) from tmp_customer_credit_orden i );

	set varBalance 	= 0;

	

	while minID <= maxID and minID is not null  do 			

			set varBalance = varBalance + (select i.amount from tmp_customer_credit_orden i where ID = minID );			

			update tmp_customer_credit_orden set balance = varBalance where ID = minID;

			set minID   = (select min(i.ID) from tmp_customer_credit_orden i where ID > minID );

	end while;

	

	

	

	select 

		cust.customerNumber,

		nat.firstName,

		ID,		

		c.entityID,

		transactionNumber ,

		transactionOn ,

		itemNumber ,

		itemName,

		quantity ,

		unitaryPrice ,

		amount ,

		balance ,

		nota 

	from 

		tmp_customer_credit_orden c 

		inner join tb_naturales nat on 

			c.entityID = nat.entityID 

		inner join tb_customer cust on 

			cust.entityID = nat.entityID 

	order by 

		c.ID desc ;		

		

		

	drop table tmp_customer_credit;

	drop table tmp_customer_credit_orden;

 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_sales_customer
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_sales_customer`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_sales_customer`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, 
IN prCustomerNumber VARCHAR(250), IN prStartOn DATETIME, IN prEndOn DATETIME, IN prTransactionCausal VARCHAR(50))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de ventas'
BEGIN

	DECLARE varCurrencyCompras INT DEFAULT 0;	
	DECLARE varCurrencyReporte INT DEFAULT 0;	
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		
	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		


	-- Borrar la tabla temporal si existe
  DROP TEMPORARY TABLE IF EXISTS tmp_sales_report;

  -- Crear tabla temporal
  CREATE TEMPORARY TABLE tmp_sales_report (
      userID INT,
      nickname VARCHAR(250),
      transactionNumber VARCHAR(50),
      transactionCausalName VARCHAR(250),
      employerName VARCHAR(250),
      tipo VARCHAR(250),
      transactionOn DATETIME,
      createdOn DATETIME,
      dayOfMonth INT,
      customerNumber VARCHAR(250),
      currencyName VARCHAR(250),
      note VARCHAR(500),
      legalName VARCHAR(250),
      zone VARCHAR(250),
      itemNumber VARCHAR(250),
      itemName VARCHAR(250),
      itemNameLog VARCHAR(250),
      phoneNumber VARCHAR(50),
      Agent VARCHAR(250),
      Commentary VARCHAR(250),
      nameCategory VARCHAR(250),
      quantity DECIMAL(18,2),
      unitaryCost DECIMAL(18,2),
      unitaryPrice DECIMAL(18,2),
      cost DECIMAL(18,2),
      amount DECIMAL(18,2),
      amountConIva DECIMAL(18,2),
      utilidad DECIMAL(18,2),
      iva DECIMAL(18,2),
      ivaTotal DECIMAL(18,2),
      varCurrencyReporte INT,
      currencyID INT,
      exchangeRate DECIMAL(18,4),
      amountCommision DECIMAL(18,2)
  );
		
		
		
	INSERT INTO tmp_sales_report (
        userID,
        nickname,
        transactionNumber,
        transactionCausalName,
        employerName,
        tipo,
        transactionOn,
        createdOn,
        dayOfMonth,
        customerNumber,
        currencyName,
        note,
        legalName,
        zone,
        itemNumber,
        itemName,
        itemNameLog,
        phoneNumber,
        Agent,
        Commentary,
        nameCategory,
        quantity,
        unitaryCost,
        unitaryPrice,
        cost,
        amount,
        amountConIva,
        utilidad,
        iva,
        ivaTotal,
        varCurrencyReporte,
        currencyID,
        exchangeRate,
        amountCommision
  )
	select 
		rx.userID,
		rx.nickname,
		rx.transactionNumber,
		rx.transactionCausalName, 
		rx.employerName,
		rx.tipo,
		rx.transactionOn,
		rx.createdOn,
		DAYOFMONTH(rx.createdOn) as dayOfMonth,
		rx.customerNumber,
		rx.currencyName,
		rx.note,
		rx.legalName,
		rx.zone,
		rx.itemNumber,
		rx.itemName,
		rx.itemNameLog,
		rx.phoneNumber,
		rx.Agent,
		rx.Commentary,
		rx.nameCategory,
		rx.quantity,
		rx.unitaryCost,
		rx.unitaryPrice,
		(rx.unitaryCost * rx.quantity) as cost,
		(rx.unitaryPrice * rx.quantity) as amount,
		(rx.unitaryPrice * rx.quantity) + (rx.iva * rx.quantity)  as amountConIva,
		(rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity)    as utilidad,
		(rx.iva) as iva,
		(rx.quantity * rx.iva) as ivaTotal,
		varCurrencyReporte,
		rx.currencyID,
		rx.exchangeRate,
		rx.amountCommision 
	from 
		(
				select 
					usr.userID,
					usr.nickname,
					tm.transactionNumber,
					tc.`name` as transactionCausalName,
					IFNULL(CONCAT(nat_emp.firstName,' ',nat_emp.lastName),'') as employerName,
					tc.name as tipo,
					tm.transactionOn,
					cus.customerNumber,
					concat(nat_cus.firstName,' ', nat_cus.lastName) as legalName ,
					ci.name as zone,
					i.itemNumber,
					i.name as itemName,
					tmd.itemNameLog,
					cat.`name` as nameCategory,
					cus.phoneNumber,
					'' AS Agent,
					'' as Commentary,
					tmd.quantity as quantity,
					tm.currencyID,
					tm.exchangeRate,
					tm.createdOn,
					cur.`name` as currencyName,
					tm.note as note,
					case 
						when varCurrencyReporte = tm.currencyID then 
							tmd.unitaryPrice 
						when tm.exchangeRate > 1 then 
							tm.exchangeRate * (tmd.unitaryPrice)
						else 
							(1/tm.exchangeRate) * (tmd.unitaryPrice)
					end unitaryPrice,
					case 
						when varCurrencyReporte = tm.currencyID  then 				
							tmd.unitaryCost * ifnull(tmd.skuQuantity ,0) 
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  tmd.unitaryCost	* ifnull(tmd.skuQuantity ,0) 												
						else 								

						  (1/tm.exchangeRate) *   tmd.unitaryCost	* ifnull(tmd.skuQuantity ,0) 				
					end  unitaryCost,					
					case 
						when varCurrencyReporte = tm.currencyID  then 				
							IFNULL(tmd.tax1,0)
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  IFNULL(tmd.tax1,0)
						else 								
						  (1/tm.exchangeRate) *   IFNULL(tmd.tax1,0)
					end as iva ,
					case 
						when varCurrencyReporte = tm.currencyID  then 				
							IFNULL(amountCommision,0)
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  IFNULL(amountCommision,0)
						else 								
						  (1/tm.exchangeRate) *   IFNULL(amountCommision,0)
					end  as amountCommision 	
				from 
					tb_transaction_master tm  									
					inner join tb_transaction_master_detail tmd on 
						tm.companyID = tmd.companyID and 
						tm.transactionID = tmd.transactionID and 
						tm.transactionMasterID = tmd.transactionMasterID 
					inner join tb_currency cur on 
						cur.currencyID = tm.currencyID 
					inner join tb_transaction_causal tc on 
						tm.transactionCausalID = tc.transactionCausalID 
					inner join tb_customer cus on 
						tm.entityID = cus.entityID 
					inner join tb_legal l on 
						cus.entityID = l.entityID 
					inner join tb_naturales nat_cus on 
						nat_cus.entityID   = l.entityID 
					inner join tb_user usr on 
						tm.createdBy = usr.userID 
					inner join tb_workflow_stage ws on 
						tm.statusID = ws.workflowStageID 
					inner join tb_transaction_master_info tmi on 
						tm.companyID = tmi.companyID and 
						tm.transactionID = tmi.transactionID and 
						tm.transactionMasterID = tmi.transactionMasterID 
					inner join tb_catalog_item ci on 
						tmi.zoneID = ci.catalogItemID 
					inner join tb_item i on 
						tmd.componentItemID = i.itemID 
					inner join tb_item_category cat on 
						cat.inventoryCategoryID = i.inventoryCategoryID 
					left join tb_naturales nat_emp on 
						nat_emp.entityID = tm.entityIDSecondary 
				where  					
					tm.companyID = prCompanyID and 
					cus.customerNumber = prCustomerNumber and			
					tm.isActive = 1 and 
					tmd.isActive = 1 and 
					ws.aplicable = 1 and 
					tm.transactionID in (19 /*facturas*/ ) and 
					tm.statusID in (67 /*aplicada*/ ) and 
					tm.transactionOn BETWEEN prStartOn and prEndOn and 
					(
					  ( prTransactionCausal = 'Todas' ) or 
						( prTransactionCausal = 'Contado' and tm.transactionCausalID in (21, 23) ) or 
						( prTransactionCausal = 'Credito' and tm.transactionCausalID in (22, 24) ) 
					)
				order by 
					tm.transactionNumber desc, tmd.transactionMasterDetailID desc					
		) rx
		order by 
					rx.transactionNumber desc;
					
					
					
					
					
	-- Mostrar los resultados finales
  SELECT 
				userID,
        nickname,
        transactionNumber,
        transactionCausalName,
        employerName,
        tipo,
        transactionOn,
        createdOn,
        dayOfMonth,
        customerNumber,
        currencyName,
        note,
        legalName,
        zone,
        itemNumber,
        itemName,
        itemNameLog,
        phoneNumber,
        Agent,
        Commentary,
        nameCategory,
        quantity,
        unitaryCost,
        unitaryPrice,
        cost,
        amount,
        amountConIva,
        utilidad,
        iva,
        ivaTotal,
        varCurrencyReporte,
        currencyID,
        exchangeRate,
        amountCommision				
	FROM 
				tmp_sales_report ORDER BY transactionNumber DESC;
				
				
				
				
	-- Mostrar los resultados resumidos
  SELECT
        transactionNumber,
        transactionCausalName,
        employerName,
        transactionOn,
				SUM(amount) as amount          
	FROM 
				tmp_sales_report 
	GROUP BY 
				transactionNumber,
        transactionCausalName,
        employerName,
        transactionOn;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_summary_credit
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_summary_credit`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_summary_credit`(IN `prUserID` INT, IN `prTokenID` VARCHAR(250), IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN

	select 

		t.customerNumber as codigoCliente,

		t.firstName as cliente,

		t.amount as capitalInicial,

		t.balance as capitalActual,

		round(t.share_,2) as cuotaPromedio,

		t.interes as interesMensual,

		round(t.interes*12,2) as interesAnual,

		t.term as numeroCuotas,	

		round(((t.term * t.frecuenciaPagoDias) / 30 ),2) as numeroDeMeses,

		t.frecuenciaPagoDias as frecuenciaPagoEnDia,

		t.tipoAmortization as amortizacion,

		t.tipoPago as frecuenciaPago,

		t.name as moneda,	

		t.simbol as simbolo,

		t.lastDate as ultimaFecha,

		datediff(t.lastDate,curdate()) as diasParaCancelar,

		round(datediff(t.lastDate,curdate())/30,2) as mesParaCancelar ,

		round((datediff(t.lastDate,curdate())/30) / ((t.term * t.frecuenciaPagoDias) / 30 ),2) * 100 as 'mesParaCancelar%'  ,

		t.TipoCambio,

		t.Factura,

		round(t.Provisionado ,2) as Provisionado 

	from 

		(

		select 

			cu.customerNumber,

			nat.firstName,

			round(ccc.amount,2) as amount,

			round(ccc.balance,2) as balance,

			cur.name,

			cur.simbol,

			round(ccc.interes/12,2) as interes,

			ccc.term,

			ci.name as tipoAmortization,

			ci2.name as tipoPago,

			ci2.sequence as frecuenciaPagoDias,

			(select avg(cca.`share`) from tb_customer_credit_amoritization cca inner join tb_workflow_stage ws2 on cca.statusID = ws2.workflowStageID  where cca.customerCreditDocumentID = ccc.customerCreditDocumentID and cca.remaining <> 0 ) as share_,

			(select max(cca.dateApply) from tb_customer_credit_amoritization cca inner join tb_workflow_stage ws2 on cca.statusID = ws2.workflowStageID  where cca.customerCreditDocumentID = ccc.customerCreditDocumentID )  as lastDate,

			ccc.exchangeRate as TipoCambio,

			ccc.documentNumber as Factura,

			ccc.balanceProvicioned as Provisionado

		from 

			tb_customer_credit_document ccc 

			inner join tb_customer_credit_line ccl on 

				ccc.customerCreditLineID = ccl.customerCreditLineID 

			inner join tb_catalog_item ci on 

				ccl.typeAmortization = ci.catalogItemID 

			inner join tb_catalog_item ci2 on 

				ccl.periodPay = ci2.catalogItemID 

			inner join tb_customer cu on 

				ccc.entityID = cu.entityID 

			inner join tb_naturales nat on 

				cu.entityID = nat.entityID 

			inner join tb_currency cur on 

				ccc.currencyID = cur.currencyID 

			inner join tb_workflow_stage ws on 

				ccc.statusID = ws.workflowStageID  

		where

			ccc.isActive = 1 and  

			ws.vinculable = 1 and 

			(

					((ccc.providerIDCredit = fn_get_provider_id (prCompanyID,prUserID)) and (fn_get_provider_id (prCompanyID,prUserID) != 0))

					or 

					(fn_get_provider_id (prCompanyID,prUserID) = 0 )

			)

		) t 

	order by 

		t.lastDate asc; 

	

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_get_report_upload_buro
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_get_report_upload_buro`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_get_report_upload_buro`(IN `prUserID` INT, IN `prTokenID` VARCHAR(250), IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Reporte para reportal al buro de credito'
BEGIN

	DECLARE itemNotReportable VARCHAR(250);
  DECLARE varAbreviature VARCHAR(50);
	SET varAbreviature = IFNULL((SELECT c.abreviature FROM tb_company c where c.companyID = prCompanyID ),'');
	
	CREATE TEMPORARY TABLE tb_tmp_customer_buro (
			companyID INT,
			customerCreditDocumentID INT,			
			entityID INT,
			TIPO_DE_ENTIDAD VARCHAR(50),
			NUMERO_CORRELATIVO VARCHAR(50),
			FECHA_DE_REPORTE VARCHAR(50),
			DEPARTAMENTO VARCHAR(50),
			NUMERO_DE_CEDULA_O_RUC VARCHAR(50),
			NOMBRE_DE_PERSONA VARCHAR(250),
			TIPO_DE_CREDITO VARCHAR(250),
			FECHA_DE_DESEMBOLSO VARCHAR(50),
			TIPO_DE_OBLIGACION VARCHAR(250),
			MONTO_AUTORIZADO DECIMAL(19,2),
			PLAZO INT,

			FRECUENCIA_DE_PAGO VARCHAR(50),

			SALDO_DEUDA DECIMAL(19,2),

			ESTADO VARCHAR(50),

      MONTO_VENCIDO DECIMAL(19,2),

			ANTIGUEDAD_DE_MORA INT,

			TIPO_DE_GARANTIA VARCHAR(50),

			FORMA_DE_RECUPERACION VARCHAR(50),

			NUMERO_DE_CREDITO VARCHAR(50),

			VALOR_DE_LA_CUOTA DECIMAL(19,2)

	);

	

	   

        

   CALL pr_core_get_parameter_value(prCompanyID,"INVENTORY_ITEM_NOT_REPORT_TO_SINRIESGO",itemNotReportable);

   	

	

	INSERT INTO tb_tmp_customer_buro(

		companyID ,customerCreditDocumentID ,entityID ,TIPO_DE_ENTIDAD ,NUMERO_CORRELATIVO ,FECHA_DE_REPORTE ,DEPARTAMENTO ,

		NUMERO_DE_CEDULA_O_RUC ,NOMBRE_DE_PERSONA ,TIPO_DE_CREDITO ,FECHA_DE_DESEMBOLSO ,TIPO_DE_OBLIGACION ,

		MONTO_AUTORIZADO ,NUMERO_DE_CREDITO ,TIPO_DE_GARANTIA ,

		PLAZO ,

		FRECUENCIA_DE_PAGO ,

		SALDO_DEUDA ,

		ESTADO ,

		MONTO_VENCIDO ,

		ANTIGUEDAD_DE_MORA ,

		FORMA_DE_RECUPERACION ,

		VALOR_DE_LA_CUOTA )

	SELECT 

		    `cc`.`companyID` AS `companyID`,

        `cc`.`customerCreditDocumentID` AS `customerCreditDocumentID`,

        `cc`.`entityID` AS `entityID`,

        '99' AS `TIPO DE ENTIDAD`,  

        '552' AS `NUMERO CORRELATIVO`, 

        DATE_FORMAT(NOW(), '%d/%m/%Y') AS `FECHA DE REPORTE`,

        '08' AS `DEPARTAMENTO`, 

        REPLACE(`c`.`identification`, '-', '') AS `NUMERO DE CEDULA O RUC`,

        CONCAT(`nat`.`firstName`, ' ', `nat`.`lastName`) AS `NOMBRE DE PERSONA`,

        RIGHT(CONCAT('0000', `tipocredito`.`sequence`),2) AS `TIPO DE CREDITO`, 

        DATE_FORMAT(`cc`.`dateOn`, '%d/%m/%Y') AS `FECHA DE DESEMBOLSO`, 

        RIGHT(CONCAT('0000', `obli`.`sequence`),2) AS `TIPO DE OBLIGACION`,  

				

				

        ROUND(

					(FN_CALCULATE_EXCHANGE_RATE(2, CAST(NOW() AS DATE),`cc`.`currencyID`,1,`cc`.`amount`) * `p`.`ratioDesembolso`),2

			  ) AS `MONTO AUTORIZADO`,

				

				

        CONCAT(varAbreviature,`cc`.`documentNumber`) AS `NUMERO DE CREDITO`,

        RIGHT(CONCAT('0000', `tipogarantia`.`sequence`),2) AS `TIPO DE GARANTIA`,          

        

				

				

				CASE

						WHEN (`cc`.`periodPay` = 190) THEN ( `cc`.`term` ) /*mensual a meses */

						WHEN (`cc`.`periodPay` = 188) THEN ( round(`cc`.`term` / 4) ) /*semanal a meses */

						WHEN (`cc`.`periodPay` = 189) THEN ( round(`cc`.`term` / 2) ) /*quincenal a meses*/
						
						WHEN (`cc`.`periodPay` = 2322) THEN ( round(`cc`.`term` / 2) ) /*catorcenal a meses*/

						WHEN (`cc`.`periodPay` = 531) THEN ( round(`cc`.`term` / 30) ) /*diario a meses*/
						
						

						ELSE 0

				END as  `PLAZO`,

         

			

        (CASE

            WHEN (`cc`.`periodPay` = 190)  THEN '05'  

            WHEN (`cc`.`periodPay` = 188)  THEN '07'  

            WHEN (`cc`.`periodPay` = 189)  THEN '06'  

						WHEN (`cc`.`periodPay` = 531)  THEN '08'  

						WHEN (`cc`.`periodPay` = 2322) THEN '06'  

            ELSE 0

        END) AS `FRECUENCIA DE PAGO`,

        

        

        (CASE

            WHEN (`cc`.`statusID` = 82) THEN 0

            ELSE ROUND((FN_CALCULATE_EXCHANGE_RATE(2,

                            CAST(NOW() AS DATE),

                            `cc`.`currencyID`,

                            1,

                            `cc`.`balance`) * `p`.`ratioBalance`),

                    2)

        END) AS `SALDO DEUDA`,

        

		  

        (CASE

        		WHEN `estadosinriesgo`.`sequence` = 1  THEN   

				   CASE      			

		            WHEN

		                ((`ws`.`workflowStageID` NOT IN (93 , 92, 82))

		                    AND (CAST(NOW() AS DATE) > (SELECT 

		                        MAX(`xl`.`dateApply`)

		                    FROM

		                        `tb_customer_credit_amoritization` `xl`

		                    WHERE

		                        (`xl`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`))))

		            THEN

		                '02' 

		            WHEN

		                ((`ws`.`workflowStageID` NOT IN (93 , 92, 82)  )

		                    AND (CAST(NOW() AS DATE) > (SELECT 

		                        MIN(`xl`.`dateApply`)

		                    FROM

		                        `tb_customer_credit_amoritization` `xl`

		                    WHERE

		                        ((`xl`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)

		                            AND (`xl`.`remaining` > 0)))))  

		            THEN

		                '02' 

		            WHEN (`ws`.`workflowStageID` = 83) THEN 'N/D'

		            WHEN (`ws`.`workflowStageID` = 92) THEN '08' 

		            WHEN (`ws`.`workflowStageID` = 82) THEN '03' 

		            WHEN (`ws`.`workflowStageID` = 77) THEN '01' 

		            ELSE RIGHT(CONCAT('0000', `estadosinriesgo`.`sequence`),2)

	            END 

	         ELSE 

	         	RIGHT(CONCAT('0000', `estadosinriesgo`.`sequence`),2)

        END) AS `ESTADO`,

        

		  

        ROUND(((

		  				  SELECT 

                        IFNULL(ROUND((CASE

                                                WHEN (`cc`.`typeAmortization` = 196)   THEN

                                                   AVG(FN_CALCULATE_EXCHANGE_RATE(2,CAST(NOW() AS DATE),`cc`.`currencyID`,1,`cx`.`balanceStart`))

                                                ELSE 

																	SUM(FN_CALCULATE_EXCHANGE_RATE(2,CAST(NOW() AS DATE),`cc`.`currencyID`,1,`cx`.`capital`)) 

                                            END),

                                            2),

                                    0) 

                    FROM

                        `tb_customer_credit_amoritization` `cx`

                    WHERE

                        ((`cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)

                            AND (`cx`.`isActive` = 1)

                            AND (`cx`.`remaining` > 0)

                            AND (`cx`.`statusID` = 78)

                            AND (`cx`.`dateApply` < CAST(NOW() AS DATE)))

					) *  `p`.`ratioBalanceExpired` ),

         2) AS `MONTO VENCIDO`,

      	

			

        (SELECT 

                IFNULL((TO_DAYS(NOW()) - TO_DAYS(MIN(`cx`.`dateApply`))),

                            0)

            FROM

                `tb_customer_credit_amoritization` `cx`

            WHERE

                ((`cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)

                    AND (`cx`.`isActive` = 1)

                    AND (`cx`.`remaining` > 0)

                    AND (`cx`.`statusID` = 78)

                    AND (`cx`.`dateApply` < CAST(NOW() AS DATE)))

			) AS `ANTIGUEDAD DE MORA`,

			

			

      (CASE

        		WHEN `recuperacion`.`sequence` = 1 THEN

        			CASE 

		            WHEN (`ws`.`workflowStageID` = 83) THEN '01' 

		            WHEN (`ws`.`workflowStageID` = 92) THEN '08' 

		            WHEN (`ws`.`workflowStageID` = 82) THEN '01' 

		            WHEN

		                ((`ws`.`workflowStageID` = 77)

		                    AND ((SELECT 

		                        IFNULL((TO_DAYS(NOW()) - TO_DAYS(MIN(`cx`.`dateApply`))),

		                                    0)

		                    FROM

		                        `tb_customer_credit_amoritization` `cx`

		                    WHERE

		                        ((`cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)

		                            AND (`cx`.`isActive` = 1)

		                            AND (`cx`.`remaining` > 0)

		                            AND (`cx`.`statusID` = 78)

		                            AND (`cx`.`dateApply` < CAST(NOW() AS DATE)))) BETWEEN 30 AND 59))

		            THEN

		                '03' 

		            WHEN

		                ((`ws`.`workflowStageID` = 77)

		                    AND ((SELECT 

		                        IFNULL((TO_DAYS(NOW()) - TO_DAYS(MIN(`cx`.`dateApply`))),

		                                    0)

		                    FROM

		                        `tb_customer_credit_amoritization` `cx`

		                    WHERE

		                        ((`cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)

		                            AND (`cx`.`isActive` = 1)

		                            AND (`cx`.`remaining` > 0)

		                            AND (`cx`.`statusID` = 78)

		                            AND (`cx`.`dateApply` < CAST(NOW() AS DATE)))) > 60))

		            THEN

		                '04' 

		            WHEN (`ws`.`workflowStageID` = 77) THEN '01' 

		            ELSE RIGHT(CONCAT('0000', `recuperacion`.`sequence`),2)

		         END 

		      ELSE 

		      	RIGHT(CONCAT('0000', `recuperacion`.`sequence`),2)

        END) AS `FORMA DE RECUPERACION`,

			  0 AS `VALOR DE LA CUOTA`

    FROM

        ((((((((((((`tb_customer_credit_document` `cc`

        JOIN `tb_currency` `cur` ON ((`cc`.`currencyID` = `cur`.`currencyID`)))

        JOIN `tb_workflow_stage` `ws` ON ((`cc`.`statusID` = `ws`.`workflowStageID`)))

        JOIN `tb_catalog_item` `ci` ON ((`cc`.`typeAmortization` = `ci`.`catalogItemID`)))

        JOIN `tb_customer_credit_document_entity_related` `p` ON ((`cc`.`customerCreditDocumentID` = `p`.`customerCreditDocumentID`)))

        JOIN `tb_catalog_item` `obli` ON ((`obli`.`catalogItemID` = `p`.`type`)))

        JOIN `tb_catalog_item` `tipocredito` ON ((`tipocredito`.`catalogItemID` = `p`.`typeCredit`)))

        JOIN `tb_catalog_item` `tipogarantia` ON ((`tipogarantia`.`catalogItemID` = `p`.`typeGarantia`)))

        JOIN `tb_catalog_item` `frepago` ON ((`frepago`.`catalogItemID` = `cc`.`periodPay`)))

        JOIN `tb_catalog_item` `recuperacion` ON ((`recuperacion`.`catalogItemID` = `p`.`typeRecuperation`)))

        JOIN `tb_catalog_item` `estadosinriesgo` ON ((`estadosinriesgo`.`catalogItemID` = `p`.`statusCredit`)))

        JOIN `tb_naturales` `nat` ON ((`p`.`entityID` = `nat`.`entityID`)))

        JOIN `tb_customer` `c` ON ((`nat`.`entityID` = `c`.`entityID`)))

    WHERE

        (

			  	(  `cc`.`isActive` = 1)

			  	AND (cc.reportSinRiesgo = 1 ) 

	        AND (`cc`.`entityID` <> 309)

	        AND (

						REPLACE(`c`.`identification`, '-', '') NOT IN (

						'0000000000000B', 

						'0000000000000A',

						'0000000000000C', 

						'0000000000000P',

						'0000000000000K',

						'2811803890004R',

						'2912906610000G',

						'2911206850000P',

						'0000000000000T'

						)

					)

	         AND (`ws`.`workflowStageID` <> 83)

			) 

    ORDER BY 

	 		CONCAT(`nat`.`firstName`, ' ', `nat`.`lastName`) ;

	 		

	

	 /*cuota segun la frecuencia de pago, si la frecuencia de pago es semanal, la cuota el monto se debe ver reflejado semanal*/

	update tb_tmp_customer_buro set VALOR_DE_LA_CUOTA = 

			(

			CASE 

				WHEN  tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '07' /*SEMANAL*/ THEN 

						tb_tmp_customer_buro.MONTO_AUTORIZADO / (tb_tmp_customer_buro.PLAZO * 4) 

				WHEN  tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '05' /*MENSUAL*/ THEN 

						tb_tmp_customer_buro.MONTO_AUTORIZADO / (tb_tmp_customer_buro.PLAZO * 1) 

				WHEN  tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '06' /*QUINCENAL*/ THEN 

						tb_tmp_customer_buro.MONTO_AUTORIZADO / (tb_tmp_customer_buro.PLAZO * 2) 

				ELSE /*DIARIO*/

						tb_tmp_customer_buro.MONTO_AUTORIZADO / (tb_tmp_customer_buro.PLAZO * 30 ) 

			END

			);

			

	

	

   update tb_tmp_customer_buro,tb_catalog_item set 

		tb_tmp_customer_buro.ESTADO = 

		case 

			when

				( 

					case 

						WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '07' then  

							DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 7 * tb_tmp_customer_buro.PLAZO  DAY)  

						WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '06' then 

							DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 15 * tb_tmp_customer_buro.PLAZO  DAY)  

						WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '05' then 

							DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 30 * tb_tmp_customer_buro.PLAZO DAY)  

					end

				) < CURDATE() THEN 

					'02' 

			else

					'01' 

		end ,

		tb_tmp_customer_buro.ANTIGUEDAD_DE_MORA = 

		case 

			when

				( 

					case 

						WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '07' then  

							DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 7 * tb_tmp_customer_buro.PLAZO  DAY)  

						WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '06' then 

							DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 15 * tb_tmp_customer_buro.PLAZO  DAY)  

						WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '05' then 

							DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 30 * tb_tmp_customer_buro.PLAZO DAY)  

					end

				) < CURDATE() THEN 

					DATEDIFF(

						CURDATE(),

						case 

							WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '07' then  

								DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 7 * tb_tmp_customer_buro.PLAZO  DAY)  

							WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '06' then 

								DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 15 * tb_tmp_customer_buro.PLAZO  DAY)  

							WHEN tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = '05' then 

								DATE_ADD(STR_TO_DATE(tb_tmp_customer_buro.FECHA_DE_DESEMBOLSO,'%d/%m/%Y'), INTERVAL 30 * tb_tmp_customer_buro.PLAZO DAY)  

						end 

					)   

			else

					0

		end

   where

   	tb_tmp_customer_buro.FRECUENCIA_DE_PAGO = tb_catalog_item.sequence and 

   	tb_catalog_item.catalogID = 51 and 

   	tb_tmp_customer_buro.ESTADO = '01'  and 

		tb_tmp_customer_buro.FORMA_DE_RECUPERACION = '03'  and 

		tb_tmp_customer_buro.entityID = 0;

	

	 

	

	update tb_tmp_customer_buro set tb_tmp_customer_buro.FORMA_DE_RECUPERACION = '03'  

	where 

		tb_tmp_customer_buro.ESTADO = '02'  ; 	



	

	update tb_tmp_customer_buro set 

			tb_tmp_customer_buro.MONTO_VENCIDO = tb_tmp_customer_buro.SALDO_DEUDA 

	where

		tb_tmp_customer_buro.ESTADO = '02'  ; 	

		 

	

	update tb_tmp_customer_buro set tb_tmp_customer_buro.FORMA_DE_RECUPERACION = '01'  

	where 

		tb_tmp_customer_buro.ESTADO = '01'  and tb_tmp_customer_buro.ANTIGUEDAD_DE_MORA = 0;  	

					

	

   update tb_tmp_customer_buro set  ESTADO = '03' 

   where  

   	SALDO_DEUDA = 0;   	

		

	 

   select 

		`i`.`TIPO_DE_ENTIDAD`,

		`i`.`NUMERO_CORRELATIVO`, 

		`i`.`FECHA_DE_REPORTE`,

		`i`.`DEPARTAMENTO`,

		`i`.`NUMERO_DE_CEDULA_O_RUC`,

		`i`.`NOMBRE_DE_PERSONA`,

		`i`.`TIPO_DE_CREDITO`,

		`i`.`FECHA_DE_DESEMBOLSO`,

		`i`.`TIPO_DE_OBLIGACION`,

		`i`.`MONTO_AUTORIZADO`,

		`i`.`PLAZO`,

		`i`.`FRECUENCIA_DE_PAGO`,

		`i`.`SALDO_DEUDA`,

		`i`.`ESTADO`,

		`i`.`MONTO_VENCIDO`,

		`i`.`ANTIGUEDAD_DE_MORA`,

		`i`.`TIPO_DE_GARANTIA`,

		`i`.`FORMA_DE_RECUPERACION`,

		`i`.`NUMERO_DE_CREDITO`,

		`i`.`VALOR_DE_LA_CUOTA`	

	from 

		tb_tmp_customer_buro i ; 

   

   DROP TABLE tb_tmp_customer_buro;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_proc_expandinvoice
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_proc_expandinvoice`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_proc_expandinvoice`(IN `prCompanyID` INT, IN `prDocumentNumber` VARCHAR(50))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para aumentar de plazo los creditos que son tipo americano y estan proximo a vencer'
BEGIN

             

  	

	

	

	

  DECLARE varStatusRegistrado INT DEFAULT 78;

  DECLARE varStatusCancelado INT DEFAULT 81;

  DECLARE varLastDate DATE; 

  DECLARE varRefDate DATE;

  DECLARE varDateMin DATE;

  DECLARE varDateMax DATE;

  DECLARE varCustomerCreditDocumentID INT;

  

    SET varCustomerCreditDocumentID = (select c.customerCreditDocumentID from tb_customer_credit_document c where c.documentNumber =  prDocumentNumber  limit 1);

     



  

    SET varLastDate    		   = (select c.dateApply from tb_customer_credit_amoritization c where 

			c.customerCreditDocumentID = varCustomerCreditDocumentID and c.statusID = varStatusRegistrado order by c.dateApply desc limit 1);

			

    SET varRefDate 				= (select c.dateApply from tb_customer_credit_amoritization c where 

			c.customerCreditDocumentID = varCustomerCreditDocumentID and c.statusID = varStatusRegistrado and c.dateApply <  

			varLastDate order by c.dateApply desc limit 1);   

  



    CASE 

			WHEN varLastDate IS NOT NULL and varRefDate IS NOT NULL THEN 

						SET varDateMin 		= DATE_ADD(varRefDate, INTERVAL 1 MONTH);

						SET varDateMax 		= DATE_ADD(varRefDate, INTERVAL 12 MONTH);		  

		  

		  		  update tb_customer_credit_amoritization set 

						dateApply = varDateMax where customerCreditDocumentID = varCustomerCreditDocumentID and dateApply = varLastDate and statusID = varStatusRegistrado;

		  

		  		  WHILE varDateMin < varDateMax DO 

		  

		  	  		  	  CASE 

											WHEN NOT EXISTS (

												SELECT customerCreditDocumentID FROM tb_customer_credit_amoritization 

												WHERE customerCreditDocumentID = varCustomerCreditDocumentID and statusID = varStatusRegistrado and dateApply = varDateMin

											) 

											THEN 

		  	  

													INSERT INTO tb_customer_credit_amoritization (

															customerCreditDocumentID,dateApply,balanceStart,interest,capital,share,balanceEnd,

															remaining,shareCapital,dayDelay,note,statusID,isActive

														)

													SELECT 

														customerCreditDocumentID,varDateMin,balanceStart,interest,capital,share,

														balanceEnd,share,shareCapital, 0 ,'',varStatusRegistrado,1 

													FROM 

														tb_customer_credit_amoritization 

													WHERE

														customerCreditDocumentID = varCustomerCreditDocumentID 

														and statusID = varStatusRegistrado and dateApply = varRefDate limit 1;

		

										END CASE ; 

			  	  	    

		  	  

									SET varDateMin = DATE_ADD(varDateMin, INTERVAL 1 MONTH);

						END WHILE;

						SELECT 'EXITO!!!' as mensaje;

						

		ELSE

        SELECT CONCAT( 'NOT PROCESS!!! varCustomerCreditDocumentID: ' , cast(varCustomerCreditDocumentID as char )) as mensaje

				union		

        SELECT CONCAT( 'SELECT * from tb_customer_credit_amoritization c where c.customerCreditDocumentID = ',

				cast(varCustomerCreditDocumentID as char ),' order by c.dateApply ' ) as mensaje;  		

		END CASE;

  



  

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_proc_suprime_share
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_proc_suprime_share`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_proc_suprime_share`(IN `prCompanyID` INT, IN `prDocumentNumber` VARCHAR(50), IN `prCuotaModificada` DATETIME, IN `prAumentaMesDeGracia` BIT, IN `prCambioInteresDelMes` DECIMAL(19,5))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Anula una cuota'
BEGIN

	

	

	

	

	

	

	

   DECLARE varPeriodoPago INT DEFAULT 0;

   DECLARE varDocumentID INT DEFAULT 0;

   DECLARE varCuotaPrimera INT DEFAULT 0; 

   DECLARE varCuotaSegunda INT DEFAULT 0;

   DECLARE varStatusCancelado INT DEFAULT 81; 

   DECLARE varStatusRegistrado INT DEFAULT 78; 

   

	SET varDocumentID  = (select c.customerCreditDocumentID from tb_customer_credit_document c where c.companyID = prCompanyID and c.documentNumber = prDocumentNumber limit 1);

	SET varPeriodoPago = (select c.periodPay from tb_customer_credit_document c where c.companyID = prCompanyID and c.documentNumber = prDocumentNumber limit 1);

	



	

	IF prAumentaMesDeGracia = 1 THEN

	

		 

		insert into tb_customer_credit_amoritization (customerCreditDocumentID,dateApply,balanceStart,interest,capital,share,balanceEnd,remaining,shareCapital,dayDelay,note,statusID,isActive)

		select 

			customerCreditDocumentID,dateApply,

			balanceStart,0 as interest,0 as capital, 0 as share,balanceEnd,0 as remaining,0 as shareCapital,

			0 as dayDelay,'se realizo prorroga de fecha' as note, varStatusCancelado ,isActive 

		from 

			tb_customer_credit_amoritization c  

		where 

			c.customerCreditDocumentID = varDocumentID and dateApply = prCuotaModificada;

		

		

		set varCuotaPrimera = (select min(c.creditAmortizationID) from tb_customer_credit_amoritization c where c.customerCreditDocumentID = varDocumentID and dateApply = prCuotaModificada limit 1);

		set varCuotaSegunda = (select max(c.creditAmortizationID) from tb_customer_credit_amoritization c where c.customerCreditDocumentID = varDocumentID and dateApply = prCuotaModificada limit 1);

		

		

		update tb_customer_credit_amoritization set 

			dateApply = (

								case 

									when varPeriodoPago = 190 then 

								  			date_add(dateApply , interval 1 month) 

								  	when varPeriodoPago = 188 then 

											date_add(dateApply , interval 7 day) 

										else 

								  			dateApply 

								end

							)

		where 

			customerCreditDocumentID = varDocumentID and 

			dateApply >= prCuotaModificada and 

			creditAmortizationID <> varCuotaSegunda ;  

						

		select 'EXITO!!!' as mensaje;  

		

	END IF;

	 

	

	IF prCambioInteresDelMes <> 0 THEN 

	

		update tb_customer_credit_amoritization set 

			interest = prCambioInteresDelMes  , share = prCambioInteresDelMes , remaining = prCambioInteresDelMes ,

			note = 'se realizo cambio de interes',

			statusID = varStatusRegistrado 

		where

			customerCreditDocumentID = varDocumentID 

			and dateApply = prCuotaModificada;

			

			

	END IF;

	

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxc_pro_add_solidario_to_credit_sin_riesgo
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxc_pro_add_solidario_to_credit_sin_riesgo`;
delimiter ;;
CREATE PROCEDURE `pr_cxc_pro_add_solidario_to_credit_sin_riesgo`(IN `prCompanyID` INT, IN `prInvoiceNumber` VARCHAR(50), IN `prCustomerNumber` VARCHAR(50), IN `prRatioDesembolso` DECIMAL(10,4), IN `prRatioBalance` DECIMAL(10,4), IN `prRatioBalanceExpired` DECIMAL(10,4), IN `prRatioShare` DECIMAL(10,4), IN `prTipoCredito` INT, IN `prTipoObligacion` INT, IN `prFrecuenciaPago` INT, IN `prEstadoCredito` INT, IN `prRecuperacion` INT, IN `prGarantia` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
BEGIN

 

DECLARE VAR_customerCreditDocumentID INT DEFAULT 0;

DECLARE VAR_entiryIDSolidario INT DEFAULT 0;

DECLARE fiadorID INT DEFAULT 390;

DECLARE propietarioID INT DEFAULT 389;



SELECT c.customerCreditDocumentID INTO VAR_customerCreditDocumentID FROM tb_customer_credit_document c where c.documentNumber = prInvoiceNumber LIMIT 1;	

SELECT C.entityID INTO VAR_entiryIDSolidario FROM 	tb_customer C WHERE 	C.customerNumber = prCustomerNumber LIMIT 1;



CASE WHEN VAR_entiryIDSolidario != 0 THEN 

	CASE WHEN NOT EXISTS(

				SELECT * 

				FROM tb_customer_credit_document_entity_related c 

				where 

					c.customerCreditDocumentID = VAR_customerCreditDocumentID 

					and c.entityID = VAR_entiryIDSolidario 

	)  

	THEN 

				INSERT INTO 

				tb_customer_credit_document_entity_related(

							customerCreditDocumentID, entityID, `type`, typeCredit, 

							statusCredit, typeGarantia, typeRecuperation, 

							ratioDesembolso, ratioBalance, ratioBalanceExpired, 

							ratioShare, createdOn, createdBy, createdIn, createdAt, isActive 

					)

				SELECT 

						customerCreditDocumentID, VAR_entiryIDSolidario as entityID, 

						fiadorID as types, typeCredit, statusCredit, typeGarantia, 

						typeRecuperation, ratioDesembolso, ratioBalance, ratioBalanceExpired, 

						ratioShare, createdOn, createdBy, createdIn, createdAt, isActive 

				FROM 

						tb_customer_credit_document_entity_related c 

				where 

						c.customerCreditDocumentID = VAR_customerCreditDocumentID and type = propietarioID LIMIT 1;	

	

	END  CASE ;

END CASE  ;







UPDATE tb_customer_credit_document_entity_related set 

	typeCredit = prTipoCredito,

	statusCredit = prEstadoCredito, 	

	typeGarantia = prGarantia, 

	typeRecuperation = prRecuperacion,

	ratioDesembolso = (prRatioDesembolso ) , 

	ratioBalance = (prRatioBalance ) ,  

	ratioBalanceExpired = (prRatioBalanceExpired ) , 

	ratioShare = (prRatioShare )

where 

	customerCreditDocumentID = VAR_customerCreditDocumentID;





SELECT 

	`NOMBRE DE PERSONA`,    

    `TIPO DE OBLIGACION`, 

    `MONTO AUTORIZADO`, 

    PLAZO, 

    `FRECUENCIA DE PAGO`, 

    `SALDO DEUDA`, 

    ESTADO, 

    `MONTO VENCIDO`, 

    `ANTIGUEDAD DE MORA`,     

    `FORMA DE RECUPERACION`, 

    `VALOR DE LA CUOTA`,

    C.`TIPO DE GARANTIA` 

from 

	vw_sin_riesgo_reporte_creditos C 

WHERE 

	C.customerCreditDocumentID = VAR_customerCreditDocumentID order by C.`TIPO DE OBLIGACION`;    

	

select c.catalogItemID,c.catalogID,c.name as TipoCredito,c.sequence from tb_catalog_item c where c.catalogID = 50 order by c.sequence;

select c.catalogItemID,c.catalogID,c.name as TipoObligacion,c.sequence from tb_catalog_item c where c.catalogID = 49 order by c.sequence;

select c.catalogItemID,c.catalogID,c.name as FrecuenciaPago,c.sequence from tb_catalog_item c where c.catalogID = 51 order by c.sequence;

select c.catalogItemID,c.catalogID,c.name as EstadoCredito,c.sequence from tb_catalog_item c where c.catalogID = 52 order by c.sequence;

select c.catalogItemID,c.catalogID,c.name as FormaRecuperacion,c.sequence from tb_catalog_item c where c.catalogID = 54 order by c.sequence; 

select c.catalogItemID,c.catalogID,c.name as TipoGarantia,c.sequence from tb_catalog_item c where c.catalogID = 53 order by c.sequence;







END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxp_get_report_expenses_detail
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxp_get_report_expenses_detail`;
delimiter ;;
CREATE PROCEDURE `pr_cxp_get_report_expenses_detail`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE , IN prTipoExpense INT , IN prCategoryExpenses INT, IN prClassExpenses INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Obtener detalle de gastos '
BEGIN


	select 
		 tm.transactionID,
		 tm.transactionMasterID,
		 tm.companyID,
		 tm.transactionNumber,
		 tm.transactionOn as createdOn ,
		 tm.amount,
		 tm.tax1 as Iva,
		 tm.tax2 as Total,
		 tm.note,
		 tm.reference1,
		 tm.reference2 ,		 
		 tm.currencyID,
		 tm.currencyID2,
		 tm.exchangeRate,
		 tm.areaID,
		 tm.priorityID  ,
		 ifnull(clas.display,'ND') as Clasificacion,
		 ci.name as Tipo,
		 ci2.name as Categoria,
		 '' as CodigoReglon,		
		 br.`name` as sucursal ,
		 
		 
		 CASE 
			WHEN pronat.firstName IS NULL THEN 
				tm.reference3 
			ELSE 
				pronat.firstName 
		 END as Proveedor ,
		 
		 CASE 
				WHEN pro.numberIdentification  IS NULL THEN 
					tm.reference4 
				ELSE
					pro.numberIdentification 
			END as ruc	
		 
	from 
		tb_transaction_master tm 
		inner join tb_branch br on 	
			br.branchID = tm.branchID 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
		inner join tb_public_catalog_detail ci on 
			ci.publicCatalogDetailID = tm.priorityID 
		inner join tb_public_catalog_detail ci2 on 
			ci2.publicCatalogDetailID = tm.areaID 
		left JOIN tb_catalog_item clas on 
			tm.classID = clas.catalogItemID
		left JOIN tb_provider pro on 
			pro.entityID = tm.entityID 
		left JOIN tb_naturales pronat on 
			pronat.entityID = pro.entityID 
	where
		tm.transactionID = 38  and 
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.transactionOn between prStartOn and prEndOn and 
		(
			(
				(tm.areaID = prCategoryExpenses and prCategoryExpenses != 0) or 
				(prCategoryExpenses = 0)
			)  
			and 
			(
				(tm.priorityID = prTipoExpense and prTipoExpense != 0)  or 
				(prTipoExpense = 0 )
			)
			AND
			(
				(tm.classID=prClassExpenses and prClassExpenses !=0 ) OR
				(prClassExpenses=0)
			)
	  );


 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxp_get_report_expenses_summary
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxp_get_report_expenses_summary`;
delimiter ;;
CREATE PROCEDURE `pr_cxp_get_report_expenses_summary`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT,  IN `prStartOn` DATE, IN `prEndOn` DATE , IN prTipoExpense INT , IN prCategoryExpenses INT , IN prClassExpenses INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Obtener gastos resumidos por categoria '
BEGIN

	SET SESSION group_concat_max_len = 10000;

	

	SET @sqlT = NULL;

	SELECT

	GROUP_CONCAT(DISTINCT CONCAT(

		'

				SUM(

					CASE WHEN 

						concat(uz.transactionOn,"-01") = "', REPLACE(u.startOn,' 00:00:00',''), '" THEN 

							uz.amount 

					ELSE 

							0 

					END

				) 

				AS "',    DATE_FORMAT(STR_TO_DATE( REPLACE(u.startOn,' 00:00:00',''), '%Y-%m-%d'), '%d/%m/%Y')  ,'" ' )

	)

	INTO @sqlT

	FROM tb_accounting_cycle u 

	WHERE 

		u.startOn between concat(YEAR(prStartOn) ,"-", RIGHT(concat("00",MONTH(prStartOn)),2),"-01") and prEndOn;



	

		

	SET @sqlT = 

	CONCAT(

		'SELECT 

				Tipo, 

				', @sqlT, 

		'from 

				(

						select 

							 month(tm.transactionOn) as monthOnlyNumber,

							 concat(year(tm.transactionOn) ,"-", right(concat("00",month(tm.transactionOn)),2)   ) as transactionOn,

							 ci.`name` as Tipo,		 

							 tm.tax2	  as amount

						from 

							tb_transaction_master tm 

							inner join tb_workflow_stage ws on 

								ws.workflowStageID = tm.statusID 

							inner join tb_public_catalog_detail ci on 

								ci.publicCatalogDetailID = tm.priorityID 		

							left join  tb_catalog_item clas on 

								tm.classID = clas.catalogItemID		

						where

							tm.transactionID = 38  and 

							tm.isActive = 1 and 

							ws.aplicable = 1 and 

							tm.transactionOn between "',prStartOn,'" and "',prEndOn,'" and 

							(

								(

									(tm.areaID = ',prCategoryExpenses,' and ',prCategoryExpenses,' != 0) or 

									(',prCategoryExpenses,' = 0)

								)  

								and 

								(

									(tm.priorityID = ',prTipoExpense,' and ',prTipoExpense,' != 0)  or 

									(',prTipoExpense,' = 0 )

								)

								and 

								(

										(tm.classID =',prClassExpenses,' and ',prClassExpenses,' != 0 ) OR

										(',prClassExpenses, ' = 0)

								)

							)

				)  uz 

			group by  				

				uz.Tipo; 

	');

		

	

	PREPARE stmt FROM @sqlT;

	EXECUTE stmt;

	DEALLOCATE PREPARE stmt;

 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxp_get_report_expenses_summary_pivot
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxp_get_report_expenses_summary_pivot`;
delimiter ;;
CREATE PROCEDURE `pr_cxp_get_report_expenses_summary_pivot`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT,  IN `prStartOn` DATE, IN `prEndOn` DATE)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Obtener gastos pivot por fecha'
BEGIN

	

	

	 

	select 

		 tm.transactionID,

		 tm.transactionMasterID,

		 tm.companyID,

		 tm.transactionNumber,

		 tm.createdOn,

		 tm.amount,

		 tm.note,

		 tm.currencyID,

		 tm.currencyID2,

		 tm.exchangeRate,

		 tm.areaID,

		 tm.priorityID  ,

		 ci.`name` as Tipo,

		 ci2.`name` as Categoria 

	from 

		tb_transaction_master tm 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = tm.statusID 

		inner join tb_catalog_item ci on 

			ci.catalogItemID = tm.priorityID 

		inner join tb_catalog_item ci2 on 

			ci2.catalogItemID = tm.areaID 

	where

		tm.transactionID = 38  and 

		tm.isActive = 1 and 

		ws.aplicable = 1 and 

		tm.createdOn between prStartOn and prEndOn;

 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_cxp_get_report_purchase_detail
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_cxp_get_report_purchase_detail`;
delimiter ;;
CREATE PROCEDURE `pr_cxp_get_report_purchase_detail`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE,IN prInventoryCategoryID INT, IN prWarehouse INT,

 IN prEntityIDProvider INT,

 IN prItmeID INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de Compra'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

	

	select 

		rx.userID,

		rx.nickname,

		rx.transactionNumber,

		rx.transactionOn,

		rx.createdOn,

		rx.providerNumber,

		rx.currencyName,

		rx.note,

		rx.legalName,

		rx.itemNumber,

		rx.itemName,

		rx.Agent,

		rx.Commentary,

		rx.nameCategory,

		rx.quantity,		

		rx.unitaryCost,

		rx.unitaryPrice,

		(rx.unitaryCost * rx.quantity) as cost,

		(rx.unitaryPrice * rx.quantity) as amount,

		varCurrencyReporte,

		rx.currencyID,

		rx.exchangeRate,

		rx.expirationDate 		

	from 

		(

				select 

					usr.userID,

					usr.nickname,

					tm.transactionNumber,

					tm.transactionOn,

					cus.providerNumber ,

					concat(nat_cus.firstName,' ', nat_cus.lastName) as legalName ,

					i.itemNumber,

					i.name as itemName,

					cat.`name` as nameCategory,

					'' AS Agent,

					'' as Commentary,

					tmd.quantity,

					tm.currencyID,

					tm.exchangeRate,

					tm.createdOn,

					tmd.expirationDate,

					cur.`name` as currencyName,

					tm.note as note,

					case 

						when varCurrencyReporte = tm.currencyID then 

							tmd.unitaryPrice 

						when tm.exchangeRate > 1 then 

							tm.exchangeRate * (tmd.unitaryPrice)

						else 

							(1/tm.exchangeRate) * (tmd.unitaryPrice)

					end unitaryPrice,

					case 

						when varCurrencyReporte = tm.currencyID  then 				

							tmd.unitaryCost

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  tmd.unitaryCost														

						else 								

						  (1/tm.exchangeRate) *   tmd.unitaryCost							

					end  unitaryCost 

				from 

					tb_transaction_master tm  					

					inner join tb_transaction_master_detail tmd on 

						tm.companyID = tmd.companyID and 

						tm.transactionID = tmd.transactionID and 

						tm.transactionMasterID = tmd.transactionMasterID 

					inner join tb_currency cur on 

						cur.currencyID = tm.currencyID 

					inner join tb_transaction_causal tc on 

						tm.transactionCausalID = tc.transactionCausalID 

					inner join tb_provider cus on 

						tm.entityID = cus.entityID 

					inner join tb_legal l on 

						cus.entityID = l.entityID 

					inner join tb_naturales nat_cus on 

						nat_cus.entityID   = l.entityID 

					inner join tb_user usr on 

						tm.createdBy = usr.userID 

					inner join tb_workflow_stage ws on 

						tm.statusID = ws.workflowStageID 

					inner join tb_item i on 

						tmd.componentItemID = i.itemID 

					inner join tb_item_category cat on 

						cat.inventoryCategoryID = i.inventoryCategoryID 

				where  					

					tm.companyID = prCompanyID and 

					tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 

					tm.isActive = 1 and 

					tmd.isActive = 1 and 					

					ws.aplicable = 1 and 

					(

						(tm.entityID = prEntityIDProvider AND prEntityIDProvider != 0)  

						OR

						(prEntityIDProvider = 0)

					)

					and 

					(

						(tmd.componentItemID = prItmeID AND prItmeID != 0)

						or 

						(prItmeID = 0 )

					)

					and 

					(

						prInventoryCategoryID = 0 

						or 

						(prInventoryCategoryID != 0 and i.inventoryCategoryID = prInventoryCategoryID )

					) and 

					(

						prWarehouse = 0 

						or 

						(

							prWarehouse != 0 and 

							tm.sourceWarehouseID =  prWarehouse 

					  )

					)

				order by 

					tm.transactionMasterID asc, tmd.transactionMasterDetailID asc

					

		) rx;

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_calculate_kardex_new_input
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_calculate_kardex_new_input`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_calculate_kardex_new_input`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento que se utilizara para calcular el costo de los Item cuando se registra una nueva entrada de mercaderia'
BEGIN

	declare varBranchID int;
	declare varSign int;
	declare varTransactionOn datetime;
	declare varWarehouseID INT; 
	DECLARE varTiposCosto VARCHAR(50);
		

	CALL pr_core_get_parameter_value(prCompanyID,'INVENTORY_TYPE_COST',varTiposCosto);
	select tm.createdAt,sign,transactionOn,tm.targetWarehouseID into varBranchID,varSign,varTransactionOn,varWarehouseID 
	from 
		tb_transaction_master tm 
	where 
		tm.companyID = prCompanyID and tm.transactionID = prTransactionID and 
		tm.transactionMasterID = prTransactionMasterID limit 1;		

	insert into tb_item_warehouse (companyID,branchID,itemID,warehouseID,quantity,cost,quantityMax,quantityMin)
	select 
		k.companyID,k.branchID,k.itemID,k.warehouseID,0,0,0,0
	from
		tb_kardex k 
	where
		k.itemID not in (
				select 
					l.itemID 
				from 
					tb_item_warehouse l 
				where  
					l.companyID = prCompanyID and 
					l.warehouseID = varWarehouseID  
		) and 
		k.companyID = prCompanyID and 
		k.transactionID = prTransactionID and 
		k.transactionMasterID = prTransactionMasterID;	

		

	insert into tb_kardex (
		companyID,branchID,transactionID,transactionMasterID,transactionDetailID,warehouseID,
		itemID,kardexCode,kardexDate,sign,movementOn,
		transactionQuantity,
		transactionCost,
	  quantityInWarehouseCurrent,quantityInCurrent 
	)
	select 
		tm.companyID,varBranchID,tm.transactionID,tm.transactionMasterID,tm.transactionMasterDetailID,
		tm.inventoryWarehouseTargetID,tm.componentItemID,0,current_timestamp(),varSign,
		varTransactionOn,		
		IF(tm.transactionID = 20  ,tm.skuQuantityBySku,tm.quantity) as tm_quantity, 
		tm.unitaryCost,
		ifnull(iw.quantity,0),i.quantity  
	from 
		tb_transaction_master_detail tm 
		inner join tb_item i on 
				tm.componentItemID = i.itemID 		
		left join tb_item_warehouse iw on 
			  iw.itemID = i.itemID and 
				iw.warehouseID = tm.inventoryWarehouseTargetID  
	where 
		tm.companyID = prCompanyID and 
		tm.transactionID = prTransactionID and tm.transactionMasterID = prTransactionMasterID and 
		tm.isActive = 1;


	update tb_kardex,tb_item  set 
		tb_kardex.oldQuantity = IFNULL(tb_item.quantity,0), 
		tb_kardex.oldCost = IFNULL(tb_item.cost,0)
	where
		tb_item.companyID = tb_kardex.companyID and 
		tb_item.itemID = tb_kardex.itemID and 		
		tb_kardex.companyID = prCompanyID and 
		tb_kardex.transactionID = prTransactionID and 
		tb_kardex.transactionMasterID = prTransactionMasterID ;

	update tb_item_warehouse , tb_kardex set 
		  tb_kardex.oldQuantityWarehouse = IFNULL(tb_item_warehouse.quantity,0),
      tb_kardex.oldCostWarehouse = 0 
	where 
		tb_item_warehouse.companyID = tb_kardex.companyID and 
		tb_item_warehouse.itemID = tb_kardex.itemID and 
		tb_item_warehouse.warehouseID = tb_kardex.warehouseID and 
		tb_kardex.companyID = prCompanyID and 
		tb_kardex.transactionID = prTransactionID and 
		tb_kardex.transactionMasterID = prTransactionMasterID ; 

	

	update tb_kardex set 
		newQuantity           = ((sign * IFNULL(transactionQuantity,0)) + IFNULL(oldQuantity,0)) ,
		newQuantityWarehouse  = ((sign * IFNULL(transactionQuantity,0)) + IFNULL(oldQuantityWarehouse,0)) 
	where 
		companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID ;
	

	

	IF varTiposCosto = 'ULTIMO COSTO' THEN  
			update tb_kardex set 
				newCost               = IFNULL(transactionCost,0), 
				newCostWarehouse      = 0
			where 
				companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID ;
	END IF;

	

	

	IF varTiposCosto = 'PROMEDIO COSTO' THEN 
			update tb_kardex set 
				newCost               = 
					(
						(IFNULL(transactionQuantity,0) * IFNULL(transactionCost,0)) + 
						(IFNULL(oldQuantity,0) * IFNULL(oldCost,0))
					) / IFNULL(newQuantity,0) ,
				newCostWarehouse      = 0
			where 
				companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID ;
	END IF;

	

	IF varTiposCosto = 'PONDERADO COSTO' THEN 
			update tb_kardex set 
				newCost               = 				
				  (((transactionQuantity) / newQuantity)  * transactionCost )					
					+					
					(((oldQuantity) / newQuantity) * oldCost ) , 
				newCostWarehouse      = 0
			where 
				companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID ;
	END IF;
	

	update tb_item , tb_kardex set 
		tb_item.quantity = tb_kardex.newQuantity,
		tb_item.cost = tb_kardex.newCost,
		tb_item.dateLastUse = NOW()
	where
		tb_item.companyID = tb_kardex.companyID and 
		tb_item.itemID = tb_kardex.itemID and 
		tb_kardex.companyID = prCompanyID and 
		tb_kardex.transactionID = prTransactionID and 
		tb_kardex.transactionMasterID = prTransactionMasterID ;


	update tb_item_warehouse , tb_kardex set 
		tb_item_warehouse.quantity = tb_kardex.newQuantityWarehouse,
		tb_item_warehouse.cost = tb_kardex.newCostWarehouse
	where
		tb_item_warehouse.companyID = tb_kardex.companyID and 
		tb_item_warehouse.itemID = tb_kardex.itemID and 
		tb_item_warehouse.warehouseID = tb_kardex.warehouseID and 
		tb_kardex.companyID = prCompanyID and 
		tb_kardex.transactionID = prTransactionID and 
		tb_kardex.transactionMasterID = prTransactionMasterID ;	

	update 
		tb_transaction_master set isApplied = 1 	
	where 
		companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID; 
	

	INSERT INTO tb_item_warehouse_expired (warehouseID,itemID,companyID,quantity,lote,dateExpired)
	SELECT 
			c.inventoryWarehouseTargetID ,
			c.componentItemID,
			c.companyID,		
			0 as quantity, 
			REPLACE( UPPER(c.lote),' ','') as lote,
			IFNULL(c.expirationDate,'1900-01-01 00:00:00') as expirationDate 
	FROM 
			tb_transaction_master_detail c 
	WHERE	
			c.transactionMasterID = prTransactionMasterID  and 
			c.transactionID = prTransactionID and 		
			c.companyID = prCompanyID and 
			c.componentID = 33 and  
			c.isActive = 1 and 
			c.componentItemID not in (
				select 
					u.itemID 
				from 
					tb_item_warehouse_expired u 
				where 
					u.companyID = c.companyID and 
					u.itemID = c.componentItemID and 
					u.warehouseID = c.inventoryWarehouseTargetID and 			
					u.dateExpired = IFNULL(c.expirationDate,'1900-01-01 00:00:00') 
			);
		

		update tb_item_warehouse_expired , tb_transaction_master_detail set 
			tb_item_warehouse_expired.quantity = 
			tb_item_warehouse_expired.quantity + (varSign * tb_transaction_master_detail.quantity) 
		where
			tb_item_warehouse_expired.itemID = tb_transaction_master_detail.componentItemID and 
			tb_item_warehouse_expired.companyID = tb_transaction_master_detail.companyID and 
			tb_item_warehouse_expired.warehouseID = tb_transaction_master_detail.inventoryWarehouseTargetID and 		
			tb_item_warehouse_expired.dateExpired = IFNULL(
							tb_transaction_master_detail.expirationDate,
							'1900-01-01 00:00:00'
			)  and 
			tb_transaction_master_detail.companyID = prCompanyID and 
			tb_transaction_master_detail.transactionID = prTransactionID and 
			tb_transaction_master_detail.transactionMasterID = prTransactionMasterID;

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_calculate_kardex_new_output
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_calculate_kardex_new_output`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_calculate_kardex_new_output`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` BIGINT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento que se utiliza para restar el inventario al momento de registrar una salida de inventario'
BEGIN
	 declare varBranchID int;
	 declare varSign int;
	 declare varTransactionOn datetime;
	 declare varWarehouseID int;
	 DECLARE varTiposCosto VARCHAR(50);
	 declare varTransactionIDFactura int default 19;
	 declare varWhileItemMin int default 0;
	 declare varWhileItemMax int default 0;
	 declare varWhileItemQuantity decimal(19,5) DEFAULT 0;
	 
	 declare varWhileFechaVencimientoMin date default 0;
	 declare varWhileFechaVencimientoMax date default 0;
	 declare varWhileFechaVencimientoQuantity decimal(19,5) DEFAULT 0;
	 declare varWhileFechaVencimientoContinue int default 1;
	  
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		BEGIN
				ROLLBACK;
				RESIGNAL;
		END;

		
    START TRANSACTION;
		CALL pr_core_get_parameter_value(prCompanyID,'INVENTORY_TYPE_COST',varTiposCosto);
		
		
		select tm.createdAt,sign,transactionOn,tm.sourceWarehouseID 
		into varBranchID,varSign,varTransactionOn,varWarehouseID 
		from tb_transaction_master tm 
		where 
			tm.companyID = prCompanyID and 
			tm.transactionID = prTransactionID and tm.transactionMasterID = prTransactionMasterID limit 1;
	
	
				
		insert into tb_item_warehouse (companyID,branchID,itemID,warehouseID,quantity,cost,quantityMax,quantityMin)
		select 
			k.companyID,k.branchID,k.itemID,k.warehouseID,0,0,0,0
		from
			tb_kardex k  
		where
			k.itemID not in (
					select 
						l.itemID 
					from 
						tb_item_warehouse l 
					where  
						l.companyID = prCompanyID and 
						l.warehouseID = varWarehouseID  
			) and 
			k.companyID = prCompanyID and 
			k.transactionID = prTransactionID and 
			k.transactionMasterID = prTransactionMasterID;
			
			
		insert into tb_kardex (
		companyID,branchID,transactionID,transactionMasterID,
		transactionDetailID,warehouseID,itemID,kardexCode,kardexDate,sign,movementOn,transactionQuantity,transactionCost,
		quantityInWarehouseCurrent,quantityInCurrent
		)
		select 
			tm.companyID,varBranchID,tm.transactionID,tm.transactionMasterID,
			tm.transactionMasterDetailID,tm.inventoryWarehouseSourceID,tm.componentItemID,
			0,current_timestamp(),varSign,varTransactionOn,
			IF(tm.transactionID = 19  ,tm.skuQuantityBySku,tm.quantity) as tm_quantity,
			tm.unitaryCost ,
			iw.quantity,i.quantity 
		from 
			tb_transaction_master_detail tm 
			inner join tb_item i on 
				tm.componentItemID = i.itemID 
			inner join tb_item_warehouse iw on 
				iw.itemID = i.itemID and 
				iw.warehouseID = varWarehouseID 
		where 
			tm.companyID = prCompanyID and 
			tm.transactionID = prTransactionID and tm.transactionMasterID = prTransactionMasterID and tm.isActive = 1;
		

		
		
		
		update tb_kardex,tb_item  set 
			tb_kardex.oldQuantity = IFNULL(tb_item.quantity,0), 
			tb_kardex.oldCost     = IFNULL(tb_item.cost,0)
		where
			tb_item.companyID = tb_kardex.companyID and 
			tb_item.itemID = tb_kardex.itemID and 
			tb_kardex.companyID = prCompanyID and 
			tb_kardex.transactionID = prTransactionID and 
			tb_kardex.transactionMasterID = prTransactionMasterID ;
	
		
		update tb_item_warehouse , tb_kardex set 
					tb_kardex.oldQuantityWarehouse 	= IFNULL(tb_item_warehouse.quantity,0),
	        tb_kardex.oldCostWarehouse 			= IFNULL(tb_item_warehouse.cost,0)
		where
			tb_item_warehouse.companyID = tb_kardex.companyID and 
			tb_item_warehouse.itemID = tb_kardex.itemID and 
			tb_item_warehouse.warehouseID = tb_kardex.warehouseID and 
			tb_kardex.companyID = prCompanyID and 
			tb_kardex.transactionID = prTransactionID and 
			tb_kardex.transactionMasterID = prTransactionMasterID ;
		
		
		
		update tb_kardex set 
			newQuantity           = ((sign * IFNULL(transactionQuantity,0)) + IFNULL(oldQuantity,0)) ,
			newQuantityWarehouse  = ((sign * IFNULL(transactionQuantity,0)) + IFNULL(oldQuantityWarehouse,0)) 
		where 
			companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID ;
		
		update tb_kardex set 
			newCost           = oldCost,
			newCostWarehouse  = oldCostWarehouse
		where 
			companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID ;	
		
		
		update tb_item , tb_kardex set 
			tb_item.quantity = tb_kardex.newQuantity,
			tb_item.cost = tb_kardex.newCost,
			tb_item.dateLastUse = NOW()
		where
			tb_item.companyID = tb_kardex.companyID and 
			tb_item.itemID = tb_kardex.itemID and 
			tb_kardex.companyID = prCompanyID and 
			tb_kardex.transactionID = prTransactionID and 
			tb_kardex.transactionMasterID = prTransactionMasterID ;
	
	  
		update tb_item_warehouse , tb_kardex set 
			tb_item_warehouse.quantity = tb_kardex.newQuantityWarehouse,
			tb_item_warehouse.cost = tb_kardex.newCostWarehouse
		where
			tb_item_warehouse.companyID = tb_kardex.companyID and 
			tb_item_warehouse.itemID = tb_kardex.itemID and 
			tb_item_warehouse.warehouseID = tb_kardex.warehouseID and 
			tb_kardex.companyID = prCompanyID and 
			tb_kardex.transactionID = prTransactionID and 
			tb_kardex.transactionMasterID = prTransactionMasterID ;
				
				
				
		update tb_transaction_master set 
			isApplied = 1 	
		where companyID = prCompanyID and transactionID = prTransactionID and transactionMasterID = prTransactionMasterID; 
	
	
	  
		if varTransactionIDFactura = prTransactionID  then 
				update tb_item , tb_kardex set 
					tb_item.quantityInvoice = IFNULL(tb_item.quantityInvoice,0) + tb_kardex.transactionQuantity 
				where
					tb_item.companyID = tb_kardex.companyID and 
					tb_item.itemID = tb_kardex.itemID and 
					tb_kardex.companyID = prCompanyID and 
					tb_kardex.transactionID = prTransactionID and 
					tb_kardex.transactionMasterID = prTransactionMasterID ;
		end if;
		
		
		
		if varTransactionIDFactura != prTransactionID  then 		
					update tb_item_warehouse_expired , tb_transaction_master_detail set 
						  tb_item_warehouse_expired.quantity = 
							tb_item_warehouse_expired.quantity + (varSign * tb_transaction_master_detail.quantity) 
					where
						tb_item_warehouse_expired.itemID = tb_transaction_master_detail.componentItemID and 
						tb_item_warehouse_expired.companyID = tb_transaction_master_detail.companyID and 
						tb_item_warehouse_expired.warehouseID = tb_transaction_master_detail.inventoryWarehouseSourceID and 
						tb_item_warehouse_expired.dateExpired = IFNULL(
							tb_transaction_master_detail.expirationDate,
							'1900-01-01 00:00:00'
						)  and 
						tb_transaction_master_detail.companyID = prCompanyID and 
						tb_transaction_master_detail.transactionID = prTransactionID and 
						tb_transaction_master_detail.transactionMasterID = prTransactionMasterID and 
						tb_transaction_master_detail.isActive = 1;
		else 
				
				set varWhileItemMin = (
						select min(u.componentItemID) 
						from tb_transaction_master_detail u where u.isActive = 1 and u.transactionMasterID = prTransactionMasterID
				);
				
				set varWhileItemMax = (
						select max(u.componentItemID) 
						from tb_transaction_master_detail u where u.isActive = 1 and u.transactionMasterID = prTransactionMasterID
				);
				
				while varWhileItemMin <= varWhileItemMax do 
							
							set varWhileItemQuantity = (
								select sum(u.skuQuantityBySku) from tb_transaction_master_detail u where u.isActive = 1 and 
								u.componentItemID = varWhileItemMin and 
								u.transactionMasterID = prTransactionMasterID
							);
							
							
							set varWhileFechaVencimientoMin = (
								select min(u.dateExpired) from tb_item_warehouse_expired u where u.itemID = varWhileItemMin 
								and u.warehouseID = varWarehouseID  
								and u.quantity > 0
							);
							
							
							set varWhileFechaVencimientoMax = (
								select max(u.dateExpired) from tb_item_warehouse_expired u where u.itemID = varWhileItemMin 
								and u.warehouseID = varWarehouseID  
								and u.quantity > 0
							);
							
							
							set varWhileFechaVencimientoContinue = 1;
							
							
							while varWhileFechaVencimientoMin <= varWhileFechaVencimientoMax and varWhileFechaVencimientoContinue = 1 do 
							
										
										set varWhileFechaVencimientoQuantity = (
											select u.quantity from tb_item_warehouse_expired u where u.itemID = varWhileItemMin 
											and u.dateExpired = varWhileFechaVencimientoMin and 
											u.quantity > 0 and 
											u.warehouseID = varWarehouseID limit 1 
										);
										
										
										if varWhileFechaVencimientoQuantity >= varWhileItemQuantity  then 											
												update tb_item_warehouse_expired set 
													quantity = quantity - varWhileItemQuantity
												where
													dateExpired = varWhileFechaVencimientoMin and 
													itemID = varWhileItemMin and 
													warehouseID = varWarehouseID;
													
												set varWhileItemQuantity = 0;
												
										elseif varWhileFechaVencimientoQuantity < varWhileItemQuantity  then 
												update tb_item_warehouse_expired set 
													quantity = 0
												where
													dateExpired = varWhileFechaVencimientoMin and 
													itemID = varWhileItemMin and 
													warehouseID = varWarehouseID;
													
												set varWhileItemQuantity = varWhileItemQuantity - varWhileFechaVencimientoQuantity;
										end if;
										
										
										if varWhileItemQuantity <= 0 then 
											set varWhileFechaVencimientoContinue = 0;
										end if;
										
										
										
										set varWhileFechaVencimientoMin = (
											select min(u.dateExpired) from tb_item_warehouse_expired u where u.itemID = varWhileItemMin 
											and u.warehouseID = varWarehouseID  
											and u.quantity > 0 and 
											u.dateExpired > varWhileFechaVencimientoMin 
										);
							end while;
							
							
							set varWhileItemMin = (
									select min(u.componentItemID) 
									from tb_transaction_master_detail u 
									where 
										u.isActive = 1 and u.transactionMasterID = prTransactionMasterID
										and u.componentItemID > varWhileItemMin 
							);
				end while; 
				
		end if;
		
		commit ;
  
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_create_transaction_input_by_ajuste
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_create_transaction_input_by_ajuste`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_create_transaction_input_by_ajuste`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, 
	IN `prTransactionID` INT, IN `prTransactionMasterID` INT, OUT `prResult` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para crer entradas de meraderia'
LBL_PROCEDURE:
BEGIN	
			DECLARE varTransactionID int default  12; 		
			DECLARE varComponentID int default  34; 			
			DECLARE varComponentItemID int default  33; 	
			DECLARE varComponentAccountID int default 4; 					
			
			DECLARE varTransactionMasterID BIGINT default 0; 			
			declare varNote varchar(150) default 'Entrada por ajuste de inventario';			
			DECLARE varTransactionNumber VARCHAR(50) DEFAULT '';
			DECLARE varTransactionCausalID int default  0;
			DECLARE varSignID int default  0;
			DECLARE varCurrencyIDFunction int default  0;
			DECLARE varCurrencyIDExternal int default  0;
			DECLARE varCurrencyIDReport int default  0;
			DECLARE varCurrencyTmporal varchar(50) default '';
			DECLARE varStatusIDTransactionInit int default  0;
			DECLARE varStatusIDTransactionFinish int default  0;
			DECLARE varWarehouseTempora varchar(50) default  '';
			DECLARE varWarehouseID int default  0;
			DECLARE varFechaStart datetime;
			DECLARE varFechaEnd datetime;
			DECLARE varWorkflowStageCycleClosed varchar(50) default '';
			DECLARE varAmount decimal(19,4) default 0;			
			DECLARE varCountDetail INT DEFAULT 0;
			DECLARE varExchangeRate DECIMAL (19,4) DEFAULT 0;
			
			CALL pr_core_get_parameter_value(prCompanyID,"INVENTORY_ITEM_WAREHOUSE_DEFAULT",varWarehouseTempora);
			SET varWarehouseID 		= (
							SELECT 
								c.targetWarehouseID 
							FROM 
								tb_transaction_master  c 
							where 
								c.transactionMasterID = prTransactionMasterID  
							);					
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",varCurrencyTmporal);
			SET varCurrencyIDFunction 		= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);		
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",varCurrencyTmporal);
			SET varCurrencyIDReport 			= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",varCurrencyTmporal);
			SET varCurrencyIDExternal 		= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);		
				
			
			SET varSignID = (SELECT t.signInventory FROM tb_transaction t where t.transactionID = varTransactionID);			
			SET varTransactionCausalID = (
				select t.transactionCausalID from tb_transaction_causal t where t.transactionID = varTransactionID and t.isDefault = 1 limit 1); 
				
			set varExchangeRate = (
					SELECT c.ratio 
					from tb_exchange_rate c 
					where 
						c.currencyID = varCurrencyIDFunction and 
						c.targetCurrencyID = varCurrencyIDExternal and 
						c.date =  CURDATE()
			);
			
			CALL pr_core_get_next_number(prCompanyID,'tb_transaction_master_otherinput',prBranchID,0,varTransactionNumber);		
			CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_transaction_master_otherinput","statusID",varStatusIDTransactionInit );	
			
			
			SET varStatusIDTransactionFinish = (
				select 
					ws.workflowStageID 
				from 
					tb_subelement e 
					inner join tb_element el on 
						e.elementID = el.elementID
					inner join tb_workflow_stage ws on 
						ws.workflowID = e.workflowID 
				where 
					e.`name` = 'statusID' and 
					el.`name` = 'tb_transaction_master_otherinput' and 
					ws.aplicable = 1 
			);
			
			
			INSERT INTO tb_transaction_master(
				companyID,transactionNumber,transactionID,branchID,transactionCausalID,transactionOn,statusIDChangeOn,componentID,
				note,sign,currencyID,currencyID2,exchangeRate,statusID,amount,isApplied,journalEntryID,
				targetWarehouseID,createdBy,createdAt,createdOn,createdIn,isActive) 
			VALUES 
			(
				prCompanyID,varTransactionNumber,varTransactionID,prBranchID,varTransactionCausalID,CURDATE(),NOW(),varComponentID,
				varNote,varSignID,varCurrencyIDFunction,varCurrencyIDFunction,1,varStatusIDTransactionFinish,0,1,0,
				varWarehouseID,prLoginID,prBranchID,NOW(),'::01',1
			);
			
			SET varTransactionMasterID = LAST_INSERT_ID();	
			
			
			DELETE FROM  
				tb_transaction_master_detail_temp 
			WHERE 
				companyID = prCompanyID and 
				transactionID = varTransactionID;
				
			
			
			
			
			INSERT INTO tb_transaction_master_detail_temp (
				transactionMasterDetailID,
 				companyID,transactionID,transactionMasterID,componentID,componentItemID,promotionID,
 				amount,cost,quantity,discount,unitaryAmount,unitaryCost,unitaryPrice,
 				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
 				quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID,
				expirationDate 				
 			)
 			SELECT 
				c.transactionMasterDetailID,
 				prCompanyID,varTransactionID,varTransactionMasterID,varComponentItemID,i.itemID,0,
 				0,c.unitaryPrice * (c.quantity - (iw.quantity)), (c.quantity - (iw.quantity))  ,0,c.unitaryPrice, c.unitaryCost,c.unitaryPrice,
 				0,0,1,0,0,
 				0,0,varWarehouseID,
				c.expirationDate 
 			from 
				tb_transaction_master tm 
				inner join  tb_transaction_master_detail c  on 
					c.transactionMasterID = tm.transactionMasterID
				inner join tb_item i on 
					c.componentItemID  = i.itemID
				inner join tb_item_warehouse iw on 
					iw.itemID = i.itemID and 
					iw.warehouseID = varWarehouseID 
			where 
				c.isActive = 1 and 
				c.transactionID = prTransactionID and 
				c.transactionMasterID = prTransactionMasterID and 
				iw.quantity < c.quantity;
				
				
			
			INSERT INTO tb_transaction_master_detail (
 				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID	,			
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				
				componentItemID,
				expirationDate,
 				cost, 
				quantity
 			)
 			select 
				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID	,		
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,componentItemID,
				expirationDate,
 				sum(cost) as cost, 
				sum(quantity) as quantity
			from 
				tb_transaction_master_detail_temp u
			where 
				u.transactionID = varTransactionID 
			group by 
				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID	,			
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,componentItemID,expirationDate;
				
			
			
			SET varAmount 			= (select sum(u.cost) from tb_transaction_master_detail u where u.transactionMasterID = varTransactionMasterID);
			SET varAmount 			= (case when varAmount is null then 0 else varAmount end);
			SET varCountDetail 	= (select count(u.transactionMasterDetailID) from tb_transaction_master_detail u where u.transactionMasterID = varTransactionMasterID and u.isActive = 1 );
			
 			UPDATE tb_transaction_master set amount = varAmount,subAmount = varAmount where transactionMasterID = varTransactionMasterID;
			
			
			IF varCountDetail = 0 THEN
				UPDATE tb_transaction_master  set isActive = 0 where transactionMasterID = varTransactionMasterID;
				SET varTransactionMasterID = 0;		
			END IF;

			
			
			SET prResult = 1;			
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_input_by_ajuste',
				0,'Success',CURRENT_TIMESTAMP());
			
			
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_input_by_ajuste_transactionID',
				0,varTransactionID,CURRENT_TIMESTAMP());
				
			
			
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_input_by_ajuste_transactionMasterID',
				0,varTransactionMasterID,CURRENT_TIMESTAMP());
		
		
		
		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_create_transaction_otherinput_by_production
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_create_transaction_otherinput_by_production`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_create_transaction_otherinput_by_production`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, 
	IN `prTransactionID` INT, IN `prTransactionMasterID` INT, 	
	IN prWarehouseID INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para crer entradas de meraderia por produccion'
LBL_PROCEDURE:
BEGIN
	
			DECLARE varTransactionID int default  12  ; 		
			DECLARE varComponentID int default  34; 			
			DECLARE varComponentItemID int default  33; 	
			DECLARE varComponentAccountID int default 4; 	
		  DECLARE minItemID int default 0;
			DECLARE maxItemID int default 0;
			DECLARE quantityWhile  DECIMAL(18,8) default 0;
			DECLARE costWhile      DECIMAL(18,8) default 0;
			
			DECLARE varTransactionMasterID BIGINT default 0; 			
			declare varNote varchar(150) default 'Entrada por produccion de inventario';			
			DECLARE varTransactionNumber VARCHAR(50) DEFAULT '';
			DECLARE varTransactionCausalID int default  78;
			DECLARE varSignID int default  0;
			DECLARE varCurrencyIDFunction int default  0;
			DECLARE varCurrencyIDExternal int default  0;
			DECLARE varCurrencyIDReport int default  0;
			DECLARE varCurrencyTmporal varchar(50) default '';
			DECLARE varStatusIDTransactionInit int default  0;
			DECLARE varStatusIDTransactionFinish int default  0;
			DECLARE varWarehouseTempora varchar(50) default  '';			
			DECLARE varFechaStart datetime;
			DECLARE varFechaEnd datetime;
			DECLARE varWorkflowStageCycleClosed varchar(50) default '';
			DECLARE varAmount decimal(19,4) default 0;			
			DECLARE varExchangeRate DECIMAL (19,4) DEFAULT 0;
			DECLARE varTransactionNumberOrigen varchar(50) default '';
			
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",varCurrencyTmporal);
			SET varCurrencyIDFunction 		= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);		
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",varCurrencyTmporal);
			SET varCurrencyIDReport 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",varCurrencyTmporal);
			SET varCurrencyIDExternal 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);						
			
			SET varSignID = (SELECT t.signInventory FROM tb_transaction t where t.transactionID = varTransactionID);			
			SET varTransactionNumberOrigen  = (
						select k.transactionNumber 
						from tb_transaction_master k 
						where k.transactionMasterID = prTransactionMasterID 
			);
				
			set varExchangeRate = (
					SELECT c.ratio 
					from tb_exchange_rate c 
					where 
						c.currencyID = varCurrencyIDFunction and 
						c.targetCurrencyID = varCurrencyIDExternal and 
						c.date =  CURDATE()
			);
			
			CALL pr_core_get_next_number(prCompanyID,'tb_transaction_master_otherinput',prBranchID,0,varTransactionNumber);		
			CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_transaction_master_otherinput","statusID",varStatusIDTransactionInit );	
			
			
			SET varStatusIDTransactionFinish = (
				select 
					ws.workflowStageID 
				from 
					tb_subelement e 
					inner join tb_element el on 
						e.elementID = el.elementID
					inner join tb_workflow_stage ws on 
						ws.workflowID = e.workflowID 
				where 
					e.`name` = 'statusID' and 
					el.`name` = 'tb_transaction_master_otherinput' and 
					ws.aplicable = 1 
			);
			
			
			INSERT INTO tb_transaction_master(
				companyID,transactionNumber,transactionID,branchID,transactionCausalID,transactionOn,statusIDChangeOn,componentID,
				note,sign,currencyID,currencyID2,exchangeRate,statusID,amount,isApplied,journalEntryID,
				targetWarehouseID,createdBy,createdAt,createdOn,createdIn,isActive,reference1) 
			VALUES 
			(
				prCompanyID,varTransactionNumber,varTransactionID,prBranchID,varTransactionCausalID,CURDATE(),NOW(),varComponentID,
				varNote,varSignID,varCurrencyIDFunction,varCurrencyIDFunction,1,varStatusIDTransactionFinish,0,1,0,
				prWarehouseID,prLoginID,prBranchID,NOW(),'::01',1,varTransactionNumberOrigen
			);
			
			SET varTransactionMasterID = LAST_INSERT_ID();	
			
			
			DELETE FROM  
				tb_transaction_master_detail_temp 
			WHERE 
				companyID = prCompanyID and 
				transactionID = varTransactionID;
				
				
			SET minItemID = (
				select 
						min(c.componentItemID)
				from 
					tb_transaction_master_detail c 
				where 
					c.transactionMasterID = prTransactionMasterID and 
					c.inventoryWarehouseTargetID = prWarehouseID and 
					c.isActive = 1 
			);
			
			SET maxItemID = (
				select 
						min(c.componentItemID)
				from 
					tb_transaction_master_detail c 
				where 
					c.transactionMasterID = prTransactionMasterID and 
					c.inventoryWarehouseTargetID = prWarehouseID and 
					c.isActive = 1 
			);
			
			
			
			while minItemID <= maxItemID and minItemID is not null do 				
					set quantityWhile  = 0 ;
					set costWhile 		 = 0 ;
					set quantityWhile  = (
																select sum(u.quantity) from tb_transaction_master_detail u 
																where 
																		u.transactionMasterID = prTransactionMasterID AND 
																		u.isActive = 1 and 
																		u.inventoryWarehouseTargetID = prWarehouseID AND 
																		u.componentItemID = minItemID 
															);
															
					set costWhile  = (
																select 
																			sum(
																				u.quantity * u.unitaryCost
																			) 
																from 
																	  tb_transaction_master_detail u 
																where 
																		u.transactionMasterID = prTransactionMasterID AND 
																		u.isActive = 1 and 
																		u.inventoryWarehouseSourceID is not null and 
																		u.inventoryWarehouseSourceID > 0 and 
																		u.skuCatalogItemID = minItemID 
															);
					
					
					SET quantityWhile = IFNULL(quantityWhile,0);
					SET costWhile 		= IFNULL(costWhile,0);					
					
					INSERT INTO tb_transaction_master_detail_temp (
						transactionMasterDetailID,
						companyID,transactionID,transactionMasterID,componentID,componentItemID,promotionID,
						amount,cost,quantity,discount,unitaryAmount,unitaryCost,unitaryPrice,
						catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
						quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID,
						expirationDate				
					)
					VALUES (
						0,
						prCompanyID,varTransactionID,varTransactionMasterID,varComponentItemID,minItemID,0,
						0  ,
						quantityWhile * costWhile   , 
						quantityWhile   ,
						0,
						costWhile  , 
						costWhile  ,
						costWhile  ,
						0,0,1,0,0,
						0,0,prWarehouseID,
						'1900-01-01'
					);
					
			
					
					set minItemID 		= (
																	select 
																			min(c.componentItemID)
																	from 
																		tb_transaction_master_detail c 
																	where 
																		c.transactionMasterID = prTransactionMasterID and 
																		c.inventoryWarehouseTargetID = prWarehouseID and 
																		c.isActive = 1 and 
																		c.componentItemID > minItemID
															);
			end while;
			
			
			
				
				
			
			INSERT INTO tb_transaction_master_detail (
 				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID	,			
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				
				componentItemID,
				expirationDate,
 				cost, 
				quantity
 			)
 			select 
				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID	,		
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,componentItemID,
				expirationDate,
 				sum(cost) as cost, 
				sum(quantity) as quantity
			from 
				tb_transaction_master_detail_temp u
			where 
				u.transactionID = varTransactionID 
			group by 
				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID	,			
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,componentItemID,expirationDate;
				
			
			
			SET varAmount = (select sum(u.cost) from tb_transaction_master_detail u where u.transactionMasterID = varTransactionMasterID);
			SET varAmount = (case when varAmount is null then 0 else varAmount end);
 			UPDATE tb_transaction_master set amount = varAmount,subAmount = varAmount where transactionMasterID = varTransactionMasterID;
			
			
			UPDATE tb_transaction_master set 
							reference1 = 	CASE 
															WHEN IFNULL(reference1,'') = '' THEN 
																	'|'
															ELSE 
																	''
														END 
			WHERE transactionMasterID = prTransactionMasterID;			
			UPDATE tb_transaction_master set 
							reference1 = 							
										CONCAT(
																	reference1, 
																	'|',
																	varTransactionNumber
									)
			WHERE transactionMasterID = prTransactionMasterID;
			
			
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_otherinput_by_production',
				0,'Success',CURRENT_TIMESTAMP());
			
			
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_otherinput_by_production_transactionID',
				0,varTransactionID,CURRENT_TIMESTAMP());
				
			
			
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_otherinput_by_production_transactionMasterID',
				0,varTransactionMasterID,CURRENT_TIMESTAMP());
		
		
		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_create_transaction_otheroutput_by_production
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_create_transaction_otheroutput_by_production`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_create_transaction_otheroutput_by_production`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, 
	IN `prTransactionID` INT, IN `prTransactionMasterID` INT, 	
	IN prWarehouseID INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para crer salida de meraderia'
LBL_PROCEDURE:
BEGIN	
			DECLARE varTransactionID int default  8  ; 		
			DECLARE varComponentID int default  35; 			
			DECLARE varComponentItemID int default  33; 	
			DECLARE varComponentAccountID int default 4; 	
			
			DECLARE varTransactionMasterID BIGINT default 0; 			
			declare varNote varchar(150) default 'Salida por produccion de inventario';			
			DECLARE varTransactionNumber VARCHAR(50) DEFAULT '';
			DECLARE varTransactionCausalID int default  77;
			DECLARE varSignID int default  0;
			DECLARE varCurrencyIDFunction int default  0;
			DECLARE varCurrencyIDExternal int default  0;
			DECLARE varCurrencyIDReport int default  0;
			DECLARE varCurrencyTmporal varchar(50) default '';
			DECLARE varStatusIDTransactionInit int default  0;
			DECLARE varStatusIDTransactionFinish int default  0;
			DECLARE varWarehouseTempora varchar(50) default  '';
			DECLARE varFechaStart datetime;
			DECLARE varFechaEnd datetime;
			DECLARE varWorkflowStageCycleClosed varchar(50) default '';
			DECLARE varAmount decimal(19,4) default 0;			
			DECLARE varExchangeRate DECIMAL (19,4) DEFAULT 0;
			DECLARE varTransactionNumberOrigen varchar(50) default '';
		
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",varCurrencyTmporal);
			SET varCurrencyIDFunction 		= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);		
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",varCurrencyTmporal);
			SET varCurrencyIDReport 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",varCurrencyTmporal);
			SET varCurrencyIDExternal 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);						
			
			SET varSignID = (SELECT t.signInventory FROM tb_transaction t where t.transactionID = varTransactionID);			
			SET varTransactionNumberOrigen  = (
						select k.transactionNumber 
						from tb_transaction_master k 
						where k.transactionMasterID = prTransactionMasterID 
			);
				
			set varExchangeRate = (
					SELECT c.ratio 
					from tb_exchange_rate c 
					where 
						c.currencyID = varCurrencyIDFunction and 
						c.targetCurrencyID = varCurrencyIDExternal and 
						c.date =  CURDATE()
			);
			
			CALL pr_core_get_next_number(prCompanyID,'tb_transaction_master_otheroutput',prBranchID,0,varTransactionNumber);		
			CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_transaction_master_otheroutput","statusID",varStatusIDTransactionInit );	
			
			
			SET varStatusIDTransactionFinish = (
				select 
					ws.workflowStageID 
				from 
					tb_subelement e 
					inner join tb_element el on 
						e.elementID = el.elementID
					inner join tb_workflow_stage ws on 
						ws.workflowID = e.workflowID 
				where 
					e.`name` = 'statusID' and 
					el.`name` = 'tb_transaction_master_otheroutput' and 
					ws.aplicable = 1 
			);
			
			
			INSERT INTO tb_transaction_master(
				companyID,transactionNumber,transactionID,branchID,transactionCausalID,transactionOn,statusIDChangeOn,componentID,
				note,sign,currencyID,currencyID2,exchangeRate,statusID,amount,isApplied,journalEntryID,
				sourceWarehouseID,createdBy,createdAt,createdOn,createdIn,isActive,reference1,notificationID) 
			VALUES 
			(
				prCompanyID,varTransactionNumber,varTransactionID,prBranchID,varTransactionCausalID,CURDATE(),NOW(),varComponentID,
				varNote,varSignID,varCurrencyIDFunction,varCurrencyIDFunction,1,varStatusIDTransactionFinish,0,1,0,
				prWarehouseID,prLoginID,prBranchID,NOW(),'::01',1,varTransactionNumberOrigen,1 
			);
			
			SET varTransactionMasterID = LAST_INSERT_ID();	
			DELETE FROM  
				tb_transaction_master_detail_temp 
			WHERE 
				companyID = prCompanyID and 
				transactionID = varTransactionID;
			
			
			INSERT INTO tb_transaction_master_detail_temp (
				transactionMasterDetailID,
 				companyID,transactionID,transactionMasterID,componentID,componentItemID,promotionID,
 				amount,cost,quantity,discount,unitaryAmount,unitaryCost,unitaryPrice,
 				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
 				quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID,
				expirationDate 				
 			)
 			SELECT 
				c.transactionMasterDetailID,
 				prCompanyID,varTransactionID,varTransactionMasterID,varComponentItemID,i.itemID,0,
 				0,
				i.cost * c.quantity, 
				c.quantity  ,
				0,
				i.cost, 
				i.cost,
				i.cost,
 				0,0,1,0,0,
 				0,0,prWarehouseID,
				c.expirationDate 
 			from 
				tb_transaction_master tm 
				inner join  tb_transaction_master_detail c  on 
					c.transactionMasterID = tm.transactionMasterID
				inner join tb_item i on 
					c.componentItemID  = i.itemID
			where 
				c.isActive = 1 and 
				c.transactionID = prTransactionID and 
				c.transactionMasterID = prTransactionMasterID and 
				IFNULL(c.inventoryWarehouseSourceID ,0) = prWarehouseID and 
				c.quantity > 0 ; 
			
			
			INSERT INTO tb_transaction_master_detail (
 				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,			
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				
				componentItemID,
				expirationDate,
 				cost, 
				quantity
 			)
 			select 
				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,		
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,componentItemID,
				expirationDate,
 				sum(cost) as cost, 
				sum(quantity) as quantity
			from 
				tb_transaction_master_detail_temp u
			where 
				u.transactionID = varTransactionID 
			group by 
				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseTargetID	,			
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,componentItemID,expirationDate;
			
			
			
			SET varAmount = (select sum(u.cost) from tb_transaction_master_detail u where u.transactionMasterID = varTransactionMasterID);
			SET varAmount = (case when varAmount is null then 0 else varAmount end);
 			UPDATE tb_transaction_master set amount = varAmount,subAmount = varAmount where transactionMasterID = varTransactionMasterID;
			
			UPDATE tb_transaction_master set 
							reference1 = 	CASE 
															WHEN IFNULL(reference1,'') = '' THEN 
																	'|'
															ELSE 
																	''
														END 
			WHERE transactionMasterID = prTransactionMasterID;			
			UPDATE tb_transaction_master set 
							reference1 = 							
										CONCAT(
																	reference1, 
																	'|',
																	varTransactionNumber
									)
			WHERE transactionMasterID = prTransactionMasterID;
			

			
			
				
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_otheroutput_by_production',
				0,'Success',CURRENT_TIMESTAMP());
			
			
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_otheroutput_by_production_transactionID',
				0,varTransactionID,CURRENT_TIMESTAMP());
				
			
			
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_otheroutput_by_production_transactionMasterID',
				0,varTransactionMasterID,CURRENT_TIMESTAMP());
		  
		
		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_create_transaction_output_by_ajuste
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_create_transaction_output_by_ajuste`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_create_transaction_output_by_ajuste`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, 
	IN `prTransactionID` INT, IN `prTransactionMasterID` INT, OUT `prResult` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para crear facturacion de meraderia'
LBL_PROCEDURE:
BEGIN
	
			DECLARE varTransactionID int default  8; 		
			DECLARE varComponentID int default  48; 			
			DECLARE varComponentItemID int default  33; 	
			DECLARE varComponentAccountID int default 4; 	
				
			DECLARE varEntityClientDefault INT default 13;
			DECLARE varTransactionMasterID BIGINT default 0; 			
			declare varNote varchar(150) default 'Salida por ajuste de inventario';			
			DECLARE varTransactionNumber VARCHAR(50) DEFAULT '';
			DECLARE varTransactionCausalID int default  0;
			DECLARE varSignID int default  0;
			DECLARE varCurrencyIDFunction int default  0;
			DECLARE varCurrencyIDExternal int default  0;
			DECLARE varCurrencyIDReport int default  0;
			DECLARE varCurrencyTmporal varchar(50) default '';
			DECLARE varStatusIDTransactionInit int default  0;
			DECLARE varStatusIDTransactionFinish int default  0;
			DECLARE varWarehouseTempora varchar(50) default  '';
			DECLARE varWarehouseID int default  0;
			DECLARE varFechaStart datetime;
			DECLARE varFechaEnd datetime;
			DECLARE varWorkflowStageCycleClosed varchar(50) default '';
			DECLARE varAmount decimal(19,4) default 0;
			DECLARE varExchangeRate DECIMAL (19,4) DEFAULT 0;
			
			CALL pr_core_get_parameter_value(prCompanyID,"INVENTORY_ITEM_WAREHOUSE_DEFAULT",varWarehouseTempora);
			SET varWarehouseID 		= (
							SELECT 
								c.targetWarehouseID 
							FROM 
								tb_transaction_master  c 
							where 
								c.transactionMasterID = prTransactionMasterID  
							);		
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",varCurrencyTmporal);
			SET varCurrencyIDFunction 		= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);		
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",varCurrencyTmporal);
			SET varCurrencyIDReport 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",varCurrencyTmporal);
			SET varCurrencyIDExternal 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);		
				
			
			SET varSignID = (SELECT t.signInventory FROM tb_transaction t where t.transactionID = varTransactionID);			
			SET varTransactionCausalID = (
				select t.transactionCausalID from 
				tb_transaction_causal t where 
				t.transactionID = varTransactionID and t.isDefault = 1 limit 1
			); 
			
			set varExchangeRate = (
					SELECT c.ratio 
					from tb_exchange_rate c 
					where 
						c.currencyID = varCurrencyIDFunction and 
						c.targetCurrencyID = varCurrencyIDExternal and 
						c.date =  CURDATE()
			);
			
			CALL pr_core_get_next_number(prCompanyID,'tb_transaction_master_otheroutput',prBranchID,0,varTransactionNumber);		
			CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_transaction_master_otheroutput","statusID",varStatusIDTransactionInit );	
			
			
			SET varStatusIDTransactionFinish = (
				select 
					ws.workflowStageID 
				from 
					tb_subelement e 
					inner join tb_element el on 
						e.elementID = el.elementID
					inner join tb_workflow_stage ws on 
						ws.workflowID = e.workflowID 
				where 
					e.`name` = 'statusID' and 
					el.`name` = 'tb_transaction_master_otheroutput' and 
					ws.aplicable = 1 
			);
			
			
			INSERT INTO tb_transaction_master(
				companyID,transactionNumber,transactionID,branchID,transactionCausalID,transactionOn,statusIDChangeOn,componentID,
				entityID,transactionOn2,reference1,reference2,reference4,periodPay,nextVisit,reference3,
				note,sign,currencyID,currencyID2,exchangeRate,statusID,amount,isApplied,journalEntryID,
				sourceWarehouseID,createdBy,createdAt,createdOn,createdIn,isActive) 
			VALUES 
			(
				prCompanyID,varTransactionNumber,varTransactionID,prBranchID,varTransactionCausalID,CURDATE(),NOW(),varComponentID,
				varEntityClientDefault,CURDATE(),293  , 1 , 0 , 190  , '0000-00-00 00:00:00' , 'N/D',
				varNote,varSignID,varCurrencyIDFunction,varCurrencyIDExternal,varExchangeRate,varStatusIDTransactionFinish,0,1,0,
				varWarehouseID,prLoginID,prBranchID,NOW(),'::01',1
			);
			
			SET varTransactionMasterID = LAST_INSERT_ID();	
			
			
			DELETE FROM  
				tb_transaction_master_detail_temp 
			WHERE 
				companyID = prCompanyID and 
				transactionID = varTransactionID;
			
			
			INSERT INTO tb_transaction_master_detail_temp (
				transactionMasterDetailID,
 				companyID,transactionID,transactionMasterID,componentID,componentItemID,promotionID,
 				amount,cost,quantity,
				discount,unitaryAmount,unitaryCost,unitaryPrice,
 				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
 				quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID,
				tax1,reference3,skuCatalogItemID,skuQuantity,skuQuantityBySku,
				skuFormatoDescription,itemNameLog,
			  expirationDate					
 			)
 			SELECT 
				c.transactionMasterDetailID,
 				prCompanyID,varTransactionID,varTransactionMasterID,varComponentItemID,i.itemID,0,
 				c.unitaryPrice * (iw.quantity - c.quantity),c.unitaryCost * (iw.quantity - c.quantity), (iw.quantity - c.quantity) ,
				0 ,c.unitaryPrice,c.unitaryCost, c.unitaryPrice,
 				0,0,1,0,0,
 				0,0,varWarehouseID,0, 0  , 78  ,  (iw.quantity - c.quantity)   , 1 , 'UNIDAD',i.name ,
				c.expirationDate 
 			from 
				tb_transaction_master tm 
				inner join  tb_transaction_master_detail c  on 
					c.transactionMasterID = tm.transactionMasterID
				inner join tb_item i on 
					c.componentItemID  = i.itemID
				inner join tb_item_warehouse iw on 
					iw.itemID = i.itemID and 
					iw.warehouseID = varWarehouseID 
			where 
				c.isActive = 1 and 
				c.transactionID = prTransactionID and 
				c.transactionMasterID = prTransactionMasterID and 
				iw.quantity > c.quantity;
				
				
			
			INSERT INTO tb_transaction_master_detail (
 				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,			
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				
				componentItemID,tax1,reference3,skuCatalogItemID,skuQuantityBySku,skuFormatoDescription		,
				itemNameLog,
				expirationDate,
 				cost, 
				quantity, 
				skuQuantity				
 			)
 			select 
				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,		
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				
				componentItemID,u.tax1,reference3,skuCatalogItemID,skuQuantityBySku,skuFormatoDescription		,
				itemNameLog,
				expirationDate,
 				sum(cost) as cost, 
				sum(quantity) as quantity,
				sum(skuQuantity) as skuQuantity
			from 
				tb_transaction_master_detail_temp u
			where 
				u.transactionID = varTransactionID 
			group by 
				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,			
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,componentItemID,tax1,
				reference3,skuCatalogItemID,skuQuantityBySku,skuFormatoDescription,
				itemNameLog,expirationDate;
				
			
			
			SET varAmount = 0; 
			SET varAmount = (select sum(u.amount) from tb_transaction_master_detail u where u.transactionMasterID = varTransactionMasterID);
			SET varAmount = (case when varAmount is null then 0 else varAmount end);
			UPDATE tb_transaction_master set amount = varAmount,subAmount = varAmount,tax1 = 0  where transactionMasterID = varTransactionMasterID;
			
			
			insert into tb_transaction_master_info (
				companyID,transactionID,transactionMasterID,
				zoneID,routeID,referenceClientName,referenceClientIdentifier,
				receiptAmount,changeAmount,mesaID
				)
			VALUES(prCompanyID,varTransactionID,varTransactionMasterID,157,0,'','',varAmount,0,546);
			
			
			IF varAmount = 0 THEN
				UPDATE tb_transaction_master  set isActive = 0 where transactionMasterID = varTransactionMasterID;
				SET varTransactionMasterID = 0;		
			END IF;

			
			
			SET prResult = 1;			
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_output_by_ajuste',
				0,'Success',CURRENT_TIMESTAMP());
			
			
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_output_by_ajuste_transactionID',
				0,varTransactionID,CURRENT_TIMESTAMP());
				
			
			
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_output_by_ajuste_transactionMasterID',
				0,varTransactionMasterID,CURRENT_TIMESTAMP());
		
		
		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_create_transaction_output_by_formulated
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_create_transaction_output_by_formulated`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_create_transaction_output_by_formulated`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prPeriodID` INT, 
	IN `prCycleID` INT, OUT `prResult` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para crer una salida de inventario automatica, para los productos tipos formulas'
LBL_PROCEDURE:
BEGIN
			
			DECLARE varTransactionID int default  8; 	
			DECLARE varComponentID int default  35; 	
			DECLARE varComponentItemID int default  33; 							
			DECLARE varComponentAccountID int default 4;		
			
			DECLARE varCountTransactionMasterDetail int default 0;
			DECLARE varTransactionMasterID BIGINT default 0; 			
			declare varNote varchar(150) default 'Salida automatica por evaluacion de formulas';			
			DECLARE varTransactionNumber VARCHAR(50) DEFAULT '';
			DECLARE varTransactionCausalID int default  0;
			DECLARE varSignID int default  0;
			DECLARE varCurrencyIDFunction int default  0;
			DECLARE varCurrencyIDExternal int default  0;
			DECLARE varCurrencyIDReport int default  0;
			DECLARE varCurrencyTmporal varchar(50) default '';
			DECLARE varStatusIDTransactionInit int default  0;
			DECLARE varStatusIDTransactionFinish int default  0;
			DECLARE varWarehouseTempora varchar(50) default  '';
			DECLARE varWarehouseID int default  0;
			DECLARE varFechaStart datetime;
			DECLARE varFechaEnd datetime;
			DECLARE varWorkflowStageCycleClosed varchar(50) default '';
			
			
			SET varFechaStart = (select u.startOn from tb_accounting_cycle u where u.componentCycleID = prCycleID and u.componentPeriodID = prPeriodID);
			SET varFechaEnd = (select u.endOn from tb_accounting_cycle u where u.componentCycleID = prCycleID and u.componentPeriodID = prPeriodID);
			
			
			CALL pr_core_get_parameter_value(prCompanyID,"INVENTORY_ITEM_WAREHOUSE_DEFAULT",varWarehouseTempora);
			SET varWarehouseID 		= (SELECT warehouseID FROM tb_warehouse where number = varWarehouseTempora);		
			
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",varCurrencyTmporal);
			SET varCurrencyIDFunction 		= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);		
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",varCurrencyTmporal);
			SET varCurrencyIDReport 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",varCurrencyTmporal);
			SET varCurrencyIDExternal 	= (SELECT currencyID FROM tb_currency where name = varCurrencyTmporal);
		
				
			
			SET varSignID = (SELECT t.signInventory FROM tb_transaction t where t.transactionID = varTransactionID);		
			SET varTransactionCausalID = (
					select t.transactionCausalID 
					from tb_transaction_causal t 
					where t.transactionID = varTransactionID and t.isDefault = 1 limit 1
			); 
			
			CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_transaction_master_otheroutput","statusID",varStatusIDTransactionInit );				
			SET varStatusIDTransactionFinish = (
				select 
					ws.workflowStageID 
				from 
					tb_subelement e 
					inner join tb_element el on 
						e.elementID = el.elementID
					inner join tb_workflow_stage ws on 
						ws.workflowID = e.workflowID 
				where 
					e.`name` = 'statusID' and 
					el.`name` = 'tb_transaction_master_otheroutput' and 
					ws.aplicable = 1 
			);
			
			
			CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",varWorkflowStageCycleClosed);
			IF   
				 EXISTS(
					SELECT cc.companyID FROM tb_accounting_cycle cc 
					WHERE  
						cc.companyID = prCompanyID and componentID = varComponentAccountID AND 
						componentPeriodID = prPeriodID and componentCycleID = prCycleID AND 
						statusID = varWorkflowStageCycleClosed 
				) 
			THEN
						SET prResult = 0;
						INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
						VALUES(prCompanyID,prBranchID,prLoginID,'','pr_inventory_create_transaction_output_by_formulated',1,'El ciclo ya esta cerrado',CURRENT_TIMESTAMP());
						LEAVE LBL_PROCEDURE;
			END IF ; 
	
	

			
			INSERT INTO tb_transaction_master(
				companyID,transactionNumber,transactionID,branchID,transactionCausalID,transactionOn,statusIDChangeOn,componentID,
				note,sign,currencyID,currencyID2,exchangeRate,statusID,amount,isApplied,journalEntryID,
				sourceWarehouseID,createdBy,createdAt,createdOn,createdIn,isActive) 
			VALUES 
			(
				prCompanyID,varTransactionNumber,varTransactionID,prBranchID,varTransactionCausalID,CURDATE(),NOW(),varComponentID,
				varNote,varSignID,varCurrencyIDFunction,varCurrencyIDFunction,1,varStatusIDTransactionFinish,0,1,0,
				varWarehouseID,prLoginID,prBranchID,NOW(),'::01',1
			);
			
			SET varTransactionMasterID = LAST_INSERT_ID();	
			
			DELETE FROM  
				tb_transaction_master_detail_temp 
			WHERE 
				companyID = prCompanyID and 
				transactionID = varTransactionID;
				
			
 			INSERT INTO tb_transaction_master_detail_temp (
				transactionMasterDetailID,
 				companyID,transactionID,transactionMasterID,componentID,componentItemID,promotionID,
 				amount,cost,quantity,discount,unitaryAmount,unitaryCost,unitaryPrice,
 				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
 				quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID,
				expirationDate				
 			)
 			SELECT 
				tmd.transactionMasterDetailID,
 				prCompanyID,varTransactionID,varTransactionMasterID,varComponentItemID,i2.itemID,0,
 				0,i2.cost * tmd.quantity * dsd.quantity, tmd.quantity * dsd.quantity ,0,0, i2.cost,0,
 				0,0,1,0,0,
 				0,0,varWarehouseID,
				tmd.expirationDate 
 			FROM 
				tb_transaction_master_detail tmd 
				inner join tb_transaction_master tmm on 
					tmd.transactionMasterID = tmm.transactionMasterID 
				inner join tb_workflow_stage ws on 
					ws.workflowStageID = tmm.statusID 
				inner join tb_transaction tt on 
					tt.transactionID = tmm.transactionID 
				inner join tb_item i on 
					tmd.componentItemID = i.itemID 
				inner join tb_item_data_sheet ds on 
					ds.itemID = i.itemID 
				inner join tb_item_data_sheet_detail dsd on 
					ds.itemDataSheetID = dsd.itemDataSheetID 
				inner join tb_item i2 on 
					i2.itemID = dsd.itemID 
			WHERE
				tmm.isActive = 1 and 
				IFNULL(tt.signInventory,0) < 0 and 
				tmd.isActive = 1 and 
				tmd.itemFormulatedApplied = 0 and 
				ds.isActive = 1 and 
				dsd.isActive = 1 and 
				(
					(tmm.transactionOn BETWEEN varFechaStart and varFechaEnd) or 
					(
							prPeriodID = 0 and 
							prCycleID = 0 
					)
				) and 
				tt.transactionID in (19  ) and 
				ws.aplicable = 1;
				
			
			#guardar la relacion de la facturas con las salidas 
			insert into tb_company_component_relation(componentIDSource,componentItemIDSource,componentIDTarget,componentItemIDTarget,isActive,note)
			select 
				35 /*transacciones de : otras salidas*/ , 
				varTransactionMasterID,
				48 /*transaccines de factura*/ , 
				k.transactionMasterDetailID,
				1,
				'relacion entre las salidas de materia primera vs detalle de facturas de recetas '
			from 
				tb_transaction_master_detail_temp k ;
		
				
			
			UPDATE tb_transaction_master_detail , tb_transaction_master_detail_temp set 
				tb_transaction_master_detail.itemFormulatedApplied = 1
			WHERE	
				tb_transaction_master_detail.transactionMasterDetailID = 
				tb_transaction_master_detail_temp.transactionMasterDetailID;
				
				
			
			INSERT INTO tb_transaction_master_detail (
 				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,			
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				
				componentItemID,
				expirationDate,
 				cost, 
				quantity
 			)
 			select 
				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,		
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				
				componentItemID,
				expirationDate,
 				sum(cost) as cost, 
				sum(quantity) as quantity
			from 
				tb_transaction_master_detail_temp u
			group by 
				companyID,transactionID,transactionMasterID,componentID,
				promotionID,quantityStockUnaswared,remaingStock,inventoryWarehouseSourceID	,			
				catalogStatusID,inventoryStatusID,isActive,quantityStock,quantiryStockInTraffic,
				discount,unitaryAmount,unitaryCost,unitaryPrice,amount,				
				componentItemID,expirationDate;
				
				
			SET varCountTransactionMasterDetail = (
			select 
				count(c.transactionMasterDetailID) as count_
			from 
				tb_transaction_master_detail c 
			where 
				c.companyID = prCompanyID and 
				c.isActive = 1 and 
				c.transactionMasterID = varTransactionMasterID and 
				c.quantity > 0 
			); 
			
			
			
			SET varCountTransactionMasterDetail = IFNULL(varCountTransactionMasterDetail,0);
			IF varCountTransactionMasterDetail = 0 THEN 
				 DELETE FROM tb_transaction_master WHERE transactionMasterID = varTransactionMasterID;
		  ELSE 
			   CALL pr_core_get_next_number(prCompanyID,'tb_transaction_master_otheroutput',prBranchID,0,varTransactionNumber);							 	
			   UPDATE tb_transaction_master set transactionNumber = varTransactionNumber where transactionMasterID = varTransactionMasterID;
			END IF;
				
			
			
			SET prResult = 1;
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_output_by_formulated',
				0,'Success',CURRENT_TIMESTAMP());
			
			
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_output_by_formulated_transactionID',
				0,varTransactionID,CURRENT_TIMESTAMP());
				
			
			
			INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)
			VALUES(
				prCompanyID,prBranchID,prLoginID,'',
				'pr_inventory_create_transaction_output_by_formulated_transactionMasterID',
				0,varTransactionMasterID,CURRENT_TIMESTAMP());
				
			
		
		
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_get_eport_list_item_expired
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_get_eport_list_item_expired`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_get_eport_list_item_expired`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Lista de Productos por Vencer'
BEGIN


  SELECT 
		r2.itemNumber,
		r2.itemName,
		r2.unitMeasure,
		r2.categoryName,
		r2.quantity,
		r2.cost,
		r2.pricePublico,
		r2.warehouseName,
		r2.dateExpired , 
		r2.lote 	,
		r2.quantityExpired,
		DATEDIFF(CURDATE(), r2.dateExpired) as dateExpiredInDay ,
		(
			select 
					GROUP_CONCAT(concat(natp.firstName,' ',natp.lastName) SEPARATOR ', ')
			from  
				tb_provider_item p 
				inner join tb_naturales natp on 
						natp.entityID = p.entityID 
			where 
				p.itemID = r2.itemID 
		) as proveedorName   
	from 
		(
			SELECT 
				r.itemID,
				r.itemNumber,
				r.itemName,
				r.unitMeasure,
				r.categoryName,
				r.quantity,
				r.cost,
				r.pricePublico,
				r.warehouseName,
				r.dateExpired , 
				r.lote 	,
				SUM(r.quantityExpired ) AS quantityExpired
			FROM 
				(
						SELECT 
							i.itemID,
							i.itemNumber,
							case
								when comp.flavorID = 309  then 
									CONCAT(i.name,' ',i.barCode )
								else 
									i.name 
							end as itemName,
							ciu.name as unitMeasure,
							ic.name as categoryName,
							i.quantity as quantity,
							i.cost as cost,
							(select pp.price from tb_price pp where pp.itemID = i.itemID and pp.typePriceID = 154 limit 1) as pricePublico,
							w.`name` as warehouseName,
							
							
							case 
								 WHEN ue.dateExpired IS NULL  THEN 
									CAST('1901-01-01' AS DATE)
								 WHEN  ue.dateExpired < '1901-01-01' THEN 
									CAST('1901-01-01' AS DATE)
								else 
									ue.dateExpired
							end as dateExpired , 
							
							ue.quantity quantityExpired ,
							ue.lote 		

						FROM 
							tb_item i
							inner join tb_workflow_stage ws on 
								i.statusID = ws.workflowStageID 
							inner join tb_catalog_item ciu on 
								i.unitMeasureID = ciu.catalogItemID 
							inner join tb_item_category ic on 
								i.inventoryCategoryID = ic.inventoryCategoryID 
							inner join tb_warehouse w on 
								w.warehouseID = i.defaultWarehouseID 	
							inner join tb_item_warehouse_expired ue on 
								ue.itemID = i.itemID and 
								ue.warehouseID = w.warehouseID and 
								ue.companyID = i.companyID 
							inner join tb_company comp on 
								comp.companyID = i.companyID 
						WHERE
							i.isActive = 1 AND 
							i.companyID = prCompanyID and 
							ue.quantity > 0 and 
							IFNULL(i.isPerishable,0) = 1
						ORDER BY 
							 ue.dateExpired asc 
				)  AS r 
			GROUP BY 
				r.itemID,
				r.itemNumber,
				r.itemName,
				r.unitMeasure,
				r.categoryName,
				r.quantity,
				r.cost,
				r.pricePublico,
				r.warehouseName,
				r.dateExpired , 
				r.lote
				
		) as r2 ;
			
					
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_get_report_auxiliar_mov_by_allwarehouse
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_get_report_auxiliar_mov_by_allwarehouse`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_get_report_auxiliar_mov_by_allwarehouse`(IN `prCompanyID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME, IN `prItemID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para obtener la lista de todos los movimientos del producto de todas las bodegas'
BEGIN

  declare flavorID int default 0;

	set flavorID = (SELECT u.flavorID from tb_company u where u.companyID = prCompanyID);

	

	

	SELECT 

		x.movementOn as movementOn,

		case

			when flavorID = 306  then 

				 REPLACE(tm.transactionNumber,'ESP','COMPRA ')

			else 

				tm.transactionNumber

		end as transactionNumber,

		i.itemNumber,

		i.name as itemName,

		ci.name as unitMeasureName,

		x.oldQuantity,

		x.oldCost,

		(x.transactionQuantity * x.sign) as transactionQuantity,

		x.transactionCost , 

		x.newQuantity,

		x.newCost,

		IF(x.sign = 1 , 'Entrada','Salida') as transactionType,

		wr.name as warehouseName,

		concat(wr.number,' ',wr.name)   as warehouseNumber

	FROM 

		tb_kardex x 

		inner join tb_item i on 

			x.itemID = i.itemID 

		inner join tb_transaction_master tm  on 

			x.transactionMasterID = tm.transactionMasterID and 

			x.transactionID = tm.transactionID 

		inner join tb_catalog_item ci on 

			i.unitMeasureID = ci.catalogItemID 

		inner join tb_warehouse wr on 

			IF(x.sign = 1,tm.targetWarehouseID,tm.sourceWarehouseID) = wr.warehouseID  

	WHERE

		x.companyID = prCompanyID and 

		x.movementOn between prStartOn and prEndOn and 

		x.itemID = prItemID 

	ORDER BY 

	 	 x.kardexID  ;

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_get_report_auxiliar_mov_by_warehouse
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_get_report_auxiliar_mov_by_warehouse`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_get_report_auxiliar_mov_by_warehouse`(IN `prCompanyID` INT, IN `prWarehouseID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME, IN `prItemID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para obtener los movimientos de productos de una bodega'
BEGIN

	SELECT 

		x.movementOn as transactionOn,

		tm.transactionNumber ,

		i.itemNumber,

		i.name as itemName,

		ci.name as itemUnitmeasure,

		IF(x.sign = 1,'Entrada','Salida') as itemType,

		x.transactionQuantity as quantity,

		x.newQuantityWarehouse as balance

	FROM 

		tb_kardex x 

		inner join tb_item i on 

			x.itemID = i.itemID 

		inner join tb_catalog_item ci on 

			i.unitMeasureID = ci.catalogItemID 

		inner join tb_transaction_master tm on 

			x.transactionMasterID = tm.transactionMasterID and 

			x.transactionID = tm.transactionID 

	WHERE 

		x.movementOn between prStartOn and prEndOn and 

		x.companyID = prCompanyID and 

		x.warehouseID = prWarehouseID and 

		i.itemID = prItemID 

	ORDER BY

	 	x.movementOn , x.kardexID  ;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_get_report_list_item
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_get_report_list_item`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_get_report_list_item`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT,IN `prWarehouseID` INT,IN `prCategoryID` INT, IN prEntityIDProvider INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Lista de Productos'
BEGIN
	SELECT 
		sub.itemNumber,
		sub.barCode,
		sub.itemName,
		sub.categoryName,
		sub.unitMeasure,
		sub.cost,
		sub.price,
		sub.price2,
		sub.price3,
		sub.warehouseName,
		sub.Moneda,
		sub.unidadMedidaName,
		sub.familyName,
		sub.isActive,
		sub.vendors,
		SUM(sub.quantity ) as quantity 
	FROM 
		(
			SELECT 
				i.itemNumber,
				i.barCode,
				case
					when comp.flavorID = 309  then 
						concat(i.`name` , ' ' , i.barCode) 
					else 
						i.name 
				end as itemName,
				ic.name as categoryName,
				ciu.name as unitMeasure,
				i.cost as cost,
				(select pp.price from tb_price pp where pp.itemID = i.itemID and pp.typePriceID = 154 limit 1) as price,
				(select pp.price from tb_price pp where pp.itemID = i.itemID and pp.typePriceID = 155 limit 1) as price2,
				(select pp.price from tb_price pp where pp.itemID = i.itemID and pp.typePriceID = 156 limit 1) as price3,
				w.`name` as warehouseName,
				cur.`name` as Moneda,
				ciu.`name` as unidadMedidaName ,
				IF(family.catalogItemID is null,family.`name`,family_cli.`name`) as familyName ,
				i.isActive,
				'1254' as vendors,  
				iw.quantity AS quantity, 				
				(
					select 
						GROUP_CONCAT(nat.firstName SEPARATOR ', ') AS productos 
					from 
						tb_item it 
						inner join tb_provider_item pi on 
							pi.itemID = it.itemID 
						inner join tb_provider pp on 
							pp.entityID = pi.entityID 
						inner join tb_naturales nat on 
							nat.entityID = pp.entityID 
					where 
						it.isActive = 1 and 
						pp.isActive = 1 and 
						it.itemID = i.itemID 
				) as providerName  
					
			FROM 
				tb_item i
				inner join tb_workflow_stage ws on 
					i.statusID = ws.workflowStageID 
				inner join tb_catalog_item ciu on 
					i.unitMeasureID = ciu.catalogItemID 
				inner join tb_item_category ic on 
					i.inventoryCategoryID = ic.inventoryCategoryID 
				inner join tb_currency cur on 
					cur.currencyID = i.currencyID 
				inner join tb_company comp on 
					comp.companyID = i.companyID 		
				left join tb_public_catalog_detail family_cli on 
					family_cli.publicCatalogDetailID = i.familyID 
				left join tb_catalog_item family on 
					family.catalogItemID = i.familyID 
					
				inner join tb_item_warehouse iw on 
					iw.itemID = i.itemID
				inner join tb_warehouse w on
					w.warehouseID = iw.warehouseID
			WHERE
			  iw.quantity > 0 and 
				i.isActive = 1 AND 
				i.companyID = prCompanyID and 
				(
					(
						prWarehouseID = 0 
					)
					or 
					(
						iw.warehouseID = prWarehouseID and   prWarehouseID != 0  
					)
				) and 
				(
					(
						prCategoryID = 0 
					)
					or 
					(
						ic.inventoryCategoryID = prCategoryID and   prCategoryID != 0  
					)
				)	AND 
				i.itemID IN (
					select 
						it.itemID 
					from 
						tb_item it 
						inner join tb_provider_item pi on 
							pi.itemID = it.itemID 
						inner join tb_provider pp on 
							pp.entityID = pi.entityID 
					where 
						it.isActive = 1 and 
						pp.isActive = 1 and 
						(
							(pp.entityID = prEntityIDProvider AND prEntityIDProvider != 0 ) or 
							(prEntityIDProvider = 0)
						)
					
				)		
			ORDER BY 
				w.`name` , ic.`name` , i.`name`
		) sub
	GROUP BY 
		sub.itemNumber,
		sub.barCode,
		sub.itemName,
		sub.categoryName,
		sub.unitMeasure,
		sub.cost,
		sub.price,
		sub.price2,
		sub.price3,
		sub.warehouseName,
		sub.Moneda,
		sub.unidadMedidaName,
		sub.familyName,
		sub.isActive,
		sub.vendors; 
		 
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_get_report_list_item_by_warehouse
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_get_report_list_item_by_warehouse`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_get_report_list_item_by_warehouse`(IN `prUserID` int,IN `prTokenID` varchar(50),IN `prCompanyID` int,IN `prWarehouseID` VARCHAR(150))
BEGIN



  

	drop temporary table if exists tb_tmp_split;

	drop temporary table if exists tb_tmp_split2;

	create temporary table tb_tmp_split( val char(255) );

	create temporary table tb_tmp_split2( val char(255) );

	set @sql = concat("insert into tb_tmp_split (val) values ('", replace(prWarehouseID, ",", "'),('"),"');");

	prepare stmt1 from @sql;

	execute stmt1;

	

	insert into tb_tmp_split2 (val) 

	select zu.val from tb_tmp_split zu; 

	

	SELECT 

		i.itemNumber,

		case 

			when comp.flavorID = 309  then 

				CONCAT(i.`name`,' ',i.barCode)

			else 

				i.name 

		end as itemName,		

		i.barCode as barCode,

		cat.`name` as categoryName, 

		ciu.name as unitMeasure,

		ic.name as categoryName,

		iw.quantity as quantity,

		i.cost as cost,

		

		

		

		

		

		

		(select pp.price from tb_price pp where pp.itemID = i.itemID and pp.typePriceID = 154 limit 1) as price,

		(select pp.price from tb_price pp where pp.itemID = i.itemID and pp.typePriceID = 155 limit 1) as price2,

		(select pp.price from tb_price pp where pp.itemID = i.itemID and pp.typePriceID = 156 limit 1) as price3,

		w.`name` as warehouseName

	FROM 

		tb_item i		

		inner join tb_item_warehouse iw on  

			i.itemID = iw.itemID 

		inner join tb_warehouse w on 

			w.warehouseID = iw.warehouseID 			

		inner join tb_workflow_stage ws on 

			i.statusID = ws.workflowStageID 		

		inner join tb_catalog_item ciu on 

			i.unitMeasureID = ciu.catalogItemID 

		inner join tb_item_category ic on 

			i.inventoryCategoryID = ic.inventoryCategoryID 		

		inner join tb_item_category cat on 

			i.inventoryCategoryID = cat.inventoryCategoryID 

		inner join tb_company comp on 

			comp.companyID = i.companyID 

			

	WHERE

		i.isActive = 1 AND 

		i.companyID = prCompanyID and 

		(

			 (

			  0 IN ( SELECT TUX.val FROM tb_tmp_split2 TUX )

			 )

			 or 

			 (

				w.warehouseID IN (SELECT TU.val FROM tb_tmp_split TU )

			 )

			 

		) and 

		(

			(comp.flavorID = 309  and i.quantity != 0 ) 

			or 

			(comp.flavorID != 309)

		) and 

		(

		 (

				comp.type = 'chicextensiones' and 

				iw.quantity != 0 

		 ) or 

		 (

			 comp.type != 'chicextensiones'

		 )

		)

	ORDER BY 

		ic.`name` , i.`name`; 



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_get_report_list_item_out_exists
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_get_report_list_item_out_exists`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_get_report_list_item_out_exists`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Lista de Productos'
BEGIN

	SELECT 

		i.itemNumber,

		case 

			when comp.flavorID = 309  then 

				CONCAT(i.`name`,' ',i.barCode)

			else 

				i.name 

		end as itemName,

		cat.`name` as categoryName, 

		ciu.name as unitMeasure,

		ic.name as categoryName,

		i.quantity as quantity,

		i.cost as cost,

		(select pp.price from tb_price pp where pp.itemID = i.itemID limit 1) as price,

		w.`name` as warehouseName

	FROM 

		tb_item i

		inner join tb_workflow_stage ws on 

			i.statusID = ws.workflowStageID 

		inner join tb_catalog_item ciu on 

			i.unitMeasureID = ciu.catalogItemID 

		inner join tb_item_category ic on 

			i.inventoryCategoryID = ic.inventoryCategoryID 

		inner join tb_warehouse w on 

			w.warehouseID = i.defaultWarehouseID 		

		inner join tb_item_category cat on 

			i.inventoryCategoryID = cat.inventoryCategoryID 

		inner join tb_company comp on 

			comp.companyID = i.companyID 

		

	WHERE

		i.isActive = 1 AND 

		i.companyID = prCompanyID and 

		i.quantity = 0 

	ORDER BY 

		ic.`name` , i.`name`; 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_get_report_list_item_width_exists
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_get_report_list_item_width_exists`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_get_report_list_item_width_exists`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Lista de Productos'
BEGIN

	SELECT 

		i.itemNumber,

		case 

			when comp.flavorID = 309  then 

				CONCAT(i.`name`,' ',i.barCode)

			else 

				i.name 

		end as itemName,

		cat.`name` as categoryName, 

		ciu.name as unitMeasure,

		ic.name as categoryName,

		i.quantity as quantity,

		i.cost as cost,

		(select pp.price from tb_price pp where pp.itemID = i.itemID limit 1) as price,

		w.`name` as warehouseName

	FROM 

		tb_item i

		inner join tb_workflow_stage ws on 

			i.statusID = ws.workflowStageID 

		inner join tb_catalog_item ciu on 

			i.unitMeasureID = ciu.catalogItemID 

		inner join tb_item_category ic on 

			i.inventoryCategoryID = ic.inventoryCategoryID 

		inner join tb_warehouse w on 

			w.warehouseID = i.defaultWarehouseID 		

		inner join tb_item_category cat on 

			i.inventoryCategoryID = cat.inventoryCategoryID 

		inner join tb_company comp on 

			comp.companyID = i.companyID 

		

	WHERE

		i.isActive = 1 AND 

		i.companyID = prCompanyID and 

		i.quantity > 0 

	ORDER BY 

		ic.`name` , i.`name`; 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_get_report_master_kardex
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_get_report_master_kardex`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_get_report_master_kardex`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT, IN `prWarehouseID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para obtener el karde de mercaderia'
BEGIN

	declare minItemID int default 0;

	declare maxItemID int default 0;

	declare minKardexID int default 0; 

	declare newCost decimal(19,9)		 default 0;

	declare newQuantity decimal(19,9) default 0;

	declare itemName varchar(500) default '';

	declare itemNumber varchar(50) default ''; 

	 

	set minItemID = (select min(i.itemID) from tb_item i where i.companyID = prCompanyID and i.isActive = 1 and IFNULL(i.isServices,0) = 0 );

	set maxItemID = (select max(i.itemID) from tb_item i where i.companyID = prCompanyID and i.isActive = 1 and IFNULL(i.isServices,0) = 0 );

		

  delete  from tb_master_kardex_temp  where userID = prUserID;

	

	

	insert into tb_master_kardex_temp (

				userID,tokenID,companyID,itemID,itemNumber,itemName,itemCategoryName,minKardexID,

				quantityInput,costInput,quantityOutput,costOutput

	)

	SELECT  

		prUserID,

		prTokenID,

		prCompanyID,

		i.itemID,

		i.itemNumber,

		i.name as itemName,

		cat.`name` as categoryName,

		MIN(k.kardexID) as kardexID,

		SUM(IF(k.sign = 1,k.transactionQuantity,0)) as entrada_cantidad,

		SUM(IF(k.sign = 1,k.transactionQuantity * k.transactionCost, 0)) as entrada_costo,

		SUM(IF(k.sign = -1,k.transactionQuantity,0)) as salida_cantidad,

		SUM(IF(k.sign = -1,k.transactionQuantity * k.transactionCost,0)) as salida_costo

	FROM 

		tb_kardex k 

		inner join tb_item i on 

			k.itemID = i.itemID and 

			k.companyID = i.companyID 

		inner join tb_item_category cat on 

			cat.inventoryCategoryID = i.inventoryCategoryID 

	where	

	  IFNULL(i.isServices,0) = 0 and 

		k.companyID = prCompanyID and 

		k.movementOn between prStartOn and  prEndOn and 

		((k.warehouseID = prWarehouseID  and prWarehouseID <> 0) or (prWarehouseID = 0))

	group by 

		prUserID,

		prTokenID,

		prCompanyID,

		i.itemID,

		i.itemNumber,

		i.name;



	

	insert into tb_master_kardex_temp (

			userID,tokenID,companyID,itemID,itemNumber,itemName,itemCategoryName,

			minKardexID,quantityInput,costInput,quantityOutput,costOutput

	)

	select 

		prUserID,prTokenID,prCompanyID,i.itemID,i.itemNumber,

		case 

			when comp.flavorID = 309  then 

				concat(i.name,' ',i.barCode)

			else 

			  concat(i.name)

		end as nameItem ,

		cat.`name`,0,0,0,0,0

	from 

		tb_item i

		inner join tb_item_category cat on 

			i.inventoryCategoryID = cat.inventoryCategoryID 

		inner join tb_company comp on 

			comp.companyID = i.companyID 

	where

	  IFNULL(i.isServices,0) = 0 and 

		i.companyID = prCompanyID and 

		i.isActive = 1 and 

		i.itemID not in (select k.itemID from tb_master_kardex_temp k where k.userID = prUserID and k.companyID = prCompanyID);

		

		

			

	while minItemID <= maxItemID and minItemID is not null do 	

		

				

				if exists (

					select  p.itemID from tb_master_kardex_temp p 

					where p.companyID = prCompanyID and p.itemID = minItemID and userID = prUserID and p.minKardexID <> 0  

				) 

				then 

						set newQuantity = 0;

						set newCost 	 = 0;

						set minKardexID 	= (

																	select min(c.minKardexID) 

																	from tb_master_kardex_temp c 

																	where c.itemID = minItemID and c.companyID = prCompanyID and c.userID = prUserID

																);

						set minKardexID	= (

																select max(p.kardexID) 

																from tb_kardex p 

																where 

																	p.companyID = prCompanyID and p.itemID = minItemID 

																	and p.kardexID < minKardexID and 

																		(

																			(p.warehouseID = prWarehouseID  and prWarehouseID <> 0) or (prWarehouseID = 0)

																		)  

															); 

						

						select 

							p.newCost,p.newQuantity into newCost,newQuantity 

						from 

							tb_kardex p where p.kardexID = minKardexID;

							

						update tb_master_kardex_temp set quantityInicial =  newQuantity, costInicial = (newCost * newQuantity) 

						where 

							companyID = prCompanyID and itemID = minItemID and userID = prUserID;

				else

				

						set newQuantity = 0;

						set newCost 	 = 0;

					

						set minKardexID = (

																select max(p.kardexID) 

																from tb_kardex p 

																where 

																	p.companyID = prCompanyID and p.itemID = minItemID and p.movementOn < prStartOn and 

																	(

																		(p.warehouseID = prWarehouseID  and prWarehouseID <> 0) or (prWarehouseID = 0)

																	) 

															); 

						select p.newCost,p.newQuantity into newCost,newQuantity from tb_kardex p where p.kardexID = minKardexID;

						

						update tb_master_kardex_temp set 

							quantityInicial =  newQuantity, costInicial = (newCost * newQuantity),minKardexID = minKardexID  

						where 

							companyID = prCompanyID and itemID = minItemID and userID = prUserID;

											

				end if;

				

				set minItemID 		= (

																select min(i.itemID) from tb_item i where i.isActive = 1 and i.companyID = prCompanyID and i.itemID > minItemID and IFNULL(i.isServices,0) = 0 

														);

	end while;

		

	select 

		* 

	from 

		tb_master_kardex_temp i 

	where 	

		userID = prUserID 

	order by 

		i.itemCategoryName,i.itemName;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_get_report_purchase
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_get_report_purchase`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_get_report_purchase`(IN `prUserID` INT, IN `prTokenID` VARCHAR(50), IN `prCompanyID` INT, IN `prWarehouseID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME, IN prEntityIDProvider INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para obtener el karde de mercaderia'
BEGIN

	

		SELECT 

			tm.transactionMasterID,

			tm.transactionNumber,

			tm.createdOn,

			cu.name as currencyName,

			ws.`name` as statusName,

			nat.firstName as providerName,

			w.`name` as warehouseName,

			i.itemNumber,

			i.`name` as itemName,

			td.quantity,

			td.unitaryCost,

			td.cost 

		FROM 

			tb_transaction_master tm 

			inner join tb_workflow_stage ws on 

				ws.workflowStageID = tm.statusID 

			inner join tb_naturales nat on 

				nat.entityID = tm.entityID 

			inner join tb_transaction_master_detail td on 

				td.transactionMasterID = tm.transactionMasterID 

			inner join tb_item i on 

				i.itemID = td.componentItemID 

			inner join tb_warehouse w on 

				w.warehouseID = tm.targetWarehouseID 

			inner join tb_currency cu on 

				cu.currencyID = tm.currencyID 

		WHERE 

			tm.isActive = 1 and 

			tm.transactionID = 21  and 

			ws.aplicable = 1 and 

			td.isActive = 1 and 

			tm.companyID = prCompanyID and 

			tm.createdOn between prStartOn and prEndOn and 

			(

				(

					tm.targetWarehouseID  = prWarehouseID  and 

					prWarehouseID != 0

				)

				or 

				(

				  prWarehouseID = 0 

				)

			) and 

			(

				(

					 tm.entityID  = prEntityIDProvider and 

					 prEntityIDProvider != 0 

				)

				or 

				(

					prEntityIDProvider = 0 

				)

			) 

		ORDER BY 

			tm.createdOn ASC;

			

			

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_get_report_ratation
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_get_report_ratation`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_get_report_ratation`(IN `prUserID` INT, 
	IN `prTokenID` VARCHAR(50), 
	IN `prCompanyID` INT, 
	IN `prWarehouseID` INT, 
	IN `prStartOn` DATETIME, 
	IN `prEndOn` DATETIME,
	IN prCantDayEvaluate INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para calcular la rotacion de inventario'
BEGIN
	 
	 #calcular el kardex
	 CALL pr_inventory_get_report_master_kardex(prUserID,prTokenID,prCompanyID,prWarehouseID,prStartOn,prEndOn);
	 
				
	select 
			re.itemID,
			re.itemNumber,
			re.itemName,
			re.cantidad_venta,
			re.quantity_actual,
			re.rotacion,
			re.rotacion_dia 
	from 
		(
			select 
				ii.itemID ,
				ii.itemNumber,
				ii.`name` as itemName,
				ifnull(venta.cantidad_venta,0) as cantidad_venta,
				ifnull(ii.quantity,0) as quantity_actual,
				round(ifnull(ifnull(venta.cantidad_venta,0) / ifnull(ii.quantity,0),0),4) as rotacion , 
				round(ifnull(prCantDayEvaluate / ifnull(ifnull(venta.cantidad_venta,0) / ifnull(ii.quantity,0),0),0),4) as rotacion_dia 
			from 
				tb_item ii 
				left join (
							select 
								i.itemID,
								ifnull(sum(tmd.quantity),0) as cantidad_venta 
							from 
								tb_item i 
								inner join tb_transaction_master_detail tmd on 
									tmd.componentItemID = i.itemID 
								inner join tb_transaction_master tm on 
									tm.transactionMasterID = tmd.transactionMasterID 
							where 
								i.isActive = 1 and 
								tm.isActive = 1 and 
								tmd.isActive = 1 and 
								tm.transactionID = 19 /*factura*/ and 
								tm.statusID in (67 /*aplicadas*/) and 
								tm.createdOn between  prStartOn and prEndOn  
							group by 
								i.itemID 
				) venta on 
					ii.itemID  = venta.itemID 			
				left join (
						select 
							 i.itemID,
							 i.itemNumber,
							 i.itemName,
							 ifnull(i.quantityInicial,0) as quantityInicial,
							 ifnull(i.quantityInput,0) as quantityInput,
							 ifnull(i.quantityOutput,0) as quantityOutput ,
							 (ifnull(i.quantityInicial,0) +  ifnull(i.quantityInput,0) - ifnull(i.quantityOutput,0)) as quantityFinal,
								
							 (
								ifnull(i.quantityInicial,0) + 
								(ifnull(i.quantityInicial,0) +  ifnull(i.quantityInput,0) - ifnull(i.quantityOutput,0))
							 ) / 2  as quantityPrimedio  
						from
							tb_master_kardex_temp i 
						where 
							i.userID = prUserID 
				) invento on 
					ii.itemID = invento.itemID 
		) re 
	order by 
		re.rotacion_dia ; 
				
		
	 
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_last_item_movement
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_last_item_movement`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_last_item_movement`(IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Recalcular costo de inventario'
BEGIN

	

	

	select 

		i.itemID,

		i.itemNumber,

		i.barCode,

		i.`name`,

		i.cost,

		i.quantity ,

		p.typePriceID,

		p.price,

		p.percentage 

	from 

		tb_item i 

		inner join tb_price p on 

			i.itemID = p.itemID 

	where 

		i.itemID in (

					select 	

						distinct  

						c.componentItemID 	

					from 	 

						tb_transaction_master tm 

						inner join tb_transaction_master_detail c on 

							tm.transactionMasterID = c.transactionMasterID 

					where

						DATE(tm.createdOn) >= DATE_ADD(DATE(NOW()), INTERVAL -1 DAY)   

		);  

	



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_inventory_recalculate_cost
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_inventory_recalculate_cost`;
delimiter ;;
CREATE PROCEDURE `pr_inventory_recalculate_cost`(IN `prCompanyID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Recalcular costo de inventario'
BEGIN

	declare minItemID_ int default 0;

	declare maxItemID_ int default 0;

	declare minKardexID_ int default 0;

	declare maxKardexID_ int default 0;

	declare sign_ int default 0;

	declare quantity1_ decimal(19,9) default 0;

	declare quantity_ decimal(19,9) default 0;

	declare cost_ decimal(19,9) default 0;

	declare quantityT_ decimal(19,9) default 0;

	declare costT_ decimal(19,9) default 0;

	DECLARE varTiposCosto VARCHAR(50);

		

	CALL pr_core_get_parameter_value(prCompanyID,'INVENTORY_TYPE_COST',varTiposCosto);

	

	set minItemID_ = (select MIN(i.itemID) from tb_item i where i.isActive = 1);

	set maxItemID_ = (select MAX(i.itemID) from tb_item i where i.isActive = 1);

	

	while minItemID_ <= maxItemID_ and minItemID_ is not null do 

		set minKardexID_ = 0;

		set maxKardexID_ = 0;

		set sign_  = 0;

		set quantity1_ = 0;

		set quantity_ = 0;

		set cost_ = 0;

		set quantityT_ = 0;

		set costT_ = 0;

	

		set minKardexID_ = (select min(i.kardexID) from tb_kardex i where i.itemID = minItemID_);

		set maxKardexID_ = (select max(i.kardexID) from tb_kardex i where i.itemID = minItemID_);

				while minKardexID_ <= maxKardexID_ and minKardexID_ is not null do

						select p.transactionQuantity,p.transactionCost,p.sign into quantityT_,costT_,sign_ from tb_kardex p where p.kardexID = minKardexID_;

			

						update tb_kardex set 

				oldCost = cost_ , 

				oldCostWarehouse = cost_,				

				oldQuantity = quantity_ ,

				oldQuantityWarehouse = quantity_

			where 

			   kardexID = minKardexID_;

						

						if sign_ = 1 then 

				set quantity1_ = quantity_;

				set quantity_ 	= quantity_  + quantityT_;

				set cost_ 		= ROUND((((quantityT_ * costT_) + (quantity1_ * cost_)) /  quantity_),8); 

						else 

				set quantity_ 	= quantity_  - quantityT_;

				set cost_ 		= cost_;

			end if;

			

						update tb_kardex set 

				newCost = ROUND(cost_,8),

				newCostWarehouse = ROUND(cost_,8) , 

				newQuantity = ROUND(quantity_,8),

				newQuantityWarehouse = ROUND(quantity_,8) where kardexID = minKardexID_;

			

						set minKardexID_ = (select min(i.kardexID) from tb_kardex i where i.itemID = minItemID_ and i.kardexID > minKardexID_);

		end while; 

		

		update tb_item set cost = ROUND(cost_,8) where itemID = minItemID_;

		update tb_item_warehouse set cost = ROUND(cost_,8) where itemID = minItemID_;

				set minItemID_ = (select MIN(i.itemID) from tb_item i where i.isActive = 1 and i.itemID > minItemID_);	

	end while;

	



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_notification_buy
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_notification_buy`;
delimiter ;;
CREATE PROCEDURE `pr_notification_buy`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de ventas'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

  select 

		gru.nameTransaction ,		

		gru.itemNumber,

		gru.itemName,

	  sum(gru.quantity) as Cantidad,

		avg(gru.unitaryCost) as CostoPromedio,

		sum(gru.utilidad) as Utilidad  		

	from 

		(

			select 

				rx.userID,

				rx.nickname,

				rx.transactionNumber,

				rx.tipo,

				rx.transactionOn,

				rx.itemNumber,

				rx.itemName,

				rx.quantity,

				rx.unitaryCost,

				rx.unitaryPrice,

				(rx.unitaryCost * rx.quantity) as cost,

				(rx.unitaryPrice * rx.quantity) as amount,

				(rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity)    as utilidad,

				varCurrencyReporte,

				rx.currencyID,

				rx.exchangeRate,

				rx.`nameTransaction`

			from 

				(

						select 

							tt.`name` as nameTransaction ,

							usr.userID,

							usr.nickname,

							tm.transactionNumber,

							tc.name as tipo,

							tm.transactionOn,				

							i.itemNumber,

							i.name as itemName,

							tmd.quantity,

							tm.currencyID,

							tm.exchangeRate,

							case 

								when varCurrencyReporte = tm.currencyID then 

									tmd.unitaryPrice 

								when tm.exchangeRate > 1 then 

									tm.exchangeRate * (tmd.unitaryPrice)

								else 

									(1/tm.exchangeRate) * (tmd.unitaryPrice)

							end unitaryPrice,

							case 

								when varCurrencyCompras = varCurrencyReporte  then 				

									tmd.unitaryCost

								when tm.exchangeRate > 1 then 

									tm.exchangeRate *  tmd.unitaryCost														

								else 								

									(1/tm.exchangeRate) *   tmd.unitaryCost							

							end  unitaryCost 

							

							

						from 

							tb_transaction_master tm 

							inner join tb_transaction_master_detail tmd on 

								tm.companyID = tmd.companyID and 

								tm.transactionID = tmd.transactionID and 

								tm.transactionMasterID = tmd.transactionMasterID 

							inner join tb_transaction tt on 

								tm.transactionID = tt.transactionID 

							inner join tb_transaction_causal tc on 

								tm.transactionCausalID = tc.transactionCausalID 			

							inner join tb_user usr on 

								tm.createdBy = usr.userID 

							inner join tb_workflow_stage ws on 

								tm.statusID = ws.workflowStageID 		

							inner join tb_item i on 

								tmd.componentItemID = i.itemID 

						where

							tm.companyID = prCompanyID and 

							tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 

							tm.isActive = 1 and 

							ws.aplicable = 1 

						order by 

							tm.transactionMasterID asc, tmd.transactionMasterDetailID asc

							

				) rx

		) gru 

	group by 

		gru.nameTransaction ,

		gru.itemNumber,

		gru.itemName; 

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_planilla_create_transaction
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_planilla_create_transaction`;
delimiter ;;
CREATE PROCEDURE `pr_planilla_create_transaction`(IN `prCompanyID` INT, IN `prCalendarID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para crear la transaccion de planilla'
BEGIN

  DECLARE varTransactionPayRoll INT DEFAULT 28;   DECLARE varTransactionMasterID INT DEFAULT 0;

  DECLARE varTransactionNumber VARCHAR(50) DEFAULT '';

  DECLARE varNominaNumber VARCHAR(50) DEFAULT '';

  DECLARE varTransactionCausalID INT DEFAULT 0;

  DECLARE varUsuario INT DEFAULT 0;

  DECLARE varBranchID INT DEFAULT 0;

  DECLARE varComponentID INT DEFAULT 0;

  DECLARE varCurrencyID INT DEFAULT 0;

  DECLARE varCalendarID INT DEFAULT 0;

  DECLARE varStatusID INT DEFAULT 0;

  DECLARE varAmount DECIMAL(18,4) DEFAULT 0;

  DECLARE varComponentPayRoll INT DEFAULT 75;

  DECLARE varComponentEmployee INT DEFAULT 39;

  DECLARE varCurrencyID2 INT DEFAULT 0;

  DECLARE varExchangeRate DECIMAL(18,8);





    select c.transactionCausalID INTO varTransactionCausalID from tb_transaction_causal c where c.transactionID = varTransactionPayRoll and c.isActive = 1 and c.isDefault = 1 ; 



    CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_transaction_master_rrhh_payroll","statusID",varStatusID );	

 

  SELECT 

  		tm.createdBy,   		tm.createdAt,   		tm.currencyID,

  		tm.number

  INTO 

  		varUsuario,

  		varBranchID,

  		varCurrencyID,

  		varNominaNumber

  FROM

  		tb_employee_calendar_pay tm

  WHERE

  		tm.companyID = prCompanyID 

  		and tm.calendarID = prCalendarID;

  		

    SELECT 

  	   SUM((C.salary + C.commission)  - C.adelantos )

  INTO 

  		varAmount 

  FROM 

  		tb_employee_calendar_pay_detail C

  WHERE

  		C.calendarID = prCalendarID 

		and C.isActive = 1;	

  		

  		

    CALL pr_core_get_next_number(prCompanyID,'tb_transaction_master_rrhh_payroll',varBranchID,0,varTransactionNumber);		

	

  

	SET varCurrencyID2 = (case when varCurrencyID = 1 then 2 else 1 end);

  

    CALL pr_core_get_exchange_rate (prCompanyID,NOW(),varCurrencyID,varCurrencyID2,varExchangeRate);

	

  INSERT INTO tb_transaction_master (companyID,transactionID,transactionNumber,branchID,entityID,transactionCausalID,transactionOn,sign,componentID,currencyID,reference1,reference2,descriptionReference,statusID,amount,createdBy,createdAt,createdOn,createdIn,isActive,isApplied,statusIDChangeOn,journalEntryID,classID,areaID,sourceWarehouseID,targetWarehouseID,currencyID2,exchangeRate)  

  VALUES (prCompanyID,varTransactionPayRoll,varTransactionNumber,varBranchID,0,varTransactionCausalID,NOW(),0,varComponentPayRoll,varCurrencyID,prCalendarID,varNominaNumber,'reference1: calendarID,reference2: NominaNumber',varStatusID,varAmount,varUsuario,varBranchID,NOW(),'::1',1,0,NOW(),0,0,0,0,0,varCurrencyID2,varExchangeRate); 

  

  SET varTransactionMasterID = LAST_INSERT_ID();

  

  INSERT INTO tb_transaction_master_detail (companyID,transactionID,transactionMasterID,componentID,componentItemID,promotionID,amount,cost,unitaryAmount,quantity,discount,unitaryCost,unitaryPrice,descriptionReference,exchangeRateReference,catalogStatusID,inventoryStatusID,isActive)

  SELECT 

  	  prCompanyID,varTransactionPayRoll,varTransactionMasterID,varComponentEmployee,c.employeeID,0,

  	  c.salary,        	  c.commission,    	  c.adelantos,     	  0,0,0,0,'Amount: Salario,Cost: Comision,UnitaryAmount: Adelantos',

  	  varExchangeRate,

  	  0,

	  0, 

	  1  	  

  FROM 

  	 tb_employee_calendar_pay_detail c

  WHERE

  	  c.calendarID = prCalendarID 

  	  and c.isActive = 1;

  

  

   	CALL pr_concept_helper_calendarpay (prCompanyID,varTransactionPayRoll,varTransactionMasterID);

	  

  

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_planilla_remove_adelanto
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_planilla_remove_adelanto`;
delimiter ;;
CREATE PROCEDURE `pr_planilla_remove_adelanto`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Eliminar el adelanto de planilla'
BEGIN



  DECLARE varAmount NUMERIC(18,2) DEFAULT 0;

  DECLARE varAdelantosActuales NUMERIC(18,2) DEFAULT 0;

  DECLARE varCalendarDetailID INT DEFAULT 0;

  DECLARE varEmployeeID INT DEFAULT 0;

  DECLARE varAccountingCycleID INT DEFAULT 0;

  DECLARE varTypeIDNomina INT DEFAULT 0;

  DECLARE varCurrencyID INT DEFAULT 0;

	



	SELECT 

		tm.currencyID, 		tm.reference1, 		tm.reference2, 		tm.entityID, 			tm.amount 			INTO 

		varCurrencyID,

		varTypeIDNomina,

		varAccountingCycleID,

		varEmployeeID,

		varAmount

	FROM 

		tb_transaction_master tm

	WHERE

		tm.companyID = prCompanyID 

		and tm.transactionID = prTransactionID 

		and tm.transactionMasterID = prTransactionMasterID

		and tm.isActive = 1;  

		

		

	select 

		pd.adelantos ,

		pd.calendarDetailID 

	INTO 

		varAdelantosActuales,

		varCalendarDetailID

	from 

		tb_employee_calendar_pay p 

		inner join tb_employee_calendar_pay_detail pd on 

			p.calendarID = pd.calendarID 

	where

		p.isActive = 1 

		and p.typeID = varTypeIDNomina 

		and p.currencyID = varCurrencyID 

		and p.accountingCycleID = varAccountingCycleID

		and pd.isActive = 1 

		and pd.employeeID = varEmployeeID;

		

		update tb_employee_calendar_pay_detail set adelantos = (varAdelantosActuales - varAmount) where calendarDetailID = varCalendarDetailID; 

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_planilla_update_adelanto
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_planilla_update_adelanto`;
delimiter ;;
CREATE PROCEDURE `pr_planilla_update_adelanto`(IN `prCompanyID` INT, IN `prTransactionID` INT, IN `prTransactionMasterID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para actualizar los adelantos en la planilla'
BEGIN

 

  DECLARE varAmount NUMERIC(18,2) DEFAULT 0;

  DECLARE varAdelantosActuales NUMERIC(18,2) DEFAULT 0;

  DECLARE varCalendarDetailID INT DEFAULT 0;

  DECLARE varEmployeeID INT DEFAULT 0;

  DECLARE varAccountingCycleID INT DEFAULT 0;

  DECLARE varTypeIDNomina INT DEFAULT 0;

  DECLARE varCurrencyID INT DEFAULT 0;

	



	SELECT 

		tm.currencyID, 		tm.reference1, 		tm.reference2, 		tm.entityID, 			tm.amount 			INTO 

		varCurrencyID,

		varTypeIDNomina,

		varAccountingCycleID,

		varEmployeeID,

		varAmount

	FROM 

		tb_transaction_master tm

	WHERE

		tm.companyID = prCompanyID 

		and tm.transactionID = prTransactionID 

		and tm.transactionMasterID = prTransactionMasterID

		and tm.isActive = 1;  

		

		

	select 

		pd.adelantos ,

		pd.calendarDetailID 

	INTO 

		varAdelantosActuales,

		varCalendarDetailID

	from 

		tb_employee_calendar_pay p 

		inner join tb_employee_calendar_pay_detail pd on 

			p.calendarID = pd.calendarID 

	where

		p.isActive = 1 

		and p.typeID = varTypeIDNomina 

		and p.currencyID = varCurrencyID 

		and p.accountingCycleID = varAccountingCycleID

		and pd.isActive = 1 

		and pd.employeeID = varEmployeeID;

		

		update tb_employee_calendar_pay_detail set adelantos = (varAdelantosActuales + varAmount) where calendarDetailID = varCalendarDetailID; 

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_purchase_get_report_purchase_detail
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_purchase_get_report_purchase_detail`;
delimiter ;;
CREATE PROCEDURE `pr_purchase_get_report_purchase_detail`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE,IN prProviderID INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de Compras'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

	

	select 

		rx.userID,

		rx.nickname,

		rx.transactionNumber,

		rx.tipo,

		rx.transactionOn,

		rx.createdOn,

		DAYOFMONTH(rx.createdOn) as dayOfMonth,

		rx.providerNumber,

		rx.legalName,

		rx.zone,

		rx.itemNumber,

		rx.itemName,

		rx.nameCategory,

		rx.quantity,

		rx.unitaryCost,		

		(rx.unitaryCost * rx.quantity) as cost,	

		varCurrencyReporte,

		rx.currencyID,

		rx.exchangeRate

	from 

		(

				select 

					usr.userID,

					usr.nickname,

					tm.transactionNumber,

					tc.name as tipo,

					tm.transactionOn,

					pro.providerNumber,

					l.legalName,

					'' as zone,

					i.itemNumber,

					i.name as itemName,

					cat.`name` as nameCategory,

					tmd.quantity,

					tm.currencyID,

					tm.exchangeRate,

					tm.createdOn,

					

					case 

						when varCurrencyCompras = varCurrencyReporte  then 				

							tmd.unitaryCost

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  tmd.unitaryCost														

						else 								

						  (1/tm.exchangeRate) *   tmd.unitaryCost							

					end  unitaryCost 					

					

				from 

					tb_transaction_master tm 

					inner join tb_transaction_master_detail tmd on 

						tm.companyID = tmd.companyID and 

						tm.transactionID = tmd.transactionID and 

						tm.transactionMasterID = tmd.transactionMasterID 

					inner join tb_transaction_causal tc on 

						tm.transactionCausalID = tc.transactionCausalID 

					inner join tb_provider pro on 

						pro.entityID = tm.entityID 

					inner join tb_legal l on 

						pro.entityID = l.entityID 

					inner join tb_user usr on 

						tm.createdBy = usr.userID 

					inner join tb_workflow_stage ws on 

						tm.statusID = ws.workflowStageID 										

					inner join tb_item i on 

						tmd.componentItemID = i.itemID 

					inner join tb_item_category cat on 

						cat.inventoryCategoryID = i.inventoryCategoryID 

				where

					tm.companyID = prCompanyID and 

					tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 

					tm.transactionID in (21  ) and 

					tm.isActive = 1 and 

					tmd.isActive = 1 and 

					ws.aplicable = 1 and 

					(

						(tm.entityID = prProviderID and prProviderID != 0) or

						(prProviderID = 0 )

					)

				order by 

					tm.transactionMasterID asc, tmd.transactionMasterDetailID asc

					

		) rx;

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_purchase_get_report_purchase_taller
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_purchase_get_report_purchase_taller`;
delimiter ;;
CREATE PROCEDURE `pr_purchase_get_report_purchase_taller`(IN `prUserID` int,IN `prTokenID` varchar(50),IN `prCompanyID` int,IN `prEmployerID` varchar(150), IN prStartOn DATETIME, IN prEndOn DATETIME , IN prCustomerCode VARCHAR(50))
BEGIN
	
	
	drop temporary table if exists tb_tmp_split;
	drop temporary table if exists tb_tmp_split2;
	create temporary table tb_tmp_split( val char(255) );
	create temporary table tb_tmp_split2( val char(255) );
	set @sql = concat("insert into tb_tmp_split (val) values ('", replace(prEmployerID, ",", "'),('"),"');");
	prepare stmt1 from @sql;
	execute stmt1;
	
	insert into tb_tmp_split2 (val) 
	select zu.val from tb_tmp_split zu; 
	
	
	
	select 
		estado.`name` as Estado,	
		CONCAT(u.firstName) as firstName,
		u.entityID ,
		count(*) as Cantidad 	
	from 
		tb_transaction_master c 
		inner join tb_naturales u on 
			c.entityIDSecondary = u.entityID 
		inner join tb_catalog_item estado on 
			estado.catalogItemID = c.areaID 
		inner join tb_employee emp on 
			emp.entityID = u.entityID
		inner join tb_customer cus on 
			cus.entityID = c.entityID 
	where 
		c.transactionID = 40   and 
		c.isActive = 1 and 
		c.companyID = prCompanyID and 
		c.createdOn between prStartOn and  prEndOn and  
		(
		   ( prCustomerCode = '' ) or 
			 (
			   prCustomerCode != ''  and 
				 cus.customerNumber = prCustomerCode 
			 )
		) and 
		(
			 (
			  0 IN ( SELECT TUX.val FROM tb_tmp_split2 TUX )
			 )
			 or 
			 (
				u.entityID IN (SELECT TU.val FROM tb_tmp_split TU )
			 )
			 
		) 
	group by 
		estado.`name`,
		u.firstName,
		u.entityID 
	order by 
		1,2 ;
		
		
	select 
		c.transactionNumber,
		c.transactionOn,
		cus.customerNumber,
		nat.firstName ,
		ep.employeNumber, 
		natp.firstName as firstNameEmployer, 
		c.note ,
		c.reference2,
		c.reference3,
		c.reference1 ,
		tmi.reference2 as modelo ,
		tmi.referenceClientName ,
		estado_equipo.`name` as estado_equipo ,
		articulo.`name` as articulo ,
		
		(
        SELECT 
					GROUP_CONCAT(tmd.reference1 SEPARATOR ', ')
        FROM 
					tb_transaction_master_detail  tmd
        WHERE 
					tmd.transactionMasterID = c.transactionMasterID 
    ) AS detalles 
		
	from 
		tb_transaction_master c 
		inner join tb_transaction_master_info tmi on 
			tmi.transactionMasterID = c.transactionMasterID 
		inner join tb_customer cus on 
			cus.entityID = c.entityID 
		inner join tb_naturales nat on 
			cus.entityID = nat.entityID  
		inner join tb_naturales natp on 
			natp.entityID = c.entityIDSecondary 
		inner join tb_employee ep on 
			ep.entityID = natp.entityID 
		
		inner join tb_catalog_item estado_equipo on 
			estado_equipo.catalogItemID = c.areaID 
		inner join tb_catalog_item articulo on 
			articulo.catalogItemID = tmi.routeID 
	where 
		c.transactionID = 40  and 
		c.isActive = 1 and 
		c.createdOn between prStartOn and  prEndOn   and 
		(
		   ( prCustomerCode = '' ) or 
			 (
			   prCustomerCode != ''  and 
				 cus.customerNumber = prCustomerCode 
			 )
		) 		
	order by 
		c.transactionMasterID desc ;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_sales_get_report_sales_by_client
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_by_client`;
delimiter ;;
CREATE PROCEDURE `pr_sales_get_report_sales_by_client`(IN `prCompanyID` INT,

	IN `prTokenID` VARCHAR(50),

	IN `prUserID` INT,

	IN `prStartOn` DATE,

	IN `prEndOn` DATE,

	IN `prInventoryCategoryID` INT,

	IN `prWarehouse` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de ventas'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;		

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	



	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);



	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

					

	select 



		uv.customerNumber,

		uv.legalName,			

		SUM(uv.quantity) as quantity,

		SUM((uv.unitaryCost * uv.quantity)) as cost,

		SUM((uv.unitaryPrice * uv.quantity)) as amount,

		SUM((uv.unitaryPrice * uv.quantity) + (uv.iva * uv.quantity))  as amountConIva,

		SUM((uv.unitaryPrice * uv.quantity) - (uv.unitaryCost * uv.quantity) )   as utilidad,

		SUM((uv.iva)) as iva,

		SUM((uv.quantity * uv.iva)) as ivaTotal,

		varCurrencyReporte,

		uv.currencyID,

		SUM(uv.amountCommision ) as amountCommision,		

		SUM(uv.pagoConPuntos) as pagoConPuntos

	from 

		(				

					select 

						rx.userID,

						rx.nickname,

						rx.transactionNumber,

						rx.employerName,

						rx.tipo,

						rx.transactionOn,

						rx.createdOn,

						DAYOFMONTH(rx.createdOn) as dayOfMonth,

						rx.customerNumber,

						rx.legalName,

						rx.zone,

						rx.itemNumber,

						rx.itemName,

						rx.itemNameLog,

						rx.phoneNumber,

						rx.Agent,

						rx.Commentary,

						rx.nameCategory,

						rx.quantity,

						rx.unitaryCost,

						rx.unitaryPrice,

						(rx.unitaryCost * rx.quantity) as cost,

						(rx.unitaryPrice * rx.quantity) as amount,

						(rx.unitaryPrice * rx.quantity) + (rx.iva * rx.quantity)  as amountConIva,

						(rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity)    as utilidad,

						(rx.iva) as iva,

						(rx.quantity * rx.iva) as ivaTotal,

						varCurrencyReporte,

						rx.currencyID,

						rx.exchangeRate,

						rx.amountCommision,						

						rx.pagoConPuntos 

					from 

						(

								select 

									usr.userID,

									usr.nickname,

									tm.transactionNumber,

									IFNULL(nat_emp.firstName,'') as employerName,

									tc.name as tipo,

									tm.transactionOn,

									cus.customerNumber,

									l.legalName,

									ci.name as zone,

									i.itemNumber,

									i.name as itemName,

									tmd.itemNameLog,

									cat.`name` as nameCategory,

									cus.phoneNumber,

									'' AS Agent,

									'' as Commentary,

									tmd.quantity,

									tm.currencyID,

									tm.exchangeRate,

									tm.createdOn,									

									case 

										when varCurrencyReporte = tm.currencyID then 

											tmd.unitaryPrice 

										when tm.exchangeRate > 1 then 

											tm.exchangeRate * (tmd.unitaryPrice)

										else 

											(1/tm.exchangeRate) * (tmd.unitaryPrice)

									end unitaryPrice,

									case 

										when varCurrencyReporte = tm.currencyID  then 			

											tmd.unitaryCost

										when tm.exchangeRate > 1 then 

											tm.exchangeRate *  tmd.unitaryCost

										else 

											(1/tm.exchangeRate) *   tmd.unitaryCost					

									end  unitaryCost ,

									case 

										when varCurrencyReporte = tm.currencyID  then 			

											IFNULL(tmd.tax1,0)

										when tm.exchangeRate > 1 then 

											tm.exchangeRate *  IFNULL(tmd.tax1,0)

										else 								

											(1/tm.exchangeRate) *   IFNULL(tmd.tax1,0)

									end as iva ,

									case 

										when varCurrencyReporte = tm.currencyID  then 			

											IFNULL(amountCommision,0)

										when tm.exchangeRate > 1 then 

											tm.exchangeRate *  IFNULL(amountCommision,0)

										else 								

											(1/tm.exchangeRate) *   IFNULL(amountCommision,0)

									end  as amountCommision , 									

									IFNULL(tmi.receiptAmountPoint,0) / 0.03 as pagoConPuntos															

								from 

									tb_transaction_master tm  					

									inner join tb_transaction_master_detail tmd on 

										tm.companyID = tmd.companyID and 

										tm.transactionID = tmd.transactionID and 

										tm.transactionMasterID = tmd.transactionMasterID 

									inner join tb_transaction_causal tc on 

										tm.transactionCausalID = tc.transactionCausalID 

									inner join tb_customer cus on 

										tm.entityID = cus.entityID 

									inner join tb_legal l on 

										cus.entityID = l.entityID 

									inner join tb_user usr on 

										tm.createdBy = usr.userID 

									inner join tb_workflow_stage ws on 

										tm.statusID = ws.workflowStageID 

									inner join tb_transaction_master_info tmi on 

										tm.companyID = tmi.companyID and 

										tm.transactionID = tmi.transactionID and 

										tm.transactionMasterID = tmi.transactionMasterID 

									inner join tb_catalog_item ci on 

										tmi.zoneID = ci.catalogItemID 

									inner join tb_item i on 

										tmd.componentItemID = i.itemID 

									inner join tb_item_category cat on 

										cat.inventoryCategoryID = i.inventoryCategoryID 

									left join tb_naturales nat_emp on 

										nat_emp.entityID = tm.entityIDSecondary 

								where  					

									tm.companyID = prCompanyID and 

									tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 

									tm.isActive = 1 and 

									tmd.isActive = 1 and 

									ws.aplicable = 1 and 

									(



										prInventoryCategoryID = 0 

										or 

										(prInventoryCategoryID != 0 and i.inventoryCategoryID = prInventoryCategoryID )

									) and 

									(

										prWarehouse = 0 

										or 

										(

											prWarehouse != 0 and 

											tm.sourceWarehouseID =  prWarehouse 

										)

									)

								order by 

									tm.transactionMasterID asc, tmd.transactionMasterDetailID asc								



						) rx		



		) uv

		GROUP BY 

			uv.customerNumber, uv.legalName;



		



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_sales_get_report_sales_by_payment
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_by_payment`;
delimiter ;;
CREATE PROCEDURE `pr_sales_get_report_sales_by_payment`(IN `prCompanyID` INT, 	IN `prUserID` INT, IN `prTokenID` VARCHAR(50) , IN `prDateTimeStart` VARCHAR(50),   IN `prDateTimeFinish` VARCHAR(50))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de ventas por metodo de pago'
BEGIN

	DECLARE vStart DATETIME;
	DECLARE vEnd DATETIME;
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);	
	DECLARE currencyIDNameTarget VARCHAR(250);	
	DECLARE currencyIDNameSource VARCHAR(250);
	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;
	DECLARE convert_ VARCHAR(50);	
	DECLARE prFlavorID INT DEFAULT 0;
	
 

	SET vStart = STR_TO_DATE(prDateTimeStart, '%Y-%m-%d %H:%i:%s');
	SET vEnd   = STR_TO_DATE(prDateTimeFinish, '%Y-%m-%d %H:%i:%s');
	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);
	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_); 
	CALL pr_core_get_parameter_value(prCompanyID, "ACCOUNTING_CURRENCY_NAME_REPORT_CONVERT", convert_);
	SET prFlavorID 								= (SELECT flavorID FROM tb_company c where c.companyID = prCompanyID);



	SELECT 
			COALESCE(
				  b.name, 
					CASE 
							WHEN tipo = 'EfectivoCordoba' THEN 'EFECTIVO CORDOBA'
							WHEN tipo = 'EfectivoDolar'   THEN 'EFECTIVO DOLAR'
							WHEN tipo = 'Puntos'          THEN 'PUNTOS'
					END
			) AS Banco,
			
			
			ROUND(SUM(CASE WHEN tipo = 'TransferenciaCordoba' THEN monto ELSE 0 END),2) AS `Transferencia Cordoba`,
			ROUND(SUM(CASE WHEN tipo = 'TransferenciaDolar'   THEN monto ELSE 0 END),2) AS `Transferencia Dólar`,
			ROUND(SUM(CASE WHEN tipo = 'TarjetaCordoba'       THEN monto ELSE 0 END),2) AS `Tarjeta Cordoba`,
			ROUND(SUM(CASE WHEN tipo = 'TarjetaDolar'         THEN monto ELSE 0 END),2) AS `Tarjeta Dólar`,
			ROUND(SUM(CASE WHEN tipo = 'EfectivoCordoba'      THEN monto ELSE 0 END),2) AS `Efectivo Cordoba`,
			ROUND(SUM(CASE WHEN tipo = 'EfectivoDolar'        THEN monto ELSE 0 END),2) AS `Efectivo Dólar`,
			ROUND(SUM(CASE WHEN tipo = 'Puntos'               THEN monto ELSE 0 END),2) AS Puntos,

			-- Totales por fila
			ROUND(SUM(CASE WHEN moneda = 'Cordoba' THEN monto ELSE 0 END),2) AS `Total Cordoba`,
			ROUND(SUM(CASE WHEN moneda = 'Dolar'   THEN monto ELSE 0 END),2) AS `Total Dólar`

	FROM 
		(
				-- Normalizar monto recibido
				SELECT 
					tmi.transactionMasterID, 
					CONVERT(
						fn_translate_transaction_master_info_amounts( 
							prCompanyID, prFlavorID, tmi.transactionID, currencyIDNameCompra, 
							currencyIDNameReporte, convert_, tm.currencyID, exchangeRate_, tmi.receiptAmount, 
							tmi.receiptAmountDol,  'Amount'
						), DECIMAL(10,2)
					)  AS monto, 
					'EfectivoCordoba' AS tipo, 
					'Cordoba' AS moneda, 
					NULL AS bankID
				FROM 
					tb_transaction_master_info tmi
					INNER JOIN tb_transaction_master tm
						ON tm.transactionMasterID = tmi.transactionMasterID
				WHERE 
					tm.isActive = 1 and 
					tm.transactionCausalID IN (21,23) /*contado*/ and 
					tm.statusID = 67 /*aplicada*/  
						
				UNION ALL
				
				-- Normalizar monto recibido en moneda extranjera 
				SELECT 
					tmi.transactionMasterID, 
					CONVERT(
						fn_translate_transaction_master_info_amounts( 
							prCompanyID, prFlavorID, tmi.transactionID, currencyIDNameCompra, 
							currencyIDNameReporte, convert_, tm.currencyID, 
							exchangeRate_, tmi.receiptAmount, tmi.receiptAmountDol,  'AmountExt'
						), DECIMAL(10,2)
					)  AS monto,    
					'EfectivoDolar',   
					'Dolar',   
					NULL AS bankID
				FROM 
					tb_transaction_master_info tmi
					INNER JOIN tb_transaction_master tm
					ON tm.transactionMasterID = tmi.transactionMasterID
				WHERE 
				  tm.isActive = 1 and 
					tm.transactionCausalID IN (21,23) /*contado*/ and 
					tm.statusID = 67 /*aplicada*/ 
					
				UNION ALL
				
				-- Normalizar monto recibido en tarjeta Cordoba 
				SELECT 
					tmi.transactionMasterID, 
					CONVERT(
						fn_translate_transaction_master_info_amounts( 
							prCompanyID, prFlavorID, tmi.transactionID, 
							currencyIDNameCompra, currencyIDNameReporte, 
							convert_, tm.currencyID, exchangeRate_, 
							tmi.receiptAmountCard, tmi.receiptAmountCardDol,  'Amount'
						), DECIMAL(10,2)
					)  AS monto,   
					'TarjetaCordoba',  'Cordoba', 
					case 
						when tm.currencyID = 1 /*cordoba*/ then 
							tmi.receiptAmountCardBankID
						when tm.currencyID = 2 /*dolares*/ then 
							tmi.receiptAmountCardBankDolID
					end as bankID 
				FROM 
					tb_transaction_master_info tmi
					INNER JOIN tb_transaction_master tm
						ON tm.transactionMasterID = tmi.transactionMasterID
				WHERE 
					tm.isActive = 1 and 
					tm.transactionCausalID IN (21,23) /*contado*/ and 
					tm.statusID = 67 /*aplicada*/ 
					
				UNION ALL
				
				-- Normalizar monto recibido en tarjeta Dolares 
				SELECT 
					tmi.transactionMasterID, 
					CONVERT(
						fn_translate_transaction_master_info_amounts( 
							prCompanyID, prFlavorID, tmi.transactionID, currencyIDNameCompra, 
							currencyIDNameReporte, convert_, tm.currencyID, exchangeRate_, 
							tmi.receiptAmountCard, tmi.receiptAmountCardDol,  'AmountExt'
						), DECIMAL(10,2)
					)  AS monto, 
					'TarjetaDolar',    'Dolar',   
					case 
						when tm.currencyID = 1 /*cordoba*/ then 
							tmi.receiptAmountCardBankID
						when tm.currencyID = 2 /*dolares*/ then 
							tmi.receiptAmountCardBankDolID
					end as bankID 
				FROM 
					tb_transaction_master_info tmi
					INNER JOIN tb_transaction_master tm
						ON tm.transactionMasterID = tmi.transactionMasterID
				WHERE 
					tm.isActive = 1 and 
					tm.transactionCausalID IN (21,23) /*contado*/ and 
					tm.statusID = 67 /*aplicada*/ 
					
					
				UNION ALL
				
				-- Normalizar monto recibido en transferencia Cordoba
				SELECT 
					tmi.transactionMasterID, 
					CONVERT(
						fn_translate_transaction_master_info_amounts( 
							prCompanyID, prFlavorID, 
							tmi.transactionID, currencyIDNameCompra, 
							currencyIDNameReporte, convert_, 
							tm.currencyID, exchangeRate_, tmi.receiptAmountBank, tmi.receiptAmountBankDol,  'Amount'
						), DECIMAL(10,2)
					)  AS monto,   
					'TransferenciaCordoba',
					'Cordoba', 
					
					case 
						when tm.currencyID = 1 /*cordoba*/ then 
							tmi.receiptAmountBankID
						when tm.currencyID = 2 /*dolares*/ then 
							tmi.receiptAmountBankDolID
					end as bankID 
					
				FROM 
					tb_transaction_master_info tmi
					INNER JOIN tb_transaction_master tm
						ON tm.transactionMasterID = tmi.transactionMasterID
				WHERE 
					tm.isActive = 1 and 
					tm.transactionCausalID IN (21,23) /*contado*/ and 
					tm.statusID = 67 /*aplicada*/ 
					
					
				UNION ALL
				
				-- Normalizar monto recibido en transferencia Dolares 
				SELECT 
					tmi.transactionMasterID, 
					CONVERT(
						fn_translate_transaction_master_info_amounts( 
							prCompanyID, prFlavorID, tmi.transactionID, currencyIDNameCompra, 
							currencyIDNameReporte, convert_, tm.currencyID, exchangeRate_, 
							tmi.receiptAmountBank, tmi.receiptAmountBankDol,  'AmountExt'
						), DECIMAL(10,2)
					)  AS monto,
					'TransferenciaDolar', 'Dolar',   					
					case 
						when tm.currencyID = 1 /*cordoba*/ then 
							tmi.receiptAmountBankID
						when tm.currencyID = 2 /*dolares*/ then 
							tmi.receiptAmountBankDolID
					end as bankID 
				FROM 
					tb_transaction_master_info tmi
					INNER JOIN tb_transaction_master tm
						ON tm.transactionMasterID = tmi.transactionMasterID
				WHERE 
					tm.isActive = 1 and 
					tm.transactionCausalID IN (21,23) /*contado*/ and 
					tm.statusID = 67 /*aplicada*/ 
					
					
				UNION ALL
				
				
				-- Normalizar monto recibido en puntos 
				SELECT 
						tmi.transactionMasterID, 
						tmi.receiptAmountPoint, 
						'Puntos', 'Cordoba', 
						NULL as bankID 
				FROM 
					tb_transaction_master_info tmi
					INNER JOIN tb_transaction_master tm
						ON tm.transactionMasterID = tmi.transactionMasterID
						
		) pagos
		INNER JOIN tb_transaction_master tm 
				ON tm.transactionMasterID = pagos.transactionMasterID
		LEFT JOIN tb_bank b 
				ON b.bankID = pagos.bankID 
	WHERE tm.companyID = prCompanyID and tm.isApplied = 1 
		AND tm.isActive = 1 
		AND tm.transactionID = 19
		AND pagos.monto > 0 
		AND tm.createdOn BETWEEN vStart and vEnd 
	GROUP BY 
		Banco WITH ROLLUP;
	
	
	
	SELECT 
		c.transactionNumber as Factura ,
		cur.`name` as Moneda, 
		bank.`name` as Banco ,
		'Tarjeta' as Tipo,		
		inf.receiptAmountCard as `Pago con Tarjeta` ,
		inf.receiptAmountCard * (bank.comisionPos / 100)  as `Pago con Tarjeta Comision`
	FROM 
		tb_transaction_master c  
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = c.statusID 
		inner join tb_currency cur on 
			cur.currencyID = c.currencyID 
		inner join tb_transaction_master_info inf on 
			inf.transactionMasterID = c.transactionMasterID  
		inner join tb_bank bank on 
			bank.bankID = inf.receiptAmountCardBankID 
	WHERE 
		c.isActive = 1 and 
		c.transactionID = 19 /*factura*/ and 
		c.companyID = prCompanyID and c.isApplied = 1 and  
		ws.aplicable = 1 and 
		c.createdOn BETWEEN vStart and vEnd and 
		(
			inf.receiptAmountCard > 0  			
		) ;
	
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_sales_get_report_sales_comisssion_summary
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_comisssion_summary`;
delimiter ;;
CREATE PROCEDURE `pr_sales_get_report_sales_comisssion_summary`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE,IN prInventoryCategoryID INT, IN prWarehouse INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de ventas'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

	

	select 		

		rx.employerName,		

		rx.amountCommision 

	from 

		(

				select 		

					nat.firstName as employerName,	

					sum(tmd.amountCommision) as amountCommision  

				from  

					tb_transaction_master tm 

					inner join tb_transaction_master_detail  tmd on 

						tm.transactionMasterID = tmd.transactionMasterID 

					inner join tb_workflow_stage ws on 

						ws.workflowStageID = tm.statusID 

					inner join tb_naturales nat on 

						tm.entityIDSecondary = nat.entityID 

				where 

					tm.transactionID = 19 and 

					tm.isActive = 1 and 

					tmd.isActive = 1 and 

					tm.transactionOn BETWEEN prStartOn and prEndOn and 

					ws.aplicable = 1 

				group by 

					nat.firstName 

					

		) rx; 



		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_sales_get_report_sales_day
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_day`;
delimiter ;;
CREATE PROCEDURE `pr_sales_get_report_sales_day`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de ventas'
BEGIN



	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;	

	

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

	

	select 

		ul.itemNumber,

		ul.itemName,

		ul.nameCategory,

		ul.quantity,

		ul.unitaryCost,

		ul.unitaryPrice,

		ul.cost,

		ul.amount,

		ul.utilidad

	from 

		(

			select 		

				rx.itemNumber,

				lower(rx.itemName) as itemName,		

				rx.nameCategory,

				sum(rx.quantity) as quantity,

				avg(rx.unitaryCost) as unitaryCost,

				avg(rx.unitaryPrice) as unitaryPrice,

				avg((rx.unitaryCost * rx.quantity)) as cost,

				sum((rx.unitaryPrice * rx.quantity)) as amount,

				sum((rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity))    as utilidad

			

			from 

				(

						select 

							usr.userID,

							usr.nickname,

							tm.transactionNumber,

							tc.name as tipo,

							tm.transactionOn,

							cus.customerNumber,

							l.legalName,

							ci.name as zone,

							i.itemNumber,

							i.name as itemName,

							cat.`name` as nameCategory,

							tmd.quantity,

							tm.currencyID,

							tm.exchangeRate,

							

							

							case 

								when varCurrencyReporte = tm.currencyID then 

									tmd.unitaryPrice 

								when tm.exchangeRate > 1 then 

									tm.exchangeRate * (tmd.unitaryPrice)

								else 

									(1/tm.exchangeRate) * (tmd.unitaryPrice)

							end unitaryPrice,

							

							

							

							case 

								when varCurrencyCompras = varCurrencyReporte  then 				

									tmd.unitaryCost

								when tm.exchangeRate > 1 then 

									tm.exchangeRate *  tmd.unitaryCost														

								else 								

									(1/tm.exchangeRate) *   tmd.unitaryCost							

							end  unitaryCost 

							

							

						from 

							tb_transaction_master tm 

							inner join tb_transaction_master_detail tmd on 

								tm.companyID = tmd.companyID and 

								tm.transactionID = tmd.transactionID and 

								tm.transactionMasterID = tmd.transactionMasterID 

							inner join tb_transaction_causal tc on 

								tm.transactionCausalID = tc.transactionCausalID 

							inner join tb_customer cus on 

								tm.entityID = cus.entityID 

							inner join tb_legal l on 

								cus.entityID = l.entityID 

							inner join tb_user usr on 

								tm.createdBy = usr.userID 

							inner join tb_workflow_stage ws on 

								tm.statusID = ws.workflowStageID 

							inner join tb_transaction_master_info tmi on 

								tm.companyID = tmi.companyID and 

								tm.transactionID = tmi.transactionID and 

								tm.transactionMasterID = tmi.transactionMasterID 

							inner join tb_catalog_item ci on 

								tmi.zoneID = ci.catalogItemID 

							inner join tb_item i on 

								tmd.componentItemID = i.itemID 

							inner join tb_item_category cat on 

								cat.inventoryCategoryID = i.inventoryCategoryID 

						where

						  tm.transactionID in (19 /*factura*/ ) and 

							tm.companyID = prCompanyID and 

							tm.createdOn between prStartOn  and prEndOn   and 

							tm.isActive = 1 and 

							tmd.isActive = 1 and 

							ws.aplicable = 1 

						order by 

							tm.transactionMasterID asc, tmd.transactionMasterDetailID asc

							

				) rx

			GROUP BY 

				rx.itemNumber,

				rx.itemName,

				rx.nameCategory 

		) ul 

	order by 

		ul.nameCategory,ul.itemName ;

	

		

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_sales_get_report_sales_detail
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_detail`;
delimiter ;;
CREATE PROCEDURE `pr_sales_get_report_sales_detail`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), 	IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE,IN prInventoryCategoryID INT, IN prWarehouse INT ,
IN prUserIDCreatedBy INT,  IN prTransactionCausalName VARCHAR(50))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de ventas'
BEGIN

	DECLARE varCurrencyCompras INT DEFAULT 0;	
	DECLARE varCurrencyReporte INT DEFAULT 0;	
	
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);	
	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	
	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		
	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		
		
	
	select 
		rx.userID,
		rx.nickname,
		rx.transactionNumber,
		rx.employerName,
		rx.tipo,
		rx.transactionOn,
		rx.createdOn,
		DAYOFMONTH(rx.createdOn) as dayOfMonth,
		rx.customerNumber,
		rx.currencyName,
		rx.note,
		rx.legalName,
		rx.zone,
		rx.itemNumber,
		rx.itemName,
		rx.itemNameLog,
		rx.phoneNumber,
		rx.Agent,
		rx.Commentary,
		rx.nameCategory,
		rx.quantity,
		rx.unitaryCost,
		rx.unitaryPrice,
		(rx.unitaryCost * rx.quantity) as cost,
		(rx.unitaryPrice * rx.quantity) as amount,
		(rx.unitaryPrice * rx.quantity) + (rx.iva * rx.quantity)  as amountConIva,
		(rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity)    as utilidad,
		(((rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity)) /  (rx.unitaryCost * rx.quantity)) * 100  as utilidad_porcentual,
		(rx.iva) as iva,
		(rx.quantity * rx.iva) as ivaTotal,
		varCurrencyReporte,
		rx.currencyID,
		rx.exchangeRate,
		rx.amountCommision 
	from 
		(
				select 
					usr.userID,
					usr.nickname,
					tm.transactionNumber,
					IFNULL(CONCAT(nat_emp.firstName,' ',nat_emp.lastName,' |',usr.nickname ),'') as employerName,
					tc.name as tipo,
					tm.transactionOn,
					cus.customerNumber,
					concat(nat_cus.firstName,' ', nat_cus.lastName) as legalName ,
					ci.name as zone,
					i.itemNumber,
					i.name as itemName,
					tmd.itemNameLog,
					cat.`name` as nameCategory,
					cus.phoneNumber,
					'' AS Agent,
					'' as Commentary,
					tmd.quantity as quantity,
					tm.currencyID,
					tm.exchangeRate,
					tm.createdOn,
					cur.`name` as currencyName,
					tm.note as note,
					
					case 
						when varCurrencyReporte = tm.currencyID then 
							tmd.unitaryPrice 
						when tm.exchangeRate > 1 then 
							tm.exchangeRate * (tmd.unitaryPrice)
						else 
							(1/tm.exchangeRate) * (tmd.unitaryPrice)
					end unitaryPrice,
					
					
					
					case 
						when varCurrencyReporte = tm.currencyID  then 				
							tmd.unitaryCost * ifnull(tmd.skuQuantity ,0) 
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  tmd.unitaryCost	* ifnull(tmd.skuQuantity ,0) 												
						else 								
						  (1/tm.exchangeRate) *   tmd.unitaryCost	* ifnull(tmd.skuQuantity ,0) 				
					end  unitaryCost ,
					
					
					case 
						when varCurrencyReporte = tm.currencyID  then 				
							IFNULL(tmd.tax1,0)
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  IFNULL(tmd.tax1,0)
						else 								
						  (1/tm.exchangeRate) *   IFNULL(tmd.tax1,0)
					end as iva ,
					
					
					case 
						when varCurrencyReporte = tm.currencyID  then 				
							IFNULL(amountCommision,0)
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  IFNULL(amountCommision,0)
						else 								
						  (1/tm.exchangeRate) *   IFNULL(amountCommision,0)
					end  as amountCommision 
					
				from 
					tb_transaction_master tm  					
					inner join tb_transaction_master_detail tmd on 
						tm.companyID = tmd.companyID and 
						tm.transactionID = tmd.transactionID and 
						tm.transactionMasterID = tmd.transactionMasterID 
					inner join tb_currency cur on 
						cur.currencyID = tm.currencyID 
					inner join tb_transaction_causal tc on 
						tm.transactionCausalID = tc.transactionCausalID 
					inner join tb_customer cus on 
						tm.entityID = cus.entityID 
					inner join tb_legal l on 
						cus.entityID = l.entityID 
					inner join tb_naturales nat_cus on 
						nat_cus.entityID   = l.entityID 
					inner join tb_user usr on 
						tm.createdBy = usr.userID 
					inner join tb_workflow_stage ws on 
						tm.statusID = ws.workflowStageID 
					inner join tb_transaction_master_info tmi on 
						tm.companyID = tmi.companyID and 
						tm.transactionID = tmi.transactionID and 
						tm.transactionMasterID = tmi.transactionMasterID 
					inner join tb_catalog_item ci on 
						tmi.zoneID = ci.catalogItemID 
					inner join tb_item i on 
						tmd.componentItemID = i.itemID 
					inner join tb_item_category cat on 
						cat.inventoryCategoryID = i.inventoryCategoryID 
					left join tb_naturales nat_emp on 
						nat_emp.entityID = tm.entityIDSecondary 
				where  					
					tm.companyID = prCompanyID and 
					tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 
					tm.isActive = 1 and 
					tmd.isActive = 1 and 
					ws.aplicable = 1 and 
					tm.transactionID in (19 /*FACTURA*/ ) and 
					(
						( UPPER(prTransactionCausalName) = UPPER('TODAS')) or 
						(
						  UPPER(prTransactionCausalName) != UPPER('TODAS') and 
							UPPER(prTransactionCausalName) = UPPER(tc.`name`) 
						)
					) and 
					(
						prInventoryCategoryID = 0 
						or 
						(prInventoryCategoryID != 0 and i.inventoryCategoryID = prInventoryCategoryID )
					) and 
					(
						prWarehouse = 0 
						or 
						(
							prWarehouse != 0 and 
							tm.sourceWarehouseID =  prWarehouse 
					  )
					) and 
					(
						prUserIDCreatedBy = 0 
						or 
						(
							prUserIDCreatedBy != 0 and 
							prUserIDCreatedBy = tm.createdBy 
						)
					)
				order by 
					tm.transactionMasterID asc, tmd.transactionMasterDetailID asc
					
		) rx;
		
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_sales_get_report_sales_detail_commission
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_detail_commission`;
delimiter ;;
CREATE PROCEDURE `pr_sales_get_report_sales_detail_commission`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE,IN prInventoryCategoryID INT, IN prWarehouse INT, IN prUserIDSales INT, IN prText VARCHAR(50), IN prEmployerID INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de ventas'
BEGIN

	DECLARE varCurrencyCompras INT DEFAULT 0;	
	DECLARE varCurrencyReporte INT DEFAULT 0;	
	
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);	
	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	
	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		
	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		
		
	
	select 
		rx.userID,
		rx.nickname,
		rx.transactionNumber,
		rx.employerName,
		rx.tipo,
		rx.transactionOn,
		rx.createdOn,
		DAYOFMONTH(rx.createdOn) as dayOfMonth,
		rx.customerNumber,
		rx.currencyName,
		rx.note,
		rx.legalName,
		rx.zone,
		rx.itemNumber,
		rx.itemName,
		rx.itemNameLog,
		rx.phoneNumber,
		rx.Agent,
		rx.Commentary,
		rx.nameCategory,
		rx.quantity,
		rx.unitaryCost,
		rx.unitaryPrice,
		(rx.unitaryCost * rx.quantity) as cost,
		(rx.unitaryPrice * rx.quantity) as amount,
		(rx.unitaryPrice * rx.quantity) + (rx.iva * rx.quantity)  as amountConIva,
		(rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity)    as utilidad,
		(rx.iva) as iva,
		(rx.quantity * rx.iva) as ivaTotal,
		varCurrencyReporte,
		rx.currencyID,
		rx.exchangeRate,
		rx.amountCommision 
	from 
		(
				select 
					usr.userID,
					usr.nickname,
					tm.transactionNumber,
					IFNULL(CONCAT(nat_emp.firstName,' ',nat_emp.lastName),'') as employerName,
					tc.name as tipo,
					tm.transactionOn,
					cus.customerNumber,
					concat(nat_cus.firstName,' ', nat_cus.lastName) as legalName ,
					ci.name as zone,
					i.itemNumber,
					i.name as itemName,
					tmd.itemNameLog,
					cat.`name` as nameCategory,
					cus.phoneNumber,
					'' AS Agent,
					'' as Commentary,
					tmd.quantity,
					tm.currencyID,
					tm.exchangeRate,
					tm.createdOn,
					cur.`name` as currencyName,
					tm.note as note,
					
					case 
						when varCurrencyReporte = tm.currencyID then 
							tmd.unitaryPrice 
						when tm.exchangeRate > 1 then 
							tm.exchangeRate * (tmd.unitaryPrice)
						else 
							(1/tm.exchangeRate) * (tmd.unitaryPrice)
					end unitaryPrice,
					
					
					
					case 
						when varCurrencyReporte = tm.currencyID  then 				
							tmd.unitaryCost
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  tmd.unitaryCost														
						else 								
						  (1/tm.exchangeRate) *   tmd.unitaryCost							
					end  unitaryCost ,
					
					
					case 
						when varCurrencyReporte = tm.currencyID  then 				
							IFNULL(tmd.tax1,0)
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  IFNULL(tmd.tax1,0)
						else 								
						  (1/tm.exchangeRate) *   IFNULL(tmd.tax1,0)
					end as iva ,
					
					
					case 
						when varCurrencyReporte = tm.currencyID  then 				
							IFNULL(amountCommision,0)
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  IFNULL(amountCommision,0)
						else 								
						  (1/tm.exchangeRate) *   IFNULL(amountCommision,0)
					end  as amountCommision 
					
				from 
					tb_transaction_master tm  					
					inner join tb_transaction_master_detail tmd on 
						tm.companyID = tmd.companyID and 
						tm.transactionID = tmd.transactionID and 
						tm.transactionMasterID = tmd.transactionMasterID 
					inner join tb_currency cur on 
						cur.currencyID = tm.currencyID 
					inner join tb_transaction_causal tc on 
						tm.transactionCausalID = tc.transactionCausalID 
					inner join tb_customer cus on 
						tm.entityID = cus.entityID 
					inner join tb_legal l on 
						cus.entityID = l.entityID 
					inner join tb_naturales nat_cus on 
						nat_cus.entityID   = l.entityID 
					inner join tb_user usr on 
						tm.createdBy = usr.userID 
					inner join tb_workflow_stage ws on 
						tm.statusID = ws.workflowStageID 
					inner join tb_transaction_master_info tmi on 
						tm.companyID = tmi.companyID and 
						tm.transactionID = tmi.transactionID and 
						tm.transactionMasterID = tmi.transactionMasterID 
					inner join tb_catalog_item ci on 
						tmi.zoneID = ci.catalogItemID 
					inner join tb_item i on 
						tmd.componentItemID = i.itemID 
					inner join tb_item_category cat on 
						cat.inventoryCategoryID = i.inventoryCategoryID 
					left join tb_naturales nat_emp on 
						nat_emp.entityID = tm.entityIDSecondary 
				where  					
					tm.companyID = prCompanyID and 
					tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 
					tm.isActive = 1 and 
					tmd.isActive = 1 and 
					ws.aplicable = 1 and 
					(
						prInventoryCategoryID = 0 
						or 
						(prInventoryCategoryID != 0 and i.inventoryCategoryID = prInventoryCategoryID )
					) and 
					(
						prWarehouse = 0 
						or 
						(
							prWarehouse != 0 and 
							tm.sourceWarehouseID =  prWarehouse 
					  )
					)
				order by 
					tm.transactionMasterID asc, tmd.transactionMasterDetailID asc
					
		) rx;
		
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_sales_get_report_sales_detail_commission_byreference
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_detail_commission_byreference`;
delimiter ;;
CREATE PROCEDURE `pr_sales_get_report_sales_detail_commission_byreference`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE,IN prInventoryCategoryID INT, IN prWarehouse INT, IN prUserIDSales INT, IN prText VARCHAR(50), IN prEmployerID INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de ventas'
BEGIN

	DECLARE varCurrencyCompras INT DEFAULT 0;	
	DECLARE varCurrencyReporte INT DEFAULT 0;	
	
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);	
	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	
	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		
	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		
		
	
	select 
		rx.userID,
		rx.nickname,
		rx.transactionNumber,
		rx.employerName,
		rx.tipo,
		rx.transactionOn,
		rx.createdOn,
		DAYOFMONTH(rx.createdOn) as dayOfMonth,
		rx.customerNumber,
		rx.currencyName,
		rx.note,
		rx.legalName,
		rx.zone,
		rx.itemNumber,
		rx.itemName,
		rx.itemNameLog,
		rx.phoneNumber,
		rx.Agent,
		rx.Commentary,
		rx.nameCategory,
		rx.quantity,
		rx.unitaryCost,
		rx.unitaryPrice,
		(rx.unitaryCost * rx.quantity) as cost,
		(rx.unitaryPrice * rx.quantity) as amount,
		(rx.unitaryPrice * rx.quantity) + (rx.iva * rx.quantity)  as amountConIva,
		(rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity)    as utilidad,
		(rx.iva) as iva,
		(rx.quantity * rx.iva) as ivaTotal,
		varCurrencyReporte,
		rx.currencyID,
		rx.exchangeRate,
		rx.amountCommision 
	from 
		(
				select 
					usr.userID,
					usr.nickname,
					tm.transactionNumber,
					IFNULL(CONCAT(nat_emp.firstName,' ',nat_emp.lastName),'') as employerName,
					tc.name as tipo,
					tm.transactionOn,
					cus.customerNumber,
					concat(nat_cus.firstName,' ', nat_cus.lastName) as legalName ,
					ci.name as zone,
					i.itemNumber,
					i.name as itemName,
					tmd.itemNameLog,
					cat.`name` as nameCategory,
					cus.phoneNumber,
					'' AS Agent,
					'' as Commentary,
					tmd.quantity,
					tm.currencyID,
					tm.exchangeRate,
					tm.createdOn,
					cur.`name` as currencyName,
					tm.note as note,
					
					case 
						when varCurrencyReporte = tm.currencyID then 
							tmd.unitaryPrice 
						when tm.exchangeRate > 1 then 
							tm.exchangeRate * (tmd.unitaryPrice)
						else 
							(1/tm.exchangeRate) * (tmd.unitaryPrice)
					end unitaryPrice,
					
					
					
					case 
						when varCurrencyReporte = tm.currencyID  then 				
							tmd.unitaryCost
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  tmd.unitaryCost														
						else 								
						  (1/tm.exchangeRate) *   tmd.unitaryCost							
					end  unitaryCost ,
					
					
					case 
						when varCurrencyReporte = tm.currencyID  then 				
							IFNULL(tmd.tax1,0)
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  IFNULL(tmd.tax1,0)
						else 								
						  (1/tm.exchangeRate) *   IFNULL(tmd.tax1,0)
					end as iva ,
					
					
					case 
						when varCurrencyReporte = tm.currencyID  then 				
							IFNULL(amountCommision,0)
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  IFNULL(amountCommision,0)
						else 								
						  (1/tm.exchangeRate) *   IFNULL(amountCommision,0)
					end  as amountCommision 
					
				from 
					tb_transaction_master tm  					
					inner join tb_transaction_master_detail tmd on 
						tm.companyID = tmd.companyID and 
						tm.transactionID = tmd.transactionID and 
						tm.transactionMasterID = tmd.transactionMasterID 
					inner join tb_transaction_master_detail_references tmdr on 
						tmd.transactionMasterDetailID = tmdr.transactionMasterDetailID 
						
					inner join tb_currency cur on 
						cur.currencyID = tm.currencyID 
					inner join tb_transaction_causal tc on 
						tm.transactionCausalID = tc.transactionCausalID 
					inner join tb_customer cus on 
						tm.entityID = cus.entityID 
					inner join tb_legal l on 
						cus.entityID = l.entityID 
					inner join tb_naturales nat_cus on 
						nat_cus.entityID   = l.entityID 
					inner join tb_user usr on 
						tm.createdBy = usr.userID 
					inner join tb_workflow_stage ws on 
						tm.statusID = ws.workflowStageID 
					inner join tb_transaction_master_info tmi on 
						tm.companyID = tmi.companyID and 
						tm.transactionID = tmi.transactionID and 
						tm.transactionMasterID = tmi.transactionMasterID 
					inner join tb_catalog_item ci on 
						tmi.zoneID = ci.catalogItemID 
					inner join tb_item i on 
						tmd.componentItemID = i.itemID 
					inner join tb_item_category cat on 
						cat.inventoryCategoryID = i.inventoryCategoryID 
					inner join tb_naturales nat_emp on 
						nat_emp.entityID = tmdr.sales  
				where  					
					tm.companyID = prCompanyID and 
					tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 
					tm.isActive = 1 and 
					tmd.isActive = 1 and 
					ws.aplicable = 1 and 
					(
						prInventoryCategoryID = 0 
						or 
						(prInventoryCategoryID != 0 and i.inventoryCategoryID = prInventoryCategoryID )
					) and 
					(
						prWarehouse = 0 
						or 
						(
							prWarehouse != 0 and 
							tm.sourceWarehouseID =  prWarehouse 
					  )
					)
				order by 
					tm.transactionMasterID asc, tmd.transactionMasterDetailID asc
					
		) rx;
		
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_sales_get_report_sales_detail_out_of_range
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_detail_out_of_range`;
delimiter ;;
CREATE PROCEDURE `pr_sales_get_report_sales_detail_out_of_range`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), 	IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE,IN prInventoryCategoryID INT, IN prWarehouse INT ,
IN prUserIDCreatedBy INT,  IN prTransactionCausalName VARCHAR(50))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de ventas'
BEGIN

	DECLARE varCurrencyCompras INT DEFAULT 0;	
	DECLARE varCurrencyReporte INT DEFAULT 0;	
	
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);	
	
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	
	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		
	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		
		
	
	SELECT 
			rx.transactionOn ,
			rx.transactionNumber,
			rx.currencyName,
			rx.createdByName,
			rx.causalName,
			rx.firstName, 
			rx.productNumber,
			rx.productName,
			round(rx.transactionQuantity,2) as transactionQuantity,
			
			round(rx.transactionPrice,2) as transactionPrice,
			round(rx.transactionPrice1,2) as transactionPrice1,
			round(rx.transactionDifPrice,2) as transactionDifPrice,
			round((rx.transactionDifPrice *  rx.transactionQuantity ),2) as transactionDifPriceTotal 
	FROM 
		(
				select 
					tm.transactionOn ,
					tm.transactionNumber,
					cur.name as currencyName,
					usr.nickname as createdByName,
					tc.`name` as causalName,
					vender_tecnico.firstName, 
					i.itemNumber as productNumber,
					i.`name` as productName,
					tmd.quantity as transactionQuantity,
					
					case 
						when varCurrencyReporte = tm.currencyID then 
							tmd.unitaryPrice 
						when tm.exchangeRate > 1 then 
							tm.exchangeRate * (tmd.unitaryPrice)
						else 
							(1/tm.exchangeRate) * (tmd.unitaryPrice)
					end transactionPrice,
					
					
					case 
						when varCurrencyReporte = tm.currencyID then 
							tmdr.precio1 
						when tm.exchangeRate > 1 then 
							tm.exchangeRate * (tmdr.precio1)
						else 
							(1/tm.exchangeRate) * (tmdr.precio1)
					end transactionPrice1,
					
					
					(
						case 
							when varCurrencyReporte = tm.currencyID then 
								tmd.unitaryPrice 
							when tm.exchangeRate > 1 then 
								tm.exchangeRate * (tmd.unitaryPrice)
							else 
								(1/tm.exchangeRate) * (tmd.unitaryPrice)
						end 
						-  
						case 
							when varCurrencyReporte = tm.currencyID then 
								tmdr.precio1 
							when tm.exchangeRate > 1 then 
								tm.exchangeRate * (tmdr.precio1)
							else 
								(1/tm.exchangeRate) * (tmdr.precio1)
						end
					) transactionDifPrice
					
					
				from 
					tb_transaction_master tm  			
					inner join tb_currency cur on 
						cur.currencyID = tm.currencyID 
					inner join tb_transaction_causal tc on 
						tm.transactionCausalID = tc.transactionCausalID 
					inner join tb_customer cus on 
						tm.entityID = cus.entityID 
					inner join tb_legal l on 
						cus.entityID = l.entityID 
					inner join tb_naturales nat_cus on 
						nat_cus.entityID   = l.entityID 
					inner join tb_user usr on 
						tm.createdBy = usr.userID 
					inner join tb_workflow_stage ws on 
						tm.statusID = ws.workflowStageID 
						
								
					inner join tb_transaction_master_detail tmd on 
						tm.companyID = tmd.companyID and 
						tm.transactionID = tmd.transactionID and 
						tm.transactionMasterID = tmd.transactionMasterID 		
					inner join tb_transaction_master_detail_references tmdr on 
								tmdr.transactionMasterDetailID = tmd.transactionMasterDetailID 
								
					inner join tb_naturales vender_tecnico on 
						vender_tecnico.entityID = tmdr.sales 
					inner join tb_item i on 
						tmd.componentItemID = i.itemID 
					inner join tb_item_category cat on 
						cat.inventoryCategoryID = i.inventoryCategoryID 
						
				where  					
					tm.companyID = prCompanyID and 
					tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 
					tm.isActive = 1 and 
					tmd.isActive = 1 and 
					ws.aplicable = 1 and 
					tm.transactionID in (19 /*FACTURA*/ ) and 
					(
						( UPPER(prTransactionCausalName) = UPPER('TODAS')) or 
						(
						  UPPER(prTransactionCausalName) != UPPER('TODAS') and 
							UPPER(prTransactionCausalName) = UPPER(tc.`name`) 
						)
					) and 
					(
						prInventoryCategoryID = 0 
						or 
						(prInventoryCategoryID != 0 and i.inventoryCategoryID = prInventoryCategoryID )
					) and 
					(
						prWarehouse = 0 
						or 
						(
							prWarehouse != 0 and 
							tm.sourceWarehouseID =  prWarehouse 
					  )
					) and 
					(
						prUserIDCreatedBy = 0 
						or 
						(
							prUserIDCreatedBy != 0 and 
							prUserIDCreatedBy = tm.createdBy 
						)
					)
				order by 
					tm.transactionMasterID asc, tmd.transactionMasterDetailID asc
		) rx 
	where 
		rx.transactionDifPrice != 0 ; 
					
		
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_sales_get_report_sales_summary
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_summary`;
delimiter ;;
CREATE PROCEDURE `pr_sales_get_report_sales_summary`(IN `prCompanyID` INT, 
IN `prTokenID` VARCHAR(50), IN `prUserID` INT, 
 IN `prStartOn` DATETIME, IN `prEndOn` DATETIME, IN `prUserIDFilter` INT , 
 IN prConceptFilter VARCHAR(150), IN prWithTax1 INT ,IN `prBranchID` INT, 
 IN prWarehouseID INT, IN prEntityIDCustomer INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Resumen de venta'
BEGIN
	DECLARE varCurrencyCompras INT DEFAULT 0;	
	DECLARE varCurrencyReporte INT DEFAULT 0;	
	DECLARE varZoneOraria INT DEFAULT 0;
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);	
	DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;
	DECLARE currencyIDNameTarget VARCHAR(250);	
	DECLARE currencyIDNameSource VARCHAR(250);
	DECLARE convert_ VARCHAR(50);	
	DECLARE prFlavorID INT DEFAULT 0;

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	CALL pr_core_get_parameter_value(prCompanyID,"CORE_ZONA_HORARIA",varZoneOraria);
	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		
	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);
	CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_); 
	CALL pr_core_get_parameter_value(prCompanyID, "ACCOUNTING_CURRENCY_NAME_REPORT_CONVERT", convert_);
	SET prFlavorID 								= (SELECT flavorID FROM tb_company c where c.companyID = prCompanyID);

	drop temporary table if exists tb_tmp_split;
	create temporary table tb_tmp_split( val char(255) );
	set @sql = concat("insert into tb_tmp_split (val) values ('", replace(prConceptFilter, ",", "'),('"),"');");
	prepare stmt1 from @sql;
	execute stmt1;
  

	select 
			rx.userID,
			rx.nickname,
			rx.currencyID,
			rx.transactionNumber,
			rx.tipo,
			rx.transactionOn,
			rx.customerNumber,
			rx.legalName,
			rx.zone,
			rx.statusName,
			rx.firstName,
			fn_translate_transaction_master_info_amounts( prCompanyID,prFlavorID,rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, 0, 0,  'CurrencyName') 
				as currencyName,
				
			'' as categoryName,
			'' as categorySubName,				
			rx.exchangeRate,
			rx.transactionID,
			
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, rx.receiptAmount, rx.receiptAmountDol,  'Amount'), DECIMAL(10,2)) as  EfectivoCordoba,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, rx.receiptAmount, rx.receiptAmountDol,  'AmountExt'), DECIMAL(10,2))  as EfectivoDolares ,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, rx.receiptAmountCard, rx.receiptAmountCardDol,  'Amount'), DECIMAL(10,2))  as TarjetaCordoba,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, rx.receiptAmountCard, rx.receiptAmountCardDol,  'AmountExt'), DECIMAL(10,2))  as TarjetaDolares ,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, rx.receiptAmountBank, rx.receiptAmountBankDol,  'Amount'), DECIMAL(10,2))  as TansferenciaCordoba,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, rx.receiptAmountBank, rx.receiptAmountBankDol,  'AmountExt'), DECIMAL(10,2))  as TransferenciaDolares, 
			avg(rx.receiptAmountPoint) as receiptAmountPoint , 
			IFNULL(AVG(rx.discount),0) as discount, 
			sum((rx.unitaryCost * rx.quantity)) as cost,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, 
			(sum((rx.unitaryPrice  * rx.quantity) +  ifnull(rx.tax2,0) )), 0,  'Convert') , DECIMAL(10,2))
			 as totalSinIva,
			CONVERT(fn_translate_transaction_master_info_amounts( prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, 
			(sum(rx.iva * rx.quantity)), 0,  'Convert'), DECIMAL(10,2)) as totalIva,
			CONVERT(
				fn_translate_transaction_master_info_amounts( 
					prCompanyID, prFlavorID, rx.transactionID, currencyIDNameCompra, currencyIDNameReporte, convert_, rx.currencyID, exchangeRate_, 
					(
						sum(
								(rx.unitaryPrice * rx.quantity) + 
								(rx.iva * rx.quantity ) + 
								( ifnull(rx.tax2,0) * 1 ) 
							) - 
						avg(
							rx.receiptAmountPoint
						)  - 
						CASE 
						  WHEN rx.statusName = 'ANULADA' THEN 0
							ELSE 
								IFNULL
								(
									AVG(rx.discount),0
								)
						END 
					), 0,  'Convert'
				) , DECIMAL(10,2)
			)  as totalDocument,
			sum((rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity))    as utilidad		
	from
		(
				select 
					usr.userID,
					case 
						when comp.flavorID = 306 then 
							nat_emp.firstName 
						else 
							usr.nickname
					end as nickname,				
					tm.transactionNumber,
					tc.name as tipo,
					tm.transactionOn,
					
					case 
						when tmi.referenceClientIdentifier <> '' then 
							tmi.referenceClientIdentifier 
						else 
							cus.customerNumber
					end as customerNumber,
					
					case 
						when tmi.referenceClientName <> '' then 
							tmi.referenceClientName  
						else 
							concat(nat_cus.firstName,' ', nat_cus.lastName)
					end as legalName,
					
					
					case 
						when tmi.referenceClientName <> '' then 
							tmi.referenceClientName  
						else 
							nat.firstName 
					end as firstName,
					
					
					
					
					ci.name as zone,
					tm.tax2 as tax2,					
					tmi.receiptAmount,
					tmi.receiptAmountDol,
					tmi.receiptAmountCard,
					tmi.receiptAmountCardDol,
					tmi.receiptAmountBank ,
					tmi.receiptAmountBankDol,			
					tmi.receiptAmountPoint , 		
					CASE 
						WHEN 
							ws.eliminable = 0 and 
							tm.createdOn between prStartOn and concat(prEndOn) and 
							tm.statusIDChangeOn between prStartOn and concat(prEndOn) THEN  
								'ANULADA' 
						WHEN 
							ws.eliminable = 0 and 
							tm.createdOn between prStartOn and concat(prEndOn) and 
							tm.statusIDChangeOn > prEndOn THEN  
								'POST-ANULADA' 
						WHEN 
							ws.eliminable = 0 and 
							tm.createdOn < prStartOn and 
							tm.statusIDChangeOn between prStartOn and concat(prEndOn)  THEN  
								'DEVOLUCION' 
						ELSE 
							ws.name	
					END as statusName,
					
					
					
					
					
					cu.name AS currencyName,
					icat.`name` as categoryName,
					CASE 
						WHEN 
							ws.eliminable = 0 and 
							tm.createdOn between prStartOn and concat(prEndOn) and 
							tm.statusIDChangeOn between prStartOn and concat(prEndOn) THEN  
								tmd.quantity * 0
						WHEN 
							ws.eliminable = 0 and 
							tm.createdOn between prStartOn and concat(prEndOn) and 
							tm.statusIDChangeOn > prEndOn THEN  
								tmd.quantity * 1
						WHEN 
							ws.eliminable = 0 and 
							tm.createdOn < prStartOn and 
							tm.statusIDChangeOn between prStartOn and concat(prEndOn) THEN  
								tmd.quantity * -1
						ELSE 
							tmd.quantity 
					END as quantity,
					tmd.unitaryPrice,
					case 
						when varCurrencyCompras = varCurrencyReporte  then 				
							tmd.unitaryCost
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  tmd.unitaryCost														
						else 								
						  (1/tm.exchangeRate) *   tmd.unitaryCost							
					end  unitaryCost ,
					IFNULL(tmd.tax1,0) as  iva ,
					IFNULL(tmd.amountCommision,0) as amountCommision,
					tm.exchangeRate,
					tm.discount,
					tm.transactionID,
					tm.currencyID 
				from 
					tb_transaction_master tm   
					inner join tb_transaction_master_detail tmd on 
						tm.companyID = tmd.companyID and 
						tm.transactionID = tmd.transactionID and 
						tm.transactionMasterID = tmd.transactionMasterID 
					inner join tb_transaction_causal tc on 
						tm.transactionCausalID = tc.transactionCausalID 
					inner join tb_customer cus on 
						tm.entityID = cus.entityID 
					INNER JOIN tb_naturales nat ON 
						nat.entityID = cus.entityID 
					inner join tb_legal l on 
						cus.entityID = l.entityID 
					inner join tb_naturales nat_cus on 
						nat_cus.entityID   = l.entityID 
					inner join tb_user usr on 
						tm.createdBy = usr.userID 
					inner join tb_branch braus on 
						braus.branchID = usr.locationID
					inner join tb_workflow_stage ws on  
						tm.statusID = ws.workflowStageID 
					inner join tb_transaction_master_info tmi on 
						tm.companyID = tmi.companyID and 
						tm.transactionID = tmi.transactionID and 
						tm.transactionMasterID = tmi.transactionMasterID 
					inner join tb_catalog_item ci on 
						tmi.zoneID = ci.catalogItemID 
					INNER JOIN tb_currency cu ON 
						cu.currencyID = tm.currencyID 
					inner join tb_item it on 
						it.itemID = tmd.componentItemID 
					inner join tb_item_category icat on 
						icat.inventoryCategoryID = it.inventoryCategoryID 
					inner join tb_company comp on 
						comp.companyID = tm.companyID 
					left join tb_naturales nat_emp on 
						nat_emp.entityID = tm.entityIDSecondary 
				where
					tm.companyID = prCompanyID and 
					tm.isActive = 1 and 
					tmd.isActive = 1 and 		
					(
						 (tm.entityID = prEntityIDCustomer and prEntityIDCustomer != 0 )
						 or 
						 (prEntityIDCustomer = 0)
					)
					and 		
					(
					  (tm.tax1 = 0 and prWithTax1 = -1 )
						or 
						(tm.tax1 > 0 and prWithTax1 = 1 )
						or 
						(prWithTax1 = 0)
					)
					and 					
					(
						(prConceptFilter = '-1') or
						(
								prConceptFilter != '-1' and 
								it.inventoryCategoryID in 
								(
									select val  from tb_tmp_split 
								)
						) 
					)
					and 
  				(
					  (tm.createdBy = prUserIDFilter and prUserIDFilter != 0 )
						or 
						(prUserIDFilter = 0)
					)
					and 
					(
						(braus.branchID = prBranchID and prBranchID != 0 )
						or 
						(prBranchID = 0)
					)
					and 
					(
						(tm.sourceWarehouseID = prWarehouseID and prWarehouseID != 0)
						or 
						(prWarehouseID = 0)
					)
					and 
					(
					(
  							tm.transactionID = 19 and 
								DATE_ADD(tm.createdOn, INTERVAL varZoneOraria HOUR)  between prStartOn and prEndOn and 								ws.aplicable = 1  
							)
							or 
							(
								tm.transactionID = 19 and 
								ws.eliminable = 0 and 
								DATE_ADD(tm.createdOn, INTERVAL varZoneOraria HOUR) between prStartOn and prEndOn  and 
								DATE_ADD(tm.statusIDChangeOn, INTERVAL varZoneOraria HOUR) between prStartOn and prEndOn 
							)
							or 
							(
	  						tm.transactionID = 19 and 
								ws.eliminable = 0 and 								
								DATE_ADD(tm.createdOn, INTERVAL varZoneOraria HOUR) between prStartOn and prEndOn  and 
								DATE_ADD(tm.statusIDChangeOn, INTERVAL varZoneOraria HOUR) > prEndOn 
  						)
							or 							
							(
								tm.transactionID = 19 and 
								ws.eliminable = 0 and 
								DATE_ADD(tm.createdOn, INTERVAL varZoneOraria HOUR) < prStartOn  and 
								DATE_ADD(tm.statusIDChangeOn, INTERVAL varZoneOraria HOUR) between prStartOn and prEndOn 
							)						
					) 
				order by 
					tm.transactionMasterID asc 
		) rx 
	group by 
			rx.userID,
			rx.nickname,
			rx.currencyID,
			rx.transactionNumber,
			rx.tipo,
			rx.transactionOn,
			rx.customerNumber,
			rx.legalName,
			rx.zone,
			rx.statusName,
			rx.firstName,
			rx.currencyName,
			rx.exchangeRate,
			rx.transactionID ;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_sales_get_report_sales_summary_credit
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_summary_credit`;
delimiter ;;
CREATE PROCEDURE `pr_sales_get_report_sales_summary_credit`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATETIME, IN `prEndOn` DATETIME, IN `prUserIDFilter` INT , IN prConceptFilter VARCHAR(150) ,IN `prBranchID` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Resumen de venta'
BEGIN
	DECLARE varCurrencyCompras INT DEFAULT 0;	
	DECLARE varCurrencyReporte INT DEFAULT 0;
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);
  DECLARE exchangeRate_ DECIMAL(18,4) DEFAULT 0;
  DECLARE currencyIDNameTarget VARCHAR(250);	
  DECLARE currencyIDNameSource VARCHAR(250);
  DECLARE convert_ VARCHAR(50);	 
  
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		
	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		
  CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);
  CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_EXTERNAL",currencyIDNameTarget);
  CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameTarget,currencyIDNameSource,exchangeRate_); 
  CALL pr_core_get_parameter_value(prCompanyID, "ACCOUNTING_CURRENCY_NAME_REPORT_CONVERT", convert_);	
  
	drop temporary table if exists tb_tmp_split;
	create temporary table tb_tmp_split( val char(255) );
	set @sql = concat("insert into tb_tmp_split (val) values ('", replace(prConceptFilter, ",", "'),('"),"');");
	prepare stmt1 from @sql;
	execute stmt1;

	select 
			rx.userID,
			rx.nickname,
			rx.transactionNumber,
			rx.tipo,
			rx.transactionOn,
			rx.customerNumber,
			rx.legalName,
			rx.zone,
			rx.statusName,
			rx.firstName,
			case 
        when convert_ = 'None'  then 
          rx.currencyName
        else 
          convert_ 
      end as currencyName,
			rx.categoryName,
			'' as categorySubName,
      case 
			when convert_ = 'Dolar' and rx.currencyName != 'Dolar'  then 
				rx.receiptAmount * exchangeRate_ 
			when convert_ = 'Cordoba' and rx.currencyName != 'Cordoba'  then 
				rx.receiptAmount / exchangeRate_  
			else 
				rx.receiptAmount
		end as receiptAmount,
			sum((rx.unitaryCost * rx.quantity)) as cost,
			sum((rx.unitaryPrice * rx.quantity)) as totalDocument,
			sum((rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity))    as utilidad		
	from
		(
				select 
					usr.userID,
					usr.nickname,
					tm.transactionNumber,
					tc.name as tipo,
					tm.transactionOn,
					cus.customerNumber,
					l.legalName,
					ci.name as zone,
					ws.name AS statusName,
					nat.firstName ,
					cu.name AS currencyName,
					icat.`name` as categoryName,
					tmd.quantity,
					tmin.receiptAmount, 
					case 
						when varCurrencyReporte = tm.currencyID then 
							tmd.unitaryPrice 
						when tm.exchangeRate > 1 then 
							tm.exchangeRate * (tmd.unitaryPrice)
						else 
							(1/tm.exchangeRate) * (tmd.unitaryPrice)
					end unitaryPrice,
					case 
						when varCurrencyCompras = varCurrencyReporte  then 				
							tmd.unitaryCost
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  tmd.unitaryCost														
						else 								
						  (1/tm.exchangeRate) *   tmd.unitaryCost							
					end  unitaryCost 
				from 
					tb_transaction_master tm 
					inner join tb_transaction_master_info  tmin on 
						tmin.transactionMasterID = tm.transactionMasterID  
					inner join tb_transaction_master_detail tmd on 
						tm.companyID = tmd.companyID and 
						tm.transactionID = tmd.transactionID and 
						tm.transactionMasterID = tmd.transactionMasterID 
					inner join tb_transaction_causal tc on 
						tm.transactionCausalID = tc.transactionCausalID 
					inner join tb_customer cus on 
						tm.entityID = cus.entityID 
					INNER JOIN tb_naturales nat ON 
						nat.entityID = cus.entityID 
					inner join tb_legal l on 
						cus.entityID = l.entityID 
					inner join tb_user usr on 
						tm.createdBy = usr.userID 
					inner join tb_branch braus on 
						braus.branchID = usr.locationID
					inner join tb_workflow_stage ws on  
						tm.statusID = ws.workflowStageID 
					inner join tb_transaction_master_info tmi on 
						tm.companyID = tmi.companyID and 
						tm.transactionID = tmi.transactionID and 
						tm.transactionMasterID = tmi.transactionMasterID 
					inner join tb_catalog_item ci on  
						tmi.zoneID = ci.catalogItemID 
					INNER JOIN tb_currency cu ON 
						cu.currencyID = tm.currencyID 
					inner join tb_item it on 
						it.itemID = tmd.componentItemID 
					inner join tb_item_category icat on 
						icat.inventoryCategoryID = it.inventoryCategoryID 
					inner join tb_company comp on 
						tm.companyID = comp.companyID 
				where
					tm.companyID = prCompanyID and 
					tm.createdOn between prStartOn and concat(prEndOn,' 23:59:59') and  
					tc.transactionCausalID in (22,24)  and 
					tm.isActive = 1 and 
					tmd.isActive = 1 and 
					ws.aplicable = 1 and 
					(
							(
								comp.flavorID != 326 
							)
					)		and 
					(
					  (tm.createdBy = prUserIDFilter and prUserIDFilter != 0 )
						or 
						(prUserIDFilter = 0)
					) 
					and 
					(
						(braus.branchID = prBranchID and prBranchID != 0 )
						or 
						(prBranchID = 0)
					)
					and 
					(
						(prConceptFilter = '-1') or
						(
								prConceptFilter != '-1' and 
								it.inventoryCategoryID in 
								(
									select val  from tb_tmp_split 
								)
						) 
					)
				order by 
					tm.transactionMasterID asc 
		) rx 
	group by 
			rx.userID,
			rx.nickname,
			rx.transactionNumber,
			rx.tipo,
			rx.transactionOn,
			rx.customerNumber,
			rx.legalName,
			rx.zone,
			rx.statusName,
			rx.firstName,
			rx.currencyName,
			rx.receiptAmount ; 
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_sales_get_report_sales_utility_summary
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_sales_get_report_sales_utility_summary`;
delimiter ;;
CREATE PROCEDURE `pr_sales_get_report_sales_utility_summary`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE, IN prExpenditureClassification INT, IN prBranchID INT, IN prWarehouseID INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de ventas'
BEGIN


	DECLARE varCurrencyCompras INT DEFAULT 0;	
	DECLARE varCurrencyReporte INT DEFAULT 0;	
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);		

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		
	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);	

	create table tb_tmp_comision_gastos (
		gastosName varchar(250),
		amount decimal(19,2)		
	);
	
	insert into tb_tmp_comision_gastos(gastosName,amount)
	select 		
		 ci.`name` as Tipo,
		 tm.amount
	from 
		tb_transaction_master tm 
		inner join tb_workflow_stage ws on 
			ws.workflowStageID = tm.statusID 
		inner join tb_public_catalog_detail ci on 
			ci.publicCatalogDetailID = tm.priorityID 
		inner join tb_public_catalog_detail ci2 on 
			ci2.publicCatalogDetailID = tm.areaID 
	where
		tm.transactionID = 38  and 
		(
			(tm.branchID = prBranchID and prBranchID != 0)
			or 
			(prBranchID = 0 )			
		) and 
		tm.isActive = 1 and 
		ws.aplicable = 1 and 
		tm.transactionOn between prStartOn and prEndOn and 
		(
			(
				tm.classID = prExpenditureClassification and 
				prExpenditureClassification != 0 
			) OR 
			(
				prExpenditureClassification = 0
			)
		);
		
	create table tb_tmp_comision_venta_costo (
		ventaName varchar(250),
		amountVenta decimal(19,2),
		amountCost decimal(19,2)
	);

	insert into tb_tmp_comision_venta_costo(ventaName,amountVenta,amountCost)	
	select 
		'venta',		
		case 
					when varCurrencyReporte = tm.currencyID then 
						tmd.unitaryPrice  * tmd.quantity 
					when tm.exchangeRate > 1 then 
						tm.exchangeRate * (tmd.unitaryPrice) * tmd.quantity 
					else 
						(1/tm.exchangeRate) * (tmd.unitaryPrice) * tmd.quantity 
		end unitaryPrice,
		case 
					when varCurrencyCompras = varCurrencyReporte  then 			
							tmd.unitaryCost * tmd.quantity
					when tm.exchangeRate > 1 then 
							tm.exchangeRate *  tmd.unitaryCost * tmd.quantity				
					else 								
						  (1/tm.exchangeRate) *   tmd.unitaryCost		* tmd.quantity	
		end  unitaryCost 
	from 
		tb_transaction_master tm  	
		inner join tb_transaction_master_detail tmd on 
			tm.companyID = tmd.companyID and 
			tm.transactionID = tmd.transactionID and 
			tm.transactionMasterID = tmd.transactionMasterID 
		inner join tb_transaction_causal tc on 
			tm.transactionCausalID = tc.transactionCausalID 
		inner join tb_workflow_stage ws on 
			tm.statusID = ws.workflowStageID 
		inner join tb_user usr on 
			usr.userID = tm.createdBy
		inner join tb_branch br on 
			usr.locationID = br.branchID 
	where
	  tm.transactionID = 19  and 
		(
			(br.branchID = prBranchID and prBranchID != 0)
			or 
			(prBranchID = 0 )			
		) and 
		(
			(tm.sourceWarehouseID = prWarehouseID and  prWarehouseID != 0)
			or 
			(prWarehouseID = 0 )
		) and 
		tm.companyID = prCompanyID and 
		tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 
		tm.isActive = 1 and 
		tmd.isActive = 1 and 
		ws.aplicable = 1;


	create table tb_tmp_comision_report (
	  orden varchar(50),
		ventaName varchar(250),		
		amountVenta decimal(19,2),
		amountCost decimal(19,2)
	);


	insert into tb_tmp_comision_report(orden,ventaName,amountVenta,amountCost)
	select 
		'001',
		'01) Ventas',
		0,
		sum(c.amountVenta) as Venta 
	from 
		tb_tmp_comision_venta_costo c ;
	

	insert into tb_tmp_comision_report(orden,ventaName,amountVenta,amountCost)
	select 
		'002',
		'02) Costo',
		0,
		sum(c.amountCost) as Venta 
	from 
		tb_tmp_comision_venta_costo c ;

	insert into tb_tmp_comision_report(orden,ventaName,amountVenta,amountCost) values 
	(
		'003',
		'03) Utilidad',
		0,
		(select x.amountCost from tb_tmp_comision_report x where x.orden = '001')
		-
		(select x.amountCost from tb_tmp_comision_report x where x.orden = '002')
	) ;

	
	insert into tb_tmp_comision_report(orden,ventaName,amountVenta,amountCost)
	select 
		'004',
		'04) Gastos',
		0,
		sum(c.amount) as Venta 
	from 
		tb_tmp_comision_gastos c ;
		
	insert into tb_tmp_comision_report(orden,ventaName,amountVenta,amountCost) values 
	(
		'005',
		'05) Utilidad Neta',
		0,
		(select x.amountCost from tb_tmp_comision_report x where x.orden = '001')
		-
		(select x.amountCost from tb_tmp_comision_report x where x.orden = '002')
		-
		(select x.amountCost from tb_tmp_comision_report x where x.orden = '004')
	) ;

	select 
		u.ventaName as Indicador , 
		u.amountVenta as Valor ,
		u.amountCost as Monto
	from 
		tb_tmp_comision_report u 
	order by 
		u.orden; 
		

	drop table tb_tmp_comision_report;
	drop table tb_tmp_comision_venta_costo;
	drop table tb_tmp_comision_gastos;
	drop table tb_tmp_comision_agente;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_sales_get_report_venta_de_producto
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_sales_get_report_venta_de_producto`;
delimiter ;;
CREATE PROCEDURE `pr_sales_get_report_venta_de_producto`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE,IN prInventoryCategoryID INT, IN prWarehouseID INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de ventas'
BEGIN
	DECLARE varCurrencyCompras INT DEFAULT 0;	
	DECLARE varCurrencyReporte INT DEFAULT 0;	
	DECLARE currencyIDNameCompra VARCHAR(250);
	DECLARE currencyIDNameReporte VARCHAR(250);	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);
	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);
	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		
	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

	select 
		rx.itemID,
		rx.itemNumber,
		rx.itemName,
		ix.quantity as quantityInAllWarehouse,
		if(fam.publicCatalogDetailID is null, famp.`name` , fam.`name` )  as family,		
		
		(
			select 
				GROUP_CONCAT(DISTINCT (CONCAT(nx.firstName," ",nx.lastName)) SEPARATOR ', <br/>')
			from 
				tb_provider_item pix 
				inner join  tb_naturales nx ON 
					nx.entityID = pix.entityID
			where 
				 pix.itemID = ix.itemID 
		) as provider,
		
		sum(rx.quantity) as quantity,		
		sum((rx.unitaryCost * rx.quantity)) as cost,
		sum((rx.unitaryPrice * rx.quantity)) as amount,
		sum((rx.unitaryPrice * rx.quantity) - (rx.unitaryCost * rx.quantity))    as utilidad ,
		ix.quantity + sum(rx.quantity)  as quantityInicial, 
		IFNULL(
			round(
				(
					sum(rx.quantity) / 
					(ix.quantity + sum(rx.quantity))
				) * 100,
				2
			),
			0
		) as percentageSales	
	from 
		(
				select 
					usr.userID,
					usr.nickname,
					tm.transactionNumber,
					IFNULL(nat_emp.firstName,'') as employerName,
					tc.name as tipo,
					tm.transactionOn,
					cus.customerNumber,
					l.legalName,
					ci.name as zone,
					i.itemID,
					i.itemNumber,
					i.name as itemName,
					cat.`name` as nameCategory,
					tmd.quantity,
					tm.currencyID,
					tm.exchangeRate,
					tm.createdOn,
					case 
						when varCurrencyReporte = tm.currencyID then 
							tmd.unitaryPrice 
						when tm.exchangeRate > 1 then 
							tm.exchangeRate * (tmd.unitaryPrice)
						else 
							(1/tm.exchangeRate) * (tmd.unitaryPrice)
					end unitaryPrice,
					case 
						when varCurrencyCompras = varCurrencyReporte  then 				
							tmd.unitaryCost
						when tm.exchangeRate > 1 then 
							tm.exchangeRate *  tmd.unitaryCost														
						else 								
						  (1/tm.exchangeRate) *   tmd.unitaryCost							
					end  unitaryCost ,
					IFNULL(tmd.tax1,0) as  iva ,
					IFNULL(amountCommision,0) as amountCommision
				from 
					tb_transaction_master tm 
					inner join tb_transaction_master_detail tmd on 
						tm.companyID = tmd.companyID and 
						tm.transactionID = tmd.transactionID and 
						tm.transactionMasterID = tmd.transactionMasterID 
					inner join tb_transaction_causal tc on 
						tm.transactionCausalID = tc.transactionCausalID 
					inner join tb_customer cus on 
						tm.entityID = cus.entityID 
					inner join tb_legal l on 
						cus.entityID = l.entityID 
					inner join tb_user usr on 
						tm.createdBy = usr.userID 
					inner join tb_workflow_stage ws on 
						tm.statusID = ws.workflowStageID 
					inner join tb_transaction_master_info tmi on 
						tm.companyID = tmi.companyID and 
						tm.transactionID = tmi.transactionID and 
						tm.transactionMasterID = tmi.transactionMasterID 
					inner join tb_catalog_item ci on 
						tmi.zoneID = ci.catalogItemID 
					inner join tb_item i on 
						tmd.componentItemID = i.itemID 
					inner join tb_item_category cat on 
						cat.inventoryCategoryID = i.inventoryCategoryID 
					left join tb_naturales nat_emp on 
						nat_emp.entityID = tm.entityIDSecondary 
				where
					tm.companyID = prCompanyID and 
					tm.transactionOn between prStartOn  and prEndOn   and 
					tm.isActive = 1 and 
					tmd.isActive = 1 and 
					tm.transactionID in (19 /*FACTURA*/) and 
					ws.aplicable = 1 and 
					ws.workflowStageID in (67 /*APLICADA*/) and 
					(
						prInventoryCategoryID = 0 
						or 
						(prInventoryCategoryID != 0 and i.inventoryCategoryID = prInventoryCategoryID )
					) and 
					(
						 ( tm.sourceWarehouseID = prWarehouseID and prWarehouseID != 0 ) 
						 or 
						 (prWarehouseID = 0 )
					)
				order by 
					tm.transactionMasterID asc, 
					tmd.transactionMasterDetailID asc
		) rx
		inner join  tb_item ix on 
			ix.itemID = rx.itemID  
		left join tb_public_catalog_detail fam on 
			fam.publicCatalogDetailID = ix.familyID 
		left join tb_catalog_item famp on 
			famp.catalogItemID = ix.familyID 	
	group by 
		rx.itemID, 
		rx.itemNumber,
		rx.itemName,
		ix.quantity ,
		fam.`name` ; 
		
		
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_sales_get_repor_sales_by_reference
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_sales_get_repor_sales_by_reference`;
delimiter ;;
CREATE PROCEDURE `pr_sales_get_repor_sales_by_reference`(IN `prCompanyID` int,IN `prTokenID` varchar(50),IN `prUserID` int,IN `prStartOn` date,IN `prEndOn` date,IN `prInventoryCategoryID` int,IN `prWarehouse` int)
BEGIN

	

	DECLARE varCurrencyCompras INT DEFAULT 0;	

	DECLARE varCurrencyReporte INT DEFAULT 0;		

	DECLARE currencyIDNameCompra VARCHAR(250);

	DECLARE currencyIDNameReporte VARCHAR(250);	

	

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameCompra);

	CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameReporte);

	

	SET varCurrencyCompras 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameCompra);		

	SET varCurrencyReporte 			= (SELECT currencyID FROM tb_currency where name = currencyIDNameReporte);		

		

	select   

		rx.doctor,

	   

		rx.doctor as employerName,

		rx.transactionNumber,		

		rx.tipo,	

		rx.currencyName,	

		rx.customerNumber,							

		rx.legalName,	

		varCurrencyReporte,

		sum((rx.unitaryPrice * rx.quantity) + (rx.iva * rx.quantity))  as amountConIva					 

	from  

		(   

				select 		

					doctor.`name` as doctor,

					tm.transactionNumber,

					IFNULL(CONCAT(nat_emp.firstName,' ',nat_emp.lastName),'') as employerName,

					tc.name as tipo,				

					cus.customerNumber,

					concat(nat_cus.firstName,' ', nat_cus.lastName) as legalName ,				

					tmd.quantity as quantity,

					cur.`name` as currencyName,	

					case 

						when varCurrencyReporte = tm.currencyID then 

							tmd.unitaryPrice 

						when tm.exchangeRate > 1 then 

							tm.exchangeRate * (tmd.unitaryPrice)

						else 

							(1/tm.exchangeRate) * (tmd.unitaryPrice)

					end unitaryPrice,

					case 

						when varCurrencyReporte = tm.currencyID  then 				

							IFNULL(tmd.tax1,0)

						when tm.exchangeRate > 1 then 

							tm.exchangeRate *  IFNULL(tmd.tax1,0)

						else 								

						  (1/tm.exchangeRate) *   IFNULL(tmd.tax1,0)

					end as iva 																																																										

				from 

					tb_transaction_master tm  					

					inner join tb_transaction_master_detail tmd on 

						tm.companyID = tmd.companyID and 

						tm.transactionID = tmd.transactionID and 

						tm.transactionMasterID = tmd.transactionMasterID 

					inner join tb_currency cur on 

						cur.currencyID = tm.currencyID 

					inner join tb_transaction_causal tc on 

						tm.transactionCausalID = tc.transactionCausalID 

					inner join tb_customer cus on 

						tm.entityID = cus.entityID 

					inner join tb_legal l on 

						cus.entityID = l.entityID 

					inner join tb_naturales nat_cus on 

						nat_cus.entityID   = l.entityID 

					inner join tb_user usr on 

						tm.createdBy = usr.userID 

					inner join tb_workflow_stage ws on 

						tm.statusID = ws.workflowStageID 

					inner join tb_transaction_master_info tmi on 

						tm.companyID = tmi.companyID and 

						tm.transactionID = tmi.transactionID and 

						tm.transactionMasterID = tmi.transactionMasterID 

					inner join tb_catalog_item ci on 

						tmi.zoneID = ci.catalogItemID 

					inner join tb_item i on 

						tmd.componentItemID = i.itemID 

					inner join tb_catalog_item doctor on 

						doctor.catalogItemID = tmi.mesaID 

					inner join tb_item_category cat on 

						cat.inventoryCategoryID = i.inventoryCategoryID 

					left join tb_naturales nat_emp on 

						nat_emp.entityID = tm.entityIDSecondary 

				where  					

					tm.companyID = prCompanyID and 

					tm.createdOn between prStartOn  and concat(prEndOn,' 23:59:59')   and 

					tm.isActive = 1 and 

					tmd.isActive = 1 and 

					ws.aplicable = 1 and 

					(

						prInventoryCategoryID = 0 

						or 

						(prInventoryCategoryID != 0 and i.inventoryCategoryID = prInventoryCategoryID )

					)  and 

					(

						prWarehouse = 0 

						or 

						(

							prWarehouse != 0 and

							tm.sourceWarehouseID =  prWarehouse

					  )

					) 					

				order by 

					tm.transactionMasterID asc, tmd.transactionMasterDetailID asc			

		) rx

group by

		rx.doctor,

    rx.transactionNumber,

    rx.employerName,

    rx.tipo,

    rx.customerNumber,

    rx.currencyName,

    rx.legalName,

		varCurrencyReporte		

ORDER BY 

		rx.doctor,  rx.transactionNumber;



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_transaction_master_detail
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_transaction_master_detail`;
delimiter ;;
CREATE PROCEDURE `pr_transaction_master_detail`(IN `prCompanyID` int,IN `prTransactionID` int,IN `prTransactionMasterID` int)
BEGIN



	IF prTransactionID = 16 THEN 	 

				SELECT 

					i.itemNumber,

					i.`name` as itemName,

					td.quantity 

				FROM 

					tb_transaction_master_detail td 

					inner join tb_item i on 

						i.itemID = td.componentItemID 

				WHERE 

					td.transactionMasterID = prTransactionMasterID and 

					td.isActive = 1

				ORDER BY 

						i.`name`;

	END IF ;	

	



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_transaction_report_registradas_anuladas
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_transaction_report_registradas_anuladas`;
delimiter ;;
CREATE PROCEDURE `pr_transaction_report_registradas_anuladas`(IN `prCompanyID` INT, IN `prTokenID` VARCHAR(50), IN `prUserID` INT, IN `prStartOn` DATE, IN `prEndOn` DATE)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Detalle de Transacciones Anuladas'
BEGIN



	SELECT 

		c.createdOn , 

		c.transactionNumber,

		ws.`name` as statusName,

		c.amount as monto ,

		t.`name` as transactionName 

	from 

		tb_transaction_master c 

		inner join tb_transaction t on 

			c.transactionID = t.transactionID 

		inner join tb_workflow_stage ws on 

			ws.workflowStageID = c.statusID 

	where 

		(

			c.isActive = 0 

			or 	

			ws.aplicable = 0 

		)

		and 

		c.companyID = prCompanyID 

		and c.createdOn between prStartOn  and concat(prEndOn,' 23:59:59'); 

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_transaction_revert
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_transaction_revert`;
delimiter ;;
CREATE PROCEDURE `pr_transaction_revert`(IN `prCompanyID` INT, IN `prTransactionIDOriginal` INT, IN `prTransactionMasterIDOriginal` BIGINT, IN `prTransactionIDRevert` INT, IN `prTransactionMasterIDRevert` BIGINT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento que se utiliza para revertir una transaccion'
BEGIN

	DECLARE transactionNumber VARCHAR(50) DEFAULT '';
	DECLARE transactionNumberOriginal VARCHAR(50) DEFAULT '';
	DECLARE statusIDTransactionInit INT DEFAULT 0;
	DECLARE statusIDTransactionAnulada INT DEFAULT 0;
	DECLARE branchID INT DEFAULT 0;
	DECLARE transactionInfoNumberOriginal VARCHAR(50) DEFAULT '';
	DECLARE transactionInfoNumber VARCHAR(50) DEFAULT '';
	DECLARE prEntityID INT DEFAULT 0;
	DECLARE prOldPoints INT DEFAULT 0;
	DECLARE prNewPoints INT DEFAULT 0;
	DECLARE prTransPoints INT DEFAULT 0;

	SET transactionNumberOriginal = (
			SELECT tm.transactionNumber 
			FROM tb_transaction_master tm 
			where 
				tm.companyID = prCompanyID and 
				tm.transactionID = prTransactionIDOriginal 
				and tm.transactionMasterID = prTransactionMasterIDOriginal limit 1);

	SET branchID = (
		SELECT tm.branchID 
		FROM tb_transaction_master tm 
		where 
			tm.companyID = prCompanyID and 
			tm.transactionID = prTransactionIDOriginal and 
			tm.transactionMasterID = prTransactionMasterIDOriginal limit 1);
			
	SET transactionInfoNumberOriginal = COALESCE((
		SELECT CAST(tmi.transactionMasterInfoID AS UNSIGNED)
		FROM tb_transaction_master_info tmi
		WHERE tmi.transactionMasterID = prTransactionMasterIDOriginal
		LIMIT 1
		), 0);

	CALL pr_core_get_parameter_value (prCompanyID,'INVOICE_BILLING_ANULADAS',statusIDTransactionAnulada);	
	CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_transaction_master_billing_revertion","statusID",statusIDTransactionInit );		
	CALL pr_core_get_next_number(prCompanyID,'tb_transaction_master_billing_revertion',branchID,0,transactionNumber);							 	

	INSERT INTO tb_transaction_master (	
		companyID,transactionID,transactionNumber,branchID,transactionCausalID,entityID,
		transactionOn,statusIDChangeOn,componentID,note,sign,currencyID,currencyID2,
		exchangeRate,reference1,reference2,reference3,reference4,statusID,amount,
		isApplied,journalEntryID,classID,areaID,priorityID,sourceWarehouseID,
		targetWarehouseID,createdBy,createdAt,createdOn,createdIn,isActive,
		discount,subAmount,tax1,tax2,tax3,tax4 , entityIDSecondary, 
		transactionOn2,descriptionReference,isTemplate,periodPay, nextVisit,numberPhone,notificationID,printerQuantity,dayExcluded 		
	)	
	select 
		tm.companyID,prTransactionIDRevert,transactionNumber,tm.branchID,tm.transactionCausalID,tm.entityID,
		CURRENT_DATE(),NOW(),tm.componentID,tm.note,(tm.sign * -1),tm.currencyID,tm.currencyID2,
		tm.exchangeRate,prTransactionIDOriginal,prTransactionMasterIDOriginal,transactionNumberOriginal,tm.reference4,statusIDTransactionInit,tm.amount,
		1,0,tm.classID,tm.areaID,tm.priorityID,tm.targetWarehouseID,tm.sourceWarehouseID,
		tm.createdBy,tm.createdAt,tm.createdOn,tm.createdIn,tm.isActive,
		tm.discount,tm.subAmount,tm.tax1,tm.tax2,tm.tax3,tm.tax4 , tm.entityIDSecondary   ,
		tm.transactionOn2,tm.descriptionReference,tm.isTemplate,tm.periodPay, tm.nextVisit,tm.numberPhone,tm.notificationID,tm.printerQuantity,tm.dayExcluded 		
	from 
		tb_transaction_master tm
	where
		tm.companyID = prCompanyID and 
		tm.transactionID = prTransactionIDOriginal and 
		tm.transactionMasterID = prTransactionMasterIDOriginal;
	

	SET prTransactionMasterIDRevert = LAST_INSERT_ID();	

	INSERT INTO tb_transaction_master_detail (
			companyID,transactionID,transactionMasterID,componentID,componentItemID,
			promotionID,amount,cost,quantity,discount,unitaryAmount,unitaryCost,unitaryPrice,reference1,
			reference2,reference3,catalogStatusID,inventoryStatusID,isActive,quantityStock,
			quantiryStockInTraffic,quantityStockUnaswared,remaingStock,expirationDate,
			inventoryWarehouseSourceID,inventoryWarehouseTargetID,tax1,tax2,tax3,tax4,
			reference4,reference5,reference6,reference7,
			descriptionReference,exchangeRateReference,lote,itemFormulatedApplied,typePriceID,skuCatalogItemID,
			skuQuantity,skuQuantityBySku,skuFormatoDescription,itemNameLog,amountCommision,itemNameDescriptionLog 
	)
	SELECT 
			tm.companyID,prTransactionIDRevert,prTransactionMasterIDRevert,tm.componentID,tm.componentItemID,
			tm.promotionID,tm.amount,tm.cost,tm.quantity,tm.discount,tm.unitaryAmount,tm.unitaryCost,tm.unitaryPrice,tm.reference1,
			tm.reference2,tm.reference3,tm.catalogStatusID,tm.inventoryStatusID,tm.isActive,tm.quantityStock,
			tm.quantiryStockInTraffic,tm.quantityStockUnaswared,tm.remaingStock,tm.expirationDate,
			tm.inventoryWarehouseTargetID,tm.inventoryWarehouseSourceID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,
			tm.reference4,tm.reference5,tm.reference6,tm.reference7,
			tm.descriptionReference,tm.exchangeRateReference,tm.lote,tm.itemFormulatedApplied,tm.typePriceID,tm.skuCatalogItemID,
			tm.skuQuantity,tm.skuQuantityBySku,tm.skuFormatoDescription,tm.itemNameLog,tm.amountCommision,tm.itemNameDescriptionLog 
	FROM 
		tb_transaction_master_detail tm 
	WHERE 
		tm.companyID = prCompanyID and
		tm.transactionID = prTransactionIDOriginal and 
		tm.transactionMasterID = prTransactionMasterIDOriginal; 
	
	
	INSERT INTO tb_transaction_master_concept (
		companyID,transactionID,transactionMasterID,
		componentID,componentItemID,conceptID,value,currencyID,exchangeRate )
	select 
		tm.companyID,prTransactionIDRevert,prTransactionMasterIDRevert,
		tm.componentID,tm.componentItemID,tm.conceptID,tm.value,tm.currencyID,tm.exchangeRate 
	from 
		tb_transaction_master_concept tm
	WHERE 
		tm.companyID = prCompanyID and
		tm.transactionID = prTransactionIDOriginal and 
		tm.transactionMasterID = prTransactionMasterIDOriginal; 	
  
  
	IF transactionInfoNumberOriginal > 0
	THEN 
  
		/* Crear transaction_master_info_reverse	al anualr una factura*/
		INSERT INTO tb_transaction_master_info ( companyID, transactionID, transactionMasterID, zoneID, routeID, mesaID, referenceClientName, 
		referenceClientIdentifier, changeAmount, receiptAmountPoint, receiptAmount, receiptAmountDol, reference1, reference2, receiptAmountBank, receiptAmountBankID, 
		receiptAmountBankReference, receiptAmountBankDol, receiptAmountBankDolID, receiptAmountBankDolReference, receiptAmountCard, receiptAmountCardBankID, 
		receiptAmountCardBankReference, receiptAmountCardDol, receiptAmountCardBankDolID, receiptAmountCardBankDolReference)
		SELECT tmi.companyID, prTransactionIDRevert, prTransactionMasterIDRevert, tmi.zoneID, tmi.routeID, tmi.mesaID, tmi.referenceClientName, 
		tmi.referenceClientIdentifier, tmi.changeAmount, tmi.receiptAmountPoint, tmi.receiptAmount, tmi.receiptAmountDol, tmi.reference1, tmi.reference2, tmi.receiptAmountBank, 
		tmi.receiptAmountBankID, tmi.receiptAmountBankReference, tmi.receiptAmountBankDol, tmi.receiptAmountBankDolID, tmi.receiptAmountBankDolReference, tmi.receiptAmountCard, tmi.receiptAmountCardBankID, 
		tmi.receiptAmountCardBankReference, tmi.receiptAmountCardDol, tmi.receiptAmountCardBankDolID, tmi.receiptAmountCardBankDolReference 
		FROM tb_transaction_master_info tmi
		WHERE tmi.transactionMasterInfoID = transactionInfoNumberOriginal limit 1;
    
    
		/*Se hace la reversion de puntos al anular una factura*/
		SET prEntityID = (SELECT tm.entityID 
		FROM tb_transaction_master tm 
		where tm.companyID = prCompanyID and tm.transactionMasterID = prTransactionMasterIDOriginal limit 1);
        
		
		SET prTransPoints = (SELECT 
		 tmi.receiptAmountPoint
		FROM tb_transaction_master_info tmi
		WHERE tmi.transactionMasterID = prTransactionMasterIDOriginal limit 1);
    
		SET prOldPoints = (SELECT c.balancePoint FROM tb_customer c WHERE c.entityID = prEntityID);
		SET prNewPoints = prOldPoints+prTransPoints;
    
		UPDATE tb_customer SET balancePoint = prNewPoints WHERE entityID = prEntityID;  
    
	END IF;

	CALL pr_inventory_calculate_kardex_new_input (prCompanyID,prTransactionIDRevert,prTransactionMasterIDRevert);

	UPDATE tb_transaction_master set 
		statusID = statusIDTransactionAnulada 
	where 
			companyID = prCompanyID and 
			transactionID = prTransactionIDOriginal and 
			transactionMasterID = prTransactionMasterIDOriginal;
      
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_zerror_reparar_kardex
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_zerror_reparar_kardex`;
delimiter ;;
CREATE PROCEDURE `pr_zerror_reparar_kardex`(IN `prItemID` INT  , IN prWarehouseID INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Reparar kardex de un producto en especifico, en una bodega '
BEGIN



	declare minDocumentID_ int default 0;

	declare maxDocumentID_ int default 0;

	declare balanceQuantity decimal(19,2) default 0;

	declare balanceTotal decimal(19,2) default 0;

	

	

	CREATE TABLE tmp_reparar_t (		

				kardexID int ,

				inicial decimal(19,2),

				cantidad decimal(19,2),

				final decimal(19,2),

				sign decimal(19,2),

				transactionMasterID int

	); 

	

  

	INSERT INTO tmp_reparar_t (kardexID,inicial,cantidad,final,sign,transactionMasterID) 

	select 

			k.kardexID,

			k.oldQuantityWarehouse,

			k.transactionQuantity,

			k.newQuantityWarehouse ,

			k.sign,

			k.transactionMasterID 

	from 

			tb_kardex k 

	where 

			k.itemID = prItemID and 

			k.warehouseID = prWarehouseID ;

			



		

			

	set minDocumentID_ = (select min(kardexID) from tmp_reparar_t);

	set maxDocumentID_ = (select max(kardexID) from tmp_reparar_t);

		

	while minDocumentID_ <= maxDocumentID_ and minDocumentID_ is not null do 

			

		update tb_kardex set oldQuantityWarehouse = balanceQuantity where kardexID = minDocumentID_;

		

		

	  set balanceQuantity = balanceQuantity + (

				select k.cantidad * k.sign from tmp_reparar_t k where k.kardexID =  minDocumentID_ 

		);		

		

		

		update tb_kardex set newQuantityWarehouse = balanceQuantity where kardexID = minDocumentID_;

		

		

		set minDocumentID_ 	= (select min(kardexID) from tmp_reparar_t where kardexID > minDocumentID_);

	end while;

	

	

	update tb_item_warehouse set quantity = balanceQuantity 

	where 

		itemID = prItemID and 

		warehouseID = prWarehouseID;

		

	

	set balanceTotal = 0;

	set balanceTotal = (select sum(c.quantity) from tb_item_warehouse c where c.itemID = prItemID);

	update tb_item set quantity = balanceTotal where itemID = prItemID;

	

	

	

	

	set balanceQuantity = 0;

	delete from tmp_reparar_t;

	

	INSERT INTO tmp_reparar_t (kardexID,inicial,cantidad,final,sign) 

	select 

			k.kardexID,

			k.oldQuantity,

			k.transactionQuantity,

			k.newQuantity ,

			k.sign

	from 

			tb_kardex k

	where 

			k.itemID = prItemID;

			

			

	set minDocumentID_ = (select min(kardexID) from tmp_reparar_t);

	set maxDocumentID_ = (select max(kardexID) from tmp_reparar_t);

		

	while minDocumentID_ <= maxDocumentID_ and minDocumentID_ is not null do 

			

		update tb_kardex set oldQuantity = balanceQuantity where kardexID = minDocumentID_;

		

		

	  set balanceQuantity = balanceQuantity + (

				select k.cantidad * k.sign from tmp_reparar_t k where k.kardexID =  minDocumentID_ 

		);		

		

		

		update tb_kardex set newQuantity = balanceQuantity where kardexID = minDocumentID_;

		

		

		set minDocumentID_ 	= (select min(kardexID) from tmp_reparar_t where kardexID > minDocumentID_);

	end while;

	

	

  DROP TABLE tmp_reparar_t;	

	select 'success' as mensaje; 

	

	

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_zerror_reparar_tabla_amortization_dias_para_gym_raptor
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_zerror_reparar_tabla_amortization_dias_para_gym_raptor`;
delimiter ;;
CREATE PROCEDURE `pr_zerror_reparar_tabla_amortization_dias_para_gym_raptor`(IN `prItemID` INT , IN `prDay` INT)
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Reparar facturas, que se facturaron como semanales, y la frecuencia de pago quedo mensual'
BEGIN



	declare minDocumentID_ int default 0;

	declare maxDocumentID_ int default 0;

	declare minAmorID_ int default 0;

	declare maxAmorID_ int default 0;

	declare dayX datetime ;

	declare countar int default 0;

	

	

		CREATE TEMPORARY TABLE tmp_reparar (		

				entityID int ,

				customerCreditDocumentID INT,

				creditAmortizationID INT,

				creditAmortizationIDMax INT,

				dateApply datetime

			); 

	

	

	 INSERT INTO tmp_reparar (entityID,customerCreditDocumentID,creditAmortizationID,creditAmortizationIDMax,dateApply) 

		select 

			ccd.entityID,

			ccd.customerCreditDocumentID,		

			min(am.creditAmortizationID) as creditAmortizationID ,

			max(am.creditAmortizationID) as creditAmortizationIDMax ,

			min(am.dateApply) as dateApply 

		from 

			tb_transaction_master tm 

			inner join tb_transaction_master_detail td on 

				tm.transactionMasterID = td.transactionMasterID 

			inner JOIN tb_customer_credit_document ccd on 

				ccd.documentNumber = tm.transactionNumber 

			inner join tb_customer_credit_amoritization am on 

				am.customerCreditDocumentID = ccd.customerCreditDocumentID 

		where

			td.componentItemID = prItemID 

		group by 

			ccd.customerCreditDocumentID,

			tm.transactionMasterID,

			tm.transactionNumber;

			

			

	set minDocumentID_ = (select min(customerCreditDocumentID) from tmp_reparar);

	set maxDocumentID_ = (select max(customerCreditDocumentID) from tmp_reparar);

		

	while minDocumentID_ <= maxDocumentID_ and minDocumentID_ is not null do 

		set dayX 						= (select min(dateApply) from tmp_reparar where customerCreditDocumentID = minDocumentID_);

		set minAmorID_ = (select min(creditAmortizationID) from tmp_reparar where customerCreditDocumentID = minDocumentID_);

		set maxAmorID_ = (select max(creditAmortizationIDMax) from tmp_reparar where customerCreditDocumentID = minDocumentID_);

		set countar = 0;

		

		

		while minAmorID_ <= maxAmorID_ and minAmorID_ is not null do 

		

			

			if countar > 0 then 

				set dayX 	= date_add(dayX,interval prDay day);

				update tb_customer_credit_amoritization set dateApply = dayX where creditAmortizationID = minAmorID_;

			end if;

			

			set countar = countar + 1;

			set minAmorID_ 	= (select min(creditAmortizationID) from tb_customer_credit_amoritization 

					where customerCreditDocumentID = minDocumentID_ and creditAmortizationID > minAmorID_ );

		end while;

		

		

		set minDocumentID_ 	= (select min(customerCreditDocumentID) from tmp_reparar where customerCreditDocumentID > minDocumentID_);

	end while;

	

	select * from tmp_reparar;

	drop table tmp_reparar;



END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for pr_zerror_trasladar_todo_a_bodega_despacho
-- ----------------------------
DROP PROCEDURE IF EXISTS `pr_zerror_trasladar_todo_a_bodega_despacho`;
delimiter ;;
CREATE PROCEDURE `pr_zerror_trasladar_todo_a_bodega_despacho`()
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Trasladar todo a bodega despacho .. '
BEGIN



		update tb_item_warehouse, tb_item 

			set tb_item_warehouse.quantity = tb_item.quantity

		where 

			tb_item_warehouse.itemID = tb_item.itemID;

			

		update tb_item_warehouse set quantity = 0 where warehouseID != 4; 

		

		update tb_transaction_master set tb_transaction_master.targetWarehouseID = 4; 

		

		update tb_kardex set warehouseID = 4; 

		

	END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for _Navicat_Temp_Stored_Proc
-- ----------------------------
DROP PROCEDURE IF EXISTS `_Navicat_Temp_Stored_Proc`;
delimiter ;;
CREATE PROCEDURE `_Navicat_Temp_Stored_Proc`(IN `prCompanyID` INT, IN `prBranchID` INT, IN `prLoginID` INT, IN `prCreatedIn` VARCHAR(50), IN `prTocken` VARCHAR(250), IN `prPeriodID` INT, IN `prCycleID` INT, OUT `prCodeError` INT, OUT `prMessageResult` VARCHAR(250))
  MODIFIES SQL DATA 
  SQL SECURITY INVOKER
  COMMENT 'Procedimiento para Cerrar un Ciclo Contable'
LBL_PROCEDURE:

BEGIN

	DECLARE componentID_ INT DEFAULT 4;		

	DECLARE workflowStageClosedPeriod_ INT DEFAULT 0;

	DECLARE workflowStageClosedCycle_ INT DEFAULT 0;

	DECLARE journalTypeIDCierre_ INT DEFAULT 0;	

	DECLARE utilityValue_ DECIMAL(19,8) DEFAULT 0;

	DECLARE totalDebit_ DECIMAL (18,8) DEFAULT 0;

	DECLARE totalCredit_ DECIMAL (18,8) DEFAULT 0;

	DECLARE exchangeRate_ DECIMAL(18,8) DEFAULT 0;	

	DECLARE workflowStageInitOfJournal_ INT DEFAULT 0;	

	DECLARE oldCycleID_ INT DEFAULT 0;

	DECLARE nextCycleID_ INT DEFAULT 0;

	DECLARE nextPeriodID_ INT DEFAULT 0;

	DECLARE currencyID_ INT DEFAULT 0;

	DECLARE currencyIDTarget_ INT DEFAULT 0;

	DECLARE currencyIDNameSource VARCHAR(250);

	DECLARE currencyIDNameTarget VARCHAR(250);	

	DECLARE accountIDResult_ INT DEFAULT 0;

	DECLARE journalEntryID_ INT DEFAULT 0;

	DECLARE resultTemp_ INT DEFAULT 0;	

	DECLARE journalNumber_ VARCHAR(50);

	DECLARE companyName_ VARCHAR(50);

	DECLARE accountNumber_ VARCHAR(50);

	DECLARE accountTypeResult VARCHAR(150);



		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_ACCOUNTTYPE_RESULT",accountTypeResult);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_JOURNALTYPE_CLOSED",journalTypeIDCierre_);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",workflowStageClosedCycle_);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED",workflowStageClosedPeriod_);

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_FUNCTION",currencyIDNameSource);

	SET currencyID_ 		= (SELECT currencyID FROM tb_currency where name = currencyIDNameSource);		

		CALL pr_core_get_parameter_value(prCompanyID,"ACCOUNTING_CURRENCY_NAME_REPORT",currencyIDNameTarget);

	SET currencyIDTarget_ 	= (SELECT currencyID FROM tb_currency where name = currencyIDNameTarget);

		CALL pr_core_get_exchange_rate (prCompanyID,CURDATE(),currencyIDNameSource,currencyIDNameTarget,exchangeRate_);	

		SET companyName_ 		= (select name from tb_company where companyID = prCompanyID);	

		CALL pr_core_get_workflow_stage_init (prCompanyID,"tb_journal_entry","statusID",workflowStageInitOfJournal_ );	

		CALL pr_core_get_next_number (prCompanyID,"tb_journal_entry",prBranchID,journalTypeIDCierre_,journalNumber_);		

		CALL pr_core_get_parameter_value(prCompanyID,'ACCOUNTING_NUMBER_UTILITY_ACUMULATE',accountNumber_);

		

	SET accountIDResult_ = (

		SELECT accountID FROM tb_account where isActive = 1 and 

		companyID = prCompanyID and accountNumber = accountNumber_ LIMIT 1

	);	

		

	SET oldCycleID_ 		= (

		SELECT 

			cc.componentCycleID 

		FROM 	

			tb_accounting_cycle cc inner join 

			tb_accounting_period cp on 

			cp.companyID = cc.companyID and 	

			cp.componentID = cc.componentID and 

			cp.componentPeriodID = cc.componentPeriodID 

		WHERE 	

			cc.companyID = prCompanyID AND 	

			cc.isActive = 1 and 	

			cp.isActive = 1 and 	

			cc.componentID = componentID_ AND 	

			cc.endOn < (		

						select 			

							cc2.startOn  		

						from 			

							tb_accounting_cycle cc2 		

						where 			

							cc2.componentCycleID = prCycleID 	

			) 

		ORDER BY 	

			cc.endOn DESC LIMIT 1 

		);

			

		SET nextCycleID_  	= (SELECT cc.componentCycleID FROM 	tb_accounting_cycle cc inner join tb_accounting_period cp on cp.companyID = cc.companyID and 	cp.componentID = cc.componentID and cp.componentPeriodID = cc.componentPeriodID WHERE 	cc.companyID = prCompanyID AND 	cc.isActive = 1 and 	cp.isActive = 1 and 	cc.componentID = componentID_ AND 	cc.startOn > (		select 			cc2.endOn  		from 			tb_accounting_cycle cc2 		where 			cc2.componentCycleID = prCycleID 	) ORDER BY 	cc.startOn ASC LIMIT 1 );	

	SET nextPeriodID_ 	= (SELECT componentPeriodID FROM tb_accounting_cycle WHERE componentCycleID = nextCycleID_);	

		SET totalDebit_ 	= (SELECT SUM(ab.debit) from tb_accounting_balance ab inner join tb_account a on ab.companyID = a.companyID and ab.accountID = a.accountID  where ab.isActive = 1 and ab.companyID = prCompanyID and ab.componentID = componentID_ and ab.componentPeriodID = prPeriodID and ab.componentCycleID = prCycleID and a.isActive = 1 and a.parentAccountID IS NULL);

	SET totalCredit_	= (SELECT SUM(ab.credit) from tb_accounting_balance  ab inner join tb_account a on ab.companyID = a.companyID and ab.accountID = a.accountID where ab.isActive = 1 and ab.companyID = prCompanyID and ab.componentID = componentID_ and ab.componentPeriodID = prPeriodID and ab.componentCycleID = prCycleID and a.isActive = 1 and a.parentAccountID IS NULL );

		IF ((oldCycleID_ IS NOT NULL ) AND ((SELECT componentCycleID FROM tb_accounting_cycle where componentCycleID = oldCycleID_ and statusID <>  workflowStageClosedCycle_) IS NOT NULL ) ) THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'EL CICLO ANTERIOR DEBE DE  ESTAR CERRADO...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',1,'El cilo anterior debe de estar cerrado',CURRENT_TIMESTAMP());

		LEAVE LBL_PROCEDURE;

	END IF;	

		IF nextCycleID_ IS NULL THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'NO PUEDE CERRAR EL CICLO, NO EXISTE UN SIGUIENTE CICLO CONTABLE...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',1,'No puede cerrar el ciclo, no existe un siguiente ciclo contable',CURRENT_TIMESTAMP());

		LEAVE LBL_PROCEDURE;

	END IF;

		IF ((prCycleID IS NOT NULL) AND ((SELECT componentCycleID FROM tb_accounting_cycle where componentCycleID = prCycleID and statusID =  workflowStageClosedCycle_) IS NOT NULL )) THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'EL CICLO ACTUAL NO DEBE DE ESTAR CERRADO...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',1,'El ciclo actual no debe de estar cerrado',CURRENT_TIMESTAMP());

		LEAVE LBL_PROCEDURE;

	END IF;

		IF ((nextCycleID_ IS NOT NULL) AND ((SELECT componentCycleID FROM tb_accounting_cycle where componentCycleID = nextCycleID_ and statusID =  workflowStageClosedCycle_) IS NOT NULL )) THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'EL CICLO SIGUIENTE NO DEBE DE ESTAR CERRADO...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',1,'El ciclo siguiente no debe de estar cerrado',CURRENT_TIMESTAMP());

		LEAVE LBL_PROCEDURE;

	END IF;

		IF totalDebit_ <> totalCredit_ THEN

		SET prCodeError 	= 1;

		SET prMessageResult = 'LOS MOVIMIENTOS DE CICLO NO SON EQUIVALENTES, DEBITOS Y CREDITOS DIFIEREN EN IMPORTE...';

		INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

		VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',1,'Los movimientos del ciclo no son equivalente, debitos y creditos difieren en importe',CURRENT_TIMESTAMP());

		LEAVE LBL_PROCEDURE;

	END IF;

	

	

		CALL `pr_accounting_mayorizate_cycle` (prCompanyID ,prBranchID,prLoginID, prPeriodID , prCycleID ,resultTemp_); 

	

		DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;

	

		CALL `pr_accounting_mayorizate_cycle` (prCompanyID , prBranchID,prLoginID,nextPeriodID_ , nextCycleID_ ,resultTemp_); 	

	

		CALL `pr_accounting_initialize_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID);

	

		CALL `pr_accounting_calculate_utility` (prCompanyID , prBranchID , prLoginID , prTocken , prPeriodID , prCycleID , utilityValue_);

	

	

		IF nextPeriodID_ = prPeriodID THEN			

				UPDATE tb_accounting_balance , tb_accounting_balance_temp

			SET tb_accounting_balance.balance = tb_accounting_balance_temp.balanceEnd		

		WHERE

			tb_accounting_balance.accountID 			= tb_accounting_balance_temp.accountID AND 			

			tb_accounting_balance_temp.companyID 	= prCompanyID AND  

			tb_accounting_balance_temp.branchID 	= prBranchID AND 

			tb_accounting_balance_temp.loginID 		= prLoginID AND 

			tb_accounting_balance.companyID 			= prCompanyID AND 

			tb_accounting_balance.componentID 		= componentID_ AND 

			tb_accounting_balance.componentPeriodID	= nextPeriodID_ AND 

			tb_accounting_balance.componentCycleID		= nextCycleID_;		

	END IF;

	

	

		IF nextPeriodID_ <> prPeriodID THEN	

				CALL `pr_accounting_mayorizate_account_tmp` (prCompanyID , prBranchID , prLoginID , prTocken , accountIDResult_, 0, 0, 0, utilityValue_);

		

				UPDATE tb_accounting_balance , tb_accounting_balance_temp

			SET tb_accounting_balance.balance = tb_accounting_balance_temp.balanceEnd		

		WHERE

			tb_accounting_balance.accountID 			= tb_accounting_balance_temp.accountID AND 			

			tb_accounting_balance_temp.companyID 	= prCompanyID AND  

			tb_accounting_balance_temp.branchID 	= prBranchID AND 

			tb_accounting_balance_temp.loginID 		= prLoginID AND 

			tb_accounting_balance.companyID 			= prCompanyID AND 

			tb_accounting_balance.componentID 			= componentID_ AND 

			tb_accounting_balance.componentPeriodID	= nextPeriodID_ AND 

			tb_accounting_balance.componentCycleID		= nextCycleID_;	 	

			

		INSERT INTO tb_journal_entry (companyID,journalNumber,journalDate,tb_exchange_rate,createdOn,createdIn,createdAt,createdBy,isActive,isApplied,statusID,note,journalTypeID,currencyID,accountingCycleID,entryName)

		VALUES(prCompanyID,journalNumber_,CURDATE(),exchangeRate_,CURRENT_TIMESTAMP(),'::1',prBranchID,prLoginID,1,0,workflowStageInitOfJournal_,CONCAT(CAST(utilityValue_ AS DECIMAL(19,2)),'/UTILIDAD'),journalTypeIDCierre_,currencyID_,prCycleID,'APP-CIERRE');		

		SET journalEntryID_ = LAST_INSERT_ID();

	

			

				INSERT INTO tb_journal_entry_detail (journalEntryID,companyID,accountID,isActive,classID,debit,credit,note,isApplied,branchID,tb_exchange_rate) 

		SELECT 

			journalEntryID_ as journalEntryID,

			prCompanyID as companyID,

			a.accountID,

			1 as isActive,

			0 as classID,

			CASE 

				WHEN att.naturaleza = 'C' and t.balanceEnd > 0 THEN 

					t.balanceEnd

				WHEN att.naturaleza = 'D' and t.balanceEnd < 0 then 

					t.balanceEnd

			END as debit,

			CASE 

				WHEN att.naturaleza = 'D' and t.balanceEnd > 0 THEN 

					t.balanceEnd

				WHEN att.naturaleza = 'C' and t.balanceEnd < 0 THEN 

					t.balanceEnd

			END as credit,

			'' as note,

			1 as isApplied, 

			prBranchID as branchID,

			exchangeRate_ as exchangeRate

		FROM 

			tb_accounting_balance_temp t

			inner join tb_account a on 

				t.accountID = a.accountID 

			inner join tb_account_type att on 

				a.accountTypeID = att.accountTypeID 

		WHERE

			t.companyID 	= prCompanyID AND  

			t.branchID 		= prBranchID AND 

			t.loginID 		= prLoginID AND

			a.isOperative 	= 1 and 

			t.balanceEnd 	<> 0 and 

			a.accountNumber REGEXP accountTypeResult

		ORDER BY 

			a.accountNumber; 

			 

				INSERT INTO tb_journal_entry_detail (journalEntryID,companyID,accountID,isActive,classID,debit,credit,note,isApplied,branchID,tb_exchange_rate) 

		VALUES (

			journalEntryID_,

			prCompanyID ,

			accountIDResult_ , 

			1,

			0, 

			IF(utilityValue_ < 0 , utilityValue_ , 0) ,

			IF(utilityValue_ > 0 , utilityValue_ , 0) ,

			'' ,

			1 , 

			prBranchID ,

			exchangeRate_ );

			

				

				UPDATE tb_accounting_balance,tb_account 

			set tb_accounting_balance.balance = 0 

		where

			tb_accounting_balance.companyID 			= tb_account.companyID and 

			tb_accounting_balance.accountID 			= tb_account.accountID and 

			tb_accounting_balance.companyID 			= prCompanyID and 

			tb_accounting_balance.componentPeriodID 	= nextPeriodID_ and 

			tb_accounting_balance.componentCycleID 	= nextCycleID_ AND 

			tb_accounting_balance.branchID 			= prBranchID AND 

			tb_account.accountNumber REGEXP accountTypeResult;

		

	END IF;	

		

	

	

		DELETE FROM tb_accounting_balance_temp WHERE companyID = prCompanyID AND branchID = prBranchID AND loginID = prLoginID;		

		

		IF nextPeriodID_ <> prPeriodID THEN	

				UPDATE tb_accounting_period set statusID = workflowStageClosedPeriod_ WHERE componentPeriodID = prPeriodID;

				UPDATE tb_accounting_cycle set statusID = workflowStageClosedCycle_ WHERE  componentCycleID = prCycleID;

		ELSE

				UPDATE tb_accounting_cycle set statusID = workflowStageClosedCycle_ WHERE  componentCycleID = prCycleID;

	END IF;

	

	SET prCodeError 	= 0;

	SET prMessageResult = 'SUCCESS';

	INSERT INTO tb_log(companyID,branchID,loginID,token,procedureName,code,description,createdOn)

	VALUES(prCompanyID,prBranchID,prLoginID,prTocken,'pr_accounting_closed_cycle',0,'Success',CURRENT_TIMESTAMP());

	

END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tb_item
-- ----------------------------
DROP TRIGGER IF EXISTS `check_quantity_insert_tb_item`;
delimiter ;;
CREATE TRIGGER `check_quantity_insert_tb_item` BEFORE INSERT ON `tb_item` FOR EACH ROW BEGIN
	DECLARE dummy,baddata INT;
	SET baddata = 0;
	
	IF NEW.quantity < 0 THEN
		SET baddata = 1;
	END IF;
	
	
	
		
		
	
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tb_item
-- ----------------------------
DROP TRIGGER IF EXISTS `check_quantity_update_tb_item`;
delimiter ;;
CREATE TRIGGER `check_quantity_update_tb_item` BEFORE UPDATE ON `tb_item` FOR EACH ROW BEGIN
	 DECLARE dummy,baddata INT;  
    SET baddata = 0;  
    
    IF NEW.quantity < 0 THEN  
        SET baddata = 1;  
    END IF;  
    
    
        
        
    
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tb_item_warehouse
-- ----------------------------
DROP TRIGGER IF EXISTS `check_quantity_insert_tb_item_warehouse`;
delimiter ;;
CREATE TRIGGER `check_quantity_insert_tb_item_warehouse` BEFORE INSERT ON `tb_item_warehouse` FOR EACH ROW BEGIN
	DECLARE dummy,baddata INT;
	SET baddata = 0;
	
	IF NEW.quantity < 0 THEN
		SET baddata = 1;
	END IF;
	
	
	
		
		
	
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tb_item_warehouse
-- ----------------------------
DROP TRIGGER IF EXISTS `check_quantity_update_tb_item_warehouse`;
delimiter ;;
CREATE TRIGGER `check_quantity_update_tb_item_warehouse` BEFORE UPDATE ON `tb_item_warehouse` FOR EACH ROW BEGIN
	 DECLARE dummy,baddata INT;  
    SET baddata = 0;  
    
    IF NEW.quantity < 0 THEN  
        SET baddata = 1;  
    END IF;  
    
    
        
        
    
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
