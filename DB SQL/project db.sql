CREATE DATABASE BlogCMS;
SHOW DATABASES;
USE BlogCMS;
SELECT DATABASE();
SHOW TABLES;
DESC categories;
drop table comments;    

# create admin table
CREATE TABLE admins (
	id SMALLINT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    added_by VARCHAR(255) NOT NULL,
    `datetime` VARCHAR(50) NOT NULL
);

# insert master admin username and password
INSERT INTO admins (full_name, username, `password`, added_by, `datetime`)
	VALUES ('Yousaf Khan', 'yousaf', 'yousaf', 'Yousaf', 'May 13, 2018');
SELECT * FROM admins;




# create categories table
CREATE TABLE categories (
	id SMALLINT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(150) NOT NULL UNIQUE,
    created_by VARCHAR(255) NOT NULL,
    added_on VARCHAR(50) NOT NULL
);



# create post table
CREATE TABLE posts (
	id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL UNIQUE,
    category VARCHAR(200) NOT NULL,
    author VARCHAR(250) NOT NULL,
    added_on VARCHAR(50) NOT NULL,
    image VARCHAR(255) NOT NULL,
    content TEXT NOT NULL
);




# create comments table
CREATE TABLE comments (
	id INT PRIMARY KEY AUTO_INCREMENT,
    comment_text TEXT NOT NULL,
    author VARCHAR(255) NOT NULL,
    author_email VARCHAR(255) NOT NULL,
    added_on VARCHAR(50) NOT NULL,
    approved VARCHAR(5) NOT NULL,
    approved_by VARCHAR(255) NOT NULL,
    post_id INT NOT NULL,
    FOREIGN KEY (post_id) REFERENCES posts(id)
);


