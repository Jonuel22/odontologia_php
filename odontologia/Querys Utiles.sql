-- QUERY QUE ME BUSCA TODOS LOS DOCTORES Y SU ESPECIALIDAD:

SELECT per.nom_per,c.cargo,esp.des_esp FROM persona per inner join empleados empl on  per.id_per=empl.id_per 
join cargos c on c.id_cargo=empl.cargo JOIN empl_vs_espec vs on vs.empl=empl.id_empl 
join especialidad esp on esp.id_esp=vs.espec

-- PROCEDURE PARA CAMBIAR EL ESTADO DE LA CITA
DELIMITER $$
CREATE PROCEDURE actualizar_estados_citas()
BEGIN
  UPDATE citas_medicas SET estado = 'FINALIZADO' WHERE estado = 'EN CURSO' AND hora_fin <= NOW();
END $$
DELIMITER ;

-- EVENTO QUE SE EJECUTA CADA MINUTO EN LA BASE DE DATOS PARA VER SI YA LA CITA PASO
CREATE EVENT actualizar_citas_programado
ON SCHEDULE EVERY 1 MINUTE
DO
  CALL actualizar_estados_citas();

--Query para activar el evento
SET GLOBAL event_scheduler = ON;
--Query para ver si esta activado
SHOW VARIABLES LIKE 'event_scheduler';


--Vamo' a eto' brutal

--Este Query me busca los doctores que entran antes de la 1 y salen a la 3 o despues de la 3
SELECT per.nom_per,c.cargo,esp.des_esp,hor.entrada,hor.salida FROM persona per 
inner join empleados empl on  per.id_per=empl.id_per 
join cargos c on c.id_cargo=empl.cargo 
JOIN empl_vs_espec vs on vs.empl=empl.id_empl 
join especialidad esp on esp.id_esp=vs.espec
inner join horarios hor on empl.horario=hor.id_horario 
where (hor.entrada <= '13:00:00' AND salida >= '15:00:00')



--Doctores que estan ocupados

SELECT per.nom_per,c.cargo,esp.des_esp,hor.entrada,hor.salida,e.start FecCita, e.hora HoraCita, ADDTIME(e.hora,'01:00:00') FinCita  FROM persona per 
inner join empleados empl on  per.id_per=empl.id_per 
join cargos c on c.id_cargo=empl.cargo 
JOIN empl_vs_espec vs on vs.empl=empl.id_empl 
join especialidad esp on esp.id_esp=vs.espec
inner join horarios hor on empl.horario=hor.id_horario join evento e on e.doctor=empl.id_empl
where e.status=5 group by empl.id_empl




SELECT per.nom_per, c.cargo, esp.des_esp, hor.entrada, hor.salida, e.start FecCita,
e.hora HoraCita, ADDTIME(e.hora,'01:00:00') FinCita,
CONCAT("Desde ",e.hora," Hasta ", ADDTIME(e.hora,'01:00:00') ) Ocupado,
CONCAT("Desde ", ADDTIME(e.hora,'01:00:00'), " hasta ", hor.salida) Disponible
FROM persona per 
INNER JOIN empleados empl ON per.id_per=empl.id_per 
JOIN cargos c ON c.id_cargo=empl.cargo 
JOIN empl_vs_espec vs ON vs.empl=empl.id_empl 
JOIN especialidad esp ON esp.id_esp=vs.espec
INNER JOIN horarios hor ON empl.horario=hor.id_horario 
JOIN evento e ON e.doctor=empl.id_empl
WHERE hor.entrada < e.hora AND hor.salida > ADDTIME(e.hora,'1:00:00') 
GROUP BY empl.id_empl;








SELECT per.nom_per, c.cargo, esp.des_esp, hor.entrada, hor.salida, e.start FecCita,
 e.hora HoraCita, ADDTIME(e.hora,'01:00:00') FinCita,
 CONCAT("Desde ",e.hora," Hasta ", ADDTIME(e.hora,'01:00:00') ) Ocupado,
 CONCAT("Desde ", ADDTIME(e.hora,'01:00:00'), " hasta ", hor.salida) Disponible
FROM persona per 
INNER JOIN empleados empl ON per.id_per=empl.id_per 
JOIN cargos c ON c.id_cargo=empl.cargo 
JOIN empl_vs_espec vs ON vs.empl=empl.id_empl 
JOIN especialidad esp ON esp.id_esp=vs.espec
INNER JOIN horarios hor ON empl.horario=hor.id_horario 
JOIN evento e ON e.doctor=empl.id_empl where e.start>CURDATE();










---------vaina pa asignar una hora random entre horario de entrada y salida del doctor-------------
SET @id_doc=1;
-- Obtener la hora de entrada y salida del empleado
SET @horEntrada=(SELECT hor.entrada FROM horarios hor INNER JOIN empleados empl on empl.horario=hor.id_horario WHERE empl.id_empl=@id_doc);
select @horEntrada;
SET @horSalida=(SELECT hor.salida FROM horarios hor INNER JOIN empleados empl on empl.horario=hor.id_horario WHERE empl.id_empl=@id_doc);
select @horSalida;

-- Convertir la hora de entrada y salida en segundos
SET @segEntrada = TIME_TO_SEC(@horEntrada);
SET @segSalida = TIME_TO_SEC(@horSalida);

-- Restar una hora a la hora de salida
SET @segSalidaMenosUnaHora = @segSalida - 3600;

-- Generar un número aleatorio entre 0 y 1
SET @rand = RAND();

-- Calcular la hora aleatoria dentro del rango
SET @segAleatorio = (@segSalidaMenosUnaHora - @segEntrada) * @rand + @segEntrada;
SET @horaAleatoria = SEC_TO_TIME(@segAleatorio);

-- Formatear la hora aleatoria sin milisegundos
SET @horaAleatoriaSinMs = TIME_FORMAT(@horaAleatoria, '%H:%i:00');

-- Mostrar la hora aleatoria sin milisegundos generada
SELECT @horaAleatoriaSinMs;
--------------------



















---------PROCEDURE(BETA)----------

DELIMITER $$
CREATE PROCEDURE AsignarHora(IN id_ser INT,OUT hora_aleatoria TIME)
BEGIN
    DECLARE id_doc INT;
    DECLARE horEntrada TIME;
    DECLARE horSalida TIME;
    DECLARE segEntrada INT;
    DECLARE segSalida INT;
    DECLARE segSalidaMenosUnaHora INT;
    DECLARE rand FLOAT;
    DECLARE segAleatorio INT;
    SET id_doc = (select vs.empl from empleados empl inner join empl_vs_espec vs on empl.id_empl=vs.empl join servicios ser on ser.espec_req=vs.espec join especialidad esp on esp.id_esp=vs.espec where ser.id_ser=id_ser order by rand() limit 1);

    -- Obtener la hora de entrada y salida del empleado
    SET horEntrada=(SELECT hor.entrada FROM horarios hor INNER JOIN empleados empl on empl.horario=hor.id_horario WHERE empl.id_empl=id_doc);
        
    SET horSalida=(SELECT hor.salida FROM horarios hor INNER JOIN empleados empl on empl.horario=hor.id_horario WHERE empl.id_empl=id_doc);
    -- Convertir la hora de entrada y salida en segundos
    SET segEntrada = TIME_TO_SEC(horEntrada);
    SET segSalida = TIME_TO_SEC(horSalida);

    -- Restar una hora a la hora de salida
    SET segSalidaMenosUnaHora = segSalida - 3600;

    -- Generar un número aleatorio entre 0 y 1
    SET rand = RAND();

    -- Calcular la hora aleatoria dentro del rango
    SET segAleatorio = (segSalidaMenosUnaHora - segEntrada) * rand + segEntrada;
    SET hora_aleatoria = SEC_TO_TIME(segAleatorio);

    -- Formatear la hora aleatoria sin milisegundos
    SET hora_aleatoria = TIME_FORMAT(hora_aleatoria, '%H:%i:00');
END $$
DELIMITER ;


--LLAMAR PROCEDURE
SET @horaAleatoria = NULL;
SET @servicio=2;
CALL AsignarHora(@servicio, @horaAleatoria,@id_doc);
SELECT @horaAleatoria;

---------------------






DELIMITER $$
CREATE PROCEDURE AsignarHora(IN id_ser INT,OUT hora_aleatoria TIME,OUT id_doc INT)
BEGIN
    DECLARE horEntrada TIME;
    DECLARE horSalida TIME;
    DECLARE segEntrada INT;
    DECLARE segSalida INT;
    DECLARE segSalidaMenosUnaHora INT;
    DECLARE rand FLOAT;
    DECLARE segAleatorio INT;
    SET id_doc = (select vs.empl from empleados empl inner join empl_vs_espec vs on empl.id_empl=vs.empl join servicios ser on ser.espec_req=vs.espec join especialidad esp on esp.id_esp=vs.espec where ser.id_ser=id_ser order by rand() limit 1);

    -- Obtener la hora de entrada y salida del empleado
    SET horEntrada=(SELECT hor.entrada FROM horarios hor INNER JOIN empleados empl on empl.horario=hor.id_horario WHERE empl.id_empl=id_doc);
        
    SET horSalida=(SELECT hor.salida FROM horarios hor INNER JOIN empleados empl on empl.horario=hor.id_horario WHERE empl.id_empl=id_doc);
    -- Convertir la hora de entrada y salida en segundos
    SET segEntrada = TIME_TO_SEC(horEntrada);
    SET segSalida = TIME_TO_SEC(horSalida);

    -- Restar una hora a la hora de salida
    SET segSalidaMenosUnaHora = segSalida - 3600;

    -- Generar un número aleatorio entre 0 y 1
    SET rand = RAND();

    -- Calcular la hora aleatoria dentro del rango
    SET segAleatorio = (segSalidaMenosUnaHora - segEntrada) * rand + segEntrada;
    SET hora_aleatoria = SEC_TO_TIME(segAleatorio);

    -- Formatear la hora aleatoria sin milisegundos
    SET hora_aleatoria = TIME_FORMAT(hora_aleatoria, '%H:%i:00');
END $$
DELIMITER ;



-- PROCEDURE DE HORAS POR TANDA

DELIMITER $$
CREATE PROCEDURE AsignarHoraXTanda(IN id_ser INT,IN tanda INT,OUT hora_aleatoria TIME,OUT id_doc INT)
BEGIN
    DECLARE horEntrada TIME;
    DECLARE horSalida TIME;
    DECLARE segEntrada INT;
    DECLARE segSalida INT;
    DECLARE segSalidaMenosUnaHora INT;
    DECLARE rand FLOAT;
    DECLARE segAleatorio INT;
    DECLARE tip_hor INT;
    SET tip_hor=1;
    /*
    SI tanda=1
Busco los doctores cuyo id de horario sea 1 y busco una hora random entre la hora de entrada (8 am) y las 12 del medio dia

SI tanda=2
Busco los doctores cuyo id de horario sea 1 y busco una hora random entre la 1 pm hasta la hora de salida(6pm)

SI tanda=3
Busco los doctores cuyo id de horario sea 2 y busco una hora random entre la entrada y la salida
    */
    IF tanda=3 THEN
   SET tip_hor=2;
    END IF;

    SET id_doc = (select vs.empl from empleados empl inner join empl_vs_espec vs on empl.id_empl=vs.empl join servicios ser on ser.espec_req=vs.espec join especialidad esp on esp.id_esp=vs.espec where ser.id_ser=id_ser and empl.horario=tip_hor order by rand() limit 1);

    -- Obtener la hora de entrada y salida del empleado
    SET horEntrada=(SELECT hor.entrada FROM horarios hor INNER JOIN empleados empl on empl.horario=hor.id_horario WHERE empl.id_empl=id_doc);
        
    SET horSalida=(SELECT hor.salida FROM horarios hor INNER JOIN empleados empl on empl.horario=hor.id_horario WHERE empl.id_empl=id_doc);
    
    -- Establecer rango de horas
    IF tanda=1 THEN
    SET segEntrada = TIME_TO_SEC(horEntrada);
    SET segSalida = TIME_TO_SEC('12:30:00');
    ELSEIF tanda=2 THEN
    SET segEntrada = TIME_TO_SEC('12:30:00');
    SET segSalida = TIME_TO_SEC(horSalida);
    SET segSalidaMenosUnaHora = segSalida - 3600;
    ELSE
    SET segEntrada = TIME_TO_SEC(horEntrada);
    SET segSalida = TIME_TO_SEC(horSalida);
    SET segSalidaMenosUnaHora = segSalida - 3600;
    END IF;
    -- Generar un número aleatorio entre 0 y 1
    SET rand = RAND();

    -- Calcular la hora aleatoria dentro del rango
    SET segAleatorio = (segSalidaMenosUnaHora - segEntrada) * rand + segEntrada;
    SET hora_aleatoria = SEC_TO_TIME(segAleatorio);

    -- Formatear la hora aleatoria sin milisegundos
    SET hora_aleatoria = TIME_FORMAT(hora_aleatoria, '%H:%i:00');
END $$
DELIMITER ;
-------------------
SET @horaAleatoria = NULL;
SET @servicio=2;
SET @tanda=1;
CALL AsignarHoraXTanda(@servicio,@tanda,@horaAleatoria,@id_doc);
SELECT @horaAleatoria;
----------------------

--TRIGGER PARA ASIGNAR FIN DE CITA--

DELIMITER $$
CREATE TRIGGER asign_hora_fin
before INSERT ON evento
FOR EACH ROW
BEGIN

DECLARE duracion TIME;
SET duracion=(SELECT ts.duracion FROM tip_servicio ts Inner JOIN servicios ser ON ser.tip_ser=ts.id_tip where ser.id_ser=NEW.servicio limit 1);

   SET NEW.fin_cita=ADDTIME(NEW.hora,duracion);
   
END $$
DELIMITER ;
