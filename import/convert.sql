DELETE FROM `void-api`.`attributes_items`;
DELETE FROM `void-api`.`characters_conditions`;
DELETE FROM `void-api`.`characters_powers`;
DELETE FROM `void-api`.`characters_skills`;
DELETE FROM `void-api`.`characters_spells`;
DELETE FROM `void-api`.`attributes`;
DELETE FROM `void-api`.`conditions`;
DELETE FROM `void-api`.`powers`;
DELETE FROM `void-api`.`skills`;
DELETE FROM `void-api`.`spells`;
DELETE FROM `void-api`.`manatypes`;
DELETE FROM `void-api`.`items`;
DELETE FROM `void-api`.`characters`;
DELETE FROM `void-api`.`believes`;
DELETE FROM `void-api`.`factions`;
DELETE FROM `void-api`.`groups`;
DELETE FROM `void-api`.`worlds`;
DELETE FROM `void-api`.`players`;

ALTER TABLE `void-api`.`believes`   auto_increment = 1;
ALTER TABLE `void-api`.`characters` auto_increment = 1;
ALTER TABLE `void-api`.`factions`   auto_increment = 1;
ALTER TABLE `void-api`.`groups`     auto_increment = 1;
ALTER TABLE `void-api`.`skills`     auto_increment = 1;
ALTER TABLE `void-api`.`spells`     auto_increment = 1;
ALTER TABLE `void-api`.`worlds`     auto_increment = 1;

INSERT INTO `void-api`.`players`
    ( `id`, `first_name`, `insertion`, `last_name`
    , `gender`, `date_of_birth`, `modified` )
SELECT `plaPLIN`, `plaFirstName`, `plaInsertion`, `plaLastName`,
    `plaGender`, `plaDateOfBirth`, `plaLastupdate`
  FROM `va`.`Tbl_Players`;

INSERT INTO `void-api`.`worlds` ( `id`, `name` )
SELECT `wldID`, `wldName` FROM `va`.`Tbl_Worlds`;

INSERT INTO `void-api`.`groups` ( `id`, `name` )
SELECT `grpID`, `grpName` FROM `va`.`Tbl_Groups`;

INSERT INTO `void-api`.`factions` ( `id`, `name` )
VALUES ( 0, '-');
SELECT `facID`, `facName` FROM `va`.`TblLkp_Faction`;
INSERT INTO `void-api`.`factions` ( `id`, `name` )

INSERT INTO `void-api`.`believes` ( `id`, `name` )
SELECT `belID`, `belName` FROM `va`.`Tbl_Beliefs`;

INSERT INTO `void-api`.`characters` ( `id`, `player_id`, `chin`, `name`, `xp`
    , `faction_id`, `belief_id`, `group_id`, `world_id`, `status`, `comments`
    , `modified` )
SELECT `chaID`, `plaPLIN`, `chaCHIN`, `chaName`, `Total Points`
    , IF(`facID` IS NULL, 1, `facID` + 1)
    , IFNULL(`chaBeliefIDFK`, 1)
    , IFNULL(`chaGroupIDFK`, 1)
    , IFNULL(`chaWorldIDFK`, 1)
    , IF(`chaDeadJN` = 1, "dead", IF(`chaActiveJN` = 1, "active", "inactive"))
    , `chaRemarks`, IFNULL(`chaLastUpdate`, `chaCreationDate`)
  FROM  `va`.`Tbl_Characters`
  LEFT JOIN `va`.`Tbl_Players`
    ON (`Tbl_Characters`.`chaPLINIDFK` = `Tbl_Players`.`plaPLIN`)
  LEFT JOIN `va`.`TblLkp_Faction`
    ON (`Tbl_Characters`.`chaFaction` = `TblLkp_Faction`.`facName`)
 WHERE 1;

INSERT INTO `void-api`.`items` ( `id`, `name`, `description`, `player_text`
    , `cs_text`, `character_id`, `expiry`)
SELECT `itmITIN`, `itmName`, `itmDescription`, `itmPlayerText`, `itmCSText`
    , `itmchaIDFK`, `itmExpireDate`
  FROM `va`.`Tbl_Items`;

INSERT INTO `void-api`.`manatypes` ( `id`, `name` )
SELECT `mnaID`, `mnaName`
  FROM `va`.`TblLkp_Mana`;

INSERT INTO `void-api`.`spells` ( `id`, `name`, `short`, `spiritual` )
SELECT `casID`, `casName`, `casShort`, `casSpiritual`
  FROM `va`.`TblLkp_Casting`;

INSERT INTO `void-api`.`skills` ( `id`, `name`, `cost`, `manatype_id`
    , `mana_amount`, `sort_order` )
SELECT `skiID`, `skiName`, `skiCost`, `skiManaType`, `skiMana`, `skiOrd`
  FROM `va`.`Tbl_Skills`;

INSERT INTO `void-api`.`powers` ( `id`, `name`, `player_text`, `cs_text` )
SELECT `pwrSPIN`, `pwrName`, `pwrDescription`, `pwrCSText`
  FROM `va`.`Tbl_Powers`
 WHERE `pwrCond` = 0;

-- ignore duplicate COIN's: 2270, 2280
INSERT INTO `void-api`.`conditions` ( `id`, `name`, `player_text`, `cs_text` )
SELECT `pwrSPIN`, `pwrName`, `pwrDescription`, `pwrCSText`
  FROM `va`.`Tbl_Powers`
 WHERE `pwrCond` = 1 AND `pwrID` != 83 AND `pwrID` != 334;

INSERT INTO `void-api`.`attributes` ( `id`, `name`, `category`, `code` )
SELECT `lorID`, `lorDescr`, `lorType`, `lorCode`
  FROM `va`.`Tbl_LoreSkills`;

INSERT INTO `void-api`.`characters_spells`
    ( `character_id`, `spell_id`, `level` )
SELECT `ccChaIDFK`, `ccCasIDFK`, `ccLevel`
  FROM `va`.`Tbl_CharacterCasting`;

INSERT INTO `void-api`.`characters_skills` ( `character_id`, `skill_id` )
SELECT `csChaIDFK`, `csSkiIDFK`
  FROM `va`.`Tbl_CharacterSkills`;

INSERT INTO `void-api`.`characters_powers`
    ( `character_id`, `power_id`, `expiry` )
SELECT `ciChaIDFK`, `pwrSPIN`, `ciExpireDate`
  FROM `va`.`Tbl_CharacterPowers`
  JOIN `va`.`Tbl_Powers` ON ( `pwrID` = `ciPwrIDFK` )
WHERE `pwrCond` = 0;

INSERT INTO `void-api`.`characters_conditions`
    ( `character_id`, `condition_id`, `expiry` )
SELECT `ciChaIDFK`, `pwrSPIN`, `ciExpireDate`
  FROM `va`.`Tbl_CharacterPowers`
  JOIN `va`.`Tbl_Powers` ON ( `pwrID` = `ciPwrIDFK` )
WHERE `pwrCond` = 1;

UPDATE `va`.`Tbl_Items` SET `itmCodeMat2` = '0'
WHERE `va`.`Tbl_Items`.`itmCodeMat1` = `va`.`Tbl_Items`.`itmCodeMat2`;

INSERT INTO `void-api`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT `itmCodeValue`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeValue` > 0;

INSERT INTO `void-api`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeForgery`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeForgery` > 0 AND `itmCodeForgery` < 6347;

INSERT INTO `void-api`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeMat1`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeMat1` > 0 AND `itmCodeMat1` < 6347;

INSERT INTO `void-api`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeMat2`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeMat2` > 0 AND `itmCodeMat2` < 6347;

INSERT INTO `void-api`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeSpecial`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeSpecial` > 0 AND `itmCodeSpecial` < 6347;

INSERT INTO `void-api`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeSpirit`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeSpirit` > 0 AND `itmCodeSpirit` < 6347;

INSERT INTO `void-api`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeMagic`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeMagic` > 0 AND `itmCodeMagic` < 6347;

INSERT INTO `void-api`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeDam1`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeDam1` > 0 AND `itmCodeDam1` < 6347;

INSERT INTO `void-api`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeDam2`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeDam2` > 0 AND `itmCodeDam2` < 6347;

-- clean up

DELETE FROM `void-api`.`manatypes` WHERE `id` = 7;

DELETE FROM `void-api`.`attributes` WHERE `id` = 5922;
DELETE FROM `void-api`.`attributes` WHERE `id` = 6245;
UPDATE `void-api`.`attributes` SET `id` = 7000+`id` WHERE `id` < 2000;
UPDATE `void-api`.`attributes` SET `id`
    = IF((ORD(SUBSTR(`code`,1,1)) - 48) > 9
        , ORD(SUBSTR(`code`,1,1)) - 55
        , ORD(SUBSTR(`code`,1,1)) - 48) * 36
    + IF((ORD(SUBSTR(`code`,2,1)) - 48) > 9
        , ORD(SUBSTR(`code`,2,1)) - 55
        , ORD(SUBSTR(`code`,2,1)) - 48);

SET @a:=0;
UPDATE `void-api`.`characters` SET `belief_id` = 5 WHERE `belief_id` = 1;
DELETE FROM `void-api`.`believes` WHERE `id` = 1;
UPDATE `void-api`.`believes` SET `id` = 1 WHERE `id` = 5;
UPDATE `void-api`.`believes` SET `id` = @a:=@a+1 ORDER BY `id`;
ALTER TABLE `void-api`.`believes` MODIFY COLUMN `id` INT(10) UNSIGNED;
ALTER TABLE `void-api`.`believes`
    MODIFY COLUMN `id` INT(10) UNSIGNED AUTO_INCREMENT;

SET @a:=0;
UPDATE `void-api`.`groups` SET `name` = "-" WHERE `id` = 1;
UPDATE `void-api`.`groups` SET `id` = @a:=@a+1 ORDER BY `id`;
ALTER TABLE `void-api`.`groups` MODIFY COLUMN `id` INT(10) UNSIGNED;
ALTER TABLE `void-api`.`groups`
    MODIFY COLUMN `id` INT(10) UNSIGNED AUTO_INCREMENT;

SET @a:=0;
UPDATE `void-api`.`characters` SET `world_id` = 5 WHERE `world_id` = 1;
DELETE FROM `void-api`.`worlds` WHERE `id` = 1;
UPDATE `void-api`.`worlds` SET `id` = 1 WHERE `id` = 5;
UPDATE `void-api`.`worlds` SET `id` = @a:=@a+1 ORDER BY `id`;
ALTER TABLE `void-api`.`worlds` MODIFY COLUMN `id` INT(10) UNSIGNED;
ALTER TABLE `void-api`.`worlds`
    MODIFY COLUMN `id` INT(10) UNSIGNED AUTO_INCREMENT;

SET @a:=0;
UPDATE `void-api`.`characters` SET `id` = `id` + 5000;
UPDATE `void-api`.`characters` SET `id` = @a:=@a+1 ORDER BY `player_id`, `chin`;
ALTER TABLE `void-api`.`characters` MODIFY COLUMN `id` INT(10) UNSIGNED;
ALTER TABLE `void-api`.`characters`
    MODIFY COLUMN `id` INT(10) UNSIGNED AUTO_INCREMENT;

SET @a:=0;
UPDATE `void-api`.`skills` SET `id` = `id` + 1000;
UPDATE `void-api`.`skills` SET `id` = @a:=@a+1 ORDER BY `sort_order`, `name`;
ALTER TABLE `void-api`.`skills` MODIFY COLUMN `id` INT(10) UNSIGNED;
ALTER TABLE `void-api`.`skills`
    MODIFY COLUMN `id` INT(10) UNSIGNED AUTO_INCREMENT;
