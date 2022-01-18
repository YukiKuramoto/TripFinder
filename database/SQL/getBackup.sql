-- source /Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/SQL/getBackup.sql

SELECT *
  FROM users
  INTO OUTFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/users.csv'
  FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
;

SELECT *
  FROM plans
  INTO OUTFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/plans.csv'
  FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
;

SELECT *
  FROM spots
  INTO OUTFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/spots.csv'
  FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
;

SELECT *
  FROM images
  INTO OUTFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/images.csv'
  FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
;

SELECT *
  FROM links
  INTO OUTFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/links.csv'
  FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
;

SELECT *
  FROM spot_comment
  INTO OUTFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/spot_comment.csv'
  FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
;

SELECT *
  FROM favoriteplans
  INTO OUTFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/favoriteplans.csv'
  FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
;

SELECT *
  FROM favoritespots
  INTO OUTFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/favoritespots.csv'
  FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
;

SELECT *
  FROM follows
  INTO OUTFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/follows.csv'
  FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
;

SELECT *
  FROM Tags
  INTO OUTFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/tags.csv'
  FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
;

SELECT *
  FROM plan_tag
  INTO OUTFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/plan_tag.csv'
  FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
;

SELECT *
  FROM spot_tag
  INTO OUTFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/spot_tag.csv'
  FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
;
