use poolboy;
DELIMITER //

DROP TABLE IF EXISTS ComboItem //
CREATE TABLE ComboItem (
  ComboID INT NOT NULL,
  RelayID INT NOT NULL,
  RelayState TINYINT unsigned NOT NULL,
  RelayStartPad INT DEFAULT NULL,
  RunMins INT DEFAULT NULL,
  PRIMARY KEY (ComboID, RelayID)
)//

DELIMITER ;

INSERT INTO ComboItem (ComboID, RelayID, RelayState, RelayStartPad, RunMins)
VALUES
    (1,1,1,0,180),
    (1,2,1,0,180),
    (1,3,1,0,180),
    (1,4,1,30,180),
    (1,5,0,0,180),
    (1,6,0,0,180),
    (2,1,1,0,180),
    (2,2,0,0,180),
    (2,3,0,0,180),
    (2,4,0,0,180),
    (2,5,1,0,180),
    (2,6,1,0,180),
    (3,1,1,0,30),
    (3,2,0,0,30),
    (3,6,0,0,30),
    (4,1,0,0,180),
    (4,3,0,0,180),
    (4,4,0,0,180),
    (4,5,0,0,180);
