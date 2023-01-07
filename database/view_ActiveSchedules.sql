 use poolboy;
 DELIMITER //

DROP VIEW IF EXISTS View_ActiveSchedules //

CREATE VIEW View_ActiveSchedules 
AS SELECT DISTINCT 
        C.ComboID, 
        C.ComboName, 
        C.ComboDesc, 
        M.TimerDay, 
        M.TimerStart, 
        M.TimerEnd 
    FROM MainTimer M 
    INNER JOIN ComboMain C ON C.ComboID = M.ComboID 
    WHERE TimerType = 'normal' //

 DELIMITER ;