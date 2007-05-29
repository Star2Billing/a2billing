-- ------------------------	UPDATE DATABASE V2 -> V3 --------------------------



CREATE TABLE cc_callerid (
    id bigserial NOT NULL,
    cid text NOT NULL,
    id_cc_card bigint NOT NULL,    
    activated boolean DEFAULT true NOT NULL
);

ALTER TABLE ONLY cc_callerid
    ADD CONSTRAINT cc_calleridd_pkey PRIMARY KEY (id);

ALTER TABLE ONLY cc_callerid
    ADD CONSTRAINT cc_callerid_cid_key UNIQUE (cid);



ALTER TABLE cc_tariffgroup ADD COLUMN removeinterprefix integer;	
	ALTER TABLE cc_tariffgroup ALTER COLUMN removeinterprefix SET DEFAULT '0';


ALTER TABLE trunk ADD COLUMN failover_trunk integer;

ALTER TABLE call ADD COLUMN src text;	


ALTER TABLE cc_ratecard ADD COLUMN musiconhold character varying(100);


ALTER TABLE trunk ADD COLUMN addparameter text;


ALTER TABLE cc_card ADD COLUMN redial text;





ALTER TABLE "cc_sip_buddies" ALTER COLUMN "dtmfmode" SET DEFAULT 'yes'::character varying;
ALTER TABLE "cc_iax_buddies" ALTER COLUMN "dtmfmode" SET DEFAULT 'yes'::character varying;


ALTER TABLE "cc_sip_buddies" DROP COLUMN nat;
ALTER TABLE "cc_sip_buddies" ADD COLUMN nat character varying(3);
ALTER TABLE "cc_sip_buddies" ALTER COLUMN "nat" SET DEFAULT 'yes'::character varying;

ALTER TABLE "cc_iax_buddies" DROP COLUMN nat;
ALTER TABLE "cc_iax_buddies" ADD COLUMN nat character varying(3);
ALTER TABLE "cc_iax_buddies" ALTER COLUMN "nat" SET DEFAULT 'yes'::character varying;

ALTER TABLE "cc_sip_buddies" ALTER COLUMN "qualify" SET DEFAULT 'yes'::character varying;
ALTER TABLE "cc_iax_buddies" ALTER COLUMN "qualify" SET DEFAULT 'yes'::character varying;


ALTER TABLE "cc_sip_buddies" ADD COLUMN regexten character varying(20);
ALTER TABLE "cc_iax_buddies" ADD COLUMN regexten character varying(20);



--				RECURRING SERVICE
-- ------------------------------------------------
ALTER TABLE cc_card ADD COLUMN runservice integer;
ALTER TABLE cc_card ALTER COLUMN runservice SET DEFAULT 1;
UPDATE cc_card SET runservice = '1';


ALTER TABLE cc_card ADD COLUMN nbservice integer;
ALTER TABLE cc_card ALTER COLUMN nbservice SET DEFAULT 0;
UPDATE cc_card SET nbservice = '0';

ALTER TABLE cc_card ADD COLUMN lastuse2 timestamp(0) without time zone ;
ALTER TABLE cc_card ALTER COLUMN lastuse2 SET DEFAULT now();
UPDATE cc_card SET lastuse2 = lastuse;
ALTER TABLE cc_card DROP COLUMN lastuse;
ALTER TABLE cc_card RENAME COLUMN lastuse2 TO lastuse;



-- ------------------- alias -------------------
ALTER TABLE cc_card ADD COLUMN useralias text;
UPDATE cc_card SET useralias = username;


ALTER TABLE ONLY cc_card
    ADD CONSTRAINT cons_username_cc_card UNIQUE (username);

ALTER TABLE ONLY cc_card
    ADD CONSTRAINT cons_useralias_cc_card UNIQUE (useralias);

-- -------------   expiration date ----------------

ALTER TABLE cc_card ADD COLUMN firstusedate timestamp without time zone;

ALTER TABLE cc_card ADD COLUMN enableexpire integer;
ALTER TABLE cc_card ALTER COLUMN enableexpire SET DEFAULT 0;
UPDATE cc_card SET enableexpire = '0';

ALTER TABLE cc_card ADD COLUMN expiredays integer;
ALTER TABLE cc_card ALTER COLUMN expiredays SET DEFAULT 0;
UPDATE cc_card SET expiredays = '0';


-- ---------------  voucher  ----------------------

CREATE TABLE cc_voucher (
    id bigserial NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
	usedate timestamp without time zone,
    expirationdate timestamp without time zone,	
	voucher text NOT NULL,
	usedcardnumber text,
    tag text,	
    credit real NOT NULL,    
    activated boolean DEFAULT true NOT NULL,
	used integer DEFAULT 0,
    currency character varying(3) DEFAULT 'USD'::character varying
);


--  ---------------  Dnid Prefix ----------------------
ALTER TABLE cc_tariffplan ADD COLUMN dnidprefix text;
ALTER TABLE cc_tariffplan ALTER COLUMN dnidprefix SET DEFAULT 'all'::text;
UPDATE cc_tariffplan SET dnidprefix = 'all';


--  ---------------  Currency ----------------------

ALTER TABLE cc_card DROP COLUMN currency;
ALTER TABLE cc_card ADD COLUMN currency character varying(3);
ALTER TABLE cc_card ALTER COLUMN currency SET DEFAULT 'USD'::character varying;
UPDATE cc_card SET currency = 'USD';

ALTER TABLE cc_voucher DROP COLUMN currency;
ALTER TABLE cc_voucher ADD COLUMN currency character varying(3);
ALTER TABLE cc_voucher ALTER COLUMN currency SET DEFAULT 'USD'::character varying;
UPDATE cc_voucher SET currency = 'USD';

-- ------------------- ROUNDING ERROR FOR FLOATING POINT -----------

 
ALTER TABLE cc_card ADD COLUMN credit2 numeric(12,4);
UPDATE cc_card SET credit2=credit;
ALTER TABLE cc_card DROP COLUMN credit;
ALTER TABLE cc_card RENAME COLUMN credit2 TO credit;

ALTER TABLE cc_voucher ADD COLUMN credit2 numeric(12,4);
UPDATE cc_voucher SET credit2=credit;
ALTER TABLE cc_voucher DROP COLUMN credit;
ALTER TABLE cc_voucher RENAME COLUMN credit2 TO credit;

ALTER TABLE logrefill ADD COLUMN credit2 numeric(12,4);
UPDATE logrefill SET credit2=credit;
ALTER TABLE logrefill DROP COLUMN credit;
ALTER TABLE logrefill RENAME COLUMN credit2 TO credit;
