use poolboy;
DELIMITER //

-- Simple table to provide a mandatory cooldown time in seconds
-- for example, if the heater has been running then when you want to turn it off this can force a pump to continue to circulate 
-- for a predetermined cooldown time.
DROP TABLE IF EXISTS RelayCooldown //
CREATE TABLE RelayCooldown (
  RelayID INT NOT NULL,
  Relay2ID INT NOT NULL,
  CooldownMin INT NOT NULL,
  PRIMARY KEY (RelayID, Relay2ID)
)//

DELIMITER ;
