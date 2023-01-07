use poolboy;
DELIMITER //

DROP VIEW IF EXISTS View_NextTimer //

CREATE VIEW View_NextTimer
AS SELECT
        MT.TimerStart AS EventTime,
        CM.ComboName AS EventName,
        "ON" AS EventType,
	 MT.TimerType AS TimerType,
	 0 AS CurrentProcess
    FROM MainTimer MT INNER JOIN ComboMain CM ON MT.ComboID = CM.ComboID
    WHERE MT.TimerStart > now()
    AND MT.TimerDay = dayofweek(now())
    UNION
    SELECT
        MT.TimerEnd AS EventTime,
        CM.ComboName AS EventName,
        "END" AS EventType,
	 MT.TimerType AS TimerType, 
	 0 AS CurrentProcess
    FROM MainTimer MT INNER JOIN ComboMain CM ON MT.ComboID = CM.ComboID
    WHERE MT.TimerEnd > now()
    AND MT.TimerDay = dayofweek(now())
    UNION
    SELECT
        MT.TimerEnd AS EventTime,
        CM.ComboName AS EventName,
        "END" AS EventType,
	 MT.TimerType AS TimerType, 
	 1 AS CurrentProcess
    FROM MainTimer MT INNER JOIN ComboMain CM ON MT.ComboID = CM.ComboID
    WHERE MT.TimerStart < now()
    AND MT.TimerEnd > now()
    AND MT.TimerDay = dayofweek(now())
    ORDER BY 5 DESC, 4 DESC, 1 ASC
    //

 DELIMITER ;