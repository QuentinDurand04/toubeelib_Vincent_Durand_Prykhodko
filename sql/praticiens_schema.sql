-- Adminer 4.8.1 PostgreSQL 16.4 (Debian 16.4-1.pgdg120+1) dump

DROP TABLE IF EXISTS "praticien";
CREATE TABLE "public"."praticien" (
    "id" uuid NOT NULL,
    "rpps" character varying(50) NOT NULL,
    "nom" character varying(50) NOT NULL,
    "prenom" character varying(50) NOT NULL,
    "adresse" character varying(100) NOT NULL,
    "tel" character varying(20) NOT NULL,
    "specialite" character varying(5) NOT NULL,
    CONSTRAINT "praticien_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "specialite";
CREATE TABLE "public"."specialite" (
    "id" character varying(5) NOT NULL,
    "label" character varying(50) NOT NULL,
    "description" text NOT NULL,
    CONSTRAINT "specialite_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


ALTER TABLE ONLY "public"."praticien" ADD CONSTRAINT "praticien_specialite_fkey" FOREIGN KEY (specialite) REFERENCES specialite(id) NOT DEFERRABLE;

-- 2025-01-28 14:33:09.931746+00
