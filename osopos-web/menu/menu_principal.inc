   <script language="JavaScript" src="menu/JSCookMenu.js"></script>
   <link rel="stylesheet" href="menu/theme.css" type="text/css">
   <script language="JavaScript" src="menu/theme.js"></script>
   <script language="JavaScript">

<!--
var MenuPrincipal = [
	[null,'Inicio','index.php','_self','Inventarios, caracter�siticas de productos, movimientos'],
	[null,'Art�culos',null,null,'Inventarios, caracter�siticas de productos, movimientos',
		[null,'Control de inventarios','invent_web.php','_self','Inventarios y caracter�sticas de productos'],
		[null,'Movimientos al inventario','mov_invent.php','_self','Transferencias entre almacenes, compras, etc.'],
	    [null,'Carrito de compras','carro.php',null,'Consulta y vaciado de art�culos en carrito virtual'],
		[null,'L�neas de producto','depto.php','_self','Agregar o modificar l�neas de productos'],
		[null,'Mantenimiento de almacenes','almacen.php','_self','Agregar o eliminar listado de producto en almac�n, etc.'],
		_cmSplit,
		[null,'Alta de producto','invent_web.php?action=agrega','_self','Agregar al cat�logo de productos']
	],
	[null,'Facturaci�n',null,null,'Facturas y operaciones de caja',
		[null,'Facturas','factur_web.php',null,'Emisi�n de facturas'],
		[null,'Caja',null,null,'Operaci�n de caja',
			[null,'Modo express','caja_web.php?mode=express',null,'Operaci�n para cobrar muchos productos en una sola venta'],
		    [null,'Modo normal','caja_web.php',null,'Operaci�n para cobrar pocos productos en una sola venta'],
		    _cmSplit,
		    [null,'Corte de caja','corte_web.php',null,'Reportes de ventas en caja']

		],
		[null,'Rentas',null,null,'Rentas de art�culos',
			[null,'Renta de productos','renta_web.php?accion=renta',null,'Renta de art�culos'],
            [null,'Clientes por entregar','renta_web.php?accion=clientes',null,'Consulta de clientes con art�culos rentados'],
            [null,'Rentas por entregar','renta_web.php?accion=devolucion',null,'Consulta de clientes con art�culos rentados'],
            [null,'Devoluci�n express','renta_web.php?accion=devolucion_express',null,'Consulta de clientes con art�culos rentados']
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
	[null,'Herramientas',null,null,'Alta de usuarios, cajeros y vendedores',
		[null,'Usuarios','password.php',null,'Alta, baja y modificaci�n de usuarios'],
		[null,'Cajeros','cajeros.php',null,'Administraci�n de cajeros'],
		[null,'Vendedores','vendedores.php',null,'Administraci�n de vendedores']
	],
	[null,'Ayuda',null,null,null,
		[null,'Licencia de operaci�n del sistema','gpl.php',null,null]
    ],
	[null,'Salir','index.php?salir=1','_self','Salir del sistema']
];

--></script>