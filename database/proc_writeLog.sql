use poolboy;
DELIMITER //

DROP PROCEDURE IF EXISTS writeLog //

CREATE PROCEDURE writeLog(IN p_logtext VARCHAR(255)) 
BEGIN 

    -- store information in log
    INSERT INTO EventLog
        (LogTime, LogText) 
    SELECT 	now(), p_logtext;

    -- kill off event records older than a year
    DELETE 
    FROM EventLog
    WHERE LogTime < DATE(date_add(now(), INTERVAL -1 YEAR));

END//

DELIMITER ;

