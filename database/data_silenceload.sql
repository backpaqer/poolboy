use poolboy;

DELETE FROM MainTimer WHERE TimerType = 'silence';

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT C.ComboID, C.RelayID, 'silence', 1, '00:00:00', '06:59:59', 0 FROM ComboItem C WHERE C.ComboID = 4;

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT C.ComboID, C.RelayID, 'silence', 1, '22:00:00', '23:59:59', 0 FROM ComboItem C WHERE C.ComboID = 4;

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT C.ComboID, C.RelayID, 'silence', 2, '00:00:00', '06:59:59', 0 FROM ComboItem C WHERE C.ComboID = 4;

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT C.ComboID, C.RelayID, 'silence', 2, '22:00:00', '23:59:59', 0 FROM ComboItem C WHERE C.ComboID = 4;

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT C.ComboID, C.RelayID, 'silence', 3, '00:00:00', '06:59:59', 0 FROM ComboItem C WHERE C.ComboID = 4;

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT C.ComboID, C.RelayID, 'silence', 3, '22:00:00', '23:59:59', 0 FROM ComboItem C WHERE C.ComboID = 4;

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT C.ComboID, C.RelayID, 'silence', 4, '00:00:00', '06:59:59', 0 FROM ComboItem C WHERE C.ComboID = 4;

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT C.ComboID, C.RelayID, 'silence', 4, '22:00:00', '23:59:59', 0 FROM ComboItem C WHERE C.ComboID = 4;

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT C.ComboID, C.RelayID, 'silence', 5, '00:00:00', '06:59:59', 0 FROM ComboItem C WHERE C.ComboID = 4;

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT C.ComboID, C.RelayID, 'silence', 5, '22:00:00', '23:59:59', 0 FROM ComboItem C WHERE C.ComboID = 4;

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT C.ComboID, C.RelayID, 'silence', 6, '00:00:00', '06:59:59', 0 FROM ComboItem C WHERE C.ComboID = 4;

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT C.ComboID, C.RelayID, 'silence', 6, '22:00:00', '23:59:59', 0 FROM ComboItem C WHERE C.ComboID = 4;

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT C.ComboID, C.RelayID, 'silence', 7, '00:00:00', '06:59:59', 0 FROM ComboItem C WHERE C.ComboID = 4;

INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState)
SELECT C.ComboID, C.RelayID, 'silence', 7, '22:00:00', '23:59:59', 0 FROM ComboItem C WHERE C.ComboID = 4;
