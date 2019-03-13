DROP DATABASE IF EXISTS ebayDB;
CREATE DATABASE ebayDB
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
GRANT SELECT,UPDATE,INSERT,DELETE
ON ebayDB.*
TO 'at'@'localhost'
IDENTIFIED BY '123';
USE ebayDB;

CREATE TABLE IF NOT EXISTS Product
(
    productID INTEGER AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(20),
    product_description VARCHAR(150),
    start_price DECIMAL(8,2),
    reserve_price DECIMAL(8,2),
    quantity INTEGER,
    categoryID INTEGER,
    conditionID INTEGER,
    sellerID INTEGER,
    auctionable BOOLEAN,
    startdate TEXT,
    enddate TEXT,
    endtime TEXT
) 
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS Category
(
    categoryID INTEGER AUTO_INCREMENT PRIMARY KEY,
    categoryname VARCHAR(40) NOT NULL
) 
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS ConditionIndex
(
    conditionID INTEGER AUTO_INCREMENT PRIMARY KEY,
    conditionname VARCHAR(40) NOT NULL
) 
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS Archive
(
    archiveID INTEGER AUTO_INCREMENT PRIMARY KEY,
    productID INTEGER NOT NULL,
    product_name VARCHAR(20) NOT NULL,
    product_description VARCHAR(150) NOT NULL,
    dealprice DECIMAL(8,2) NOT NULL,
    quantity INTEGER NOT NULL,
    categoryID INTEGER NOT NULL,
    conditionID INTEGER NOT NULL,
    buyerID INTEGER NOT NULL,
    sellerID INTEGER NOT NULL,
    auctionable BOOLEAN NOT NULL,
    dealdate VARCHAR(40) NOT NULL,
    ratings INTEGER,
    buyer_comment VARCHAR(150),
    seller_comment VARCHAR(150)
) 
ENGINE = InnoDB;

INSERT INTO Category (categoryname)
VALUES ('Electronics');

INSERT INTO Category (categoryname)
VALUES ('Food');

INSERT INTO Category (categoryname)
VALUES ('Fashion');

INSERT INTO Category (categoryname)
VALUES ('Home');

INSERT INTO Category (categoryname)
VALUES ('Health & Beauty');

INSERT INTO Category (categoryname)
VALUES ('Sports');

INSERT INTO Category (categoryname)
VALUES ('Toys & Games');

INSERT INTO Category (categoryname)
VALUES ('Art & Music');

INSERT INTO Category (categoryname)
VALUES ('Miscellaneous');

INSERT INTO ConditionIndex (conditionname)
VALUES ('New');

INSERT INTO ConditionIndex (conditionname)
VALUES ('Refurbished');

INSERT INTO ConditionIndex (conditionname)
VALUES ('Used / Worn');
/*
CREATE PHOTOS TABLE 
*/ 

CREATE TABLE IF NOT EXISTS Photos (
	photoID INT NOT NULL AUTO_INCREMENT,
	productID INT NOT NULL,
	file_path TEXT,
	PRIMARY KEY (photoID),
	FOREIGN KEY (productID) REFERENCES Product(productID) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB;

/*
CREATE USERS TABLE ......?
*/

CREATE TABLE IF NOT EXISTS Users (
    userID INTEGER NOT NULL,
    username TEXT,
    password1 TEXT,
    email TEXT,
    phone TEXT,
    accountbalance INTEGER,
    DOB TEXT,
    PRIMARY KEY (userID)
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS BidEvents (
	bidID INT NOT NULL AUTO_INCREMENT,
    productID INT NOT NULL,
    buyerID INT NOT NULL, 
    payment BOOLEAN NOT NULL,
    bidPrice DECIMAL(8,2),
    PRIMARY KEY (bidID),
    FOREIGN KEY (productID) REFERENCES Product(productID) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (buyerID) REFERENCES Users(userID) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB;

/*
CREATE WATCHLIST TABLE
*/

CREATE TABLE IF NOT EXISTS Watchlist (
    watchID INT NOT NULL AUTO_INCREMENT,

    productID INT NOT NULL,
    buyerID INT NOT NULL,

    PRIMARY KEY (watchID)
) ENGINE=INNODB;






/* interest categories? and do we parse input before adding to this or is it something to do with configuring the table? (eg password..?) we'll figure it out :) */


