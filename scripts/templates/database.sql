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
-- Statement using the CTE
SELECT name, path FROM (
    SELECT name, path, max(lvl)
    FROM   dir_cte
    GROUP BY name) a
ORDER BY name;

DROP TABLE IF EXISTS t_users;
CREATE TABLE t_users (
    username varchar(32)
    ,passwd char(60)
);

INSERT INTO t_users VALUES ('admin', '$2y$10$WCwyeWTSdx.Q6V0yqMAXdeef38PG6jfg6ftThNMVY2/kYTjcUQig2');