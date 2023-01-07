use poolboy;
DELIMITER //

DROP TABLE IF EXISTS Sensors //

CREATE TABLE Sensors (
  SensorCode    CHAR(20),
  SensorName    VARCHAR(255),
  SensorType    CHAR(20),
  PRIMARY KEY (SensorCode)
)//

DELIMITER ;

--INSERT INTO Sensors (SensorCode, SensorName, SensorType)
--VALUES
--    ('28-021317bf77aa', 'Air Temp', 'Temp'),
--    ('28-021317eecfaa', 'Pool Temp', 'Temp');
