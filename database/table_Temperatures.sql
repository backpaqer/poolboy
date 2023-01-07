use poolboy;
DELIMITER //

DROP TABLE IF EXISTS TempHistory //
DROP TABLE IF EXISTS TempSummary //

CREATE TABLE TempHistory (
  TempTime      DATETIME NOT NULL,
  TempSensor    VARCHAR(255) NOT NULL,
  TempValue     FLOAT 
)//

CREATE TABLE TempSummary (
  TempTime      DATE NOT NULL,
  TempSensor    VARCHAR(255) NOT NULL,
  TempValueMin  FLOAT,
  TempValueMax  FLOAT,
  TempValueAvg  FLOAT
)//

DELIMITER ;