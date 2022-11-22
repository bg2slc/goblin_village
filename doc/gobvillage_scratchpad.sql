DROP DATABASE IF EXISTS gvDB;
CREATE DATABASE gvDB;
USE gvDB;

CREATE TABLE Villages	(
    villId          INT         PRIMARY KEY,
    villName        VARCHAR(20),
    villDesc        VARCHAR(40),
    villPop         INT,
    villAge         INT,        -- in number of in-game weeks
    villLastModified    DATE    -- IRL time last modified.
);

INSERT INTO Villages VALUES
(1, "alpha", "description about alpha", 80, 7, "1212-12-12"),
(2, "bravo", "bravo description", 90, 16, "1212-12-15"),
(3, "charlie", "some charlie describos", 70, 54, "1212-12-01");

CREATE TABLE Goblins     (
    gobId       INT     PRIMARY KEY,
    villId      INT,            -- FK
    gobName     VARCHAR(18),
    roleId      INT             -- FK
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
