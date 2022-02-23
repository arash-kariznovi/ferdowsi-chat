use sadaf;

DROP TABLE IF EXISTS `sadaf`.`Skills`;
CREATE TABLE  `sadaf`.`Skills` (
  `SkillID` int(11) NOT NULL AUTO_INCREMENT,
  `SkillName` varchar(100) NOT NULL,
  PRIMARY KEY (`SkillID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

DROP TABLE IF EXISTS `sadaf`.`PersonsSkills`;
CREATE TABLE  `sadaf`.`PersonsSkills` (
  `SkillID` int(11) NOT NULL,
  `PersonID` int(11) NOT NULL,
  PRIMARY KEY (`SkillID`, `PersonID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

DROP TABLE IF EXISTS `sadaf`.`Projects`;
CREATE TABLE  `sadaf`.`Projects` (
  `ProjectID` int(11) NOT NULL AUTO_INCREMENT,
  `ProjectName` varchar(100) NOT NULL,
  `StartDate`	DATE,
	`EndDate`	DATE,
	`Description`	TEXT(500),
	`Link`	TEXT(500),
  PRIMARY KEY (`ProjectID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

DROP TABLE IF EXISTS `sadaf`.`PersonsProjects`;
CREATE TABLE  `sadaf`.`PersonsProjects` (
  `ProjectID` int(11) NOT NULL,
  `PersonID` int(11) NOT NULL,
  PRIMARY KEY (`ProjectID`, `PersonID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

DROP TABLE IF EXISTS `sadaf`.`Publications`;
CREATE TABLE  `sadaf`.`Publications` (
    id int(15) auto_increment primary key,
    personID int(15) not null,
    publication varchar(100) not null,
    constraint fk_person FOREIGN KEY (personID) REFERENCES persons(PersonID)
                                     on delete restrict
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

DROP TABLE IF EXISTS `sadaf`.`Interests`;
CREATE TABLE  `sadaf`.`Interests` (
                                      `InterestID` int(11) NOT NULL AUTO_INCREMENT,
                                      `InterestName` varchar(100) NOT NULL ,
                                      PRIMARY KEY (`InterestID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

DROP TABLE IF EXISTS `sadaf`.`PersonsInterests`;
CREATE TABLE  `sadaf`.`PersonsInterests` (
                                             `InterestID` int(11) NOT NULL,
                                             `PersonID` int(11) NOT NULL,
                                             PRIMARY KEY (`InterestID`, `PersonID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;


INSERT INTO sadaf.SpecialPages VALUES (null,'/ManageSkills.php')
