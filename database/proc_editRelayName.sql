use poolboy;
DELIMITER //

DROP PROCEDURE IF EXISTS editRelayName //

CREATE PROCEDURE editRelayName(iRelayID INT, iRelayName TEXT)
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
      CALL writeLog("Error Captured in editRelayName");

    CALL writeLog(CONCAT("editRelayName executed(",iRelayID,",",iRelayName,")"));

    UPDATE Relays
    SET RelayDescription = iRelayName
    WHERE RelayID = iRelayID;

END//

DELIMITER ;
