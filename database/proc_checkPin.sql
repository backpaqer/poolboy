use poolboy;
DELIMITER //

DROP PROCEDURE IF EXISTS checkPin //

CREATE PROCEDURE checkPin(IN pPin INT, INOUT pRelayState INT) 
BEGIN 
    SELECT M.RelayState, M.TimerType, M.RelayID
    INTO @RelayState, @TimerType, @RelayID
    FROM MainTimer M 
    INNER JOIN View_ActiveRelays A ON M.RelayID = A.RelayID 
    WHERE M.TimerDay = dayofweek(now()) 
    AND M.TimerStart <= now() 
    AND M.TimerEnd > now() 
    AND A.RelayGPIOpin = pPin
    ORDER BY M.TimerType, ID DESC
    LIMIT 1;

    -- If the relay is turned off 
    IF @RelayState <> 1 THEN
        -- but there's a cooldown timer in effect
        SELECT 1 INTO @RelayState
        FROM Cooldown
        WHERE RelayID = @RelayID;
    ELSE
    -- If the relay is turned on, 
        -- but there's a max temp limit imposed that will turn something (like a heater) off
        SELECT 0, R.TestPeriod
        INTO @RelayState, @TestPeriod 
        FROM RelaySensors R
        INNER JOIN TempHistory T ON T.TempSensor = R.SensorCode 
        INNER JOIN Sensors S ON S.SensorCode = R.SensorCode
        WHERE R.RelayID = @RelayID
        AND R.TriggerMax = 1
        AND R.DoWhat = 0
        AND S.SensorType = 'Temp'
        AND T.TempValue >= R.TriggerValue
        AND T.TempTime > DATE_ADD(now(), INTERVAL (-1 * R.TestPeriod) MINUTE)
        ORDER BY T.TempTime DESC
        LIMIT 1;

        -- if we have a period, then load in an override that keeps things turned off.
        IF @TestPeriod > 0 THEN
            CALL loadOverrideRelay(@RelayID, 0, @TestPeriod);
        END IF;
    END IF;

    SET pRelayState = @RelayState;

END//

DELIMITER ;

