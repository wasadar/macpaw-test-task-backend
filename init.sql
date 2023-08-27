CREATE SCHEMA collections_schema;

CREATE USER 'app'@'localhost' IDENTIFIED BY '123456';

GRANT ALL PRIVILEGES ON collections_schema.* TO 'app'@'localhost';

FLUSH PRIVILEGES;