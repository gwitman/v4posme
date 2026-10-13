<style>
#app { visibility: hidden; }

.ls-wrap{ font-family:'Open Sans', Arial, sans-serif; }

.ls-filter-card{
	background:#fff;border:1px solid #e8ebf0;border-radius:14px;
	padding:18px 20px;margin-bottom:22px;box-shadow:0 4px 14px rgba(37,45,67,.06);
}
.ls-label{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#8a93a6;margin-bottom:6px;display:block;}
.ls-select{
	width:100%;height:42px;border:1px solid #d9dee7;border-radius:10px;padding:0 12px;
	background:#fff;color:#384250;font-size:14px;outline:none;transition:border .2s,box-shadow .2s;
}
.ls-select:focus{border-color:#696cff;box-shadow:0 0 0 3px rgba(105,108,255,.15);}
.ls-btn{
	width:100%;height:42px;border:0;border-radius:10px;color:#fff;font-weight:700;font-size:14px;cursor:pointer;
	background:linear-gradient(135deg,#696cff,#8f74ff);box-shadow:0 6px 16px rgba(105,108,255,.35);
	transition:transform .15s,box-shadow .15s;
}
.ls-btn:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(105,108,255,.45);}
.ls-btn:disabled{opacity:.6;cursor:not-allowed;transform:none;}

.ls-alert{
	display:flex;align-items:center;gap:10px;background:#eef4ff;border:1px solid #d4e2ff;color:#3b5ba9;
	padding:14px 18px;border-radius:12px;margin-bottom:22px;font-size:14px;
}

.ls-stat{
	border-radius:16px;padding:18px 20px;color:#fff;position:relative;overflow:hidden;
	box-shadow:0 8px 22px rgba(37,45,67,.12);margin-bottom:22px;min-height:118px;
}
.ls-stat .ls-stat-ico{position:absolute;right:-6px;bottom:-10px;font-size:70px;opacity:.18;}
.ls-stat .ls-stat-label{font-size:13px;font-weight:600;opacity:.92;}
.ls-stat .ls-stat-value{font-size:34px;font-weight:800;line-height:1.1;margin:6px 0 2px;}
.ls-stat .ls-stat-sub{font-size:12px;opacity:.8;}
.ls-g1{background:linear-gradient(135deg,#696cff,#9a7bff);}
.ls-g2{background:linear-gradient(135deg,#1fc8a9,#45e3a0);}
.ls-g3{background:linear-gradient(135deg,#03b8e0,#5ad1ff);}

.ls-panel{background:#fff;border:1px solid #e8ebf0;border-radius:16px;box-shadow:0 4px 14px rgba(37,45,67,.06);margin-bottom:22px;overflow:hidden;}
.ls-panel-head{padding:16px 20px;border-bottom:1px solid #eef0f4;font-size:15px;font-weight:700;color:#384250;display:flex;align-items:center;gap:8px;}
.ls-panel-body{padding:20px;}

.ls-bar-row{margin-bottom:16px;}
.ls-bar-row:last-child{margin-bottom:0;}
.ls-bar-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;}
.ls-bar-name{font-weight:600;color:#495057;font-size:14px;}
.ls-bar-count{background:#f0f1ff;color:#696cff;font-weight:700;font-size:12px;padding:3px 10px;border-radius:20px;}
.ls-bar-track{background:#eef0f4;border-radius:10px;height:16px;overflow:hidden;}
.ls-bar-fill{height:16px;border-radius:10px;transition:width .7s cubic-bezier(.25,.8,.25,1);}

.ls-table{width:100%;border-collapse:separate;border-spacing:0;}
.ls-table thead th{
	text-align:left;font-size:12px;text-transform:uppercase;letter-spacing:.4px;color:#8a93a6;
	padding:12px 16px;background:#f8f9fc;border-bottom:1px solid #eef0f4;
}
.ls-table tbody td{padding:14px 16px;border-bottom:1px solid #f1f3f7;color:#495057;font-size:14px;}
.ls-table tbody tr:hover{background:#f8f9fc;}
.ls-chip{display:inline-block;background:#e7f6ff;color:#0b84c4;font-weight:600;font-size:12px;padding:4px 10px;border-radius:8px;}
.ls-muted{color:#9aa2b1;font-size:12px;}
.ls-empty{padding:44px 16px;text-align:center;color:#9aa2b1;}
.ls-empty .ls-empty-ico{font-size:46px;display:block;margin-bottom:10px;opacity:.6;}

.ls-spin{display:inline-block;width:16px;height:16px;border:2px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:ls-rot .7s linear infinite;vertical-align:middle;margin-right:6px;}
@keyframes ls-rot{to{transform:rotate(360deg);}}
</style>

<div id="app" class="ls-wrap">

	<!-- Filtro -->
	<div class="ls-filter-card">
		<div class="row">
			<div class="col-lg-9 col-md-8">
				<label class="ls-label" for="userID">Usuario</label>
				<select class="ls-select" id="userID" v-model="userID">
					<option value="0" disabled>Seleccione un usuario...</option>
					<?php
					if($objListUser){
						foreach($objListUser as $user){
							echo '<option value="'.$user->userID.'">'.$user->nickname.' ('.$user->email.')</option>';
						}
					}
					?>
				</select>
			</div>
			<div class="col-lg-3 col-md-4">
				<label class="ls-label">&nbsp;</label>
				<button class="ls-btn" @click="cargarListado" :disabled="loading">
					<span v-if="loading" class="ls-spin"></span>
					<span v-else><i class="icon16 i-search-3"></i></span>
					Consultar
				</button>
			</div>
		</div>
	</div>

	<!-- Mensaje -->
	<div v-if="mostrarAlerta" class="ls-alert">
		<i class="icon16 i-info"></i>
		<span>{{ mensaje }}</span>
	</div>

	<template v-if="!mostrarAlerta">

		<!-- Tarjetas resumen -->
		<div class="row">
			<div class="col-lg-4 col-sm-6">
				<div class="ls-stat ls-g1">
					<div class="ls-stat-label">Total Transacciones</div>
					<div class="ls-stat-value">{{ counterTransaction }}</div>
					<div class="ls-stat-sub">createdBy = userID</div>
					<i class="icon20 i-cycle ls-stat-ico"></i>
				</div>
			</div>
			<div class="col-lg-4 col-sm-6">
				<div class="ls-stat ls-g2">
					<div class="ls-stat-label">Tipos de Transacción</div>
					<div class="ls-stat-value">{{ statistic.length }}</div>
					<div class="ls-stat-sub">categorías con registros</div>
					<i class="icon20 i-stack ls-stat-ico"></i>
				</div>
			</div>
			<div class="col-lg-4 col-sm-12">
				<div class="ls-stat ls-g3">
					<div class="ls-stat-label">Sesiones Mostradas</div>
					<div class="ls-stat-value">{{ objListData.length }}</div>
					<div class="ls-stat-sub">últimas 5 sesiones</div>
					<i class="icon20 i-clock ls-stat-ico"></i>
				</div>
			</div>
		</div>

		<!-- Estadistica visual por tipo de transaccion -->
		<div class="ls-panel">
			<div class="ls-panel-head"><i class="icon16 i-bars"></i> Transacciones por Tipo</div>
			<div class="ls-panel-body">
				<div v-if="statistic.length === 0" class="ls-empty">
					<i class="icon20 i-bars ls-empty-ico"></i>
					Este usuario no tiene transacciones registradas.
				</div>
				<div v-else>
					<div v-for="(item, idx) in statistic" :key="item.transactionID" class="ls-bar-row">
						<div class="ls-bar-top">
							<span class="ls-bar-name">{{ item.transactionName }}</span>
							<span class="ls-bar-count">{{ item.counter }}</span>
						</div>
						<div class="ls-bar-track">
							<div class="ls-bar-fill"
								 :style="{ width: porcentaje(item.counter) + '%', background: colores[idx % colores.length] }">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Ultimas 5 sesiones -->
		<div class="ls-panel">
			<div class="ls-panel-head"><i class="icon16 i-list"></i> Últimas 5 Sesiones</div>
			<div class="table-responsive">
				<table class="ls-table">
					<thead>
						<tr>
							<th>IP</th>
							<th>Plataforma</th>
							<th>Último Movimiento</th>
							<th>Detalle</th>
						</tr>
					</thead>
					<tbody>
						<tr v-if="objListData.length === 0">
							<td colspan="4" class="ls-empty">Sin registros de sesión.</td>
						</tr>
						<tr v-for="session in objListData" :key="session.session_id">
							<td><span class="ls-chip">{{ session.ip_address }}</span></td>
							<td><i class="icon16 i-screen"></i> {{ session.user_agent }}</td>
							<td>{{ formatFecha(session.last_activity) }}</td>
							<td><span class="ls-muted">{{ session.user_data }}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

	</template>

</div>
