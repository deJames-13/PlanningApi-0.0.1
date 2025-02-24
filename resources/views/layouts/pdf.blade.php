<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


		<title>@yield('title')</title>


		<style>
			body {
				font-family: Arial, sans-serif !important;
				margin: 0;
				padding: 0;
			}

			.content {
				padding: 20px;
			}

			.table-container {
				width: 100%;
				margin: 20px 0;
				border-collapse: collapse;
			}

			.table-container th,
			.table-container td {
				border: 1px solid #ddd;
				padding: 8px;
			}

			.table-container th {
				background-color: #f2f2f2;
				text-align: left;
			}

			.table-container tr:nth-child(even) {
				background-color: #f9f9f9;
			}

			.table-container tr:hover {
				background-color: #f1f1f1;
			}

			.table-container th {
				padding-top: 12px;
				padding-bottom: 12px;
				background-color: #4CAF50;
				color: white;
			}

			.page-break {
				page-break-after: always;
			}
		</style>

		<link rel="stylesheet" href="{{ public_path('css/bootstrap.min.css') }}">

	</head>

	<body class="m-0 p-0">
		<div class="content">
			@yield('content')
		</div>
	</body>

</html>
