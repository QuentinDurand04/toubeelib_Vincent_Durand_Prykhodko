-- Adminer 4.8.1 PostgreSQL 16.4 (Debian 16.4-1.pgdg120+1) dump

DROP TABLE IF EXISTS "patient";
CREATE TABLE "public"."patient" (
    "id" uuid NOT NULL,
    "nom" character varying(50) NOT NULL,
    "prenom" character varying(50) NOT NULL,
    "datenaissance" date NOT NULL,
    "adresse" character varying(100),
    "tel" character varying(20),
    "mail" character varying(100),
    "idmedcintraitant" uuid,
    "numsecusociale" character varying(50),
    CONSTRAINT "patient_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "rdv";
CREATE TABLE "public"."rdv" (
    "id" uuid NOT NULL,
    "date" timestamp,
    "patientid" uuid,
    "praticienid" uuid,
    "status" integer,
    CONSTRAINT "rdv_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "status";
CREATE TABLE "public"."status" (
    "id" integer NOT NULL,
    "label" character varying(50) NOT NULL,
    CONSTRAINT "status_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


ALTER TABLE ONLY "public"."rdv" ADD CONSTRAINT "rdv_patientid_fkey" FOREIGN KEY (patientid) REFERENCES patient(id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."rdv" ADD CONSTRAINT "rdv_status_fkey" FOREIGN KEY (status) REFERENCES status(id) NOT DEFERRABLE;

-- 2025-01-28 14:31:42.678918+00
