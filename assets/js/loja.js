tipo_cliente = null;

function add_carrinho(id_produto){//Adiciona os itens na sessao 
	event.preventDefault();
	$.ajax({
		url:'../admin/controller/loja/carrinho.php',
		method:"POST",
		data:{id:id_produto},
		success:function(data){
			atualiza_carrinho_html(data);
		}
	})
}

function atualiza_carrinho_html(itens){//Atualiza os itens no html
	carrinho = JSON.parse(itens);

	$("#itens_carrinho").html('');
	total_compra_socios = 0;
	total_compra_nao_socios = 0;

	$.each(carrinho, function(chave,valor){
		valor_unitario 	= (tipo_cliente == 1)?valor.valor_socios:valor.valor;
		valor_unitario 	= valor_unitario * valor.qntd;

		total_compra_socios += parseInt((valor.valor_socios*valor.qntd));
		total_compra_nao_socios += parseInt((valor.valor*valor.qntd));
		
		$("#itens_carrinho").append('\
			<div class="card container col-md-12">\
			<a href="#" class="color-bordo ">'+valor.nome+'</a>\
			<!-- <div class="col-md-5">\
			<img src="../admin/dist/img/loja/produtos/'+valor.foto+'" width="100%" alt="">\
			</div> -->\
			<div class="" style="margin-top: 0px!important">\
			<label>Quantidade:</label>\
			<input type="number" min="0" max="10" style="margin-top: 0px!important" class=" form-control-number pull-right" onchange="atualiza_quantidade_produto('+(valor.id)+',this.value)" pattern="[0-9]*"  value="'+valor.qntd+'">\
			</div>\
			<p class="color-bordo text-right">Valor: R$ '+valor_unitario+',00</p>\
			</div>\
			');
	});

	valor_total 	= (tipo_cliente == 1)?total_compra_socios:total_compra_nao_socios;

	$(".total_compra").html('\
		<h4 class="d-inline">Total da compra: R$</h4> <h3 class="d-inline">'+valor_total+',00</h3><br>\
		');	
}

function setTipoCliente(value){//Atualiza o carrinho de acordo com o tipo de cliente
	this.tipo_cliente = value;
	$.get('../admin/controller/loja/carrinho.php',function(data){
		atualiza_carrinho_html(data);
	});
}

function atualiza_quantidade_produto(id_produto,qntd){

	$.ajax({
		url:'../admin/controller/loja/carrinho.php',
		method:"POST",
		data:{qntd_produto:qntd,id:id_produto},
		success:function(data){
			console.log(data);
			atualiza_carrinho_html(data);
		}
	})
}