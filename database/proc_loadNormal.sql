use poolboy;
DELIMITER //

DROP PROCEDURE IF EXISTS loadNormal //

CREATE PROCEDURE loadNormal(IN pComboID INT, IN pDay INT, IN pStart CHAR(8), IN pEnd CHAR(8))
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
      CALL writeLog("Error Captured in loadNormal");

    CALL writeLog(CONCAT("loadNormal executed(",pComboID,":",pDay,":'",pStart,"':'",pEnd,"')"));

    INSERT INTO MainTimer 
        (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState) 
    SELECT C.ComboID, 
        C.RelayID, 
        "normal", 
        pDay,
        pStart, 
        pEnd, 
        C.RelayState 
        FROM ComboItem C 
        INNER JOIN View_ActiveRelays A ON C.RelayID = A.RelayID 
        WHERE C.ComboID = pComboID;

END//

DELIMITER ;
