CREATE SCHEMA gvDB;
-- Create User with full access to a schema
CREATE USER `gobbo`@`localhost` IDENTIFIED BY 'gobbopw';
GRANT ALL PRIVILEGES ON `gvDB`.* TO `gobbo`@`localhost`;
FLUSH PRIVILEGES;
ALTER USER 'gobbo'@'localhost' IDENTIFIED BY 'gobbopw';
FLUSH PRIVILEGES;
GRANT ALL PRIVILEGES ON `gvDB`.* TO `gobbo`@`localhost`;
