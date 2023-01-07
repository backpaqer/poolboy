use poolboy;
DELIMITER //

DROP PROCEDURE IF EXISTS clearNormal //

CREATE PROCEDURE clearNormal(IN pComboID INT, IN pDay INT, IN pStart CHAR(8)) 
BEGIN 

    CALL writeLog(CONCAT("clearNormal executed(",pComboID,":",pDay,":",pStart,")"));

    DELETE 
    FROM MainTimer 
    WHERE TimerType = "normal" 
    AND ComboID = pComboID
    AND TimerDay = pDay
    AND TimerStart = pStart;
END//

DELIMITER ;

