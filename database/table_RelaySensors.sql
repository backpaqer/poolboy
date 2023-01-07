use poolboy;
DELIMITER //

DROP TABLE IF EXISTS RelaySensors //

CREATE TABLE RelaySensors (
  RelayID       INT,      -- pointer to Relay table
  SensorCode    CHAR(20), -- pointer to Sensor table
  TriggerMax    BIT,      -- 1 = this is a maximum test value, 0 = this is a minimum test value
  TriggerValue  FLOAT,    -- this is the value of the sensor to test 
  DoWhat        TINYINT,  -- 0 = turn off, 1 = turn on.
  TestPeriod    INT,      -- when the condition is met, this is how long to put an override in for in minutes
  PRIMARY KEY (RelayID, SensorCode)
)//

DELIMITER ;

INSERT INTO RelaySensors (RelayID, SensorCode, TriggerMax, TriggerValue, DoWhat, TestPeriod)
VALUES
    (3,'28-021317eecfaa', 1, 38.0, 0, 30);  -- example turn heater off if temp exceeds 38C.

INSERT INTO RelaySensors (RelayID, SensorCode, TriggerMax, TriggerValue, DoWhat, TestPeriod)
VALUES
    (4,'dummy garage heater', 0, 10.0, 1, 15); -- example turn garage heating on for 15 mins if temp drops below 10C 
