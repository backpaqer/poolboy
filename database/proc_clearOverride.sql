use poolboy;
DELIMITER //

DROP PROCEDURE IF EXISTS clearOverride //

CREATE PROCEDURE clearOverride(OUT p1 INT) 
BEGIN 

    -- if an override is no longer valid, delete it.
    -- overrides are only short term. if it's today (and expired) - then delete it
    -- if not today or tomorrow, delete it
    SELECT COUNT(*) INTO p1 
    FROM MainTimer 
    WHERE TimerType LIKE 'override%' 
    AND ((TimerDay = dayofweek(now()) AND TimerEnd < now()) 
    OR ((TimerDay <> dayofweek(date_add(now(), INTERVAL 24 HOUR)) AND (TimerDay <> dayofweek(now())))));

    DELETE 
    FROM MainTimer 
    WHERE TimerType LIKE 'override%' 
    AND ((TimerDay = dayofweek(now()) AND TimerEnd < now()) 
    OR ((TimerDay <> dayofweek(date_add(now(), INTERVAL 24 HOUR)) AND (TimerDay <> dayofweek(now())))));

    -- adding in cooldown clearing
    DELETE 
    FROM Cooldown
    WHERE EndTime < now();

END//

DELIMITER ;

