use poolboy;
DELIMITER //

DROP PROCEDURE IF EXISTS loadOverride //

CREATE PROCEDURE loadOverride(p1 INT)
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
      CALL writeLog("Error Captured in loadOverride");

    -- p1 is the Combo ID we are overriding with.
    -- updated routine to firstly rename the existing "override" records to a different name so that
    -- any connectivity remains current and overrides the new overrides until this routine is completed
    -- and we will then delete these displaced overrides at the end so the new overrides can take over
    -- initially clear out any existing overrides so we don't get tangled up - keeps things simple.
    -- then load all the combo settings in with their timings.
    START TRANSACTION;

    SELECT p1 INTO @p1;
    SELECT 0 INTO @Invert;

    -- updated to take both normal override (combo) and overrider (relay) overrides and then reset the temp (a) records
    -- and the relay (r) records at the end so that any relay overrides would need to be retested.
    UPDATE MainTimer
    SET TimerType = "overridea"
    WHERE TimerType = "override";

    -- if calling param is 0, we're just clearing out the overrides, so we're not loading anything in.
    IF @p1 <> 0 THEN

        -- if we have a current running normal schedule and we want to create an override for the same combo
        -- - we are going to "invert" the override, ie cancel out the normal programming for the duration of the normal programming.
        SELECT IFNULL(MAX(1),0)
        INTO @Invert
        FROM MainTimer
        WHERE TimerDay = dayofweek(now())
        AND TimerStart <= now()
        AND TimerEnd > now()
        AND TimerType = 'normal'
        AND ComboID = p1;

        -- If @Invert=0 then normal override
        -- Note: Invert processing currently does not span midnight. 
        -- Normally users wouldn't be stuffing about at this time anyway (normal users that is)
        IF @Invert = 0 THEN
            INSERT INTO MainTimer 
                (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState) 
            SELECT 	C.ComboID, 
                    C.RelayID, 
                    "override",
                    dayofweek(now()), 
                    TIME(date_add(now(), INTERVAL C.RelayStartPad MINUTE)), 
                    TIME(date_add(now(), INTERVAL C.RunMins MINUTE)), 
                    C.RelayState
                    FROM ComboItem C 
                    INNER JOIN View_ActiveRelays A ON C.RelayID = A.RelayID
                    WHERE C.ComboID = p1;
        ELSE
        -- If @Invert <> 0 then clone the current normal schedule as an override and invert the RelayState.
            INSERT INTO MainTimer 
                (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState) 
            SELECT  ComboID, 
                    RelayID, 
                    "override",
                    TimerDay,
                    TimerStart,
                    TimerEnd,
                    (1 - RelayState)
            FROM MainTimer
            WHERE TimerDay = dayofweek(now())
            AND TimerStart <= now()
            AND TimerEnd > now()
            AND TimerType = 'normal'
            AND ComboID = p1;
        END IF;

        -- if the override spans midnight, split the override records into two parts (pre-midnight
        -- and post-midnight and then delete the original override records)
        INSERT INTO MainTimer
            (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState) 
        SELECT M.ComboID, M.RelayID, M.TimerType, 
                dayofweek(date_add(now(), INTERVAL C.RunMins MINUTE)),
                "00:00:00",
                M.TimerEnd,
                M.RelayState
                FROM MainTimer M 
                INNER JOIN ComboItem C ON M.ComboID = C.ComboID AND M.RelayID = C.RelayID 
                WHERE M.TimerStart > M.TimerEnd
                AND M.TimerType = "override";

        INSERT INTO MainTimer
            (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState) 
        SELECT M.ComboID, M.RelayID, M.TimerType, 
                M.TimerDay,
                M.TimerStart,
                "23:59:59",
                M.RelayState
                FROM MainTimer M 
                WHERE M.TimerStart > M.TimerEnd
                AND M.TimerType = "override";

        DELETE FROM MainTimer
        WHERE TimerStart > TimerEnd;
    END IF;

    SELECT COUNT(*) INTO @count
      FROM MainTimer
      WHERE TimerType = "override";

    -- if no new override records detected but a valid relay was passed in to override - don't kill off existing.
    IF @count = 0 AND @p1 <> 0 THEN
        ROLLBACK;
        CALL writeLog("loadOverride ROLLBACK OCCURRED");
    ELSE
        -- overrider comes before override in the sort (descending sort)
        DELETE FROM MainTimer
        WHERE TimerType = "overridea"
        OR TimerType = 'overrider';

        COMMIT;
    END IF;

END//

DELIMITER ;
