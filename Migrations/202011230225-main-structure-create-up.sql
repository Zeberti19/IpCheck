CREATE SCHEMA sch_ip_check
    CREATE TABLE t_data (
        id           SERIAL PRIMARY KEY,
        datetime     TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
        url          TEXT UNIQUE,
        response_time_avg NUMERIC NOT NULL,
        response_time_min NUMERIC NOT NULL,
        response_time_max NUMERIC NOT NULL
    );
CREATE FUNCTION sch_ip_check.f_data_before() RETURNS trigger AS $f_ip_check_data_before$
BEGIN
    NEW.url := trim(NEW.url);
    RETURN NEW;
END;
$f_ip_check_data_before$ LANGUAGE plpgsql;
CREATE TRIGGER trg_data_before
BEFORE INSERT OR UPDATE ON sch_ip_check.t_data
FOR EACH ROW
EXECUTE PROCEDURE sch_ip_check.f_data_before();
