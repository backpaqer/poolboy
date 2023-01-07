use poolboy;
DELIMITER //

DROP PROCEDURE IF EXISTS writeTemperature //

CREATE PROCEDURE writeTemperature(IN pSensor VARCHAR(255), IN pTemp FLOAT) 
BEGIN 
    DECLARE historical_temps INTEGER;
    DECLARE sensor_present INTEGER;
    DECLARE summary_check INTEGER;
    DECLARE sensor_relay INTEGER;

    -- store information in log
    INSERT INTO TempHistory
        (TempTime, TempSensor, TempValue) 
    SELECT 	now(), pSensor, pTemp;

    -- Check for Sensor
    SELECT COUNT(*) INTO sensor_present
    FROM Sensors 
    WHERE SensorCode = pSensor;

    IF sensor_present = 0 THEN
        INSERT INTO Sensors
            (SensorCode, SensorName, SensorType)
        VALUES
            (pSensor, 'New Sensor', 'Temp');
    END IF;

    -- if there's historical temp values for previous day, create a history record and kill them off
    -- as long as there isn't an existing summary record that is now that we're doing rolling 24 hour data
    SELECT COUNT(*) INTO summary_check
    FROM TempSummary
    WHERE TempTime = DATE(NOW());

    IF summary_check = 0 THEN
        SELECT COUNT(*) INTO historical_temps
        FROM TempHistory 
        WHERE DATE(TempTime) < DATE(now());

        IF historical_temps > 0 THEN
            INSERT INTO TempSummary 
                (TempTime, TempSensor, TempValueMin, TempValueMax, TempValueAvg)
            SELECT DATE(TempTime), TempSensor, MIN(TempValue), MAX(TempValue), AVG(TempValue)
            FROM TempHistory
            WHERE DATE(TempTime) < DATE(now())
            GROUP BY DATE(TempTime), TempSensor;

            -- DELETE 
            -- FROM TempHistory
            -- WHERE TempTime < DATE(now());

            DELETE
            FROM TempHistory
            WHERE TempTime < DATE_ADD(now(), INTERVAL -1 DAY);

        END IF;
    END IF;

    -- Check to see if there is a relay threshold set up for this sensor and call the loadoverride proc that checks/sets any overrides
    -- for where sensors exceed the thresholds (upper or lower)
    SELECT RelayID INTO sensor_relay
    FROM RelaySensors
    WHERE SensorCode = pSensor;

    IF sensor_relay > 0 THEN
        CALL loadOverrideRelaySensor(sensor_relay);
    END IF;
END//

DELIMITER ;

