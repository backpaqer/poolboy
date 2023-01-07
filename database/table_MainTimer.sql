use poolboy;
DELIMITER //

DROP TABLE IF EXISTS MainTimer //
CREATE TABLE MainTimer (
  ID            INT NOT NULL AUTO_INCREMENT,
  ComboID       INT NOT NULL,
  RelayID       INT NOT NULL,
  TimerType     TEXT NOT NULL,
  TimerDay      TINYINT unsigned NOT NULL, 
  TimerStart    TIME NOT NULL,
  TimerStartX   VARCHAR(10),
  TimerEnd      TIME NOT NULL,
  TimerEndX     VARCHAR(10),
  RelayState    TINYINT NOT NULL,
  PRIMARY KEY   (ID)
)//

DELIMITER ;

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT ComboID, RelayID, 'normal', S.Days,'09:00:00','17:00:00', RelayState 
FROM ComboItem, (SELECT ones.1 AS Days FROM (VALUES (1),(2),(3),(4),(5),(6),(7)) ones) S 
WHERE ComboID = 2;
