-- creating tables

CREATE TABLE ordertypes (
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	description VARCHAR(20) NOT NULL
);

CREATE TABLE orderstatus (
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    textid VARCHAR(2) NOT NULL,
	description VARCHAR(20) NOT NULL
);

CREATE TABLE countries (
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	country VARCHAR(20) NOT NULL,
	externalid int
);

CREATE TABLE orders (
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	firstName VARCHAR(30) NOT NULL,
	lastName VARCHAR(30) NOT NULL,
	email VARCHAR(50) NOT NULL,
	phoneNumber VARCHAR(20),
	orderType INT,
	orderStatus INT,
	orderValue DOUBLE,
	scheduledDate DATE,
	streetAddress VARCHAR(50) NOT NULL,
	city VARCHAR(30) NOT NULL,
	state VARCHAR(30) NOT NULL,
	country INT,
	postalCode VARCHAR(15) NOT NULL,
	latitude DOUBLE,
	longitude DOUBLE,
	FOREIGN KEY (ordertype) REFERENCES ordertypes(id),
	FOREIGN KEY (orderstatus) REFERENCES orderstatus(id),
	FOREIGN KEY (country) REFERENCES countries(id)
);

-- inserts

INSERT INTO countries (country) VALUES ('Canada');
INSERT INTO countries (country) VALUES ('United States');
INSERT INTO countries (country) VALUES ('Mexico');

INSERT INTO orderstatus(textid, description) VALUES ('PE', 'Pending');
INSERT INTO orderstatus(textid, description) VALUES ('AS', 'Assigned');
INSERT INTO orderstatus(textid, description) VALUES ('RO', 'On Route');
INSERT INTO orderstatus(textid, description) VALUES ('DO', 'Done');
INSERT INTO orderstatus(textid, description) VALUES ('CA', 'Cancelled');

INSERT INTO ordertypes (description) VALUES ('Delivery');
INSERT INTO ordertypes (description) VALUES ('Servicing');
INSERT INTO ordertypes (description) VALUES ('Installation');