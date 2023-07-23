CREATE DATABASE ordering_system;

USE ordering_system;

CREATE TABLE category(
    id int(3) not null primary key AUTO_INCREMENT,
    category_name VARCHAR(255)
);

CREATE TABLE item (
    id INT(5) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    description VARCHAR(255),
    added_date VARCHAR(255),
    price INT(5),
    discount INT(3),
    quantity INT(5),
    sold_quantity INT(255),
    category_id INT(3),
    foreign key(category) references category(id)
);

CREATE TABLE item_media (
    id INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    item_id INT(10),
    FOREIGN KEY (item_id) REFERENCES item(id),
    primary_img VARCHAR(255),
    additional_image1 VARCHAR(255),
    additional_image2 VARCHAR(255),
    additional_image3 VARCHAR(255),
    youtube_video_link VARCHAR(255)
);
--  creating USER table with 6 columns
CREATE TABLE user_level (
    id int(3) not null primary key AUTO_INCREMENT,
    user_level VARCHAR(255)
);

CREATE TABLE users (
    id INT(5) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    image VARCHAR(25),
    name VARCHAR(255),
    password VARCHAR(255),
    phone INT(5),
    email VARCHAR(255),
    address VARCHAR(255),
    user_level_id int(3),
    created_date VARCHAR(255),
     foreign key(user_level_id) references user_levels (id)
);


CREATE TABLE orders(
    id INT(5) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    order_date date,
    total_price int(11),
    order_status VARCHAR(255),
    user_id int(5)
);
CREATE TABLE order_item(
    id int(5) not null primary key AUTO_INCREMENT,
    order_id int(5),
    item_id int(5),
    num_ordered int(10),
    quoted_price int(10),
    foreign key(order_id) references orders(id)
);