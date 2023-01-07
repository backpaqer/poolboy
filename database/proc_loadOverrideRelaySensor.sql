use poolboy;
DELIMITER //

DROP PROCEDURE IF EXISTS loadOverrideRelaySensors //

CREATE PROCEDURE loadOverrideRelaySensors(iRelayID INT)
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
      CALL writeLog("Error Captured in loadOverrideRelaySensor");

    CALL writeLog(CONCAT("loadOverrideRelay executed(",iRelayID,")"));

    SELECT iRelayID INTO @iRelayID;

    -- this introduces a new override level, we'll call it overridesen
    -- which needs to be wiped out by other procs.

    -- Valid Relay ID passed in, check to see if there is a RelaySensor override definition.
    IF @iRelayID <> 0 THEN
        -- Is there an existing overridesen?
        SELECT COUNT(*) INTO @ExistingSen
        FROM MainTimer
        WHERE RelayID = @iRelayID
        AND TimerType = 'overrider';

        IF @ExistingSen = 0 THEN    
            -- based on relay, is there a related sensor? If not, goodbye. 
            -- if so, check TriggerMax if=1 check to see if sensor exceeds TriggerValue
            -- or. if TriggerMax=0 check to see if sensor is under TriggerValue
            -- if true value DoWhat is loaded into the override relaystate
            -- TestPeriod is how long the override is to last for before the sensor is checked again.
            -- note that this testperiod is mainly useful for pump/temp pairs as generally you don't want to be power-cycling a pump every minute

            -- BETA: consider that RelayID is a primary key and that we'll only have one sensor driving it.

            SELECT  SensorCode, TriggerMax, TriggerValue, DoWhat, TestPeriod, COUNT(*) 
            INTO @RSSensorCode, @RSTriggerMax, @RSTriggerValue, @RSDoWhat, @RSTestPeriod, @RSCount
            FROM RelaySensors 
            WHERE RelayID = @iRelayID;

            -- if there's a Relay Sensor relationship present, grab the latest sensor temperature and test against the triggers
            IF @RSCount = 1 THEN
                SELECT TempValue INTO @CurrentSensorValue
                FROM TempHistory
                WHERE TempSensor = @RSSensorCode
                ORDER BY TempTime DESC LIMIT 1;

                IF (@RSTriggerMax = 1 && @RSTriggerValue < @CurrentSensorValue) 
                OR (@RSTriggerMax = 0 && @RSTriggerValue > @CurrentSensorValue) THEN
                    CALL loadOverrideRelay(@iRelayID, @RSDoWhat, @RSTestPeriod);
                END IF;
            END IF;
        END IF;
    END IF;

END//

DELIMITER ;
