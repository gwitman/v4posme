<style>
#app { visibility: hidden; }
.ls-stat-icon{
	width:42px;height:42px;border-radius:12px;
	display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;
}
.ls-bar-track{background:#eceef1;border-radius:8px;height:14px;overflow:hidden;}
.ls-bar-fill{height:14px;border-radius:8px;transition:width .6s ease;}
.ls-empty{padding:40px 15px;text-align:center;color:#8592a3;}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div id="app">

	<!-- Filtro -->
	<div class="card mb-4">
		<div class="card-body">
			<div class="row g-3 align-items-end">
				<div class="col-md-8">
					<label for="userID" class="form-label fw-semibold">Usuario</label>
					<select class="form-select" id="userID" v-model="userID">
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
				<div class="col-md-4">
					<button class="btn btn-primary w-100" @click="cargarListado" :disabled="loading">
						<span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
						<i v-else class="bx bx-search-alt me-1"></i>
						Consultar
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Alerta / mensaje -->
	<div v-if="mostrarAlerta" class="alert alert-info d-flex align-items-center" role="alert">
		<i class="bx bx-info-circle me-2"></i>
		<span>{{ mensaje }}</span>
	</div>

	<template v-if="!mostrarAlerta">

		<!-- Tarjetas resumen -->
		<div class="row">
			<div class="col-lg-4 col-md-6 col-6 mb-4">
				<div class="card h-100">
					<div class="card-body">
						<div class="ls-stat-icon mb-2" style="background:#696cff;"><i class="bx bx-transfer"></i></div>
						<span class="fw-semibold d-block mb-1">Total Transacciones</span>
						<h4 class="card-title mb-1">{{ counterTransaction }}</h4>
						<small class="text-muted">createdBy = userID</small>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 col-6 mb-4">
				<div class="card h-100">
					<div class="card-body">
						<div class="ls-stat-icon mb-2" style="background:#71dd37;"><i class="bx bx-category"></i></div>
						<span class="fw-semibold d-block mb-1">Tipos de Transacción</span>
						<h4 class="card-title mb-1">{{ statistic.length }}</h4>
						<small class="text-muted">categorías con registros</small>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-12 col-12 mb-4">
				<div class="card h-100">
					<div class="card-body">
						<div class="ls-stat-icon mb-2" style="background:#03c3ec;"><i class="bx bx-time-five"></i></div>
						<span class="fw-semibold d-block mb-1">Sesiones Mostradas</span>
						<h4 class="card-title mb-1">{{ objListData.length }}</h4>
						<small class="text-muted">últimas 5 sesiones</small>
					</div>
				</div>
			</div>
		</div>

		<!-- Estadistica visual por tipo de transaccion -->
		<div class="card mb-4">
			<h5 class="card-header"><i class="bx bx-bar-chart-alt-2 me-1"></i> Transacciones por Tipo</h5>
			<div class="card-body">
				<div v-if="statistic.length === 0" class="ls-empty">
					<i class="bx bx-bar-chart-alt-2 fs-1 d-block mb-2"></i>
					Este usuario no tiene transacciones registradas.
				</div>
				<div v-else>
					<div v-for="(item, idx) in statistic" :key="item.transactionID" class="mb-3">
						<div class="d-flex justify-content-between mb-1">
							<span class="fw-semibold">{{ item.transactionName }}</span>
							<span class="badge bg-label-primary">{{ item.counter }}</span>
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
		<div class="card">
			<h5 class="card-header"><i class="bx bx-list-ul me-1"></i> Últimas 5 Sesiones</h5>
			<div class="table-responsive text-nowrap">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>IP</th>
							<th>Plataforma</th>
							<th>Último Movimiento</th>
							<th>Detalle</th>
						</tr>
					</thead>
					<tbody class="table-border-bottom-0">
						<tr v-if="objListData.length === 0">
							<td colspan="4" class="ls-empty">Sin registros de sesión.</td>
						</tr>
						<tr v-for="session in objListData" :key="session.session_id">
							<td><span class="badge bg-label-info">{{ session.ip_address }}</span></td>
							<td><i class="bx bx-devices me-1"></i>{{ session.user_agent }}</td>
							<td>{{ formatFecha(session.last_activity) }}</td>
							<td><small class="text-muted">{{ session.user_data }}</small></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

	</template>

</div>
