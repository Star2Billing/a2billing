

DROP USER a2billinguser;
CREATE USER a2billinguser WITH PASSWORD 'a2billing' CREATEDB;
CREATE DATABASE mya2billing OWNER a2billinguser;
