<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Internet Banking Basic</title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>    
	<header class="ibb-header">
		<nav class="navbar navbar-expand navbar-dark bg-dark justify-content-between">
			<a href="#" class="navbar-brand ml-2">
				<img src="assets/images/logo_white.png" width="85px" class="d-inline-block align-top img-fluid" alt="LuanDEV Logo">
			</a>
			
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="#"><i class="fas fa-exchange-alt"></i> Transferir</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#"><i class="fas fa-hand-holding-usd"></i> Retirar</a>
				</li>   
				<li class="nav-item">
					<a class="nav-link" href="#"><i class="fas fa-coins"></i> Depositar</a>
				</li>   
				<li class="nav-item active">
					<a class="nav-link" href="#"><i class="fas fa-receipt"></i> Histórico de Ações</a>
				</li>       
			</ul>
			
			<ul class="navbar-nav">   
				<li class="nav-item">
					<p class="navbar-text mb-0 mr-3"><span class="badge badge-success">R$ 1500</span></p>
				</li>  
				<li class="nav-item">
					<p class="navbar-text text-light mb-0 mr-4"><span class="badge badge-info"><i class="fas fa-user-circle"></i> Nome do Usuário</span></p>
				</li>                  
				<li class="nav-item">
					<a class="btn btn-danger mr-2" href="#">Encerrar sessão</a>
				</li>
			</ul>
		</nav>
	</header>
	
	<main>
		<div class="container">
			<div class="py-5">
				<form method="post">
					<table class="table table-striped table-bordered">
						<thead class="text-center">
							<tr>
								<th scope="col"><i class="fas fa-hashtag text-info"></i> ID</th>
								<th scope="col"><i class="fas fa-pencil-alt text-warning"></i> ALTERAR?</th>
								<th scope="col"><i class="fas fa-user text-primary"></i> NOME</th>
								<th scope="col"><i class="fas fa-user-circle text-primary"></i> CONTA</th>
								<th scope="col"><i class="fas fa-key text-primary"></i> SENHA</th>
								<th scope="col"><i class="fas fa-cogs text-primary"></i> FUNÇÃO</th>
								<th scope="col"><i class="fas fa-dollar-sign text-success"></i> SALDO</th>
								<th scope="col"><i class="fas fa-times text-danger"></i> EXCLUIR?</th>
							</tr>
						</thead>
						<tbody class="text-center">
							<tr>
								<td scope="row"><b>#1</b></td>
								<td><input type="checkbox" class="acc-edit"></td>
								<td><p class="text-muted my-0">Jose Silva</p></td>
								<td><input type="text" class="accs-num form-control" maxlength="10"></td>
								<td>
									<input type="password" class="accs-new-password form-control" maxlength="6">
								</td>
								<td>
									<select class="accs-role form-control">
										<option value="subadmin">Subadmin</option>
										<option value="user">Usuário</option>
									</select>
								</td>
								<td><input type="text" class="accs-balance form-control"></td>
								<td><input type="checkbox" class="accs-delete" value="ID"></td>
								<input type="hidden" class="accs-id">
							</tr>
							<tr>
							<td scope="row"><b>#4</b></td>
								<td><input type="checkbox" class="acc-edit"></td>
								<td><p class="text-muted my-0">Jose Silva</p></td>
								<td><input type="text" class="accs-num form-control" maxlength="10"></td>
								<td>
									<input type="password" class="accs-new-password form-control" maxlength="6">
								</td>
								<td>
									<select class="accs-role form-control">
										<option value="subadmin">Subadmin</option>
										<option value="user">Usuário</option>
									</select>
								</td>
								<td><input type="text" class="accs-balance form-control"></td>
								<td><input type="checkbox" class="accs-delete" value="ID"></td>
								<input type="hidden" class="accs-id">
							</tr>
							<tr>
							<td scope="row"><b>#8</b></td>
								<td><input type="checkbox" class="acc-edit"></td>
								<td><p class="text-muted my-0">Jose Silva</p></td>
								<td><input type="text" class="accs-num form-control" maxlength="10"></td>
								<td>
									<input type="password" class="accs-new-password form-control" maxlength="6">
								</td>
								<td>
									<select class="accs-role form-control">
										<option value="subadmin">Subadmin</option>
										<option value="user">Usuário</option>
									</select>
								</td>
								<td><input type="text" class="accs-balance form-control"></td>
								<td><input type="checkbox" class="accs-delete" value="ID"></td>
								<input type="hidden" class="accs-id">
							</tr>
						</tbody>
					</table>
					<button type="submit" class="btn btn-outline-success float-right"><i class="fas fa-save"></i> Salvar alterações</button>
				</form>
			</div>
		</div>
	</main>
	
	<footer class="bg-dark py-2">
		<div class="container">
			<p class="text-center text-light mb-0">Todos direitos reservados 2019 &copy; LuanDEV.</p>
		</div>
	</footer>
	
	<!-- build:js -->
	<script src="./assets/scripts/bundle.min.js"></script>
	<!-- endbuild -->

	<?php
		var_dump($_POST);
	?>
</body>
</html>