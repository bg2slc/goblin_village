DROP DATABASE IF EXISTS gvDB;
CREATE DATABASE gvDB;
USE gvDB;

CREATE TABLE Users  (
    userId              INT         NOT NULL AUTO_INCREMENT,
    userName            VARCHAR(20),
    userLastLogin       DATE,
    userAdmin           BOOLEAN,
    PRIMARY KEY (userId)
);

CREATE TABLE Villages	(
    villId              INT         NOT NULL AUTO_INCREMENT,
    villName            VARCHAR(20),
    userId              VARCHAR(20), -- FK
    terrId	            INT,	     -- FK
    villDescription     VARCHAR(40),
    villPopulation      INT,
    villAge             INT,        -- in number of in-game weeks
    villLastModified    DATE,    -- IRL time last modified.
    PRIMARY KEY (villId)
);

INSERT INTO Villages VALUES
(1, "alpha", "wydamn", 1, "description about alpha", 80, 7, "1212-12-12"),
(2, "bravo", "wydamn", 1, "bravo description", 90, 16, "1212-12-15"),
(3, "charlie", "wydamn", 1, "some charlie describos", 70, 54, "1212-12-01");

CREATE TABLE Goblins     (
    gobId               INT         NOT NULL AUTO_INCREMENT,
    gobName             VARCHAR(18),
    villId              INT,            -- FK
    roleId              INT,            -- FK
    profile             INT,            -- FK
    statBlock           INT,            -- FK
    PRIMARY KEY (gobId)
);

CREATE TABLE Goblins_Profile (
    gobId               INT         NOT NULL, -- FK from Goblins
    gender              CHAR,
    height              FLOAT,
    weight              FLOAT,
    complexion          VARCHAR(12),
    complexionColour    CHAR(6),    -- Hex Value
    hair                VARCHAR(12),
    hairColour          CHAR(6),
    dress               VARCHAR(24), -- typical style of clothes
    voice               VARCHAR(20), -- accent, pitch, speaking style.
    motivations         VARCHAR(20),
    likes               VARCHAR(20),
    dislikes            VARCHAR(20),
    PRIMARY KEY (gobId),
    FOREIGN KEY (PersonID) REFERENCES Persons(PersonID)
)

CREATE TABLE FlavourText (
    flavId              INT         NOT NULL AUTO_INCREMENT,
    flavType            VARCHAR(12),
    flavText            VARCHAR(40),
    PRIMARY KEY (flavId)
)


INSERT INTO FlavourText (flavType, flavText) VALUES
("voiceTone", "husky, low"),
("voiceTone", "gravelly, deep"),
("voiceTone", "rich and deep"),
("voiceTone", "shrill, high-pitched"),
("voiceTone", "ringing like a bell"),
("voiceTone", "piercingly high"),
("voiceTone", "raised pitch, like a high whisper")

CREATE TABLE Goblins_StatBlock (
    gobId               INT         NOT NULL DISTINCT, -- FK from Goblins
    class               VARCHAR(12),
    hitDie              INT,    -- second value of hitdie (as in, 8 in 1d8)
    health              INT,
    armorclass          INT,
    -- base attributes
    strength            INT,
    dexterity           INT,
    constitution        INT,
    intelligence        INT,
    wisdom              INT,
    charisma            INT,
    -- skill proficiencies
    acrobatics          INT,
    animal_handling     INT,
    arcana              INT,
    athletics           INT,
    deception           INT,
    history             INT,
    insight             INT,
    intimidation        INT,
    investigation       INT,
    medicine            INT,
    nature              INT,
    perception          INT,
    performance         INT,
    persuasion          INT,
    religion            INT,
    sleight_of_hand     INT,
    stealth             INT,
    survival            INT,
    -- 
)


-- CREATE TABLE CharacterClasses (




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

CREATE TABLE Organizations  (
    orgId               INT         NOT NULL AUTO_INCREMENT,
    orgName             VARCHAR(18),
    roleId              INT,

CREATE TABLE Terrains	(
    terrId              INT         NOT NULL AUTO_INCREMENT,
    terrName	        VARCHAR(12),
    PRIMARY KEY (terrId)
);


-- CREATE TABLE Societies         {
--     socId           INT     PRIMARY KEY,
--     roleId          INT,        -- role primarily associated with group
--     socName         VARCHAR(20),
--     socNotes        BLOB,       -- Text File
--     socRespect      INT,        -- Rating from 0 - 10
--     socLove         INT,        -- Rating from 0 - 10
-- }
