use poolboy;
DELIMITER //

-- Triggers for the MainTimer table to maintain a history of when things get updated

IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TRIGGERS WHERE TRIGGER_NAME = 'before_MainTimer_insert') THEN
    DROP TRIGGER before_MainTimer_insert;
END IF;

CREATE TRIGGER before_MainTimer_insert 
    BEFORE INSERT ON MainTimer 
    FOR EACH ROW
BEGIN   
    INSERT INTO MainTimerAudit
    SET ChangeAction = 'insert',
        ChangeDate = NOW(),
        ID = NEW.ID,
        ComboID = NEW.ComboID,
        RelayID = NEW.RelayID,
        TimerType = NEW.TimerType,
        TimerDay = NEW.TimerDay,
        TimerStart = NEW.TimerStart,
        TimerEnd = NEW.TimerEnd,
        RelayState = NEW.RelayState;

    -- If the RelayID exists on MainTimer with a State = 1 (on) and this turns it off, create the cooldown timer if necessary.
    IF NEW.RelayState = 0 THEN

        SELECT COUNT(*) INTO @Count
        FROM MainTimer M 
        WHERE M.TimerDay = dayofweek(now()) 
            AND M.TimerStart <= now() \
            AND M.TimerEnd > now() \
            AND M.RelayID = NEW.RelayID
            AND M.RelayState = 1
        ORDER BY M.TimerType DESC LIMIT 1;            

        IF @Count > 0 THEN 
            -- clean it out first
            DELETE FROM Cooldown WHERE MainRelayID = NEW.RelayID;

            INSERT INTO Cooldown (MainRelayID, RelayID, EndTime)
            SELECT RC.RelayID, RC.Relay2ID, date_add(now(), INTERVAL RC.CooldownMin MINUTE)
            FROM RelayCooldown RC
            WHERE RC.RelayID = NEW.RelayID;
        END IF;

    END IF;

END; //

IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TRIGGERS WHERE TRIGGER_NAME = 'before_MainTimer_delete') THEN
    DROP TRIGGER before_MainTimer_delete;
END IF;

CREATE TRIGGER before_MainTimer_delete
    BEFORE DELETE ON MainTimer 
    FOR EACH ROW
BEGIN   
    INSERT INTO MainTimerAudit
    SET ChangeAction = 'delete',
        ChangeDate = NOW(),
        ID = OLD.ID,
        ComboID = OLD.ComboID,
        RelayID = OLD.RelayID,
        TimerType = OLD.TimerType,
        TimerDay = OLD.TimerDay,
        TimerStart = OLD.TimerStart,
        TimerEnd = OLD.TimerEnd,
        RelayState = OLD.RelayState;

    -- If the RelayID exists on MainTimer with a State = 1 (on) and this deletes it (turns it off), create the cooldown timer.
    IF OLD.RelayState = 1 THEN

        -- let's make this simple. if we turn off a relay that exists in the RelayCooldown table
        -- then we add a record for the other relay it relies on in the Cooldown table.
        DELETE FROM Cooldown WHERE MainRelayID = OLD.RelayID;

        INSERT INTO Cooldown (MainRelayID, RelayID, EndTime)
        SELECT RC.RelayID, RC.Relay2ID, date_add(now(), INTERVAL RC.CooldownMin MINUTE)
        FROM RelayCooldown RC
        WHERE RC.RelayID = OLD.RelayID;

    END IF;
END; //

DELIMITER ;