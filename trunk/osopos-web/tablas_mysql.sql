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
  iva_porc      int DEFAULT 15 NOT NULL
);
REVOKE ALL ON articulos FROM PUBLIC;
GRANT SELECT,UPDATE ON articulos TO "caja1";
GRANT SELECT,UPDATE ON articulos TO "caja2";
GRANT SELECT,UPDATE ON articulos TO "caja3";
GRANT ALL ON articulos TO "scaja";
CREATE TABLE ventas (
  numero            int AUTO_INCREMENT PRIMARY KEY,
  monto             float,
  tipo_pago         tinyint NOT NULL DEFAULT 20,
  tipo_factur       tinyint NOT NULL DEFAULT 5,
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
  id_venta        int4 NOT NULL,
  codigo          varchar(20) NOT NULL,
  cantidad        smallint
);

CREATE TABLE facturas_ingresos (
  id              int4 AUTO_INCREMENT PRIMARY KEY,
  fecha           DATE,
  rfc             character varying(13),
  dom_calle       character varying(30),
  dom_numero      character varying(15),
  dom_inter       character varying(3),
  dom_col         character varying(20),
  dom_ciudad      character varying(30),
  dom_edo         character varying(20),
  dom_cp          int4,
  subtotal        float not null,
  iva             float
);

CREATE TABLE fact_ingresos_detalle (
  id_factura        int4 not null,
  codigo            varchar(20) default '',
  concepto          varchar(128) not null,
  cant              int,
  precio            float
);

CREATE TABLE clientes_fiscales (
  rfc               varchar(13) PRIMARY KEY NOT NULL,
  curp              varchar(18),
  nombre            varchar(70) NOT NULL
);

CREATE TABLE departamento (
  id                smallint AUTO_INCREMENT PRIMARY KEY,
  nombre            varchar(25)
);
INSERT INTO departamento VALUES (0, 'Sin clasificar');

CREATE TABLE proveedores (
  id          smallint AUTO_INCREMENT PRIMARY KEY,
  nick        varchar(15) NOT NULL,
  razon_soc   varchar(30),
  calle       varchar(30),
  colonia     varchar(25),
  ciudad      varchar(30),
  estado      varchar(30),
  contacto    varchar(40),
  email       varchar(40),
  url         varchar(80)
);
INSERT INTO proveedores (id, nick) VALUES (0, 'Sin proveedor');

CREATE TABLE telefonos_proveedor (
  id_proveedor smallint NOT NULL,
  clave_ld     varchar(3) DEFAULT NULL,
  numero       varchar(7) NOT NULL,
  ext          smallint,
  fax          bool DEFAULT 'f'
);

CREATE TABLE users (
  id           smallint AUTO_INCREMENT PRIMARY KEY NOT NULL,
  user         varchar(10),
  passwd       varchar(32) DEFAULT '',
  level        int NOT NULL DEFAULT 0
);


