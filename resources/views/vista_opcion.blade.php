
<div class="container">

	<div class="row">

		@if(count($dataColumns) > 0 && count($dataTabla) > 0)

			<div class="w-100 mb-2">
				@if($tabla == 'tbl_comando')
					<div id="contcomanderror"></div>
					<div class="d-flex w-100">
						<input type="text" id="comandoArtisan" placeholder="Ej: integracion:enviar-planos" class="form-control mr-2">
		      			<button class="btn btn-warning mr-2" onclick="ejecutarComando('#comandoArtisan','#contcomanderror')"><i class="text-white fa fa-play-circle mr-1"></i>Ejecutar comando</button>
					</div>
					<div>
						<p>Default : ['key:generate','optimize:clear','cache:clear','route:clear','config:cache','view:clear','view:cache','schedule:run']</p>
					</div>
		      	@endif
			</div>

			<table class="table">
			  <thead>
			    <tr id="normal">
			      	@foreach($dataColumns as $valueColumn)
						<th scope="col">{{$valueColumn}}</th>
					@endforeach
				    <th scope="col"></th>
				    <th scope="col"></th>
			    </tr>
			  </thead>
			  <tbody>

			  		<?php $sum=0; $total = count($dataColumns); ?>
					@foreach($dataTabla as $val)
						<tr>
						  @foreach($dataColumns as $nomCol)
						   	@if($nomCol == 'codigo')
						   		<th scope="row">#{{$val[$nomCol]}}</th>
						   	@elseif($nomCol == 'estado' || $nomCol == 'truncate' || $nomCol == 'drop_table')
						   		<td>
						   			<select class="form-control camposTabla_{{$val['codigo']}}" >
							   			@if($val[$nomCol] == 0)
							   				<option selected value="0">Inactivo</option>
							   				<option value="1">Activo</option>
							   			@else
							   				<option selected value="1">Activo</option>
							   				<option value="0">Inactivo</option>
							   			@endif
							   		</select>
						   		</td>
						   	@else
						  		<td><input type="text" value="{{$val[$nomCol]}}" title="{{$val[$nomCol]}}" id="{{$nomCol}}_{{$val['codigo']}}" class="form-control camposTabla_{{$val['codigo']}}"></td>
						  	@endif
						  @endforeach
					      <td>
					      	<button class="btn btn-info act-camptable" onclick="actualizarReg('{{$tabla}}','{{$val['codigo']}}','.camposTabla_','#contErrorTbl')"><i class="fa fa-sync mr-1"></i></button>
					      </td>
					      <td>
					      	<button class="btn btn-danger act-camptable" onclick="eliminarReg('{{$tabla}}','{{$val['codigo']}}','#contErrorTbl')"><i class="fa fa-trash mr-1"></i></button>
					      </td>
					    </tr>				
					@endforeach

				<tr>

				  @foreach($dataColumns as $nomCol)
				  		@if($nomCol == 'codigo')
					   		<th scope="row">#{{count($dataTabla)+1}}</th>
					   	@elseif($nomCol == 'estado')
					   		<td>
					   			<select class="form-control newCamposTabla" >
						   			<option value="0" selected>Inactivo</option>
			      					<option value="1">Activo</option>
						   		</select>
					   		</td>
					   	@else
					  		<td><input type="text" placeholder="{{$nomCol}}" title="{{$nomCol}}" id="insert_{{$nomCol}}_{{$val['codigo']}}" class="form-control newCamposTabla"></td>
					  	@endif
				  @endforeach

				  <td>
			      	<button class="btn btn-success act-camptable" onclick="registrarNuevo('{{$tabla}}','.newCamposTabla','#contErrorTbl')"><i class="fa fa-plus mr-1"></i></button>
			      </td>

			      <td></td>

			    </tr>

			  </tbody>
			</table>

			<div id="contErrorTbl" class="w-100 mt-2"></div>
		@else
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<p class="text-center w-100">No se encontrarón resultados en la tabla</p>
			</div>
		@endif

		@if($tabla == 'tbl_log')
			<div class="w-100 text-center p-2" >
                <h4>Log de errores</h4>
                <div class="contLogError"></div>
                <div id="spinnerLoaderLog" class="w-100 text-center mt-3" style="display: none;">
                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                    <p class="text-dark">Por favor espere un momento...</p>
                </div>
                <div class="w-100 mt-2">
                    <button class="btn btn-secondary" onclick="desplegarLog('{{url('/')}}/storage/logs/laravel.log')"><i class="fa fa-data"></i> Laravel Storage log</button>
                    <button class="btn btn-success" onclick="limpiarLog('.contLogError')"><i class="fa fa-trash mr-1"></i>Limpiar</button>
                </div>
            </div>
        @endif

        @if($tabla == 'tbl_plano')
			<div class="w-100 text-center p-2" >
                <h4>Lista de planos generados</h4>
                <div class="contplanos text-center" style="max-height: 200px;overflow-y: scroll;">
                	<?php $directorio = 'public/plano/'; $ficheros  = scandir($directorio); $sum = 1; ?>
                	@foreach($ficheros as $key => $valFichero)
                		@if($sum > 2)
                			<a href="{{url("/")}}/public/plano/{{$valFichero}}" style="text-decoration: underline;" target="_blank" >Archivo plano ´{{ $valFichero }}´</a><br>
                		@endif
                		<?php $sum++; ?>
                	@endforeach
                </div>
            </div>
        @endif

		<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"></div>

	</div>

</div>