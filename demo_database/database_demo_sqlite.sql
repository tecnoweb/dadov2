CREATE TABLE IF NOT EXISTS `base_fields` (
    `id` INTEGER(11) NOT NULL,
    `text` TEXT(50) NOT NULL,
    `text_area` TEXT NOT NULL,
    `text_editor` TEXT NOT NULL,
    `integer` INTEGER(11) NOT NULL,
    `float` REAL(5,2) NOT NULL,
    `enum` TEXT('one','two','three') NOT NULL,
    `set` TEXT('one','two','three') NOT NULL,
    `date` TEXT NOT NULL,
    `datetime` TEXT NOT NULL,
    `time` TEXT NOT NULL,
    `bool` INTEGER(1) NOT NULL,
    `point` TEXT NOT NULL
);

INSERT INTO `base_fields` (`id`, `text`, `text_area`, `text_editor`, `integer`, `float`, `enum`, `set`, `date`, `datetime`, `time`, `bool`, `point`) VALUES;
CREATE TABLE IF NOT EXISTS `consultation` (
    `id` INTEGER(11) NOT NULL,
    `name` TEXT(50) NOT NULL,
    `date` TEXT NOT NULL,
    `office` TEXT(50) NOT NULL,
    `manager` TEXT(50) NOT NULL,
    `country` INTEGER(11) NOT NULL,
    `region` INTEGER(11) NOT NULL,
    `city` INTEGER(11) NOT NULL
);

INSERT INTO `consultation` (`id`, `name`, `date`, `office`, `manager`, `country`, `region`, `city`) VALUES;
CREATE TABLE IF NOT EXISTS `customers` (
    `customerNumber` INTEGER(11) NOT NULL,
    `customerName` TEXT(50) NOT NULL,
    `contactLastName` TEXT(50) NOT NULL,
    `contactFirstName` TEXT(50) NOT NULL,
    `phone` TEXT(50) NOT NULL,
    `addressLine1` TEXT(50) NOT NULL,
    `addressLine2` TEXT(50) DEFAULT NULL,
    `city` TEXT(50) NOT NULL,
    `state` TEXT(50) DEFAULT NULL,
    `postalCode` TEXT(15) DEFAULT NULL,
    `country` TEXT(50) CHARACTER TEXT utf8 NOT NULL,
    `salesRepEmployeeNumber` INTEGER(11) DEFAULT NULL,
    `creditLimit` REAL DEFAULT NULL,
    `avatar` BLOB NOT NULL,
    `photo` TEXT(50) NOT NULL,
    `attach` TEXT(255) NOT NULL,
    `sex` TEXT('male','female') NOT NULL,
    `interests` TEXT('sports','programming','cars','girls','drinks','fights','history','cooking','shopping') NOT NULL
);

INSERT INTO `customers` (`customerNumber`, `customerName`, `contactLastName`, `contactFirstName`, `phone`, `addressLine1`, `addressLine2`, `city`, `state`, `postalCode`, `country`, `salesRepEmployeeNumber`, `creditLimit`, `avatar`, `photo`, `attach`, `sex`, `interests`) VALUES;
CREATE TABLE IF NOT EXISTS `customers_orders_fk` (
    `customer_id` INTEGER(11) NOT NULL,
    `order_id` INTEGER(11) NOT NULL
);

INSERT INTO `customers_orders_fk` (`customer_id`, `order_id`) VALUES;
CREATE TABLE IF NOT EXISTS `employees` (
    `employeeNumber` INTEGER(11) NOT NULL,
    `lastName` TEXT(50) NOT NULL,
    `firstName` TEXT(50) NOT NULL,
    `extension` TEXT(10) NOT NULL,
    `email` TEXT(100) NOT NULL,
    `officeCode` TEXT(10) NOT NULL,
    `reportsTo` INTEGER(11) DEFAULT NULL,
    `jobTitle` TEXT(50) NOT NULL
);

INSERT INTO `employees` (`employeeNumber`, `lastName`, `firstName`, `extension`, `email`, `officeCode`, `reportsTo`, `jobTitle`) VALUES;
CREATE TABLE IF NOT EXISTS `gallery` (
    `id` INTEGER(11) NOT NULL,
    `image` TEXT(127) NOT NULL,
    `title` TEXT(127) NOT NULL,
    `description` TEXT NOT NULL,
    `active` INTEGER(1) NOT NULL
);

INSERT INTO `gallery` (`id`, `image`, `title`, `description`, `active`) VALUES;
CREATE TABLE IF NOT EXISTS `meta_location` (
    `id` INTEGER(11) NOT NULL,
    `iso` TEXT(50) DEFAULT NULL,
    `local_name` TEXT(255) DEFAULT NULL,
    `type` TEXT(2) DEFAULT NULL,
    `in_location` INTEGER(11) DEFAULT NULL,
    `geo_lat` REAL(18,11) DEFAULT NULL,
    `geo_lng` REAL(18,11) DEFAULT NULL,
    `db_id` TEXT(50) DEFAULT NULL
);

INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
INSERT INTO `meta_location` (`id`, `iso`, `local_name`, `type`, `in_location`, `geo_lat`, `geo_lng`, `db_id`) VALUES;
CREATE TABLE IF NOT EXISTS `offices` (
    `officeCode` INTEGER(10) NOT NULL,
    `city` TEXT(50) NOT NULL,
    `phone` TEXT(50) NOT NULL,
    `addressLine1` TEXT(50) NOT NULL,
    `addressLine2` TEXT(50) DEFAULT NULL,
    `state` TEXT(50) DEFAULT NULL,
    `country` TEXT(50) NOT NULL,
    `postalCode` TEXT(15) NOT NULL,
    `territory` TEXT(10) NOT NULL,
    `ordering` INTEGER(6) NOT NULL
);

INSERT INTO `offices` (`officeCode`, `city`, `phone`, `addressLine1`, `addressLine2`, `state`, `country`, `postalCode`, `territory`, `ordering`) VALUES;
CREATE TABLE IF NOT EXISTS `orderdetails` (
    `id` INTEGER(11) NOT NULL,
    `orderNumber` INTEGER(11) NOT NULL,
    `productCode` TEXT(15) NOT NULL,
    `quantityOrdered` INTEGER(11) NOT NULL,
    `priceEach` REAL NOT NULL,
    `orderLineNumber` INTEGER(6) NOT NULL
);

INSERT INTO `orderdetails` (`id`, `orderNumber`, `productCode`, `quantityOrdered`, `priceEach`, `orderLineNumber`) VALUES;
INSERT INTO `orderdetails` (`id`, `orderNumber`, `productCode`, `quantityOrdered`, `priceEach`, `orderLineNumber`) VALUES;
INSERT INTO `orderdetails` (`id`, `orderNumber`, `productCode`, `quantityOrdered`, `priceEach`, `orderLineNumber`) VALUES;
CREATE TABLE IF NOT EXISTS `orders` (
    `orderNumber` INTEGER(11) NOT NULL,
    `orderDate` TEXT NOT NULL,
    `requiredDate` TEXT NOT NULL,
    `shippedDate` TEXT DEFAULT NULL,
    `status` TEXT(15) NOT NULL,
    `comments` TEXT DEFAULT NULL,
    `customerNumber` INTEGER(11) NOT NULL
);

INSERT INTO `orders` (`orderNumber`, `orderDate`, `requiredDate`, `shippedDate`, `status`, `comments`, `customerNumber`) VALUES;
CREATE TABLE IF NOT EXISTS `payments` (
    `paymentId` INTEGER(11) NOT NULL,
    `customerNumber` INTEGER(11) NOT NULL,
    `checkNumber` TEXT(50) NOT NULL,
    `paymentDate` TEXT NOT NULL,
    `amount` REAL NOT NULL
);

INSERT INTO `payments` (`paymentId`, `customerNumber`, `checkNumber`, `paymentDate`, `amount`) VALUES;
CREATE TABLE IF NOT EXISTS `productlines` (
    `productLine` TEXT(50) NOT NULL,
    `textDescription` TEXT(4000) DEFAULT NULL,
    `htmlDescription` TEXT DEFAULT NULL,
    `image` BLOB DEFAULT NULL
);

INSERT INTO `productlines` (`productLine`, `textDescription`, `htmlDescription`, `image`) VALUES;
CREATE TABLE IF NOT EXISTS `products` (
    `id` INTEGER(11) NOT NULL,
    `productCode` TEXT(15) NOT NULL,
    `productName` TEXT(70) NOT NULL,
    `productLine` TEXT(50) NOT NULL,
    `productScale` TEXT(10) NOT NULL,
    `productVendor` TEXT(50) NOT NULL,
    `productDescription` TEXT NOT NULL,
    `quantityInStock` INTEGER(6) NOT NULL,
    `buyPrice` REAL NOT NULL,
    `MSRP` REAL NOT NULL
);

INSERT INTO `products` (`id`, `productCode`, `productName`, `productLine`, `productScale`, `productVendor`, `productDescription`, `quantityInStock`, `buyPrice`, `MSRP`) VALUES;
CREATE TABLE IF NOT EXISTS `uploads` (
    `id` INTEGER(11) NOT NULL,
    `simple_upload` TEXT(255) NOT NULL,
    `simple_image` TEXT(255) NOT NULL,
    `auto_resize` TEXT(255) NOT NULL,
    `auto_crop` TEXT(255) NOT NULL,
    `manual_crop` TEXT(255) NOT NULL,
    `manual_crop_2` TEXT(255) NOT NULL,
    `manual_crop_3` TEXT(255) NOT NULL,
    `watermark` TEXT(255) NOT NULL,
    `watermark_position` TEXT(255) NOT NULL,
    `image_with_thumbs` TEXT(255) NOT NULL
);

INSERT INTO `uploads` (`id`, `simple_upload`, `simple_image`, `auto_resize`, `auto_crop`, `manual_crop`, `manual_crop_2`, `manual_crop_3`, `watermark`, `watermark_position`, `image_with_thumbs`) VALUES;
CREATE TABLE IF NOT EXISTS `users` (
    `user_id` INTEGER(11) NOT NULL,
    `user_name` TEXT(150) NOT NULL,
    `user_email` TEXT(40) NOT NULL,
    `user_pass` TEXT(255) NOT NULL,
    `pr_id` TEXT(240) DEFAULT NULL,
    `joining_date` TEXT NOT NULL DEFAULT current_timestamp()
);

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_pass`, `pr_id`, `joining_date`) VALUES;
