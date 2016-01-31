-- SELECT "delete..." AS '';
-- DELETE FROM `va-void`.`attributes_items`;
-- DELETE FROM `va-void`.`characters_conditions`;
-- DELETE FROM `va-void`.`characters_powers`;
-- DELETE FROM `va-void`.`characters_skills`;
-- DELETE FROM `va-void`.`characters_spells`;
-- DELETE FROM `va-void`.`attributes`;
-- DELETE FROM `va-void`.`conditions`;
-- DELETE FROM `va-void`.`powers`;
-- DELETE FROM `va-void`.`skills`;
-- DELETE FROM `va-void`.`spells`;
-- DELETE FROM `va-void`.`manatypes`;
-- DELETE FROM `va-void`.`items`;
-- DELETE FROM `va-void`.`characters`;
-- DELETE FROM `va-void`.`believes`;
-- DELETE FROM `va-void`.`factions`;
-- DELETE FROM `va-void`.`groups`;
-- DELETE FROM `va-void`.`worlds`;
-- DELETE FROM `va-void`.`players`;
-- 
-- ALTER TABLE `va-void`.`believes`   auto_increment = 1;
-- ALTER TABLE `va-void`.`characters` auto_increment = 1;
-- ALTER TABLE `va-void`.`factions`   auto_increment = 1;
-- ALTER TABLE `va-void`.`groups`     auto_increment = 1;
-- ALTER TABLE `va-void`.`skills`     auto_increment = 1;
-- ALTER TABLE `va-void`.`spells`     auto_increment = 1;
-- ALTER TABLE `va-void`.`worlds`     auto_increment = 1;
-- 
-- SELECT "import..." AS '';
-- 
-- SELECT " PLAYERS" AS '';
-- INSERT INTO `va-void`.`players`
--     ( `id`, `first_name`, `insertion`, `last_name`
--     , `gender`, `date_of_birth`, `modified` )
-- SELECT `plaPLIN`, `plaFirstName`, `plaInsertion`, `plaLastName`,
--     `plaGender`, `plaDateOfBirth`, `plaLastupdate`
--   FROM `va`.`Tbl_Players`;
-- 
-- SELECT " WORLDS" AS '';
-- INSERT INTO `va-void`.`worlds` ( `id`, `name` )
-- SELECT `wldID`, `wldName` FROM `va`.`Tbl_Worlds`;
-- 
-- SELECT " GROUPS" AS '';
-- INSERT INTO `va-void`.`groups` ( `id`, `name` )
-- SELECT `grpID`, `grpName` FROM `va`.`Tbl_Groups`;
-- 
-- SELECT " FACTIONS" AS '';
-- INSERT INTO `va-void`.`factions` ( `id`, `name` )
-- VALUES ( 1, '-' );
-- INSERT INTO `va-void`.`factions` ( `id`, `name` )
-- SELECT `facID` + 1, `facName` FROM `va`.`TblLkp_Faction`;
-- 
-- SELECT " BELIEVES" AS '';
-- INSERT INTO `va-void`.`believes` ( `id`, `name` )
-- SELECT `belID`, `belName` FROM `va`.`Tbl_Beliefs`;
-- 
-- SELECT " CHARACTERS" AS '';
-- INSERT INTO `va-void`.`characters` ( `id`, `player_id`, `chin`, `name`, `xp`
--     , `faction_id`, `belief_id`, `group_id`, `world_id`, `status`, `comments`
--     , `modified` )
-- SELECT `chaID`, `plaPLIN`, `chaCHIN`, `chaName`, `Total Points`
--     , IF(`facID` IS NULL, 1, `facID` + 1)
--     , IFNULL(`chaBeliefIDFK`, 1)
--     , IFNULL(`chaGroupIDFK`, 1)
--     , IFNULL(`chaWorldIDFK`, 1)
--     , IF(`chaDeadJN` = 1, "dead", IF(`chaActiveJN` = 1, "active", "inactive"))
--     , `chaRemarks`, IFNULL(`chaLastUpdate`, `chaCreationDate`)
--   FROM  `va`.`Tbl_Characters`
--   LEFT JOIN `va`.`Tbl_Players`
--     ON (`Tbl_Characters`.`chaPLINIDFK` = `Tbl_Players`.`plaPLIN`)
--   LEFT JOIN `va`.`TblLkp_Faction`
--     ON (`Tbl_Characters`.`chaFaction` = `TblLkp_Faction`.`facName`)
--  WHERE 1;
-- 
-- SELECT " ITEMS" AS '';
-- INSERT INTO `va-void`.`items` ( `id`, `name`, `description`, `player_text`
--     , `cs_text`, `character_id`, `expiry`)
-- SELECT `itmITIN`, `itmName`, `itmDescription`, `itmPlayerText`, `itmCSText`
--     , `itmchaIDFK`, `itmExpireDate`
--   FROM `va`.`Tbl_Items`;
-- 
-- SELECT " MANA" AS '';
-- INSERT INTO `va-void`.`manatypes` ( `id`, `name` )
-- SELECT `mnaID`, `mnaName`
--   FROM `va`.`TblLkp_Mana`;
-- 
-- SELECT " CASTING" AS '';
-- INSERT INTO `va-void`.`spells` ( `id`, `name`, `short`, `spiritual` )
-- SELECT `casID`, `casName`, `casShort`, `casSpiritual`
--   FROM `va`.`TblLkp_Casting`;
-- 
-- SELECT " SKILLS" AS '';
-- INSERT INTO `va-void`.`skills` ( `id`, `name`, `cost`, `manatype_id`
--     , `mana_amount`, `sort_order` )
-- SELECT `skiID`, `skiName`, `skiCost`, `skiManaType`, `skiMana`, `skiOrd`
--   FROM `va`.`Tbl_Skills`;
-- 
-- SELECT " POWERS" AS '';
-- INSERT INTO `va-void`.`powers` ( `id`, `name`, `player_text`, `cs_text` )
-- SELECT `pwrSPIN`, `pwrName`, `pwrDescription`, `pwrCSText`
--   FROM `va`.`Tbl_Powers`
--  WHERE `pwrCond` = 0;
-- 
-- SELECT " CONDITIONS" AS '';
-- -- ignore duplicate COIN's: 2270, 2280
-- INSERT INTO `va-void`.`conditions` ( `id`, `name`, `player_text`, `cs_text` )
-- SELECT `pwrSPIN`, `pwrName`, `pwrDescription`, `pwrCSText`
--   FROM `va`.`Tbl_Powers`
--  WHERE `pwrCond` = 1 AND `pwrID` != 83 AND `pwrID` != 334;
-- 
-- SELECT " ATTRIBUTES" AS '';
-- INSERT INTO `va-void`.`attributes` ( `id`, `name`, `category`, `code` )
-- SELECT `lorID`, `lorDescr`, `lorType`, `lorCode`
--   FROM `va`.`Tbl_LoreSkills`;
-- 
-- SELECT " CHARACTERS - SPELLS" AS '';
-- INSERT INTO `va-void`.`characters_spells`
--     ( `character_id`, `spell_id`, `level` )
-- SELECT `ccChaIDFK`, `ccCasIDFK`, `ccLevel`
--   FROM `va`.`Tbl_CharacterCasting`;
-- 
-- SELECT " CHARACTERS - SKILLS" AS '';
-- INSERT INTO `va-void`.`characters_skills` ( `character_id`, `skill_id` )
-- SELECT `csChaIDFK`, `csSkiIDFK`
--   FROM `va`.`Tbl_CharacterSkills`;
-- 
-- SELECT " CHARACTERS - POWERS" AS '';
-- INSERT INTO `va-void`.`characters_powers`
--     ( `character_id`, `power_id`, `expiry` )
-- SELECT `ciChaIDFK`, `pwrSPIN`, `ciExpireDate`
--   FROM `va`.`Tbl_CharacterPowers`
--   JOIN `va`.`Tbl_Powers` ON ( `pwrID` = `ciPwrIDFK` )
-- WHERE `pwrCond` = 0;
-- 
-- SELECT " CHARACTERS - CONDITIONS" AS '';
-- INSERT INTO `va-void`.`characters_conditions`
--     ( `character_id`, `condition_id`, `expiry` )
-- SELECT `ciChaIDFK`, `pwrSPIN`, `ciExpireDate`
--   FROM `va`.`Tbl_CharacterPowers`
--   JOIN `va`.`Tbl_Powers` ON ( `pwrID` = `ciPwrIDFK` )
-- WHERE `pwrCond` = 1;
-- 
-- SELECT " ITEM - CODES" AS '';
-- -- UPDATE `va`.`Tbl_Items` SET `itmCodeMat2` = '0'
-- -- WHERE `va`.`Tbl_Items`.`itmCodeMat1` = `va`.`Tbl_Items`.`itmCodeMat2`;

INSERT INTO `va-void`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT `itmCodeValue`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeValue` > 0;

INSERT INTO `va-void`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeForgery`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeForgery` > 0 AND `itmCodeForgery` < 6347;

INSERT INTO `va-void`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeMat1`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeMat1` > 0 AND `itmCodeMat1` < 6347;

INSERT INTO `va-void`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeMat2`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeMat2` > 0 AND `itmCodeMat2` < 6347
   AND `itmCodeMat1` <> `itmCodeMat2`;

INSERT INTO `va-void`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeSpecial`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeSpecial` > 0 AND `itmCodeSpecial` < 6347;

INSERT INTO `va-void`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeSpirit`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeSpirit` > 0 AND `itmCodeSpirit` < 6347;

INSERT INTO `va-void`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeMagic`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeMagic` > 0 AND `itmCodeMagic` < 6347;

INSERT INTO `va-void`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeDam1`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeDam1` > 0 AND `itmCodeDam1` < 6347;

INSERT INTO `va-void`.`attributes_items` ( `attribute_id`, `item_id` )
SELECT DISTINCT `itmCodeDam2`, `itmITIN`
  FROM `va`.`Tbl_Items`
 WHERE `itmCodeDam2` > 0 AND `itmCodeDam2` < 6347;

-- clean up
SELECT "clean up ..." AS '';

SELECT " manatypes" AS '';
DELETE FROM `va-void`.`manatypes` WHERE `id` = 7;

SELECT " attributes" AS '';
DELETE FROM `va-void`.`attributes` WHERE `id` = 5922;
DELETE FROM `va-void`.`attributes` WHERE `id` = 6245;
UPDATE `va-void`.`attributes` SET `id` = 7000+`id` WHERE `id` < 2000;
UPDATE `va-void`.`attributes` SET `id`
    = IF((ORD(SUBSTR(`code`,1,1)) - 48) > 9
        , ORD(SUBSTR(`code`,1,1)) - 55
        , ORD(SUBSTR(`code`,1,1)) - 48) * 36
    + IF((ORD(SUBSTR(`code`,2,1)) - 48) > 9
        , ORD(SUBSTR(`code`,2,1)) - 55
        , ORD(SUBSTR(`code`,2,1)) - 48);

SELECT " believes" AS '';
SET @a:=0;
UPDATE `va-void`.`characters` SET `belief_id` = 5 WHERE `belief_id` = 1;
DELETE FROM `va-void`.`believes` WHERE `id` = 1;
UPDATE `va-void`.`believes` SET `id` = 1 WHERE `id` = 5;
UPDATE `va-void`.`believes` SET `id` = @a:=@a+1 ORDER BY `id`;
ALTER TABLE `va-void`.`believes` MODIFY COLUMN `id` INT(10) UNSIGNED;
ALTER TABLE `va-void`.`believes`
    MODIFY COLUMN `id` INT(10) UNSIGNED AUTO_INCREMENT;

SELECT " groups" AS '';
SET @a:=0;
UPDATE `va-void`.`groups` SET `name` = "-" WHERE `id` = 1;
UPDATE `va-void`.`groups` SET `id` = @a:=@a+1 ORDER BY `id`;
ALTER TABLE `va-void`.`groups` MODIFY COLUMN `id` INT(10) UNSIGNED;
ALTER TABLE `va-void`.`groups`
    MODIFY COLUMN `id` INT(10) UNSIGNED AUTO_INCREMENT;

SELECT " worlds" AS '';
SET @a:=0;
UPDATE `va-void`.`characters` SET `world_id` = 5 WHERE `world_id` = 1;
DELETE FROM `va-void`.`worlds` WHERE `id` = 1;
UPDATE `va-void`.`worlds` SET `id` = 1 WHERE `id` = 5;
UPDATE `va-void`.`worlds` SET `id` = @a:=@a+1 ORDER BY `id`;
ALTER TABLE `va-void`.`worlds` MODIFY COLUMN `id` INT(10) UNSIGNED;
ALTER TABLE `va-void`.`worlds`
    MODIFY COLUMN `id` INT(10) UNSIGNED AUTO_INCREMENT;

SELECT " characters" AS '';
SET @a:=0;
UPDATE `va-void`.`characters` SET `id` = `id` + 5000;
UPDATE `va-void`.`characters` SET `id` = @a:=@a+1 ORDER BY `player_id`, `chin`;
ALTER TABLE `va-void`.`characters` MODIFY COLUMN `id` INT(10) UNSIGNED;
ALTER TABLE `va-void`.`characters`
    MODIFY COLUMN `id` INT(10) UNSIGNED AUTO_INCREMENT;

SELECT " skills" AS '';
SET @a:=0;
UPDATE `va-void`.`skills` SET `id` = `id` + 1000;
UPDATE `va-void`.`skills` SET `id` = @a:=@a+1 ORDER BY `sort_order`, `name`;
ALTER TABLE `va-void`.`skills` MODIFY COLUMN `id` INT(10) UNSIGNED;
ALTER TABLE `va-void`.`skills`
    MODIFY COLUMN `id` INT(10) UNSIGNED AUTO_INCREMENT;
