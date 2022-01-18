-- source /Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/SQL/readBackUp.sql

-- SET @in_path_users = '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/users.csv';

-- SET @IN_PATH=CONCAT(@IN_PATH, 'users.csv');

DELETE FROM users;
DELETE FROM tags;


LOAD DATA LOCAL
  INFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/users.csv'
  INTO TABLE TripFinder.users
  FIELDS TERMINATED BY ',' ENCLOSED BY '"'
;

LOAD DATA LOCAL
  INFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/plans.csv'
  INTO TABLE TripFinder.plans
  FIELDS TERMINATED BY ',' ENCLOSED BY '"'
;

LOAD DATA LOCAL
  INFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/spots.csv'
  INTO TABLE TripFinder.spots
  FIELDS TERMINATED BY ',' ENCLOSED BY '"'
;

LOAD DATA LOCAL
  INFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/images.csv'
  INTO TABLE TripFinder.images
  FIELDS TERMINATED BY ',' ENCLOSED BY '"'
;

LOAD DATA LOCAL
  INFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/links.csv'
  INTO TABLE TripFinder.links
  FIELDS TERMINATED BY ',' ENCLOSED BY '"'
;

LOAD DATA LOCAL
  INFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/spot_comment.csv'
  INTO TABLE TripFinder.spot_comment
  FIELDS TERMINATED BY ',' ENCLOSED BY '"'
;

LOAD DATA LOCAL
  INFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/favoriteplans.csv'
  INTO TABLE TripFinder.favoriteplans
  FIELDS TERMINATED BY ',' ENCLOSED BY '"'
;

LOAD DATA LOCAL
  INFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/favoritespots.csv'
  INTO TABLE TripFinder.favoritespots
  FIELDS TERMINATED BY ',' ENCLOSED BY '"'
;

LOAD DATA LOCAL
  INFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/follows.csv'
  INTO TABLE TripFinder.follows
  FIELDS TERMINATED BY ',' ENCLOSED BY '"'
;

LOAD DATA LOCAL
  INFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/tags.csv'
  INTO TABLE TripFinder.tags
  FIELDS TERMINATED BY ',' ENCLOSED BY '"'
;

LOAD DATA LOCAL
  INFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/plan_tag.csv'
  INTO TABLE TripFinder.plan_tag
  FIELDS TERMINATED BY ',' ENCLOSED BY '"'
;

LOAD DATA LOCAL
  INFILE '/Users/kuramotoyuuki/workspace/TechBoost/TripFinder/database/BackUp/spot_tag.csv'
  INTO TABLE TripFinder.spot_tag
  FIELDS TERMINATED BY ',' ENCLOSED BY '"'
;
