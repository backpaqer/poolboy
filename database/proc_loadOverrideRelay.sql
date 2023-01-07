use poolboy;
DELIMITER //

DROP PROCEDURE IF EXISTS loadOverrideRelay //

CREATE PROCEDURE loadOverrideRelay(iRelayID INT, iRelayState INT, iDuration INT)
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
      CALL writeLog("Error Captured in loadOverrideRelay");

    -- If there is a current "overrider" record for this relay, we delete it and recreate.
    DELETE FROM MainTimer
    WHERE RelayID = iRelayID
    AND TimerType = 'overrider';

    -- let's go.
    -- iRelayID is the Relay ID we are overriding with and iRelayState we are overriding with.
    SELECT iRelayID INTO @iRelayID;

    IF @iRelayID <> 0 THEN

        INSERT INTO MainTimer 
            (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState) 
        SELECT 	0, iRelayID, "overrider", dayofweek(now()), TIME(date_add(now(), INTERVAL 0 MINUTE)), TIME(date_add(now(), INTERVAL iDuration MINUTE)), iRelayState;

        -- if the override spans midnight, split the override records into two parts (pre-midnight
        -- and post-midnight and then delete the original override records)
        INSERT INTO MainTimer
            (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState) 
        SELECT M.ComboID, M.RelayID, M.TimerType, 
                dayofweek(date_add(now(), INTERVAL 15 MINUTE)),
                "00:00:00",
                M.TimerEnd,
                M.RelayState
                FROM MainTimer M 
                WHERE M.TimerStart > M.TimerEnd
                AND M.TimerType = "overrider";

        INSERT INTO MainTimer
            (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState) 
        SELECT M.ComboID, M.RelayID, M.TimerType, 
                M.TimerDay,
                M.TimerStart,
                "23:59:59",
                M.RelayState
                FROM MainTimer M 
                WHERE M.TimerStart > M.TimerEnd
                AND M.TimerType = "overrider";

        DELETE FROM MainTimer
        WHERE TimerStart > TimerEnd;
    END IF;
END//

DELIMITER ;
