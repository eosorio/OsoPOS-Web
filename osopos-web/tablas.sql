CREATE TABLE articulos (
  codigo        varchar(20) PRIMARY KEY NOT NULL,
  descripcion   varchar(50) NOT NULL,
  pu            real,
  descuento     real DEFAULT 0,
  cant          int,
  min           int DEFAULT 0,
  max           int,
  id_prov       int4 DEFAULT 0,
  id_depto      int4 DEFAULT 0,
  p_costo       real DEFAULT 0,
  prov_clave    varchar(20) DEFAULT '',
  iva_porc      int DEFAULT 15 NOT NULL,
  divisa        character(3) NOT NULL DEFAULT 'MXP',
  codigo2       varchar(20),
  pu2           real,
  pu3           real,
  pu4           real,
  pu5           real
);
REVOKE ALL ON articulos FROM PUBLIC;
GRANT SELECT,UPDATE ON articulos TO "caja1";
GRANT SELECT,UPDATE ON articulos TO "caja2";
GRANT SELECT,UPDATE ON articulos TO "caja3";
GRANT ALL ON articulos TO "scaja";

CREATE TABLE ventas (
  numero            SERIAL PRIMARY KEY,
  monto             real,
  tipo_pago         int2 NOT NULL DEFAULT 20,
  tipo_factur       int2 NOT NULL DEFAULT 5,
  corte_parcial     bool DEFAULT 'f',
  utilidad          real,
  id_vendedor       int4 NOT NULL DEFAULT 0,
  id_cajero         int4 NOT NULL DEFAULT 0,
  fecha             date NOT NULL,
  hora              time NOT NULL
);

REVOKE ALL ON ventas FROM PUBLIC;
GRANT INSERT,SELECT ON ventas TO "caja1";
GRANT INSERT,SELECT,UPDATE,DELETE ON ventas TO "scaja";

CREATE TABLE ventas_detalle (
  "id_venta"        int4 NOT NULL,
  "codigo"          varchar(20) NOT NULL,
  "descrip"         varchar(40) NOT NULL,
  "cantidad"        int2,
  "pu"              float NOT NULL DEFAULT 0,
  "iva_porc"        float not null default 15
);

CREATE TABLE facturas_ingresos (
  "id"              SERIAL PRIMARY KEY,
  "fecha"           DATE,
  "rfc"             character varying(13),
  "dom_calle"       character varying(30),
  "dom_numero"      character varying(15),
  "dom_inter"       character varying(3),
  "dom_col"         character varying(20),
  "dom_ciudad"      character varying(30),
  "dom_edo"         character varying(20),
  "dom_cp"          int4,
  "subtotal"        real not null,
  "iva"             real
);
REVOKE ALL ON facturas_ingresos TO PUBLIC;
GRANT INSERT, SELECT ON facturas_ingresos TO "cajero1";

CREATE TABLE fact_ingresos_detalle (
  id_factura        int4 not null,
  codigo            varchar(20) default '',
  concepto          varchar(128) not null,
  cant              int,
  precio            real
);
REVOKE ALL ON fact_ingresos_detalle TO PUBLIC;
GRANT INSERT, SELECT ON fact_ingresos_detalle TO "cajero1";

CREATE TABLE clientes_fiscales (
  rfc               varchar(13) PRIMARY KEY NOT NULL,
  curp              varchar(18),
  nombre            varchar(70) NOT NULL
);
REVOKE ALL ON clientes_fiscales TO PUBLIC;
GRANT INSERT, SELECT ON clientes_fiscales TO "cajero1";

CREATE TABLE departamento (
  id                SERIAL PRIMARY KEY,
  nombre            varchar(25)
);
COPY "departamento" FROM stdin;
0	 Sin clasificar
\.

CREATE TABLE proveedores (
  "id"          SERIAL PRIMARY KEY,
  "nick"        varchar(15) NOT NULL,
  "razon_soc"   varchar(30),
  "calle"       varchar(30),
  "colonia"     varchar(25),
  "ciudad"      varchar(30),
  "estado"      varchar(30),
  "contacto"    varchar(40),
  "email"       varchar(40),
  "url"         varchar(80)
);
INSERT INTO proveedores (id, nick) VALUES (0, 'Sin proveedor');

CREATE TABLE telefonos_proveedor (
  "id_proveedor" int4 NOT NULL,
  "clave_ld"     varchar(3) DEFAULT NULL,
  "numero"       varchar(7) NOT NULL,
  "ext"          int2,
  "fax"          bool DEFAULT 'f'
);

CREATE TABLE users (
  "id"           SERIAL NOT NULL,
  "user"         varchar(10),
  "passwd"       varchar(32) DEFAULT '',
  "level"        int NOT NULL DEFAULT 0
);

CREATE TABLE divisas (
  "id"           char(3) NOT NULL,
  "nombre"       varchar(20),
  "tipo_cambio"  real
);
