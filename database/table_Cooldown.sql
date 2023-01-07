use poolboy;
DELIMITER //

-- Simple table. If a record exists in here, then the relay must be on until the EndTime is met. Then the record can be deleted.
DROP TABLE IF EXISTS Cooldown //
CREATE TABLE Cooldown (
  CooldownID INT NOT NULL AUTO_INCREMENT,
  MainRelayID INT NOT NULL,
  RelayID INT NOT NULL,
  EndTime DATETIME NOT NULL,
  PRIMARY KEY (CooldownID)
)//

DELIMITER ;
