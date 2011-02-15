#========================================================================== #
# Project Filename:    C:\Programme\Datanamic\DeZign for Databases V3\Project1.dez#
# Project Name:                                                             #
# Author:                                                                   #
# DBMS:                MySQL 4                                              #
# Copyright:                                                                #
# Generated on:        14.09.2003 23:12:42                                  #
#========================================================================== #

#========================================================================== #
#  Tables                                                                   #
#========================================================================== #

CREATE TABLE product (
    product_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(80) NOT NULL,
    article_number VARCHAR(40),
    description TEXT,
    manufacturer_ID INTEGER UNSIGNED NOT NULL,
    category_ID INTEGER UNSIGNED NOT NULL,
    CONSTRAINT PK_product PRIMARY KEY (product_id),
    KEY IDX_product_1(manufacturer_ID),
    KEY IDX_product_2(category_ID)
);

CREATE TABLE manufacturer (
    manufacturer_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(80) NOT NULL,
    CONSTRAINT PK_manufacturer PRIMARY KEY (manufacturer_id),
    UNIQUE KEY IDX_manufacturer_1(manufacturer_id)
);

CREATE TABLE category (
    category_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(80) NOT NULL,
    CONSTRAINT PK_category PRIMARY KEY (category_id),
    UNIQUE KEY IDX_category_1(category_id)
);

#========================================================================== #
#  Foreign Keys                                                             #
#========================================================================== #

ALTER TABLE product
    ADD FOREIGN KEY (manufacturer_ID) REFERENCES manufacturer (manufacturer_id);

ALTER TABLE product
    ADD FOREIGN KEY (category_ID) REFERENCES category (category_id);
