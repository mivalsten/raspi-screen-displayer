DROP TABLE IF EXISTS t_config;
CREATE TABLE t_config (
    name varchar(60) PRIMARY KEY NOT NULL
    ,value varchar(60) NOT NULL
);
INSERT INTO t_config VALUES ('stagingImageType','.png');
INSERT INTO t_config VALUES ('framerate','25');
INSERT INTO t_config VALUES ('slideDurationSeconds','5');

DROP TABLE IF EXISTS t_directories;
CREATE TABLE t_directories (
    name varchar(60) PRIMARY KEY NOT NULL
    ,path varchar(255) NOT NULL
    ,base varchar(60)
);
INSERT INTO t_directories VALUES ('rootPath',     '/srv/inz', NULL);
INSERT INTO t_directories VALUES ('incoming',     '/in',      'rootPath');
INSERT INTO t_directories VALUES ('stage',        '/staging', 'rootPath');
INSERT INTO t_directories VALUES ('stagePDF',     '/1pdf',    'stage');
INSERT INTO t_directories VALUES ('stageImage',   '/2image',  'stage');
INSERT INTO t_directories VALUES ('stageVideo',   '/3video',  'stage');
INSERT INTO t_directories VALUES ('output',       '/out',     'rootPath');
INSERT INTO t_directories VALUES ('www',          '/www',     'rootPath');
INSERT INTO t_directories VALUES ('wwwUploads',   '/uploads', 'www');
INSERT INTO t_directories VALUES ('db',   '/db', 'rootPath');

DROP VIEW IF EXISTS v_directories;
CREATE VIEW v_directories (name, path)
AS
WITH dir_cte (name, path, base, lvl)
AS
(
    SELECT *, 0 as lvl from t_directories
    UNION ALL
    SELECT t1.name
          ,dir_cte.path || t1.path as path
          ,t1.base as base
          ,dir_cte.lvl+1 as lvl
    FROM dir_cte
    LEFT JOIN t_directories as t1
    ON dir_cte.name = t1.base
    where t1.base IS NOT NULL
)
SELECT name, path FROM (
    SELECT name, path, max(lvl)
    FROM   dir_cte
    GROUP BY name) a
ORDER BY name;

DROP TABLE IF EXISTS schedules;
create table schedules (
	name varchar(30)
	,start int
	,end int
);
	
insert into schedules values ('schedule1', 1548097200, 1548108000);
insert into schedules values ('schedule2', 0, 0);
insert into schedules values ('schedule3', 1548338400, 1548439200);
insert into schedules values ('schedule4', 0, 0);
insert into schedules values ('schedule5', 0, 0);
insert into schedules values ('schedule6', 0, 0);
insert into schedules values ('schedule7', 0, 0);

DROP VIEW IF EXISTS v_schedules;
create view v_schedules AS
select name
from schedules
where start < strftime('%s','now') and end > strftime('%s','now')--and datetime(end) > datetime('now');
ORDER BY name
LIMIT 1;

DROP TABLE IF EXISTS t_users;
CREATE TABLE t_users (
    username varchar(32)
    ,passwd char(60)
	,isAdmin boolean
);

--create dafault user admin with password admin so that first user can log in to the system
INSERT INTO t_users VALUES ('admin', '$2y$10$WCwyeWTSdx.Q6V0yqMAXdeef38PG6jfg6ftThNMVY2/kYTjcUQig2', 1);
