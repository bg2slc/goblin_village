DROP DATABASE IF EXISTS gvDB;
CREATE DATABASE gvDB;
USE gvDB;

CREATE TABLE Users  (
    id              INT         NOT NULL AUTO_INCREMENT,
    name            VARCHAR(20),
    lastLogin       DATE,
    admin           BOOLEAN,
    PRIMARY KEY (id)
);

CREATE TABLE Villages	(
    id              INT         NOT NULL AUTO_INCREMENT,
    name            VARCHAR(20),
    userId          VARCHAR(20), -- FK
    terrainId	    INT,	     -- FK
    description     VARCHAR(40),
    population      INT,
    age             INT,        -- in number of in-game weeks
    lastModified    DATE,    -- IRL time last modified.
    PRIMARY KEY (id)
);

INSERT INTO Villages VALUES
(1, "alpha", "wydamn", 1, "description about alpha", 80, 7, "1212-12-12"),
(2, "bravo", "wydamn", 1, "bravo description", 90, 16, "1212-12-15"),
(3, "charlie", "wydamn", 1, "some charlie describos", 70, 54, "1212-12-01");

CREATE TABLE Goblins     (
    id              INT         NOT NULL AUTO_INCREMENT,
    villId          INT,            -- FK
    name            VARCHAR(18),
    role            INT,            -- FK
    PRIMARY KEY (id)
);

CREATE TABLE Terrains	(
    id              INT         NOT NULL AUTO_INCREMENT,
    name	        VARCHAR(12),
    PRIMARY KEY (id)
);

-- CREATE TABLE GoblinProfiles  {
--     gobId       INT     PRIMARY KEY, -- FK
--     gobGender   CHAR,               
--     gobHeight   FLOAT,              -- in cm
--     gobWeight   FLOAT,              -- in kilograms
--     gobSkin     INT,                -- FK
--     gobHair     INT                 -- FK
-- }
-- 
-- CREATE TABLE GoblinTraits   {
--     gobID           INT     PRIMARY KEY, -- FK
--     gobVerbal       VARCHAR(30),
--     gobAppearance   VARCHAR(30),
--     gobFashion      VARCHAR(30),
--     gobQuirks       VARCHAR(40),
--     gobNotes        BLOB        -- Text File
-- }

-- -- In the village, a society can represent any group of goblins
-- -- Society for each role, and age group, as well as organizations or families
-- -- Goblins often belong to multiple societies, by choice or not.
-- CREATE TABLE Societies         {
--     socId           INT     PRIMARY KEY,
--     roleId          INT,        -- role primarily associated with group
--     socName         VARCHAR(20),
--     socNotes        BLOB,       -- Text File
--     socRespect      INT,        -- Rating from 0 - 10
--     socLove         INT,        -- Rating from 0 - 10
-- }
