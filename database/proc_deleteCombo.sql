use poolboy;
DELIMITER //

DROP PROCEDURE IF EXISTS deleteCombo //

CREATE PROCEDURE deleteCombo(IN pComboID INT) 
BEGIN 

    SELECT OverrideDisplay INTO @OverrideDisplay
    FROM ComboMain
    WHERE ComboID = pComboID;

    IF @OverrideDisplay = 1 THEN
        -- if an override is no longer valid, delete it.
        DELETE 
        FROM ComboFlow 
        WHERE CurrentComboID = pComboID
        OR AvailableComboID = pComboID;

        DELETE 
        FROM ComboItem
        WHERE ComboID = pComboID;

        DELETE
        FROM MainTimer 
        WHERE ComboID = pComboID;

        DELETE 
        FROM ComboMain
        WHERE ComboID = pComboID;
    END IF;

END//

DELIMITER ;

