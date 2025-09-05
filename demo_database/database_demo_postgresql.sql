CREATE TABLE IF NOT EXISTS "base_fields" (
    "id" INTEGER NOT NULL,
    "text" VARCHAR(50) NOT NULL,
    "text_area" TEXT NOT NULL,
    "text_editor" TEXT NOT NULL,
    "integer" INTEGER NOT NULL,
    "float" REAL NOT NULL,
    "enum" TEXT CHECK ("enum" IN ('one','two','three')) NOT NULL,
    "set" TEXT[]('one','two','three') NOT NULL,
    "date" DATE NOT NULL,
    "datetime" TIMESTAMP NOT NULL,
    "time" TIME NOT NULL,
    "bool" BOOLEAN(1) NOT NULL,
    "point" POINT NOT NULL
);

INSERT INTO "base_fields" ("id", "text", "text_area", "text_editor", "integer", "float", "enum", "set", "date", "datetime", "time", "bool", "point") VALUES;
CREATE TABLE IF NOT EXISTS "consultation" (
    "id" INTEGER NOT NULL,
    "name" VARCHAR(50) NOT NULL,
    "date" DATE NOT NULL,
    "office" VARCHAR(50) NOT NULL,
    "manager" VARCHAR(50) NOT NULL,
    "country" INTEGER NOT NULL,
    "region" INTEGER NOT NULL,
    "city" INTEGER NOT NULL
);

INSERT INTO "consultation" ("id", "name", "date", "office", "manager", "country", "region", "city") VALUES;
CREATE TABLE IF NOT EXISTS "customers" (
    "customerNumber" INTEGER NOT NULL,
    "customerName" VARCHAR(50) NOT NULL,
    "contactLastName" VARCHAR(50) NOT NULL,
    "contactFirstName" VARCHAR(50) NOT NULL,
    "phone" VARCHAR(50) NOT NULL,
    "addressLine1" VARCHAR(50) NOT NULL,
    "addressLine2" VARCHAR(50) DEFAULT NULL,
    "city" VARCHAR(50) NOT NULL,
    "state" VARCHAR(50) DEFAULT NULL,
    "postalCode" VARCHAR(15) DEFAULT NULL,
    "country" VARCHAR(50) CHARACTER TEXT[] utf8 NOT NULL,
    "salesRepEmployeeNumber" INTEGER DEFAULT NULL,
    "creditLimit" DOUBLE PRECISION DEFAULT NULL,
    "avatar" BYTEA NOT NULL,
    "photo" VARCHAR(50) NOT NULL,
    "attach" VARCHAR(255) NOT NULL,
    "sex" TEXT CHECK ("sex" IN ('male','female')) NOT NULL,
    "interests" TEXT[]('sports','programming','cars','girls','drinks','fights','history','cooking','shopping') NOT NULL
);

INSERT INTO "customers" ("customerNumber", "customerName", "contactLastName", "contactFirstName", "phone", "addressLine1", "addressLine2", "city", "state", "postalCode", "country", "salesRepEmployeeNumber", "creditLimit", "avatar", "photo", "attach", "sex", "interests") VALUES;
CREATE TABLE IF NOT EXISTS "customers_orders_fk" (
    "customer_id" INTEGER NOT NULL,
    "order_id" INTEGER NOT NULL
);

INSERT INTO "customers_orders_fk" ("customer_id", "order_id") VALUES;
CREATE TABLE IF NOT EXISTS "employees" (
    "employeeNumber" INTEGER NOT NULL,
    "lastName" VARCHAR(50) NOT NULL,
    "firstName" VARCHAR(50) NOT NULL,
    "extension" VARCHAR(10) NOT NULL,
    "email" VARCHAR(100) NOT NULL,
    "officeCode" VARCHAR(10) NOT NULL,
    "reportsTo" INTEGER DEFAULT NULL,
    "jobTitle" VARCHAR(50) NOT NULL
);

INSERT INTO "employees" ("employeeNumber", "lastName", "firstName", "extension", "email", "officeCode", "reportsTo", "jobTitle") VALUES;
CREATE TABLE IF NOT EXISTS "gallery" (
    "id" INTEGER NOT NULL,
    "image" VARCHAR(127) NOT NULL,
    "title" VARCHAR(127) NOT NULL,
    "description" TEXT NOT NULL,
    "active" SMALLINT NOT NULL
);

INSERT INTO "gallery" ("id", "image", "title", "description", "active") VALUES;
CREATE TABLE IF NOT EXISTS "meta_location" (
    "id" INTEGER NOT NULL,
    "iso" VARCHAR(50) DEFAULT NULL,
    "local_name" VARCHAR(255) DEFAULT NULL,
    "type" CHAR(2) DEFAULT NULL,
    "in_location" INTEGER DEFAULT NULL,
    "geo_lat" DOUBLE PRECISION(18,11) DEFAULT NULL,
    "geo_lng" DOUBLE PRECISION(18,11) DEFAULT NULL,
    "db_id" VARCHAR(50) DEFAULT NULL
);

INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
INSERT INTO "meta_location" ("id", "iso", "local_name", "type", "in_location", "geo_lat", "geo_lng", "db_id") VALUES;
CREATE TABLE IF NOT EXISTS "offices" (
    "officeCode" INTEGER NOT NULL,
    "city" VARCHAR(50) NOT NULL,
    "phone" VARCHAR(50) NOT NULL,
    "addressLine1" VARCHAR(50) NOT NULL,
    "addressLine2" VARCHAR(50) DEFAULT NULL,
    "state" VARCHAR(50) DEFAULT NULL,
    "country" VARCHAR(50) NOT NULL,
    "postalCode" VARCHAR(15) NOT NULL,
    "territory" VARCHAR(10) NOT NULL,
    "ordering" SMALLINT(6) NOT NULL
);

INSERT INTO "offices" ("officeCode", "city", "phone", "addressLine1", "addressLine2", "state", "country", "postalCode", "territory", "ordering") VALUES;
CREATE TABLE IF NOT EXISTS "orderdetails" (
    "id" INTEGER NOT NULL,
    "orderNumber" INTEGER NOT NULL,
    "productCode" VARCHAR(15) NOT NULL,
    "quantityOrdered" INTEGER NOT NULL,
    "priceEach" DOUBLE PRECISION NOT NULL,
    "orderLineNumber" SMALLINT(6) NOT NULL
);

INSERT INTO "orderdetails" ("id", "orderNumber", "productCode", "quantityOrdered", "priceEach", "orderLineNumber") VALUES;
INSERT INTO "orderdetails" ("id", "orderNumber", "productCode", "quantityOrdered", "priceEach", "orderLineNumber") VALUES;
INSERT INTO "orderdetails" ("id", "orderNumber", "productCode", "quantityOrdered", "priceEach", "orderLineNumber") VALUES;
CREATE TABLE IF NOT EXISTS "orders" (
    "orderNumber" INTEGER NOT NULL,
    "orderDate" TIMESTAMP NOT NULL,
    "requiredDate" TIMESTAMP NOT NULL,
    "shippedDate" TIMESTAMP DEFAULT NULL,
    "status" VARCHAR(15) NOT NULL,
    "comments" TEXT DEFAULT NULL,
    "customerNumber" INTEGER NOT NULL
);

INSERT INTO "orders" ("orderNumber", "orderDate", "requiredDate", "shippedDate", "status", "comments", "customerNumber") VALUES;
CREATE TABLE IF NOT EXISTS "payments" (
    "paymentId" INTEGER NOT NULL,
    "customerNumber" INTEGER NOT NULL,
    "checkNumber" VARCHAR(50) NOT NULL,
    "paymentDate" TIMESTAMP NOT NULL,
    "amount" DOUBLE PRECISION NOT NULL
);

INSERT INTO "payments" ("paymentId", "customerNumber", "checkNumber", "paymentDate", "amount") VALUES;
CREATE TABLE IF NOT EXISTS "productlines" (
    "productLine" VARCHAR(50) NOT NULL,
    "textDescription" VARCHAR(4000) DEFAULT NULL,
    "htmlDescription" TEXT DEFAULT NULL,
    "image" BYTEA DEFAULT NULL
);

INSERT INTO "productlines" ("productLine", "textDescription", "htmlDescription", "image") VALUES;
CREATE TABLE IF NOT EXISTS "products" (
    "id" INTEGER NOT NULL,
    "productCode" VARCHAR(15) NOT NULL,
    "productName" VARCHAR(70) NOT NULL,
    "productLine" VARCHAR(50) NOT NULL,
    "productScale" VARCHAR(10) NOT NULL,
    "productVendor" VARCHAR(50) NOT NULL,
    "productDescription" TEXT NOT NULL,
    "quantityInStock" SMALLINT(6) NOT NULL,
    "buyPrice" DOUBLE PRECISION NOT NULL,
    "MSRP" DOUBLE PRECISION NOT NULL
);

INSERT INTO "products" ("id", "productCode", "productName", "productLine", "productScale", "productVendor", "productDescription", "quantityInStock", "buyPrice", "MSRP") VALUES;
CREATE TABLE IF NOT EXISTS "uploads" (
    "id" INTEGER NOT NULL,
    "simple_upload" VARCHAR(255) NOT NULL,
    "simple_image" VARCHAR(255) NOT NULL,
    "auto_resize" VARCHAR(255) NOT NULL,
    "auto_crop" VARCHAR(255) NOT NULL,
    "manual_crop" VARCHAR(255) NOT NULL,
    "manual_crop_2" VARCHAR(255) NOT NULL,
    "manual_crop_3" VARCHAR(255) NOT NULL,
    "watermark" VARCHAR(255) NOT NULL,
    "watermark_position" VARCHAR(255) NOT NULL,
    "image_with_thumbs" VARCHAR(255) NOT NULL
);

INSERT INTO "uploads" ("id", "simple_upload", "simple_image", "auto_resize", "auto_crop", "manual_crop", "manual_crop_2", "manual_crop_3", "watermark", "watermark_position", "image_with_thumbs") VALUES;
CREATE TABLE IF NOT EXISTS "users" (
    "user_id" INTEGER NOT NULL,
    "user_name" VARCHAR(150) NOT NULL,
    "user_email" VARCHAR(40) NOT NULL,
    "user_pass" VARCHAR(255) NOT NULL,
    "pr_id" VARCHAR(240) DEFAULT NULL,
    "joining_date" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP()
);

INSERT INTO "users" ("user_id", "user_name", "user_email", "user_pass", "pr_id", "joining_date") VALUES;
