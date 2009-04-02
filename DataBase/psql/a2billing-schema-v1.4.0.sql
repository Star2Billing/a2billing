--
-- A2Billing database
--

--  Default values - Please change them to whatever you want 
 
-- 	Database name is: mya2billing
-- 	Database user is: a2billinguser
-- 	User password is: a2billing



-- 1. make sure that the Database user is GRANT to access the database in pg_hba.conf!

--     a line like this will do it
    
--     # TYPE  DATABASE    USER        IP-ADDRESS        IP-MASK           METHOD
--     # Database asterisk/a2billing login with password for a non real user
--     #
--     local   mya2billing all						md5
    
--     DON'T FORGET TO RESTART Postgresql SERVER IF YOU MADE ANY MODIFICATION ON THIS FILE
    
-- 2. open a terminal and enter the below commands. We assume our superuser to be postgres.
--    Please adapt to your setup.

--     su - postgres
--     psql -f a2billing-schema-v1.4.0.sql template1

--     NOTE: the errors you will see about missing tables are OK, it's the default behaviour of pgsql.
    
--     When prompted for the password, please enter the one you choose. In our case, it's 'a2billing'. 



\set ON_ERROR_STOP ON;

SET default_with_oids = true;

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: postgres
--

CREATE PROCEDURAL LANGUAGE plpgsql;


ALTER PROCEDURAL LANGUAGE plpgsql OWNER TO postgres;

SET search_path = public, pg_catalog;

--
-- Name: cc_card_serial_set(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION cc_card_serial_set() RETURNS trigger
    AS $$
  BEGIN
    UPDATE cc_card_seria SET value=value+1 WHERE id=NEW.id_seria;
    SELECT value INTO NEW.serial FROM cc_card_seria WHERE id=NEW.id_seria;
    RETURN NEW;
  END
$$
    LANGUAGE plpgsql;


ALTER FUNCTION public.cc_card_serial_set() OWNER TO postgres;

--
-- Name: cc_card_serial_update(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION cc_card_serial_update() RETURNS trigger
    AS $$
  BEGIN
    IF NEW.id_seria IS NOT NULL AND NEW.id_seria = OLD.id_seria THEN
      RETURN NEW;
    END IF;
    UPDATE cc_card_seria SET value=value+1 WHERE id=NEW.id_seria;
    SELECT value INTO NEW.serial FROM cc_card_seria WHERE id=NEW.id_seria;
    RETURN NEW;
  END
$$
    LANGUAGE plpgsql;


ALTER FUNCTION public.cc_card_serial_update() OWNER TO postgres;

--
-- Name: cc_ratecard_validate_regex(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION cc_ratecard_validate_regex() RETURNS trigger
    AS $_$
  BEGIN
    IF SUBSTRING(new.dialprefix,1,1) != '_' THEN
      RETURN new;
    END IF;
    PERFORM '0' ~* REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE('^' || new.dialprefix || '$', 'X', '[0-9]', 'g'), 'Z', '[1-9]', 'g'), 'N', '[2-9]', 'g'), E'\\.', '+', 'g'), '_', '', 'g');
    RETURN new;
  END
$_$
    LANGUAGE plpgsql;


ALTER FUNCTION public.cc_ratecard_validate_regex() OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = true;

--
-- Name: cc_agent; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_agent (
    id bigint NOT NULL,
    datecreation timestamp without time zone DEFAULT now(),
    active boolean DEFAULT false NOT NULL,
    login character varying(20) NOT NULL,
    passwd character varying(40),
    location text,
    language character varying(5) DEFAULT 'en'::text,
    id_tariffgroup integer,
    options integer DEFAULT 0 NOT NULL,
    credit numeric(15,5) DEFAULT 0 NOT NULL,
    currency character varying(3) DEFAULT 'USD'::character varying NOT NULL,
    locale character varying(10) DEFAULT 'C'::character varying,
    commission numeric(10,4) DEFAULT 0 NOT NULL,
    vat numeric(10,4) DEFAULT 0 NOT NULL,
    banner text,
    perms integer,
    lastname character varying(50),
    firstname character varying(50),
    address character varying(100),
    city character varying(40),
    state character varying(40),
    country character varying(40),
    zipcode character varying(20),
    phone character varying(20),
    email character varying(70),
    fax character varying(20),
    company character varying(50),
    secret character varying(20) NOT NULL
);


ALTER TABLE public.cc_agent OWNER TO postgres;

--
-- Name: cc_agent_commission; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_agent_commission (
    id bigint NOT NULL,
    id_payment bigint,
    id_card bigint NOT NULL,
    date timestamp without time zone DEFAULT now() NOT NULL,
    amount numeric(15,5) NOT NULL,
    paid_status smallint DEFAULT 0::smallint NOT NULL,
    description text,
    id_agent integer NOT NULL
);


ALTER TABLE public.cc_agent_commission OWNER TO postgres;

--
-- Name: cc_agent_commission_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_agent_commission_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_agent_commission_id_seq OWNER TO postgres;

--
-- Name: cc_agent_commission_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_agent_commission_id_seq OWNED BY cc_agent_commission.id;


--
-- Name: cc_agent_commission_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_agent_commission_id_seq', 1, false);


--
-- Name: cc_agent_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_agent_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_agent_id_seq OWNER TO postgres;

--
-- Name: cc_agent_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_agent_id_seq OWNED BY cc_agent.id;


--
-- Name: cc_agent_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_agent_id_seq', 1, false);


--
-- Name: cc_agent_tariffgroup; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_agent_tariffgroup (
    id_agent bigint NOT NULL,
    id_tariffgroup integer NOT NULL
);


ALTER TABLE public.cc_agent_tariffgroup OWNER TO postgres;

--
-- Name: cc_alarm; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_alarm (
    id bigint NOT NULL,
    name text NOT NULL,
    periode integer DEFAULT 1 NOT NULL,
    type integer DEFAULT 1 NOT NULL,
    maxvalue numeric NOT NULL,
    minvalue numeric DEFAULT (-1) NOT NULL,
    id_trunk integer,
    status integer DEFAULT 0 NOT NULL,
    numberofrun integer DEFAULT 0 NOT NULL,
    numberofalarm integer DEFAULT 0 NOT NULL,
    datecreate timestamp without time zone DEFAULT now(),
    datelastrun timestamp without time zone DEFAULT now(),
    emailreport text
);


ALTER TABLE public.cc_alarm OWNER TO postgres;

--
-- Name: cc_alarm_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_alarm_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_alarm_id_seq OWNER TO postgres;

--
-- Name: cc_alarm_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_alarm_id_seq OWNED BY cc_alarm.id;


--
-- Name: cc_alarm_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_alarm_id_seq', 1, false);


--
-- Name: cc_alarm_report; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_alarm_report (
    id bigint NOT NULL,
    cc_alarm_id bigint NOT NULL,
    calculatedvalue numeric NOT NULL,
    daterun timestamp without time zone DEFAULT now()
);


ALTER TABLE public.cc_alarm_report OWNER TO postgres;

--
-- Name: cc_alarm_report_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_alarm_report_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_alarm_report_id_seq OWNER TO postgres;

--
-- Name: cc_alarm_report_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_alarm_report_id_seq OWNED BY cc_alarm_report.id;


--
-- Name: cc_alarm_report_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_alarm_report_id_seq', 1, false);


--
-- Name: cc_autorefill_report; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_autorefill_report (
    id bigint NOT NULL,
    daterun timestamp(0) without time zone DEFAULT now(),
    totalcardperform integer,
    totalcredit double precision
);


ALTER TABLE public.cc_autorefill_report OWNER TO postgres;

--
-- Name: cc_autorefill_report_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_autorefill_report_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_autorefill_report_id_seq OWNER TO postgres;

--
-- Name: cc_autorefill_report_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_autorefill_report_id_seq OWNED BY cc_autorefill_report.id;


--
-- Name: cc_autorefill_report_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_autorefill_report_id_seq', 1, false);


--
-- Name: cc_backup; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_backup (
    id bigint NOT NULL,
    name character varying(255) DEFAULT ''::character varying NOT NULL,
    path character varying(255) DEFAULT ''::character varying NOT NULL,
    creationdate timestamp without time zone DEFAULT now()
);


ALTER TABLE public.cc_backup OWNER TO postgres;

--
-- Name: cc_backup_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_backup_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_backup_id_seq OWNER TO postgres;

--
-- Name: cc_backup_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_backup_id_seq OWNED BY cc_backup.id;


--
-- Name: cc_backup_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_backup_id_seq', 1, false);


--
-- Name: cc_billing_customer; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_billing_customer (
    id bigint NOT NULL,
    id_card bigint NOT NULL,
    date timestamp without time zone DEFAULT now() NOT NULL,
    id_invoice bigint NOT NULL
);


ALTER TABLE public.cc_billing_customer OWNER TO postgres;

--
-- Name: cc_billing_customer_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_billing_customer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_billing_customer_id_seq OWNER TO postgres;

--
-- Name: cc_billing_customer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_billing_customer_id_seq OWNED BY cc_billing_customer.id;


--
-- Name: cc_billing_customer_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_billing_customer_id_seq', 1, false);


--
-- Name: cc_call; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_call (
    id bigint NOT NULL,
    sessionid text NOT NULL,
    uniqueid text NOT NULL,
    nasipaddress text,
    starttime timestamp without time zone,
    stoptime timestamp without time zone,
    sessiontime integer,
    calledstation text,
    sessionbill double precision,
    id_tariffgroup integer,
    id_tariffplan integer,
    id_ratecard integer,
    id_trunk integer,
    sipiax integer DEFAULT 0,
    src text,
    id_did integer,
    buycost numeric(15,5) DEFAULT 0,
    id_card_package_offer integer DEFAULT 0,
    real_sessiontime integer,
    card_id bigint NOT NULL,
    dnid character varying(40),
    terminatecauseid smallint DEFAULT 1,
    destination integer DEFAULT 0
);


ALTER TABLE public.cc_call OWNER TO postgres;

--
-- Name: cc_call_archive; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_call_archive (
    id bigint NOT NULL,
    sessionid character varying(40) NOT NULL,
    uniqueid character varying(30) NOT NULL,
    username character varying(40) NOT NULL,
    nasipaddress character varying(30),
    starttime timestamp without time zone,
    stoptime timestamp without time zone,
    sessiontime integer,
    calledstation character varying(30),
    startdelay integer,
    stopdelay integer,
    terminatecause character varying(20),
    usertariff character varying(20),
    calledprovider character varying(20),
    calledcountry character varying(30),
    calledsub character varying(20),
    calledrate double precision,
    sessionbill double precision,
    destination character varying(40),
    id_tariffgroup integer,
    id_tariffplan integer,
    id_ratecard integer,
    id_trunk integer,
    sipiax integer DEFAULT 0,
    src character varying(30),
    id_did integer,
    buyrate numeric(15,5) DEFAULT 0,
    buycost numeric(15,5) DEFAULT 0,
    id_card_package_offer integer DEFAULT 0,
    real_sessiontime integer
);


ALTER TABLE public.cc_call_archive OWNER TO postgres;

--
-- Name: cc_call_archive_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_call_archive_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_call_archive_id_seq OWNER TO postgres;

--
-- Name: cc_call_archive_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_call_archive_id_seq OWNED BY cc_call_archive.id;


--
-- Name: cc_call_archive_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_call_archive_id_seq', 1, false);


--
-- Name: cc_call_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_call_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_call_id_seq OWNER TO postgres;

--
-- Name: cc_call_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_call_id_seq OWNED BY cc_call.id;


--
-- Name: cc_call_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_call_id_seq', 1, false);


--
-- Name: cc_callback_spool; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_callback_spool (
    id bigint NOT NULL,
    uniqueid text,
    entry_time timestamp without time zone DEFAULT now(),
    status text,
    server_ip text,
    num_attempt integer DEFAULT 0 NOT NULL,
    last_attempt_time timestamp without time zone,
    manager_result text,
    agi_result text,
    callback_time timestamp without time zone,
    channel text,
    exten text,
    context text,
    priority text,
    application text,
    data text,
    timeout text,
    callerid text,
    variable character varying(300),
    account text,
    async text,
    actionid text,
    id_server integer,
    id_server_group integer
);


ALTER TABLE public.cc_callback_spool OWNER TO postgres;

--
-- Name: cc_callback_spool_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_callback_spool_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_callback_spool_id_seq OWNER TO postgres;

--
-- Name: cc_callback_spool_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_callback_spool_id_seq OWNED BY cc_callback_spool.id;


--
-- Name: cc_callback_spool_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_callback_spool_id_seq', 1, false);


--
-- Name: cc_callerid; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_callerid (
    id bigint NOT NULL,
    cid text NOT NULL,
    id_cc_card bigint NOT NULL,
    activated boolean DEFAULT true NOT NULL
);


ALTER TABLE public.cc_callerid OWNER TO postgres;

--
-- Name: cc_callerid_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_callerid_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_callerid_id_seq OWNER TO postgres;

--
-- Name: cc_callerid_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_callerid_id_seq OWNED BY cc_callerid.id;


--
-- Name: cc_callerid_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_callerid_id_seq', 1, false);


--
-- Name: cc_campaign; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_campaign (
    id bigint NOT NULL,
    name character varying(50) NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
    startingdate timestamp without time zone DEFAULT now(),
    expirationdate timestamp without time zone,
    description text,
    id_card bigint DEFAULT 0::bigint NOT NULL,
    secondusedreal integer DEFAULT 0,
    nb_callmade integer DEFAULT 0,
    status integer DEFAULT 1 NOT NULL,
    frequency integer DEFAULT 20 NOT NULL,
    forward_number character varying(50),
    daily_start_time time without time zone DEFAULT '10:00:00'::time without time zone NOT NULL,
    daily_stop_time time without time zone DEFAULT '18:00:00'::time without time zone NOT NULL,
    monday smallint DEFAULT 1::smallint NOT NULL,
    tuesday smallint DEFAULT 1::smallint NOT NULL,
    wednesday smallint DEFAULT 1::smallint NOT NULL,
    thursday smallint DEFAULT 1::smallint NOT NULL,
    friday smallint DEFAULT 1::smallint NOT NULL,
    saturday smallint DEFAULT 0::smallint NOT NULL,
    sunday smallint DEFAULT 0::smallint NOT NULL,
    id_cid_group integer NOT NULL,
    id_campaign_config integer NOT NULL
);


ALTER TABLE public.cc_campaign OWNER TO postgres;

--
-- Name: cc_campaign_config; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_campaign_config (
    id integer NOT NULL,
    name character varying(40) NOT NULL,
    flatrate numeric(15,5) DEFAULT 0 NOT NULL,
    context character varying(40) NOT NULL,
    description text
);


ALTER TABLE public.cc_campaign_config OWNER TO postgres;

--
-- Name: cc_campaign_config_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_campaign_config_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_campaign_config_id_seq OWNER TO postgres;

--
-- Name: cc_campaign_config_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_campaign_config_id_seq OWNED BY cc_campaign_config.id;


--
-- Name: cc_campaign_config_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_campaign_config_id_seq', 1, false);


--
-- Name: cc_campaign_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_campaign_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_campaign_id_seq OWNER TO postgres;

--
-- Name: cc_campaign_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_campaign_id_seq OWNED BY cc_campaign.id;


--
-- Name: cc_campaign_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_campaign_id_seq', 1, false);


--
-- Name: cc_campaign_phonebook; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_campaign_phonebook (
    id_campaign integer NOT NULL,
    id_phonebook integer NOT NULL
);


ALTER TABLE public.cc_campaign_phonebook OWNER TO postgres;

--
-- Name: cc_campaign_phonestatus; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_campaign_phonestatus (
    id_phonenumber bigint NOT NULL,
    id_campaign integer NOT NULL,
    id_callback character varying(40) NOT NULL,
    status integer DEFAULT 0 NOT NULL,
    lastuse timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.cc_campaign_phonestatus OWNER TO postgres;

--
-- Name: cc_campaignconf_cardgroup; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_campaignconf_cardgroup (
    id_campaign_config integer NOT NULL,
    id_card_group integer NOT NULL
);


ALTER TABLE public.cc_campaignconf_cardgroup OWNER TO postgres;

--
-- Name: cc_card; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_card (
    id bigint NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
    firstusedate timestamp without time zone,
    expirationdate timestamp without time zone,
    enableexpire integer DEFAULT 0,
    expiredays integer DEFAULT 0,
    username text NOT NULL,
    useralias text NOT NULL,
    uipass text,
    credit numeric(12,4) NOT NULL,
    tariff integer DEFAULT 0,
    id_didgroup integer DEFAULT 0,
    activated boolean DEFAULT false NOT NULL,
    lastname text,
    firstname text,
    address text,
    city text,
    state text,
    country text,
    zipcode text,
    phone text,
    email text,
    fax text,
    inuse integer DEFAULT 0,
    simultaccess integer DEFAULT 0,
    currency character varying(3) DEFAULT 'USD'::character varying,
    lastuse date DEFAULT now(),
    nbused integer DEFAULT 0,
    typepaid integer DEFAULT 0,
    creditlimit integer DEFAULT 0,
    voipcall integer DEFAULT 0,
    sip_buddy integer DEFAULT 0,
    iax_buddy integer DEFAULT 0,
    language text DEFAULT 'en'::text,
    redial text,
    runservice integer DEFAULT 0,
    nbservice integer DEFAULT 0,
    id_campaign integer DEFAULT 0,
    num_trials_done integer DEFAULT 0,
    vat numeric(6,3) DEFAULT 0,
    servicelastrun timestamp without time zone,
    initialbalance numeric(12,4) DEFAULT 0 NOT NULL,
    invoiceday integer DEFAULT 1,
    autorefill integer DEFAULT 0,
    loginkey text,
    mac_addr character varying(17) DEFAULT '00-00-00-00-00-00'::character varying NOT NULL,
    id_timezone integer DEFAULT 0,
    status integer DEFAULT 1 NOT NULL,
    tag character varying(50),
    voicemail_permitted integer DEFAULT 0 NOT NULL,
    voicemail_activated integer DEFAULT 0 NOT NULL,
    last_notification timestamp without time zone,
    email_notification character varying(70),
    notify_email smallint DEFAULT 0 NOT NULL,
    credit_notification integer DEFAULT (-1) NOT NULL,
    id_group integer DEFAULT 1 NOT NULL,
    company_name character varying(50),
    company_website character varying(60),
    vat_rn character varying(40) DEFAULT NULL::character varying,
    traffic bigint DEFAULT 0,
    traffic_target text,
    discount numeric(5,2) DEFAULT 0::numeric NOT NULL,
    restriction smallint DEFAULT 0::smallint NOT NULL,
    id_seria integer,
    serial bigint
);


ALTER TABLE public.cc_card OWNER TO postgres;

--
-- Name: cc_card_archive; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_card_archive (
    id bigint NOT NULL,
    creationdate timestamp without time zone DEFAULT now() NOT NULL,
    firstusedate timestamp without time zone,
    expirationdate timestamp without time zone,
    enableexpire integer DEFAULT 0,
    expiredays integer DEFAULT 0,
    username character varying(50) NOT NULL,
    useralias character varying(50) NOT NULL,
    uipass character varying(50),
    credit numeric(15,5) NOT NULL,
    tariff integer DEFAULT 0,
    id_didgroup integer DEFAULT 0,
    activated boolean DEFAULT false NOT NULL,
    status integer DEFAULT 1,
    lastname character varying(50),
    firstname character varying(50),
    address character varying(100),
    city character varying(40),
    state character varying(40),
    country character varying(40),
    zipcode character varying(20),
    phone character varying(20),
    email character varying(70),
    fax character varying(20),
    inuse integer DEFAULT 0,
    simultaccess integer DEFAULT 0,
    currency character varying(3) DEFAULT 'USD'::character varying,
    lastuse timestamp without time zone,
    nbused integer DEFAULT 0,
    typepaid integer DEFAULT 0,
    creditlimit integer DEFAULT 0,
    voipcall integer DEFAULT 0,
    sip_buddy integer DEFAULT 0,
    iax_buddy integer DEFAULT 0,
    language character varying(5) DEFAULT 'en'::text,
    redial character varying(50),
    runservice integer DEFAULT 0,
    nbservice integer DEFAULT 0,
    id_campaign integer DEFAULT 0,
    num_trials_done bigint DEFAULT 0,
    vat numeric(6,3) DEFAULT 0,
    servicelastrun timestamp without time zone,
    initialbalance numeric(15,5) DEFAULT 0 NOT NULL,
    invoiceday integer DEFAULT 1,
    autorefill integer DEFAULT 0,
    loginkey character varying(40),
    activatedbyuser boolean DEFAULT true NOT NULL,
    id_timezone integer DEFAULT 0,
    tag character varying(50),
    voicemail_permitted integer DEFAULT 0 NOT NULL,
    voicemail_activated smallint DEFAULT 0::smallint NOT NULL,
    last_notification timestamp without time zone,
    email_notification character varying(70),
    notify_email smallint DEFAULT 0::smallint NOT NULL,
    credit_notification integer DEFAULT (-1) NOT NULL,
    id_group integer DEFAULT 1 NOT NULL,
    company_name character varying(50) DEFAULT NULL::character varying,
    company_website character varying(60) DEFAULT NULL::character varying,
    vat_rn character varying(40) DEFAULT NULL::character varying,
    traffic bigint,
    traffic_target text,
    discount numeric(5,2) DEFAULT 0.00 NOT NULL,
    restriction smallint DEFAULT 0::smallint NOT NULL,
    mac_addr character(17) DEFAULT '00-00-00-00-00-00'::bpchar NOT NULL
);


ALTER TABLE public.cc_card_archive OWNER TO postgres;

--
-- Name: cc_card_archive_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_card_archive_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_card_archive_id_seq OWNER TO postgres;

--
-- Name: cc_card_archive_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_card_archive_id_seq OWNED BY cc_card_archive.id;


--
-- Name: cc_card_archive_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_card_archive_id_seq', 1, false);


--
-- Name: cc_card_group; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_card_group (
    id integer NOT NULL,
    name character varying(30) NOT NULL,
    description text,
    users_perms integer DEFAULT 0 NOT NULL,
    id_agent integer
);


ALTER TABLE public.cc_card_group OWNER TO postgres;

--
-- Name: cc_card_group_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_card_group_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_card_group_id_seq OWNER TO postgres;

--
-- Name: cc_card_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_card_group_id_seq OWNED BY cc_card_group.id;


--
-- Name: cc_card_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_card_group_id_seq', 1, true);


--
-- Name: cc_card_history; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_card_history (
    id bigint NOT NULL,
    id_cc_card bigint,
    datecreated timestamp without time zone DEFAULT now(),
    description text
);


ALTER TABLE public.cc_card_history OWNER TO postgres;

--
-- Name: cc_card_history_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_card_history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_card_history_id_seq OWNER TO postgres;

--
-- Name: cc_card_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_card_history_id_seq OWNED BY cc_card_history.id;


--
-- Name: cc_card_history_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_card_history_id_seq', 1, false);


--
-- Name: cc_card_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_card_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_card_id_seq OWNER TO postgres;

--
-- Name: cc_card_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_card_id_seq OWNED BY cc_card.id;


--
-- Name: cc_card_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_card_id_seq', 1, false);


--
-- Name: cc_card_package_offer; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_card_package_offer (
    id bigint NOT NULL,
    id_cc_card bigint NOT NULL,
    id_cc_package_offer bigint NOT NULL,
    date_consumption timestamp without time zone DEFAULT now(),
    used_secondes bigint NOT NULL
);


ALTER TABLE public.cc_card_package_offer OWNER TO postgres;

--
-- Name: cc_card_package_offer_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_card_package_offer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_card_package_offer_id_seq OWNER TO postgres;

--
-- Name: cc_card_package_offer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_card_package_offer_id_seq OWNED BY cc_card_package_offer.id;


--
-- Name: cc_card_package_offer_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_card_package_offer_id_seq', 1, false);


--
-- Name: cc_card_seria; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_card_seria (
    id integer NOT NULL,
    name character(30) NOT NULL,
    description text,
    value bigint DEFAULT 0 NOT NULL
);


ALTER TABLE public.cc_card_seria OWNER TO postgres;

--
-- Name: cc_card_seria_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_card_seria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_card_seria_id_seq OWNER TO postgres;

--
-- Name: cc_card_seria_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_card_seria_id_seq OWNED BY cc_card_seria.id;


--
-- Name: cc_card_seria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_card_seria_id_seq', 1, false);


--
-- Name: cc_card_subscription; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_card_subscription (
    id bigint NOT NULL,
    id_cc_card bigint DEFAULT 0 NOT NULL,
    id_subscription_fee integer DEFAULT 0 NOT NULL,
    startdate timestamp without time zone DEFAULT now(),
    stopdate timestamp without time zone,
    product_id character varying(100) NOT NULL,
    product_name character varying(100) NOT NULL
);


ALTER TABLE public.cc_card_subscription OWNER TO postgres;

--
-- Name: cc_card_subscription_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_card_subscription_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_card_subscription_id_seq OWNER TO postgres;

--
-- Name: cc_card_subscription_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_card_subscription_id_seq OWNED BY cc_card_subscription.id;


--
-- Name: cc_card_subscription_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_card_subscription_id_seq', 1, false);


--
-- Name: cc_cardgroup_service; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_cardgroup_service (
    id_card_group integer NOT NULL,
    id_service integer NOT NULL
);


ALTER TABLE public.cc_cardgroup_service OWNER TO postgres;

--
-- Name: cc_charge; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_charge (
    id bigint NOT NULL,
    id_cc_card bigint NOT NULL,
    iduser integer DEFAULT 0 NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
    amount numeric(12,4) NOT NULL,
    currency character varying(3) DEFAULT 'USD'::character varying,
    chargetype integer DEFAULT 0,
    description text,
    id_cc_did bigint DEFAULT 0,
    id_cc_card_subscription bigint,
    cover_from date,
    cover_to date,
    charged_status smallint DEFAULT 0::smallint NOT NULL,
    invoiced_status smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.cc_charge OWNER TO postgres;

--
-- Name: cc_charge_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_charge_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_charge_id_seq OWNER TO postgres;

--
-- Name: cc_charge_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_charge_id_seq OWNED BY cc_charge.id;


--
-- Name: cc_charge_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_charge_id_seq', 1, false);


--
-- Name: cc_config; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_config (
    id integer NOT NULL,
    config_title character varying(100) NOT NULL,
    config_key character varying(100) NOT NULL,
    config_value character varying(300) NOT NULL,
    config_description text NOT NULL,
    config_valuetype integer DEFAULT 0 NOT NULL,
    config_listvalues text,
    config_group_title character varying(64) NOT NULL
);


ALTER TABLE public.cc_config OWNER TO postgres;

--
-- Name: cc_config_group; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_config_group (
    id integer NOT NULL,
    group_title character varying(64) NOT NULL,
    group_description character varying(255) NOT NULL
);


ALTER TABLE public.cc_config_group OWNER TO postgres;

--
-- Name: cc_config_group_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_config_group_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_config_group_id_seq OWNER TO postgres;

--
-- Name: cc_config_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_config_group_id_seq OWNED BY cc_config_group.id;


--
-- Name: cc_config_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_config_group_id_seq', 13, true);


--
-- Name: cc_config_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_config_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_config_id_seq OWNER TO postgres;

--
-- Name: cc_config_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_config_id_seq OWNED BY cc_config.id;


--
-- Name: cc_config_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_config_id_seq', 250, true);


--
-- Name: cc_configuration; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_configuration (
    configuration_id bigint NOT NULL,
    configuration_title character varying(64) NOT NULL,
    configuration_key character varying(64) NOT NULL,
    configuration_value character varying(255) NOT NULL,
    configuration_description character varying(255) NOT NULL,
    configuration_type integer DEFAULT 0 NOT NULL,
    use_function character varying(255),
    set_function character varying(255)
);


ALTER TABLE public.cc_configuration OWNER TO postgres;

--
-- Name: cc_configuration_configuration_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_configuration_configuration_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_configuration_configuration_id_seq OWNER TO postgres;

--
-- Name: cc_configuration_configuration_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_configuration_configuration_id_seq OWNED BY cc_configuration.configuration_id;


--
-- Name: cc_configuration_configuration_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_configuration_configuration_id_seq', 25, true);


--
-- Name: cc_country; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_country (
    id integer NOT NULL,
    countrycode text NOT NULL,
    countryprefix text DEFAULT '0'::text NOT NULL,
    countryname text NOT NULL
);


ALTER TABLE public.cc_country OWNER TO postgres;

--
-- Name: cc_country_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_country_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_country_id_seq OWNER TO postgres;

--
-- Name: cc_country_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_country_id_seq OWNED BY cc_country.id;


--
-- Name: cc_country_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_country_id_seq', 1, false);


--
-- Name: cc_currencies; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_currencies (
    id integer NOT NULL,
    currency character(3) DEFAULT ''::bpchar NOT NULL,
    name character varying(30) DEFAULT ''::character varying NOT NULL,
    value numeric(12,5) DEFAULT 0.00000 NOT NULL,
    lastupdate timestamp without time zone DEFAULT now(),
    basecurrency character(3) DEFAULT 'USD'::bpchar NOT NULL
);


ALTER TABLE public.cc_currencies OWNER TO postgres;

--
-- Name: cc_currencies_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_currencies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_currencies_id_seq OWNER TO postgres;

--
-- Name: cc_currencies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_currencies_id_seq OWNED BY cc_currencies.id;


--
-- Name: cc_currencies_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_currencies_id_seq', 1, false);


--
-- Name: cc_did; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_did (
    id bigint NOT NULL,
    id_cc_didgroup bigint NOT NULL,
    id_cc_country integer NOT NULL,
    activated integer DEFAULT 1 NOT NULL,
    reserved integer DEFAULT 0,
    iduser bigint DEFAULT 0 NOT NULL,
    did text NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
    startingdate timestamp without time zone DEFAULT now(),
    expirationdate timestamp without time zone,
    description text,
    secondusedreal integer DEFAULT 0,
    billingtype integer DEFAULT 0,
    fixrate numeric(12,4) NOT NULL
);


ALTER TABLE public.cc_did OWNER TO postgres;

--
-- Name: cc_did_destination; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_did_destination (
    id bigint NOT NULL,
    destination text NOT NULL,
    priority integer DEFAULT 0 NOT NULL,
    id_cc_card bigint NOT NULL,
    id_cc_did bigint NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
    activated integer DEFAULT 1 NOT NULL,
    secondusedreal integer DEFAULT 0,
    voip_call integer DEFAULT 0
);


ALTER TABLE public.cc_did_destination OWNER TO postgres;

--
-- Name: cc_did_destination_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_did_destination_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_did_destination_id_seq OWNER TO postgres;

--
-- Name: cc_did_destination_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_did_destination_id_seq OWNED BY cc_did_destination.id;


--
-- Name: cc_did_destination_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_did_destination_id_seq', 1, false);


--
-- Name: cc_did_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_did_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_did_id_seq OWNER TO postgres;

--
-- Name: cc_did_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_did_id_seq OWNED BY cc_did.id;


--
-- Name: cc_did_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_did_id_seq', 1, false);


--
-- Name: cc_did_use; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_did_use (
    id bigint NOT NULL,
    id_cc_card bigint,
    id_did bigint NOT NULL,
    reservationdate timestamp without time zone DEFAULT now() NOT NULL,
    releasedate timestamp without time zone,
    activated integer DEFAULT 0,
    month_payed integer DEFAULT 0,
    reminded smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.cc_did_use OWNER TO postgres;

--
-- Name: cc_did_use_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_did_use_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_did_use_id_seq OWNER TO postgres;

--
-- Name: cc_did_use_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_did_use_id_seq OWNED BY cc_did_use.id;


--
-- Name: cc_did_use_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_did_use_id_seq', 1, false);


--
-- Name: cc_didgroup; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_didgroup (
    id bigint NOT NULL,
    iduser integer DEFAULT 0 NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
    didgroupname text NOT NULL
);


ALTER TABLE public.cc_didgroup OWNER TO postgres;

--
-- Name: cc_didgroup_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_didgroup_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_didgroup_id_seq OWNER TO postgres;

--
-- Name: cc_didgroup_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_didgroup_id_seq OWNED BY cc_didgroup.id;


--
-- Name: cc_didgroup_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_didgroup_id_seq', 1, false);


--
-- Name: cc_ecommerce_product; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_ecommerce_product (
    id bigint NOT NULL,
    product_name text NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
    description text,
    expirationdate timestamp without time zone,
    enableexpire integer DEFAULT 0,
    expiredays integer DEFAULT 0,
    credit numeric(12,4) NOT NULL,
    tariff integer DEFAULT 0,
    id_didgroup integer DEFAULT 0,
    mailtype character varying(50) DEFAULT ''::character varying NOT NULL,
    activated boolean DEFAULT false NOT NULL,
    simultaccess integer DEFAULT 0,
    currency character varying(3) DEFAULT 'USD'::character varying,
    typepaid integer DEFAULT 0,
    creditlimit integer DEFAULT 0,
    language text DEFAULT 'en'::text,
    runservice integer DEFAULT 0,
    sip_friend integer DEFAULT 0,
    iax_friend integer DEFAULT 0
);


ALTER TABLE public.cc_ecommerce_product OWNER TO postgres;

--
-- Name: cc_ecommerce_product_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_ecommerce_product_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_ecommerce_product_id_seq OWNER TO postgres;

--
-- Name: cc_ecommerce_product_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_ecommerce_product_id_seq OWNED BY cc_ecommerce_product.id;


--
-- Name: cc_ecommerce_product_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_ecommerce_product_id_seq', 1, false);


--
-- Name: cc_epayment_log; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_epayment_log (
    id bigint NOT NULL,
    cardid bigint DEFAULT 0::bigint NOT NULL,
    amount numeric(15,5) DEFAULT 0 NOT NULL,
    vat double precision DEFAULT 0 NOT NULL,
    paymentmethod character varying(255) NOT NULL,
    cc_owner character varying(255) NOT NULL,
    cc_number character varying(255) NOT NULL,
    cc_expires character varying(255) NOT NULL,
    creationdate timestamp(0) without time zone DEFAULT now(),
    status integer DEFAULT 0 NOT NULL,
    cvv character varying(4),
    credit_card_type character varying(20),
    currency character varying(4),
    transaction_detail text
);


ALTER TABLE public.cc_epayment_log OWNER TO postgres;

--
-- Name: cc_epayment_log_agent; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_epayment_log_agent (
    id bigint NOT NULL,
    agent_id bigint DEFAULT 0::bigint NOT NULL,
    amount numeric(15,5) DEFAULT 0::numeric NOT NULL,
    vat double precision DEFAULT 0::double precision NOT NULL,
    paymentmethod character(50) NOT NULL,
    cc_owner character varying(64) DEFAULT NULL::character varying,
    cc_number character varying(32) DEFAULT NULL::character varying,
    cc_expires character varying(7) DEFAULT NULL::character varying,
    creationdate timestamp without time zone DEFAULT now() NOT NULL,
    status integer DEFAULT 0 NOT NULL,
    cvv character varying(4) DEFAULT NULL::character varying,
    credit_card_type character varying(20) DEFAULT NULL::character varying,
    currency character varying(4) DEFAULT NULL::character varying,
    transaction_detail text
);


ALTER TABLE public.cc_epayment_log_agent OWNER TO postgres;

--
-- Name: cc_epayment_log_agent_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_epayment_log_agent_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_epayment_log_agent_id_seq OWNER TO postgres;

--
-- Name: cc_epayment_log_agent_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_epayment_log_agent_id_seq OWNED BY cc_epayment_log_agent.id;


--
-- Name: cc_epayment_log_agent_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_epayment_log_agent_id_seq', 1, false);


--
-- Name: cc_epayment_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_epayment_log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_epayment_log_id_seq OWNER TO postgres;

--
-- Name: cc_epayment_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_epayment_log_id_seq OWNED BY cc_epayment_log.id;


--
-- Name: cc_epayment_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_epayment_log_id_seq', 1, false);


--
-- Name: cc_iax_buddies; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_iax_buddies (
    id integer NOT NULL,
    id_cc_card integer DEFAULT 0 NOT NULL,
    name character varying(80) DEFAULT ''::character varying NOT NULL,
    type character varying(6) DEFAULT 'friend'::character varying NOT NULL,
    username character varying(80) DEFAULT ''::character varying NOT NULL,
    accountcode character varying(20),
    regexten character varying(20),
    callerid character varying(80),
    amaflags character varying(7),
    secret character varying(80),
    md5secret character varying(80),
    nat character varying(3) DEFAULT 'yes'::character varying NOT NULL,
    dtmfmode character varying(7) DEFAULT 'RFC2833'::character varying NOT NULL,
    disallow character varying(100) DEFAULT 'all'::character varying,
    allow character varying(100) DEFAULT 'gsm,ulaw,alaw'::character varying,
    host character varying(31) DEFAULT ''::character varying NOT NULL,
    qualify character varying(7) DEFAULT 'yes'::character varying NOT NULL,
    canreinvite character varying(20) DEFAULT 'yes'::character varying,
    callgroup character varying(10),
    context character varying(80),
    defaultip character varying(15),
    fromuser character varying(80),
    fromdomain character varying(80),
    insecure character varying(20),
    language character varying(2),
    mailbox character varying(50),
    permit character varying(95),
    deny character varying(95),
    mask character varying(95),
    pickupgroup character varying(10),
    port character varying(5) DEFAULT ''::character varying NOT NULL,
    restrictcid character varying(1),
    rtptimeout character varying(3),
    rtpholdtimeout character varying(3),
    musiconhold character varying(100),
    regseconds integer DEFAULT 0 NOT NULL,
    ipaddr character varying(15) DEFAULT ''::character varying NOT NULL,
    cancallforward character varying(3) DEFAULT 'yes'::character varying,
    trunk character varying(3) DEFAULT 'no'::character varying
);


ALTER TABLE public.cc_iax_buddies OWNER TO postgres;

--
-- Name: cc_iax_buddies_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_iax_buddies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_iax_buddies_id_seq OWNER TO postgres;

--
-- Name: cc_iax_buddies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_iax_buddies_id_seq OWNED BY cc_iax_buddies.id;


--
-- Name: cc_iax_buddies_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_iax_buddies_id_seq', 1, false);


--
-- Name: cc_invoice; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_invoice (
    id bigint NOT NULL,
    reference character varying(30),
    id_card bigint NOT NULL,
    date timestamp without time zone DEFAULT now() NOT NULL,
    paid_status smallint DEFAULT 0::smallint NOT NULL,
    status smallint DEFAULT 0::smallint NOT NULL,
    title character varying(50) NOT NULL,
    description text NOT NULL
);


ALTER TABLE public.cc_invoice OWNER TO postgres;

--
-- Name: cc_invoice_conf; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_invoice_conf (
    id integer NOT NULL,
    key_val character varying(50) NOT NULL,
    value character varying(50) NOT NULL
);


ALTER TABLE public.cc_invoice_conf OWNER TO postgres;

--
-- Name: cc_invoice_conf_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_invoice_conf_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_invoice_conf_id_seq OWNER TO postgres;

--
-- Name: cc_invoice_conf_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_invoice_conf_id_seq OWNED BY cc_invoice_conf.id;


--
-- Name: cc_invoice_conf_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_invoice_conf_id_seq', 10, true);


--
-- Name: cc_invoice_id_seq1; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_invoice_id_seq1
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_invoice_id_seq1 OWNER TO postgres;

--
-- Name: cc_invoice_id_seq1; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_invoice_id_seq1 OWNED BY cc_invoice.id;


--
-- Name: cc_invoice_id_seq1; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_invoice_id_seq1', 1, false);


--
-- Name: cc_invoice_item; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_invoice_item (
    id bigint NOT NULL,
    id_invoice bigint NOT NULL,
    date timestamp without time zone DEFAULT now() NOT NULL,
    price numeric(15,5) DEFAULT 0::numeric NOT NULL,
    vat numeric(4,2) DEFAULT 0::numeric NOT NULL,
    description text NOT NULL,
    id_ext bigint,
    type_ext character varying(10)
);


ALTER TABLE public.cc_invoice_item OWNER TO postgres;

--
-- Name: cc_invoice_item_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_invoice_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_invoice_item_id_seq OWNER TO postgres;

--
-- Name: cc_invoice_item_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_invoice_item_id_seq OWNED BY cc_invoice_item.id;


--
-- Name: cc_invoice_item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_invoice_item_id_seq', 1, false);


--
-- Name: cc_invoice_payment; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_invoice_payment (
    id_invoice bigint NOT NULL,
    id_payment bigint NOT NULL
);


ALTER TABLE public.cc_invoice_payment OWNER TO postgres;

--
-- Name: cc_iso639; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_iso639 (
    code text NOT NULL,
    name text NOT NULL,
    lname text,
    charset text DEFAULT 'ISO-8859-1'::text NOT NULL
);


ALTER TABLE public.cc_iso639 OWNER TO postgres;

--
-- Name: cc_logpayment; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_logpayment (
    id integer NOT NULL,
    date timestamp(0) without time zone DEFAULT now() NOT NULL,
    payment numeric(15,5) NOT NULL,
    card_id bigint NOT NULL,
    id_logrefill bigint,
    description text,
    added_refill smallint DEFAULT 0 NOT NULL,
    payment_type smallint DEFAULT 0 NOT NULL,
    added_commission smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.cc_logpayment OWNER TO postgres;

--
-- Name: cc_logpayment_agent; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_logpayment_agent (
    id bigint NOT NULL,
    date timestamp without time zone DEFAULT now() NOT NULL,
    payment numeric(15,5) NOT NULL,
    agent_id bigint NOT NULL,
    id_logrefill bigint,
    description text,
    added_refill smallint DEFAULT 0::smallint NOT NULL,
    payment_type smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.cc_logpayment_agent OWNER TO postgres;

--
-- Name: cc_logpayment_agent_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_logpayment_agent_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_logpayment_agent_id_seq OWNER TO postgres;

--
-- Name: cc_logpayment_agent_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_logpayment_agent_id_seq OWNED BY cc_logpayment_agent.id;


--
-- Name: cc_logpayment_agent_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_logpayment_agent_id_seq', 1, false);


--
-- Name: cc_logpayment_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_logpayment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_logpayment_id_seq OWNER TO postgres;

--
-- Name: cc_logpayment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_logpayment_id_seq OWNED BY cc_logpayment.id;


--
-- Name: cc_logpayment_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_logpayment_id_seq', 1, false);


--
-- Name: cc_logrefill; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_logrefill (
    id bigint NOT NULL,
    date timestamp(0) without time zone DEFAULT now() NOT NULL,
    credit numeric(15,5) NOT NULL,
    card_id bigint NOT NULL,
    description text,
    refill_type smallint DEFAULT 0 NOT NULL,
    added_invoice smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.cc_logrefill OWNER TO postgres;

--
-- Name: cc_logrefill_agent; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_logrefill_agent (
    id bigint NOT NULL,
    date timestamp without time zone DEFAULT now() NOT NULL,
    credit numeric(15,5) NOT NULL,
    agent_id bigint NOT NULL,
    description text,
    refill_type smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.cc_logrefill_agent OWNER TO postgres;

--
-- Name: cc_logrefill_agent_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_logrefill_agent_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_logrefill_agent_id_seq OWNER TO postgres;

--
-- Name: cc_logrefill_agent_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_logrefill_agent_id_seq OWNED BY cc_logrefill_agent.id;


--
-- Name: cc_logrefill_agent_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_logrefill_agent_id_seq', 1, false);


--
-- Name: cc_logrefill_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_logrefill_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_logrefill_id_seq OWNER TO postgres;

--
-- Name: cc_logrefill_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_logrefill_id_seq OWNED BY cc_logrefill.id;


--
-- Name: cc_logrefill_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_logrefill_id_seq', 1, false);


--
-- Name: cc_notification; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_notification (
    id bigint NOT NULL,
    key_value character varying(40),
    date timestamp without time zone DEFAULT now() NOT NULL,
    priority smallint DEFAULT 0::smallint NOT NULL,
    from_type smallint NOT NULL,
    from_id bigint DEFAULT 0::bigint
);


ALTER TABLE public.cc_notification OWNER TO postgres;

--
-- Name: cc_notification_admin; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_notification_admin (
    id_notification bigint NOT NULL,
    id_admin integer NOT NULL,
    viewed smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.cc_notification_admin OWNER TO postgres;

--
-- Name: cc_notification_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_notification_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_notification_id_seq OWNER TO postgres;

--
-- Name: cc_notification_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_notification_id_seq OWNED BY cc_notification.id;


--
-- Name: cc_notification_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_notification_id_seq', 1, false);


--
-- Name: cc_outbound_cid_group; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_outbound_cid_group (
    id bigint NOT NULL,
    creationdate timestamp(0) without time zone DEFAULT now(),
    group_name text NOT NULL
);


ALTER TABLE public.cc_outbound_cid_group OWNER TO postgres;

--
-- Name: cc_outbound_cid_group_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_outbound_cid_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_outbound_cid_group_id_seq OWNER TO postgres;

--
-- Name: cc_outbound_cid_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_outbound_cid_group_id_seq OWNED BY cc_outbound_cid_group.id;


--
-- Name: cc_outbound_cid_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_outbound_cid_group_id_seq', 1, false);


--
-- Name: cc_outbound_cid_list; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_outbound_cid_list (
    id bigint NOT NULL,
    outbound_cid_group bigint NOT NULL,
    cid text NOT NULL,
    activated integer DEFAULT 0 NOT NULL,
    creationdate timestamp(0) without time zone DEFAULT now()
);


ALTER TABLE public.cc_outbound_cid_list OWNER TO postgres;

--
-- Name: cc_outbound_cid_list_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_outbound_cid_list_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_outbound_cid_list_id_seq OWNER TO postgres;

--
-- Name: cc_outbound_cid_list_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_outbound_cid_list_id_seq OWNED BY cc_outbound_cid_list.id;


--
-- Name: cc_outbound_cid_list_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_outbound_cid_list_id_seq', 1, false);


--
-- Name: cc_package_group; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_package_group (
    id integer NOT NULL,
    name character varying(30) NOT NULL,
    description text
);


ALTER TABLE public.cc_package_group OWNER TO postgres;

--
-- Name: cc_package_group_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_package_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_package_group_id_seq OWNER TO postgres;

--
-- Name: cc_package_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_package_group_id_seq OWNED BY cc_package_group.id;


--
-- Name: cc_package_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_package_group_id_seq', 1, false);


--
-- Name: cc_package_offer; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_package_offer (
    id bigint NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
    label text NOT NULL,
    packagetype integer NOT NULL,
    billingtype integer NOT NULL,
    startday integer NOT NULL,
    freetimetocall integer NOT NULL
);


ALTER TABLE public.cc_package_offer OWNER TO postgres;

--
-- Name: cc_package_offer_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_package_offer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_package_offer_id_seq OWNER TO postgres;

--
-- Name: cc_package_offer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_package_offer_id_seq OWNED BY cc_package_offer.id;


--
-- Name: cc_package_offer_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_package_offer_id_seq', 1, false);


--
-- Name: cc_package_rate; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_package_rate (
    package_id integer NOT NULL,
    rate_id integer NOT NULL
);


ALTER TABLE public.cc_package_rate OWNER TO postgres;

--
-- Name: cc_packgroup_package; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_packgroup_package (
    packagegroup_id integer NOT NULL,
    package_id integer NOT NULL
);


ALTER TABLE public.cc_packgroup_package OWNER TO postgres;

--
-- Name: cc_payment_methods; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_payment_methods (
    id bigint NOT NULL,
    payment_method text NOT NULL,
    payment_filename text NOT NULL
);


ALTER TABLE public.cc_payment_methods OWNER TO postgres;

--
-- Name: cc_payment_methods_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_payment_methods_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_payment_methods_id_seq OWNER TO postgres;

--
-- Name: cc_payment_methods_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_payment_methods_id_seq OWNED BY cc_payment_methods.id;


--
-- Name: cc_payment_methods_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_payment_methods_id_seq', 4, true);


--
-- Name: cc_payments; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_payments (
    id bigint NOT NULL,
    customers_name text NOT NULL,
    customers_email_address text NOT NULL,
    item_name text NOT NULL,
    item_id text NOT NULL,
    item_quantity integer DEFAULT 0 NOT NULL,
    payment_method character varying(32) NOT NULL,
    cc_type character varying(20),
    cc_owner character varying(64),
    cc_number character varying(32),
    cc_expires character varying(6),
    orders_status integer NOT NULL,
    orders_amount numeric(14,6),
    last_modified timestamp without time zone,
    date_purchased timestamp without time zone,
    orders_date_finished timestamp without time zone,
    currency character varying(3),
    currency_value numeric(14,6),
    customers_id bigint DEFAULT 0::bigint NOT NULL
);


ALTER TABLE public.cc_payments OWNER TO postgres;

--
-- Name: cc_payments_agent; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_payments_agent (
    id bigint NOT NULL,
    agent_id bigint NOT NULL,
    agent_name character varying(200) NOT NULL,
    agent_email_address character varying(96) NOT NULL,
    item_name character varying(127) DEFAULT NULL::character varying,
    item_id character varying(127) DEFAULT NULL::character varying,
    item_quantity integer DEFAULT 0 NOT NULL,
    payment_method character varying(32) NOT NULL,
    cc_type character varying(20) DEFAULT NULL::character varying,
    cc_owner character varying(64) DEFAULT NULL::character varying,
    cc_number character varying(32) DEFAULT NULL::character varying,
    cc_expires character varying(4) DEFAULT NULL::character varying,
    orders_status integer NOT NULL,
    orders_amount numeric(14,6) DEFAULT NULL::numeric,
    last_modified timestamp without time zone,
    date_purchased timestamp without time zone,
    orders_date_finished timestamp without time zone,
    currency character(3) DEFAULT NULL::bpchar,
    currency_value numeric(14,6) DEFAULT NULL::numeric
);


ALTER TABLE public.cc_payments_agent OWNER TO postgres;

--
-- Name: cc_payments_agent_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_payments_agent_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_payments_agent_id_seq OWNER TO postgres;

--
-- Name: cc_payments_agent_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_payments_agent_id_seq OWNED BY cc_payments_agent.id;


--
-- Name: cc_payments_agent_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_payments_agent_id_seq', 1, false);


--
-- Name: cc_payments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_payments_id_seq OWNER TO postgres;

--
-- Name: cc_payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_payments_id_seq OWNED BY cc_payments.id;


--
-- Name: cc_payments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_payments_id_seq', 1, false);


--
-- Name: cc_payments_status; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_payments_status (
    id bigint NOT NULL,
    status_id integer NOT NULL,
    status_name character varying(200) NOT NULL
);


ALTER TABLE public.cc_payments_status OWNER TO postgres;

--
-- Name: cc_payments_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_payments_status_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_payments_status_id_seq OWNER TO postgres;

--
-- Name: cc_payments_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_payments_status_id_seq OWNED BY cc_payments_status.id;


--
-- Name: cc_payments_status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_payments_status_id_seq', 8, true);


--
-- Name: cc_paypal; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_paypal (
    id bigint NOT NULL,
    payer_id character varying(60) DEFAULT NULL::character varying,
    payment_date character varying(50) DEFAULT NULL::character varying,
    txn_id character varying(50) DEFAULT NULL::character varying,
    first_name character varying(50) DEFAULT NULL::character varying,
    last_name character varying(50) DEFAULT NULL::character varying,
    payer_email character varying(75) DEFAULT NULL::character varying,
    payer_status character varying(50) DEFAULT NULL::character varying,
    payment_type character varying(50) DEFAULT NULL::character varying,
    memo text,
    item_name character varying(127) DEFAULT NULL::character varying,
    item_number character varying(127) DEFAULT NULL::character varying,
    quantity bigint DEFAULT 0::bigint NOT NULL,
    mc_gross numeric(9,2) DEFAULT NULL::numeric,
    mc_fee numeric(9,2) DEFAULT NULL::numeric,
    tax numeric(9,2) DEFAULT NULL::numeric,
    mc_currency character varying(3) DEFAULT NULL::character varying,
    address_name character varying(255) DEFAULT ''::character varying NOT NULL,
    address_street character varying(255) DEFAULT ''::character varying NOT NULL,
    address_city character varying(255) DEFAULT ''::character varying NOT NULL,
    address_state character varying(255) DEFAULT ''::character varying NOT NULL,
    address_zip character varying(255) DEFAULT ''::character varying NOT NULL,
    address_country character varying(255) DEFAULT ''::character varying NOT NULL,
    address_status character varying(255) DEFAULT ''::character varying NOT NULL,
    payer_business_name character varying(255) DEFAULT ''::character varying NOT NULL,
    payment_status character varying(255) DEFAULT ''::character varying NOT NULL,
    pending_reason character varying(255) DEFAULT ''::character varying NOT NULL,
    reason_code character varying(255) DEFAULT ''::character varying NOT NULL,
    txn_type character varying(255) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.cc_paypal OWNER TO postgres;

--
-- Name: cc_paypal_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_paypal_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_paypal_id_seq OWNER TO postgres;

--
-- Name: cc_paypal_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_paypal_id_seq OWNED BY cc_paypal.id;


--
-- Name: cc_paypal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_paypal_id_seq', 1, false);


--
-- Name: cc_phonebook; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_phonebook (
    id integer NOT NULL,
    name character varying(30) NOT NULL,
    description text,
    id_card bigint NOT NULL
);


ALTER TABLE public.cc_phonebook OWNER TO postgres;

--
-- Name: cc_phonebook_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_phonebook_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_phonebook_id_seq OWNER TO postgres;

--
-- Name: cc_phonebook_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_phonebook_id_seq OWNED BY cc_phonebook.id;


--
-- Name: cc_phonebook_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_phonebook_id_seq', 1, false);


--
-- Name: cc_phonenumber; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_phonenumber (
    id bigint NOT NULL,
    id_phonebook integer NOT NULL,
    number character varying(30) NOT NULL,
    name character varying(40),
    creationdate timestamp without time zone DEFAULT now() NOT NULL,
    status smallint DEFAULT 1::smallint NOT NULL,
    info text,
    amount integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.cc_phonenumber OWNER TO postgres;

--
-- Name: cc_phonenumber_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_phonenumber_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_phonenumber_id_seq OWNER TO postgres;

--
-- Name: cc_phonenumber_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_phonenumber_id_seq OWNED BY cc_phonenumber.id;


--
-- Name: cc_phonenumber_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_phonenumber_id_seq', 1, false);


--
-- Name: cc_prefix; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_prefix (
    prefix bigint NOT NULL,
    destination character varying(60) NOT NULL
);


ALTER TABLE public.cc_prefix OWNER TO postgres;

--
-- Name: cc_prefix_prefix_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_prefix_prefix_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_prefix_prefix_seq OWNER TO postgres;

--
-- Name: cc_prefix_prefix_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_prefix_prefix_seq OWNED BY cc_prefix.prefix;


--
-- Name: cc_prefix_prefix_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_prefix_prefix_seq', 1, false);


--
-- Name: cc_provider; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_provider (
    id bigint NOT NULL,
    provider_name text NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
    description text
);


ALTER TABLE public.cc_provider OWNER TO postgres;

--
-- Name: cc_provider_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_provider_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_provider_id_seq OWNER TO postgres;

--
-- Name: cc_provider_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_provider_id_seq OWNED BY cc_provider.id;


--
-- Name: cc_provider_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_provider_id_seq', 1, false);


--
-- Name: cc_ratecard; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_ratecard (
    id integer NOT NULL,
    idtariffplan integer DEFAULT 0 NOT NULL,
    dialprefix text NOT NULL,
    buyrate numeric(15,5) DEFAULT 0::numeric NOT NULL,
    buyrateinitblock integer DEFAULT 0 NOT NULL,
    buyrateincrement integer DEFAULT 0 NOT NULL,
    rateinitial numeric(15,5) DEFAULT 0::numeric NOT NULL,
    initblock integer DEFAULT 0 NOT NULL,
    billingblock integer DEFAULT 0 NOT NULL,
    connectcharge numeric(15,5) DEFAULT 0::numeric NOT NULL,
    disconnectcharge numeric(15,5) DEFAULT 0::numeric NOT NULL,
    stepchargea numeric(15,5) DEFAULT 0::numeric NOT NULL,
    chargea numeric(15,5) DEFAULT 0::numeric NOT NULL,
    timechargea integer DEFAULT 0 NOT NULL,
    billingblocka integer DEFAULT 0 NOT NULL,
    stepchargeb numeric(15,5) DEFAULT 0::numeric NOT NULL,
    chargeb numeric(15,5) DEFAULT 0::numeric NOT NULL,
    timechargeb integer DEFAULT 0 NOT NULL,
    billingblockb integer DEFAULT 0 NOT NULL,
    stepchargec real DEFAULT 0 NOT NULL,
    chargec real DEFAULT 0 NOT NULL,
    timechargec integer DEFAULT 0 NOT NULL,
    billingblockc integer DEFAULT 0 NOT NULL,
    startdate timestamp(0) without time zone DEFAULT now(),
    stopdate timestamp(0) without time zone,
    starttime integer DEFAULT 0 NOT NULL,
    endtime integer DEFAULT 10079 NOT NULL,
    id_trunk integer DEFAULT (-1),
    musiconhold character varying(100),
    id_outbound_cidgroup integer DEFAULT (-1) NOT NULL,
    rounding_calltime integer DEFAULT 0 NOT NULL,
    rounding_threshold integer DEFAULT 0 NOT NULL,
    additional_block_charge numeric(15,5) DEFAULT 0 NOT NULL,
    additional_block_charge_time integer DEFAULT 0 NOT NULL,
    tag character varying(50),
    is_merged integer DEFAULT 0,
    additional_grace integer DEFAULT 0 NOT NULL,
    minimal_cost numeric(15,5) DEFAULT 0::numeric NOT NULL,
    announce_time_correction numeric(5,3) DEFAULT 1.0 NOT NULL,
    destination integer DEFAULT 0
);


ALTER TABLE public.cc_ratecard OWNER TO postgres;

--
-- Name: cc_ratecard_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_ratecard_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_ratecard_id_seq OWNER TO postgres;

--
-- Name: cc_ratecard_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_ratecard_id_seq OWNED BY cc_ratecard.id;


--
-- Name: cc_ratecard_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_ratecard_id_seq', 1, false);


--
-- Name: cc_receipt; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_receipt (
    id bigint NOT NULL,
    id_card bigint NOT NULL,
    date timestamp without time zone DEFAULT now() NOT NULL,
    title character varying(50) NOT NULL,
    description text NOT NULL,
    status smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.cc_receipt OWNER TO postgres;

--
-- Name: cc_receipt_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_receipt_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_receipt_id_seq OWNER TO postgres;

--
-- Name: cc_receipt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_receipt_id_seq OWNED BY cc_receipt.id;


--
-- Name: cc_receipt_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_receipt_id_seq', 1, false);


--
-- Name: cc_receipt_item; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_receipt_item (
    id bigint NOT NULL,
    id_receipt bigint NOT NULL,
    date timestamp without time zone DEFAULT now() NOT NULL,
    price numeric(15,5) DEFAULT 0::numeric NOT NULL,
    description text NOT NULL,
    id_ext bigint,
    type_ext character varying(10) DEFAULT NULL::character varying
);


ALTER TABLE public.cc_receipt_item OWNER TO postgres;

--
-- Name: cc_receipt_item_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_receipt_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_receipt_item_id_seq OWNER TO postgres;

--
-- Name: cc_receipt_item_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_receipt_item_id_seq OWNED BY cc_receipt_item.id;


--
-- Name: cc_receipt_item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_receipt_item_id_seq', 1, false);


--
-- Name: cc_restricted_phonenumber; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_restricted_phonenumber (
    id bigint NOT NULL,
    number character varying(50) NOT NULL,
    id_card bigint NOT NULL
);


ALTER TABLE public.cc_restricted_phonenumber OWNER TO postgres;

--
-- Name: cc_restricted_phonenumber_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_restricted_phonenumber_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_restricted_phonenumber_id_seq OWNER TO postgres;

--
-- Name: cc_restricted_phonenumber_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_restricted_phonenumber_id_seq OWNED BY cc_restricted_phonenumber.id;


--
-- Name: cc_restricted_phonenumber_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_restricted_phonenumber_id_seq', 1, false);


--
-- Name: cc_server_group; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_server_group (
    id bigint NOT NULL,
    name text,
    description text
);


ALTER TABLE public.cc_server_group OWNER TO postgres;

--
-- Name: cc_server_group_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_server_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_server_group_id_seq OWNER TO postgres;

--
-- Name: cc_server_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_server_group_id_seq OWNED BY cc_server_group.id;


--
-- Name: cc_server_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_server_group_id_seq', 1, false);


--
-- Name: cc_server_manager; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_server_manager (
    id bigint NOT NULL,
    id_group integer DEFAULT 1,
    server_ip text,
    manager_host text,
    manager_username text,
    manager_secret text,
    lasttime_used timestamp without time zone DEFAULT now()
);


ALTER TABLE public.cc_server_manager OWNER TO postgres;

--
-- Name: cc_server_manager_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_server_manager_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_server_manager_id_seq OWNER TO postgres;

--
-- Name: cc_server_manager_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_server_manager_id_seq OWNED BY cc_server_manager.id;


--
-- Name: cc_server_manager_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_server_manager_id_seq', 1, true);


--
-- Name: cc_service; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_service (
    id bigint NOT NULL,
    name text NOT NULL,
    amount double precision NOT NULL,
    period integer DEFAULT 1 NOT NULL,
    rule integer DEFAULT 0 NOT NULL,
    daynumber integer DEFAULT 0 NOT NULL,
    stopmode integer DEFAULT 0 NOT NULL,
    maxnumbercycle integer DEFAULT 0 NOT NULL,
    status integer DEFAULT 0 NOT NULL,
    numberofrun integer DEFAULT 0 NOT NULL,
    datecreate timestamp(0) without time zone DEFAULT now(),
    datelastrun timestamp(0) without time zone DEFAULT now(),
    emailreport text,
    totalcredit double precision DEFAULT 0 NOT NULL,
    totalcardperform integer DEFAULT 0 NOT NULL,
    operate_mode smallint DEFAULT 0,
    dialplan integer DEFAULT 0,
    use_group smallint DEFAULT 0
);


ALTER TABLE public.cc_service OWNER TO postgres;

--
-- Name: cc_service_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_service_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_service_id_seq OWNER TO postgres;

--
-- Name: cc_service_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_service_id_seq OWNED BY cc_service.id;


--
-- Name: cc_service_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_service_id_seq', 1, false);


--
-- Name: cc_service_report; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_service_report (
    id bigint NOT NULL,
    cc_service_id bigint NOT NULL,
    daterun timestamp(0) without time zone DEFAULT now(),
    totalcardperform integer,
    totalcredit double precision
);


ALTER TABLE public.cc_service_report OWNER TO postgres;

--
-- Name: cc_service_report_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_service_report_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_service_report_id_seq OWNER TO postgres;

--
-- Name: cc_service_report_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_service_report_id_seq OWNED BY cc_service_report.id;


--
-- Name: cc_service_report_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_service_report_id_seq', 1, false);


--
-- Name: cc_sip_buddies; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_sip_buddies (
    id integer NOT NULL,
    id_cc_card integer DEFAULT 0 NOT NULL,
    name character varying(80) DEFAULT ''::character varying NOT NULL,
    type character varying(6) DEFAULT 'friend'::character varying NOT NULL,
    username character varying(80) DEFAULT ''::character varying NOT NULL,
    accountcode character varying(20),
    regexten character varying(20),
    callerid character varying(80),
    amaflags character varying(7),
    secret character varying(80),
    md5secret character varying(80),
    nat character varying(3) DEFAULT 'yes'::character varying NOT NULL,
    dtmfmode character varying(7) DEFAULT 'RFC2833'::character varying NOT NULL,
    disallow character varying(100) DEFAULT 'all'::character varying,
    allow character varying(100) DEFAULT 'gsm,ulaw,alaw'::character varying,
    host character varying(31) DEFAULT ''::character varying NOT NULL,
    qualify character varying(7) DEFAULT 'yes'::character varying NOT NULL,
    canreinvite character varying(20) DEFAULT 'yes'::character varying,
    callgroup character varying(10),
    context character varying(80),
    defaultip character varying(15),
    fromuser character varying(80),
    fromdomain character varying(80),
    insecure character varying(20),
    language character varying(2),
    mailbox character varying(50),
    permit character varying(95),
    deny character varying(95),
    mask character varying(95),
    pickupgroup character varying(10),
    port character varying(5) DEFAULT ''::character varying NOT NULL,
    restrictcid character varying(1),
    rtptimeout character varying(3),
    rtpholdtimeout character varying(3),
    musiconhold character varying(100),
    regseconds integer DEFAULT 0 NOT NULL,
    ipaddr character varying(15) DEFAULT ''::character varying NOT NULL,
    cancallforward character varying(3) DEFAULT 'yes'::character varying,
    fullcontact character varying(80),
    setvar character varying(100) DEFAULT ''::character varying NOT NULL,
    regserver character varying(20)
);


ALTER TABLE public.cc_sip_buddies OWNER TO postgres;

--
-- Name: cc_sip_buddies_empty; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW cc_sip_buddies_empty AS
    SELECT cc_sip_buddies.id, cc_sip_buddies.id_cc_card, cc_sip_buddies.name, cc_sip_buddies.accountcode, cc_sip_buddies.regexten, cc_sip_buddies.amaflags, cc_sip_buddies.callgroup, cc_sip_buddies.callerid, cc_sip_buddies.canreinvite, cc_sip_buddies.context, cc_sip_buddies.defaultip, cc_sip_buddies.dtmfmode, cc_sip_buddies.fromuser, cc_sip_buddies.fromdomain, cc_sip_buddies.host, cc_sip_buddies.insecure, cc_sip_buddies.language, cc_sip_buddies.mailbox, cc_sip_buddies.md5secret, cc_sip_buddies.nat, cc_sip_buddies.permit, cc_sip_buddies.deny, cc_sip_buddies.mask, cc_sip_buddies.pickupgroup, cc_sip_buddies.port, cc_sip_buddies.qualify, cc_sip_buddies.restrictcid, cc_sip_buddies.rtptimeout, cc_sip_buddies.rtpholdtimeout, ''::text AS secret, cc_sip_buddies.type, cc_sip_buddies.username, cc_sip_buddies.disallow, cc_sip_buddies.allow, cc_sip_buddies.musiconhold, cc_sip_buddies.regseconds, cc_sip_buddies.ipaddr, cc_sip_buddies.cancallforward, cc_sip_buddies.fullcontact, cc_sip_buddies.setvar FROM cc_sip_buddies;


ALTER TABLE public.cc_sip_buddies_empty OWNER TO postgres;

--
-- Name: cc_sip_buddies_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_sip_buddies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_sip_buddies_id_seq OWNER TO postgres;

--
-- Name: cc_sip_buddies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_sip_buddies_id_seq OWNED BY cc_sip_buddies.id;


--
-- Name: cc_sip_buddies_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_sip_buddies_id_seq', 1, false);


--
-- Name: cc_speeddial; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_speeddial (
    id bigint NOT NULL,
    id_cc_card bigint DEFAULT 0 NOT NULL,
    phone text NOT NULL,
    name text NOT NULL,
    speeddial integer DEFAULT 0,
    creationdate timestamp without time zone DEFAULT now()
);


ALTER TABLE public.cc_speeddial OWNER TO postgres;

--
-- Name: cc_speeddial_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_speeddial_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_speeddial_id_seq OWNER TO postgres;

--
-- Name: cc_speeddial_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_speeddial_id_seq OWNED BY cc_speeddial.id;


--
-- Name: cc_speeddial_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_speeddial_id_seq', 1, false);


--
-- Name: cc_status_log; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_status_log (
    id bigint NOT NULL,
    status integer NOT NULL,
    id_cc_card bigint NOT NULL,
    updated_date timestamp without time zone DEFAULT now()
);


ALTER TABLE public.cc_status_log OWNER TO postgres;

--
-- Name: cc_status_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_status_log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_status_log_id_seq OWNER TO postgres;

--
-- Name: cc_status_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_status_log_id_seq OWNED BY cc_status_log.id;


--
-- Name: cc_status_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_status_log_id_seq', 1, false);


--
-- Name: cc_subscription_fee; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_subscription_fee (
    id bigint NOT NULL,
    label text NOT NULL,
    fee numeric(12,4) NOT NULL,
    currency character varying(3) DEFAULT 'USD'::character varying,
    status integer DEFAULT 0 NOT NULL,
    numberofrun integer DEFAULT 0 NOT NULL,
    datecreate timestamp(0) without time zone DEFAULT now(),
    datelastrun timestamp(0) without time zone DEFAULT now(),
    emailreport text,
    totalcredit double precision DEFAULT 0 NOT NULL,
    totalcardperform integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.cc_subscription_fee OWNER TO postgres;

--
-- Name: cc_subscription_fee_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_subscription_fee_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_subscription_fee_id_seq OWNER TO postgres;

--
-- Name: cc_subscription_fee_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_subscription_fee_id_seq OWNED BY cc_subscription_fee.id;


--
-- Name: cc_subscription_fee_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_subscription_fee_id_seq', 1, false);


--
-- Name: cc_support; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_support (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.cc_support OWNER TO postgres;

--
-- Name: cc_support_component; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_support_component (
    id integer NOT NULL,
    id_support integer NOT NULL,
    name character varying(50) DEFAULT ''::character varying NOT NULL,
    activated smallint DEFAULT 1 NOT NULL
);


ALTER TABLE public.cc_support_component OWNER TO postgres;

--
-- Name: cc_support_component_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_support_component_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_support_component_id_seq OWNER TO postgres;

--
-- Name: cc_support_component_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_support_component_id_seq OWNED BY cc_support_component.id;


--
-- Name: cc_support_component_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_support_component_id_seq', 1, false);


--
-- Name: cc_support_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_support_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_support_id_seq OWNER TO postgres;

--
-- Name: cc_support_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_support_id_seq OWNED BY cc_support.id;


--
-- Name: cc_support_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_support_id_seq', 1, false);


--
-- Name: cc_system_log; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_system_log (
    id bigint NOT NULL,
    iduser integer DEFAULT 0 NOT NULL,
    loglevel integer DEFAULT 0 NOT NULL,
    action text NOT NULL,
    description text,
    data text,
    tablename character varying(255),
    pagename character varying(255),
    ipaddress character varying(255),
    creationdate timestamp(0) without time zone DEFAULT now()
);


ALTER TABLE public.cc_system_log OWNER TO postgres;

--
-- Name: cc_system_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_system_log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_system_log_id_seq OWNER TO postgres;

--
-- Name: cc_system_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_system_log_id_seq OWNED BY cc_system_log.id;


--
-- Name: cc_system_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_system_log_id_seq', 1, false);


--
-- Name: cc_tariffgroup; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_tariffgroup (
    id integer NOT NULL,
    iduser integer DEFAULT 0 NOT NULL,
    idtariffplan integer DEFAULT 0 NOT NULL,
    tariffgroupname text NOT NULL,
    lcrtype integer DEFAULT 0 NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
    removeinterprefix integer DEFAULT 0 NOT NULL,
    id_cc_package_offer bigint DEFAULT 0 NOT NULL
);


ALTER TABLE public.cc_tariffgroup OWNER TO postgres;

--
-- Name: cc_tariffgroup_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_tariffgroup_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_tariffgroup_id_seq OWNER TO postgres;

--
-- Name: cc_tariffgroup_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_tariffgroup_id_seq OWNED BY cc_tariffgroup.id;


--
-- Name: cc_tariffgroup_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_tariffgroup_id_seq', 1, false);


--
-- Name: cc_tariffgroup_plan; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_tariffgroup_plan (
    idtariffgroup integer NOT NULL,
    idtariffplan integer NOT NULL
);


ALTER TABLE public.cc_tariffgroup_plan OWNER TO postgres;

--
-- Name: cc_tariffplan; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_tariffplan (
    id integer NOT NULL,
    iduser integer DEFAULT 0 NOT NULL,
    tariffname text NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
    startingdate timestamp without time zone DEFAULT now(),
    expirationdate timestamp without time zone,
    description text,
    id_trunk integer DEFAULT 0,
    secondusedreal integer DEFAULT 0,
    secondusedcarrier integer DEFAULT 0,
    secondusedratecard integer DEFAULT 0,
    reftariffplan integer DEFAULT 0,
    idowner integer DEFAULT 0,
    dnidprefix text DEFAULT 'all'::text NOT NULL,
    calleridprefix text DEFAULT 'all'::text NOT NULL
);


ALTER TABLE public.cc_tariffplan OWNER TO postgres;

--
-- Name: cc_tariffplan_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_tariffplan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_tariffplan_id_seq OWNER TO postgres;

--
-- Name: cc_tariffplan_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_tariffplan_id_seq OWNED BY cc_tariffplan.id;


--
-- Name: cc_tariffplan_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_tariffplan_id_seq', 1, false);


--
-- Name: cc_templatemail; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_templatemail (
    mailtype text,
    fromemail text,
    fromname text,
    subject text,
    messagetext text,
    messagehtml text,
    id integer NOT NULL,
    id_language character varying(20) DEFAULT 'en'::character varying
);


ALTER TABLE public.cc_templatemail OWNER TO postgres;

--
-- Name: cc_templatemail_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_templatemail_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_templatemail_id_seq OWNER TO postgres;

--
-- Name: cc_templatemail_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_templatemail_id_seq OWNED BY cc_templatemail.id;


--
-- Name: cc_templatemail_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_templatemail_id_seq', 7, true);


--
-- Name: cc_ticket; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_ticket (
    id bigint NOT NULL,
    id_component integer NOT NULL,
    title character varying(100) NOT NULL,
    description text,
    priority smallint DEFAULT 0 NOT NULL,
    creationdate timestamp without time zone DEFAULT now() NOT NULL,
    creator bigint NOT NULL,
    status integer DEFAULT 0 NOT NULL,
    creator_type smallint DEFAULT 0::smallint NOT NULL,
    viewed_cust smallint DEFAULT 1::smallint NOT NULL,
    viewed_agent smallint DEFAULT 1::smallint NOT NULL,
    viewed_admin smallint DEFAULT 1::smallint NOT NULL
);


ALTER TABLE public.cc_ticket OWNER TO postgres;

--
-- Name: cc_ticket_comment; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_ticket_comment (
    id bigint NOT NULL,
    date timestamp without time zone DEFAULT now() NOT NULL,
    id_ticket bigint NOT NULL,
    description text,
    creator bigint NOT NULL,
    creator_type boolean DEFAULT false NOT NULL,
    viewed_cust smallint DEFAULT 1::smallint NOT NULL,
    viewed_agent smallint DEFAULT 1::smallint NOT NULL,
    viewed_admin smallint DEFAULT 1::smallint NOT NULL
);


ALTER TABLE public.cc_ticket_comment OWNER TO postgres;

--
-- Name: cc_ticket_comment_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_ticket_comment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_ticket_comment_id_seq OWNER TO postgres;

--
-- Name: cc_ticket_comment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_ticket_comment_id_seq OWNED BY cc_ticket_comment.id;


--
-- Name: cc_ticket_comment_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_ticket_comment_id_seq', 1, false);


--
-- Name: cc_ticket_comment_id_ticket_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_ticket_comment_id_ticket_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_ticket_comment_id_ticket_seq OWNER TO postgres;

--
-- Name: cc_ticket_comment_id_ticket_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_ticket_comment_id_ticket_seq OWNED BY cc_ticket_comment.id_ticket;


--
-- Name: cc_ticket_comment_id_ticket_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_ticket_comment_id_ticket_seq', 1, false);


--
-- Name: cc_ticket_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_ticket_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_ticket_id_seq OWNER TO postgres;

--
-- Name: cc_ticket_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_ticket_id_seq OWNED BY cc_ticket.id;


--
-- Name: cc_ticket_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_ticket_id_seq', 1, false);


--
-- Name: cc_timezone; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_timezone (
    id integer NOT NULL,
    gmtzone character varying(255),
    gmttime character varying(255),
    gmtoffset bigint DEFAULT 0 NOT NULL
);


ALTER TABLE public.cc_timezone OWNER TO postgres;

--
-- Name: cc_timezone_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_timezone_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_timezone_id_seq OWNER TO postgres;

--
-- Name: cc_timezone_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_timezone_id_seq OWNED BY cc_timezone.id;


--
-- Name: cc_timezone_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_timezone_id_seq', 75, true);


--
-- Name: cc_trunk; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_trunk (
    id_trunk integer NOT NULL,
    trunkcode text NOT NULL,
    trunkprefix text,
    providertech text NOT NULL,
    providerip text NOT NULL,
    removeprefix text,
    secondusedreal integer DEFAULT 0,
    secondusedcarrier integer DEFAULT 0,
    secondusedratecard integer DEFAULT 0,
    creationdate timestamp(0) without time zone DEFAULT now(),
    failover_trunk integer,
    addparameter text,
    id_provider integer,
    inuse integer DEFAULT 0,
    maxuse integer DEFAULT (-1),
    status integer DEFAULT 1,
    if_max_use integer DEFAULT 0
);


ALTER TABLE public.cc_trunk OWNER TO postgres;

--
-- Name: cc_trunk_id_trunk_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_trunk_id_trunk_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_trunk_id_trunk_seq OWNER TO postgres;

--
-- Name: cc_trunk_id_trunk_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_trunk_id_trunk_seq OWNED BY cc_trunk.id_trunk;


--
-- Name: cc_trunk_id_trunk_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_trunk_id_trunk_seq', 2, true);


--
-- Name: cc_ui_authen; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_ui_authen (
    userid bigint NOT NULL,
    login text NOT NULL,
    pwd_encoded text NOT NULL,
    groupid integer,
    perms integer,
    confaddcust integer,
    name text,
    direction text,
    zipcode text,
    state text,
    phone text,
    fax text,
    datecreation timestamp without time zone DEFAULT now(),
    email character varying(70)
);


ALTER TABLE public.cc_ui_authen OWNER TO postgres;

--
-- Name: cc_ui_authen_userid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_ui_authen_userid_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_ui_authen_userid_seq OWNER TO postgres;

--
-- Name: cc_ui_authen_userid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_ui_authen_userid_seq OWNED BY cc_ui_authen.userid;


--
-- Name: cc_ui_authen_userid_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_ui_authen_userid_seq', 3, true);


--
-- Name: cc_voucher; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cc_voucher (
    id bigint NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
    usedate timestamp without time zone,
    expirationdate timestamp without time zone,
    voucher text NOT NULL,
    usedcardnumber text,
    tag text,
    credit numeric(12,4) NOT NULL,
    activated boolean DEFAULT true NOT NULL,
    used integer DEFAULT 0,
    currency character varying(3) DEFAULT 'USD'::character varying
);


ALTER TABLE public.cc_voucher OWNER TO postgres;

--
-- Name: cc_voucher_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cc_voucher_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.cc_voucher_id_seq OWNER TO postgres;

--
-- Name: cc_voucher_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cc_voucher_id_seq OWNED BY cc_voucher.id;


--
-- Name: cc_voucher_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cc_voucher_id_seq', 1, false);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_agent ALTER COLUMN id SET DEFAULT nextval('cc_agent_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_agent_commission ALTER COLUMN id SET DEFAULT nextval('cc_agent_commission_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_alarm ALTER COLUMN id SET DEFAULT nextval('cc_alarm_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_alarm_report ALTER COLUMN id SET DEFAULT nextval('cc_alarm_report_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_autorefill_report ALTER COLUMN id SET DEFAULT nextval('cc_autorefill_report_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_backup ALTER COLUMN id SET DEFAULT nextval('cc_backup_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_billing_customer ALTER COLUMN id SET DEFAULT nextval('cc_billing_customer_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_call ALTER COLUMN id SET DEFAULT nextval('cc_call_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_call_archive ALTER COLUMN id SET DEFAULT nextval('cc_call_archive_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_callback_spool ALTER COLUMN id SET DEFAULT nextval('cc_callback_spool_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_callerid ALTER COLUMN id SET DEFAULT nextval('cc_callerid_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_campaign ALTER COLUMN id SET DEFAULT nextval('cc_campaign_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_campaign_config ALTER COLUMN id SET DEFAULT nextval('cc_campaign_config_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_card ALTER COLUMN id SET DEFAULT nextval('cc_card_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_card_archive ALTER COLUMN id SET DEFAULT nextval('cc_card_archive_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_card_group ALTER COLUMN id SET DEFAULT nextval('cc_card_group_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_card_history ALTER COLUMN id SET DEFAULT nextval('cc_card_history_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_card_package_offer ALTER COLUMN id SET DEFAULT nextval('cc_card_package_offer_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_card_seria ALTER COLUMN id SET DEFAULT nextval('cc_card_seria_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_card_subscription ALTER COLUMN id SET DEFAULT nextval('cc_card_subscription_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_charge ALTER COLUMN id SET DEFAULT nextval('cc_charge_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_config ALTER COLUMN id SET DEFAULT nextval('cc_config_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_config_group ALTER COLUMN id SET DEFAULT nextval('cc_config_group_id_seq'::regclass);


--
-- Name: configuration_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_configuration ALTER COLUMN configuration_id SET DEFAULT nextval('cc_configuration_configuration_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_country ALTER COLUMN id SET DEFAULT nextval('cc_country_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_currencies ALTER COLUMN id SET DEFAULT nextval('cc_currencies_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_did ALTER COLUMN id SET DEFAULT nextval('cc_did_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_did_destination ALTER COLUMN id SET DEFAULT nextval('cc_did_destination_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_did_use ALTER COLUMN id SET DEFAULT nextval('cc_did_use_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_didgroup ALTER COLUMN id SET DEFAULT nextval('cc_didgroup_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_ecommerce_product ALTER COLUMN id SET DEFAULT nextval('cc_ecommerce_product_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_epayment_log ALTER COLUMN id SET DEFAULT nextval('cc_epayment_log_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_epayment_log_agent ALTER COLUMN id SET DEFAULT nextval('cc_epayment_log_agent_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_iax_buddies ALTER COLUMN id SET DEFAULT nextval('cc_iax_buddies_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_invoice ALTER COLUMN id SET DEFAULT nextval('cc_invoice_id_seq1'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_invoice_conf ALTER COLUMN id SET DEFAULT nextval('cc_invoice_conf_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_invoice_item ALTER COLUMN id SET DEFAULT nextval('cc_invoice_item_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_logpayment ALTER COLUMN id SET DEFAULT nextval('cc_logpayment_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_logpayment_agent ALTER COLUMN id SET DEFAULT nextval('cc_logpayment_agent_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_logrefill ALTER COLUMN id SET DEFAULT nextval('cc_logrefill_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_logrefill_agent ALTER COLUMN id SET DEFAULT nextval('cc_logrefill_agent_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_notification ALTER COLUMN id SET DEFAULT nextval('cc_notification_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_outbound_cid_group ALTER COLUMN id SET DEFAULT nextval('cc_outbound_cid_group_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_outbound_cid_list ALTER COLUMN id SET DEFAULT nextval('cc_outbound_cid_list_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_package_group ALTER COLUMN id SET DEFAULT nextval('cc_package_group_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_package_offer ALTER COLUMN id SET DEFAULT nextval('cc_package_offer_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_payment_methods ALTER COLUMN id SET DEFAULT nextval('cc_payment_methods_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_payments ALTER COLUMN id SET DEFAULT nextval('cc_payments_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_payments_agent ALTER COLUMN id SET DEFAULT nextval('cc_payments_agent_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_payments_status ALTER COLUMN id SET DEFAULT nextval('cc_payments_status_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_paypal ALTER COLUMN id SET DEFAULT nextval('cc_paypal_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_phonebook ALTER COLUMN id SET DEFAULT nextval('cc_phonebook_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_phonenumber ALTER COLUMN id SET DEFAULT nextval('cc_phonenumber_id_seq'::regclass);


--
-- Name: prefix; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_prefix ALTER COLUMN prefix SET DEFAULT nextval('cc_prefix_prefix_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_provider ALTER COLUMN id SET DEFAULT nextval('cc_provider_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_ratecard ALTER COLUMN id SET DEFAULT nextval('cc_ratecard_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_receipt ALTER COLUMN id SET DEFAULT nextval('cc_receipt_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_receipt_item ALTER COLUMN id SET DEFAULT nextval('cc_receipt_item_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_restricted_phonenumber ALTER COLUMN id SET DEFAULT nextval('cc_restricted_phonenumber_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_server_group ALTER COLUMN id SET DEFAULT nextval('cc_server_group_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_server_manager ALTER COLUMN id SET DEFAULT nextval('cc_server_manager_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_service ALTER COLUMN id SET DEFAULT nextval('cc_service_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_service_report ALTER COLUMN id SET DEFAULT nextval('cc_service_report_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_sip_buddies ALTER COLUMN id SET DEFAULT nextval('cc_sip_buddies_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_speeddial ALTER COLUMN id SET DEFAULT nextval('cc_speeddial_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_status_log ALTER COLUMN id SET DEFAULT nextval('cc_status_log_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_subscription_fee ALTER COLUMN id SET DEFAULT nextval('cc_subscription_fee_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_support ALTER COLUMN id SET DEFAULT nextval('cc_support_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_support_component ALTER COLUMN id SET DEFAULT nextval('cc_support_component_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_system_log ALTER COLUMN id SET DEFAULT nextval('cc_system_log_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_tariffgroup ALTER COLUMN id SET DEFAULT nextval('cc_tariffgroup_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_tariffplan ALTER COLUMN id SET DEFAULT nextval('cc_tariffplan_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_templatemail ALTER COLUMN id SET DEFAULT nextval('cc_templatemail_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_ticket ALTER COLUMN id SET DEFAULT nextval('cc_ticket_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_ticket_comment ALTER COLUMN id SET DEFAULT nextval('cc_ticket_comment_id_seq'::regclass);


--
-- Name: id_ticket; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_ticket_comment ALTER COLUMN id_ticket SET DEFAULT nextval('cc_ticket_comment_id_ticket_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_timezone ALTER COLUMN id SET DEFAULT nextval('cc_timezone_id_seq'::regclass);


--
-- Name: id_trunk; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_trunk ALTER COLUMN id_trunk SET DEFAULT nextval('cc_trunk_id_trunk_seq'::regclass);


--
-- Name: userid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_ui_authen ALTER COLUMN userid SET DEFAULT nextval('cc_ui_authen_userid_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE cc_voucher ALTER COLUMN id SET DEFAULT nextval('cc_voucher_id_seq'::regclass);


--
-- Data for Name: cc_agent; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_agent_commission; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_agent_tariffgroup; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_alarm; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_alarm_report; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_autorefill_report; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_backup; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_billing_customer; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_call; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_call_archive; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_callback_spool; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_callerid; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_campaign; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_campaign_config; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_campaign_phonebook; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_campaign_phonestatus; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_campaignconf_cardgroup; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_card; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_card_archive; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_card_group; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_card_group VALUES (1, 'DEFAULT', 'This group is the default group used when you create a customer. It''s forbidden to delete it because you need at least one group but you can edit it.', 129022, NULL);


--
-- Data for Name: cc_card_history; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_card_package_offer; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_card_seria; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_card_subscription; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_cardgroup_service; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_charge; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_config; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_config VALUES (65, 'Enable SSL', 'enable_ssl', '1', 'secure webserver for checkout procedure?', 1, 'yes,no', 'epayment_method');
INSERT INTO cc_config VALUES (194, 'Monitor File Format', 'monitor_formatfile', 'gsm', 'format of the recorded monitor file.', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (1, 'Card Number length', 'interval_len_cardnumber', '10-15', 'Card Number length, You can define a Range e.g: 10-15.', 0, '10-15,5-20,10-30', 'global');
INSERT INTO cc_config VALUES (2, 'Card Alias length', 'len_aliasnumber', '15', 'Card Number Alias Length e.g: 15.', 0, NULL, 'global');
INSERT INTO cc_config VALUES (3, 'Voucher length', 'len_voucher', '15', 'Voucher Number Length.', 0, NULL, 'global');
INSERT INTO cc_config VALUES (4, 'Base Currency', 'base_currency', 'usd', 'Base Currency to use for application.', 0, NULL, 'global');
INSERT INTO cc_config VALUES (5, 'Invoice Image', 'invoice_image', 'asterisk01.jpg', 'Image to Display on the Top of Invoice', 0, NULL, 'global');
INSERT INTO cc_config VALUES (6, 'Admin Email', 'admin_email', 'root@localhost', 'Web Administrator Email Address.', 0, NULL, 'global');
INSERT INTO cc_config VALUES (7, 'DID Bill Payment Day', 'didbilling_daytopay', '5', 'DID Bill Payment Day of Month', 0, NULL, 'global');
INSERT INTO cc_config VALUES (8, 'Manager Host', 'manager_host', 'localhost', 'Manager Host Address', 0, NULL, 'global');
INSERT INTO cc_config VALUES (9, 'Manager User ID', 'manager_username', 'myasterisk', 'Manger Host User Name', 0, NULL, 'global');
INSERT INTO cc_config VALUES (10, 'Manager Password', 'manager_secret', 'mycode', 'Manager Host Password', 0, NULL, 'global');
INSERT INTO cc_config VALUES (11, 'Use SMTP Server', 'smtp_server', '0', 'Define if you want to use an STMP server or Send Mail (value yes for server SMTP)', 1, 'yes,no', 'global');
INSERT INTO cc_config VALUES (12, 'SMTP Host', 'smtp_host', 'localhost', 'SMTP Hostname', 0, NULL, 'global');
INSERT INTO cc_config VALUES (13, 'SMTP UserName', 'smtp_username', '', 'User Name to connect on the SMTP server', 0, NULL, 'global');
INSERT INTO cc_config VALUES (14, 'SMTP Password', 'smtp_password', '', 'Password to connect on the SMTP server', 0, NULL, 'global');
INSERT INTO cc_config VALUES (15, 'Use Realtime', 'use_realtime', '1', 'if Disabled, it will generate the config files and offer an option to reload asterisk after an update on the Voip settings', 1, 'yes,no', 'global');
INSERT INTO cc_config VALUES (16, 'Go To Customer', 'customer_ui_url', '../../customer/index.php', 'Link to the customer account', 0, NULL, 'global');
INSERT INTO cc_config VALUES (17, 'Context Callback', 'context_callback', 'a2billing-callback', 'Contaxt to use in Callback', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (18, 'Extension', 'extension', '1000', 'Extension to call while callback.', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (19, 'Wait before callback', 'sec_wait_before_callback', '10', 'Seconds to wait before callback.', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (20, 'Avoid Repeat Duration', 'sec_avoid_repeate', '10', 'Number of seconds before the call-back can be re-initiated from the web page to prevent repeated and unwanted calls.', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (21, 'Time out', 'timeout', '20', 'if the callback doesnt succeed within the value below, then the call is deemed to have failed.', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (22, 'Answer on Call', 'answer_call', '1', 'if we want to manage the answer on the call. Disabling this for callback trigger numbers makes it ring not hang up.', 1, 'yes,no', 'callback');
INSERT INTO cc_config VALUES (23, 'No of Predictive Calls', 'nb_predictive_call', '10', 'number of calls an agent will do when the call button is clicked.', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (24, 'Delay for Availability', 'nb_day_wait_before_retry', '1', 'Number of days to wait before the number becomes available to call again.', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (25, 'PD Contect', 'context_preditctivedialer', 'a2billing-predictivedialer', 'The context to redirect the call for the predictive dialer.', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (26, 'Max Time to call', 'predictivedialer_maxtime_tocall', '5400', 'When a call is made we need to limit the call duration : amount in seconds.', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (27, 'PD Caller ID', 'callerid', '123456', 'Set the callerID for the predictive dialer and call-back.', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (28, 'Callback CallPlan ID', 'all_callback_tariff', '1', 'ID Call Plan to use when you use the all-callback mode, check the ID in the "list Call Plan" - WebUI.', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (29, 'Server Group ID', 'id_server_group', '1', 'Define the group of servers that are going to be used by the callback.', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (30, 'Audio Intro', 'callback_audio_intro', 'prepaid-callback_intro', 'Audio intro message when the callback is initiate.', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (31, 'Signup URL', 'signup_page_url', '', 'url of the signup page to show up on the sign in page (if empty no link will show up).', 0, NULL, 'webcustomerui');
INSERT INTO cc_config VALUES (32, 'Payment Method', 'paymentmethod', '1', 'Enable or disable the payment methods; yes for multi-payment or no for single payment method option.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (33, 'Personal Info', 'personalinfo', '1', 'Enable or disable the page which allow customer to modify its personal information.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (34, 'Payment Info', 'customerinfo', '1', 'Enable display of the payment interface - yes or no.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (35, 'SIP/IAX Info', 'sipiaxinfo', '1', 'Enable display of the sip/iax info - yes or no.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (36, 'CDR', 'cdr', '1', 'Enable the Call history - yes or no.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (37, 'Invoices', 'invoice', '1', 'Enable invoices - yes or no.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (38, 'Voucher Screen', 'voucher', '1', 'Enable the voucher screen - yes or no.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (39, 'Paypal', 'paypal', '1', 'Enable the paypal payment buttons - yes or no.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (40, 'Speed Dial', 'speeddial', '1', 'Allow Speed Dial capabilities - yes or no.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (41, 'DID', 'did', '1', 'Enable the DID (Direct Inwards Dialling) interface - yes or no.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (42, 'RateCard', 'ratecard', '1', 'Show the ratecards - yes or no.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (43, 'Simulator', 'simulator', '1', 'Offer simulator option on the customer interface - yes or no.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (44, 'CallBack', 'callback', '1', 'Enable the callback option on the customer interface - yes or no.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (45, 'Predictive Dialer', 'predictivedialer', '1', 'Enable the predictivedialer option on the customer interface - yes or no.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (46, 'WebPhone', 'webphone', '1', 'Let users use SIP/IAX Webphone (Options : yes/no).', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (47, 'WebPhone Server', 'webphoneserver', 'localhost', 'IP address or domain name of asterisk server that would be used by the web-phone.', 0, NULL, 'webcustomerui');
INSERT INTO cc_config VALUES (48, 'Caller ID', 'callerid', '1', 'Let the users add new callerid.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (49, 'Password', 'password', '1', 'Let the user change the webui password.', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (50, 'CallerID Limit', 'limit_callerid', '5', 'The total number of callerIDs for CLI Recognition that can be add by the customer.', 0, NULL, 'webcustomerui');
INSERT INTO cc_config VALUES (51, 'Trunk Name', 'sip_iax_info_trunkname', 'call-labs', 'Trunk Name to show in sip/iax info.', 0, NULL, 'sip-iax-info');
INSERT INTO cc_config VALUES (52, 'Codecs Allowed', 'sip_iax_info_allowcodec', 'g729', 'Allowed Codec, ulaw, gsm, g729.', 0, NULL, 'sip-iax-info');
INSERT INTO cc_config VALUES (53, 'Host', 'sip_iax_info_host', 'call-labs.com', 'Host information.', 0, NULL, 'sip-iax-info');
INSERT INTO cc_config VALUES (54, 'IAX Parms', 'iax_additional_parameters', 'canreinvite = no', 'IAX Additional Parameters.', 0, NULL, 'sip-iax-info');
INSERT INTO cc_config VALUES (55, 'SIP Parms', 'sip_additional_parameters', 'trustrpid = yes | sendrpid = yes | canreinvite = no', 'SIP Additional Parameters.', 0, NULL, 'sip-iax-info');
INSERT INTO cc_config VALUES (56, 'Enable', 'enable', '1', 'Enable/Disable.', 1, 'yes,no', 'epayment_method');
INSERT INTO cc_config VALUES (57, 'HTTP Server Customer', 'http_server', 'http://www.call-labs.com', 'Set the Server Address of Customer Website, It should be empty for productive Servers.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (58, 'HTTPS Server Customer', 'https_server', 'https://www.call-labs.com', 'https://localhost - Enter here your Secure Customers Server Address, should not be empty for productive servers.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (59, 'Server Customer IP/Domain', 'http_cookie_domain', '26.63.165.200', 'Enter your Domain Name or IP Address for the Customers application, eg, 26.63.165.200.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (60, 'Secure Server Customer IP/Domain', 'https_cookie_domain', '26.63.165.200', 'Enter your Secure server Domain Name or IP Address for the Customers application, eg, 26.63.165.200.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (61, 'Application Customer Path', 'http_cookie_path', '/customer/', 'Enter the Physical path of your Customers Application on your server.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (62, 'Secure Application Customer Path', 'https_cookie_path', '/customer/', 'Enter the Physical path of your Customers Application on your Secure Server.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (63, 'Application Customer Physical Path', 'dir_ws_http_catalog', '/customer/', 'Enter the Physical path of your Customers Application on your server.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (64, 'Secure Application Customer Physical Path', 'dir_ws_https_catalog', '/customer/', 'Enter the Physical path of your Customers Application on your Secure server.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (66, 'HTTP Domain', 'http_domain', '26.63.165.200', 'Http Address.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (67, 'Directory Path', 'dir_ws_http', '/~areski/svn/a2billing/payment/customer/', 'Directory Path.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (68, 'Payment Amount', 'purchase_amount', '1:2:5:10:20', 'define the different amount of purchase that would be available - 5 amount maximum (5:10:15).', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (69, 'Item Name', 'item_name', 'Credit Purchase', 'Item name that would be display to the user when he will buy credit.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (70, 'Currency Code', 'currency_code', 'USD', 'Currency for the Credit purchase, only one can be define here.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (71, 'Paypal Payment URL', 'paypal_payment_url', 'https://secure.paypal.com/cgi-bin/webscr', 'Define here the URL of paypal gateway the payment (to test with paypal sandbox).', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (72, 'Paypal Verify URL', 'paypal_verify_url', 'ssl://www.paypal.com', 'paypal transaction verification url.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (73, 'Authorize.NET Payment URL', 'authorize_payment_url', 'https://secure.authorize.net/gateway/transact.dll', 'Define here the URL of Authorize gateway.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (74, 'PayPal Store Name', 'store_name', 'Asterisk2Billing', 'paypal store name to show in the paypal site when customer will go to pay.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (75, 'Transaction Key', 'transaction_key', 'asdf1212fasd121554sd4f5s45sdf', 'Transaction Key for security of Epayment Max length of 60 Characters.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (76, 'Secret Word', 'moneybookers_secretword', 'areski', 'Moneybookers secret word.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (77, 'Enable', 'enable_signup', '1', 'Enable Signup Module.', 1, 'yes,no', 'signup');
INSERT INTO cc_config VALUES (78, 'Captcha Security', 'enable_captcha', '1', 'enable Captcha on the signup module (value : YES or NO).', 1, 'yes,no', 'signup');
INSERT INTO cc_config VALUES (79, 'Credit', 'credit', '0', 'amount of credit applied to a new user.', 0, NULL, 'signup');
INSERT INTO cc_config VALUES (80, 'CallPlan ID List', 'callplan_id_list', '1,2', 'the list of id of call plans which will be shown in signup.', 0, NULL, 'signup');
INSERT INTO cc_config VALUES (81, 'Card Activation', 'activated', '0', 'Specify whether the card is created as active or pending.', 1, 'yes,no', 'signup');
INSERT INTO cc_config VALUES (82, 'Access Type', 'simultaccess', '0', 'Simultaneous or non concurrent access with the card - 0 = INDIVIDUAL ACCESS or 1 = SIMULTANEOUS ACCESS.', 0, NULL, 'signup');
INSERT INTO cc_config VALUES (83, 'Paid Type', 'typepaid', '0', 'PREPAID CARD  =  0 - POSTPAY CARD  =  1.', 0, NULL, 'signup');
INSERT INTO cc_config VALUES (84, 'Credit Limit', 'creditlimit', '0', 'Define credit limit, which is only used for a POSTPAY card.', 0, NULL, 'signup');
INSERT INTO cc_config VALUES (85, 'Run Service', 'runservice', '0', 'Authorise the recurring service to apply on this card  -  Yes 1 - No 0.', 0, NULL, 'signup');
INSERT INTO cc_config VALUES (86, 'Enable Expire', 'enableexpire', '0', 'Enable the expiry of the card  -  Yes 1 - No 0.', 0, NULL, 'signup');
INSERT INTO cc_config VALUES (87, 'Date Format', 'expirationdate', '', 'Expiry Date format YYYY-MM-DD HH:MM:SS. For instance 2004-12-31 00:00:00', 0, NULL, 'signup');
INSERT INTO cc_config VALUES (88, 'Expire Limit', 'expiredays', '0', 'The number of days after which the card will expire.', 0, NULL, 'signup');
INSERT INTO cc_config VALUES (89, 'Create SIP', 'sip_account', '1', 'Create a sip account from signup ( default : yes ).', 1, 'yes,no', 'signup');
INSERT INTO cc_config VALUES (90, 'Create IAX', 'iax_account', '1', 'Create an iax account from signup ( default : yes ).', 1, 'yes,no', 'signup');
INSERT INTO cc_config VALUES (91, 'Activate Card', 'activatedbyuser', '0', 'active card after the new signup. if No, the Signup confirmation is needed and an email will be sent to the user with a link for activation (need to put the link into the Signup mail template).', 1, 'yes,no', 'signup');
INSERT INTO cc_config VALUES (92, 'Customer Interface URL', 'urlcustomerinterface', 'http://localhost/customer/', 'url of the customer interface to display after activation.', 0, NULL, 'signup');
INSERT INTO cc_config VALUES (93, 'Asterisk Reload', 'reload_asterisk_if_sipiax_created', '0', 'Define if you want to reload Asterisk when a SIP / IAX Friend is created at signup time.', 1, 'yes,no', 'signup');
INSERT INTO cc_config VALUES (94, 'Backup Path', 'backup_path', '/tmp', 'Path to store backup of database.', 0, NULL, 'backup');
INSERT INTO cc_config VALUES (95, 'GZIP Path', 'gzip_exe', '/bin/gzip', 'Path for gzip.', 0, NULL, 'backup');
INSERT INTO cc_config VALUES (96, 'GunZip Path', 'gunzip_exe', '/bin/gunzip', 'Path for gunzip .', 0, NULL, 'backup');
INSERT INTO cc_config VALUES (97, 'MySql Dump Path', 'mysqldump', '/usr/bin/mysqldump', 'path for mysqldump.', 0, NULL, 'backup');
INSERT INTO cc_config VALUES (98, 'PGSql Dump Path', 'pg_dump', '/usr/bin/pg_dump', 'path for pg_dump.', 0, NULL, 'backup');
INSERT INTO cc_config VALUES (99, 'MySql Path', 'mysql', '/usr/bin/mysql', 'Path for MySql.', 0, NULL, 'backup');
INSERT INTO cc_config VALUES (100, 'PSql Path', 'psql', '/usr/bin/psql', 'Path for PSql.', 0, NULL, 'backup');
INSERT INTO cc_config VALUES (101, 'SIP File Path', 'buddy_sip_file', '/etc/asterisk/additional_a2billing_sip.conf', 'Path to store the asterisk configuration files SIP.', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (102, 'IAX File Path', 'buddy_iax_file', '/etc/asterisk/additional_a2billing_iax.conf', 'Path to store the asterisk configuration files IAX.', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (103, 'API Security Key', 'api_security_key', 'Ae87v56zzl34v', 'API have a security key to validate the http request, the key has to be sent after applying md5, Valid characters are [a-z,A-Z,0-9].', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (104, 'Authorized IP', 'api_ip_auth', '127.0.0.1', 'API to restrict the IPs authorised to make a request, Define The the list of ips separated by '';''.', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (105, 'Admin Email', 'email_admin', 'root@localhost', 'Administative Email.', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (106, 'MOH Directory', 'dir_store_mohmp3', '/var/lib/asterisk/mohmp3', 'MOH (Music on Hold) base directory.', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (107, 'MOH Classes', 'num_musiconhold_class', '10', 'Number of MOH classes you have created in musiconhold.conf : acc_1, acc_2... acc_10 class	etc....', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (108, 'Display Help', 'show_help', '1', 'Display the help section inside the admin interface  (YES - NO).', 1, 'yes,no', 'webui');
INSERT INTO cc_config VALUES (109, 'Max File Upload Size', 'my_max_file_size_import', '1024000', 'File Upload parameters, PLEASE CHECK ALSO THE VALUE IN YOUR PHP.INI THE LIMIT IS 2MG BY DEFAULT .', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (110, 'Audio Directory Path', 'dir_store_audio', '/var/lib/asterisk/sounds/a2billing', 'Not used yet, The goal is to upload files and use them in the IVR.', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (111, 'Max Audio File Size', 'my_max_file_size_audio', '3072000', 'upload maximum file size.', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (112, 'Extensions Allowed', 'file_ext_allow', 'gsm, mp3, wav', 'File type extensions permitted to be uploaded such as "gsm, mp3, wav" (separated by ,).', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (113, 'Muzic Files Allowed', 'file_ext_allow_musiconhold', 'mp3', 'File type extensions permitted to be uploaded for the musiconhold such as "gsm, mp3, wav" (separate by ,).', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (114, 'Link Audio', 'link_audio_file', '0', 'Enable link on the CDR viewer to the recordings. (YES - NO).', 1, 'yes,no', 'webui');
INSERT INTO cc_config VALUES (115, 'Monitor Path', 'monitor_path', '/var/spool/asterisk/monitor', 'Path to link the recorded monitor files.', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (116, 'Monitor Format', 'monitor_formatfile', 'gsm', 'FORMAT OF THE RECORDED MONITOR FILE.', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (117, 'Invoice Icon', 'show_icon_invoice', '1', 'Display the icon in the invoice.', 1, 'yes,no', 'webui');
INSERT INTO cc_config VALUES (118, 'Show Top Frame', 'show_top_frame', '0', 'Display the top frame (useful if you want to save space on your little tiny screen ) .', 1, 'yes,no', 'webui');
INSERT INTO cc_config VALUES (119, 'Currency', 'currency_choose', 'usd, eur, cad, hkd', 'Allow the customer to chose the most appropriate currency ("all" can be used).', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (120, 'Card Export Fields', 'card_export_field_list', 'id, username, useralias, lastname, credit, tariff, activated, language, inuse, currency, sip_buddy, iax_buddy, nbused, mac_addr, template_invoice, template_outstanding', 'Fields to export in csv format from cc_card table.', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (121, 'Vouvher Export Fields', 'voucher_export_field_list', 'voucher, credit, tag, activated, usedcardnumber, usedate, currency', 'Field to export in csv format from cc_voucher table.', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (122, 'Advance Mode', 'advanced_mode', '0', 'Advanced mode - Display additional configuration options on the ratecard (progressive rates, musiconhold, ...).', 1, 'yes,no', 'webui');
INSERT INTO cc_config VALUES (123, 'SIP/IAX Delete', 'delete_fk_card', '1', 'Delete the SIP/IAX Friend & callerid when a card is deleted.', 1, 'yes,no', 'webui');
INSERT INTO cc_config VALUES (124, 'Type', 'type', 'friend', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO cc_config VALUES (125, 'Allow', 'allow', 'ulaw,alaw,gsm,g729', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO cc_config VALUES (126, 'Context', 'context', 'a2billing', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO cc_config VALUES (127, 'Nat', 'nat', 'yes', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO cc_config VALUES (128, 'AMA Flag', 'amaflag', 'billing', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO cc_config VALUES (129, 'Qualify', 'qualify', 'yes', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO cc_config VALUES (130, 'Host', 'host', 'dynamic', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO cc_config VALUES (131, 'DTMF Mode', 'dtmfmode', 'RFC2833', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO cc_config VALUES (132, 'Alarm Log File', 'cront_alarm', '/var/log/a2billing/cront_a2b_alarm.log', 'To disable application logging, remove/comment the log file name aside service.', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (133, 'Auto refill Log File', 'cront_autorefill', '/var/log/a2billing/cront_a2b_autorefill.log', 'To disable application logging, remove/comment the log file name aside service.', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (134, 'Bactch Process Log File', 'cront_batch_process', '/var/log/a2billing/cront_a2b_batch_process.log', 'To disable application logging, remove/comment the log file name aside service .', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (135, 'Archive Log File', 'cront_archive_data', '/var/log/a2billing/cront_a2b_archive_data.log', 'To disable application logging, remove/comment the log file name aside service .', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (136, 'DID Billing Log File', 'cront_bill_diduse', '/var/log/a2billing/cront_a2b_bill_diduse.log', 'To disable application logging, remove/comment the log file name aside service .', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (137, 'Subscription Fee Log File', 'cront_subscriptionfee', '/var/log/a2billing/cront_a2b_subscription_fee.log', 'To disable application logging, remove/comment the log file name aside service.', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (138, 'Currency Cront Log File', 'cront_currency_update', '/var/log/a2billing/cront_a2b_currency_update.log', 'To disable application logging, remove/comment the log file name aside service.', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (139, 'Invoice Cront Log File', 'cront_invoice', '/var/log/a2billing/cront_a2b_invoice.log', 'To disable application logging, remove/comment the log file name aside service.', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (140, 'Cornt Log File', 'cront_check_account', '/var/log/a2billing/cront_a2b_check_account.log', 'To disable application logging, remove/comment the log file name aside service .', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (141, 'Paypal Log File', 'paypal', '/var/log/a2billing/a2billing_paypal.log', 'paypal log file, to log all the transaction & error.', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (142, 'EPayment Log File', 'epayment', '/var/log/a2billing/a2billing_epayment.log', 'epayment log file, to log all the transaction & error .', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (143, 'ECommerce Log File', 'api_ecommerce', '/var/log/a2billing/a2billing_api_ecommerce_request.log', 'Log file to store the ecommerce API requests .', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (144, 'Callback Log File', 'api_callback', '/var/log/a2billing/a2billing_api_callback_request.log', 'Log file to store the CallBack API requests.', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (145, 'Webservice Card Log File', 'api_card', '/var/log/a2billing/a2billing_api_card.log', 'Log file to store the Card Webservice Logs', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (146, 'AGI Log File', 'agi', '/var/log/a2billing/a2billing_agi.log', 'File to log.', 0, NULL, 'log-files');
INSERT INTO cc_config VALUES (147, 'Description', 'description', 'agi-config', 'Description/notes field', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (148, 'Asterisk Version', 'asterisk_version', '1_4', 'Asterisk Version Information, 1_1,1_2,1_4 By Default it will take 1_2 or higher .', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (149, 'Answer Call', 'answer_call', '1', 'Manage the answer on the call. Disabling this for callback trigger numbers makes it ring not hang up.', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (150, 'Play Audio', 'play_audio', '1', 'Play audio - this will disable all stream file but not the Get Data , for wholesale ensure that the authentication works and than number_try = 1.', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (151, 'Say GoodBye', 'say_goodbye', '0', 'play the goodbye message when the user has finished.', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (152, 'Play Language Menu', 'play_menulanguage', '0', 'enable the menu to choose the language, press 1 for English, pulsa 2 para el español, Pressez 3 pour Français', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (153, 'Force Language', 'force_language', '', 'force the use of a language, if you dont want to use it leave the option empty, Values : ES, EN, FR, etc... (according to the audio you have installed).', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (154, 'Intro Prompt', 'intro_prompt', '', 'Introduction prompt : to specify an additional prompt to play at the beginning of the application .', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (155, 'Min Call Credit', 'min_credit_2call', '0', 'Minimum amount of credit to use the application .', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (156, 'Min Bill Duration', 'min_duration_2bill', '0', 'this is the minimum duration in seconds of a call in order to be billed any call with a length less than min_duration_2bill will have a 0 cost useful not to charge callers for system errors when a call was answered but it actually didn''t connect.', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (157, 'Not Enough Credit', 'notenoughcredit_cardnumber', '0', 'if user doesn''t have enough credit to call a destination, prompt him to enter another cardnumber .', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (158, 'New Caller ID', 'notenoughcredit_assign_newcardnumber_cid', '0', 'if notenoughcredit_cardnumber = YES  then	assign the CallerID to the new cardnumber.', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (159, 'Use DNID', 'use_dnid', '0', 'if YES it will use the DNID and try to dial out, without asking for the phonenumber to call.', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (160, 'Not Use DNID', 'no_auth_dnid', '2400,2300', 'list the dnid on which you want to avoid the use of the previous option "use_dnid" .', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (161, 'Try Count', 'number_try', '3', 'number of times the user can dial different number.', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (162, 'Force CallPlan', 'force_callplan_id', '', 'this will force to select a specific call plan by the Rate Engine.', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (163, 'Say Balance After Auth', 'say_balance_after_auth', '1', 'Play the balance to the user after the authentication (values : yes - no).', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (164, 'Say Balance After Call', 'say_balance_after_call', '0', 'Play the balance to the user after the call (values : yes - no).', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (165, 'Say Rate', 'say_rateinitial', '0', 'Play the initial cost of the route (values : yes - no)', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (166, 'Say Duration', 'say_timetocall', '1', 'Play the amount of time that the user can call (values : yes - no).', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (167, 'Auto Set CLID', 'auto_setcallerid', '1', 'enable the setup of the callerID number before the outbound is made, by default the user callerID value will be use.', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (168, 'Force CLID', 'force_callerid', '', 'If auto_setcallerid is enabled, the value of force_callerid will be set as CallerID.', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (169, 'CLID Sanitize', 'cid_sanitize', '0', 'If force_callerid is not set, then the following option ensures that CID is set to one of the card''s configured caller IDs or blank if none available.(NO - disable this feature, caller ID can be anything, CID - Caller ID must be one of the customers caller IDs, DID - Caller ID must be one of the customers DID nos, BOTH - Caller ID must be one of the above two items)', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (170, 'CLID Enable', 'cid_enable', '0', 'enable the callerid authentication if this option is active the CC system will check the CID of caller  .', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (171, 'Ask PIN', 'cid_askpincode_ifnot_callerid', '1', 'if the CID does not exist, then the caller will be prompt to enter his cardnumber .', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (172, 'FailOver LCR/LCD Prefix', 'failover_lc_prefix', '0', 'if we will failover for LCR/LCD prefix. For instance if you have 346 and 34 for if 346 fail it will try to outbound with 34 route.', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (173, 'Auto CLID', 'cid_auto_assign_card_to_cid', '1', 'if the callerID authentication is enable and the authentication fails then the user will be prompt to enter his cardnumber;this option will bound the cardnumber entered to the current callerID so that next call will be directly authenticate.', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (174, 'Auto Create Card', 'cid_auto_create_card', '0', 'if the callerID is captured on a2billing, this option will create automatically a new card and add the callerID to it.', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (175, 'Auto Create Card Length', 'cid_auto_create_card_len', '10', 'set the length of the card that will be auto create (ie, 10).', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (176, 'Auto Create Card Type', 'cid_auto_create_card_typepaid', 'POSTPAY', 'billing type of the new card( value : POSTPAY or PREPAY) .', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (177, 'Auto Create Card Credit', 'cid_auto_create_card_credit', '0', 'amount of credit of the new card.', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (178, 'Auto Create Card Limit', 'cid_auto_create_card_credit_limit', '1000', 'if postpay, define the credit limit for the card.', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (179, 'Auto Create Card TariffGroup', 'cid_auto_create_card_tariffgroup', '6', 'the tariffgroup to use for the new card (this is the ID that you can find on the admin web interface) .', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (180, 'Auto CLID Security', 'callerid_authentication_over_cardnumber', '0', 'to check callerID over the cardnumber authentication (to guard against spoofing).', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (181, 'SIP Call', 'sip_iax_friends', '0', 'enable the option to call sip/iax friend for free (values : YES - NO).', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (182, 'SIP Call Prefix', 'sip_iax_pstn_direct_call_prefix', '555', 'if SIP_IAX_FRIENDS is active, you can define a prefix for the dialed digits to call a pstn number .', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (183, 'Direct Call', 'sip_iax_pstn_direct_call', '0', 'this will enable a prompt to enter your destination number. if number start by sip_iax_pstn_direct_call_prefix we do directly a sip iax call, if not we do a normal call.', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (184, 'IVR Voucher Refill', 'ivr_voucher', '0', 'enable the option to refill card with voucher in IVR (values : YES - NO) .', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (185, 'IVR Voucher Prefix', 'ivr_voucher_prefix', '8', 'if ivr_voucher is active, you can define a prefix for the voucher number to refill your card, values : number - don''t forget to change prepaid-refill_card_with_voucher audio accordingly .', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (186, 'IVR Low Credit', 'jump_voucher_if_min_credit', '0', 'When the user credit are below the minimum credit to call min_credit jump directly to the voucher IVR menu  (values: YES - NO) .', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (188, 'SIP/IAX Dial Command Params', 'dialcommand_param_sipiax_friend', '|60|HiL(3600000:61000:30000)', 'by default (3600000  =  1HOUR MAX CALL).', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (189, 'Outbound Call', 'switchdialcommand', '0', 'Define the order to make the outbound call<br>YES -> SIP/dialedphonenumber@gateway_ip - NO  SIP/gateway_ip/dialedphonenumber<br>Both should work exactly the same but i experimented one case when gateway was supporting dialedphonenumber@gateway_ip, So in case of trouble, try it out.', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (190, 'Failover Retry Limit', 'failover_recursive_limit', '2', 'failover recursive search - define how many time we want to authorize the research of the failover trunk when a call fails (value : 0 - 20) .', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (191, 'Max Time', 'maxtime_tocall_negatif_free_route', '5400', 'This setting specifies an upper limit for the duration of a call to a destination for which the selling rate is less than or equal to 0.', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (192, 'Send Reminder', 'send_reminder', '0', 'Send a reminder email to the user when they are under min_credit_2call.', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (193, 'Record Call', 'record_call', '0', 'enable to monitor the call (to record all the conversations) value : YES - NO .', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (195, 'AGI Force Currency', 'agi_force_currency', '', 'Force to play the balance to the caller in a predefined currency, to use the currency set for by the customer leave this field empty.', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (196, 'Currency Associated', 'currency_association', 'usd:dollars,mxn:pesos,eur:euros,all:credit', 'Define all the audio (without file extensions) that you want to play according to currency (use , to separate, ie "usd:prepaid-dollar,mxn:pesos,eur:Euro,all:credit").', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (197, 'Minor Currency Associated', 'currency_association_minor', 'usd:prepaid-cents,eur:prepaid-cents,gbp:prepaid-pence,all:credit', 'Define all the audio (without file extensions) that you want to play according to minor currency (use , to separate, ie "usd:prepaid-cents,eur:prepaid-cents,gbp:prepaid-pence,all:credit").', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (198, 'File Enter Destination', 'file_conf_enter_destination', 'prepaid-enter-dest', 'Please enter the file name you want to play when we prompt the calling party to enter the destination number, file_conf_enter_destination = prepaid-enter-number-u-calling-1-or-011.', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (200, 'Bill Callback', 'callback_bill_1stleg_ifcall_notconnected', '1', 'Define if you want to bill the 1st leg on callback even if the call is not connected to the destination.', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (201, 'International prefixes', 'international_prefixes', '011,00,09,1', 'List the prefixes you want stripped off if the call plan requires it', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (202, 'Server GMT', 'server_GMT', 'GMT+10:00', 'Define the sever gmt time', 0, NULL, 'global');
INSERT INTO cc_config VALUES (203, 'Invoice Template Path', 'invoice_template_path', '../invoice/', 'gives invoice template path from default one', 0, NULL, 'global');
INSERT INTO cc_config VALUES (204, 'Outstanding Template Path', 'outstanding_template_path', '../outstanding/', 'gives outstanding template path from default one', 0, NULL, 'global');
INSERT INTO cc_config VALUES (205, 'Sales Template Path', 'sales_template_path', '../sales/', 'gives sales template path from default one', 0, NULL, 'global');
INSERT INTO cc_config VALUES (187, 'Dial Command Params', 'dialcommand_param', '|60|HRrL(%timeout%:61000:30000)', 'More information about the Dial : http://voip-info.org/wiki-Asterisk+cmd+dial<br>30 :  The timeout parameter is optional. If not specifed, the Dial command will wait indefinitely, exiting only when the originating channel hangs up, or all the dialed channels return a busy or error condition. Otherwise it specifies a maximum time, in seconds, that the Dial command is to wait for a channel to answer.<br>H: Allow the caller to hang up by dialing * <br>r: Generate a ringing tone for the calling party<br>R: Indicate ringing to the calling party when the called party indicates ringing, pass no audio until answered.<br>g: When the called party hangs up, exit to execute more commands in the current context. (new in 1.4)<br>i: Asterisk will ignore any forwarding (302 Redirect) requests received. Essential for DID usage to prevent fraud. (new in 1.4)<br>m: Provide Music on Hold to the calling party until the called channel answers.<br>L(x[:y][:z]): Limit the call to ''x'' ms, warning when ''y'' ms are left, repeated every ''z'' ms)<br>%timeout% tag is replaced by the calculated timeout according the credit & destination rate!.', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (206, 'Extra charge DIDs', 'extracharge_did', '1800,1900', 'Add extra per-minute charges to this comma-separated list of DNIDs; needs "extracharge_fee" and "extracharge_buyfee"', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (207, 'Extra charge DID fees', 'extracharge_fee', '0.05,0.15', 'Comma-separated list of extra sell-rate charges corresponding to the DIDs in "extracharge_did"', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (208, 'Extra charge DID buy fees', 'extracharge_buyfee', '0.04,0.13', 'Comma-separated list of extra buy-rate charges corresponding to the DIDs in "extracharge_did"', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (209, 'Support Modules', 'support', '1', 'Enable or Disable the module of support', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (210, 'List of possible values to notify', 'values_notifications', '10:20:50:100:500:1000', 'Possible values to choose when the user receive a notification. You can define a List e.g: 10:20:100.', 0, NULL, 'notifications');
INSERT INTO cc_config VALUES (211, 'Notifications Modules', 'notification', '1', 'Enable or Disable the module of notification for the customers', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (212, 'Notications Cron Module', 'cron_notifications', '1', 'Enable or Disable the cron module of notification for the customers. If it correctly configured in the crontab', 0, 'yes,no', 'notifications');
INSERT INTO cc_config VALUES (213, 'Notications Delay', 'delay_notifications', '1', 'Delay in number of days to send an other notification for the customers. If the value is 0, it will notify the user everytime the cront is running.', 0, NULL, 'notifications');
INSERT INTO cc_config VALUES (214, 'Payment Amount', 'purchase_amount_agent', '100:200:500:1000', 'define the different amount of purchase that would be available.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (215, 'Max Time For Unlimited Calls', 'maxtime_tounlimited_calls', '5400', 'For unlimited calls, limit the duration: amount in seconds .', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (216, 'Max Time For Free Calls', 'maxtime_tofree_calls', '5400', 'For free calls, limit the duration: amount in seconds .', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (217, 'CallPlan threshold Deck switch', 'callplan_deck_minute_threshold', '', 'CallPlan threshold Deck switch. <br/>This option will switch the user callplan from one call plan ID to and other Callplan ID
The parameters are as follow : <br/>
-- ID of the first callplan : called seconds needed to switch to the next CallplanID <br/>
-- ID of the second callplan : called seconds needed to switch to the next CallplanID <br/>
-- if not needed seconds are defined it will automatically switch to the next one <br/>
-- if defined we will sum the previous needed seconds and check if the caller had done at least the amount of calls necessary to go to the next step and have the amount of seconds needed<br/>
value example for callplan_deck_minute_threshold = 1:300, 2:60, 3', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (218, 'Payment Historique Modules', 'payment', '1', 'Enable or Disable the module of payment historique for the customers', 1, 'yes,no', 'webcustomerui');
INSERT INTO cc_config VALUES (219, 'Menu Language Order', 'conf_order_menulang', 'en:fr:es', 'Enter the list of languages authorized for the menu.Use the code language separate by a colon charactere e.g: en:es:fr', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (220, 'Disable annoucement the second of the times that the card can call', 'disable_announcement_seconds', '0', 'Desactived the annoucement of the seconds when there are more of one minutes (values : yes - no)', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config VALUES (221, 'Charge for the paypal extra fees', 'charge_paypal_fee', '0', 'Actived, if you want assum the fee of paypal and don''t apply it on the customer (values : yes - no)', 1, 'yes,no', 'epayment_method');
INSERT INTO cc_config VALUES (222, 'Cents Currency Associated', 'currency_cents_association', '', 'Define all the audio (without file extensions) that you want to play according to cents currency (use , to separate, ie "amd:lumas").By default the file used is "prepaid-cents" .Use plural to define the cents currency sound, but import two sounds but cents currency defined : ending by ''s'' and not ending by ''s'' (i.e. for lumas , add 2 files : ''lumas'' and ''luma'') ', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (223, 'Context Campaign''s Callback', 'context_campaign_callback', 'a2billing-campaign-callback', 'Context to use in Campaign of Callback', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (224, 'Default Context forward Campaign''s Callback ', 'default_context_campaign', 'campaign', 'Context to use by default to forward the call in Campaign of Callback', 0, NULL, 'callback');
INSERT INTO cc_config VALUES (226, 'Enable CDR local cache', 'cache_enabled', '0', 'If you want enabled the local cache to save the CDR in a SQLite Database.', 1, 'yes,no', 'global');
INSERT INTO cc_config VALUES (227, 'Path for the CDR cache file', 'cache_path', '/etc/asterisk/cache_a2billing', 'Defined the file that you want use for the CDR cache to save the CDR in a local SQLite database.', 0, NULL, 'global');
INSERT INTO cc_config VALUES (228, 'PNL Pay Phones', 'report_pnl_pay_phones', '(8887798764,0.02,0.06)', 'Info for PNL report. Must be in form "(number1,buycost,sellcost),(number2,buycost,sellcost)", number can be prefix, i.e 1800', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (229, 'PNL Toll Free Numbers', 'report_pnl_toll_free', '(6136864646,0.1,0),(6477249717,0.1,0)', 'Info for PNL report. must be in form "(number1,buycost,sellcost),(number2,buycost,sellcost)", number can be prefix, i.e 1800', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (230, 'Verbosity', 'verbosity_level', '0', '0 = FATAL; 1 = ERROR; WARN = 2 ; INFO = 3 ; DEBUG = 4', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (231, 'Logging', 'logging_level', '3', '0 = FATAL; 1 = ERROR; WARN = 2 ; INFO = 3 ; DEBUG = 4', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (232, 'Enable info module about customers', 'customer_info_enabled', 'LEFT', 'If you want enabled the info module customer and place it somewhere on the home page.', 0, 'NONE,LEFT,CENTER,RIGHT', 'dashboard');
INSERT INTO cc_config VALUES (233, 'Enable info module about refills', 'refill_info_enabled', 'CENTER', 'If you want enabled the info module refills and place it somewhere on the home page.', 0, 'NONE,LEFT,CENTER,RIGHT', 'dashboard');
INSERT INTO cc_config VALUES (234, 'Enable info module about payments', 'payment_info_enabled', 'CENTER', 'If you want enabled the info module payments and place it somewhere on the home page.', 0, 'NONE,LEFT,CENTER,RIGHT', 'dashboard');
INSERT INTO cc_config VALUES (235, 'Enable info module about calls', 'call_info_enabled', 'RIGHT', 'If you want enabled the info module calls and place it somewhere on the home page.', 0, 'NONE,LEFT,CENTER,RIGHT', 'dashboard');
INSERT INTO cc_config VALUES (236, 'PlugnPay Payment URL', 'plugnpay_payment_url', 'https://pay1.plugnpay.com/payment/pnpremote.cgi', 'Define here the URL of PlugnPay gateway.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (237, 'DIDX ID', 'didx_id', '708XXX', 'DIDX parameter : ID', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (238, 'DIDX PASS', 'didx_pass', 'XXXXXXXXXX', 'DIDX parameter : Password', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (239, 'DIDX MIN RATING', 'didx_min_rating', '0', 'DIDX parameter : min rating', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (240, 'DIDX RING TO', 'didx_ring_to', '0', 'DIDX parameter : ring to', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (225, 'Card Show Fields', 'card_show_field_list', 'id:,username:, useralias:, lastname:,id_group:, id_agent:,  credit:, tariff:, status:, language:, inuse:, currency:, sip_buddy:, iax_buddy:, nbused:,', 'Fields to show in Customer. Order is important. You can setup size of field using "fieldname:10%" notation or "fieldname:" for harcoded size,"fieldname" for autosize. <br/>You can use:<br/> id,username, useralias, lastname, id_group, id_agent,  credit, tariff, status, language, inuse, currency, sip_buddy, iax_buddy, nbused, firstname, email, discount, callerid, id_seria, serial', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (241, 'Card Serial Pad Length', 'card_serial_length', '7', 'Value of zero padding for serial. If this value set to 3 serial wil looks like 001', 0, NULL, 'webui');
INSERT INTO cc_config VALUES (242, 'Dial Balance reservation', 'dial_balance_reservation', '0.25', 'Credit to reserve from the balance when a call is made. This will prevent negative balance on huge peak.', 0, NULL, 'agi-conf1');
INSERT INTO cc_config VALUES (243, 'HTTP Server Agent', 'http_server_agent', 'http://www.call-labs.com', 'Set the Server Address of Agent Website, It should be empty for productive Servers.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (244, 'HTTPS Server Agent', 'https_server_agent', 'https://www.call-labs.com', 'https://localhost - Enter here your Secure Agents Server Address, should not be empty for productive servers.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (245, 'Server Agent IP/Domain', 'http_cookie_domain_agent', '26.63.165.200', 'Enter your Domain Name or IP Address for the Agents application, eg, 26.63.165.200.', 0, NULL, '5');
INSERT INTO cc_config VALUES (246, 'Secure Server Agent IP/Domain', 'https_cookie_domain_agent', '26.63.165.200', 'Enter your Secure server Domain Name or IP Address for the Agents application, eg, 26.63.165.200.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (247, 'Application Agent Path', 'http_cookie_path_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your server.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (248, 'Secure Application Agent Path', 'https_cookie_path_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your Secure Server.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (249, 'Application Agent Physical Path', 'dir_ws_http_catalog_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your server.', 0, NULL, 'epayment_method');
INSERT INTO cc_config VALUES (250, 'Secure Application Agent Physical Path', 'dir_ws_https_catalog_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your Secure server.', 0, NULL, 'epayment_method');


--
-- Data for Name: cc_config_group; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_config_group VALUES (1, 'global', 'This configuration group handles the global settings for application.');
INSERT INTO cc_config_group VALUES (2, 'callback', 'This configuration group handles calllback settings.');
INSERT INTO cc_config_group VALUES (3, 'webcustomerui', 'This configuration group handles Web Customer User Interface.');
INSERT INTO cc_config_group VALUES (4, 'sip-iax-info', 'SIP & IAX client configuration information.');
INSERT INTO cc_config_group VALUES (5, 'epayment_method', 'Epayment Methods Configuration.');
INSERT INTO cc_config_group VALUES (6, 'signup', 'This configuration group handles the signup related settings.');
INSERT INTO cc_config_group VALUES (7, 'backup', 'This configuration group handles the backup/restore related settings.');
INSERT INTO cc_config_group VALUES (8, 'webui', 'This configuration group handles the WEBUI and API Configuration.');
INSERT INTO cc_config_group VALUES (9, 'peer_friend', 'This configuration group define parameters for the friends creation.');
INSERT INTO cc_config_group VALUES (10, 'log-files', 'This configuration group handles the Log Files Directory Paths.');
INSERT INTO cc_config_group VALUES (11, 'agi-conf1', 'This configuration group handles the AGI Configuration.');
INSERT INTO cc_config_group VALUES (12, 'notifications', 'This configuration group handles the notifcations configuration');
INSERT INTO cc_config_group VALUES (13, 'dashboard', 'This configuration group handles the dashboard configuration');


--
-- Data for Name: cc_configuration; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_configuration VALUES (1, 'Login Username', 'MODULE_PAYMENT_AUTHORIZENET_LOGIN', 'testing', 'The login username used for the Authorize.net service', 0, NULL, NULL);
INSERT INTO cc_configuration VALUES (2, 'Transaction Key', 'MODULE_PAYMENT_AUTHORIZENET_TXNKEY', 'Test', 'Transaction Key used for encrypting TP data', 0, NULL, NULL);
INSERT INTO cc_configuration VALUES (3, 'Transaction Mode', 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE', 'Test', 'Transaction mode used for processing orders', 0, NULL, 'tep_cfg_select_option(array(''Test'', ''Production''), ');
INSERT INTO cc_configuration VALUES (4, 'Transaction Method', 'MODULE_PAYMENT_AUTHORIZENET_METHOD', 'Credit Card', 'Transaction method used for processing orders', 0, NULL, 'tep_cfg_select_option(array(''Credit Card'', ''eCheck''), ');
INSERT INTO cc_configuration VALUES (5, 'Customer Notifications', 'MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER', 'False', 'Should Authorize.Net e-mail a receipt to the customer?', 0, NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO cc_configuration VALUES (6, 'Enable Authorize.net Module', 'MODULE_PAYMENT_AUTHORIZENET_STATUS', 'True', 'Do you want to accept Authorize.net payments?', 0, NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO cc_configuration VALUES (7, 'Enable PayPal Module', 'MODULE_PAYMENT_PAYPAL_STATUS', 'True', 'Do you want to accept PayPal payments?', 0, NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO cc_configuration VALUES (8, 'E-Mail Address', 'MODULE_PAYMENT_PAYPAL_ID', 'you@yourbusiness.com', 'The e-mail address to use for the PayPal service', 0, NULL, NULL);
INSERT INTO cc_configuration VALUES (10, 'E-Mail Address', 'MODULE_PAYMENT_MONEYBOOKERS_ID', 'you@yourbusiness.com', 'The eMail address to use for the moneybookers service', 0, NULL, NULL);
INSERT INTO cc_configuration VALUES (11, 'Referral ID', 'MODULE_PAYMENT_MONEYBOOKERS_REFID', '989999', 'Your personal Referral ID from moneybookers.com', 0, NULL, NULL);
INSERT INTO cc_configuration VALUES (13, 'Transaction Language', 'MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE', 'Selected Language', 'The default language for the payment transactions', 0, NULL, 'tep_cfg_select_option(array(''Selected Language'',''EN'', ''DE'', ''ES'', ''FR''), ');
INSERT INTO cc_configuration VALUES (14, 'Enable moneybookers Module', 'MODULE_PAYMENT_MONEYBOOKERS_STATUS', 'True', 'Do you want to accept moneybookers payments?', 0, NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO cc_configuration VALUES (15, 'Enable PlugnPay Module', 'MODULE_PAYMENT_PLUGNPAY_STATUS', 'True', 'Do you want to accept payments through PlugnPay?', 0, NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO cc_configuration VALUES (16, 'Login Username', 'MODULE_PAYMENT_PLUGNPAY_LOGIN', 'Your Login Name', 'Enter your PlugnPay account username', 0, NULL, NULL);
INSERT INTO cc_configuration VALUES (17, 'Publisher Email', 'MODULE_PAYMENT_PLUGNPAY_PUBLISHER_EMAIL', 'Enter Your Email Address', 'The email address you want PlugnPay conformations sent to', 0, NULL, NULL);
INSERT INTO cc_configuration VALUES (18, 'cURL Setup', 'MODULE_PAYMENT_PLUGNPAY_CURL', 'Not Compiled', 'Whether cURL is compiled into PHP or not.  Windows users, select not compiled.', 0, NULL, 'tep_cfg_select_option(array(''Not Compiled'', ''Compiled''), ');
INSERT INTO cc_configuration VALUES (19, 'cURL Path', 'MODULE_PAYMENT_PLUGNPAY_CURL_PATH', 'The Path To cURL', 'For Not Compiled mode only, input path to the cURL binary (i.e. c:/curl/curl)', 0, NULL, NULL);
INSERT INTO cc_configuration VALUES (20, 'Transaction Mode', 'MODULE_PAYMENT_PLUGNPAY_TESTMODE', 'Test', 'Transaction mode used for processing orders', 0, NULL, 'tep_cfg_select_option(array(''Test'', ''Test And Debug'', ''Production''), ');
INSERT INTO cc_configuration VALUES (21, 'Require CVV', 'MODULE_PAYMENT_PLUGNPAY_CVV', 'yes', 'Ask For CVV information', 0, NULL, 'tep_cfg_select_option(array(''yes'', ''no''), ');
INSERT INTO cc_configuration VALUES (22, 'Transaction Method', 'MODULE_PAYMENT_PLUGNPAY_PAYMETHOD', 'credit', 'Transaction method used for processing orders.<br><b>NOTE:</b> Selecting ''onlinecheck'' assumes you''ll offer ''credit'' as well.', 0, NULL, 'tep_cfg_select_option(array(''credit'', ''onlinecheck''), ');
INSERT INTO cc_configuration VALUES (23, 'Authorization Type', 'MODULE_PAYMENT_PLUGNPAY_CCMODE', 'authpostauth', 'Credit card processing mode', 0, NULL, 'tep_cfg_select_option(array(''authpostauth'', ''authonly''), ');
INSERT INTO cc_configuration VALUES (24, 'Customer Notifications', 'MODULE_PAYMENT_PLUGNPAY_DONTSNDMAIL', 'yes', 'Should PlugnPay not email a receipt to the customer?', 0, NULL, 'tep_cfg_select_option(array(''yes'', ''no''), ');
INSERT INTO cc_configuration VALUES (25, 'Accepted Credit Cards', 'MODULE_PAYMENT_PLUGNPAY_ACCEPTED_CC', 'Mastercard, Visa', 'The credit cards you currently accept', 0, NULL, '_selectOptions(array(''Amex'',''Discover'', ''Mastercard'', ''Visa''), ');
INSERT INTO cc_configuration VALUES (9, 'Alternative Transaction Currency', 'MODULE_PAYMENT_PAYPAL_CURRENCY', 'Selected Currency', 'The alternative currency to use for credit card transactions if the system currency is not usable', 0, NULL, 'tep_cfg_select_option(array(''USD'',''CAD'',''EUR'',''GBP'',''JPY''), ');
INSERT INTO cc_configuration VALUES (12, 'Alternative Transaction Currency', 'MODULE_PAYMENT_MONEYBOOKERS_CURRENCY', 'Selected Currency', 'The alternative currency to use for credit card transactions if the system currency is not usable', 0, NULL, 'tep_cfg_select_option(array(''EUR'', ''USD'', ''GBP'', ''HKD'', ''SGD'', ''JPY'', ''CAD'', ''AUD'', ''CHF'', ''DKK'', ''SEK'', ''NOK'', ''ILS'', ''MYR'', ''NZD'', ''TWD'', ''THB'', ''CZK'', ''HUF'', ''SKK'', ''ISK'', ''INR''), ');


--
-- Data for Name: cc_country; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_country VALUES (1, 'AFG', '93', 'Afghanistan');
INSERT INTO cc_country VALUES (2, 'ALB', '355', 'Albania');
INSERT INTO cc_country VALUES (3, 'DZA', '213', 'Algeria');
INSERT INTO cc_country VALUES (4, 'ASM', '684', 'American Samoa');
INSERT INTO cc_country VALUES (5, 'AND', '376', 'Andorra');
INSERT INTO cc_country VALUES (6, 'AGO', '244', 'Angola');
INSERT INTO cc_country VALUES (7, 'AIA', '1264', 'Anguilla');
INSERT INTO cc_country VALUES (8, 'ATA', '672', 'Antarctica');
INSERT INTO cc_country VALUES (9, 'ATG', '1268', 'Antigua And Barbuda');
INSERT INTO cc_country VALUES (10, 'ARG', '54', 'Argentina');
INSERT INTO cc_country VALUES (11, 'ARM', '374', 'Armenia');
INSERT INTO cc_country VALUES (12, 'ABW', '297', 'Aruba');
INSERT INTO cc_country VALUES (13, 'AUS', '61', 'Australia');
INSERT INTO cc_country VALUES (14, 'AUT', '43', 'Austria');
INSERT INTO cc_country VALUES (15, 'AZE', '994', 'Azerbaijan');
INSERT INTO cc_country VALUES (16, 'BHS', '1242', 'Bahamas');
INSERT INTO cc_country VALUES (17, 'BHR', '973', 'Bahrain');
INSERT INTO cc_country VALUES (18, 'BGD', '880', 'Bangladesh');
INSERT INTO cc_country VALUES (19, 'BRB', '1246', 'Barbados');
INSERT INTO cc_country VALUES (20, 'BLR', '375', 'Belarus');
INSERT INTO cc_country VALUES (21, 'BEL', '32', 'Belgium');
INSERT INTO cc_country VALUES (22, 'BLZ', '501', 'Belize');
INSERT INTO cc_country VALUES (23, 'BEN', '229', 'Benin');
INSERT INTO cc_country VALUES (24, 'BMU', '1441', 'Bermuda');
INSERT INTO cc_country VALUES (25, 'BTN', '975', 'Bhutan');
INSERT INTO cc_country VALUES (26, 'BOL', '591', 'Bolivia');
INSERT INTO cc_country VALUES (27, 'BIH', '387', 'Bosnia And Herzegovina');
INSERT INTO cc_country VALUES (28, 'BWA', '267', 'Botswana');
INSERT INTO cc_country VALUES (29, 'BVT', '0', 'Bouvet Island');
INSERT INTO cc_country VALUES (30, 'BRA', '55', 'Brazil');
INSERT INTO cc_country VALUES (31, 'IOT', '1284', 'British Indian Ocean Territory');
INSERT INTO cc_country VALUES (32, 'BRN', '673', 'Brunei Darussalam');
INSERT INTO cc_country VALUES (33, 'BGR', '359', 'Bulgaria');
INSERT INTO cc_country VALUES (34, 'BFA', '226', 'Burkina Faso');
INSERT INTO cc_country VALUES (35, 'BDI', '257', 'Burundi');
INSERT INTO cc_country VALUES (36, 'KHM', '855', 'Cambodia');
INSERT INTO cc_country VALUES (37, 'CMR', '237', 'Cameroon');
INSERT INTO cc_country VALUES (38, 'CAN', '1', 'Canada');
INSERT INTO cc_country VALUES (39, 'CPV', '238', 'Cape Verde');
INSERT INTO cc_country VALUES (40, 'CYM', '1345', 'Cayman Islands');
INSERT INTO cc_country VALUES (41, 'CAF', '236', 'Central African Republic');
INSERT INTO cc_country VALUES (42, 'TCD', '235', 'Chad');
INSERT INTO cc_country VALUES (43, 'CHL', '56', 'Chile');
INSERT INTO cc_country VALUES (44, 'CHN', '86', 'China');
INSERT INTO cc_country VALUES (45, 'CXR', '618', 'Christmas Island');
INSERT INTO cc_country VALUES (46, 'CCK', '61', 'Cocos (Keeling); Islands');
INSERT INTO cc_country VALUES (47, 'COL', '57', 'Colombia');
INSERT INTO cc_country VALUES (48, 'COM', '269', 'Comoros');
INSERT INTO cc_country VALUES (49, 'COG', '242', 'Congo');
INSERT INTO cc_country VALUES (50, 'COD', '243', 'Congo, The Democratic Republic Of The');
INSERT INTO cc_country VALUES (51, 'COK', '682', 'Cook Islands');
INSERT INTO cc_country VALUES (52, 'CRI', '506', 'Costa Rica');
INSERT INTO cc_country VALUES (54, 'HRV', '385', 'Croatia');
INSERT INTO cc_country VALUES (55, 'CUB', '53', 'Cuba');
INSERT INTO cc_country VALUES (56, 'CYP', '357', 'Cyprus');
INSERT INTO cc_country VALUES (57, 'CZE', '420', 'Czech Republic');
INSERT INTO cc_country VALUES (58, 'DNK', '45', 'Denmark');
INSERT INTO cc_country VALUES (59, 'DJI', '253', 'Djibouti');
INSERT INTO cc_country VALUES (60, 'DMA', '1767', 'Dominica');
INSERT INTO cc_country VALUES (61, 'DOM', '1809', 'Dominican Republic');
INSERT INTO cc_country VALUES (62, 'ECU', '593', 'Ecuador');
INSERT INTO cc_country VALUES (63, 'EGY', '20', 'Egypt');
INSERT INTO cc_country VALUES (64, 'SLV', '503', 'El Salvador');
INSERT INTO cc_country VALUES (65, 'GNQ', '240', 'Equatorial Guinea');
INSERT INTO cc_country VALUES (66, 'ERI', '291', 'Eritrea');
INSERT INTO cc_country VALUES (67, 'EST', '372', 'Estonia');
INSERT INTO cc_country VALUES (68, 'ETH', '251', 'Ethiopia');
INSERT INTO cc_country VALUES (69, 'FLK', '500', 'Falkland Islands (Malvinas);');
INSERT INTO cc_country VALUES (70, 'FRO', '298', 'Faroe Islands');
INSERT INTO cc_country VALUES (71, 'FJI', '679', 'Fiji');
INSERT INTO cc_country VALUES (72, 'FIN', '358', 'Finland');
INSERT INTO cc_country VALUES (73, 'FRA', '33', 'France');
INSERT INTO cc_country VALUES (74, 'GUF', '596', 'French Guiana');
INSERT INTO cc_country VALUES (75, 'PYF', '594', 'French Polynesia');
INSERT INTO cc_country VALUES (76, 'ATF', '689', 'French Southern Territories');
INSERT INTO cc_country VALUES (77, 'GAB', '241', 'Gabon');
INSERT INTO cc_country VALUES (78, 'GMB', '220', 'Gambia');
INSERT INTO cc_country VALUES (79, 'GEO', '995', 'Georgia');
INSERT INTO cc_country VALUES (80, 'DEU', '49', 'Germany');
INSERT INTO cc_country VALUES (81, 'GHA', '233', 'Ghana');
INSERT INTO cc_country VALUES (82, 'GIB', '350', 'Gibraltar');
INSERT INTO cc_country VALUES (83, 'GRC', '30', 'Greece');
INSERT INTO cc_country VALUES (84, 'GRL', '299', 'Greenland');
INSERT INTO cc_country VALUES (85, 'GRD', '1473', 'Grenada');
INSERT INTO cc_country VALUES (86, 'GLP', '590', 'Guadeloupe');
INSERT INTO cc_country VALUES (87, 'GUM', '1671', 'Guam');
INSERT INTO cc_country VALUES (88, 'GTM', '502', 'Guatemala');
INSERT INTO cc_country VALUES (89, 'GIN', '224', 'Guinea');
INSERT INTO cc_country VALUES (90, 'GNB', '245', 'Guinea-Bissau');
INSERT INTO cc_country VALUES (91, 'GUY', '592', 'Guyana');
INSERT INTO cc_country VALUES (92, 'HTI', '509', 'Haiti');
INSERT INTO cc_country VALUES (93, 'HMD', '0', 'Heard Island And McDonald Islands');
INSERT INTO cc_country VALUES (94, 'VAT', '0', 'Holy See (Vatican City State);');
INSERT INTO cc_country VALUES (95, 'HND', '504', 'Honduras');
INSERT INTO cc_country VALUES (96, 'HKG', '852', 'Hong Kong');
INSERT INTO cc_country VALUES (97, 'HUN', '36', 'Hungary');
INSERT INTO cc_country VALUES (98, 'ISL', '354', 'Iceland');
INSERT INTO cc_country VALUES (99, 'IND', '91', 'India');
INSERT INTO cc_country VALUES (100, 'IDN', '62', 'Indonesia');
INSERT INTO cc_country VALUES (101, 'IRN', '98', 'Iran, Islamic Republic Of');
INSERT INTO cc_country VALUES (102, 'IRQ', '964', 'Iraq');
INSERT INTO cc_country VALUES (103, 'IRL', '353', 'Ireland');
INSERT INTO cc_country VALUES (104, 'ISR', '972', 'Israel');
INSERT INTO cc_country VALUES (105, 'ITA', '39', 'Italy');
INSERT INTO cc_country VALUES (106, 'JAM', '1876', 'Jamaica');
INSERT INTO cc_country VALUES (107, 'JPN', '81', 'Japan');
INSERT INTO cc_country VALUES (108, 'JOR', '962', 'Jordan');
INSERT INTO cc_country VALUES (109, 'KAZ', '7', 'Kazakhstan');
INSERT INTO cc_country VALUES (110, 'KEN', '254', 'Kenya');
INSERT INTO cc_country VALUES (111, 'KIR', '686', 'Kiribati');
INSERT INTO cc_country VALUES (112, 'PRK', '850', 'Korea, Democratic People''s Republic Of');
INSERT INTO cc_country VALUES (113, 'KOR', '82', 'Korea, Republic of');
INSERT INTO cc_country VALUES (114, 'KWT', '965', 'Kuwait');
INSERT INTO cc_country VALUES (115, 'KGZ', '996', 'Kyrgyzstan');
INSERT INTO cc_country VALUES (117, 'LVA', '371', 'Latvia');
INSERT INTO cc_country VALUES (118, 'LBN', '961', 'Lebanon');
INSERT INTO cc_country VALUES (119, 'LSO', '266', 'Lesotho');
INSERT INTO cc_country VALUES (120, 'LBR', '231', 'Liberia');
INSERT INTO cc_country VALUES (121, 'LBY', '218', 'Libyan Arab Jamahiriya');
INSERT INTO cc_country VALUES (122, 'LIE', '423', 'Liechtenstein');
INSERT INTO cc_country VALUES (123, 'LTU', '370', 'Lithuania');
INSERT INTO cc_country VALUES (124, 'LUX', '352', 'Luxembourg');
INSERT INTO cc_country VALUES (125, 'MAC', '853', 'Macao');
INSERT INTO cc_country VALUES (126, 'MKD', '389', 'Macedonia, The Former Yugoslav Republic Of');
INSERT INTO cc_country VALUES (127, 'MDG', '261', 'Madagascar');
INSERT INTO cc_country VALUES (128, 'MWI', '265', 'Malawi');
INSERT INTO cc_country VALUES (129, 'MYS', '60', 'Malaysia');
INSERT INTO cc_country VALUES (130, 'MDV', '960', 'Maldives');
INSERT INTO cc_country VALUES (131, 'MLI', '223', 'Mali');
INSERT INTO cc_country VALUES (132, 'MLT', '356', 'Malta');
INSERT INTO cc_country VALUES (133, 'MHL', '692', 'Marshall islands');
INSERT INTO cc_country VALUES (134, 'MTQ', '596', 'Martinique');
INSERT INTO cc_country VALUES (135, 'MRT', '222', 'Mauritania');
INSERT INTO cc_country VALUES (136, 'MUS', '230', 'Mauritius');
INSERT INTO cc_country VALUES (137, 'MYT', '269', 'Mayotte');
INSERT INTO cc_country VALUES (138, 'MEX', '52', 'Mexico');
INSERT INTO cc_country VALUES (139, 'FSM', '691', 'Micronesia, Federated States Of');
INSERT INTO cc_country VALUES (140, 'MDA', '1808', 'Moldova, Republic Of');
INSERT INTO cc_country VALUES (141, 'MCO', '377', 'Monaco');
INSERT INTO cc_country VALUES (142, 'MNG', '976', 'Mongolia');
INSERT INTO cc_country VALUES (143, 'MSR', '1664', 'Montserrat');
INSERT INTO cc_country VALUES (144, 'MAR', '212', 'Morocco');
INSERT INTO cc_country VALUES (145, 'MOZ', '258', 'Mozambique');
INSERT INTO cc_country VALUES (146, 'MMR', '95', 'Myanmar');
INSERT INTO cc_country VALUES (147, 'NAM', '264', 'Namibia');
INSERT INTO cc_country VALUES (148, 'NRU', '674', 'Nauru');
INSERT INTO cc_country VALUES (149, 'NPL', '977', 'Nepal');
INSERT INTO cc_country VALUES (150, 'NLD', '31', 'Netherlands');
INSERT INTO cc_country VALUES (151, 'ANT', '599', 'Netherlands Antilles');
INSERT INTO cc_country VALUES (152, 'NCL', '687', 'New Caledonia');
INSERT INTO cc_country VALUES (153, 'NZL', '64', 'New Zealand');
INSERT INTO cc_country VALUES (154, 'NIC', '505', 'Nicaragua');
INSERT INTO cc_country VALUES (155, 'NER', '227', 'Niger');
INSERT INTO cc_country VALUES (156, 'NGA', '234', 'Nigeria');
INSERT INTO cc_country VALUES (157, 'NIU', '683', 'Niue');
INSERT INTO cc_country VALUES (158, 'NFK', '672', 'Norfolk Island');
INSERT INTO cc_country VALUES (159, 'MNP', '1670', 'Northern Mariana Islands');
INSERT INTO cc_country VALUES (160, 'NOR', '47', 'Norway');
INSERT INTO cc_country VALUES (161, 'OMN', '968', 'Oman');
INSERT INTO cc_country VALUES (162, 'PAK', '92', 'Pakistan');
INSERT INTO cc_country VALUES (163, 'PLW', '680', 'Palau');
INSERT INTO cc_country VALUES (164, 'PSE', '970', 'Palestinian Territory, Occupied');
INSERT INTO cc_country VALUES (165, 'PAN', '507', 'Panama');
INSERT INTO cc_country VALUES (166, 'PNG', '675', 'Papua New Guinea');
INSERT INTO cc_country VALUES (167, 'PRY', '595', 'Paraguay');
INSERT INTO cc_country VALUES (168, 'PER', '51', 'Peru');
INSERT INTO cc_country VALUES (169, 'PHL', '63', 'Philippines');
INSERT INTO cc_country VALUES (170, 'PCN', '0', 'Pitcairn');
INSERT INTO cc_country VALUES (171, 'POL', '48', 'Poland');
INSERT INTO cc_country VALUES (172, 'PRT', '351', 'Portugal');
INSERT INTO cc_country VALUES (173, 'PRI', '1787', 'Puerto Rico');
INSERT INTO cc_country VALUES (174, 'QAT', '974', 'Qatar');
INSERT INTO cc_country VALUES (175, 'REU', '262', 'Reunion');
INSERT INTO cc_country VALUES (176, 'ROU', '40', 'Romania');
INSERT INTO cc_country VALUES (177, 'RUS', '7', 'Russian Federation');
INSERT INTO cc_country VALUES (178, 'RWA', '250', 'Rwanda');
INSERT INTO cc_country VALUES (179, 'SHN', '290', 'Saint Helena');
INSERT INTO cc_country VALUES (180, 'KNA', '1869', 'Saint Kitts And Nevis');
INSERT INTO cc_country VALUES (181, 'LCA', '1758', 'Saint Lucia');
INSERT INTO cc_country VALUES (182, 'SPM', '508', 'Saint Pierre And Miquelon');
INSERT INTO cc_country VALUES (183, 'VCT', '1784', 'Saint Vincent And The Grenadines');
INSERT INTO cc_country VALUES (184, 'WSM', '685', 'Samoa');
INSERT INTO cc_country VALUES (185, 'SMR', '378', 'San Marino');
INSERT INTO cc_country VALUES (186, 'STP', '239', 'São Tomé And Principe');
INSERT INTO cc_country VALUES (187, 'SAU', '966', 'Saudi Arabia');
INSERT INTO cc_country VALUES (188, 'SEN', '221', 'Senegal');
INSERT INTO cc_country VALUES (189, 'SYC', '248', 'Seychelles');
INSERT INTO cc_country VALUES (190, 'SLE', '232', 'Sierra Leone');
INSERT INTO cc_country VALUES (191, 'SGP', '65', 'Singapore');
INSERT INTO cc_country VALUES (192, 'SVK', '421', 'Slovakia');
INSERT INTO cc_country VALUES (193, 'SVN', '386', 'Slovenia');
INSERT INTO cc_country VALUES (194, 'SLB', '677', 'Solomon Islands');
INSERT INTO cc_country VALUES (195, 'SOM', '252', 'Somalia');
INSERT INTO cc_country VALUES (196, 'ZAF', '27', 'South Africa');
INSERT INTO cc_country VALUES (197, 'SGS', '0', 'South Georgia And The South Sandwich Islands');
INSERT INTO cc_country VALUES (198, 'ESP', '34', 'Spain');
INSERT INTO cc_country VALUES (199, 'LKA', '94', 'Sri Lanka');
INSERT INTO cc_country VALUES (200, 'SDN', '249', 'Sudan');
INSERT INTO cc_country VALUES (201, 'SUR', '597', 'Suriname');
INSERT INTO cc_country VALUES (202, 'SJM', '0', 'Svalbard and Jan Mayen');
INSERT INTO cc_country VALUES (203, 'SWZ', '268', 'Swaziland');
INSERT INTO cc_country VALUES (204, 'SWE', '46', 'Sweden');
INSERT INTO cc_country VALUES (205, 'CHE', '41', 'Switzerland');
INSERT INTO cc_country VALUES (206, 'SYR', '963', 'Syrian Arab Republic');
INSERT INTO cc_country VALUES (207, 'TWN', '886', 'Taiwan, Province Of China');
INSERT INTO cc_country VALUES (208, 'TJK', '992', 'Tajikistan');
INSERT INTO cc_country VALUES (209, 'TZA', '255', 'Tanzania, United Republic Of');
INSERT INTO cc_country VALUES (210, 'THA', '66', 'Thailand');
INSERT INTO cc_country VALUES (212, 'TGO', '228', 'Togo');
INSERT INTO cc_country VALUES (213, 'TKL', '690', 'Tokelau');
INSERT INTO cc_country VALUES (214, 'TON', '676', 'Tonga');
INSERT INTO cc_country VALUES (215, 'TTO', '1868', 'Trinidad And Tobago');
INSERT INTO cc_country VALUES (216, 'TUN', '216', 'Tunisia');
INSERT INTO cc_country VALUES (217, 'TUR', '90', 'Turkey');
INSERT INTO cc_country VALUES (218, 'TKM', '993', 'Turkmenistan');
INSERT INTO cc_country VALUES (219, 'TCA', '1649', 'Turks And Caicos Islands');
INSERT INTO cc_country VALUES (220, 'TUV', '688', 'Tuvalu');
INSERT INTO cc_country VALUES (221, 'UGA', '256', 'Uganda');
INSERT INTO cc_country VALUES (222, 'UKR', '380', 'Ukraine');
INSERT INTO cc_country VALUES (223, 'ARE', '971', 'United Arab Emirates');
INSERT INTO cc_country VALUES (224, 'GBR', '44', 'United Kingdom');
INSERT INTO cc_country VALUES (225, 'USA', '1', 'United States');
INSERT INTO cc_country VALUES (226, 'UMI', '0', 'United States Minor Outlying Islands');
INSERT INTO cc_country VALUES (227, 'URY', '598', 'Uruguay');
INSERT INTO cc_country VALUES (228, 'UZB', '998', 'Uzbekistan');
INSERT INTO cc_country VALUES (229, 'VUT', '678', 'Vanuatu');
INSERT INTO cc_country VALUES (230, 'VEN', '58', 'Venezuela');
INSERT INTO cc_country VALUES (231, 'VNM', '84', 'Vietnam');
INSERT INTO cc_country VALUES (232, 'VGB', '1284', 'Virgin Islands, British');
INSERT INTO cc_country VALUES (233, 'VIR', '808', 'Virgin Islands, U.S.');
INSERT INTO cc_country VALUES (234, 'WLF', '681', 'Wallis And Futuna');
INSERT INTO cc_country VALUES (235, 'ESH', '0', 'Western Sahara');
INSERT INTO cc_country VALUES (236, 'YEM', '967', 'Yemen');
INSERT INTO cc_country VALUES (237, 'YUG', '0', 'Yugoslavia');
INSERT INTO cc_country VALUES (238, 'ZMB', '260', 'Zambia');
INSERT INTO cc_country VALUES (239, 'ZWE', '263', 'Zimbabwe');
INSERT INTO cc_country VALUES (240, 'ASC', '0', 'Ascension Island');
INSERT INTO cc_country VALUES (241, 'DGA', '0', 'Diego Garcia');
INSERT INTO cc_country VALUES (242, 'XNM', '0', 'Inmarsat');
INSERT INTO cc_country VALUES (244, 'AK', '0', 'Alaska');
INSERT INTO cc_country VALUES (245, 'HI', '0', 'Hawaii');
INSERT INTO cc_country VALUES (53, 'CIV', '225', 'Côte d''Ivoire');
INSERT INTO cc_country VALUES (246, 'ALA', '35818', 'Aland Islands');
INSERT INTO cc_country VALUES (247, 'BLM', '0', 'Saint Barthelemy');
INSERT INTO cc_country VALUES (248, 'GGY', '441481', 'Guernsey');
INSERT INTO cc_country VALUES (249, 'IMN', '441624', 'Isle of Man');
INSERT INTO cc_country VALUES (250, 'JEY', '441534', 'Jersey');
INSERT INTO cc_country VALUES (251, 'MAF', '0', 'Saint Martin');
INSERT INTO cc_country VALUES (252, 'MNE', '382', 'Montenegro, Republic of');
INSERT INTO cc_country VALUES (253, 'SRB', '381', 'Serbia, Republic of');
INSERT INTO cc_country VALUES (254, 'CPT', '0', 'Clipperton Island');
INSERT INTO cc_country VALUES (211, 'TLS', '670', 'Timor-Leste');
INSERT INTO cc_country VALUES (255, 'TAA', '0', 'Tristan da Cunha');
INSERT INTO cc_country VALUES (116, 'LAO', '856', 'Lao People''s Democratic Republic');
INSERT INTO cc_country VALUES (243, 'TMP', '0', 'East timor');


--
-- Data for Name: cc_currencies; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_currencies VALUES (1, 'ALL', 'Albanian Lek (ALL)', 0.00974, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (2, 'DZD', 'Algerian Dinar (DZD)', 0.01345, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (3, 'XAL', 'Aluminium Ounces (XAL)', 1.08295, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (4, 'ARS', 'Argentine Peso (ARS)', 0.32455, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (5, 'AWG', 'Aruba Florin (AWG)', 0.55866, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (6, 'AUD', 'Australian Dollar (AUD)', 0.73384, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (7, 'BSD', 'Bahamian Dollar (BSD)', 1.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (8, 'BHD', 'Bahraini Dinar (BHD)', 2.65322, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (9, 'BDT', 'Bangladesh Taka (BDT)', 0.01467, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (10, 'BBD', 'Barbados Dollar (BBD)', 0.50000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (11, 'BYR', 'Belarus Ruble (BYR)', 0.00046, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (12, 'BZD', 'Belize Dollar (BZD)', 0.50569, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (13, 'BMD', 'Bermuda Dollar (BMD)', 1.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (14, 'BTN', 'Bhutan Ngultrum (BTN)', 0.02186, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (15, 'BOB', 'Bolivian Boliviano (BOB)', 0.12500, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (16, 'BRL', 'Brazilian Real (BRL)', 0.46030, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (17, 'GBP', 'British Pound (GBP)', 1.73702, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (18, 'BND', 'Brunei Dollar (BND)', 0.61290, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (19, 'BGN', 'Bulgarian Lev (BGN)', 0.60927, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (20, 'BIF', 'Burundi Franc (BIF)', 0.00103, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (21, 'KHR', 'Cambodia Riel (KHR)', 0.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (22, 'CAD', 'Canadian Dollar (CAD)', 0.86386, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (23, 'KYD', 'Cayman Islands Dollar (KYD)', 1.16496, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (24, 'XOF', 'CFA Franc (BCEAO) (XOF)', 0.00182, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (25, 'XAF', 'CFA Franc (BEAC) (XAF)', 0.00182, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (26, 'CLP', 'Chilean Peso (CLP)', 0.00187, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (27, 'CNY', 'Chinese Yuan (CNY)', 0.12425, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (28, 'COP', 'Colombian Peso (COP)', 0.00044, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (29, 'KMF', 'Comoros Franc (KMF)', 0.00242, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (30, 'XCP', 'Copper Ounces (XCP)', 2.16403, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (31, 'CRC', 'Costa Rica Colon (CRC)', 0.00199, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (32, 'HRK', 'Croatian Kuna (HRK)', 0.16249, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (33, 'CUP', 'Cuban Peso (CUP)', 1.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (34, 'CYP', 'Cyprus Pound (CYP)', 2.07426, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (35, 'CZK', 'Czech Koruna (CZK)', 0.04133, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (36, 'DKK', 'Danish Krone (DKK)', 0.15982, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (37, 'DJF', 'Dijibouti Franc (DJF)', 0.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (38, 'DOP', 'Dominican Peso (DOP)', 0.03035, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (39, 'XCD', 'East Caribbean Dollar (XCD)', 0.37037, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (40, 'ECS', 'Ecuador Sucre (ECS)', 0.00004, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (41, 'EGP', 'Egyptian Pound (EGP)', 0.17433, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (42, 'SVC', 'El Salvador Colon (SVC)', 0.11426, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (43, 'ERN', 'Eritrea Nakfa (ERN)', 0.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (44, 'EEK', 'Estonian Kroon (EEK)', 0.07615, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (45, 'ETB', 'Ethiopian Birr (ETB)', 0.11456, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (46, 'EUR', 'Euro (EUR)', 1.19175, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (47, 'FKP', 'Falkland Islands Pound (FKP)', 0.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (48, 'GMD', 'Gambian Dalasi (GMD)', 0.03515, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (49, 'GHC', 'Ghanian Cedi (GHC)', 0.00011, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (50, 'GIP', 'Gibraltar Pound (GIP)', 0.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (51, 'XAU', 'Gold Ounces (XAU)', 555.55556, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (52, 'GTQ', 'Guatemala Quetzal (GTQ)', 0.13103, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (53, 'GNF', 'Guinea Franc (GNF)', 0.00022, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (54, 'HTG', 'Haiti Gourde (HTG)', 0.02387, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (55, 'HNL', 'Honduras Lempira (HNL)', 0.05292, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (56, 'HKD', 'Hong Kong Dollar (HKD)', 0.12884, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (57, 'HUF', 'Hungarian Forint (HUF)', 0.00461, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (58, 'ISK', 'Iceland Krona (ISK)', 0.01436, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (59, 'INR', 'Indian Rupee (INR)', 0.02253, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (60, 'IDR', 'Indonesian Rupiah (IDR)', 0.00011, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (61, 'IRR', 'Iran Rial (IRR)', 0.00011, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (62, 'ILS', 'Israeli Shekel (ILS)', 0.21192, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (63, 'JMD', 'Jamaican Dollar (JMD)', 0.01536, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (64, 'JPY', 'Japanese Yen (JPY)', 0.00849, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (65, 'JOD', 'Jordanian Dinar (JOD)', 1.41044, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (66, 'KZT', 'Kazakhstan Tenge (KZT)', 0.00773, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (67, 'KES', 'Kenyan Shilling (KES)', 0.01392, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (68, 'KRW', 'Korean Won (KRW)', 0.00102, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (69, 'KWD', 'Kuwaiti Dinar (KWD)', 3.42349, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (70, 'LAK', 'Lao Kip (LAK)', 0.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (71, 'LVL', 'Latvian Lat (LVL)', 1.71233, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (72, 'LBP', 'Lebanese Pound (LBP)', 0.00067, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (73, 'LSL', 'Lesotho Loti (LSL)', 0.15817, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (74, 'LYD', 'Libyan Dinar (LYD)', 0.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (75, 'LTL', 'Lithuanian Lita (LTL)', 0.34510, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (76, 'MOP', 'Macau Pataca (MOP)', 0.12509, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (77, 'MKD', 'Macedonian Denar (MKD)', 0.01945, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (78, 'MGF', 'Malagasy Franc (MGF)', 0.00011, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (79, 'MWK', 'Malawi Kwacha (MWK)', 0.00752, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (80, 'MYR', 'Malaysian Ringgit (MYR)', 0.26889, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (81, 'MVR', 'Maldives Rufiyaa (MVR)', 0.07813, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (82, 'MTL', 'Maltese Lira (MTL)', 2.77546, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (83, 'MRO', 'Mauritania Ougulya (MRO)', 0.00369, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (84, 'MUR', 'Mauritius Rupee (MUR)', 0.03258, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (85, 'MXN', 'Mexican Peso (MXN)', 0.09320, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (86, 'MDL', 'Moldovan Leu (MDL)', 0.07678, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (87, 'MNT', 'Mongolian Tugrik (MNT)', 0.00084, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (88, 'MAD', 'Moroccan Dirham (MAD)', 0.10897, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (89, 'MZM', 'Mozambique Metical (MZM)', 0.00004, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (90, 'NAD', 'Namibian Dollar (NAD)', 0.15817, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (91, 'NPR', 'Nepalese Rupee (NPR)', 0.01408, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (92, 'ANG', 'Neth Antilles Guilder (ANG)', 0.55866, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (93, 'TRY', 'New Turkish Lira (TRY)', 0.73621, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (94, 'NZD', 'New Zealand Dollar (NZD)', 0.65096, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (95, 'NIO', 'Nicaragua Cordoba (NIO)', 0.05828, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (96, 'NGN', 'Nigerian Naira (NGN)', 0.00777, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (97, 'NOK', 'Norwegian Krone (NOK)', 0.14867, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (98, 'OMR', 'Omani Rial (OMR)', 2.59740, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (99, 'XPF', 'Pacific Franc (XPF)', 0.00999, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (100, 'PKR', 'Pakistani Rupee (PKR)', 0.01667, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (101, 'XPD', 'Palladium Ounces (XPD)', 277.77778, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (102, 'PAB', 'Panama Balboa (PAB)', 1.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (103, 'PGK', 'Papua New Guinea Kina (PGK)', 0.33125, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (104, 'PYG', 'Paraguayan Guarani (PYG)', 0.00017, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (105, 'PEN', 'Peruvian Nuevo Sol (PEN)', 0.29999, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (106, 'PHP', 'Philippine Peso (PHP)', 0.01945, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (107, 'XPT', 'Platinum Ounces (XPT)', 1000.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (108, 'PLN', 'Polish Zloty (PLN)', 0.30574, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (109, 'QAR', 'Qatar Rial (QAR)', 0.27476, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (110, 'ROL', 'Romanian Leu (ROL)', 0.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (111, 'RON', 'Romanian New Leu (RON)', 0.34074, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (112, 'RUB', 'Russian Rouble (RUB)', 0.03563, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (113, 'RWF', 'Rwanda Franc (RWF)', 0.00185, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (114, 'WST', 'Samoa Tala (WST)', 0.35492, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (115, 'STD', 'Sao Tome Dobra (STD)', 0.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (116, 'SAR', 'Saudi Arabian Riyal (SAR)', 0.26665, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (117, 'SCR', 'Seychelles Rupee (SCR)', 0.18114, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (118, 'SLL', 'Sierra Leone Leone (SLL)', 0.00034, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (119, 'XAG', 'Silver Ounces (XAG)', 9.77517, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (120, 'SGD', 'Singapore Dollar (SGD)', 0.61290, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (121, 'SKK', 'Slovak Koruna (SKK)', 0.03157, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (122, 'SIT', 'Slovenian Tolar (SIT)', 0.00498, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (123, 'SOS', 'Somali Shilling (SOS)', 0.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (124, 'ZAR', 'South African Rand (ZAR)', 0.15835, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (125, 'LKR', 'Sri Lanka Rupee (LKR)', 0.00974, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (126, 'SHP', 'St Helena Pound (SHP)', 0.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (127, 'SDD', 'Sudanese Dinar (SDD)', 0.00427, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (128, 'SRG', 'Surinam Guilder (SRG)', 0.36496, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (129, 'SZL', 'Swaziland Lilageni (SZL)', 0.15817, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (130, 'SEK', 'Swedish Krona (SEK)', 0.12609, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (131, 'CHF', 'Swiss Franc (CHF)', 0.76435, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (132, 'SYP', 'Syrian Pound (SYP)', 0.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (133, 'TWD', 'Taiwan Dollar (TWD)', 0.03075, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (134, 'TZS', 'Tanzanian Shilling (TZS)', 0.00083, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (135, 'THB', 'Thai Baht (THB)', 0.02546, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (136, 'TOP', 'Tonga Paanga (TOP)', 0.48244, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (137, 'TTD', 'Trinidad&Tobago Dollar (TTD)', 0.15863, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (138, 'TND', 'Tunisian Dinar (TND)', 0.73470, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (139, 'USD', 'U.S. Dollar (USD)', 1.00000, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (140, 'AED', 'UAE Dirham (AED)', 0.27228, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (141, 'UGX', 'Ugandan Shilling (UGX)', 0.00055, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (142, 'UAH', 'Ukraine Hryvnia (UAH)', 0.19755, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (143, 'UYU', 'Uruguayan New Peso (UYU)', 0.04119, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (144, 'VUV', 'Vanuatu Vatu (VUV)', 0.00870, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (145, 'VEB', 'Venezuelan Bolivar (VEB)', 0.00037, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (146, 'VND', 'Vietnam Dong (VND)', 0.00006, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (147, 'YER', 'Yemen Riyal (YER)', 0.00510, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (148, 'ZMK', 'Zambian Kwacha (ZMK)', 0.00031, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (149, 'ZWD', 'Zimbabwe Dollar (ZWD)', 0.00001, '2009-04-02 16:05:53.530259', 'USD');
INSERT INTO cc_currencies VALUES (150, 'GYD', 'Guyana Dollar (GYD)', 0.00527, '2009-04-02 16:05:53.530259', 'USD');


--
-- Data for Name: cc_did; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_did_destination; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_did_use; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_didgroup; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_ecommerce_product; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_epayment_log; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_epayment_log_agent; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_iax_buddies; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_invoice; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_invoice_conf; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_invoice_conf VALUES (1, 'company_name', 'My company');
INSERT INTO cc_invoice_conf VALUES (2, 'address', 'address');
INSERT INTO cc_invoice_conf VALUES (3, 'zipcode', 'xxxx');
INSERT INTO cc_invoice_conf VALUES (4, 'country', 'country');
INSERT INTO cc_invoice_conf VALUES (5, 'city', 'city');
INSERT INTO cc_invoice_conf VALUES (6, 'phone', 'xxxxxxxxxxx');
INSERT INTO cc_invoice_conf VALUES (7, 'fax', 'xxxxxxxxxxx');
INSERT INTO cc_invoice_conf VALUES (8, 'email', 'xxxxxxx@xxxxxxx.xxx');
INSERT INTO cc_invoice_conf VALUES (9, 'vat', 'xxxxxxxxxx');
INSERT INTO cc_invoice_conf VALUES (10, 'web', 'www.xxxxxxx.xxx');


--
-- Data for Name: cc_invoice_item; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_invoice_payment; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_iso639; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_iso639 VALUES ('ab', 'Abkhazian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('om', 'Afan (Oromo)    ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('aa', 'Afar            ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('af', 'Afrikaans       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('sq', 'Albanian        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('am', 'Amharic         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ar', 'Arabic          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('hy', 'Armenian        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('as', 'Assamese        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ay', 'Aymara          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('az', 'Azerbaijani     ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ba', 'Bashkir         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('eu', 'Basque          ', 'Euskera         ', 'ISO-8859-15     ');
INSERT INTO cc_iso639 VALUES ('bn', 'Bengali Bangla  ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('dz', 'Bhutani         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('bh', 'Bihari          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('bi', 'Bislama         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('br', 'Breton          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('bg', 'Bulgarian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('my', 'Burmese         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('be', 'Byelorussian    ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('km', 'Cambodian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ca', 'Catalan         ', '          		    ', 'ISO-8859-15     ');
INSERT INTO cc_iso639 VALUES ('zh', 'Chinese         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('co', 'Corsican        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('hr', 'Croatian        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('cs', 'Czech           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('da', 'Danish          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('nl', 'Dutch           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('en', 'English         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('eo', 'Esperanto       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('et', 'Estonian        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('fo', 'Faroese         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('fj', 'Fiji            ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('fi', 'Finnish         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('fr', 'French          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('fy', 'Frisian         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('gl', 'Galician        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ka', 'Georgian        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('de', 'German          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('el', 'Greek           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('kl', 'Greenlandic     ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('gn', 'Guarani         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('gu', 'Gujarati        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ha', 'Hausa           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('he', 'Hebrew          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('hi', 'Hindi           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('hu', 'Hungarian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('is', 'Icelandic       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('id', 'Indonesian      ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ia', 'Interlingua     ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ie', 'Interlingue     ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('iu', 'Inuktitut       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ik', 'Inupiak         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ga', 'Irish           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('it', 'Italian         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ja', 'Japanese        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('jv', 'Javanese        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('kn', 'Kannada         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ks', 'Kashmiri        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('kk', 'Kazakh          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('rw', 'Kinyarwanda     ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ky', 'Kirghiz         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('rn', 'Kurundi         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ko', 'Korean          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ku', 'Kurdish         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('lo', 'Laothian        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('la', 'Latin           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('lv', 'Latvian Lettish ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ln', 'Lingala         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('lt', 'Lithuanian      ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('mk', 'Macedonian      ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('mg', 'Malagasy        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ms', 'Malay           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ml', 'Malayalam       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('mt', 'Maltese         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('mi', 'Maori           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('mr', 'Marathi         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('mo', 'Moldavian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('mn', 'Mongolian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('na', 'Nauru           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ne', 'Nepali          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('no', 'Norwegian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('oc', 'Occitan         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('or', 'Oriya           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ps', 'Pashto Pushto   ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('fa', 'Persian (Farsi) ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('pl', 'Polish          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('pt', 'Portuguese      ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('pa', 'Punjabi         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('qu', 'Quechua         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('rm', 'Rhaeto-Romance  ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ro', 'Romanian        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ru', 'Russian         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('sm', 'Samoan          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('sg', 'Sangho          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('sa', 'Sanskrit        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('gd', 'Scots Gaelic    ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('sr', 'Serbian         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('sh', 'Serbo-Croatian  ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('st', 'Sesotho         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('tn', 'Setswana        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('sn', 'Shona           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('sd', 'Sindhi          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('si', 'Singhalese      ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ss', 'Siswati         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('sk', 'Slovak          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('sl', 'Slovenian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('so', 'Somali          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('es', 'Spanish         ', '         		     ', 'ISO-8859-15     ');
INSERT INTO cc_iso639 VALUES ('su', 'Sundanese       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('sw', 'Swahili         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('sv', 'Swedish         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('tl', 'Tagalog         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('tg', 'Tajik           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ta', 'Tamil           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('tt', 'Tatar           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('te', 'Telugu          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('th', 'Thai            ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('bo', 'Tibetan         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ti', 'Tigrinya        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('to', 'Tonga           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ts', 'Tsonga          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('tr', 'Turkish         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('tk', 'Turkmen         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('tw', 'Twi             ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ug', 'Uigur           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('uk', 'Ukrainian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('ur', 'Urdu            ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('uz', 'Uzbek           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('vi', 'Vietnamese      ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('vo', 'Volapuk         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('cy', 'Welsh           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('wo', 'Wolof           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('xh', 'Xhosa           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('yi', 'Yiddish         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('yo', 'Yoruba          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('za', 'Zhuang          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 VALUES ('zu', 'Zulu            ', '                ', 'ISO-8859-1      ');


--
-- Data for Name: cc_logpayment; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_logpayment_agent; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_logrefill; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_logrefill_agent; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_notification; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_notification_admin; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_outbound_cid_group; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_outbound_cid_list; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_package_group; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_package_offer; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_package_rate; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_packgroup_package; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_payment_methods; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_payment_methods VALUES (1, 'paypal', 'paypal.php');
INSERT INTO cc_payment_methods VALUES (2, 'Authorize.Net', 'authorizenet.php');
INSERT INTO cc_payment_methods VALUES (3, 'MoneyBookers', 'moneybookers.php');
INSERT INTO cc_payment_methods VALUES (4, 'plugnpay', 'plugnpay.php');


--
-- Data for Name: cc_payments; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_payments_agent; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_payments_status; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_payments_status VALUES (1, -2, 'Failed');
INSERT INTO cc_payments_status VALUES (2, -1, 'Denied');
INSERT INTO cc_payments_status VALUES (3, 0, 'Pending');
INSERT INTO cc_payments_status VALUES (4, 1, 'In-Progress');
INSERT INTO cc_payments_status VALUES (5, 2, 'Completed');
INSERT INTO cc_payments_status VALUES (6, 3, 'Processed');
INSERT INTO cc_payments_status VALUES (7, 4, 'Refunded');
INSERT INTO cc_payments_status VALUES (8, 5, 'Unknown');


--
-- Data for Name: cc_paypal; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_phonebook; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_phonenumber; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_prefix; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_provider; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_ratecard; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_receipt; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_receipt_item; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_restricted_phonenumber; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_server_group; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_server_group VALUES (1, 'default', 'default group of server');


--
-- Data for Name: cc_server_manager; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_server_manager VALUES (1, 1, 'localhost', 'localhost', 'myasterisk', 'mycode', '2009-04-02 16:05:53.530259');


--
-- Data for Name: cc_service; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_service_report; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_sip_buddies; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_speeddial; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_status_log; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_subscription_fee; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_support; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_support VALUES (1, 'DEFAULT');


--
-- Data for Name: cc_support_component; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_support_component VALUES (1, 1, 'DEFAULT', 1);


--
-- Data for Name: cc_system_log; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_tariffgroup; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_tariffgroup_plan; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_tariffplan; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_templatemail; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_templatemail VALUES ('signup', 'info@call-labs.com', 'Call-Labs', 'SIGNUP CONFIRMATION', '
Thank you for registering with us
Please click on below link to activate your account.

http://call-labs.com/A2Billing_UI/signup/activate.php?key$loginkey

Please make sure you active your account by making payment to us either by
credit card, wire transfer, money order, cheque, and western union money
transfer, money Gram, and Pay pal.


Kind regards,
Call Labs
', '', 1, 'en');
INSERT INTO cc_templatemail VALUES ('epaymentverify', 'info@call-labs.com', 'Call-Labs', 'Epayment Gateway Security Verification Failed', 'Dear Administrator

Please check the Epayment Log, System has logged a Epayment Security failure. that may be a possible attack on epayment processing.

Time of Transaction: $time
Payment Gateway: $paymentgateway
Amount: $amount



Kind regards,
Call Labs
', '', 2, 'en');
INSERT INTO cc_templatemail VALUES ('forgetpassword', 'info@call-labs.com', 'Call-Labs', 'Login Information', 'Your login information is as below:

Your account is $card_gen

Your password is $password

Your cardalias is $cardalias

http://call-labs.com/A2BCustomer_UI/


Kind regards,
Call Labs
', '', 4, 'en');
INSERT INTO cc_templatemail VALUES ('signupconfirmed', 'info@call-labs.com', 'Call-Labs', 'SIGNUP CONFIRMATION', 'Thank you for registering with us

Please make sure you active your account by making payment to us either by
credit card, wire transfer, money order, cheque, and western union money
transfer, money Gram, and Pay pal.

Your account is $card_gen

Your password is $password

To go to your account :
http://call-labs.com/A2BCustomer_UI/

Kind regards,
Call Labs
', '', 5, 'en');
INSERT INTO cc_templatemail VALUES ('payment', 'info@call-labs.com', 'Call-Labs', 'PAYMENT CONFIRMATION', 'Thank you for shopping at Call-Labs.

Shopping details is as below.

Item Name = <b>$itemName</b>
Item ID = <b>$itemID</b>
Amount = <b>$itemAmount</b>
Payment Method = <b>$paymentMethod</b>
Status = <b>$paymentStatus</b>


Kind regards,
Call Labs
', '', 6, 'en');
INSERT INTO cc_templatemail VALUES ('invoice', 'info@call-labs.com', 'Call-Labs', 'A2BILLING INVOICE', 'Dear Customer.

Attached is the invoice.

Kind regards,
Call Labs
', '', 7, 'en');
INSERT INTO cc_templatemail VALUES ('reminder', 'info@call-labs.com', 'Call-Labs', 'Your Call-Labs account $cardnumber is low on credit ($currency $credit_currency)', '

Your Call-Labs Account number $cardnumber is running low on credit.

There is currently only $credit_currency $currency left on your account which is lower than the warning level defined ($credit_notification)


Please top up your account ASAP to ensure continued service

If you no longer wish to receive these notifications or would like to change the balance amount at which these warnings are generated,
please connect on your myaccount panel and change the appropriate parameters


your account information :
Your account number for VOIP authentication : $cardnumber

http://myaccount.call-labs.com/
Your account login : $cardalias
Your account password : $password


Thanks,
/Call-Labs Team
-------------------------------------
http://www.call-labs.com
 ', '', 3, 'en');


--
-- Data for Name: cc_ticket; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_ticket_comment; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: cc_timezone; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_timezone VALUES (1, '(GMT-12:00) International Date Line West', 'GMT-12:00', -43200);
INSERT INTO cc_timezone VALUES (2, '(GMT-11:00) Midway Island, Samoa', 'GMT-11:00', -39600);
INSERT INTO cc_timezone VALUES (3, '(GMT-10:00) Hawaii', 'GMT-10:00', -36000);
INSERT INTO cc_timezone VALUES (4, '(GMT-09:00) Alaska', 'GMT-09:00', -32400);
INSERT INTO cc_timezone VALUES (5, '(GMT-08:00) Pacific Time (US & Canada) Tijuana', 'GMT-08:00', -28800);
INSERT INTO cc_timezone VALUES (6, '(GMT-07:00) Arizona', 'GMT-07:00', -25200);
INSERT INTO cc_timezone VALUES (7, '(GMT-07:00) Chihuahua, La Paz, Mazatlan', 'GMT-07:00', -25200);
INSERT INTO cc_timezone VALUES (8, '(GMT-07:00) Mountain Time(US & Canada)', 'GMT-07:00', -25200);
INSERT INTO cc_timezone VALUES (9, '(GMT-06:00) Central America', 'GMT-06:00', -21600);
INSERT INTO cc_timezone VALUES (10, '(GMT-06:00) Central Time (US & Canada)', 'GMT-06:00', -21600);
INSERT INTO cc_timezone VALUES (11, '(GMT-06:00) Guadalajara, Mexico City, Monterrey', 'GMT-06:00', -21600);
INSERT INTO cc_timezone VALUES (12, '(GMT-06:00) Saskatchewan', 'GMT-06:00', -21600);
INSERT INTO cc_timezone VALUES (13, '(GMT-05:00) Bogota, Lima, Quito', 'GMT-05:00', -18000);
INSERT INTO cc_timezone VALUES (14, '(GMT-05:00) Eastern Time (US & Canada)', 'GMT-05:00', -18000);
INSERT INTO cc_timezone VALUES (15, '(GMT-05:00) Indiana (East)', 'GMT-05:00', -18000);
INSERT INTO cc_timezone VALUES (16, '(GMT-04:00) Atlantic Time (Canada)', 'GMT-04:00', -14400);
INSERT INTO cc_timezone VALUES (17, '(GMT-04:00) Caracas, La Paz', 'GMT-04:00', -14400);
INSERT INTO cc_timezone VALUES (18, '(GMT-04:00) Santiago', 'GMT-04:00', -14400);
INSERT INTO cc_timezone VALUES (19, '(GMT-03:30) NewFoundland', 'GMT-03:30', -12600);
INSERT INTO cc_timezone VALUES (20, '(GMT-03:00) Brasillia', 'GMT-03:00', -10800);
INSERT INTO cc_timezone VALUES (21, '(GMT-03:00) Buenos Aires, Georgetown', 'GMT-03:00', -10800);
INSERT INTO cc_timezone VALUES (22, '(GMT-03:00) Greenland', 'GMT-03:00', -10800);
INSERT INTO cc_timezone VALUES (23, '(GMT-03:00) Mid-Atlantic', 'GMT-03:00', -10800);
INSERT INTO cc_timezone VALUES (24, '(GMT-01:00) Azores', 'GMT-01:00', -3600);
INSERT INTO cc_timezone VALUES (25, '(GMT-01:00) Cape Verd Is.', 'GMT-01:00', -3600);
INSERT INTO cc_timezone VALUES (26, '(GMT) Casablanca, Monrovia', 'GMT+00:00', 0);
INSERT INTO cc_timezone VALUES (27, '(GMT) Greenwich Mean Time : Dublin, Edinburgh, Lisbon,  London', 'GMT', 0);
INSERT INTO cc_timezone VALUES (28, '(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna', 'GMT+01:00', 3600);
INSERT INTO cc_timezone VALUES (29, '(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague', 'GMT+01:00', 3600);
INSERT INTO cc_timezone VALUES (30, '(GMT+01:00) Brussels, Copenhagen, Madrid, Paris', 'GMT+01:00', 3600);
INSERT INTO cc_timezone VALUES (31, '(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb', 'GMT+01:00', 3600);
INSERT INTO cc_timezone VALUES (32, '(GMT+01:00) West Central Africa', 'GMT+01:00', 3600);
INSERT INTO cc_timezone VALUES (33, '(GMT+02:00) Athens, Istanbul, Minsk', 'GMT+02:00', 7200);
INSERT INTO cc_timezone VALUES (34, '(GMT+02:00) Bucharest', 'GMT+02:00', 7200);
INSERT INTO cc_timezone VALUES (35, '(GMT+02:00) Cairo', 'GMT+02:00', 7200);
INSERT INTO cc_timezone VALUES (36, '(GMT+02:00) Harere, Pretoria', 'GMT+02:00', 7200);
INSERT INTO cc_timezone VALUES (37, '(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius', 'GMT+02:00', 7200);
INSERT INTO cc_timezone VALUES (38, '(GMT+02:00) Jeruasalem', 'GMT+02:00', 7200);
INSERT INTO cc_timezone VALUES (39, '(GMT+03:00) Baghdad', 'GMT+03:00', 10800);
INSERT INTO cc_timezone VALUES (40, '(GMT+03:00) Kuwait, Riyadh', 'GMT+03:00', 10800);
INSERT INTO cc_timezone VALUES (41, '(GMT+03:00) Moscow, St.Petersburg, Volgograd', 'GMT+03:00', 10800);
INSERT INTO cc_timezone VALUES (42, '(GMT+03:00) Nairobi', 'GMT+03:00', 10800);
INSERT INTO cc_timezone VALUES (43, '(GMT+03:30) Tehran', 'GMT+03:30', 12600);
INSERT INTO cc_timezone VALUES (44, '(GMT+04:00) Abu Dhabi, Muscat', 'GMT+04:00', 14400);
INSERT INTO cc_timezone VALUES (45, '(GMT+04:00) Baku, Tbillisi, Yerevan', 'GMT+04:00', 14400);
INSERT INTO cc_timezone VALUES (46, '(GMT+04:30) Kabul', 'GMT+04:30', 16200);
INSERT INTO cc_timezone VALUES (47, '(GMT+05:00) Ekaterinburg', 'GMT+05:00', 18000);
INSERT INTO cc_timezone VALUES (48, '(GMT+05:00) Islamabad, Karachi, Tashkent', 'GMT+05:00', 18000);
INSERT INTO cc_timezone VALUES (49, '(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi', 'GMT+05:30', 19800);
INSERT INTO cc_timezone VALUES (50, '(GMT+05:45) Kathmandu', 'GMT+05:45', 20700);
INSERT INTO cc_timezone VALUES (51, '(GMT+06:00) Almaty, Novosibirsk', 'GMT+06:00', 21600);
INSERT INTO cc_timezone VALUES (52, '(GMT+06:00) Astana, Dhaka', 'GMT+06:00', 21600);
INSERT INTO cc_timezone VALUES (53, '(GMT+06:00) Sri Jayawardenepura', 'GMT+06:00', 21600);
INSERT INTO cc_timezone VALUES (54, '(GMT+06:30) Rangoon', 'GMT+06:30', 23400);
INSERT INTO cc_timezone VALUES (55, '(GMT+07:00) Bangkok, Hanoi, Jakarta', 'GMT+07:00', 25200);
INSERT INTO cc_timezone VALUES (56, '(GMT+07:00) Krasnoyarsk', 'GMT+07:00', 25200);
INSERT INTO cc_timezone VALUES (57, '(GMT+08:00) Beijiing, Chongging, Hong Kong, Urumqi', 'GMT+08:00', 28800);
INSERT INTO cc_timezone VALUES (58, '(GMT+08:00) Irkutsk, Ulaan Bataar', 'GMT+08:00', 28800);
INSERT INTO cc_timezone VALUES (59, '(GMT+08:00) Kuala Lumpur, Singapore', 'GMT+08:00', 28800);
INSERT INTO cc_timezone VALUES (60, '(GMT+08:00) Perth', 'GMT+08:00', 28800);
INSERT INTO cc_timezone VALUES (61, '(GMT+08:00) Taipei', 'GMT+08:00', 28800);
INSERT INTO cc_timezone VALUES (62, '(GMT+09:00) Osaka, Sapporo, Tokyo', 'GMT+09:00', 32400);
INSERT INTO cc_timezone VALUES (63, '(GMT+09:00) Seoul', 'GMT+09:00', 32400);
INSERT INTO cc_timezone VALUES (64, '(GMT+09:00) Yakutsk', 'GMT+09:00', 32400);
INSERT INTO cc_timezone VALUES (65, '(GMT+09:00) Adelaide', 'GMT+09:00', 32400);
INSERT INTO cc_timezone VALUES (66, '(GMT+09:30) Darwin', 'GMT+09:30', 34200);
INSERT INTO cc_timezone VALUES (67, '(GMT+10:00) Brisbane', 'GMT+10:00', 36000);
INSERT INTO cc_timezone VALUES (68, '(GMT+10:00) Canberra, Melbourne, Sydney', 'GMT+10:00', 36000);
INSERT INTO cc_timezone VALUES (69, '(GMT+10:00) Guam, Port Moresby', 'GMT+10:00', 36000);
INSERT INTO cc_timezone VALUES (70, '(GMT+10:00) Hobart', 'GMT+10:00', 36000);
INSERT INTO cc_timezone VALUES (71, '(GMT+10:00) Vladivostok', 'GMT+10:00', 36000);
INSERT INTO cc_timezone VALUES (72, '(GMT+11:00) Magadan, Solomon Is., New Caledonia', 'GMT+11:00', 39600);
INSERT INTO cc_timezone VALUES (73, '(GMT+12:00) Auckland, Wellington', 'GMT+1200', 43200);
INSERT INTO cc_timezone VALUES (74, '(GMT+12:00) Fiji, Kamchatka, Marshall Is.', 'GMT+12:00', 43200);
INSERT INTO cc_timezone VALUES (75, '(GMT+13:00) Nuku alofa', 'GMT+13:00', 46800);


--
-- Data for Name: cc_trunk; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_trunk VALUES (1, 'DEFAULT', '011', 'IAX2', 'kiki@switch-2.kiki.net', '', 0, 0, 0, '2005-03-14 01:01:36', 0, '', NULL, 0, -1, 1, 0);


--
-- Data for Name: cc_ui_authen; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cc_ui_authen VALUES (2, 'admin', '410fc6268dd3332226de95e42d9efa4046c5463769d7493b85e65cfa5c26362dc2455cc23c0bc5831deb008def4ab11a9eaa9b76ba3f377da134f39ec60dd758', 0, 32767, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-02-26 21:14:05.391501', NULL);
INSERT INTO cc_ui_authen VALUES (1, 'root', '410fc6268dd3332226de95e42d9efa4046c5463769d7493b85e65cfa5c26362dc2455cc23c0bc5831deb008def4ab11a9eaa9b76ba3f377da134f39ec60dd758', 0, 5242879, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-02-26 20:33:27.691314', NULL);


--
-- Data for Name: cc_voucher; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Name: cc_agent_commission_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_agent_commission
    ADD CONSTRAINT cc_agent_commission_pkey PRIMARY KEY (id);


--
-- Name: cc_agent_login_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_agent
    ADD CONSTRAINT cc_agent_login_key UNIQUE (login);


--
-- Name: cc_agent_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_agent
    ADD CONSTRAINT cc_agent_pkey PRIMARY KEY (id);


--
-- Name: cc_agent_tariffgroup_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_agent_tariffgroup
    ADD CONSTRAINT cc_agent_tariffgroup_pkey PRIMARY KEY (id_agent, id_tariffgroup);


--
-- Name: cc_alarm_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_alarm
    ADD CONSTRAINT cc_alarm_pkey PRIMARY KEY (id);


--
-- Name: cc_alarm_report_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_alarm_report
    ADD CONSTRAINT cc_alarm_report_pkey PRIMARY KEY (id);


--
-- Name: cc_autorefill_report_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_autorefill_report
    ADD CONSTRAINT cc_autorefill_report_pkey PRIMARY KEY (id);


--
-- Name: cc_backup_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_backup
    ADD CONSTRAINT cc_backup_pkey PRIMARY KEY (id);


--
-- Name: cc_billing_customer_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_billing_customer
    ADD CONSTRAINT cc_billing_customer_pkey PRIMARY KEY (id);


--
-- Name: cc_call_archive_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_call_archive
    ADD CONSTRAINT cc_call_archive_pkey PRIMARY KEY (id);


--
-- Name: cc_call_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_call
    ADD CONSTRAINT cc_call_pkey PRIMARY KEY (id);


--
-- Name: cc_callback_spool_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_callback_spool
    ADD CONSTRAINT cc_callback_spool_pkey PRIMARY KEY (id);


--
-- Name: cc_callback_spool_uniqueid_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_callback_spool
    ADD CONSTRAINT cc_callback_spool_uniqueid_key UNIQUE (uniqueid);


--
-- Name: cc_callerid_cid_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_callerid
    ADD CONSTRAINT cc_callerid_cid_key UNIQUE (cid);


--
-- Name: cc_calleridd_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_callerid
    ADD CONSTRAINT cc_calleridd_pkey PRIMARY KEY (id);


--
-- Name: cc_campaign_config_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_campaign_config
    ADD CONSTRAINT cc_campaign_config_pkey PRIMARY KEY (id);


--
-- Name: cc_campaign_phonebook_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_campaign_phonebook
    ADD CONSTRAINT cc_campaign_phonebook_pkey PRIMARY KEY (id_campaign, id_phonebook);


--
-- Name: cc_campaign_phonestatus_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_campaign_phonestatus
    ADD CONSTRAINT cc_campaign_phonestatus_pkey PRIMARY KEY (id_phonenumber, id_campaign);


--
-- Name: cc_campaign_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_campaign
    ADD CONSTRAINT cc_campaign_pkey PRIMARY KEY (id);


--
-- Name: cc_campaignconf_cardgroup_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_campaignconf_cardgroup
    ADD CONSTRAINT cc_campaignconf_cardgroup_pkey PRIMARY KEY (id_campaign_config, id_card_group);


--
-- Name: cc_card_group_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_card_group
    ADD CONSTRAINT cc_card_group_pkey PRIMARY KEY (id);


--
-- Name: cc_card_history_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_card_history
    ADD CONSTRAINT cc_card_history_pkey PRIMARY KEY (id);


--
-- Name: cc_card_seria_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_card_seria
    ADD CONSTRAINT cc_card_seria_pkey PRIMARY KEY (id);


--
-- Name: cc_card_subscription_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_card_subscription
    ADD CONSTRAINT cc_card_subscription_pkey PRIMARY KEY (id);


--
-- Name: cc_charge_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_charge
    ADD CONSTRAINT cc_charge_pkey PRIMARY KEY (id);


--
-- Name: cc_config_group_group_title_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_config_group
    ADD CONSTRAINT cc_config_group_group_title_key UNIQUE (group_title);


--
-- Name: cc_config_group_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_config_group
    ADD CONSTRAINT cc_config_group_pkey PRIMARY KEY (id);


--
-- Name: cc_config_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_config
    ADD CONSTRAINT cc_config_pkey PRIMARY KEY (id);


--
-- Name: cc_configuration_id_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_configuration
    ADD CONSTRAINT cc_configuration_id_pkey PRIMARY KEY (configuration_id);


--
-- Name: cc_country_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_country
    ADD CONSTRAINT cc_country_pkey PRIMARY KEY (id);


--
-- Name: cc_currencies_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_currencies
    ADD CONSTRAINT cc_currencies_pkey PRIMARY KEY (id);


--
-- Name: cc_did_destination_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_did_destination
    ADD CONSTRAINT cc_did_destination_pkey PRIMARY KEY (id);


--
-- Name: cc_did_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_did
    ADD CONSTRAINT cc_did_pkey PRIMARY KEY (id);


--
-- Name: cc_did_use_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_did_use
    ADD CONSTRAINT cc_did_use_pkey PRIMARY KEY (id);


--
-- Name: cc_didgroup_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_didgroup
    ADD CONSTRAINT cc_didgroup_pkey PRIMARY KEY (id);


--
-- Name: cc_ecommerce_product_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_ecommerce_product
    ADD CONSTRAINT cc_ecommerce_product_pkey PRIMARY KEY (id);


--
-- Name: cc_epayment_log_agent_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_epayment_log_agent
    ADD CONSTRAINT cc_epayment_log_agent_pkey PRIMARY KEY (id);


--
-- Name: cc_epayment_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_epayment_log
    ADD CONSTRAINT cc_epayment_log_pkey PRIMARY KEY (id);


--
-- Name: cc_iax_buddies_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_iax_buddies
    ADD CONSTRAINT cc_iax_buddies_pkey PRIMARY KEY (id);


--
-- Name: cc_invoice_conf_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_invoice_conf
    ADD CONSTRAINT cc_invoice_conf_pkey PRIMARY KEY (id);


--
-- Name: cc_invoice_conf_unique; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_invoice_conf
    ADD CONSTRAINT cc_invoice_conf_unique UNIQUE (key_val);


--
-- Name: cc_invoice_item_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_invoice_item
    ADD CONSTRAINT cc_invoice_item_pkey PRIMARY KEY (id);


--
-- Name: cc_invoice_payment_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_invoice_payment
    ADD CONSTRAINT cc_invoice_payment_pkey PRIMARY KEY (id_invoice, id_payment);


--
-- Name: cc_invoice_payment_unique; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_invoice_payment
    ADD CONSTRAINT cc_invoice_payment_unique UNIQUE (id_payment);


--
-- Name: cc_invoice_pkey1; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_invoice
    ADD CONSTRAINT cc_invoice_pkey1 PRIMARY KEY (id);


--
-- Name: cc_invoice_unique_ref; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_invoice
    ADD CONSTRAINT cc_invoice_unique_ref UNIQUE (reference);


--
-- Name: cc_iso639_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_iso639
    ADD CONSTRAINT cc_iso639_pkey PRIMARY KEY (code);


--
-- Name: cc_logpayment_agent_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_logpayment_agent
    ADD CONSTRAINT cc_logpayment_agent_pkey PRIMARY KEY (id);


--
-- Name: cc_logrefill_agent_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_logrefill_agent
    ADD CONSTRAINT cc_logrefill_agent_pkey PRIMARY KEY (id);


--
-- Name: cc_notification_admin_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_notification_admin
    ADD CONSTRAINT cc_notification_admin_pkey PRIMARY KEY (id_notification, id_admin);


--
-- Name: cc_notification_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_notification
    ADD CONSTRAINT cc_notification_pkey PRIMARY KEY (id);


--
-- Name: cc_outbound_cid_group_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_outbound_cid_group
    ADD CONSTRAINT cc_outbound_cid_group_pkey PRIMARY KEY (id);


--
-- Name: cc_outbound_cid_list_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_outbound_cid_list
    ADD CONSTRAINT cc_outbound_cid_list_pkey PRIMARY KEY (id);


--
-- Name: cc_package_group_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_package_group
    ADD CONSTRAINT cc_package_group_pkey PRIMARY KEY (id);


--
-- Name: cc_package_rate_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_package_rate
    ADD CONSTRAINT cc_package_rate_pkey PRIMARY KEY (package_id, rate_id);


--
-- Name: cc_packgroup_package_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_packgroup_package
    ADD CONSTRAINT cc_packgroup_package_pkey PRIMARY KEY (packagegroup_id, package_id);


--
-- Name: cc_payment_methods_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_payment_methods
    ADD CONSTRAINT cc_payment_methods_pkey PRIMARY KEY (id);


--
-- Name: cc_payments_agent_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_payments_agent
    ADD CONSTRAINT cc_payments_agent_pkey PRIMARY KEY (id);


--
-- Name: cc_payments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_payments
    ADD CONSTRAINT cc_payments_pkey PRIMARY KEY (id);


--
-- Name: cc_payments_status_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_payments_status
    ADD CONSTRAINT cc_payments_status_pkey PRIMARY KEY (id);


--
-- Name: cc_paypal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_paypal
    ADD CONSTRAINT cc_paypal_pkey PRIMARY KEY (id);


--
-- Name: cc_phonebook_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_phonebook
    ADD CONSTRAINT cc_phonebook_pkey PRIMARY KEY (id);


--
-- Name: cc_phonenumber_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_phonenumber
    ADD CONSTRAINT cc_phonenumber_pkey PRIMARY KEY (id);


--
-- Name: cc_prefix_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_prefix
    ADD CONSTRAINT cc_prefix_pkey PRIMARY KEY (prefix);


--
-- Name: cc_provider_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_provider
    ADD CONSTRAINT cc_provider_pkey PRIMARY KEY (id);


--
-- Name: cc_ratecard_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_ratecard
    ADD CONSTRAINT cc_ratecard_pkey PRIMARY KEY (id);


--
-- Name: cc_receipt_item_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_receipt_item
    ADD CONSTRAINT cc_receipt_item_pkey PRIMARY KEY (id);


--
-- Name: cc_receipt_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_receipt
    ADD CONSTRAINT cc_receipt_pkey PRIMARY KEY (id);


--
-- Name: cc_restricted_phonenumber_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_restricted_phonenumber
    ADD CONSTRAINT cc_restricted_phonenumber_pkey PRIMARY KEY (id);


--
-- Name: cc_server_group_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_server_group
    ADD CONSTRAINT cc_server_group_pkey PRIMARY KEY (id);


--
-- Name: cc_server_manager_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_server_manager
    ADD CONSTRAINT cc_server_manager_pkey PRIMARY KEY (id);


--
-- Name: cc_service_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_service
    ADD CONSTRAINT cc_service_pkey PRIMARY KEY (id);


--
-- Name: cc_service_report_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_service_report
    ADD CONSTRAINT cc_service_report_pkey PRIMARY KEY (id);


--
-- Name: cc_sip_buddies_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_sip_buddies
    ADD CONSTRAINT cc_sip_buddies_pkey PRIMARY KEY (id);


--
-- Name: cc_speeddial_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_speeddial
    ADD CONSTRAINT cc_speeddial_pkey PRIMARY KEY (id);


--
-- Name: cc_status_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_status_log
    ADD CONSTRAINT cc_status_log_pkey PRIMARY KEY (id);


--
-- Name: cc_subscription_fee_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_subscription_fee
    ADD CONSTRAINT cc_subscription_fee_pkey PRIMARY KEY (id);


--
-- Name: cc_support_component_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_support_component
    ADD CONSTRAINT cc_support_component_pkey PRIMARY KEY (id);


--
-- Name: cc_support_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_support
    ADD CONSTRAINT cc_support_pkey PRIMARY KEY (id);


--
-- Name: cc_system_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_system_log
    ADD CONSTRAINT cc_system_log_pkey PRIMARY KEY (id);


--
-- Name: cc_tariffgroup_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_tariffgroup
    ADD CONSTRAINT cc_tariffgroup_pkey PRIMARY KEY (id);


--
-- Name: cc_tariffplan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_tariffplan
    ADD CONSTRAINT cc_tariffplan_pkey PRIMARY KEY (id);


--
-- Name: cc_ticket_comment_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_ticket_comment
    ADD CONSTRAINT cc_ticket_comment_pkey PRIMARY KEY (id);


--
-- Name: cc_ticket_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_ticket
    ADD CONSTRAINT cc_ticket_pkey PRIMARY KEY (id);


--
-- Name: cc_timezone_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_timezone
    ADD CONSTRAINT cc_timezone_pkey PRIMARY KEY (id);


--
-- Name: cc_trunk_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_trunk
    ADD CONSTRAINT cc_trunk_pkey PRIMARY KEY (id_trunk);


--
-- Name: cc_ui_authen_login_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_ui_authen
    ADD CONSTRAINT cc_ui_authen_login_key UNIQUE (login);


--
-- Name: cc_ui_authen_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_ui_authen
    ADD CONSTRAINT cc_ui_authen_pkey PRIMARY KEY (userid);


--
-- Name: cc_voucher_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_voucher
    ADD CONSTRAINT cc_voucher_pkey PRIMARY KEY (id);


--
-- Name: cons_cc_backup_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_backup
    ADD CONSTRAINT cons_cc_backup_name_key UNIQUE (name);


--
-- Name: cons_cc_card_archive_username; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_card_archive
    ADD CONSTRAINT cons_cc_card_archive_username UNIQUE (username);


--
-- Name: cons_cc_card_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_card
    ADD CONSTRAINT cons_cc_card_pkey PRIMARY KEY (id);


--
-- Name: cons_cc_card_useralias; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_card
    ADD CONSTRAINT cons_cc_card_useralias UNIQUE (useralias);


--
-- Name: cons_cc_card_username; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_card
    ADD CONSTRAINT cons_cc_card_username UNIQUE (username);


--
-- Name: cons_cc_cardgroup_unique; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_cardgroup_service
    ADD CONSTRAINT cons_cc_cardgroup_unique UNIQUE (id_card_group, id_service);


--
-- Name: cons_cc_currencies_currency_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_currencies
    ADD CONSTRAINT cons_cc_currencies_currency_key UNIQUE (currency);


--
-- Name: cons_cc_provider_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_provider
    ADD CONSTRAINT cons_cc_provider_name_key UNIQUE (provider_name);


--
-- Name: cons_cc_speeddial_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_speeddial
    ADD CONSTRAINT cons_cc_speeddial_pkey UNIQUE (id_cc_card, speeddial);


--
-- Name: cons_cc_templatemail_mailtype; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_templatemail
    ADD CONSTRAINT cons_cc_templatemail_mailtype UNIQUE (id, id_language);


--
-- Name: cons_cc_ui_authen_login_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_ui_authen
    ADD CONSTRAINT cons_cc_ui_authen_login_key UNIQUE (login);


--
-- Name: cons_did_cc_did; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_did
    ADD CONSTRAINT cons_did_cc_did UNIQUE (did);


--
-- Name: cons_iduser_tariffname; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_tariffplan
    ADD CONSTRAINT cons_iduser_tariffname UNIQUE (iduser, tariffname);


--
-- Name: cons_phonelistname; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_campaign
    ADD CONSTRAINT cons_phonelistname UNIQUE (name);


--
-- Name: cons_txn_id_cc_paypal; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_paypal
    ADD CONSTRAINT cons_txn_id_cc_paypal UNIQUE (txn_id);


--
-- Name: cons_useralias_cc_card; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_card
    ADD CONSTRAINT cons_useralias_cc_card UNIQUE (useralias);


--
-- Name: cons_username_cc_card; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_card
    ADD CONSTRAINT cons_username_cc_card UNIQUE (username);


--
-- Name: cons_voucher_cc_voucher; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_voucher
    ADD CONSTRAINT cons_voucher_cc_voucher UNIQUE (voucher);


--
-- Name: iax_unique_name; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_iax_buddies
    ADD CONSTRAINT iax_unique_name UNIQUE (name);


--
-- Name: iso639_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_iso639
    ADD CONSTRAINT iso639_name_key UNIQUE (name);


--
-- Name: pk_groupplan; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_tariffgroup_plan
    ADD CONSTRAINT pk_groupplan PRIMARY KEY (idtariffgroup, idtariffplan);


--
-- Name: unique_name; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cc_sip_buddies
    ADD CONSTRAINT unique_name UNIQUE (name);


--
-- Name: cc_call_calledstation_arc_ind; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX cc_call_calledstation_arc_ind ON cc_call_archive USING btree (calledstation);


--
-- Name: cc_call_calledstation_ind; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX cc_call_calledstation_ind ON cc_call USING btree (calledstation);


--
-- Name: cc_call_starttime_arc_ind; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX cc_call_starttime_arc_ind ON cc_call_archive USING btree (starttime);


--
-- Name: cc_call_starttime_ind; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX cc_call_starttime_ind ON cc_call USING btree (starttime);


--
-- Name: cc_call_terminatecause_arc_ind; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX cc_call_terminatecause_arc_ind ON cc_call_archive USING btree (terminatecause);


--
-- Name: cc_call_terminatecause_id; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX cc_call_terminatecause_id ON cc_call USING btree (terminatecauseid);


--
-- Name: cc_call_username_arc_ind; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX cc_call_username_arc_ind ON cc_call_archive USING btree (username);


--
-- Name: cc_card_archive_creationdate_ind; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX cc_card_archive_creationdate_ind ON cc_card_archive USING btree (creationdate);


--
-- Name: cc_card_archive_username_ind; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX cc_card_archive_username_ind ON cc_card_archive USING btree (username);


--
-- Name: cc_card_creationdate_ind; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX cc_card_creationdate_ind ON cc_card USING btree (creationdate);


--
-- Name: cc_card_username_ind; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX cc_card_username_ind ON cc_card USING btree (username);


--
-- Name: cc_prefix_dest; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX cc_prefix_dest ON cc_prefix USING btree (destination);


--
-- Name: ind_cc_card_package_offer_date_consumption; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX ind_cc_card_package_offer_date_consumption ON cc_card_package_offer USING btree (date_consumption);


--
-- Name: ind_cc_card_package_offer_id_card; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX ind_cc_card_package_offer_id_card ON cc_card_package_offer USING btree (id_cc_card);


--
-- Name: ind_cc_card_package_offer_id_package_offer; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX ind_cc_card_package_offer_id_package_offer ON cc_card_package_offer USING btree (id_cc_package_offer);


--
-- Name: ind_cc_charge_creationdate; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX ind_cc_charge_creationdate ON cc_charge USING btree (creationdate);


--
-- Name: ind_cc_charge_id_cc_card; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX ind_cc_charge_id_cc_card ON cc_charge USING btree (id_cc_card);


--
-- Name: ind_cc_ratecard_dialprefix; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX ind_cc_ratecard_dialprefix ON cc_ratecard USING btree (dialprefix);


--
-- Name: cc_card_serial; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER cc_card_serial
    BEFORE INSERT ON cc_card
    FOR EACH ROW
    EXECUTE PROCEDURE cc_card_serial_set();


--
-- Name: cc_card_serial_upd; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER cc_card_serial_upd
    BEFORE UPDATE ON cc_card
    FOR EACH ROW
    EXECUTE PROCEDURE cc_card_serial_update();


--
-- Name: cc_ratecard_validate_regex; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER cc_ratecard_validate_regex
    BEFORE INSERT OR UPDATE ON cc_ratecard
    FOR EACH ROW
    EXECUTE PROCEDURE cc_ratecard_validate_regex();


--
-- Name: cc_agent_id_tariffgroup_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cc_agent
    ADD CONSTRAINT cc_agent_id_tariffgroup_fkey FOREIGN KEY (id_tariffgroup) REFERENCES cc_tariffgroup(id);


--
-- Name: cc_support_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cc_support_component
    ADD CONSTRAINT cc_support_id_fkey FOREIGN KEY (id_support) REFERENCES cc_support(id) ON DELETE CASCADE;


--
-- Name: cc_ticket_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cc_ticket_comment
    ADD CONSTRAINT cc_ticket_id_fkey FOREIGN KEY (id_ticket) REFERENCES cc_ticket(id) ON DELETE CASCADE;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- database install complete
--



