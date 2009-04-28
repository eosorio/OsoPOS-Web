   <script language="JavaScript" src="menu/JSCookMenu.js"></script>
   <link rel="stylesheet" href="menu/theme.css" type="text/css">
   <script language="JavaScript" src="menu/theme.js"></script>
   <script language="JavaScript">

<!--
var MenuPrincipal = [
	[null,'Inicio','index.php','_self','Inventarios, características de productos, movimientos'],
	[null,'Artículos',null,null,'Inventarios, características de productos, movimientos',
		[null,'Control de inventarios','invent_web.php','_self','Inventarios y características de productos'],
		[null,'Movimientos al inventario','mov_invent.php','_self','Transferencias entre almacenes, compras, etc.'],
	    [null,'Carrito de compras','carro.php',null,'Consulta y vaciado de artículos en carrito virtual'],
		[null,'Líneas de producto','depto.php','_self','Agregar o modificar líneas de productos'],
		[null,'Mantenimiento de almacenes','almacen.php','_self','Agregar o eliminar listado de producto en almacén, etc.'],
		_cmSplit,
		[null,'Alta de producto','invent_web.php?action=agrega','_self','Agregar al catálogo de productos']
	],
	[null,'Facturación',null,null,'Facturas y operaciones de caja',
		[null,'Facturas',null,null,'Facturas',
			[null,'Elaborar','factur_web.php',null,'Elaboración de facturas'],
			[null,'Lista', 'factur_web_muestra.php', null, 'Listado de facturas']
		],
		[null,'Caja',null,null,'Operación de caja',
			[null,'Modo express','caja_web.php?mode=express',null,'Operación para cobrar muchos productos en una sola venta'],
		    [null,'Modo normal','caja_web.php',null,'Operación para cobrar pocos productos en una sola venta'],
		    _cmSplit,
		    [null,'Corte de caja','corte_web.php',null,'Reportes de ventas en caja']

		],
		[null,'Rentas',null,null,'Rentas de artículos',
			[null,'Renta de productos','renta_web.php?accion=renta',null,'Renta de artículos'],
            [null,'Clientes por entregar','renta_web.php?accion=clientes',null,'Consulta de clientes con artículos rentados'],
            [null,'Rentas por entregar','renta_web.php?accion=devolucion',null,'Consulta de clientes con artículos rentados'],
            [null,'Devolución express','renta_web.php?accion=devolucion_express',null,'Consulta de clientes con artículos rentados']
		]
	],
	[null,'Clientes',null,null,'Consulta y alta de clientes',
	    [null,'Listado','clientes.php',null,'Listado de clientes'],
		[null,'Alta','clientes.php?accion=nuevo',null,'Alta de clientes']
	],
	[null,'Proveedores',null,null,'Consulta y alta de proveedores',
 	    [null,'Listado','proveedor.php',null,'Listado de proveedores'],
 	    [null,'Alta','proveedor.php?accion=agrega',null,'Alta de proveedores']
	],
	[null,'Catálogos',null,null,'Alta de usuarios, cajeros y vendedores',
		[null,'Usuarios','password.php',null,'Alta, baja y modificación de usuarios'],
		[null,'Cajeros','cajeros.php',null,'Administración de cajeros'],
		[null,'Vendedores','vendedores.php',null,'Administración de vendedores'],
                _cmSplit,
		[null,'Módulos de operación',null,null,'Módulos de OsoPOS Web',
			       [null, 'Listar', 'modulos.php?action=list', null, 'Listado de módulos'],
                               [null, 'Usuarios por módulo', 'modulos.php?action=users', null, 'Listado de usuarios por módulo']
                 ]
	],
	[null,'Ayuda',null,null,null,
		[null,'Licencia de operación del sistema','gpl.php',null,null]
    ],
	[null,'Salir','index.php?salir=1','_self','Salir del sistema']
];

--></script>
