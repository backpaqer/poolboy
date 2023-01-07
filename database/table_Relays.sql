--
--+------------------+---------------------+------+-----+---------+-------+
--| Field            | Type                | Null | Key | Default | Extra |
--+------------------+---------------------+------+-----+---------+-------+
--| RelayID          | int(10) unsigned    | NO   | PRI | NULL    |       |
--| RelayGPIOpin     | int(10) unsigned    | YES  |     | NULL    |       |
--| RelayDescription | text                | YES  |     | NULL    |       |
--| RelayOrder       | int(10) unsigned    | YES  |     | NULL    |       |
--| RelayActive      | tinyint(3) unsigned | YES  |     | NULL    |       |
--| RelayIcon        | text                | YES  |     | NULL    |       |
--+------------------+---------------------+------+-----+---------+-------+
use poolboy;
DELIMITER //

DROP TABLE IF EXISTS Relays //
CREATE TABLE Relays (
    RelayID             INT,
    RelayGPIOpin        INT,
    RelayDescription    TEXT,
    RelayOrder          INT,
    RelayActive         TINYINT,
    RelayIcon           TEXT,
    PRIMARY KEY         (RelayID)
)//

DELIMITER ;
INSERT INTO Relays (RelayID, RelayGPIOpin, RelayDescription, RelayOrder,RelayActive, RelayIcon)
VALUES
    (1,15,'Pump',1,1,''),
    (2,14,'Spa',2,1,''),
    (3,23,'Heat',3,1,''),
    (4,18,'Jets',4,1,''),
    (5,25,'Cell',5,1,''),
    (6,24,'Main',6,1,''),
    (7,7,'Blow',7,1,''),
    (8,8,'Relay 8',8,1,''),
    (9,5,'Relay 9',9,1,''),
    (10,12,'Relay 10',10,1,''),
    (11,20,'Relay 11',11,1,''),
    (12,16,'Relay 12',12,1,''),
    (13,21,'Relay 13',13,1,''),
    (14,6,'Relay 14',14,1,''),
    (15,19,'Relay 15',15,1,''),
    (16,13,'Relay 16',16,1,'');