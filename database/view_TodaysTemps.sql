 use poolboy;
 DELIMITER //

DROP VIEW IF EXISTS View_TodaysTemps //

CREATE VIEW View_TodaysTemps 
AS SELECT 
        S.SensorCode AS SensorCode,
        S.SensorName AS SensorName,
        CONCAT('[',TIME_FORMAT(TH.TempTime, '%H,%i,%s'),']') AS SenseTime, 
        FORMAT(TH.TempValue,3) AS SenseTemp 
    FROM TempHistory TH INNER JOIN Sensors S ON TH.TempSensor = S.SensorCode 
    WHERE TIME_FORMAT(TH.TempTime, '%i') IN (0,15,30,45)
    ORDER BY TH.TempTime //

 DELIMITER ;