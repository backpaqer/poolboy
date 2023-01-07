use poolboy;
DELIMITER //

DROP PROCEDURE IF EXISTS insertCombo //

CREATE PROCEDURE insertCombo(IN pComboName VARCHAR(255), IN pComboDesc TEXT) 
BEGIN 
    SELECT COUNT(*) INTO @count 
    FROM ComboMain
    WHERE ComboName = pComboName;

    -- don't create duplicates, don't throw error either.
    IF @count = 0 THEN
        -- store information
        INSERT INTO ComboMain 
            (ComboName, ComboDesc, OverrideDisplay) 
        VALUES (pComboName,pComboDesc,1);
    END IF;

END//

DELIMITER ;

