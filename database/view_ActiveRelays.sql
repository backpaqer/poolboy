 use poolboy;
 DELIMITER //

DROP VIEW IF EXISTS ActiveRelays //
DROP VIEW IF EXISTS View_ActiveRelays //

CREATE VIEW View_ActiveRelays 
AS SELECT 
        Relays.RelayID AS RelayID,
        Relays.RelayGPIOpin AS RelayGPIOpin,
        Relays.RelayDescription AS RelayDescription 
    FROM Relays 
    WHERE Relays.RelayActive = 1
    ORDER BY Relays.RelayOrder //

 DELIMITER ;