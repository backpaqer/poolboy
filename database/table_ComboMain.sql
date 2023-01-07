 use poolboy;
 DELIMITER //

DROP TABLE IF EXISTS ComboMain //
CREATE TABLE ComboMain (
  ComboID INT NOT NULL AUTO_INCREMENT,
  ComboName text,
  ComboDesc text,
  OverrideDisplay int,
  PRIMARY KEY (ComboID)
)//

DELIMITER ;

INSERT INTO ComboMain (ComboID, ComboName, ComboDesc, OverrideDisplay)
VALUES
    (1,'SPA','Alters valves, pump on, heat on, solar auto, chlorinator off etc',1),
    (2,'NORMAL','Normal pool filtration',1),
    (3,'VAC','Settings for manual pool vacuuming',1),
    (4,'QUIET','Restrict noisy equipment operation',0);
