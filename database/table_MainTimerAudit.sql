use poolboy;
DELIMITER //

DROP TABLE IF EXISTS MainTimerAudit; //

CREATE TABLE MainTimerAudit (
  ID            INT NOT NULL,
  ComboID       INT NOT NULL,
  RelayID       INT NOT NULL,
  TimerType     TEXT NOT NULL,
  TimerDay      TINYINT unsigned NOT NULL,
  TimerStart    TIME NOT NULL,
  TimerStartX   VARCHAR(10),
  TimerEnd      TIME NOT NULL,
  TimerEndX     VARCHAR(10),
  RelayState    TINYINT NOT NULL,
  ChangeAction  VARCHAR(50) DEFAULT NULL,
  ChangeDate    DATETIME DEFAULT NULL
)//

DELIMITER ;