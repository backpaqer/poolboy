use poolboy;
DELIMITER //

DROP TABLE IF EXISTS ComboFlow //
CREATE TABLE ComboFlow (
    CurrentComboID      INT,
    AvailableComboID    INT,
    FlowDescription     text,
    PRIMARY KEY         (CurrentComboID, AvailableComboID)
)//

DELIMITER ;

INSERT INTO ComboFlow (CurrentComboID, AvailableComboID, FlowDescription) 
VALUES 
    (0,1,'Spa'),
    (0,2,'Pool'),
    (0,3,'Vac'),
    (1,0,'Spa Off'),
    (1,2,'Pool'),
    (1,3,'Vac'),
    (2,0,'Pool Off'),
    (2,1,'Spa'),
    (2,3,'Vac'),
    (3,0,'Vac Off'),
    (3,1,'Spa'),
    (3,2,'Pool');
